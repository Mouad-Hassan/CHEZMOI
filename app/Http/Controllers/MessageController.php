<?php

namespace App\Http\Controllers;

use App\Models\Annonce;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MessageController extends Controller
{
    /**
     * Liste des conversations organisées par annonce.
     */
    public function index(Request $request): View
    {
        $userId = $request->user()->id;

        // Récupérer tous les messages de l'utilisateur
        $messages = Message::where('expediteur_id', $userId)
            ->orWhere('destinataire_id', $userId)
            ->with(['expediteur', 'destinataire', 'annonce'])
            ->orderByDesc('date_envoi')
            ->get();

        // Grouper par annonce
        $conversationsParAnnonce = $messages
            ->groupBy('annonce_id')
            ->map(function ($groupe, $annonceId) use ($userId) {
                $annonce = $groupe->first()->annonce;
                $dernier = $groupe->first();

                // Trouver l'interlocuteur principal (celui qui a envoyé le dernier message ou le destinataire)
                $interlocuteur = $dernier->expediteur_id === $userId ? $dernier->destinataire : $dernier->expediteur;

                return [
                    'annonce' => $annonce,
                    'interlocuteur' => $interlocuteur,
                    'dernier' => $dernier,
                    'non_lus' => $groupe->where('destinataire_id', $userId)->where('statut_lecture', false)->count(),
                    'total_messages' => $groupe->count(),
                ];
            })
            ->sortByDesc(fn ($conv) => $conv['dernier']->date_envoi)
            ->values();

        return view('messages.index', compact('conversationsParAnnonce'));
    }

    /**
     * Conversation avec un utilisateur (historique complet).
     */
    public function conversation(Request $request, User $user): View
    {
        $userId = $request->user()->id;
        abort_if($user->id === $userId, 403, 'Vous ne pouvez pas vous envoyer un message.');

        // Filtrer par annonce si spécifié
        $annonceId = $request->query('annonce_id');

        // Construire la base de la requête pour les messages entre les deux utilisateurs
        $query = Message::where(function ($q) use ($userId, $user) {
            $q->where('expediteur_id', $userId)->where('destinataire_id', $user->id)
              ->orWhere('expediteur_id', $user->id)->where('destinataire_id', $userId);
        });

        // Filtrer par annonce si spécifié
        if ($annonceId) {
            $query->where('annonce_id', $annonceId);
        }

        $messages = $query->with('annonce')->orderBy('date_envoi')->get();

        // Marque les messages reçus comme lus
        $markAsReadQuery = Message::where('expediteur_id', $user->id)
            ->where('destinataire_id', $userId)
            ->where('statut_lecture', false);

        if ($annonceId) {
            $markAsReadQuery->where('annonce_id', $annonceId);
        }

        $markAsReadQuery->update(['statut_lecture' => true]);

        $annonce = $annonceId ? \App\Models\Annonce::find($annonceId) : null;

        return view('messages.show', compact('user', 'messages', 'annonce'));
    }

    /**
     * Envoi d'un message + notification au destinataire (cahier des charges 8 et 9).
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'destinataire_id' => ['required', 'integer', 'exists:users,id'],
            'annonce_id' => ['nullable', 'integer', 'exists:annonces,id'],
            'contenu' => ['required', 'string', 'max:2000'],
        ]);

        abort_if((int) $data['destinataire_id'] === $request->user()->id, 403, 'Destinataire invalide.');

        if (! empty($data['annonce_id'])) {
            $annonce = Annonce::findOrFail($data['annonce_id']);

            // L'annonce doit être validée
            abort_unless($annonce->statut_validation === Annonce::STATUT_VALIDE, 404);

            // Si l'expéditeur est l'acheteur, le destinataire doit être le propriétaire de l'annonce
            if ($request->user()->isAcheteur()) {
                abort_unless((int) $data['destinataire_id'] === $annonce->user_id, 422,
                    'Le destinataire doit être le propriétaire de l’annonce.');
            }
            // Si l'expéditeur est le vendeur, il peut répondre à n'importe qui à propos de son annonce
            elseif ($request->user()->isProprietaire()) {
                abort_unless($annonce->user_id === $request->user()->id, 422,
                    'Vous ne pouvez envoyer un message que concernant vos propres annonces.');
            }
        }

        Message::create($data + [
            'expediteur_id' => $request->user()->id,
            'statut_lecture' => false,
            'date_envoi' => now(),
        ]);

        User::find($data['destinataire_id'])->notifications()->create([
            'type' => 'nouveau_message',
            'contenu' => 'Vous avez reçu un nouveau message de '.$request->user()->nom.'.',
            'date_creation' => now(),
        ]);

        return back()->with('status', 'Message envoyé.');
    }

    /**
     * Formulaire de contact du propriétaire d'une annonce.
     */
    public function contacter(Request $request, Annonce $annonce): View
    {
        abort_if($annonce->statut_validation !== Annonce::STATUT_VALIDE, 404);
        abort_unless($request->user()->isAcheteur(), 403);
        abort_if($annonce->user_id === $request->user()->id, 403, 'Vous ne pouvez pas contacter votre propre annonce.');

        return view('messages.create', compact('annonce'));
    }
}
