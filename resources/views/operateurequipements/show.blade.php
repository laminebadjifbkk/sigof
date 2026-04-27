@extends('layout.user-layout')
@section('title', $operateur?->user?->display_operateur . ' | ' . remove_accents_uppercase('infrastructures et équipements'))
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
                            <li class="breadcrumb-item active">Equipements</li>
                        </ol>
                    </nav>
                </div>

                {{-- Alertes --}}
                @if ($message = Session::get('status'))
                    <div class="alert alert-success bg-success text-light border-0 alert-dismissible fade show" role="alert">
                        <strong>{{ $message }}</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if ($message = Session::get('danger'))
                    <div class="alert alert-danger bg-danger text-light border-0 alert-dismissible fade show" role="alert">
                        <strong>{{ $message }}</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <div class="alert alert-danger bg-danger text-light border-0 alert-dismissible fade show" role="alert">
                            <strong>{{ $error }}</strong>
                        </div>
                    @endforeach
                @endif

                {{-- Tableau --}}
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0 text-uppercase fw-bold text-primary">
                                <i class="bi bi-building-gear me-2"></i> Infrastructures / Équipements
                            </h5>
                            @can('devenir-operateur-agrement-ouvert')
                                @can('agrement-visible-par-op')
                                    <button type="button" class="btn btn-primary btn-sm d-flex align-items-center shadow-sm"
                                        data-bs-toggle="modal" data-bs-target="#AddRefModal">
                                        <i class="bi bi-plus-circle me-2"></i> Ajouter
                                    </button>
                                @endcan
                            @endcan
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle">
                                <thead class="table-primary text-center">
                                    <tr>
                                        <th>N°</th>
                                        <th>Désignation</th>
                                        <th>Quantité</th>
                                        <th>État</th>
                                        <th>Type</th>
                                        <th>Statut</th>
                                        <th><i class="bi bi-gear"></i></th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    @php $i = 1; @endphp
                                    @foreach ($operateur->operateurequipements as $operateurequipement)
                                        <tr>
                                            <td class="fw-semibold">{{ $i++ }}</td>
                                            <td>{{ $operateurequipement?->designation }}</td>
                                            <td>{{ $operateurequipement?->quantite }}</td>
                                            <td>{{ $operateurequipement?->etat }}</td>
                                            <td>{{ $operateurequipement?->type }}</td>
                                            <td>
                                                <span class="{{ $operateurequipement?->statut }}">
                                                    {{ $operateurequipement?->statut }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-center align-items-center gap-2">
                                                    <a href="#" class="btn btn-outline-info btn-sm" title="Voir détails">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    @can('devenir-operateur-agrement-ouvert')
                                                        <div class="dropdown">
                                                            <a class="btn btn-sm btn-light" href="#"
                                                                data-bs-toggle="dropdown" aria-expanded="false" title="Actions">
                                                                <i class="bi bi-three-dots-vertical"></i>
                                                            </a>
                                                            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                                                <li>
                                                                    <button type="button" class="dropdown-item"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#EditModal{{ $operateurequipement->id }}">
                                                                        <i class="bi bi-pencil me-2"></i>Modifier
                                                                    </button>
                                                                </li>
                                                                <li>
                                                                    <form action="{{ route('operateurequipements.destroy', $operateurequipement->id) }}"
                                                                        method="POST"
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
                </div>

            </div>
        </div>
    </section>

    {{-- ====== MODALS (hors de tout conteneur imbriqué) ====== --}}

    {{-- Modal Ajouter --}}
    <div class="modal fade" id="AddRefModal" tabindex="-1" aria-labelledby="AddRefModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content shadow-lg border-0 rounded-4 overflow-hidden">
                <form method="POST" action="{{ route('operateurequipements.store') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="operateur" value="{{ $operateur->id }}">

                    <div class="modal-header bg-dark text-white py-2 px-4">
                        <div>
                            <h5 class="modal-title fw-bold text-uppercase mb-0" id="AddRefModalLabel">
                                <i class="bi bi-building-gear me-2"></i> Ajouter un équipement / infrastructure
                            </h5>
                        </div>
                    </div>

                    <div class="modal-body px-4 py-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Désignation <span class="text-danger">*</span></label>
                            <input type="text" name="designation" value="{{ old('designation') }}"
                                class="form-control form-control-sm @error('designation') is-invalid @enderror"
                                placeholder="Ex. : Salles de cours ou Ordinateurs" required>
                            @error('designation')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Quantité <span class="text-danger">*</span></label>
                            <input type="number" name="quantite" min="0" value="{{ old('quantite') }}"
                                class="form-control form-control-sm @error('quantite') is-invalid @enderror"
                                placeholder="Ex. : 5" required>
                            @error('quantite')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">État <span class="text-danger">*</span></label>
                            <select name="etat" class="form-select form-select-sm @error('etat') is-invalid @enderror" required>
                                <option value="">-- Choisir --</option>
                                <option value="Neuf(ve)"  {{ old('etat') == 'Neuf(ve)'  ? 'selected' : '' }}>Neuf(ve)</option>
                                <option value="Bon etat"  {{ old('etat') == 'Bon etat'  ? 'selected' : '' }}>Bon état</option>
                                <option value="Usé(e)"    {{ old('etat') == 'Usé(e)'    ? 'selected' : '' }}>Usé(e)</option>
                            </select>
                            @error('etat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Type <span class="text-danger">*</span></label>
                            <select name="type" class="form-select form-select-sm @error('type') is-invalid @enderror" required>
                                <option value="">-- Choisir --</option>
                                <option value="Infrastructure" {{ old('type') == 'Infrastructure' ? 'selected' : '' }}>Infrastructure</option>
                                <option value="Equipement"    {{ old('type') == 'Equipement'    ? 'selected' : '' }}>Équipement</option>
                            </select>
                            @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="modal-footer bg-light px-4 py-3 d-flex justify-content-between">
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i> Fermer
                        </button>
                        <button type="submit" class="btn btn-dark btn-sm">
                            <i class="bi bi-save2 me-1"></i> Ajouter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modals Modifier --}}
    @foreach ($operateurequipements as $operateurequipement)
        <div class="modal fade" id="EditModal{{ $operateurequipement->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content shadow-lg border-0 rounded-4 overflow-hidden">
                    <form method="POST" action="{{ route('operateurequipements.update', $operateurequipement->id) }}"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="operateur" value="{{ $operateur->id }}">

                        <div class="modal-header bg-dark text-white py-2 px-4">
                            <div>
                                <h5 class="modal-title fw-bold text-uppercase mb-0">
                                    <i class="bi bi-pencil-fill me-2"></i> Modifier un équipement / infrastructure
                                </h5>
                            </div>
                        </div>

                        <div class="modal-body px-4 py-4">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Désignation <span class="text-danger">*</span></label>
                                <input type="text" name="designation"
                                    value="{{ old('designation', $operateurequipement->designation) }}"
                                    class="form-control form-control-sm @error('designation') is-invalid @enderror"
                                    placeholder="Ex. : Salles de cours ou Ordinateurs" required>
                                @error('designation')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Quantité <span class="text-danger">*</span></label>
                                <input type="number" min="0" name="quantite"
                                    value="{{ old('quantite', $operateurequipement->quantite) }}"
                                    class="form-control form-control-sm @error('quantite') is-invalid @enderror"
                                    placeholder="Ex. : 5" required>
                                @error('quantite')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">État <span class="text-danger">*</span></label>
                                <select name="etat" class="form-select form-select-sm @error('etat') is-invalid @enderror" required>
                                    <option value="">-- Choisir l'état --</option>
                                    <option value="Neuf(ve)"  {{ old('etat', $operateurequipement->etat) == 'Neuf(ve)'  ? 'selected' : '' }}>Neuf(ve)</option>
                                    <option value="Bon etat"  {{ old('etat', $operateurequipement->etat) == 'Bon etat'  ? 'selected' : '' }}>Bon état</option>
                                    <option value="Usé(e)"    {{ old('etat', $operateurequipement->etat) == 'Usé(e)'    ? 'selected' : '' }}>Usé(e)</option>
                                </select>
                                @error('etat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Type <span class="text-danger">*</span></label>
                                <select name="type" class="form-select form-select-sm @error('type') is-invalid @enderror" required>
                                    <option value="">-- Choisir le type --</option>
                                    <option value="Infrastructure" {{ old('type', $operateurequipement->type) == 'Infrastructure' ? 'selected' : '' }}>Infrastructure</option>
                                    <option value="Equipement"    {{ old('type', $operateurequipement->type) == 'Equipement'    ? 'selected' : '' }}>Équipement</option>
                                </select>
                                @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="modal-footer bg-light px-4 py-3 d-flex justify-content-between">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                                <i class="bi bi-x-circle me-1"></i> Fermer
                            </button>
                            <button type="submit" class="btn btn-dark btn-sm">
                                <i class="bi bi-check2-square me-1"></i> Modifier
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

@endsection