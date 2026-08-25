{{-- <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script type="text/javascript">
        function callbackThen(response) {
            // read Promise object
            response.json().then(function(data) {
                console.log(data);
                if (data.success && data.score > 0.5) {
                    console.log('valid recpatcha');
                } else {
                    document.getElementById('registerForm').addEventListener('submit', function(event) {
                        event.preventDefault();
                        alert('recpatcha error');
                    });
                }
            });
        }

        function callbackCatch(error) {
            console.error('Error:', error)
        }
    </script>

    {!! htmlScriptTagJsApi([
    'callback_then' => 'callbackThen',
    'callback_catch' => 'callbackCatch',
    ]) !!}
    <title>{{ config('app.name', 'ONFP') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @if (isset($header))
        <header class="bg-white dark:bg-gray-800 shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
        @endif

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>
    @include('sweetalert::alert')
</body>

</html>
--}}

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIGOF') · Dakar 2026 - Programme des traducteurs officiels</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link
        href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400..800&family=Inter:wght@400;500;600;700;800&family=IBM+Plex+Mono:wght@500;600&display=swap"
        rel="stylesheet">

    {{-- En Blade "classique" (asset()). Si vous utilisez Vite, remplacez par :
       @vite(['resources/css/sigof.css', 'resources/js/sigof.js']) --}}
    <link rel="stylesheet" href="{{ asset('assets/css/sigof.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
    <style>
        .field-error,
        .recap-block .field-error {
            color: #c0392b !important;
            display: block;
            font-size: 12.5px;
            margin-top: 4px;
        }

        .confirmation-card {
            max-width: 560px;
            margin: 40px auto;
            background: #fff;
            border-radius: 12px;
            padding: 40px 32px;
            text-align: center;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
        }

        .confirmation-icon {
            display: flex;
            justify-content: center;
            margin-bottom: 16px;
        }

        .confirmation-ref {
            margin: 24px 0;
            padding: 16px;
            background: var(--gray-50, #f7f7f7);
            border-radius: 8px;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .ref-label {
            font-size: 12.5px;
            color: var(--gray-700);
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        .ref-value {
            font-size: 22px;
            font-weight: 700;
            letter-spacing: 0.05em;
        }

        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 999px;
            font-size: 12.5px;
            font-weight: 600;
        }

        .badge-pending {
            background: #fff8e1;
            color: #8a6d00;
        }

        @media print {
            body * {
                visibility: hidden;
            }

            #printable-receipt,
            #printable-receipt * {
                visibility: visible;
            }

            #printable-receipt {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                margin: 0;
                box-shadow: none;
                border: none;
            }

            .no-print {
                display: none !important;
            }

            /* Optionnel : cache aussi le header/footer/nav de votre layouts.app si présents */
            header,
            footer,
            nav,
            .navbar {
                display: none !important;
            }
        }

        /* ==========================================================================
   Alerts (messages flash + erreurs de validation)
   ========================================================================== */

        .alert {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 14px 16px;
            margin-bottom: 20px;
            border-radius: 8px;
        }

        .alert-success {
            background: #e8f5e9;
            border: 1px solid #a5d6a7;
            color: #2e7d32;
        }

        .alert-danger {
            background: #fdecea;
            border: 1px solid #f5c2c0;
            color: #842029;
        }

        .alert-icon-bg {
            fill: currentColor;
        }

        /* Variante empilée : titre + liste d'erreurs (ex. $errors->all()) */
        .alert-list {
            display: block;
        }

        .alert-list strong {
            display: block;
            margin-bottom: 8px;
        }

        .alert-list ul {
            margin: 0 0 0 20px;
            padding: 0;
        }

        .alert-list li {
            margin-bottom: 2px;
        }

        .civility-toggle {
            display: inline-flex;
            border: 1px solid #d9dde3;
            border-radius: 8px;
            overflow: hidden;
            width: fit-content;
        }

        .civility-toggle input[type="radio"] {
            display: none;
        }

        .civility-option {
            padding: 8px 24px;
            cursor: pointer;
            font-size: 14px;
            color: #555;
            background: #fff;
            transition: all 0.15s ease;
            margin: 0;
        }

        .civility-toggle input[type="radio"]:checked+.civility-option {
            background: #2563eb;
            color: #fff;
            font-weight: 500;
        }

        .civility-option:hover {
            background: #f3f4f6;
        }

        .civility-toggle input[type="radio"]:checked+.civility-option:hover {
            background: #2563eb;
        }

        .recap-wrapper {
            display: flex;
            flex-direction: column;
            gap: 16px;
            margin-top: 12px;
        }

        .recap-card {
            background: #fff;
            border: 1px solid #e6e4de;
            border-radius: 12px;
            padding: 18px 20px;
        }

        .recap-card-header {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 14px;
            padding-bottom: 10px;
            border-bottom: 1px solid #f0efe9;
        }

        .recap-icon {
            font-size: 16px;
        }

        .recap-card-header h4 {
            margin: 0;
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 0.02em;
            text-transform: uppercase;
            color: #2b2b28;
        }

        .recap-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 14px 20px;
        }

        .recap-item {
            display: flex;
            flex-direction: column;
            gap: 3px;
        }

        .recap-item-full {
            grid-column: 1 / -1;
        }

        .recap-label {
            font-size: 11.5px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            color: #9a978d;
        }

        .recap-value {
            font-size: 14.5px;
            color: #23221f;
            font-weight: 500;
        }

        .recap-value:empty::after {
            content: '—';
            color: #c4c1b8;
            font-weight: 400;
        }

        .recap-doc-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .recap-doc {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 12px;
            background: #faf9f6;
            border: 1px solid #eeece5;
            border-radius: 8px;
        }

        .recap-doc-name {
            font-size: 13px;
            font-weight: 600;
            color: #4a4842;
        }

        .recap-doc-file {
            font-size: 13px;
            color: #706e66;
            text-align: right;
        }

        @media (max-width: 600px) {
            .recap-grid {
                grid-template-columns: 1fr;
            }

            .recap-doc {
                flex-direction: column;
                align-items: flex-start;
                gap: 4px;
            }
        }

        .brand-logo {
            height: 54px;
            width: auto;
            display: block;
        }

        .brand-logo-onfp {
            height: 50px;
            width: auto;
            display: block;
        }

        .partners-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 50px;
        }

        .partner-item {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .partner-logo {
            height: 38px;
            max-width: 160px;
            width: auto;
            object-fit: contain;
            filter: grayscale(100%);
            opacity: .65;
            transition: filter .2s ease, opacity .2s ease, transform .15s ease;
        }

        .partner-item:hover .partner-logo {
            filter: grayscale(0%);
            opacity: 1;
            transform: translateY(-2px);
        }

        /* Nouveau style pour la page home */
        /*.hero {
            background:
                linear-gradient(180deg, rgba(212, 241, 249, 0.7) 0%, rgba(212, 241, 249, 0.55) 100%),
                url('{{ asset('assets/img/ylp-campaign-photo.jpg') }}') right bottom / 68% auto no-repeat;
            background-color: #D4F1F9;
        }*/

        .hero {
            background:
                linear-gradient(180deg, rgba(212, 241, 249, 0.7) 0%, rgba(212, 241, 249, 0.55) 100%),
                url('{{ asset('assets/img/ylp-campaign-photo.jpg') }}') right bottom / 68% auto no-repeat;
            background-color: #D4F1F9;
        }

        @media (max-width: 768px) {
            .hero {
                background:
                    linear-gradient(180deg, rgba(212, 241, 249, 0.7) 0%, rgba(212, 241, 249, 0.55) 100%),
                    url('{{ asset('assets/img/ylp-campaign-photo.jpg') }}') center top / 75% auto no-repeat;
                background-color: #D4F1F9;
            }
        }

        /* Le texte du hero était pensé pour un fond sombre : on repasse en couleurs
           sombres pour rester lisible sur le nouveau fond clair. !important car sigof.css
           applique déjà une couleur claire à ces éléments avec une spécificité plus forte. */
        .hero .eyebrow {
            color: var(--green) !important;
        }

        .hero .eyebrow em {
            color: var(--green) !important;
            font-style: normal;
        }

        .hero h1 {
            color: #131416 !important;
        }

        .hero h1 em {
            color: var(--green) !important;
            font-style: italic;
        }

        .hero .lead {
            color: #23221f !important;
            opacity: 1 !important;
        }

        .hero .countdown-caption {
            color: #ff0000 !important;
        }

        .hero .btn-ghost {
            color: #131416 !important;
            border-color: #131416 !important;
        }

        .hero .btn-ghost:hover {
            background: rgba(19, 20, 22, 0.06) !important;
        }

        .hero .cd-num {
            color: var(--green) !important;
        }

        .stat-strip {
            background: var(--green) !important;
            color: #fff !important;
        }

        .hero .btn-primary {
            background: var(--green) !important;
            border-color: var(--green) !important;
            color: #fff !important;
        }

        .hero-bubble-card {
            background: #fff;
            box-shadow: 0 8px 28px rgba(19, 20, 22, 0.12);
        }

        .hero-bubble-card .quote,
        .hero-bubble-card .who {
            color: #131416;
        }

        /* Nouveau footer */
        #siteFooter {
            background: var(--green) !important;
            color: #fff !important;
        }

        #siteFooter p,
        #siteFooter li,
        #siteFooter .motto {
            color: rgba(255, 255, 255, 0.85) !important;
        }

        #siteFooter h5 {
            color: #fff !important;
        }

        #siteFooter a {
            color: #fff !important;
        }

        #siteFooter a:hover {
            color: var(--gold) !important;
        }

        #siteFooter .brand-divider {
            background: rgba(255, 255, 255, 0.25) !important;
        }

        #siteFooter .footer-bottom {
            border-top-color: rgba(255, 255, 255, 0.2) !important;
        }

        /* Page de connexion et de changement de mot de passe */
        .auth-side {
            background: linear-gradient(180deg, #D4F1F9 0%, #E8F8FC 100%);
            position: relative;
            overflow: hidden;
        }

        .auth-side .ylp-brandbar {
            display: flex;
            align-items: center;
            gap: 18px;
            margin-bottom: 34px;
            flex-wrap: wrap;
        }

        .auth-side .ylp-brandbar img {
            height: 40px;
            width: auto;
            display: block;
        }

        .auth-side .ylp-brandbar .divider {
            width: 1px;
            height: 28px;
            background: rgba(19, 20, 22, 0.15);
        }

        .auth-side .eyebrow {
            color: var(--green);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            font-size: 12.5px;
        }

        .auth-side h2 {
            font-family: 'Bricolage Grotesque', sans-serif;
            font-weight: 800;
            color: #131416;
            line-height: 1.15;
        }

        .auth-side h2 em {
            color: var(--green);
            font-style: italic;
        }

        .auth-side .ylp-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 22px;
            padding: 8px 14px;
            background: #fff;
            border-radius: 999px;
            font-size: 12.5px;
            font-weight: 700;
            color: #131416;
            box-shadow: 0 2px 8px rgba(19, 20, 22, 0.08);
        }

        .auth-side .ylp-badge .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--green);
        }

        .auth-side .rings-deco {
            opacity: 0.9;
        }

        .auth-form-col .auth-card {
            border-top: 4px solid var(--green);
        }

        .auth-card .btn-primary {
            background: var(--green);
            border-color: var(--green);
        }

        .auth-card .link-accent {
            color: var(--green);
        }

        .cd-item {
            background: rgba(255, 255, 255, 0.06);
            border: 2px solid rgba(255, 255, 255, 0.28);
            border-radius: 16px 16px 16px 4px;
            padding: 10px 14px;
            min-width: 60px;
            text-align: center;
        }

        .cd-num {
            display: block;
            font-family: var(--font-mono);
            font-size: 26px;
            font-weight: 600;
            color: var(--gold);
            line-height: 1;
            font-variant-numeric: tabular-nums;
        }

        .cd-label {
            display: block;
            font-family: var(--font-mono);
            font-size: 9.5px;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: var(--gray-500);
            margin-top: 5px;
        }

        .lang-card {
            border: 2px solid var(--green);
            border-radius: 20px 20px 20px 6px;
            padding: 16px;
            background: var(--cream);
            transition: transform .15s ease, box-shadow .15s ease;
        }

        .lang-card:hover {
            transform: translateY(-3px);
            box-shadow: 6px 6px 0 var(--green);
        }


        .prog-card {
            border: 2px solid var(--green);
            border-radius: 26px 26px 26px 6px;
            padding: 26px;
            background: var(--cream);
            transition: transform .15s ease, box-shadow .15s ease;
        }

        .prog-card:hover {
            transform: translateY(-4px);
            box-shadow: 8px 8px 0 var(--green);
        }

        .hero-visual {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: left;
            top: 4cm;
        }

        @media (max-width: 980px) {
            .hero .container>div:first-child {
                margin-top: 3cm;
            }
        }

        .required-marker {
            color: var(--danger, red);
            margin-left: 0.5px;
        }

        /* --- Vérification du statut de candidature --- */
        .status-check-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(19, 20, 22, 0.55);
            z-index: 200;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .status-check-overlay.open {
            display: flex;
        }

        .status-check-card {
            background: #fff;
            width: 100%;
            max-width: 420px;
            border-radius: 24px 24px 24px 6px;
            border-top: 4px solid var(--green);
            padding: 32px 28px;
            position: relative;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.25);
        }

        .status-check-card .close-btn {
            position: absolute;
            top: 14px;
            right: 14px;
            width: 30px;
            height: 30px;
            border-radius: 10px 10px 10px 3px;
            border: 2px solid var(--black);
            background: #fff;
            font-size: 14px;
            line-height: 1;
            cursor: pointer;
        }

        .status-check-card h3 {
            font-size: 21px;
            margin-bottom: 6px;
        }

        .status-check-card .muted {
            color: var(--gray-700);
            font-size: 13.5px;
            margin-bottom: 22px;
        }

        .status-check-card .field {
            margin-bottom: 16px;
        }

        .status-check-card .field label {
            display: block;
            font-family: var(--font-mono);
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .05em;
            margin-bottom: 6px;
            color: var(--gray-700);
        }

        .status-check-card .field input {
            width: 100%;
            border: 2px solid var(--black);
            border-radius: 12px 12px 12px 4px;
            padding: 11px 13px;
            font-family: var(--font-body);
            font-size: 14px;
        }

        .status-check-result {
            margin-top: 18px;
            padding: 14px 16px;
            border-radius: 12px;
            font-size: 13.5px;
            display: none;
        }

        .status-check-result.show {
            display: block;
        }

        .status-check-result.ok {
            background: #DEF0E7;
            color: var(--green);
        }

        .status-check-result.error {
            background: #F5DEDA;
            color: var(--brick);
        }
    </style>
    @stack('styles')
