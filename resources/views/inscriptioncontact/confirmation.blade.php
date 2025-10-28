<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ONFP - Partnership Engagement Day</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Select2 JS + jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#structure').select2({
                placeholder: "Choisir votre structure",
                allowClear: true,
                width: '100%'
            });
        });
    </script>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #e9f4ff, #f8f9fa);
            font-family: 'Poppins', sans-serif;
        }

        .form-card {
            max-width: 700px;
            margin: 80px auto;
            background: #fff;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .form-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }

        h2 {
            color: #F28500;
            font-weight: 700;
        }

        h5 {
            color: #6c757d;
            font-weight: 400;
            margin-bottom: 30px;
        }

        .form-floating>input {
            padding: 1.5rem 1rem 0.5rem 1rem;
        }

        .btn-primary {
            background: #F28500;
            border: none;
            font-weight: 600;
            padding: 14px;
            border-radius: 12px;
            width: 100%;
            transition: 0.3s;
        }

        .btn-primary:hover {
            background: #F28500;
        }

        .btn-secondary {
            background: #6c757d;
            border: none;
            font-weight: 600;
            padding: 14px;
            border-radius: 12px;
            width: 100%;
            transition: 0.3s;
        }

        .btn-secondary:hover {
            background: #6c757d;
        }

        .footer-text {
            text-align: center;
            font-size: 14px;
            color: #6c757d;
            margin-top: 25px;
        }

        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }

        .form-group {
            position: relative;
            margin-bottom: 20px;
        }

        input.form-control {
            padding-left: 40px;
            border-radius: 10px;
            height: 40px;
        }

        select.form-control {
            padding-left: 30px;
            border-radius: 10px;
            height: 40px;
        }

        input.form-control:focus {
            border-color: #F28500;
            box-shadow: 0 0 5px rgba(13, 110, 253, 0.2);
        }

        select.form-control:focus {
            border-color: #F28500;
            box-shadow: 0 0 5px rgba(13, 110, 253, 0.2);
        }

        .btn-sm-custom {
            padding: 0.25rem 0.6rem;
            /* moins de padding que btn-sm par défaut */
            font-size: 0.8rem;
        }
    </style>
</head>

<body>
    <div class="form-card">
        <h2 class="text-center mb-3">ONFP <br>PARTNERSHIP ENGAGEMENT DAY</h2>
        <h5 class="text-center text-secondary mb-2">
            Confirmation d'inscription
        </h5>
        @if (session('success'))
            <div class="alert alert-success text-center rounded-3">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger rounded-3">
                <ul class="mb-0">
                    @foreach ($errors->all() as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="container mt-4">
            <div class="card shadow-sm rounded-4">
                <div class="card-body">
                    <p><strong>Structure :</strong> {{ $inscription->structure }}</p>
                    <p><strong>Nom :</strong> {{ $inscription->nom }}</p>
                    <p><strong>Email :</strong> {{ $inscription->email }}</p>
                    <p><strong>Fonction :</strong> {{ $inscription->fonction }}</p>
                    <p><strong>Téléphone :</strong> {{ $inscription->telephone }}</p>

                    <div class="d-flex justify-content-between mt-3 text-center">
                        <a href="{{ route('inscriptioncontact') }}" class="btn btn-secondary btn-sm w-auto">
                            Retour à l'inscription
                        </a>
                        
                        <a href="{{ route('inscription.questions', $inscription->id) }}"
                            class="btn btn-primary btn-sm w-auto">
                            Répondre aux questions
                        </a>
                    </div>


                    {{-- <div class="d-flex justify-content-between mt-3">
                        <a href="{{ route('inscriptioncontact') }}" class="btn btn-secondary btn-sm">
                            Retour
                        </a>

                        <a href="{{ route('inscription.questions', $inscription->id) }}" class="btn btn-primary btn-sm">
                            Répondre aux questions
                        </a>
                    </div> --}}
                </div>
            </div>
        </div>


        <p class="footer-text">© {{ date('Y') }} ONFP</p>
        {{-- <p class="footer-text">© {{ date('Y') }} ONFP — Tous droits réservés</p> --}}

    </div>


    <!-- SweetAlert CSS/JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Script à la fin de la page -->
    <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            grecaptcha.ready(function() {
                grecaptcha.execute('{{ config('services.recaptcha.site_key') }}', {
                        action: 'submit'
                    })
                    .then(function(token) {
                        document.getElementById('recaptcha_token').value = token;
                    });
            });
        });
    </script>

    @include('sweetalert::alert')



</body>

</html>
