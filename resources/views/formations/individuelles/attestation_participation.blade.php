<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Attestation de réussite, {{ $individuelle->user->firstname ?? '' }}
        {{ $individuelle->user->name ?? '' }}</title>
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
            letter-spacing: 2px;
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
            <p class="text-intro-numero"><strong>{{ $individuelle->user?->numero_attestation }}</strong></p>
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
                            @if ($individuelle->user->civilite ?? null)
                                {{ $individuelle->user->civilite }}
                            @endif
                            <span class="nom-participant">{{ $individuelle->user->firstname }}
                                {{ $individuelle->user->name }}</span>
                            @if ($individuelle->user->date_naissance ?? null)
                                né(e) le
                                {{ \Carbon\Carbon::parse($individuelle->user->date_naissance)->format('d/m/Y') }}
                            @endif
                            @if ($individuelle->user->lieu_naissance ?? null)
                                à {{ $individuelle->user->lieu_naissance }}
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
                        En foi de quoi, la présente attestation est délivrée pour attester de la participation effective
                        du bénéficiaire à la formation susmentionnée.
                    </p>
                    {{-- <p class="text-intro-numero"><strong>{{ $individuelle->user?->numero_attestation }}</strong></p> --}}
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
