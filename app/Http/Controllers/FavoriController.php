<?php

namespace App\Http\Controllers;

use App\Models\Annonce;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FavoriController extends Controller
{
    /**
     * Liste des favoris de l'utilisateur (cahier des charges 7).
     */
    public function index(Request $request): View
    {
        $favoris = $request->user()->favoris()
            ->with('annonce.typeBien')
            ->latest('date_ajout')
            ->paginate(9);

        return view('favoris.index', compact('favoris'));
    }

    /**
     * Ajoute (ou retire) un bien des favoris.
     */
    public function toggle(Request $request, Annonce $annonce): RedirectResponse
    {
        $user = $request->user();

        // Une annonce qui n'est pas publiée ne doit jamais pouvoir être
        // enregistrée, même si son URL est connue.
        abort_unless($annonce->statut_validation === Annonce::STATUT_VALIDE, 404);
        abort_if($annonce->user_id === $user->id, 403, 'Vous ne pouvez pas ajouter votre propre annonce aux favoris.');

        $favori = $user->favoris()->where('annonce_id', $annonce->id)->first();

        if ($favori) {
            $favori->delete();

            return back()->with('status', 'Annonce retirée de vos favoris.');
        }

        $user->favoris()->create([
            'annonce_id' => $annonce->id,
            'date_ajout' => now()->toDateString(),
        ]);

        return back()->with('status', 'Annonce ajoutée à vos favoris.');
    }

    /**
     * Suppression d'un favori.
     */
    public function destroy(Request $request, Annonce $annonce): RedirectResponse
    {
        $request->user()->favoris()->where('annonce_id', $annonce->id)->delete();

        return back()->with('status', 'Favori supprimé.');
    }
}
