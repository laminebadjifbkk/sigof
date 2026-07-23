@extends('layout.user-layout')
@section('title', 'Ingénieur | ' . $ingenieur->name)
@section('space-work')

    <div class="pagetitle">
        {{-- <h1>Data Tables</h1> --}}
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Accueil</a></li>
                <li class="breadcrumb-item">Tables</li>
                <li class="breadcrumb-item active">{{ $ingenieur->name }}</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Total formations : {{ $ingenieur->formations->count() }}</h5>
        <a href="{{ route('ingenieurs.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left-circle"></i> Retour
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-primary">
                <tr>
                    <th scope="col" style="width: 50px;">N°</th>
                    <th scope="col">Années</th>
                    <th scope="col" class="text-center">Formations</th>
                    <th scope="col" style="width: 120px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($groupes as $index => $items)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $index }}</td>
                        <td class="text-center">{{ number_format($items->count(), 0, '', ' ') }}</td>
                        <td>
                            <a href="{{ route('ingenieurs.formations.parAnnee', [
                                'ingenieur' => $ingenieur->id,
                                'annee' => $index,
                            ]) }}"
                                class="btn btn-sm btn-outline-primary d-flex align-items-center justify-content-center gap-1">
                                Voir plus <i class="bi bi-arrow-right-short"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <hr>

    @can('ingenieur-show')
        <section class="section">
            <div class="row">
                <div class="col-lg-12">
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
                                role="alert">{{ $error }}</div>
                        @endforeach
                    @endif
                    <div class="card">
                        <div class="card-body">
                            <div class="pt-1">
                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">

                                    {{-- Titre à gauche --}}
                                    <div class="d-flex align-items-center gap-2">
                                        <h6 class="mb-0 text-muted fw-semibold text-uppercase">
                                            Liste des formations
                                        </h6>
                                    </div>

                                    <div class="d-flex align-items-center gap-2 text-info fw-semibold">
                                        <i class="bi bi-list-ul me-1"></i>
                                        <span>
                                            Affichage :
                                            <span class="text-dark">{{ $affichees }}</span>
                                            sur
                                            <span class="text-dark">{{ $total }}</span> demandes
                                        </span>
                                    </div>

                                    {{-- Boutons à droite --}}
                                    @can('formation-create')
                                        <div class="d-flex align-items-center gap-2">
                                            <a href="#" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#AddFormationModal" title="Ajouter une formation">
                                                Ajouter
                                            </a>
                                            <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="modal"
                                                data-bs-target="#generate_rapport">
                                                Rechercher avancée
                                            </button>
                                            @can('suivi-convention')
                                                <div class="dropdown">
                                                    <a href="#" class="btn btn-sm btn-light" data-bs-toggle="dropdown"
                                                        title="Options">
                                                        <i class="bi bi-three-dots-vertical"></i>
                                                    </a>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li>
                                                            <button type="button" class="dropdown-item" data-bs-toggle="modal"
                                                                data-bs-target="#generate_rapportFormation">
                                                                <i class="bi bi-file-earmark-text"></i> Suivi-convention {{ $ingenieur->name }}
                                                            </button>
                                                        </li>
                                                    </ul>
                                                </div>
                                            @endcan
                                        </div>
                                    @endcan

                                </div>
                            </div>
                            @if ($ingenieur->formations->isNotEmpty())
                                <div class="table-responsive">
                                    <table class="table datatables align-middle justify-content-center" id="table-formations">
                                        <thead>
                                            <tr>
                                                {{-- <th class="text-center" width="2%">Code</th> --}}
                                                <th>Type</th>
                                                <th>Intitulé formation</th>
                                                <th>Modules</th>
                                                <th>Régions</th>
                                                <th class="text-center">Statut</th>
                                                <th width='3%'>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i = 1; ?>
                                            @foreach ($ingenieur->formations as $formation)
                                                <tr>
                                                    {{-- <td class="text-center">{{ $formation?->code }}</td> --}}
                                                    <td><a href="#">{{ $formation->types_formation?->name }}</a></td>
                                                    <td>{{ $formation?->name }}</td>
                                                    <td>
                                                        {{ $formation?->module?->name ?? ($formation?->collectivemodule?->module ?? '') }}
                                                    </td>
                                                    <td>
                                                        {{-- {{ $formation->departement?->region?->nom }} --}}
                                                        @if ($formation->regions->isNotEmpty())
                                                            <span>
                                                                {{ $formation->regions->pluck('nom')->join(', ') }}
                                                            </span>
                                                        @else
                                                            <span class="fs-5 text-muted">Aucune</span>
                                                        @endif
                                                    </td>
                                                    {{-- <td>
                                                        @isset($formation?->module?->name)
                                                            {{ $formation?->module?->name }}
                                                        @endisset
                                                        @isset($formation?->collectivemodule?->module)
                                                            {{ $formation?->collectivemodule?->module }}
                                                        @endisset
                                                    </td> --}}
                                                    <td class="text-center"><a href="#"><span
                                                                class="{{ $formation?->statut }}">{{ $formation?->statut }}</span></a>
                                                    </td>
                                                    <td>
                                                        <span class="d-flex align-items-baseline"><a
                                                                href="{{ route('formations.show', $formation) }}"
                                                                class="btn btn-primary btn-sm" title="voir détails"><i
                                                                    class="bi bi-eye"></i></a>
                                                            <div class="filter">
                                                                <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                                                        class="bi bi-three-dots"></i></a>
                                                                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                                                    <li>
                                                                        <a href="{{ route('formations.edit', $formation) }}"
                                                                            class="dropdown-item">
                                                                            <i class="bi bi-pencil"></i> Modifier
                                                                        </a>
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
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-info bg-warning text-light border-0 alert-dismissible fade show"
                                    role="alert">
                                    <strong>Aucune formation attribuée à cet ingénieur.</strong>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <span class="d-flex align-items-baseline"><a href="{{ route('ingenieurs.index') }}"
                                    class="btn btn-success btn-sm" title="retour"><i
                                        class="bi bi-arrow-counterclockwise"></i></a>&nbsp;
                                <p> | retour</p>
                            </span>
                            {{-- <h5 class="card-title">Liste des demandes collectives imputées à {{ $ingenieur->name }}</h5> --}}
                            <h5 class="card-title">Liste des demandes collectives</h5>
                            @if ($ingenieur->collectivemodules->isNotEmpty())
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover align-middle datatables"
                                        id="table-collectives">
                                        <thead>
                                            <tr>
                                                {{-- <th>N° DEM.</th> --}}
                                                <th>Modules</th>
                                                <th>Nom structure</th>
                                                {{-- <th>E-mail</th>
                                            <th>Téléphone</th> --}}
                                                <th>Région</th>
                                                <th class="text-center">Effectif</th>
                                                <th class="text-center">Statut</th>
                                                <th class="text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($ingenieur->collectivemodules as $collectivemodule)
                                                <tr>
                                                    {{-- <td>{{ $collectivemodule->collective?->numero }}</td> --}}
                                                    <td>{{ $collectivemodule->module }}</td>
                                                    <td>
                                                        {{ $collectivemodule->collective?->name_with_sigle }}
                                                    </td>
                                                    {{-- <td>
                                                    <a
                                                        href="mailto:{{ $collectivemodule->collective->user->email }}">{{ $collectivemodule->collective->user->email }}</a>
                                                </td>
                                                <td>
                                                    <a
                                                        href="tel:+221{{ $collectivemodule->collective->telephone }}">{{ $collectivemodule->collective->telephone }}</a>
                                                </td> --}}
                                                    <td>{{ $collectivemodule->collective->departement?->region?->nom }}</td>
                                                    <td class="text-center">
                                                        {{ count($collectivemodule->listecollectives) }}</td>
                                                    <td class="text-center">
                                                        <span
                                                            class="{{ $collectivemodule->statut }}">{{ $collectivemodule->statut }}</span>
                                                    </td>
                                                    <td class="text-center">
                                                        @can('collective-show')
                                                            <div class="d-flex align-items-center">
                                                                <a href="{{ route('collectives.show', $collectivemodule->collective) }}"
                                                                    class="btn btn-primary btn-sm me-1" title="Voir détails">
                                                                    <i class="bi bi-eye"></i>
                                                                </a>
                                                                {{-- <div class="dropdown">
                                                                <a href="#" class="btn btn-light btn-sm dropdown-toggle"
                                                                    data-bs-toggle="dropdown">
                                                                    <i class="bi bi-three-dots"></i>
                                                                </a>
                                                                <ul class="dropdown-menu dropdown-menu-end">
                                                                    @can('collective-update')
                                                                        <li>
                                                                            <a href="{{ route('collectives.edit', $collectivemodules->collective) }}"
                                                                                class="dropdown-item">
                                                                                <i class="bi bi-pencil"></i> Modifier
                                                                            </a>
                                                                        </li>
                                                                    @endcan
                                                                    @can('collective-delete')
                                                                        <li>
                                                                            <form
                                                                                action="{{ route('collectives.destroy', $collectivemodules->collective) }}"
                                                                                method="POST">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                                <button type="submit"
                                                                                    class="dropdown-item show_confirm">
                                                                                    <i class="bi bi-trash"></i> Supprimer
                                                                                </button>
                                                                            </form>
                                                                        </li>
                                                                    @endcan
                                                                </ul>
                                                            </div> --}}
                                                            </div>
                                                        @endcan
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-info bg-info text-light border-0 alert-dismissible fade show"
                                    role="alert">
                                    <strong>Aucune demande collective n'est encore imputée à cet ingénieur.</strong>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- End Edit ingenieur-->
                <div class="col-lg-12 col-md-12 d-flex flex-column align-items-center justify-content-center">
                    <div class="modal fade" id="AddFormationModal" tabindex="-1">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <form method="post" action="{{ route('formations.store') }}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="card-header text-center bg-gradient-default">
                                        <h1 class="h4 text-black mb-0">CRÉER FORMATION</h1>
                                    </div>
                                    <input type="hidden" name="ingenieur" value="{{ $ingenieur->id }}">
                                    <div class="modal-body">
                                        <div class="row g-3">
                                            <div class="col-12 col-md-12 col-lg-12 col-sm-12 col-xs-12 col-xxl-12">
                                                <label for="intitule" class="form-label">Intitulé<span
                                                        class="text-danger mx-1">*</span></label>
                                                <textarea name="intitule" id="intitule" rows="1"
                                                    class="form-control form-control-sm @error('intitule') is-invalid @enderror"
                                                    placeholder="ex : Technique de coupe-couture">{{ old('intitule') }}</textarea>
                                                @error('intitule')
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="col-12 col-md-12 col-lg-8 col-sm-12 col-xs-12 col-xxl-12">
                                                <label for="name" class="form-label">Bénéficiaires<span
                                                        class="text-danger mx-1">*</span></label>
                                                <textarea name="name" id="name" rows="1"
                                                    class="form-control form-control-sm @error('name') is-invalid @enderror" placeholder="Bénéficiaires">{{ old('name') }}</textarea>
                                                @error('name')
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                                <label for="annee" class="form-label">Année<span
                                                        class="text-danger mx-1">*</span></label>
                                                <div class="input-group has-validation">
                                                    <input type="number" min="2024" max="2080" name="annee"
                                                        value="{{ old('annee') }}"
                                                        class="form-control form-control-sm @error('annee') is-invalid @enderror"
                                                        id="annee" placeholder="annee">
                                                    @error('annee')
                                                        <span class="invalid-feedback" role="alert">
                                                            <div>{{ $message }}</div>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>

                                            {{-- <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                        <label for="code" class="form-label">Code<span
                                                class="text-danger mx-1">*</span></label>
                                        <div class="input-group has-validation">
                                            <input type="text" name="code"
                                                value="{{ $numFormation ?? old('code') }}"
                                                class="form-control form-control-sm @error('code') is-invalid @enderror"
                                                id="code" placeholder="code">
                                            @error('code')
                                                <span class="invalid-feedback" role="alert">
                                                    <div>{{ $message }}</div>
                                                </span>
                                            @enderror
                                        </div>
                                    </div> --}}

                                            <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                                <label for="departement" class="form-label">Département<span
                                                        class="text-danger mx-1">*</span></label>
                                                <select name="departement"
                                                    class="form-select  @error('departement') is-invalid @enderror"
                                                    aria-label="Select" id="select-field-departement-modal"
                                                    data-placeholder="Choisir département">
                                                    <option value="{{ old('departement') }}">
                                                        {{ old('departement') }}
                                                    </option>
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

                                            <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                                <label for="lieu" class="form-label">Lieu formation<span
                                                        class="text-danger mx-1">*</span></label>
                                                <input type="text" name="lieu" value="{{ old('lieu') }}"
                                                    class="form-control form-control-sm @error('lieu') is-invalid @enderror"
                                                    id="lieu" placeholder="Lieu formation">
                                                @error('lieu')
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                                <label for="types_formation" class="form-label">Type demande<span
                                                        class="text-danger mx-1">*</span></label>
                                                <select name="types_formation"
                                                    class="form-select  @error('types_formation') is-invalid @enderror"
                                                    aria-label="Select" id="select-field-types_formation"
                                                    data-placeholder="Choisir type formation">
                                                    <option value="{{ old('types_formation') }}">
                                                        {{ old('types_formation') }}
                                                    </option>
                                                    @foreach ($types_formations as $types_formation)
                                                        <option value="{{ $types_formation->name }}">
                                                            {{ $types_formation->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('types_formation')
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                                <label for="type_certification" class="form-label">Type certification<span
                                                        class="text-danger mx-1">*</span></label>
                                                <select name="type_certification"
                                                    class="form-select  @error('type_certification') is-invalid @enderror"
                                                    aria-label="Select" id="select-field-type_certification"
                                                    data-placeholder="Choisir niveau de qualification">
                                                    <option value="{{ old('type_certification') }}">
                                                        {{ old('type_certification') }}
                                                    </option>
                                                    <option value="Titre">
                                                        Titre
                                                    </option>
                                                    <option value="Attestation">
                                                        Attestation
                                                    </option>
                                                </select>
                                                @error('type_certification')
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                                <label for="date_debut" class="form-label">Date début</label>
                                                <input type="date" name="date_debut" value="{{ old('date_debut') }}"
                                                    class="datepicker form-control form-control-sm @error('date_debut') is-invalid @enderror"
                                                    id="date_debut" placeholder="jj/mm/aaaa">
                                                @error('date_debut')
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                                <label for="date_fin" class="form-label">Date fin</label>
                                                <input type="date" name="date_fin" value="{{ old('date_fin') }}"
                                                    class="datepicker form-control form-control-sm @error('date_fin') is-invalid @enderror"
                                                    id="date_fin" placeholder="jj/mm/aaaa">
                                                @error('date_fin')
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                                <label for="prevue_h" class="form-label">Effectif prévu homme</label>
                                                <input type="number" name="prevue_h" min="0" max="25"
                                                    value="{{ old('prevue_h') }}"
                                                    class="form-control form-control-sm @error('prevue_h') is-invalid @enderror"
                                                    id="prevue_h" placeholder="Effectif homme">
                                                @error('prevue_h')
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                                <label for="prevue_f" class="form-label">Effectif prévu femme</label>
                                                <input type="number" name="prevue_f" min="0" max="25"
                                                    value="{{ old('prevue_f') }}"
                                                    class="form-control form-control-sm @error('prevue_f') is-invalid @enderror"
                                                    id="prevue_f" placeholder="Effectif femme">
                                                @error('prevue_f')
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                                <label for="frais_operateurs" class="form-label">Frais opérateur</label>
                                                <input type="number" name="frais_operateurs" min="0" step="0.001"
                                                    value="{{ old('frais_operateurs') }}"
                                                    class="form-control form-control-sm @error('frais_operateurs') is-invalid @enderror"
                                                    id="frais_operateurs" placeholder="Frais opérateur">
                                                @error('frais_operateurs')
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                                <label for="frais_add" class="form-label">Frais additionels</label>
                                                <input type="number" name="frais_add" min="0" step="0.001"
                                                    value="{{ old('frais_add') }}"
                                                    class="form-control form-control-sm @error('frais_add') is-invalid @enderror"
                                                    id="frais_add" placeholder="Frais additionels">
                                                @error('frais_add')
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                                <label for="autes_frais" class="form-label">Autres frais</label>
                                                <input type="number" name="autes_frais" min="0" step="0.001"
                                                    value="{{ old('autes_frais') }}"
                                                    class="form-control form-control-sm @error('autes_frais') is-invalid @enderror"
                                                    id="autes_frais" placeholder="Autres frais">
                                                @error('autes_frais')
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div>

                                        </div>
                                        <div class="modal-footer mt-5">
                                            <button type="button" class="btn btn-secondary btn-sm"
                                                data-bs-dismiss="modal">Fermer</button>
                                            <button type="submit" class="btn btn-primary btn-sm">Créer formation</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Edit ingenieur -->
                @foreach ($ingenieurs as $ingenieur)
                    <div class="modal fade" id="EditingenieurModal{{ $ingenieur->id }}" tabindex="-1" role="dialog"
                        aria-labelledby="EditingenieurModalLabel{{ $ingenieur->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form method="post" action="{{ route('ingenieurs.update', $ingenieur->id) }}"
                                    enctype="multipart/form-data" class="row g-3">
                                    @csrf
                                    @method('patch')
                                    <div class="modal-header" id="EditingenieurModalLabel{{ $ingenieur->id }}">
                                        <h5 class="modal-title"><i class="bi bi-pencil" title="Ajouter"></i> Modifier
                                            ingénieurs
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <input type="hidden" name="id" value="{{ $ingenieur->id }}">
                                    <div class="modal-body">
                                        <div class="form-floating mb-3">
                                            <input type="text" name="matricule"
                                                value="{{ $ingenieur->matricule ?? old('matricule') }}"
                                                class="form-control form-control-sm @error('matricule') is-invalid @enderror"
                                                id="matricule" placeholder="Matricule" autofocus>
                                            @error('matricule')
                                                <span class="invalid-feedback" role="alert">
                                                    <div>{{ $message }}</div>
                                                </span>
                                            @enderror
                                            <label for="floatingInput">Matricule</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input type="text" name="name"
                                                value="{{ $ingenieur->name ?? old('name') }}"
                                                class="form-control form-control-sm @error('name') is-invalid @enderror"
                                                id="name" placeholder="Ingénieur" autofocus>
                                            @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <div>{{ $message }}</div>
                                                </span>
                                            @enderror
                                            <label for="floatingInput">Ingénieur<span
                                                    class="text-danger mx-1">*</span></label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input type="text" name="initiale"
                                                value="{{ $ingenieur->initiale ?? old('initiale') }}"
                                                class="form-control form-control-sm @error('initiale') is-invalid @enderror"
                                                id="initiale" placeholder="initiale">
                                            @error('initiale')
                                                <span class="invalid-feedback" role="alert">
                                                    <div>{{ $message }}</div>
                                                </span>
                                            @enderror
                                            <label for="floatingInput">Initiale<span class="text-danger mx-1">*</span></label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input type="text" name="specialite"
                                                value="{{ $ingenieur->specialite ?? old('specialite') }}"
                                                class="form-control form-control-sm @error('specialite') is-invalid @enderror"
                                                id="specialite" placeholder="specialite">
                                            @error('specialite')
                                                <span class="invalid-feedback" role="alert">
                                                    <div>{{ $message }}</div>
                                                </span>
                                            @enderror
                                            <label for="floatingInput">Spécialité</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input type="text" name="fonction"
                                                value="{{ $ingenieur->fonction ?? old('fonction') }}"
                                                class="form-control form-control-sm @error('fonction') is-invalid @enderror"
                                                id="fonction" placeholder="fonction">
                                            @error('specialite')
                                                <span class="invalid-feedback" role="alert">
                                                    <div>{{ $message }}</div>
                                                </span>
                                            @enderror
                                            <label for="floatingInput">Fonction<span class="text-danger mx-1">*</span></label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input type="text" name="email"
                                                value="{{ $ingenieur->email ?? old('email') }}"
                                                class="form-control form-control-sm @error('email') is-invalid @enderror"
                                                id="email" placeholder="email">
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <div>{{ $message }}</div>
                                                </span>
                                            @enderror
                                            <label for="floatingInput">Email<span class="text-danger mx-1">*</span></label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input type="text" name="telephone"
                                                value="{{ $ingenieur->telephone ?? old('telephone') }}"
                                                class="form-control form-control-sm @error('telephone') is-invalid @enderror"
                                                id="telephone" placeholder="Telephone">
                                            @error('telephone')
                                                <span class="invalid-feedback" role="alert">
                                                    <div>{{ $message }}</div>
                                                </span>
                                            @enderror
                                            <label for="floatingInput">Telephone<span
                                                    class="text-danger mx-1">*</span></label>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary btn-sm"
                                            data-bs-dismiss="modal">Fermer</button>
                                        <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-printer"></i>
                                            Modifier</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
                <div class="modal fade" id="generate_rapportFormation" tabindex="-1" role="dialog"
                    aria-labelledby="generate_rapportFormationLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 shadow">
                            <div class="modal-header text-white"
                                style="background: linear-gradient(135deg, #2c3e50 0%, #4a6fa5 100%);">
                                <h1 class="h5 mb-0">
                                    <i class="bi bi-file-earmark-bar-graph me-2"></i>Générer un rapport
                                </h1>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                    aria-label="Fermer"></button>
                            </div>

                            <form method="post" action="{{ route('formations.reports') }}">
                                @csrf
                                <div class="modal-body px-4 py-4">
                                    <div class="row g-3">

                                        <div class="col-md-6">
                                            <label for="annee" class="form-label fw-semibold">
                                                Année <span class="text-danger">*</span>
                                            </label>
                                            <select name="annee"
                                                class="form-select form-select-sm @error('annee') is-invalid @enderror"
                                                id="select-field-formation-annee-rapport" data-placeholder="Choisir année">
                                                <option value="{{ old('annee') }}">{{ old('annee') }}</option>
                                                @foreach ($formations_annee as $anneeformation)
                                                    <option value="{{ $anneeformation->annee }}">
                                                        {{ $anneeformation->annee }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('annee')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label for="statut" class="form-label fw-semibold">
                                                Statut <span class="text-danger">*</span>
                                            </label>
                                            <select name="statut"
                                                class="form-select form-select-sm @error('statut') is-invalid @enderror"
                                                id="select-field-formation-region-rapport" data-placeholder="Choisir statut">
                                                <option value="{{ old('statut') }}">{{ old('statut') }}</option>
                                                <option value="Tous">Tous</option>
                                                @foreach ($formations_statut as $statutformation)
                                                    <option value="{{ $statutformation->statut }}">
                                                        {{ $statutformation->statut }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('statut')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="col-12">
                                            <label for="pole_id" class="form-label fw-semibold">
                                                Pôle <span class="text-danger">*</span>
                                            </label>
                                            <select name="pole_id"
                                                class="form-select form-select-sm @error('pole_id') is-invalid @enderror"
                                                id="select-field-formation-pole-rapport" data-placeholder="Choisir pôle">
                                                <option value="">-- Choisir un pôle --</option>
                                                <option value="Tous" {{ old('pole_id') == 'Tous' ? 'selected' : '' }}>
                                                    Tous
                                                </option>
                                                @foreach ($poles as $pole)
                                                    <option value="{{ $pole->id }}"
                                                        {{ old('pole_id') == $pole->id ? 'selected' : '' }}>
                                                        {{ $pole->name ?? $pole->code }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('pole_id')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">
                                                De <span class="text-danger">*</span>
                                            </label>
                                            <input type="date" name="from_date"
                                                class="form-control form-control-sm @error('from_date') is-invalid @enderror from_date">
                                            @error('from_date')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">
                                                À <span class="text-danger">*</span>
                                            </label>
                                            <input type="date" name="to_date"
                                                class="form-control form-control-sm @error('to_date') is-invalid @enderror to_date">
                                            @error('to_date')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="col-12">
                                            <hr class="my-1">
                                        </div>

                                        <div class="col-md-6">
                                            <label for="age_limite_jeunes" class="form-label fw-semibold">
                                                Âge limite "jeunes" <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group input-group-sm">
                                                <input type="number" name="age_limite_jeunes" min="1" max="99"
                                                    value="{{ old('age_limite_jeunes', 35) }}"
                                                    class="form-control @error('age_limite_jeunes') is-invalid @enderror"
                                                    id="age_limite_jeunes">
                                                <span class="input-group-text">ans</span>
                                                @error('age_limite_jeunes')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <small class="text-muted">Par défaut : 35 ans</small>
                                        </div>

                                    </div>
                                </div>

                                <div class="modal-footer border-0 pt-0">
                                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                                        Fermer
                                    </button>
                                    <button type="submit" class="btn btn-primary btn-sm submit_rapport">
                                        <i class="bi bi-download me-1"></i>Générer
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- RECHERCHE AVANCEE --}}
                <div class="modal fade" id="generate_rapport" tabindex="-1" role="dialog"
                    aria-labelledby="generate_rapportLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog modal-lg">
                        <div class="modal-content border-0 shadow-lg rounded-4">
                            <div class="modal-header text-white"
                                style="background: linear-gradient(135deg, #2c3e50 0%, #4a6fa5 100%);">
                                <h1 class="h5 mb-0">
                                    <i class="bi bi-search me-2"></i>Rechercher une formation
                                </h1>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                    aria-label="Fermer"></button>
                            </div>

                            <form method="post" action="{{ route('formations.report') }}">
                                @csrf
                                <div class="modal-body p-4">
                                    <div class="row g-3">

                                        <div class="col-12">
                                            <div class="form-floating">
                                                <input type="text" name="code" value="{{ old('code') }}"
                                                    class="form-control @error('code') is-invalid @enderror" id="code"
                                                    placeholder="Code">
                                                <label for="code"><i class="bi bi-hash me-1"></i>Code</label>
                                                @error('code')
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="form-floating">
                                                <input type="text" name="intitule" value="{{ old('intitule') }}"
                                                    class="form-control @error('intitule') is-invalid @enderror"
                                                    id="intitule" placeholder="Intitulé">
                                                <label for="intitule"><i class="bi bi-card-text me-1"></i>Intitulé</label>
                                                @error('intitule')
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="form-floating">
                                                <input type="text" name="name" value="{{ old('name') }}"
                                                    class="form-control @error('name') is-invalid @enderror" id="name"
                                                    placeholder="Bénéficiaires">
                                                <label for="name"><i class="bi bi-person me-1"></i>Bénéficiaires</label>
                                                @error('name')
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="form-floating">
                                                <input type="text" name="numero_convention"
                                                    value="{{ old('numero_convention') }}"
                                                    class="form-control @error('numero_convention') is-invalid @enderror"
                                                    id="numero_convention" placeholder="Numéro convention">
                                                <label for="numero_convention"><i class="bi bi-hash me-1"></i>Numéro
                                                    convention</label>
                                                @error('numero_convention')
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        {{-- <div class="col-12">
                                    <div class="form-floating">
                                        <input type="text" name="operateur" value="{{ old('operateur') }}"
                                            class="form-control @error('operateur') is-invalid @enderror" id="operateur"
                                            placeholder="Opérateurs">
                                        <label for="operateur"><i class="bi bi-diagram-3 me-1"></i>Opérateurs</label>
                                        @error('operateur')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>
                                </div> --}}

                                    </div>
                                </div>

                                <div class="modal-footer border-0 pt-0 pb-4 px-4">
                                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">
                                        <i class="bi bi-x-lg me-1"></i>Fermer
                                    </button>
                                    <button type="submit" class="btn btn-primary rounded-pill px-4 submit_rapport">
                                        <i class="bi bi-search me-1"></i>Rechercher
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endcan
@endsection
@push('scripts')
    <script>
        new DataTable('#table-formations', {
            layout: {
                topStart: {
                    buttons: ['csv', 'excel', 'print'],
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
    <script>
        new DataTable('#table-collectives', {
            layout: {
                topStart: {
                    buttons: ['csv', 'excel', 'print'],
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
