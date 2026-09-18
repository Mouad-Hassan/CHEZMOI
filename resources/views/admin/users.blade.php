@extends('layouts.app')

@section('title', 'Administration — Utilisateurs')

@section('content')
<div class="min-h-[calc(100vh-16rem)] bg-gradient-to-b from-beige-50/70 to-white py-8">
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <section class="relative overflow-hidden rounded-3xl bg-charcoal px-7 py-8 text-white shadow-xl sm:px-10 mb-7">
        <div class="absolute -right-12 -top-16 h-56 w-56 rounded-full bg-gold/15"></div>
        <div class="relative flex flex-col justify-between gap-6 lg:flex-row lg:items-end">
            <div>
                <p class="text-xs font-bold uppercase tracking-[.2em] text-gold">Administration</p>
                <h1 class="mt-3 text-3xl font-extrabold sm:text-4xl">Gestion des utilisateurs</h1>
                <p class="mt-2 text-white/70">Consultez les comptes et attribuez les rôles.</p>
            </div>
            <nav class="flex flex-wrap gap-2">
                <a href="{{ route('admin.annonces') }}" class="rounded-xl border border-white/20 bg-white/10 px-4 py-2.5 text-sm font-bold hover:bg-white/20">Annonces</a>
                <a href="{{ route('admin.users') }}" class="rounded-xl bg-white px-4 py-2.5 text-sm font-bold text-charcoal hover:bg-gold">Utilisateurs</a>
                <a href="{{ route('admin.type-biens') }}" class="rounded-xl border border-white/20 bg-white/10 px-4 py-2.5 text-sm font-bold hover:bg-white/20">Catégories</a>
                <a href="{{ route('admin.dashboard') }}" class="rounded-xl border border-white/20 bg-white/10 px-4 py-2.5 text-sm font-bold hover:bg-white/20">Statistiques</a>
            </nav>
        </div>
    </section>

    <form method="GET" class="mb-6 flex flex-wrap items-end gap-4 rounded-2xl border border-beige-100 bg-white p-5 shadow-sm">
        <div>
            <label class="block text-xs font-semibold text-charcoal/60 mb-1">Rôle</label>
            <select name="role" class="brand-input py-2 px-3 text-sm">
                <option value="">Tous</option>
                <option value="admin" @selected(request('role') === 'admin')>Administrateur</option>
                <option value="proprietaire" @selected(request('role') === 'proprietaire')>Propriétaire</option>
                <option value="acheteur" @selected(request('role') === 'acheteur')>Acheteur</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-semibold text-charcoal/60 mb-1">Recherche</label>
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Nom ou email"
                   class="brand-input py-2 px-3 text-sm">
        </div>
        <button type="submit" class="btn-primary px-5 py-2.5">Filtrer</button>
        <a href="{{ route('admin.users') }}" class="text-sm text-charcoal/70 hover:underline">Réinitialiser</a>
    </form>

    <div class="overflow-x-auto rounded-2xl border border-beige-100 bg-white shadow-lg">
        <table class="w-full text-sm">
            <thead class="bg-beige-50 text-left text-xs uppercase tracking-wider text-charcoal/60">
                <tr>
                    <th class="px-5 py-3 font-semibold">Nom</th>
                    <th class="px-5 py-3 font-semibold">Email</th>
                    <th class="px-5 py-3 font-semibold">Annonces</th>
                    <th class="px-5 py-3 font-semibold">Rôle</th>
                    <th class="px-5 py-3 font-semibold text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-beige-100">
                @foreach ($users as $user)
                    <tr class="transition hover:bg-beige-50/60">
                        <td class="px-5 py-3 font-medium text-charcoal">{{ $user->nom }}</td>
                        <td class="px-5 py-3 text-charcoal">{{ $user->email }}</td>
                        <td class="px-5 py-3 text-charcoal">{{ $user->annonces_count }}</td>
                        <td class="px-5 py-3">
                            @if ($user->id === auth()->id())
                                <span class="text-xs rounded-full bg-beige-50 text-charcoal/70 px-2 py-0.5">
                                    {{ $user->role_label }} (vous)
                                </span>
                            @else
                                <form method="POST" action="{{ route('admin.users.role', $user) }}" class="flex items-center gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <select name="role" class="rounded-lg border border-beige-200 bg-white px-2 py-1 text-xs text-charcoal focus:border-gold focus:ring-1 focus:ring-gold">
                                        <option value="acheteur" @selected($user->role === 'acheteur')>Acheteur</option>
                                        <option value="proprietaire" @selected($user->role === 'proprietaire')>Propriétaire</option>
                                        <option value="admin" @selected($user->role === 'admin')>Administrateur</option>
                                    </select>
                                    <button type="submit" class="link-gold text-xs">Enregistrer</button>
                                </form>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-right">
                            @if ($user->id !== auth()->id())
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline"
                                      onsubmit="return confirm('Supprimer cet utilisateur et toutes ses annonces ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Supprimer</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $users->links() }}</div>
</div>
</div>
@endsection
