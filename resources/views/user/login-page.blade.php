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
    <title>Connexion SOGOF</title>
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

                            {{-- <div class="d-flex justify-content-center py-4">
                                <a href="{{ route('accueil') }}" class="logo d-flex align-items-center w-auto"
                                    target="_blank">
                                    <span class="d-none d-lg-block">ONFP</span>
                                </a>
                            </div> --}}

                            <div class="card mb-3">

                                <div class="card-body">

                                    <div class="pt-0 pb-2 text-center">
                                        <h5 class="card-title fs-4 mb-1">
                                            {{-- <a href="{{ route('accueil') }}"
                                                class="logo d-flex align-items-center justify-content-center w-auto"
                                                target="_blank">
                                                <span class="d-none d-lg-block">SIGOF</span>
                                            </a> --}}

                                            <a href="{{ route('accueil') }}" style="text-decoration: none">
                                                <span class="fw-bold" style="font-size: 2rem;">SIGOF Connexion</span>
                                            </a>
                                        </h5>
                                        <p class="small mb-0">Entrez vos identifiants pour vous connecter</p>
                                    </div>

                                    {{-- <a href="{{ url('auth/google') }}" --}}
                                    {{-- <a href="{{ url('auth/google') }}"
                                        class="btn btn-light w-100 d-flex align-items-center justify-content-center border shadow-sm mb-3"
                                        style="background-color: #fff; color: #444; font-weight: 500;">
                                        <img src="https://www.gstatic.com/images/branding/product/1x/gsa_64dp.png"
                                            alt="Google" style="width:20px; height:20px; margin-right:8px;">
                                        Se connecter avec Google
                                    </a> --}}
                                    {{-- <div class="d-flex justify-content-center mt-3">
                                        <a href="{{ url('auth/google') }}"
                                            class="btn btn-light d-flex align-items-center justify-content-center border shadow-sm mb-3"
                                            style="background-color: #fff; width: 50px; height: 50px; padding: 0;"
                                            title="Se connecter avec Google">
                                            <img src="https://www.gstatic.com/images/branding/product/1x/gsa_64dp.png"
                                                alt="Google" style="width:24px; height:24px;">
                                        </a>
                                    </div> --}}

                                    <form class="row g-3 needs-validation" novalidate method="POST"
                                        action="{{ route('login') }}">
                                        @csrf
                                        <div class="col-12">
                                            <label for="email" class="form-label">Email<span
                                                    class="text-danger mx-1">*</span></label>
                                            <div class="input-group has-validation">
                                                <span class="input-group-text" id="inputGroupPrepend">@</span>
                                                <input type="email" name="email"
                                                    class="form-control form-control-sm @error('email') is-invalid @enderror"
                                                    id="email" required placeholder="Votre adresse e-mail"
                                                    value="{{ old('email') }}" autofocus>
                                                <div class="invalid-feedback">
                                                    @error('email')
                                                        {{ $message }}
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-12">
                                            <label for="password" class="form-label">Mot de passe<span
                                                    class="text-danger mx-1">*</span></label>
                                            <div class="input-group has-validation">
                                                <span class="input-group-text" id="inputGroupPrepend"><i
                                                        class="bi bi-key"></i></span>
                                                <input type="password" name="password"
                                                    class="form-control form-control-sm  @error('password') is-invalid @enderror"
                                                    id="password" required placeholder="Votre mot de passe">
                                                <button class="btn btn-outline-secondary" type="button"
                                                    id="togglePassword">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <div class="invalid-feedback">
                                                    @error('password')
                                                        {{ $message }}
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="remember"
                                                    value="true" id="rememberMe">
                                                <label class="form-check-label" for="rememberMe">Souviens-toi de
                                                    moi</label>
                                            </div>
                                        </div>
                                        {{-- <div class="col-12">
                                            <button class="btn btn-primary btn-sm w-100" type="submit">Se
                                                connecter</button>
                                        </div> --}}
                                        <div class="col-12 col-xxl-12">
                                            <button class="btn text-white fw-bold w-100" style="background: #FF8000;"
                                                type="submit">
                                                Se connecter
                                            </button>
                                        </div>

                                        <div class="col-12">
                                            <p class="small mb-0">Retour à la page d'<a
                                                    href="{{ route('accueil') }}">accueil</a></p>
                                        </div>
                                        <div class="col-12">
                                            <p class="small mb-0">Si vous n'avez pas encore de compte, <a
                                                    href="{{ route('register') }}">S'inscrire</a></p>
                                        </div>
                                        <div class="col-12">
                                            @if (Route::has('password.request'))
                                                <p class="small mb-0">
                                                    Mot de passe oublié ?
                                                    <a href="{{ route('password.email') }}">Réinitialiser</a>
                                                </p>
                                            @endif
                                        </div>
                                    </form>
                                </div>
                            </div>

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

    <script src="{{ asset('assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/chart.js/chart.umd.js') }}"></script>
    <script src="{{ asset('assets/vendor/echarts/echarts.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/quill/quill.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/simple-datatables/simple-datatables.js') }}"></script>
    <script src="{{ asset('assets/vendor/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/php-email-form/validate.js') }}"></script>

    <script src="{{ asset('assets/js/main.js') }}"></script>

    <script>
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
    </script>

</body>

</html>
