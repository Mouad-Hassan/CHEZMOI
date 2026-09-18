<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AnnonceRequest;
use App\Models\Annonce;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AnnonceController extends Controller
{
    /**
     * Recherche avancée paginée des annonces validées (cahier des charges 6 et 11).
     */
    public function index(Request $request): JsonResponse
    {
        $annonces = Annonce::query()
            ->valide()
            ->with(['typeBien', 'photos'])
            ->when($request->filled('ville'), fn ($q) => $q->where('ville', $request->input('ville')))
            ->when($request->filled('prix_min'), fn ($q) => $q->where('prix', '>=', $request->input('prix_min')))
            ->when($request->filled('prix_max'), fn ($q) => $q->where('prix', '<=', $request->input('prix_max')))
            ->when($request->filled('type_bien_id'), fn ($q) => $q->where('type_bien_id', $request->input('type_bien_id')))
            ->when($request->filled('surface'), fn ($q) => $q->where('surface', '>=', $request->input('surface')))
            ->when($request->filled('chambres'), fn ($q) => $q->where('nombre_chambres', '>=', $request->input('chambres')))
            ->latest('date_publication')
            ->paginate((int) $request->input('par_page', 9));

        return response()->json($annonces);
    }

    public function show(Annonce $annonce): JsonResponse
    {
        abort_if($annonce->statut_validation !== Annonce::STATUT_VALIDE, 404);

        $annonce->increment('vues');
        $annonce->load(['typeBien', 'photos', 'user:id,nom,email,role']);

        return response()->json($annonce);
    }

    /**
     * Création d'une annonce (propriétaire authentifié via Sanctum).
     */
    public function store(AnnonceRequest $request): JsonResponse
    {
        $data = $request->validated();
        unset($data['photos']);

        $annonce = $request->user()->annonces()->create($data + [
            'statut_validation' => Annonce::STATUT_EN_ATTENTE,
            'date_publication' => now(),
        ]);

        return response()->json($annonce, 201);
    }
}
