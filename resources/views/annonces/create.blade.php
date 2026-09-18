@extends('layouts.app')

@section('title', 'Nouvelle annonce')

@section('content')
<div class="min-h-[calc(100vh-16rem)] bg-gradient-to-b from-beige-50/70 to-white py-8">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <a href="{{ route('annonces.index') }}" class="text-sm font-semibold text-gold hover:underline">&larr; Retour à mes annonces</a>
        </div>

        <section class="relative overflow-hidden rounded-3xl bg-charcoal px-7 py-8 text-white shadow-xl sm:px-10 mb-7">
            <div class="absolute -right-12 -top-16 h-56 w-56 rounded-full bg-gold/15"></div>
            <div class="relative">
                <p class="text-xs font-bold uppercase tracking-[.2em] text-gold">Mon espace</p>
                <h1 class="mt-3 text-3xl font-extrabold sm:text-4xl">Publier une nouvelle annonce</h1>
                <p class="mt-2 text-white/70">Présentez votre bien avec des informations claires et des photos de qualité.</p>
            </div>
        </section>

        <div class="brand-card p-6 sm:p-8">

        @if ($errors->any())
            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 text-red-800 px-4 py-3 text-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('annonces.store') }}" enctype="multipart/form-data">
            @csrf
            @include('annonces.partials.form-fields')

            <div class="mt-8 flex flex-col-reverse sm:flex-row sm:items-center gap-3 border-t border-beige-100 pt-6">
                <a href="{{ route('annonces.index') }}" class="brand-outline-button">
                    Annuler
                </a>
                <button type="submit" class="brand-button">
                    Publier l'annonce
                </button>
            </div>
        </form>
        </div>
    </div>
</div>
@endsection
