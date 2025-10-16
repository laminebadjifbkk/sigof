<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ONFP - Partnership Engagement Day</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
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
            color: #0d6efd;
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
            background: #0d6efd;
            border: none;
            font-weight: 600;
            padding: 14px;
            border-radius: 12px;
            width: 100%;
            transition: 0.3s;
        }

        .btn-primary:hover {
            background: #0b5ed7;
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
            height: 50px;
        }

        select.form-control {
            padding-left: 40px;
            border-radius: 10px;
            height: 50px;
        }

        input.form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 5px rgba(13, 110, 253, 0.2);
        }

        select.form-control:focus {
            border-color: #0d6efd;
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
                <select name="structure" class="form-control" required>
                    <option value="">-- Choisir votre structure --</option>
                    <option value="Structure A" {{ old('structure') == 'Structure A' ? 'selected' : '' }}>Structure A
                    </option>
                    <option value="Structure B" {{ old('structure') == 'Structure B' ? 'selected' : '' }}>Structure B
                    </option>
                    <option value="Structure C" {{ old('structure') == 'Structure C' ? 'selected' : '' }}>Structure C
                    </option>
                    <!-- ajoute autant d'options que nécessaire -->
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

            <button type="submit" class="btn btn-primary btn-sm mt-3">Envoyer ma confirmation</button>
        </form>

        <p class="footer-text">© {{ date('Y') }} ONFP — Tous droits réservés</p>
    </div>
</body>

</html>
