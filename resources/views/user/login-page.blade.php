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
                    document.getElementById('loginForm').addEventListener('submit', function(event) {
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
    <title>Connexion ONFP</title>
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
                <h1>Bienvenue sur votre<br>espace SIGOF</h1>
                <p class="lead">Accédez à votre tableau de bord pour suivre vos formations, vos candidatures
                    et vos démarches en toute simplicité.</p>

                <ul class="brand-features">
                    <li>
                        <span class="ico"><i class="bi bi-shield-check"></i></span>
                        Connexion sécurisée et données protégées
                    </li>
                    <li>
                        <span class="ico"><i class="bi bi-lightning-charge"></i></span>
                        Un accès rapide à tous vos services
                    </li>
                    <li>
                        <span class="ico"><i class="bi bi-people"></i></span>
                        Un accompagnement tout au long de votre parcours
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
                <h2>Connexion</h2>
                <p class="subtitle">Entrez vos identifiants pour accéder à votre compte.</p>

                <a href="{{ url('auth/google') }}" class="btn-google" title="Se connecter avec Google">
                    <img src="https://www.gstatic.com/images/branding/product/1x/gsa_64dp.png" alt="Google">
                    <span>Se connecter avec Google</span>
                </a>

                <div class="divider">ou avec votre e-mail</div>

                <form id="loginForm" class="needs-validation" novalidate method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-3">
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

                    <div class="mb-3">
                        <label for="password" class="field-label">Mot de passe <span
                                class="text-danger">*</span></label>
                        <div class="field has-toggle">
                            <i class="bi bi-lock"></i>
                            <input type="password" name="password" id="password"
                                class="form-control @error('password') is-invalid @enderror" required
                                placeholder="Votre mot de passe" autocomplete="current-password">
                            <button class="toggle-pass" type="button" id="togglePassword"
                                aria-label="Afficher le mot de passe">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <span class="invalid-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check mb-0">
                            <input class="form-check-input" type="checkbox" name="remember" value="true"
                                id="rememberMe">
                            <label class="form-check-label" for="rememberMe">Se souvenir de moi</label>
                        </div>

                        @if (Route::has('password.request'))
                            <a class="link link-forgot" href="{{ route('password.request') }}">Mot de passe
                                oublié ?</a>
                        @endif
                    </div>

                    <button class="btn-onfp" type="submit" id="submitBtn">
                        <span class="label">Se connecter</span>
                        <i class="bi bi-arrow-right"></i>
                    </button>
                </form>

                <div class="login-footer">
                    <div>Vous n'avez pas encore de compte ? <a class="link"
                            href="{{ route('register') }}">S'inscrire</a>
                    </div>
                    <a class="back-home" href="{{ route('accueil') }}">
                        <i class="bi bi-arrow-left"></i> Retour à l'accueil
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
        // Afficher / masquer le mot de passe
        document.getElementById("togglePassword").addEventListener("click", function() {
            const passwordField = document.getElementById("password");
            const icon = this.querySelector("i");
            const show = passwordField.type === "password";

            passwordField.type = show ? "text" : "password";
            icon.classList.toggle("bi-eye", !show);
            icon.classList.toggle("bi-eye-slash", show);
            this.setAttribute("aria-label", show ? "Masquer le mot de passe" : "Afficher le mot de passe");
        });

        // Validation + état de chargement du bouton
        document.getElementById("loginForm").addEventListener("submit", function(event) {
            if (!this.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
                this.classList.add("was-validated");
                return;
            }
            const btn = document.getElementById("submitBtn");
            // léger délai pour ne pas bloquer l'envoi du formulaire
            setTimeout(function() {
                btn.disabled = true;
                btn.querySelector(".label").textContent = "Connexion en cours…";
                btn.querySelector("i").className = "spinner-border spinner-border-sm";
            }, 10);
        });
    </script>

</body>

</html>
