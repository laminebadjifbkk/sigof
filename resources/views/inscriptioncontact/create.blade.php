<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ONFP - Partnership Engagement Day</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Select2 JS + jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#structure').select2({
                placeholder: "Choisir votre structure",
                allowClear: true,
                width: '100%'
            });
        });
    </script>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #e9f4ff, #f8f9fa);
            font-family: 'Poppins', sans-serif;
        }

        .form-card {
            max-width: 700px;
            margin: 80px auto;
            background: #fff;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .form-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }

        h2 {
            color: #F28500;
            font-weight: 700;
        }

        h5 {
            color: #6c757d;
            font-weight: 400;
            margin-bottom: 30px;
        }

        .form-floating>input {
            padding: 1.5rem 1rem 0.5rem 1rem;
        }

        .btn-primary {
            background: #F28500;
            border: none;
            font-weight: 600;
            padding: 14px;
            border-radius: 12px;
            width: 100%;
            transition: 0.3s;
        }

        .btn-primary:hover {
            background: #F28500;
        }

        .footer-text {
            text-align: center;
            font-size: 14px;
            color: #6c757d;
            margin-top: 25px;
        }

        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }

        .form-group {
            position: relative;
            margin-bottom: 20px;
        }

        input.form-control {
            padding-left: 40px;
            border-radius: 10px;
            height: 50px;
        }

        select.form-control {
            padding-left: 40px;
            border-radius: 10px;
            height: 50px;
        }

        input.form-control:focus {
            border-color: #F28500;
            box-shadow: 0 0 5px rgba(13, 110, 253, 0.2);
        }

        select.form-control:focus {
            border-color: #F28500;
            box-shadow: 0 0 5px rgba(13, 110, 253, 0.2);
        }
    </style>
</head>

