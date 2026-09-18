@extends('layouts.app')

@section('title', 'Administration — Catégories')

@section('content')
<div class="min-h-[calc(100vh-16rem)] bg-gradient-to-b from-beige-50/70 to-white py-8"><div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <section class="relative overflow-hidden rounded-3xl bg-charcoal px-7 py-8 text-white shadow-xl sm:px-10 mb-7">
        <div class="absolute -right-12 -top-16 h-56 w-56 rounded-full bg-gold/15"></div>
        <div class="relative flex flex-col justify-between gap-6 lg:flex-row lg:items-end">
            <div>
                <p class="text-xs font-bold uppercase tracking-[.2em] text-gold">Administration</p>
                <h1 class="mt-3 text-3xl font-extrabold sm:text-4xl">Gestion des catégories</h1>
                <p class="mt-2 text-white/70">Organisez les types de biens proposés sur ChezMoi.</p>
            </div>
            <nav class="flex flex-wrap gap-2">
                <a href="{{ route('admin.annonces') }}" class="rounded-xl border border-white/20 bg-white/10 px-4 py-2.5 text-sm font-bold hover:bg-white/20">Annonces</a>
                <a href="{{ route('admin.users') }}" class="rounded-xl border border-white/20 bg-white/10 px-4 py-2.5 text-sm font-bold hover:bg-white/20">Utilisateurs</a>
                <a href="{{ route('admin.type-biens') }}" class="rounded-xl bg-white px-4 py-2.5 text-sm font-bold text-charcoal hover:bg-gold">Catégories</a>
                <a href="{{ route('admin.dashboard') }}" class="rounded-xl border border-white/20 bg-white/10 px-4 py-2.5 text-sm font-bold hover:bg-white/20">Statistiques</a>
            </nav>
        </div>
    </section>

    <form method="POST" action="{{ route('admin.type-biens.store') }}"
          class="mb-6 flex flex-wrap items-end gap-4 rounded-2xl border border-beige-100 bg-white p-5 shadow-sm">
        @csrf
        <div>
            <label for="type" class="block text-xs font-semibold text-charcoal/60 mb-1">Nouvelle catégorie</label>
            <input id="type" name="type" type="text" required placeholder="Ex. Studio"
                   class="brand-input py-2 px-3 text-sm">
        </div>
        <button type="submit" class="btn-primary px-5 py-2.5">
            Ajouter
        </button>
    </form>

    <div class="overflow-hidden rounded-2xl border border-beige-100 bg-white shadow-lg">
        <table class="w-full text-sm">
            <thead class="bg-beige-50 text-left text-xs uppercase tracking-wider text-charcoal/60">
                <tr>
                    <th class="px-5 py-3 font-semibold">Catégorie</th>
                    <th class="px-5 py-3 font-semibold">Annonces liées</th>
                    <th class="px-5 py-3 font-semibold text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-beige-100">
                @foreach ($types as $type)
                    <tr class="transition hover:bg-beige-50/60">
                        <td class="px-5 py-3 font-medium text-charcoal">{{ $type->type }}</td>
                        <td class="px-5 py-3 text-charcoal">{{ $type->annonces_count }}</td>
                        <td class="px-5 py-3 text-right">
                            <form method="POST" action="{{ route('admin.type-biens.destroy', $type) }}" class="inline"
                                  onsubmit="return confirm('Supprimer cette catégorie ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
</div></div>
@endsection
