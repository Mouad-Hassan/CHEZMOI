@extends('layouts.app')

@section('title', 'Mon espace')

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <section class="relative overflow-hidden rounded-3xl bg-charcoal px-7 py-8 text-white shadow-xl sm:px-10 mb-7">
            <div class="absolute -right-12 -top-16 h-56 w-56 rounded-full bg-gold/15"></div>
            <div class="relative flex flex-col justify-between gap-6 lg:flex-row lg:items-end">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[.2em] text-gold">Espace Acheteur / Locataire</p>
                    <h1 class="mt-3 text-3xl font-extrabold sm:text-4xl">Trouvez votre chez-vous</h1>
                    <p class="mt-2 text-white/70">Parcourez les annonces, sauvegardez vos coups de cœur et contactez les propriétaires.</p>
                </div>
                <nav class="flex flex-wrap gap-2">
                    <a href="{{ route('home') }}" class="rounded-xl bg-white px-5 py-2.5 text-sm font-bold text-charcoal hover:bg-gold">
                        Rechercher un bien
                    </a>
                    <a href="{{ route('annonces.index') }}" class="rounded-xl border border-white/20 bg-white/10 px-5 py-2.5 text-sm font-bold hover:bg-white/20">
                        Toutes les annonces
                    </a>
                    <a href="{{ route('messages.index') }}" class="rounded-xl border border-white/20 bg-white/10 px-5 py-2.5 text-sm font-bold hover:bg-white/20">
                        Mes messages
                    </a>
                </nav>
            </div>
        </section>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
            @php
                $cartes = [
                    ['label' => 'Mes favoris', 'valeur' => $stats['favoris']],
                    ['label' => 'Messages reçus', 'valeur' => $stats['messages']],
                    ['label' => 'Messages non lus', 'valeur' => $stats['non_lus']],
                ];
            @endphp

            @foreach ($cartes as $carte)
                <div class="brand-card p-5">
                    <p class="text-sm text-charcoal/60">{{ $carte['label'] }}</p>
                    <p class="text-2xl font-bold text-charcoal">{{ $carte['valeur'] }}</p>
                </div>
            @endforeach
        </div>

        <div class="brand-card overflow-hidden">
            <div class="px-5 py-3 border-b border-beige-100 font-semibold text-charcoal">Derniers favoris</div>

            @if ($favoris->isEmpty())
                <p class="p-5 text-charcoal/50 text-sm">Vous n'avez aucun favori pour le moment.</p>
            @else
                <ul class="divide-y divide-beige-100 text-sm">
                    @foreach ($favoris as $favori)
                        <li class="px-5 py-3 flex items-center justify-between gap-4">
                            <a href="{{ route('annonces.show', $favori->annonce) }}" class="link-gold font-medium hover:underline">
                                {{ $favori->annonce->titre }}
                            </a>
                            <span class="text-charcoal/60 text-right">{{ $favori->annonce->typeBien->type }} — {{ $favori->annonce->ville }}</span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        <div class="brand-card overflow-hidden mt-8">
            <div class="px-5 py-3 border-b border-beige-100 font-semibold text-charcoal">Derniers messages par annonce</div>

            @if ($stats['messages'] > 0)
                <ul class="divide-y divide-beige-100 text-sm">
                    @php
                        $derniersMessages = \App\Models\Message::where('expediteur_id', auth()->id())
                            ->orWhere('destinataire_id', auth()->id())
                            ->with(['expediteur', 'destinataire', 'annonce'])
                            ->orderByDesc('date_envoi')
                            ->take(5)
                            ->get()
                            ->groupBy('annonce_id');
                    @endphp
                    @foreach ($derniersMessages as $annonceId => $groupMessages)
                        @php
                            $message = $groupMessages->first();
                            $interlocuteur = $message->expediteur_id === auth()->id() ? $message->destinataire : $message->expediteur;
                        @endphp
                        <li class="px-5 py-3 flex items-center justify-between gap-4">
                            <div class="flex-1">
                                @if ($message->annonce)
                                    <a href="{{ route('messages.show', $interlocuteur) }}?annonce_id={{ $message->annonce->id }}" class="link-gold font-medium hover:underline">
                                        {{ $message->annonce->titre }}
                                    </a>
                                    <p class="text-charcoal/60 text-xs">Avec {{ $interlocuteur->nom }}</p>
                                @else
                                    <a href="{{ route('messages.show', $interlocuteur) }}" class="link-gold font-medium hover:underline">
                                        Conversation avec {{ $interlocuteur->nom }}
                                    </a>
                                @endif
                            </div>
                            <span class="text-charcoal/60 text-right">{{ $message->date_envoi->format('d/m/Y H:i') }}</span>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="p-5 text-charcoal/50 text-sm">Vous n'avez aucun message pour le moment.</p>
            @endif
        </div>
    </div>
@endsection
