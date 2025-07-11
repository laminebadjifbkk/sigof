@extends('layout.user-layout')
@section('title', 'ONFP | MODULES OPERATEURS')
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
                            <li class="breadcrumb-item active">Données</li>
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
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <h5 class="card-title mb-0">Liste des modules opérateurs</h5>
                            @can('rapport-operateur-view')
                                <a href="#"
                                    class="btn btn-outline-primary btn-sm d-flex align-items-center gap-1 px-3 py-2 rounded-pill shadow-sm"
                                    data-bs-toggle="modal" data-bs-target="#generate_rapport_module_region"
                                    title="Générer rapports">
                                    <i class="bi bi-search"></i> Rechercher plus
                                </a>
                            @endcan
                        </div>
                        <table class="table datatables table-bordered table-hover align-middle justify-content-center"
                            id="table-operateurModules">
                            <thead>
                                <tr>
                                    <th width="10%">Domaine</th>
                                    <th>Module</th>
                                    <th width="22%">Niveau de qualification</th>
                                    <th>Opérateur</th>
                                    <th>Région</th>
                                    <th>Statut</th>
                                    @can('operateurmodule-show')
                                        <th width="3%"><i class="bi bi-gear"></i></th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; ?>
                                @foreach ($operateurmodules as $operateurmodule)
                                    <tr>
                                        <td>{{ $operateurmodule?->domaine }}</td>
                                        <td>{{ $operateurmodule?->module }}</td>
                                        <td>{{ $operateurmodule?->categorie }}</td>
                                        <td>
                                            <a href="{{ route('operateurs.show', $operateurmodule?->operateur) }}">{{ $operateurmodule?->operateur?->user?->operateur . ' (' . $operateurmodule?->operateur?->user?->username . ')' }}

                                            </a>
                                        </td>
                                        <td>{{ $operateurmodule?->operateur?->region?->nom }}</td>
                                        <td>
                                            <span class="{{ $operateurmodule?->statut }}">
                                                {{ $operateurmodule?->statut }}
                                            </span>
                                        </td>
                                        @can('operateurmodule-show')
                                            <td>
                                                <span class="d-flex align-items-baseline justify-content-center"><a
                                                        href="{{ route('operateurmodules.show', $operateurmodule) }}"
                                                        class="btn btn-primary btn-sm" title="voir détails"><i
                                                            class="bi bi-eye"></i></a>
                                                    <div class="filter">
                                                        <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                                                class="bi bi-three-dots"></i></a>
                                                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                                            <li>
                                                                <button type="button" class="dropdown-item btn btn-sm mx-1"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#EditOperateurmoduleModal{{ $operateurmodule->uuid }}">
                                                                    <i class="bi bi-pencil" title="Modifier"></i> Modifier
                                                                </button>
                                                            </li>
                                                            <li>
                                                                <button type="button" class="dropdown-item btn btn-sm mx-1"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#myModal{{ $operateurmodule->id }}">
                                                                    <i class="bi bi-trash" title="Supprimer"></i> Supprimer
                                                                </button>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </span>
                                            </td>
                                        @endcan
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <!-- End Table with stripped rows -->
                    </div>
                </div>

            </div>
        </div>

        <!-- Edit Operateur Module -->
        @foreach ($operateurmodules as $operateurmodule)
            <div class="modal fade" id="EditOperateurmoduleModal{{ $operateurmodule->uuid }}" tabindex="-1" role="dialog"
                aria-labelledby="EditOperateurmoduleModalLabel{{ $operateurmodule->uuid }}" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form method="POST" action="{{ route('operateurmodules.update', $operateurmodule) }}"
                            enctype="multipart/form-data" class="needs-validation" novalidate>
                            @csrf
                            @method('PATCH')

                            <div class="card shadow-lg border-0">
                                <div class="card-header bg-default text-center py-2 rounded-top">
                                    <h4 class="mb-0">✏️ Modification</h4>
                                </div>

                                <div class="card-body row g-4 px-4">
                                    <input type="hidden" name="id" value="{{ $operateurmodule?->id }}">
                                    <input type="hidden" name="operateur" value="{{ $operateurmodule?->operateur?->id }}">

                                    {{-- Module --}}
                                    <div class="col-12">
                                        <label for="module" class="form-label">Module <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="module" id="module_operateur_edit"
                                            value="{{ old('module', $operateurmodule->module) }}"
                                            class="form-control form-control-sm @error('module') is-invalid @enderror"
                                            placeholder="Nom du module" required>
                                        <div id="moduleListEdit"></div>
                                        @error('module')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Domaine --}}
                                    <div class="col-12">
                                        <label for="domaine" class="form-label">Domaine <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="domaine"
                                            value="{{ old('domaine', $operateurmodule->domaine) }}"
                                            class="form-control form-control-sm @error('domaine') is-invalid @enderror"
                                            placeholder="Domaine du module" required>
                                        @error('domaine')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Catégorie --}}
                                    <div class="col-12">
                                        <label for="categorie" class="form-label">Catégorie <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="categorie"
                                            value="{{ old('categorie', $operateurmodule->categorie) }}"
                                            class="form-control form-control-sm @error('categorie') is-invalid @enderror"
                                            placeholder="Catégorie" required>
                                        @error('categorie')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Niveau de qualification --}}
                                    <div class="col-12">
                                        <label for="niveau_qualification" class="form-label">Niveau de qualification <span
                                                class="text-danger">*</span></label>
                                        <select name="niveau_qualification"
                                            class="form-select form-select-sm @error('niveau_qualification') is-invalid @enderror"
                                            id="select-field-niveau_qualification-update" required>
                                            <option disabled selected>Choisir un niveau</option>
                                            @foreach (['Initiation', 'Pré-qualification', 'Qualification'] as $niveau)
                                                <option value="{{ $niveau }}"
                                                    {{ old('niveau_qualification', $operateurmodule->niveau_qualification) == $niveau ? 'selected' : '' }}>
                                                    {{ $niveau }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('niveau_qualification')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="card-footer d-flex justify-content-end gap-2 p-3 bg-light border-top">
                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                                        <i class="bi bi-x-circle"></i> Fermer
                                    </button>
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="bi bi-check-circle"></i> Enregistrer les modifications
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
        <!-- End Edit Operateur Module-->
        <!-- The Modal Delete -->
        @foreach ($operateurmodules as $operateurmodule)
            <div class="modal" id="myModal{{ $operateurmodule->id }}">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Confirmation</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body">
                            Êtes-vous sûre de bien vouloir supprimer ?
                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <form method="post" action="{{ route('operateurmodules.destroy', $operateurmodule->id) }}">
                                @csrf
                                @method('DELETE')
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                                        Non</button>
                                    <button class="btn btn-danger btn-sm">
                                        <i class="bi bi-trash"></i> Oui
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <div class="modal fade" id="generate_rapport_module_region" tabindex="-1" role="dialog"
            aria-labelledby="generate_rappor_module_regionLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form method="post" action="{{ route('operateurmodules.rapport') }}">
                        @csrf
                        <div class="card shadow-lg border-0">
                            <div class="card-header bg-default text-center py-2 rounded-top">
                                <h4 class="mb-0"><i class="bi bi-search"></i>Rechercher opérateur par module</h4>
                            </div>
                            <div class="card-body row g-4 px-4">
                                <div class="modal-body">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="module" class="form-label">Module</label>
                                                        <input type="text" name="module" id="module_operateur"
                                                            class="form-control form-control-sm"
                                                            placeholder="Module ou spécialité" />
                                                        <div id="moduleList"></div>
                                                        {{ csrf_field() }}
                                                    </div>
                                                </div>
                                                {{-- <div class="col-12">
                                            <div class="form-group">
                                                <label for="operateur" class="form-label">Opérateur</label>
                                                <select name="operateur"
                                                    class="form-select  @error('operateur') is-invalid @enderror"
                                                    aria-label="Select" id="select-field-region-module-rapport"
                                                    data-placeholder="Choisir">
                                                    <option value=""></option>
                                                    @foreach ($operateurs as $op)
                                                        <option value="{{ $op?->id }}">
                                                            {{ $op?->user?->username }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('operateur')
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div> --}}
                                                {{-- <div class="col-12">
                                            <div class="form-group">
                                                <label for="statut" class="form-label">Statut</label>
                                                <select name="statut"
                                                    class="form-select form-select-sm @error('statut') is-invalid @enderror"
                                                    aria-label="Select" id="select-field-statut-rappo"
                                                    data-placeholder="Choisir">
                                                    <option value="{{ old('statut') }}">
                                                        {{ old('statut') }}
                                                    </option>

                                                    @foreach ($module_statuts as $module)
                                                        <option value="{{ $module?->statut }}">
                                                            {{ $module?->statut }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('statut')
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div> --}}
                                            </div>

                                        </div>
                                        {{-- <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary btn-sm"
                                        data-bs-dismiss="modal">Fermer</button>
                                    <div class="text-center">
                                        <button type="submit"
                                            class="btn btn-primary btn-block submit_rapport btn-sm">Envoyer</button>
                                    </div>
                                </div> --}}
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer bg-light border-0 rounded-bottom-4 d-flex justify-content-between">
                                <button type="button" class="btn btn-outline-secondary btn-sm px-4 py-2 rounded-pill"
                                    data-bs-dismiss="modal">
                                    <i class="bi bi-x-circle"></i> Fermer
                                </button>
                                <button type="submit"
                                    class="btn btn-success btn-sm px-4 py-2 rounded-pill submit_rapport shadow-sm">
                                    <i class="bi bi-search"></i> Rechercher
                                </button>
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
        new DataTable('#table-operateurModules', {
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
