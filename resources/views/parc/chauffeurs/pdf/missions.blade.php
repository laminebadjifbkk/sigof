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
            margin-bottom: 15px;
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
            background: #efefef;
            text-align: center;
        }

        tbody td {
            vertical-align: middle;
        }

        tfoot th {
            background: #f5f5f5;
            font-weight: bold;
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

    <table>

        <thead>
            <tr>
                <th width="5%">N°</th>
                <th width="40%">Objet</th>
                <th width="20%">Période</th>
                <th width="10%">Nuitées</th>
                <th width="10%">Taux nuitée</th>
                <th width="15%">Montant</th>
            </tr>
        </thead>

        <tbody>

            @php
                $totalNuitees = 0;
                $totalMontant = 0;
            @endphp

            @foreach ($missions as $mission)
                @php
                    $totalNuitees += $mission->nuitees;
                    $totalMontant += $mission->indemnites_total;
                @endphp

                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>

                    <td>{{ $mission->objet }}</td>

                    <td class="text-center">
                        {{-- {{ $mission->date_depart->format('d/m/Y') }}
                        au
                        {{ $mission->date_retour->format('d/m/Y') }} --}}

                        {{ $mission->periode_mission }}
                    </td>

                    <td class="text-center">
                        {{ $mission->nuitees }}
                    </td>

                    <td class="text-right">
                        {{ number_format($mission->taux_journalier, 0, ',', ' ') }}
                    </td>

                    <td class="text-right">
                        {{ number_format($mission->indemnites_total, 0, ',', ' ') }} F CFA
                    </td>
                </tr>
            @endforeach

        </tbody>

        <tfoot>
            <tr>
                <th colspan="3">TOTAL</th>
                <th class="text-center">{{ $totalNuitees }}</th>
                <th></th>
                <th class="text-right">
                    {{ number_format($totalMontant, 0, ',', ' ') }} F CFA
                </th>
            </tr>
        </tfoot>

    </table>

</body>

</html>
