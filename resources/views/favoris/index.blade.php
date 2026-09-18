@extends('layouts.app')

@section('title', 'Mes favoris')

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <section class="relative overflow-hidden rounded-3xl bg-charcoal px-7 py-8 text-white shadow-xl sm:px-10 mb-10">
            <div class="absolute -right-12 -top-16 h-56 w-56 rounded-full bg-gold/15"></div>
            <div class="relative flex flex-col justify-between gap-6 lg:flex-row lg:items-end">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[.2em] text-gold">Mon espace</p>
                    <h1 class="mt-3 text-3xl font-extrabold sm:text-4xl">Mes favoris</h1>
                    <p class="mt-2 text-white/70">Retrouvez tous les biens que vous avez sauvegardés.</p>
                </div>
                <nav class="flex flex-wrap gap-2">
                    <a href="{{ route('annonces.index') }}" class="rounded-xl bg-white px-5 py-2.5 text-sm font-bold text-charcoal hover:bg-gold">Parcourir les annonces</a>
                    <a href="{{ route('home') }}" class="rounded-xl border border-white/20 bg-white/10 px-5 py-2.5 text-sm font-bold hover:bg-white/20">Retour à l'accueil</a>
                </nav>
            </div>
        </section>

        @if ($favoris->isEmpty())
            <div class="brand-card border-dashed p-10 text-center text-charcoal/50">
                Vous n'avez aucun favori pour le moment.
            </div>
        @else
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($favoris as $favori)
                    @php $annonce = $favori->annonce; @endphp
                    <article class="brand-card group overflow-hidden flex flex-col">
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
                        <div class="flex flex-1 flex-col p-5">
                            <span class="mb-1 text-xs font-semibold uppercase tracking-wider text-charcoal/50">
                                {{ $annonce->typeBien->type }} · {{ $annonce->ville }}
                            </span>
                            <h2 class="text-base font-bold leading-snug text-charcoal group-hover:text-gold transition-colors duration-300 line-clamp-2">
                                {{ $annonce->titre }}
                            </h2>
                            <p class="mt-auto pt-4 text-xl font-extrabold text-gold">
                                {{ number_format((float) $annonce->prix, 0, ',', ' ') }} DH
                            </p>

                            <div class="mt-3 flex items-center gap-3 border-t border-beige-100 pt-3 text-sm">
                                <a href="{{ route('annonces.show', $annonce) }}" class="link-gold hover:underline">Voir l'annonce</a>
                                <form method="POST" action="{{ route('favoris.destroy', $annonce) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Supprimer</button>
                                </form>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="mt-10">{{ $favoris->links() }}</div>
        @endif
    </div>
@endsection
