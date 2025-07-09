@extends('layout.user-layout')
@section('title', remove_accents_uppercase($operateur?->user?->username) . ' | ' .
    remove_accents_uppercase('infrastructures et équipements'))
@section('space-work')

    <section class="section register">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="pagetitle">
                    {{-- <h1>Data Tables</h1> --}}
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Accueil</a></li>
                            <li class="breadcrumb-item">Tables</li>
                            <li class="breadcrumb-item active">Equipements</li>
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
                            <h5 class="card-title">INFRASTRUCTURES / EQUIPEMENTS</h5>
                            @can('devenir-operateur-agrement-ouvert')
                                @can('agrement-visible-par-op')
                                    <h5 class="card-title">
                                        <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#AddRefModal">Ajouter
                                        </button>
                                    </h5>
                                @endcan
                            @endcan
                        </div> --}}
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0 text-uppercase fw-bold text-primary">
                                <i class="bi bi-person-lines-fill me-2"></i>INFRASTRUCTURES / EQUIPEMENTS
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
                        <!-- Table with stripped rows -->
                        <table
                            class="table table-bordered table-hover datatables align-middle justify-content-center table-borderless">
                            <thead class="table-primary text-center">
                                <tr>
                                    <th class="text-center">N°</th>
                                    <th>DESIGNATION</th>
                                    <th class="text-center">QUANTITE</th>
                                    <th class="text-center">ETAT</th>
                                    <th class="text-center">TYPE</th>
                                    <th class="text-center" width="2%"><i class="bi bi-gear"></i></th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                <?php $i = 1; ?>
                                @foreach ($operateur->operateurequipements as $operateurequipement)
                                    <tr>
                                        <td style="text-align: center;">{{ $i++ }}</td>
                                        <td>{{ $operateurequipement->designation }}</td>
                                        <td style="text-align: center;">{{ $operateurequipement->quantite }}</td>
                                        <td style="text-align: center;">{{ $operateurequipement->etat }}</td>
                                        <td style="text-align: center;">{{ $operateurequipement->type }}</td>
                                        <td style="text-align: center;">
                                            <span class="d-flex align-items-baseline justify-content-center"><a
                                                    href="" class="btn btn-outline-info btn-sm mx-1"
                                                    title="Voir détails">
                                                    <i class="bi bi-eye"></i></a>
                                                @can('devenir-operateur-agrement-ouvert')
                                                    <div class="filter">
                                                        <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                                                class="bi bi-three-dots"></i></a>
                                                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                                            <li>
                                                                <button type="button" class="dropdown-item btn btn-sm mx-1"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#EditoperateurequipementModal{{ $operateurequipement->id }}">
                                                                    <i class="bi bi-pencil" title="Modifier"></i> Modifier
                                                                </button>
                                                            </li>
                                                            <li>
                                                                <form
                                                                    action="{{ route('operateurequipements.destroy', $operateurequipement->id) }}"
                                                                    method="post">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="dropdown-item show_confirm"><i
                                                                            class="bi bi-trash"></i>Supprimer</button>
                                                                </form>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                @endcan
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <!-- End Table with stripped rows -->
                    </div>
                </div>

            </div>
        </div>

        <!-- Add References -->
        {{-- <div class="modal fade" id="AddRefModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form method="post" action="{{ route('operateurequipements.store') }}" enctype="multipart/form-data"
                        class="row g-3">
                        @csrf
                        <div class="card-header text-center bg-gradient-default">
                            <h1 class="h4 text-black mb-0">INFRASTRUCTURES / EQUIPEMENTS</h1>
                        </div>
                        <input type="hidden" name="operateur" value="{{ $operateur->id }}">
                        <div class="modal-body">
                            <div class="col-12 mb-2">
                                <label for="designation" class="form-label">Désignation<span
                                        class="text-danger mx-1">*</span></label>
                                <input type="text" name="designation" value="{{ old('designation') }}"
                                    class="form-control form-control-sm @error('designation') is-invalid @enderror"
                                    placeholder="Désignation">
                                @error('designation')
                                    <span class="invalid-feedback" role="alert">
                                        <div>{{ $message }}</div>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-12 mb-2">
                                <label for="quantite" class="form-label">Quantité<span
                                        class="text-danger mx-1">*</span></label>
                                <input type="number" min="0" name="quantite" value="{{ old('quantite') }}"
                                    class="form-control form-control-sm @error('quantite') is-invalid @enderror"
                                    placeholder="Quantité">
                                @error('quantite')
                                    <span class="invalid-feedback" role="alert">
                                        <div>{{ $message }}</div>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-12 mb-2">
                                <label for="etat" class="form-label">Etat<span
                                        class="text-danger mx-1">*</span></label>
                                <select name="etat" class="form-select selectpicker"
                                    data-live-search="true @error('etat') is-invalid @enderror" aria-label="Select"
                                    id="select-field-etat-add" data-placeholder="Choisir etat">
                                    <option value="">
                                        {{ old('etat') }}
                                    </option>
                                    <option value="Neuf(ve)">
                                        Neuf(ve)
                                    </option>
                                    <option value="Bon etat">
                                        Bon etat
                                    </option>
                                    <option value="Usé(e)">
                                        Usé(e)
                                    </option>
                                </select>
                                @error('etat')
                                    <span class="invalid-feedback" role="alert">
                                        <div>{{ $message }}</div>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-12 mb-2">
                                <label for="type" class="form-label">Type<span
                                        class="text-danger mx-1">*</span></label>
                                <select name="type" class="form-select selectpicker"
                                    data-live-search="true @error('type') is-invalid @enderror" aria-label="Select"
                                    id="select-field-type-add" data-placeholder="Choisir type">
                                    <option value="">
                                        {{ old('type') }}
                                    </option>
                                    <option value="Infrastructure">
                                        Infrastructure
                                    </option>
                                    <option value="Equipement">
                                        Equipement
                                    </option>
                                </select>
                                @error('type')
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
        <div class="modal fade" id="AddRefModal" tabindex="-1" aria-labelledby="AddEquipementModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content shadow-lg border-0 rounded-4 overflow-hidden">
                    <form method="POST" action="{{ route('operateurequipements.store') }}" enctype="multipart/form-data"
                        class="p-3">
                        @csrf

                        <div class="bg-dark text-white text-center py-3">
                            <h5 class="mb-0 text-uppercase fw-bold">
                                <i class="bi bi-building-gear me-2"></i> Ajouter un équipement / infrastructure
                            </h5>
                        </div>

                        <input type="hidden" name="operateur" value="{{ $operateur->id }}">

                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="designation" class="form-label">Désignation <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="designation" value="{{ old('designation') }}"
                                    class="form-control form-control-sm @error('designation') is-invalid @enderror"
                                    placeholder="Ex. : Tableau numérique interactif">
                                @error('designation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="quantite" class="form-label">Quantité <span
                                        class="text-danger">*</span></label>
                                <input type="number" name="quantite" min="0" value="{{ old('quantite') }}"
                                    class="form-control form-control-sm @error('quantite') is-invalid @enderror"
                                    placeholder="Ex. : 5">
                                @error('quantite')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="etat" class="form-label">État <span class="text-danger">*</span></label>
                                <select name="etat" id="select-field-etat-add"
                                    class="form-select form-select-sm @error('etat') is-invalid @enderror"
                                    data-placeholder="Choisir l'état">
                                    <option value="">-- Choisir --</option>
                                    <option value="Neuf(ve)" {{ old('etat') == 'Neuf(ve)' ? 'selected' : '' }}>Neuf(ve)
                                    </option>
                                    <option value="Bon etat" {{ old('etat') == 'Bon etat' ? 'selected' : '' }}>Bon état
                                    </option>
                                    <option value="Usé(e)" {{ old('etat') == 'Usé(e)' ? 'selected' : '' }}>Usé(e)</option>
                                </select>
                                @error('etat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
                                <select name="type" id="select-field-type-add"
                                    class="form-select form-select-sm @error('type') is-invalid @enderror"
                                    data-placeholder="Choisir le type">
                                    <option value="">-- Choisir --</option>
                                    <option value="Infrastructure"
                                        {{ old('type') == 'Infrastructure' ? 'selected' : '' }}>Infrastructure</option>
                                    <option value="Equipement" {{ old('type') == 'Equipement' ? 'selected' : '' }}>
                                        Équipement</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="modal-footer d-flex justify-content-between px-4">
                            <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill"
                                data-bs-dismiss="modal">
                                <i class="bi bi-x-circle me-1"></i> Fermer
                            </button>
                            <button type="submit" class="btn btn-dark btn-sm rounded-pill text-white">
                                <i class="bi bi-save2 me-1"></i> Ajouter
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- End Add References-->
        <!-- Edit References -->
        {{-- @foreach ($operateurequipements as $operateurequipement)
            <div class="modal fade" id="EditoperateurequipementModal{{ $operateurequipement->id }}" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form method="post"
                            action="{{ route('operateurequipements.update', $operateurequipement->id) }}"
                            enctype="multipart/form-data" class="row g-3">
                            @csrf
                            @method('patch')
                            <div class="card-header text-center bg-gradient-default">
                                <h1 class="h4 text-black mb-0">MODIFICATION</h1>
                            </div>
                            <input type="hidden" name="operateur" value="{{ $operateur->id }}">
                            <div class="modal-body">
                                <div class="col-12 mb-2">
                                    <label for="designation" class="form-label">Désignation<span
                                            class="text-danger mx-1">*</span></label>
                                    <input type="text" name="designation"
                                        value="{{ $operateurequipement->designation ?? old('designation') }}"
                                        class="form-control form-control-sm @error('designation') is-invalid @enderror"
                                        placeholder="Désignation">
                                    @error('designation')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-12 mb-2">
                                    <label for="quantite" class="form-label">Quantité<span
                                            class="text-danger mx-1">*</span></label>
                                    <input type="number" min="0" name="quantite"
                                        value="{{ $operateurequipement->quantite ?? old('quantite') }}"
                                        class="form-control form-control-sm @error('quantite') is-invalid @enderror"
                                        placeholder="Quantité">
                                    @error('quantite')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12 mb-2">
                                    <label for="etat" class="form-label">Etat<span
                                            class="text-danger mx-1">*</span></label>
                                    <select name="etat" class="form-select selectpicker"
                                        data-live-search="true @error('etat') is-invalid @enderror" aria-label="Select"
                                        id="select-field-etat-update" data-placeholder="Choisir etat">
                                        <option value="{{ $operateurequipement->etat }}">
                                            {{ $operateurequipement->etat ?? old('etat') }}
                                        </option>
                                        <option value="Neuf(ve)">
                                            Neuf(ve)
                                        </option>
                                        <option value="Bon etat">
                                            Bon etat
                                        </option>
                                        <option value="Usé(e)">
                                            Usé(e)
                                        </option>
                                    </select>
                                    @error('etat')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-12 mb-2">
                                    <label for="type" class="form-label">Type<span
                                            class="text-danger mx-1">*</span></label>
                                    <select name="type" class="form-select selectpicker"
                                        data-live-search="true @error('type') is-invalid @enderror" aria-label="Select"
                                        id="select-field-type-update" data-placeholder="Choisir type">
                                        <option value="{{ $operateurequipement->type }}">
                                            {{ $operateurequipement->type ?? old('type') }}
                                        </option>
                                        <option value="Infrastructure">
                                            Infrastructure
                                        </option>
                                        <option value="Equipement">
                                            Equipement
                                        </option>
                                    </select>
                                    @error('type')
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
        <!-- End Edit References-->
        @foreach ($operateurequipements as $operateurequipement)
            <div class="modal fade" id="EditoperateurequipementModal{{ $operateurequipement->id }}" tabindex="-1"
                aria-labelledby="EditEquipementModalLabel{{ $operateurequipement->id }}" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content shadow-lg border-0 rounded-4 overflow-hidden">
                        <form method="POST"
                            action="{{ route('operateurequipements.update', $operateurequipement->id) }}"
                            enctype="multipart/form-data" class="p-3">
                            @csrf
                            @method('patch')

                            <div class="bg-dark text-white text-center py-3">
                                <h5 class="mb-0 text-uppercase fw-bold">
                                    <i class="bi bi-pencil-fill me-2"></i> Modifier un équipement / infrastructure
                                </h5>
                            </div>

                            <input type="hidden" name="operateur" value="{{ $operateur->id }}">

                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="designation" class="form-label">Désignation <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="designation"
                                        value="{{ old('designation', $operateurequipement->designation) }}"
                                        class="form-control form-control-sm @error('designation') is-invalid @enderror"
                                        placeholder="Ex. : Tableau numérique interactif">
                                    @error('designation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="quantite" class="form-label">Quantité <span
                                            class="text-danger">*</span></label>
                                    <input type="number" min="0" name="quantite"
                                        value="{{ old('quantite', $operateurequipement->quantite) }}"
                                        class="form-control form-control-sm @error('quantite') is-invalid @enderror"
                                        placeholder="Ex. : 5">
                                    @error('quantite')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="etat" class="form-label">État <span
                                            class="text-danger">*</span></label>
                                    <select name="etat" id="select-field-etat-update"
                                        class="form-select form-select-sm @error('etat') is-invalid @enderror">
                                        <option value="">-- Choisir l'état --</option>
                                        <option value="Neuf(ve)"
                                            {{ old('etat', $operateurequipement->etat) == 'Neuf(ve)' ? 'selected' : '' }}>
                                            Neuf(ve)</option>
                                        <option value="Bon etat"
                                            {{ old('etat', $operateurequipement->etat) == 'Bon etat' ? 'selected' : '' }}>
                                            Bon état</option>
                                        <option value="Usé(e)"
                                            {{ old('etat', $operateurequipement->etat) == 'Usé(e)' ? 'selected' : '' }}>
                                            Usé(e)</option>
                                    </select>
                                    @error('etat')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="type" class="form-label">Type <span
                                            class="text-danger">*</span></label>
                                    <select name="type" id="select-field-type-update"
                                        class="form-select form-select-sm @error('type') is-invalid @enderror">
                                        <option value="">-- Choisir le type --</option>
                                        <option value="Infrastructure"
                                            {{ old('type', $operateurequipement->type) == 'Infrastructure' ? 'selected' : '' }}>
                                            Infrastructure</option>
                                        <option value="Equipement"
                                            {{ old('type', $operateurequipement->type) == 'Equipement' ? 'selected' : '' }}>
                                            Équipement</option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="modal-footer d-flex justify-content-between px-4">
                                <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill"
                                    data-bs-dismiss="modal">
                                    <i class="bi bi-x-circle me-1"></i> Fermer
                                </button>
                                <button type="submit" class="btn btn-dark btn-sm rounded-pill text-white">
                                    <i class="bi bi-check2-square me-1"></i> Modifier
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
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
