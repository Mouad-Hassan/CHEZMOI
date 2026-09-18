<?php

namespace App\Http\Controllers;

use App\Models\Annonce;
use App\Models\TypeBien;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Page d'accueil + recherche avancée (cahier des charges 6) avec pagination (11).
     */
    public function index(Request $request): View
    {
        $types = TypeBien::orderBy('type')->get();
        $villes = Annonce::query()->valide()->distinct()->orderBy('ville')->pluck('ville');

        $annonces = Annonce::query()
            ->valide()
            ->with(['typeBien', 'photos'])
            ->when($request->filled('ville'), fn ($q) => $q->where('ville', $request->input('ville')))
            ->when($request->filled('prix_min'), fn ($q) => $q->where('prix', '>=', $request->input('prix_min')))
            ->when($request->filled('prix_max'), fn ($q) => $q->where('prix', '<=', $request->input('prix_max')))
            ->when($request->filled('type_bien_id'), fn ($q) => $q->where('type_bien_id', $request->input('type_bien_id')))
            ->when($request->filled('surface'), fn ($q) => $q->where('surface', '>=', $request->input('surface')))
            ->when($request->filled('chambres'), fn ($q) => $q->where('nombre_chambres', '>=', $request->input('chambres')))
            ->when($request->filled('q'), function ($q) use ($request) {
                $mot = $request->input('q');
                $q->where(fn ($w) => $w->where('titre', 'like', "%{$mot}%")
                    ->orWhere('description', 'like', "%{$mot}%")
                    ->orWhere('adresse', 'like', "%{$mot}%"));
            })
            ->latest('date_publication')
            ->paginate(9)
            ->withQueryString();

        return view('home', compact('annonces', 'types', 'villes'));
    }
}
