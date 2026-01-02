<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    {{-- <title>{{ $title }}</title> --}}
    <title>Lettre de prise en charge de
        {{ format_proper_name($formulaire->prenom) . ' ' . remove_accents_uppercase($formulaire->nom) }} </title>

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
    <!-- ========================= -->
    <!-- PAGE 1 : LETTRE           -->
    <!-- ========================= -->
    <div class="page-lettre">
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

                <div style="float:right; width:40%; text-align:right;font-size: 10pt;">
                    ONFP/DG/DIOF/ss<br><br>
                    <i>Dakar, le ...............................</i>
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
            <strong style="text-decoration: underline; margin-bottom:5px;">Objet </strong>: Prise en charge de formation

            <p style="text-align: justify; margin-top:5px;margin-bottom:5px;">
                <b>{{ $formulaire?->responsable_etablieement }}</b>,
            </p>

            <p style="text-align: justify; margin-top:5px;">
                Pour l’année académique <b>{{ $formulaire?->annee_scolaire ?? date('Y') . '-' . (date('Y') + 1) }}</b>,
                l’Office national de Formation professionnelle (<b>ONFP</b>)
                assure la prise en charge de la formation d'un(e)
                {{ $formulaire?->autre_1 ?? '-' }}
                dans votre établissement, selon le tableau ci-après.
            </p>

            {{-- Tableau récapitulatif --}}
            <table width="100%" border="1" cellspacing="0" cellpadding="6"
                style="border-collapse: collapse; text-align: center; margin-top: 10px;">

                <thead style="background: #f1f1f1; font-weight: bold;">
                    <tr>
                        <td>Prénom et Nom</td>
                        <td>Date et lieu de naissance</td>
                        <td>Spécialité</td>
                        <td width="12%">Niveau</td>
                        <td>Montant (CFA)</td>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td>{{ format_proper_name($formulaire->prenom) . ' ' . remove_accents_uppercase($formulaire->nom) }}
                        </td>
                        <td>{{ $formulaire->date_naissance->format('d/m/Y') . ' à ' . remove_accents_uppercase($formulaire->lieu_naissance) }}
                        </td>
                        <td>{{ $formulaire->formation }}</td>
                        <td>{{ $formulaire->diplome_vise }}</td>
                        <td><b>{{ number_format($formulaire?->montant_onfp, 0, ',', ' ') }}</b></td>
                    </tr>
                </tbody>

            </table>

            <p style="text-align: justify; margin-top:10px;">
                À cet effet, je vous transmets le contrat ci-joint en deux exemplaires originaux que vous voudrez
                bien
                signer et me retourner. <br>
                Je vous prie de croire, {{ $formulaire?->responsable_etablieement }}, en l’assurance de ma
                considération distinguée.
            </p>

            <br><br>

            <p><b>P.J : </b>Contrat</p>

            <br>

            {{-- Signature --}}
            {{-- <div style="margin-top: 40px;">
                <b>A</b><br>
                {{ $formulaire?->responsable_etablieement }}
                @php
                    $prefix = $formulaire?->autre_2 ?? '';
                    $article = Str::startsWith($prefix, 'au') ? 'du' : 'de';
                @endphp
                {{ $article }}
                {!! nl2br(
                    e(
                        $formulaire->nom_etablissement .
                            ' (' .
                            str_replace(
                                ["l'", 'à ', 'au '], // valeurs à supprimer
                                '',
                                $formulaire?->autre_2,
                            ) .
                            ')',
                    ),
                ) !!}
                <br>
                <b>{{ $formulaire?->adresse_etablessement ?? '-' }}</b>
            </div> --}}
            <div style="margin-top: 40px;">
                <b>A</b><br>
                {{ $formulaire?->responsable_etablieement }}

                @php
                    $prefix = $formulaire?->autre_2 ?? '';
                    $article = Str::startsWith($prefix, 'au') ? 'du' : 'de';
                @endphp

                {{ $article }}

                {!! nl2br(
                    e(
                        $formulaire->nom_etablissement .
                            // ajouter uniquement si autre_2 est différent de nom_etablissement
                            ($formulaire?->autre_2 && $formulaire?->autre_2 !== 'au'
                                ? ' (' . str_replace(["l'", 'à ', 'au '], '', $formulaire?->autre_2) . ')'
                                : ''),
                    ),
                ) !!}

                <br>
                <b>{{ $formulaire?->adresse_etablessement ?? '-' }}</b>
            </div>

        </div>
        {{-- <footer>
            <div class="footer-line"></div>

            <div class="footer-content">
                <p class="footer-text footer-left">Cité Sipres 1, Lot 2 - 2 voies liberté 6 extension VDN
                    Tel: <a href="tel:+221338279251">33 827 92 51</a> - Fax: 33 827 92 55 <br>
                    BP: 21013 Dakar-Ponty - Email: <a href="mailto:onfp@onfp.sn">onfp@onfp.sn</a>
                </p>
            </div>
        </footer> --}}

        @include('formulaire.footer-simple')
    </div>

</body>

</html>
