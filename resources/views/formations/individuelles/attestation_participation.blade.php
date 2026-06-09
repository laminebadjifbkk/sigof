<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8" />
    <title>Attestation de Participation – {{ $individuelle->user->firstname ?? '' }}
        {{ $individuelle->user->name ?? '' }}</title>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=EB+Garamond:ital,wght@0,400;0,600;0,700;1,400&family=Cormorant+Garamond:wght@300;400;600&display=swap');

        @media print {

            html,
            body {
                height: 100%;
                margin: 0;
                padding: 0;
            }

            .border-outer {
                min-height: 253.8mm;
                height: 253.8mm;
            }

            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .doc-address {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }

        @page {
            margin: 1.8cm 2.2cm;
            size: A4 portrait;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'EB Garamond', Georgia, 'Times New Roman', serif;
            font-size: 13.5px;
            color: #1a1a1a;
            line-height: 1.7;
            background: #fff;
        }

        /* ── Page pleine hauteur ── */
        .page {
            width: 100%;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
        }

        /* ── Cadre décoratif ── */
        .border-outer {
            border: 2.5px solid #19b363;
            padding: 5px;
            display: flex;
            flex-direction: column;
            height: 283.8mm;
            bottom: 0cm;
            /* ← une seule règle, supprimez min-height */
            overflow: hidden;
            /* ← évite tout débordement */
        }

        .border-inner {
            border: 1px solid #b8996e;
            padding: 28px 38px 0;
            position: relative;
            overflow: visible;
            /* important pour les coins */
            height: 273.8mm;
            flex: 1;
            display: flex;
            flex-direction: column;
            bottom: 0cm;
        }

        /* Coins décoratifs */
        .corner {
            position: absolute;
            width: 22px;
            height: 22px;
            border-color: #b8996e;
            border-style: solid;
        }

        .corner-tl {
            top: 6px;
            left: 6px;
            border-width: 2px 0 0 2px;
        }

        .corner-tr {
            top: 6px;
            right: 6px;
            border-width: 2px 2px 0 0;
        }

        .corner-bl {
            bottom: 6px;
            left: 6px;
            border-width: 0 0 2px 2px;
        }

        .corner-br {
            bottom: 6px;
            right: 6px;
            border-width: 0 2px 2px 0;
        }

        /* ── Contenu principal (prend tout l'espace disponible) ── */
        .content-area {
            flex: 1;
            display: flex;
            flex-direction: column;
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
            color: #b8996e;
            margin: 0 0 6px;
        }

        .title-ornament {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-top: 8px;
        }

        .title-ornament .line {
            height: 1px;
            width: 80px;
            background: linear-gradient(to right, transparent, #b8996e);
        }

        .title-ornament .line.right {
            background: linear-gradient(to left, transparent, #b8996e);
        }

        .title-ornament .diamond {
            width: 7px;
            height: 7px;
            background: #b8996e;
            transform: rotate(45deg);
        }

        /* ── Corps du texte ── */
        .body-text {
            font-size: 14.5px;
            line-height: 1.85;
            text-align: justify;
            margin-bottom: 16px;
            hyphens: auto;
        }

        .body-text p {
            margin: 0 0 14px;
            text-indent: 2em;
        }

        .body-text p:first-child {
            text-indent: 0;
        }

        .highlight {
            font-weight: 700;
            color: #19b363;
            border-bottom: 1px dotted #b8996e;
        }

        .fill {
            display: inline-block;
            min-width: 180px;
            border-bottom: 1px solid #333;
            text-align: center;
            font-style: italic;
            color: #555;
        }

        /* ── Formule de foi ── */
        .foi-text {
            font-size: 13px;
            font-style: italic;
            text-align: justify;
            color: #333;
            margin: 16px 0 0;
            padding: 13px 18px;
            border-left: 3px solid #b8996e;
            background: #faf8f4;
        }

        /* ── Espace flexible (pousse le footer vers le bas) ── */
        .spacer {
            flex: 1;
            min-height: 20px;
        }

        /* ── Séparateur pied de page ── */
        .footer-separator {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 0 0 22px;
        }

        .footer-separator .sep-line {
            flex: 1;
            height: 1px;
            background: linear-gradient(to right, transparent, #19b363 30%, #19b363 70%, transparent);
        }

        .footer-separator .sep-ornament {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .footer-separator .sep-diamond {
            width: 6px;
            height: 6px;
            background: #b8996e;
            transform: rotate(45deg);
        }

        .footer-separator .sep-diamond-sm {
            width: 4px;
            height: 4px;
            background: #19b363;
            transform: rotate(45deg);
        }

        /* ── Pied de page / Signature ── */
        .footer-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 20px;
            padding-bottom: 10px;
        }

        /* QR code */
        .qr-bloc {
            width: 90px;
            text-align: center;
            flex-shrink: 0;
        }

        .qr-bloc img {
            width: 80px;
            height: 80px;
        }

        .qr-bloc p {
            font-size: 8px;
            color: #aaa;
            margin: 2px 0 0;
            letter-spacing: 0.5px;
        }

        /* Ville + date */
        .city-date {
            font-size: 13px;
            font-style: italic;
            text-align: center;
            flex: 1;
            padding-top: 8px;
        }

        /* Bloc signature */
        .signature-block {
            text-align: center;
            min-width: 220px;
            flex-shrink: 0;
        }

        .signature-block .sig-title {
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #19b363;
            margin-bottom: 3px;
        }

        .signature-block .sig-subtitle {
            font-size: 11px;
            color: #666;
            font-style: italic;
            margin-bottom: 0;
        }

        /* Zone signature + cachet : espace généreux */
        .sig-space {
            height: 90px;
            border-bottom: 1px solid #555;
            margin: 8px 16px 0;
            position: relative;
        }

        .sig-space::after {
            content: 'Signature & Cachet';
            position: absolute;
            bottom: -18px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 9.5px;
            color: #999;
            font-style: italic;
            white-space: nowrap;
            letter-spacing: 0.5px;
        }

        /* ── Adresse / pied absolu ── */
        .doc-address {
            position: fixed;
            bottom: 15mm;
            left: 2.2cm;
            right: 2.2cm;

            padding-left: 2.2cm;
            padding-right: 2.2cm;

            font-size: 9.5px;
            color: #777;
            text-align: center;

            border-top: 1px dashed #c8b98f;
        }

        .page {
            height: calc(297mm - 3.6cm);
        }

        body {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .attestation-intro {
            display: block;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="page">
        <div class="border-outer">
            <div class="border-inner">

                <!-- Coins décoratifs -->
                <div class="corner corner-tl"></div>
                <div class="corner corner-tr"></div>
                <div class="corner corner-bl"></div>
                <div class="corner corner-br"></div>

                <!-- Contenu principal -->
                <div class="content-area">

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

                    <!-- Titre -->
                    <div class="doc-title">
                        <h1>Attestation de Participation</h1>
                        <div class="title-ornament">
                            <span class="line"></span>
                            <span class="diamond"></span>
                            <span class="line right"></span>
                        </div>
                    </div>

                    <!-- Corps -->
                    <div class="body-text">
                        <p class="attestation-intro">
                            Le Directeur général de l'<strong>Office National de Formation Professionnelle
                                (ONFP)</strong>
                            atteste que <br>
                            <span class="highlight">
                                {{ $individuelle?->user?->civilite ?? 'Monsieur/Madame' }}
                                {{ $individuelle?->user?->firstname ?? '……………………' }}
                                {{ $individuelle?->user?->name ?? '' }}
                            </span>,

                            @if ($individuelle?->user?->date_naissance)
                                né(e) le
                                <span
                                    class="highlight">{{ \Carbon\Carbon::parse($individuelle->user?->date_naissance)->translatedFormat('d F Y') }}</span>
                                à <span
                                    class="highlight">{{ $individuelle?->user?->lieu_naissance ?? '…………………' }}</span>,
                            @else
                                né(e) le <span class="fill">……………………</span>
                                à <span class="fill">……………………</span>,
                            @endif

                            a effectivement suivi la formation intitulée :
                        </p>

                        <p
                            style="text-align: center; text-indent: 0; font-size: 15px; font-weight: 700; color: #19b363; margin: 10px 0 16px; font-style: italic;">
                            «&nbsp;{{ $formation?->intitule ?? '………………………………………………………………' }}&nbsp;»
                        </p>

                        <p style="text-indent: 0;">
                            organisée du
                            <span
                                class="highlight">{{ $formation?->date_debut?->translatedFormat('d F Y') ?? '…………………' }}</span>
                            au
                            <span
                                class="highlight">{{ $formation?->date_fin?->translatedFormat('d F Y') ?? '…………………' }}</span>,
                            à <span class="highlight">{{ $formation?->lieu ?? '…………………………………' }}</span>
                            {{-- ,
                            exécutée par l'opérateur
                            <span
                                class="highlight">{{ $formation?->operateur?->user?->display_operateur ?? '…………………' }}</span> --}}.
                        </p>
                    </div>

                    <!-- Formule de foi -->
                    <div class="foi-text">
                        En foi de quoi, la présente attestation est délivrée pour certifier la participation
                        effective du bénéficiaire à ladite formation.
                    </div>

                    <!-- Espace flexible -->
                    <div class="spacer"></div>

                    <!-- Séparateur décoratif pied de page -->
                    <div class="footer-separator">
                        <div class="sep-line"></div>
                        <div class="sep-ornament">
                            <span class="sep-diamond-sm"></span>
                            <span class="sep-diamond"></span>
                            <span class="sep-diamond-sm"></span>
                        </div>
                        <div class="sep-line"></div>
                    </div>

                    <!-- Pied de page -->
                    <div class="footer-section">

                        <!-- Ville et date -->
                        <div class="city-date">
                            Fait à
                            <strong>{{ 'Dakar' }}</strong>,<br>
                            le
                            {{--  <strong>
                                {{ $formation?->date_pv_finale?->translatedFormat('d F Y') ??
                                    ($formation?->date_pv?->translatedFormat('d F Y') ?? '………………………') }}
                            </strong> --}}
                            <strong>
                                {{ $now->translatedFormat('d F Y') }}
                            </strong>
                        </div>

                        <!-- Bloc signature -->
                        <div class="signature-block">
                            <div class="sig-title">Le Directeur Général</div>
                            <div class="sig-subtitle">ou son représentant</div>
                            {{-- <div class="sig-space"></div> --}}
                        </div>

                        <!-- QR code -->
                        @if (isset($qrCodeBase64))
                            <div class="qr-bloc">
                                <img src="data:image/png;base64,{{ $qrCodeBase64 }}" alt="QR Code vérification" />
                                <p>Vérifier l'authenticité</p>
                            </div>
                        @else
                            <div style="width: 90px; flex-shrink: 0;"></div>
                        @endif
                    </div>

                    <!-- Adresse institutionnelle -->
                    <div class="doc-address">
                        Cité SIPRES 1 LOT 2 – 2 voies Liberté 6 extension VDN &nbsp;|&nbsp;
                        Tél : 33 827 92 51 &nbsp;|&nbsp; Fax : 33 827 92 55 &nbsp;|&nbsp;
                        B.P : 21013 Dakar Ponty &nbsp;|&nbsp;
                        onfp@onfp.sn &nbsp;|&nbsp; www.onfp.sn
                    </div>

                </div><!-- /content-area -->

            </div><!-- /border-inner -->
        </div><!-- /border-outer -->
    </div><!-- /page -->
</body>

</html>
