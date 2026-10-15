<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ONFP - Célébration des 40 ans</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #fdf1e6, #f8f9fa);
            font-family: 'Poppins', sans-serif;
        }

        .form-card {
            max-width: 700px;
            margin: 60px auto;
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

        .badge-40 {
            display: inline-block;
            background: #fdf1e6;
            color: #c05f1c;
            border: 1px solid rgba(224, 122, 47, .35);
            padding: 5px 16px;
            border-radius: 999px;
            font-size: .8rem;
            font-weight: 700;
            margin-bottom: 14px;
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

        .btn-primary {
            background: #F28500;
            border: none;
            font-weight: 600;
            padding: 14px;
            border-radius: 12px;
            width: 100%;
            transition: 0.3s;
        }

        .btn-primary:hover,
        .btn-primary:focus {
            background: #d97500;
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
            top: 20px;
            transform: translateY(-50%);
            color: #6c757d;
            pointer-events: none;
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

        textarea.form-control {
            border-radius: 10px;
        }

        .form-control:focus {
            border-color: #F28500;
            box-shadow: 0 0 5px rgba(242, 133, 0, 0.25);
        }
    </style>
</head>

<body>
    <div class="form-card">
        <div class="text-center">
            <span class="badge-40">🎉 1986 – 2026</span>
        </div>
        <h2 class="text-center mb-3">CÉLÉBRATION DES 40 ANS<br>DE L'ONFP</h2>
        <h5 class="text-center text-secondary mb-2">
            Confirmez votre participation
        </h5>
        <p class="text-center text-muted mb-4">
            📅 Le jeudi 29 octobre 2026<br>
            {{-- TODO : précisez l'heure et le lieu exacts --}}
            📍 Dakar
        </p>

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

        <form action="{{ route('inscriptioncontact.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <i class="bi bi-building input-icon"></i>
                <input type="text" name="structure" class="form-control"
                    placeholder="Structure que vous représentez" value="{{ old('structure') }}" required>
            </div>

            <div class="form-group">
                <i class="bi bi-person input-icon"></i>
                <input type="text" name="nom" class="form-control" placeholder="Prénom et Nom du représentant"
                    value="{{ old('nom') }}" required>
            </div>

            <div class="form-group">
                <i class="bi bi-briefcase input-icon"></i>
                <input type="text" name="fonction" class="form-control" placeholder="Fonction"
                    value="{{ old('fonction') }}" required>
            </div>

            <div class="form-group">
                <i class="bi bi-telephone input-icon"></i>
                <input type="text" name="telephone" class="form-control" placeholder="Téléphone"
                    value="{{ old('telephone') }}" required>
            </div>

            <div class="form-group">
                <i class="bi bi-envelope input-icon"></i>
                <input type="email" name="email" class="form-control" placeholder="Adresse mail"
                    value="{{ old('email') }}" required>
            </div>

            <div class="form-group">
                <textarea name="commentaire" class="form-control" rows="4" placeholder="Votre commentaire (facultatif)">{{ old('commentaire') }}</textarea>
            </div>

            <input type="hidden" name="recaptcha_token" id="recaptcha_token">
            <input type="hidden" name="autre" value="40 ANS ONFP">

            <button type="submit" class="btn btn-primary btn-sm mt-3">Envoyer ma confirmation</button>
        </form>

        <p class="footer-text">© {{ date('Y') }} ONFP</p>

        <div class="text-center mt-3">
            <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal"
                data-bs-target="#checkModal">
                <i class="bi bi-search"></i> Déjà inscrit ?
            </button>
        </div>
    </div>

    <!-- Modal Vérification -->
    <div class="modal fade" id="checkModal" tabindex="-1" aria-labelledby="checkModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 shadow">
                <div class="modal-header bg-light">
                    <h5 class="modal-title text-dark mb-0" id="checkModalLabel">Vérifier votre inscription</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('inscriptioncontact.check') }}" method="POST">
                        @csrf

                        <div class="alert alert-info small mb-3">
                            <i class="bi bi-info-circle"></i>
                            Entrez l'adresse mail et le téléphone utilisés lors de votre inscription.
                        </div>

                        <div class="form-group mb-3">
                            <i class="bi bi-envelope input-icon"></i>
                            <input type="email" name="email" class="form-control" placeholder="Adresse mail"
                                required>
                        </div>

                        <div class="form-group mb-3">
                            <i class="bi bi-telephone input-icon"></i>
                            <input type="text" name="telephone" class="form-control" placeholder="Téléphone"
                                required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search"></i> Vérifier mon inscription
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

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
