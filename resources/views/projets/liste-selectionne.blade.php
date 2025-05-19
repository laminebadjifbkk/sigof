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
        {{-- <table class="table table-responsive">
            <thead>
                <tr class="heading" style="text-align: center;">
                    <td colspan="11"><b>{{ __('LISTE DES CANDIDATS SELECTIONNES') }}</b>
                    </td>
                </tr>
                <tr class="heading">
                    <td colspan="5"><b>{{ $projet?->type_projet }}</b>
                        {{ $projet?->sigle }} - {{ $projet?->name }}
                    </td>
                    <td colspan="6"><b>{{ __('Module : ') }}</b> {{ $projetmodule->module }}
                    </td>
                </tr>
                <tr class="heading">
                    <td colspan="5"><b>{{ __('Lieu: ') }}</b> {{ $formation?->lieu }}
                    </td>
                    <td colspan="6"><b>{{ __('Opérateur: ') }}</b>
                        {{ $formation?->operateur?->user?->operateur . ' (' . $formation?->operateur?->user?->username . ')' }}
                    </td>
                </tr>

                <tr class="heading">
                    <td colspan="2"><b>{{ __('Code formation : ') }}</b>
                        {{ $formation?->code }}
                    </td>
                    <td colspan="3"><b>{{ __('Niveau de qualification : ') }}</b>
                        @if ($formation?->type_certification !== 'Titre')
                            {{ $formation?->titre ?? $formation?->referentiel?->titre }}
                        @else
                            @if (!empty($formation?->referentiel?->categorie))
                                {{ $formation?->referentiel?->categorie . ' de la ' . $formation?->referentiel?->convention?->name }}
                            @endif
                        @endif
                    </td>
                    <td colspan="6"><b>{{ __('Titre : ') }}</b>
                        @if ($formation?->type_certification !== 'Titre')
                            {{ $formation?->type_certification }}
                        @else
                            {{ $formation?->referentiel?->titre }}
                        @endif
                    </td>
                </tr>
                <tr class="heading">
                    <td rowspan="2" class="item" style="text-align: center;"><b>N° ordre</b></td>
                    <td rowspan="2" class="item" style="text-align: center;"><b>CIN</b></td>
                    <td rowspan="2" class="item" style="text-align: center;"><b>Civilité</b></td>
                    <td rowspan="2" class="item" style="text-align: center;"><b>Prénom</b></td>
                    <td rowspan="2" class="item" style="text-align: center;"><b>NOM</b></td>
                    <td rowspan="2" class="item" style="text-align: center;"><b>Date naissance</b></td>
                    <td rowspan="2" class="item" style="text-align: center;"><b>Lieu de naissance</b></td>
                    <td rowspan="2" class="item" style="text-align: center;"><b>Téléphone</b></td>
                    <td colspan="3" style="text-align: center;"><b>{{ __('DECISION DU JURY') }}</b></td>
                </tr>
                <tr class="item" style="text-align: center;">
                    <td><b>Note</b></td>
                    <td><b>Niveau de maitrise</b></td>
                    <td><b>Observations</b></td>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; ?>
                @foreach ($projet->individuelles as $individuelle)
                    <tr class="item" style="text-align: center;">
                        <td>{{ $i++ }}</td>
                        <td>{{ $individuelle->user->cin }}</td>
                        <td>{{ $individuelle?->user?->civilite }}</td>
                        <td>{{ format_proper_name($individuelle?->user?->firstname) }}</td>
                        <td>{{ remove_accents_uppercase($individuelle?->user?->name) }}</td>
                        <td>{{ $individuelle?->user?->date_naissance?->format('d/m/Y') }}</td>
                        <td>{{ remove_accents_uppercase($individuelle?->user?->lieu_naissance) }}</td>
                        <td>{{ $individuelle?->user?->telephone }}</td>
                        <td>{{ $individuelle?->note_obtenue ?? '' }}</td>
                        <td>{{ $individuelle?->appreciation }}</td>
                        <td>{{ $individuelle?->observations }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table> --}}

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
                    {{-- <td class="item" style="text-align: center;" width="5%"><b>Rang</b></td>
                    <td class="item" style="text-align: center;" width="12%"><b>CIN</b></td>
                    <td class="item" style="text-align: center;" width="5%"><b>Civilité</b></td>
                    <td class="item" style="text-align: center;" width="15%"><b>Prénom</b></td>
                    <td class="item" style="text-align: center;" width="10%"><b>NOM</b></td>
                    <td class="item" style="text-align: center;" width="10%"><b>Date naissance</b></td>
                    <td class="item" style="text-align: center;" width="12%"><b>Lieu de naissance</b></td>
                    <td class="item" style="text-align: center;"><b>Email</b></td>
                    <td class="item" style="text-align: center;" width="10%"><b>Téléphone</b></td>
                    <td class="item" style="text-align: center;"><b>Localité</b></td> --}}
                    <td class="item" style="text-align: center;"><b>Rang</b></td>
                    <td class="item" style="text-align: center;" width="15%"><b>CIN</b></td>
                    <td class="item" style="text-align: center;"><b>Civilité</b></td>
                    <td class="item" style="text-align: center;"><b>Prénom</b></td>
                    <td class="item" style="text-align: center;"><b>NOM</b></td>
                    <td class="item" style="text-align: center;"><b>Date naissance</b></td>
                    <td class="item" style="text-align: center;"><b>Lieu de naissance</b></td>
                    <td class="item" style="text-align: center;"><b>Email</b></td>
                    <td class="item" style="text-align: center;" width="12%"><b>Téléphone</b></td>
                    <td class="item" style="text-align: center;"><b>Localité</b></td>
                </tr>
            </thead>
            <tbody>
                {{-- <?php $i = 1; ?>
                @forelse($individuelles as $individuelle)
                    <tr class="item" style="text-align: center;">
                        <td>{{ $i++ }}</td>
                        <td>{{ $individuelle->user->cin }}</td>
                        <td>{{ $individuelle?->user?->civilite }}</td>
                        <td>{{ format_proper_name($individuelle?->user?->firstname) }}</td>
                        <td>{{ remove_accents_uppercase($individuelle?->user?->name) }}</td>
                        <td>{{ $individuelle?->user?->date_naissance?->format('d/m/Y') }}</td>
                        <td>{{ remove_accents_uppercase($individuelle?->user?->lieu_naissance) }}</td>
                        <td><span style="color: #007bff;">{{ $individuelle?->user?->email }}</span></td>
                        <td>{{ $individuelle?->user?->telephone }}</td>
                        <td>{{ $individuelle->departement->nom }}</td>
                    </tr>
                @endforeach --}}
                @php
                    $rang = 1;
                    $precedenteNote = null;
                    $compteur = 0;
                    $rangAffiche = 1;

                    $formatRangFr = function ($rang) {
                        return $rang === 1 ? '1er' : $rang . 'ème';
                    };
                @endphp

                @foreach ($individuelles as $individuelle)
                    @php
                        $noteActuelle = $individuelle->note;

                        // Détection de changement de note
                        if ($noteActuelle !== $precedenteNote) {
                            $rangAffiche = $rang;
                            $compteur = 1;
                        } else {
                            $compteur++;
                        }

                        $precedenteNote = $noteActuelle;

                        // On arrête après le 25e rang
                        if ($rangAffiche > 25) {
                            break;
                        }

                        $rang++;
                    @endphp
                    <tr class="item" style="text-align: center;">
                        <td>
                            {{ $formatRangFr($rangAffiche) }}
                            @if ($compteur > 1)
                                <sup>(exæquo)</sup>
                            @endif
                        </td>
                        <td>{{ $individuelle->user->cin }}</td>
                        <td>{{ $individuelle?->user?->civilite }}</td>
                        <td>{{ format_proper_name($individuelle?->user?->firstname) }}</td>
                        <td>{{ remove_accents_uppercase($individuelle?->user?->name) }}</td>
                        <td>{{ $individuelle?->user?->date_naissance?->format('d/m/Y') }}</td>
                        <td>{{ remove_accents_uppercase($individuelle?->user?->lieu_naissance) }}</td>
                        <td><span style="color: #007bff;">{{ $individuelle?->user?->email }}</span></td>
                        <td>{{ $individuelle?->user?->telephone }}</td>
                        <td>{{ $individuelle->departement->nom }}</td>
                    </tr>
                @endforeach

            </tbody>
        </table>

        {{-- <h4 valign="top">
            <b><u>SIGNATURE DES MEMBRES DU JURY</u></b> : @isset($formation?->date_pv)
                <span
                    style="float: right; font-style: italic">{{ $formation?->departement?->nom . ', ' . $formation?->departement?->region?->nom . ', le ' . $formation?->date_pv?->format('d/m/Y') }}</span>
            @endisset
            <br>
            <?php $i = 1; ?>
            {{ $formation?->evaluateur?->name . ', ' . $formation?->evaluateur?->fonction }}<br>
            {{ $formation?->onfpevaluateur?->name . ', ' . $formation?->onfpevaluateur?->fonction }}<br>
            @isset($membres_jury)
                @foreach ($membres_jury as $item)
                    {{ $item }} <br>
                @endforeach
            @endisset
        </h4> --}}
    </div>
</body>

</html>
