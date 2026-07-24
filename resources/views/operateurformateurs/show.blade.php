@extends('layout.user-layout')
@section('title', $operateur?->user?->display_operateur . ' | ' . remove_accents_uppercase('formateurs'))
@section('space-work')

    <section class="section">
        <div class="row justify-content-center">
            <div class="col-12">

                {{-- Breadcrumb --}}
                <div class="pagetitle">
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Accueil</a></li>
                            <li class="breadcrumb-item">Tables</li>
                            <li class="breadcrumb-item active">Formateurs</li>
                        </ol>
                    </nav>
                </div>

                {{-- Alertes --}}
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
                            role="alert">
                            <strong>{{ $error }}</strong>
                        </div>
                    @endforeach
                @endif

                {{-- Tableau --}}
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0 text-uppercase fw-bold text-primary">
                                <i class="bi bi-person-lines-fill me-2"></i> Formateurs
                            </h5>
                            {{-- @can('devenir-operateur-agrement-ouvert')
                                @can('agrement-visible-par-op') --}}
                            @can('create-formateurs')
                                <button type="button" class="btn btn-primary btn-sm d-flex align-items-center shadow-sm"
                                    data-bs-toggle="modal" data-bs-target="#AddformateurModal">
                                    <i class="bi bi-plus-circle me-2"></i> Ajouter
                                </button>
                            @endcan
                            {{-- @endcan
                            @endcan --}}
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle">
                                <thead class="table-primary text-center">
                                    <tr>
                                        <th>N°</th>
                                        <th>Prénom(s) & Nom</th>
                                        <th>Spécialité</th>
                                        <th>Expérience (ans)</th>
                                        <th>Réf. professionnelles</th>
                                        <th>CV</th>
                                        <th>Statut</th>
                                        <th><i class="bi bi-gear"></i></th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    @php $i = 1; @endphp
                                    @foreach ($operateur->operateurformateurs as $operateurformateur)
                                        <tr>
                                            <td class="fw-semibold">{{ $i++ }}</td>
                                            <td>{{ $operateurformateur?->name }}</td>
                                            <td>{{ $operateurformateur?->domaine }}</td>
                                            <td>{{ $operateurformateur?->nbre_annees_experience }}</td>
                                            <td>{{ $operateurformateur?->references }}</td>
                                            <td>
                                                @if ($operateurformateur?->file)
                                                    <a class="btn btn-outline-secondary btn-sm" title="CV"
                                                        target="_blank"
                                                        href="{{ asset($operateurformateur?->getCVFormateurs()) }}">
                                                        <i class="bi bi-file-earmark-pdf"></i>
                                                    </a>
                                                @else
                                                    <span class="text-muted small">Aucun</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="{{ $operateurformateur?->statut }}">
                                                    {{ $operateurformateur?->statut }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-center align-items-center gap-2">
                                                    <a href="#" class="btn btn-outline-info btn-sm"
                                                        title="Voir détails">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    @can('create-formateurs')
                                                        <div class="dropdown">
                                                            <a class="btn btn-sm btn-light" href="#"
                                                                data-bs-toggle="dropdown" aria-expanded="false" title="Actions">
                                                                <i class="bi bi-three-dots-vertical"></i>
                                                            </a>
                                                            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                                                <li>
                                                                    <button type="button" class="dropdown-item"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#EditModal{{ $operateurformateur->id }}">
                                                                        <i class="bi bi-pencil me-2"></i>Modifier
                                                                    </button>
                                                                </li>
                                                                <li>
                                                                    <form
                                                                        action="{{ route('operateurformateurs.destroy', $operateurformateur->id) }}"
                                                                        method="POST"
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
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ====== MODALS (hors de tout conteneur imbriqué) ====== --}}

    {{-- Modal Ajouter --}}
    <div class="modal fade" id="AddformateurModal" tabindex="-1" aria-labelledby="AddformateurModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content shadow-lg border-0 rounded-4 overflow-hidden">
                <form method="POST" action="{{ route('operateurformateurs.store') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="operateur" value="{{ $operateur->id }}">

                    <div class="modal-header bg-warning bg-gradient text-dark py-2 px-4">
                        <div>
                            <h5 class="modal-title fw-bold text-uppercase mb-0" id="AddformateurModalLabel">
                                Ajouter un Formateur
                            </h5>
                            <small>Remplissez soigneusement les informations du formateur</small>
                        </div>
                    </div>

                    <div class="modal-body px-4 py-4">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold">Prénom(s) et Nom <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="name" value="{{ old('name') }}"
                                    class="form-control form-control-sm @error('name') is-invalid @enderror"
                                    placeholder="Ex : Fatou Diop" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Domaine d'expertise <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="domaine" value="{{ old('domaine') }}"
                                    class="form-control form-control-sm @error('domaine') is-invalid @enderror"
                                    placeholder="Ex : Comptabilité, Génie Civil..." required>
                                @error('domaine')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Années d'expérience <span
                                        class="text-danger">*</span></label>
                                <input type="number" name="nbre_annees_experience"
                                    value="{{ old('nbre_annees_experience') }}" min="0"
                                    class="form-control form-control-sm @error('nbre_annees_experience') is-invalid @enderror"
                                    placeholder="Ex : 7" required>
                                @error('nbre_annees_experience')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Référence professionnelle <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="reference" value="{{ old('reference') }}"
                                    class="form-control form-control-sm @error('reference') is-invalid @enderror"
                                    placeholder="Ex : Ex-Responsable à l'ENSA ou Projet X du BIT" required>
                                <small class="text-muted">Structure, projet, responsable ou mission</small>
                                @error('reference')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">CV signé <span class="text-danger">*</span></label>
                                <input type="file" name="cv"
                                    class="form-control form-control-sm @error('cv') is-invalid @enderror"
                                    accept=".pdf,.jpg,.jpeg,.png" required>
                                <small class="text-muted">Formats acceptés : PDF, JPG, PNG</small>
                                @error('cv')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

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

    {{-- Modals Modifier --}}
    @foreach ($operateurformateurs as $operateurformateur)
        <div class="modal fade" id="EditModal{{ $operateurformateur->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content shadow-lg border-0 rounded-4 overflow-hidden">
                    <form method="POST" action="{{ route('operateurformateurs.update', $operateurformateur->id) }}"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="operateur" value="{{ $operateur->id }}">

                        <div class="modal-header bg-info bg-gradient text-white py-2 px-4">
                            <div>
                                <h5 class="modal-title fw-bold text-uppercase mb-0">Modifier le Formateur</h5>
                                <small>Mettez à jour les informations du formateur</small>
                            </div>
                        </div>

                        <div class="modal-body px-4 py-4">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Prénom(s) et Nom <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="name"
                                        value="{{ old('name', $operateurformateur->name) }}"
                                        class="form-control form-control-sm @error('name') is-invalid @enderror" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Domaine d'expertise <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="domaine"
                                        value="{{ old('domaine', $operateurformateur->domaine) }}"
                                        class="form-control form-control-sm @error('domaine') is-invalid @enderror"
                                        required>
                                    @error('domaine')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Années d'expérience <span
                                            class="text-danger">*</span></label>
                                    <input type="number" name="nbre_annees_experience" min="0"
                                        value="{{ old('nbre_annees_experience', $operateurformateur->nbre_annees_experience) }}"
                                        class="form-control form-control-sm @error('nbre_annees_experience') is-invalid @enderror"
                                        required>
                                    @error('nbre_annees_experience')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Référence professionnelle <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="reference"
                                        value="{{ old('reference', $operateurformateur->references) }}"
                                        class="form-control form-control-sm @error('reference') is-invalid @enderror"
                                        required>
                                    <small class="text-muted">Structure, projet, responsable ou mission</small>
                                    @error('reference')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-10">
                                    <label class="form-label fw-semibold">CV signé (si modification)</label>
                                    <input type="file" name="cv"
                                        class="form-control form-control-sm @error('cv') is-invalid @enderror"
                                        accept=".pdf,.jpg,.jpeg,.png">
                                    <small class="text-muted">Laisser vide pour conserver l'actuel</small>
                                    @error('cv')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-2 text-center">
                                    <label class="form-label fw-semibold">CV actuel</label><br>
                                    @if (!empty($operateurformateur?->file))
                                        <a class="btn btn-outline-secondary btn-sm" title="Voir CV" target="_blank"
                                            href="{{ asset($operateurformateur?->getCVFormateurs()) }}">
                                            <i class="bi bi-file-earmark-pdf"></i>
                                        </a>
                                    @else
                                        <span class="badge bg-warning">Aucun</span>
                                    @endif
                                </div>
                            </div>
                        </div>

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

@endsection
