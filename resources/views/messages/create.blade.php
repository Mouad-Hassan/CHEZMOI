@extends('layouts.app')

@section('title', 'Contacter le propriétaire')

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <section class="relative overflow-hidden rounded-3xl bg-charcoal px-7 py-8 text-white shadow-xl sm:px-10 mb-8">
            <div class="absolute -right-12 -top-16 h-56 w-56 rounded-full bg-gold/15"></div>
            <div class="relative flex flex-col justify-between gap-6 lg:flex-row lg:items-end">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[.2em] text-gold">Contacter</p>
                    <h1 class="mt-3 text-3xl font-extrabold sm:text-4xl">Contacter le propriétaire</h1>
                    <p class="mt-2 text-white/70">À propos de : <strong class="text-white">{{ $annonce->titre }}</strong> — {{ $annonce->ville }}</p>
                </div>
                <nav class="flex flex-wrap gap-2">
                    <a href="{{ route('annonces.show', $annonce) }}" class="rounded-xl border border-white/20 bg-white/10 px-5 py-2.5 text-sm font-bold hover:bg-white/20">Retour à l'annonce</a>
                </nav>
            </div>
        </section>

        <div class="max-w-2xl mx-auto brand-card p-8 sm:p-10">

            @if ($errors->any())
                <div class="mb-5 rounded-xl border border-beige-200 bg-beige-100/60 text-charcoal px-4 py-3 text-sm">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('messages.store') }}">
                @csrf
                <input type="hidden" name="destinataire_id" value="{{ $annonce->user_id }}">
                <input type="hidden" name="annonce_id" value="{{ $annonce->id }}">

                <textarea name="contenu" rows="5" required placeholder="Bonjour, je suis intéressé(e) par votre bien…"
                          class="brand-input mb-4.5 leading-relaxed">{{ old('contenu') }}</textarea>

                <button type="submit" class="brand-button">
                    Envoyer le message
                </button>
            </form>
        </div>
    </div>
@endsection