</head>

<body>

    @include('partials.svg-defs')
    @include('partials.navbar')

    <main class="page-enter">
        @yield('content')
    </main>

    @include('partials.footer')

    <script src="{{ asset('assets/js/sigof.js') }}"></script>

    <script>
        document.querySelectorAll('.upload-box input[type="file"]').forEach(input => {
            input.addEventListener('change', function() {
                const fileName = this.files.length ? this.files[0].name : 'Aucun fichier sélectionné';
                this.closest('.upload-box').querySelector('.file-name').textContent = fileName;
            });
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const totalSteps = 5;
            let currentStep = 1;

            const steps = document.querySelectorAll('.reg-step');
            const stepItems = document.querySelectorAll('.step-item');
            const nextBtn = document.getElementById('regNext');
            const backBtn = document.getElementById('regBack');
            const submitBtn = document.getElementById('regSubmit');
            const form = document.getElementById('registerForm');

            const labels = {
                langue_specialisation: {
                    anglais_bilingue: 'Anglais (profil bilingue)',
                    arabe: 'Arabe',
                    espagnol: 'Espagnol',
                    portugais: 'Portugais',
                    chinois: 'Chinois (Mandarin)',
                    japonais: 'Japonais',
                    coreen: 'Coréen',
                    allemand: 'Allemand',
                    russe: 'Russe',
                    italien: 'Italien'
                },
                diplome: {
                    licence: 'Licence',
                    master: 'Master',
                    doctorat: 'Doctorat',
                    certification: 'Certification linguistique reconnue'
                },
                langue_maternelle: {
                    wolof: 'Wolof',
                    francais: 'Français',
                    pulaar: 'Pulaar',
                    serere: 'Sérère',
                    autre: 'Autre'
                },
                niveau_francais: {
                    c1: 'C1',
                    c2: 'C2 / Bilingue'
                },
                langue_vivante_2: {
                    anglais: 'Anglais',
                    espagnol: 'Espagnol',
                    arabe: 'Arabe',
                    portugais: 'Portugais',
                    aucune: 'Aucune'
                },
                zone: {
                    diamniadio: 'Diamniadio Olympic Stadium',
                    dakar_centre: 'Dakar centre',
                    saly: 'Saly - Petite Côte',
                    indifferent: 'Indifférent'
                }
            };

            function fieldValue(name) {
                const el = form.querySelector(`[name="${name}"]`);
                if (!el) return '';

                // Gère les groupes de radios (ex: civilite)
                if (el.type === 'radio') {
                    const checked = form.querySelector(`[name="${name}"]:checked`);
                    return checked ? checked.value : '';
                }

                return el.value;
            }

            function selectTextFor(name) {
                const el = form.querySelector(`[name="${name}"]`);
                if (!el || el.tagName !== 'SELECT') return '';
                return el.selectedOptions.length ? el.selectedOptions[0].textContent.trim() : '';
            }

            function labelFor(name) {
                const val = fieldValue(name);
                return (labels[name] && labels[name][val]) ? labels[name][val] : (val || '-');
            }

            function fileNameFor(name) {
                const el = form.querySelector(`[name="${name}"]`);
                return (el && el.files.length) ? el.files[0].name : 'Aucun fichier';
            }

            function formatDate(value) {
                if (!value) return '';
                const [year, month, day] = value.split('-');
                if (!year || !month || !day) return value;
                return `${day}/${month}/${year}`;
            }

            function buildRecap() {
                document.getElementById('recap-cin').textContent = fieldValue('cin') || '-';
                document.getElementById('recap-civilite').textContent = fieldValue('civilite') || '-';
                document.getElementById('recap-nom').textContent = `${fieldValue('prenom')} ${fieldValue('nom')}`;
                document.getElementById('recap-email').textContent = fieldValue('email') || '-';
                document.getElementById('recap-telephone').textContent = fieldValue('telephone') || '-';
                document.getElementById('recap-date_naissance').textContent = formatDate(fieldValue(
                    'date_naissance')) || '-';
                document.getElementById('recap-lieu_naissance').textContent = fieldValue('lieu_naissance') || '-';
                document.getElementById('recap-adresse').textContent = fieldValue('adresse') || '-';
                document.getElementById('recap-departement').textContent = fieldValue('departement') || '-';

                document.getElementById('recap-langue_specialisation').textContent = labelFor(
                    'langue_specialisation');
                document.getElementById('recap-certification').textContent = fieldValue('certification') ||
                    'Aucune';
                document.getElementById('recap-diplome').textContent = labelFor('diplome');
                document.getElementById('recap-langue_maternelle').textContent = labelFor('langue_maternelle');
                document.getElementById('recap-niveau_francais').textContent = labelFor('niveau_francais');
                document.getElementById('recap-langue_vivante_2').textContent = labelFor('langue_vivante_2');

                document.getElementById('recap-disponible_debut').textContent = formatDate(fieldValue(
                    'disponible_debut')) || '-';
                document.getElementById('recap-disponible_fin').textContent = formatDate(fieldValue(
                    'disponible_fin')) || '-';
                document.getElementById('recap-zone').textContent = labelFor('zone');
                /* document.getElementById('recap-delegation_souhaitee').textContent = fieldValue(
                    'delegation_souhaitee') || 'Non précisé'; */

                document.getElementById('recap-piece_identite').textContent = fileNameFor('piece_identite');
                document.getElementById('recap-diplome_fichier').textContent = fileNameFor('diplome_fichier');
                document.getElementById('recap-certification_fichier').textContent = fileNameFor(
                    'certification_fichier');
                document.getElementById('recap-cv').textContent = fileNameFor('cv');
            }

            function showStep(step) {
                steps.forEach(s => s.classList.toggle('active', parseInt(s.dataset.step) === step));
                stepItems.forEach(s => s.classList.toggle('current', parseInt(s.dataset.step) === step));

                backBtn.style.display = step === 1 ? 'none' : 'inline-flex';
                nextBtn.style.display = step === totalSteps ? 'none' : 'inline-flex';
                submitBtn.style.display = step === totalSteps ? 'inline-flex' : 'none';

                if (step === totalSteps) buildRecap();
            }

            nextBtn.addEventListener('click', function() {
                if (currentStep < totalSteps) {
                    currentStep++;
                    showStep(currentStep);
                }
            });

            backBtn.addEventListener('click', function() {
                if (currentStep > 1) {
                    currentStep--;
                    showStep(currentStep);
                }
            });

            // Affiche le nom du fichier choisi dans chaque upload-box
            document.querySelectorAll('.upload-box input[type="file"]').forEach(function(input) {
                input.addEventListener('change', function() {
                    const span = input.closest('.upload-box').querySelector('.file-name');
                    if (span && input.files.length) span.textContent = input.files[0].name;
                });
            });

            showStep(currentStep);
        });
    </script>
    <!-- reCAPTCHA -->
    <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}"></script>

    <!-- Vérification du statut de candidature -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var overlay = document.getElementById('statusCheckOverlay');
            var openBtn = document.getElementById('btnOpenStatusCheck');
            var closeBtn = document.getElementById('btnCloseStatusCheck');
            var form = document.getElementById('statusCheckForm');
            var resultBox = document.getElementById('statusCheckResult');

            function openModal() {
                overlay.classList.add('open');
                resultBox.classList.remove('show', 'ok', 'error');
                resultBox.textContent = '';
            }

            function closeModal() {
                overlay.classList.remove('open');
            }

            openBtn.addEventListener('click', openModal);
            closeBtn.addEventListener('click', closeModal);
            overlay.addEventListener('click', function(e) {
                if (e.target === overlay) closeModal();
            });

            // Soumission en Ajax pour afficher le résultat sans quitter la page.
            // Adapter selon la réponse JSON réelle de votre contrôleur
            // (ex: { statut: 'validee'|'attente'|'rejetee', message: '...' }).
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                var submitBtn = form.querySelector('button[type="submit"]');
                submitBtn.disabled = true;
                submitBtn.textContent = 'Vérification...';

                fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                ?.content ||
                                form.querySelector('input[name="_token"]').value,
                            'Accept': 'application/json'
                        },
                        body: new FormData(form)
                    })
                    .then(function(res) {
                        return res.json();
                    })
                    .then(function(data) {
                        resultBox.classList.remove('ok', 'error');
                        resultBox.classList.add('show', data.success ? 'ok' : 'error');
                        resultBox.textContent = data.message || (data.success ?
                            'Statut récupéré avec succès.' :
                            "Aucune candidature trouvée avec ces informations.");
                    })
                    .catch(function() {
                        resultBox.classList.remove('ok');
                        resultBox.classList.add('show', 'error');
                        resultBox.textContent = "Une erreur est survenue, veuillez réessayer.";
                    })
                    .finally(function() {
                        submitBtn.disabled = false;
                        submitBtn.textContent = 'Vérifier le statut';
                    });
            });
        });
    </script>
    @stack('scripts')
</body>

</html>
