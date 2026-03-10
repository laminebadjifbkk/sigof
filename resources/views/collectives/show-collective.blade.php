@extends('layout.user-layout')
@section('title', 'DEMANDE COLLECTIVE DE ' . strtoupper($user?->civilite . ' ' . $user?->firstname . ' ' .
    $user?->name))
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
                {{-- <div class="card">
                    <div class="card-header text-center bg-gradient-default">
                        <h1 class="h4 text-black mb-0">DEMANDES COLLECTIVES</h1>
                    </div>
                    <h5 class="card-title m-2">
                        Bienvenue {{ $user->civilite . ' ' . $user->name }}</h5>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">

                            <div class="d-flex align-items-center gap-2">
                                <span class="fw-semibold text-muted">Étape</span>
                                <span class="badge bg-info-subtle text-info px-3 py-2">
                                    1 / 3
                                </span>
                            </div>

                            <a href="{{ url('/profil') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-arrow-left-circle"></i> Retour
                            </a>

                        </div>
                        <table class="table table-bordered table-hover table-borderless">
                            <thead>
                                <tr>
                                    <th scope="col">N° demande</th>
                                    <th scope="col">Nom structure</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Téléphone</th>
                                    <th scope="col">Localité</th>
                                    <th scope="col">Statut</th>
                                    <th class="col"><i class="bi bi-gear"></i></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($user?->collectives as $collective)
                                    @isset($collective?->numero)
                                        <tr>
                                            <td>{{ $collective?->numero }}
                                            </td>
                                            <td>{{ $collective?->name }}
                                                @isset($collective?->sigle)
                                                    {{ '(' . $collective?->sigle . ')' }}
                                                @endisset
                                            </td>
                                            <td><a href="mailto:{{ $collective->email }}">{{ $collective->email }}</a>
                                            </td>
                                            <td><a
                                                    href="tel:+221{{ $collective->telephone }}">{{ $collective->telephone }}</a>
                                            </td>
                                            <td>{{ $collective->departement?->region?->nom }}</td>
                                            <td>
                                                <span
                                                    class="{{ $collective?->statut_demande }}">{{ $collective?->statut_demande }}</span>
                                            </td>
                                            <td>
                                                @can('view', $collective)
                                                    <span class="d-flex align-items-baseline"><a
                                                            href="{{ route('collectives.show', $collective) }}"
                                                            class="btn btn-warning btn-sm" title="voir détails"><i
                                                                class="bi bi-eye"></i></a>
                                                        <div class="filter">
                                                            <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                                                    class="bi bi-three-dots"></i></a>
                                                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                                                @can('update', $collective)
                                                                    <li><a class="dropdown-item btn btn-sm"
                                                                            href="{{ route('collectives.edit', $collective) }}"
                                                                            class="mx-1" title="Modifier"><i
                                                                                class="bi bi-pencil"></i>Modifier</a>
                                                                    </li>
                                                                @endcan
                                                                @can('delete', $collective)
                                                                    <li>
                                                                        <form action="{{ route('collectives.destroy', $collective) }}"
                                                                            method="post">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="submit" class="dropdown-item show_confirm"
                                                                                title="Supprimer"><i
                                                                                    class="bi bi-trash"></i>Supprimer</button>
                                                                        </form>
                                                                    </li>
                                                                @endcan
                                                            </ul>
                                                        </div>
                                                    </span>
                                                @endcan
                                            </td>
                                        </tr>
                                    @endisset
                                @endforeach
                            </tbody>
                        </table>
                        <div class="d-flex align-items-center gap-2">
                            <span class="fw-semibold text-muted">Étape</span>
                            <span class="badge bg-info-subtle text-info px-3 py-2">
                                2 / 3
                            </span>
                        </div>
                        <h5 class="card-title">Formations demandées</h5>
                        @if ($user?->collectives?->flatMap(fn($collective) => $collective->collectivemodules)->isNotEmpty())
                        @else
                            <div class="alert alert-warning">Aucune formation pour le momement
                                !
                            </div>
                        @endif
                    </div>
                </div> --}}
                <div class="card shadow-sm border-0">

                    {{-- HEADER GLOBAL --}}
                    <div class="card-body bg-light border-bottom">

                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                            <div>
                                <h4 class="fw-bold text-primary mb-1">
                                    <i class="bi bi-folder-check me-2"></i>
                                    Dossier n° {{ $collective?->numero ?? '-' }}
                                </h4>

                                <small class="text-muted">
                                    {{ $user->civilite }} {{ $user->firstname }} {{ $user->name }}
                                </small>
                            </div>

                            <a href="{{ url('/profil') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-arrow-left"></i> Retour
                            </a>

                        </div>

                    </div>


                    @if ($collective)

                        {{-- INFORMATIONS DEMANDE --}}
                        <div class="card-body border-bottom">

                            <div class="row g-4">

                                {{-- STRUCTURE --}}
                                <div class="col-md-6">

                                    <h6 class="fw-bold text-primary mb-3">
                                        <i class="bi bi-building me-1"></i>
                                        Structure
                                    </h6>

                                    <div class="small text-muted">

                                        <div><strong>Nom :</strong>
                                            {{ $collective->name }}
                                            @if ($collective->sigle)
                                                ({{ $collective->sigle }})
                                            @endif
                                        </div>

                                        <div><strong>Email :</strong>
                                            <a href="mailto:{{ $collective->email }}">
                                                {{ $collective->email }}
                                            </a>
                                        </div>

                                        <div><strong>Téléphone :</strong>
                                            <a href="tel:+221{{ $collective->telephone }}">
                                                {{ $collective->telephone }}
                                            </a>
                                        </div>

                                        <div><strong>Statut juridique :</strong>
                                            {{ $collective->statut_juridique ?? '-' }}
                                        </div>

                                        <div><strong>Région :</strong>
                                            {{ $collective->departement?->region?->nom ?? '-' }}
                                        </div>

                                        <div><strong>Département :</strong>
                                            {{ $collective->departement?->nom ?? '-' }}
                                        </div>

                                        <div><strong>Adresse :</strong>
                                            {{ $collective->adresse ?? '-' }}
                                        </div>

                                    </div>

                                </div>


                                {{-- RESPONSABLE --}}
                                <div class="col-md-6">

                                    <h6 class="fw-bold text-primary mb-3">
                                        <i class="bi bi-person-badge me-1"></i>
                                        Responsable
                                    </h6>

                                    <div class="small text-muted">

                                        <div><strong>Nom :</strong>
                                            {{ $collective->civilite_responsable }}
                                            {{ $collective->prenom_responsable }}
                                            {{ $collective->nom_responsable }}
                                        </div>

                                        <div><strong>Email :</strong>
                                            <a href="mailto:{{ $collective->email_responsable }}">
                                                {{ $collective->email_responsable }}
                                            </a>
                                        </div>

                                        <div><strong>Téléphone :</strong>
                                            <a href="tel:+221{{ $collective->telephone_responsable }}">
                                                {{ $collective->telephone_responsable }}
                                            </a>
                                        </div>

                                        <div><strong>Fonction :</strong>
                                            {{ $collective->fonction_responsable ?? '-' }}
                                        </div>

                                    </div>

                                </div>

                            </div>

                            {{-- STATUT + ACTION --}}
                            <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap gap-2">

                                <span class="{{ $collective->statut_demande }}">
                                    {{ $collective->statut_demande }}
                                </span>

                                <a href="{{ route('collectives.show', $collective) }}"
                                    class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-eye"></i> Voir les détails
                                </a>

                                <div class="text-center">
                                    <a href="{{ route('collectives.edit', $collective) }}"
                                        class="btn btn-outline-success btn-sm" title="Modifier">Modifier</a>
                                </div>

                            </div>

                        </div>


                        {{-- STATISTIQUES --}}
                        <div class="card-body border-bottom">

                            {{-- <div class="row g-3 text-center"> --}}
                            <div class="d-flex justify-content-between align-items-center mb-3">

                                <div>
                                    <div class="p-3 bg-primary-subtle rounded-3">
                                        <h6 class="text-muted mb-1">Formations sollicitées</h6>
                                        <h4 class="fw-bold text-primary text-center mb-0">{{ $totalModules }}</h4>
                                    </div>
                                </div>

                                {{-- <div class="my-2 p-3 border rounded text-center">

                                    @php
                                        // Vérifie si l'utilisateur a joint sa carte d'identité (CIN)
                                        $hasCIN = $files->contains(function ($file) {
                                            return $file->sigle === 'CIN';
                                        });

                                        // Vérifie si l'utilisateur a joint soit le Ninea/RCC ou AC
$hasRC = $files->contains(function ($file) {
    return in_array($file->sigle, ['Ninea/RC', 'AC']);
                                        });
                                    @endphp

                                    @if ($hasCIN && $hasRC)
                                        <span class="text-success fw-bold fs-5">
                                            ✅ Demande complète
                                        </span>
                                    @else
                                        <span class="text-danger fw-bold fs-5 d-block">
                                            ⚠ Demande incomplète !
                                        </span>
                                        <span class="text-danger fw-normal fs-6 d-block">
                                            Veuillez téléverser les documents nécessaires.
                                        </span>
                                    @endif

                                </div> --}}

                                <div class="my-2 p-3 border rounded text-center">

                                    @php
                                        // Vérification des documents
                                        $hasCIN = $files->contains(fn($file) => $file->sigle === 'CIN');
                                        $hasRC = $files->contains(
                                            fn($file) => in_array($file->sigle, ['Ninea/RC', 'AC']),
                                        );

                                        // Vérifie s'il y a au moins un module
