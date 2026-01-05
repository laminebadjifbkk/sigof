<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    {{-- <title>{{ $title }}</title> --}}
    <title>Ordres de missions
        {{ format_proper_name($mission->reference) . ' ' . remove_accents_uppercase($mission->reference) }} </title>

    <!-- Favicons -->
    <link href="{{ asset('assets/img/favicon-onfp.png') }}" rel="icon">
    <link href="{{ asset('assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">
    <style>
        @page {
            size: 21cm 29.7cm;
            margin-top: 1cm;
            margin-bottom: 0.5cm;
        }

        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 0 25px 25px 25px;
            border: 0px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            font-size: 12pt;
            /* taille de police Word */
            line-height: 1.15;
            /* interligne Word */
            font-family: Tahoma, Arial, sans-serif;
            /* police Tahoma */
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

        /* body {
            margin: 0;
            padding-bottom: 30px;
        } */
        body {
            font-family: Tahoma, Arial, sans-serif;
            font-size: 12pt;
            /* taille Word */
            line-height: 1.15;
            /* interligne Word */
            margin: 0;
        }

        table {
            width: 100%;
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

        .no-page-break {
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .page-break {
            page-break-after: always;
        }

        table.fixed {
            table-layout: fixed;
            width: 100%;
            border-collapse: collapse;
        }

        table.fixed th,
        table.fixed td {
            overflow-wrap: break-word;
            word-wrap: break-word;
            hyphens: auto;
            text-align: center;
        }

        .header-text {
            font-size: 10pt;
            line-height: 1.2;
            text-align: center;
        }

        .header-text b {
            font-size: 11pt;
        }

        .header-text em {
            font-size: 9pt;
        }

        /* ===== FOOTER GLOBAL ===== */
        footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 2.3cm;
            font-family: Tahoma, Arial, sans-serif;
            font-size: 11pt;
            background: #fff;
        }

        /* ligne */
        .footer-line {
            width: 18cm;
            height: 2px;
            background-color: #5D4037;
            margin: 0 auto 4px auto;
        }

        /* table footer */
        .footer-table {
            width: 18cm;
            margin: 0 auto;
            border-collapse: collapse;
        }

        /* texte */
        .footer-text {
            text-align: center;
            line-height: 1.4;
        }

        /* pagination */
        .footer-page {
            text-align: right;
            white-space: nowrap;
            font-weight: bold;
        }

        /* DomPDF counters */
        .page:before {
            content: counter(page);
        }

        .total-pages:before {
            content: counter(pages);
        }

        /* === SUPPRIMER TOUT CONTOUR DU FOOTER === */
        .footer-table,
        .footer-table tr,
        .footer-table td {
            border: none !important;
        }

        /* ===== TABLE SANS BORDURES (Décompte mission) ===== */
        .table-no-border,
        .table-no-border td,
        .table-no-border th {
            border: none !important;
        }

        .page-break:last-child {
            page-break-after: auto;
        }

        .page-feuille table th,
        .page-feuille table td {
            border: 1px solid #000;
        }
    </style>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="//db.onlinewebfonts.com/c/dd79278a2e4c4a2090b763931f2ada53?family=ArialW02-Regular" rel="stylesheet"
        type="text/css" />
</head>


<body>
    @foreach ($employees as $employee)
        <!-- ========================= -->
        <!-- PAGE 1 : LETTRE           -->
        <!-- ========================= -->
        @php
            $vehiculeId = $employee->pivot?->vehicule_id;
            $vehicule = $mission->vehicules->firstWhere('id', $vehiculeId);
        @endphp
        <div class="page-lettre page-break">
            <div style="font-family: Tahoma, Arial, sans-serif; font-size: 12pt; line-height: 1.3;">
                <div style="width:100%; font-size:10pt;">
                    <div style="float:left; width:60%; text-align:left; line-height:1.1; font-size:10pt;">

                        <!-- Lignes centrées -->
                        <div style="text-align:center;">
                            <!-- Ligne 1 : complètement à gauche -->
                            <strong style="font-size:10pt; display:block;">
                                REPUBLIQUE DU SENEGAL
                            </strong>

                            <em style="font-size:8pt;">UN PEUPLE - UN BUT - UNE FOI</em><br>
                            <span>---------</span><br>
                            <strong style="font-size:10pt;">
                                MINISTERE DE L’EMPLOI ET DE LA FORMATION <br>
                                PROFESSIONNELLE ET TECHNIQUE
                            </strong>
                        </div>

                    </div>

                    <div style="float:right; width:40%; font-size:10pt;">

                        <div style="text-align:right;">
                            ONFP/DG/DRH/DIVagp/mn
                        </div>

                        <br><br>

                        <div style="text-align:left;">
                            Dakar, le
                        </div>

                    </div>

                    <div style="clear:both;"></div>
                </div>


                <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/img/logo-onfp.jpg'))) }}"
                    style="width: 340px; margin-top: 10px;">

                <div style="text-align:right;">
                    <strong><i>Directeur général</i></strong>
                </div>
                <br>
                <br>
                <h2 style="text-decoration: underline; text-align: center;">ORDRE DE MISSION </h2 class="text-center">

                <p style="text-align: justify; margin-top:5px;margin-bottom:5px; text-align: center;">
                    {{ $vehicule ? $vehicule->immatriculation : '-' }}
                </p>

                <br><br>

                <h4 style="margin-bottom:15px;">
                    Décompte des frais de mission
                </h4>

                <table class="table-no-border" width="100%" cellspacing="0" cellpadding="6"
                    style="border-collapse: collapse; font-size:12pt;">
                    <tr>
                        <td width="55%">
                            <b>Nombre de jours :</b> {{ $jours }} jour{{ $jours > 1 ? 's' : '' }}
                        </td>
                        <td width="45%">
                            <b>Prénom(s) - Nom :</b> {{ $employee?->user?->firstname }} {{ $employee?->user?->name }}
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <b>Taux journalier :</b> ......................................................
                        </td>
                        <td>
                            <b>Fonction :</b> {{ $employee?->fonction?->name }}
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <b>Indemnité de mission :</b> ............................................
                        </td>
                        <td>
                            <b>Objet :</b> {{ $mission->objet }}
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <b>Frais de déplacement (aller et retour) :</b>
                        </td>
                        <td>
                            <b>Destination :</b> {{ $mission->lieu_arrivee }}
                        </td>
                    </tr>

                    <tr>
                        <td>
                            ....................................................................................
                        </td>
                        <td>
                            <b>Département :</b> {{ $mission?->departement?->nom ?? $mission?->lieu_arrivee }}
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <b>Total frais de mission :</b> ............................................
                        </td>
                        <td>
                            <b>Région :</b> {{ $mission?->departement?->region?->nom ?? $mission?->lieu_arrivee }}
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <b>Avance :</b> ....................................................................
                        </td>
                        <td>
                            <b>Date de départ :</b> {{ $mission->date_depart->format('d/m/Y') }}
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <b>Reste à percevoir :</b> ...................................................
                        </td>
                        <td>
                            <b>Date de retour :</b> {{ $mission->date_retour?->format('d/m/Y') }}
                        </td>
                    </tr>
                </table>

                <br><br>
            </div>

            @include('formulaire.footer-simple')
        </div>
        <!-- ========================= -->
        <!-- FEUILLE DE DEPLACEMENT   -->
        <!-- ========================= -->

        <div class="page-feuille {{ $loop->last ? '' : 'page-break' }}">
            <div style="width:100%; font-size:10pt;">
                <div style="float:left; width:60%; text-align:left; line-height:1.1; font-size:10pt;">

                    <!-- Lignes centrées -->
                    <div style="text-align:center;">
                        <!-- Ligne 1 : complètement à gauche -->
                        <strong style="font-size:10pt; display:block;">
                            REPUBLIQUE DU SENEGAL
                        </strong>

                        <em style="font-size:8pt;">UN PEUPLE - UN BUT - UNE FOI</em><br>
                        <span>---------</span><br>
                        <strong style="font-size:10pt;">
                            MINISTERE DE L’EMPLOI ET DE LA FORMATION <br>
                            PROFESSIONNELLE ET TECHNIQUE
                        </strong>
                    </div>

                </div>

                <div style="float:right; width:40%; font-size:10pt;">

                    <div style="text-align:right;">
                        ONFP/DG/DRH/DIVagp/{{ auth()->user()->username }}
                    </div>

                    <br><br>

                    <div style="text-align:left;">
                        Dakar, le
                    </div>

                </div>

                <div style="clear:both;"></div>
            </div>


            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/img/logo-onfp.jpg'))) }}"
                style="width: 340px; margin-top: 10px;">

            <div style="text-align:right;">
                <strong><i>Directeur général</i></strong>
            </div>
            <br>
            <h2 style="text-decoration: underline; text-align: center;">
                FEUILLE DE DEPLACEMENT
            </h2>

            <table class="table-no-border" cellpadding="0" cellspacing="0"
                style="font-size:12pt; border-collapse: collapse;">
                <tr>
                    <td width="30%" style="padding:2px 0;"><b>Ordre de mission :</b></td>
                    <td width="70%" style="padding:2px 0;">
                        {{-- {{ $mission->reference }} --}}
                    </td>
                </tr>
                <tr>
                    <td style="padding:2px 0;"><b>Prénom(s) - Nom :</b></td>
                    <td style="padding:2px 0;">{{ $employee?->user?->firstname }} {{ $employee?->user?->name }}</td>
                </tr>
                <tr>
                    <td style="padding:2px 0;"><b>Fonction :</b></td>
                    <td style="padding:2px 0;">{{ $employee?->fonction?->name }}</td>
                </tr>
                <tr>
                    <td style="padding:2px 0;"><b>Itinéraire :</b></td>
                    <td style="padding:2px 0;">
                        {{ $mission->itineraire ?? $mission->lieu_depart . ' - ' . $mission->lieu_arrive }}
                    </td>
                </tr>
            </table>

            <br>

            <!-- ===== TABLE DES VISAS ===== -->
            <table width="100%" cellpadding="10" cellspacing="0" style="border-collapse:collapse; font-size:12pt;">

                @for ($i = 1; $i <= 3; $i++)
                    <!-- ENTÊTE RÉPÉTÉ -->
                    <tr style="text-align:center; font-weight:bold;">
                        <th>VU AU DÉPART</th>
                        <th>VU À L’ARRIVÉE</th>
                        <th>VU AU DÉPART</th>
                    </tr>

                    <!-- LIGNE SIGNATURES -->
                    <tr style="height:120px;">
                        <td>
                            @if ($i === 1)
                                Date : {{ $mission->date_depart->format('d/m/Y') }}<br><br><br><br><br><br>
                            @else
                                Date : <br><br><br><br><br><br>
                            @endif
                            Signature
                        </td>
                        <td>
                            Date : <br><br><br><br><br><br>
                            Signature
                        </td>
                        <td>
                            Date : <br><br><br><br><br><br>
                            Signature
                        </td>
                    </tr>
                @endfor

            </table>
        </div>

        @include('formulaire.footer-simple')
    @endforeach
</body>

</html>
