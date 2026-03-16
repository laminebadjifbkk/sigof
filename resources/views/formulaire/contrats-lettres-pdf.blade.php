<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    {{-- <title>{{ $title }}</title> --}}
    <title>Liste des contrats et lettre de prises en charge {{ $statut }} </title>

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
            padding-top: 0px;
            padding-bottom: 25px;
            padding-left: 25px;
            padding-right: 25px;
            border: 0px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            font-size: 13px;
            line-height: 22px;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
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


        .page-number {
            position: relative;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            align-items: center;
            padding-bottom: 0cm;
        }

        .footer-line {
            width: 18cm;
            height: 2px;
            background-color: #5D4037;
            margin-bottom: 0mm;
            margin-left: auto;
            margin-right: auto;
        }

        /* Supprime tout espace automatique du paragraphe */
        .footer-text {
            margin: 0;
            padding: 0.5mm 0 0 0;
            /* Légère marge haute pour l’espacement */
            line-height: 1.5;
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
    @foreach ($formulaires as $formulaire)
        <!-- ========================= -->
        <!-- PAGE 1 : LETTRE           -->
        <!-- ========================= -->
        <div class="page-lettre">
            <div style="font-family: 'DejaVu Sans', sans-serif; font-size: 13px; line-height: 1.6;">
                <div style="width:100%; font-size:14px;">
                    <div style="float:left; width:60%; text-align:left; line-height:1.1; font-size:12px;">

                        <!-- Lignes centrées -->
                        <div style="text-align:center;">
                            <!-- Ligne 1 : complètement à gauche -->
                            <strong style="font-size:13px; display:block;">
                                REPUBLIQUE DU SENEGAL
                            </strong>

                            <em style="font-size:11px;">UN PEUPLE - UN BUT - UNE FOI</em><br>
                            <span>---------</span><br>
                            <strong style="font-size:12px;">
                                MINISTERE DE L’EMPLOI ET DE LA FORMATION <br>
                                PROFESSIONNELLE ET TECHNIQUE
                            </strong>
                        </div>

                    </div>

                    <div style="float:right; width:40%; text-align:right;">
                        ONFP/DG/DIOF/{{ auth()->user()->username }}<br><br>
                        <i>Dakar, le ...............................</i>
                    </div>

                    <div style="clear:both;"></div>
                </div>


                <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/img/logo-onfp.jpg'))) }}"
                    style="width: 340px; margin-top: 10px;">

                <div style="text-align:right;">
                    <strong><i>Directeur général</i></strong>
                </div>


                <strong style="text-decoration: underline;">Objet</strong>: Prise en charge de formation

                <p>
                    Madame, Monsieur,
                </p>

                <p style="text-align: justify;">
                    Pour l’année académique <b>2025/2026</b>, l’Office National de Formation Professionnelle
                    (<b>ONFP</b>)
                    assure la prise en charge de la formation d’un(e) étudiant(e)/admis(e) dans votre établissement,
                    selon le tableau ci-après.
                </p>

                {{-- Tableau récapitulatif --}}
                <div class="table-responsive">
                    <table width="100%" border="1" cellspacing="0" cellpadding="6"
                        style="border-collapse: collapse; text-align: center; margin-top: 10px;">

                        <thead style="background: #f1f1f1; font-weight: bold;">
                            <tr>
                                <td>Prénom et Nom</td>
                                <td>Date & lieu de naissance</td>
                                <td>Spécialité</td>
                                <td>Niveau</td>
                                <td>Montant (F CFA)</td>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td>{{ $formulaire->prenom . ' ' . $formulaire->nom }}</td>
                                <td>{{ $formulaire->date_naissance->format('d/m/Y') . ' à ' . $formulaire->lieu_naissance }}
                                </td>
                                <td>{{ $formulaire->formation }}</td>
                                <td>{{ $formulaire->diplome_vise }}</td>
                                <td><b>{{ number_format($formulaire->montant_unique, 0, ',', ' ') }}</b></td>
                            </tr>
                        </tbody>

                    </table>
                </div>

                <p style="text-align: justify; margin-top: 20px;">
                    À cet effet, je vous transmets le contrat ci-joint en deux exemplaires originaux que vous voudrez
                    bien
                    signer et me retourner.
                </p>

                <p style="text-align: justify;">
                    Je vous prie de croire, Madame, Monsieur, en l’assurance de ma considération distinguée.
                </p>

                <br><br>

                <p><b>P.J :</b> Contrat</p>

                <br>

                {{-- Signature --}}
                <div style="margin-top: 40px;">
                    <b>A</b><br>
                    Madame / Monsieur le/la Responsable de<br>
                    {{ $formulaire->nom_etablissement ?? 'Votre établissement' }}<br>
                    <b>{{ $formulaire->region ?? 'DAKAR' }}</b>
                </div>

            </div>

            <footer>
                <div class="page-number" id="footer">
                    <div class="footer-line"></div>
                    <p class="footer-text">Cité Sipres 1, Lot 2 - 2 voies liberté 6 extension VDN Tel: (+221) 33 827 92
                        51 -
                        Fax: (+221) 33 827 92
                        55 <br> BP: 21013 Dakar-Ponty Email: <a href="#">onfp@onfp.sn</a></p>
                </div>
            </footer>
        </div>

        <!-- Saut de page obligatoire -->
        <div style="page-break-after: always;"></div>
        <!-- ========================= -->
        <!-- PAGE 2 : CONTRAT          -->
        <!-- ========================= -->
        <div class="page-contrat">

            <div style="font-family: 'DejaVu Sans', sans-serif; font-size: 13px; line-height: 1.6;">

                <!-- ENTETE IDENTIQUE À LA LETTRE -->
                <div style="width:100%; font-size:14px;">
                    <div style="float:left; width:60%; text-align:left; line-height:1.1; font-size:12px;">

                        <div style="text-align:center;">
                            <strong style="font-size:13px; display:block;">
                                REPUBLIQUE DU SENEGAL
                            </strong>

                            <em style="font-size:11px;">UN PEUPLE - UN BUT - UNE FOI</em><br>
                            <span>---------</span><br>
                            <strong style="font-size:12px;">
                                MINISTERE DE L’EMPLOI ET DE LA FORMATION <br>
                                PROFESSIONNELLE ET TECHNIQUE
                            </strong>
                        </div>

                    </div>

                    <div style="float:right; width:40%; text-align:right;">
                        ONFP/DG/DIOF/{{ auth()->user()->username }}<br><br>
                        <i>Dakar, le ...............................</i>
                    </div>

                    <div style="clear:both;"></div>
                </div>

                <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/img/logo-onfp.jpg'))) }}"
                    style="width: 340px; margin-top: 10px;">

                <br><br>

                <!-- TITRE CONTRAT -->
                <h3 style="text-align:center;">
                    CONTRAT N°
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    ONFP/DG/DIOF/{{ auth()->user()->username }}
                </h3>

                <p><b>Entre les soussignés</b></p>

                <p>
                    <b>Office National de Formation Professionnelle (ONFP)</b><br>
                    Cité SIPRES 1, Lot 2, 2 voies Liberté 6 extension VDN - BP 21013 – Dakar Ponty
                </p>

                <p>
                    <b>Et</b><br>
                    <b>{{ $formulaire->nom_etablissement }}</b><br>
                    Adresse : Dakar<br>
                    Tél : 33 849 69 19 / 33 821 50 74
                </p>

                <p>
                    Il a été convenu et arrêté ce qui suit :
                </p>

                <h4><u>Article 1 </u>: Objet du contrat</h4>

                <p>
                    Pour l’année académique <b>2025-2026</b>, l’ONFP confie à l’Ecole Supérieure de Commerce, qui
                    accepte,
                    la formation d’un(e) étudiant(e), conformément aux indications du tableau suivant :
                </p>

                <!-- TABLEAU -->
                <div class="table-responsive">
                    <table width="100%" border="1" cellspacing="0" cellpadding="6"
                        style="border-collapse: collapse; text-align: center; margin-top: 10px;">

                        <thead style="background: #f1f1f1; font-weight: bold;">
                            <tr>
                                <td>Prénom et Nom</td>
                                <td>Date & lieu de naissance</td>
                                <td>Spécialité</td>
                                <td>Niveau</td>
                                <td>Montant (F CFA)</td>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td>{{ $formulaire->prenom . ' ' . $formulaire->nom }}</td>
                                <td>{{ $formulaire->date_naissance->format('d/m/Y') . ' à ' . $formulaire->lieu_naissance }}
                                </td>
                                <td>{{ $formulaire->formation }}</td>
                                <td>{{ $formulaire->diplome_vise }}</td>
                                <td><b>{{ number_format($formulaire->montant_unique, 0, ',', ' ') }}</b></td>
                            </tr>
                        </tbody>

                    </table>
                </div>

                <br>

                <h4><u>Article 2 </u>: Engagement des parties</h4>

                <p><b>A : Engagement de l’ONFP</b></p>
                <ul>
                    <li>A prendre en charge les frais de scolarité annuels (excepté les frais d’inscription), selon les
                        modalités prévues à l’article 3 ;</li>
                    <li>A réaliser des visites ponctuelles au niveau de l’établissement pour le suivi de la formation.
                    </li>
                </ul>

                <p><b>B : Engagement de l’Etablissement</b></p>
                <ul>
                    <li>Assurer à l’étudiant(e) une formation correspondant à la spécialité et au niveau indiqué dans le
                        contrat ;</li>
                    <li>Veiller au respect de l’assiduité de l’étudiant(e) ;</li>
                    <li>Mettre à la disposition de l’ONFP les relevés de notes, factures et rapport d’exécution ;</li>
                    <li>Signaler tout manquement de l’étudiant(e) (assiduité, résultats, discipline) ;</li>
                    <li>Faciliter les visites de contrôle de l’ONFP ;</li>
                    <li>Autoriser l’étudiant(e) à démarrer les cours en absence de l’avance prévue à l’article 3 ;</li>
                    <li>Autoriser l’étudiant(e) à poursuivre les cours jusqu’à la fin de l’année scolaire.</li>
                </ul>

                <h4><u>Article 3 </u>: Modalités de paiement</h4>

                <p>Le règlement s’effectue selon les modalités ci-après :</p>
                <ul>
                    <li>50% dès signature du présent contrat par les deux parties, sous réserve de la disponibilité du
                        budget de l’ONFP et sur présentation d’une facture d’acompte + certificat d’inscription ;</li>
                    <li>50% à la fin de la formation, après présentation du rapport d’exécution, des relevés de notes et
                        de la facture reliquat.</li>
                </ul>

                <h4><u>Article 4 </u>: Modification</h4>
                <p>
                    Toute modification du contrat fera l’objet d’un avenant signé par les deux parties.
                </p>

                <h4><u>Article 5 </u>: Résiliation</h4>
                <p>
                    Le contrat peut être résilié à tout moment en cas de manquement grave ou d’arrêt de l’étudiant(e).
                </p>

                <h4><u>Article 6 </u>: Règlement des litiges</h4>
                <p>
                    Tout litige sera réglé à l’amiable. À défaut, le droit sénégalais sera appliqué.
                </p>

                <br>

                <p>
                    Fait à Dakar en deux exemplaires originaux, le ……………………………
                </p>

                <br>

                <span class="no-page-break">
                    <div style="margin-top: 2mm; font-style: italic; width:100%;">

                        <!-- Partie gauche : Établissement -->
                        <div style="float:left; width:50%; text-align:left;">
                            <strong>Pour l’Établissement</strong><br><br><br>
                            <em style="font-style: italic; font-weight: normal;">
                                Le Directeur / La Directrice
                            </em>
                        </div>

                        <!-- Partie droite : ONFP -->
                        <div style="float:right; width:50%; text-align:right;">
                            <strong>Pour l’ONFP</strong><br><br><br>
                            <em style="font-style: italic; font-weight: normal;">
                                Le Directeur Général
                            </em>
                        </div>

                        <div style="clear: both;"></div>
                    </div>
                </span>

            </div>

            <footer>
                <div class="page-number">
                    <div class="footer-line"></div>
                    <p class="footer-text">Cité Sipres 1, Lot 2 - 2 voies liberté 6 extension VDN
                        Tel: (+221) 33 827 92 51 - Fax: (+221) 33 827 92 55 <br>
                        BP: 21013 Dakar-Ponty - Email: onfp@onfp.sn
                    </p>
                </div>
            </footer>
        </div>

        <div style="page-break-after: always;"></div>

        <!-- Saut de page pour le prochain formulaire -->
        <div style="page-break-after: always;"></div>
    @endforeach
</body>

</html>
