@extends('layout.user-layout')
@section('title', 'ONFP | OPERATEURS')
@section('space-work')

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
    <section class="section dashboard">
        <div class="row">
            <!-- Left side columns -->
            <div class="col-12 col-md-12 col-lg-12 col-sm-12 col-xs-12 col-xxl-12">
                <div class="row">

                    <div class="col-12 col-md-4 col-lg-3 col-sm-12 col-xs-12 col-xxl-3">
                        <div class="card info-card revenue-card">
                            <a href="{{ route('operateurs.index') }}">
                                <div class="card-body">
                                    <h5 class="card-title">Demandes <span>| opérateurs</span></h5>
                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-people"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6>
                                                {{ count($operateurs) }}
                                            </h6>
                                            <span class="text-muted small pt-2 ps-1"></span>
                                            {{-- <span class="text-success small pt-1 fw-bold">{{ number_format($pourcentage_nouveau, 2, ',', ' ') .'%' }}</span> --}}
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- Sales Card -->
                    <div class="col-12 col-md-4 col-lg-3 col-sm-12 col-xs-12 col-xxl-3">
                        <div class="card info-card sales-card">
                            {{-- <div class="filter">
                                <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                            </div> --}}
                            {{-- {{ route('commissionagrements.show', $commissionagrement->id) }} --}}
                            <a href="{{ route('operateurs.agreer') }}">
                                <div class="card-body">
                                    <h5 class="card-title">Agréments <span>| en cours</span></h5>
                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-people"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6>
                                                {{ $operateur_agreer }}
                                            </h6>
                                            <span
                                                class="text-success small pt-1 fw-bold">{{ number_format($pourcentage_agreer, 2, ',', ' ') . '%' }}</span>
                                            {{-- <span class="text-muted small pt-2 ps-1">increase</span> --}}
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    {{-- <div class="col-12 col-md-4 col-lg-3 col-sm-12 col-xs-12 col-xxl-3">
                        <div class="card info-card sales-card">
                            <a href="#">
                                <div class="card-body">
                                    <h5 class="card-title">Opérateurs <span>| Nouvelle demande</span></h5>
                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-people"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6>
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 col-lg-3 col-sm-12 col-xs-12 col-xxl-3">
                        <div class="card info-card revenue-card">
                            <a href="#">
                                <div class="card-body">
                                    <h5 class="card-title">Opérateurs <span>| Renouvellement</span></h5>
                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-people"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6>
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div> --}}
                    <div class="col-12 col-md-4 col-lg-3 col-sm-12 col-xs-12 col-xxl-3">
                        <div class="card info-card customers-card">
                            {{-- <div class="filter">
                                <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                        class="bi bi-three-dots"></i></a>
                            </div> --}}
                            {{-- {{ route('showRejeter', ['id' => $commissionagrement->id]) }} --}}
                            <a href="{{ route('operateurs.expirer') }}">
                                <div class="card-body">
                                    <h5 class="card-title">Agréments <span>| expirés</span></h5>
                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-people"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6>
                                                {{ $operateur_expirer }}
                                            </h6>
                                            <span
                                                class="text-success small pt-1 fw-bold">{{ number_format($pourcentage_expirer, 2, ',', ' ') . '%' }}</span>
                                            {{-- <span class="text-muted small pt-2 ps-1">increase</span> --}}
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <div class="col-12 col-md-4 col-lg-3 col-sm-12 col-xs-12 col-xxl-3">
                        <div class="card info-card revenue-card">
                            <a href="#">
                                <div class="card-body">
                                    <h5 class="card-title">Nouvelles <span>| demandes</span></h5>
                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-people"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6>
                                                {{ $operateur_nouveau }}
                                            </h6>
                                            <span
                                                class="text-success small pt-1 fw-bold">{{ number_format($pourcentage_nouveau, 2, ',', ' ') . '%' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="row">
            <div class="col-12 col-md-12 col-lg-12 col-sm-12 col-xs-12 col-xxl-12">
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
                <div class="card">
                    <div class="card-body">
                        @if (auth()->user()->hasRole('super-admin|admin|DEC'))
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title">{{ $title }}</h5>
                                <span class="d-flex align-items-baseline">
                                    {{-- <a href="#" class="btn btn-primary btn-sm float-end" data-bs-toggle="modal"
                                            data-bs-target="#AddOperateurModal" title="Ajouter">Ajouter</a> --}}
                                    <a href="{{ route('operateurs.create') }}"
                                        class="btn btn-primary btn-sm float-end btn-rounded"><i
                                            class="fas fa-plus"></i>Ajouter</a>
                                    <div class="filter">
                                        <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                                class="bi bi-three-dots"></i></a>
                                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                            <li>
                                                <button type="button" class="dropdown-item btn btn-sm"
                                                    data-bs-toggle="modal" data-bs-target="#generate_rapport"></i>Rechercher
                                                    plus</button>
                                            </li>
                                            @hasrole('admin|super-admin')
                                                <li>
                                                    <form action="{{ route('importO') }}" method="get">
                                                        <button type="submit"
                                                            class="dropdown-item btn btn-sm">Importer</button>
                                                    </form>
                                                    {{-- <button type="button" class="dropdown-item btn btn-sm"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#importOperateur"></i>Importer</button> --}}
                                                </li>
                                            @endhasrole
                                        </ul>
                                    </div>
                                </span>
                            </div>
                        @endif
                        {{-- <h5 class="card-title">Liste des opérateurs</h5> --}}
                        @if ($operateurs->isNotEmpty())
                            <div class="table-responsive">
                                <table class="table datatables table-bordered table-hover table-striped"
                                    id="table-operateurs">
                                    <thead>
                                        <tr>
                                            @can('afficher-dossier-operateur')
                                                <th width="3%" class="text-center">Dossier</th>
                                            @endcan
                                            <th width="15%" class="text-center">N° agrément</th>
                                            @can('afficher-operateur-name')
                                                <th width="40%">Opérateurs</th>
                                            @endcan
                                            <th>Sigle</th>
                                            @can('afficher-operateur-email')
                                                <th>Email</th>
                                            @endcan
                                            @can('afficher-operateur-telephone')
                                                <th>Telephone</th>
                                            @endcan
                                            @can('afficher-operateur-adresse')
                                                <th>Adresse</th>
                                            @endcan
                                            @can('afficher-operateur-responsable')
                                                <th>Responsable</th>
                                            @endcan
                                            @can('afficher-operateur-module')
                                                <th class="text-center">Modules</th>
                                            @endcan
                                            @can('afficher-operateur-formation')
                                                <th class="text-center">Formations</th>
                                            @endcan
                                            @can('afficher-operateur-statut')
                                                <th width="15%" class="text-center">Statut</th>
                                            @endcan
                                            @can('operateur-show')
                                                <th width="2%"><i class="bi bi-gear"></i></th>
                                            @endcan
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1; ?>
                                        @foreach ($operateurs as $operateur)
                                            {{-- @isset($operateur?->numero_agrement) --}}
                                            <tr>
                                                @can('afficher-dossier-operateur')
                                                    <td class="text-center">{{ $operateur?->numero_dossier }}</td>
                                                @endcan
                                                <td>{{ $operateur?->numero_agrement }}</td>
                                                @can('afficher-operateur-name')
                                                    <td>{{ $operateur?->user?->operateur }}</td>
                                                @endcan
                                                <td>{{ $operateur?->user?->username }}</td>
                                                @can('afficher-operateur-email')
                                                    <td><a
                                                            href="mailto:{{ $operateur?->user?->email }}">{{ $operateur?->user?->email }}</a>
                                                    </td>
                                                @endcan
                                                @can('afficher-operateur-telephone')
                                                    <td><a href="tel:+{{ $operateur?->user?->fixe }}">{{ $operateur?->user?->fixe . ' ' . $operateur?->user?->telephone }}
                                                        </a>
                                                    </td>
                                                @endcan
                                                @can('afficher-operateur-adresse')
                                                    <td>{{ $operateur?->user?->adresse }}</td>
                                                @endcan
                                                @can('afficher-operateur-responsable')
                                                    <td>{{ $operateur?->user?->firstname . ' ' . $operateur?->user?->name }}
                                                    </td>
                                                @endcan
                                                @can('afficher-operateur-module')
                                                    <td style="text-align: center;">
                                                        {{-- <span
                                                        class="badge bg-info">{{ count($operateur?->operateurmodules) }}</span> --}}
                                                        @foreach ($operateur->operateurmodules as $operateurmodule)
                                                            @if ($loop->last)
                                                                <a href="#"><span
                                                                        class="badge bg-info">{{ $loop->count }}</span></a>
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                @endcan
                                                @can('afficher-operateur-formation')
                                                    <td class="text-center">
                                                        {{-- <span
                                                        class="badge bg-info">{{ count($operateur?->formations) }}</span> --}}
                                                        @foreach ($operateur->formations as $formation)
                                                            @if ($loop->last)
                                                                <a href="#"><span
                                                                        class="badge bg-info">{{ $loop->count }}</span></a>
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                @endcan
                                                @can('afficher-operateur-statut')
                                                    <td style="text-align: center;"><span
                                                            class="{{ $operateur?->statut_agrement }}">
                                                            {{ $operateur?->statut_agrement }}</span></td>
                                                @endcan
                                                @can('operateur-show')
                                                    <td>
                                                        <span class="d-flex align-items-baseline"><a
                                                                href="{{ route('operateurs.show', $operateur) }}"
                                                                class="btn btn-primary btn-sm" title="voir détails"><i
                                                                    class="bi bi-eye"></i></a>
                                                            <div class="filter">
                                                                <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                                                        class="bi bi-three-dots"></i></a>
                                                                <ul
                                                                    class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                                                    @can('operateur-update')
                                                                        <li>
                                                                            <a class="dropdown-item btn btn-sm"
                                                                                href="{{ route('operateurs.edit', $operateur) }}"
                                                                                class="mx-1" title="Modifier"><i
                                                                                    class="bi bi-pencil"></i>Modifier</a>
                                                                        </li>
                                                                    @endcan
                                                                    @can('operateur-delete')
                                                                        <li>
                                                                            <form
                                                                                action="{{ route('operateurs.destroy', $operateur) }}"
                                                                                method="post">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                                <button type="submit"
                                                                                    class="dropdown-item show_confirm"
                                                                                    title="Supprimer"><i
                                                                                        class="bi bi-trash"></i>Supprimer</button>
                                                                            </form>
                                                                        </li>
                                                                    @endcan
                                                                </ul>
                                                            </div>
                                                            {{-- @endif --}}
                                                        </span>
                                                    </td>
                                                @endcan
                                            </tr>
                                            {{-- @endisset --}}
                                        @endforeach

                                    </tbody>
                                </table>

                            </div>
                        @else
                            <div class="alert alert-info">Aucun opérateur enregistré pour l'instant !</div>
                        @endif
                        <!-- End Table with stripped rows -->

                    </div>
                </div>

            </div>
        </div>
        {{-- <div
            class="col-12 col-md-12 col-lg-12 col-sm-12 col-xs-12 col-xxl-12 d-flex flex-column align-items-center justify-content-center">
            <div class="modal fade" id="AddOperateurModal" tabindex="-1">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <form method="post" action="{{ route('addOperateur') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="card-header text-center bg-gradient-default">
                                <h1 class="h4 text-black mb-0">AJOUTER OPÉRATEUR</h1>
                            </div>
                            <div class="modal-body">
                                <div class="row g-3">

                                    <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                        <label for="numero_dossier" class="form-label">Numéro dossier<span
                                                class="text-danger mx-1">*</span></label>
                                        <div class="input-group has-validation">
                                            <input type="number" min="0" name="numero_dossier"
                                                value="{{ old('numero_dossier') }}"
                                                class="form-control form-control-sm @error('numero_dossier') is-invalid @enderror"
                                                id="numero_dossier" placeholder="Numéro dossier">
                                            @error('numero_dossier')
                                                <span class="invalid-feedback" role="alert">
                                                    <div>{{ $message }}</div>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                        <label for="numero_arrive" class="form-label">Numéro courrier<span
                                                class="text-danger mx-1">*</span></label>
                                        <div class="input-group has-validation">
                                            <input type="text" placeholder="Rechercher numéro courrier..."
                                                class="form-control form-control-sm @error('product') is-invalid @enderror"
                                                name="numero_arrive" id="numero" required>
                                            <div id="productList"></div>
                                            @error('numero_arrive')
                                                <span class="invalid-feedback" role="alert">
                                                    <div>{{ $message }}</div>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                        <label for="numero_agrement" class="form-label">Numéro agrément<span
                                                class="text-danger mx-1">*</span></label>
                                        <input type="text" name="numero_agrement"
                                            value="{{ old('numero_agrement') }}"
                                            class="form-control form-control-sm @error('numero_agrement') is-invalid @enderror"
                                            id="numero_agrement" placeholder="Numéro agrément">
                                        @error('numero_agrement')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-12 col-lg-12 col-sm-12 col-xs-12 col-xxl-12">
                                        <label for="operateur" class="form-label">Raison sociale opérateur<span
                                                class="text-danger mx-1">*</span></label>
                                        <input type="text" placeholder="La raison sociale de l'opérateur"
                                            class="form-control form-control-sm @error('objet') is-invalid @enderror"
                                            name="operateur" id="objet" value="" required>
                                        @error('operateur')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-6 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                        <label for="username" class="form-label">Sigle<span
                                                class="text-danger mx-1">*</span></label>
                                        <input type="text" name="username" value="{{ old('username') }}"
                                            class="form-control form-control-sm @error('username') is-invalid @enderror"
                                            id="username" placeholder="username">
                                        @error('username')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-6 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                        <label for="email" class="form-label">Email<span
                                                class="text-danger mx-1">*</span></label>
                                        <input type="email" name="email" value="{{ old('email') }}"
                                            class="form-control form-control-sm @error('email') is-invalid @enderror"
                                            id="email" placeholder="Adresse email">
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-6 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                        <label for="fixe" class="form-label">Téléphone fixe<span
                                                class="text-danger mx-1">*</span></label>
                                        <input name="fixe" type="text" maxlength="12"
                                            class="form-control form-control-sm @error('fixe') is-invalid @enderror"
                                            id="fixe" value="{{ old('fixe') }}" autocomplete="tel"
                                            placeholder="XX:XXX:XX:XX">
                                        @error('fixe')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-6 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                        <label for="telephone" class="form-label">Téléphone<span
                                                class="text-danger mx-1">*</span></label>
                                        <input name="telephone" type="text" maxlength="12"
                                            class="form-control form-control-sm @error('telephone') is-invalid @enderror"
                                            id="telephone" value="{{ old('telephone') }}" autocomplete="tel"
                                            placeholder="XX:XXX:XX:XX">
                                        @error('telephone')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-6 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                        <label for="bp" class="form-label">Boite postal</label>
                                        <input type="text" name="bp" value="{{ old('bp') }}"
                                            class="form-control form-control-sm @error('bp') is-invalid @enderror"
                                            id="bp" placeholder="Boite postal">
                                        @error('bp')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-12 col-md-6 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                        <label for="categorie" class="form-label">Catégorie<span
                                                class="text-danger mx-1">*</span></label>
                                        <select name="categorie"
                                            class="form-select  @error('categorie') is-invalid @enderror"
                                            aria-label="Select" id="select-field-categorie-pro"
                                            data-placeholder="Choisir">
                                            <option value="{{ old('categorie') }}">
                                                {{ old('categorie') }}
                                            </option>
                                            <option value="Publique">
                                                Publique
                                            </option>
                                            <option value="Privé">
                                                Privé
                                            </option>
                                            <option value="Autre">
                                                Autre
                                            </option>
                                        </select>
                                        @error('categorie')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-6 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                        <label for="statut" class="form-label">Statut juridique<span
                                                class="text-danger mx-1">*</span></label>
                                        <select name="statut" class="form-select  @error('statut') is-invalid @enderror"
                                            aria-label="Select" id="select-field-statutop"
                                            data-placeholder="Choisir statut">
                                            <option value="{{ old('statut') }}">
                                                {{ old('statut') }}
                                            </option>
                                            <option value="GIE">
                                                GIE
                                            </option>
                                            <option value="Association">
                                                Association
                                            </option>
                                            <option value="Entreprise individuelle">
                                                Entreprise individuelle
                                            </option>
                                            <option value="SA">
                                                SA
                                            </option>
                                            <option value="SAS">
                                                SAS
                                            </option>
                                            <option value="SUARL">
                                                SUARL
                                            </option>
                                            <option value="SARL">
                                                SARL
                                            </option>
                                            <option value="SNC">
                                                SNC
                                            </option>
                                            <option value="SCS">
                                                SCS
                                            </option>
                                            <option value="Etablissement public">
                                                Etablissement public
                                            </option>
                                            <option value="Autre">
                                                Autre
                                            </option>
                                        </select>
                                        @error('statut')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-6 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                        <label for="autre_statut" class="form-label">Si autre ?
                                            précisez</label>
                                        <input type="text" name="autre_statut" value="{{ old('autre_statut') }}"
                                            class="form-control form-control-sm @error('autre_statut') is-invalid @enderror"
                                            id="autre_statut" placeholder="autre statut juridique">
                                        @error('autre_statut')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-6 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                        <label for="demande_signe" class="form-label">Demande signée</label>
                                        <select name="demande_signe"
                                            class="form-select  @error('demande_signe') is-invalid @enderror"
                                            aria-label="Select" id="select-field-demande_signe"
                                            data-placeholder="Choisir">
                                            <option value="{{ old('demande_signe') }}">
                                                {{ old('demande_signe') }}
                                            </option>
                                            <option value="Oui">
                                                Oui
                                            </option>
                                            <option value="Non">
                                                Non
                                            </option>
                                        </select>
                                        @error('demande_signe')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-6 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                        <label for="departement" class="form-label">Siège social<span
                                                class="text-danger mx-1">*</span></label>
                                        <select name="departement"
                                            class="form-select form-select-sm @error('departement') is-invalid @enderror"
                                            aria-label="Select" id="select-field-departementop"
                                            data-placeholder="Choisir">
                                            <option value="{{ old('departement') }}">{{ old('departement') }}</option>
                                            @foreach ($departements as $departement)
                                                <option value="{{ $departement->nom }}">
                                                    {{ $departement->nom }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('departement')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-6 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                        <label for="type_demande" class="form-label">Type demande<span
                                                class="text-danger mx-1">*</span></label>
                                        <select name="type_demande"
                                            class="form-select form-select-sm @error('type_demande') is-invalid @enderror"
                                            aria-label="Select" id="select-fieldop"
                                            data-placeholder="Choisir type de demande">
                                            <option value="{{ old('type_demande') }}">
                                                {{ old('type_demande') }}
                                            </option>
                                            <option value="Nouvelle">
                                                Nouvelle
                                            </option>
                                            <option value="Renouvellement">
                                                Renouvellement
                                            </option>
                                        </select>
                                        @error('type_demande')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-6 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                        <label for="web" class="form-label">Site web</label>
                                        <input type="text" name="web" value="{{ old('web') }}"
                                            class="form-control form-control-sm @error('web') is-invalid @enderror"
                                            id="web" placeholder="www.">
                                        @error('web')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-12 col-lg-12 col-sm-12 col-xs-12 col-xxl-12">
                                        <label for="adresse" class="form-label">Adresse<span
                                                class="text-danger mx-1">*</span></label>
                                        <textarea name="adresse" id="adresse" rows="1"
                                            class="form-control form-control-sm @error('adresse') is-invalid @enderror"
                                            placeholder="Adresse exacte de l'opérateur">{{ old('adresse') }}</textarea>
                                        @error('adresse')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-6 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                        <label for="rccm" class="form-label">RCCM / Ninea</label>
                                        <select name="rccm"
                                            class="form-select form-select-sm @error('rccm') is-invalid @enderror"
                                            aria-label="Select" id="select-field-registreop" data-placeholder="Choisir">
                                            <option value="{{ old('rccm') }}">
                                                {{ old('rccm') }}
                                            </option>
                                            <option value="Registre de commerce">
                                                Registre de commerce
                                            </option>
                                            <option value="Ninea">
                                                Ninea
                                            </option>
                                            <option value="Aucun">
                                                Aucun
                                            </option>
                                            <option value="Autre">
                                                Autre
                                            </option>
                                        </select>
                                        @error('rccm')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-6 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                        <label for="ninea" class="form-label">Numéro RCCM / Ninea</label>
                                        <input type="text" name="ninea" value="{{ old('ninea') }}"
                                            class="form-control form-control-sm @error('ninea') is-invalid @enderror"
                                            id="ninea" placeholder="Votre ninéa / Numéro RCCM">
                                        @error('ninea')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-6 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                        <label for="quitusfiscal" class="form-label">Quitus fiscal<span
                                                class="text-danger mx-1">*</span></label>
                                        <select name="quitusfiscal"
                                            class="form-select  @error('quitusfiscal') is-invalid @enderror"
                                            aria-label="Select" id="select-field-quitusfiscal"
                                            data-placeholder="Choisir">
                                            <option value="{{ old('quitusfiscal') }}">
                                                {{ old('quitusfiscal') }}
                                            </option>
                                            <option value="Oui">
                                                Oui
                                            </option>
                                            <option value="Non">
                                                Non
                                            </option>
                                        </select>
                                        @error('quitusfiscal')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-6 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                        <label for="quitus" class="form-label">Scan quitus fiscal</label>
                                        <input type="file" name="quitus" id="quitus" value="{{ old('quitus') }}"
                                            class="form-control @error('quitus') is-invalid @enderror btn btn-outline-success btn-sm">
                                        @error('quitus')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-12 col-md-6 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                        <label for="date_quitus" class="form-label">Date délivrance</label>
                                        <input type="text" name="date_quitus" value="{{ old('date_quitus') }}"
                                            class="form-control form-control-sm @error('date_quitus') is-invalid @enderror"
                                            id="datepicker" placeholder="JJ/MM/AAAA" autocomplete="bday">
                                        @error('date_quitus')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-6 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                        <label for="arrete_creation" class="form-label">Arrêté création</label>
                                        <select name="arrete_creation"
                                            class="form-select  @error('arrete_creation') is-invalid @enderror"
                                            aria-label="Select" id="select-field-arrete_creation-op"
                                            data-placeholder="Choisir">
                                            <option value="{{ old('arrete_creation') }}">
                                                {{ old('arrete_creation') }}
                                            </option>
                                            <option value="MFP">
                                                MFP
                                            </option>
                                            <option value="MESRI">
                                                MESRI
                                            </option>
                                            <option value="MEN">
                                                MEN
                                            </option>
                                            <option value="Aucun">
                                                Aucun
                                            </option>
                                            <option value="Autre">
                                                Autre
                                            </option>
                                        </select>
                                        @error('arrete_creation')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-6 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                        <label for="file_arrete_creation" class="form-label">Scan</label>
                                        <input type="file" name="file_arrete_creation" id="file_arrete_creation"
                                            value="{{ old('file_arrete_creation') }}"
                                            class="form-control @error('file_arrete_creation') is-invalid @enderror btn btn-outline-primary btn-sm">
                                        @error('file_arrete_creation')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-6 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                        <label for="formulaire_signe" class="form-label">Formulaire signée</label>
                                        <select name="formulaire_signe"
                                            class="form-select  @error('formulaire_signe') is-invalid @enderror"
                                            aria-label="Select" id="select-field-formulaire_signeop"
                                            data-placeholder="Choisir">
                                            <option value="{{ old('formulaire_signe') }}">
                                                {{ old('formulaire_signe') }}
                                            </option>
                                            <option value="Oui">
                                                Oui
                                            </option>
                                            <option value="Non">
                                                Non
                                            </option>
                                        </select>
                                        @error('formulaire_signe')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-6 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                        <label for="cvsigne" class="form-label">CV signés</label>
                                        <select name="cvsigne"
                                            class="form-select  @error('cvsigne') is-invalid @enderror"
                                            aria-label="Select" id="select-field-cvsigne" data-placeholder="Choisir">
                                            <option value="{{ old('cvsigne') }}">
                                                {{ old('cvsigne') }}
                                            </option>
                                            <option value="Oui">
                                                Oui
                                            </option>
                                            <option value="Non">
                                                Non
                                            </option>
                                        </select>
                                        @error('cvsigne')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <hr class="dropdown-divider mt-5">

                                    <div class="col-12 col-md-6 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                        <label for="civilite" class="form-label">Civilité<span
                                                class="text-danger mx-1">*</span></label>
                                        <select name="civilite"
                                            class="form-select form-select-sm @error('civilite') is-invalid @enderror"
                                            aria-label="Select" id="select-field-civiliteop"
                                            data-placeholder="Choisir civilité">
                                            <option value="{{ old('civilite') }}">
                                                {{ old('civilite') }}
                                            </option>
                                            <option value="M.">
                                                M.
                                            </option>
                                            <option value="Mme">
                                                Mme
                                            </option>
                                        </select>
                                        @error('civilite')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-12 col-md-6 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                        <label for="prenom" class="form-label">Prénom responsable<span
                                                class="text-danger mx-1">*</span></label>
                                        <input type="text" name="prenom" value="{{ old('prenom') }}"
                                            class="form-control form-control-sm @error('prenom') is-invalid @enderror"
                                            id="prenom" placeholder="Prénom responsable">
                                        @error('prenom')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-6 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                        <label for="nom" class="form-label">Nom responsable<span
                                                class="text-danger mx-1">*</span></label>
                                        <input type="text" name="nom" value="{{ old('nom') }}"
                                            class="form-control form-control-sm @error('nom') is-invalid @enderror"
                                            id="nom" placeholder="Nom responsable">
                                        @error('nom')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-6 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                        <label for="fonction_responsable" class="form-label">Fonction responsable<span
                                                class="text-danger mx-1">*</span></label>
                                        <input type="text" name="fonction_responsable"
                                            value="{{ old('fonction_responsable') }}"
                                            class="form-control form-control-sm @error('fonction_responsable') is-invalid @enderror"
                                            id="fonction_responsable" placeholder="Fonction responsable">
                                        @error('fonction_responsable')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-6 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                        <label for="email_responsable" class="form-label">Adresse e-mail</label>
                                        <input type="email" name="email_responsable"
                                            value="{{ old('email_responsable') }}"
                                            class="form-control form-control-sm @error('email_responsable') is-invalid @enderror"
                                            id="email_responsable" placeholder="Adresse email responsable">
                                        @error('email_responsable')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-6 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                        <label for="telephone_parent" class="form-label">Téléphone responsable</label>
                                        <input name="telephone_parent" type="text" maxlength="12"
                                            class="form-control form-control-sm @error('telephone_parent') is-invalid @enderror"
                                            id="telephone_secondaire" value="{{ old('telephone_parent') }}"
                                            autocomplete="tel" placeholder="XX:XXX:XX:XX">
                                        @error('telephone_parent')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="modal-footer mt-5">
                                    <button type="button" class="btn btn-secondary btn-sm"
                                        data-bs-dismiss="modal">Fermer</button>
                                    <button type="submit" class="btn btn-primary btn-sm">Enregistrer</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div> --}}

        <div class="modal fade" id="generate_rapport" tabindex="-1" role="dialog"
            aria-labelledby="generate_rapportLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="card-header text-center bg-gradient-default">
                        <h1 class="h4 text-black mb-0">FAIRE UNE RECHERCHE</h1>
                    </div>
                    <form method="post" action="{{ route('operateurs.report') }}">
                        @csrf
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-12 col-md-12 col-lg-12 col-sm-12 col-xs-12 col-xxl-12">
                                    <div class="row">
                                        <div class="col-12 col-md-12 col-lg-12 col-sm-12 col-xs-12 col-xxl-12">
                                            <div class="form-group">
                                                <label for="operateur_name" class="form-label">Raison sociale</label>
                                                <input type="text" name="operateur_name"
                                                    value="{{ old('operateur_name') }}"
                                                    class="form-control form-control-sm @error('operateur_name') is-invalid @enderror"
                                                    id="operateur_name" placeholder="Raison sociale">
                                                @error('operateur_name')
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-12 col-lg-12 col-sm-12 col-xs-12 col-xxl-12">
                                    <div class="row">
                                        <div class="col-12 col-md-12 col-lg-12 col-sm-12 col-xs-12 col-xxl-12">
                                            <div class="form-group">
                                                <label for="operateur_sigle" class="form-label">Sigle</label>
                                                <input type="text" name="operateur_sigle"
                                                    value="{{ old('operateur_sigle') }}"
                                                    class="form-control form-control-sm @error('operateur_sigle') is-invalid @enderror"
                                                    id="operateur_sigle" placeholder="Sigle">
                                                @error('operateur_sigle')
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-12 col-lg-12 col-sm-12 col-xs-12 col-xxl-12">
                                    <div class="row">
                                        <div class="col-12 col-md-12 col-lg-12 col-sm-12 col-xs-12 col-xxl-12">
                                            <div class="form-group">
                                                <label for="numero_agrement" class="form-label">N° agrément</label>
                                                <input type="text" name="numero_agrement"
                                                    value="{{ old('numero_agrement') }}"
                                                    class="form-control form-control-sm @error('numero_agrement') is-invalid @enderror"
                                                    id="numero_agrement" placeholder="Numéro agrément">
                                                @error('numero_agrement')
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-12 col-lg-12 col-sm-12 col-xs-12 col-xxl-12">
                                    <div class="row">
                                        <div class="col-12 col-md-12 col-lg-12 col-sm-12 col-xs-12 col-xxl-12">
                                            <div class="form-group">
                                                <label for="telephone" class="form-label">Téléphone</label>
                                                <input name="telephone" type="text" maxlength="12"
                                                    class="form-control form-control-sm @error('telephone') is-invalid @enderror"
                                                    id="telephone" value="{{ old('telephone') }}" autocomplete="tel"
                                                    placeholder="XX:XXX:XX:XX">
                                                @error('telephone')
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-12 col-lg-12 col-sm-12 col-xs-12 col-xxl-12">
                                    <div class="row">
                                        <div class="col-12 col-md-12 col-lg-12 col-sm-12 col-xs-12 col-xxl-12">
                                            <div class="form-group">
                                                <label for="email" class="form-label">Email</label>
                                                <input type="email" name="email" value="{{ old('email') }}"
                                                    class="form-control form-control-sm @error('email') is-invalid @enderror"
                                                    id="email" placeholder="email@email.com">
                                                @error('email')
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary btn-sm"
                                        data-bs-dismiss="modal">Fermer</button>
                                    <div class="text-center">
                                        <button type="submit"
                                            class="btn btn-primary btn-block submit_rapport btn-sm">Rechercher</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="importOperateur" tabindex="-1" role="dialog"
            aria-labelledby="importOperateurLabel" aria-hidden="true" data-bs-backdrop="static"
            data-bs-keyboard="false">

            <div class="modal-dialog modal-xl">
                <div class="modal-content">

                    <!-- Header du Modal avec texte centré -->
                    {{-- <div class="modal-header bg-secondary text-white">
                        <h5 class="modal-title w-100 text-center" id="importOperateurLabel">
                            FAIRE UNE RECHERCHE
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div> --}}

                    <div class="card-header text-center bg-gradient-default">
                        <h1 class="h4 text-black mb-0">IMPORTER</h1>
                    </div>

                    <!-- Formulaire -->
                    <form method="post" action="{{ route('import.operateurs') }}" enctype="multipart/form-data"
                        class="p-3" novalidate>
                        @csrf

                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="file" class="form-label">
                                    Fichier (.XLSX, .CSV, .XLS) <span class="text-danger">*</span>
                                </label>
                                <input type="file" name="file" value="{{ old('file') }}"
                                    class="form-control form-control-sm @error('file') is-invalid @enderror"
                                    id="file" required>

                                @error('file')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Footer du Modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-sm"
                                data-bs-dismiss="modal">Fermer</button>
                            <button type="submit" class="btn btn-primary btn-sm text-white">Importer</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>

    </section>

@endsection
@push('scripts')
    <script>
        new DataTable('#table-operateurs', {
            layout: {
                topStart: {
                    buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
                }
            },
            "order": [
                [0, 'desc']
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
