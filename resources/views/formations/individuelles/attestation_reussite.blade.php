<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Attestation de Réussite — ONFP</title>
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
            font-size: 35pt;
            color: #C8972A;
            /* font-style: italic; */
            font-weight: normal;
            letter-spacing: 2px;
            margin-top: 2mm;
            font-family: 'Old London', serif;
            text-align: center;
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

        .corps {
            position: relative;
            z-index: 3;
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
            z-index: 1;
            width: 100%;
            padding: 0 6mm;
            font-size: 10.5pt;
            color: #111;
            text-align: justify;
            line-height: 1.85;
        }

        .corps p {
            margin-bottom: 3mm;
        }

        .corps .intro {
            font-weight: bold;
            font-style: italic;
        }

        .corps .nom-participant {
            font-weight: bold;
        }

        .corps .formation-intitule {
            font-weight: bold;
            font-style: italic;
        }

        /* ── Pied : date + signature ── */
        .footer {
            width: 100%;
            padding: 0 8mm;
            display: flex;
            justify-content: flex-end;
            align-items: flex-start;
            margin-top: 2mm;
            margin-bottom: 3mm;
        }

        .signature-block {
            text-align: center;
            font-size: 10pt;
        }

        .signature-block .fait-le {
            font-weight: bold;
            font-style: italic;
            margin-bottom: 8mm;
        }

        .signature-block .titre-sig {
            font-size: 9.5pt;
            margin-bottom: 14mm;
        }

        .signature-block .nom-sig {
            font-style: italic;
            font-size: 10pt;
            font-weight: normal;
        }

        /* ── QR code ── */
        .qr-zone {
            position: absolute;
            bottom: 10mm;
            left: 14mm;
            z-index: 3;
            text-align: center;
        }

        .qr-zone img {
            width: 22mm;
            height: 22mm;
        }

        .qr-zone p {
            font-size: 5.5pt;
            color: #666;
            margin-top: 1mm;
            letter-spacing: 0.3px;
        }

        .body-zone {
            position: relative;
            width: 100%;
            flex: 1;
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

        .corps {
            position: relative;
            z-index: 2;
        }

        .body-zone {
            position: relative;
            width: 100%;
            flex: 1;
        }

        .watermark-container {
            position: absolute;

            left: 50%;
            top: 105mm;
            /* centre exact de la page A4 paysage */

            transform: translate(-50%, -50%);

            width: 90mm;
            height: 90mm;

            z-index: 2;
        }

        .watermark-img {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .watermark-img1 {
            width: 85mm;
            opacity: 0.24;
        }

        .watermark-img2 {
            width: 55mm;
            opacity: 0.40;
        }
    </style>
</head>

<body>

    <div class="page">

        {{-- Cadre doré (contour_complet.png) --}}
        <img class="border-img"
            src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/img/contour_complet.png'))) }}"
            alt="">

        <div class="watermark-container">
            <img class="watermark-img watermark-img1"
                src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/img/img1.png'))) }}">

            <img class="watermark-img watermark-img2"
                src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/img/img2.png'))) }}">
        </div>

        {{-- QR code en bas à gauche --}}
        <div class="qr-zone">
            <img src="data:image/png;base64,{{ $qrCodeBase64 }}" alt="QR Code">
            <p>Vérifier l'authenticité</p>
        </div>

        <div class="content">

            {{-- En-tête --}}
            <div class="header">
                <div class="republique">République du Sénégal</div>
                <div class="devise">Un Peuple &bull; Un But &bull; Une Foi</div>
                <div class="ministere">Ministère de la Formation Professionnelle, de l'Apprentissage et de l'Insertion
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
            <div class="titre-attestation">A t t e s t a t i o n</div>

            {{-- Corps --}}
            <div class="body-zone">

                <div class="corps">
                    <p class="intro">
                        Le Directeur général de l'Office national de Formation professionnelle (ONFP) atteste que
                    </p>
                    <p>
                        @if ($individuelle->user->civilite ?? null)
                            «{{ $individuelle->user->civilite }}»
                        @endif
                        <span class="nom-participant">
                            «{{ $individuelle->user->firstname }}» «{{ $individuelle->user->name }}»
                        </span>
                        @if ($individuelle->user->date_naissance ?? null)
                            né(e) le
                            «{{ \Carbon\Carbon::parse($individuelle->user->date_naissance)->format('d/m/Y') }}»
                        @endif
                        @if ($individuelle->user->lieu_naissance ?? null)
                            à «{{ $individuelle->user->lieu_naissance }}»
                        @endif
                        a suivi avec succès la formation
                        en <span class="formation-intitule">«{{ $moduleName }}»</span>
                        qui s'est déroulée
                        du {{ $formation->date_debut?->format('d/m/Y') }}
                        au {{ $formation->date_fin?->format('d/m/Y') }}
                        @if ($formation->localite ?? null)
                            à {{ strtoupper($formation->localite->name) }}.
                        @else
                            .
                        @endif
                    </p>
                    <p>
                        En foi de quoi, la présente attestation lui est délivrée pour servir et valoir ce que de droit.
                    </p>
                </div>
            </div>

            {{-- Pied --}}
            <div class="footer">
                <div class="signature-block">
                    <div class="fait-le">Fait le {{ $now->format('d') }} {{ $now->translatedFormat('F Y') }}</div>
                    <div class="titre-sig">Le Directeur général</div>
                    <div class="nom-sig">Mamadou Mounirou LY</div>
                </div>
            </div>

        </div>{{-- /content --}}

    </div>{{-- /page --}}

</body>

</html>
