<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attestation Authentique — Vérification</title>
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
            max-width: 560px;
            width: 100%;
            overflow: hidden;
        }

        /* Bandeau vert supérieur */
        .card-header {
            background: linear-gradient(135deg, #0D7E4A 0%, #12A362 100%);
            padding: 2rem 2rem 1.5rem;
            text-align: center;
            position: relative;
        }

        .shield-icon {
            width: 64px;
            height: 64px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 2rem;
        }

        .card-header h1 {
            color: #fff;
            font-size: 1.4rem;
            font-weight: 700;
            letter-spacing: -0.01em;
        }

        .card-header p {
            color: rgba(255, 255, 255, 0.85);
            font-size: 0.875rem;
            margin-top: 0.35rem;
        }

        /* Contenu */
        .card-body {
            padding: 2rem;
        }

        .participant-name {
            font-size: 1.5rem;
            font-weight: 700;
            color: #0D7E4A;
            margin-bottom: 0.25rem;
        }

        .participant-sub {
            font-size: 0.875rem;
            color: #6B7280;
            margin-bottom: 1.75rem;
        }

        .info-grid {
            display: grid;
            gap: 0.875rem;
        }

        .info-row {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 10px;
            padding: 0.875rem 1rem;
        }

        .info-icon {
            font-size: 1.1rem;
            margin-top: 0.1rem;
            flex-shrink: 0;
        }

        .info-label {
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #9CA3AF;
            display: block;
            margin-bottom: 0.2rem;
        }

        .info-value {
            font-size: 0.95rem;
            font-weight: 600;
            color: #1A2332;
        }

        /* Pied de page */
        .card-footer {
            background: #F8FAFC;
            border-top: 1px solid #E2E8F0;
            padding: 1.25rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .verified-badge {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.8rem;
            color: #0D7E4A;
            font-weight: 600;
        }

        .verified-dot {
            width: 8px;
            height: 8px;
            background: #0D7E4A;
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.4;
            }
        }

        .verified-time {
            font-size: 0.75rem;
            color: #9CA3AF;
        }

        /* ── En-tête ── */
        .header {
            text-align: center;
            margin-bottom: 18px;
            padding-bottom: 16px;
            border-bottom: 0px solid #c8b07a;
        }

        .republique-bloc {
            font-size: 11.5px;
            line-height: 1.6;
            color: #1a1a1a;
            text-align: center;
            margin-bottom: 8px;
        }

        .republique-bloc b {
            font-size: 12px;
            letter-spacing: 0.5px;
        }

        .logo-onfp {
            display: block;
            margin: 8px auto 4px;
            width: 100%;
            max-width: 190px;
        }
    </style>
</head>

<body>

    <div class="card">
        <!-- En-tête officiel -->
        <div class="header">
            <div class="republique-bloc">
                <b>REPUBLIQUE DU SENEGAL</b><br>
                Un Peuple – Un But – Une Foi<br>
                <b>
                    *************<br>
                    MINISTERE DE L'EMPLOI ET DE LA FORMATION PROFESSIONNELLE ET TECHNIQUE<br>
                    *************
                </b><br>
                <img class="logo-onfp"
                    src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/img/logo_sigle.png'))) }}"
                    alt="Logo ONFP" />
            </div>
        </div>

        {{-- <div class="card-header">
            <div class="shield-icon">✅</div>
            <h1>Attestation Authentique</h1>
            <p>Ce document a été vérifié avec succès</p>
        </div> --}}

        <!-- Titre -->
        <div class="doc-title">
            <h1>Attestation Authentique</h1>
            <p>Ce document a été vérifié avec succès</p>
            <div class="title-ornament">
                <span class="line"></span>
                <span class="diamond"></span>
                <span class="line right"></span>
            </div>
        </div>

        <div class="card-body">
            <div class="participant-name">
                {{ $individuelle->user->firstname }} {{ $individuelle->user->name }}
            </div>
            <p class="participant-sub">A bien participé à la formation suivante</p>

            <div class="info-grid">
                <div class="info-row">
                    <span class="info-icon">📋</span>
                    <div>
                        <span class="info-label">Formation</span>
                        <span class="info-value">{{ $formation->name }}</span>
                    </div>
                </div>

                <div class="info-row">
                    <span class="info-icon">🔖</span>
                    <div>
                        <span class="info-label">Code</span>
                        <span class="info-value">{{ $formation->code }}</span>
                    </div>
                </div>

                @if (isset($moduleName))
                    <div class="info-row">
                        <span class="info-icon">📚</span>
                        <div>
                            <span class="info-label">Module</span>
                            <span class="info-value">{{ $moduleName }}</span>
                        </div>
                    </div>
                @endif

                <div class="info-row">
                    <span class="info-icon">📅</span>
                    <div>
                        <span class="info-label">Période</span>
                        <span class="info-value">
                            {{ $formation->date_debut?->format('d/m/Y') }}
                            — {{ $formation->date_fin?->format('d/m/Y') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <div class="verified-badge">
                <span class="verified-dot"></span>
                Signature cryptographique valide
            </div>
            <span class="verified-time">
                Vérifié le {{ \Carbon\Carbon::now()->format('d/m/Y à H:i') }}
            </span>
        </div>
    </div>

</body>

</html>
