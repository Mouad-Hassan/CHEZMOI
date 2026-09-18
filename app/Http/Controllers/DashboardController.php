<?php

namespace App\Http\Controllers;

use App\Models\Annonce;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Tableau de bord selon le rôle (cahier des charges 10).
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        // Tableau de bord administrateur
        if ($user->isAdmin()) {
            $stats = [
                'utilisateurs' => User::count(),
                'annonces' => Annonce::count(),
                'validees' => Annonce::where('statut_validation', Annonce::STATUT_VALIDE)->count(),
                'en_attente' => Annonce::where('statut_validation', Annonce::STATUT_EN_ATTENTE)->count(),
                'refusees' => Annonce::where('statut_validation', Annonce::STATUT_REFUSE)->count(),
                'vues' => (int) Annonce::sum('vues'),
            ];

            return view('dashboard.admin', compact('stats'));
        }

        // Tableau de bord vendeur / propriétaire
        if ($user->isProprietaire()) {
            $stats = [
                'annonces' => $user->annonces()->count(),
                'validees' => $user->annonces()->valide()->count(),
                'en_attente' => $user->annonces()->enAttente()->count(),
                'vues' => (int) $user->annonces()->sum('vues'),
                'messages' => Message::where('expediteur_id', $user->id)
                    ->orWhere('destinataire_id', $user->id)
                    ->count(),
                'non_lus' => Message::where('destinataire_id', $user->id)->where('statut_lecture', false)->count(),
            ];

            $annonces = $user->annonces()
                ->withCount('favoris')
                ->latest('date_publication')
                ->take(5)
                ->get();

            return view('dashboard.proprietaire', compact('stats', 'annonces'));
        }

        // Tableau de bord acheteur / locataire
        $stats = [
            'favoris' => $user->favoris()->count(),
            'messages' => Message::where('expediteur_id', $user->id)
                ->orWhere('destinataire_id', $user->id)
                ->count(),
            'non_lus' => Message::where('destinataire_id', $user->id)->where('statut_lecture', false)->count(),
        ];

        $favoris = $user->favoris()
            ->with('annonce.typeBien')
            ->latest('date_ajout')
            ->take(5)
            ->get();

        return view('dashboard.acheteur', compact('stats', 'favoris'));
    }
}
