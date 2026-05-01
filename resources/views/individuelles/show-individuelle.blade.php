@extends('layout.user-layout')
@section('title', 'Dossier ' . strtoupper($user?->civilite . ' ' . $user?->firstname . ' ' . $user?->name))
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

                    {{-- HEADER GLOBAL --}}
                    <div class="card-body bg-light border-bottom">

                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                            <div>
                                <h4 class="fw-bold text-primary mb-1">
                                    <i class="bi bi-folder-check me-2"></i>
                                    Dossier n° {{ $user?->cin ?? '-' }}
                                </h4>

                                {{-- <small class="text-muted">
                                    {{ $user->civilite }} {{ $user->firstname }} {{ $user->name }}
                                </small> --}}
                            </div>

                            <a href="{{ url('/profil') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-arrow-left"></i> Retour
                            </a>

                        </div>

                    </div>

                    {{-- INFORMATIONS DEMANDE --}}
                    <div class="card-body border-bottom">
                        <div class="row g-4">

                            {{-- INFORMATIONS PERSONNELLES --}}
                            <div class="col-md-6">
                                <h6 class="fw-bold text-primary mb-3">
                                    <i class="bi bi-person-vcard me-1"></i>
                                    Informations personnelles
                                </h6>

                                <div class="small text-muted">

                                    <div class="mb-2">
                                        <strong>Prénom :</strong>
                                        {{ $user->firstname }}
                                    </div>

                                    <div class="mb-2">
                                        <strong>Nom :</strong>
                                        {{ $user->name }}
                                    </div>

                                    <div class="mb-2">
                                        <strong>Date de naissance :</strong>
                                        {{ optional($user->date_naissance)->format('d/m/Y') ?? '-' }}
                                    </div>

                                    <div class="mb-2">
                                        <strong>Lieu de naissance :</strong>
                                        {{ $user->lieu_naissance ?? '-' }}
                                    </div>

                                    <div class="mb-2">
                                        <strong>Email :</strong>
                                        <a href="mailto:{{ $user->email }}">
                                            {{ $user->email }}
                                        </a>
                                    </div>

                                    <div class="mb-2">
                                        <strong>Téléphone :</strong>
                                        <a href="tel:+221{{ $user->telephone }}">
                                            {{ $user->telephone }}
                                        </a>
                                    </div>

                                    <div class="mb-0">
                                        <strong>Adresse :</strong>
                                        {{ $user->adresse ?? '-' }}
                                    </div>

                                </div>
                            </div>
                            {{-- QUESTIONNAIRE --}}
                            <div class="col-md-6">

                                <h6 class="fw-bold text-info mb-3">
                                    <i class="bi bi-clipboard-check me-1"></i>
                                    Questionnaire de suivi post-formation
                                </h6>

                                <div class="small">

                                    @if ($hasFormedModule)
                                        <div class="card border-0 shadow-sm">
                                            <div class="card-body">

                                                <p class="text-muted mb-3">
                                                    Vous pouvez remplir le questionnaire de suivi post-formation en cliquant
                                                    sur le bouton ci-dessous.
                                                </p>

                                                {{-- <a href="{{ route('individuelles.suivi.formulaire') }}"
                                                    class="btn btn-info btn-sm">

                                                    <i class="bi bi-pencil-square me-1"></i>
                                                    Ouvrir le questionnaire
                                                </a> --}}
                                                <a href="{{ route('individuelles.suivi.modules') }}"
                                                    class="btn btn-info btn-sm">
                                                    <i class="bi bi-pencil-square me-1"></i>
                                                    Ouvrir le questionnaire
                                                </a>

                                            </div>
                                        </div>
                                    @else
                                        <div class="alert alert-warning mb-0">
                                            Vous n’avez pas encore été formé sur un module.
                                            Le questionnaire sera disponible après validation de votre formation.
                                        </div>
                                    @endif

                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-5">
                            <button type="button" class="btn btn-info btn-sm">
                                <span class="badge bg-white text-info">{{ $individuelle_total }} sur 3</span>
                            </button>
                            @if ($individuelle_total < 3 && !empty($user?->cin))
                                <button type="button"
                                    class="btn btn-success btn-sm float-end rounded-pill px-4 shadow-sm d-flex align-items-center gap-2"
                                    data-bs-toggle="modal" data-bs-target="#AddIndividuelleModal{{ $user?->id }}">
                                    <i class="bi bi-plus-circle-fill"></i>
                                    Ajouter
                                </button>
                            @endif
                        </div>

                        <div class="table-responsive">
                            <table class="table align-middle table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th width="8%" class="text-center">Choix n°</th>
                                        <th>Module</th>
                                        <th width="8%">Dépôt</th>
                                        <th>Département</th>
                                        <th>Région</th>
                                        <th width="15%">Niveau étude</th>
                                        <th width="15%">Diplôme</th>
                                        <th width="5%">Statut</th>
                                        <th style="width:5%;"><i class="bi bi-gear"></i></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i = 1; @endphp
                                    @foreach ($individuelles as $individuelle)
                                        <tr>
                                            <td class="text-center">{{ $i++ }}</td>
                                            <td class="fw-semibold text-primary">{{ $individuelle?->module?->name }}</td>
                                            <td>{{ $individuelle?->date_depot?->format('d/m/Y') }}</td>
                                            <td>{{ $individuelle?->departement?->nom }}</td>
                                            <td>{{ $individuelle?->departement?->region?->nom }}</td>
                                            <td>{{ $individuelle?->niveau_etude }}</td>
                                            <td>{{ $individuelle?->diplome_academique }}</td>
                                            <td>
                                                <span class="{{ $individuelle?->statut }}">{{ $individuelle?->statut }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="d-flex align-items-baseline">
                                                    <a href="{{ route('individuelles.show', $individuelle) }}"
                                                        class="btn btn-success btn-sm" title="voir détails"><i
                                                            class="bi bi-eye"></i></a>
                                                    <div class="filter">
                                                        <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                                                class="bi bi-three-dots"></i></a>
                                                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                                            <li><a class="dropdown-item btn btn-sm"
                                                                    href="{{ route('individuelles.edit', $individuelle) }}"
                                                                    class="mx-1" title="Modifier"><i
                                                                        class="bi bi-pencil"></i>Modifier</a>
                                                            </li>
                                                            <li>
                                                                <form
                                                                    action="{{ route('individuelles.destroy', $individuelle) }}"
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

                        @can('upload-file-view')

                            <div class="container-fluid pt-4">
                                <div class="row g-4">

                                    <div class="col-12 col-lg-5">

                                        <div class="card shadow-sm h-100">
                                            <div class="card-body">

                                                <h5 class="card-title mb-3">
                                                    <i class="bi bi-upload me-1"></i>
                                                    Joindre un document
                                                </h5>


                                                <form method="post" action="{{ route('files.update', $user?->uuid) }}"
                                                    enctype="multipart/form-data">

                                                    @csrf
                                                    @method('patch')

                                                    <input type="hidden" name="idUser" value="{{ $user?->id }}">

                                                    <div
                                                        class="alert border-0 shadow-sm rounded-4 p-4 mb-4 bg-warning bg-opacity-10">

                                                        <div class="d-flex align-items-center mb-3">
                                                            <div class="me-3">
                                                                <i
                                                                    class="bi bi-exclamation-triangle-fill text-warning fs-4"></i>
                                                            </div>
                                                            <div>
                                                                <h6 class="mb-0 fw-bold text-warning">
                                                                    NB : Documents requis
                                                                </h6>
                                                            </div>
                                                        </div>

                                                        <ul class="mb-0 ps-4 small text-dark">
                                                            <li class="mb-2">
                                                                <i class="bi bi-card-text text-secondary me-2"></i>
                                                                La carte nationale d'identité (recto/verso)
                                                            </li>
                                                            <li class="mb-2">
                                                                <i class="bi bi-file-earmark-person text-secondary me-2"></i>
                                                                CV : Un curriculum vitae (optionnel)
                                                            </li>
                                                            <li>
                                                                <i class="bi bi-award text-secondary me-2"></i>
                                                                Diplômes ou attestations (si disponibles)
                                                            </li>
                                                        </ul>

                                                    </div>

                                                    {{-- Légende --}}
                                                    <div class="mb-3">
                                                        <label class="form-label">
                                                            Légende <span class="text-danger">*</span>
                                                        </label>

                                                        <select name="legende"
                                                            class="form-select form-select-sm @error('legende') is-invalid @enderror">

                                                            <option value="">Choisir...</option>

                                                            @foreach ($user_files as $file)
                                                                <option value="{{ $file?->id }}">
                                                                    {{ $file?->legende }}
                                                                </option>
                                                            @endforeach

                                                        </select>

                                                        @error('legende')
                                                            <div class="invalid-feedback">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>

                                                    {{-- Fichier --}}
                                                    <div class="mb-3">
                                                        <label class="form-label">
                                                            Fichier <span class="text-danger">*</span>
                                                        </label>

                                                        <input type="file" name="file"
                                                            class="form-control form-control-sm @error('file') is-invalid @enderror">

                                                        @error('file')
                                                            <div class="text-danger small">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>

                                                    {{-- Bouton --}}
                                                    <div class="text-end">
                                                        <button type="submit" class="btn btn-primary btn-sm">
                                                            <i class="bi bi-upload me-1"></i>
                                                            Téléverser
                                                        </button>
                                                    </div>

                                                </form>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-7">

                                        <div class="card shadow-sm h-100">

                                            <div class="card-body">

                                                <h5 class="card-title mb-3">
                                                    <i class="bi bi-folder2-open me-1"></i>
                                                    Fichiers joints
                                                </h5>

                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-sm table-hover align-middle">

                                                        <thead class="table-light">
                                                            <tr>
                                                                <th style="width:5%">N°</th>
                                                                <th>Légende</th>
                                                                <th style="width:10%">Fichier</th>
                                                                <th style="width:10%" class="text-center">Statut</th>
                                                                <th style="width:10%" class="text-center">Actions</th>
                                                            </tr>
                                                        </thead>

                                                        <tbody>
                                                            @php $i = 1; @endphp

                                                            @foreach ($files as $file)
                                                                <tr>
                                                                    <td>{{ $i++ }}</td>
                                                                    <td class="text-start">{{ $file->legende }}</td>

                                                                    <td>
                                                                        <a class="btn btn-outline-secondary btn-sm"
                                                                            target="_blank"
                                                                            href="{{ asset($file->getFichier()) }}">
                                                                            <i class="bi bi-download"></i>
                                                                        </a>
                                                                    </td>

                                                                    <td class="text-center">

                                                                        <span class="{{ $file?->statut }}">
                                                                            {{ $file?->statut }}
                                                                        </span>
                                                                    </td>

                                                                    {{-- <td>
                                                                        <button class="btn btn-outline-danger btn-sm">
                                                                            <i class="bi bi-trash"></i>
                                                                        </button>
                                                                    </td> --}}
                                                                    <td class="text-center">
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
                            {{-- <div class="row mb-3 pt-5">
                                <h5 class="card-title col-12 col-md-4">
                                    FICHIERS JOINTS</h5>
                                <div class="col-12 col-md-8">
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
                            </div> --}}
                            {{-- <form method="post" action="{{ route('files.update', $user?->uuid) }}"
                                enctype="multipart/form-data">
                                @csrf
                                @method('patch')
                                <h5 class="card-title">JOINDRE VOS SCANS DE DOSSIERS</h5>
                                <input type="hidden" name="idUser" value="{{ $user->id }}">
                                <span style="color:red;">NB:</span>
                                <span>Seule la Carte Nationale d'Identité (recto/verso) </span><span style="color:red;"> est
                                    requise</span>.
                                <div class="row mb-3 mt-3">
                                    <label for="legende" class="col-12 col-md-4 col-form-label">LEGENDE<span
                                            class="text-danger mx-1">*</span></label>
                                    <div class="col-12 col-md-8">
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
                                    <label for="file" class="col-12 col-md-4 col-form-label">CHOISIR FICHIER<span
                                            class="text-danger mx-1">*</span></label>
                                    <div class="col-12 col-md-8">
                                        <div class="pt-2">
                                            <input type="file" name="file" id="file"
                                                class="form-control @error('file') is-invalid @enderror btn btn-primary btn-sm">
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
                            </form> --}}
                        @endcan
                    </div>
                </div>
            </div>
        </div>

        {{-- Ajouter un autre choix --}}

        @foreach ($user?->individuelles as $individuelle)
            <div class="col-12 d-flex flex-column align-items-center justify-content-center">
                <div class="modal fade" id="AddIndividuelleModal{{ $user?->id }}" tabindex="-1">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <form method="post" action="{{ route('individuelles.store') }}"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="card shadow-lg border-0">
                                    <div class="card-header bg-default text-center py-2 rounded-top">
                                        <h4 class="mb-0">➕ Formuler une autre demande individuelle</h4>
                                    </div>

                                    <div class="modal-body row g-4 px-4">
                                        <div class="modal-body">
                                            <div class="row g-3">
                                                <input type="hidden" name="iduser"
                                                    value="{{ $individuelle->users_id }}">
                                                <div class="col-12 col-md-12 col-lg-8 col-sm-12 col-xs-12 col-xxl-8">
                                                    <label for="module" class="form-label">Formation sollicitée
                                                        (module)<span class="text-danger mx-1">*</span></label>

                                                    <input type="text" name="module"
                                                        value="{{ old('module_name') }}"
                                                        class="form-control form-control-sm @error('module_name') is-invalid @enderror"
                                                        id="module_name" placeholder="Formation choisie" autofocus>
                                                    <div id="countryList"></div>
                                                    {{ csrf_field() }}
                                                    @error('module')
                                                        <span class="invalid-feedback" role="alert">
                                                            <div>{{ $message }}</div>
                                                        </span>
                                                    @enderror

                                                    {{-- <select name="module" class="form-select  @error('module') is-invalid @enderror"
                                            aria-label="Select" id="select-field-module-ind"
                                            data-placeholder="Choisir formation">
                                            <option value="">
                                                {{ old('module') }}
                                            </option>
                                            @foreach ($modules as $module)
                                                <option value="{{ $module->id }}">
                                                    {{ $module->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('module')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror --}}
                                                </div>

                                                {{-- <div class="col-12 col-md-12 col-lg-6 col-sm-12 col-xs-12 col-xxl-4">
                                        <label for="autre_module" class="form-label">Si autre formation ? précisez</label>
                                        <input type="text" name="autre_module" value="{{ old('autre_module') }}"
                                            class="form-control form-control-sm @error('autre_module') is-invalid @enderror"
                                            id="autre_module" placeholder="autre diplôme académique">
                                        @error('autre_module')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div> --}}

                                                <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                                    <label for="departement" class="form-label">Lieu de formation<span
                                                            class="text-danger mx-1">*</span></label>
                                                    <select name="departement"
                                                        class="form-select  @error('departement') is-invalid @enderror"
                                                        aria-label="Select" id="select-field-departement-ind"
                                                        data-placeholder="Choisir la localité">
                                                        <option value="{{ $individuelle?->departement?->nom }}">
                                                            {{ $individuelle?->departement?->nom ?? old('departement') }}
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

                                                <div class="col-12 col-md-12 col-lg-6 col-sm-12 col-xs-12 col-xxl-4">
                                                    <label for="adresse" class="form-label">Adresse<span
                                                            class="text-danger mx-1">*</span></label>
                                                    <input type="text" name="adresse"
                                                        value="{{ $individuelle?->adresse ?? old('adresse') }}"
                                                        class="form-control form-control-sm @error('adresse') is-invalid @enderror"
                                                        id="adresse" placeholder="adresse">
                                                    @error('adresse')
                                                        <span class="invalid-feedback" role="alert">
                                                            <div>{{ $message }}</div>
                                                        </span>
                                                    @enderror
                                                </div>

                                                <div class="col-12 col-md-12 col-lg-6 col-sm-12 col-xs-12 col-xxl-4">
                                                    <label for="telephone_secondaire" class="form-label">Téléphone
                                                        secondaire<span class="text-danger mx-1">*</span></label>
                                                    <input name="telephone_secondaire" type="text" maxlength="12"
                                                        class="form-control form-control-sm @error('telephone_secondaire') is-invalid @enderror"
                                                        id="telephone_secondaire"
                                                        value="{{ old('telephone_secondaire', str_replace(' ', '', $individuelle->telephone) ?? '') }}"
                                                        autocomplete="tel" placeholder="Téléphone">
                                                    @error('telephone_secondaire')
                                                        <span class="invalid-feedback" role="alert">
                                                            <div>{{ $message }}</div>
                                                        </span>
                                                    @enderror
                                                </div>

                                                <div class="col-12 col-md-12 col-lg-6 col-sm-12 col-xs-12 col-xxl-4">
                                                    <label for="Niveau étude" class="form-label">Niveau étude<span
                                                            class="text-danger mx-1">*</span></label>
                                                    <select name="niveau_etude"
                                                        class="form-select  @error('niveau_etude') is-invalid @enderror"
                                                        aria-label="Select" id="select-field-niveau_etude-ind"
                                                        data-placeholder="Choisir niveau étude">
                                                        <option
                                                            value="{{ $individuelle->niveau_etude ?? old('niveau_etude') }}">
                                                            {{ $individuelle->niveau_etude ?? old('niveau_etude') }}
                                                        </option>
                                                        <option value="Aucun">
                                                            Aucun
                                                        </option>
                                                        <option value="Arabe">
                                                            Arabe
                                                        </option>
                                                        <option value="Elementaire">
                                                            Elementaire
                                                        </option>
                                                        <option value="Secondaire">
                                                            Secondaire
                                                        </option>
                                                        <option value="Moyen">
                                                            Moyen
                                                        </option>
                                                        <option value="Supérieur">
                                                            Supérieur
                                                        </option>
                                                    </select>
                                                    @error('niveau_etude')
                                                        <span class="invalid-feedback" role="alert">
                                                            <div>{{ $message }}</div>
                                                        </span>
                                                    @enderror
                                                </div>

                                                <div class="col-12 col-md-12 col-lg-6 col-sm-12 col-xs-12 col-xxl-4">
                                                    <label for="diplome_academique" class="form-label">Diplôme
                                                        académique<span class="text-danger mx-1">*</span></label>
                                                    <select name="diplome_academique"
                                                        class="form-select  @error('diplome_academique') is-invalid @enderror"
                                                        aria-label="Select" id="select-field-diplome_academique-ind"
                                                        data-placeholder="Choisir diplôme académique">
                                                        <option
                                                            value="{{ $individuelle->diplome_academique ?? old('diplome_academique') }}">
                                                            {{ $individuelle->diplome_academique ?? old('diplome_academique') }}
                                                        </option>
                                                        <option value="Aucun">
                                                            Aucun
                                                        </option>
                                                        <option value="Arabe">
                                                            Arabe
                                                        </option>
                                                        <option value="CFEE">
                                                            CFEE
                                                        </option>
                                                        <option value="BFEM">
                                                            BFEM
                                                        </option>
                                                        <option value="BAC">
                                                            BAC
                                                        </option>
                                                        <option value="Licence">
                                                            Licence
                                                        </option>
                                                        <option value="Master 2">
                                                            Master 2
                                                        </option>
                                                        <option value="Doctorat">
                                                            Doctorat
                                                        </option>
                                                        <option value="Autre">
                                                            Autre
                                                        </option>
                                                    </select>
                                                    @error('diplome_academique')
                                                        <span class="invalid-feedback" role="alert">
                                                            <div>{{ $message }}</div>
                                                        </span>
                                                    @enderror
                                                </div>

                                                <div class="col-12 col-md-12 col-lg-6 col-sm-12 col-xs-12 col-xxl-4">
                                                    <label for="etablissement_academique" class="form-label">Etablissement
                                                        académique</label>
                                                    <input type="text" name="etablissement_academique"
                                                        value="{{ $individuelle?->etablissement_academique ?? old('etablissement_academique') }}"
                                                        class="form-control form-control-sm @error('etablissement_academique') is-invalid @enderror"
                                                        id="etablissement_academique"
                                                        placeholder="Etablissement obtention">
                                                    @error('etablissement_academique')
                                                        <span class="invalid-feedback" role="alert">
                                                            <div>{{ $message }}</div>
                                                        </span>
                                                    @enderror
                                                </div>

                                                <div class="col-12 col-md-12 col-lg-6 col-sm-12 col-xs-12 col-xxl-4">
                                                    <label for="autre_diplome_academique" class="form-label">Si autre ?
                                                        précisez</label>
                                                    <input type="text" name="autre_diplome_academique"
                                                        value="{{ $individuelle?->autre_diplome_academique ?? old('autre_diplome_academique') }}"
                                                        class="form-control form-control-sm @error('autre_diplome_academique') is-invalid @enderror"
                                                        id="autre_diplome_academique"
                                                        placeholder="autre diplôme académique">
                                                    @error('autre_diplome_academique')
                                                        <span class="invalid-feedback" role="alert">
                                                            <div>{{ $message }}</div>
                                                        </span>
                                                    @enderror
                                                </div>
                                                <div class="col-12 col-md-12 col-lg-6 col-sm-12 col-xs-12 col-xxl-4">
                                                    <label for="option_diplome_academique" class="form-label">Option du
                                                        diplôme</label>
                                                    <input type="text" name="option_diplome_academique"
                                                        value="{{ $individuelle?->option_diplome_academique ?? old('option_diplome_academique') }}"
                                                        class="form-control form-control-sm @error('option_diplome_academique') is-invalid @enderror"
                                                        id="option_diplome_academique" placeholder="Ex: Mathématiques">
                                                    @error('option_diplome_academique')
                                                        <span class="invalid-feedback" role="alert">
                                                            <div>{{ $message }}</div>
                                                        </span>
                                                    @enderror
                                                </div>
                                                <div class="col-12 col-md-12 col-lg-6 col-sm-12 col-xs-12 col-xxl-4">
                                                    <label for="diplome_pro" class="form-label">Diplôme professionnel<span
                                                            class="text-danger mx-1">*</span></label>
                                                    <select name="diplome_professionnel"
                                                        class="form-select  @error('diplome_professionnel') is-invalid @enderror"
                                                        aria-label="Select" id="select-field-diplome_professionnel-ind"
                                                        data-placeholder="Choisir diplôme professionnel">
                                                        <option
                                                            value="{{ $individuelle?->diplome_professionnel ?? old('diplome_professionnel') }}">
                                                            {{ $individuelle?->diplome_professionnel ?? old('diplome_professionnel') }}
                                                        </option>
                                                        <option value="Aucun">
                                                            Aucun
                                                        </option>
                                                        <option value="CAP">
                                                            CAP
                                                        </option>
                                                        <option value="BEP">
                                                            BEP
                                                        </option>
                                                        <option value="BT">
                                                            BT
                                                        </option>
                                                        <option value="BTS">
                                                            BTS
                                                        </option>
                                                        <option value="CPS">
                                                            CPS
                                                        </option>
                                                        <option value="L3 Pro">
                                                            L3 Pro
                                                        </option>
                                                        <option value="DTS">
                                                            DTS
                                                        </option>
                                                        <option value="Autre">
                                                            Autre
                                                        </option>
                                                    </select>
                                                    @error('diplome_professionnel')
                                                        <span class="invalid-feedback" role="alert">
                                                            <div>{{ $message }}</div>
                                                        </span>
                                                    @enderror
                                                </div>

                                                <div class="col-12 col-md-12 col-lg-6 col-sm-12 col-xs-12 col-xxl-4">
                                                    <label for="autre_diplome_professionnel" class="form-label">Si autre ?
                                                        précisez</label>
                                                    <input type="text" name="autre_diplome_professionnel"
                                                        value="{{ $individuelle?->autre_diplome_professionnel ?? old('autre_diplome_professionnel') }}"
                                                        class="form-control form-control-sm @error('autre_diplome_professionnel') is-invalid @enderror"
                                                        id="autre_diplome_professionnel"
                                                        placeholder="autre diplôme professionnel ou attestations">
                                                    @error('autre_diplome_professionnel')
                                                        <span class="invalid-feedback" role="alert">
                                                            <div>{{ $message }}</div>
                                                        </span>
                                                    @enderror
                                                </div>

                                                <div class="col-12 col-md-12 col-lg-6 col-sm-12 col-xs-12 col-xxl-4">
                                                    <label for="etablissement_professionnel"
                                                        class="form-label">Etablissement
                                                        professionnel</label>
                                                    <input type="text" name="etablissement_professionnel"
                                                        value="{{ $individuelle?->etablissement_professionnel ?? old('etablissement_professionnel') }}"
                                                        class="form-control form-control-sm @error('etablissement_professionnel') is-invalid @enderror"
                                                        id="etablissement_professionnel"
                                                        placeholder="Etablissement obtention">
                                                    @error('etablissement_professionnel')
                                                        <span class="invalid-feedback" role="alert">
                                                            <div>{{ $message }}</div>
                                                        </span>
                                                    @enderror
                                                </div>

                                                <div class="col-12 col-md-12 col-lg-6 col-sm-12 col-xs-12 col-xxl-4">
                                                    <label for="specialite_diplome_professionnel"
                                                        class="form-label">Spécialité</label>
                                                    <input type="text" name="specialite_diplome_professionnel"
                                                        value="{{ $individuelle?->specialite_diplome_professionnel ?? old('specialite_diplome_professionnel') }}"
                                                        class="form-control form-control-sm @error('specialite_diplome_professionnel') is-invalid @enderror"
                                                        id="specialite_diplome_professionnel"
                                                        placeholder="Ex: électricité">
                                                    @error('specialite_diplome_professionnel')
                                                        <span class="invalid-feedback" role="alert">
                                                            <div>{{ $message }}</div>
                                                        </span>
                                                    @enderror
                                                </div>

                                                <div class="col-12 col-md-12 col-lg-6 col-sm-12 col-xs-12 col-xxl-4">
                                                    <label for="projet_poste_formation" class="form-label">Votre projet
                                                        après la
                                                        formation<span class="text-danger mx-1">*</span></label>
                                                    <select name="projet_poste_formation"
                                                        class="form-select  @error('projet_poste_formation') is-invalid @enderror"
                                                        aria-label="Select" id="select-field-projet_poste_formation-ind"
                                                        data-placeholder="Choisir projet">
                                                        <option
                                                            value="{{ $individuelle?->projet_poste_formation ?? old('projet_poste_formation') }}">
                                                            {{ $individuelle?->projet_poste_formation ?? old('projet_poste_formation') }}
                                                        </option>
                                                        <option value="Poursuivre mes études">
                                                            Poursuivre mes études
                                                        </option>
                                                        <option value="Chercher un emploi">
                                                            Chercher un emploi
                                                        </option>
                                                        <option value="Lancer mon entreprise">
                                                            Lancer mon entreprise
                                                        </option>
                                                        <option value="Retourner dans mon entreprise">
                                                            Retourner dans mon entreprise
                                                        </option>
                                                        <option value="Aucun de ces projets">
                                                            Aucun de ces projets
                                                        </option>
                                                    </select>
                                                    @error('projet_poste_formation')
                                                        <span class="invalid-feedback" role="alert">
                                                            <div>{{ $message }}</div>
                                                        </span>
                                                    @enderror
                                                </div>

                                                <div class="col-12 mb-0">
                                                    <label for="qualification" class="form-label">Qualification et autres
                                                        diplômes</label>
                                                    <textarea name="qualification" id="qualification" rows="1"
                                                        class="form-control form-control-sm @error('qualification') is-invalid @enderror"
                                                        placeholder="Qualification et autres diplômes">{{ $individuelle?->qualification ?? old('qualification') }}</textarea>
                                                    @error('qualification')
                                                        <span class="invalid-feedback" role="alert">
                                                            <div>{{ $message }}</div>
                                                        </span>
                                                    @enderror
                                                </div>

                                                <div class="col-12 mb-0">
                                                    <label for="experience" class="form-label">Expériences et
                                                        stages</label>
                                                    <textarea name="experience" id="experience" rows="1"
                                                        class="form-control form-control-sm @error('experience') is-invalid @enderror"
                                                        placeholder="Expériences ou stages">{{ $individuelle?->experience ?? old('experience') }}</textarea>
                                                    @error('experience')
                                                        <span class="invalid-feedback" role="alert">
                                                            <div>{{ $message }}</div>
                                                        </span>
                                                    @enderror
                                                </div>

                                                <div class="col-12 mb-0">
                                                    <label for="projetprofessionnel" class="form-label">Informations
                                                        complémentaires
                                                        sur
                                                        le projet
                                                        professionnel<span class="text-danger mx-1">*</span></label>
                                                    <textarea name="projetprofessionnel" id="projetprofessionnel" rows="5"
                                                        class="form-control form-control-sm @error('projetprofessionnel') is-invalid @enderror"
                                                        placeholder="Si vous disposez déjà d'un projet professionnel, merci d'écrire son résumé en quelques lignes">{{ $individuelle?->projetprofessionnel ?? old('projetprofessionnel') }}</textarea>
                                                    @error('projetprofessionnel')
                                                        <span class="invalid-feedback" role="alert">
                                                            <div>{{ $message }}</div>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            {{-- <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary btn-sm"
                                            data-bs-dismiss="modal">Fermer</button>
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            Ajouter</button>
                                    </div> --}}
                                        </div>

                                        <div class="card-footer d-flex justify-content-end gap-2 p-3 bg-light border-top">
                                            <button type="button" class="btn btn-secondary btn-sm"
                                                data-bs-dismiss="modal">
                                                <i class="bi bi-x-circle"></i> Fermer
                                            </button>
                                            <button type="submit" class="btn btn-primary btn-sm">
                                                <i class="bi bi-save"></i> Enregistrer
                                            </button>
                                        </div>
                                    </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </section>
@endsection
