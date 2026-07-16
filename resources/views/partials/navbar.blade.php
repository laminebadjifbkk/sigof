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
        <div class="brand-lockup">
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
                <span class="sigof">SIGOF</span>
                <span class="sub">ONFP — Traducteurs officiels</span>
            </div>
            <div class="brand-divider"></div>
            <div class="dakar-chip">
                <span class="bulle-mark"><svg width="16" height="14" viewBox="0 0 38 34">
                        <use href="#bulle-teranga" fill="var(--cream)" />
                    </svg></span>
                DAKAR 2026 · JOJ
            </div>
        </div>

        <nav class="main-nav" id="mainNav">
            <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Accueil</a>
            @guest
            <a href="{{ route('connexion') }}" class="nav-link {{ request()->routeIs('connexion') ? 'active' : '' }}">Connexion</a>
            <a href="{{ route('inscription') }}" class="nav-link {{ request()->routeIs('inscription') ? 'active' : '' }}">Inscription</a>
            @endguest
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard*') ? 'active' : '' }}">Espace admin</a>
        </nav>

        <div class="nav-actions">
            @guest
            {{-- CTA connexion : toujours visible, y compris en mobile --}}
            <a href="{{ route('connexion') }}" class="btn btn-ghost btn-sm nav-cta-login">Se connecter</a>
            {{-- CTA inscription : masqué en mobile (règle .nav-cta-register dans sigof.css) --}}
            <a href="{{ route('inscription') }}" class="btn btn-primary btn-sm nav-cta-register">Je m'inscris</a>
            @else
            <!--  <button type="button" class="avatar-bubble" id="userMenuBtn" aria-haspopup="dialog" aria-controls="userModal" aria-expanded="false" title="{{ trim((Auth::user()->civilite ?? '').' '.(Auth::user()->firstname ?? '').' '.(Auth::user()->name ?? '')) }}">
                {{ Auth::check() ? Str::upper(Str::substr(Auth::user()->firstname, 0, 1)).''.Str::upper(Str::substr(Auth::user()->name, 0, 1)) : 'FN' }}
            </button> -->
            <button type="button" class="avatar-bubble" data-bs-toggle="modal" data-bs-target="#userModal" title="{{ trim((Auth::user()->civilite ?? '').' '.(Auth::user()->firstname ?? '').' '.(Auth::user()->name ?? '')) }}">
                {{ Auth::check() ? Str::upper(Str::substr(Auth::user()->firstname, 0, 1)).''.Str::upper(Str::substr(Auth::user()->name, 0, 1)) : 'FN' }}
            </button>
            @endguest
            <button class="menu-toggle" id="menuToggle" aria-label="Ouvrir le menu" aria-expanded="false" aria-controls="mainNav">
                <span></span><span></span><span></span>
            </button>
        </div>
    </div>
</header>