@extends('layouts.app')

@section('title', 'Tableau de bord administrateur')

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-8 flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="section-label">Espace administration</p>
                <span class="gold-line"></span>
                <h1 class="mt-3 text-3xl font-extrabold tracking-tight text-charcoal">
                    Tableau de bord — Administrateur
                </h1>
            </div>
            <div class="flex gap-2 text-sm flex-wrap">
                <a href="{{ route('admin.annonces') }}" class="btn-primary px-5 py-2.5">Gérer les annonces</a>
                <a href="{{ route('admin.users') }}" class="btn-outline px-5 py-2.5">Utilisateurs</a>
                <a href="{{ route('admin.type-biens') }}" class="btn-outline px-5 py-2.5">Catégories</a>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @php
                $cartes = [
                    ['label' => 'Utilisateurs', 'valeur' => $stats['utilisateurs'], 'couleur' => 'text-charcoal'],
                    ['label' => 'Annonces (total)', 'valeur' => $stats['annonces'], 'couleur' => 'text-charcoal'],
                    ['label' => 'Annonces validées', 'valeur' => $stats['validees'], 'couleur' => 'text-gold'],
                    ['label' => 'Annonces en attente', 'valeur' => $stats['en_attente'], 'couleur' => 'text-charcoal'],
                    ['label' => 'Annonces refusées', 'valeur' => $stats['refusees'], 'couleur' => 'text-charcoal'],
                    ['label' => 'Vues cumulées', 'valeur' => $stats['vues'], 'couleur' => 'text-charcoal'],
                ];
            @endphp

            @foreach ($cartes as $carte)
                <div class="brand-card p-5">
                    <p class="text-sm text-charcoal/60">{{ $carte['label'] }}</p>
                    <p class="text-3xl font-bold {{ $carte['couleur'] }}">{{ $carte['valeur'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
@endsection
