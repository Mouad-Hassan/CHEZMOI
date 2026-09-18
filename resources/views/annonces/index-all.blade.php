@extends('layouts.app')

@section('title', 'Toutes les annonces')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">

    <section class="relative overflow-hidden rounded-3xl bg-charcoal px-7 py-8 text-white shadow-xl sm:px-10 mb-7">
        <div class="absolute -right-12 -top-16 h-56 w-56 rounded-full bg-gold/15"></div>
        <div class="relative flex flex-col justify-between gap-6 lg:flex-row lg:items-end">
            <div>
                <p class="text-xs font-bold uppercase tracking-[.2em] text-gold">Annonces</p>
                <h1 class="mt-3 text-3xl font-extrabold sm:text-4xl">Toutes les annonces</h1>
                <p class="mt-2 text-white/70">Parcourez toutes les annonces immobilières disponibles.</p>
            </div>
            <nav class="flex flex-wrap gap-2">
                <a href="{{ route('home') }}" class="rounded-xl border border-white/20 bg-white/10 px-5 py-2.5 text-sm font-bold hover:bg-white/20">
                    Recherche avancée
                </a>
                @auth
                    @if (auth()->user()->isProprietaire())
                        <a href="{{ route('annonces.mes-annonces') }}" class="rounded-xl bg-white px-5 py-2.5 text-sm font-bold text-charcoal hover:bg-gold">
                            Mes annonces
                        </a>
                    @endif
                @endauth
            </nav>
        </div>
    </section>

    @if ($annonces->isEmpty())
        <div class="brand-card border-dashed p-16 text-center">
            <svg class="mx-auto mb-4 h-12 w-12 text-beige-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <p class="text-charcoal/60">Aucune annonce disponible pour le moment.</p>
        </div>
    @else
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($annonces as $annonce)
                <a href="{{ route('annonces.show', $annonce) }}"
                   class="brand-card group overflow-hidden flex flex-col"
                   id="annonce-card-{{ $annonce->id }}">

                    {{-- Image avec gradient beige --}}
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

                    {{-- Corps de la carte --}}
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
</div>
@endsection