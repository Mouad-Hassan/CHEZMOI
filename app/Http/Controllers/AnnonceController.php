<?php

namespace App\Http\Controllers;

use App\Http\Requests\AnnonceRequest;
use App\Models\Annonce;
use App\Models\TypeBien;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AnnonceController extends Controller
{
    /**
     * Liste de toutes les annonces validées (pour les acheteurs et visiteurs).
     */
    public function indexAll(Request $request): View
    {
        $annonces = Annonce::query()
            ->valide()
            ->with('typeBien', 'photos')
            ->withCount('favoris')
            ->latest('date_publication')
            ->paginate(12);

        return view('annonces.index-all', compact('annonces'));
    }

    /**
     * Liste des annonces du propriétaire connecté.
     */
    public function index(Request $request): View
    {
        $annonces = $request->user()->annonces()
            ->with('typeBien')
            ->withCount('favoris')
            ->latest('date_publication')
            ->paginate(10);

        return view('annonces.index', compact('annonces'));
    }

    public function create(): View
    {
        $types = TypeBien::orderBy('type')->get();

        return view('annonces.create', compact('types'));
    }

    /**
     * Création d'une annonce (statut « en attente » jusqu'à validation admin).
     */
    public function store(AnnonceRequest $request): RedirectResponse
    {
        $data = $request->validated();
        unset($data['photos']);

        $annonce = $request->user()->annonces()->create($data + [
            'statut_validation' => Annonce::STATUT_EN_ATTENTE,
            'date_publication' => now(),
        ]);

        $this->enregistrerPhotos($annonce, $request);
        $this->notifierAdmins($annonce);

        return redirect()->route('annonces.index')
            ->with('status', 'Annonce créée. Elle sera visible après validation par un administrateur.');
    }

    /**
     * Consultation d'une annonce (publique si validée, sinon propriétaire/admin).
     */
    public function show(Request $request, Annonce $annonce): View
    {
        $user = $request->user();
        $estProprietaire = $user && $annonce->user_id === $user->id;
        $estAdmin = $user && $user->isAdmin();

        if ($annonce->statut_validation !== Annonce::STATUT_VALIDE && ! $estProprietaire && ! $estAdmin) {
            abort(404);
        }

        // Compteur de vues (on ne compte pas les vues du propriétaire)
        if (! $estProprietaire) {
            $annonce->increment('vues');
        }

        $annonce->load(['typeBien', 'photos', 'user']);

        $estFavori = $user
            ? $user->favoris()->where('annonce_id', $annonce->id)->exists()
            : false;

        return view('annonces.show', compact('annonce', 'estFavori'));
    }

    public function edit(Annonce $annonce): View
    {
        $this->authorize('update', $annonce);
        $types = TypeBien::orderBy('type')->get();

        return view('annonces.edit', compact('annonce', 'types'));
    }

    public function update(AnnonceRequest $request, Annonce $annonce): RedirectResponse
    {
        $this->authorize('update', $annonce);

        $data = $request->validated();
        unset($data['photos']);

        // Toute modification repasse l'annonce en attente de validation
        $data['statut_validation'] = Annonce::STATUT_EN_ATTENTE;

        $annonce->update($data);

        $this->enregistrerPhotos($annonce, $request);

        return redirect()->route('annonces.index')
            ->with('status', 'Annonce mise à jour. Elle repasse en attente de validation.');
    }

    public function destroy(Annonce $annonce): RedirectResponse
    {
        $this->authorize('delete', $annonce);

        foreach ($annonce->photos as $photo) {
            Storage::disk('public')->delete($photo->chemin);
        }

        $annonce->delete();

        return redirect()->route('annonces.index')->with('status', 'Annonce supprimée.');
    }

    /**
     * Enregistre les photos envoyées sur le disque public.
     */
    private function enregistrerPhotos(Annonce $annonce, Request $request): void
    {
        if (! $request->hasFile('photos')) {
            return;
        }

        foreach ($request->file('photos') as $file) {
            $chemin = $file->store('annonces', 'public');
            $annonce->photos()->create(['chemin' => $chemin]);
        }
    }

    /**
     * Notifie les administrateurs qu'une annonce doit être validée (cahier des charges 9).
     */
    private function notifierAdmins(Annonce $annonce): void
    {
        User::where('role', User::ROLE_ADMIN)->get()->each(function (User $admin) use ($annonce) {
            $admin->notifications()->create([
                'type' => 'nouvelle_annonce',
                'contenu' => "Nouvelle annonce à valider : « {$annonce->titre} ».",
                'date_creation' => now(),
            ]);
        });
    }
}
