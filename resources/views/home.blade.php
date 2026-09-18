@extends('layouts.app')

@section('title', 'Trouvez votre bien immobilier')
@section('meta_description', 'Achetez, vendez ou louez un bien immobilier au Maroc en toute confiance avec ChezMoi. Des milliers d\'annonces vous attendent !')

@section('content')

    {{-- ================================================================
         SECTION HERO — Image de fond + overlay gradient beige/charcoal
         ================================================================ --}}
    <section class="relative isolate overflow-hidden" id="hero">

        {{-- Image de fond --}}
        <div class="absolute inset-0 -z-20 bg-cover bg-center bg-no-repeat"
             style="background-image: url('https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=2200&q=85');">
        </div>

        {{-- Overlay gradient : charcoal → beige transparent (correspond à la palette) --}}
        <div class="absolute inset-0 -z-10 hero-chez-gradient"></div>

        {{-- Overlay beige très léger en haut pour le fondu avec la navbar --}}
        <div class="absolute inset-x-0 top-0 -z-10 h-32"
             style="background: linear-gradient(to bottom, rgba(248,248,248,0.08), transparent);">
        </div>

        {{-- Contenu texte héro --}}
        <div class="mx-auto max-w-7xl px-4 pb-44 pt-24 sm:px-6 sm:pb-52 sm:pt-32 lg:pt-36">
            <div class="max-w-3xl animate-fade-up">
                <h1 class="text-5xl font-extrabold leading-tight tracking-tight text-white drop-shadow-[0_2px_14px_rgba(15,15,15,0.55)] sm:text-7xl">
                    Trouvez la maison<br>
                    <span style="color:#F7C873;">de vos rêves</span>
                </h1>
                <p class="mt-6 max-w-xl text-lg leading-8 text-white/85 sm:text-xl"
                   style="text-shadow: 0 1px 6px rgba(15,15,15,0.55);">
                    Achetez ou vendez un bien immobilier en toute confiance avec ChezMoi.
                    Des milliers d'annonces vous attendent !
                </p>

            </div>
        </div>

        {{-- ===== Barre de recherche flottante ===== --}}
        <form method="GET" action="{{ route('home') }}"
              class="absolute inset-x-4 bottom-6 mx-auto max-w-5xl
                     rounded-3xl bg-white p-4 shadow-2xl sm:bottom-10 sm:p-5 lg:bottom-20
                     border border-beige-100 z-20"
              id="search-form">
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-7 sm:gap-4">

                {{-- Type de bien --}}
                <div class="relative">
                    <label for="type_bien_id" class="sr-only">Type de bien</label>
                    <select id="type_bien_id" name="type_bien_id" class="brand-input appearance-none px-5 py-4 pr-12 text-base">
                        <option value="">Type de bien</option>
                        @foreach ($types as $type)
                            <option value="{{ $type->id }}"
                                    @selected((string) request('type_bien_id') === (string) $type->id)>
                                {{ $type->type }}
                            </option>
                        @endforeach
                    </select>
                    <svg class="pointer-events-none absolute right-4 top-1/2 h-5 w-5 -translate-y-1/2 text-charcoal/40"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>

                {{-- Ville --}}
                <div>
                    <label for="ville" class="sr-only">Ville</label>
                    <input id="ville" type="text" name="ville"
                           value="{{ request('ville') }}"
                           placeholder="Ville"
                           class="brand-input px-5 py-4 text-base">
                </div>

                <div>
                    <label for="prix_min" class="sr-only">Prix min</label>
                    <input id="prix_min" type="number" name="prix_min"
                           min="0" value="{{ request('prix_min') }}"
                           placeholder="Prix min"
                           class="brand-input px-5 py-4 text-base">
                </div>

                <div>
                    <label for="prix_max" class="sr-only">Prix max</label>
                    <input id="prix_max" type="number" name="prix_max"
                           min="0" value="{{ request('prix_max') }}"
                           placeholder="Prix max"
                           class="brand-input px-5 py-4 text-base">
                </div>

                <div>
                    <label for="surface" class="sr-only">Surface min (m²)</label>
                    <input id="surface" type="number" name="surface"
                           min="0" value="{{ request('surface') }}"
                           placeholder="Surface min"
                           class="brand-input px-5 py-4 text-base">
                </div>

                <div>
                    <label for="chambres" class="sr-only">Chambres min</label>
                    <input id="chambres" type="number" name="chambres"
                           min="0" value="{{ request('chambres') }}"
                           placeholder="Chambres min"
                           class="brand-input px-5 py-4 text-base">
                </div>

                {{-- Bouton --}}
                <div class="flex sm:col-span-2 lg:col-span-1">
                    <button type="submit"
                            id="btn-rechercher"
                            class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-charcoal px-6 py-4
                                   text-sm font-bold text-white shadow-lg transition
                                   hover:bg-charcoal-dark hover:shadow-xl
                                   focus:outline-none focus:ring-2 focus:ring-gold focus:ring-offset-2">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Rechercher
                    </button>
                </div>
            </div>
        </form>
    </section>

    {{-- ================================================================
         SECTION ANNONCES
         ================================================================ --}}
    <section id="annonces" class="mx-auto max-w-7xl px-4 pb-16 pt-36 sm:px-6 sm:pt-44">

        {{-- En-tête section --}}
        <div class="mb-8 flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="section-label">Sélection ChezMoi</p>
                <span class="gold-line"></span>
                <h2 class="mt-3 text-3xl font-extrabold tracking-tight text-charcoal">
                    Les dernières annonces
                </h2>
            </div>
            <div class="flex items-center gap-3">
                <span class="rounded-full bg-beige-100 px-3.5 py-1.5 text-sm font-semibold text-charcoal">
                    {{ $annonces->total() }} annonce(s)
                </span>
            </div>
        </div>

        @if ($annonces->isEmpty())
            {{-- État vide --}}
            <div class="brand-card border-dashed p-16 text-center">
                <svg class="mx-auto mb-4 h-12 w-12 text-beige-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <p class="text-charcoal/60">Aucune annonce ne correspond à votre recherche.</p>
                <a href="{{ route('home') }}" class="mt-4 inline-flex btn-outline text-sm px-5 py-2">
                    Réinitialiser les filtres
                </a>
            </div>
        @else
            {{-- Grille de cartes --}}
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($annonces as $annonce)
                    <a href="{{ route('annonces.show', $annonce) }}"
                       class="brand-card group overflow-hidden flex flex-col"
                       id="annonce-card-{{ $annonce->id }}">

                        {{-- --- Image avec gradient beige --- --}}
                        <div class="annonce-image-container relative h-56 w-full bg-beige-50">
                            @if ($annonce->photo_principale)
                                <img src="{{ asset('storage/'.$annonce->photo_principale) }}"
                                     alt="{{ $annonce->titre }}"
                                     class="h-full w-full object-cover">
                            @else
                                <div class="flex h-full items-center justify-center bg-gradient-to-br from-beige-50 to-beige-100">
                                    <svg class="h-12 w-12 text-beige-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                              d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @endif

                        </div>

                        {{-- --- Corps de la carte --- --}}
                        <div class="flex flex-1 flex-col p-5">
                            <p class="mb-1.5 text-xs font-semibold uppercase tracking-wider text-charcoal/50">
                                {{ $annonce->typeBien->type }} · {{ $annonce->ville }}
                            </p>
                            <h3 class="text-base font-bold leading-snug text-charcoal
                                       group-hover:text-gold transition-colors duration-300 line-clamp-2">
                                {{ $annonce->titre }}
                            </h3>
                            <p class="mt-auto pt-4 text-xl font-extrabold text-gold">
                                {{ number_format((float) $annonce->prix, 0, ',', ' ') }} DH
                            </p>

                            {{-- Caractéristiques --}}
                            <div class="mt-3 flex gap-4 border-t border-beige-100 pt-3 text-sm text-charcoal/60">
                                <span class="flex items-center gap-1">
                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                                    </svg>
                                    {{ $annonce->surface }} m²
                                </span>
                                @if ($annonce->nombre_chambres)
                                    <span class="flex items-center gap-1">
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                        </svg>
                                        {{ $annonce->nombre_chambres }} ch.
                                    </span>
                                @endif
                                @if ($annonce->nombre_salles_bain)
                                    <span>{{ $annonce->nombre_salles_bain }} sdb</span>
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-10">{{ $annonces->links() }}</div>
        @endif
    </section>

    {{-- ================================================================
         SECTION CTA — Vendez votre bien
         ================================================================ --}}
    @guest
    <section class="bg-section-beige py-16 mt-8" id="cta-vendeurs">
        <div class="mx-auto max-w-4xl px-4 text-center sm:px-6">
            <p class="section-label">Vous êtes propriétaire ?</p>
            <span class="gold-line mx-auto"></span>
            <h2 class="mt-4 text-3xl font-extrabold text-charcoal">
                Publiez votre annonce gratuitement
            </h2>
            <p class="mt-4 text-charcoal/70 max-w-xl mx-auto">
                Rejoignez des milliers de propriétaires qui font confiance à ChezMoi
                pour vendre leur bien rapidement.
            </p>
            <div class="mt-8 flex flex-col items-center justify-center gap-4 sm:flex-row">
                <a href="{{ route('register') }}" class="btn-primary px-8 py-3.5 text-base">
                    Créer un compte vendeur
                </a>
                <a href="{{ route('login') }}" class="btn-outline px-8 py-3.5 text-base">
                    Se connecter
                </a>
            </div>
        </div>
    </section>
    @endguest

@endsection
