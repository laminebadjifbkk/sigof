@extends('layout.user-layout')
@section('title', 'ONFP | INSCRIPTION PARTENAIRES')
@section('space-work')
    @can('inscriptioncontact-view')
        <section class="section register">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="pagetitle">
                        {{-- <h1>Data Tables</h1> --}}
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('/home') }}">Accueil</a></li>
                                <li class="breadcrumb-item">Tables</li>
                                <li class="breadcrumb-item active">Données</li>
                            </ol>
                        </nav>
                    </div><!-- End Page Title -->
                    @if ($message = Session::get('status'))
                        <div class="alert alert-success bg-success text-light border-0 alert-dismissible fade show"
                            role="alert">
                            <strong>{{ $message }}</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if ($message = Session::get('danger'))
                        <div class="alert alert-danger bg-danger text-light border-0 alert-dismissible fade show"
                            role="alert">
                            <strong>{{ $message }}</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if ($errors->any())
                        @foreach ($errors->all() as $error)
                            <div class="alert alert-danger bg-danger text-light border-0 alert-dismissible fade show"
                                role="alert"><strong>{{ $error }}</strong></div>
                        @endforeach
                    @endif
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="card-title mb-0">PARTENAIRES</h5>
                            </div>
                            <div class="table-responsive">
                                <table class="table datatables table-bordered table-hover align-middle justify-content-center"
                                    id="table-jury">
                                    <thead class="table-primary text-center">
                                        <tr>
                                            {{-- <th width="8%">Civilité</th>
                                            <th>Prénom</th> --}}
                                            <th>Nom</th>
                                            <th>Fonction</th>
                                            <th>Structure</th>
                                            <th>Email</th>
                                            <th>Téléphone</th>
                                            <th>Commentaires</th>
                                            <th>Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($inscriptions as $inscription)
                                            <tr>
                                                {{-- <td class="text-center">{{ $inscription?->civilite }}</td>
                                                <td>{{ $inscription?->prenom }}</td> --}}
                                                <td>{{ $inscription?->nom }}</td>
                                                <td>{{ $inscription?->fonction }}</td>
                                                <td>{{ $inscription?->structure }}</td>
                                                <td>
                                                    <a href="mailto:{{ $inscription?->email }}" class="text-decoration-none">
                                                        {{ $inscription?->email }}
                                                    </a>
                                                </td>
                                                <td class="text-center">
                                                    <a href="tel:+221{{ $inscription?->telephone }}"
                                                        class="text-decoration-none">
                                                        {{ $inscription?->telephone }}
                                                    </a>
                                                </td>
                                                <td>{{ $inscription?->commentaire }}</td>
                                                <td>
                                                    <span>
                                                        @php
                                                            $date = $inscription?->created_at
                                                                ? \Carbon\Carbon::parse($inscription?->created_at)
                                                                : null;
                                                        @endphp
                                                        <i class="bi bi-calendar-event text-success me-1"></i>
                                                        {{ $inscription?->created_at?->format('H:i:s') }}

                                                        @if ($date)
                                                            @if ($date->isToday())
                                                                <span class="badge bg-success">Aujourd'hui</span>
                                                            @elseif ($date->isYesterday())
                                                                <span class="badge bg-warning">Hier</span>
                                                            @elseif ($date->diffInDays(\Carbon\Carbon::today()) < 7)
                                                                <span class="badge bg-primary">
                                                                    Il y a {{ $date->diffInDays(\Carbon\Carbon::today()) }}
                                                                    jours
                                                                </span>
                                                            @else
                                                                @php
                                                                    $diff = $date->diff(\Carbon\Carbon::today());
                                                                    $ans = $diff->y;
                                                                    $mois = $diff->m;
                                                                    $jours = $diff->d;

                                                                    $parts = [];
                                                                    if ($ans > 0) {
                                                                        $parts[] =
                                                                            $ans .
                                                                            ' ' .
                                                                            \Illuminate\Support\Str::plural('an', $ans);
                                                                    }
                                                                    if ($mois > 0) {
                                                                        $parts[] = $mois . ' mois';
                                                                    } // "mois" invariable
                                                                    if ($jours > 0) {
                                                                        $parts[] =
                                                                            $jours .
                                                                            ' ' .
                                                                            \Illuminate\Support\Str::plural(
                                                                                'jour',
                                                                                $jours,
                                                                            );
                                                                    }
                                                                @endphp

                                                                <span class="badge bg-secondary">
                                                                    Il y a {{ implode(' ', $parts) }}
                                                                </span>
                                                            @endif
                                                        @else
                                                            <span class="badge bg-danger">Date non disponible</span>
                                                        @endif
                                                    </span>
                                                </td>
                                                {{-- <td class="text-center">
                                                    <div class="btn-group">
                                                        <a href="{{ route('inscriptioncontacts.show', $inscription) }}"
                                                            class="btn btn-warning btn-sm" title="Voir détails">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                    </div>
                                                </td> --}}
                                                {{-- <td class="text-center">
                                                    <div class="btn-group">
                                                        <button class="btn btn-warning btn-sm viewInscriptionBtn"
                                                            data-id="{{ $inscription->id }}" title="Voir détails">
                                                            <i class="bi bi-eye"></i>
                                                        </button>

                                                        <div class="filter">
                                                            <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                                                    class="bi bi-three-dots"></i></a>
                                                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                                                <form
                                                                    action="{{ route('inscriptioncontacts.destroy', $inscription->id) }}"
                                                                    method="post">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit"
                                                                        class="dropdown-item show_confirm">Supprimer</button>
                                                                </form>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </td> --}}
                                                <td class="text-center">
                                                    <div class="btn-group">
                                                        {{-- Bouton Voir --}}
                                                        <button class="btn btn-warning btn-sm viewInscriptionBtn"
                                                            data-id="{{ $inscription->id }}" title="Voir détails">
                                                            <i class="bi bi-eye"></i>
                                                        </button>&nbsp;

                                                        {{-- Bouton Modifier --}}
                                                        <button class="btn btn-info btn-sm editInscriptionBtn"
                                                            data-id="{{ $inscription->id }}" title="Modifier">
                                                            <i class="bi bi-pencil-square"></i>
                                                        </button>

                                                        {{-- Menu Supprimer --}}
                                                        <div class="filter">
                                                            <a class="icon" href="#" data-bs-toggle="dropdown">
                                                                <i class="bi bi-three-dots"></i>
                                                            </a>
                                                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                                                <form
                                                                    action="{{ route('inscriptioncontacts.destroy', $inscription->id) }}"
                                                                    method="post">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit"
                                                                        class="dropdown-item show_confirm">Supprimer</button>
                                                                </form>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal de visualisation -->
            <div class="modal fade" id="viewInscriptionModal" tabindex="-1" aria-labelledby="viewInscriptionLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-warning text-white">
                            <h5 class="modal-title" id="viewInscriptionLabel">Détails de l’inscription</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                        </div>
                        <div class="modal-body">
                            <div id="inscriptionDetails" class="p-3 text-center">
                                <div class="spinner-border text-warning" role="status">
                                    <span class="visually-hidden">Chargement...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal de modification -->
            <div class="modal fade" id="editInscriptionModal" tabindex="-1" aria-labelledby="editInscriptionLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-warning text-white">
                            <h5 class="modal-title" id="editInscriptionLabel">Modifier l’inscription</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                        </div>
                        {{-- <form id="editInscriptionForm">
                            @csrf
                            @method('PUT') --}}
                        <form method="POST" action="{{ route('inscriptioncontacts.update', $inscription->id ?? '') }}"
                            id="editInscriptionForm">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <label class="form-label">Structure</label>
                                        {{-- <input type="text" name="structure" class="form-control" required> --}}
                                        <select name="structure" id="structure" class="form-control select2" required>
                                            <option value="">-- Sélectionnez une structure --</option>
                                            <!-- Ministères et Directions -->
                                            <optgroup label="Ministères et Directions">
                                                <option
                                                    value="Ministère de l’Emploi et de la Formation professionnelle et Technique (MEFPT)">
                                                    Ministère de l’Emploi et de la Formation professionnelle et Technique
                                                    (MEFPT)</option>
                                                <option
                                                    value="Direction du Financement et du Partenariat avec les Organisations (MASAE)">
                                                    Direction du Financement et du Partenariat avec les Organisations (MASAE)
                                                </option>
                                                <option
                                                    value="Direction générale du Cadre de vie et de l’Hygiène publique (MULHP)">
                                                    Direction
                                                    générale du Cadre de vie et de l’Hygiène publique (MULHP)</option>
                                                <option
                                                    value="DGCFEDSP / Ministère de l’Economie du Plan et de la Coopération (MEPC)">
                                                    DGCFEDSP
                                                    / Ministère de l’Economie du Plan et de la Coopération (MEPC)</option>
                                                <option
                                                    value="Direction de la Planification, des Etudes et du Suivi-Evaluation (MEPM)">
                                                    Direction de la Planification, des Etudes et du Suivi-Evaluation (MEPM)
                                                </option>
                                                <option
                                                    value="Direction générale de la Formation professionnelle et technique (DGFPT)">
                                                    Direction générale de la Formation professionnelle et technique (DGFPT)
                                                </option>
                                            </optgroup>

                                            <!-- Ambassades et Représentations étrangères -->
                                            <optgroup label="Ambassades et Représentations étrangères">
                                                <option value="Ambassade des Émirats arabes unis à Dakar">Ambassade des Émirats
                                                    arabes unis à
                                                    Dakar</option>
                                                <option value="Ambassade du Qatar à Dakar">Ambassade du Qatar à Dakar</option>
                                                <option value="Ambassade du Qatar à Koweït à Dakar">Ambassade du Qatar à Koweït
                                                    à Dakar</option>
                                                <option value="Délégation à l’Union Européenne au Sénégal (EEAS)">Délégation à
                                                    l’Union
                                                    Européenne au Sénégal (EEAS)</option>
                                                <option value="Ambassade du Canada">Ambassade du Canada</option>
                                                <option value="Ambassade du Maroc">Ambassade du Maroc</option>
                                                <option value="Délégation générale des Îles Canaries">Délégation générale des
                                                    Îles Canaries
                                                </option>
                                                <option value="Chambre officielle de Commerce d’Espagne à Dakar">Chambre
                                                    officielle de Commerce
                                                    d’Espagne à Dakar</option>
                                                <option value="Délégation générale de la Wallonie Bruxelles">Délégation
                                                    générale de la Wallonie
                                                    Bruxelles</option>
                                            </optgroup>

                                            <!-- Agences de coopération internationale -->
                                            <optgroup label="Agences de coopération internationale">
                                                <option value="Agence Française de Développement (AFD)">Agence Française de
                                                    Développement (AFD)
                                                </option>
                                                <option value="Agence de coopération Belge (ENABEL)">Agence de coopération
                                                    Belge (ENABEL)
                                                </option>
                                                <option value="Agence de coopération Allemande (KFW)">Agence de coopération
                                                    Allemande (KFW)
                                                </option>
                                                <option value="Agence de coopération Allemande (GIZ)">Agence de coopération
                                                    Allemande (GIZ)
                                                </option>
                                                <option value="Agence de coopération Luxembourgeoise (LuxDev)">Agence de
                                                    coopération
                                                    Luxembourgeoise (LuxDev)</option>
                                                <option
                                                    value="Agence Andalouse de Coopération Internationale pour le Développement (AACID)">
                                                    Agence Andalouse de Coopération Internationale pour le Développement (AACID)
                                                </option>
                                                <option value="Agence de coopération Turque (TIKA)">Agence de coopération
                                                    Turque (TIKA)</option>
                                                <option value="Agence Italienne pour la Coopération au Développement (AICS)">
                                                    Agence Italienne
                                                    pour la Coopération au Développement (AICS)</option>
                                                <option value="Agence japonaise de coopération Internationale (JICA)">Agence
                                                    japonaise de
                                                    coopération Internationale (JICA)</option>
                                            </optgroup>

                                            <!-- Agences et Fonds nationaux -->
                                            <optgroup label="Agences et Fonds nationaux">
                                                <option
                                                    value="Fonds de Financement de la Formation professionnelle et Technique (3FPT)">
                                                    3FPT
                                                </option>
                                                <option
                                                    value="Agence nationale pour la Promotion de l’Emploi des Jeunes (ANPEJ)">
                                                    ANPEJ</option>
                                                <option value="Centre National des Qualifications Professionnelles (CNQP)">CNQP
                                                </option>
                                                <option value="Programme de Formation Ecole-Entreprise (PF2E)">PF2E</option>
                                                <option value="Agence nationale de la Maison de l’Outil (ANAMO)">ANAMO</option>
                                                <option value="Agence de Développement et d'Encadrement des PME (ADEPME)">
                                                    ADEPME</option>
                                                <option
                                                    value="Agence de Promotion des Investissements et des Grands Travaux (APIX-SA)">
                                                    APIX-SA
                                                </option>
                                                <option value="Caisse des Dépôts et de Consignations (CDC)">CDC</option>
                                                <option value="Agence Sénégalaise d’Electrification Rurale (ASER)">ASER
                                                </option>
                                                <option value="Agence pour l’Economie et la Maitrise de l’Energie (AEME)">AEME
                                                </option>
                                                <option value="Fonds de Développement des Transports Terrestres (FDTT)">FDTT
                                                </option>
                                                <option value="Fonds d'entretien routier autonome (FERA)">FERA</option>
                                                <option
                                                    value="Fonds de promotion de l’industrie cinématographique et audiovisuelle (FOPICA)">
                                                    FOPICA</option>
                                                <option
                                                    value="Délégation Générale à l'Entreprenariat Rapide des Femmes et des Jeunes (DER/FJ)">
                                                    DER/FJ</option>
                                                <option value="Port Autonome de Dakar (PAD)">Port Autonome de Dakar (PAD)
                                                </option>
                                                <option value="Dubai Port (DP World) Sénégal">Dubai Port (DP World) Sénégal
                                                </option>
                                                <option value="Conseil sénégalais des Chargeurs (COSEC)">COSEC</option>
                                                <option value="Société Africaine de Raffinage (SAR)">SAR</option>
                                                <option value="Groupe SONATEL Orange">Groupe SONATEL Orange</option>
                                                <option value="Société Nationale des Eaux du Sénégal (SONES)">SONES</option>
                                                <option value="Sénégal Numérique (SENUM-SA)">SENUM-SA</option>
                                                <option value="Conseil exécutif des Transports urbains durables (CETUD)">CETUD
                                                </option>
                                                <option value="Office des Forages Ruraux (OFOR)">OFOR</option>
                                                <option value="Société Nationale de Gestion Intégrée des Déchets (SONAGED)">
                                                    SONAGED</option>
                                                <option value="Télédiffusion Sénégal (TDS)">Télédiffusion Sénégal (TDS)
                                                </option>
                                                <option value="Agence d'Exécution des Travaux Routiers (AGEROUTE)">AGEROUTE
                                                </option>
                                                <option value="Agence Sénégalaise de Promotion Touristique (ASPT)">ASPT
                                                </option>
                                                <option value="Office national de l’Assainissement du Sénégal (ONAS)">ONAS
                                                </option>
                                            </optgroup>

                                            <!-- Projets et Programmes -->
                                            <optgroup label="Projets et Programmes">
                                                <option value="Agri-Jeunes Tekki Ndawñi">Agri-Jeunes Tekki Ndawñi</option>
                                                <option value="Projet Formation, dignité, inclusion et innovation (VIS)">Projet
                                                    Formation,
                                                    dignité, inclusion et innovation (VIS)</option>
                                                <option value="Projet Emplois Verts DELTA, Saloum">Projet Emplois Verts DELTA,
                                                    Saloum</option>
                                                <option
                                                    value="Comité d’organisation des Jeux Olympiques de la Jeunesse (JOJ 2026)">
                                                    Comité JOJ
                                                    2026</option>
                                                <option value="Programme des Domaines Agricoles Communautaires (PRODAC)">PRODAC
                                                </option>
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
                                                <option value="Confédération nationale des Employeurs du Sénégal (CNES)">CNES
                                                </option>
                                                <option value="Conseil national du Patronat (CNP)">CNP</option>
                                                <option value="UNCCIAS">Union nationale des Chambres de Commerce, d’Industrie
                                                    et d’Agriculture
                                                    (UNCCIAS)</option>
                                                <option value="UNCM">Union nationale des Chambres de Métiers du Sénégal
                                                    (UNCM)</option>
                                                <option value="Union des Elus locaux du Sénégal">Union des Elus locaux du
                                                    Sénégal</option>
                                                <option value="Cadre des opérateurs de formation ONFP">Cadre des opérateurs de
                                                    formation ONFP
                                                </option>
                                                <option value="Diaspora/bonnes volontés">Diaspora / Bonnes volontés</option>
                                            </optgroup>

                                            <!-- Organisations, ONG et Institutions -->
                                            <optgroup label="Organisations, ONG et Institutions">
                                                <option value="Club des Investisseurs Sénégalais">Club des Investisseurs
                                                    Sénégalais</option>
                                                <option
                                                    value="Complexe Cheikh Ahmadoul Khadim pour l'Education et la Formation">
                                                    Complexe
                                                    Cheikh Ahmadoul Khadim pour l'Éducation et la Formation</option>
                                                <option value="Fondation Lonase">Fondation Lonase</option>
                                                <option
                                                    value="Table ronde des Etablissements de Formation Professionnelle et Technique">
                                                    Table
                                                    ronde des Établissements de Formation</option>
                                                <option value="ONG Pratical Action">ONG Pratical Action</option>
                                                <option value="Nouvelles Editions Numériques Africaines (NENA)">Nouvelles
                                                    Editions Numériques
                                                    Africaines (NENA)</option>
                                                <option value="Institut Supérieur de formation à Distance (ISFAD)">ISFAD
                                                </option>
                                                <option value="Bureau International du Travail (BIT)">Bureau International du
                                                    Travail (BIT)
                                                </option>
                                                <option value="Associates in Research And Education For Developement (ARED)">
                                                    ARED</option>
                                                <option
                                                    value="Centre d'études et de recherches sur les qualifications (CEREQ)">
                                                    CEREQ</option>
                                                <option value="Centre canadien de Coopération Internationale (CECI)">CECI
                                                </option>
                                                <option value="Ecole Supérieure d’Economie Appliquée (ESEA)">Ecole Supérieure
                                                    d’Economie
                                                    Appliquée (ESEA)</option>
                                            </optgroup>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Nom</label>
                                        <input type="text" name="nom" class="form-control" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Fonction</label>
                                        <input type="text" name="fonction" class="form-control" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Téléphone</label>
                                        <input type="text" name="telephone" class="form-control" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control" required>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Commentaire</label>
                                        <textarea name="commentaire" class="form-control" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary btn-sm"
                                    data-bs-dismiss="modal">Fermer</button>
                                {{-- <button type="submit" class="btn btn-primary btn-sm">Enregistrer</button> --}}
                                <button type="submit" class="btn btn-primary btn-sm">Enregistrer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    @endcan
