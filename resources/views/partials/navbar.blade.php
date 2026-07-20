{{--
  partials/navbar.blade.php
  Utilisé par layouts/app.blade.php et layouts/dashboard.blade.php.
  Comportement mobile demandé : sous 980px, seul le bouton "Se connecter"
  reste visible dans la barre d'actions (voir .nav-cta-register dans sigof.css).
  Le lien "Inscription" du menu burger reste disponible pour s'inscrire.
  Quand l'utilisateur est authentifié (@auth), les CTA connexion/inscription
  sont remplacés par un avatar (initiales prénom+nom) qui ouvre un modal
  contenant les infos du compte et le bouton "Se déconnecter".
--}}
<header class="site">
    <div class="header-inner">
        {{-- <div class="brand-lockup">
            <span class="bulle-mark">
                <svg width="38" height="34" viewBox="0 0 38 34">
                    <use href="#bulle-teranga" />
                    <circle cx="10" cy="16" r="3" fill="var(--gold)" />
                    <circle cx="18" cy="12" r="3" fill="var(--green)" />
                    <circle cx="26" cy="16" r="3" fill="var(--brick)" />
                    <circle cx="14" cy="21" r="3" fill="var(--navy)" />
                    <circle cx="22" cy="21" r="3" fill="var(--black)" />
                </svg>
            </span>
            <div class="brand-text">
                <a href="{{ route('dashboard') }}">
                    <span class="sigof">ONFP YLP</span>
                </a>
            </div>
            @guest
            <div class="brand-divider"></div>
            <div class="dakar-chip">
                <span class="bulle-mark"><svg width="16" height="14" viewBox="0 0 38 34">
                        <use href="#bulle-teranga" fill="var(--cream)" />
                    </svg></span>
                JOJ DAKAR 2026
            </div>
            @endguest
        </div> --}}

        <div class="brand-lockup">
            <span class="bulle-mark">
                <img src="{{ asset('images/logo-ylp.png') }}" alt="Dakar 2026 - Youth Linguists Programme"
                    class="brand-logo">
            </span>
            <div class="brand-text">
                <a href="{{ route('ylphome') }}">
                    <span class="sigof">SIGOF</span>
                </a>
            </div>
            <div class="brand-divider"></div>
            {{--  @guest
                <div class="brand-divider"></div>
                <div class="dakar-chip">
                    <span class="bulle-mark"><svg width="16" height="14" viewBox="0 0 38 34">
                            <use href="#bulle-teranga" fill="var(--cream)" />
                        </svg></span>
                    YLP
                </div>
            @endguest --}}
        </div>

        <nav class="main-nav" id="mainNav">
            @guest
                <a href="{{ route('ylphome') }}"
                    class="nav-link {{ request()->routeIs('ylphome') ? 'active' : '' }}">Accueil</a>
                <a href="{{ route('connexion') }}"
                    class="nav-link {{ request()->routeIs('connexion') ? 'active' : '' }}">Connexion</a>
                <a href="{{ route('inscription') }}"
                    class="nav-link {{ request()->routeIs('inscription') ? 'active' : '' }}">Inscription</a>
                <a href="{{ route('dashboard') }}"
                    class="nav-link {{ request()->routeIs('dashboard*') ? 'active' : '' }}">Espace admin</a>
            @endguest
        </nav>

        <div class="nav-actions">
            @guest
                <a href="{{ route('connexion') }}" class="btn btn-ghost btn-sm nav-cta-login">Se connecter</a>
                <a href="{{ route('inscription') }}" class="btn btn-primary btn-sm nav-cta-register">Je m'inscris</a>
            @else
                <button type="button" class="avatar-bubble" data-bs-toggle="modal" data-bs-target="#userModal"
                    title="{{ trim((Auth::user()->civilite ?? '') . ' ' . (Auth::user()->firstname ?? '') . ' ' . (Auth::user()->name ?? '')) }}">
                    {{ Auth::check() ? Str::upper(Str::substr(Auth::user()->firstname, 0, 1)) . '' . Str::upper(Str::substr(Auth::user()->name, 0, 1)) : 'FN' }}
                </button>
            @endguest

            @guest
                <button class="menu-toggle" id="menuToggle" aria-label="Ouvrir le menu" aria-expanded="false"
                    aria-controls="mainNav">
                    <span></span><span></span><span></span>
                </button>
            @else
                <button class="menu-toggle" id="sidebarToggle" aria-label="Ouvrir le menu latéral" aria-expanded="false"
                    aria-controls="dashSidebar">
                    <span></span><span></span><span></span>
                </button>
            @endguest
        </div>
    </div>
</header>
