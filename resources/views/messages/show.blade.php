@extends('layouts.app')

@section('title', 'Conversation avec '.$user->nom)

@section('content')
    <div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
        <section class="relative overflow-hidden rounded-3xl bg-charcoal px-7 py-6 text-white shadow-xl sm:px-10 mb-6">
            <div class="absolute -right-12 -top-16 h-56 w-56 rounded-full bg-gold/15"></div>
            <div class="relative flex flex-col justify-between gap-4 lg:flex-row lg:items-end">
                <div>
                    <a href="{{ route('messages.index') }}" class="text-sm text-gold/90 font-semibold hover:underline">&larr; Toutes les conversations</a>
                    <h1 class="mt-2 text-2xl font-extrabold sm:text-3xl">Conversation avec {{ $user->nom }}</h1>
                    @if ($annonce)
                        <p class="mt-1 text-white/70 text-sm">À propos de : {{ $annonce->titre }}</p>
                    @endif
                </div>
            </div>
        </section>

        <div class="brand-card p-5 space-y-4 max-h-[28rem] overflow-y-auto mb-4">
            @forelse ($messages as $message)
                @php $estMoi = $message->expediteur_id === auth()->id(); @endphp
                <div class="flex {{ $estMoi ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-[75%] rounded-2xl px-4 py-3 text-sm
                        {{ $estMoi ? 'bg-gold text-white shadow-sm' : 'bg-beige-50 text-charcoal border border-beige-100' }}">
                        @if ($message->annonce)
                            <p class="text-xs {{ $estMoi ? 'text-white/85' : 'text-charcoal/60' }} mb-1.5 font-medium">
                                À propos de : {{ $message->annonce->titre }}
                            </p>
                        @endif
                        <p class="whitespace-pre-line leading-relaxed">{{ $message->contenu }}</p>
                        <p class="text-[11px] mt-2 {{ $estMoi ? 'text-white/75' : 'text-charcoal/50' }}">
                            {{ $message->date_envoi?->format('d/m/Y H:i') }}
                        </p>
                    </div>
                </div>
            @empty
                <p class="text-charcoal/60 text-sm text-center py-8">Aucun message. Écrivez le premier !</p>
            @endforelse
        </div>

        <form method="POST" action="{{ route('messages.store') }}" class="brand-card p-5">
            @csrf
            <input type="hidden" name="destinataire_id" value="{{ $user->id }}">

            <label for="annonce_id" class="block text-sm font-semibold text-charcoal mb-1.5">Annonce concernée (facultatif)</label>
            <select id="annonce_id" name="annonce_id" class="brand-input mb-3.5">
                <option value="">Aucune annonce</option>
                @if (auth()->user()->isAcheteur())
                    {{-- L'acheteur voit les annonces du vendeur (interlocuteur) --}}
                    @foreach (\App\Models\Annonce::where('user_id', $user->id)->valide()->orderBy('titre')->get() as $annonceOption)
                        <option value="{{ $annonceOption->id }}"
                                @selected($annonce && $annonce->id === $annonceOption->id || (string) old('annonce_id') === (string) $annonceOption->id || (string) request('annonce_id') === (string) $annonceOption->id)>
                            {{ $annonceOption->titre }}
                        </option>
                    @endforeach
                @else
                    {{-- Le vendeur voit ses propres annonces --}}
                    @foreach (auth()->user()->annonces()->valide()->orderBy('titre')->get() as $annonceOption)
                        <option value="{{ $annonceOption->id }}"
                                @selected($annonce && $annonce->id === $annonceOption->id || (string) old('annonce_id') === (string) $annonceOption->id || (string) request('annonce_id') === (string) $annonceOption->id)>
                            {{ $annonceOption->titre }}
                        </option>
                    @endforeach
                @endif
            </select>

            <textarea name="contenu" rows="3" required placeholder="Votre message…"
                      class="brand-input mb-3.5"></textarea>

            <button type="submit" class="brand-button">
                Envoyer
            </button>
        </form>
    </div>
@endsection
