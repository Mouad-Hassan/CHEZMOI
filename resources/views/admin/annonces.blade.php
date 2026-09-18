@extends('layouts.app')

@section('title', 'Administration — Annonces')

@section('content')
<div class="min-h-[calc(100vh-16rem)] bg-gradient-to-b from-beige-50/70 to-white py-8">
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <section class="relative overflow-hidden rounded-3xl bg-charcoal px-7 py-8 text-white shadow-xl sm:px-10 mb-7">
        <div class="absolute -right-12 -top-16 h-56 w-56 rounded-full bg-gold/15"></div>
        <div class="relative flex flex-col justify-between gap-6 lg:flex-row lg:items-end">
            <div>
                <p class="text-xs font-bold uppercase tracking-[.2em] text-gold">Administration</p>
                <h1 class="mt-3 text-3xl font-extrabold sm:text-4xl">Gestion des annonces</h1>
                <p class="mt-2 text-white/70">Consultez et modérez toutes les publications.</p>
            </div>
            <nav class="flex flex-wrap gap-2">
                <a href="{{ route('admin.annonces') }}" class="rounded-xl bg-white px-4 py-2.5 text-sm font-bold text-charcoal hover:bg-gold">Annonces</a>
                <a href="{{ route('admin.users') }}" class="rounded-xl border border-white/20 bg-white/10 px-4 py-2.5 text-sm font-bold hover:bg-white/20">Utilisateurs</a>
                <a href="{{ route('admin.type-biens') }}" class="rounded-xl border border-white/20 bg-white/10 px-4 py-2.5 text-sm font-bold hover:bg-white/20">Catégories</a>
                <a href="{{ route('admin.dashboard') }}" class="rounded-xl border border-white/20 bg-white/10 px-4 py-2.5 text-sm font-bold hover:bg-white/20">Statistiques</a>
            </nav>
        </div>
    </section>

    <form method="GET" class="brand-card p-4 mb-6 flex flex-wrap gap-3 items-end">
        <div>
            <label class="block text-xs font-semibold text-charcoal/60 mb-1">Statut</label>
            <select name="statut" class="brand-input py-2 px-3 text-sm">
                <option value="">Tous</option>
                @foreach (['en_attente', 'valide', 'refuse'] as $statut)
                    <option value="{{ $statut }}" @selected(request('statut') === $statut)>
                        {{ str_replace('_', ' ', $statut) }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-semibold text-charcoal/60 mb-1">Ville</label>
            <input type="text" name="ville" value="{{ request('ville') }}"
                   class="brand-input py-2 px-3 text-sm">
        </div>
        <button type="submit" class="rounded-xl bg-charcoal px-5 py-2.5 text-white text-sm font-semibold transition hover:bg-charcoal">Filtrer</button>
        <a href="{{ route('admin.annonces') }}" class="text-sm text-charcoal/70 hover:underline">Réinitialiser</a>
    </form>

    <div class="brand-card overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-beige-50 text-charcoal/60 text-left">
                <tr>
                    <th class="px-5 py-3 font-semibold">Titre</th>
                    <th class="px-5 py-3 font-semibold">Propriétaire</th>
                    <th class="px-5 py-3 font-semibold">Type</th>
                    <th class="px-5 py-3 font-semibold">Ville</th>
                    <th class="px-5 py-3 font-semibold">Prix</th>
                    <th class="px-5 py-3 font-semibold">Statut</th>
                    <th class="px-5 py-3 font-semibold text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-beige-100">
                @forelse ($annonces as $annonce)
                    <tr>
                        <td class="px-5 py-3 font-medium text-charcoal">
                            <a href="{{ route('annonces.show', $annonce) }}" class="hover:underline">{{ $annonce->titre }}</a>
                        </td>
                        <td class="px-5 py-3 text-charcoal">{{ $annonce->user->nom }}</td>
                        <td class="px-5 py-3 text-charcoal">{{ $annonce->typeBien->type }}</td>
                        <td class="px-5 py-3 text-charcoal">{{ $annonce->ville }}</td>
                        <td class="px-5 py-3 text-charcoal">{{ number_format((float) $annonce->prix, 0, ',', ' ') }} DH</td>
                        <td class="px-5 py-3">
                            @php
                                $classes = [
                                    'valide' => 'bg-beige-100 text-gold',
                                    'en_attente' => 'bg-beige-50 text-charcoal',
                                    'refuse' => 'bg-beige-200 text-charcoal',
                                ][$annonce->statut_validation] ?? 'bg-beige-50 text-charcoal/70';
                            @endphp
                            <span class="text-xs rounded-full px-2 py-0.5 {{ $classes }} font-semibold">
                                {{ str_replace('_', ' ', $annonce->statut_validation) }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-right whitespace-nowrap space-x-2">
                            @if ($annonce->statut_validation !== 'valide')
                                <form method="POST" action="{{ route('admin.annonces.valider', $annonce) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="link-gold hover:underline">Valider</button>
                                </form>
                            @endif

                            @if ($annonce->statut_validation !== 'refuse')
                                <form method="POST" action="{{ route('admin.annonces.refuser', $annonce) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-charcoal hover:underline">Refuser</button>
                                </form>
                            @endif

                            <form method="POST" action="{{ route('admin.annonces.destroy', $annonce) }}" class="inline"
                                  onsubmit="return confirm('Supprimer définitivement cette annonce ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-5 py-6 text-center text-charcoal/50">Aucune annonce.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $annonces->links() }}</div>
</div>
</div>
@endsection
