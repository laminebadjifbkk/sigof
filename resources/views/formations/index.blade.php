@extends('layout.user-layout')
@section('title', 'SIGOF | FORMATIONS')
@section('space-work')

    <div class="pagetitle">
        {{-- <h1>Data Tables</h1> --}}
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Accueil</a></li>
                <li class="breadcrumb-item">Tables</li>
                <li class="breadcrumb-item active">Données</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <section class="section dashboard">
        <div class="row">
            <!-- Left side columns -->
            <div class="col-lg-12">
                <div class="row">
                    <!-- Sales Card -->
                    {{--  <div class="col-12 pt-5">
                        <div class="card info-card customers-card">
                            <a href="#">
                                <div class="card-body">
                                    <h5 class="card-title">Formations<span> | {{ date('d/m/Y') }}</span></h5>
                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-calendar-date-fill"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6>
                                                <span class="text-primary">{{ $count_today ?? '0' }}</span>
                                            </h6>
                                            <span class="text-success small pt-1 fw-bold">Aujourd'hui</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div> --}}
                    <div class="col-12 pt-5">
                        <div class="row">
                            {{-- <div class="col-12 col-md-4 col-lg-2 col-sm-12 col-xs-12 col-xxl-2">
                                <div class="card info-card revenue-card shadow-sm" style="max-width: 220px;">
                                    <div class="card-body p-2">
                                        <h5 class="card-title text-truncate mb-1" title="Formations"
                                            style="font-size: 1rem;">
                                            Formations
                                        </h5>
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-primary text-white"
                                                style="width: 32px; height: 32px; font-size: 1.25rem;">
                                                <i class="bi bi-people"></i>
                                            </div>
                                            <div class="ps-2">
                                                <h6 class="mb-0" style="font-size: 0.9rem;">
                                                    {{ number_format(count($formations), 0, '', ' ') }}</h6>
                                            </div>
                                        </div>

                                        <a href="{{ route('formations.index') }}"
                                            class="btn btn-outline-primary btn-sm w-100 d-flex align-items-center justify-content-center py-1"
                                            style="font-size: 0.85rem; gap: 6px;">
                                            Voir plus <i class="bi bi-arrow-right-short"></i>
                                        </a>
                                    </div>
                                </div>
                            </div> --}}

                            <div class="col-12 col-md-4 col-lg-2 col-sm-12 col-xs-12 col-xxl-2">
                                <div class="card info-card customers-card revenue-card shadow-sm" style="max-width: 220px;">
                                    <div class="card-body p-2">
                                        <h5 class="card-title text-truncate mb-1" title="Aujourd'hui"
                                            style="font-size: 1rem;">
                                            Aujourd'hui
                                        </h5>
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-primary text-white"
                                                style="width: 32px; height: 32px; font-size: 1.25rem;">
                                                <i class="bi bi-calendar-date-fill"></i>
                                            </div>
                                            <div class="ps-2">
                                                <h6 class="mb-0" style="font-size: 0.9rem;">{{ $count_today ?? '0' }}</h6>
                                            </div>
                                        </div>
                                        <a href="#"
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
                                    <div class="card info-card sales-card shadow-sm" style="max-width: 220px;">
                                        <div class="card-body p-2">
                                            <h5 class="card-title text-truncate mb-1" title="{{ $statut }}"
                                                style="font-size: 1rem;">
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
                                                </div>
                                            </div>
                                            <a href="{{ route('formations.parType', ['libelle' => $statut]) }}"
                                                target="_blank"
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
                    {{-- <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                        <div class="card info-card sales-card">
                            <a href="#">
                                <div class="card-body">
                                    <h5 class="card-title">Formations<span> | individuelles</span></h5>
                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-file-earmark-text"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6>
                                                <span
                                                    class="text-primary">{{ $individuelles_formations_count ?? '0' }}</span>
                                            </h6>
                                            <span class="text-success small pt-1 fw-bold"></span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div> --}}
                    {{-- <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                        <div class="card info-card revenue-card">
                            <a href="#">
                                <div class="card-body">
                                    <h5 class="card-title">Formations<span> | collectives</span></h5>
                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-file-earmark-text"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6>
                                                <span class="text-primary">{{ $collectives_formations_count ?? '0' }}</span>
                                            </h6>
                                            <span class="text-success small pt-1 fw-bold"></span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div> --}}
                    {{-- <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                        <div class="card info-card sales-card">
                            <a href="#">
                                <div class="card-body">
                                    <h5 class="card-title">Formations <span>| Toutes</span></h5>
                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-file-earmark-text"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6>
                                                <span class="text-primary">{{ count($formations) ?? '0' }}</span>
                                            </h6>
                                            <span class="text-success small pt-1 fw-bold">Toutes</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div> --}}

                </div>
            </div>
        </div>
    </section>
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
                        {{-- <div class="pt-0">
                            <button type="button" class="btn btn-primary float-end btn-rounded" data-bs-toggle="modal"
                                data-bs-target="#AddFormationModal">
                                <i class="bi bi-folder-plus" title="Ajouter"></i>
                            </button>
                        </div> --}}
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title mb-0">{{ $title }}</h5>

                            @can('formation-create')
                                <div class="d-flex align-items-center gap-2">
                                    <!-- Bouton Ajouter -->
                                    <a href="#" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#AddFormationModal" title="Ajouter une formation">
                                        Ajouter
                                    </a>

                                    <!-- Menu déroulant -->
                                    <div class="dropdown">
                                        <a href="#" class="btn btn-sm btn-light" data-bs-toggle="dropdown"
                                            title="Options">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <button type="button" class="dropdown-item" data-bs-toggle="modal"
                                                    data-bs-target="#generate_rapportFormation">
                                                    <i class="bi bi-file-earmark-text"></i> Générer suivi-convention
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            @endcan
                        </div>
                        @if ($formations->isNotEmpty())
                            <table class="table datatables table-bordered table-hover align-middle justify-content-center"
                                id="table-formations">
                                <thead class="table-success text-center">
                                    <tr>
                                        <th width='6%' class="text-center">Code</th>
                                        <th width='8%' class="text-center">N° conv.</th>
                                        {{-- <th width='15%'>Type formation</th> --}}
                                        {{--  <th width='15%'>Localité</th> --}}
                                        <th width='25%'>Bénéficiaires</th>
                                        <th width='15%'>Modules</th>
                                        <th width='15%'>Niveau qualif.</th>
                                        <th width='10%' class="text-center">Opérateurs</th>
                                        <th width='5%' class="text-center">Statut</th>
                                        @can('formation-show')
                                            <th width='3%'><i class="bi bi-gear"></i></th>
                                        @endcan
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($formations as $formation)
                                        <tr>
                                            <td style="text-align: center">{{ $formation?->code }}</td>
                                            <td style="text-align: center">{{ $formation?->numero_convention }}</td>
                                            {{-- <td>{{ $formation->types_formation->name ?? 'Non spécifié' }}</td> --}}
                                            {{--  <td>{{ $formation->departement->region->nom ?? 'Non spécifié' }}</td> --}}
                                            <td>{{ $formation?->name ?? ' ' }}</td>
                                            <td>
                                                <span
                                                    class="{{ $formation->module->name ?? ($formation->collectivemodule->module ?? 'Aucun') }}">
                                                    {{ $formation->module->name ?? ($formation->collectivemodule->module ?? 'Aucun') }}
                                                </span>
                                            </td>
                                            {{-- <td>{{ $formation->type_certification }}</td> --}}
                                            <td>{{ $formation?->titre ?? $formation?->referentiel?->titre }}</td>
                                            <td class="text-center">{{ $formation?->operateur?->user?->username ?? ' ' }}
                                            </td>
                                            <td class="text-center">
                                                <a><span
                                                        class="{{ $formation->statut }}">{{ $formation->statut }}</span></a>
                                            </td>
                                            @can('formation-show')
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <!-- Bouton Voir détails -->
                                                        <a href="{{ route('formations.show', $formation) }}"
                                                            class="btn btn-primary btn-sm" title="Voir les détails">
                                                            <i class="bi bi-eye"></i>
                                                        </a>

                                                        <!-- Menu déroulant d'actions -->
                                                        <div class="dropdown">
                                                            <a href="#" class="btn btn-sm btn-light"
                                                                data-bs-toggle="dropdown" aria-expanded="false"
                                                                title="Plus d'actions">
                                                                <i class="bi bi-three-dots-vertical"></i>
                                                            </a>
                                                            <ul class="dropdown-menu dropdown-menu-end">
                                                                @can('formation-update')
                                                                    <li>
                                                                        <a href="{{ route('formations.edit', $formation) }}"
                                                                            class="dropdown-item">
                                                                            <i class="bi bi-pencil"></i> Modifier
                                                                        </a>
                                                                    </li>
                                                                @endcan
                                                                @can('formation-delete')
                                                                    <li>
                                                                        <form
                                                                            action="{{ route('formations.destroy', $formation) }}"
                                                                            method="POST" class="dropdown-item show_confirm">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="submit"
                                                                                class="dropdown-item text-danger">
                                                                                <i class="bi bi-trash"></i> Supprimer
                                                                            </button>
                                                                        </form>
                                                                    </li>
                                                                @endcan
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </td>
                                            @endcan
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="alert alert-info">Aucune formation créée pour l'instant !</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 d-flex flex-column align-items-center justify-content-center">
            <div class="modal fade" id="AddFormationModal" tabindex="-1">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <form method="post" action="{{ route('formations.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="card-header text-center bg-gradient-default">
                                <h1 class="h4 text-black mb-0">CRÉER FORMATION</h1>
                            </div>
                            <div class="modal-body">
                                <div class="row g-3">
                                    <div class="col-12 col-md-12 col-lg-8 col-sm-12 col-xs-12 col-xxl-8">
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
                                    </div>

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
                                        <label for="types_formation" class="form-label">Type formation<span
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
                                    <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-printer"></i>
                                        Enregistrer</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="generate_rapportFormation" tabindex="-1" role="dialog"
            aria-labelledby="generate_rapportFormationLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="card-header text-center bg-gradient-default">
                        <h1 class="h4 text-black mb-0">Générer rapport</h1>
                    </div>
                    <form method="post" action="{{ route('formations.reports') }}">
                        @csrf
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="annee" class="form-label">Année<span
                                            class="text-danger mx-1">*</span></label>
                                    <select name="annee"
                                        class="form-select form-select-sm @error('annee') is-invalid @enderror"
                                        aria-label="Select" id="select-field-formation-annee-rapport"
                                        data-placeholder="Choisir année">
                                        <option value="{{ old('annee') }}">
                                            {{ old('annee') }}
                                        </option>
                                        @foreach ($formations_annee as $anneeformation)
                                            <option value="{{ $anneeformation->annee }}">
                                                {{ $anneeformation->annee }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('annee')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="region" class="form-label">Statut<span
                                            class="text-danger mx-1">*</span></label>
                                    <select name="statut"
                                        class="form-select form-select-sm @error('statut') is-invalid @enderror"
                                        aria-label="Select" id="select-field-formation-region-rapport"
                                        data-placeholder="Choisir statut">
                                        <option value="{{ old('statut') }}">
                                            {{ old('statut') }}
                                        </option>
                                        <option value="Tous">Tous</option>
                                        @foreach ($formations_statut as $statutformation)
                                            <option value="{{ $statutformation->statut }}">
                                                {{ $statutformation->statut }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('statut')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary btn-sm"
                                            data-bs-dismiss="modal">Fermer</button>
                                        <div class="text-center">
                                            <button type="submit"
                                                class="btn btn-primary btn-block submit_rapport btn-sm">Générer</button>
                                        </div>
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
        new DataTable('#table-formations', {
            layout: {
                topStart: {
                    buttons: ['excel', 'pdf', 'print'],
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
