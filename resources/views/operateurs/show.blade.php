@extends('layout.user-layout')
@section('title', 'OPERATEUR | ' . $operateur?->user?->display_operateur)
@section('space-work')
    @can('operateur-show')
        <section
            class="section profile min-vh-0 d-flex flex-column align-items-center justify-content-center py-0 section profile">
            <div class="container-fluid">
                <div class="pagetitle">
                    {{-- <h1>Data Tables</h1> --}}
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Accueil</a></li>
                            <li class="breadcrumb-item">Tables</li>
                            <li class="breadcrumb-item active">{{ $operateur?->user?->display_operateur }}</li>
                        </ol>
                    </nav>
                </div><!-- End Page Title -->
                <div class="row justify-content-center">
                    @if ($message = Session::get('status'))
                        <div class="alert alert-success bg-success text-light border-0 alert-dismissible fade profile-overview show"
                            role="alert">
                            <strong>{{ $message }}</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if ($message = Session::get('danger'))
                        <div class="alert alert-danger bg-danger text-light border-0 alert-dismissible fade profile-overview show"
                            role="alert">
                            <strong>{{ $message }}</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if ($errors->any())
                        @foreach ($errors->all() as $error)
                            <div class="alert alert-danger bg-danger text-light border-0 alert-dismissible fade profile-overview show"
                                role="alert"><strong>{{ $error }}</strong></div>
                        @endforeach
                    @endif
                    <div class="flex items-center gap-4">
                        <div class="card">
                            <div class="card-body">
                                <ul class="nav nav-tabs nav-tabs-bordered align-items-center gap-2 flex-wrap position-relative">

                                    @can('operateur-view')
                                        <li class="nav-item">
                                            <a href="{{ route('operateurs.index', $operateur?->id) }}"
                                                class="btn btn-light btn-sm d-flex align-items-center gap-1" title="Retour">
                                                <i class="bi bi-arrow-left"></i>
                                                <span>Retour</span>
                                            </a>
                                        </li>

                                        <li class="nav-item">
                                            <button class="nav-link" data-bs-toggle="tab"
                                                data-bs-target="#details-overview">Opérateur
                                            </button>
                                        </li>
                                    @endcan

                                    {{-- Exemple d'onglet avec badge en haut à droite --}}
                                    <li class="nav-item position-relative">
                                        <button class="nav-link d-flex align-items-center justify-content-center active"
                                            data-bs-toggle="tab" data-bs-target="#module-overview">
                                            Modules
                                            @if ($operateur->operateurmodules_count > 0)
                                                <span
                                                    class="badge bg-primary position-absolute top-0 end-0 translate-middle p-1 rounded-circle">
                                                    {{ $operateur->operateurmodules_count }}
                                                </span>
                                            @endif
                                        </button>
                                    </li>

                                    <li class="nav-item position-relative">
                                        <button class="nav-link d-flex align-items-center justify-content-center"
                                            data-bs-toggle="tab" data-bs-target="#references-overview">
                                            Références
                                            @if ($operateur->operateureferences_count > 0)
                                                <span
                                                    class="badge bg-secondary position-absolute top-0 end-0 translate-middle p-1 rounded-circle">
                                                    {{ $operateur->operateureferences_count }}
                                                </span>
                                            @endif
                                        </button>
                                    </li>

                                    <li class="nav-item position-relative">
                                        <button class="nav-link d-flex align-items-center justify-content-center"
                                            data-bs-toggle="tab" data-bs-target="#equipement-overview">
                                            Équipements
                                            @if ($operateur->operateurequipements_count > 0)
                                                <span
                                                    class="badge bg-warning text-dark position-absolute top-0 end-0 translate-middle p-1 rounded-circle">
                                                    {{ $operateur->operateurequipements_count }}
                                                </span>
                                            @endif
                                        </button>
                                    </li>

                                    <li class="nav-item position-relative">
                                        <button class="nav-link d-flex align-items-center justify-content-center"
                                            data-bs-toggle="tab" data-bs-target="#formateur-overview">
                                            Formateurs
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

                                    <li class="nav-item position-relative">
                                        <button class="nav-link d-flex align-items-center justify-content-center"
                                            data-bs-toggle="tab" data-bs-target="#files">
                                            Fichiers
                                            @if ($operateur->user->files_count > 0)
                                                <span
                                                    class="badge bg-dark position-absolute top-0 end-0 translate-middle p-1 rounded-circle">
                                                    {{ $operateur->user->files_count ?? 0 }}
                                                </span>
                                            @endif
                                        </button>
                                    </li>

                                    @if ($operateur->formations_count > 0)
                                        <li class="nav-item position-relative">
                                            <button class="nav-link d-flex align-items-center justify-content-center"
                                                data-bs-toggle="tab" data-bs-target="#formation-overview">
                                                Formations
                                                <span
                                                    class="badge bg-success position-absolute top-0 end-0 translate-middle p-1 rounded-circle">
                                                    {{ $operateur->formations_count }}
                                                </span>
                                            </button>
                                        </li>
                                    @endif

                                    @can('show-observations')
                                        <li class="nav-item position-relative">
                                            <button class="nav-link d-flex align-items-center justify-content-center"
                                                data-bs-toggle="tab" data-bs-target="#observations-overview">
                                                Observations
                                                @if (!empty($operateur->observations))
                                                    <span
                                                        class="badge bg-info position-absolute top-0 end-0 translate-middle p-1 rounded-circle">
                                                        !
                                                    </span>
                                                @endif
                                            </button>
                                        </li>
                                    @endcan

                                    <li class="nav-item position-relative">
                                        {{-- Détail opérateur --}}
                                        <div class="tab-content pt-0">
                                            @if ($validations && $validations->isNotEmpty())
                                                @hasanyrole('super-admin|admin|DIOF|ADIOF|Ingenieur|Operateur')
                                                    <span class="d-flex mt-2 align-items-baseline">
                                                        <nav class="header-nav ms-auto">
                                                            <ul class="d-flex align-items-center list-unstyled mb-0 pt-2">
                                                                {{-- <li class="me-3 fw-semibold text-uppercase text-muted"
                                                                    style="letter-spacing: 1px;">
                                                                    Historique
                                                                </li> --}}
                                                                <a class="nav-link nav-icon" href="#"
                                                                    data-bs-toggle="dropdown">
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
                                                            <a class="nav-link nav-icon" href="#"
                                                                data-bs-toggle="dropdown">
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
                                        </div>
                                    </li>

                                </ul>

                                {{-- Détail représentant --}}
                                <div class="tab-content pt-2">
                                    <div class="tab-pane fade profile-overview" id="references-overview">
                                        <form method="post" action="#" enctype="multipart/form-data" class="row g-3">
                                            @csrf
                                            @method('PUT')
                                            {{-- <div class="d-flex justify-content-between align-items-center">
                                                <h5 class="card-title">EXPERIENCES ET REFERENCES PROFESSIONNELLES</h5>
                                                @can('devenir-operateur-agrement-ouvert')
                                                    @can('agrement-visible-par-op')
                                                        <h5 class="card-title">
                                                            <a href="{{ route('showReference', ['id' => $operateur?->id]) }}"
                                                                class="btn btn-outline-primary float-end btn-rounded btn-sm"
                                                                target="_blank">Ajouter</a>
                                                        </h5>
                                                    @endcan
                                                @endcan
                                            </div> --}}

                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <h5 class="mb-0 fw-semibold text-uppercase text-info">
                                                    <i class="bi bi-briefcase-fill me-2"></i> Expériences & Références
                                                    professionnelles
                                                </h5>

                                                @can('devenir-operateur-agrement-ouvert')
                                                    @can('agrement-visible-par-op')
                                                        <a href="{{ route('showReference', ['uuid' => $operateur?->uuid]) }}"
                                                            class="btn btn-info btn-sm rounded-pill shadow-sm d-inline-flex align-items-center gap-1 px-3 py-1"
                                                            target="_blank" title="Ajouter, Modifier, Supprimer des références">
                                                            <i class="bi bi-plus-circle-fill"></i>
                                                            Gérer références
                                                        </a>
                                                    @endcan
                                                @endcan
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
                                                    @foreach ($operateur?->operateureferences as $operateureference)
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
                                    <div class="tab-pane fade profile-overview" id="equipement-overview">
                                        <form method="post" action="#" enctype="multipart/form-data" class="row g-3">
                                            @csrf
                                            @method('PUT')
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <h5 class="mb-0 fw-bold text-uppercase text-dark">
                                                    <i class="bi bi-building-gear"></i> Infrastructures / Équipements
                                                </h5>

                                                @can('devenir-operateur-agrement-ouvert')
                                                    @can('agrement-visible-par-op')
                                                        <a href="{{ route('showEquipement', ['uuid' => $operateur?->uuid]) }}"
                                                            class="btn btn-dark btn-sm rounded-pill shadow-sm d-inline-flex align-items-center gap-1 px-3 py-1"
                                                            target="_blank" title="Ajouter, Modifier, Supprimer les équipements">
                                                            <i class="bi bi-plus-circle-fill"></i>
                                                            Gérer
                                                        </a>
                                                    @endcan
                                                @endcan
                                            </div>

                                            <table
                                                class="table table-bordered table-hover datatables align-middle justify-content-center table-borderless">
                                                <thead>
                                                    <tr>
                                                        <th>DESIGNATION</th>
                                                        <th class="text-center">QUANTITE</th>
                                                        <th class="text-center">ETAT</th>
                                                        <th class="text-center">TYPE</th>
                                                        {{-- <th class="text-center"><i class="bi bi-gear"></i></th> --}}
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $i = 1; ?>
                                                    @foreach ($operateur?->operateurequipements as $operateurequipement)
                                                        <tr>
                                                            <td>{{ $operateurequipement->designation }}</td>
                                                            <td style="text-align: center;">
                                                                {{ $operateurequipement->quantite }}</td>
                                                            <td style="text-align: center;">{{ $operateurequipement->etat }}
                                                            </td>
                                                            <td style="text-align: center;">{{ $operateurequipement->type }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </form>
                                    </div>
                                </div>
                                <div class="tab-content pt-2">
                                    <div class="tab-pane fade profile-overview" id="formateur-overview">
                                        <form method="post" action="#" enctype="multipart/form-data" class="row g-3">
                                            @csrf
                                            @method('PUT')
                                            {{-- <div class="d-flex justify-content-between align-items-center">
                                                <h5 class="card-title">FORMATEURS</h5>
                                                @can('devenir-operateur-agrement-ouvert')
                                                    @can('agrement-visible-par-op')
                                                        <h5 class="card-title">
                                                            <a href="{{ route('showFormateur', ['id' => $operateur?->id]) }}"
                                                                class="btn btn-outline-primary float-end btn-rounded btn-sm"
                                                                target="_blank">
                                                                <i class="bi bi-plus" title="Ajouter, Modifier, Supprimer"></i> </a>
                                                        </h5>
                                                    @endcan
                                                @endcan
                                            </div> --}}
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <h5 class="mb-0 fw-bold text-uppercase text-primary">
                                                    <i class="bi bi-person-badge-fill me-2"></i> Formateurs
                                                </h5>
                                                @can('devenir-operateur-agrement-ouvert')
                                                    @can('agrement-visible-par-op')
                                                        <a href="{{ route('showFormateur', ['uuid' => $operateur?->uuid]) }}"
                                                            class="btn btn-primary btn-sm rounded-pill shadow-sm d-inline-flex align-items-center gap-1 px-3 py-1"
                                                            target="_blank" title="Ajouter, Modifier, Supprimer un formateur">
                                                            <i class="bi bi-people-fill"></i>
                                                            Gérer formateurs
                                                        </a>
                                                    @endcan
                                                @endcan
                                            </div>
                                            <table
                                                class="table table-bordered table-hover datatables align-middle justify-content-center">
                                                <thead>
                                                    <tr>
                                                        <th>PRENOM(S) ET NOM</th>
                                                        <th>CHAMPS PROFESSIONNELS</th>
                                                        <th class="text-center">ANNEES EXPERIENCE</th>
                                                        <th>REFERENCES</th>
                                                        <th class="text-center">CV FORMATEURS</th>
                                                        {{-- <th class="text-center"><i class="bi bi-gear"></i></th> --}}
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $i = 1; ?>
                                                    @foreach ($operateur?->operateurformateurs as $operateurformateur)
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
                                                            {{-- <td style="text-align: center;">
                                                            <span class="d-flex align-items-baseline">
                                                                <a href="{{ route('operateurformateurs.show', $operateurformateur->id) }}"
                                                                    class="btn btn-primary btn-sm" title="voir détails"><i
                                                                        class="bi bi-eye"></i></a>
                                                                <div class="filter">
                                                                    <a class="icon" href="#"
                                                                        data-bs-toggle="dropdown"><i
                                                                            class="bi bi-three-dots"></i></a>
                                                                    <ul
                                                                        class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                                                        <button class="dropdown-item btn btn-sm mx-1"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#EditoperateurformateurModal{{ $operateurformateur->id }}">Modifier
                                                                        </button>
                                                                        <form
                                                                            action="{{ route('operateurformateurs.destroy', $operateurformateur->id) }}"
                                                                            method="post">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="submit"
                                                                                class="dropdown-item show_confirm"
                                                                                title="Supprimer">Supprimer</button>
                                                                        </form>
                                                                    </ul>
                                                                </div>
                                                            </span>
                                                        </td> --}}
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </form>
                                    </div>
                                </div>

                                <div class="tab-content pt-2">
                                    {{-- Début Edition --}}
                                    <div class="tab-pane fade profile-overview files" id="files">

                                        {{-- Fichiers --}}
                                        @include('operateurs.files')
                                    </div>
                                </div>


                                {{-- Détail Observations --}}
                                <div class="tab-content">
                                    <div class="tab-pane fade profile-overview pt-2" id="observations-overview">
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
                                                        data-bs-toggle="modal" data-bs-target="#addobservations"
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

                                <div class="tab-content pt-2">
                                    <div class="tab-pane fade profile-overview" id="localites-overview">
                                        <form method="post" action="#" enctype="multipart/form-data" class="row g-3">
                                            @csrf
                                            @method('PUT')
                                            {{-- <div class="d-flex justify-content-between align-items-center">
                                                <h5 class="card-title">LOCALITES</h5>
                                                @can('devenir-operateur-agrement-ouvert')
                                                    @can('agrement-visible-par-op')
                                                        <h5 class="card-title">
                                                            <a href="{{ route('showLocalite', ['id' => $operateur?->id]) }}"
                                                                class="btn btn-outline-primary float-end btn-rounded btn-sm"
                                                                target="_blank">
                                                                <i class="bi bi-plus" title="Ajouter, Modifier, Supprimer"></i> </a>
                                                        </h5>
                                                    @endcan
                                                @endcan
                                            </div> --}}
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <h4 class="mb-0 fw-bold text-uppercase text-success">
                                                    <i class="bi bi-geo-alt-fill me-2"></i> Localités
                                                </h4>

                                                @can('devenir-operateur-agrement-ouvert')
                                                    @can('agrement-visible-par-op')
                                                        <a href="{{ route('showLocalite', ['uuid' => $operateur?->uuid]) }}"
                                                            class="btn btn-sm btn-success rounded-pill shadow-sm d-flex align-items-center gap-2"
                                                            target="_blank" title="Ajouter, Modifier, Supprimer des localités">
                                                            <i class="bi bi-plus-circle-fill fs-5"></i>
                                                            Gérer les localités
                                                        </a>
                                                    @endcan
                                                @endcan
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
                                                    @foreach ($operateur?->operateurlocalites as $operateurlocalite)
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

                                <div class="tab-content pt-2">
                                    <div class="tab-pane fade profile-overview" id="details-overview">
                                        <form method="post" action="#" enctype="multipart/form-data" class="row g-3">

                                            <div class="col-12 mb-2 pt-3">
                                                <div class="label">Dénomination</div>
                                                <div>{{ $operateur?->user?->display_operateur }}</div>
                                            </div>
                                            {{-- <div class="col-12 col-md-4 mb-0">
                                            <div class="label">Sigle</div>
                                            <div>{{ $operateur?->user?->username }}</div>
                                        </div> --}}
                                            <div class="col-12 col-md-4 mb-0">
                                                <div class="label">Région</div>
                                                <div>{{ $operateur?->region?->nom }}</div>
                                            </div>

                                            @if (!empty($operateur?->departement?->nom))
                                                <div class="col-12 col-md-4 mb-0">
                                                    <div class="label">Département</div>
                                                    <div>{{ $operateur?->departement?->nom }}</div>
                                                </div>
                                            @endif

                                            <div class="col-12 col-md-4 mb-0">
                                                <div class="label">Numéro agrément</div>
                                                <div>{{ $operateur?->numero_agrement }}</div>
                                            </div>
                                            <div class="col-12 col-md-4 mb-0">
                                                <div class="label">Adresse email</div>
                                                <div><a
                                                        href="mailto:{{ $operateur?->user?->email }}">{{ $operateur?->user?->email }}</a>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-4 mb-0">
                                                <div class="label">Téléphone fixe</div>
                                                <div><a
                                                        href="tel:+221{{ $operateur?->user?->fixe }}">{{ $operateur?->user?->fixe }}</a>
                                                </div>
                                            </div>
                                            @if (!empty($operateur?->user?->bp))
                                                <div class="col-12 col-md-4 mb-0">
                                                    <div class="label">Boite postale</div>
                                                    <div>{{ $operateur?->user?->bp }}</div>
                                                </div>
                                            @endif
                                            @if (!empty($operateur?->user?->categorie))
                                                <div class="col-12 col-md-4 mb-0">
                                                    <div class="label">Catégorie</div>
                                                    <div>{{ $operateur?->user?->categorie }}</div>
                                                </div>
                                            @endif

                                            @if (!empty($operateur?->statut))
                                                <div class="col-12 col-md-4 mb-0">
                                                    <div class="label">Statut juridique</div>
                                                    <div>{{ $operateur?->statut }}</div>
                                                </div>
                                            @endif

                                            @if (!empty($operateur?->autre_statut))
                                                <div class="col-12 col-md-4 mb-0">
                                                    <div class="label">Autre statut</div>
                                                    <div>{{ $operateur?->autre_statut }}</div>
                                                </div>
                                            @endif

                                            @if (!empty($operateur?->user?->adresse))
                                                <div class="col-12 col-md-4 mb-0">
                                                    <div class="label">Adrese</div>
                                                    <div>{{ $operateur?->user?->adresse }}</div>
                                                </div>
                                            @endif

                                            @if (!empty($operateur?->user?->rccm))
                                                <div class="col-12 col-md-4 mb-0">
                                                    <div class="label">RCCM/Ninea</div>
                                                    <div>{{ $operateur?->user?->rccm }}</div>
                                                </div>
                                            @endif

                                            @if (!empty($operateur?->user?->ninea))
                                                <div class="col-12 col-md-4 mb-0">
                                                    <div class="label">N° RCCM/Ninea</div>
                                                    <div>{{ $operateur?->user?->ninea }}</div>
                                                </div>
                                            @endif

                                            {{-- @if (!empty($operateur?->debut_quitus))
                                                <div class="col-12 col-md-4 mb-0">
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

                                            @if (!empty($operateur?->debut_quitus))
                                                <div class="col-12 col-md-4">
                                                    <div class="label">Durée quitus</div>
                                                    <div>
                                                        {{ $operateur?->debut_quitus?->diffForHumans(['parts' => 3, 'join' => ', ']) }}
                                                    </div>
                                                </div>
                                            @endif

                                            <h5 class="card-title">Personne morale</h5>

                                            @if (!empty($operateur?->user?->civilite))
                                                <div class="col-12 col-md-4">
                                                    <div class="label">Civilité</div>
                                                    <div>{{ $operateur?->user?->civilite }}</div>
                                                </div>
                                            @endif

                                            @if (!empty($operateur?->user?->firstname))
                                                <div class="col-12 col-md-4 mb-0">
                                                    <div class="label">Prénom</div>
                                                    <div>{{ $operateur?->user?->firstname }}</div>
                                                </div>
                                            @endif

                                            @if (!empty($operateur?->user?->name))
                                                <div class="col-12 col-md-4 mb-0">
                                                    <div class="label">Nom</div>
                                                    <div>{{ $operateur?->user?->name }}</div>
                                                </div>
                                            @endif

                                            @if (!empty($operateur?->user?->email_responsable))
                                                <div class="col-12 col-md-4 mb-0">
                                                    <div class="label">Email</div>
                                                    <div><a
                                                            href="mailto:{{ $operateur?->user?->email_responsable }}">{{ $operateur?->user?->email_responsable }}</a>
                                                    </div>
                                                </div>
                                            @endif

                                            @if (!empty($operateur?->user?->fonction_responsable))
                                                <div class="col-12 col-md-4 mb-0">
                                                    <div class="label">Téléphone</div>
                                                    <div><a
                                                            href="tel:+221{{ $operateur?->user?->fonction_responsable }}">{{ $operateur?->user?->telephone }}</a>
                                                    </div>
                                                </div>
                                            @endif

                                            @if (!empty($operateur?->user?->fonction_responsable))
                                                <div class="col-12 col-md-4 mb-0">
                                                    <div class="label">Fonction</div>
                                                    <div>{{ $operateur?->user?->fonction_responsable }}
                                                    </div>
                                                </div>
                                            @endif

                                            @can('operateur-update')
                                                <div class="text-center">
                                                    <a href="{{ route('operateurs.edit', $operateur) }}"
                                                        class="btn btn-outline-primary btn-sm" title="Modifier">Modifier cet
                                                        opérateur</a>
                                                </div>
                                            @endcan
                                        </form>
                                    </div>
                                </div>

                                {{-- Détail Modules --}}
                                {{-- class show et active pour l'affichage par défaut --}}
                                <div class="tab-content pt-2">
                                    <div class="tab-pane show active fade profile-overview" id="module-overview">
                                        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
                                            <h5 class="mb-0 text-uppercase fw-bold text-primary">
                                                <i class="bi bi-briefcase-fill me-2"></i> Modules de formation
                                            </h5>
                                        </div>

                                        <p class="small fst-italic">
                                            <small>{{ __('Le nombre de modules est limité à cinq(05)') }}</small>
                                            <small>
                                                {{ __(' sauf pour les établissements publics ') }}</small>
                                        </p>
                                        @can('agrement-visible-par-op')
                                            <form method="post" action="{{ route('operateurmodules.store') }}"
                                                enctype="multipart/form-data" class="row g-3">
                                                @csrf
                                                @can('devenir-operateur-agrement-ouvert')
                                                    <div class="col-12 mb-0">
                                                        <div class="row g-3">
                                                            <input type="hidden" name="operateur" value="{{ $operateur?->id }}">

                                                            <!-- DOMAINE & MODULE côte à côte -->
                                                            <div class="col-md-6">
                                                                <label for="domaine" class="form-label">DOMAINE <span
                                                                        class="text-danger">*</span></label>
                                                                <select name="domaine" id="select-field-civilite"
                                                                    class="form-select form-select-sm @error('domaine') is-invalid @enderror">
                                                                    <option value="">-- Sélectionnez un domaine --</option>
                                                                    @foreach ($domaines as $domaine)
                                                                        <option value="{{ $domaine->name }}">{{ $domaine->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                @error('domaine')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>

                                                            <div class="col-md-6">
                                                                <label for="module" class="form-label">MODULE OU SPECIALITE <span
                                                                        class="text-danger">*</span></label>
                                                                <input type="text" name="module" id="module_operateur"
                                                                    class="form-control form-control-sm @error('module') is-invalid @enderror"
                                                                    placeholder="Module ou spécialité" />
                                                                <div id="moduleList"></div>
                                                            </div>

                                                            <!-- NIVEAU QUALIFICATION & EMPLOI OU METIER côte à côte -->
                                                            <div class="col-md-6">
                                                                <label for="niveau_qualification" class="form-label">TITRE OU NIVEAU
                                                                    DE
                                                                    QUALIFICATION <span class="text-danger">*</span></label>
                                                                <select name="niveau_qualification"
                                                                    class="form-select form-select-sm @error('niveau_qualification') is-invalid @enderror"
                                                                    aria-label="Select" id="select-field-niveau_qualification"
                                                                    data-placeholder="Choisir qualification">
                                                                    <option value="">{{ old('niveau_qualification') }}</option>
                                                                    <option value="Pré-qualification">Pré-qualification</option>
                                                                    <option value="Renforcement de capacités">Renforcement de capacités
                                                                    </option>
                                                                    <option value="Qualification">Qualification</option>
                                                                </select>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <label for="categorie" class="form-label">CATEGORIE
                                                                    PROFESSIONNELLE</label>
                                                                <input type="text" name="categorie"
                                                                    placeholder="Niveau de qualification"
                                                                    class="form-control form-control-sm @error('categorie') is-invalid @enderror" />

                                                                <p class="small fst-italic mb-0"
                                                                    style="white-space: normal; word-wrap: break-word;">
                                                                    {{ __("Préciser la catégorie professionnelle, l'emploi ou le métier correspondant lorsqu'il s'agit d'une pré-qualification ou qualification") }}
                                                                </p>
                                                            </div>

                                                            <!-- Bouton -->
                                                            <div class="col-12 text-center">
                                                                <button type="submit"
                                                                    class="btn btn-outline-success btn-sm">Enregistrer</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endcan

                                            </form><!-- End module -->
                                        @endcan

                                        <div class="col-12 mb-0">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h5 class="card-title">DOMAINES DE COMPETENCES OU PROGRAMMES DE FORMATION</h5>
                                                <span class="card-title d-flex align-items-baseline">Statut
                                                    :&nbsp;
                                                    <span class="{{ $operateur?->statut_agrement }} text-white btn-sm">
                                                        {{ $operateur?->statut_agrement }}</span>
                                                    @can('agrement-view')
                                                        <div class="filter">
                                                            <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                                                    class="bi bi-three-dots"></i></a>
                                                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
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
                                                                        <form action="{{ route('lettreOperateur') }}" method="post"
                                                                            target="_blank">
                                                                            @csrf
                                                                            <input type="hidden" name="id"
                                                                                value="{{ $operateur?->id }}">
                                                                            <button class="btn btn-sm mx-1">Lettre agrément</button>
                                                                        </form>
                                                                    </li>
                                                                @endcan
                                                            </ul>
                                                        </div>
                                                    @endcan
                                                </span>
                                            </div>
                                            <div class="row g-3">
                                                <table
                                                    class="table table-bordered table-hover datatables align-middle justify-content-center"
                                                    id="table-operateurModules">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col" width='2%'>N°</th>
                                                            <th scope="col">DOMAINE</th>
                                                            <th scope="col">MODULE</th>
                                                            <th scope="col">NIVEAU DE QUALIFICATION</th>
                                                            <th scope="col">CATEGORIE PROFESSIONNELLE</th>
                                                            <th class="text-center">STATUT</th>
                                                            @can('devenir-operateur-agrement-show')
                                                                <th class="text-center" width='2%'>ACTIONS
                                                                </th>
                                                            @endcan
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php $i = 1; ?>
                                                        @foreach ($operateur?->operateurmodules as $operateurmodule)
                                                            <tr>
                                                                <td style="text-align: center;">{{ $i++ }}</td>
                                                                <td>{{ $operateurmodule?->domaine }}</td>
                                                                <td>{{ $operateurmodule?->module }}</td>
                                                                <td>{{ $operateurmodule?->niveau_qualification }}</td>
                                                                <td>{{ $operateurmodule?->categorie }}</td>
                                                                <td style="text-align: center;">
                                                                    <span
                                                                        class="{{ $operateurmodule?->statut }}">{{ $operateurmodule?->statut }}</span>
                                                                </td>
                                                                @can('devenir-operateur-agrement-show')
                                                                    <td style="text-align: center;">
                                                                        <div class="d-flex justify-content-center">
                                                                            @can('devenir-operateur-agrement-update')
                                                                                <button class="btn btn-warning text-white btn-sm me-1"
                                                                                    data-bs-toggle="modal"
                                                                                    data-bs-target="#EditOperateurmoduleModal{{ $operateurmodule->id }}"
                                                                                    title="Modifier">
                                                                                    <i class="bi bi-pencil-square"></i>
                                                                                </button>
                                                                            @endcan

                                                                            @can('devenir-operateur-agrement-delete')
                                                                                <form
                                                                                    action="{{ route('operateurmodules.destroy', $operateurmodule) }}"
                                                                                    method="POST">
                                                                                    @csrf
                                                                                    @method('DELETE')
                                                                                    <button type="submit"
                                                                                        class="btn btn-danger btn-sm show_confirm"
                                                                                        title="Supprimer">
                                                                                        <i class="bi bi-trash"></i>
                                                                                    </button>
                                                                                </form>
                                                                            @endcan
                                                                        </div>
                                                                    </td>
                                                                @endcan
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            {{-- </form> --}}
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-content pt-2">
                                    <div class="tab-pane fade profile-overview pt-0" id="formation-overview">
                                        <h5 class="card-title">FORMATIONS</h5>
                                        <table
                                            class="table table-bordered table-hover datatables  align-middle justify-content-center"
                                            id="table-formations">
                                            <thead>
                                                <tr>
                                                    <th width="5%">Convention</th>
                                                    <th width="8%">Type</th>
                                                    <th>Intitulé formation</th>
                                                    <th>Localité</th>
                                                    <th>Modules</th>
                                                    <th width="5%">Effectif</th>
                                                    <th width="5%">Statut</th>
                                                    @can('formation-show')
                                                        <th class="text-center"><i class="bi bi-gear"></i></th>
                                                    @endcan
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $i = 1; ?>
                                                @foreach ($operateur?->formations as $formation)
                                                    <tr>
                                                        <td>{{ $formation?->numero_convention }}</td>
                                                        <td>{{ $formation->types_formation?->name }}</td>
                                                        <td>{{ $formation?->intitule }}</td>
                                                        <td>{{ $formation->departement?->region?->nom }}</td>
                                                        @if (!empty($formation?->collectivemodule?->module))
                                                            <td>{{ $formation->collectivemodule->module }}</td>
                                                            <td class="text-center">
                                                                <span
                                                                    class="text-primary fw-bold">{{ $formation?->listecollectives?->count() ?? 0 }}</span>
                                                            </td>
                                                        @elseif(!empty($formation?->module?->name))
                                                            <td>{{ $formation->module->name }}</td>
                                                            <td class="text-center">
                                                                <span
                                                                    class="text-primary fw-bold">{{ $formation?->individuelles?->count() ?? 0 }}</span>
                                                            </td>
                                                        @endif
                                                        <td><a href="#">
                                                                <span
                                                                    class="{{ $formation?->statut }}">{{ $formation?->statut }}</span>
                                                            </a>
                                                        </td>
                                                        @can('formation-show')
                                                            <td>
                                                                <span class="d-flex align-items-baseline"><a
                                                                        href="{{ route('formations.show', $formation) }}"
                                                                        class="btn btn-primary btn-sm" title="voir détails"><i
                                                                            class="bi bi-eye"></i></a>
                                                                    <div class="filter">
                                                                        <a class="icon" href="#"
                                                                            data-bs-toggle="dropdown"><i
                                                                                class="bi bi-three-dots"></i></a>
                                                                        <ul
                                                                            class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                                                            <li><a class="dropdown-item btn btn-sm"
                                                                                    href="{{ route('formations.edit', $formation) }}"
                                                                                    class="mx-1" title="Modifier"><i
                                                                                        class="bi bi-pencil"></i>Modifier</a>
                                                                            </li>
                                                                            <li>
                                                                                <form
                                                                                    action="{{ route('formations.destroy', $formation) }}"
                                                                                    method="post">
                                                                                    @csrf
                                                                                    @method('DELETE')
                                                                                    <button type="submit"
                                                                                        class="dropdown-item show_confirm"
                                                                                        title="Supprimer"><i
                                                                                            class="bi bi-trash"></i>Supprimer</button>
                                                                                </form>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </span>
                                                            </td>
                                                        @endcan
                                                    </tr>
                                                @endforeach

                                            </tbody>
                                        </table>
                                        <!-- End Table with stripped rows -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include('operateurs.modal')
        </section>
    @endcan
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
