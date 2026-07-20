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
