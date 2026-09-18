@extends('layouts.app')

@section('title', 'Messagerie')

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <section class="relative overflow-hidden rounded-3xl bg-charcoal px-7 py-8 text-white shadow-xl sm:px-10 mb-10">
            <div class="absolute -right-12 -top-16 h-56 w-56 rounded-full bg-gold/15"></div>
            <div class="relative flex flex-col justify-between gap-6 lg:flex-row lg:items-end">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[.2em] text-gold">Mon espace</p>
                    <h1 class="mt-3 text-3xl font-extrabold sm:text-4xl">Messagerie</h1>
                    <p class="mt-2 text-white/70">Échangez avec les propriétaires et les acheteurs.</p>
                </div>
                <nav class="flex flex-wrap gap-2">
                    <a href="{{ route('annonces.index') }}" class="rounded-xl bg-white px-5 py-2.5 text-sm font-bold text-charcoal hover:bg-gold">Voir toutes les annonces</a>
                    <a href="{{ route('notifications.index') }}" class="rounded-xl border border-white/20 bg-white/10 px-5 py-2.5 text-sm font-bold hover:bg-white/20">Notifications</a>
                </nav>
            </div>
        </section>

        @if ($conversationsParAnnonce->isEmpty())
            <div class="brand-card border-dashed p-10 text-center text-charcoal/50">
                Aucune conversation pour le moment.
            </div>
        @else
            <div class="brand-card divide-y divide-beige-100">
                @foreach ($conversationsParAnnonce as $conversation)
                    <a href="{{ route('messages.show', $conversation['interlocuteur']) }}?annonce_id={{ $conversation['annonce']->id }}"
                       class="flex items-center justify-between px-5 py-4 hover:bg-beige-50 transition-colors">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-1">
                                @if ($conversation['annonce']->photo_principale)
                                    <img src="{{ asset('storage/'.$conversation['annonce']->photo_principale) }}"
                                         alt="{{ $conversation['annonce']->titre }}"
                                         class="h-12 w-12 rounded-lg object-cover">
                                @else
                                    <div class="h-12 w-12 rounded-lg bg-beige-100 flex items-center justify-center">
                                        <svg class="h-6 w-6 text-beige-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                                <div>
                                    <p class="font-medium text-charcoal">{{ $conversation['annonce']->titre }}</p>
                                    <p class="text-sm text-charcoal/60">Avec {{ $conversation['interlocuteur']->nom }} · {{ $conversation['annonce']->ville }}</p>
                                </div>
                            </div>
                            <p class="text-sm text-charcoal/60 mt-1">{{ Str::limit($conversation['dernier']->contenu, 80) }}</p>
                        </div>
                        <div class="flex items-center gap-3 ml-4">
                            <span class="text-xs text-charcoal/50">{{ $conversation['total_messages'] }} msg</span>
                            @if ($conversation['non_lus'] > 0)
                                <span class="rounded-full bg-gold text-white text-xs font-semibold px-2.5 py-0.5 shadow-sm">
                                    {{ $conversation['non_lus'] }}
                                </span>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
@endsection
