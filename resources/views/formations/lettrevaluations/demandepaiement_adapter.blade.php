<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8" />
    <title>{{ $title }}</title>

    <style>
        @page {
            margin: 2cm 2.5cm;
        }

        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 14px;
            color: #000;
            line-height: 1.4;
            margin: 0;
        }

        .container {
            max-width: 700px;
            margin: auto;
            padding: 0;
        }

        .header,
        .footer {
            text-align: center;
            margin-bottom: 20px;
        }

        .header .date {
            float: right;
            font-size: 12px;
        }

        .header .contact-info p {
            margin: 2px 0;
        }

        .clear {
            clear: both;
        }

        .title {
            text-align: center;
            font-weight: bold;
            font-size: 18px;
            margin: 30px 0 20px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .subtitle {
            font-weight: bold;
            margin-bottom: 15px;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }

        table th,
        table td {
            border: 1px solid #000;
            padding: 8px 10px;
            text-align: left;
        }

        table th {
            background: #eee;
        }

        .right {
            text-align: right;
        }

        .amount-net,
        .amount-IR,
        .amount-brut {
            font-weight: bold;
        }

        .signature {
            margin-top: 60px;
            text-align: center;
        }

        .signature p {
            margin-bottom: 60px;
        }

        /* Pour positionner QR code si besoin */
        .qr {
            float: right;
            margin-top: -100px;
        }

        .bank-box {
            border: 1px solid #000;
            padding: 10px 15px;
            margin-top: 15px;
            background: #f9f9f9;
            width: 95%;
        }

        .bank-box p {
            margin: 3px 0;
        }

        .bank-box-title {
            font-weight: bold;
            text-transform: uppercase;
            text-align: left;
            margin-bottom: 8px;
            font-size: 13px;
        }
    </style>
</head>

@foreach ($formation->evaluateurs as $evaluateur)

    <body>
        <div class="container">
            {{-- QR code affiché en haut à droite --}}
            {{-- @if (isset($qrCodeBase64))
            <div style="text-align: right; margin-top: 10px;">
                <img src="data:image/png;base64,{{ $qrCodeBase64 }}" width="100" alt="QR Code">
            </div>
        @endif --}}
            <div class="header">
                <div class="contact-info" style="float:left; width: 70%; text-align: left;">
                    <p><strong>Prénom :</strong> {{ $evaluateur?->name }}</p>
                    <p><strong>Nom :</strong> {{ $evaluateur?->lastname }}</p>
                    <p><strong>Titre :</strong> {{ $evaluateur?->fonction }}</p>
                    <p><strong>Téléphone :</strong> {{ $evaluateur?->telephone }}</p>
                </div>

                <div class="date" style="width: 30%; float:right; text-align: right;">
                    {{ $formation?->departement?->region?->nom }}, le
                    {{ $formation?->date_pv_finale?->translatedFormat('d F Y') ?? $formation?->date_pv?->translatedFormat('d F Y') }}
                </div>

                <div class="clear" style="clear: both;"></div>
            </div>


            <div class="header" style="margin-top: 30px;">
                <p><strong>Office National de Formation Professionnelle (ONFP)</strong></p>
            </div>

            <div class="header" style="float: left; text-align: left;">
                <p>
                    <u><b>Réf.</b></u> :
                    lettre de mission N°
                    {{ $evaluateur?->pivot?->numero_lettre ?: '..............................' }}/ONFP/DG/DEC/{{ $formation->onfpevaluateurs->first()?->initiale }}
                    du
                    {{ $evaluateur?->pivot?->date_lettre
                        ? \Carbon\Carbon::parse($evaluateur->pivot->date_lettre)->translatedFormat('d F Y')
                        : '..............................' }}
                </p>
            </div>
            <br>
            <br>
            <div class="title">Demande de Paiement</div>

            <div class="header">
                <p>Indemnités relatives à l’évaluation de la formation en
                    {{ $formation?->intitule ?? 'Aucun' }}
                    {{-- {{ $formation?->module->name ?? ($formation?->collectivemodule?->module ?? 'Aucun') }} --}}
                    en date du {{ $formation?->date_pv?->translatedFormat('d F Y') }}
                    @if (!empty($formation?->date_pv_finale))
                        au {{ $formation?->date_pv_finale?->translatedFormat('d F Y') }}
                    @endif
                    exécutée par l’opérateur {{ $formation?->operateur?->user?->operateur ?? ' ' }}
                </p>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Désignation</th>
                        <th style="width: 25%; border: 1px solid #000; padding: 8px; text-align: center;">Montant (F
                            CFA)
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $brut = $evaluateur?->pivot?->indemnite ?? 0;
                    $montant_ir = round($brut * 0.05);
                    $montant_net = $brut - $montant_ir;
                    
                    // 🔤 Conversion en lettres (via number-to-words)
                    $numberToWords = new NumberToWords\NumberToWords();
                    $numberTransformer = $numberToWords->getNumberTransformer('fr');
                    /* $montant_lettres = ucfirst($numberTransformer->toWords($brut)) . ' francs CFA'; */
                    $montant_lettres = $brut > 0 ? ucfirst($numberTransformer->toWords($brut)) . ' francs CFA' : 'Zéro franc CFA';
                    ?>
                    <tr>
                        <td>
                            Indemnités d’évaluation de la formation en
                            {{ $formation?->intitule ?? 'Aucun' }}
                            {{-- {{ $formation?->module?->name ?? ($formation?->collectivemodule?->module ?? 'Aucun') }} --}}
                            en date du {{ $formation?->date_pv?->translatedFormat('d F Y') }}
                            @if (!empty($formation?->date_pv_finale))
                                au {{ $formation?->date_pv_finale?->translatedFormat('d F Y') }}
                            @endif
                            <br><br>
                            <strong>Montant net</strong>
                        </td>
                        <td style="width: 25%; text-align: center;">{{ number_format($montant_net, 0, ',', ' ') }}</td>
                    </tr>
                    <tr>
                        <td>IR 5%</td>
                        <td style="width: 25%; text-align: center;">{{ number_format($montant_ir, 0, ',', ' ') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Montant brut</strong></td>
                        <td style="width: 25%; border: 1px solid #000; padding: 8px; text-align: center;">
                            <strong>{{ number_format($brut, 0, ',', ' ') }}</strong>
                        </td>
                    </tr>
                </tbody>
            </table>

            <p>
                <strong>Arrêté la présente demande de paiement à la somme de :</strong>
                {{ $montant_lettres }}
            </p>
            {{-- Ajout des informations bancaires si disponibles --}}
            @if ($evaluateur?->banque || $evaluateur?->numero_compte || $evaluateur?->rib)
                <div class="bank-box">
                    <div class="bank-box-title">Coordonnées bancaires</div>

                    @if ($evaluateur?->banque)
                        <p><strong>Banque :</strong> {{ $evaluateur->banque }}</p>
                    @endif

                    @if ($evaluateur?->numero_compte)
                        <p><strong>Numéro de compte :</strong> {{ $evaluateur->numero_compte }}</p>
                    @endif

                    @if ($evaluateur?->rib)
                        <p><strong>RIB :</strong> {{ $evaluateur->rib }}</p>
                    @endif
                </div>
            @endif

            <div class="signature" style="width: 35%; float:right; text-align: right;">
                <p style="text-align: right; font-style: italic">
                    <span>
                        <b>Prénom, Nom et Signature </b><br><br><br>
                        {{-- {{ $formation?->evaluateur?->name . ' ' . $formation?->evaluateur?->lastname }} --}}
                    </span>
                </p>
            </div>

            {{-- @if (isset($qrCodeBase64))
            <div style="width: 35%; float: left; text-align: left;">
                <img src="data:image/png;base64,{{ $qrCodeBase64 }}" width="100" alt="QR Code">
            </div>
        @endif --}}
        </div>
    </body>
@endforeach

</html>
