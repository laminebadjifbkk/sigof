@extends('layout.user-layout')
@section('title', 'AGREMENT | ' . $operateur?->user?->username)
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
                        <li class="breadcrumb-item active">{{ $operateur?->user?->username }}</li>
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
                            <ul class="nav nav-tabs nav-tabs-bordered">
                                <li class="nav-item">
                                    <span class="nav-link"><a href="{{ route('operateurs.show', $operateur) }}"
                                            class="btn btn-secondary btn-sm" title="retour"><i
                                                class="bi bi-arrow-counterclockwise"></i></a>
                                    </span>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link" data-bs-toggle="tab"
                                        data-bs-target="#profile-overview">Opérateur</button>
                                </li>

                                <li class="nav-item">
                                    <button class="nav-link active" data-bs-toggle="tab"
                                        data-bs-target="#module-overview">Module
                                    </button>
                                </li>

                                <li class="nav-item">
                                    <button class="nav-link" data-bs-toggle="tab"
                                        data-bs-target="#references-overview">Références</button>
                                </li>

                                <li class="nav-item">
                                    <button class="nav-link" data-bs-toggle="tab"
                                        data-bs-target="#equipement-overview">Equipements</button>
                                </li>

                                <li class="nav-item">
                                    <button class="nav-link" data-bs-toggle="tab"
                                        data-bs-target="#formateur-overview">Formateurs</button>
                                </li>

                                <li class="nav-item">
                                    <button class="nav-link" data-bs-toggle="tab"
                                        data-bs-target="#localites-overview">Localités</button>
                                </li>

                                @can('operateur-show-files')
                                    <li class="nav-item">
                                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#files">Fichiers</button>
                                    </li>
                                @endcan

                                <li class="nav-item">
                                    <button class="nav-link" data-bs-toggle="tab"
                                        data-bs-target="#observations-overview">Observations</button>
                                </li>

                            </ul>
                            <div class="d-flex justify-content-between align-items-center">
                            </div>
                            <div class="tab-content pt-0">
                                @php
                                    $validations = $operateur?->validationoperateurs;
                                @endphp
                                @if ($validations && $validations->isNotEmpty())
                                    @hasanyrole('super-admin|admin|DIOF|ADIOF|Ingenieur')
                                        <span class="d-flex mt-2 align-items-baseline">
                                            <nav class="header-nav ms-auto">
                                                <ul class="d-flex align-items-center list-unstyled mb-0 pt-2">
                                                    <li class="me-3 fw-semibold text-uppercase text-muted"
                                                        style="letter-spacing: 1px;">
                                                        Historique
                                                    </li>
                                                    <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
                                                        <i class="bi bi-chat-left-text m-1"></i>
                                                        <span class="badge bg-success badge-number"
                                                            title="{{ $operateur?->statut }}">
                                                            {{ $operateur?->validationoperateurs->count() }}
                                                        </span>
                                                    </a>
                                                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow messages">
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
                                                            <form action="{{ route('validationmessageop') }}" method="post"
                                                                target="_blank">
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
                                                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow messages">
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
                                                        <form action="{{ route('validationmessageop') }}" method="post"
                                                            target="_blank">
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
                                <div class="tab-pane fade profile-overview" id="profile-overview">
                                    <form method="post" action="#" enctype="multipart/form-data" class="row">
                                        @csrf
                                        @method('PUT')
                                        {{-- <div class="d-flex justify-content-between align-items-center">
                                            <h5 class="card-title">Opérateur</h5>
                                        </div> --}}
                                        {{-- <h5 class="text-primary fw-semibold mb-3">Opérateur</h5> --}}

                                        <div class="col-12 mb-2 pt-3">
                                            <div class="label">Raison sociale</div>
                                            <div>{{ $operateur?->user?->operateur }}</div>
                                        </div>
                                        <div class="col-12 col-md-4 mb-2">
                                            <div class="label">Sigle</div>
                                            <div>{{ $operateur?->user?->username }}</div>
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

                                        {{-- @if ($operateur?->debut_quitus)
                                            <div class="col-12 col-md-4 mb-2">
                                                <div class="label">Quitus</div>
                                                <div>
                                                    @if (!empty($operateur?->debut_quitus))
                                                        <a class="btn btn-outline-secondary btn-sm"
                                                            title="télécharger le quitus" target="_blank"
                                                            href="{{ asset($operateur?->getQuitus()) }}">
                                                            <i class="bi bi-file-image"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif --}}
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
                            <div class="tab-content pt-0">
                                <div class="tab-pane fade profile-overview pt-3" id="references-overview">
                                    <form method="post" action="#" enctype="multipart/form-data" class="row g-3">
                                        @csrf
                                        @method('PUT')

                                        <div class="d-flex justify-content-between align-items-center">

                                            <h5 class="mb-0 fw-semibold text-uppercase text-info">
                                                <i class="bi bi-briefcase-fill me-2"></i> Expériences & Références
                                                professionnelles
                                            </h5>
                                            {{--  <h5 class="card-title">
                                                <a href="{{ route('showReference', ['id' => $operateur->id]) }}"
                                                    class="btn btn-outline-primary float-end btn-rounded btn-sm"
                                                    target="_blank">
                                                    <i class="bi bi-plus" title="Ajouter, Modifier, Supprimer"></i> </a>
                                            </h5> --}}
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
                                            <tbody>
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
                            <div class="tab-content pt-0">
                                <div class="tab-pane fade profile-overview pt-3" id="equipement-overview">
                                    <form method="post" action="#" enctype="multipart/form-data" class="row g-3">
                                        @csrf
                                        @method('PUT')
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h5 class="mb-0 fw-bold text-uppercase text-dark">
                                                <i class="bi bi-building-gear"></i> Infrastructures / Équipements
                                            </h5>
                                            {{--  <h5 class="card-title">
                                                <a href="{{ route('showEquipement', ['id' => $operateur->id]) }}"
                                                    class="btn btn-outline-primary float-end btn-rounded btn-sm"
                                                    target="_blank">
                                                    <i class="bi bi-plus" title="Ajouter, Modifier, Supprimer"></i> </a>
                                            </h5> --}}
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
                                                    {{-- <th class="text-center"><i class="bi bi-gear"></i></th> --}}
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
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </form>
                                </div>
                            </div>
                            <div class="tab-content pt-0">
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
                                                    <th class="text-center">CV</th>
                                                    <th class="text-center">STATUT</th>
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
                            <div class="tab-content pt-0">
                                <div class="tab-pane fade show active profile-overview pt-3" id="module-overview">
                                    <div class="card mb-4 shadow-sm border-0 w-100">
                                        <div
                                            class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
                                            <div class="d-flex flex-wrap align-items-center justify-content-md-center">
                                                <i class="bi bi-building text-primary me-2"></i>
                                                <span class="fw-bold">Date commission :</span>

                                                <span class="ms-2 text-primary">
                                                    {{ $operateur->commissionagrements->pluck('fin_commission')->filter()->map(fn($date) => \Carbon\Carbon::parse($date)->format('d/m/Y'))->implode(' - ') }}
                                                </span>
                                            </div>

                                            {{-- Statut sur une seule ligne --}}
                                            <div class="d-flex align-items-center">
                                                <span class="fw-semibold text-muted me-2">Type :</span>
                                                <span
                                                    class="badge {{ $operateur?->type_demande }} px-3 py-2 fs-6 shadow-sm rounded-pill">
                                                    {{ $operateur?->type_demande }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="card-body px-4">

                                            {{-- MODULES --}}
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


                                            {{-- REFERENCES --}}
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

                                            {{-- EQUIPEMENTS --}}
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

                                            {{-- FORMATEURS --}}
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

                                            {{-- LOCALITES --}}
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

                                            {{-- ÉTAT (sans bouton) --}}
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

                                            {{-- QUITUS --}}
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
                                                <div>
                                                    {{-- Bouton pour télécharger le quitus --}}
                                                    {{-- <a href="{{ asset($operateur?->getQuitus()) }}" target="_blank"
                                                        class="btn btn-sm btn-outline-info" title="Télécharger le Quitus">
                                                        <i class="bi bi-download"></i> Télécharger
                                                    </a> --}}
                                                </div>
                                            </div>
                                            {{-- Certifier informations --}}
                                            <div
                                                class="d-flex justify-content-between align-items-center border-bottom py-1 position-relative">
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-bookmark-check text-primary me-2"></i>Certifier
                                                    informations
                                                    {{-- <span
                                                class="badge {{ $operateur->file8 === true ? 'bg-danger' : 'bg-success' }} position-absolute top-50 start-50 translate-middle-y"
                                                style="transform: translateX(-50%);">{{ $operateur?->file8 }}</span> --}}
                                                    @php
                                                        $estCertifie = boolval($operateur->file8);
                                                    @endphp

                                                    <span
                                                        class="badge {{ $estCertifie ? 'bg-success' : 'bg-danger' }} position-absolute top-50 start-50 translate-middle-y"
                                                        style="transform: translateX(-50%);">
                                                        {!! $estCertifie ? '<i class="bi bi-check-circle"></i> Oui' : '<i class="bi bi-x-circle"></i> Non' !!}
                                                    </span>
                                                </div>
                                                {{-- <div>
                                                    <button type="button" class="btn btn-sm btn-outline-primary me-1"
                                                        data-bs-toggle="modal" data-bs-target="#certificationModal">
                                                        <i class="bi bi-pencil-square me-1"></i> Je certifie
                                                    </button>
                                                </div> --}}
                                            </div>
                                        </div>

                                        @can('update', $operateur)
                                            <div
                                                class="card-footer bg-light text-center py-3 border-top d-flex justify-content-center gap-3">
                                                {{-- Bouton Modifier --}}
                                                {{-- <button class="btn btn-warning btn-sm text-white px-4" title="Modifier"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#EditOperateurModal{{ $operateur->id }}">
                                                    <i class="bi bi-pencil me-1"></i> Modifier
                                                </button> --}}

                                                {{-- Bouton Supprimer --}}
                                                {{-- @can('devenir-operateur-agrement-delete')
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
                                                @endcan --}}
                                            </div>
                                        @endcan
                                    </div>
                                    <div class="col-12 mb-0">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div
                                                class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
                                                <h5 class="mb-0 text-uppercase fw-bold text-primary">
                                                    <i class="bi bi-briefcase-fill me-2"></i> Modules de formation
                                                </h5>
                                            </div>
                                            <span>
                                                @can('fichesynthese-view')
                                                    <form
                                                        action="{{ route('ficheSyntheseOperateur', ['id' => $operateur->id]) }}"
                                                        method="post" target="_blank" class="d-inline">
                                                        @csrf
                                                        <button type="submit"
                                                            class="btn btn-secondary btn-sm rounded-pill px-3 py-1 shadow-sm hover-shadow">
                                                            <i class="bi bi-file-earmark-text me-1"></i> Fiche synthèse
                                                        </button>
                                                    </form>
                                                @endcan
                                            </span>

                                            <span class="card-title d-flex align-items-baseline">Statut
                                                :&nbsp;
                                                <span class="{{ $operateur->statut_agrement }} text-white">
                                                    {{ $operateur?->statut_agrement }}</span>
                                                <div class="filter">
                                                    <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                                            class="bi bi-three-dots"></i></a>
                                                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                                        {{-- <form
                                                            action="{{ route('agreerOperateur', ['id' => $operateur->id]) }}"
                                                            method="post">
                                                            @csrf
                                                            @method('PUT')
                                                            <button class="show_confirm_valider btn btn-sm mx-1"><i
                                                                    class="bi bi-check2-circle"
                                                                    title="Valider"></i>&nbsp;agréé opérateur</button>
                                                        </form> --}}
                                                        {{-- <div>
                                                            <button class="btn btn-sm mx-1" data-bs-toggle="modal"
                                                                data-bs-target="#ReserveAgrementModal{{ $operateur->id }}"><i
                                                                    class="bi bi-chat-square-text"
                                                                    title="Justification"></i>&nbsp;sous réserve
                                                            </button>
                                                        </div> --}}
                                                        <button class="btn btn-sm mx-1" data-bs-toggle="modal"
                                                            data-bs-target="#RejetAgrementModal{{ $operateur->id }}"><i
                                                                class="bi bi-check2-circle"
                                                                title="Agrément"></i>&nbsp;Validation opérateurs
                                                        </button>
                                                        <form
                                                            action="{{ route('agreerAllModuleOperateur', ['id' => $operateur->id]) }}"
                                                            method="post">
                                                            @csrf
                                                            @method('PUT')
                                                            <button class="show_confirm_valider btn btn-sm mx-1"><i
                                                                    class="bi bi-check2-circle"
                                                                    title="Valider"></i>&nbsp;Validation modules</button>
                                                        </form>
                                                    </ul>
                                                </div>
                                            </span>
                                        </div>
                                        <div class="row g-3">
                                            <table
                                                class="table table-bordered table-hover datatables align-middle justify-content-center"
                                                id="table-operateurModules">
                                                <thead>
                                                    <tr>
                                                        <th scope="col" width="3%">N°</th>
                                                        <th scope="col">DOMAINE</th>
                                                        <th scope="col">MODULE</th>
                                                        <th scope="col">CATEGORIE</th>
                                                        <th scope="col">QUALIFICATION</th>
                                                        <th class="text-center" width="5%">STATUT</th>
                                                        <th class="text-center" width="2%"><i class="bi bi-gear"></i>
                                                        </th>
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
                                                                    <div class="filter">
                                                                        <a class="icon" href="#"
                                                                            data-bs-toggle="dropdown"><i
                                                                                class="bi bi-three-dots"></i></a>
                                                                        <ul
                                                                            class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                                                            {{-- <form
                                                                                action="{{ route('validation-operateur-modules.update', $operateurmodule->id) }}"
                                                                                method="post">
                                                                                @csrf
                                                                                @method('PUT')
                                                                                <button
                                                                                    class="show_confirm_valider dropdown-item btn btn-sm mx-1">agréé</button>
                                                                            </form> --}}
                                                                            <button class="dropdown-item btn btn-sm mx-1"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#AddRegionModal{{ $operateurmodule->id }}">Agréer
                                                                                module
                                                                            </button>
                                                                            <button class="dropdown-item btn btn-sm mx-1"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#EditOperateurmoduleModal{{ $operateurmodule->id }}">modifier
                                                                            </button>
                                                                            <form
                                                                                action="{{ route('operateurmodules.destroy', $operateurmodule->id) }}"
                                                                                method="post">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                                <button type="submit"
                                                                                    class="dropdown-item show_confirm"
                                                                                    title="Supprimer">supprimer</button>
                                                                            </form>
                                                                        </ul>
                                                                    </div>
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-content pt-2">
                                {{-- Début Edition --}}
                                <div class="tab-pane fade files" id="files">
                                    <div class="row mb-3">
                                        <h5 class="card-title col-12 col-md-4">
                                            FICHIERS JOINTS</h5>
                                        {{-- @php
                                            // Filtrer uniquement les fichiers qui ont une valeur non vide
                                            $validFiles = $user?->files->filter(fn($file) => !empty($file->file));
                                        @endphp --}}

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
                                                            {{-- <tr>
                                                            <th width="5%" class="text-center">N°</th>
                                                            <th>Légende</th>
                                                            <th width="10%" class="text-center">File</th>
                                                            @can('user-show-file')
                                                                <th width="5%" class="text-center"><i
                                                                        class="bi bi-gear"></i></th>
                                                            @endcan
                                                        </tr> --}}
                                                            <tr>
                                                                <th style="width: 5%">N°</th>
                                                                <th>Légende</th>
                                                                <th style="width: 10%">Fichier</th>
                                                                <th style="width: 10%">Statut</th>
                                                                <th style="width: 10%">Supprimer</th>
                                                                @hasanyrole('super-admin|admin|DIOF|Ingenieur')
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

                                                                    @hasanyrole('super-admin|admin|DIOF|Ingenieur')
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
                                <div class="tab-pane fade profile-overview pt-0" id="observations-overview">
                                    <div class="d-flex justify-content-between align-items-center mt-0">
                                        <h5 class="card-title">Observations</h5>
                                        <span>Visite conformité : <span
                                                class="{{ $operateur?->visite_conformite }}">{{ $operateur?->visite_conformite }}</span></span>
                                        <a href="#" class="btn btn-success btn-sm float-end" data-bs-toggle="modal"
                                            data-bs-target="#addobservations" title="Ajouter">Conformité</a>
                                    </div>
                                    @if (!empty($operateur?->observations))
                                        <textarea name="observation" id="observation" rows="10" @readonly(true)
                                            class="form-control form-control-sm @error('date_reponse') is-invalid @enderror" placeholder="Observations">{{ $operateur?->observations ?? old('observation') }}</textarea>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Edit Operateur-->
        <!-- Edit Operateur Module -->
        @foreach ($operateur->operateurmodules as $operateurmodule)
            <div class="modal fade" id="EditOperateurmoduleModal{{ $operateurmodule->id }}" tabindex="-1"
                role="dialog" aria-labelledby="EditOperateurmoduleModalLabel{{ $operateurmodule->id }}"
                aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        {{-- <form method="POST" action="#">
                            @csrf --}}
                        {{-- <form method="post" action="{{ route('operateurmodules.update', $operateurmodule->id) }}"
                            enctype="multipart/form-data" class="row g-3">
                            @csrf
                            @method('patch')
                            <div class="modal-header" id="EditOperateurmoduleModalLabel{{ $operateurmodule->id }}">
                                <h5 class="modal-title">Modification module
                                    opérateur</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="id" value="{{ $operateurmodule->id }}">

                                <div class="col-12 mb-0">
                                    <label for="module" class="form-label">Module<span
                                            class="text-danger mx-1">*</span></label>
                                    <input type="text" name="module" id="module_operateur_edit"
                                        value="{{ $operateurmodule->module ?? old('module') }}"
                                        class="form-control form-control-sm @error('module') is-invalid @enderror"
                                        placeholder="module">
                                    <div id="moduleListEdit"></div>
                                    {{ csrf_field() }}
                                    @error('module')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12 mb-0">
                                    <label for="domaine" class="form-label">Domaine<span
                                            class="text-danger mx-1">*</span></label>
                                    <input type="text" name="domaine"
                                        value="{{ $operateurmodule->domaine ?? old('domaine') }}"
                                        class="form-control form-control-sm @error('domaine') is-invalid @enderror"
                                        placeholder="domaine">
                                    @error('domaine')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-12 mb-0">
                                    <label for="categorie" class="form-label">Catégorie<span
                                            class="text-danger mx-1">*</span></label>
                                    <input type="text" name="categorie"
                                        value="{{ $operateurmodule->categorie ?? old('categorie') }}"
                                        class="form-control form-control-sm @error('categorie') is-invalid @enderror"
                                        placeholder="categorie">
                                    @error('categorie')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-12 mb-0">
                                    <label for="niveau_qualification" class="form-label">Niveau de qualification<span
                                            class="text-danger mx-1">*</span></label>
                                    <select name="niveau_qualification" class="form-select selectpicker"
                                        data-live-search="true @error('niveau_qualification') is-invalid @enderror"
                                        aria-label="Select" id="select-field-niveau_qualification-update"
                                        data-placeholder="Choisir niveau qualification">
                                        <option value="{{ $operateurmodule->niveau_qualification }}">
                                            {{ $operateurmodule->niveau_qualification ?? old('niveau_qualification') }}
                                        </option>
                                        <option value="Pré-qualification">
                                            Pré-qualification
                                        </option>
                                        <option value="Pré-qualification">
                                            Pré-qualification
                                        </option>
                                        <option value="Qualification">
                                            Qualification
                                        </option>
                                    </select>
                                    @error('niveau_qualification')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Fermer</button>
                                <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-printer"></i>
                                    Modifier</button>
                            </div>
                        </form> --}}
                        <form method="post" action="{{ route('operateurmodules.update', $operateurmodule) }}"
                            enctype="multipart/form-data" class="row g-3">
                            @csrf
                            @method('patch')

                            <div class="modal-header" id="EditOperateurmoduleModalLabel{{ $operateurmodule->id }}">
                                <h5 class="modal-title">Modification module opérateur</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>

                            <div class="modal-body">
                                <input type="hidden" name="id" value="{{ $operateurmodule->id }}">
                                <div class="col-12 col-md-12">
                                    <label for="module_operateur_edit" class="form-label">Module <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="module" id="module_operateur_edit"
                                        value="{{ old('module', $operateurmodule->module) }}"
                                        class="form-control form-control-sm @error('module') is-invalid @enderror"
                                        placeholder="Module">
                                    @error('module')
                                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-12">
                                    <label for="domaine" class="form-label">Domaine <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="domaine"
                                        value="{{ old('domaine', $operateurmodule->domaine) }}"
                                        class="form-control form-control-sm @error('domaine') is-invalid @enderror"
                                        placeholder="Domaine">
                                    @error('domaine')
                                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-12">
                                    <label for="categorie" class="form-label">Catégorie <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="categorie"
                                        value="{{ old('categorie', $operateurmodule->categorie) }}"
                                        class="form-control form-control-sm @error('categorie') is-invalid @enderror"
                                        placeholder="Catégorie">
                                    @error('categorie')
                                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-12">
                                    <label for="niveau_qualification" class="form-label">Niveau de qualification <span
                                            class="text-danger">*</span></label>
                                    <select name="niveau_qualification"
                                        class="form-select form-select-sm @error('niveau_qualification') is-invalid @enderror"
                                        aria-label="Choisir niveau qualification" data-live-search="true"
                                        data-placeholder="Choisir niveau qualification">
                                        <option value="" disabled>-- Sélectionner --</option>
                                        @foreach (['Pré-qualification', 'Renforcement de capacités', 'Qualification'] as $niveau)
                                            <option value="{{ $niveau }}"
                                                {{ old('niveau_qualification', $operateurmodule->niveau_qualification) == $niveau ? 'selected' : '' }}>
                                                {{ $niveau }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('niveau_qualification')
                                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary btn-sm"
                                    data-bs-dismiss="modal">Fermer</button>
                                <button type="submit" class="btn btn-primary btn-sm">Modifier
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        @endforeach
        <!-- End Edit Operateur Module-->
        <!-- The Modal Delete -->
        @foreach ($operateur->operateurmodules as $operateurmodule)
            <div class="modal" id="myModal{{ $operateurmodule->id }}">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Confirmation</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body">
                            Êtes-vous sûre de bien vouloir supprimer ?
                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <form method="post" action="{{ route('operateurmodules.destroy', $operateurmodule->id) }}">
                                @csrf
                                @method('DELETE')
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
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
        {{-- @foreach ($operateur->operateurmodules as $operateurmodule)
            <div class="modal fade" id="AddRegionModal{{ $operateurmodule->id }}" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form method="post"
                            action="{{ route('validation-operateur-modules.destroy', $operateurmodule->id) }}"
                            enctype="multipart/form-data" class="row">
                            @csrf
                            @method('DELETE')
                            <div class="modal-header">
                                <h5 class="modal-title">Rejet module</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <label for="motif" class="form-label">Motifs du rejet<span
                                        class="text-danger mx-1">*</span></label>
                                <textarea name="motif" id="motif" rows="5"
                                    class="form-control form-control-sm @error('motif') is-invalid @enderror"
                                    placeholder="Enumérer les motifs du rejet">{{ $operateurmodule?->motif ?? old('motif') }}</textarea>
                                @error('motif')
                                    <span class="invalid-feedback" role="alert">
                                        <div>{{ $message }}</div>
                                    </span>
                                @enderror
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Fermer</button>
                                <button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-printer"></i>
                                    Rejeter</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach --}}
        @foreach ($operateur->operateurmodules as $operateurmodule)
            <div class="modal fade" id="AddRegionModal{{ $operateurmodule->id }}" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content shadow-lg rounded-3">
                        <form method="POST"
                            action="{{ route('validation-operateur-modules.destroy', $operateurmodule->id) }}">
                            @csrf
                            @method('DELETE')
                            <div class="modal-header bg-light border-bottom-0">
                                <h5 class="modal-title fw-bold text-info" id="AddRegionModal{{ $operateurmodule->id }}">
                                    Traitement du module
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Fermer"></button>
                            </div>

                            <div class="modal-body">
                                {{-- Champ Statut --}}
                                <div class="mb-3">
                                    <label for="statut-{{ $operateurmodule->id }}" class="form-label">
                                        Statut module <span class="text-danger">*</span>
                                    </label>
                                    @php
                                        $selectedStatut = old('statut', $operateurmodule->statut);
                                    @endphp
                                    <select name="statut" id="statut-{{ $operateurmodule->id }}"
                                        class="form-select form-select-sm @error('statut') is-invalid @enderror" autofocus>
                                        <option value="" disabled {{ !$selectedStatut ? 'selected' : '' }}>
                                            -- Sélectionner un statut --
                                        </option>
                                        <option value="agréé" {{ $selectedStatut === 'agréé' ? 'selected' : '' }}>
                                            agréé
                                        </option>
                                        <option value="réserve" {{ $selectedStatut === 'réserve' ? 'selected' : '' }}>
                                            réserve
                                        </option>
                                        <option value="rejeté" {{ $selectedStatut === 'rejeté' ? 'selected' : '' }}>
                                            rejeté
                                        </option>
                                        <option value="expiré" {{ $selectedStatut === 'expiré' ? 'selected' : '' }}>
                                            expiré
                                        </option>
                                    </select>
                                    @error('statut')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Champ Commentaires --}}
                                <div class="mb-3">
                                    <label for="motif-{{ $operateurmodule->id }}" class="form-label">
                                        Commentaires ou remarques <span class="text-danger">*</span>
                                    </label>
                                    @php
                                        $lastValidation = collect($operateurmodule->moduleoperateurstatuts)
                                            ->sortByDesc('created_at')
                                            ->first();
                                    @endphp
                                    <textarea name="motif" id="motif-{{ $operateurmodule->id }}" rows="5"
                                        class="form-control form-control-sm @error('motif') is-invalid @enderror"
                                        placeholder="Indiquez les raisons ou recommandations">{{ old('motif', $lastValidation?->motif) }}</textarea>
                                    @error('motif')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="modal-footer border-top-0">
                                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                                    Annuler
                                </button>
                                <button type="submit" class="btn btn-info btn-sm">
                                    Soumettre
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        @endforeach

        {{-- Agrément sous réserve --}}
        {{-- @foreach ($operateurs as $operateur) --}}
        <div class="modal fade" id="ReserveAgrementModal{{ $operateur->id }}" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    {{-- <form method="POST" action="{{ route('addRegion') }}">
                        @csrf --}}
                    <form method="post" action="{{ route('validation-operateur.update', $operateur->id) }}"
                        enctype="multipart/form-data" class="row">
                        @csrf
                        @method('PUT')
                        <div class="card-header text-center bg-gradient-default">
                            <h1 class="h4 text-black mb-0">SOUS RESERVE</h1>
                        </div>
                        <div class="modal-body">
                            <label for="motif" class="form-label">Motifs<span
                                    class="text-danger mx-1">*</span></label>
                            <textarea name="motif" id="motif" rows="5"
                                class="form-control form-control-sm @error('motif') is-invalid @enderror" placeholder="Justification">{{ $operateur?->motif ?? old('motif') }}</textarea>
                            @error('motif')
                                <span class="invalid-feedback" role="alert">
                                    <div>{{ $message }}</div>
                                </span>
                            @enderror
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-sm"
                                data-bs-dismiss="modal">Fermer</button>
                            <button type="submit" class="btn btn-warning btn-sm">Mettre sous réserve</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        {{-- @endforeach --}}
        {{-- Agrément rejeter --}}
        {{-- @foreach ($operateurs as $operateur)
            <div class="modal fade" id="RejetAgrementModal{{ $operateur->id }}" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form method="post" action="{{ route('validation-operateur.destroy', $operateur->id) }}"
                            enctype="multipart/form-data" class="row">
                            @csrf
                            @method('DELETE')
                            <div class="card-header text-center bg-gradient-default">
                                <h1 class="h4 text-black mb-0">REJET AGREMENT</h1>
                            </div>
                            <div class="modal-body">
                                <label for="motif" class="form-label">Motifs<span
                                        class="text-danger mx-1">*</span></label>
                                <textarea name="motif" id="motif" rows="5"
                                    class="form-control form-control-sm @error('motif') is-invalid @enderror" placeholder="Justification">{{ $operateur?->motif ?? old('motif') }}</textarea>
                                @error('motif')
                                    <span class="invalid-feedback" role="alert">
                                        <div>{{ $message }}</div>
                                    </span>
                                @enderror
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary btn-sm"
                                    data-bs-dismiss="modal">Fermer</button>
                                <button type="submit" class="btn btn-danger btn-sm">Rejeter</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach --}}

        <div class="modal fade" id="RejetAgrementModal{{ $operateur->id }}" tabindex="-1"
            aria-labelledby="RejetAgrementModalLabel{{ $operateur->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content shadow-lg rounded-3">
                    <form method="POST" action="{{ route('validation-operateur.destroy', $operateur->id) }}">
                        @csrf
                        @method('DELETE')
                        <div class="modal-header bg-light border-bottom-0">
                            <h5 class="modal-title fw-bold text-info" id="RejetAgrementModalLabel{{ $operateur->id }}">
                                Traitement agrément
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Fermer"></button>
                        </div>

                        <div class="modal-body">
                            {{-- Champ Statut --}}
                            <div class="mb-3">
                                <label for="statut-{{ $operateur->id }}" class="form-label">
                                    Statut de la demande <span class="text-danger">*</span>
                                </label>
                                @php
                                    $selectedStatut = old('statut') ?? $operateur->statut_agrement;
                                @endphp

                                <select name="statut" id="statut-{{ $operateur->id }}"
                                    class="form-select form-select-sm @error('statut') is-invalid @enderror" autofocus>

                                    <option value="" disabled {{ empty($selectedStatut) ? 'selected' : '' }}>
                                        -- Sélectionner un statut --
                                    </option>

                                    <option value="agréé" {{ $selectedStatut === 'agréé' ? 'selected' : '' }}>
                                        agréé
                                    </option>
                                    <option value="sous réserve"
                                        {{ $selectedStatut === 'sous réserve' ? 'selected' : '' }}>
                                        sous réserve
                                    </option>
                                    <option value="rejeté" {{ $selectedStatut === 'rejeté' ? 'selected' : '' }}>
                                        rejeté
                                    </option>
                                    <option value="Nouveau" {{ $selectedStatut === 'Nouveau' ? 'selected' : '' }}>
                                        Nouveau
                                    </option>
                                    <option value="expiré" {{ $selectedStatut === 'expiré' ? 'selected' : '' }}>
                                        expiré
                                    </option>
                                </select>
                                @error('statut')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Champ Commentaires --}}
                            <div class="mb-3">
                                <label for="motif-{{ $operateur->id }}" class="form-label">
                                    Commentaires ou remarques
                                </label>
                                @php
                                    $lastValidation = collect($operateur->validationoperateurs)
                                        ->sortByDesc('created_at')
                                        ->first();
                                @endphp
                                <textarea name="motif" id="motif-{{ $operateur->id }}" rows="5"
                                    class="form-control form-control-sm @error('motif') is-invalid @enderror"
                                    placeholder="Indiquez les raisons ou recommandations">{{ old('motif', $lastValidation?->motif) }}</textarea>
                                @error('motif')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="modal-footer border-top-0">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                                Fermer
                            </button>
                            <button type="submit" class="btn btn-info btn-sm">
                                Enregistrer
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>

        <div class="modal fade" id="addobservations" tabindex="-1" role="dialog"
            aria-labelledby="addobservationsLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Observations</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="post" action="{{ route('observations', ['id' => $operateur->id]) }}"
                        enctype="multipart/form-data" class="row g-3">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="row g-3">

                                <div class="col-12">
                                    <label for="visite_conformite" class="form-label">Visite conformité<span
                                            class="text-danger mx-1">*</span></label>
                                    <select name="visite_conformite"
                                        class="form-select form-select-sm @error('visite_conformite') is-invalid @enderror"
                                        aria-label="Select" id="select-field-visite_conformite"
                                        data-placeholder="Choisir">
                                        <option value="{{ $operateur?->visite_conformite ?? old('visite_conformite') }}">
                                            {{ $operateur?->visite_conformite ?? old('visite_conformite') }}
                                        </option>
                                        <option value="Oui">
                                            Oui
                                        </option>
                                        <option value="Non">
                                            Non
                                        </option>
                                    </select>
                                    @error('visite_conformite')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="observation" class="form-label">Observations<span
                                            class="text-danger mx-1">*</span></label>
                                    <textarea name="observation" id="observation" rows="10"
                                        class="form-control form-control-sm @error('date_reponse') is-invalid @enderror" placeholder="Observations">{{ $operateur?->observations ?? old('observation') }}</textarea>
                                    @error('observation')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary btn-sm"
                                        data-bs-dismiss="modal">Fermer</button>
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary btn-sm">Enregistrer</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

@endsection
@push('scripts')
    <script>
        new DataTable('#table-operateurModules', {
            layout: {
                topStart: {
                    buttons: ['csv', 'excel', 'print'],
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
@endpush
