@extends('layout.user-layout')
@section('title', remove_accents_uppercase($operateur?->user?->username) . ' | ' .
    remove_accents_uppercase('formateurs'))
@section('space-work')

    <section class="section">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="pagetitle">
                    {{-- <h1>Data Tables</h1> --}}
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Accueil</a></li>
                            <li class="breadcrumb-item">Tables</li>
                            <li class="breadcrumb-item active">Formateurs</li>
                        </ol>
                    </nav>
                </div><!-- End Page Title -->
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
                        {{-- <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title">FORMATEURS</h5>
                            @can('devenir-operateur-agrement-ouvert')
                                @can('agrement-visible-par-op')
                                    <h5 class="card-title">
                                        <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#AddformateurModal">Ajouter
                                        </button>
                                    </h5>
                                @endcan
                            @endcan
                        </div> --}}
                        <!-- En-tête de la carte -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0 text-uppercase fw-bold text-primary">
                                <i class="bi bi-person-lines-fill me-2"></i> Formateurs
                            </h5>
                            @can('devenir-operateur-agrement-ouvert')
                                @can('agrement-visible-par-op')
                                    <button type="button" class="btn btn-primary btn-sm d-flex align-items-center shadow-sm"
                                        data-bs-toggle="modal" data-bs-target="#AddformateurModal">
                                        <i class="bi bi-plus-circle me-2"></i> Ajouter
                                    </button>
                                @endcan
                            @endcan
                        </div>
                        <table
                            class="table table-bordered table-hover datatables align-middle justify-content-center table-borderless">
                            <thead class="table-primary text-center">
                                <tr>
                                    <th>N°</th>
                                    <th>Prénom(s) et Nom</th>
                                    <th>Champs professionnels</th>
                                    <th>Années d'expérience</th>
                                    <th>Références professionnelles</th>
                                    <th>CV</th>
                                    <th>Statut</th>
                                    <th><i class="bi bi-gear"></i></th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                @php $i = 1; @endphp
                                @foreach ($operateur->operateurformateurs as $operateurformateur)
                                    <tr>
                                        <td class="text-center fw-semibold">{{ $i++ }}</td>
                                        <td>{{ $operateurformateur?->name }}</td>
                                        <td>{{ $operateurformateur?->domaine }}</td>
                                        <td class="text-center">{{ $operateurformateur?->nbre_annees_experience }}</td>
                                        <td>{{ $operateurformateur?->references }}</td>
                                        <td class="text-center">
                                            @if ($operateurformateur?->file)
                                                {{-- <a href="{{ asset('storage/cv/' . $operateurformateur->cv) }}"
                                                    target="_blank" class="btn btn-outline-secondary btn-sm"
                                                    title="Voir le CV">
                                                    <i class="bi bi-file-earmark-pdf"></i>
                                                </a> --}}

                                                <a class="btn btn-outline-secondary btn-sm" title="Convention"
                                                    target="_blank"
                                                    href="{{ asset($operateurformateur?->getCVFormateurs()) }}">
                                                    <i class="bi bi-file-earmark-pdf"></i>
                                                </a>
                                            @else
                                                <span class="text-muted small">Aucun fichier</span>
                                            @endif
                                        </td>
                                        <td>{{ $operateurformateur?->statut }}</td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center align-items-center gap-2">
                                                <a href="#" class="btn btn-outline-info btn-sm" title="Voir détails">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                @can('devenir-operateur-agrement-ouvert')
                                                    <!-- Dropdown pour les actions supplémentaires -->
                                                    <div class="dropdown">
                                                        <a class="btn btn-sm btn-light" href="#" data-bs-toggle="dropdown"
                                                            aria-expanded="false" title="Actions">
                                                            <i class="bi bi-three-dots-vertical"></i>
                                                        </a>
                                                        <ul
                                                            class="dropdown-menu dropdown-menu-end dropdown-menu-sm-start shadow-sm">
                                                            <li>
                                                                <button type="button" class="dropdown-item"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#EditoperateurformateurModal{{ $operateurformateur->id }}">
                                                                    <i class="bi bi-pencil me-2"></i>Modifier
                                                                </button>
                                                            </li>
                                                            <li>
                                                                <form
                                                                    action="{{ route('operateurformateurs.destroy', $operateurformateur->id) }}"
                                                                    method="post"
                                                                    onsubmit="return confirm('Confirmer la suppression ?')">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="dropdown-item text-danger">
                                                                        <i class="bi bi-trash me-2"></i>Supprimer
                                                                    </button>
                                                                </form>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                {{-- <div class="card shadow-sm border-0 rounded-4 mb-5">
                    <div class="card-body px-4 py-4 pb-5">

                        <!-- En-tête de la carte -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0 text-uppercase fw-bold text-primary">
                                <i class="bi bi-person-lines-fill me-2"></i> Formateurs
                            </h5>
                            @can('devenir-operateur-agrement-ouvert')
                                @can('agrement-visible-par-op')
                                    <button type="button" class="btn btn-primary btn-sm d-flex align-items-center shadow-sm"
                                        data-bs-toggle="modal" data-bs-target="#AddformateurModal">
                                        <i class="bi bi-plus-circle me-2"></i> Ajouter
                                    </button>
                                @endcan
                            @endcan
                        </div>

                        <!-- Tableau -->
                        <div class="table-responsive position-relative">
                            <table class="table table-striped table-hover align-middle datatables">
                                <thead class="table-primary text-center">
                                    <tr>
                                        <th>N°</th>
                                        <th>Prénom(s) et Nom</th>
                                        <th>Champs professionnels</th>
                                        <th>Années d'expérience</th>
                                        <th>Références professionnelles</th>
                                        <th>CV</th>
                                        <th><i class="bi bi-gear"></i></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i = 1; @endphp
                                    @foreach ($operateur->operateurformateurs as $operateurformateur)
                                        <tr>
                                            <td class="text-center fw-semibold">{{ $i++ }}</td>
                                            <td>{{ $operateurformateur->name }}</td>
                                            <td>{{ $operateurformateur->domaine }}</td>
                                            <td class="text-center">{{ $operateurformateur->nbre_annees_experience }}</td>
                                            <td>{{ $operateurformateur->references }}</td>
                                            <td class="text-center">
                                                @if ($operateurformateur->cv)
                                                    <a href="{{ asset('storage/cv/' . $operateurformateur->cv) }}"
                                                        target="_blank" class="btn btn-outline-secondary btn-sm"
                                                        title="Voir le CV">
                                                        <i class="bi bi-file-earmark-pdf"></i>
                                                    </a>
                                                @else
                                                    <span class="text-muted small">Aucun fichier</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center align-items-center gap-2">
                                                    <a href="#" class="btn btn-outline-info btn-sm"
                                                        title="Voir détails">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    @can('devenir-operateur-agrement-ouvert')
                                                        <!-- Dropdown pour les actions supplémentaires -->
                                                        <div class="dropdown">
                                                            <a class="btn btn-sm btn-light" href="#"
                                                                data-bs-toggle="dropdown" aria-expanded="false" title="Actions">
                                                                <i class="bi bi-three-dots-vertical"></i>
                                                            </a>
                                                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-sm-start shadow-sm">
                                                                <li>
                                                                    <button type="button" class="dropdown-item"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#EditoperateurformateurModal{{ $operateurformateur->id }}">
                                                                        <i class="bi bi-pencil me-2"></i>Modifier
                                                                    </button>
                                                                </li>
                                                                <li>
                                                                    <form
                                                                        action="{{ route('operateurformateurs.destroy', $operateurformateur->id) }}"
                                                                        method="post"
                                                                        onsubmit="return confirm('Confirmer la suppression ?')">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit"
                                                                            class="dropdown-item text-danger">
                                                                            <i class="bi bi-trash me-2"></i>Supprimer
                                                                        </button>
                                                                    </form>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- Fin tableau -->
                    </div>
                </div> --}}

            </div>
        </div>

        <!-- Add Formateur -->
        {{-- <div class="modal fade" id="AddformateurModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form method="post" action="{{ route('operateurformateurs.store') }}" enctype="multipart/form-data"
                        class="row g-3">
                        @csrf
                        <div class="card-header text-center bg-gradient-default">
                            <h1 class="h4 text-black mb-0">AJOUTER FORMATEURS</h1>
                        </div>
                        <input type="hidden" name="operateur" value="{{ $operateur->id }}">
                        <div class="modal-body">
                            <div class="col-12 mb-2">
                                <label for="name" class="form-label">Prénom(s) et Nom formateur<span
                                        class="text-danger mx-1">*</span></label>
                                <input type="text" name="name" value="{{ old('name') }}"
                                    class="form-control form-control-sm @error('name') is-invalid @enderror"
                                    placeholder="Prénom(s) et Nom formateur">
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <div>{{ $message }}</div>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-12 mb-2">
                                <label for="domaine" class="form-label">Domaine<span
                                        class="text-danger mx-1">*</span></label>
                                <input type="text" name="domaine" value="{{ old('domaine') }}"
                                    class="form-control form-control-sm @error('domaine') is-invalid @enderror"
                                    placeholder="Domaine">
                                @error('domaine')
                                    <span class="invalid-feedback" role="alert">
                                        <div>{{ $message }}</div>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-12 mb-2">
                                <label for="nbre_annees_experience" class="form-label">Nombre années expérience<span
                                        class="text-danger mx-1">*</span></label>
                                <input type="number" min="0" name="nbre_annees_experience"
                                    value="{{ old('nbre_annees_experience') }}"
                                    class="form-control form-control-sm @error('nbre_annees_experience') is-invalid @enderror"
                                    placeholder="Nombre années expérience">
                                @error('nbre_annees_experience')
                                    <span class="invalid-feedback" role="alert">
                                        <div>{{ $message }}</div>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-12 mb-2">
                                <label for="reference" class="form-label">Référence<span
                                        class="text-danger mx-1">*</span></label>
                                <input type="text" name="reference" value="{{ old('reference') }}"
                                    class="form-control form-control-sm @error('reference') is-invalid @enderror"
                                    placeholder="Référence">
                                @error('reference')
                                    <span class="invalid-feedback" role="alert">
                                        <div>{{ $message }}</div>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-12 mb-2">
                                <label for="cv" class="form-label">CV Signé<span
                                        class="text-danger mx-1">*</span></label>
                                        <input type="file" name="cv" id="cv"
                                            class="form-control form-control-sm @error('cv') is-invalid @enderror"
                                            accept=".jpg, .jpeg, .png, .svg, .gif, .pdf">
                                @error('cv')
                                    <span class="invalid-feedback" role="alert">
                                        <div>{{ $message }}</div>
                                    </span>
                                @enderror
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
        </div> --}}
        <div class="modal fade" id="AddformateurModal" tabindex="-1" aria-labelledby="AddformateurModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content shadow-lg border-0 rounded-4 overflow-hidden">
                    <form method="POST" action="{{ route('operateurformateurs.store') }}" enctype="multipart/form-data"
                        class="needs-validation" novalidate>
                        @csrf
                        <input type="hidden" name="operateur" value="{{ $operateur->id }}">

                        <!-- Modal Header -->
                        <!-- Modal Header amélioré -->
                        <div
                            class="modal-header bg-warning bg-gradient text-dark py-2 px-4 border-bottom border-3 border-white rounded-top">
                            <div class="d-flex align-items-center w-100">
                                <div class="flex-grow-1">
                                    <h5 class="modal-title fw-bold text-uppercase mb-0" id="AddformateurModalLabel">
                                        Ajouter un Formateur
                                    </h5>
                                    <small class="text-white">Remplissez soigneusement les informations du
                                        formateur</small>
                                </div>
                            </div>
                        </div>


                        <!-- Modal Body -->
                        <div class="modal-body px-4 py-4">
                            <div class="row g-3">

                                <!-- Nom -->
                                <div class="col-md-12">
                                    <label for="name" class="form-label fw-semibold">Prénom(s) et Nom <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" value="{{ old('name') }}"
                                        class="form-control form-control-sm @error('name') is-invalid @enderror"
                                        placeholder="Ex : Fatou Diop" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Domaine -->
                                <div class="col-md-12">
                                    <label for="domaine" class="form-label fw-semibold">Domaine d'expertise <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="domaine" id="domaine" value="{{ old('domaine') }}"
                                        class="form-control form-control-sm @error('domaine') is-invalid @enderror"
                                        placeholder="Ex : Comptabilité, Génie Civil, Informatique" required>
                                    @error('domaine')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Expérience -->
                                <div class="col-md-12">
                                    <label for="nbre_annees_experience" class="form-label fw-semibold">Années d'expérience
                                        <span class="text-danger">*</span></label>
                                    <input type="number" name="nbre_annees_experience" id="nbre_annees_experience"
                                        value="{{ old('nbre_annees_experience') }}" min="0"
                                        class="form-control form-control-sm @error('nbre_annees_experience') is-invalid @enderror"
                                        placeholder="Ex : 7" required>
                                    @error('nbre_annees_experience')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Référence -->
                                <div class="col-md-12">
                                    <label for="reference" class="form-label fw-semibold">Référence professionnelle <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="reference" id="reference"
                                        value="{{ old('reference') }}"
                                        class="form-control form-control-sm @error('reference') is-invalid @enderror"
                                        placeholder="Ex : Ex-Responsable à l’ENSA ou Projet X du BIT" required>
                                    <small class="text-muted">Nom d’une structure, d’un projet, d’un responsable ou d’une
                                        mission effectuée</small>
                                    @error('reference')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- CV -->
                                <div class="col-md-12">
                                    <label for="cv" class="form-label fw-semibold">CV signé <span
                                            class="text-danger">*</span></label>
                                    <input type="file" name="cv" id="cv"
                                        class="form-control form-control-sm @error('cv') is-invalid @enderror"
                                        accept=".pdf,.jpg,.jpeg,.png" required>
                                    <small class="text-muted">Formats acceptés : PDF, JPG, PNG</small>
                                    @error('cv')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>
                        </div>

                        <!-- Modal Footer -->
                        <div class="modal-footer bg-light px-4 py-3 d-flex justify-content-between">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                                <i class="bi bi-x-circle me-1"></i> Annuler
                            </button>
                            <button type="submit" class="btn btn-success btn-sm">
                                <i class="bi bi-check-circle me-1"></i> Ajouter Formateur
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- End Add Formateur-->
        <!-- Edit Formateur -->
        {{-- @foreach ($operateurformateurs as $operateurformateur)
            <div class="modal fade" id="EditoperateurformateurModal{{ $operateurformateur->id }}" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form method="post" action="{{ route('operateurformateurs.update', $operateurformateur->id) }}"
                            enctype="multipart/form-data" class="row g-3">
                            @csrf
                            @method('patch')
                            <div class="card-header text-center bg-gradient-default">
                                <h1 class="h4 text-black mb-0">MODIFICATION</h1>
                            </div>
                            <input type="hidden" name="operateur" value="{{ $operateur->id }}">
                            <div class="modal-body">
                                <div class="col-12 mb-2">
                                    <label for="name" class="form-label">Prénom(s) et Nom formateur<span
                                            class="text-danger mx-1">*</span></label>
                                    <input type="text" name="name"
                                        value="{{ $operateurformateur->name ?? old('name') }}"
                                        class="form-control form-control-sm @error('name') is-invalid @enderror"
                                        placeholder="Prénom(s) et Nom formateur">
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-12 mb-2">
                                    <label for="domaine" class="form-label">Domaine<span
                                            class="text-danger mx-1">*</span></label>
                                    <input type="text" name="domaine"
                                        value="{{ $operateurformateur->domaine ?? old('domaine') }}"
                                        class="form-control form-control-sm @error('domaine') is-invalid @enderror"
                                        placeholder="Domaine">
                                    @error('domaine')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-12 mb-2">
                                    <label for="nbre_annees_experience" class="form-label">Nombre années expérience<span
                                            class="text-danger mx-1">*</span></label>
                                    <input type="number" min="0" name="nbre_annees_experience"
                                        value="{{ $operateurformateur->nbre_annees_experience ?? old('nbre_annees_experience') }}"
                                        class="form-control form-control-sm @error('nbre_annees_experience') is-invalid @enderror"
                                        placeholder="Nombre années expérience">
                                    @error('nbre_annees_experience')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-12 mb-2">
                                    <label for="reference" class="form-label">Référence<span
                                            class="text-danger mx-1">*</span></label>
                                    <input type="text" name="reference"
                                        value="{{ $operateurformateur->references ?? old('reference') }}"
                                        class="form-control form-control-sm @error('reference') is-invalid @enderror"
                                        placeholder="Référence">
                                    @error('reference')
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
        @endforeach --}}
        @foreach ($operateurformateurs as $operateurformateur)
            <div class="modal fade" id="EditoperateurformateurModal{{ $operateurformateur->id }}" tabindex="-1"
                aria-labelledby="EditoperateurformateurModalLabel{{ $operateurformateur->id }}" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content shadow-lg border-0 rounded-4 overflow-hidden">
                        <form method="POST" action="{{ route('operateurformateurs.update', $operateurformateur->id) }}"
                            enctype="multipart/form-data" class="needs-validation" novalidate>
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="operateur" value="{{ $operateur->id }}">

                            <!-- Modal Header -->
                            <div
                                class="modal-header bg-info bg-gradient text-white py-2 px-4 border-bottom border-3 border-white rounded-top">
                                <div class="d-flex align-items-center w-100">
                                    <div class="flex-grow-1">
                                        <h5 class="modal-title fw-bold text-uppercase mb-0"
                                            id="EditoperateurformateurModalLabel{{ $operateurformateur->id }}">
                                            Modifier le Formateur
                                        </h5>
                                        <small class="text-white">Mettez à jour les informations du formateur</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Body -->
                            <div class="modal-body px-4 py-4">
                                <div class="row g-3">

                                    <!-- Nom -->
                                    <div class="col-md-12">
                                        <label for="name" class="form-label fw-semibold">Prénom(s) et Nom <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="name" id="name"
                                            value="{{ old('name', $operateurformateur->name) }}"
                                            class="form-control form-control-sm @error('name') is-invalid @enderror"
                                            required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Domaine -->
                                    <div class="col-md-12">
                                        <label for="domaine" class="form-label fw-semibold">Domaine d'expertise <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="domaine" id="domaine"
                                            value="{{ old('domaine', $operateurformateur->domaine) }}"
                                            class="form-control form-control-sm @error('domaine') is-invalid @enderror"
                                            required>
                                        @error('domaine')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Expérience -->
                                    <div class="col-md-12">
                                        <label for="nbre_annees_experience" class="form-label fw-semibold">Années
                                            d'expérience <span class="text-danger">*</span></label>
                                        <input type="number" name="nbre_annees_experience" min="0"
                                            value="{{ old('nbre_annees_experience', $operateurformateur->nbre_annees_experience) }}"
                                            class="form-control form-control-sm @error('nbre_annees_experience') is-invalid @enderror"
                                            required>
                                        @error('nbre_annees_experience')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Référence -->
                                    <div class="col-md-12">
                                        <label for="reference" class="form-label fw-semibold">Référence professionnelle
                                            <span class="text-danger">*</span></label>
                                        <input type="text" name="reference" id="reference"
                                            value="{{ old('reference', $operateurformateur->references) }}"
                                            class="form-control form-control-sm @error('reference') is-invalid @enderror"
                                            required>
                                        <small class="text-muted">Ex : Ex-Responsable à l’ENSA, Projet du BIT, etc.</small>
                                        @error('reference')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- CV (optionnel) -->
                                    <div class="col-md-10">
                                        <label for="cv" class="form-label fw-semibold">CV signé (si
                                            modification)</label>
                                        <input type="file" name="cv" id="cv"
                                            class="form-control form-control-sm @error('cv') is-invalid @enderror"
                                            accept=".pdf,.jpg,.jpeg,.png">
                                        <small class="text-muted">Laissez vide si vous ne souhaitez pas le modifier</small>
                                        @error('cv')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-2">
                                        <label for="cv" class="form-label fw-semibold">SCAN CV</label><br>
                                        @if (!empty($operateurformateur?->file))
                                            <div>
                                                <a class="btn btn-outline-secondary btn-sm" title="Convention"
                                                    target="_blank"
                                                    href="{{ asset($operateurformateur?->getCVFormateurs()) }}">
                                                    <i class="bi bi-file-earmark-pdf"></i>
                                                </a>
                                            </div>
                                        @else
                                            <div class="badge bg-warning">Aucun</div>
                                        @endif
                                    </div>

                                </div>
                            </div>

                            <!-- Modal Footer -->
                            <div class="modal-footer bg-light px-4 py-3 d-flex justify-content-between">
                                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                                    <i class="bi bi-x-circle me-1"></i> Annuler
                                </button>
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="bi bi-check-circle me-1"></i> Enregistrer les modifications
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
        <!-- End Edit Formateur-->

    </section>

@endsection
@push('scripts')
    <script>
        new DataTable('#table-regions', {
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
