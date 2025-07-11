<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>{{ $title }}</title>
    <style>
        @page {
            margin: 0cm 0cm;
        }

        .invoice-box {
            max-width: 1000px;
            margin: auto;
            /* padding: 30px; */
            font-size: 12px;
            line-height: 18px;
            color:rgb(0, 0, 0);
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
            bottom: 0cm;
            left: 0cm;
            right: 0cm;
            height: 2cm;

            /** Extra personal styles **/
            background-color: #ffffff;
            color: rgb(0, 0, 0);
            text-align: center;
            line-height: 1.5cm;
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
    <div style="text-align: center;">
        <b>REPUBLIQUE DU SENEGAL<br></b>
        Un Peuple - Un But - Une Foi<br>
        <b>********<br>
            MINISTERE DE LA FORMATION PROFESSIONNELLE ET TECHNIQUE<br>
            ********<br><br>
            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/img/logo-onfp.jpg'))) }}"
                style="width: 100%; max-width: 300px" />
        </b>
    </div>
    <div class="invoice-box">
        <table class="table table-responsive">
            <thead>
                <tr class="heading" style="text-align: center;">
                    <td colspan="9"><b>{{ __('FEUILLE DE PRÉSENCE QUOTIDIENNE') }}</b>
                    </td>
                </tr>
                <tr class="heading">
                    <td colspan="4">{{ __('Code : ') }}
                        @if (!empty($formation?->code))
                            {{ $formation?->code . 'C' }}
                        @endif
                    </td>
                    <td colspan="2"><b>{{ __('Responsable suivi : ') }}</b>
                        @if (!empty($formation?->date_suivi))
                            {{ $formation?->suivi_dossier }}
                        @endif
                    </td>
                    <td colspan="1"><b>{{ __('Date : ') }}</b>
                        @if (!empty($emargementcollective?->date))
                            {{ $emargementcollective?->date?->format('d/m/Y') }}
                        @endif
                    </td>
                    <td colspan="2"><b>{{ $emargementcollective?->jour }}</b>
                    </td>
                </tr>
                <tr class="heading">
                    <td colspan="4">{{ __('Intitulé formation : ') }}
                        {{ $formation?->collectivemodule?->module }}
                    </td>
                    <td colspan="5"><b>{{ __('Opérateur : ') }}</b>
                        {{ $formation?->operateur?->user?->operateur . ' (' . $formation?->operateur?->user?->username . ')' }}
                    </td>
                </tr>
                <tr class="heading">
                    <td colspan="4">{{ __('Adresse : ') }}
                        {{ $formation?->lieu }}
                    </td>
                    <td colspan="5"><b>{{ __('Contact : ') }}</b>
                        {{-- {{ substr($formation?->operateur?->user?->fixe, 0, 2) .
                            ' ' .
                            substr($formation?->operateur?->user?->fixe, 2, 3) .
                            ' ' .
                            substr($formation?->operateur?->user?->fixe, 5, 2) .
                            ' ' .
                            substr($formation?->operateur?->user?->fixe, 7, 2) }}
                        @if (!empty($formation?->operateur?->user?->telephone))
                            {{ ' / ' .
                                substr($formation?->operateur?->user?->telephone, 0, 2) .
                                ' ' .
                                substr($formation?->operateur?->user?->telephone, 2, 3) .
                                ' ' .
                                substr($formation?->operateur?->user?->telephone, 5, 2) .
                                ' ' .
                                substr($formation?->operateur?->user?->telephone, 7, 2) }}
                        @endif --}}
                        {{ $formation?->operateur?->user?->fixe }}
                        @if (!empty($formation?->operateur?->user?->telephone))
                            {{ ' / ' . $formation?->operateur?->user?->telephone }}
                        @endif
                    </td>
                </tr>
                <tr class="item" style="text-align: center;">
                    <td width="3%"><b>N°</b></td>
                    <td><b>CIN</b></td>
                    {{-- <td><b>Civilité</b></td> --}}
                    <td><b>Prénom</b></td>
                    <td><b>NOM</b></td>
                    <td width="8%"><b>Date naissance</b></td>
                    <td><b>Lieu de naissance</b></td>
                    <td width="8%"><b>Téléphone</b></td>
                    <td width="8%"><b>Présence</b></td>
                    <td><b>Emargement</b></td>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; ?>
                @foreach ($feuillepresencecollectives as $feuillepresencecollective)
                    <tr class="item" style="text-align: center;">
                        <td>{{ $i++ }}</td>
                        <td>{{ $feuillepresencecollective?->listecollective?->cin }}</td>
                        {{-- <td>{{ $individuelle?->user?->civilite }}</td> --}}
                        <td>{{ format_proper_name($feuillepresencecollective?->listecollective?->prenom) }}</td>
                        <td>{{ remove_accents_uppercase($feuillepresencecollective?->listecollective?->nom) }}</td>
                        <td>{{ $feuillepresencecollective?->listecollective?->date_naissance?->format('d/m/Y') }}</td>
                        <td>{{ remove_accents_uppercase($feuillepresencecollective?->listecollective?->lieu_naissance) }}
                        </td>
                        <td>
                            {{ $feuillepresencecollective?->listecollective?->telephone }}
                        </td>
                        <td>
                            {{ ucwords(in_array($feuillepresencecollective?->emargementcollectives_id, $feuillepresenceListecollective) ? $feuillepresencecollective?->presence : '') }}
                        </td>
                        <td></td>
                    </tr>
                @endforeach

            </tbody>
        </table>
    </div>
</body>

</html>
