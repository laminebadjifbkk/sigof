<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    {{-- <title>{{ $title }}</title> --}}
    <title>Liste des opérateurs {{ $statut }} en {{ $commissionagrement?->date?->format('Y') }}</title>

    <!-- Favicons -->
    <link href="{{ asset('assets/img/favicon-onfp.png') }}" rel="icon">
    <link href="{{ asset('assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">
    <style>
        /* @page {
            margin: 0cm 0cm;
        } */

        @page {
            size: 21cm 29.7cm;
            margin-top: 1cm;
            margin-bottom: 0cm;
        }

        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding-top: 0px;
            padding-bottom: 25px;
            padding-left: 25px;
            padding-right: 25px;
            border: 0px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            font-size: 13px;
            line-height: 22px;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            /* color: #555; */
        }

        /** RTL **/
        .rtl {
            imputation: rtl;
        }

        .invoice-box table tr.heading td {
            background: rgb(255, 255, 255);
            border: 0px solid #000000;
            border-collapse: collapse;
            font-weight: bold;
        }

        body {
            margin: 0;
            padding-bottom: 30px;
            /* hauteur approximative du footer */
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
            border-left: 1px solid rgb(0, 0, 0);
            border-right: 1px solid rgb(0, 0, 0);
            border-top: 1px solid rgb(0, 0, 0);
            border-bottom: 1px solid rgb(0, 0, 0);
            border: 1px solid;
        }

        .Oui {
            color: #198754;
            text-align: center;
        }

        .Non {
            color: #DC3545;
            padding: 4px 8px;
            text-align: center;
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


        .page-number {
            position: relative;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            align-items: center;
            padding-bottom: 0.2cm;
        }

        /* Nettoyage et espacement */
        .footer-text {
            margin: 0;
            padding: 1mm 0 0 0;
            line-height: 1.4;
            max-width: 27cm;
        }

        .no-page-break {
            page-break-inside: avoid;
            break-inside: avoid;
            /* pour compatibilité avec certains moteurs */
        }

        .page-break {
            page-break-after: always;
        }

        table.fixed {
            table-layout: fixed;
            /* Largeurs fixes pour les colonnes */
            width: 100%;
            border-collapse: collapse;
        }

        table.fixed th,
        table.fixed td {
            overflow-wrap: break-word;
            /* Texte long va à la ligne */
            word-wrap: break-word;
            /* Compatibilité anciens moteurs */
            hyphens: auto;
            /* Coupe les mots si nécessaire */
            text-align: center;
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
    @foreach ($operateurs as $operateur)
        {{-- <div style="text-align: center;">
            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/img/entete.png'))) }}"
                style="width: 100%; max-width: 370px" />
        </div> --}}
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
        {{--  <div class="invoice-box">
        <table>
            <thead>
                <tr class="heading">
                    <td colspan="3" style="text-align: center;">
                        <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/img/entete.png'))) }}"
                            style="width: 100%; max-width: 370px" />
                    </td>
                    <td colspan="3"></td>
                    <td colspan="3" style="text-align: right;"></td>
                </tr>
            </thead>
        </table>
    </div> --}}
        <h4 style="text-align: center;">AGREMENT OPERATEUR</h4>
        <div class="invoice-box">
            <b>Opérateur</b> :
            {{ $operateur?->user?->operateur . ' (' . $operateur?->user?->username . ')' }}
            <br>
            <b>Responsable</b> :
            {{ $operateur?->user?->firstname . ' ' . $operateur?->user?->name }}
            <br>
            <b>Adresse</b> :
            {{ $operateur?->user?->adresse }}
            <br>
            <b>Téléphone</b> :
            <a style="text-decoration:none" href="tel:+221{{ $operateur?->user?->telephone }}">
                {{-- {{ substr($operateur?->user?->fixe, 0, 2) .
                ' ' .
                substr($operateur?->user?->fixe, 2, 3) .
                ' ' .
                substr($operateur?->user?->fixe, 5, 2) .
                ' ' .
                substr($operateur?->user?->fixe, 7, 2) }}

            {{ ' / ' .
                substr($operateur?->user?->telephone, 0, 2) .
                ' ' .
                substr($operateur?->user?->telephone, 2, 3) .
                ' ' .
                substr($operateur?->user?->telephone, 5, 2) .
                ' ' .
                substr($operateur?->user?->telephone, 7, 2) }} --}}

                {{ $operateur?->user?->fixe . ' / ' . $operateur?->user?->telephone }}
            </a>
            <br>
            <b>Email</b> :
            <a style="text-decoration:none"
                href="mailto:{{ $operateur?->user?->email }}">{{ $operateur?->user?->email }}</a>
            <br>
            Est agréé par l'ONFP sous le N°: <span
                style="color: #DC3545; font-weight: bold">{{ $operateur?->numero_agrement }}</span> <br>
            <table class="table table-responsive fixed">
                <tbody>
                    <tr class="item" style="text-align: center;">
                        <td colspan="9"><b>{{ __('FORMATIONS AGRÉÉES') }}</b></td>
                    </tr>
                    <tr class="item" style="text-align: center;">
                        <td colspan="2" style="width: 20%;"><b>{{ __('DOMAINES') }}</b></td>
                        <td colspan="3" style="width: 40%;"><b>{{ __('MODULES / SPECIALITE') }}</b></td>
                        <td colspan="4" style="width: 40%;">
                            <b>{{ __('TITRE OU NIVEAU DE QUALIFICATION') }}</b>
                        </td>
                    </tr>
                    {{-- @foreach ($operateur?->operateurmodules?->where('statut', 'agréé') as $operateurmodule)
                    <tr class="item" style="text-align: center;">
                        <td colspan="2">{{ $operateurmodule?->domaine }}</td>
                        <td colspan="2">{{ $operateurmodule?->module }}</td>
                        <td colspan="5">{{ $operateurmodule?->categorie }}</td>
                    </tr>
                @endforeach --}}
                    <?php
                    $operateurmodules = $operateur?->operateurmodules?->where('statut', 'agréé') ?? collect();
                    ?>

                    @foreach ($operateurmodules as $operateurmodule)
                        <tr class="item" style="text-align: center;">
                            <td colspan="2" style="width: 20%;">{{ $operateurmodule->domaine ?? '-' }}</td>
                            <td colspan="3" style="width: 20%;">{{ $operateurmodule->module ?? '-' }}</td>
                            <td colspan="4" style="width: 40%;">{{ $operateurmodule->categorie ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            Le présent agrément est valable deux (2) ans renouvelables une fois. Durant cette période, l'opérateur
            dispose de la faculté de renoncer à son agrément, en le notifiant par écrit à l'ONFP, au moins un
            (1) mois à l'avance. L'ONFP se réserve le droit de suspendre ou de résilier, à tout moment, le présent
            agrément, par notification écrite à l'opérateur.
            <ul>
                L'Opérateur agréé :
                <li>déclare avoir pris connaissance des procédures de l'ONFP notamment celles relatives à l'opérateur de
                    formation,</li>
                <li>s'engage à exécuter comme assistant les formations qui lui sont confiées dans le respect des normes
                    du
                    métier et participe à la demande de l'ONFP à toute activité en lien avec cet agrément,</li>
                <li>certifie que l'agrément dont il bénéficie ne peut donner lieu à aucune responsabilité ou obligation
                    de
                    l'ONFP vis-à-vis d'un tiers ou de l'administration,</li>
                <li>reconnait que toute production faite dans le cadre des actions de formation qui lui sont confiées,
                    est
                    la propriété de l'Office.</li>

                <span class="no-page-break">
                    <h4 style="margin-top: 2mm; font-style: italic;">
                        <strong>L'Opérateur</strong><br>
                        <em class="text-muted" style="font-style: italic; font-weight: normal;">
                            (Lu et approuvé - Signature)
                        </em>
                        <span style="float: right; font-style: normal; font-style: italic;">
                            <strong>Le Directeur Général</strong>
                        </span>
                    </h4>
                </span>
            </ul>
        </div>
        <footer>
            <p class="footer-text">
                Cité Sipres 1, Lot 2 - 2 voies liberté 6 extension VDN
                Tél: (+221) 33 827 92 51 - Fax: (+221) 33 827 92 55
                BP: 21013 Dakar-Ponty - Email: <a href="mailto:onfp@onfp.sn">onfp@onfp.sn</a>
            </p>
        </footer>
        <div style="page-break-after: always;"></div>
    @endforeach
</body>

</html>
