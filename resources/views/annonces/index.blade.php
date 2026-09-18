@extends('layouts.app')

@section('title', 'Mes annonces')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">

    <section class="relative overflow-hidden rounded-3xl bg-charcoal px-7 py-8 text-white shadow-xl sm:px-10 mb-7">
        <div class="absolute -right-12 -top-16 h-56 w-56 rounded-full bg-gold/15"></div>
        <div class="relative flex flex-col justify-between gap-6 lg:flex-row lg:items-end">
            <div>
                <p class="text-xs font-bold uppercase tracking-[.2em] text-gold">Mon espace</p>
                <h1 class="mt-3 text-3xl font-extrabold sm:text-4xl">Mes annonces</h1>
                <p class="mt-2 text-white/70">Gérez vos publications et suivez leurs performances.</p>
            </div>
            <a href="{{ route('annonces.create') }}"
               id="btn-nouvelle-annonce"
               class="rounded-xl bg-white px-5 py-2.5 text-sm font-bold text-charcoal hover:bg-gold inline-flex items-center gap-2">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                Nouvelle annonce
            </a>
            <a href="{{ route('annonces.index') }}"
               class="rounded-xl border border-white/20 bg-white/10 px-5 py-2.5 text-sm font-bold hover:bg-white/20">
                Voir toutes les annonces
            </a>
        </div>
    </section>

    @if ($annonces->isEmpty())
        {{-- État vide --}}
        <div class="brand-card border-dashed p-16 text-center">
            <svg class="mx-auto mb-4 h-12 w-12 text-beige-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <p class="text-charcoal/60 font-medium">Vous n'avez encore publié aucune annonce.</p>
            <a href="{{ route('annonces.create') }}" class="btn-primary mt-5 px-6 py-2.5 text-sm">
                Publier ma première annonce
            </a>
        </div>
    @else
        {{-- Tableau des annonces --}}
        <div class="brand-card overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-beige-100 bg-beige-50 text-left">
                        <th class="px-5 py-3.5 text-xs font-bold uppercase tracking-wider text-charcoal/60">Titre</th>
                        <th class="px-5 py-3.5 text-xs font-bold uppercase tracking-wider text-charcoal/60 hidden sm:table-cell">Type</th>
                        <th class="px-5 py-3.5 text-xs font-bold uppercase tracking-wider text-charcoal/60 hidden md:table-cell">Ville</th>
                        <th class="px-5 py-3.5 text-xs font-bold uppercase tracking-wider text-charcoal/60">Prix</th>
                        <th class="px-5 py-3.5 text-xs font-bold uppercase tracking-wider text-charcoal/60 hidden lg:table-cell">Vues</th>
                        <th class="px-5 py-3.5 text-xs font-bold uppercase tracking-wider text-charcoal/60">Statut</th>
                        <th class="px-5 py-3.5 text-xs font-bold uppercase tracking-wider text-charcoal/60 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-beige-100">
                    @foreach ($annonces as $annonce)
                        <tr class="group hover:bg-beige-50 transition-colors duration-150">
                            {{-- Titre --}}
                            <td class="px-5 py-4 font-medium">
                                <a href="{{ route('annonces.show', $annonce) }}"
                                   class="text-charcoal hover:text-gold transition-colors duration-200 group-hover:underline">
                                    {{ Str::limit($annonce->titre, 40) }}
                                </a>
                            </td>

                            {{-- Type de bien --}}
                            <td class="px-5 py-4 text-charcoal/70 hidden sm:table-cell">
                                {{ $annonce->typeBien->type }}
                            </td>

                            {{-- Ville --}}
                            <td class="px-5 py-4 text-charcoal/70 hidden md:table-cell">
                                {{ $annonce->ville }}
                            </td>

                            {{-- Prix --}}
                            <td class="px-5 py-4 font-bold text-gold">
                                {{ number_format((float) $annonce->prix, 0, ',', ' ') }} DH
                            </td>

                            {{-- Vues --}}
                            <td class="px-5 py-4 text-charcoal/60 hidden lg:table-cell">
                                <span class="flex items-center gap-1">
                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    {{ $annonce->vues }}
                                </span>
                            </td>

                            {{-- Statut --}}
                            <td class="px-5 py-4">
                                @php
                                    $statusConfig = [
                                        'valide'     => ['bg-gold/15 text-gold-dark',     'Validée'],
                                        'en_attente' => ['bg-beige-100 text-charcoal',     'En attente'],
                                        'refuse'     => ['bg-red-100 text-red-600',        'Refusée'],
                                    ][$annonce->statut_validation] ?? ['bg-beige-50 text-charcoal/60', $annonce->statut_validation];
                                @endphp
                                <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-bold {{ $statusConfig[0] }}">
                                    {{ $statusConfig[1] }}
                                </span>
                            </td>

                            {{-- Actions --}}
                            <td class="px-5 py-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('annonces.show', $annonce) }}"
                                       class="text-xs font-semibold text-charcoal/60 hover:text-gold transition-colors">
                                        Voir
                                    </a>
                                    <a href="{{ route('annonces.edit', $annonce) }}"
                                       class="text-xs font-semibold text-charcoal/60 hover:text-charcoal transition-colors">
                                        Modifier
                                    </a>
                                    <form method="POST" action="{{ route('annonces.destroy', $annonce) }}" class="inline"
                                          onsubmit="return confirm('Supprimer cette annonce ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="text-xs font-semibold text-red-400 hover:text-red-600 transition-colors">
                                            Supprimer
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-6">{{ $annonces->links() }}</div>
    @endif
</div>
@endsection
