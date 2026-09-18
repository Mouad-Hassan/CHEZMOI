<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Annonce;
use App\Models\Message;
use App\Models\TypeBien;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Statistiques générales (cahier des charges 10).
     */
    public function dashboard(): View
    {
        $stats = [
            'utilisateurs' => User::count(),
            'proprietaires' => User::where('role', User::ROLE_PROPRIETAIRE)->count(),
            'acheteurs' => User::where('role', User::ROLE_ACHETEUR)->count(),
            'annonces' => Annonce::count(),
            'validees' => Annonce::where('statut_validation', Annonce::STATUT_VALIDE)->count(),
            'en_attente' => Annonce::where('statut_validation', Annonce::STATUT_EN_ATTENTE)->count(),
            'refusees' => Annonce::where('statut_validation', Annonce::STATUT_REFUSE)->count(),
            'vues' => (int) Annonce::sum('vues'),
            'messages' => Message::count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    /**
     * Gestion des utilisateurs (cahier des charges 4).
     */
    public function users(Request $request): View
    {
        $users = User::query()
            ->withCount('annonces')
            ->when($request->filled('role'), fn ($q) => $q->where('role', $request->input('role')))
            ->when($request->filled('q'), function ($q) use ($request) {
                $mot = $request->input('q');
                $q->where(fn ($w) => $w->where('nom', 'like', "%{$mot}%")->orWhere('email', 'like', "%{$mot}%"));
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.users', compact('users'));
    }

    /**
     * Modification du rôle d'un utilisateur.
     */
    public function updateUserRole(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'role' => ['required', 'in:admin,proprietaire,acheteur'],
        ]);

        abort_if($user->id === $request->user()->id, 403, 'Vous ne pouvez pas modifier votre propre rôle.');

        $user->update($data);

        return back()->with('status', "Rôle de {$user->nom} mis à jour.");
    }

    /**
     * Suppression d'un utilisateur.
     */
    public function destroyUser(Request $request, User $user): RedirectResponse
    {
        abort_if($user->id === $request->user()->id, 403, 'Vous ne pouvez pas supprimer votre propre compte.');

        // Les suppressions en cascade de la base ne déclenchent pas les
        // événements Eloquent : supprimer explicitement les fichiers évite
        // de laisser des photos orphelines dans le stockage public.
        $photos = $user->annonces()->with('photos')->get()->flatMap->photos;
        foreach ($photos as $photo) {
            Storage::disk('public')->delete($photo->chemin);
        }

        $user->delete();

        return back()->with('status', "Utilisateur {$user->nom} supprimé.");
    }

    /**
     * Annonces à valider (cahier des charges 4).
     */
    public function annonces(Request $request): View
    {
        $annonces = Annonce::query()
            ->with(['typeBien', 'user'])
            ->when($request->filled('statut'), fn ($q) => $q->where('statut_validation', $request->input('statut')))
            ->when($request->filled('ville'), fn ($q) => $q->where('ville', $request->input('ville')))
            ->latest('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.annonces', compact('annonces'));
    }

    /**
     * Validation d'une annonce + notifications (cahier des charges 9).
     */
    public function valider(Annonce $annonce): RedirectResponse
    {
        $annonce->update([
            'statut_validation' => Annonce::STATUT_VALIDE,
            'date_publication' => now(),
        ]);

        $this->notifier($annonce, 'annonce_validee', "Votre annonce « {$annonce->titre} » a été validée.");
        $this->notifierNouvelleAnnoncePubliee($annonce);

        return back()->with('status', 'Annonce validée.');
    }

    /**
     * Refus d'une annonce + notification au propriétaire.
     */
    public function refuser(Annonce $annonce): RedirectResponse
    {
        $annonce->update(['statut_validation' => Annonce::STATUT_REFUSE]);

        $this->notifier($annonce, 'annonce_refusee', "Votre annonce « {$annonce->titre} » a été refusée.");

        return back()->with('status', 'Annonce refusée.');
    }

    /**
     * Suppression d'une annonce frauduleuse.
     */
    public function destroyAnnonce(Annonce $annonce): RedirectResponse
    {
        $annonce->loadMissing('photos');
        foreach ($annonce->photos as $photo) {
            Storage::disk('public')->delete($photo->chemin);
        }

        $annonce->delete();

        return back()->with('status', 'Annonce supprimée.');
    }

    /**
     * Gestion des catégories / types de biens.
     */
    public function typeBiens(): View
    {
        $types = TypeBien::withCount('annonces')->orderBy('type')->get();

        return view('admin.type-biens', compact('types'));
    }

    public function storeTypeBien(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'type' => ['required', 'string', 'max:255', 'unique:type_biens,type'],
        ]);

        TypeBien::create($data);

        return back()->with('status', 'Catégorie ajoutée.');
    }

    public function destroyTypeBien(TypeBien $typeBien): RedirectResponse
    {
        if ($typeBien->annonces()->exists()) {
            return back()->with('error', 'Impossible de supprimer une catégorie utilisée par des annonces.');
        }

        $typeBien->delete();

        return back()->with('status', 'Catégorie supprimée.');
    }

    private function notifier(Annonce $annonce, string $type, string $contenu): void
    {
        $annonce->user->notifications()->create([
            'type' => $type,
            'contenu' => $contenu,
            'date_creation' => now(),
        ]);
    }

    /**
     * Notifie tous les acheteurs de la publication d'une nouvelle annonce.
     */
    private function notifierNouvelleAnnoncePubliee(Annonce $annonce): void
    {
        User::where('role', User::ROLE_ACHETEUR)->get()->each(function (User $acheteur) use ($annonce) {
            $acheteur->notifications()->create([
                'type' => 'nouvelle_publication',
                'contenu' => "Nouvelle annonce publiée : « {$annonce->titre} » à {$annonce->ville}.",
                'date_creation' => now(),
            ]);
        });
    }
}