<body>
    <div class="form-card">
        <h2 class="text-center mb-3">ONFP <br>PARTNERSHIP ENGAGEMENT DAY</h2>
        <h5 class="text-center text-secondary mb-2">
            Confirmez votre participation
        </h5>
        <p class="text-center text-muted mb-4">
            📅 Le 06 novembre à partir de 08h<br>
            📍 Hôtel AZALAÏ DAKAR
        </p>

        @if (session('success'))
            <div class="alert alert-success text-center rounded-3">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger rounded-3">
                <ul class="mb-0">
                    @foreach ($errors->all() as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('inscriptioncontact.store') }}" method="POST">
            @csrf
            <div class="form-group">
                {{-- <select name="structure" id="structure" class="form-control select2" required>
                    <option value="">-- Choisir votre structure --</option>
                    <option value="Ministère de l’Emploi et de la Formation professionnelle et Technique (MEFPT)">
                        Ministère de l’Emploi et de la Formation professionnelle et Technique (MEFPT)</option>
                    <option value="Ministère de l’Agriculture, de la Souveraineté Alimentaire et de l’Elevage (MASAE)">
                        Ministère de l’Agriculture, de la Souveraineté Alimentaire et de l’Elevage (MASAE)</option>
                    <option value="Ministère de l’Urbanisme, du Logement et de l’Hygiène Publique (MULHP)">Ministère de
                        l’Urbanisme, du Logement et de l’Hygiène Publique (MULHP)</option>
                    <option value="Ministère de l’Economie du Plan et de la Coopération (MEPC)">Ministère de l’Economie
                        du Plan et de la Coopération (MEPC)</option>
                    <option value="Ministère de l’Energie, du Pétrole et des Mines (MEPM)">Ministère de l’Energie, du
                        Pétrole et des Mines (MEPM)</option>
                    <option value="Délégation à l’Union Européenne au Sénégal (EEAS)">Délégation à l’Union Européenne au
                        Sénégal (EEAS)</option>
                    <option value="Ambassade du Canada">Ambassade du Canada</option>
                    <option value="Agence Française de Développement (AFD)">Agence Française de Développement (AFD)
                    </option>
                    <option value="Ambassade du Maroc">Ambassade du Maroc</option>
                    <option value="Délégation générale des Îles Canaries">Délégation générale des Îles Canaries</option>
                    <option value="Chambre officielle de Commerce d’Espagne à Dakar">Chambre officielle de Commerce
                        d’Espagne à Dakar</option>
                    <option value="Délégation générale de la Wallonie Bruxelles">Délégation générale de la Wallonie
                        Bruxelles</option>
                    <option value="Agence de coopération Belge (ENABEL)">Agence de coopération Belge (ENABEL)</option>
                    <option value="Agence de coopération Allemande (KFW)">Agence de coopération Allemande (KFW)</option>
                    <option value="Agence de coopération Allemande (GIZ)">Agence de coopération Allemande (GIZ)</option>
                    <option value="Agence de coopération Luxembourgeoise (Luxdév)">Agence de coopération Luxembourgeoise
                        (Luxdév)</option>
                    <option value="Agence Andalouse de Coopération Internationale pour le Développement (AACID)">Agence
                        Andalouse de Coopération Internationale pour le Développement (AACID)</option>
                    <option value="Agence de coopération Turque (TIKA)">Agence de coopération Turque (TIKA)</option>
                    <option value="Agence Italienne pour la Coopération au Développement (AICS)">Agence Italienne pour
                        la Coopération au Développement (AICS)</option>
                    <option value="Agence japonaise de coopération Internationale (JICA)">Agence japonaise de
                        coopération Internationale (JICA)</option>
                    <option value="Centre d'études et de recherches sur les qualifications (CEREQ)">Centre d'études et
                        de recherches sur les qualifications (CEREQ)</option>
                    <option value="Centre canadien de Coopération Internationale (CECI)">Centre canadien de Coopération
                        Internationale (CECI)</option>
                    <option value="Direction générale de la Formation professionnelle et technique (DGFPT)">Direction
                        générale de la Formation professionnelle et technique (DGFPT)</option>
                    <option value="Agence de Promotion et de l’Investissement et des Grands Travaux (APIX-SA)">Agence de
                        Promotion et de l’Investissement et des Grands Travaux (APIX-SA)</option>
                    <option value="Caisse des Dépôts et de Consignations (CDC)">Caisse des Dépôts et de Consignations
                        (CDC)</option>
                    <option value="Agence Sénégalaise d’Electrification Rurale (ASER)">Agence Sénégalaise
                        d’Electrification Rurale (ASER)</option>
                    <option value="Agence pour l’Economie et la Maitrise de l’Energie (AEME)">Agence pour l’Economie et
                        la Maitrise de l’Energie (AEME)</option>
                    <option value="Fonds de Développement des Transports Terrestres (FDTT)">Fonds de Développement des
                        Transports Terrestres (FDTT)</option>
                    <option value="Fonds d'entretien routier autonome (FERA)">Fonds d'entretien routier autonome (FERA)
                    </option>
                    <option value="Fonds de promotion de l’industrie cinématographique et audiovisuelle (FOPICA)">Fonds
                        de promotion de l’industrie cinématographique et audiovisuelle (FOPICA)</option>
                    <option value="Délégation Générale à l'Entreprenariat Rapide des Femmes et des Jeunes (DER/FJ)">
                        Délégation Générale à l'Entreprenariat Rapide des Femmes et des Jeunes (DER/FJ)</option>
                    <option value="Port Autonome de Dakar (PAD)">Port Autonome de Dakar (PAD)</option>
                    <option value="Dubai Port (Dp World) Sénégal">Dubai Port (Dp World) Sénégal</option>
                    <option value="Conseil sénégalais des Chargeurs (COSEC)">Conseil sénégalais des Chargeurs (COSEC)
                    </option>
                    <option value="Société Africaine de Raffinage (SAR)">Société Africaine de Raffinage (SAR)</option>
                    <option value="Groupe SONATEL Orange">Groupe SONATEL Orange</option>
                    <option value="Société Nationale des Eaux du Sénégal (SONES)">Société Nationale des Eaux du Sénégal
                        (SONES)</option>
                    <option value="Sénégal Numérique (SENUM-SA)">Sénégal Numérique (SENUM-SA)</option>
                    <option value="Conseil exécutif des Transports urbains durables (CETUD)">Conseil exécutif des
                        Transports urbains durables (CETUD)</option>
                    <option value="Office des Forages Ruraux (OFOR)">Office des Forages Ruraux (OFOR)</option>
                    <option value="Société Nationale de Gestion Intégrée des Déchets (SONAGED)">Société Nationale de
                        Gestion Intégrée des Déchets (SONAGED)</option>
                    <option value="Télédiffusion Sénégal (TDS)">Télédiffusion Sénégal (TDS)</option>
                    <option value="Ecole Supérieure d’Economie Appliquée (ESEA)">Ecole Supérieure d’Economie Appliquée
                        (ESEA)</option>
                    <option value="Agence d'Exécution des Travaux Routiers (AGEROUTE)">Agence d'Exécution des Travaux
                        Routiers (AGEROUTE)</option>
                    <option value="Agence Sénégalaise de Promotion Touristique (ASPT)">Agence Sénégalaise de Promotion
                        Touristique (ASPT)</option>
                    <option value="Office national de l’Assainissement du Sénégal (ONAS)">Office national de
                        l’Assainissement du Sénégal (ONAS)</option>
                    <option
                        value="Projet d’Appui à l’Insertion des Jeunes Ruraux Agri-preneurs (Agri-Jeunes Tekki Ndawñi)">
                        Projet d’Appui à l’Insertion des Jeunes Ruraux Agri-preneurs (Agri-Jeunes Tekki Ndawñi)</option>
                    <option value="Projet Formation, dignité, inclusion et innovation (VIS)">Projet Formation, dignité,
                        inclusion et innovation (VIS)</option>
                    <option value="Projet Emplois Verts DELTA, Saloum">Projet Emplois Verts DELTA, Saloum</option>
                    <option value="Comité d’organisation des Jeux Olympiques de la Jeunesse (JOJ 2026)">Comité
                        d’organisation des Jeux Olympiques de la Jeunesse (JOJ 2026)</option>
                    <option value="Programme des Domaines Agricoles Communautaires (PRODAC)">Programme des Domaines
                        Agricoles Communautaires (PRODAC)</option>
                    <option value="Fonds d’appui à la Stabilisation (FONSTAB)">Fonds d’appui à la Stabilisation
                        (FONSTAB)</option>
                    <option value="Projet PAPSEN/PAIS">Projet PAPSEN/PAIS</option>
                    <option value="Sénégal Gold Opération (SGO)">Sénégal Gold Opération (SGO)</option>
                    <option value="SEN BOTO SA">SEN BOTO SA</option>
                    <option value="SOCOCIM">SOCOCIM</option>
                    <option value="Ciments de l’Afrique (CIMAF)">Ciments de l’Afrique (CIMAF)</option>
                    <option value="Compagnie Sucrière Sénégalaise (CSS)">Compagnie Sucrière Sénégalaise (CSS)</option>
                    <option value="Ciments du sahel">Ciments du sahel</option>
                    <option value="Dangote Cement">Dangote Cement</option>
                    <option value="Axa Assurances Sénégal">Axa Assurances Sénégal</option>
                    <option value="Ville de Dakar">Ville de Dakar</option>
                    <option value="Commune de Khombole">Commune de Khombole</option>
                    <option value="Commune de Sandiara">Commune de Sandiara</option>
                    <option value="Confédération nationale des Employeurs du Sénégal (CNES)">Confédération nationale des
                        Employeurs du Sénégal (CNES)</option>
                    <option value="Conseil national du Patronat (CNP)">Conseil national du Patronat (CNP)</option>
                    <option
                        value="Union nationale des Chambres de Commerce d’Industrie et d’Agriculture au Sénégal (UNCCIAS)">
                        Union nationale des Chambres de Commerce d’Industrie et d’Agriculture au Sénégal (UNCCIAS)
                    </option>
                    <option value="Union nationale des Chambres de Métiers du Sénégal (UNCM)">Union nationale des
                        Chambres de Métiers du Sénégal (UNCM)</option>
                    <option value="Union des Elus locaux du Sénégal">Union des Elus locaux du Sénégal</option>
                    <option value="Cadre des opérateurs de formation ONFP">Cadre des opérateurs de formation ONFP
                    </option>
                    <option value="Diaspora/bonnes volontés">Diaspora/bonnes volontés</option>
                    <option value="Club des Investisseurs Sénégalais">Club des Investisseurs Sénégalais</option>
                    <option value="Complexe Cheikh Ahmadoul Khadim pour l'Education et la Formation">Complexe Cheikh
                        Ahmadoul Khadim pour l'Education et la Formation</option>
                    <option value="Fondation Lonase">Fondation Lonase</option>
                    <option value="Table ronde des Etablissements de Formation Professionnelle et Technique">Table
                        ronde
                        des Etablissements de Formation Professionnelle et Technique</option>
                    <option value="ONG Pratical Action">ONG Pratical Action</option>
                    <option value="Nouvelles Editions Numériques Africaines (NENA)">Nouvelles Editions Numériques
                        Africaines (NENA)</option>
                    <option value="Institut Supérieur de formation à Distance (ISFAD)">Institut Supérieur de formation
                        à
                        Distance (ISFAD)</option>
                    <option value="Bureau International du Travail (BIT)">Bureau International du Travail (BIT)
                    </option>
                    <option value="Associates in Research And Education For Developement (ARED)">Associates in Research
                        And Education For Developement (ARED)</option>
                </select> --}}

                <select name="structure" id="structure" class="form-control select2" required>
                    <option value="">-- Choisir votre structure --</option>

                    <!-- Ministères et Directions -->
                    <optgroup label="Ministères et Directions">
                        <option value="Ministère de l’Emploi et de la Formation professionnelle et Technique (MEFPT)">
                            Ministère de l’Emploi et de la Formation professionnelle et Technique (MEFPT)</option>
                        <option value="Direction du Financement et du Partenariat avec les Organisations (MASAE)">
                            Direction du Financement et du Partenariat avec les Organisations (MASAE)</option>
                        <option value="Direction générale du Cadre de vie et de l’Hygiène publique (MULHP)">Direction
                            générale du Cadre de vie et de l’Hygiène publique (MULHP)</option>
                        <option value="DGCFEDSP / Ministère de l’Economie du Plan et de la Coopération (MEPC)">DGCFEDSP
                            / Ministère de l’Economie du Plan et de la Coopération (MEPC)</option>
                        <option value="Direction de la Planification, des Etudes et du Suivi-Evaluation (MEPM)">
                            Direction de la Planification, des Etudes et du Suivi-Evaluation (MEPM)</option>
                        <option value="Direction générale de la Formation professionnelle et technique (DGFPT)">
                            Direction générale de la Formation professionnelle et technique (DGFPT)</option>
                    </optgroup>

                    <!-- Ambassades et Représentations étrangères -->
                    <optgroup label="Ambassades et Représentations étrangères">
                        <option value="Ambassade des Émirats arabes unis à Dakar">Ambassade des Émirats arabes unis à
                            Dakar</option>
                        <option value="Ambassade du Qatar à Dakar">Ambassade du Qatar à Dakar</option>
                        <option value="Ambassade du Qatar à Koweït à Dakar">Ambassade du Qatar à Koweït à Dakar</option>
                        <option value="Délégation à l’Union Européenne au Sénégal (EEAS)">Délégation à l’Union
                            Européenne au Sénégal (EEAS)</option>
                        <option value="Ambassade du Canada">Ambassade du Canada</option>
                        <option value="Ambassade du Maroc">Ambassade du Maroc</option>
                        <option value="Délégation générale des Îles Canaries">Délégation générale des Îles Canaries
                        </option>
                        <option value="Chambre officielle de Commerce d’Espagne à Dakar">Chambre officielle de Commerce
                            d’Espagne à Dakar</option>
                        <option value="Délégation générale de la Wallonie Bruxelles">Délégation générale de la Wallonie
                            Bruxelles</option>
                    </optgroup>

                    <!-- Agences de coopération internationale -->
                    <optgroup label="Agences de coopération internationale">
                        <option value="Agence Française de Développement (AFD)">Agence Française de Développement (AFD)
                        </option>
                        <option value="Agence de coopération Belge (ENABEL)">Agence de coopération Belge (ENABEL)
                        </option>
                        <option value="Agence de coopération Allemande (KFW)">Agence de coopération Allemande (KFW)
                        </option>
                        <option value="Agence de coopération Allemande (GIZ)">Agence de coopération Allemande (GIZ)
                        </option>
                        <option value="Agence de coopération Luxembourgeoise (LuxDev)">Agence de coopération
                            Luxembourgeoise (LuxDev)</option>
                        <option value="Agence Andalouse de Coopération Internationale pour le Développement (AACID)">
                            Agence Andalouse de Coopération Internationale pour le Développement (AACID)</option>
                        <option value="Agence de coopération Turque (TIKA)">Agence de coopération Turque (TIKA)</option>
                        <option value="Agence Italienne pour la Coopération au Développement (AICS)">Agence Italienne
                            pour la Coopération au Développement (AICS)</option>
                        <option value="Agence japonaise de coopération Internationale (JICA)">Agence japonaise de
                            coopération Internationale (JICA)</option>
                    </optgroup>

                    <!-- Agences et Fonds nationaux -->
                    <optgroup label="Agences et Fonds nationaux">
                        <option value="Fonds de Financement de la Formation professionnelle et Technique (3FPT)">3FPT
                        </option>
                        <option value="Agence nationale pour la Promotion de l’Emploi des Jeunes (ANPEJ)">ANPEJ</option>
                        <option value="Centre National des Qualifications Professionnelles (CNQP)">CNQP</option>
                        <option value="Programme de Formation Ecole-Entreprise (PF2E)">PF2E</option>
                        <option value="Agence nationale de la Maison de l’Outil (ANAMO)">ANAMO</option>
                        <option value="Agence de Développement et d'Encadrement des PME (ADEPME)">ADEPME</option>
                        <option value="Agence de Promotion des Investissements et des Grands Travaux (APIX-SA)">APIX-SA
                        </option>
                        <option value="Caisse des Dépôts et de Consignations (CDC)">CDC</option>
                        <option value="Agence Sénégalaise d’Electrification Rurale (ASER)">ASER</option>
                        <option value="Agence pour l’Economie et la Maitrise de l’Energie (AEME)">AEME</option>
                        <option value="Fonds de Développement des Transports Terrestres (FDTT)">FDTT</option>
                        <option value="Fonds d'entretien routier autonome (FERA)">FERA</option>
                        <option value="Fonds de promotion de l’industrie cinématographique et audiovisuelle (FOPICA)">
                            FOPICA</option>
                        <option value="Délégation Générale à l'Entreprenariat Rapide des Femmes et des Jeunes (DER/FJ)">
                            DER/FJ</option>
                        <option value="Port Autonome de Dakar (PAD)">Port Autonome de Dakar (PAD)</option>
                        <option value="Dubai Port (DP World) Sénégal">Dubai Port (DP World) Sénégal</option>
                        <option value="Conseil sénégalais des Chargeurs (COSEC)">COSEC</option>
                        <option value="Société Africaine de Raffinage (SAR)">SAR</option>
                        <option value="Groupe SONATEL Orange">Groupe SONATEL Orange</option>
                        <option value="Société Nationale des Eaux du Sénégal (SONES)">SONES</option>
                        <option value="Sénégal Numérique (SENUM-SA)">SENUM-SA</option>
                        <option value="Conseil exécutif des Transports urbains durables (CETUD)">CETUD</option>
                        <option value="Office des Forages Ruraux (OFOR)">OFOR</option>
                        <option value="Société Nationale de Gestion Intégrée des Déchets (SONAGED)">SONAGED</option>
                        <option value="Télédiffusion Sénégal (TDS)">Télédiffusion Sénégal (TDS)</option>
                        <option value="Agence d'Exécution des Travaux Routiers (AGEROUTE)">AGEROUTE</option>
                        <option value="Agence Sénégalaise de Promotion Touristique (ASPT)">ASPT</option>
                        <option value="Office national de l’Assainissement du Sénégal (ONAS)">ONAS</option>
                    </optgroup>

                    <!-- Projets et Programmes -->
                    <optgroup label="Projets et Programmes">
                        <option value="Agri-Jeunes Tekki Ndawñi">Agri-Jeunes Tekki Ndawñi</option>
                        <option value="Projet Formation, dignité, inclusion et innovation (VIS)">Projet Formation,
                            dignité, inclusion et innovation (VIS)</option>
                        <option value="Projet Emplois Verts DELTA, Saloum">Projet Emplois Verts DELTA, Saloum</option>
                        <option value="Comité d’organisation des Jeux Olympiques de la Jeunesse (JOJ 2026)">Comité JOJ
                            2026</option>
                        <option value="Programme des Domaines Agricoles Communautaires (PRODAC)">PRODAC</option>
                        <option value="Fonds d’appui à la Stabilisation (FONSTAB)">FONSTAB</option>
                        <option value="Projet PAPSEN/PAIS">PAPSEN/PAIS</option>
                    </optgroup>

                    <!-- Entreprises privées -->
                    <optgroup label="Entreprises privées">
                        <option value="Sénégal Gold Opération (SGO)">SGO</option>
                        <option value="SEN BOTO SA">SEN BOTO SA</option>
                        <option value="SOCOCIM">SOCOCIM</option>
                        <option value="Ciments de l’Afrique (CIMAF)">CIMAF</option>
                        <option value="Compagnie Sucrière Sénégalaise (CSS)">CSS</option>
                        <option value="Ciments du Sahel">Ciments du Sahel</option>
                        <option value="Dangote Cement">Dangote Cement</option>
                        <option value="Axa Assurances Sénégal">Axa Assurances Sénégal</option>
                    </optgroup>

                    <!-- Collectivités et Organisations nationales -->
                    <optgroup label="Collectivités et Organisations nationales">
                        <option value="Ville de Dakar">Ville de Dakar</option>
                        <option value="Commune de Khombole">Commune de Khombole</option>
                        <option value="Commune de Sandiara">Commune de Sandiara</option>
                        <option value="Confédération nationale des Employeurs du Sénégal (CNES)">CNES</option>
                        <option value="Conseil national du Patronat (CNP)">CNP</option>
                        <option value="UNCCIAS">Union nationale des Chambres de Commerce, d’Industrie et d’Agriculture
                            (UNCCIAS)</option>
                        <option value="UNCM">Union nationale des Chambres de Métiers du Sénégal (UNCM)</option>
                        <option value="Union des Elus locaux du Sénégal">Union des Elus locaux du Sénégal</option>
                        <option value="Cadre des opérateurs de formation ONFP">Cadre des opérateurs de formation ONFP
                        </option>
                        <option value="Diaspora/bonnes volontés">Diaspora / Bonnes volontés</option>
                    </optgroup>

                    <!-- Organisations, ONG et Institutions -->
                    <optgroup label="Organisations, ONG et Institutions">
                        <option value="Club des Investisseurs Sénégalais">Club des Investisseurs Sénégalais</option>
                        <option value="Complexe Cheikh Ahmadoul Khadim pour l'Education et la Formation">Complexe
                            Cheikh Ahmadoul Khadim pour l'Éducation et la Formation</option>
                        <option value="Fondation Lonase">Fondation Lonase</option>
                        <option value="Table ronde des Etablissements de Formation Professionnelle et Technique">Table
                            ronde des Établissements de Formation</option>
                        <option value="ONG Pratical Action">ONG Pratical Action</option>
                        <option value="Nouvelles Editions Numériques Africaines (NENA)">Nouvelles Editions Numériques
                            Africaines (NENA)</option>
                        <option value="Institut Supérieur de formation à Distance (ISFAD)">ISFAD</option>
                        <option value="Bureau International du Travail (BIT)">Bureau International du Travail (BIT)
                        </option>
                        <option value="Associates in Research And Education For Developement (ARED)">ARED</option>
                        <option value="Centre d'études et de recherches sur les qualifications (CEREQ)">CEREQ</option>
                        <option value="Centre canadien de Coopération Internationale (CECI)">CECI</option>
                        <option value="Ecole Supérieure d’Economie Appliquée (ESEA)">Ecole Supérieure d’Economie
                            Appliquée (ESEA)</option>
                    </optgroup>
                </select>


            </div>


            <div class="form-group">
                <i class="bi bi-person input-icon"></i>
                <input type="text" name="nom" class="form-control"
                    placeholder="Prénom et Nom du représentant" value="{{ old('nom') }}" required>
            </div>

            <div class="form-group">
                <i class="bi bi-briefcase input-icon"></i>
                <input type="text" name="fonction" class="form-control" placeholder="Fonction"
                    value="{{ old('fonction') }}" required>
            </div>

            <div class="form-group">
                <i class="bi bi-telephone input-icon"></i>
                <input type="text" name="telephone" class="form-control" placeholder="Téléphone"
                    value="{{ old('telephone') }}" required>
            </div>

            <div class="form-group">
                <i class="bi bi-envelope input-icon"></i>
                <input type="email" name="email" class="form-control" placeholder="Adresse mail"
                    value="{{ old('email') }}" required>
            </div>
            <!-- Champ Commentaire -->
            <div class="form-group">
                {{-- <i class="bi bi-chat-left-text input-icon"></i> --}}
                <textarea name="commentaire" class="form-control" rows="4" placeholder="Votre commentaire (facultatif)">{{ old('commentaire') }}</textarea>
            </div>

            <input type="hidden" name="autre" value="PARTNERSHIP ENGAGEMENT DAY">

            <button type="submit" class="btn btn-primary btn-sm mt-3">Envoyer ma confirmation</button>
        </form>

        <p class="footer-text">© {{ date('Y') }} ONFP</p>
        {{-- <p class="footer-text">© {{ date('Y') }} ONFP — Tous droits réservés</p> --}}
    </div>
</body>

</html>