@endsection

@push('scripts')
    <script>
        new DataTable('#table-jury', {
            ordering: false, // désactive le tri automatique
            layout: {
                topStart: {
                    buttons: ['csv', 'excel', 'print'],
                }
            },
            language: {
                "sProcessing": "Traitement en cours...",
                "sSearch": "Rechercher&nbsp;:",
                "sLengthMenu": "Afficher _MENU_ &eacute;l&eacute;ments",
                "sInfo": "Affichage de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
                "sInfoEmpty": "Affichage de l'&eacute;l&eacute;ment 0 &agrave; 0 sur 0 &eacute;l&eacute;ment",
                "sInfoFiltered": "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
                "sInfoPostFix": "",
                "sLoadingRecords": "Chargement en cours...",
                "sZeroRecords": "Aucun &eacute;l&eacute;ment &agrave; afficher",
                "sEmptyTable": "Aucune donn&eacute;e disponible dans le tableau",
                "oPaginate": {
                    "sFirst": "Premier",
                    "sPrevious": "Pr&eacute;c&eacute;dent",
                    "sNext": "Suivant",
                    "sLast": "Dernier"
                },
                "oAria": {
                    "sSortAscending": ": activer pour trier la colonne par ordre croissant",
                    "sSortDescending": ": activer pour trier la colonne par ordre d&eacute;croissant"
                },
                "select": {
                    "rows": {
                        _: "%d lignes sÃ©lÃ©ctionnÃ©es",
                        0: "Aucune ligne sÃ©lÃ©ctionnÃ©e",
                        1: "1 ligne sÃ©lÃ©ctionnÃ©e"
                    }
                }
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            $('.viewInscriptionBtn').on('click', function() {
                const id = $(this).data('id');
                const modal = $('#viewInscriptionModal');
                const detailsContainer = $('#inscriptionDetails');

                // Affiche la modale avec le loader
                modal.modal('show');
                detailsContainer.html(
                    '<div class="spinner-border text-warning" role="status"><span class="visually-hidden">Chargement...</span></div>'
                );

                // Requête AJAX
                $.ajax({
                    url: "{{ url('/inscriptioncontacts') }}/" + id + "/details",
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        if (data.error) {
                            detailsContainer.html('<div class="alert alert-danger">' + data
                                .error + '</div>');
                            return;
                        }

                        let html = `
                    <table class="table table-bordered">
                        <tr><th>Structure</th><td>${data.structure ?? ''}</td></tr>
                        <tr><th>Nom</th><td>${data.nom ?? ''}</td></tr>
                        <tr><th>Fonction</th><td>${data.fonction ?? ''}</td></tr>
                        <tr><th>Téléphone</th><td>${data.telephone ?? ''}</td></tr>
                        <tr><th>Email</th><td>${data.email ?? ''}</td></tr>
                        <tr><th>Commentaire</th><td>${data.commentaire ?? ''}</td></tr>
                    </table>
                `;
                        detailsContainer.html(html);
                    },
                    error: function(xhr) {
                        detailsContainer.html(
                            '<div class="alert alert-danger">Erreur lors du chargement des données.</div>'
                        );
                        console.error(xhr.responseText);
                    }
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Bouton Modifier
            $('.editInscriptionBtn').on('click', function() {
                const id = $(this).data('id');
                const modal = $('#editInscriptionModal');
                const form = $('#editInscriptionForm');

                // Charger les données dans le formulaire
                $.get("{{ url('/inscriptioncontacts') }}/" + id + "/details", function(data) {
                    form.attr('action', "{{ url('/inscriptioncontacts') }}/" + id);
                    form.find('select[name="structure"]').val(data.structure).trigger('change');
                    form.find('input[name="nom"]').val(data.nom);
                    form.find('input[name="fonction"]').val(data.fonction);
                    form.find('input[name="telephone"]').val(data.telephone);
                    form.find('input[name="email"]').val(data.email);
                    form.find('textarea[name="commentaire"]').val(data.commentaire);
                    modal.modal('show');
                });
            });
        });
    </script>
@endpush
