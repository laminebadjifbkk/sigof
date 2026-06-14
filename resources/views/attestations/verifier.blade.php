<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Vérifier une attestation — ONFP</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            padding: 40px;
            width: 100%;
            max-width: 480px;
            text-align: center;
        }

        .card img {
            height: 70px;
            margin-bottom: 20px;
        }

        .card h2 {
            font-size: 20px;
            color: #333;
            margin-bottom: 8px;
        }

        .card p.subtitle {
            font-size: 13px;
            color: #888;
            margin-bottom: 30px;
        }

        input {
            width: 100%;
            padding: 14px;
            font-size: 15px;
            border: 1.5px solid #ddd;
            border-radius: 8px;
            letter-spacing: 1.5px;
            text-align: center;
            text-transform: uppercase;
            outline: none;
            transition: border-color 0.2s;
        }

        input:focus {
            border-color: #0d6efd;
        }

        .hint {
            font-size: 11px;
            color: #aaa;
            margin-top: 8px;
            margin-bottom: 20px;
        }

        button {
            width: 100%;
            padding: 14px;
            background: #0d6efd;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            cursor: pointer;
            transition: background 0.2s;
        }

        button:hover {
            background: #0b5ed7;
        }

        @if(session('error'))
        .alert-error {
            background: #fdecea;
            border: 1px solid #f5c6cb;
            color: #721c24;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 20px;
            font-size: 13px;
        }
        @endif
    </style>
</head>
<body>

    <div class="card">
        <img src="{{ asset('assets/img/logo_sigle.png') }}" alt="ONFP">
        <h2>Vérification d'attestation</h2>
        <p class="subtitle">Saisissez le numéro figurant sous le QR code</p>

        @if(session('error'))
            <div class="alert-error">{{ session('error') }}</div>
        @endif

        <form action="{{ route('attestation.verifier.numero.recherche') }}" method="GET">
            <input
                type="text"
                name="numero"
                placeholder="ONFP-2026-000001-L"
                value="{{ old('numero') }}"
                autofocus
                required
            >
            <p class="hint">Exemple : ONFP-2026-000001-L</p>
            <button type="submit">Vérifier</button>
        </form>
    </div>

</body>
</html>