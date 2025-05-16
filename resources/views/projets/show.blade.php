@extends('layout.user-layout')
@section('title', 'ONFP | ' . $projet?->sigle)
@section('space-work')

    <section class="section dashboard">
        <div class="row">
            <!-- Left side columns -->

        </div>
    </section>

    <section
        class="section profile min-vh-0 d-flex flex-column align-items-center justify-content-center py-0 section profile">
        <div class="container-fluid">
            <div class="pagetitle">
                {{-- <h1>Data Tables</h1> --}}
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Accueil</a></li>
                        <li class="breadcrumb-item">Tables</li>
                        <li class="breadcrumb-item active">{{ $projet?->sigle }}</li>
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
                                    <span class="nav-link"><a href="{{ route('projets.index', $projet?->id) }}"
                                            class="btn btn-secondary btn-sm" title="retour"><i
                                                class="bi bi-arrow-counterclockwise"></i></a>
                                    </span>
                                </li>

                                <li class="nav-item">
                                    <button class="nav-link" data-bs-toggle="tab"
                                        data-bs-target="#profile-overview">{{ $projet->sigle }}
                                    </button>
                                </li>

                                <li class="nav-item">
                                    <button class="nav-link active" data-bs-toggle="tab"
                                        data-bs-target="#modules-overview">Modules</button>
                                </li>

                                @if (auth()->user()->hasRole(['super-admin', 'admin', 'DIOF', 'Ingenieur', 'ADIOF']))
                                    <li class="nav-item">
                                        <button class="nav-link" data-bs-toggle="tab"
                                            data-bs-target="#localites-overview">{{ $projet?->type_localite }}</button>
                                    </li>
                                @endif

                                {{-- <li class="nav-item">
                                    <button class="nav-link">
                                        <a style="text-decoration: none; color: black"
                                            href="{{ route('projetsBeneficiaire', $projet?->uuid) }}" title="voir"
                                            target="_blank">Liste</a>
                                    </button>
                                </li> --}}

                            </ul>

                            <div class="tab-content">

                                <div class="tab-pane fade profile-overview" id="profile-overview">
                                    <form method="post" action="#" enctype="multipart/form-data" class="row g-3">
                                        @csrf
                                        @method('PUT')
                                        <h5 class="card-title">À propos</h5>
                                        @if (!empty($projet?->description))
                                            <div class="label">Description</div>
                                            <p class="fst-italic">{{ $projet?->description }}</p>
                                        @endif

                                        <div class="col-12 col-md-12 col-lg-12 col-sm-12 col-xs-12 col-xxl-12">
                                            <div class="label">Partenaire</div>
                                            <div class="pt-2">{{ $projet?->name }}</div>
                                        </div>

                                        <div class="col-12 col-md-3 col-lg-3 col-sm-12 col-xs-12 col-xxl-3">
                                            <div class="label">Sigle</div>
                                            <div class="pt-2">{{ $projet?->sigle }}</div>
                                        </div>

                                        @if ($projet?->date_signature)
                                            <div class="col-12 col-md-3 col-lg-3 col-sm-12 col-xs-12 col-xxl-3">
                                                <div class="label">Date signature</div>
                                                <div class="pt-2">{{ $projet?->date_signature->format('d/m/Y') }}</div>
                                            </div>
                                        @endif
                                        @if ($projet?->budjet)
                                            <div class="col-12 col-md-3 col-lg-3 col-sm-12 col-xs-12 col-xxl-3">
                                                <div class="label">Budjet</div>
                                                <div class="pt-2">{{ number_format($projet?->budjet, 2, ',', ' ') }}
                                                </div>
                                            </div>
                                        @endif
                                        @if ($projet?->duree)
                                            <div class="col-12 col-md-3 col-lg-3 col-sm-12 col-xs-12 col-xxl-3">
                                                <div class="label">Durée</div>
                                                <div class="pt-2">{{ $projet?->duree . ' mois' }}</div>
                                            </div>
                                        @endif
                                        @if ($projet?->debut)
                                            <div class="col-12 col-md-3 col-lg-3 col-sm-12 col-xs-12 col-xxl-3">
                                                <div class="label">Date début</div>
                                                <div class="pt-2">{{ $projet?->debut->format('d/m/Y') }}</div>
                                            </div>
                                        @endif
                                        @if ($projet?->fin)
                                            <div class="col-12 col-md-3 col-lg-3 col-sm-12 col-xs-12 col-xxl-3">
                                                <div class="label">Date fin</div>
                                                <div class="pt-2">{{ $projet?->fin->format('d/m/Y') }}</div>
                                            </div>
                                        @endif
                                    </form>
                                </div>
                                <div class="tab-pane fade show active profile-overview" id="modules-overview">
                                    <div class="col-12 col-md-12 col-lg-12 col-sm-12 col-xs-12 col-xxl-12 pt-5">
                                        <div class="row">
                                            <div class="col-12 col-md-4 col-lg-2 col-sm-12 col-xs-12 col-xxl-2">
                                                <div class="card info-card revenue-card shadow-sm"
                                                    style="max-width: 220px;">
                                                    <div class="card-body p-2">
                                                        <h5 class="card-title text-truncate mb-1"
                                                            title="{{ $projet?->sigle }}" style="font-size: 1rem;">
                                                            {{ $projet?->sigle }}
                                                        </h5>
                                                        <div class="d-flex align-items-center mb-2">
                                                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-primary text-white"
                                                                style="width: 32px; height: 32px; font-size: 1.25rem;">
                                                                <i class="bi bi-people"></i>
                                                            </div>
                                                            <div class="ps-2">
                                                                <h6 class="mb-0" style="font-size: 0.9rem;">
                                                                    {{ number_format(count($individuelles), 0, '', ' ') }}</h6>
                                                                <span class="text-muted small">demandeurs</span>
                                                            </div>
                                                        </div>

                                                        <a href="{{ route('projets.show', $projet) }}"
                                                            class="btn btn-outline-primary btn-sm w-100 d-flex align-items-center justify-content-center py-1"
                                                            style="font-size: 0.85rem; gap: 6px;">
                                                            Voir plus <i class="bi bi-arrow-right-short"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Sales Card -->
                                            @foreach ($groupes as $statut => $items)
                                                <div class="col-12 col-md-4 col-lg-2 col-sm-12 col-xs-12 col-xxl-2">
                                                    <div class="card info-card sales-card shadow-sm"
                                                        style="max-width: 220px;">
                                                        <div class="card-body p-2">
                                                            <h5 class="card-title text-truncate mb-1"
                                                                title="{{ $statut }}" style="font-size: 1rem;">
                                                                {{ $statut }}
                                                            </h5>
                                                            <div class="d-flex align-items-center mb-2">
                                                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-primary text-white"
                                                                    style="width: 32px; height: 32px; font-size: 1.25rem;">
                                                                    <i class="bi bi-people"></i>
                                                                </div>
                                                                <div class="ps-2">
                                                                    <h6 class="mb-0" style="font-size: 0.9rem;">
                                                                        {{ number_format($items->count(), 0, '', ' ') }}</h6>
                                                                    <span class="text-muted small">demandeurs</span>
                                                                </div>
                                                            </div>
                                                            <a href="{{ route('projets.parStatut', ['statut' => $statut, 'projetid' => $projet->id]) }}" target="_blank"
                                                                class="btn btn-outline-primary btn-sm w-100 d-flex align-items-center justify-content-center py-1"
                                                                style="font-size: 0.85rem; gap: 6px;">
                                                                Voir plus <i class="bi bi-arrow-right-short"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <!-- Bouton Ajouter un module aligné en haut à droite -->
                                    <div class="d-flex justify-content-end m-3">
                                        <button type="button"
                                            class="btn btn-primary btn-sm rounded-pill px-4 shadow-sm d-flex align-items-center gap-2"
                                            data-bs-toggle="modal" data-bs-target="#AddModuleModal">
                                            <i class="bi bi-plus-circle-fill"></i>
                                            Ajouter un module
                                        </button>
                                    </div>

                                    <!-- Section Infos -->
                                    <div
                                        class="d-flex flex-wrap justify-content-between align-items-center mb-4 p-3 bg-light rounded shadow-sm">
                                        <h5 class="mb-0 text-primary">
                                            <i class="bi bi-list-task me-2"></i> Liste des modules
                                        </h5>

                                        {{-- <h6 class="mb-0">
                                            <span class="badge bg-info text-white fs-6">
                                                <i class="bi bi-people-fill me-1"></i>
                                                Demandes reçues : {{ count($projet?->individuelles) }}
                                            </span>
                                        </h6> --}}

                                        @if ($projet?->effectif)
                                            <h6 class="mb-0">
                                                <span class="badge bg-success fs-6">
                                                    <i class="bi bi-bar-chart-line-fill me-1"></i>
                                                    Effectif prévu : {{ $projet?->effectif }}
                                                </span>
                                            </h6>
                                        @endif
                                    </div>

                                    <!-- Tableau des modules -->
                                    <div class="row g-3">
                                        <table class="table datatables align-middle table-borderless"
                                            id="table-operateurModules">
                                            <thead>
                                                <tr>
                                                    {{-- <th class="text-center" width="5%">N°</th> --}}
                                                    <th>Module</th>
                                                    <th>Domaines</th>
                                                    <th>Statut</th>
                                                    <th class="text-center">Reçues</th>
                                                    @if ($projet?->effectif)
                                                        <th class="text-center" width="10%">Besoin</th>
                                                    @endif
                                                    @if (auth()->user()->hasRole(['super-admin', 'admin', 'DIOF', 'Ingenieur', 'ADIOF']))
                                                        <th width="5%" class="text-center">
                                                            <i class="bi bi-gear"></i>
                                                        </th>
                                                    @endif
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $i = 1; @endphp
                                                @foreach ($projet?->projetmodules as $projetmodule)
                                                    <tr>
                                                        {{-- <td class="text-center">{{ $i++ }}</td> --}}
                                                        <td>{{ $projetmodule?->module }}</td>
                                                        <td>{{ $projetmodule?->domaine }}</td>
                                                        <td>
                                                            @if ($projetmodule?->statut == 'ouvert')
                                                                <span class="badge bg-success rounded-pill">
                                                                    {{ $projetmodule?->statut }}
                                                                </span>
                                                            @elseif ($projetmodule?->statut == 'fermé')
                                                                <span class="badge bg-danger rounded-pill">
                                                                    {{ $projetmodule?->statut }}
                                                                </span>
                                                            @elseif ($projetmodule?->statut == 'terminé')
                                                                <span class="badge bg-primary rounded-pill">
                                                                    {{ $projetmodule?->statut }}
                                                                </span>
                                                            @else
                                                                <span class="badge bg-default rounded-pill">
                                                                    {{ $projetmodule?->statut }}
                                                                </span>
                                                            @endif
                                                        </td>
                                                        @php
                                                            $count = $projet
                                                                ->individuelles()
                                                                ->whereHas('module', function ($query) use (
                                                                    $projetmodule,
                                                                ) {
                                                                    $query->where('name', $projetmodule->module);
                                                                })
                                                                ->count();
                                                        @endphp

                                                        <td class="text-center">
                                                            @if ($count == 0)
                                                                <span class="badge bg-danger rounded-pill">
                                                                    0
                                                                </span>
                                                            @elseif ($count > 0 && $count <= 19)
                                                                <span class="badge bg-warning text-dark rounded-pill">
                                                                    {{ $count }}
                                                                </span>
                                                            @else
                                                                <span class="badge bg-success rounded-pill">
                                                                    {{ $count }}
                                                                </span>
                                                            @endif
                                                        </td>
                                                        @if ($projet?->effectif)
                                                            <td class="text-center">{{ $projetmodule?->effectif }}
                                                            </td>
                                                        @endif
                                                        @if (auth()->user()->hasRole(['super-admin', 'admin', 'DIOF', 'Ingenieur', 'ADIOF']))
                                                            <td style="text-align: center;">
                                                                <span
                                                                    class="d-flex align-items-baseline justify-content-center gap-2">
                                                                    <!-- Bouton "Voir détails" -->
                                                                    <a href="{{ route('projetmodules.show', $projetmodule) }}"
                                                                        class="btn btn-primary btn-sm shadow-sm rounded-pill transition-all hover:shadow-lg"
                                                                        title="Voir détails">
                                                                        <i class="bi bi-eye"></i>
                                                                    </a>

                                                                    <!-- Dropdown pour actions -->
                                                                    <div class="dropdown">
                                                                        <a class="btn btn-light btn-sm shadow-sm rounded-pill transition-all hover:shadow-lg"
                                                                            href="#" role="button"
                                                                            data-bs-toggle="dropdown"
                                                                            aria-expanded="false">
                                                                            <i class="bi bi-three-dots"></i>
                                                                        </a>
                                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                                            <!-- Modifier -->
                                                                            <li>
                                                                                {{-- <button class="dropdown-item"
                                                                                    data-bs-toggle="modal"
                                                                                    data-bs-target="#EditModuleModal{{ $projetmodule?->id }}">
                                                                                    <i class="bi bi-pencil-fill me-2"></i>
                                                                                    Modifier
                                                                                </button> --}}
                                                                                <a href="{{ route('projetmodules.edit', $projetmodule) }}"
                                                                                    class="dropdown-item">
                                                                                    <i class="bi bi-pencil-fill me-2"></i>
                                                                                    Modifier
                                                                                </a>
                                                                            </li>
                                                                            <!-- Supprimer -->
                                                                            <li>
                                                                                <form
                                                                                    action="{{ route('projetmodules.destroy', $projetmodule) }}"
                                                                                    method="post">
                                                                                    @csrf
                                                                                    @method('DELETE')
                                                                                    <button type="submit"
                                                                                        class="dropdown-item text-danger show_confirm">
                                                                                        <i
                                                                                            class="bi bi-trash-fill me-2"></i>
                                                                                        Supprimer
                                                                                    </button>
                                                                                </form>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </span>
                                                            </td>
                                                        @endif
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    {{-- Ancienne section formulaire globale --}}
                                    {{-- </form> --}}
                                </div>

                                <div class="tab-pane fade profile-overview" id="localites-overview">

                                    <div class="col-12 col-md-12 col-lg-12 col-sm-12 col-xs-12 col-xxl-12">
                                        <div class="d-flex justify-content-between align-items-center mt-3">
                                            <h5 class="card-title">Liste des {{ $projet?->type_localite }}</h5>
                                            @if (auth()->user()->hasRole(['super-admin', 'admin', 'DIOF', 'Ingenieur', 'ADIOF']))
                                                <div class="pt-1">
                                                    <div class="d-flex justify-content-end m-3">
                                                        <button type="button"
                                                            class="btn btn-primary btn-sm rounded-pill px-4 shadow-sm d-flex align-items-center gap-2"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#AddprojetlocaliteModal">
                                                            <i class="bi bi-plus-circle-fill"></i>
                                                            Ajouter {{ strtolower($projet?->type_localite) }}
                                                        </button>
                                                    </div>
                                                </div>
                                            @endcan
                                    </div>

                                    <div class="row g-3">
                                        <table class="table datatables align-middle justify-content-center"
                                            id="table-projetlocalites">
                                            <thead>
                                                <tr>
                                                    <th width="5%" class="text-center" scope="col">N°</th>
                                                    <th>{{ $projet?->type_localite }}</th>
                                                    @if ($projet?->effectif)
                                                        <th class="text-center" scope="col">Besoin</th>
                                                    @endif
                                                    <th class="text-center" scope="col">Reçues</th>
                                                    <th width="5%" class="text-center" scope="col">#</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $i = 1; ?>
                                                @foreach ($projetlocalites as $projetlocalite)
                                                    <tr>
                                                        <td style="text-align: center;">{{ $i++ }}</td>
                                                        <td>{{ $projetlocalite?->localite }}</td>
                                                        @if ($projet?->effectif)
                                                            <td class="text-center">
                                                                @if ($projetlocalite?->effectif > 0)
                                                                    <span class="badge bg-success rounded-pill">
                                                                        {{ $projetlocalite?->effectif }}
                                                                    </span>
                                                                @else
                                                                    <span class="badge bg-danger rounded-pill">
                                                                        0
                                                                    </span>
                                                                @endif
                                                            </td>
                                                        @endif
                                                        @php
                                                            $count = $projet
                                                                ->individuelles()
                                                                ->whereHas(
                                                                    strtolower($projet->type_localite), // relation dynamique
                                                                    function ($query) use ($projetlocalite) {
                                                                        $query->where('nom', $projetlocalite->localite);
                                                                    },
                                                                )
                                                                ->count();
                                                        @endphp

                                                        <td class="text-center">
                                                            @if ($count == 0)
                                                                <span class="badge bg-danger rounded-pill">
                                                                    0
                                                                </span>
                                                            @elseif ($count > 0 && $count <= 19)
                                                                <span class="badge bg-warning text-dark rounded-pill">
                                                                    {{ $count }}
                                                                </span>
                                                            @else
                                                                <span class="badge bg-success rounded-pill">
                                                                    {{ $count }}
                                                                </span>
                                                            @endif
                                                        </td>
                                                        <td class="text-center">
                                                            <div class="btn-group align-items-center">
                                                                <a href="{{ route('projetlocalites.show', $projetlocalite) }}"
                                                                    class="btn btn-sm btn-outline-warning"
                                                                    title="Voir détails">
                                                                    <i class="bi bi-eye"></i>
                                                                </a>
                                                                @if (auth()->user()->hasRole(['super-admin', 'admin', 'DIOF', 'Ingenieur', 'ADIOF']))
                                                                    <button type="button"
                                                                        class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                                                        data-bs-toggle="dropdown"
                                                                        aria-expanded="false">
                                                                        <i class="bi bi-gear"></i>
                                                                    </button>
                                                                    <ul class="dropdown-menu">
                                                                        <li>
                                                                            <button type="button"
                                                                                class="dropdown-item"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#EditprojetlocaliteModal{{ $projetlocalite?->id }}">
                                                                                <i
                                                                                    class="bi bi-pencil-square me-2"></i>
                                                                                Modifier
                                                                            </button>
                                                                        </li>
                                                                        <li>
                                                                            <form
                                                                                action="{{ route('projetlocalites.destroy', $projetlocalite) }}"
                                                                                method="POST" class="d-inline">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                                <button type="submit"
                                                                                    class="dropdown-item show_confirm">
                                                                                    <i class="bi bi-trash3 me-2"></i>
                                                                                    Supprimer
                                                                                </button>
                                                                            </form>
                                                                        </li>
                                                                    </ul>
                                                                @endif
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
                        <!-- End Bordered Tabs -->
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="AddModuleModal" tabindex="-1" aria-labelledby="AddModuleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg"><!-- modal-lg pour un format adapté -->
                <div class="modal-content rounded-4 shadow-sm border-0">
                    <div class="modal-header bg-primary text-white rounded-top-4">
                        <h5 class="modal-title" id="AddModuleModalLabel">
                            <i class="bi bi-plus-circle me-2"></i> Ajouter un nouveau module
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Fermer"></button>
                    </div>
                    <form method="post" action="{{ url('projetmodules') }}" enctype="multipart/form-data"
                        class="p-3">
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" name="projet" value="{{ $projet?->id }}">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label for="module_name" class="form-label">Module<span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="module" id="module_name"
                                        class="form-control form-control-sm" placeholder="Nom du module" required>
                                    <div id="countryList"></div>
                                </div>
                                <div class="col-md-6">
                                    <label for="domaine" class="form-label">Domaine<span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="domaine" id="domaine"
                                        class="form-control form-control-sm" placeholder="Entrer un domaine" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="effectif" class="form-label">Effectif prévu</label>
                                    <input type="number" min="0" name="effectif" id="effectif"
                                        class="form-control form-control-sm" placeholder="Effectif prévu">
                                </div>
                                <div class="col-md-12">
                                    <label for="description" class="form-label">Description du module</label>
                                    <textarea name="description" id="description" rows="4" class="form-control form-control-sm"
                                        placeholder="Décrire brièvement le module..."></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            @if (auth()->user()->hasRole(['super-admin', 'admin', 'DIOF', 'Ingenieur', 'ADIOF']))
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="bi bi-check-circle me-1"></i> Enregistrer
                                </button>
                            @endif
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                                <i class="bi bi-x-circle me-1"></i> Fermer
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
        <!-- End Modal -->
    </div>

    <!-- Add projetlocalite -->
    <div class="modal fade" id="AddprojetlocaliteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" action="{{ url('projetlocalites') }}" enctype="multipart/form-data"
                    class="row g-3">
                    @csrf
                    <div class="card-header text-center bg-gradient-default">
                        <h1 class="h4 text-black mb-0">Ajouter {{ $projet->type_localite }}</h1>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <input name="projet" value="{{ $projet?->id }}" type="hidden">
                            <input name="type_localite" value="{{ $projet->type_localite }}" type="hidden">

                            <div class="col-12 col-md-12 col-lg-12 col-sm-12 col-xs-12 col-xxl-12">
                                <label for="localite" class="form-label">Localité<span
                                        class="text-danger mx-1">*</span></label>
                                <input type="text" name="localite" value="{{ old('localite') }}"
                                    class="form-control form-control-sm @error('localite') is-invalid @enderror"
                                    id="localite" placeholder="localite">
                                @error('localite')
                                    <span class="invalid-feedback" role="alert">
                                        <div>{{ $message }}</div>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-12 col-md-12 col-lg-12 col-sm-12 col-xs-12 col-xxl-12">
                                <label for="effectif" class="form-label">Effectif</label>
                                <input type="number" min="0" name="effectif" value="{{ old('effectif') }}"
                                    class="form-control form-control-sm @error('effectif') is-invalid @enderror"
                                    id="effectif" placeholder="Effectif">
                                @error('effectif')
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
    <!-- End Add projetlocalite-->
    @foreach ($projetlocalites as $projetlocalite)
        <div class="modal fade" id="EditprojetlocaliteModal{{ $projetlocalite?->id }}" tabindex="-1"
            aria-labelledby="EditprojetlocaliteModalLabel{{ $projetlocalite?->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-3 shadow-sm">
                    <form method="POST" action="{{ route('projetlocalites.update', $projetlocalite) }}"
                        class="p-3">
                        @csrf
                        @method('PATCH')

                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title" id="EditprojetlocaliteModalLabel{{ $projetlocalite?->id }}">
                                Modifier : {{ $projetlocalite->localite }}
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <input type="hidden" name="id" value="{{ $projetlocalite?->projet->id }}">

                            <div class="mb-3">
                                <label for="localite-{{ $projetlocalite->id }}" class="form-label">Localité <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="localite" id="localite-{{ $projetlocalite->id }}"
                                    class="form-control form-control-sm @error('localite') is-invalid @enderror"
                                    value="{{ old('localite', $projetlocalite?->localite) }}"
                                    placeholder="Nom de la localité">
                                @error('localite')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="effectif-{{ $projetlocalite->id }}" class="form-label">Effectif</label>
                                <input type="number" min="0" name="effectif"
                                    id="effectif-{{ $projetlocalite->id }}"
                                    class="form-control form-control-sm @error('effectif') is-invalid @enderror"
                                    value="{{ old('effectif', $projetlocalite?->effectif) }}"
                                    placeholder="Nombre de participants">
                                @error('effectif')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                                Annuler
                            </button>
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="bi bi-save me-1"></i> Enregistrer
                            </button>
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
    new DataTable('#table-operateurModules', {
        layout: {
            topStart: {
                buttons: ['csv', 'excel', 'print'],
            }
        },
        "order": [
            [3, 'desc']
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
