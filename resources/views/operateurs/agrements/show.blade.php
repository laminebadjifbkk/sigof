@extends('layout.user-layout')
@section('title', 'AGREMENT | ' . $operateur?->user?->display_operateur)
@section('space-work')
    <section
        class="section profile min-vh-0 d-flex flex-column align-items-center justify-content-center py-0 section profile">
        <div class="container-fluid">
            <div class="pagetitle">
                {{-- <h1>Data Tables</h1> --}}
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Accueil</a></li>
                        <li class="breadcrumb-item">Tables</li>
                        <li class="breadcrumb-item active">{{ $operateur?->user?->display_operateur }}</li>
                    </ol>
                </nav>
            </div><!-- End Page Title -->
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
                            <ul class="nav nav-tabs nav-tabs-bordered align-items-center gap-2 flex-wrap position-relative">

                                <li class="nav-item">
                                    <a href="{{ route('agrement') }}"
                                        class="btn btn-light btn-sm d-flex align-items-center gap-1" title="Retour">
                                        <i class="bi bi-arrow-left"></i>
                                        <span>Retour</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link" data-bs-toggle="tab"
                                        data-bs-target="#profile-overview">Opérateur</button>
                                </li>

                                <li class="nav-item">
                                    <button class="nav-link active position-relative" data-bs-toggle="tab"
                                        data-bs-target="#module-overview">
                                        Modules

                                        @if ($operateur->operateurmodules_count > 0)
                                            <span
                                                class="badge bg-primary position-absolute top-0 start-100 translate-middle p-1 rounded-circle">
                                                {{ $operateur->operateurmodules_count }}
                                            </span>
                                        @endif
                                    </button>
                                </li>

                                <li class="nav-item">
                                    <button class="nav-link position-relative" data-bs-toggle="tab"
                                        data-bs-target="#references-overview">Références
                                        @if ($operateur->operateureferences_count > 0)
                                            <span
                                                class="badge bg-secondary position-absolute top-0 end-0 translate-middle p-1 rounded-circle">
                                                {{ $operateur->operateureferences_count }}
                                            </span>
                                        @endif
                                    </button>
                                </li>

                                <li class="nav-item">
                                    <button class="nav-link position-relative" data-bs-toggle="tab"
                                        data-bs-target="#equipement-overview">Equipements
                                        @if ($operateur->operateurequipements_count > 0)
                                            <span
                                                class="badge bg-warning text-dark position-absolute top-0 end-0 translate-middle p-1 rounded-circle">
                                                {{ $operateur->operateurequipements_count }}
                                            </span>
                                        @endif
                                    </button>
                                </li>

                                <li class="nav-item">
                                    <button class="nav-link position-relative" data-bs-toggle="tab"
                                        data-bs-target="#formateur-overview">Formateurs
                                        @if ($operateur->operateurformateurs_count > 0)
                                            <span
                                                class="badge bg-info text-dark position-absolute top-0 end-0 translate-middle p-1 rounded-circle">
                                                {{ $operateur->operateurformateurs_count }}
                                            </span>
                                        @endif
                                    </button>
                                </li>

                                <li class="nav-item">
                                    <button class="nav-link position-relative" data-bs-toggle="tab"
                                        data-bs-target="#localites-overview">Localités

                                        @if ($operateur->operateurlocalites_count > 0)
                                            <span
                                                class="badge bg-secondary text-white position-absolute top-0 end-0 translate-middle p-1 rounded-circle">
                                                {{ $operateur->operateurlocalites_count }}
                                            </span>
                                        @endif
                                    </button>
                                </li>

                                @can('operateur-show-files')
                                    <li class="nav-item">
                                        <button class="nav-link position-relative" data-bs-toggle="tab"
                                            data-bs-target="#files">Fichiers
                                            @if ($operateur->user->files_count > 0)
                                                <span
                                                    class="badge bg-dark position-absolute top-0 end-0 translate-middle p-1 rounded-circle">
                                                    {{ $operateur->user->files_count ?? 0 }}
                                                </span>
                                            @endif
                                        </button>
                                    </li>
                                @endcan

                                <li class="nav-item">
                                    <button class="nav-link position-relative" data-bs-toggle="tab"
                                        data-bs-target="#observations-overview">Observations</button>
                                </li>

                                <li class="nav-item">
                                    @if ($validations && $validations->isNotEmpty())
                                        @hasanyrole('super-admin|admin|DIOF|ADIOF|Ingenieur|DEC')
                                            <span class="d-flex mt-2 align-items-baseline">
                                                <nav class="header-nav ms-auto">
                                                    <ul class="d-flex align-items-center list-unstyled mb-0 pt-2">
                                                        {{-- <li class="me-3 fw-semibold text-uppercase text-muted"
                                                            style="letter-spacing: 1px;">
                                                            Historique
                                                        </li> --}}
                                                        <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
                                                            <i class="bi bi-chat-left-text m-1"></i>
                                                            <span class="badge bg-success badge-number"
                                                                title="{{ $operateur?->statut }}">
                                                                {{ $operateur?->validationoperateurs->count() }}
                                                            </span>
                                                        </a>
                                                        <ul
                                                            class="dropdown-menu dropdown-menu-end dropdown-menu-arrow messages">
                                                            <li class="dropdown-header">
                                                                Vous avez
                                                                {{ $operateur?->validationoperateurs->count() }}
                                                                validation(s)
                                                            </li>
                                                            <li>
                                                                <hr class="dropdown-divider">
                                                            </li>
                                                            @foreach ($operateur?->validationoperateurs->sortByDesc('created_at')->take(2) as $validationoperateur)
                                                                <li class="message-item">
                                                                    <div>
                                                                        <p><span
                                                                                class="{{ $validationoperateur->action }}">{{ $validationoperateur->action }}</span>
                                                                        </p>
                                                                        <p>
                                                                            {{ $validationoperateur->user->firstname . ' ' . $validationoperateur->user->name }}
                                                                        </p>
                                                                        <p>{!! $validationoperateur->created_at->diffForHumans() !!}</p>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <hr class="dropdown-divider">
                                                                </li>
                                                            @endforeach
                                                            <li class="dropdown-footer">
                                                                <form action="{{ route('validationmessageop') }}"
                                                                    method="post" target="_blank">
                                                                    @csrf
                                                                    <input type="hidden" name="id"
                                                                        value="{{ $operateur?->id }}">
                                                                    <button class="btn btn-sm mx-1">Voir
                                                                        toutes les validations</button>
                                                                </form>
                                                            </li>
                                                        </ul>
                                                    </ul>
                                                </nav>
                                            </span>
                                        @endhasanyrole
                                    @else
                                        <span class="d-flex mt-2 align-items-baseline">
                                            <nav class="header-nav ms-auto">
                                                <ul class="d-flex align-items-center">
                                                    <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
                                                        <i class="bi bi-chat-left-text m-1"></i>
                                                        <span class="badge bg-success badge-number"
                                                            title="{{ $operateur?->statut }}">
                                                            {{ $operateur?->validationoperateurs->count() }}
                                                        </span>
                                                    </a>
                                                    <ul
                                                        class="dropdown-menu dropdown-menu-end dropdown-menu-arrow messages">
                                                        <li class="dropdown-header">
                                                            Vous avez
                                                            {{ $operateur?->validationoperateurs->count() }}
                                                            validation(s)
                                                        </li>
                                                        <li>
                                                            <hr class="dropdown-divider">
                                                        </li>
                                                        @foreach ($operateur?->validationoperateurs->sortByDesc('created_at')->take(2) as $validationoperateur)
                                                            <li class="message-item">
                                                                <div>
                                                                    <p><span
                                                                            class="{{ $validationoperateur->action }}">{{ $validationoperateur->action }}</span>
                                                                    </p>
                                                                    <p>{!! $validationoperateur->created_at->diffForHumans() !!}</p>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <hr class="dropdown-divider">
                                                            </li>
                                                        @endforeach
                                                        <li class="dropdown-footer">
                                                            <form action="{{ route('validationmessageop') }}"
                                                                method="post" target="_blank">
                                                                @csrf
                                                                <input type="hidden" name="id"
                                                                    value="{{ $operateur?->id }}">
                                                                <button class="btn btn-sm mx-1">Voir
                                                                    toutes les validations</button>
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </ul>
                                            </nav>
                                        </span>
                                    @endif
                                </li>
                            </ul>
                            {{-- Détail opérateur --}}
                            <div class="tab-content pt-0">
                                <div class="tab-pane fade profile-overview" id="profile-overview">
                                    <form method="post" action="#" enctype="multipart/form-data" class="row">
                                        @csrf
                                        @method('PUT')
                                        {{-- <div class="d-flex justify-content-between align-items-center">
                                            <h5 class="card-title">Opérateur</h5>
                                        </div> --}}
                                        <div class="col-12 mb-2 pt-3">
                                            <div class="label">Dénomination</div>
                                            <div>{{ $operateur?->user?->display_operateur }}</div>
                                        </div>
                                        {{-- <div class="col-12 col-md-4 mb-2">
                                            <div class="label">Sigle</div>
                                            <div>{{ $operateur?->user?->username }}</div>
                                        </div> --}}
                                        <div class="col-12 col-md-4 mb-2">
                                            <div class="label">Région</div>
                                            <div>{{ $operateur?->region?->nom }}</div>
                                        </div>
                                        <div class="col-12 col-md-4 mb-2">
                                            <div class="label">Département</div>
                                            <div>{{ $operateur?->departement?->nom }}</div>
                                        </div>
                                        <div class="col-12 col-md-4 mb-2">
                                            <div class="label">Adrese</div>
                                            <div>{{ $operateur?->user?->adresse }}</div>
                                        </div>
                                        <div class="col-12 col-md-4 mb-2">
                                            <div class="label">Numéro agrément</div>
                                            <div>{{ $operateur?->numero_agrement }}</div>
                                        </div>
                                        <div class="col-12 col-md-4 mb-2">
                                            <div class="label">Adresse email</div>
                                            <div><a
                                                    href="mailto:{{ $operateur?->user?->email }}">{{ $operateur?->user?->email }}</a>
                                            </div>
                                        </div>
                                        @if ($operateur?->user?->fixe)
                                            <div class="col-12 col-md-4 mb-2">
                                                <div class="label">Téléphone fixe</div>
                                                <div><a
                                                        href="tel:+221{{ $operateur?->user?->fixe }}">{{ $operateur?->user?->fixe }}</a>
                                                </div>
                                            </div>
                                        @endif
                                        @if ($operateur?->user?->bp)
                                            <div class="col-12 col-md-4 mb-2">
                                                <div class="label">Boite postale</div>
                                                <div>{{ $operateur?->user?->bp }}</div>
                                            </div>
                                        @endif
                                        @if ($operateur?->user?->categorie)
                                            <div class="col-12 col-md-4 mb-2">
                                                <div class="label">Catégorie</div>
                                                <div>{{ $operateur?->user?->categorie }}</div>
                                            </div>
                                        @endif

                                        @if ($operateur?->statut)
                                            <div class="col-12 col-md-4 mb-2">
                                                <div class="label">Statut juridique</div>
                                                <div>{{ $operateur?->statut }}</div>
                                            </div>
                                        @endif

                                        @if ($operateur?->autre_statut)
                                            <div class="col-12 col-md-4 mb-2">
                                                <div class="label">Autre statut</div>
                                                <div>{{ $operateur?->autre_statut }}</div>
                                            </div>
                                        @endif

                                        @if ($operateur?->user?->rccm)
                                            <div class="col-12 col-md-4 mb-2">
                                                <div class="label">RCCM/Ninea</div>
                                                <div>{{ $operateur?->user?->rccm }}</div>
                                            </div>
                                        @endif

                                        @if ($operateur?->user?->ninea)
                                            <div class="col-12 col-md-4 mb-2">
                                                <div class="label">N° RCCM/Ninea</div>
                                                <div>{{ $operateur?->user?->ninea }}</div>
                                            </div>
                                        @endif

                                        @if ($operateur?->debut_quitus)
                                            <div class="col-12 col-md-4">
                                                <div class="label">Date délivrance quitus</div>
                                                <div>{{ $operateur?->debut_quitus?->diffForHumans() }}</div>
                                            </div>
                                        @endif
                                    </form>
                                    <form method="post" action="#" enctype="multipart/form-data" class="row">
                                        @csrf
                                        @method('PUT')
                                        {{-- <h5 class="card-title">Personne morale</h5> --}}
                                        <h5 class="text-primary fw-semibold mb-3">Personne morale</h5>
                                        <div class="col-12 col-md-4">
                                            <div class="label">Civilité</div>
                                            <div>{{ $operateur?->user?->civilite }}</div>
                                        </div>
                                        <div class="col-12 col-md-4 mb-2">
                                            <div class="label">Prénom</div>
                                            <div>{{ $operateur?->user?->firstname }}</div>
                                        </div>
                                        <div class="col-12 col-md-4 mb-2">
                                            <div class="label">Nom</div>
                                            <div>{{ $operateur?->user?->name }}</div>
                                        </div>
                                        <div class="col-12 col-md-4 mb-2">
                                            <div class="label">Email</div>
                                            <div><a
                                                    href="mailto:{{ $operateur?->user?->email_responsable }}">{{ $operateur?->user?->email_responsable }}</a>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4 mb-2">
                                            <div class="label">Téléphone</div>
                                            <div><a
                                                    href="tel:+221{{ $operateur?->user?->telephone }}">{{ $operateur?->user?->telephone }}</a>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4 mb-2">
                                            <div class="label">Fonction</div>
                                            <div>{{ $operateur?->user?->fonction_responsable }}
                                            </div>
                                        </div>
                                    </form>

                                    <div class="text-center">
                                        <a href="{{ route('operateurs.edit', $operateur) }}"
                                            class="btn btn-primary btn-sm text-white" title="Modifier">Modifier</a>
                                    </div>
                                </div>
                            </div>
                            {{-- Détail représentant --}}
                            <div class="tab-content pt-2">
                                <div class="tab-pane fade profile-overview pt-3" id="references-overview">
                                    <form method="post" action="#" enctype="multipart/form-data" class="row g-3">
                                        @csrf
                                        @method('PUT')

                                        <div class="d-flex justify-content-between align-items-center">


                                            <h5 class="mb-0 fw-semibold text-uppercase text-info">
                                                <i class="bi bi-briefcase-fill me-2"></i> Expériences & Références
                                                professionnelles
                                            </h5>
                                            {{-- <h5 class="card-title">
                                                <a href="{{ route('showReference', ['id' => $operateur->id]) }}"
                                                    class="btn btn-outline-primary float-end btn-rounded btn-sm"
                                                    target="_blank">
                                                    <i class="bi bi-plus" title="Ajouter, Modifier, Supprimer"></i> </a> --}}
                                        </div>

                                        <table
                                            class="table table-bordered table-hover datatables align-middle justify-content-center table-borderless">
                                            <thead>
                                                <tr>
                                                    <th scope="col">ORGANISME</th>
                                                    <th scope="col">CONTACT</th>
                                                    <th scope="col">PERIODE</th>
                                                    <th scope="col">DESCRIPTION DES INTERVENTIONS</th>
                                                </tr>
                                            </thead>
                                            <tbody
                                                class="table table-bordered table-hover datatables align-middle justify-content-center table-borderless">
                                                <?php $i = 1; ?>
                                                @foreach ($operateur->operateureferences as $operateureference)
                                                    <tr>
                                                        <td>{{ $operateureference?->organisme }}</td>
                                                        <td>{{ $operateureference?->contact }}</td>
                                                        <td>{{ $operateureference?->periode }}</td>
                                                        <td>{{ $operateureference?->description }}</td>

                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </form>
                                </div>
                            </div>
                            <div class="tab-content pt-2">
                                <div class="tab-pane fade profile-overview pt-3" id="equipement-overview">
                                    <form method="post" action="#" enctype="multipart/form-data" class="row g-3">
                                        @csrf
                                        @method('PUT')
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h5 class="mb-0 fw-bold text-uppercase text-dark">
                                                <i class="bi bi-building-gear"></i> Infrastructures / Équipements
                                            </h5>
                                        </div>
                                        <table
                                            class="table table-bordered table-hover datatables align-middle justify-content-center table-borderless">
                                            <thead>
                                                <tr>
                                                    <th>DESIGNATION</th>
                                                    <th class="text-center">QUANTITE</th>
                                                    <th class="text-center">ETAT</th>
                                                    <th class="text-center">TYPE</th>
                                                    <th class="text-center">STATUT</th>
                                                    <th class="text-center">VALIDATION</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $i = 1; ?>
                                                @foreach ($operateur->operateurequipements as $operateurequipement)
                                                    <tr>
                                                        <td>{{ $operateurequipement->designation }}</td>
                                                        <td style="text-align: center;">
                                                            {{ $operateurequipement->quantite }}</td>
                                                        <td style="text-align: center;">{{ $operateurequipement->etat }}
                                                        </td>
                                                        <td style="text-align: center;">{{ $operateurequipement->type }}
                                                        </td>
                                                        <td style="text-align: center;">
                                                            <span
                                                                class="{{ $operateurequipement?->statut }}">{{ $operateurequipement?->statut }}</span>
                                                        </td>

                                                        <td class="text-center">
                                                            <a href="#"
                                                                class="btn btn-outline-success btn-sm d-inline-flex align-items-center gap-1 shadow-sm"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#ValidationEquipementModal{{ $operateurequipement->id }}">
                                                                <i class="bi bi-check2-circle fs-6"></i>
                                                                <span class="d-none d-md-inline">Valider</span>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </form>
                                </div>
                            </div>
                            <div class="tab-content pt-2">
                                <div class="tab-pane fade profile-overview pt-3" id="formateur-overview">
                                    <form method="post" action="#" enctype="multipart/form-data" class="row g-3">
                                        @csrf
                                        @method('PUT')
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h5 class="mb-0 fw-bold text-uppercase text-primary">
                                                <i class="bi bi-person-badge-fill me-2"></i> Formateurs
                                            </h5>
                                            {{-- <h5 class="card-title">
                                                <a href="{{ route('showFormateur', ['id' => $operateur->id]) }}"
                                                    class="btn btn-outline-primary float-end btn-rounded btn-sm"
                                                    target="_blank">
                                                    <i class="bi bi-plus" title="Ajouter, Modifier, Supprimer"></i> </a>
                                            </h5> --}}
                                        </div>
                                        <table
                                            class="table table-bordered table-hover datatables align-middle justify-content-center">
                                            <thead>
                                                <tr>
                                                    <th>PRENOM(S) ET NOM</th>
                                                    <th>CHAMPS PROFESSIONNELS</th>
                                                    <th class="text-center">ANNEES EXPERIENCE</th>
                                                    <th>REFERENCES</th>
                                                    <th>CV</th>
                                                    <th>STATUT</th>
                                                    <th class="text-center">VALIDATION</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $i = 1; ?>
                                                @foreach ($operateur->operateurformateurs as $operateurformateur)
                                                    <tr>
                                                        <td>{{ $operateurformateur->name }}</td>
                                                        <td>{{ $operateurformateur->domaine }}</td>
                                                        <td style="text-align: center;">
                                                            {{ $operateurformateur->nbre_annees_experience }}</td>
                                                        <td>{{ $operateurformateur->references }}</td>
                                                        <td class="text-center">
                                                            @if ($operateurformateur?->file)
                                                                <a class="btn btn-outline-secondary btn-sm"
                                                                    title="Convention" target="_blank"
                                                                    href="{{ asset($operateurformateur?->getCVFormateurs()) }}">
                                                                    <i class="bi bi-file-earmark-pdf"></i>
                                                                </a>
                                                            @else
                                                                <span class="text-muted small">Aucun</span>
                                                            @endif
                                                        </td>
                                                        <td style="text-align: center;">
                                                            <span
                                                                class="{{ $operateurformateur?->statut }}">{{ $operateurformateur?->statut }}</span>
                                                        </td>
                                                        <td class="text-center">
                                                            <a href="#"
                                                                class="btn btn-outline-success btn-sm d-inline-flex align-items-center gap-1 shadow-sm"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#ValidationFormateurModal{{ $operateurformateur->id }}">
                                                                <i class="bi bi-check2-circle fs-6"></i>
                                                                <span class="d-none d-md-inline">Valider</span>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </form>
                                </div>
                            </div>
                            <div class="tab-content pt-2">
                                <div class="tab-pane fade profile-overview pt-3" id="localites-overview">
                                    <form method="post" action="#" enctype="multipart/form-data" class="row g-3">
                                        @csrf
                                        @method('PUT')
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h4 class="mb-0 fw-bold text-uppercase text-success">
                                                <i class="bi bi-geo-alt-fill me-2"></i> Localités
                                            </h4>
                                        </div>
                                        <table
                                            class="table table-bordered table-hover datatables align-middle justify-content-center">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">N°</th>
                                                    <th>LOCALITE</th>
                                                    <th>REGION</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $i = 1; ?>
                                                @foreach ($operateur->operateurlocalites as $operateurlocalite)
                                                    <tr>
                                                        <td style="text-align: center;">{{ $i++ }}</td>
                                                        <td>{{ $operateurlocalite->name }}</td>
                                                        <td>{{ $operateurlocalite->region }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </form>
                                </div>
                            </div>
                            {{-- Détail Modules --}}
                            <div class="tab-content pt-2">
                                <div class="tab-pane fade show active profile-overview pt-3" id="module-overview">

                                    <!-- End module -->
                                    <div class="card mb-4 shadow-sm border-0 w-100">
                                        <div
                                            class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
                                            <div>
                                                {{-- <h5 class="mb-1 text-dark fw-bold d-flex align-items-center">
                                                    <i class="bi bi-building text-primary me-2 fs-5"></i>
                                                    <span>Date commission :</span>

                                                    <span class="{{ $operateur?->statut_agrement }} text-white">
                                                        {{ $operateur?->statut_agrement }}</span>

                                                        <span class="ms-2 text-primary">
                                                            {{ $operateur->commissionagrements->pluck('fin_commission')->filter()->map(fn($date) => \Carbon\Carbon::parse($date)->format('d/m/Y'))->implode(' - ') }}
                                                        </span>
                                                </h5> --}}

                                                <div
                                                    class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
                                                    {{-- <span><i class="bi bi-building text-primary me-2 fs-5"></i>
                                                        <span>Date commission :</span>
                                                    </span> --}}

                                                    <span class="{{ $operateur?->statut_agrement }} text-white">
                                                        {{ $operateur?->statut_agrement }}</span>

                                                    <span class="ms-2 text-primary">
                                                        {{ $operateur->commissionagrements->pluck('fin_commission')->filter()->map(fn($date) => \Carbon\Carbon::parse($date)->format('d/m/Y'))->implode(' - ') }}
                                                    </span>
                                                </div>
                                            </div>

                                            {{-- Statut sur une seule ligne --}}
                                            <div class="d-flex align-items-center">
                                                <span class="fw-semibold text-muted me-2">Type de demande :</span>
                                                <span
                                                    class="badge {{ $operateur?->type_demande }} px-3 py-2 fs-6 shadow-sm rounded-pill">
                                                    {{ $operateur?->type_demande }}
                                                </span>
                                            </div>
                                        </div>

                                        {{-- <div class="card-body px-4">
                                            <div
                                                class="d-flex justify-content-between align-items-center border-bottom py-1 position-relative">
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-journal-code text-info me-2 fs-5"></i>
                                                    <span class="me-2">Modules</span>
                                                </div>

                                                <span
                                                    class="badge {{ count($operateur->operateurmodules) === 0 ? 'bg-danger' : 'bg-info' }} position-absolute top-50 start-50 translate-middle-y"
                                                    style="transform: translateX(-50%);">
                                                    {{ count($operateur->operateurmodules) }}
                                                </span>

                                                <div>
                                                    <a href="{{ route('operateurs.show', $operateur) }}" target="_blank"
                                                        class="btn btn-sm btn-outline-success me-1"
                                                        title="Ajouter/Modifier">
                                                        <i class="bi bi-pencil-square me-1"></i> Ajouter /
                                                        Modifier
                                                    </a>
                                                </div>
                                            </div>


                                            <div
                                                class="d-flex justify-content-between align-items-center border-bottom py-1 position-relative">
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-bookmark-check text-primary me-2"></i>Références
                                                    <span
                                                        class="badge {{ count($operateur->operateureferences) === 0 ? 'bg-danger' : 'bg-info' }} position-absolute top-50 start-50 translate-middle-y"
                                                        style="transform: translateX(-50%);">{{ count($operateur->operateureferences) }}</span>
                                                </div>
                                                <div>
                                                    <a href="{{ route('showReference', $operateur->uuid) }}"
                                                        target="_blank" class="btn btn-sm btn-outline-success me-1"
                                                        title="Ajouter/Modifier">
                                                        <i class="bi bi-pencil-square me-1"></i> Ajouter /
                                                        Modifier
                                                    </a>
                                                </div>
                                            </div>

                                            <div
                                                class="d-flex justify-content-between align-items-center border-bottom py-1 position-relative">
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-hdd-network text-warning me-2"></i>Équipements
                                                    & Infrastructures
                                                    <span
                                                        class="badge {{ count($operateur->operateurequipements) === 0 ? 'bg-danger' : 'bg-info' }} position-absolute top-50 start-50 translate-middle-y"
                                                        style="transform: translateX(-50%);">{{ count($operateur->operateurequipements) }}</span>
                                                </div>
                                                <div>
                                                    <a href="{{ route('showEquipement', $operateur->uuid) }}"
                                                        target="_blank" class="btn btn-sm btn-outline-success me-1"
                                                        title="Ajouter/Modifier">
                                                        <i class="bi bi-pencil-square me-1"></i> Ajouter /
                                                        Modifier
                                                    </a>
                                                </div>
                                            </div>

                                            <div
                                                class="d-flex justify-content-between align-items-center border-bottom py-1 position-relative">
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-person-workspace text-success me-2"></i>Formateurs
                                                    <span
                                                        class="badge {{ count($operateur->operateurformateurs) === 0 ? 'bg-danger' : 'bg-info' }} position-absolute top-50 start-50 translate-middle-y"
                                                        style="transform: translateX(-50%);">{{ count($operateur->operateurformateurs) }}</span>
                                                </div>
                                                <div>
                                                    <a href="{{ route('showFormateur', $operateur->uuid) }}"
                                                        target="_blank" class="btn btn-sm btn-outline-success me-1"
                                                        title="Ajouter/Modifier">
                                                        <i class="bi bi-pencil-square me-1"></i> Ajouter /
                                                        Modifier
                                                    </a>
                                                </div>
                                            </div>

                                            <div
                                                class="d-flex justify-content-between align-items-center border-bottom py-1 position-relative">
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-geo-alt text-danger me-2"></i>Localités
                                                    <span
                                                        class="badge {{ count($operateur->operateurlocalites) === 0 ? 'bg-danger' : 'bg-info' }} position-absolute top-50 start-50 translate-middle-y"
                                                        style="transform: translateX(-50%);">{{ count($operateur->operateurlocalites) }}</span>
                                                </div>
                                                <div>
                                                    <a href="{{ route('showLocalite', $operateur->uuid) }}"
                                                        target="_blank" class="btn btn-sm btn-outline-success me-1"
                                                        title="Ajouter/Modifier">
                                                        <i class="bi bi-pencil-square me-1"></i> Ajouter /
                                                        Modifier
                                                    </a>
                                                </div>
                                            </div>


                                            <div
                                                class="d-flex justify-content-between align-items-center border-bottom py-1 position-relative">
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-file-earmark-text text-dark me-2"></i>
                                                    Validité quitus
                                                    @if ($operateur?->debut_quitus)
                                                        <span
                                                            class="badge {{ ($diff?->y ?? 0) * 12 + ($diff?->m ?? 0) > 3 ? 'bg-danger' : 'bg-info' }} position-absolute top-50 start-50 translate-middle-y"
                                                            style="transform: translateX(-50%);">
                                                            {{ $diffText }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div
                                                class="d-flex justify-content-between align-items-center border-bottom py-1 position-relative">
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-info-circle text-secondary me-2"></i>État
                                                    de la demande
                                                    <span
                                                        class="badge {{ $statut_demande === 'incomplète' ? 'bg-danger' : 'bg-success' }} position-absolute top-50 start-50 translate-middle-y"
                                                        style="transform: translateX(-50%);">{{ $statut_demande }}</span>
                                                </div>
                                            </div>

                                            <div
                                                class="d-flex justify-content-between align-items-center border-bottom py-1 position-relative">
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-bookmark-check text-primary me-2"></i>Certifier
                                                    informations
                                                    @php
                                                        $estCertifie = boolval($operateur->file8);
                                                    @endphp

                                                    <span
                                                        class="badge {{ $estCertifie ? 'bg-success' : 'bg-danger' }} position-absolute top-50 start-50 translate-middle-y"
                                                        style="transform: translateX(-50%);">
                                                        {!! $estCertifie ? '<i class="bi bi-check-circle"></i> Oui' : '<i class="bi bi-x-circle"></i> Non' !!}
                                                    </span>
                                                </div>
                                            </div>
                                        </div> --}}

                                        <div class="card-body px-4">

                                            @foreach ($sections as $section)
                                                <div
                                                    class="d-flex justify-content-between align-items-center border-bottom py-1 position-relative">

                                                    <div class="d-flex align-items-center">
                                                        <i class="bi {{ $section['icon'] }} me-2"></i>
                                                        {{ $section['label'] }}

                                                        @if (isset($section['count']))
                                                            <span
                                                                class="badge {{ $section['badge'] ?? ($section['count'] === 0 ? 'bg-danger' : 'bg-info') }}
            position-absolute top-50 start-50 translate-middle-y"
                                                                style="transform: translateX(-50%);">
                                                                {{ $section['count'] }}
                                                            </span>
                                                        @endif
                                                    </div>

                                                    <div>
                                                        @if (!empty($section['route']))
                                                            <a href="{{ $section['route'] }}" target="_blank"
                                                                class="btn btn-sm btn-outline-success">
                                                                <i class="bi bi-pencil-square me-1"></i> Ajouter / Modifier
                                                            </a>
                                                        @elseif(!empty($section['modal']))
                                                            <button class="btn btn-sm btn-outline-success"
                                                                title="Modifier" data-bs-toggle="modal"
                                                                data-bs-target="#{{ $section['modal'] }}">
                                                                <i class="bi bi-pencil-square me-1"></i> Ajouter / Modifier
                                                            </button>
                                                        @endif
                                                    </div>

                                                </div>
                                            @endforeach

                                            {{-- ETAT DEMANDE --}}
                                            <div
                                                class="d-flex justify-content-between align-items-center border-bottom py-1 position-relative">
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-info-circle text-secondary me-2"></i>
                                                    État de la demande

                                                    <span
                                                        class="badge {{ $statut_demande === 'incomplète' ? 'bg-danger' : 'bg-success' }}
            position-absolute top-50 start-50 translate-middle-y"
                                                        style="transform: translateX(-50%);">
                                                        {{ $statut_demande }}
                                                    </span>
                                                </div>
                                            </div>


                                            {{-- CERTIFICATION --}}
                                            @php $estCertifie = boolval($operateur->file8); @endphp

                                            <div
                                                class="d-flex justify-content-between align-items-center border-bottom py-1 position-relative">

                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-bookmark-check text-primary me-2"></i>
                                                    Certifier informations

                                                    <span
                                                        class="badge {{ $estCertifie ? 'bg-success' : 'bg-danger' }}
            position-absolute top-50 start-50 translate-middle-y"
                                                        style="transform: translateX(-50%);">
                                                        {!! $estCertifie ? '<i class="bi bi-check-circle"></i> Oui' : '<i class="bi bi-x-circle"></i> Non' !!}
                                                    </span>
                                                </div>

                                                <div>
                                                    @if ($statut_demande === 'complète')
                                                        <button type="button" class="btn btn-sm btn-outline-primary"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#certificationModal{{ $operateur->id }}">
                                                            <i class="bi bi-pencil-square me-1"></i>
                                                            Je certifie
                                                        </button>
                                                    @else
                                                        <span class="badge bg-warning text-dark p-2">
                                                            <i class="bi bi-exclamation-triangle-fill me-1"></i>
                                                            Demande incomplète
                                                        </span>
                                                    @endif
                                                </div>

                                            </div>

                                        </div>

                                        @can('update', $operateur)
                                            <div
                                                class="card-footer bg-light text-center py-3 border-top d-flex justify-content-center gap-3">
                                                @can('devenir-operateur-agrement-delete')
                                                    @can('delete', $operateur)
                                                        <form action="{{ route('operateurs.destroy', $operateur) }}" method="post"
                                                            class="d-inline-block show_confirm">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-outline-danger btn-sm px-4"
                                                                title="Supprimer">
                                                                <i class="bi bi-trash me-1"></i> Supprimer
                                                            </button>
                                                        </form>
                                                    @endcan
                                                @endcan
                                            </div>
                                        @endcan

                                    </div>

                                    {{-- <div class="col-12 mb-0">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div
                                                class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
                                                <h5 class="mb-0 text-uppercase fw-bold text-primary">
                                                    <i class="bi bi-briefcase-fill me-2"></i> Modules de formation
                                                </h5>
                                            </div>
                                            <h5>Type : <span
                                                    class="{{ $operateur?->type_demande }} btn-sm">{{ $operateur?->type_demande }}</span>
                                            </h5>
                                            <span class="card-title d-flex align-items-baseline">Statut
                                                :&nbsp;
                                                <span class="{{ $operateur?->statut_agrement }} text-white">
                                                    {{ $operateur?->statut_agrement }}</span>
                                                @if (auth()->user()->hasRole('super-admin|admin|DEC'))
                                                    <div class="filter">
                                                        <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                                                class="bi bi-three-dots"></i></a>
                                                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                                            <li>
                                                                <button class="btn btn-sm mx-1" data-bs-toggle="modal"
                                                                    data-bs-target="#RejetAgrementModal{{ $operateur->id }}"><i
                                                                        class="bi bi-check2-circle"
                                                                        title="Justification"></i>&nbsp;Validation
                                                                </button>
                                                            </li>
                                                            @can('fichesynthese-view')
                                                                <li>
                                                                    <form action="{{ route('ficheSyntheseOperateur') }}"
                                                                        method="post" target="_blank">
                                                                        @csrf
                                                                        <input type="hidden" name="id"
                                                                            value="{{ $operateur?->id }}">
                                                                        <button class="btn btn-sm mx-1">Fiche synthèse</button>
                                                                    </form>
                                                                </li>
                                                            @endcan
                                                            @can('lettreagrement-view')
                                                                <li>
                                                                    <form action="{{ route('lettreOperateur') }}"
                                                                        method="post" target="_blank">
                                                                        @csrf
                                                                        <input type="hidden" name="id"
                                                                            value="{{ $operateur?->id }}">
                                                                        <button class="btn btn-sm mx-1">Lettre
                                                                            agrément</button>
                                                                    </form>
                                                                </li>
                                                            @endcan
                                                        </ul>
                                                    </div>
                                                @endif
                                            </span>
                                        </div>
                                        <div class="row g-3">
                                            <table
                                                class="table table-bordered table-hover datatables align-middle justify-content-center"
                                                id="table-operateurModules">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">N°</th>
                                                        <th scope="col">DOMAINE</th>
                                                        <th scope="col">MODULE</th>
                                                        <th scope="col">CATEGORIE</th>
                                                        <th scope="col">QUALIFICATION</th>
                                                        <th class="text-center">STATUT</th>
                                                        @if (auth()->user()->hasRole('super-admin|admin|DEC'))
                                                            <th class="text-center"><i class="bi bi-gear"></i></th>
                                                        @endif
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $i = 1; ?>
                                                    @foreach ($operateur->operateurmodules as $operateurmodule)
                                                        <tr>
                                                            <td style="text-align: center;">{{ $i++ }}</td>
                                                            <td>{{ $operateurmodule?->domaine }}</td>
                                                            <td>{{ $operateurmodule?->module }}</td>
                                                            <td>{{ $operateurmodule?->categorie }}</td>
                                                            <td>{{ $operateurmodule?->niveau_qualification }}</td>
                                                            <td style="text-align: center;">
                                                                <span
                                                                    class="{{ $operateurmodule?->statut }}">{{ $operateurmodule?->statut }}</span>
                                                            </td>
                                                            <td style="text-align: center;">
                                                                <span
                                                                    class="d-flex align-items-baseline justify-content-center">
                                                                    <a href="{{ route('operateurmodules.show', $operateurmodule) }}"
                                                                        class="btn btn-primary btn-sm"
                                                                        title="voir détails"><i class="bi bi-eye"></i></a>
                                                                    @if (auth()->user()->hasRole('super-admin|admin|DEC'))
                                                                        <div class="filter">
                                                                            <a class="icon" href="#"
                                                                                data-bs-toggle="dropdown"><i
                                                                                    class="bi bi-three-dots"></i></a>
                                                                            <ul
                                                                                class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">

                                                                                <button
                                                                                    class="dropdown-item btn btn-sm mx-1"
                                                                                    data-bs-toggle="modal"
                                                                                    data-bs-target="#EditOperateurmoduleModal{{ $operateurmodule->id }}">Modifier
                                                                                </button>
                                                                                <form
                                                                                    action="{{ route('operateurmodules.destroy', $operateurmodule) }}"
                                                                                    method="post">
                                                                                    @csrf
                                                                                    @method('DELETE')
                                                                                    <button type="submit"
                                                                                        class="dropdown-item show_confirm"
                                                                                        title="Supprimer">Supprimer</button>
                                                                                </form>
                                                                            </ul>
                                                                        </div>
                                                                    @endif
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div> --}}
                                </div>
                            </div>
                            <div class="tab-content pt-2">
                                {{-- Début Edition --}}
                                <div class="tab-pane fade files" id="files">
                                    <div class="row mb-3">
                                        <h5 class="card-title col-12 col-md-4">
                                            FICHIERS JOINTS</h5>

                                        @php
                                            // Filtrer uniquement les fichiers qui ont une valeur non vide
                                            $validFiles = $operateur?->user?->files->filter(
                                                fn($file) => !empty($file->file),
                                            );
                                        @endphp

                                        @if ($validFiles->isNotEmpty())
                                            <div class="col-12 col-md-8">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-hover datatables"
                                                        id="table-iles">
                                                        <thead>
                                                            <tr>
                                                                <th style="width: 5%">N°</th>
                                                                <th>Légende</th>
                                                                <th style="width: 10%">Fichier</th>
                                                                <th style="width: 10%">Statut</th>
                                                                <th style="width: 10%">Supprimer</th>
                                                                @hasanyrole('super-admin|admin|DIOF|Ingenieur|DEC')
                                                                    <th style="width: 10%">Valider</th>
                                                                    <th style="width: 10%">Rejeter</th>
                                                                @endhasanyrole
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @php $i = 1; @endphp
                                                            @foreach ($validFiles as $file)
                                                                <tr class="text-center align-middle">
                                                                    <td>{{ $i++ }}</td>
                                                                    <td>{{ $file->legende }}</td>
                                                                    <td>
                                                                        <a class="btn btn-outline-secondary btn-sm"
                                                                            title="Télécharger" target="_blank"
                                                                            href="{{ asset($file->getFichier()) }}">
                                                                            <i class="bi bi-download"></i>
                                                                        </a>
                                                                    </td>
                                                                    <td>
                                                                        @php
                                                                            $statut = $file->statut ?? 'Attente';
                                                                            $badgeClass = match ($statut) {
                                                                                'Validé' => 'success',
                                                                                'Rejeté', 'Invalide' => 'danger',
                                                                                default => 'secondary',
                                                                            };
                                                                        @endphp
                                                                        <span
                                                                            class="badge bg-{{ $badgeClass }}">{{ $statut }}</span>
                                                                    </td>
                                                                    {{-- Supprimer --}}
                                                                    <td>
                                                                        @if ($file->statut !== 'Validé')
                                                                            <form action="{{ route('fileDestroy') }}"
                                                                                method="post" class="d-inline">
                                                                                @csrf
                                                                                @method('put')
                                                                                <input type="hidden" name="idFile"
                                                                                    value="{{ $file->id }}">
                                                                                <button type="submit"
                                                                                    class="btn btn-outline-danger btn-sm show_confirm"
                                                                                    title="Supprimer">
                                                                                    <i class="bi bi-trash"></i>
                                                                                </button>
                                                                            </form>
                                                                        @endif
                                                                    </td>

                                                                    @hasanyrole('super-admin|admin|DIOF|Ingenieur|DEC')
                                                                        {{-- Valider --}}
                                                                        <td>
                                                                            <form action="{{ route('fileValidate') }}"
                                                                                method="post" class="d-inline">
                                                                                @csrf
                                                                                @method('put')
                                                                                <input type="hidden" name="idFile"
                                                                                    value="{{ $file->id }}">
                                                                                <button type="submit"
                                                                                    class="btn btn-outline-success btn-sm show_confirm_valider"
                                                                                    title="Valider">
                                                                                    <i class="bi bi-check-circle"></i>
                                                                                </button>
                                                                            </form>
                                                                        </td>
                                                                        {{-- Invalider --}}
                                                                        <td>
                                                                            <form action="{{ route('fileInvalide') }}"
                                                                                method="post" class="d-inline">
                                                                                @csrf
                                                                                @method('put')
                                                                                <input type="hidden" name="idFile"
                                                                                    value="{{ $file->id }}">
                                                                                <button type="submit"
                                                                                    class="btn btn-outline-warning btn-sm show_confirm_rejeter"
                                                                                    title="Invalider">
                                                                                    <i class="bi bi-x-circle"></i>
                                                                                </button>
                                                                            </form>
                                                                        </td>
                                                                    @endhasanyrole
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        @else
                                            <div class="alert alert-info">
                                                <p class="text-muted">Aucun fichier joint.</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Détail Observations --}}
                            <div class="tab-content">
                                <div class="tab-pane fade profile-overview pt-3" id="observations-overview">
                                    <div class="card shadow-sm border-0">
                                        <div
                                            class="card-header d-flex justify-content-between align-items-center bg-light">
                                            <h5 class="mb-0">Observations visite de conformité</h5>
                                            @if (!empty($operateur?->visite_conformite))
                                                <span class="badge bg-info text-white">
                                                    {{ $operateur->visite_conformite }}
                                                </span>
                                            @endif
                                            <div class="d-flex align-items-center gap-2">
                                                <a href="#"
                                                    class="btn btn-success btn-sm d-flex align-items-center gap-1"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#addobservations{{ $operateur->id }}"
                                                    title="Ajouter">
                                                    <i class="bi bi-plus"></i>
                                                    Ajouter/Modifier
                                                </a>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            @if (!empty($operateur?->observations))
                                                <textarea name="observation" id="observation" rows="8" readonly class="form-control form-control-sm"
                                                    placeholder="Aucune observation pour le moment">{{ $operateur->observations }}</textarea>
                                            @else
                                                <div class="text-muted">Aucune observation pour le moment.</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- @include('operateurs.agrements.modals') --}}
                            @foreach ($operateur->operateurmodules as $operateurmodule)
                                <div class="modal" id="myModal{{ $operateurmodule->id }}">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <!-- Modal Header -->
                                            <div class="modal-header">
                                                <h4 class="modal-title">Confirmation</h4>
                                                <button type="button" class="btn-close"
                                                    data-bs-dismiss="modal"></button>
                                            </div>

                                            <!-- Modal body -->
                                            <div class="modal-body">
                                                Êtes-vous sûre de bien vouloir supprimer ?
                                            </div>

                                            <!-- Modal footer -->
                                            <div class="modal-footer">
                                                <form method="post"
                                                    action="{{ route('operateurmodules.destroy', $operateurmodule) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">
                                                            Non</button>
                                                        <button class="btn btn-danger">
                                                            <i class="bi bi-trash"></i> Oui
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script>
        new DataTable('#table-operateurModules', {
            ordering: false,
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
    </script>
@endpush
