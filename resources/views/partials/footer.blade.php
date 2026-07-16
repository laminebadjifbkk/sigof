{{-- partials/footer.blade.php — utilisé par layouts/app.blade.php (pas affiché sur le dashboard) --}}
<footer class="site" id="siteFooter">
  <div class="container">
    <div class="footer-grid">
      <div>
        <div class="footer-brand">
          <svg width="32" height="29" viewBox="0 0 38 34"><use href="#bulle-teranga" fill="var(--cream)"/>
            <circle cx="10" cy="16" r="3" fill="var(--gold)"/><circle cx="18" cy="12" r="3" fill="var(--green)"/>
            <circle cx="26" cy="16" r="3" fill="var(--brick)"/><circle cx="14" cy="21" r="3" fill="var(--navy)"/><circle cx="22" cy="21" r="3" fill="var(--black)"/>
          </svg>
          <span class="sigof" style="color:var(--cream);">SIGOF</span>
        </div>
        <p style="margin-top:14px;">Plateforme du partenariat ONFP × COJO pour la formation et la mobilisation des traducteurs officiels des Jeux Olympiques de la Jeunesse Dakar 2026.</p>
      </div>
      <div>
        <h5>Programme</h5>
        <ul>
          <li><a href="{{ route('ylphome') }}">Présentation</a></li>
          <li><a href="{{ route('inscription') }}">S'inscrire</a></li>
          <li><a href="{{ route('connexion') }}">Espace candidat</a></li>
          <li><a href="{{ route('dashboard') }}">Espace admin</a></li>
        </ul>
      </div>
      <div>
        <h5>Partenaires</h5>
        <ul>
          <li>ONFP</li>
          <li>COJO - YOGOC Dakar 2026</li>
          <li>Ministère de la Jeunesse</li>
        </ul>
      </div>
      <div>
        <h5>Contact</h5>
        <ul>
          <li>contact@contact.sn</li>
          <li>+221 33 800 00 00</li>
          <li>Dakar, Sénégal</li>
        </ul>
      </div>
    </div>
    <div class="footer-bottom">
      <span class="motto">Afrig Dalal, Ndakaaru Jëmël — L'Afrique accueille, Dakar célèbre.</span>
      <span class="motto">© {{ date('Y') }} ONFP × COJO — SIGOF.</span>
    </div>
  </div>
</footer>
