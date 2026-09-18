@extends('layouts.app')

@section('title', 'Tableau de bord vendeur')

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <section class="relative overflow-hidden rounded-3xl bg-charcoal px-7 py-8 text-white shadow-xl sm:px-10 mb-7">
            <div class="absolute -right-12 -top-16 h-56 w-56 rounded-full bg-gold/15"></div>
            <div class="relative flex flex-col justify-between gap-6 lg:flex-row lg:items-end">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[.2em] text-gold">Espace Vendeur / Propriétaire</p>
                    <h1 class="mt-3 text-3xl font-extrabold sm:text-4xl">Mon tableau de bord</h1>
                    <p class="mt-2 text-white/70">Publiez vos biens, suivez les vues et échangez avec les acheteurs.</p>
                </div>
                <nav class="flex flex-wrap gap-2">
                    <a href="{{ route('annonces.create') }}" class="rounded-xl bg-white px-5 py-2.5 text-sm font-bold text-charcoal hover:bg-gold">
                        + Nouvelle annonce
                    </a>
                    <a href="{{ route('annonces.mes-annonces') }}" class="rounded-xl border border-white/20 bg-white/10 px-5 py-2.5 text-sm font-bold hover:bg-white/20">
                        Mes annonces
                    </a>
                    <a href="{{ route('messages.index') }}" class="rounded-xl border border-white/20 bg-white/10 px-5 py-2.5 text-sm font-bold hover:bg-white/20">
                        Mes messages
                    </a>
                </nav>
            </div>
        </section>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
            @php
                $cartes = [
                    ['label' => 'Annonces publiées', 'valeur' => $stats['annonces']],
                    ['label' => 'Validées', 'valeur' => $stats['validees']],
                    ['label' => 'En attente', 'valeur' => $stats['en_attente']],
                    ['label' => 'Vues', 'valeur' => $stats['vues']],
                    ['label' => 'Messages', 'valeur' => $stats['messages']],
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
            <div class="px-5 py-3 border-b border-beige-100 font-semibold text-charcoal">Dernières annonces</div>

            @if ($annonces->isEmpty())
                <p class="p-5 text-charcoal/50 text-sm">Vous n'avez encore publié aucune annonce.</p>
            @else
                <table class="w-full text-sm">
                    <thead class="bg-beige-50 text-charcoal/60 text-left">
                        <tr>
                            <th class="px-5 py-2 font-semibold">Titre</th>
                            <th class="px-5 py-2 font-semibold">Ville</th>
                            <th class="px-5 py-2 font-semibold">Prix</th>
                            <th class="px-5 py-2 font-semibold">Vues</th>
                            <th class="px-5 py-2 font-semibold">Favoris</th>
                            <th class="px-5 py-2 font-semibold">Statut</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-beige-100">
                        @foreach ($annonces as $annonce)
                            <tr>
                                <td class="px-5 py-3">
                                    <a href="{{ route('annonces.show', $annonce) }}" class="link-gold font-medium hover:underline">
                                        {{ $annonce->titre }}
                                    </a>
                                </td>
                                <td class="px-5 py-3 text-charcoal">{{ $annonce->ville }}</td>
                                <td class="px-5 py-3 text-charcoal">{{ number_format((float) $annonce->prix, 0, ',', ' ') }} DH</td>
                                <td class="px-5 py-3 text-charcoal">{{ $annonce->vues }}</td>
                                <td class="px-5 py-3 text-charcoal">{{ $annonce->favoris_count }}</td>
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
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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
