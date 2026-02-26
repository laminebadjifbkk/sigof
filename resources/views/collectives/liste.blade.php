<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>{{ $title }}</title>

    <!-- Favicons -->
    <link href="{{ asset('assets/img/favicon-onfp.png') }}" rel="icon">
    <link href="{{ asset('assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">
    <style>
        @page {
            size: A4 landscape;
            /* Définir le format A4 en paysage */
            margin: 1cm 1.5cm;
        }

        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #000;
            line-height: 1.4;
            margin: 0;
        }

        .section {
            margin-bottom: 30px;
        }

        .container {
            max-width: 1000px;
            /* Augmentée pour mieux utiliser la largeur paysage */
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
            font-size: 12px;
            margin: 30px 0 20px;
            text-transform: none;
            letter-spacing: 1px;
        }

        .subtitle {
            font-weight: bold;
            margin-bottom: 15px;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 10px;
            /* plus petit qu'avant */
        }

        table th,
        table td {
            border: 1px solid #000;
            padding: 4px 6px;
            text-align: left;
            font-size: 10px;
        }

        table th {
            background: #eee;
        }

        footer {
            position: fixed;
            bottom: 0cm;
            left: 0cm;
            right: 0cm;
            height: 0.5cm;

            background-color: #ffffff;
            color: #000;
            font-size: 12px;
            font-family: Arial, sans-serif;
            text-align: center;

            z-index: 1000;
        }

        .page-number {
            position: relative;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            align-items: center;
            padding-bottom: 0.2cm;
        }

        /* La ligne est élargie pour coller au format paysage */
        .footer-line {
            width: 25cm;
            /* adapté au format paysage (29,7 cm - marges) */
            height: 2px;
            background-color: #5D4037;
            margin: 0 auto 2mm auto;
        }

        /* Nettoyage et espacement */
        .footer-text {
            margin: 0;
            padding: 1mm 0 0 0;
            line-height: 1.4;
            max-width: 27cm;
        }

        .table-noborder,
        .table-noborder td,
        .table-noborder tr {
            border: none !important;
            padding: 0.3rem;
        }

        .table-noborder {
            width: 100%;
            border-collapse: collapse;
        }

        .no-page-break {
            page-break-inside: avoid;
            break-inside: avoid;
            /* pour compatibilité avec certains moteurs */
        }

        @media print {
            .landscape {
                page: landscape;
            }
        }
    </style>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
@php
    $modulesAvecCandidats = $collective?->collectivemodules
        ->filter(fn($m) => $m->listecollectives?->isNotEmpty())
        ->values(); // réindexe
@endphp

<body>
    @foreach ($modulesAvecCandidats as $index => $collectivemodule)
        {{-- ===== En-tête ===== --}}
        <div class="table-responsive">
            <table cellpadding="0" cellspacing="0" width="100%" style="border: none;">
                <tbody>
                    <tr>
                        <td valign="top" width="40%" style="text-align: left; border: none;">
                            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/img/logo-onfp.jpg'))) }}"
                                style="width:100%; max-width:250px;" />
                        </td>

                        <td valign="top" width="60%" style="border:none;">
                            <h4 style="margin-top:0;"><u>LISTE DES CANDIDATS</u></h4>

                            <p style="margin:0;">
                                <strong><u>Nom de la structure</u> :</strong>
                                {{ $collectivemodule->collective?->name }}
                            </p>
                            <p style="margin:0;">
                                <strong><u>Formation sollicitée</u> :</strong>
                                {{ $collectivemodule->module }}
                            </p>
                            <p style="margin:0;">
                                <strong><u>Niveau de qualification demandé</u> :</strong>
                                {{ $collectivemodule->niveau_qualification }}
                            </p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- ===== Tableau candidats ===== --}}
        @if ($collectivemodule->listecollectives->isNotEmpty())
            <div class="table-responsive">
                <div class="section landscape mt-0">
                    <table cellspacing="0" cellpadding="5" width="100%">
                        <thead>
                            <tr>
                                <th>N°</th>
                                <th>Prénom(s)</th>
                                <th>Nom</th>
                                <th>Date Naissance</th>
                                <th>Lieu Naissance</th>
                                <th>N° CIN</th>
                                <th>Téléphone</th>
                                <th>Niveau d'étude</th>
                                <th>Expérience</th>
                                <th>Autres expériences</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($collectivemodule->listecollectives as $i => $candidat)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $candidat->prenom }}</td>
                                    <td>{{ $candidat->nom }}</td>
                                    <td>{{ optional($candidat->date_naissance)->format('d/m/Y') }}</td>
                                    <td>{{ $candidat->lieu_naissance }}</td>
                                    <td>{{ $candidat->cin }}</td>
                                    <td>{{ $candidat->telephone }}</td>
                                    <td>{{ $candidat->niveau_etude }}</td>
                                    <td>{{ $candidat->experience }}</td>
                                    <td>{{ $candidat->autre_experience }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        {{-- ===== Footer ===== --}}
        <footer>
            <div class="page-number">
                <div class="footer-line"></div>
                <p class="footer-text">
                    Cité Sipres 1, Lot 2 - 2 voies liberté 6 extension VDN
                    Tel: (+221) 33 827 92 51 - Fax: (+221) 33 827 92 55
                    BP: 21013 Dakar-Ponty – Email: onfp@onfp.sn
                </p>
            </div>
        </footer>

        {{-- Saut de page uniquement si ce n’est pas le dernier module --}}
        @if (!$loop->last)
            <div style="page-break-after: always;"></div>
        @endif
    @endforeach
</body>

</html>
