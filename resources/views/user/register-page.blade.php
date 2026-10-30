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
                    document.getElementById('registerForm').addEventListener('submit', function(event) {
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
    <title>Inscription ONFP</title>
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
                <h1>Rejoignez<br>l'espace ONFP</h1>
                <p class="lead">Créez votre compte en quelques instants pour déposer vos demandes, suivre vos
                    formations et rester informé à chaque étape.</p>

                <ul class="brand-features">
                    <li>
                        <span class="ico"><i class="bi bi-stopwatch"></i></span>
                        Inscription simple et rapide
                    </li>
                    <li>
                        <span class="ico"><i class="bi bi-card-checklist"></i></span>
                        Suivi de vos demandes en temps réel
                    </li>
                    <li>
                        <span class="ico"><i class="bi bi-shield-lock"></i></span>
                        Vos données personnelles protégées
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
                <h2>Inscription</h2>
                <p class="subtitle">Entrez vos informations pour créer votre compte.</p>

                <a href="{{ url('auth/google') }}" class="btn-google" title="S'inscrire avec Google">
                    <img src="https://www.gstatic.com/images/branding/product/1x/gsa_64dp.png" alt="Google">
                    <span>Utiliser Google pour s'inscrire</span>
                </a>

                <div class="divider">ou avec votre e-mail</div>

                <form id="registerForm" class="needs-validation" novalidate method="POST"
                    action="{{ route('register') }}">
                    @csrf

                    {{-- E-mail --}}
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

                    {{-- Téléphone --}}
                    <div class="mb-3">
                        <label for="votre_telephone" class="field-label">Téléphone <span
                                class="text-danger">*</span></label>
                        <div class="field">
                            <i class="bi bi-telephone"></i>
                            <input type="tel" name="votre_telephone" id="votre_telephone" maxlength="12"
                                class="form-control @error('votre_telephone') is-invalid @enderror" required
                                placeholder="Votre numéro de téléphone" value="{{ old('votre_telephone') }}"
                                autocomplete="tel">
                        </div>
                        @error('votre_telephone')
                            <span class="invalid-text">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Mot de passe --}}
                    <div class="mb-3">
                        <label for="password" class="field-label">Mot de passe <span
                                class="text-danger">*</span></label>
                        <div class="field has-toggle">
                            <i class="bi bi-lock"></i>
                            <input type="password" name="password" id="password"
                                class="form-control @error('password') is-invalid @enderror" required
                                placeholder="Créez un mot de passe" autocomplete="new-password">
                            <button class="toggle-pass" type="button" data-target="password"
                                aria-label="Afficher le mot de passe">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <span class="invalid-text">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Confirmation --}}
                    <div class="mb-3">
                        <label for="password_confirmation" class="field-label">Confirmez le mot de passe <span
                                class="text-danger">*</span></label>
                        <div class="field has-toggle">
                            <i class="bi bi-shield-lock"></i>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="form-control @error('password_confirmation') is-invalid @enderror" required
                                placeholder="Confirmez votre mot de passe" autocomplete="new-password">
                            <button class="toggle-pass" type="button" data-target="password_confirmation"
                                aria-label="Afficher le mot de passe">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        <span class="invalid-text" id="matchError" style="display:none;">Les mots de passe ne
                            correspondent pas.</span>
                        @error('password_confirmation')
                            <span class="invalid-text">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Termes et conditions --}}
                    <div class="mb-4">
                        <div class="form-check mb-0">
                            <input class="form-check-input @error('termes') is-invalid @enderror" name="termes"
                                type="checkbox" value="1" id="acceptTerms" required>
                            <label class="form-check-label" for="acceptTerms">
                                J'accepte les
                                <button type="button" class="btn-terms" data-bs-toggle="modal"
                                    data-bs-target="#largeModal">termes et conditions</button>
                                <span class="text-danger">*</span>
                            </label>
                        </div>
                        @error('termes')
                            <span class="invalid-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <button class="btn-onfp" type="submit" id="submitBtn">
                        <span class="label">S'inscrire</span>
                        <i class="bi bi-arrow-right"></i>
                    </button>
                </form>

                <div class="login-footer">
                    <div>Vous avez déjà un compte ? <a class="link" href="{{ route('login') }}">Se connecter</a>
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

    @include('user.termes')

    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <script>
        // Afficher / masquer les mots de passe (un bouton par champ)
        document.querySelectorAll(".toggle-pass").forEach(function(btn) {
            btn.addEventListener("click", function() {
                const field = document.getElementById(this.dataset.target);
                const icon = this.querySelector("i");
                const show = field.type === "password";

                field.type = show ? "text" : "password";
                icon.classList.toggle("bi-eye", !show);
                icon.classList.toggle("bi-eye-slash", show);
                this.setAttribute("aria-label", show ? "Masquer le mot de passe" :
                    "Afficher le mot de passe");
            });
        });

        const form = document.getElementById("registerForm");
        const pwd = document.getElementById("password");
        const pwdConfirm = document.getElementById("password_confirmation");
        const matchError = document.getElementById("matchError");

        function checkMatch() {
            const mismatch = pwdConfirm.value !== "" && pwd.value !== pwdConfirm.value;
            matchError.style.display = mismatch ? "block" : "none";
            pwdConfirm.classList.toggle("is-invalid", mismatch);
            return !mismatch;
        }
        pwd.addEventListener("input", checkMatch);
        pwdConfirm.addEventListener("input", checkMatch);

        // Validation + état de chargement du bouton
        form.addEventListener("submit", function(event) {
            if (!this.checkValidity() || !checkMatch()) {
                event.preventDefault();
                event.stopPropagation();
                this.classList.add("was-validated");
                return;
            }
            const btn = document.getElementById("submitBtn");
            setTimeout(function() {
                btn.disabled = true;
                btn.querySelector(".label").textContent = "Création du compte…";
                btn.querySelector("i").className = "spinner-border spinner-border-sm";
            }, 10);
        });
    </script>

</body>

</html>
