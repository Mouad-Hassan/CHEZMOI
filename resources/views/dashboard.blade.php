@extends('layouts.app')

@section('title', 'Tableau de bord')

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <section class="relative overflow-hidden rounded-3xl bg-charcoal px-7 py-8 text-white shadow-xl sm:px-10 mb-7">
            <div class="absolute -right-12 -top-16 h-56 w-56 rounded-full bg-gold/15"></div>
            <div class="relative flex flex-col justify-between gap-6 lg:flex-row lg:items-end">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[.2em] text-gold">Bienvenue</p>
                    <h1 class="mt-3 text-3xl font-extrabold sm:text-4xl">Tableau de bord</h1>
                    <p class="mt-2 text-white/70">Vous êtes connecté à votre espace ChezMoi.</p>
                </div>
                <nav class="flex flex-wrap gap-2">
                    <a href="{{ route('home') }}" class="rounded-xl bg-white px-5 py-2.5 text-sm font-bold text-charcoal hover:bg-gold">
                        Accueil
                    </a>
                    <a href="{{ route('annonces.index') }}" class="rounded-xl border border-white/20 bg-white/10 px-5 py-2.5 text-sm font-bold hover:bg-white/20">
                        Annonces
                    </a>
                </nav>
            </div>
        </section>

        <div class="brand-card p-8">
            <p class="text-lg font-semibold text-charcoal">
                {{ __("You're logged in!") }}
            </p>
        </div>
    </div>
@endsection
