@extends('layout.user-layout')
@section('title', 'ONFP - Formation ' . $type_formation . ' ' . $formation?->name)
@section('space-work')
    <section
        class="section profile min-vh-0 d-flex flex-column align-items-center justify-content-center py-0 section profile">
        <div class="container-fluid">
            <div class="pagetitle">
                {{-- <h1>Data Tables</h1> --}}
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/home') }}">Accueil</a></li>
                        <li class="breadcrumb-item">Tables</li>
                        <li class="breadcrumb-item active">Formation {{ $type_formation }}</li>
                    </ol>
                </nav>
            </div>
            <!-- End Title -->
            <div class="row justify-content-center">
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
                <div class="flex items-center gap-4">
                    <div class="card">
                        <div class="card-body">
                            <ul class="nav nav-tabs nav-tabs-bordered">

                                <li class="nav-item">
                                    <span class="nav-link"><a href="{{ route('formations.index', $formation) }}"
                                            class="btn btn-secondary btn-sm" title="retour"><i
                                                class="bi bi-arrow-counterclockwise"></i></a>
                                    </span>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-overview">Détails
                                        formation</button>
                                </li>

                                <li class="nav-item">
                                    <button class="nav-link" data-bs-toggle="tab"
                                        data-bs-target="#operateur-overview">Opérateur</button>
                                </li>

                                <li class="nav-item">
                                    <button class="nav-link active" data-bs-toggle="tab"
                                        data-bs-target="#beneficiaires-overview">Bénéficiaires
                                    </button>
                                </li>

                                <li class="nav-item">
                                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#module-overview">Module
                                    </button>
                                </li>

                                <li class="nav-item">
                                    <button class="nav-link" data-bs-toggle="tab"
                                        data-bs-target="#ingenieur-overview">Ingénieur
                                    </button>
                                </li>

                                <li class="nav-item">
                                    <button class="nav-link" data-bs-toggle="tab"
                                        data-bs-target="#emargement-overview">Suivi
                                    </button>
                                </li>

                                <li class="nav-item">
                                    <button class="nav-link" data-bs-toggle="tab"
                                        data-bs-target="#evaluation-overview">Évaluation
                                    </button>
                                </li>

                                <li class="nav-item">
                                    <button class="nav-link" data-bs-toggle="tab"
                                        data-bs-target="#retrait-attestation-overview">Attestations
                                    </button>
                                </li>

                            </ul>
                            <div class="d-flex justify-content-between align-items-center">
                            </div>
                            <div class="tab-content pt-0">
                                <div class="tab-pane fade profile-overview pt-3" id="profile-overview">
                                    <form method="post" action="#" enctype="multipart/form-data" class="row g-3">
                                        @csrf
                                        @method('PUT')

                                        <h5 class="mb-2 text-uppercase fw-bold text-info">
                                            <i class="bi bi-people-fill me-2"></i>Détails formation
                                        </h5>

                                        <div class="col-12 mb-1">
                                            <div class="label">Intitulé formation</div>
                                            <div>{{ $formation?->name }}</div>
                                        </div>

                                        <div class="col-12 col-md-3 mb-1">
                                            <div class="label">Code</div>
                                            <div>{{ $formation?->code }}</div>
                                        </div>

                                        @if (!empty($formation?->module?->name))
                                            <div class="col-12 col-md-3 mb-1">
                                                <div class="label">Module</div>
                                                <div>{{ $formation?->module?->name }}</div>
                                            </div>
                                        @endif

                                        <div class="col-12 col-md-3 mb-1">
                                            <div class="label">Région</div>
                                            <div>{{ $formation?->departement->region->nom }}</div>
                                        </div>

                                        <div class="col-12 col-md-3 mb-1">
                                            <div class="label">Département</div>
                                            <div>{{ $formation->departement->nom }}
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-3 mb-1">
                                            <div class="label">Adresse exacte</div>
                                            <div>{{ $formation?->lieu }}
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-3 mb-1">
                                            <div class="label">Type formation</div>
                                            <div>{{ $formation?->types_formation?->name }}</div>
                                        </div>

                                        <div class="col-12 col-md-3 mb-1">
                                            <div class="label">Niveau qualification</div>
                                            <div>{{ $formation?->titre ?? $formation?->referentiel?->titre }}</div>
                                        </div>

                                        @if (!empty($formation?->date_debut))
                                            <div class="col-12 col-md-3 mb-1">
                                                <div class="label">Date début</div>
                                                <div>{{ $formation?->date_debut->format('d/m/Y') }}</div>
                                            </div>
                                        @endif

                                        @if (!empty($formation?->date_fin))
                                            <div class="col-12 col-md-3 mb-1">
                                                <div class="label">Date fin</div>
                                                <div>{{ $formation?->date_fin->format('d/m/Y') }}</div>
                                            </div>
                                        @endif

                                        @if (!empty($formation?->effectif_prevu))
                                            <div class="col-12 col-md-3 mb-1">
                                                <div class="label">Effectif prévu</div>
                                                <div>{{ $formation?->effectif_prevu }}</div>
                                            </div>
                                        @endif

                                        @if (!empty($formation?->prevue_h))
                                            <div class="col-12 col-md-3 mb-1">
                                                <div class="label">Prévu hommes</div>
                                                <div>{{ $formation?->prevue_h }}</div>
                                            </div>
                                        @endif

                                        @if (!empty($formation?->prevue_f))
                                            <div class="col-12 col-md-3 mb-1">
                                                <div class="label">Prévu femmes</div>
                                                <div>{{ $formation?->prevue_f }}</div>
                                            </div>
                                        @endif

                                        @if (!empty($formation?->duree_formation))
                                            <div class="col-12 col-md-3 mb-1">
                                                <div class="label">Durée (jours)</div>
                                                <div>{{ $formation?->duree_formation }}</div>
                                            </div>
                                        @endif

                                        @if (!empty($formation?->lieu))
                                            <div class="col-12 col-md-3 mb-1">
                                                <div class="label">Lieu</div>
                                                <div>{{ $formation?->lieu }}</div>
                                            </div>
                                        @endif

                                        @if (!empty($formation?->types_formation?->name))
                                            <div class="col-12 col-md-3 mb-1">
                                                <div class="label">Type formation</div>
                                                <div>{{ $formation?->types_formation?->name }}</div>
                                            </div>
                                        @endif

                                        @if (!empty($formation?->type_certification))
                                            <div class="col-12 col-md-3 mb-1">
                                                <div class="label">Type certification</div>
                                                <div>{{ $formation?->type_certification }}</div>
                                            </div>
                                        @endif

                                        @if (!empty($formation?->lettre_mission))
                                            <div class="col-12 col-md-3 mb-1">
                                                <div class="label">N° lettre mission DIOF</div>
                                                <div>{{ $formation?->lettre_mission }}</div>
                                            </div>
                                        @endif

                                        @if (!empty($formation?->date_lettre?->format('Y-m-d')))
                                            <div class="col-12 col-md-3 mb-1">
                                                <div class="label">Date lettre mission DIOF</div>
                                                <div>{{ $formation?->date_lettre?->format('d/m/Y') }}</div>
                                            </div>
                                        @endif

                                        @if (!empty($formation?->numero_convention))
                                            <div class="col-12 col-md-3 mb-1">
                                                <div class="label">N° convention</div>
                                                <div>{{ $formation?->numero_convention }}</div>
                                            </div>
                                        @endif

                                        @if (!empty($formation?->date_convention?->format('Y-m-d')))
                                            <div class="col-12 col-md-3 mb-1">
                                                <div class="label">Date convention</div>
                                                <div>{{ $formation?->date_convention?->format('d/m/Y') }}</div>
                                            </div>
                                        @endif

                                        @if (!empty($formation?->file_convention))
                                            <div class="col-12 col-md-3 mb-1">
                                                <div class="label">Scan convention</div>
                                                <div>
                                                    <a class="btn btn-outline-secondary btn-sm" title="DETF"
                                                        target="_blank"
                                                        href="{{ asset($formation->getFileConvention()) }}">
                                                        <i class="bi bi-file-earmark-pdf"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        @endif

                                        @if (!empty($formation?->detf_file))
                                            <div class="col-12 col-md-3 mb-1">
                                                <div class="label">Scan DETF</div>
                                                <div>
                                                    <a class="btn btn-outline-secondary btn-sm" title="DETF"
                                                        target="_blank" href="{{ asset($formation->getFileDetf()) }}">
                                                        <i class="bi bi-file-earmark-pdf"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        @endif

                                        @if (!empty($formation?->file_pv))
                                            <div class="col-12 col-md-3 mb-1">
                                                <div class="label">Scan PV</div>
                                                <div>
                                                    <a class="btn btn-outline-secondary btn-sm" title="DETF"
                                                        target="_blank" href="{{ asset($formation->getFilePV()) }}">
                                                        <i class="bi bi-file-earmark-pdf"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        @endif

                                        @if (!empty($formation?->lettre_mission_file))
                                            <div class="col-12 col-md-3 mb-1">
                                                <div class="label">Scan LM</div>
                                                <div>
                                                    <a class="btn btn-outline-secondary btn-sm" title="DETF"
                                                        target="_blank" href="{{ asset($formation->getFileLM()) }}">
                                                        <i class="bi bi-file-earmark-pdf"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        @endif

                                        @if (!empty($formation?->date_pv?->format('Y-m-d')))
                                            <div class="col-12 col-md-3 mb-1">
                                                <div class="label">Date évaluation</div>
                                                <div>{{ $formation?->date_pv?->format('d/m/Y') }}</div>
                                            </div>
                                        @endif

                                        @if (!empty($formation?->frais_evaluateur))
                                            <div class="col-12 col-md-3 mb-1">
                                                <div class="label">Frais évaluateur</div>
                                                <div>{{ $formation?->frais_evaluateur }}</div>
                                            </div>
                                        @endif

                                        @if (!empty($formation?->frais_evaluateur))
                                            <div class="col-12 col-md-3 mb-1">
                                                <div class="label">Evaluateur(s)</div>
                                                <div>{{ $formation?->evaluateurs?->count() }}</div>
                                            </div>
                                        @endif

                                        @if (!empty($formation?->frais_operateurs))
                                            <div class="col-12 col-md-3 mb-1">
                                                <div class="label">Frais opérateur</div>
                                                <div>{{ number_format($formation?->frais_operateurs, 2, ',', ' ') }}</div>
                                            </div>
                                        @endif

                                        @if (!empty($formation?->frais_add))
                                            <div class="col-12 col-md-3 mb-1">
                                                <div class="label">Frais additionels</div>
                                                <div>{{ number_format($formation?->frais_add, 2, ',', ' ') }}</div>
                                            </div>
                                        @endif

                                        @if (!empty($formation?->autes_frais))
                                            <div class="col-12 col-md-3 mb-1">
                                                <div class="label">Autres frais</div>
                                                <div>{{ number_format($formation?->autes_frais, 2, ',', ' ') }}</div>
                                            </div>
                                        @endif

                                        @if (!empty($formation?->projets_id))
                                            <div class="col-12 col-md-3 mb-1">
                                                <div class="label">Partenaire</div>
                                                <div>{{ $formation?->projet?->name }}</div>
                                            </div>
                                        @endif

                                        @if (!empty($formation?->programmes_id))
                                            <div class="col-12 col-md-3 mb-1">
                                                <div class="label">Programme</div>
                                                <div>{{ $formation?->programme?->name }}</div>
                                            </div>
                                        @endif

                                        @if (!empty($formation?->choixoperateur?->description))
                                            <div class="col-12 col-md-3 mb-1">
                                                <div class="label">Choix opérateur</div>
                                                <div>{{ !empty($formation?->choixoperateur?->description) }}</div>
                                            </div>
                                        @endif


                                        <div class="col-12 mb-1 text-center pt-5">
                                            <a class="btn btn-outline-primary btn-sm"
                                                href="{{ route('formations.edit', $formation) }}" class="mx-1"
                                                title="Modifier">Modifier cette formation</a>
                                        </div>
                                    </form>

                                </div>
                            </div>
                            {{-- Détail --}}
                            <div class="tab-content">
                                <div class="tab-pane fade profile-overview" id="operateur-overview">
                                    @if (!empty($operateur))
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h5 class="card-title">
                                                {{ $formation?->operateur?->user?->operateur . '(' . $formation?->operateur?->user?->username . ')' }}
                                                @can('operateur-check')
                                                    <a class="btn btn-info btn-sm" title=""
                                                        href="{{ route('operateurs.show', $formation?->operateur) }}"><i
                                                            class="bi bi-eye"></i></a>&nbsp;
                                                    <a href="{{ url('formationcollectiveoperateurs', ['$idformation' => $formation->id, '$idcollectivemodule' => $formation->collectivemodule->id, '$idlocalite' => $formation->departement->region->id]) }}"
                                                        class="btn btn-primary float-end btn-sm">
                                                        <i class="bi bi-pencil" title="Changer opérateur"></i> </a>
                                                @endcan
                                            </h5>
                                        </div>
                                    @elseif(!empty($formation->collectivemodule->module))
                                        
                                        @can('operateur-check')
                                            <div class="pt-2 text-end">
                                                <a href="{{ url('formationcollectiveoperateurs', [
                                                    'idformation' => $formation?->id,
                                                    'idcollectivemodule' => $formation?->collectivemodule?->id,
                                                    'idlocalite' => $formation?->departement?->region?->id,
                                                ]) }}"
                                                    class="btn btn-info btn-sm shadow-sm d-inline-flex align-items-center gap-1">
                                                    <i class="bi bi-person-plus-fill"></i>
                                                    <span>Ajouter opérateur</span>
                                                </a>
                                            </div>
                                        @endcan
                                    @else
                                    @endif
                                    
                                </div>
                            </div>
                            <div class="tab-content pt-0">
                                <div class="tab-pane fade show active profile-overview" id="beneficiaires-overview">
                                    
                                    @if (!empty($formation?->collectivemodule))
                                        <div class="col-12 mb-0">
                                            <div class="d-flex justify-content-between align-items-center mt-3">
                                                <span class="card-title d-flex align-items-baseline">Code:&nbsp;
                                                    <span class="badge bg-info text-white">
                                                        {{ $formation?->code }}</span>
                                                </span>
                                                @can('formation-show')
                                                    {{-- @if (auth()->user()->hasRole('super-admin')) --}}
                                                    <span class="card-title d-flex align-items-baseline">Statut:&nbsp;
                                                        <span class="{{ $formation?->statut }} text-white">
                                                            {{ $formation?->statut }}</span>
                                                        <div class="filter">
                                                            <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                                                    class="bi bi-three-dots"></i></a>
                                                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                                                <form action="{{ route('listePresenceCol') }}" method="post"
                                                                    target="_blank">
                                                                    @csrf
                                                                    {{-- @method('PUT') --}}
                                                                    <input type="hidden" name="id"
                                                                        value="{{ $formation->id }}">
                                                                    <button class="btn btn-sm mx-1">Liste
                                                                        bénéficiaires</button>
                                                                </form>
                                                                <hr>
                                                                @can('demarrer-formation')
                                                                    <form
                                                                        action="{{ route('formations.notifyStart', $formation) }}"
                                                                        method="POST">
                                                                        @csrf
                                                                        <button type="submit"
                                                                            class="show_confirm_valider btn btn-sm mx-1">
                                                                            Démarrage notifications
                                                                        </button>
                                                                    </form>
                                                                @endcan
                                                                @can('terminer-formation')
                                                                    <form action="{{ route('formationTerminer') }}"
                                                                        method="post">
                                                                        @csrf
                                                                        {{-- @method('PUT') --}}
                                                                        <input type="hidden" name="id"
                                                                            value="{{ $formation->id }}">
                                                                        <button
                                                                            class="show_confirm_valider btn btn-sm mx-1">Terminer</button>
                                                                    </form>
                                                                @endcan

                                                                @can('annuler-formation')
                                                                    <button class="btn btn-sm mx-1" data-bs-toggle="modal"
                                                                        data-bs-target="#SuspendreDemandeModal">Traitement
                                                                    </button>
                                                                @endcan
                                                                <hr>
                                                                <form action="{{ route('feuillePresenceCol') }}"
                                                                    method="post" target="_blank">
                                                                    @csrf
                                                                    {{-- @method('PUT') --}}
                                                                    <input type="hidden" name="id"
                                                                        value="{{ $formation->id }}">
                                                                    <button class="btn btn-sm mx-1">Feuille présence</button>
                                                                </form>
                                                                <form action="{{ route('ficheSuiviCol') }}" method="post"
                                                                    target="_blank">
                                                                    @csrf
                                                                    {{-- @method('PUT') --}}
                                                                    <input type="hidden" name="id"
                                                                        value="{{ $formation->id }}">
                                                                    <button class="btn btn-sm mx-1">Feuille de
                                                                        présence</button>
                                                                </form>
                                                                @can('pv-formation')
                                                                    <form action="{{ route('pvViergeCol') }}" method="post"
                                                                        target="_blank">
                                                                        @csrf
                                                                        {{-- @method('PUT') --}}
                                                                        <input type="hidden" name="id"
                                                                            value="{{ $formation->id }}">
                                                                        <button class="btn btn-sm mx-1">PV vierge</button>
                                                                    </form>
                                                                    <form action="{{ route('pvEvaluationCol') }}" method="post"
                                                                        target="_blank">
                                                                        @csrf
                                                                        {{-- @method('PUT') --}}
                                                                        <input type="hidden" name="id"
                                                                            value="{{ $formation->id }}">
                                                                        <button class="btn btn-sm mx-1">PV Finale</button>
                                                                    </form>
                                                                @endcan
                                                                <hr>
                                                                @can('email-formation')
                                                                    <form action="{{ route('sendFormationEmailCol') }}"
                                                                        method="post">
                                                                        @csrf
                                                                        {{-- @method('PUT') --}}
                                                                        <input type="hidden" name="id"
                                                                            value="{{ $formation->id }}">
                                                                        <button
                                                                            class="show_confirm_valider btn btn-sm mx-1">Démarrage
                                                                            (e-mail)</button>
                                                                    </form>

                                                                    <form action="{{ route('sendWelcomeEmailCol') }}"
                                                                        method="post">
                                                                        @csrf
                                                                        {{-- @method('PUT') --}}
                                                                        <input type="hidden" name="id"
                                                                            value="{{ $formation->id }}">
                                                                        <button
                                                                            class="show_confirm_valider btn btn-sm mx-1">Résultats
                                                                            (e-mail)</button>
                                                                    </form>
                                                                @endcan
                                                                @can('sms-formation')
                                                                    <hr>

                                                                    <button class="btn btn-sm mx-1" data-bs-toggle="modal"
                                                                        data-bs-target="#sendFormationSMS">Démarrage
                                                                        (SMS)
                                                                    </button>
                                                                    <br>
                                                                    <button class="btn btn-sm mx-1" data-bs-toggle="modal"
                                                                        data-bs-target="#sendWelcomeSMS">Résultats
                                                                        (SMS)
                                                                    </button>
                                                                @endcan
                                                                <form action="{{ route('suivretousCol', $formation?->id) }}"
                                                                    method="post">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <button class="show_confirm_suivi btn btn-sm mx-1">Suivre
                                                                        tous</button>
                                                                </form>
                                                            </ul>
                                                        </div>
                                                    </span>
                                                @endcan
                                                <div class="float-end">
                                                    <a href="{{ url('formationdemandeurscollectives', [
                                                        'idformation' => $formation->id,
                                                        'idcollectivemodule' => $formation?->collectivemodule?->id,
                                                        'idlocalite' => $formation->departement->region->id,
                                                    ]) }}"
                                                        class="btn btn-outline-primary btn-sm rounded-pill shadow-sm d-inline-flex align-items-center gap-1 px-3 py-1"
                                                        style="transition: all 0.3s ease;">
                                                        <i class="bi bi-box-arrow-in-down"></i>
                                                        <span>Intégrer bénéficiaires</span>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="row g-3 pt-3">
                                                <h5 class="mb-0 text-uppercase fw-bold text-primary">
                                                    <i class="bi bi-people-fill me-2"></i> Liste des bénéficiaires
                                                </h5>
                                                <table
                                                    class="table table-bordered table-hover datatables align-middle justify-content-center table-borderless"
                                                    id="table-operateurModules">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center" width="2%">N°</th>
                                                            <th class="text-center" width="15">CIN</th>
                                                            <th class="text-center">Civilité</th>
                                                            <th class="text-center">Prénom</th>
                                                            <th class="text-center">Nom</th>
                                                            <th class="text-center">Date naissance</th>
                                                            <th class="text-center">Lieu naissance</th>
                                                            <th class="text-center">Telephone</th>
                                                            <th class="text-center">Niveau étude</th>
                                                            @if ($formation->statut === 'Terminée')
                                                                <th class="text-center">Note</th>
                                                                <th class="text-center">Appréciation</th>
                                                                @can('rapport-suivi-formes-view')
                                                                    <th class="text-center">Suivi</th>
                                                                @endcan
                                                            @endif
                                                            <th class="col"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php $i = 1; ?>
                                                        @foreach ($listecollectives as $listecollective)
                                                            <tr class="text-center">
                                                                <td class="text-center">{{ $i++ }}</td>
                                                                <td>
                                                                    {{ $listecollective?->cin }}</td>
                                                                <td>
                                                                    {{ $listecollective?->civilite }}</td>
                                                                <td>
                                                                    {{ $listecollective?->prenom }}</td>
                                                                <td>
                                                                    {{ $listecollective?->nom }}</td>
                                                                <td>
                                                                    {{ $listecollective?->date_naissance->format('d/m/Y') }}
                                                                </td>
                                                                <td>
                                                                    {{ $listecollective?->lieu_naissance }}
                                                                </td>
                                                                <td>
                                                                    {{ $listecollective?->telephone }}
                                                                </td>
                                                                <td>
                                                                    {{ $listecollective?->niveau_etude }}
                                                                </td>
                                                                @if ($formation->statut === 'Terminée')
                                                                    <td>
                                                                        {{ $listecollective?->note_obtenue }}
                                                                    </td>
                                                                    <td>
                                                                        {{ $listecollective?->appreciation }}
                                                                    </td>
                                                                    @can('rapport-suivi-formes-view')
                                                                        <td>
                                                                            @if (empty($listecollective?->suivi))
                                                                                <form
                                                                                    action="{{ route('SuivreFormesCol', $listecollective?->id) }}"
                                                                                    method="post">
                                                                                    @csrf
                                                                                    @method('PUT')
                                                                                    <button
                                                                                        class="show_confirm_suivi btn btn-dark rounded-pill btn-sm float-center">Suivre</button>
                                                                                </form>
                                                                            @else
                                                                                <button type="button"
                                                                                    class="btn btn-success rounded-pill btn-sm float-center">{{ $listecollective?->suivi }}</button>
                                                                            @endif
                                                                        </td>
                                                                    @endcan
                                                                @endif

                                                                <td class="text-center">
                                                                    <div
                                                                        class="d-flex justify-content-center align-items-center gap-2">
                                                                        <!-- Bouton voir détails -->
                                                                        <a href="{{ route('listecollectives.show', $listecollective) }}"
                                                                            class="btn btn-primary btn-sm"
                                                                            title="Voir détails" target="_blank">
                                                                            <i class="bi bi-eye"></i>
                                                                        </a>

                                                                        <!-- Dropdown menu -->
                                                                        <div class="dropdown">
                                                                            <a href="#" class="btn btn-sm btn-light"
                                                                                data-bs-toggle="dropdown"
                                                                                aria-expanded="false" title="Actions">
                                                                                <i class="bi bi-three-dots-vertical"></i>
                                                                            </a>
                                                                            <ul class="dropdown-menu dropdown-menu-end">
                                                                                <!-- Modifier -->
                                                                                <li>
                                                                                    <a class="dropdown-item"
                                                                                        href="{{ route('listecollectives.edit', $listecollective) }}">
                                                                                        <i
                                                                                            class="bi bi-pencil-square me-1"></i>
                                                                                        Modifier
                                                                                    </a>
                                                                                </li>

                                                                                <!-- Retirer (modal) -->
                                                                                @can('retirer-demandeur-formation')
                                                                                    <li>
                                                                                        <button type="button"
                                                                                            class="dropdown-item"
                                                                                            data-bs-toggle="modal"
                                                                                            data-bs-target="#indiponibleModal{{ $listecollective->id }}">
                                                                                            <i class="bi bi-person-x me-1"></i>
                                                                                            Retirer
                                                                                        </button>
                                                                                    </li>
                                                                                @endcan

                                                                                <!-- Ne plus suivre -->
                                                                                @if (!empty($listecollective?->suivi))
                                                                                    <li>
                                                                                        <form
                                                                                            action="{{ route('nepasSuivreCol', $listecollective->id) }}"
                                                                                            method="POST" class="px-3">
                                                                                            @csrf
                                                                                            @method('PUT')
                                                                                            <button type="submit"
                                                                                                class="dropdown-item text-danger show_confirm_suivi">
                                                                                                <i
                                                                                                    class="bi bi-slash-circle me-1"></i>
                                                                                                Ne plus suivre
                                                                                            </button>
                                                                                        </form>
                                                                                    </li>
                                                                                @endif
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
                                    @else
                                        <div class="alert alert-info">Aucun bénéficiaire pour le moment
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Détail Modules --}}
                            <div class="tab-content pt-2">
                                <div class="tab-pane fade module-overview pt-3" id="module-overview">
                                    @if (!empty($module_collective))
                                        {{-- <div class="d-flex justify-content-between align-items-center"> --}}
                                        <h5 class="card-title">
                                            Module : {{ $module_collective?->module }}
                                            @can('module-check')
                                                <a href="{{ url('formationcollectivemodules', ['$idformation' => $formation->id, '$idlocalite' => $formation->departement->region->id]) }}"
                                                    class="btn btn-outline-primary btn-sm" target="_blank">
                                                    <i class="bi bi-pencil" title="Changer module"></i></a>
                                            @endcan
                                        </h5>

                                        <h5 class="card-title">
                                            Structure :
                                            {{ $formation?->collectivemodule?->collective->name . ' (' . $formation?->collectivemodule?->collective->sigle . ')' }}
                                            <a class="btn btn-outline-info btn-sm" title="modifier module"
                                                href="{{ route('collectives.show', $formation->collectivemodule?->collective) }}"
                                                target="_blank"><i class="bi bi-eye"></i></a>
                                        </h5>
                                        {{-- </div> --}}
                                    @else
                                        <div>
                                            @can('module-check')
                                                <a href="{{ url('collectivemoduleformations', ['idformation' => $formation->id, 'idlocalite' => $formation->departement->region->id]) }}"
                                                    class="btn btn-sm btn-outline-primary rounded-pill d-flex align-items-center gap-1 shadow-sm float-end"
                                                    title="Ajouter un module collectif">
                                                    <i class="bi bi-plus-circle fs-6"></i>
                                                    <span class="d-none d-sm-inline">Ajouter module</span>
                                                </a>
                                            @endcan
                                        </div>
                                        <div class="alert alert-info mt-5">Aucun module pour le moment !</div>
                                    @endif

                                </div>
                            </div>

                            {{-- Détail ingenieur --}}
                            <div class="tab-content pt-0">
                                <div class="tab-pane fade ingenieur-overview pt-3" id="ingenieur-overview">
                                    @if (empty($ingenieur))
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h1 class="card-title">Ingénieur </h1>
                                            @can('ingenieur-check')
                                                <div class="pb-2">
                                                    <a href="{{ url('formationingenieurs', ['$idformation' => $formation->id]) }}"
                                                        class="btn btn-outline-success btn-sm rounded-pill d-flex align-items-center gap-1 float-end shadow-sm pt-1 px-3"
                                                        title="Ajouter un ingénieur">
                                                        <i class="bi bi-plus-circle-fill fs-6"></i>
                                                        <span class="d-none d-sm-inline">Ajouter ingénieur</span>
                                                    </a>
                                                </div>
                                            @endcan
                                        </div>
                                    @endif
                                    @if (!empty($ingenieur))
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h5 class="card-title">
                                                {{ $ingenieur?->name }}
                                                @can('ingenieur-check')
                                                    <div class="d-flex justify-content-between align-items-center gap-2 pb-2">
                                                        <a href="{{ route('ingenieurs.show', $ingenieur?->id) }}"
                                                            class="btn btn-outline-info btn-sm rounded-pill d-flex align-items-center gap-1 shadow-sm"
                                                            title="Voir l'ingénieur">
                                                            <i class="bi bi-eye-fill fs-6"></i>
                                                            <span class="d-none d-sm-inline">Voir</span>
                                                        </a>

                                                        <a href="{{ url('formationingenieurs', ['$idformation' => $formation->id]) }}"
                                                            class="btn btn-outline-primary btn-sm rounded-pill d-flex align-items-center gap-1 shadow-sm"
                                                            title="Changer l'ingénieur">
                                                            <i class="bi bi-pencil-fill fs-6"></i>
                                                            <span class="d-none d-sm-inline">Changer</span>
                                                        </a>
                                                    </div>
                                                @endcan
                                            </h5>
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <div>
                                                    <h6 class="text-muted mb-0">Agent de suivi</h6>
                                                    <h5 class="mb-0">
                                                        {{ $formation?->suivi_dossier ?? 'Aucun' }}&nbsp;&nbsp;
                                                    </h5>
                                                </div>

                                                @can('ingenieur-check')
                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-primary rounded-pill d-flex align-items-center gap-2 shadow-sm"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#EditAgentSuiviModal{{ $formation->id }}"
                                                        title="{{ $formation?->suivi_dossier ? 'Modifier' : 'Ajouter' }} l’agent de suivi">
                                                        {{-- <i class="bi bi-person-plus fs-5"></i> --}}
                                                        <span class="d-none d-sm-inline">
                                                            {{ $formation?->suivi_dossier ? 'Modifier' : 'Ajouter' }}
                                                        </span>
                                                    </button>
                                                @endcan
                                            </div>
                                        </div>
                                    @else
                                        <div class="alert alert-info">Aucun ingénieur pour le moment
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="tab-content pt-2">
                                <div class="tab-pane fade evaluation-overview pt-3" id="evaluation-overview">
                                    <div class="col-12 mb-0">
                                        <form method="post"
                                            action="{{ url('notedemandeurscollectives', ['$idformation' => $formation->id]) }}"
                                            enctype="multipart/form-data" class="row g-3">
                                            @csrf
                                            @method('PUT')
                                            @if (!empty($operateur))
                                                <div class="d-flex justify-content-between align-items-center">

                                                    <h5 class="mb-0 text-uppercase fw-bold text-primary">
                                                        <i class="bi bi-people-fill me-2"></i> Liste des bénéficiaires :
                                                        {{ $listecollectives->count() }}
                                                    </h5>
                                                    <h5
                                                        class="card-title d-flex align-items-center justify-content-between">
                                                        @can('jury-formation')
                                                            <span class="fw-bold text-dark">
                                                                <i class="bi bi-people-fill me-2 text-primary fs-5"></i>
                                                                Membres du jury&nbsp;&nbsp;
                                                            </span>
                                                            <button type="button"
                                                                class="btn btn-sm btn-outline-primary rounded-pill d-flex align-items-center gap-1 shadow-sm"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#EditMembresJuryModal{{ $formation->id }}"
                                                                title="Ajouter un membre du jury">
                                                                <i class="bi bi-plus-circle fs-6"></i>
                                                                <span class="d-none d-sm-inline">Ajouter</span>
                                                            </button>
                                                        @endcan
                                                    </h5>
                                                </div>
                                                <div class="row g-3">
                                                    <table class="table table-bordered table-hover datatables"
                                                        id="table-evaluation">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-center" width="2%">N°</th>
                                                                <th class="text-center">Civilité</th>
                                                                <th class="text-center" width="15">CIN</th>
                                                                <th class="text-center">Prénom</th>
                                                                <th class="text-center">NOM</th>
                                                                <th class="text-center">Date naissance</th>
                                                                <th class="text-center">Lieu de naissance</th>
                                                                <th class="text-center">Note<span
                                                                        class="text-danger mx-1">*</span></th>
                                                                <th class="text-center">Appréciation</th>
                                                                <th class="text-center">Observations</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php $i = 1; ?>
                                                            @foreach ($listecollectives as $listecollective)
                                                                <tr class="text-center">
                                                                    <td>{{ $i++ }}</td>
                                                                    <td>{{ $listecollective->civilite }}</td>
                                                                    <td>{{ $listecollective?->cin }}</td>
                                                                    <td>{{ $listecollective?->prenom }}</td>
                                                                    <td>{{ $listecollective?->nom }}</td>
                                                                    <td>{{ $listecollective?->date_naissance?->format('d/m/Y') }}
                                                                    </td>
                                                                    <td>{{ $listecollective?->lieu_naissance }}</td>
                                                                    <td width="10%" class="text-center">
                                                                        <input type="text"
                                                                            class="form-control note-input"
                                                                            value="{{ $listecollective?->note_obtenue }}"
                                                                            name="notes[]"
                                                                            placeholder="note (0-20 ou texte)"
                                                                            step="0.01" min="0" max="20">

                                                                        <input type="hidden" name="listecollectives[]"
                                                                            value="{{ $listecollective?->id }}">
                                                                    </td>

                                                                    {{-- Champ appréciation --}}
                                                                    <td width="10%" class="text-center">
                                                                        <input type="text"
                                                                            class="form-control appreciation-input"
                                                                            value="{{ $listecollective?->appreciation }}"
                                                                            name="appreciations[]"
                                                                            placeholder="appréciation">
                                                                    </td>
                                                                    <td
                                                                        style="text-align: center; vertical-align: middle;">
                                                                        @can('evaluation-formation')
                                                                            <button type="button"
                                                                                class="btn btn-outline-primary btn-sm"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#EditDemandeurModal{{ $listecollective->id }}">
                                                                                <i class="bi bi-plus"
                                                                                    title="Observations"></i>
                                                                            </button>

                                                                            <!-- Nouveau bouton : Ajouter les notes manuellement -->
                                                                            <button type="button"
                                                                                class="btn btn-outline-success btn-sm mx-1"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#AddNoteModal{{ $listecollective->id }}">
                                                                                <i class="bi bi-pencil-square"
                                                                                    title="Ajouter une note manuellement"></i>
                                                                            </button>
                                                                        @endcan
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                    </table>
                                                </div>
                                                @can('evaluation-formation')
                                                    <div class="text-center">
                                                        <button type="submit" class="btn btn-outline-primary btn-sm"><i
                                                                class="bi bi-check2-circle"></i>&nbsp;Evaluer</button>
                                                    </div>
                                                @endcan
                                            @endif
                                        </form>
                                    </div>
                                </div>
                            </div>

                            {{-- Emargement --}}
                            <div class="tab-content pt-2">
                                <div class="tab-pane fade module-overview" id="emargement-overview">
                                    <div class="col-12 mb-0">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h1 class="card-title">Feuilles de présence</h1>
                                            <div class="d-flex align-items-center gap-2">
                                                <!-- Bouton Ajouter une feuille de présence collective -->
                                                <button type="button"
                                                    class="btn btn-sm btn-outline-primary rounded-pill d-flex align-items-center gap-1 shadow-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#ajouterJoursCol{{ $formation->id }}"
                                                    title="Ajouter une feuille de présence">
                                                    <i class="bi bi-calendar-plus fs-5"></i>
                                                    <span class="d-none d-sm-inline">Créer feuille/fiche</span>
                                                </button>

                                                <!-- Dropdown menu -->
                                                <div class="dropdown">
                                                    <a href="#" class="btn btn-sm btn-light"
                                                        data-bs-toggle="dropdown" aria-expanded="false" title="Actions">
                                                        <i class="bi bi-three-dots-vertical"></i>
                                                    </a>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li>
                                                            <form action="{{ route('feuillePresenceColFinale') }}"
                                                                method="POST" target="_blank" class="px-3">
                                                                @csrf
                                                                <input type="hidden" name="idformation"
                                                                    value="{{ $formation->id }}">
                                                                <input type="hidden" name="idmodule"
                                                                    value="{{ $formation?->collectivemodule?->id }}">
                                                                <input type="hidden" name="idlocalite"
                                                                    value="{{ $formation?->departement?->region?->id }}">
                                                                <button type="submit" class="dropdown-item">
                                                                    <i class="bi bi-journal-text me-1"></i> Feuille de
                                                                    présence
                                                                </button>
                                                            </form>
                                                        </li>
                                                        <li>
                                                            <form action="{{ route('etatTransportCol') }}"
                                                                method="POST" target="_blank" class="px-3">
                                                                @csrf
                                                                <input type="hidden" name="idformation"
                                                                    value="{{ $formation->id }}">
                                                                <input type="hidden" name="idmodule"
                                                                    value="{{ $formation?->collectivemodule?->id }}">
                                                                <input type="hidden" name="idlocalite"
                                                                    value="{{ $formation?->departement?->region?->id }}">
                                                                <button type="submit" class="dropdown-item">
                                                                    <i class="bi bi-truck me-1"></i> État transport
                                                                </button>
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row g-3">
                                            <table class="table table-bordered table-hover datatables"
                                                id="table-evaluation">
                                                <thead>
                                                    <tr class="text-center">
                                                        <th width="5%">N°</th>
                                                        <th width="10%">Jours</th>
                                                        <th width="10%">Date</th>
                                                        <th width="10%">Effectif</th>
                                                        <th width="10%">SCAN</th>
                                                        <th>Observations</th>
                                                        <th width="3%"><i class="bi bi-gear"></i>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $i = 1; ?>
                                                    @foreach ($emargementcollectives as $emargementcollective)
                                                        <tr valign="middle" class="text-center">
                                                            <td>{{ $i++ }}</td>
                                                            <td>{{ $emargementcollective?->jour }}
                                                            </td>
                                                            <td>
                                                                {{ $emargementcollective?->date?->format('d/m/Y') }}</td>
                                                            <td>
                                                                {{-- {{ count($emargementcollective?->formation?->listecollectives) }} --}}
                                                                {{ count($emargementcollective?->feuillesPresenceCollectives) }}
                                                            </td>
                                                            <td>
                                                                @if (!empty($emargementcollective?->file))
                                                                    <div>
                                                                        <a class="btn btn-outline-secondary btn-sm"
                                                                            title="Feuille émargement" target="_blank"
                                                                            href="{{ asset($emargementcollective->getFileEmargement()) }}">
                                                                            <i class="bi bi-file-earmark-pdf"></i>
                                                                        </a>
                                                                    </div>
                                                                @else
                                                                    <div class="badge bg-warning">Aucun</div>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @php
                                                                    $obs = trim(
                                                                        $emargementcollective?->observations ?? '',
                                                                    );
                                                                    $words = $obs ? explode(' ', $obs) : [];
                                                                    $preview =
                                                                        count($words) > 10
                                                                            ? implode(' ', array_slice($words, 0, 10)) .
                                                                                '...'
                                                                            : $obs;
                                                                @endphp

                                                                @if ($obs)
                                                                    <span>{{ $preview }}</span>

                                                                    @if (count($words) > 10)
                                                                        <!-- Bouton pour ouvrir le modal -->
                                                                        <button type="button"
                                                                            class="btn btn-link btn-sm p-0 ms-2"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#observationsModal{{ $emargementcollective->id }}">
                                                                            Voir plus
                                                                        </button>

                                                                        <!-- Modal Bootstrap -->
                                                                        <div class="modal fade"
                                                                            id="observationsModal{{ $emargementcollective->id }}"
                                                                            tabindex="-1"
                                                                            aria-labelledby="observationsModalLabel{{ $emargementcollective->id }}"
                                                                            aria-hidden="true">
                                                                            <div
                                                                                class="modal-dialog modal-dialog-centered">
                                                                                <div class="modal-content">
                                                                                    <div class="modal-header">
                                                                                        <h5 class="modal-title"
                                                                                            id="observationsModalLabel{{ $emargementcollective->id }}">
                                                                                            Observations complètes
                                                                                        </h5>
                                                                                        <button type="button"
                                                                                            class="btn-close"
                                                                                            data-bs-dismiss="modal"
                                                                                            aria-label="Fermer"></button>
                                                                                    </div>
                                                                                    <div class="modal-body">
                                                                                        {!! nl2br(e($obs)) !!}
                                                                                    </div>
                                                                                    <div class="modal-footer">
                                                                                        <button type="button"
                                                                                            class="btn btn-secondary btn-sm"
                                                                                            data-bs-dismiss="modal">Fermer</button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <div class="d-flex align-items-center gap-2 mt-2">
                                                                    <!-- Bouton Voir feuille d’émargement collective -->
                                                                    <form
                                                                        action="{{ route('formation.emargementcollective.form', [
                                                                            'idformation' => $formation->id,
                                                                            'idmodule' => $formation->collectivemodule->id,
                                                                            'idlocalite' => $formation->departement->id,
                                                                        ]) }}"
                                                                        method="GET">
                                                                        @csrf
                                                                        <input type="hidden" name="idformation"
                                                                            value="{{ $formation?->id }}">
                                                                        <input type="hidden" name="idmodule"
                                                                            value="{{ $formation?->collectivemodule?->id }}">
                                                                        <input type="hidden" name="idlocalite"
                                                                            value="{{ $formation?->departement?->region?->id }}">
                                                                        <input type="hidden" name="idemargement"
                                                                            value="{{ $emargementcollective?->id }}">
                                                                        <button type="submit"
                                                                            class="btn btn-outline-primary btn-sm"
                                                                            title="Voir la feuille de présence">
                                                                            <i class="bi bi-eye"></i>
                                                                        </button>
                                                                    </form>

                                                                    <!-- Menu déroulant -->
                                                                    <div class="dropdown">
                                                                        <a href="#" class="btn btn-sm btn-light"
                                                                            data-bs-toggle="dropdown"
                                                                            aria-expanded="false" title="Actions">
                                                                            <i class="bi bi-three-dots-vertical"></i>
                                                                        </a>
                                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                                            <!-- Modifier -->
                                                                            <li>
                                                                                <button type="button"
                                                                                    class="dropdown-item"
                                                                                    data-bs-toggle="modal"
                                                                                    data-bs-target="#EditEmargementModal{{ $emargementcollective->id }}">
                                                                                    <i class="bi bi-pencil me-1"></i>
                                                                                    Modifier
                                                                                </button>
                                                                            </li>

                                                                            <!-- Supprimer -->
                                                                            <li>
                                                                                <form
                                                                                    action="{{ route('emargementcollectives.destroy', $emargementcollective->id) }}"
                                                                                    method="POST" class="px-3">
                                                                                    @csrf
                                                                                    @method('DELETE')
                                                                                    <button type="submit"
                                                                                        class="dropdown-item text-danger show_confirm">
                                                                                        <i class="bi bi-trash me-1"></i>
                                                                                        Supprimer
                                                                                    </button>
                                                                                </form>
                                                                            </li>
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

                            {{-- Retrait attestation --}}
                            <div class="tab-content pt-2">
                                <div class="tab-pane fade attestation-overview pt-1" id="retrait-attestation-overview">
                                    <div class="col-12 mb-0">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h1 class="card-title">Retrait des attestations</h1>

                                            <!-- Bouton Télécharger PDF -->
                                            <span>
                                                <a href="{{ route('exporter-liste-admis-col.pdf', $formation->id) }}"
                                                    class="btn btn-sm btn-outline-primary rounded-pill d-flex align-items-center gap-1 shadow-sm mx-2"
                                                    title="Télécharger la liste en PDF" target="_blank">
                                                    <i class="bi bi-file-earmark-pdf fs-6"></i>
                                                    <span class="d-none d-sm-inline">Liste Admis</span>
                                                </a>
                                            </span>

                                            <h5 class="card-title d-flex align-items-center justify-content-between">
                                                @can('attestation-formation')
                                                    <span class="fw-bold text-dark">
                                                        <i class="bi bi-mortarboard-fill me-2 text-success fs-5"></i>
                                                        Attestations&nbsp;&nbsp;
                                                    </span>
                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-warning rounded-pill d-flex align-items-center gap-1 shadow-sm"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#EditRemiseAttestationsModal{{ $formation->id }}"
                                                        title="Informer les bénéficiaires">
                                                        <i class="bi bi-plus-circle fs-6"></i>
                                                        <span class="d-none d-sm-inline">Statut</span>
                                                    </button>
                                                @endcan
                                            </h5>
                                        </div>
                                        <div class="row g-3">
                                            <table class="table table-bordered table-hover datatables"
                                                id="table-evaluation">
                                                <thead>
                                                    <tr class="text-center">
                                                        <th>N°</th>
                                                        <th>Civilité</th>
                                                        <th>Prénom</th>
                                                        <th>NOM</th>
                                                        <th>Date naissance</th>
                                                        <th>Lieu de naissance</th>
                                                        <th>Note<span class="text-danger mx-1">*</span></th>
                                                        <th>Diplôme</th>
                                                        <th><i class="bi bi-gear"></i></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $i = 1; ?>
                                                    @foreach ($listecollectives as $listecollective)
                                                        <tr class="text-center">
                                                            <td>{{ $i++ }}</td>
                                                            <td>{{ $listecollective?->civilite }}</td>
                                                            <td>{{ $listecollective?->prenom }}</td>
                                                            <td>{{ $listecollective?->nom }}</td>
                                                            <td>{{ $listecollective?->date_naissance?->format('d/m/Y') }}
                                                            </td>
                                                            <td>{{ $listecollective?->lieu_naissance }}</td>
                                                            <td>
                                                                <span>{{ $listecollective?->note_obtenue }}</span>
                                                            </td>
                                                            <td style="vertical-align: middle;">
                                                                @if (!empty($listecollective?->retrait_diplome))
                                                                    <a href="#" data-bs-toggle="modal"
                                                                        data-bs-target="#EditShowModal{{ $listecollective?->id }}"><i
                                                                            class="bi bi-check-circle text-success"
                                                                            title="diplome retiré"></i></a>
                                                                @else
                                                                    <i class="bi bi-x text-danger"
                                                                        title="diplome non retiré"></i>
                                                                @endif
                                                            </td>
                                                            <td style="text-align: center; vertical-align: middle;">
                                                                @can('attestation-formation')
                                                                    <button type="button"
                                                                        class="btn btn-outline-primary btn-sm"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#EditAttestationsModal{{ $listecollective->id }}">
                                                                        <i class="bi bi-plus" title="Attestation"></i>
                                                                    </button>
                                                                @endcan
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
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="EditRemiseAttestationsModal{{ $formation->id }}" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form method="post" action="{{ url('remiseAttestations', ['$idformation' => $formation->id]) }}"
                        enctype="multipart/form-data" class="row">
                        @csrf
                        @method('PUT')
                        <div class="card-header text-center bg-gradient-default">
                            <h1 class="h4 text-black mb-0">SATATUT ATTESTATIONS</h1>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="formationid" value="{{ $formation->id }}">
                            <label for="region" class="form-label">Statut attestations<span
                                    class="text-danger mx-1">*</span></label>
                            <select name="statut"
                                class="form-select form-select-sm @error('statut') is-invalid @enderror"
                                aria-label="Select" id="select-field-statut-attestations"
                                data-placeholder="Choisir statut attestations">
                                <option value="{{ $formation?->attestation ?? old('statut') }}">
                                    {{ $formation?->attestation ?? old('statut') }}
                                </option>
                                <option value="En cours">
                                    En cours
                                </option>
                                <option value="disponible">
                                    disponible
                                </option>
                                <option value="retiré">
                                    retiré
                                </option>
                            </select>
                            @error('statut')
                                <span class="invalid-feedback" role="alert">
                                    <div>{{ $message }}</div>
                                </span>
                            @enderror
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-sm"
                                data-bs-dismiss="modal">Fermer</button>
                            <button type="submit" class="btn btn-primary btn-sm"><i
                                    class="bi bi-arrow-right-circle"></i>
                                Valider</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="SuspendreDemandeModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="post" action="{{ route('validation-formations.destroy', $formation->id) }}"
                        enctype="multipart/form-data" class="row">
                        @csrf
                        @method('DELETE')
                        <div class="card-header text-center bg-gradient-default">
                            <h1 class="h4 text-black mb-0">Suspendre demande</h1>
                        </div>

                        <input type="hidden" name="arretete_formation" value="Suspendue">

                        <div class="modal-body">
                            <!-- Sélecteur du statut -->
                            <div class="mb-3">
                                <label for="statut" class="form-label">Statut<span
                                        class="text-danger mx-1">*</span></label>
                                <select name="statut" id="statut"
                                    class="form-select form-select-sm @error('statut') is-invalid @enderror">
                                    <option value="">Sélectionner</option>
                                    <option value="En cours" {{ old('statut') == 'En cours' ? 'selected' : '' }}>En
                                        cours</option>
                                    <option value="Suspendue" {{ old('statut') == 'Suspendue' ? 'selected' : '' }}>
                                        Suspendre</option>
                                    <option value="Annulée" {{ old('statut') == 'Annulée' ? 'selected' : '' }}>Annulée
                                    </option>
                                    <option value="Nouvelle" {{ old('statut') == 'Nouvelle' ? 'selected' : '' }}>Nouvelle
                                    </option>
                                </select>
                                @error('statut')
                                    <span class="invalid-feedback" role="alert">
                                        <div>{{ $message }}</div>
                                    </span>
                                @enderror
                            </div>

                            <!-- Champ des motifs -->
                            <label for="motif" class="form-label">Motifs<span
                                    class="text-danger mx-1">*</span></label>
                            <textarea name="motif" id="motif" rows="5"
                                class="form-control form-control-sm @error('motif') is-invalid @enderror" placeholder="Motifs">{{ old('motif') }}</textarea>
                            @error('motif')
                                <span class="invalid-feedback" role="alert">
                                    <div>{{ $message }}</div>
                                </span>
                            @enderror
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-sm"
                                data-bs-dismiss="modal">Fermer</button>
                            <button type="submit" class="btn btn-danger btn-sm">Valider</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        {{-- Observations --}}
        @foreach ($listecollectives as $listecollective)
            <div class="modal fade" id="EditDemandeurModal{{ $listecollective->id }}" tabindex="-1" role="dialog"
                aria-labelledby="EditDemandeurModalLabel{{ $listecollective->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form method="post" action="{{ route('listecollectives.updateObservationsCollective') }}"
                            enctype="multipart/form-data" class="row g-3">
                            @csrf
                            @method('patch')
                            <div class="modal-header" id="EditDemandeurModalLabel{{ $listecollective->id }}">
                                <h5 class="modal-title">Ajouter un commentaire ou une observation</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="id" value="{{ $listecollective->id }}">
                                <label for="floatingInput" class="mb-3">Observation<span
                                        class="text-danger mx-1">*</span></label>
                                <textarea name="observations" id="observations" cols="30" rows="5"
                                    class="form-control form-control-sm @error('observations') is-invalid @enderror" placeholder="Observations"
                                    autofocus>{{ $listecollective->observations ?? old('observations') }}</textarea>
                                @error('observations')
                                    <span class="invalid-feedback" role="alert">
                                        <div>{{ $message }}</div>
                                    </span>
                                @enderror
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary btn-sm"
                                    data-bs-dismiss="modal">Fermer</button>
                                <button type="submit" class="btn btn-primary btn-sm"></i>
                                    Modifier</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach

        @foreach ($listecollectives as $listecollective)
            <div class="modal fade" id="AddNoteModal{{ $listecollective->id }}" tabindex="-1"
                aria-labelledby="AddNoteLabel{{ $listecollective->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('noteformationcollectivestore', $listecollective->id) }}" method="POST">
                            @csrf
                            <div class="modal-header bg-defautlt">
                                <h5 class="modal-title" id="AddNoteLabel{{ $listecollective->id }}">
                                    {{ $listecollective->civilite . ' ' . $listecollective->prenom . ' ' . $listecollective->nom }}
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Fermer"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="note" class="form-label">Note obtenue<span
                                            class="text-danger">*</span></label>
                                    <input type="number" step="0.01" min="0" max="20" name="note"
                                        id="note" class="form-control form-control-sm" required placeholder="0"
                                        value="{{ old('note', $listecollective->note_obtenue) }}">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary btn-sm"
                                    data-bs-dismiss="modal">Fermer</button>
                                <button type="submit" class="btn btn-success btn-sm">Enregistrer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
        {{-- Agent de suivi --}}
        <div class="modal fade" id="EditAgentSuiviModal{{ $formation->id }}" tabindex="-1" role="dialog"
            aria-labelledby="EditAgentSuiviModalLabel{{ $formation->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="post" action="{{ route('formations.updateAgentSuivi') }}"
                        enctype="multipart/form-data" class="row g-3">
                        @csrf
                        @method('patch')

                        <div class="card-header text-center bg-gradient-default">
                            <h1 class="h4 text-black mb-0">Ajouter un agent de suivi</h1>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="id" value="{{ $formation->id }}">
                            <div class="form-floating mb-3">
                                <input type="text" name="suivi_dossier"
                                    value="{{ $formation?->suivi_dossier ?? old('suivi_dossier') }}"
                                    class="form-control form-control-sm @error('suivi_dossier') is-invalid @enderror"
                                    id="suivi_dossier" placeholder="Nom de l'agent de suivi" autofocus>
                                @error('suivi_dossier')
                                    <span class="invalid-feedback" role="alert">
                                        <div>{{ $message }}</div>
                                    </span>
                                @enderror
                                <label for="floatingInput">Agent suivi</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="date" name="date_suivi"
                                    value="{{ $formation?->date_suivi?->format('Y-m-d') ?? old('date_suivi') }}"
                                    class="datepicker form-control form-control-sm @error('date_suivi') is-invalid @enderror"
                                    id="date_suivi" placeholder="jj/mm/aaaa">
                                @error('date_suivi')
                                    <span class="invalid-feedback" role="alert">
                                        <div>{{ $message }}</div>
                                    </span>
                                @enderror
                                <label for="floatingInput">Date suivi</label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-sm"
                                data-bs-dismiss="modal">Fermer</button>
                            <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-printer"></i>
                                Valider</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Jours formation --}}
        <div class="modal fade" id="ajouterJoursCol{{ $formation->id }}" tabindex="-1" role="dialog"
            aria-labelledby="ajouterJoursColLabel{{ $formation->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="post" action="{{ route('formations.ajouterJoursCol') }}"
                        enctype="multipart/form-data" class="row g-3">
                        @csrf
                        @method('patch')
                        <div class="card-header text-center bg-gradient-default">
                            <h1 class="h4 text-black mb-0">AJOUTER JOUR</h1>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="idformation" value="{{ $formation->id }}">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label>Nombre de jours<span class="text-danger mx-1">*</span></label>
                                    <input type="number" min="1" max="1" name="jour"
                                        value="{{ '1' ?? old('jour') }}"
                                        class="form-control form-control-sm @error('jour') is-invalid @enderror"
                                        id="jour" placeholder="Nombre de jour">
                                    @error('jour')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-sm"
                                data-bs-dismiss="modal">Fermer</button>
                            <button type="submit" class="btn btn-primary btn-sm">Ajouter</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @foreach ($emargementcollectives as $emargementcol)
            <div class="modal fade" id="EditEmargementModal{{ $emargementcol->id }}" tabindex="-1"
                aria-labelledby="EditEmargementModalLabel{{ $emargementcol->id }}" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content shadow rounded-3">
                        <form method="POST" action="{{ route('emargementcollectives.update', $emargementcol->id) }}"
                            enctype="multipart/form-data" class="row g-3 needs-validation" novalidate>
                            @csrf
                            @method('PATCH')

                            <div class="modal-header bg-default rounded-top">
                                <h5 class="modal-title w-100 text-center"
                                    id="EditEmargementModalLabel{{ $emargementcol->id }}">
                                    Modification du {{ $emargementcol?->jour }}
                                </h5>
                            </div>

                            <div class="modal-body">
                                <input type="hidden" name="idformation" value="{{ $formation->id }}">

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="jour" class="form-label">Jour <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="jour"
                                                value="{{ $emargementcol?->jour ?? old('jour') }}"
                                                class="form-control form-control-sm @error('jour') is-invalid @enderror"
                                                id="jour" placeholder="Nombre de jour" required>
                                            @error('jour')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="date" class="form-label">Date <span
                                                    class="text-danger">*</span></label>
                                            <input type="date" name="date"
                                                value="{{ $emargementcol?->date?->format('Y-m-d') ?? old('date') }}"
                                                class="form-control form-control-sm @error('date') is-invalid @enderror"
                                                id="date" required>
                                            @error('date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label for="feuille" class="form-label">Joindre scan feuille de présence
                                                {{ $emargementcol?->jour }}</label>
                                            <input type="file" name="feuille" id="feuille"
                                                class="form-control form-control-sm @error('feuille') is-invalid @enderror btn btn-outline-secondary btn-sm">
                                            @error('feuille')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label for="observations" class="form-label">Observations</label>
                                            <textarea name="observations" id="observations" cols="30" rows="10"
                                                class="form-control form-control-sm @error('observations') is-invalid @enderror" placeholder="Observations">{{ $emargementcol?->observations ?? old('observations') }}</textarea>
                                            @error('observations')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                                    <i class="bi bi-x-circle"></i> Fermer
                                </button>
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="bi bi-pencil-square"></i> Modifier
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
        <div class="modal fade" id="EditMembresJuryModal{{ $formation->id }}" tabindex="-1" role="dialog"
            aria-labelledby="EditMembresJuryModalLabel{{ $formation->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content shadow-sm rounded-4 border-0">
                    <form method="post" action="{{ route('formations.updateMembresJury') }}"
                        enctype="multipart/form-data">
                        @csrf
                        @method('patch')

                        <div class="modal-header bg-warning text-white">
                            <h5 class="modal-title" id="EditMembresJuryModalLabel{{ $formation->id }}">
                                <i class="bi bi-pencil-square me-1"></i> Évaluation & Jury
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Fermer"></button>
                        </div>

                        <div class="modal-body">
                            <input type="hidden" name="id" value="{{ $formation->id }}">

                            <div class="row g-3">

                                {{-- N° Convention --}}
                                <div class="col-md-6">
                                    <label class="form-label">N° convention <span class="text-danger">*</span></label>
                                    <input type="text" name="numero_convention"
                                        value="{{ old('numero_convention', $formation->numero_convention) }}"
                                        class="form-control form-control-sm @error('numero_convention') is-invalid @enderror"
                                        placeholder="N° convention">
                                    @error('numero_convention')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Date Convention --}}
                                <div class="col-md-6">
                                    <label class="form-label">Date convention <span class="text-danger">*</span></label>
                                    <input type="date" name="date_convention"
                                        value="{{ old('date_convention', $formation?->date_convention?->format('Y-m-d')) }}"
                                        class="form-control form-control-sm @error('date_convention') is-invalid @enderror">
                                    @error('date_convention')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Date évaluation --}}
                                <div class="col-md-6">
                                    <label class="form-label">Date évaluation <span class="text-danger">*</span></label>
                                    <input type="date" name="date_pv"
                                        value="{{ old('date_pv', $formation?->date_pv?->format('Y-m-d')) }}"
                                        class="form-control form-control-sm @error('date_pv') is-invalid @enderror">
                                    @error('date_pv')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Montant indemnité --}}
                                <div class="col-md-6">
                                    <label class="form-label">Montant indemnité de membre <span
                                            class="text-danger">*</span></label>
                                    <input type="number" name="frais_evaluateur" min="0" step="0.001"
                                        value="{{ old('frais_evaluateur', $formation->frais_evaluateur) }}"
                                        class="form-control form-control-sm @error('frais_evaluateur') is-invalid @enderror"
                                        placeholder="Montant indemnité">
                                    @error('frais_evaluateur')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Évaluateurs ONFP --}}
                                <div class="col-md-12">
                                    <label class="form-label">Évaluateurs ONFP <span
                                            class="text-danger">*</span></label>
                                    <select name="onfpevaluateur[]" id="onfpevaluateurSelected"
                                        class="form-select form-select-sm @error('onfpevaluateur') is-invalid @enderror"
                                        multiple>
                                        @foreach ($onfpevaluateurs as $onfpevaluateur)
                                            <option value="{{ $onfpevaluateur->id }}"
                                                @if (collect(old('onfpevaluateur', $formation?->onfpevaluateurs?->pluck('id')->toArray()))->contains(
                                                        $onfpevaluateur->id)) selected @endif>
                                                {{ $onfpevaluateur->name . ' ' . $onfpevaluateur->lastname }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('onfpevaluateur')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Niveau qualification --}}
                                <div class="col-md-6">
                                    <label class="form-label">Niveau de qualification <span
                                            class="text-danger">*</span></label>
                                    <select name="titre"
                                        class="form-select form-select-sm @error('titre') is-invalid @enderror">
                                        <option value="{{ $formation?->titre }}">
                                            {{ $formation?->titre ?? ($formation?->referentiel?->titre ?? 'Choisir') }}
                                        </option>
                                        <option value="">Aucun</option>
                                        @foreach ($referentiels as $referentiel)
                                            <option value="{{ $referentiel->titre }}">{{ $referentiel->titre }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('titre')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Type certification --}}
                                <div class="col-md-6">
                                    <label class="form-label">Type de certification <span
                                            class="text-danger">*</span></label>
                                    <select name="type_certification"
                                        class="form-select form-select-sm @error('type_certification') is-invalid @enderror">
                                        <option value="{{ $formation->type_certification }}">
                                            {{ $formation->type_certification ?? 'Choisir' }}
                                        </option>
                                        <option value="Titre">Titre</option>
                                        <option value="Attestation">Attestation</option>
                                    </select>
                                    @error('type_certification')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Autres membres du jury --}}
                                <div class="col-md-12">
                                    <label class="form-label">Autres membres du jury</label>
                                    <textarea name="membres_jury" rows="2"
                                        class="form-control form-control-sm @error('membres_jury') is-invalid @enderror"
                                        placeholder="Ex : Membre 1 ; Membre 2 ; Membre 3">{{ old('membres_jury', $formation->membres_jury) }}</textarea>
                                    @error('membres_jury')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Recommandations --}}
                                <div class="col-md-12">
                                    <label class="form-label">Recommandations</label>
                                    <textarea name="recommandations" rows="2"
                                        class="form-control form-control-sm @error('recommandations') is-invalid @enderror"
                                        placeholder="Recommandations">{{ old('recommandations', $formation->recommandations) }}</textarea>
                                    @error('recommandations')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>
                        </div>

                        <div class="modal-footer border-top">
                            <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">
                                <i class="bi bi-x-circle me-1"></i> Fermer
                            </button>
                            <button type="submit" class="btn btn-warning btn-sm">
                                <i class="bi bi-check-circle me-1"></i> Valider
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @foreach ($listecollectives as $listecollective)
            <div class="modal fade" id="indiponibleModal{{ $listecollective->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form method="post"
                            action="{{ url('collectiveindisponibles', ['$idformation' => $formation->id]) }}"
                            enctype="multipart/form-data" class="row">
                            @csrf
                            @method('PUT')

                            <div class="card-header bg-gradient-default">
                                <h3 class="h4 text-black mb-0">Retirer
                                    {{ $listecollective?->civilite . ' ' . $listecollective?->prenom . ' ' . $listecollective?->nom }}
                                </h3>
                            </div>

                            <div class="modal-body">
                                <input type="hidden" name="listecollectiveid" value="{{ $listecollective->id }}">
                                <label for="motif" class="form-label">Justification du retrait<span
                                        class="text-danger mx-1">*</span></label>
                                <textarea name="motif" id="motif" rows="5"
                                    class="form-control form-control-sm @error('motif') is-invalid @enderror"
                                    placeholder="Expliquer les raisons du retrait de ce bénéficiaire">{{ $listecollective?->motif_rejet ?? old('motif') }}</textarea>
                                @error('motif')
                                    <span class="invalid-feedback" role="alert">
                                        <div>{{ $message }}</div>
                                    </span>
                                @enderror
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary btn-sm"
                                    data-bs-dismiss="modal">Fermer</button>
                                <button type="submit" class="btn btn-danger btn-sm"><i
                                        class="bi bi-arrow-right-circle"></i>
                                    Retirer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach

        {{-- Attestations retrait --}}
        @foreach ($formation?->listecollectives as $listecollective)
            <div class="modal fade" id="EditShowModal{{ $listecollective->id }}" tabindex="-1" role="dialog"
                aria-labelledby="EditShowModalLabel{{ $listecollective->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <div class="card-header text-center bg-gradient-default">
                            <h4 class="h4 text-black mb-0">
                                {{ strtoupper($formation?->type_certification) . ' de ' . $listecollective->civilite . ' ' . $listecollective->prenom . ' ' . $listecollective->nom }}
                            </h4>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">
                                <input type="hidden" name="id" value="{{ $listecollective->id }}">
                                <div class="col-12">
                                    <div class="row g-3">
                                        <label for="retrait" class="form-label">Informations !<span
                                                class="text-danger mx-1">*</span></label>
                                        <div class="col-12">
                                            <label class="form-check-label" for="moi">
                                                {{ 'Retrait effectué par ' . $listecollective?->retrait_diplome }}
                                            </label>

                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        {{-- Attestations --}}
        @foreach ($listecollectives as $listecollective)
            <div class="modal fade" id="EditAttestationsModal{{ $listecollective->id }}" tabindex="-1"
                role="dialog" aria-labelledby="EditAttestationsModalLabel{{ $listecollective->id }}"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form method="post" action="{{ route('individuelles.updateAttestationsCol') }}"
                            enctype="multipart/form-data" class="row g-3">
                            @csrf
                            @method('patch')
                            <div class="card-header text-center bg-gradient-default">
                                <h4 class="h4 text-black mb-0">
                                    {{ 'RETRAIT ' . strtoupper($formation?->type_certification) }}
                                </h4>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="id" value="{{ $listecollective->id }}">

                                <div class="mb-2">
                                    <strong>Bénéficiaire :</strong>
                                    {{ $listecollective->civilite . ' ' . $listecollective->prenom . ' ' . $listecollective->nom }}
                                </div>

                                <hr>

                                <div class="mb-3">
                                    <label class="form-label">Qui va retirer le diplôme ?<span
                                            class="text-danger mx-1">*</span></label>
                                    <div class="form-check">
                                        <input class="form-check-input personne-radio" type="radio" name="personne"
                                            id="personne_moi_{{ $listecollective->id }}" value="moi"
                                            {{ old('personne') == 'moi' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="personne_moi_{{ $listecollective->id }}">
                                            Le propriétaire
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input personne-radio" type="radio" name="personne"
                                            id="personne_autre_{{ $listecollective->id }}" value="autre"
                                            {{ old('personne') == 'autre' ? 'checked' : '' }}>
                                        <label class="form-check-label"
                                            for="personne_autre_{{ $listecollective->id }}">
                                            Une autre personne
                                        </label>
                                    </div>
                                    @error('personne')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="date_retrait" class="form-label">Date de retrait <span
                                            class="text-danger mx-1">*</span></label>
                                    <input type="date" name="date_retrait"
                                        value="{{ old('date_retrait', date('Y-m-d')) }}"
                                        class="form-control form-control-sm @error('date_retrait') is-invalid @enderror"
                                        id="date_retrait_{{ $listecollective->id }}">
                                    @error('date_retrait')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div id="autre-personne-fields-{{ $listecollective->id }}"
                                    style="{{ old('personne') === 'autre' ? '' : 'display: none;' }}">
                                    <div class="mb-3">
                                        <label for="cin" class="form-label">N° CIN</label>
                                        <input type="text" name="cin" minlength="13" maxlength="14"
                                            value="{{ old('cin') }}"
                                            class="form-control form-control-sm @error('cin') is-invalid @enderror"
                                            placeholder="Numéro carte d'identité nationale">
                                        @error('cin')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="name" class="form-label">Nom du tiers</label>
                                        <input type="text" name="name" value="{{ old('name') }}"
                                            class="form-control form-control-sm @error('name') is-invalid @enderror"
                                            placeholder="Prénom et NOM">
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="commentaires" class="form-label">Commentaires</label>
                                    <input type="text" name="commentaires" maxlength="150"
                                        value="{{ old('commentaires') }}"
                                        class="form-control form-control-sm @error('commentaires') is-invalid @enderror"
                                        placeholder="Un petit commentaire...">
                                    @error('commentaires')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary btn-sm"
                                    data-bs-dismiss="modal">Fermer</button>
                                <button type="submit" class="btn btn-primary btn-sm">Valider</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </section>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.personne-radio').forEach(function(radio) {
                radio.addEventListener('change', function() {
                    const modalContent = radio.closest('.modal-content');
                    const id = modalContent.closest('.modal').id.replace('EditAttestationsModal',
                        '');
                    const autreFields = modalContent.querySelector('#autre-personne-fields-' + id);

                    if (radio.value === 'autre') {
                        autreFields.style.display = 'block';
                    } else {
                        autreFields.style.display = 'none';
                    }
                });
            });
        });

        new DataTable('#table-operateurModules', {
            paging: false, // Supprime la pagination
            info: false, // Supprime les infos en bas (Affichage de l'élément X à Y...)
            layout: {
                topStart: {
                    buttons: ['csv', 'excel'],
                }
            },
            "order": [
                [0, 'asc']
            ],
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
    </script>
    {{-- Script de gestion dynamique des champs appréciation --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const noteInputs = document.querySelectorAll('.note-input');

            noteInputs.forEach(noteInput => {
                const appreciationInput = noteInput.closest('tr').querySelector('.appreciation-input');

                function toggleAppreciation() {
                    const value = noteInput.value.trim();

                    // Si c’est une note numérique valide entre 0 et 20 → cacher appréciation
                    if (value !== '' && !isNaN(value) && value >= 0 && value <= 20) {
                        appreciationInput.style.display = 'none';
                        appreciationInput.value = ''; // efface pour ne pas envoyer de valeur inutile
                    } else {
                        appreciationInput.style.display = ''; // sinon on l'affiche
                    }
                }

                // Initialisation et écoute des changements
                toggleAppreciation();
                noteInput.addEventListener('input', toggleAppreciation);
            });
        });
    </script>
@endpush
