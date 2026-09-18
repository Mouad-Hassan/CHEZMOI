@extends('layouts.app')

@section('title', $annonce->titre)
@section('meta_description', Str::limit($annonce->description, 155))

@section('content')
<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">

    {{-- Breadcrumb --}}
    <nav class="mb-6 flex items-center gap-2 text-sm text-charcoal/50" aria-label="Fil d'Ariane">
        <a href="{{ route('home') }}" class="hover:text-gold transition-colors font-medium">Accueil</a>
        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-charcoal font-medium truncate max-w-xs">{{ $annonce->titre }}</span>
    </nav>

    <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">

        {{-- ============ COLONNE PRINCIPALE ============ --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Titre + localisation --}}
            <div>
                <h1 class="text-2xl font-extrabold leading-snug text-charcoal sm:text-3xl">
                    {{ $annonce->titre }}
                </h1>
                <p class="mt-2 flex items-center gap-1.5 text-charcoal/60">
                    <svg class="h-4 w-4 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    {{ $annonce->adresse ? $annonce->adresse.', ' : '' }}{{ $annonce->ville }}
                </p>
            </div>

            {{-- Galerie photos avec gradient beige --}}
            @if ($annonce->photos->isNotEmpty())
                <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
                    @foreach ($annonce->photos as $photo)
                        <div class="annonce-image-container h-36 w-full rounded-xl border border-beige-100 overflow-hidden">
                            <img src="{{ asset('storage/'.$photo->chemin) }}"
                                 alt="{{ $annonce->titre }}"
                                 class="h-full w-full object-cover">
                        </div>
                    @endforeach
                </div>
            @else
                <div class="annonce-image-container h-52 rounded-xl border border-beige-100 overflow-hidden bg-beige-50">
                    <div class="flex h-full items-center justify-center text-charcoal/30">
                        <svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
            @endif

            {{-- Description --}}
            <div class="brand-card p-6">
                <h2 class="mb-3 flex items-center gap-2 text-lg font-bold text-charcoal">
                    <span class="h-5 w-1 rounded-full bg-gold block"></span>
                    Description
                </h2>
                <p class="whitespace-pre-line leading-relaxed text-charcoal/80">{{ $annonce->description }}</p>
            </div>

            {{-- Caractéristiques --}}
            <div class="brand-card p-6">
                <h2 class="mb-4 flex items-center gap-2 text-lg font-bold text-charcoal">
                    <span class="h-5 w-1 rounded-full bg-gold block"></span>
                    Caractéristiques
                </h2>
                <div class="grid grid-cols-2 gap-4 sm:grid-cols-3">
                    <div class="rounded-xl bg-beige-50 p-4 text-center">
                        <p class="text-xs font-semibold uppercase tracking-wider text-charcoal/50">Surface</p>
                        <p class="mt-1 text-xl font-bold text-charcoal">{{ $annonce->surface }} <span class="text-sm font-normal">m²</span></p>
                    </div>
                    @if ($annonce->nombre_chambres)
                        <div class="rounded-xl bg-beige-50 p-4 text-center">
                            <p class="text-xs font-semibold uppercase tracking-wider text-charcoal/50">Chambres</p>
                            <p class="mt-1 text-xl font-bold text-charcoal">{{ $annonce->nombre_chambres }}</p>
                        </div>
                    @endif
                    @if ($annonce->nombre_salles_bain)
                        <div class="rounded-xl bg-beige-50 p-4 text-center">
                            <p class="text-xs font-semibold uppercase tracking-wider text-charcoal/50">Salles de bain</p>
                            <p class="mt-1 text-xl font-bold text-charcoal">{{ $annonce->nombre_salles_bain }}</p>
                        </div>
                    @endif

                    <div class="rounded-xl bg-beige-50 p-4 text-center">
                        <p class="text-xs font-semibold uppercase tracking-wider text-charcoal/50">Vues</p>
                        <p class="mt-1 text-xl font-bold text-charcoal">{{ $annonce->vues }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ============ SIDEBAR ============ --}}
        <aside class="space-y-4 lg:sticky lg:top-24 self-start">

            {{-- Prix --}}
            <div class="brand-card p-6">
                <div class="flex items-baseline gap-2">
                    <p class="text-3xl font-extrabold text-gold">
                        {{ number_format((float) $annonce->prix, 0, ',', ' ') }} DH
                    </p>

                </div>



                {{-- Détails rapides --}}
                <dl class="mt-5 space-y-2.5 text-sm">
                    <div class="flex justify-between border-b border-beige-100 pb-2">
                        <dt class="text-charcoal/55">Type de bien</dt>
                        <dd class="font-semibold text-charcoal">{{ $annonce->typeBien->type }}</dd>
                    </div>
                    <div class="flex justify-between border-b border-beige-100 pb-2">
                        <dt class="text-charcoal/55">Ville</dt>
                        <dd class="font-semibold text-charcoal">{{ $annonce->ville }}</dd>
                    </div>
                    <div class="flex justify-between border-b border-beige-100 pb-2">
                        <dt class="text-charcoal/55">Surface</dt>
                        <dd class="font-semibold text-charcoal">{{ $annonce->surface }} m²</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-charcoal/55">Statut</dt>
                        <dd>
                            <span class="rounded-full px-2.5 py-0.5 text-xs font-bold
                                {{ $annonce->statut_validation === 'valide'
                                    ? 'bg-gold/20 text-gold-dark'
                                    : ($annonce->statut_validation === 'refuse'
                                        ? 'bg-red-100 text-red-600'
                                        : 'bg-beige-100 text-charcoal') }}">
                                {{ str_replace('_', ' ', $annonce->statut_validation) }}
                            </span>
                        </dd>
                    </div>
                </dl>
            </div>

            {{-- Actions --}}
            @auth
                @if (auth()->user()->isAcheteur() && auth()->id() !== $annonce->user_id)
                    {{-- Bouton favoris --}}
                    <form method="POST" action="{{ route('favoris.toggle', $annonce) }}">
                        @csrf
                        <button type="submit"
                                class="w-full rounded-xl border-2 px-4 py-3 font-semibold transition text-sm
                                    {{ $estFavori
                                        ? 'border-gold bg-gold/10 text-gold-dark hover:bg-gold/20'
                                        : 'border-beige-200 bg-white text-charcoal hover:border-gold hover:bg-beige-50' }}">
                            {{ $estFavori ? '★ Retirer des favoris' : '☆ Ajouter aux favoris' }}
                        </button>
                    </form>
                @endif

                @if (auth()->user()->isAcheteur()
                     && $annonce->statut_validation === \App\Models\Annonce::STATUT_VALIDE
                     && auth()->id() !== $annonce->user_id)
                    <a href="{{ route('messages.contacter', $annonce) }}"
                       class="btn-primary w-full py-3.5 text-sm"
                       id="btn-contacter">
                        Contacter le propriétaire
                    </a>
                @endif
            @else
                <a href="{{ route('login') }}"
                   class="btn-primary w-full py-3.5 text-sm text-center block"
                   id="btn-connecter-contact">
                    Connectez-vous pour contacter
                </a>
            @endauth

            {{-- Retour --}}
            <a href="{{ route('home') }}"
               class="flex items-center justify-center gap-1.5 text-sm font-semibold text-charcoal/60 hover:text-gold transition-colors">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Retour aux annonces
            </a>
        </aside>
    </div>
</div>
@endsection