$hasModule = $modules->isNotEmpty() ?? false;

// Vérifie s'il y a des bénéficiaires sur au moins un module avec effectif ≥ 10
                                        $hasBeneficiaries = $modules->contains(function ($module) {
                                            return $module->listecollectives->count() >= 10;
                                        });
                                    @endphp

                                    @if ($hasCIN && $hasRC && $hasModule && $hasBeneficiaries)
                                        <span class="text-success fw-bold fs-5">
                                            ✅ Demande complète
                                        </span>
                                    @else
                                        <span class="text-danger fw-bold fs-5 d-block">
                                            ⚠ Demande incomplète !
                                        </span>

                                        <div class="text-danger fs-6">
                                            @if (!$hasCIN)
                                                Veuillez téléverser la carte d'identité du responsable.<br>
                                            @endif
                                            @if (!$hasRC)
                                                Veuillez téléverser le Ninéa/RCC ou l'Acte de création.<br>
                                            @endif

                                            @if (!$hasModule)
                                                Ajouter au moins un module.<br>
                                            @endif

                                            @if ($hasModule && !$hasBeneficiaries)
                                                Ajouter au moins 10 bénéficiaires sur un module.
                                            @endif
                                        </div>
                                    @endif

                                </div>

                                <div>
                                    <div class="p-3 bg-success-subtle rounded-3">
                                        <h6 class="text-muted mb-1">Effectif total</h6>
                                        <h4 class="fw-bold text-success text-center mb-0">{{ $totalEffectif }}</h4>
                                    </div>
                                </div>

                                {{-- <div>
                                    <div class="p-3 bg-warning-subtle rounded-3">
                                        <h6 class="text-muted mb-1">Modules formés</h6>
                                        <h4 class="fw-bold text-warning text-center mb-0">{{ $totalFormes }}</h4>
                                    </div>
                                </div> --}}

                            </div>

                        </div>


                        {{-- MODULES --}}
                        <div class="card-body">

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-bold mb-0">
                                    <i class="bi bi-mortarboard me-1"></i>
                                    Formations demandées
                                </h6>

                                <button class="btn btn-outline-success btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#AddcollectiveModuleModal">
                                    <i class="bi bi-plus-circle"></i> Ajouter formation
                                </button>
                            </div>

                            @if ($modules->isNotEmpty())

                                <div class="table-responsive">
                                    <table class="table align-middle table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Module(s)</th>
                                                <th>Niveau de qualification</th>
                                                <th class="text-center">Effectif</th>
                                                <th class="text-center">Statut</th>
                                                <th class="text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($modules as $module_collective)
                                                <tr>
                                                    <td class="fw-semibold text-primary">
                                                        {{ $module_collective->module }}
                                                    </td>

                                                    <td>
                                                        {{ $module_collective->niveau_qualification ?? '-' }}
                                                    </td>

                                                    <td class="text-center">
                                                        {{ $module_collective->listecollectives?->count() ?? 0 }}
                                                    </td>

                                                    <td class="text-center">
                                                        <span class="{{ $module_collective->statut }}">
                                                            {{ $module_collective->statut }}
                                                        </span>
                                                    </td>

                                                    <td class="text-center">

                                                        <div class="d-flex justify-content-end align-items-center gap-2">

                                                            {{-- Bouton Voir --}}
                                                            <a href="{{ route('collectivemodules.show', $module_collective) }}"
                                                                class="btn btn-sm btn-outline-primary">
                                                                <i class="bi bi-plus me-0"></i> Ajouter bénéficiaires
                                                            </a>

                                                            {{-- Dropdown Actions --}}
                                                            <div class="dropdown position-static">

                                                                <button class="btn btn-light btn-sm" type="button"
                                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                                    <i class="bi bi-three-dots"></i>
                                                                </button>

                                                                <ul class="dropdown-menu dropdown-menu-end shadow-sm">

                                                                    {{-- Modifier --}}
                                                                    <li>
                                                                        <button class="dropdown-item" data-bs-toggle="modal"
                                                                            data-bs-target="#EditRegionModal{{ $module_collective->id }}">
                                                                            <i class="bi bi-pencil me-2"></i> Modifier
                                                                        </button>
                                                                    </li>

                                                                    {{-- Supprimer --}}
                                                                    <li>
                                                                        <form
                                                                            action="{{ route('collectivemodules.destroy', $module_collective) }}"
                                                                            method="POST">
                                                                            @csrf
                                                                            @method('DELETE')

                                                                            <button type="submit"
                                                                                class="dropdown-item text-danger show_confirm">
                                                                                <i class="bi bi-trash me-2"></i> Supprimer
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
                            @else
                                <div class="alert alert-warning d-flex align-items-center">
                                    <i class="bi bi-info-circle me-2"></i>
                                    Aucune formation enregistrée.
                                </div>

                            @endif

                        </div>
                    @else
                        <div class="card-body">
                            <div class="alert alert-warning">
                                Aucune demande collective enregistrée.
                            </div>
                        </div>

                    @endif

                </div>
            </div>

            <div class="modal fade" id="AddcollectiveModuleModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form method="post" action="{{ route('collectivemodules.store') }}"
                            enctype="multipart/form-data" class="row g-3">
                            @csrf
                            <div class="card-header text-center bg-gradient-default">
                                <h1 class="h4 text-black mb-0">Ajouter un nouveau module</h1>
                            </div>
                            <div class="modal-body">
                                <div class="form-floating mb-3">
                                    <input type="hidden" name="collectiveid" value="{{ $user?->collective?->id }}">
                                    <input type="text" name="module_name" value="{{ old('module_name') }}"
                                        class="form-control form-control-sm @error('module_name') is-invalid @enderror"
                                        id="module_name" placeholder="Formation sollicitée" autofocus>
                                    <div id="countryList"></div>
                                    {{ csrf_field() }}
                                    @error('module_name')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                    <label for="floatingInput">Module</label>
                                </div>
                                <div class="col-12">
                                    <label for="niveau_qualification" class="form-label">Niveau qualification<span
                                            class="text-danger mx-1">*</span></label>
                                    <select name="niveau_qualification"
                                        class="form-select  @error('niveau_qualification') is-invalid @enderror"
                                        aria-label="Select" id="select-field" data-placeholder="Choisir">
                                        <option value="">Choisir</option>
                                        <option value="Renforcement de capacités">Renforcement de capacités</option>
                                        <option value="Qualification">Qualification</option>
                                        {{-- <option value="Aucun">Aucun</option> --}}
                                    </select>
                                    @error('niveau_qualification')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <input type="hidden" name="collective" value="{{ $user?->collective?->id }}">
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary btn-sm"
                                    data-bs-dismiss="modal">Fermer</button>
                                <button type="submit" class="btn btn-primary btn-sm">Enregistrer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            @foreach ($collective->collectivemodules as $collectivemodule)
                <div class="modal fade" id="EditRegionModal{{ $collectivemodule->id }}" tabindex="-1" role="dialog"
                    aria-labelledby="EditRegionModalLabel{{ $collectivemodule->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <form method="post" action="{{ route('collectivemodules.update', $collectivemodule) }}"
                                enctype="multipart/form-data" class="row g-3">
                                @csrf
                                @method('patch')

                                <div class="card-header text-center bg-gradient-default">
                                    <h1 class="h4 text-black mb-0">Modifier module</h1>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="id" value="{{ $collectivemodule->id }}">
                                    <input type="hidden" name="collective" value="{{ $collective->id }}">
                                    <div class="form-floating mb-3">
                                        <input type="text" name="module_name"
                                            value="{{ $collectivemodule->module ?? old('module_name') }}"
                                            class="form-control form-control-sm @error('module_name') is-invalid @enderror"
                                            id="module_name" placeholder="Module" autofocus>
                                        @error('module_name')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                        <label for="floatingInput">Module</label>
                                    </div>
                                    <div class="col-12">
                                        <label for="niveau_qualification" class="form-label">
                                            Niveau de qualification <span class="text-danger mx-1">*</span>
                                        </label>
                                        <select name="niveau_qualification" id="niveau_qualificationmodal"
                                            class="form-select @error('niveau_qualification') is-invalid @enderror"
                                            aria-label="Sélection du niveau de qualification" data-placeholder="Choisir">

                                            <option value="" disabled
                                                {{ old('niveau_qualification', $collectivemodule?->niveau_qualification) ? '' : 'selected' }}>
                                                Choisir
                                            </option>

                                            @php
                                                $niveauOptions = ['Renforcement de capacités', 'Qualification'];
                                            @endphp

                                            @foreach ($niveauOptions as $option)
                                                <option value="{{ $option }}"
                                                    {{ old('niveau_qualification', $collectivemodule?->niveau_qualification) === $option ? 'selected' : '' }}>
                                                    {{ $option }}
                                                </option>
                                            @endforeach
                                        </select>

                                        @error('niveau_qualification')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary btn-sm"
                                        data-bs-dismiss="modal">Fermer</button>
                                    <button type="submit" class="btn btn-primary btn-sm">Modifier</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
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

                                <div class="alert border-0 shadow-sm rounded-4 p-4 mb-4 bg-warning bg-opacity-10">

                                    <div class="d-flex align-items-center mb-3">
                                        <div class="me-3">
                                            <i class="bi bi-exclamation-triangle-fill text-warning fs-4"></i>
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
                                            La carte nationale d'identité (recto/verso) du responsable
                                        </li>
                                        <li class="mb-2">
                                            <i class="bi bi-geo-alt text-secondary me-2"></i>
                                            L’acte de création, le NINEA ou toute autre pièce justifiant l’existence légale
                                            de votre
                                            structure
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

                                    <thead class="table-light text-center">
                                        <tr>
                                            <th style="width:5%">N°</th>
                                            <th>Légende</th>
                                            <th style="width:10%">Fichier</th>
                                            <th style="width:10%">Statut</th>
                                            <th style="width:10%">Actions</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @php $i = 1; @endphp

                                        @foreach ($files as $file)
                                            <tr class="text-center">
                                                <td>{{ $i++ }}</td>
                                                <td class="text-start">{{ $file->legende }}</td>

                                                <td>
                                                    <a class="btn btn-outline-secondary btn-sm" target="_blank"
                                                        href="{{ asset($file->getFichier()) }}">
                                                        <i class="bi bi-download"></i>
                                                    </a>
                                                </td>

                                                <td>

                                                    <span class="{{ $file?->statut }}">
                                                        {{ $file?->statut }}
                                                    </span>
                                                </td>

                                                <td>
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

        {{-- Ajouter un autre choix --}}
        <div class="col-lg-12 col-md-12 d-flex flex-column align-items-center justify-content-center">
            <div class="modal fade" id="AddCollectiveModal" tabindex="-1">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <form method="post" action="{{ route('collectives.store') }}" enctype="multipart/form-data">
                            @csrf
                            {{-- <div class="modal-header">
                                <h5 class="modal-title"><i class="bi bi-plus" title="Ajouter"></i> Ajouter une demande
                                    collective</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div> --}}
                            <div class="card-header text-center bg-gradient-default">
                                <h1 class="h4 text-black mb-0"> ajouter une nouvelle demande collective</h1>
                            </div>
                            <div class="modal-body">
                                <div class="row g-3">
                                    <div class="col-12 mb-0">
                                        <label for="name" class="form-label">Nom de la structure<span
                                                class="text-danger mx-1">*</span></label>
                                        <textarea name="name" id="name" rows="1"
                                            class="form-control form-control-sm @error('name') is-invalid @enderror"
                                            placeholder="La raison sociale de l'opérateur">{{ old('name') }}</textarea>
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-4 mb-0">
                                        <label for="sigle" class="form-label">Sigle<span
                                                class="text-danger mx-1">*</span></label>
                                        <input type="text" name="sigle" value="{{ old('sigle') }}"
                                            class="form-control form-control-sm @error('sigle') is-invalid @enderror"
                                            id="sigle" placeholder="Sigle ou abréviation">
                                        @error('sigle')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-4 mb-0">
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

                                    <div class="col-12 col-md-4 mb-0">
                                        <label for="fixe" class="form-label">Téléphone fixe</label>
                                        <input type="number" name="fixe" value="{{ old('fixe') }}"
                                            class="form-control form-control-sm @error('fixe') is-invalid @enderror"
                                            id="fixe" placeholder="3xxxxxxxx">
                                        @error('fixe')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-4 mb-0">
                                        <label for="telephone" class="form-label">Téléphone portable<span
                                                class="text-danger mx-1">*</span></label>
                                        <input type="number" min="0" name="telephone"
                                            value="{{ old('telephone') }}"
                                            class="form-control form-control-sm @error('telephone') is-invalid @enderror"
                                            id="telephone" placeholder="7xxxxxxxx">
                                        @error('telephone')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-4 mb-0">
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

                                    <div class="col-12 col-md-4 mb-0">
                                        <label for="statut" class="form-label">Statut juridique<span
                                                class="text-danger mx-1">*</span></label>
                                        <select name="statut" class="form-select  @error('statut') is-invalid @enderror"
                                            aria-label="Select" id="select-field-statut-col"
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
                                            <option value="Entreprise">
                                                Entreprise
                                            </option>
                                            <option value="Institution publique">
                                                Institution publique
                                            </option>
                                            <option value="Institution privée">
                                                Institution privée
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

                                    <div class="col-12 col-md-4 mb-0">
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
                                    <div class="col-12 col-md-4 mb-0">
                                        <label for="departement" class="form-label">
                                            Département <span class="text-danger mx-1">*</span>
                                        </label>

                                        <select name="departement"
                                            class="form-select form-select-sm @error('departement') is-invalid @enderror"
                                            required>

                                            <option value="">-- Choisir un département --</option>

                                            @foreach ($departements as $departement)
                                                <option value="{{ $departement->id }}"
                                                    {{ old('departement') == $departement->id ? 'selected' : '' }}>
                                                    {{ $departement->nom }}
                                                </option>
                                            @endforeach
                                        </select>

                                        @error('departement')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-4 mb-0">
                                        <label for="adresse" class="form-label">Adresse<span
                                                class="text-danger mx-1">*</span></label>
                                        <input type="text" name="adresse" value="{{ old('adresse') }}"
                                            class="form-control form-control-sm @error('adresse') is-invalid @enderror"
                                            id="adresse" placeholder="Adresse exacte">
                                        @error('adresse')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    {{-- <div class="col-12 mb-0">
                                        <label for="module" class="form-label">Formation sollicitée<span
                                                class="text-danger mx-1">*</span></label>
                                        <input type="text" name="module" value="{{ old('module_name') }}"
                                            class="form-control form-control-sm @error('module_name') is-invalid @enderror"
                                            id="module_name" placeholder="Nom du module" autofocus>
                                        <div id="countryList"></div>
                                        {{ csrf_field() }}
                                        @error('module')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div> --}}

                                    <div class="col-12 mb-0">
                                        <label for="description" class="form-label">Description de l'organisation<span
                                                class="text-danger mx-1">*</span></label>
                                        <textarea name="description" id="description" rows="2" minlength="100" maxlength="200"
                                            class="form-control form-control-sm @error('description') is-invalid @enderror"
                                            placeholder="Faire un résumé de la description de l'organisation, de ses activités et de ses réalisations">{{ old('description') }}</textarea>

                                        @error('description')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-12 mb-0">
                                        <label for="projetprofessionnel" class="form-label">Projet professionnel<span
                                                class="text-danger mx-1">*</span></label>
                                        <textarea name="projetprofessionnel" id="projetprofessionnel" rows="2" minlength="100" maxlength="200"
                                            class="form-control form-control-sm @error('projetprofessionnel') is-invalid @enderror"
                                            placeholder="Faire un résumé de la description détaillée du projet professionnel et de l'effet attendu après la formation">{{ old('projetprofessionnel') }}</textarea>

                                        @error('projetprofessionnel')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <hr class="dropdown-divider mt-3">

                                    <div class="col-12 col-md-4 mb-0">
                                        <label for="civilite" class="form-label">Civilité responsable<span
                                                class="text-danger mx-1">*</span></label>
                                        <select name="civilite"
                                            class="form-select form-select-sm @error('civilite') is-invalid @enderror"
                                            aria-label="Select" id="select-field-civilite"
                                            data-placeholder="Choisir civilité">
                                            <option value="{{ old('civilite') }}">
                                                {{ old('civilite') }}
                                            </option>
                                            <option value="Monsieur">
                                                Monsieur
                                            </option>
                                            <option value="Madame">
                                                Madame
                                            </option>
                                        </select>
                                        @error('civilite')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-12 col-md-4 mb-0">
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

                                    <div class="col-12 col-md-4 mb-0">
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

                                    <div class="col-12 col-md-4 mb-0">
                                        <label for="email_responsable" class="form-label">Adresse e-mail<span
                                                class="text-danger mx-1">*</span></label>
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

                                    <div class="col-12 col-md-4 mb-0">
                                        <label for="telephone_responsable" class="form-label">Téléphone responsable<span
                                                class="text-danger mx-1">*</span></label>
                                        <input type="number" min="0" name="telephone_responsable"
                                            value="{{ old('telephone_responsable') }}"
                                            class="form-control form-control-sm @error('telephone_responsable') is-invalid @enderror"
                                            id="telephone_responsable" placeholder="7xxxxxxxx">
                                        @error('telephone_responsable')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-4 mb-0">
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
        </div>
    </section>
@endsection
