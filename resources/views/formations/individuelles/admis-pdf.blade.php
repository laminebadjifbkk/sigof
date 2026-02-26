<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>{{ $formation?->name }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin-top: 1cm;
            margin-bottom: 0cm;
        }

        .invoice-box {
            max-width: 1000px;
            margin: auto;
            /* padding: 30px; */
            font-size: 11px;
            line-height: 18px;
            color: rgb(0, 0, 0);
            ;
        }

        /** RTL **/
        .rtl {
            imputation: rtl;
        }

        .invoice-box table tr.heading td {
            background: rgb(255, 255, 255);
            border: 1px solid #000000;
            border-collapse: collapse;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table td,
        table th {
            border: 1px solid;
        }

        footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: #ffffff;
            color: #000;
            font-size: 10px;
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 6px 0;
            /* <-- Donne de la hauteur au footer */
            border-top: 2px solid #5D4037;
            /* ligne visible */
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


        .header-text {
            font-size: 10px;
            /* réduit la taille du texte, ajuste selon besoin */
            line-height: 1.2;
            /* compacité verticale */
            text-align: center;
        }

        .header-text b {
            font-size: 11px;
            /* tu peux ajuster pour les titres */
        }

        .header-text em {
            font-size: 9px;
            /* texte en italique plus petit */
        }

        body {
            margin: 0;
            padding-bottom: 30px;
            /* hauteur approximative du footer */
        }
    </style>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="//db.onlinewebfonts.com/c/dd79278a2e4c4a2090b763931f2ada53?family=ArialW02-Regular" rel="stylesheet"
        type="text/css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body>
    <div class="header-text">
        <b>REPUBLIQUE DU SENEGAL<br></b>
        <em class="text-muted">Un Peuple - Un But - Une Foi</em><br>
        <b>********<br>
            MINISTERE DE L'EMPLOI ET DE LA FORMATION <br> PROFESSIONNELLE ET TECHNIQUE<br>
            <br>
            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/img/logo-onfp.jpg'))) }}"
                style="width: 100%; max-width: 300px" />
        </b>
    </div>
    <div class="invoice-box">
        <table class="table table-bordered">
            <thead>
                <tr class="heading" style="text-align: center;">
                    <td colspan="8"><b>{{ __('RETRAIT DES ATTESTATIONS') }}</b></td>
                </tr>
                <tr class="heading">
                    <td colspan="4"><b>{{ __('Période: ') }}</b>
                        @isset($formation?->date_debut)
                            {{ 'du ' . $formation?->date_debut?->format('d/m/Y') }}
                        @endisset
                        @isset($formation?->date_fin)
                            {{ ' au ' . $formation?->date_fin?->format('d/m/Y') }}
                        @endisset
                    </td>
                    <td colspan="4"><b>{{ __('Intitulé formation: ') }}</b> {{ $formation?->intitule }}</td>
                </tr>
                <tr class="heading">
                    <td colspan="4"><b>{{ __('Lieu: ') }}</b> {{ $formation?->lieu }}</td>
                    <td colspan="4"><b>{{ __('Opérateur: ') }}</b>
                        {{ $formation?->operateur?->user?->operateur . ' (' . $formation?->operateur?->user?->username . ')' }}
                    </td>
                </tr>
                <tr class="heading">
                    <td colspan="2"><b>{{ __('Code : ') }}</b> {{ $formation?->code }}</td>
                    <td colspan="2"><b>{{ __('Niveau qualification : ') }}</b>
                        @if ($formation?->type_certification !== 'Titre')
                            {{ $formation?->titre ?? $formation?->referentiel?->titre }}
                        @else
                            @if (!empty($formation?->referentiel?->categorie))
                                {{ $formation?->referentiel?->categorie . ' de la ' . $formation?->referentiel?->convention?->name }}
                            @endif
                        @endif
                    </td>
                    <td colspan="4"><b>{{ __('Type certification : ') }}</b>
                        @if ($formation?->type_certification !== 'Titre')
                            {{ $formation?->type_certification }}
                        @else
                            {{ $formation?->referentiel?->titre }}
                        @endif
                    </td>
                </tr>

                <tr class="heading">
                    <td style="text-align: center; width: 3%;"><b>N°</b></td>
                    <td style="text-align: center; width: 12%;"><b>N° CIN</b></td>
                    <td style="text-align: center; width: 25%;"><b>Name</b></td>
                    <td style="text-align: center; width: 10%;"><b>Téléphone</b></td>
                    <td style="text-align: center; width: 10%;"><b>Appréciation</b></td>
                    <td style="text-align: center; width: 12%;"><b>Date retrait</b></td>
                    <td style="text-align: center; width: 12%;"><b>Signature bénéficiaire</b></td>
                    <td style="text-align: center;"><b>Commentaires</b></td>
                </tr>
            </thead>
            <tbody>
                @php $i = 1; @endphp
                @foreach ($individuelles as $individuelle)
                    <tr style="text-align: center;">
                        <td>{{ $i++ }}</td>
                        <td>{{ $individuelle->user->cin }}</td>
                        <td>{{ $individuelle->user->civilite . ' - ' . format_proper_name($individuelle->user->firstname) . ' - ' . remove_accents_uppercase($individuelle->user->name) . ' - ' . remove_accents_uppercase($individuelle->user->date_naissance?->format('d/m/Y')) . ' - ' . remove_accents_uppercase($individuelle->user->lieu_naissance) }}
                        </td>
                        <td>{{ $individuelle->user->telephone }}</td>
                        <td>{{ $individuelle?->appreciation }}</td>
                        <td></td> <!-- date retrait -->
                        <td></td> <!-- Signature bénéficiaire -->
                        <td></td> <!-- Commentaires -->
                    </tr>
                @endforeach

            </tbody>
        </table>
    </div>

    <footer>
        <p class="footer-text">
            Cité Sipres 1, Lot 2 - 2 voies liberté 6 extension VDN
            Tél: (+221) 33 827 92 51 - Fax: (+221) 33 827 92 55
            BP: 21013 Dakar-Ponty - Email: <a href="mailto:onfp@onfp.sn">onfp@onfp.sn</a>
        </p>
    </footer>
</body>

</html>
