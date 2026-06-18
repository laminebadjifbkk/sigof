{{-- <!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8" />
    <title>Attestation de Participation – {{ $listecollective->prenom ?? '' }}
        {{ $listecollective->nom ?? '' }}</title>

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
            border: 2.5px solid #b8996e;
            padding: 5px;
            display: flex;
            flex-direction: column;
            height: 283.8mm;
            bottom: 0cm;
            overflow: hidden;
        }

        .border-inner {
            border: 1px solid #b8996e;
            padding: 28px 38px 0;
            position: relative;
            overflow: visible;
            height: 273.8mm;
            flex: 1;
            display: flex;
            flex-direction: column;
            bottom: 0cm;
        }

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

        .content-area {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

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
            color: #1a3a5c;
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
        .spacer {
            flex: 1;
            min-height: 20px;
        }

        .footer-separator {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 0 0 22px;
        }

        .footer-separator .sep-line {
            flex: 1;
            height: 1px;
            background: linear-gradient(to right, transparent, #1a3a5c 30%, #1a3a5c 70%, transparent);
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
            background: #1a3a5c;
            transform: rotate(45deg);
        }

        .footer-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 20px;
            padding-bottom: 10px;
        }

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

        .city-date {
            font-size: 13px;
            font-style: italic;
            text-align: center;
            flex: 1;
            padding-top: 8px;
        }

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
            color: #1a3a5c;
            margin-bottom: 3px;
        }

        .signature-block .sig-subtitle {
            font-size: 11px;
            color: #666;
            font-style: italic;
            margin-bottom: 0;
        }

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
                                {{ $listecollective?->civilite ?? 'Monsieur/Madame' }}
                                {{ $listecollective?->prenom ?? '……………………' }}
                                {{ $listecollective?->nom ?? '' }}
                            </span>,

                            @if ($listecollective?->date_naissance)
                                né(e) le
                                <span
                                    class="highlight">{{ \Carbon\Carbon::parse($listecollective?->date_naissance)->translatedFormat('d F Y') }}</span>
                                à <span class="highlight">{{ $listecollective?->lieu_naissance ?? '…………………' }}</span>,
                            @else
                                né(e) le <span class="fill">……………………</span>
                                à <span class="fill">……………………</span>,
                            @endif

                            a effectivement suivi la formation intitulée :
                        </p>

                        <p
                            style="text-align: center; text-indent: 0; font-size: 15px; font-weight: 700; color: #1a3a5c; margin: 10px 0 16px; font-style: italic;">
                            «&nbsp;{{ $formation?->intitule ?? '………………………………………………………………' }}&nbsp;»
                        </p>

                        <p style="text-indent: 0;">
                            organisée
                            {{ $formation->periode_formatee }}
                            à <span class="highlight">{{ $formation?->lieu ?? '…………………………………' }}</span>.
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
                            <strong>
                                {{ $now->translatedFormat('d F Y') }}
                            </strong>
                        </div>

                        <!-- Bloc signature -->
                        <div class="signature-block">
                            <div class="sig-title">Le Directeur Général</div>
                            <div class="sig-subtitle">ou son représentant</div>
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
 --}}

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Attestation de Réussite — – {{ $listecollective->prenom ?? '' }}
        {{ $listecollective->nom ?? '' }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @page {
            size: A4 landscape;
            margin: 0;
        }

        body {
            width: 297mm;
            height: 210mm;
            font-family: Candara, Calibri, "Trebuchet MS", Arial, sans-serif;
            background: #fff;
            overflow: hidden;
        }

        /* ── Contour image (cadre doré) ── */
        .page {
            position: relative;
            width: 297mm;
            height: 210mm;
            background: #fff;
        }

        .border-img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: fill;
            z-index: 1;
        }

        /* ── Contenu principal ── */
        .content {
            position: absolute;
            top: 8mm;
            left: 10mm;
            right: 10mm;
            bottom: 8mm;
            z-index: 2;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* ── En-tête institutionnel ── */
        .header {
            text-align: center;
            margin-top: 4mm;
            line-height: 1.5;
        }

        .header .republique {
            font-size: 9pt;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: #111;
            font-weight: normal;
        }

        .header .devise {
            font-size: 8pt;
            color: #333;
            letter-spacing: 1px;
        }

        .header .ministere {
            font-size: 7.5pt;
            color: #111;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 1mm;
        }

        .header .onfp {
            font-size: 13pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 4px;
            color: #111;
            margin-top: 1.5mm;
        }

        /* ── Logo ONFP ── */
        .logo-wrap {
            margin-top: 3mm;
            margin-bottom: 8mm;
            display: flex;
            justify-content: center;
            text-align: center;
        }

        .logo-wrap img {
            height: 30mm;
            object-fit: contain;
        }

        /* ── Titre Attestation ── */
        .titre-attestation {
            font-size: 41pt;
            color: #C8972A;
            /* font-style: italic; */
            font-weight: normal;
            letter-spacing: 2px;
            margin-top: 2mm;
            font-family: 'Old London', serif;
            text-align: center;
            margin-bottom: 10mm;
        }

        /* ── Zone centrale : cercles décoratifs + texte ── */
        .body-zone {
            position: relative;
            width: 100%;
            flex: 1;
            margin-top: 15px;
        }

        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-repeat: no-repeat;
            background-position: center;
            pointer-events: none;
        }

        .watermark-1 {
            width: 75mm;
            height: 75mm;
            opacity: 0.08;
            z-index: 1;
            background-size: contain;
            background-image: url("data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/img/img1.png'))) }}");
        }

        .watermark-2 {
            width: 55mm;
            height: 55mm;
            opacity: 0.15;
            z-index: 2;
            background-size: contain;
            background-image: url("data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/img/img2.png'))) }}");
        }

        /* Cercle décoratif en filigrane centré */
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 55mm;
            height: 55mm;
            opacity: 0.12;
            z-index: 0;
            pointer-events: none;
        }

        .watermark img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        /* Texte du corps */
        .corps {
            position: relative;
            z-index: 2;
            width: 100%;
            padding: 0;
            font-size: 12pt;
            color: #111;
            line-height: 1.85;
        }

        .corps .intro {
            font-size: 13pt;
            font-weight: bold;
            font-style: italic;
            font-family: Candara, Calibri, "Trebuchet MS", Arial, sans-serif;
            letter-spacing: 3px;
            text-align: center;
            /* ajuste ici (1px à 4px selon rendu) */
        }

        .corps .text-intro {
            font-size: 12pt;
            font-weight: normal;
            font-family: Candara, Calibri, "Trebuchet MS", Arial, sans-serif;
            letter-spacing: 3px;
            text-align: center;
            /* ← centrage */
            /* margin-right: 10mm ← SUPPRIMÉ */
        }

        .corps .nom-participant {
            font-weight: bold;
        }

        .corps .formation-intitule {
            font-weight: bold;
            font-style: normal;
        }

        /* ── Pied : date + signature ── */
        /* ── Pied : date + signature ───────────────────────────── */
        .footer {
            position: absolute;
            right: 20mm;
            bottom: 50mm;
            /* augmente cette valeur */
            /* ajuste selon ton cadre */
            width: auto;

            display: flex;
            justify-content: flex-end;
            align-items: flex-end;
        }

        /* Bloc signature */
        .signature-block {
            text-align: center;
            font-family: Candara, Calibri, "Trebuchet MS", Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.2;
            margin: 0;
            padding: 0;
        }

        /* Date */
        .signature-block .fait-le {
            font-weight: bold;
            font-style: italic;
            margin-top: 12mm;
            position: relative;
            right: 36mm;
            /* ajuste la valeur */
        }

        /* Fonction */
        .signature-block .titre-sig {
            font-size: 12pt;
            font-weight: normal;
            font-weight: bold;
            margin: 5mm 0 38mm 0;
        }

        /* Nom */
        .signature-block .nom-sig {
            font-size: 12pt;
            font-weight: bold;
            font-style: italic;
            margin: 0;
            padding: 0;
            line-height: 1;
        }

        /* Supprime tout espace sous le dernier élément */
        .signature-block>*:last-child {
            margin-bottom: 0 !important;
        }

        .watermark-container {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 80mm;
            height: 80mm;
            z-index: 1;
        }

        .watermark-img {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .watermark-img1 {
            width: 75mm;
            opacity: 0.08;
        }

        .watermark-img2 {
            width: 55mm;
            opacity: 0.15;
        }

        .watermark-container {
            position: absolute;

            left: 50%;
            top: 115mm;
            /* centre exact de la page A4 paysage */

            transform: translate(-50%, -50%);

            width: 90mm;
            height: 90mm;

            z-index: 2;
        }

        .watermark-img {
            position: absolute;
            top: 60%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .watermark-img1 {
            width: 65mm;
            opacity: 0.48;
        }

        .watermark-img2 {
            width: 35mm;
            opacity: 0.80;
        }

        .text-intro .line-one,
        .text-intro .line-two {
            display: block;
            width: 100%;
            white-space: nowrap;
            overflow: hidden;
            text-align: center;
            letter-spacing: 2.5px;
            /* ← redondant mais explicite */
        }

        /* ── QR code ── */
        .qr-zone {
            position: absolute;
            bottom: 10mm;
            left: 13mm;
            /* au lieu de margin-left, plus fiable en position absolute */
            z-index: 1;
            width: 30mm;
            /* largeur fixe = empêche le débordement/troncature du texte */
            text-align: center;
        }

        .qr-zone img {
            width: 22mm;
            height: 22mm;
            display: block;
            margin: 0 auto;
            padding: 0;
        }

        .qr-zone .qr-label {
            font-size: 5.5pt;
            color: #000000;
            margin: 1mm 0 0 0;
            letter-spacing: 0.3px;
        }

        .qr-zone .text-intro-numero {
            width: auto;
            white-space: nowrap;
            color: #000000;
            font-family: Candara, Calibri, "Trebuchet MS", Arial, sans-serif;
            margin: 1mm 0 0 0;
            /* au lieu de 62mm : colle le numéro sous le label */
            padding: 0;
            line-height: 1.1;
            text-align: center;
            letter-spacing: 0.5px;
            font-size: 9pt;
            font-weight: bold;
            font-style: italic;
        }

        .signature-block .titre-sig {
            font-size: 12pt;
            font-weight: bold;
            margin: 5mm 0 2mm 0;
            /* 38mm → 2mm : "ou son représentant" doit suivre de près */
        }

        .signature-block .ou-representant {
            font-size: 10pt;
            font-weight: normal;
            font-style: italic;
            margin: 0;
            /* plus besoin du -3mm compensatoire */
            padding: 0;
            line-height: 1;
        }
    </style>
</head>

<body>

    <div class="page">

        {{-- Cadre doré (contour_complet.png) --}}
        <img class="border-img"
            src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/img/contour_simple.png'))) }}"
            alt="">

        <div class="watermark-container">
            {{-- <img class="watermark-img watermark-img1"
                src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/img/img1.png'))) }}" > --}}

            <img class="watermark-img watermark-img2"
                src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/img/img2.png'))) }}">
        </div>

        {{-- QR code en bas à gauche --}}
        {{-- <div class="qr-zone">
            <img src="data:image/png;base64,{{ $qrCodeBase64 }}" alt="QR Code">
            <p class="qr-label"><strong>Vérifier l'authenticité</strong></p>
            <p class="text-intro-numero"><strong>{{ $listecollective?->numero_attestation }}</strong></p>
        </div> --}}

        <div class="content">

            {{-- En-tête --}}
            <div class="header">
                <div class="republique">République du Sénégal</div>
                <div class="devise">Un Peuple &bull; Un But &bull; Une Foi</div>
                <div class="ministere">Ministère de l'Emploi et de la Formation Professionnelle et Technique
                </div>
                <div class="onfp">Office National de Formation Professionnelle</div>
            </div>

            {{-- Logo --}}
            <div class="logo-wrap">
                <img class="logo-onfp"
                    src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/img/logo_sigle.png'))) }}"
                    alt="Logo ONFP" />
            </div>

            {{-- Titre --}}
            <div class="titre-attestation">Attestation de participation</div>

            {{-- Corps --}}
            <div class="body-zone">

                <div class="corps">
                    <p class="intro">
                        Le Directeur général de l'Office national de Formation professionnelle (ONFP) atteste que
                    </p>
                    <p class="text-intro">
                        <span class="line-one">
                            @if ($listecollective->civilite ?? null)
                                {{ $listecollective->civilite }}
                            @endif
                            <span class="nom-participant">{{ $listecollective->prenom }}
                                {{ $listecollective->nom }}</span>
                            @if ($listecollective->date_naissance ?? null)
                                né(e) le
                                {{ \Carbon\Carbon::parse($listecollective->date_naissance)->format('d/m/Y') }}
                            @endif
                            @if ($listecollective->lieu_naissance ?? null)
                                à {{ $listecollective->lieu_naissance }}
                            @endif
                            a effectivement suivi la formation en
                        </span>
                        <span class="line-two">
                            <span class="formation-intitule">{{ $formation->intitule }}</span>
                            organisée {{ $formation->periode_formatee }}
                            @if ($formation->lieu ?? null)
                                à {{ strtoupper($formation->lieu) }}.
                            @else
                                .
                            @endif
                        </span>
                    </p>
                    <p class="text-intro">
                        En foi de quoi, la présente attestation est délivrée pour certifier la participation
                        effective du bénéficiaire à ladite formation.
                    </p>
                    {{-- <p class="text-intro-numero"><strong>{{ $listecollective?->numero_attestation }}</strong></p> --}}
                </div>
            </div>

            {{-- Pied --}}
            <div class="footer">
                <div class="signature-block">
                    <div class="fait-le">
                        Fait à Dakar, le {{ $now->translatedFormat('d F Y') }}
                    </div>
                    <div class="titre-sig">Le Directeur général</div>
                    <div class="ou-representant">ou son représentant</div>
                </div>
            </div>

        </div>{{-- /content --}}

    </div>{{-- /page --}}
    <script>
        function fitTextToLine(selector) {
            document.querySelectorAll(selector).forEach(function(el) {

                const probe = document.createElement('span');
                probe.style.cssText = [
                    'position:absolute',
                    'visibility:hidden',
                    'white-space:nowrap',
                    'font-family:' + window.getComputedStyle(el).fontFamily,
                    'letter-spacing:' + window.getComputedStyle(el).letterSpacing,
                    'font-weight:' + window.getComputedStyle(el).fontWeight,
                ].join(';');
                probe.innerHTML = el.innerHTML;
                document.body.appendChild(probe);

                const corps = el.closest('.corps');
                const corpsStyle = window.getComputedStyle(corps);
                const maxWidth = corps.clientWidth -
                    parseFloat(corpsStyle.paddingLeft) -
                    parseFloat(corpsStyle.paddingRight);

                // Partir d'une taille de base fixe pour être cohérent
                let fontSize = 12;
                probe.style.fontSize = fontSize + 'px';

                // 1. Agrandir jusqu'à remplir 98% de la largeur
                while (probe.offsetWidth < maxWidth * 0.98 && fontSize < 60) {
                    fontSize += 0.5;
                    probe.style.fontSize = fontSize + 'px';
                }

                // 2. Reculer si on a dépassé
                while (probe.offsetWidth > maxWidth && fontSize > 6) {
                    fontSize -= 0.5;
                    probe.style.fontSize = fontSize + 'px';
                }

                document.body.removeChild(probe);
                el.style.fontSize = fontSize + 'px';
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            fitTextToLine('.text-intro .line-one');
            fitTextToLine('.text-intro .line-two');
        });
    </script>
</body>

</html>
