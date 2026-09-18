@extends('layouts.app')

@section('title', 'Mon profil')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">

    <section class="relative overflow-hidden rounded-3xl bg-charcoal px-7 py-8 text-white shadow-xl sm:px-10 mb-7">
        <div class="absolute -right-12 -top-16 h-56 w-56 rounded-full bg-gold/15"></div>
        <div class="relative flex flex-col justify-between gap-6 lg:flex-row lg:items-end">
            <div>
                <p class="text-xs font-bold uppercase tracking-[.2em] text-gold">Mon espace</p>
                <h1 class="mt-3 text-3xl font-extrabold sm:text-4xl">Mon profil</h1>
                <p class="mt-2 text-white/70">Mettez à jour vos informations personnelles et votre mot de passe.</p>
            </div>
            <nav class="flex flex-wrap gap-2">
                <a href="{{ route('dashboard') }}" class="rounded-xl border border-white/20 bg-white/10 px-5 py-2.5 text-sm font-bold hover:bg-white/20">Retour tableau de bord</a>
            </nav>
        </div>
    </section>

    <div class="space-y-6">
        <div class="brand-card p-6 sm:p-8">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="brand-card p-6 sm:p-8">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="brand-card p-6 sm:p-8 border-red-100">
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</div>
@endsection
