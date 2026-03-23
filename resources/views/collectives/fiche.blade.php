<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>{{ $title }}</title>

    <!-- Favicons -->
    <link href="{{ asset('assets/img/favicon-onfp.png') }}" rel="icon">
    <link href="{{ asset('assets/img/favicon-onfp.png') }}" rel="apple-touch-icon">

    <style>
        @page {
            margin: 2cm 2.5cm;
        }

        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 14px;
            color: #000;
            line-height: 1.4;
            margin: 0;
            padding-bottom: 30px;
        }

        .section {
            margin-bottom: 30px;
        }

        .container {
            max-width: 700px;
            margin: auto;
            padding: 0;
        }

        .header,
        .footer {
            text-align: center;
            margin-bottom: 20px;
        }

        .header .date {
            float: right;
            font-size: 12px;
        }

        .header .contact-info p {
            margin: 2px 0;
        }

        .clear {
            clear: both;
        }

        .title {
            text-align: center;
            font-weight: bold;
            font-size: 12px;
            margin: 30px 0 20px;
            letter-spacing: 1px;
        }

        .subtitle {
            font-weight: bold;
            margin-bottom: 15px;
            font-size: 14px;
        }

        /* ✅ TABLE FIX */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
            table-layout: fixed; /* important pour PDF */
        }

        table th,
        table td {
            border: 1px solid #000;
            padding: 8px 10px;
            text-align: left;
            vertical-align: top;

            /* 🔥 anti-débordement */
            word-wrap: break-word;
            overflow-wrap: break-word;
            word-break: break-word;
            white-space: normal;
        }

        table th {
            background: #eee;
        }

        /* 🔥 spécial pour liens longs */
        a {
            word-break: break-all;
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
            border-top: 2px solid #5D4037;
            z-index: 1000;
        }

        .footer-text {
            margin: 0;
            padding-top: 2px;
            line-height: 1.4;
            max-width: 27cm;
        }

        .header-text {
            font-size: 10px;
            line-height: 1.2;
            text-align: center;
        }

        .header-text b {
            font-size: 11px;
        }

        .header-text em {
            font-size: 9px;
        }
    </style>

    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>
    <div class="container">

        <div class="header-text">
            <b>REPUBLIQUE DU SENEGAL<br></b>
            <em class="text-muted">Un Peuple - Un But - Une Foi</em><br>
            <b>********<br>
                MINISTERE DE L'EMPLOI ET DE LA FORMATION <br> PROFESSIONNELLE ET TECHNIQUE<br><br>

                <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/img/logo-onfp.jpg'))) }}"
                    style="width: 100%; max-width: 300px" />
            </b>
        </div>

        <h4 class="title">Fiche de renseignement de demande de formation (collective)</h4>

        <div class="section">
            <p class="subtitle">I. <u>Identification de l'organisation</u></p>

            <table>
                <tbody>
                    <tr>
                        <th>Nom de la structure</th>
                        <td colspan="10">{{ $collective?->name_with_sigle }}</td>
                    </tr>
                    <tr>
                        <th>Statut</th>
                        <td colspan="10">{{ $collective?->statut_juridique }}</td>
                    </tr>
                    <tr>
                        <th>Personne responsable</th>
                        <td colspan="10">
                            {{ $collective?->prenom_responsable . ' ' . $collective?->nom_responsable }}
                        </td>
                    </tr>
                    <tr>
                        <th>Adresse de la structure</th>
                        <td colspan="4">{{ $collective?->adresse }}</td>
                        <td colspan="3">{{ $collective?->departement?->nom }}</td>
                        <td colspan="3">{{ $collective?->departement?->region?->nom }}</td>
                    </tr>
                    <tr>
                        <th>Contact</th>
                        <td colspan="5">
                            <a href="mailto:{{ $collective?->user?->email }}">
                                {{ $collective?->user?->email }}
                            </a>
                        </td>
                        <td colspan="5">
                            <a href="tel:+221{{ $collective?->user?->telephone }}">
                                {{ $collective?->user?->telephone }}
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="section">
            <p class="subtitle">II. <u>Formation sollicitée</u></p>
            <table>
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>Module</th>
                        <th>Niveau qualification</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @php $i = 1; @endphp
                    @forelse ($collective?->collectivemodules as $collectivemodule)
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td>{{ $collectivemodule?->module }}</td>
                            <td>{{ $collectivemodule?->niveau_qualification }}</td>
                            <td>{{ $collectivemodule?->statut }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td>Description</td>
                            <td colspan="3" class="text-center">Aucune formation sollicitée</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="section">
            <p class="subtitle">III. <u>Projet professionnel</u></p>
            <table>
                <tbody>
                    <tr>
                        <th>Description de l'organisation, de ses activités et de ses réalisations</th>
                        <td colspan="10">{{ $collective?->description }}</td>
                    </tr>
                    <tr>
                        <th>Description du projet professionnel et de l'effet attendu de la formation</th>
                        <td colspan="10">{{ $collective?->projetprofessionnel }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>

    <footer>
        <p class="footer-text">
            Cité Sipres 1, Lot 2 - 2 voies liberté 6 extension VDN
            Tél: (+221) 33 827 92 51 - Fax: (+221) 33 827 92 55
            BP: 21013 Dakar-Ponty - Email:
            <a href="mailto:onfp@onfp.sn">onfp@onfp.sn</a>
        </p>
    </footer>
</body>

</html>