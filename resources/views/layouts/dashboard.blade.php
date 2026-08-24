<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') · SIGOF Admin</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400..800&family=Inter:wght@400;500;600;700;800&family=IBM+Plex+Mono:wght@500;600&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/sigof.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.min.css">

    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.2.5/css/buttons.dataTables.min.css">
    <style>
        .status-pill {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 999px;
            font-size: 12.5px;
            font-weight: 600;
            white-space: nowrap;
        }

        .status-en-attente {
            background: #fff8e1;
            color: #8a6d00;
        }

        .status-validee {
            background: #e8f5e9;
            color: #2e7d32;
        }

        .status-rejetee {
            background: #fdecea;
            color: #842029;
        }

        .status-en-cours {
            background: #e3f2fd;
            color: #1565c0;
        }

        .status-inconnu {
            background: #f0f0f0;
            color: #616161;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 16px;
            margin-top: 12px;
        }

        .detail-item {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .detail-label {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            color: var(--gray-500, #888);
        }

        .detail-value {
            font-size: 14.5px;
            font-weight: 500;
        }

        .documents-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 16px;
            margin-top: 12px;
        }

        .document-card {
            border: 1px solid var(--gray-200, #eee);
            border-radius: 8px;
            padding: 12px;
            display: flex;
            flex-direction: column;
            gap: 8px;
            align-items: flex-start;
        }

        .document-label {
            font-weight: 600;
            font-size: 13.5px;
        }

        .document-preview img {
            max-width: 100%;
            max-height: 140px;
            border-radius: 6px;
            object-fit: cover;
        }

        .document-file-link {
            display: inline-block;
            padding: 8px 12px;
            background: var(--gray-50, #f7f7f7);
            border-radius: 6px;
            font-size: 13px;
        }

        .document-missing {
            font-size: 13px;
            color: var(--gray-500, #999);
            font-style: italic;
        }

        .btn-success {
            background: #2e7d32;
            color: #fff;
            border: none;
        }

        .btn-danger {
            background: #c0392b;
            color: #fff;
            border: none;
        }

        .btn-outline {
            background: transparent;
            border: 1px solid var(--gray-300, #ddd);
            color: inherit;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem 1.5rem;
        }

        .form-grid .field {
            display: flex;
            flex-direction: column;
        }

        .form-grid .field.full-width {
            grid-column: 1 / -1;
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
        }

        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 20px;
        }

        .kpi-grid-compact {
            grid-template-columns: repeat(2, 1fr);
            margin-bottom: 0;
        }

        .kpi-card {
            background: #fff;
            border: 1px solid #e6e4de;
            border-radius: 12px;
            padding: 16px 18px;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .kpi-label {
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            color: #9a978d;
        }

        .kpi-value {
            font-size: 26px;
            font-weight: 700;
            color: #23221f;
        }

        .kpi-success {
            color: #2e7d32;
        }

        .kpi-warning {
            color: #b58900;
        }

        .kpi-danger {
            color: #c0392b;
        }

        .kpi-sub {
            font-size: 12px;
            color: #9a978d;
        }

        .rapport-row {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
            margin-bottom: 20px;
        }

        .rapport-col {
            margin-bottom: 0;
        }

        @media (max-width: 900px) {
            .kpi-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .rapport-row {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 500px) {
            .kpi-grid {
                grid-template-columns: 1fr;
            }
        }

        .export-filters {
            display: grid;
            grid-template-columns: repeat(5, 1fr) auto;
            gap: 12px;
            align-items: end;
        }

        .export-submit {
            display: flex;
        }

        .export-submit .btn {
            width: 100%;
            height: 38px;
        }

        @media (max-width: 900px) {
            .export-filters {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 500px) {
            .export-filters {
                grid-template-columns: 1fr;
            }
        }

        .brand-logo {
            height: 34px;
            width: auto;
            display: block;
        }



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
            color: #4a4842 !important;
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


        /* --- Nouvelle charte graphique : fond noir de la sidebar remplacé par le vert --- */
        .dash-sidebar {
            background: var(--green) !important;
        }

        .dash-brand {
            border-bottom-color: rgba(255, 255, 255, 0.15) !important;
        }

        #dashSidebar .dash-link {
            color: #fff !important;
            font-weight: 700 !important;
        }

        .dash-link:hover {
            background: rgba(255, 255, 255, 0.12) !important;
            color: #fff !important;
        }

        .dash-link.active {
            background: var(--gold) !important;
            color: var(--black) !important;
        }

        .dash-link .ic {
            background: rgba(255, 255, 255, 0.5) !important;
        }

        .dash-link.active .ic {
            background: var(--black) !important;
        }

        .dash-foot {
            border-top-color: rgba(255, 255, 255, 0.15) !important;
            color: rgba(255, 255, 255, 0.6) !important;
        }

        .error-text {
            color: red;
            font-size: 0.9rem;
        }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    @stack('styles')
</head>

<body>

    @include('partials.svg-defs')
    @include('partials.navbar')

    {{-- Le dashboard n'affiche pas la navbar/footer publics : sidebar + topbar suffisent --}}
    <div class="dash-shell">
        @include('partials.sidebar')

        <main class="dash-main page-enter">
            @yield('content')
            <div class="sidebar-overlay" id="sidebarOverlay"></div>
        </main>
    </div>
    @include('partials.user-modal') {{-- juste avant les scripts --}}

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/js/sigof.js') }}"></script>
    <script src="https://cdn.datatables.net/2.3.2/js/dataTables.min.js"></script>

    <script src="https://cdn.datatables.net/buttons/3.2.5/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.5/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.5/js/buttons.print.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <!-- reCAPTCHA -->
    <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}"></script>
    @stack('scripts')
</body>

</html>
