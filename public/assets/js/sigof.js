/**
 * SIGOF x Dakar 2026 — comportements JS partagés
 * Chargé sur toutes les pages (public/js/sigof.js).
 * Chaque bloc vérifie la présence de ses éléments avant de s'exécuter :
 * un seul fichier peut donc être inclus partout sans erreur.
 */

document.addEventListener('DOMContentLoaded', () => {

  /* ---------- Menu mobile (navbar) ---------- */
  const menuToggle = document.getElementById('menuToggle');
  const mainNav = document.getElementById('mainNav');
  if (menuToggle && mainNav) {
    menuToggle.addEventListener('click', () => {
      mainNav.classList.toggle('open');
      const isOpen = mainNav.classList.contains('open');
      menuToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    });
  }

  /* ---------- Étape d'inscription (stepper) ---------- */
  const stepItems = document.querySelectorAll('.step-item');
  const regSteps = document.querySelectorAll('.reg-step');
  const nextBtn = document.getElementById('regNext');
  const backBtn = document.getElementById('regBack');

  if (nextBtn && backBtn && regSteps.length) {
    let currentStep = 1;
    const totalSteps = regSteps.length;

    const renderStep = () => {
      stepItems.forEach(item => {
        const n = parseInt(item.dataset.step, 10);
        item.classList.toggle('current', n === currentStep);
        item.classList.toggle('done', n < currentStep);
      });
      regSteps.forEach(s => s.classList.toggle('active', parseInt(s.dataset.step, 10) === currentStep));
      backBtn.style.visibility = currentStep === 1 ? 'hidden' : 'visible';
      nextBtn.textContent = currentStep === totalSteps ? "Envoyer ma candidature" : "Étape suivante";
    };

    nextBtn.addEventListener('click', () => {
      if (currentStep < totalSteps) {
        currentStep++;
        renderStep();
      } else {
        // Dernière étape : ici, on soumettrait le formulaire au contrôleur Laravel
        // (ex : document.getElementById('registerForm').submit();)
        nextBtn.textContent = 'Candidature envoyée ✓';
        nextBtn.disabled = true;
      }
    });

    backBtn.addEventListener('click', () => {
      if (currentStep > 1) {
        currentStep--;
        renderStep();
      }
    });

    renderStep();
  }

  /* ---------- Sidebar dashboard : état actif ---------- */
  document.querySelectorAll('.dash-link').forEach(link => {
    link.addEventListener('click', (e) => {
      // Laisser la navigation Laravel s'effectuer normalement (href réel).
      // On met juste à jour l'état visuel avant le changement de page.
      document.querySelectorAll('.dash-link').forEach(l => l.classList.remove('active'));
      link.classList.add('active');
    });
  });

  /* ---------- Recherche live dans le tableau du dashboard ---------- */
  const tableSearch = document.getElementById('tableSearch');
  const dataTable = document.getElementById('dataTable');
  if (tableSearch && dataTable) {
    tableSearch.addEventListener('input', (e) => {
      const q = e.target.value.toLowerCase();
      dataTable.querySelectorAll('tbody tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
      });
    });
  }

});
