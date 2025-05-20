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
            line-height: 15px;
            color: color: rgb(0, 0, 0);
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
            bottom: 0cm;
            left: 0cm;
            right: 0cm;
            height: 2cm;

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
                    <td colspan="10"><b>{{ __('LISTE DES CANDIDATS SELECTIONNES') }}</b></td>
                </tr>
                <tr class="heading">
                    <td colspan="6"><b>{{ $projet?->type_projet }}</b> : {{ $projet?->name }} ({{ $projet?->sigle }})
                    </td>
                    <td colspan="4"><b>{{ __('Module : ') }}</b> {{ $projetmodule->module }}</td>
                </tr>

                <tr class="heading">
                    <td class="item" style="text-align: center;"><b>N°</b></td>
                    <td class="item" style="text-align: center;"><b>CIN</b></td>
                    <td class="item" style="text-align: center;"><b>Civilité</b></td>
                    <td class="item" style="text-align: center;"><b>Prénom</b></td>
                    <td class="item" style="text-align: center;"><b>NOM</b></td>
                    <td class="item" style="text-align: center;"><b>Date naissance</b></td>
                    <td class="item" style="text-align: center;"><b>Lieu de naissance</b></td>
                    <td class="item" style="text-align: center;"><b>Téléphone</b></td>
                    <td class="item" style="text-align: center;"><b>Localité</b></td>
                    <td class="item" style="text-align: center;"><b>Rang</b></td>
                </tr>
            </thead>
            <tbody>
                @php
                    $i = 1;
                    $rang = 1;
                    $precedenteNote = null;
                    $compteur = 0;
                    $rangAffiche = 1;
                    $rangLimiteDepasse = false;

                    $formatRangFr = function ($rang) {
                        return $rang === 1 ? '1er' : $rang . 'ème';
                    };
                @endphp

                @foreach ($individuelles as $individuelle)
                    @php
                        $noteActuelle = $individuelle->note;

                        // Nouveau rang si note différente
                        if ($noteActuelle !== $precedenteNote) {
                            // On vérifie si on a dépassé le rang 25
                            if ($rang > 25) {
                                $rangLimiteDepasse = true;
                                break;
                            }

                            $rangAffiche = $rang;
                            $compteur = 1;
                        } else {
                            $compteur++;
                        }

                        $precedenteNote = $noteActuelle;
                        $rang++;
                    @endphp

                    <tr class="item" style="text-align: center;">
                        <td>{{ $i++ }}</td>
                        <td>{{ $individuelle->user->cin }}</td>
                        <td>{{ $individuelle?->user?->civilite }}</td>
                        <td>{{ format_proper_name($individuelle?->user?->firstname) }}</td>
                        <td>{{ remove_accents_uppercase($individuelle?->user?->name) }}</td>
                        <td>{{ $individuelle?->user?->date_naissance?->format('d/m/Y') }}</td>
                        <td>{{ remove_accents_uppercase($individuelle?->user?->lieu_naissance) }}</td>
                        <td>{{ $individuelle?->user?->telephone }}</td>
                        <td>{{ $individuelle->departement->nom }}</td>
                        <td>
                            {{ $formatRangFr($rangAffiche) }}
                            @if ($compteur > 1)
                                <sup>(exæquo)</sup>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>

        </table>
    </div>
</body>

</html>
