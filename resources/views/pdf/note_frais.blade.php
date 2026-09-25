<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>{{ $title ?? 'Note de frais' }}</title>
    <style>
        @page {
            margin: 0cm 0cm;
        }

        .invoice-box {
            max-width: 700px;
            margin: auto;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            font-size: 13px;
            line-height: 20px;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        }

        table.lignes {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table.lignes th,
        table.lignes td {
            border: 1px solid #333;
            padding: 5px;
            font-size: 12px;
        }

        table.lignes th {
            background: #eee;
            font-weight: bold;
            text-align: center;
        }

        table.lignes td.montant,
        table.lignes th.montant {
            text-align: right;
        }

        table.lignes tr.groupe td {
            background: #f5f5f5;
            font-weight: bold;
        }

        table.lignes tr.sous-total td {
            font-weight: bold;
        }

        table.lignes tr.total td {
            font-weight: bold;
            background: #eee;
        }

        footer {
            position: fixed;
            bottom: 0cm;
            left: 0cm;
            right: 0cm;
            height: 1.5cm;
            background-color: #ffffff;
            color: #000;
            font-size: 12px;
            font-family: Arial, sans-serif;
            text-align: center;
            z-index: 1000;
        }

        .footer-line {
            width: 18cm;
            height: 2px;
            background-color: #5D4037;
            margin: 0 auto;
        }

        .footer-text {
            margin: 0;
            padding: 0.5mm 0 0 0;
            line-height: 1.5;
        }
    </style>
</head>

<body>
    <div class="invoice-box">
        <h2 style="text-align: center;">
            NOTE DE FRAIS {{ $noteFrais->type === 'ACOMPTE' ? "D'ACOMPTE" : 'DEFINITIVE' }}
        </h2>

        <p>
            <b>Réf :</b> Convention d'assistance N° {{ $noteFrais->formation?->numero_convention }}
            ONFP/DG/DIOF/DF du {{ $noteFrais->formation?->date_convention?->format('d/m/Y') }}
        </p>
        <p>
            <b>Module :</b> {{ $noteFrais->formation?->intitule ?? $noteFrais->formation?->name }}
        </p>
        <p><b>Bénéficiaires :</b> {{ $noteFrais->beneficiaires }}</p>
        @if ($noteFrais->type === 'DEFINITIVE')
            <p>
                <b>Période de la formation :</b>
                {{ optional($noteFrais->periode_debut)->format('d/m/Y') }}
                au {{ optional($noteFrais->periode_fin)->format('d/m/Y') }}
                à {{ $noteFrais->lieu }}
            </p>
        @else
            <p><b>Lieu de la formation :</b> {{ $noteFrais->lieu }}</p>
        @endif
        <p><b>Doit :</b> Office Nationale de Formation Professionnelle (ONFP)</p>
        @if ($noteFrais->session_label)
            <p>{{ $noteFrais->session_label }}</p>
        @endif

        <table class="lignes">
            <thead>
                <tr>
                    <th>Rubriques</th>
                    <th>Libellés</th>
                    <th>Unités</th>
                    <th>Qte</th>
                    <th>PU (FCFA)</th>
                    <th class="montant">Montant (FCFA)</th>
                </tr>
            </thead>
            <tbody>
                @foreach (['PEDAGOGIQUE' => 'FRAIS PEDAGOGIQUES', 'ADMINISTRATIF' => 'FRAIS ADMINISTRATIFS'] as $groupe => $label)
                    @php $lignesGroupe = $noteFrais->lignes->where('rubrique.groupe', $groupe); @endphp
                    @foreach ($lignesGroupe as $i => $ligne)
                        <tr>
                            @if ($i === 0)
                                <td rowspan="{{ $lignesGroupe->count() }}">{{ $label }}</td>
                            @endif
                            <td>{{ $ligne->rubrique->libelle }}</td>
                            <td>{{ $ligne->unite }}</td>
                            <td>{{ number_format($ligne->qte, 0, ',', ' ') }}</td>
                            <td>{{ number_format($ligne->pu, 0, ',', ' ') }}</td>
                            <td class="montant">{{ number_format($ligne->montant, 0, ',', ' ') }}</td>
                        </tr>
                    @endforeach
                    <tr class="sous-total">
                        <td colspan="5">
                            Sous total {{ $groupe === 'PEDAGOGIQUE' ? '1' : '2' }}
                        </td>
                        <td class="montant">
                            {{ number_format($groupe === 'PEDAGOGIQUE' ? $noteFrais->sous_total_pedagogique : $noteFrais->sous_total_administratif, 0, ',', ' ') }}
                        </td>
                    </tr>
                @endforeach

                <tr class="total">
                    <td colspan="5">TOTAL FRAIS OPERATEUR</td>
                    <td class="montant">{{ number_format($noteFrais->total_frais_operateur, 0, ',', ' ') }}</td>
                </tr>

                @if ($noteFrais->type === 'ACOMPTE')
                    <tr class="total">
                        <td colspan="5">ACOMPTE
                            ({{ rtrim(rtrim(number_format($noteFrais->taux_acompte, 2), '0'), '.') }}%)</td>
                        <td class="montant">{{ number_format($noteFrais->montant_acompte_demande, 0, ',', ' ') }}</td>
                    </tr>
                @else
                    <tr class="sous-total">
                        <td colspan="5">ACOMPTE RECU (B)</td>
                        <td class="montant">{{ number_format($noteFrais->montant_acompte_recu, 0, ',', ' ') }}</td>
                    </tr>
                    <tr class="total">
                        <td colspan="5">RELIQUAT (RESTE A PERCEVOIR) (A-B)</td>
                        <td class="montant">{{ number_format($noteFrais->reliquat, 0, ',', ' ') }}</td>
                    </tr>
                @endif
            </tbody>
        </table>

        <p style="margin-top: 20px;">
            <b>Banque RIB :</b> {{ $noteFrais->banque_rib }}
        </p>

        <p style="text-align: right; margin-top: 40px;">
            Cachet et signature de l'opérateur
        </p>
    </div>

    <footer>
        <div class="page-number">
            <div class="footer-line"></div>
            <p class="footer-text">
                Cité Sipres 1, Lot 2 - 2 voies liberté 6 extension VDN Tel: (+221) 33 827 92 51 -
                Fax: (+221) 33 827 92 55 <br> BP: 21013 Dakar-Ponty Email: <a href="#">onfp@onfp.sn</a>
            </p>
        </div>
    </footer>
</body>

</html>
