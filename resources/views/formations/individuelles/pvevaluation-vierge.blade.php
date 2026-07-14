<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>{{ $title }}</title>
    {{-- <style>
        @page {
            margin: 0cm 0cm;
        }

        .invoice-box {
            max-width: 1000px;
            margin: auto;
            padding: 30px;
            font-size: 12px;
            line-height: 20px;
            color:rgb(0, 0, 0);
            ;
        }

        .rtl {
            imputation: rtl;
        }

        .invoice-box table tr.heading td {
            background: rgb(255, 255, 255);
            border: 1px solid #000000;
            font-weight: bold;
        }

        .invoice-box table tr.total td {
            border-top: 2px solid #eee;
            border-bottom: 1px solid #eee;
            border-left: 1px solid #eee;
            border-right: 1px solid #eee;
            background: #eee;
            font-weight: bold;
        }

        .invoice-box table tr.item td {
            border: 1px solid #000000;
        }

        table {
            border-left: 0px solid rgb(0, 0, 0);
            border-right: 0;
            border-top: 0px solid rgb(0, 0, 0);
            border-bottom: 0;
            width: 100%;
            border-spacing: 0px;
        }

        table td,
        table th {
            border-left: 0;
            border-right: 0px solid rgb(0, 0, 0);
            border-top: 0;
            border-bottom: 0px solid rgb(0, 0, 0);
        }
    </style> --}}
    <style>
        /* @page {
            size: 21cm 29.7cm;
            margin-top: 1cm;
            margin-bottom: 0cm;
        } */

        @page {
            size: A4 landscape;
            margin-top: 1cm;
            margin-bottom: 2.2cm;
            /* espace réservé pour le footer sur CHAQUE page */
        }

        .invoice-box {
            max-width: 1000px;
            margin: auto;
            /* padding: 30px; */
            font-size: 10px;
            line-height: 13px;
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

        .invoice-box table tr.total td {
            /* border-top: 2px solid #eee;
            border-bottom: 1px solid #eee;
            border-left: 1px solid #eee;
            border-right: 1px solid #eee; */
            /* background: #eee;
            font-weight: normal; */
        }

        /* .invoice-box table tr.item td {
            border: 1px solid #000000;
        } */

        table {
            /* border-left: 0px solid rgb(0, 0, 0);
            border-right: 0;
            border-top: 0px solid rgb(0, 0, 0);
            border-bottom: 0; */
            width: 100%;
            /* border-spacing: 0px; */
            border-collapse: collapse;
        }

        table td,
        table th {
            /* border-left: 0;
            border-right: 0px solid rgb(0, 0, 0);
            border-top: 0;
            border-bottom: 0px solid rgb(0, 0, 0); */
            border: 1px solid;
        }

        footer {
            position: fixed;
            bottom: -1.8cm;
            /* ajuster pour repositionner dans la marge réservée */
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

        /*éviter qu'une ligne du tableau soit coupée entre deux pages*/
        table tr {
            page-break-inside: avoid;
        }

        /*Réduire les marges des blocs signature*/
        .no-page-break h4 {
            margin-top: 1mm;
            margin-bottom: 1mm;
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
            MINISTERE DE L'EMPLOI ET DE LA FORMATION PROFESSIONNELLE ET TECHNIQUE<br>
            <br>
            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/img/logo-onfp.jpg'))) }}"
                style="width: 100%; max-width: 300px" />
        </b>
    </div>
    <div class="invoice-box"
        style="font-size: {{ $compact ? '9px' : '11px' }}; line-height: {{ $compact ? '11px' : '15px' }};">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr class="heading" style="text-align: center;">
                        <td colspan="11"><b>{{ __("PROCES VERBAL D'EVALUATION DE FORMATION") }}</b>
                        </td>
                    </tr>
                    <tr class="heading">
                        <td colspan="5"><b>{{ __('Période: ') }}</b>
                            @if ($formation?->date_debut)
                                {{ 'du ' . $formation->date_debut->format('d/m/Y') }}
                            @endif

                            @if ($formation?->date_fin)
                                {{ ' au ' . $formation->date_fin->format('d/m/Y') }}
                            @endif
                        </td>
                        <td colspan="6"><b>{{ __('Intitulé formation: ') }}</b> {{ $formation?->intitule }}
                        </td>
                    </tr>
                    <tr class="heading">
                        <td colspan="5"><b>{{ __('Lieu: ') }}</b> {{ $formation?->lieu }}
                        </td>
                        <td colspan="6"><b>{{ __('Opérateur: ') }}</b>
                            {{ $formation?->operateur?->user?->display_operateur }}
                        </td>
                    </tr>
                    <tr class="heading">
                        <td colspan="5">
                            <b>{{ __('Niveau qualification :') }}</b>
                            {{ $formation->niveauQualificationAffichage() }}
                        </td>
                        <td colspan="6"><b>{{ __('Type certification : ') }}</b>
                            @if ($formation?->type_certification !== 'Titre')
                                {{ $formation?->type_certification }}
                            @else
                                {{ $formation?->referentiel?->titre }}
                            @endif
                        </td>
                    </tr>

                    <tr class="heading">
                        <td colspan="5"><b>{{ __('Code : ') }}</b>
                            {{ $formation?->code . 'C' }}
                        </td>
                        <td colspan="6"><b>{{ __('Bénéficiaires : ') }}</b>
                            {{ $formation?->name }}
                        </td>
                    </tr>

                    <tr class="heading">
                        {{--  <td colspan="7">
                        <b>{{ __('Ingénieur en charge : ') }}</b>{{ $formation?->ingenieur?->name . '(' . $formation?->ingenieur?->initiale . ')' }}
                    </td> --}}
                        <td rowspan="2" class="item" style="text-align: center; width: 3%;"><b>N°</b></td>
                        <td rowspan="2" class="item" style="text-align: center; width: 12%;"><b>N° CIN</b></td>
                        <td rowspan="2" class="item" style="text-align: center; width: 5%;"><b>Civilité</b></td>
                        <td rowspan="2" class="item" style="text-align: center;"><b>Prénom</b></td>
                        <td rowspan="2" class="item" style="text-align: center;"><b>NOM</b></td>
                        <td rowspan="2" class="item" style="text-align: center; width: 10%;"><b>Date nais.</b></td>
                        <td rowspan="2" class="item" style="text-align: center; width: 17%;"><b>Lieu naissance</b></td>
                        <td rowspan="2" class="item" style="text-align: center; width: 10%;"><b>Téléphone</b></td>
                        <td colspan="3" style="text-align: center;"><b>{{ __('DECISION DU JURY') }}</b>
                        </td>
                    </tr>
                    <tr class="item" style="text-align: center;">
                        <td style="text-align: center; width: 5%;"><b>Note</b></td>
                        <td style="width: 12%;"><b>Niveau maitrise</b></td>
                        <td><b>Observations</b></td>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    @foreach ($formation->individuelles as $individuelle)
                        <tr class="item" style="text-align: center;">
                            <td>{{ $i++ }}</td>
                            <td>{{ $individuelle->user->cin }}</td>
                            <td>{{ $individuelle?->user?->civilite }}</td>
                            <td>{{ format_proper_name($individuelle?->user?->firstname) }}</td>
                            <td>{{ remove_accents_uppercase($individuelle?->user?->name) }}</td>
                            <td>{{ $individuelle?->user?->date_naissance?->format('d/m/Y') }}</td>
                            <td>{{ remove_accents_uppercase($individuelle?->user?->lieu_naissance) }}</td>
                            <td>{{ $individuelle?->user?->telephone }}</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{-- <div class="no-page-break">
            <h4 style="margin-top: 2mm;">
                <b><u>SIGNATURE DES MEMBRES DU JURY</u></b>
                @if ($formation?->date_pv)
                    <span style="float: right; font-style: italic">
                        {{ 'Fait à ' . remove_accents_uppercase($formation?->lieu ?? '') . ', le ........................................................' }}
                    </span>
                @endif
            </h4>
        </div> --}}
        {{-- <div class="no-page-break">

            <h4 style="margin-top: 2mm;">
                <b><u>SIGNATURE DES MEMBRES DU JURY</u></b>

                @if ($dateSignature)
                    <span style="float: right; font-style: italic">
                        {{ 'Fait à ' . remove_accents_uppercase($formation?->lieu ?? '') . ', le ' . $dateSignature->translatedFormat('d F Y') }}
                    </span>
                @endif
            </h4>
            <div style="margin-top: 0; padding-top: 0;">
                <div class="table-responsive">
                    <table class="table-noborder" style="width: 100%;">
                        <tbody>
                            @foreach ($evaluateurs->chunk(3) as $trio)
                                <tr>
                                    @foreach ($trio as $personne)
                                        <td style="width: 30%;">
                                            <div class="d-flex align-items-start mb-0">
                                                <div>
                                                    <strong>{{ $personne->name }} {{ $personne->lastname }}</strong>
                                                    @if ($personne->fonction)
                                                        <br><em class="text-muted">{{ $personne->fonction }}</em>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="border-bottom" style="height: 15px;"></div>
                                        </td>
                                    @endforeach

                                    @for ($i = $trio->count(); $i < 3; $i++)
                                        <td style="width: 30%;"></td>
                                    @endfor
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>


                <div class="table-responsive">
                    <table class="table-noborder" style="width: 100%;">
                        <tbody>
                            @if (!empty($membres_jury))
                                @foreach (collect($membres_jury)->chunk(2) as $ligne)
                                    <tr>
                                        @foreach ($ligne as $item)
                                            <td style="width: 50%; vertical-align: top; padding-bottom: 1rem;">
                                                <div class="d-flex align-items-start mb-1">
                                                    <i class="bi bi-people-fill text-dark me-2 mt-1"></i>
                                                    <div><strong>{{ $item }}</strong></div>
                                                </div>
                                                <div style="height: 40px;"></div>
                                            </td>
                                        @endforeach

                                        @if (count($ligne) < 2)
                                            <td></td>
                                        @endif
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div> --}}
        <table class="table-noborder" style="width: 100%; margin-top: 2mm;">
            <tr>
                <td colspan="3" style="padding: 0 0 3mm 0;">
                    <b><u>SIGNATURE DES MEMBRES DU JURY</u></b>
                    @if ($dateSignature)
                        <span style="float: right; font-style: italic">
                            {{ 'Fait à ' . remove_accents_uppercase($formation?->lieu ?? '') . ', le ' . $dateSignature->translatedFormat('d F Y') }}
                        </span>
                    @endif
                </td>
            </tr>
            @foreach ($evaluateurs->chunk(3) as $trio)
                <tr style="page-break-inside: avoid;">
                    @foreach ($trio as $personne)
                        <td style="width: 33%; vertical-align: top; padding: 2mm 3mm;">
                            <strong>{{ $personne->name }} {{ $personne->lastname }}</strong>
                            @if ($personne->fonction)
                                <br><em>{{ $personne->fonction }}</em>
                            @endif
                            {{-- <div style="border-bottom: 1px solid #000; height: 12px; margin-top: 3mm;"></div> --}}
                        </td>
                    @endforeach
                    @for ($i = $trio->count(); $i < 3; $i++)
                        <td style="width: 33%;"></td>
                    @endfor
                </tr>
            @endforeach

            @if (!empty($membres_jury))
                @foreach (collect($membres_jury)->chunk(2) as $ligne)
                    <tr style="page-break-inside: avoid;">
                        @foreach ($ligne as $item)
                            <td colspan="2" style="width: 50%; vertical-align: top; padding: 2mm 3mm;">
                                <strong>{{ $item }}</strong>
                                <div style="height: 18px;"></div>
                            </td>
                        @endforeach
                        @if (count($ligne) < 2)
                            <td colspan="2"></td>
                        @endif
                    </tr>
                @endforeach
            @endif
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
