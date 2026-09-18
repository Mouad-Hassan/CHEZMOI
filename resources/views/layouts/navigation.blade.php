
<nav id="main-nav"
     class="sticky top-0 z-50 relative bg-white border-b border-beige-100 transition-shadow duration-300 shadow-sm">

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between gap-4">

            <!-- ===== Liens gauche (desktop) ===== -->
            <div class="hidden sm:flex sm:items-center sm:gap-7">

                <a href="{{ route('home') }}"
                   class="text-sm font-semibold transition-colors duration-200
                          {{ request()->routeIs('home') ? 'text-gold border-b-2 border-gold pb-0.5' : 'text-charcoal hover:text-gold' }}">
                    Accueil
                </a>

                {{-- Annonces : visible pour tous sauf admin --}}
                @if (Route::has('annonces.index') && (!auth()->check() || Auth::user()->role !== 'admin'))
                    <a href="{{ route('annonces.index') }}"
                       class="text-sm font-semibold transition-colors duration-200
                              {{ request()->routeIs('annonces.index') ? 'text-gold border-b-2 border-gold pb-0.5' : 'text-charcoal hover:text-gold' }}">
                        Annonces
                    </a>
                @endif

                @auth
                    <a href="{{ route('dashboard') }}"
                       class="text-sm font-semibold transition-colors duration-200
                              {{ request()->routeIs('dashboard') ? 'text-gold border-b-2 border-gold pb-0.5' : 'text-charcoal hover:text-gold' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('messages.index') }}"
                       class="text-sm font-semibold transition-colors duration-200
                              {{ request()->routeIs('messages.index') ? 'text-gold border-b-2 border-gold pb-0.5' : 'text-charcoal hover:text-gold' }}">
                        Messages
                    </a>
                @endauth

            </div>


            <!-- ===== Logo centré ===== -->
            <a href="{{ route('home') }}"
               class="absolute left-1/2 top-1/2 z-10 -translate-x-1/2 -translate-y-1/2"
               id="nav-logo"
               aria-label="ChezMoi — Accueil">

                <img src="{{ asset('images/chezmoi-logo.png') }}"
                     alt="Logo ChezMoi"
                     class="block h-14 max-w-[220px] w-auto object-contain">

            </a>


            <!-- ===== Actions droite (desktop) ===== -->
            <div class="hidden sm:flex sm:items-center sm:gap-3">

                @auth

                    <!-- Utilisateur connecté — Dropdown -->
                    <div class="relative">

                        <button id="user-menu-button"
                                class="flex items-center gap-2 rounded-xl border border-beige-200 bg-beige-50 px-3.5 py-2 text-sm font-semibold text-charcoal transition hover:border-gold hover:bg-beige-100">

                            <span class="flex h-6 w-6 items-center justify-center rounded-full bg-gold text-xs font-bold text-charcoal">
                                {{ strtoupper(substr(Auth::user()->nom, 0, 1)) }}
                            </span>

                            <span class="max-w-[120px] truncate">
                                {{ Auth::user()->nom }}
                            </span>

                            <svg id="dropdown-arrow"
                                 class="h-4 w-4 text-charcoal/60 transition-transform duration-200"
                                 fill="none"
                                 stroke="currentColor"
                                 viewBox="0 0 24 24">

                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M19 9l-7 7-7-7"/>

                            </svg>

                        </button>


                        <!-- Dropdown -->
                        <div id="user-dropdown"
                             class="absolute right-0 mt-2 w-52 origin-top-right rounded-2xl border border-beige-100 bg-white py-1.5 shadow-xl hidden transition-all duration-200">


                            <!-- Mon profil -->
                            <a href="{{ route('profile.edit') }}"
                               class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-charcoal hover:bg-beige-50 transition-colors">

                                <svg class="h-4 w-4 text-charcoal/50"
                                     fill="none"
                                     stroke="currentColor"
                                     viewBox="0 0 24 24">

                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>

                                </svg>

                                Mon profil
                            </a>


                            {{-- Mes annonces : pour proprietaire seulement --}}
                            @if (Route::has('annonces.mes-annonces') && Auth::user()->isProprietaire())

                                <a href="{{ route('annonces.mes-annonces') }}"
                                   class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-charcoal hover:bg-beige-50 transition-colors">

                                    <svg class="h-4 w-4 text-charcoal/50"
                                         fill="none"
                                         stroke="currentColor"
                                         viewBox="0 0 24 24">

                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              stroke-width="2"
                                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>

                                    </svg>

                                    Mes annonces
                                </a>

                            @endif


                            {{-- Mes favoris : caché pour admin --}}
                            @if (Route::has('favoris.index') && Auth::user()->role !== 'admin')

                                <a href="{{ route('favoris.index') }}"
                                   class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-charcoal hover:bg-beige-50 transition-colors">

                                    <svg class="h-4 w-4 text-charcoal/50"
                                         fill="none"
                                         stroke="currentColor"
                                         viewBox="0 0 24 24">

                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              stroke-width="2"
                                              d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>

                                    </svg>

                                    Mes favoris
                                </a>

                            @endif

                            {{-- Messages : pour tous les utilisateurs connectés --}}
                            <a href="{{ route('messages.index') }}"
                               class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-charcoal hover:bg-beige-50 transition-colors">

                                <svg class="h-4 w-4 text-charcoal/50"
                                     fill="none"
                                     stroke="currentColor"
                                     viewBox="0 0 24 24">

                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>

                                </svg>

                                Messages
                            </a>


                            <div class="my-1.5 border-t border-beige-100"></div>


                            <!-- Déconnexion -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <button type="submit"
                                        class="flex w-full items-center gap-2.5 px-4 py-2.5 text-sm text-red-500 hover:bg-red-50 transition-colors">

                                    <svg class="h-4 w-4"
                                         fill="none"
                                         stroke="currentColor"
                                         viewBox="0 0 24 24">

                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              stroke-width="2"
                                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>

                                    </svg>

                                    Se déconnecter

                                </button>

                            </form>

                        </div>

                    </div>

                @else

                    <!-- Visiteur non connecté -->

                    <a href="{{ route('login') }}"
                       class="text-sm font-semibold text-charcoal transition hover:text-gold"
                       id="nav-login">

                        Se connecter

                    </a>


                    @if (Route::has('register'))

                        <a href="{{ route('register') }}"
                           class="rounded-xl bg-charcoal px-5 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-charcoal-dark hover:shadow-md"
                           id="nav-register">

                            S'inscrire

                        </a>

                    @endif

                @endauth

            </div>


            <!-- ===== Hamburger mobile ===== -->
            <button id="nav-hamburger"
                    class="sm:hidden inline-flex items-center justify-center rounded-lg p-2 text-charcoal hover:bg-beige-100 focus:outline-none transition">

                <svg id="hamburger-icon"
                     class="h-6 w-6"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M4 6h16M4 12h16M4 18h-16"/>

                </svg>

                <svg id="close-icon"
                     class="h-6 w-6 hidden"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M6 18L18 6M6 6l12-12"/>

                </svg>

            </button>

        </div>
    </div>


    <!-- ===== Menu mobile ===== -->
    <div id="mobile-menu"
         class="sm:hidden border-t border-beige-100 bg-white px-4 py-4 space-y-1 hidden transition-all duration-200">


        <!-- Accueil -->
        <a href="{{ route('home') }}"
           class="flex items-center rounded-xl px-4 py-3 text-sm font-semibold transition
                  {{ request()->routeIs('home') ? 'bg-beige-100 text-gold' : 'text-charcoal hover:bg-beige-50 hover:text-gold' }}">

            Accueil

        </a>


        {{-- Annonces mobile : visible pour tous sauf admin --}}
        @if (Route::has('annonces.index') && (!auth()->check() || Auth::user()->role !== 'admin'))

            <a href="{{ route('annonces.index') }}"
               class="flex items-center rounded-xl px-4 py-3 text-sm font-semibold transition
                      {{ request()->routeIs('annonces.index') ? 'bg-beige-100 text-gold' : 'text-charcoal hover:bg-beige-50 hover:text-gold' }}">

                Annonces

            </a>

        @endif


        @auth

            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}"
               class="flex items-center rounded-xl px-4 py-3 text-sm font-semibold transition text-charcoal hover:bg-beige-50 hover:text-gold">

                Dashboard

            </a>

            <!-- Messages -->
            <a href="{{ route('messages.index') }}"
               class="flex items-center rounded-xl px-4 py-3 text-sm font-semibold transition text-charcoal hover:bg-beige-50 hover:text-gold">

                Messages

            </a>

            <!-- Mon profil -->
            <a href="{{ route('profile.edit') }}"
               class="flex items-center rounded-xl px-4 py-3 text-sm font-semibold transition text-charcoal hover:bg-beige-50 hover:text-gold">

                Mon profil

            </a>

            {{-- Mes annonces mobile : pour proprietaire seulement --}}
            @if (Auth::user()->isProprietaire())

                <a href="{{ route('annonces.mes-annonces') }}"
                   class="flex items-center rounded-xl px-4 py-3 text-sm font-semibold transition text-charcoal hover:bg-beige-50 hover:text-gold">

                    Mes annonces

                </a>

            @endif


            <div class="my-2 border-t border-beige-100"></div>


            <!-- Déconnexion -->
            <form method="POST" action="{{ route('logout') }}">

                @csrf

                <button type="submit"
                        class="flex w-full items-center rounded-xl px-4 py-3 text-sm font-semibold text-red-500 hover:bg-red-50 transition">

                    Se déconnecter

                </button>

            </form>

        @else

            <div class="my-2 border-t border-beige-100"></div>


            <!-- Connexion -->
            <a href="{{ route('login') }}"
               class="flex items-center rounded-xl px-4 py-3 text-sm font-semibold text-charcoal hover:bg-beige-50 hover:text-gold transition">

                Se connecter

            </a>


            @if (Route::has('register'))

                <a href="{{ route('register') }}"
                   class="flex items-center justify-center rounded-xl bg-charcoal px-4 py-3 text-sm font-semibold text-white hover:bg-charcoal-dark transition">

                    S'inscrire

                </a>

            @endif

        @endauth

    </div>

</nav>

