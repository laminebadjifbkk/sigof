<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    {{-- <title>{{ $title }}</title> --}}
    <title>Contrats de prise en charge de
        {{ format_proper_name($formulaire->prenom) . ' ' . remove_accents_uppercase($formulaire->nom) }} </title>

    <!-- Favicons -->
    <link href="{{ asset('assets/img/favicon-onfp.png') }}" rel="icon">
    <link href="{{ asset('assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">
    <style>
        @page {
            size: 21cm 29.7cm;
            margin-top: 1cm;
            margin-bottom: 0cm;
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
            border: 0px solid #ffffff;
            border-collapse: collapse;
            font-weight: bold;
        }

        /* body {
            margin: 0;
            padding-bottom: 30px;
        } */

        body {
            font-family: Tahoma, Arial, sans-serif;
        }


        table {
            width: 100%;
            border-collapse: collapse;
        }

        table td,
        table th {
            border-left: 1px solid rgb(255, 255, 255);
            border-right: 1px solid rgb(255, 255, 255);
            border-top: 1px solid rgb(255, 255, 255);
            border-bottom: 1px solid rgb(255, 255, 255);
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

        .header-fix {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 0.1cm;
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
    <div class="header-fix"></div>
    <!-- FOOTER DÉCLARÉ AVANT -->
    @include('formulaire.footer')
    <div class="page-contrat">

        <div style="font-family: Tahoma, Arial, sans-serif; font-size: 12pt; line-height: 1.3;">

            <!-- ENTETE IDENTIQUE À LA LETTRE -->
            <div style="width:100%; font-size:10pt;">
                <div style="float:left; width:60%; text-align:left; line-height:1.1; font-size:10pt;">

                    <div style="text-align:center;">
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

                {{-- <div style="float:right; width:40%; text-align:right;font-size: 10pt;">
                    ONFP/DG/DIOF/ss<br><br>
                    <i>Dakar, le ...............................</i>
                </div> --}}

                <div style="clear:both;"></div>
            </div>

            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/img/logo-onfp.jpg'))) }}"
                style="width: 340px; margin-top: 10px;">

            <br>

            <!-- TITRE CONTRAT -->
            <h3 style="text-align:center;">
                CONTRAT N° <span style="display:inline-block; width:100px; border-bottom:0px solid #000;"></span>
                <span style="font-size:10pt;">ONFP/DG/DIOF/ss</span>
            </h3>

            <p style="text-align:center; margin:0; margin-bottom:10px; line-height:1;">
                <b>Entre les soussignés</b>
            </p>

            <p style="margin:0;margin-bottom:10px;  line-height:1;">
                <b>Office national de Formation professionnelle (ONFP)</b><br>
                Cité SIPRES 1, Lot 2, 2 voies Liberté 6 extension VDN - BP 21013 – Dakar Ponty
            </p>

            <p style="text-align:center; margin:0;margin-bottom:10px;  line-height:1;">
                <b>Et</b>
            </p>

            <p style="margin:0;margin-bottom:10px;  line-height:1;">
                <b>
                    {{-- {{ $formulaire?->nom_etablissement .
                        ' (' .
                        str_replace(
                            ["l'", 'à ', 'au '], // valeurs à supprimer
                            '',
                            $formulaire?->autre_2,
                        ) .
                        ')' }} --}}

                    <b>{{ $nomEtablissement . ' (' . $autre2 . ')' }}</b><br>
                </b>
                <b>Adresse</b> : {{ $formulaire?->adresse_etablessement }}<br>
                <b>Tél</b> : {{ $formulaire?->telephone_etablissement }}
            </p>

            <p style="text-align:center; margin:0; margin-bottom:10px; line-height:1;">
                Il a été convenu et arrêté ce qui suit :
            </p>

            <h4 style="text-align: justify; margin:0;margin-bottom:10px; line-height:1;">
                <u><b>Article 1 </b></u>: Objet du contrat
            </h4>

            {{-- <p style="text-align: justify; margin:0; line-height:1;">
                Pour l’année académique <b>2025-2026</b>, l’ONFP confie {{ $formulaire?->autre_2 ?? '-' }},
                qui accepte, la formation d'un(e) {{ $formulaire?->autre_1 ?? '-' }},
                conformément aux indications du tableau suivant :
            </p> --}}

            <p style="text-align: justify; margin:0; line-height:1;">
                Pour l’année académique <b>2025-2026</b>, l’ONFP confie
                {{ $formulaire?->autre_2 && $formulaire?->autre_2 !== 'au'
                    ? $formulaire?->autre_2 . ' au '
                    : $formulaire->nom_etablissement }},
                qui accepte, la formation d'un(e) {{ $formulaire?->autre_1 ?? '-' }},
                conformément aux indications du tableau suivant :
            </p>

            <!-- TABLEAU -->
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
                        <td>{{ $formulaire->date_naissance->format('d/m/Y') . ' à ' . remove_accents_uppercase($formulaire?->lieu_naissance) }}
                        </td>
                        <td>{{ $formulaire->formation }}</td>
                        <td>{{ $formulaire->diplome_vise }}</td>
                        <td><b>{{ number_format($formulaire?->montant_onfp, 0, ',', ' ') }}</b></td>
                    </tr>
                </tbody>

            </table>

            <h4 style="text-align: justify; margin-top:5px;margin-bottom:5px;"><u><b>Article 2 </b></u>: Engagement des
                parties</h4>

            <p style="text-align: justify; margin-top:5px;margin-bottom:5px;"><b>A : Engagement de l’ONFP</b></p>
            <p style="text-align: justify; margin-top:5px;margin-bottom:5px;">L’ONFP s’engage :</p>
            <ul style="text-align: justify; margin-top:5px;margin-bottom:5px;">
                <li>A prendre en charge les frais de scolarité annuels (excepté les frais d’inscription), selon les
                    modalités prévues à l’article 3 ;</li>
                <li>A réaliser des visites ponctuelles au niveau de l’établissement pour le suivi de la formation.
                </li>
            </ul>

            <p style="text-align: justify; margin-top:5px;margin-bottom:5px;"><b>B : Engagement de l’Etablissement</b>
            </p>
            <p style="text-align: justify; margin-top:5px;margin-bottom:5px;">L’Etablissement s’engage à :</p>
            <ul style="text-align: justify; margin-top:5px;margin-bottom:5px;">
                <li>Assurer à {{ 'l\'' . $formulaire?->autre_1 ?? '-' }} une
                    formation correspondant à la spécialité et au niveau indiqué dans le
                    contrat ;</li>
                <li>Veiller au respect de l’assiduité de
                    {{ 'l\'' . $formulaire?->autre_1 ?? '-' }} ;</li>
                <li>Mettre à la disposition de l’ONFP les relevés de notes, factures et rapport d’exécution ;</li>
                <li>Signaler tout manquement de
                    {{ 'l\'' . $formulaire?->autre_1 ?? '-' }} (assiduité,
                    résultats, discipline) ;</li>
                <li>Faciliter les visites de contrôle de l’ONFP ;</li>
                <li>Autoriser {{ 'l\'' . $formulaire?->autre_1 ?? '-' }} à démarrer
                    les cours en absence de l’avance prévue à l’article 3 ;</li>
                <li>Autoriser {{ 'l\'' . $formulaire?->autre_1 ?? '-' }} à
                    poursuivre les cours jusqu’à la fin de l’année scolaire.</li>
            </ul>

            <!-- Saut de page obligatoire -->
            <div style="page-break-after: always;"></div>
            <h4 style="text-align: justify; margin-top:5px;margin-bottom:5px;"><u><b>Article 3 </b></u>: Modalités de
                paiement</h4>

            <p style="text-align: justify; margin-top:5px;margin-bottom:5px;">Le règlement s’effectue selon les
                modalités ci-après :</p>
            <ul style="text-align: justify; margin-top:5px;margin-bottom:5px;">
                <li>
                    <b>50%</b> dès signature du présent contrat par les deux parties, sous réserve de la disponibilité
                    du
                    budget de l’ONFP et sur présentation d’une facture d’acompte en trois (3) exemplaires originaux par
                    l’établissement et d’une copie du certificat d’inscription attestant que
                    {{ 'l\'' . $formulaire?->autre_1 ?? '-' }} s’est
                    acquitté des droits d’inscription ;
                </li>
                <li style="text-align: justify; margin-top:5px;margin-bottom:5px;"><b>50%</b> à la fin de la formation,
                    après présentation par l’établissement d’un rapport d’exécution
                    avec les relevés de notes et de la facture reliquat en trois (3) exemplaires originaux.</li>
            </ul>

            <h4 style="text-align: justify; margin-top:5px;margin-bottom:5px;"><u><b>Article 4 </b></u>: Modification
            </h4>
            <p style="text-align: justify; margin-top:5px;margin-bottom:5px;">
                Toute modification du contrat fera l’objet d’un avenant signé par les deux parties.
            </p>

            <h4 style="text-align: justify; margin-top:5px;margin-bottom:5px;"><u><b>Article 5 </b></u>: Résiliation
            </h4>
            <p style="text-align: justify; margin-top:5px;margin-bottom:5px;">
                Le contrat peut être résilié à tout moment en cas de manquement grave ou d’arrêt de
                {{ 'l\'' . $formulaire?->autre_1 ?? '-' }}.
            </p>

            <h4 style="text-align: justify; margin-top:5px;margin-bottom:5px;"><u><b>Article 6 </b></u>: Règlement des
                litiges</h4>
            <p style="text-align: justify; margin-top:5px;margin-bottom:5px;">
                Tout litige sera réglé à l’amiable. À défaut, le droit sénégalais sera appliqué.
            </p>

            <p style="text-align: center; margin-top: 10px"> <br><br>
                Fait à Dakar en deux exemplaires originaux, le ……………………………
            </p>

            <br>

            <span class="no-page-break">
                <div style="margin-top: 5mm; font-style: italic; width:100%;">

                    <!-- Partie gauche : Établissement -->
                    <div style="float:left; width:50%; text-align:left;">
                        <strong>Pour l’Établissement</strong><br><br><br><br><br><br><br>
                        <em style="font-style: italic; font-weight: normal;">
                            {{ $titre }}
                        </em>
                    </div>

                    <!-- Partie droite : ONFP -->
                    <div style="float:right; width:50%; text-align:right;">
                        <strong>Pour
                            l’ONFP&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong><br><br><br><br><br><br><br>
                        <em style="font-style: italic; font-weight: normal;">
                            Le Directeur Général
                        </em>
                    </div>

                    <div style="clear: both;"></div>
                </div>
            </span>

        </div>
        {{-- <footer>
            <div class="page-number">
                <div class="footer-line"></div>
                <p class="footer-text">Cité Sipres 1, Lot 2 - 2 voies liberté 6 extension VDN
                    Tel: <a href="tel:+221338279251">33 827 92 51</a> - Fax: 33 827 92 55 <br>
                    BP: 21013 Dakar-Ponty - Email: <a href="mailto:onfp@onfp.sn">onfp@onfp.sn</a>
                </p>
            </div>
        </footer> --}}
    </div>

</body>

</html>
