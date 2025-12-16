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
                    console.log('recpatcha valid');
                } else {
                    document.getElementById('registerForm').addEventListener('submit', function(event) {
                        event.preventDefault();
                        alert('erreur recpatcha');
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
    <title>Connexion</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <link href="{{ asset('assets/img/favicon-onfp.png') }}" rel="icon">
    <link href="{{ asset('assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/quill/quill.snow.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/quill/quill.bubble.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/simple-datatables/style.css') }}" rel="stylesheet">

    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">

</head>

<body>

    <main>
        <div class="container">
            <section
                class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
                <div class="container">
                    <div class="row justify-content-center">
                        <div
                            class="col-12 col-md-6 col-sm-12 col-xs-12 col-xxl-6 d-flex flex-column align-items-center justify-content-center">
                            <div class="d-flex justify-content-center py-4">
                                <a href="{{ route('accueil') }}" class="logo d-flex align-items-center w-auto"
                                    target="_blank">
                                    <span class="d-none d-lg-block">ONFP</span>
                                </a>
                            </div>

                            <div class="card mb-3">

                                <div class="card-body">
                                    <div class="pt-0 pb-2">
                                        <h5 class="card-title text-center pb-0 fs-4">Réinitialisation du mot de passe
                                        </h5>
                                        <p class="text-center small">Entrez votre adresse e-mail pour recevoir un lien
                                            de
                                            réinitialisation.</p>
                                    </div>

                                    <form method="POST" action="{{ route('password.email') }}"
                                        class="row g-3 needs-validation">
                                        @csrf

                                        <div class="mb-3">
                                            <label for="email" class="form-label">Adresse e-mail <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                                <input type="email" name="email"
                                                    class="form-control @error('email') is-invalid @enderror"
                                                    id="email" placeholder="Votre email"
                                                    value="{{ old('email') }}" required autofocus>
                                                @error('email')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <button type="submit" class="btn text-white fw-bold w-100"
                                            style="background: #FF8000;">Envoyer le lien</button>
                                    </form>
                                    <div class="text-center mt-3">
                                        <a href="{{ url('/login') }}" class="back-to-login">⬅ Retour à la
                                            connexion</a>
                                    </div>

                                </div>
                            </div>

                            {{-- <div class="col-md-6">
                                <div class="login-container">
                                    <div class="text-center">
                                        <span class="d-none d-lg-block">ONFP</span>
                                        <h4 class="mt-3">Réinitialisation du mot de passe</h4>
                                        <p class="text-muted">Entrez votre adresse e-mail pour recevoir un lien de
                                            réinitialisation.
                                        </p>
                                    </div>

                                    @if (session('status'))
                                        <div class="alert alert-success text-center">
                                            ✅ {{ session('status') }}
                                        </div>
                                    @endif

                                    <form method="POST" action="{{ route('password.email') }}" class="mt-4">
                                        @csrf

                                        <div class="mb-3">
                                            <label for="email" class="form-label">Adresse e-mail <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                                <input type="email" name="email"
                                                    class="form-control @error('email') is-invalid @enderror"
                                                    id="email" placeholder="Votre email"
                                                    value="{{ old('email') }}" required autofocus>
                                                @error('email')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <button type="submit" class="btn btn-primary w-100">📩 Envoyer le
                                            lien</button>
                                    </form>

                                    <div class="text-center mt-3">
                                        <a href="{{ url('/login-page') }}" class="back-to-login">⬅ Retour à la
                                            connexion</a>
                                    </div>

                                    <div class="credits">
                                        Conçu par <a href="https://www.onfp.sn/" target="_blank">Lamine BADJI</a>
                                    </div>
                                </div>
                            </div> --}}

                            <div class="credits">
                                &copy; Copyright <strong><span><a href="https://www.onfp.sn/"
                                            target="_blank">ONFP</a></span></strong>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <!-- JS Files -->
    <script src="{{ asset('assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/chart.js/chart.umd.js') }}"></script>
    <script src="{{ asset('assets/vendor/echarts/echarts.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/quill/quill.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/simple-datatables/simple-datatables.js') }}"></script>
    <script src="{{ asset('assets/vendor/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/php-email-form/validate.js') }}"></script>

    <script src="{{ asset('assets/js/main.js') }}"></script>

    {{-- <script>
        document.getElementById("togglePassword").addEventListener("click", function() {
            let passwordField = document.getElementById("password");
            let icon = this.querySelector("i");

            if (passwordField.type === "password") {
                passwordField.type = "text";
                icon.classList.remove("bi-eye");
                icon.classList.add("bi-eye-slash");
            } else {
                passwordField.type = "password";
                icon.classList.remove("bi-eye-slash");
                icon.classList.add("bi-eye");
            }
        });
    </script> --}}

</body>

</html>
