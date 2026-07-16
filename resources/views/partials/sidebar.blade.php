{{--
  partials/sidebar.blade.php
  Utilisé par layouts/dashboard.blade.php.
  Seul "dashboard.index" pointe vers une route existante dans ce livrable ;
  les autres liens sont à brancher sur vos futurs contrôleurs (Candidatures,
  Traducteurs, Formations, Délégations, Rapports, Paramètres).
--}}
<aside class="dash-sidebar">
  <div class="dash-brand">
    <svg width="30" height="27" viewBox="0 0 38 34"><use href="#bulle-teranga" fill="var(--black)"/>
      <circle cx="10" cy="16" r="3" fill="var(--gold)"/><circle cx="18" cy="12" r="3" fill="var(--green)"/>
      <circle cx="26" cy="16" r="3" fill="var(--brick)"/><circle cx="14" cy="21" r="3" fill="var(--navy)"/><circle cx="22" cy="21" r="3" fill="var(--cream)"/>
    </svg>
    <span class="sigof">SIGOF Admin</span>
  </div>

  <nav class="dash-nav">
    <a href="{{ route('dashboard') }}" class="dash-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
      <span class="ic"></span>Vue d'ensemble
    </a>
    <a href="#" class="dash-link"><span class="ic"></span>Candidatures</a>
    <a href="#" class="dash-link"><span class="ic"></span>Traducteurs formés</a>
    <a href="#" class="dash-link"><span class="ic"></span>Formations COJO</a>
    <a href="#" class="dash-link"><span class="ic"></span>Délégations</a>
    <a href="#" class="dash-link"><span class="ic"></span>Rapports</a>
    <a href="#" class="dash-link"><span class="ic"></span>Paramètres</a>
  </nav>

  <div class="dash-foot">ONFP × COJO<br>SIGOF v1.0 - Dakar 2026</div>
</aside>
