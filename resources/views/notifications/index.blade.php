@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <section class="relative overflow-hidden rounded-3xl bg-charcoal px-7 py-8 text-white shadow-xl sm:px-10 mb-8">
            <div class="absolute -right-12 -top-16 h-56 w-56 rounded-full bg-gold/15"></div>
            <div class="relative flex flex-col justify-between gap-6 lg:flex-row lg:items-end">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[.2em] text-gold">Vos notifications</p>
                    <h1 class="mt-3 text-3xl font-extrabold sm:text-4xl">Notifications</h1>
                    <p class="mt-2 text-white/70">Restez informé de l'activité sur votre compte.</p>
                </div>
                @if ($notifications->isNotEmpty())
                    <form method="POST" action="{{ route('notifications.tout-lu') }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="rounded-xl border border-white/20 bg-white/10 px-5 py-2.5 text-sm font-bold hover:bg-white/20">
                            Tout marquer comme lu
                        </button>
                    </form>
                @endif
            </div>
        </section>

        @if ($notifications->isEmpty())
            <div class="rounded-2xl border border-dashed border-beige-200 bg-white p-10 text-center text-charcoal/50 brand-card">
                Aucune notification.
            </div>
        @else
            <div class="brand-card divide-y divide-beige-100">
                @foreach ($notifications as $notification)
                    <div class="px-5 py-4 flex items-start justify-between gap-4 {{ $notification->lu ? '' : 'bg-beige-100/40' }}">
                        <div>
                            <p class="text-sm text-charcoal">{{ $notification->contenu }}</p>
                            <p class="text-xs text-charcoal/50 mt-1">
                                {{ $notification->date_creation?->format('d/m/Y H:i') }} — {{ $notification->type }}
                            </p>
                        </div>

                        @unless ($notification->lu)
                            <form method="POST" action="{{ route('notifications.lu', $notification->id) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="link-gold text-xs hover:underline whitespace-nowrap">
                                    Marquer comme lu
                                </button>
                            </form>
                        @endunless
                    </div>
                @endforeach
            </div>

            <div class="mt-6">{{ $notifications->links() }}</div>
        @endif
    </div>
@endsection
