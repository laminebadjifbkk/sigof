@extends('layout.user-layout')
@section('title', remove_accents_uppercase('DOSSIER | AGREMENT'))
@section('space-work')
    <section class="section">
        <div class="row justify-content-center">
            <div class="col-12">
                @if ($message = Session::get('status'))
                    <div class="alert alert-danger bg-danger text-light border-0 alert-dismissible fade show" region="alert">
                        <strong>{{ $message }}</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if ($message = Session::get('success'))
                    <div class="alert alert-success bg-success text-light border-0 alert-dismissible fade show"
                        region="alert">
                        <strong>{{ $message }}</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <div class="alert alert-danger bg-danger text-light border-0 alert-dismissible fade show"
                            role="alert">
                            <strong>{{ $error }}</strong>
                        </div>
                    @endforeach
                @endif
                <div class="card">
                    <div class="card-body">
                        {{-- <div class="d-flex justify-content-between align-items-center mt-0">
                            <span class="d-flex mt-0 align-items-baseline"><a href="{{ url('/profil') }}"
                                    class="btn btn-success btn-sm" title="retour"><i
                                        class="bi bi-arrow-counterclockwise"></i></a>&nbsp;
                                <p> | Profil</p>
                            </span>
                            <button class="btn btn-info btn-sm">
                                <span class="badge bg-white text-info">{{ $operateurA->count() }}</span>
                            </button>
                            @can('devenir-operateur-agrement-ouvert')
                                @can('devenir-operateur-agrement-create')
                                    @can('agrement-ouvert')
                                        <button type="button" class="btn btn-warning btn-sm float-end btn-rounded"
                                            data-bs-toggle="modal" data-bs-target="#AddoperateurModal">
                                            Renouveler agrément
                                        </button>
                                    @elsecan('agrement-fermer')
                                        <span class="text-danger small fw-bold">Les agréments sont actuellement
                                            <span class="text-uppercase">fermés</span></span>
                                    @endcan
                                @endcan
                            @endcan
                        </div> --}}

                        <div class="shadow rounded-3">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center px-4 py-2">
                                <div class="d-flex align-items-center gap-2">
                                    <a href="{{ url('/profil') }}" class="btn btn-outline-success btn-sm" title="Retour">
                                        <i class="bi bi-arrow-counterclockwise"></i>
                                    </a>
                                    <span class="fw-bold">| Profil</span>
                                </div>

                                <div class="d-flex align-items-center gap-2">
                                    <button class="btn btn-info btn-sm position-relative">
                                        <i class="bi bi-person-badge"></i>
                                        <span
                                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-white text-info">
                                            {{ $operateurA->count() }}
                                        </span>
                                    </button>

                                    @can('devenir-operateur-agrement-ouvert')
                                        @can('devenir-operateur-agrement-create')
                                            @can('agrement-ouvert')
                                                <button type="button" class="btn btn-warning btn-sm fw-bold btn-rounded"
                                                    data-bs-toggle="modal" data-bs-target="#AddoperateurModal">
                                                    <i class="bi bi-arrow-repeat me-1"></i> Renouveler agrément
                                                </button>
                                            @elsecan('agrement-fermer')
                                                <span class="text-danger small fw-bold">
                                                    Les agréments sont actuellement <span class="text-uppercase">fermés</span>
                                                </span>
                                            @endcan
                                        @endcan
                                    @endcan
                                </div>
                            </div>
                        </div>

                        {{-- <div class="table-responsive mt-3">
                            <table class="table table-bordered table-hover table-borderless">
                                <thead>
                                    <tr>
                                        <th class="text-center">Année</th>
                                        <th class="text-center">Module</th>
                                        <th class="text-center">Référence</th>
                                        <th class="text-center">Equi. & Infras.</th>
                                        <th class="text-center">Formateurs</th>
                                        <th class="text-center">Localités</th>
                                        <th class="text-center">Type demande</th>
                                        <th class="text-center" width="2%">Etat</th>
                                        <th class="text-center">Statut</th>
                                        <th class="text-center">Quitus</th>
                                        <th class="text-center" width="2%"><i class="bi bi-gear"></i></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($operateurA as $operateur)
                                        <tr>
                                            <td style="text-align: center;">{{ $operateur?->annee_agrement?->format('Y') }}
                                            </td>
                                            <td style="text-align: center;">
                                                <span class="badge bg-info">
                                                    {{ count($operateur->operateurmodules) }}
                                                </span>
                                                @can('devenir-operateur-agrement-ouvert')
                                                    @can('agrement-ouvert')
                                                        <a href="{{ route('operateurs.show', $operateur) }}" target="_blank">
                                                            <i class="bi bi-plus" title="Voir"></i> </a>
                                                    @endcan
                                                @endcan
                                            </td>
                                            <td style="text-align: center;">
                                                <span class="badge bg-info">
                                                    {{ count($operateur->operateureferences) }}
                                                </span>

                                                @can('devenir-operateur-agrement-ouvert')
                                                    @can('agrement-ouvert')
                                                        <a href="{{ route('showReference', ['id' => $operateur->id]) }}"
                                                            target="_blank">
                                                            <i class="bi bi-plus" title="Ajouter, Modifier, Supprimer"></i> </a>
                                                    @endcan
                                                @endcan
                                            </td>
                                            <td style="text-align: center;">
                                                <span class="badge bg-info">
                                                    {{ count($operateur->operateurequipements) }}
                                                </span>

                                                @can('devenir-operateur-agrement-ouvert')
                                                    @can('agrement-ouvert')
                                                        <a href="{{ route('showEquipement', ['id' => $operateur->id]) }}"
                                                            target="_blank">
                                                            <i class="bi bi-plus" title="Ajouter, Modifier, Supprimer"></i> </a>
                                                    @endcan
                                                @endcan
                                            </td>
                                            <td style="text-align: center;">
                                                <span class="badge bg-info">
                                                    {{ count($operateur->operateurformateurs) }}
                                                </span>
                                                @can('devenir-operateur-agrement-ouvert')
                                                    @can('agrement-ouvert')
                                                        <a href="{{ route('showFormateur', ['id' => $operateur->id]) }}"
                                                            target="_blank">
                                                            <i class="bi bi-plus" title="Ajouter, Modifier, Supprimer"></i> </a>
                                                    @endcan
                                                @endcan
                                            </td>
                                            <td style="text-align: center;">
                                                <span class="badge bg-info">
                                                    {{ count($operateur->operateurlocalites) }}
                                                </span>
                                                @can('devenir-operateur-agrement-ouvert')
                                                    @can('agrement-ouvert')
                                                        <a href="{{ route('showLocalite', ['id' => $operateur->id]) }}"
                                                            target="_blank">
                                                            <i class="bi bi-plus" title="Ajouter, Modifier, Supprimer"></i> </a>
                                                    @endcan
                                                @endcan
                                            </td>
                                            <td style="text-align: center;"><span
                                                    class="{{ $operateur?->type_demande }}">{{ $operateur?->type_demande }}</span>
                                            </td>
                                            <td style="text-align: center;">
                                                <span class="{{ $statut_demande }} mb-2">
                                                    {{ $statut_demande }}
                                                </span>
                                            </td>
                                            <td style="text-align: center;">
                                                <span class="{{ $operateur?->statut_agrement }} mb-2">
                                                    {{ $operateur?->statut_agrement }}
                                                </span>
                                            </td>
                                            <td style="text-align: center;">
                                                <a class="btn btn-outline-secondary btn-sm" title="Télécharger le quitus"
                                                    target="_blank" href="{{ asset($operateur?->getQuitus()) }}">
                                                    <i class="bi bi-file-image"></i>
                                                </a>
                                            </td>
                                            <td style="text-align: center;">
                                                @can('devenir-operateur-agrement-show')
                                                    @can('view', $operateur)
                                                        <span class="d-flex align-items-baseline">
                                                            <a href="{{ route('operateurs.show', $operateur) }}"
                                                                class="btn btn-success btn-sm" target="_blank"
                                                                title="voir détails">
                                                                 <i class="bi bi-pencil-square me-1"></i> Ajouter / Modifier
                                                            </a>
                                                            @can('agrement-ouvert')
                                                                <div class="filter">
                                                                    <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                                                            class="bi bi-three-dots"></i></a>
                                                                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                                                        @can('devenir-operateur-agrement-update')
                                                                            @can('update', $operateur)
                                                                                <li>
                                                                                    <button type="button"
                                                                                        class="dropdown-item btn btn-sm mx-1"
                                                                                        data-bs-toggle="modal"
                                                                                        data-bs-target="#EditOperateurModal{{ $operateur->id }}">
                                                                                        <i class="bi bi-pencil" title="Modifier"></i>
                                                                                        Modifier
                                                                                    </button>
                                                                                </li>
                                                                            @endcan
                                                                        @endcan
                                                                        @can('devenir-operateur-agrement-delete')
                                                                            @can('delete', $operateur)
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
                                                                        @endcan
                                                                    </ul>
                                                                </div>
                                                            @endcan
                                                        </span>
                                                    @endcan
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div> --}}

                        @foreach ($operateurA as $operateur)
                            <div class="card mb-4 shadow-sm border-0 w-100">
                                <div
                                    class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="mb-1 text-dark fw-bold d-flex align-items-center">
                                            <i class="bi bi-building text-primary me-2 fs-5"></i>
                                            <span>Année d’agrément :</span>
                                            <span
                                                class="ms-2 text-primary">{{ $operateur?->annee_agrement?->format('Y') }}</span>
                                        </h5>
                                        <div class="d-flex align-items-center mt-1">
                                            <i class="bi bi-arrow-right-circle text-secondary me-2"></i>
                                            <span class="fst-italic">Type de demande :</span>
                                            <span class="ms-2 fw-semibold {{ $operateur?->type_demande }}">
                                                {{ $operateur?->type_demande }}
                                            </span>
                                        </div>
                                    </div>

                                    {{-- Statut sur une seule ligne --}}
                                    <div class="d-flex align-items-center">
                                        <span class="fw-semibold text-muted me-2">Statut :</span>
                                        <span
                                            class="badge {{ $operateur?->statut_agrement }} px-3 py-2 fs-6 shadow-sm rounded-pill">
                                            {{ $operateur?->statut_agrement }}
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
                                                class="btn btn-sm btn-outline-success me-1" title="Ajouter/Modifier">
                                                <i class="bi bi-pencil-square me-1"></i> Ajouter / Modifier
                                            </a>
                                        </div>
                                    </div>


                                    {{-- REFERENCES --}}
                                    <div
                                        class="d-flex justify-content-between align-items-center border-bottom py-1 position-relative">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-bookmark-check text-primary me-2"></i>Références :
                                            <span
                                                class="badge {{ count($operateur->operateureferences) === 0 ? 'bg-danger' : 'bg-info' }} position-absolute top-50 start-50 translate-middle-y"
                                                style="transform: translateX(-50%);">{{ count($operateur->operateureferences) }}</span>
                                        </div>
                                        <div>
                                            <a href="{{ route('showReference', $operateur->id) }}" target="_blank"
                                                class="btn btn-sm btn-outline-success me-1" title="Ajouter/Modifier">
                                                <i class="bi bi-pencil-square me-1"></i> Ajouter / Modifier
                                            </a>
                                        </div>
                                    </div>

                                    {{-- EQUIPEMENTS --}}
                                    <div
                                        class="d-flex justify-content-between align-items-center border-bottom py-1 position-relative">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-hdd-network text-warning me-2"></i>Équipements & Infrastructures
                                            :
                                            <span
                                                class="badge {{ count($operateur->operateurequipements) === 0 ? 'bg-danger' : 'bg-info' }} position-absolute top-50 start-50 translate-middle-y"
                                                style="transform: translateX(-50%);">{{ count($operateur->operateurequipements) }}</span>
                                        </div>
                                        <div>
                                            <a href="{{ route('showEquipement', $operateur->id) }}" target="_blank"
                                                class="btn btn-sm btn-outline-success me-1" title="Ajouter/Modifier">
                                                <i class="bi bi-pencil-square me-1"></i> Ajouter / Modifier
                                            </a>
                                        </div>
                                    </div>

                                    {{-- FORMATEURS --}}
                                    <div
                                        class="d-flex justify-content-between align-items-center border-bottom py-1 position-relative">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-person-workspace text-success me-2"></i>Formateurs :
                                            <span
                                                class="badge {{ count($operateur->operateurformateurs) === 0 ? 'bg-danger' : 'bg-info' }} position-absolute top-50 start-50 translate-middle-y"
                                                style="transform: translateX(-50%);">{{ count($operateur->operateurformateurs) }}</span>
                                        </div>
                                        <div>
                                            <a href="{{ route('showFormateur', $operateur->id) }}" target="_blank"
                                                class="btn btn-sm btn-outline-success me-1" title="Ajouter/Modifier">
                                                <i class="bi bi-pencil-square me-1"></i> Ajouter / Modifier
                                            </a>
                                        </div>
                                    </div>

                                    {{-- LOCALITES --}}
                                    <div
                                        class="d-flex justify-content-between align-items-center border-bottom py-1 position-relative">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-geo-alt text-danger me-2"></i>Localités :
                                            <span
                                                class="badge {{ count($operateur->operateurlocalites) === 0 ? 'bg-danger' : 'bg-info' }} position-absolute top-50 start-50 translate-middle-y"
                                                style="transform: translateX(-50%);">{{ count($operateur->operateurlocalites) }}</span>
                                        </div>
                                        <div>
                                            <a href="{{ route('showLocalite', $operateur->id) }}" target="_blank"
                                                class="btn btn-sm btn-outline-success me-1" title="Ajouter/Modifier">
                                                <i class="bi bi-pencil-square me-1"></i> Ajouter / Modifier
                                            </a>
                                        </div>
                                    </div>

                                    {{-- ÉTAT (sans bouton) --}}
                                    <div
                                        class="d-flex justify-content-between align-items-center border-bottom py-1 position-relative">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-info-circle text-secondary me-2"></i>État de la demande
                                            <span
                                                class="badge {{ $statut_demande === 'invalide' ? 'bg-danger' : 'bg-success' }} position-absolute top-50 start-50 translate-middle-y"
                                                style="transform: translateX(-50%);">{{ $statut_demande }}</span>
                                        </div>
                                    </div>

                                    {{-- QUITUS --}}
                                    <div class="d-flex justify-content-between align-items-center pt-2">
                                        <div>
                                            <i class="bi bi-file-earmark-text text-dark me-2"></i>Quitus
                                        </div>
                                        <div>
                                            <a href="{{ asset($operateur?->getQuitus()) }}" target="_blank"
                                                class="btn btn-sm btn-outline-info" title="Télécharger le Quitus">
                                                <i class="bi bi-download"></i> Télécharger
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                @can('update', $operateur)
                                    <div
                                        class="card-footer bg-light text-center py-3 border-top d-flex justify-content-center gap-3">
                                        {{-- Bouton Modifier --}}
                                        <button class="btn btn-warning btn-sm text-white px-4" title="Modifier"
                                            data-bs-toggle="modal" data-bs-target="#EditOperateurModal{{ $operateur->id }}">
                                            <i class="bi bi-pencil me-1"></i> Modifier
                                        </button>

                                        {{-- Bouton Supprimer --}}
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
                        @endforeach

                        @can('upload-file-view')
                            <hr>
                            <div class="row pt-5">
                                <h5 class="card-title col-12 col-md-4">
                                    FICHIERS JOINTS</h5>
                                <div class="col-12 col-md-8 col-lg-8 col-sm-12 col-xs-12 col-xxl-6">
                                    <table class="table table-bordered table-hover datatables" id="table-iles">
                                        <thead>
                                            <tr class="text-center">
                                                <th style="width: 5%">N°</th>
                                                <th>Légende</th>
                                                <th style="width: 10%">Fichier</th>
                                                <th style="width: 10%">Statut</th>
                                                <th style="width: 10%">Supprimer</th>
                                                @hasanyrole('super-admin|admin|DIOF')
                                                    <th style="width: 10%">Valider</th>
                                                    <th style="width: 10%">Rejeter</th>
                                                @endhasanyrole
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $i = 1; @endphp
                                            @foreach ($files as $file)
                                                <tr class="text-center align-middle">
                                                    <td>{{ $i++ }}</td>
                                                    <td>{{ $file->legende }}</td>
                                                    <td>
                                                        <a class="btn btn-outline-secondary btn-sm" title="Télécharger"
                                                            target="_blank" href="{{ asset($file->getFichier()) }}">
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
                                                        <span class="badge bg-{{ $badgeClass }}">{{ $statut }}</span>
                                                    </td>
                                                    {{-- Supprimer --}}
                                                    <td>
                                                        @if ($file->statut !== 'Validé')
                                                            <form action="{{ route('fileDestroy') }}" method="post"
                                                                class="d-inline">
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

                                                    @hasanyrole('super-admin|admin|DIOF')
                                                        {{-- Valider --}}
                                                        <td>
                                                            <form action="{{ route('fileValidate') }}" method="post"
                                                                class="d-inline">
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
                                                            <form action="{{ route('fileInvalide') }}" method="post"
                                                                class="d-inline">
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
                            <form method="post" action="{{ route('files.update', $operateur?->user) }}"
                                enctype="multipart/form-data">
                                @csrf
                                @method('patch')
                                <input type="hidden" name="idUser" value="{{ $operateur?->user->id }}">
                                <h5 class="card-title">JOINDRE VOS SCANS DE DOSSIERS</h5>
                                <span style="color:red;">NB:</span>
                                <span>Seuls l'acte ou l'arrêté
                                    de création, ainsi que le NINEA ou le registre de commerce,</span> <span
                                    style="color:red;"> sont exigés</span>.
                                <!-- Profile Edit Form -->
                                <div class="row mb-3 mt-3">
                                    <label for="legende" class="col-12 col-md-4 col-form-label">Légende<span
                                            class="text-danger mx-1">*</span></label>
                                    <div class="col-12 col-md-8 col-lg-8 col-sm-12 col-xs-12 col-xxl-6">
                                        <select name="legende" class="form-select  @error('legende') is-invalid @enderror"
                                            aria-label="Select" id="select-field-file" data-placeholder="Choisir">
                                            <option value="{{ old('legende') }}">

                                            </option>
                                            @foreach ($user_files as $file)
                                                <option value="{{ $file?->id }}">
                                                    {{ $file?->legende }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('legende')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="file" class="col-12 col-md-4 col-form-label">Fichier<span
                                            class="text-danger mx-1">*</span></label>
                                    <div class="col-12 col-md-8 col-lg-8 col-sm-12 col-xs-12 col-xxl-6">
                                        <div class="pt-2">
                                            <input type="file" name="file" id="file"
                                                class="form-control @error('file') is-invalid @enderror btn btn-info btn-sm">
                                            @error('file')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="file" class="col-12 col-md-4 col-form-label">
                                        Téléverser un fichier <span class="text-danger mx-1">*</span>
                                    </label>
                                    <div class="col-12 col-md-8">
                                        <div class="pt-2">
                                            <button type="submit" class="btn btn-primary btn-sm text-white">
                                                <i class="bi bi-upload me-1"></i> Téléverser
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        @endcan
                        @foreach ($operateurs as $operateur)
                            <div class="col-lg-12 col-md-12 d-flex flex-column align-items-center justify-content-center">
                                <div class="modal fade" id="validationViewModal{{ $operateur?->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="col-12">
                                                <table
                                                    class="table table-bordered table-hover table-borderless table-stripped">
                                                    <tr>
                                                        <td>Modules</td>
                                                        <td style="text-align: center;"><span
                                                                class="{{ $module_count }}">{{ count($operateur->operateurmodules) }}</span>
                                                        </td>
                                                        <td style="text-align: center;"><a
                                                                href="{{ route('operateurs.show', $operateur->id) }}"
                                                                class="btn btn-outline-primary btn-rounded btn-sm"
                                                                target="_blank">
                                                                <i class="bi bi-plus"
                                                                    title="Ajouter, Modifier, Supprimer"></i> </a></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Références professionnelles</td>
                                                        <td style="text-align: center;"><span
                                                                class="{{ $reference_count }}">{{ count($operateur->operateureferences) }}</span>
                                                        </td>
                                                        <td style="text-align: center;"><a
                                                                href="{{ route('showReference', ['id' => $operateur->id]) }}"
                                                                class="btn btn-outline-primary btn-rounded btn-sm"
                                                                target="_blank">
                                                                <i class="bi bi-plus"
                                                                    title="Ajouter, Modifier, Supprimer"></i> </a></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Infrastructures et Equipements</td>
                                                        <td style="text-align: center;"><span
                                                                class="{{ $equipement_count }}">{{ count($operateur->operateurequipements) }}</span>
                                                        </td>
                                                        <td style="text-align: center;"><a
                                                                href="{{ route('showEquipement', ['id' => $operateur->id]) }}"
                                                                class="btn btn-outline-primary btn-rounded btn-sm"
                                                                target="_blank">
                                                                <i class="bi bi-plus"
                                                                    title="Ajouter, Modifier, Supprimer"></i> </a></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Formateurs</td>
                                                        <td style="text-align: center;"><span
                                                                class="{{ $formateur_count }}">{{ count($operateur->operateurformateurs) }}</span>
                                                        </td>
                                                        <td style="text-align: center;"><a
                                                                href="{{ route('showFormateur', ['id' => $operateur->id]) }}"
                                                                class="btn btn-outline-primary btn-rounded btn-sm"
                                                                target="_blank">
                                                                <i class="bi bi-plus"
                                                                    title="Ajouter, Modifier, Supprimer"></i> </a></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Localités</td>
                                                        <td style="text-align: center;"><span
                                                                class="{{ $localite_count }}">{{ count($operateur->operateurlocalites) }}</span>
                                                        </td>
                                                        <td style="text-align: center;"><a
                                                                href="{{ route('showLocalite', ['id' => $operateur->id]) }}"
                                                                class="btn btn-outline-primary btn-rounded btn-sm"
                                                                target="_blank">
                                                                <i class="bi bi-plus"
                                                                    title="Ajouter, Modifier, Supprimer"></i> </a></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 d-flex flex-column align-items-center justify-content-center">
            <div class="modal fade" id="AddoperateurModal" tabindex="-1">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <form method="POST" action="{{ route('renewOperateur') }}" enctype="multipart/form-data">
                            @csrf

                            {{-- En-tête du formulaire --}}
                            <div class="card-header bg-white border-bottom text-center py-4">
                                <h4 class="text-primary fw-bold mb-0">
                                    <i class="bi bi-arrow-repeat me-2 text-dark"></i>Renouvellement
                                </h4>
                            </div>

                            {{-- Corps du formulaire --}}
                            <div class="modal-body px-4 pt-4">
                                <div class="row g-4">
                                    {{-- Quitus fiscal --}}
                                    <div class="col-lg-6">
                                        <label for="quitus" class="form-label fw-semibold">Quitus fiscal
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="file" name="quitus" id="quitus"
                                            accept=".jpg, .jpeg, .png, .svg, .gif"
                                            class="form-control form-control-sm @error('quitus') is-invalid @enderror">
                                        @error('quitus')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Date du quitus --}}
                                    <div class="col-lg-6">
                                        <label for="date_quitus" class="form-label fw-semibold">Date du visa quitus
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="date_quitus" id="datepicker"
                                            value="{{ old('date_quitus') }}"
                                            class="form-control form-control-sm @error('date_quitus') is-invalid @enderror"
                                            placeholder="JJ/MM/AAAA" autocomplete="bday">
                                        @error('date_quitus')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Pied de modal --}}
                            <div class="modal-footer bg-light mt-4 py-3 px-4">
                                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                                    <i class="bi bi-x-circle me-1"></i>Fermer
                                </button>
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="bi bi-check2-circle me-1"></i>Renouveler l’agrément
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @foreach ($operateurs as $operateur)
            <div class="modal fade" id="EditOperateurModal{{ $operateur->id }}" tabindex="-1"
                aria-labelledby="EditOperateurModalLabel{{ $operateur->id }}" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <form method="POST" action="{{ route('operateurs.updated', $operateur->uuid) }}"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')

                            {{-- En-tête --}}
                            <div class="card-header text-center bg-white border-bottom py-3">
                                <h4 class="text-primary fw-bold mb-0">
                                    <i class="bi bi-pencil-square me-2 text-dark"></i> Modification Opérateur
                                </h4>
                            </div>

                            {{-- Corps --}}
                            <div class="modal-body px-4 pt-4">
                                <input type="hidden" name="id" value="{{ $operateur->id }}">

                                <div class="row g-4">
                                    {{-- Type de demande --}}
                                    <div class="col-lg-6">
                                        <label for="type_demande" class="form-label fw-semibold">Type de demande <span
                                                class="text-danger">*</span></label>
                                        <select name="type_demande" id="select-field-registre"
                                            class="form-select form-select-sm @error('type_demande') is-invalid @enderror">
                                            <option value="{{ $operateur?->type_demande }}">
                                                {{ $operateur?->type_demande }}</option>
                                            <option value="Nouvelle">Nouvelle</option>
                                            <option value="Renouvellement">Renouvellement</option>
                                        </select>
                                        @error('type_demande')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Département --}}
                                    <div class="col-lg-6">
                                        <label for="departement" class="form-label fw-semibold">Département <span
                                                class="text-danger">*</span></label>
                                        <select name="departement" id="select-field-departement-update"
                                            class="form-select form-select-sm @error('departement') is-invalid @enderror">
                                            <option value="{{ $operateur->departement?->nom }}">
                                                {{ $operateur->departement?->nom }}</option>
                                            @foreach ($departements as $departement)
                                                <option value="{{ $departement->nom }}">{{ $departement->nom }}</option>
                                            @endforeach
                                        </select>
                                        @error('departement')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Quitus --}}
                                    <div class="col-lg-6">
                                        <label for="quitus" class="form-label fw-semibold">Quitus fiscal <span
                                                class="text-danger">*</span></label>
                                        <input type="file" name="quitus" id="quitus"
                                            class="form-control form-control-sm @error('quitus') is-invalid @enderror"
                                            accept=".jpg, .jpeg, .png, .svg, .gif">
                                        @error('quitus')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Bouton de téléchargement --}}
                                    <div class="col-lg-1 d-flex align-items-end">
                                        <a href="{{ asset($operateur?->getQuitus()) }}"
                                            class="btn btn-outline-secondary btn-sm" target="_blank"
                                            title="Télécharger le quitus">
                                            <i class="bi bi-file-image"></i>
                                        </a>
                                    </div>

                                    {{-- Date visa quitus --}}
                                    <div class="col-lg-5">
                                        <label for="date_quitus" class="form-label fw-semibold">Date visa quitus <span
                                                class="text-danger">*</span></label>
                                        <input type="date" name="date_quitus" id="date_quitus"
                                            value="{{ $operateur?->debut_quitus?->format('Y-m-d') ?? old('date_quitus') }}"
                                            class="form-control form-control-sm @error('date_quitus') is-invalid @enderror">
                                        @error('date_quitus')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Pied de formulaire --}}
                            <div class="modal-footer bg-light py-3 px-4 mt-4">
                                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                                    <i class="bi bi-x-circle me-1"></i> Fermer
                                </button>
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="bi bi-save me-1"></i> Enregistrer les modifications
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </section>
@endsection
