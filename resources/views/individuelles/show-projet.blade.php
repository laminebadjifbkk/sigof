@extends('layout.user-layout')
@section('title', 'ONFP | ' . $projet?->sigle)
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
                    <div class="card-header d-flex justify-content-between align-items-center mt-0">
                        <h5 class="card-title">{{ strtoupper($projet?->type_projet . ' ' . $projet?->sigle) }}</h5>
                        <a href="{{ url('/profil') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left"></i> Retour
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mt-0">
                            {{-- <span class="d-flex align-items-baseline"><a href="{{ url('/profil') }}"
                                    class="btn btn-success btn-sm" title="retour"><i
                                        class="bi bi-arrow-counterclockwise"></i></a>&nbsp;
                                <p> | Profil</p>
                            </span> --}}
                            {{-- HEADER GLOBAL --}}
                            <div class="card-body bg-light border-bottom">

                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                                    <div>
                                        <h4 class="fw-bold text-primary mb-1">
                                            <i class="bi bi-folder-check me-2"></i>
                                            Dossier n° {{ $user->cin ?? '-' }}
                                        </h4>

                                        {{-- <small class="text-muted">
                                    {{ $user->civilite }} {{ $user->firstname }} {{ $user->name }}
                                </small> --}}
                                    </div>

                                </div>

                            </div>
                            <button type="button" class="btn btn-info btn-sm">
                                <span class="badge bg-white text-info">{{ $individuelle_total }} sur 1</span>
                            </button>

                            @if (!empty($user->cin) && !empty($statut))
                                @if ($individuelle_total < 3 && !empty($user?->cin))
                                @endif
                            @endif
                        </div>

                        {{-- <div class="d-flex justify-content-between align-items-center mt-0">
                            <h5 class="card-title">
                                Bonjour
                                {{ $user->civilite . ' ' . $user->firstname . ' ' . $user->name }}
                            </h5>
                        </div> --}}


                        {{-- INFORMATIONS DEMANDE --}}
                        <div class="card-body border-bottom">

                            <div class="row g-4">

                                {{-- STRUCTURE --}}
                                <div class="col-md-6">

                                    <h6 class="fw-bold text-primary mb-3">
                                        <i class="bi bi-building me-1"></i>
                                        Informations personnelles
                                    </h6>

                                    <div class="small text-muted">

                                        <div><strong>Prénom :</strong>
                                            {{ $user->firstname }}
                                        </div>

                                        <div><strong>Nom :</strong>
                                            {{ $user->name }}
                                        </div>

                                        <div><strong>Date naissance :</strong>
                                            {{ $user->date_naissance->format('d/m/Y') }}
                                        </div>

                                        <div><strong>Lieu naissance :</strong>
                                            {{ $user->lieu_naissance }}
                                        </div>

                                        <div><strong>Email :</strong>
                                            <a href="mailto:{{ $user->email }}">
                                                {{ $user->email }}
                                            </a>
                                        </div>

                                        <div><strong>Téléphone :</strong>
                                            <a href="tel:+221{{ $user->telephone }}">
                                                {{ $user->telephone }}
                                            </a>
                                        </div>

                                        <div><strong>Adresse :</strong>
                                            {{ $user->adresse ?? '-' }}
                                        </div>

                                    </div>

                                </div>

                                <div class="col-md-6">
                                    <div class="alert alert-danger fw-bold" role="alert">
                                        <span class="text-primary">Faites défiler vers le bas</span> pour téléverser les
                                        fichiers requis avant la date limite.
                                        <span class="fw-normal">Tous les dossiers incomplets seront systématiquement
                                            rejetés !</span><br>
                                        <span class="text-primary">Si vous souhaitez changer de module</span>, veuillez
                                        d'abord <strong>supprimer la
                                            demande précédente</strong>.
                                    </div>
                                </div>

                            </div>

                        </div>

                        <div class="table-responsive">
                            <table class="table align-middle table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Module</th>
                                        <th>{{ $projet->type_localite }}</th>
                                        <th>Niveau étude</th>
                                        <th>Diplômes</th>
                                        <th width="5%">Statut</th>
                                        <th style="width:3%;"><i class="bi bi-gear"></i></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($individuelles as $individuelle)
                                        <tr>
                                            <td>{{ $individuelle?->module?->name }}</td>
                                            <td>{{ $individuelle?->{strtolower($projet->type_localite)}?->nom }}</td>
                                            <td>{{ $individuelle?->niveau_etude }}</td>
                                            <td>
                                                @php
                                                    $valeurs = collect([
                                                        $individuelle?->diplome_academique,
                                                        $individuelle?->diplome_professionnel,
                                                    ])
                                                        ->reject(fn($v) => !$v || in_array($v, ['Aucun', 'Autre']))
                                                        ->values();
                                                @endphp

                                                {{ $valeurs->isNotEmpty() ? $valeurs->implode(' et ') : 'Aucun' }}
                                            </td>
                                            <td>
                                                @hasanyrole('super-admin|admin|DIOF|ADIOF|Ingenieur')
                                                    <span
                                                        class="{{ $individuelle?->statut }}">{{ $individuelle?->statut }}</span>
                                                @endhasanyrole
                                                @hasrole('Demandeur')
                                                    @if (!empty($individuelle->projets_id))
                                                        @if ($individuelle->projet?->statut === 'ouvert')
                                                            <span
                                                                class="btn btn-info btn-sm text-white d-inline-flex align-items-center">
                                                                <i class="bi bi-check-circle me-1"></i> Enregistrée
                                                            </span>
                                                        @else
                                                            <span
                                                                class="{{ $individuelle?->statut }}">{{ $individuelle?->statut }}</span>
                                                        @endif
                                                    @else
                                                        <span
                                                            class="{{ $individuelle?->statut }}">{{ $individuelle?->statut }}</span>
                                                    @endif
                                                @endhasrole
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
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="card-title">
                    {{ 'Nombre de modules : ' . $projet->projetmodules->count() }}
                </h5>
            </div>
            <div class="card-body">
                @if ($projet->projetmodules && $projet->projetmodules->count())
                    <ul class="list-group list-group-flush">
                        @foreach ($projet->projetmodules as $index => $projetmodule)
                            <li class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center gap-2" style="cursor: pointer;"
                                        data-bs-toggle="collapse" data-bs-target="#collapseModule-{{ $projetmodule->id }}">
                                        <i class="bi bi-chevron-down"></i> {{-- Icône flèche --}}
                                        <strong>{{ $index + 1 }}. {{ $projetmodule->module }}</strong>
                                    </div>

                                    <!-- Vérifie si une demande existe déjà pour ce module -->
                                    @php
                                        $demandeExistante = $projet
                                            ->individuelles()
                                            ->where('users_id', Auth::id())
                                            ->whereHas('module', function ($query) use ($projetmodule) {
                                                $query->where('name', $projetmodule->module);
                                            })
                                            ->first();
                                        $statut = $projetmodule->statut;
                                        $badgeClass = match ($statut) {
                                            'ouvert' => 'btn-outline-success',
                                            'fermé' => 'btn-outline-danger',
                                            'terminé' => 'btn-outline-secondary',
                                            'Exécutée' => 'btn-outline-primary',
                                            'exécutée' => 'btn-outline-primary',
                                            default => 'btn-outline-light text-dark',
                                        };
                                    @endphp


                                    <!-- Bouton "Ajouter" ou "Modifier" selon l'existence de la demande -->

                                    @if (!($jours_restant < 0) && $statut == 'ouvert')
                                        <button type="button"
                                            class="btn {{ $demandeExistante ? 'btn-outline-warning' : 'btn-outline-success' }} btn-sm rounded-pill px-3 shadow-sm d-flex align-items-center gap-2"
                                            data-bs-toggle="modal"
                                            data-bs-target="#{{ $demandeExistante ? 'EditIndividuelleModal' : 'AddIndividuelleModal' }}{{ $projetmodule->id }}">
                                            <i
                                                class="bi {{ $demandeExistante ? 'bi-pencil-fill' : 'bi-plus-circle-fill' }}"></i>
                                            {{ $demandeExistante ? 'Modifier' : ucfirst(strtolower($projetmodule->statut)) }}
                                        </button>
                                    @else
                                        <button type="button"
                                            class="btn  {{ $badgeClass }} btn-sm rounded-pill px-3 shadow-sm d-flex align-items-center gap-2"
                                            disabled>
                                            <i class="bi bi-clock-fill"></i>
                                            {{ ucfirst(strtolower($projetmodule->statut)) }}
                                        </button>
                                    @endif
                                </div>

                                <!-- Zone de description cachée -->
                                <div class="collapse mt-3" id="collapseModule-{{ $projetmodule->id }}">
                                    <div class="text-muted">
                                        {!! '- ' .
                                            implode('- ', array_map(fn($line) => nl2br(e($line)), explode("\n", ucfirst($projetmodule->description)))) !!}
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="alert alert-info text-center text-muted">
                        Aucun module disponible.
                    </div>
                @endif
            </div>
        </div>

        {{-- @can('upload-file-view')
            <hr>
            <div class="row mb-3 pt-5">
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
                                        <a class="btn btn-outline-secondary btn-sm" title="Télécharger" target="_blank"
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
                                        <span class="badge bg-{{ $badgeClass }}">{{ $statut }}</span>
                                    </td>
        <td>
            @if ($file->statut !== 'Validé')
                <form action="{{ route('fileDestroy') }}" method="post" class="d-inline">
                    @csrf
                    @method('put')
                    <input type="hidden" name="idFile" value="{{ $file->id }}">
                    <button type="submit" class="btn btn-outline-danger btn-sm show_confirm" title="Supprimer">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
            @endif
        </td>
        @hasanyrole('super-admin|admin|DIOF')
            <td>
                <form action="{{ route('fileValidate') }}" method="post" class="d-inline">
                    @csrf
                    @method('put')
                    <input type="hidden" name="idFile" value="{{ $file->id }}">
                    <button type="submit" class="btn btn-outline-success btn-sm show_confirm_valider" title="Valider">
                        <i class="bi bi-check-circle"></i>
                    </button>
                </form>
            </td>
            <td>
                <form action="{{ route('fileInvalide') }}" method="post" class="d-inline">
                    @csrf
                    @method('put')
                    <input type="hidden" name="idFile" value="{{ $file->id }}">
                    <button type="submit" class="btn btn-outline-warning btn-sm show_confirm_rejeter" title="Invalider">
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
        <form method="post" action="{{ route('files.update', $user?->uuid) }}" enctype="multipart/form-data">
            @csrf
            @method('patch')
            <h5 class="card-title">JOINDRE VOS SCANS DE DOSSIERS</h5>
            <input type="hidden" name="idUser" value="{{ $user?->id }}">
            <span style="color:red;">NB : </span>Pièces requises<br>
            <ul>
                <li>Une copie de la carte nationale d’identité (obligatoire);</li>
                <li>Un certificat de résidence de la commune (obligatoire);</li>
                <li>Un CV (optionnel);</li>
                <li>Copie des diplômes ou attestations (si disponibles);</li>
            </ul>
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
        </form>
    @endcan --}}

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
                                                La carte nationale d'identité (recto/verso)
                                            </li>
                                            <li class="mb-2">
                                                <i class="bi bi-geo-alt text-secondary me-2"></i>
                                                Un certificat de résidence
                                            </li>
                                            <li class="mb-2">
                                                <i class="bi bi-file-earmark-person text-secondary me-2"></i>
                                                Un curriculum vitae (CV)
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
                                                        <a class="btn btn-outline-secondary btn-sm" target="_blank"
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
        @endcan

        {{-- @foreach ($user?->individuelles as $individuelle) --}}
        @foreach ($projet->projetmodules as $projetmodule)
            @php
                $demandeExistante = $projet
                    ->individuelles()
                    ->where('users_id', Auth::id())
                    ->whereHas('module', function ($query) use ($projetmodule) {
                        $query->where('name', $projetmodule->module);
                    })
                    ->first();
            @endphp

            @if ($demandeExistante)
                <div class="col-12 d-flex flex-column align-items-center justify-content-center">
                    <div class="modal fade" id="EditIndividuelleModal{{ $projetmodule->id }}" tabindex="-1">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                {{--  <div class="card-header text-center bg-gradient-default">
                                    <span class="text-black mb-0">➖ Modifier la demande pour le module :
                                        {{ $projetmodule->module }}</span>
                                </div> --}}
                                <div class="modal-body">
                                    <div class="row g-3">
                                        <form method="post" action="{{ route('individuelles.update', $individuelle) }}"
                                            enctype="multipart/form-data" class="row g-3">
                                            @csrf
                                            @method('PUT')

                                            <!-- HEADER -->
                                            <div class="modal-header bg-warning bg-gradient text-white py-4 px-4">
                                                <div>
                                                    <h5 class="modal-title fw-bold mb-1">
                                                        Modifier la demande de formation pour le
                                                        {{ $projet?->type_projet . ' ' . $projet?->sigle }}
                                                    </h5>
                                                    <small class="opacity-80">
                                                        {{ $projetmodule?->module }}
                                                    </small>
                                                </div>
                                                <button type="button" class="btn-close btn-close-white"
                                                    data-bs-dismiss="modal"></button>
                                            </div>
                                            {{-- <div class="col-12">
                                                <label for="module" class="form-label">Formation sollicitée
                                                    (module)
                                                    <span class="text-danger mx-1">*</span></label>
                                                <input type="text" name="module"
                                                    value="{{ $individuelle?->module?->name ?? old('module_name') }}"
                                                    class="form-control form-control-sm @error('module_name') is-invalid @enderror"
                                                    id="module_name" placeholder="Formation choisie" autofocus>
                                                <div id="countryList"></div>
                                                {{ csrf_field() }}
                                                @error('module')
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div> --}}
                                            {{-- <div class="col-12 col-md-12 col-lg-6 col-sm-12 col-xs-12 col-xxl-6">
                                                <label for="departement" class="form-label">Département<span
                                                        class="text-danger mx-1">*</span></label>
                                                <select name="departement"
                                                    class="form-select  @error('departement') is-invalid @enderror"
                                                    aria-label="Select" id="select-field-departement"
                                                    data-placeholder="Choisir">
                                                    <option
                                                        value="{{ $individuelle?->departement?->nom ?? old('departement') }}">
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
                                            </div> --}}
                                            <input type="hidden" value="{{ $individuelle?->module?->name }}"
                                                name="module">
                                            <input type="hidden" name="projet" value="{{ $projet?->sigle }}">
                                            <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                                <label for="module_select_{{ $projetmodule->id }}" class="form-label">
                                                    {{ $projetmodule?->projet?->type_localite }} <span
                                                        class="text-danger mx-1">*</span>
                                                </label>
                                                <select name="localite"
                                                    class="form-select form-select-sm @error(strtolower($projetmodule->projet->type_localite)) is-invalid @enderror"
                                                    aria-label="Select" id="module_select_{{ $projetmodule->id }}"
                                                    data-placeholder="Choisir {{ strtolower($projetmodule->projet->type_localite) }}">

                                                    <!-- Affiche "Choisir" si aucun département ou région n'est sélectionné -->
                                                    <option value="" disabled
                                                        {{ !$individuelle?->{strtolower($projetmodule->projet->type_localite)}?->nom ? 'selected' : '' }}>
                                                        Choisir {{ strtolower($projetmodule->projet->type_localite) }}
                                                    </option>

                                                    <!-- Affiche le département ou la région de l'individuelle concernée -->
                                                    <option
                                                        value="{{ $individuelle?->{strtolower($projetmodule->projet->type_localite)}?->nom ?? old(strtolower($projetmodule->projet->type_localite)) }}"
                                                        {{ $individuelle?->{strtolower($projetmodule->projet->type_localite)}?->nom == old(strtolower($projetmodule->projet->type_localite)) ? 'selected' : '' }}>
                                                        {{ $individuelle?->{strtolower($projetmodule->projet->type_localite)}?->nom ?? old(strtolower($projetmodule->projet->type_localite)) }}
                                                    </option>

                                                    <!-- Affiche toutes les localités disponibles -->
                                                    @foreach ($projetmodule->projetlocalites as $projetlocalite)
                                                        <option value="{{ $projetlocalite->localite }}"
                                                            {{ old(strtolower($projetmodule->projet->type_localite)) == $projetlocalite->localite ? 'selected' : '' }}>
                                                            {{ $projetlocalite->localite }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('localite')
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="col-12 col-md-12 col-lg-6 col-sm-12 col-xs-12 col-xxl-8 col-xl-8">
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
                                                    value="{{ old('telephone_secondaire', $individuelle?->user?->telephone ?? '') }}"
                                                    autocomplete="tel" placeholder="XX:XXX:XX:XX">
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
                                                    class="form-select form-select-sm @error('niveau_etude') is-invalid @enderror"
                                                    aria-label="Select" id="select-field-niveau_etude"
                                                    data-placeholder="Choisir niveau étude">
                                                    <option value="{{ $individuelle->niveau_etude }}">
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
                                                    class="form-select form-select-sm  @error('diplome_academique') is-invalid @enderror"
                                                    aria-label="Select" id="select-field-diplome_academique"
                                                    data-placeholder="Choisir diplôme académique">
                                                    <option value="{{ $individuelle->diplome_academique }}">
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
                                                    value="{{ $individuelle->etablissement_academique ?? old('etablissement_academique') }}"
                                                    class="form-control form-control-sm @error('etablissement_academique') is-invalid @enderror"
                                                    id="etablissement_academique" placeholder="Etablissement obtention">
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
                                                    value="{{ $individuelle->autre_diplome_academique ?? old('autre_diplome_academique') }}"
                                                    class="form-control form-control-sm @error('autre_diplome_academique') is-invalid @enderror"
                                                    id="autre_diplome_academique" placeholder="autre diplôme académique">
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
                                                    value="{{ $individuelle->option_diplome_academique ?? old('option_diplome_academique') }}"
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
                                                    class="form-select form-select-sm @error('diplome_professionnel') is-invalid @enderror"
                                                    aria-label="Select" id="select-field-diplome_professionnel"
                                                    data-placeholder="Choisir diplôme professionnel">
                                                    <option value="{{ $individuelle->diplome_professionnel }}">
                                                        {{ $individuelle->diplome_professionnel ?? old('diplome_professionnel') }}
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
                                                    value="{{ $individuelle->autre_diplome_professionnel ?? old('autre_diplome_professionnel') }}"
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
                                                <label for="etablissement_professionnel" class="form-label">Etablissement
                                                    professionnel</label>
                                                <input type="text" name="etablissement_professionnel"
                                                    value="{{ $individuelle->etablissement_professionnel ?? old('etablissement_professionnel') }}"
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
                                                    value="{{ $individuelle->specialite_diplome_professionnel ?? old('specialite_diplome_professionnel') }}"
                                                    class="form-control form-control-sm @error('specialite_diplome_professionnel') is-invalid @enderror"
                                                    id="specialite_diplome_professionnel" placeholder="Ex: électricité">
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
                                                    class="form-select form-select-sm  @error('projet_poste_formation') is-invalid @enderror"
                                                    aria-label="Select" id="select-field-projet_poste_formation"
                                                    data-placeholder="Choisir projet poste formation">
                                                    <option value="{{ $individuelle->projet_poste_formation }}">
                                                        {{ $individuelle->projet_poste_formation ?? old('projet_poste_formation') }}
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

                                            @if (auth()->user()->hasRole('super-admin|admin'))
                                                <div class="col-12 col-md-12 col-lg-6 col-sm-12 col-xs-12 col-xxl-4">
                                                    <label for="date_depot" class="form-label">Date dépot<span
                                                            class="text-danger mx-1">*</span></label>
                                                    <input type="text" name="date_depot"
                                                        value="{{ old('date_depot', optional($individuelle->date_depot)->format('d/m/Y')) }}"
                                                        class="form-control form-control-sm @error('date_depot') is-invalid @enderror"
                                                        id="datepicker" placeholder="JJ/MM/AAAA" autocomplete="bday">
                                                    @error('date_depot')
                                                        <span class="invalid-feedback" role="alert">
                                                            <div>{{ $message }}</div>
                                                        </span>
                                                    @enderror
                                                </div>
                                            @else
                                                <div class="col-12 col-md-12 col-lg-6 col-sm-12 col-xs-12 col-xxl-4">
                                                    <label for="date_depot" class="form-label">Date dépot<span
                                                            class="text-danger mx-1">*</span></label>
                                                    <input type="text" name="date_depot"
                                                        value="{{ old('date_depot', optional($individuelle->date_depot)->format('d/m/Y')) }}"
                                                        readonly
                                                        class="form-control form-control-sm @error('date_depot') is-invalid @enderror"
                                                        id="datepickers" placeholder="JJ/MM/AAAA" autocomplete="bday">
                                                    @error('date_depot')
                                                        <span class="invalid-feedback" role="alert">
                                                            <div>{{ $message }}</div>
                                                        </span>
                                                    @enderror
                                                </div>
                                            @endif

                                            {{-- @can('projet-view')
                                                <div class="col-12">
                                                    <label for="projet" class="form-label">Partenaire</label>
                                                    <select name="projet"
                                                        class="form-select  @error('projet') is-invalid @enderror"
                                                        aria-label="Select" id="select-field-projet"
                                                        data-placeholder="Choisir">
                                                        <option>
                                                            {{ $individuelle?->projet?->sigle ?? old('projet') }}
                                                        </option>
                                                        <option value="null">
                                                            Aucun
                                                        </option>
                                                        @foreach ($projets as $projet)
                                                            <option value="{{ $projet?->sigle }}">
                                                                {{ $projet?->name . ' (' . $projet?->sigle . ')' }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('projet')
                                                        <span class="invalid-feedback" role="alert">
                                                            <div>{{ $message }}</div>
                                                        </span>
                                                    @enderror
                                                </div>
                                            @endcan --}}

                                            <div class="col-12">
                                                <label for="qualification" class="form-label">Qualification et autres
                                                    diplômes</label>
                                                <textarea name="qualification" id="qualification" rows="2"
                                                    class="form-control form-control-sm @error('qualification') is-invalid @enderror"
                                                    placeholder="Qualification et autres diplômes">{{ $individuelle->qualification ?? old('qualification') }}</textarea>
                                                @error('qualification')
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="col-12">
                                                <label for="experience" class="form-label">Expériences et
                                                    stages</label>
                                                <textarea name="experience" id="experience" rows="2"
                                                    class="form-control form-control-sm @error('experience') is-invalid @enderror"
                                                    placeholder="Expériences ou stages">{{ $individuelle->experience ?? old('experience') }}</textarea>
                                                @error('experience')
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="col-12">
                                                <label for="projetprofessionnel" class="form-label">Informations
                                                    complémentaires sur
                                                    le projet
                                                    professionnel<span class="text-danger mx-1">*</span></label>
                                                <textarea name="projetprofessionnel" id="projetprofessionnel" rows="5"
                                                    class="form-control form-control-sm @error('projetprofessionnel') is-invalid @enderror"
                                                    placeholder="Si vous disposez déjà d'un projet professionnel, merci d'écrire son résumé en quelques lignes">{{ $individuelle->projetprofessionnel ?? old('projetprofessionnel') }}</textarea>
                                                @error('projetprofessionnel')
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div
                                                class="card-footer d-flex justify-content-end gap-2 p-3 bg-light border-top">
                                                <button type="button" class="btn btn-secondary btn-sm"
                                                    data-bs-dismiss="modal">
                                                    <i class="bi bi-x-circle"></i> Fermer
                                                </button>
                                                <button type="submit" class="btn btn-primary btn-sm">
                                                    <i class="bi bi-save"></i> Enregistrer
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
        {{-- @endforeach --}}

        <!-- Modal Choisir Localité -->
        <div class="modal fade" id="ChoisirLocaliteModal" tabindex="-1" aria-labelledby="ChoisirLocaliteModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title" id="ChoisirLocaliteModalLabel">Choisir une localité</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <form id="form-choisir-localite">
                            <div class="mb-3">
                                <label for="localite-select" class="form-label">Sélectionnez la localité :</label>
                                <select class="form-select" id="localite-select" name="localite">
                                    <option value="">-- Choisir --</option>
                                    @foreach ($projet->projetlocalites as $localite)
                                        <option value="{{ $localite->id }}">{{ $localite->localite }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </form>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Fermer</button>
                        <button type="button" class="btn btn-primary btn-sm" id="btn-choisir-localite">Choisir</button>
                    </div>

                </div>
            </div>
        </div>

    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('[id^="module_select_"]').forEach(select => {
                select.addEventListener('change', function(e) {
                    let moduleId = e.target.id.split('_').pop();
                    let selectedLocalite = e.target.value;

                    console.log('Module sélectionné ID:', moduleId);
                    console.log('Localité choisie ID:', selectedLocalite);

                    // Ici tu peux faire ce que tu veux (ajax, afficher un champ caché, etc.)
                });
            });
        });
    </script>
@endpush
