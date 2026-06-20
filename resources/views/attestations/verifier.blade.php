<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérifier une attestation — ONFP</title>
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', system-ui, sans-serif;
            background: #F0F4F8;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
            color: #1A2332;
        }

        .card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
            max-width: 480px;
            width: 100%;
            overflow: hidden;
        }

        /* ── En-tête institutionnel ── */
        .header {
            text-align: center;
            padding: 24px 24px 16px;
            border-bottom: 1px solid #E2E8F0;
        }

        .republique-bloc {
            font-size: 11px;
            line-height: 1.7;
            color: #1a1a1a;
        }

        .republique-bloc b {
            font-size: 11.5px;
            letter-spacing: 0.4px;
        }

        .logo-onfp {
            display: block;
            margin: 10px auto 4px;
            max-width: 150px;
            width: 100%;
        }

        /* ── Corps ── */
        .card-body {
            padding: 2rem;
        }

        .card-body h2 {
            font-size: 1.2rem;
            font-weight: 700;
            color: #1A2332;
            margin-bottom: 6px;
            text-align: center;
        }

        .card-body p.subtitle {
            font-size: 13px;
            color: #6B7280;
            text-align: center;
            margin-bottom: 1.75rem;
        }

        /* ── Alerte erreur ── */
        .alert-error {
            background: #fdecea;
            border: 1px solid #f5c6cb;
            color: #721c24;
            border-radius: 8px;
            padding: 12px 16px;
            margin-bottom: 1.25rem;
            font-size: 13px;
            text-align: left;
        }

        /* ── Champ de saisie ── */
        .input-wrap {
            position: relative;
            margin-bottom: 6px;
        }

        input[type="text"] {
            width: 100%;
            padding: 14px 16px;
            font-size: 15px;
            font-family: 'Courier New', monospace;
            letter-spacing: 2px;
            text-align: center;
            text-transform: uppercase;
            border: 1.5px solid #E2E8F0;
            border-radius: 10px;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            color: #1A2332;
            background: #F8FAFC;
        }

        input[type="text"]:focus {
            border-color: #FF8000;
            box-shadow: 0 0 0 3px rgba(13, 126, 74, 0.1);
            background: #fff;
        }

        .hint {
            font-size: 11px;
            color: #9CA3AF;
            text-align: center;
            margin-bottom: 1.5rem;
        }

        /* ── Bouton ── */
        button[type="submit"] {
            width: 100%;
            padding: 14px;
            background: #FF8000;
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s, transform 0.1s;
            letter-spacing: 0.3px;
        }

        button[type="submit"]:hover {
            background: #FF8000;
        }

        button[type="submit"]:active {
            transform: scale(0.99);
        }

        /* ── Pied ── */
        .card-footer {
            background: #F8FAFC;
            border-top: 1px solid #E2E8F0;
            padding: 1rem 2rem;
            text-align: center;
            font-size: 11px;
            color: #9CA3AF;
        }
    </style>
</head>

<body>

    <div class="card">

        {{-- En-tête institutionnel --}}
        <div class="header">
            <div class="republique-bloc">
                <b>REPUBLIQUE DU SENEGAL</b><br>
                Un Peuple – Un But – Une Foi<br>
                <b>Ministère de l'Emploi et de la Formation Professionnelle et Technique</b>
            </div>
            <img class="logo-onfp"
                src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/img/logo_sigle.png'))) }}"
                alt="Logo ONFP" />
        </div>

        {{-- Formulaire --}}
        <div class="card-body">
            <h2>Vérification attestations et titres</h2>
            <p class="subtitle">Saisissez le numéro figurant sous le QR code</p>

            @if (session('error'))
                <div class="alert-error">⚠️ {{ session('error') }}</div>
            @endif

            <form action="{{ route('attestation.verifier.numero.recherche') }}" method="GET">
                <div class="input-wrap">
                    <input type="text" name="numero" placeholder="AI-2025-0000002-B"
                        value="{{ old('numero') }}" autofocus required>
                </div>
                <p class="hint">Exemple : AI-2025-0000002-B</p>
                <button type="submit">Vérifier l'authenticité</button>
            </form>
        </div>

        {{-- Pied --}}
        <div class="card-footer">
            Office National de Formation Professionnelle — ONFP &copy; {{ date('Y') }}
        </div>

    </div>

</body>

</html>
