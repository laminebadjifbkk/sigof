<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>{{ $title }}</title>
    <style>
        /* @page {
            size: 21cm 29.7cm;
            margin-top: 1cm;
            margin-bottom: 0cm;
        } */

        @page {
            size: A4 landscape;
            margin-top: 1cm;
            margin-bottom: 0cm;
        }

        .invoice-box {
            max-width: 1000px;
            margin: auto;
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
        <table class="table table-responsive">
            <thead>
                <tr class="heading" style="text-align: center;">
                    <td colspan="11"><b>{{ __("PROCES VERBAL D'EVALUATION DE FORMATION") }}</b>
                    </td>
                </tr>
                <tr class="heading">
                    <td colspan="5"><b>{{ __('Période : ') }}</b>
                        @isset($formation?->date_debut)
                            {{ 'Du ' . $formation?->date_debut?->format('d/m/Y') }}
                        @endisset
                        @isset($formation?->date_fin)
                            {{ ' au ' . $formation?->date_fin?->format('d/m/Y') }}
                        @endisset
                    </td>
                    <td colspan="6"><b>{{ __('Intitulé formation : ') }}</b>
                        {{ $formation?->intitule }}
                    </td>
                </tr>
                <tr class="heading">
                    <td colspan="5"><b>{{ __('Lieu : ') }}</b> {{ $formation?->lieu }}
                    </td>
                    <td colspan="6"><b>{{ __('Opérateur : ') }}</b>
                        {{ $formation?->operateur?->user?->operateur . ' (' . $formation?->operateur?->user?->username . ')' }}
                    </td>
                </tr>
                <tr class="heading">
                    <td colspan="2"><b>{{ __('Code : ') }}</b>
                        {{ $formation?->code . 'C' }}
                    </td>
                    <td colspan="4"><b>{{ __('Niveau qualification : ') }}</b>
                        @if ($formation?->type_certification !== 'Titre')
                            {{ $formation?->titre ?? $formation?->referentiel?->titre }}
                        @else
                            @if (!empty($formation?->referentiel?->categorie))
                                {{ $formation?->referentiel?->categorie . ' de la ' . $formation?->referentiel?->convention?->name }}
                            @endif
                        @endif
                    </td>
                    <td colspan="5"><b>{{ __('Type certification : ') }}</b>
                        @if ($formation?->type_certification !== 'Titre')
                            {{ $formation?->type_certification }}
                        @else
                            {{ $formation?->referentiel?->titre }}
                        @endif
                    </td>
                </tr>
                <tr class="heading">
                    {{--  <td colspan="7">
                        <b>{{ __('Ingénieur en charge : ') }}</b>{{ $formation?->ingenieur?->name . '(' . $formation?->ingenieur?->initiale . ')' }}
                    </td> --}}
                    <td rowspan="2" class="item" style="text-align: center; width: 2%;"><b>N°</b></td>
                    <td rowspan="2" class="item" style="text-align: center; width: 10%;"><b>N° CIN</b></td>
                    <td rowspan="2" class="item" style="text-align: center; width: 5%;"><b>Civilité</b></td>
                    <td rowspan="2" class="item" style="text-align: center;"><b>Prénom</b></td>
                    <td rowspan="2" class="item" style="text-align: center;"><b>NOM</b></td>
                    <td rowspan="2" class="item" style="text-align: center; width: 8%;"><b>Date nais.</b></td>
                    <td rowspan="2" class="item" style="text-align: center;"><b>Lieu naissance</b>
                    </td>
                    <td rowspan="2" class="item" style="text-align: center; width: 8%;"><b>Téléphone</b></td>
                    <td colspan="3" style="text-align: center;"><b>{{ __('DECISION DU JURY') }}</b>
                    </td>
                </tr>
                <tr class="item" style="text-align: center;">
                    <td style="text-align: center;"><b>Note</b></td>
                    <td style="width: 12%;"><b>Niveau maitrise</b></td>
                    <td><b>Observations</b></td>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; ?>
                @foreach ($formation->listecollectives->where('statut', 'formé') as $listecollective)
                    <tr class="item" style="text-align: center;">
                        <td>{{ $i++ }}</td>
                        <td>{{ $listecollective?->cin }}</td>
                        <td>{{ $listecollective->civilite }}</td>
                        <td>{{ format_proper_name($listecollective?->prenom) }}</td>
                        <td>{{ remove_accents_uppercase($listecollective?->nom) }}</td>
                        <td>{{ $listecollective?->date_naissance?->format('d/m/Y') }}</td>
                        <td>{{ remove_accents_uppercase($listecollective?->lieu_naissance) }}</td>
                        <td>{{ $listecollective?->telephone }}</td>
                        <td>{{ $listecollective?->note_obtenue ?? '' }}</td>
                        <td>{{ $listecollective?->appreciation }}</td>
                        <td>{{ $listecollective?->observations }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="no-page-break">
            <h4 style="margin-top: 2mm;">
                <b><u>SIGNATURE DES MEMBRES DU JURY</u></b>
                @if ($formation?->date_pv)
                    <span style="float: right; font-style: italic">
                        @if ($formation?->date_pv_finale)
                            {{ 'Fait à ' . remove_accents_uppercase($formation?->lieu ?? '') . ', le ' . $formation?->date_pv_finale?->translatedFormat('d F Y') }}
                        @else
                            {{ 'Fait à ' . remove_accents_uppercase($formation?->lieu ?? '') . ', le ' . $formation?->date_pv?->translatedFormat('d F Y') }}
                        @endif
                    </span>
                @endif
            </h4>
            <div style="margin-top: 0; padding-top: 0;">
                {{-- Table des évaluateurs (3 par ligne) --}}
                <table class="table-noborder" style="width: 100%;">
                    <tbody>
                        @php
                            $evaluateurs = collect($formation?->evaluateurs)->merge($formation?->onfpevaluateurs);
                        @endphp

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

                                {{-- Compléter la ligne s'il y a moins de 3 évaluateurs --}}
                                @for ($i = $trio->count(); $i < 3; $i++)
                                    <td style="width: 30%;"></td>
                                @endfor
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- Table des autres membres du jury --}}
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
