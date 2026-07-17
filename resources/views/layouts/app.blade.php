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
                return el ? el.value : '';
            }

            function labelFor(name) {
                const val = fieldValue(name);
                return (labels[name] && labels[name][val]) ? labels[name][val] : (val || '—');
            }

            function fileNameFor(name) {
                const el = form.querySelector(`[name="${name}"]`);
                return (el && el.files.length) ? el.files[0].name : 'Aucun fichier';
            }

            function buildRecap() {
                document.getElementById('recap-nom').textContent = `${fieldValue('prenom')} ${fieldValue('nom')}`;
                document.getElementById('recap-email').textContent = fieldValue('email') || '—';
                document.getElementById('recap-telephone').textContent = fieldValue('telephone') || '—';
                document.getElementById('recap-date_naissance').textContent = fieldValue('date_naissance') || '—';

                document.getElementById('recap-langue_specialisation').textContent = labelFor('langue_specialisation');
                document.getElementById('recap-certification').textContent = fieldValue('certification') || 'Aucune';
                document.getElementById('recap-diplome').textContent = labelFor('diplome');
                document.getElementById('recap-langue_maternelle').textContent = labelFor('langue_maternelle');
                document.getElementById('recap-niveau_francais').textContent = labelFor('niveau_francais');
                document.getElementById('recap-langue_vivante_2').textContent = labelFor('langue_vivante_2');

                document.getElementById('recap-disponible_debut').textContent = fieldValue('disponible_debut') || '—';
                document.getElementById('recap-disponible_fin').textContent = fieldValue('disponible_fin') || '—';
                document.getElementById('recap-zone').textContent = labelFor('zone');
                document.getElementById('recap-delegation_souhaitee').textContent = fieldValue('delegation_souhaitee') || 'Non précisé';

                document.getElementById('recap-piece_identite').textContent = fileNameFor('piece_identite');
                document.getElementById('recap-diplome_fichier').textContent = fileNameFor('diplome_fichier');
                document.getElementById('recap-certification_fichier').textContent = fileNameFor('certification_fichier');
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
    @stack('scripts')
</body>

</html>