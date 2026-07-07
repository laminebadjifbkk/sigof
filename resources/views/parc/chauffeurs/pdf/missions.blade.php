<h2 style="text-align:center">
    Récapitulatif des missions
</h2>

<p>
    <strong>Chauffeur :</strong>
    {{ $chauffeur->employee->user->firstname }}
    {{ $chauffeur->employee->user->name }}
</p>

<table width="100%" border="1" cellspacing="0" cellpadding="6">

    <thead>

        <tr style="background:#efefef">

            <th>N°</th>
            <th>Référence</th>
            <th>Période</th>
            <th>Nuitées</th>
            <th>Taux</th>
            <th>Montant</th>

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

                <td>{{ $loop->iteration }}</td>

                <td>{{ $mission->reference }}</td>

                <td>
                    {{ $mission->date_depart->format('d/m/Y') }}
                    -
                    {{ $mission->date_retour->format('d/m/Y') }}
                </td>

                <td align="center">
                    {{ $mission->nuitees }}
                </td>

                <td align="right">
                    {{ number_format($mission->taux_journalier, 0, ',', ' ') }}
                </td>

                <td align="right">
                    {{ number_format($mission->indemnites_total, 0, ',', ' ') }}
                </td>

            </tr>
        @endforeach

    </tbody>

    <tfoot>

        <tr>

            <th colspan="3">TOTAL</th>

            <th>{{ $totalNuitees }}</th>

            <th></th>

            <th>
                {{ number_format($totalMontant, 0, ',', ' ') }} F CFA
            </th>

        </tr>

    </tfoot>

</table>
