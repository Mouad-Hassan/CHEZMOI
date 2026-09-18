<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="@yield('meta_description', 'ChezMoi — Achetez, vendez ou louez un bien immobilier en toute confiance. Des milliers d\'annonces vous attendent !')">

        <title>@yield('title', config('app.name', 'ChezMoi')) — ChezMoi</title>

        <!-- Google Fonts : Outfit -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Vite (CSS + JS compilés) -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @stack('head')
    </head>
    <body class="flex min-h-screen flex-col font-sans antialiased bg-white-soft text-charcoal">

        <!-- Navigation principale -->
        @include('layouts.navigation')

        <!-- Contenu de la page -->
        <main class="flex-1">
            @if (isset($header))
                <header class="bg-white border-b border-beige-100 shadow-sm">
                    <div class="mx-auto max-w-7xl px-4 py-5 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            @yield('content')
            {{ $slot ?? '' }}
        </main>

        <!-- Footer -->
        <footer class="mt-auto border-t border-beige-100 bg-white py-10">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col items-center justify-between gap-4 sm:flex-row">
                    <!-- Logo Footer -->
                    <a href="{{ route('home') }}" aria-label="ChezMoi — Accueil">
                        <img src="{{ asset('images/chezmoi-logo.png') }}" alt="ChezMoi" class="h-10 w-auto object-contain">
                    </a>
                    <p class="text-sm text-charcoal/50">
                        © {{ date('Y') }} ChezMoi — Aménagement & Rénovation. Tous droits réservés.
                    </p>
                    <div class="flex gap-5 text-sm text-charcoal/60">
                        <a href="{{ route('home') }}" class="hover:text-gold transition-colors">Accueil</a>
                        @if (Route::has('annonces.index'))
                            <a href="{{ route('annonces.index') }}" class="hover:text-gold transition-colors">Annonces</a>
                        @endif
                        @guest
                            <a href="{{ route('login') }}" class="hover:text-gold transition-colors">Connexion</a>
                        @endguest
                    </div>
                </div>
            </div>
        </footer>

        @stack('scripts')
    </body>
</html>
