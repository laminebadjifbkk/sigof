<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script type="text/javascript">
        function callbackThen(response) {
            response.json().then(function(data) {
                console.log(data);
                if (data.success && data.score > 0.5) {
                    console.log('recaptcha valid');
                } else {
                    document.getElementById('forgotForm').addEventListener('submit', function(event) {
                        event.preventDefault();
                        alert('Erreur reCAPTCHA : veuillez réessayer.');
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
    <title>Mot de passe oublié — ONFP</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <link href="{{ asset('assets/img/favicon-32x32.png') }}" rel="icon">
    <link href="{{ asset('assets/img/favicon-32x32.png') }}" rel="apple-touch-icon">

    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@500;600;700;800&display=swap"
        rel="stylesheet">

    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/login.css') }}" rel="stylesheet">

</head>

<body class="login-page">
    <main class="login-wrapper">

        {{-- ===== Panneau marque ===== --}}
        <aside class="login-brand">
            <div class="brand-top">
                <a href="{{ route('accueil') }}" class="brand-logo-card" title="ONFP — Accueil">
                    <img src="{{ asset('assets/img/ONFP_photo-profil_1080x1080.png') }}" alt="Logo ONFP">
                </a>
            </div>

            <div class="brand-middle">
                <h1>Mot de passe<br>oublié ?</h1>
                <p class="lead">Pas de panique. Quelques étapes suffisent pour retrouver l'accès à votre compte
                    ONFP.</p>

                <ul class="brand-features">
                    <li>
                        <span class="ico"><i class="bi bi-envelope-at"></i></span>
                        Saisissez l'adresse e-mail de votre compte
                    </li>
                    <li>
                        <span class="ico"><i class="bi bi-link-45deg"></i></span>
                        Recevez un lien de réinitialisation
                    </li>
                    <li>
                        <span class="ico"><i class="bi bi-key"></i></span>
                        Choisissez un nouveau mot de passe
                    </li>
                </ul>
            </div>

            <div class="brand-bottom">
                <span class="tricolor"></span>
                <span>Office National de Formation Professionnelle</span>
            </div>
        </aside>

        {{-- ===== Panneau formulaire ===== --}}
        <section class="login-form-side">
            <div class="login-card">

                <a href="{{ route('accueil') }}" class="mobile-logo" title="ONFP — Accueil">
                    <img src="{{ asset('assets/img/ONFP_photo-profil_1080x1080.png') }}" alt="Logo ONFP">
                </a>

                <span class="tricolor short"></span>
                <h2>Réinitialisation</h2>
                <p class="subtitle">Vous avez oublié votre mot de passe ? Entrez votre adresse e-mail pour recevoir
                    un lien de réinitialisation.</p>

                {{-- Message de succès --}}
                @if ($message = Session::get('status'))
                    <div class="login-alert login-alert-success alert alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill"></i>
                        <div>{{ $message }}</div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                            aria-label="Fermer"></button>
                    </div>
                @endif

                <form id="forgotForm" class="needs-validation" novalidate method="POST"
                    action="{{ route('password.email') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="email" class="field-label">Adresse e-mail <span
                                class="text-danger">*</span></label>
                        <div class="field">
                            <i class="bi bi-envelope"></i>
                            <input type="email" name="email" id="email"
                                class="form-control @error('email') is-invalid @enderror" required
                                placeholder="nom@exemple.com" value="{{ old('email') }}" autocomplete="email"
                                autofocus>
                        </div>
                        @error('email')
                            <span class="invalid-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <button class="btn-onfp" type="submit" id="submitBtn">
                        <span class="label">Envoyer le lien</span>
                        <i class="bi bi-send"></i>
                    </button>
                </form>

                <div class="login-footer">
                    <a class="back-home" href="{{ url('/login') }}">
                        <i class="bi bi-arrow-left"></i> Retour à la connexion
                    </a>
                </div>

                <div class="credits">
                    &copy; {{ date('Y') }} <a href="https://www.onfp.sn/" target="_blank"
                        rel="noopener"><strong>ONFP</strong></a> — Tous droits réservés
                </div>
            </div>
        </section>
    </main>

    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <script>
        // Validation + état de chargement du bouton
        document.getElementById("forgotForm").addEventListener("submit", function(event) {
            if (!this.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
                this.classList.add("was-validated");
                return;
            }
            const btn = document.getElementById("submitBtn");
            setTimeout(function() {
                btn.disabled = true;
                btn.querySelector(".label").textContent = "Envoi en cours…";
                btn.querySelector("i").className = "spinner-border spinner-border-sm";
            }, 10);
        });
    </script>

</body>

</html>
