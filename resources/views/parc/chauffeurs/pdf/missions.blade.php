<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">

    <style>
        @page {
            size: A4 landscape;
            margin: 15px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #000;
        }

        h2 {
            text-align: center;
            margin-bottom: 10px;
        }

        p {
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid #000;
        }

        th,
        td {
            padding: 6px;
        }

        thead th {
            background: #d9d9d9;
            text-align: center;
            font-weight: bold;
        }

        .mois {
            background: #bfbfbf;
            font-weight: bold;
            text-transform: uppercase;
        }

        .total-mois {
            background: #efefef;
            font-weight: bold;
        }

        tfoot tr {
            background: #d0d0d0;
            font-weight: bold;
            font-size: 12px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }
    </style>

</head>

<body>

    <h2>Récapitulatif des missions</h2>

    <p>
        <strong>Chauffeur :</strong>
        {{ $chauffeur->employee->user->firstname }}
        {{ $chauffeur->employee->user->name }}
    </p>

    @php
        $numero = 1;
    @endphp

    <table>

        <thead>
            <tr>
                <th width="5%">N°</th>
                <th width="40%">Objet</th>
                <th width="20%">Période</th>
                <th width="10%">Nuitées</th>
                <th width="10%">Taux</th>
                <th width="15%">Montant</th>
            </tr>
        </thead>

        <tbody>

            @foreach ($missionsParMois as $mois => $missions)

                @php
                    $totalMoisNuitees = $missions->sum('nuitees');
                    $totalMoisMontant = $missions->sum('indemnites_total');
                @endphp

                <tr class="mois">
                    <td colspan="6">
                        {{ \Carbon\Carbon::parse($mois . '-01')->locale('fr')->translatedFormat('F Y') }}
                    </td>
                </tr>

                @foreach ($missions as $mission)

                    <tr>

                        <td class="text-center">
                            {{ $numero++ }}
                        </td>

                        <td>
                            {{ $mission->objet }}
                        </td>

                        <td class="text-center">
                            {{ $mission->periode_mission }}
                        </td>

                        <td class="text-center">
                            {{ $mission->nuitees }}
                        </td>

                        <td class="text-right">
                            {{ number_format($mission->taux_journalier, 0, ',', ' ') }}
                        </td>

                        <td class="text-right">
                            {{ number_format($mission->indemnites_total, 0, ',', ' ') }}
                            F CFA
                        </td>

                    </tr>

                @endforeach

                <tr class="total-mois">

                    <td colspan="3">
                        Total {{ \Carbon\Carbon::parse($mois . '-01')->locale('fr')->translatedFormat('F Y') }}
                    </td>

                    <td class="text-center">
                        {{ $totalMoisNuitees }}
                    </td>

                    <td></td>

                    <td class="text-right">
                        {{ number_format($totalMoisMontant, 0, ',', ' ') }}
                        F CFA
                    </td>

                </tr>

            @endforeach

        </tbody>

        <tfoot>

            <tr>

                <th colspan="3">
                    TOTAL GÉNÉRAL
                </th>

                <th class="text-center">
                    {{ $totalAnneeNuitees }}
                </th>

                <th></th>

                <th class="text-right">
                    {{ number_format($totalAnneeMontant, 0, ',', ' ') }}
                    F CFA
                </th>

            </tr>

        </tfoot>

    </table>

</body>

</html>