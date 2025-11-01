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
    </style>
</head>

<body>
    <div class="form-card">
        <h2 class="text-center mb-3">ONFP <br>PARTNERSHIP ENGAGEMENT DAY</h2>
        <h5 class="text-center text-secondary mb-2">
            Confirmez votre participation
        </h5>
        <p class="text-center text-muted mb-4">
            📅 Le 06 novembre à partir de 08h<br>
            📍 Hôtel AZALAÏ DAKAR
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
                <select name="structure" id="structure" class="form-control select2" required>
                    <option value="">-- Sélectionnez une structure --</option>
                    @foreach ($structures as $group => $options)
                        <optgroup label="{{ $group }}">
                            @foreach ($options as $option)
                                <option value="{{ $option }}" {{ old('structure') }}>
                                    {{ $option }}
                                </option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
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
            <!-- Champ Commentaire -->
            <div class="form-group">
                {{-- <i class="bi bi-chat-left-text input-icon"></i> --}}
                <textarea name="commentaire" class="form-control" rows="4" placeholder="Votre commentaire (facultatif)">{{ old('commentaire') }}</textarea>
            </div>

            <input type="hidden" name="recaptcha_token" id="recaptcha_token">

            <input type="hidden" name="autre" value="PARTNERSHIP ENGAGEMENT DAY">
            <button type="submit" class="btn btn-primary btn-sm mt-3">Envoyer ma confirmation</button>
        </form>

        <p class="footer-text">© {{ date('Y') }} ONFP</p>
        {{-- <p class="footer-text">© {{ date('Y') }} ONFP — Tous droits réservés</p> --}}

        <!-- Bouton "Déjà inscrit ?" -->
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
                    <h5 class="modal-title text-dark" id="checkModalLabel">
                        Vérifier votre inscription
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">

                    <form action="{{ route('inscriptioncontact.check') }}" method="POST">
                        @csrf

                        <div class="alert alert-info small mb-3">
                            <i class="bi bi-info-circle"></i>
                            Sélectionnez d’abord votre structure, puis entrez votre adresse mail et votre téléphone.
                        </div>

                        <!-- Structure -->
                        <div class="form-group mb-3">
                            <i class="bi bi-building input-icon"></i>
                            <select name="structure" class="form-control select2" required>
                                <option value="">-- Sélectionnez une structure --</option>
                                @foreach ($structures as $group => $options)
                                    <optgroup label="{{ $group }}">
                                        @foreach ($options as $option)
                                            <option value="{{ $option }}">{{ $option }}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>

                        <!-- Email -->
                        <div class="form-group mb-3 position-relative">
                            <i class="bi bi-envelope input-icon"></i>
                            <input type="email" name="email" class="form-control" placeholder="Adresse mail"
                                required>
                        </div>

                        <!-- Téléphone -->
                        <div class="form-group mb-3 position-relative">
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
    <!-- Bootstrap Bundle JS (avec Popper inclus, obligatoire pour les modales) -->

    <script>
        $(document).ready(function() {
            // Initialisation principale (pour le formulaire principal)
            $('#structure').select2({
                placeholder: "Choisir votre structure",
                allowClear: true,
                width: '100%'
            });

            // Initialisation spécifique pour le modal "Déjà inscrit ?"
            $('#checkModal').on('shown.bs.modal', function() {
                $(this).find('.select2').select2({
                    dropdownParent: $('#checkModal'),
                    placeholder: "Choisir la structure que vous représentez",
                    allowClear: true,
                    width: '100%'
                });
            });
        });
    </script>


</body>

</html>
