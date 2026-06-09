<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attestation Invalide — Vérification</title>
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
            background: #FEF2F2;
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

        .card-header {
            background: linear-gradient(135deg, #B91C1C 0%, #DC2626 100%);
            padding: 2rem 2rem 1.5rem;
            text-align: center;
        }

        .alert-icon {
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
        }

        .card-header p {
            color: rgba(255, 255, 255, 0.85);
            font-size: 0.875rem;
            margin-top: 0.35rem;
        }

        .card-body {
            padding: 2rem;
        }

        .warning-box {
            background: #FEF2F2;
            border: 1px solid #FECACA;
            border-left: 4px solid #DC2626;
            border-radius: 8px;
            padding: 1.25rem;
            margin-bottom: 1.5rem;
        }

        .warning-box p {
            font-size: 0.9rem;
            color: #7F1D1D;
            line-height: 1.6;
        }

        .reasons-title {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            color: #9CA3AF;
            margin-bottom: 0.75rem;
        }

        .reason-list {
            list-style: none;
            display: grid;
            gap: 0.5rem;
        }

        .reason-list li {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            font-size: 0.875rem;
            color: #374151;
            background: #F9FAFB;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            padding: 0.65rem 0.875rem;
        }

        .reason-list li::before {
            content: "—";
            color: #DC2626;
            font-weight: 700;
            flex-shrink: 0;
        }

        .card-footer {
            background: #F9FAFB;
            border-top: 1px solid #E5E7EB;
            padding: 1.25rem 2rem;
            text-align: center;
        }

        .card-footer p {
            font-size: 0.8rem;
            color: #6B7280;
        }

        .card-footer strong {
            color: #1A2332;
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


        /* ── Titre principal ── */
        .doc-title {
            text-align: center;
            margin: 16px 0 24px;
        }

        .doc-title h1 {
            font-family: 'Cormorant Garamond', serif;
            font-size: 26px;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #ff0000;
            margin: 0 0 6px;
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
        <div class="alert-icon">⛔</div>
        <h1>Document Non Valide</h1>
        <p>Cette attestation n'a pas pu être authentifiée</p>
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
            <div class="warning-box">
                <p>
                    La vérification cryptographique de ce document a <strong>échoué</strong>.
                    Ce document est peut-être falsifié, altéré ou son lien a expiré.
                </p>
            </div>

            <p class="reasons-title">Causes possibles</p>
            <ul class="reason-list">
                <li>Le QR code a été modifié ou recréé manuellement</li>
                <li>Le contenu du document a été altéré après émission</li>
                <li>Le lien de vérification est corrompu ou incomplet</li>
                <li>Document émis par un système non autorisé</li>
            </ul>
        </div>

        <div class="card-footer">
            <p>
                Pour toute question, contactez l'organisme émetteur.<br>
                <strong>Ne pas accepter ce document comme preuve valide.</strong>
            </p>
        </div>
    </div>

</body>

</html>
