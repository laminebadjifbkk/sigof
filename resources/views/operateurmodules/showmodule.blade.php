@extends('layout.user-layout')
@section('title', $operateur?->user?->display_operateur . ' | ' . remove_accents_uppercase('localités'))
@section('space-work')

    <section class="section register">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="pagetitle">

                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Accueil</a></li>
                            <li class="breadcrumb-item">Tables</li>
                            <li class="breadcrumb-item active">Modules</li>
                        </ol>
                    </nav>
                </div>

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
                        {{--  <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0 text-uppercase fw-bold text-primary">
                                <i class="bi bi-person-lines-fill me-2"></i> Modules
                            </h5>
                            @can('devenir-operateur-agrement-ouvert')
                                @can('agrement-visible-par-op')
                                    <button type="button" class="btn btn-primary btn-sm d-flex align-items-center shadow-sm"
                                        data-bs-toggle="modal" data-bs-target="#AddlocaliteModal">
                                        <i class="bi bi-plus-circle me-2"></i> Ajouter
                                    </button>
                                @endcan
                            @endcan
                        </div> --}}

                        <div class="tab-content pt-2">
                            <div class="tab-pane show active fade profile-overview" id="module-overview">
                                <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
                                    <h5 class="mb-0 text-uppercase fw-bold text-primary">
                                        <i class="bi bi-briefcase-fill me-2"></i> Modules de formation
                                    </h5>
                                </div>

                                <p class="small fst-italic">
                                    <small>{{ __('Le nombre de modules est limité à cinq(05)') }}</small>
                                    <small>
                                        {{ __(' sauf pour les établissements publics ') }}</small>
                                </p>
                                @can('agrement-visible-par-op')
                                    <form method="post" action="{{ route('operateurmodules.store') }}"
                                        enctype="multipart/form-data" class="row g-3">
                                        @csrf
                                        @can('devenir-operateur-agrement-ouvert')
                                            <div class="col-12 mb-0">
                                                <div class="row g-3">
                                                    <input type="hidden" name="operateur" value="{{ $operateur?->id }}">

                                                    <!-- DOMAINE & MODULE côte à côte -->
                                                    <div class="col-md-6">
                                                        <label for="domaine" class="form-label">DOMAINE <span
                                                                class="text-danger">*</span></label>
                                                        <select name="domaine" id="select-field-civilite"
                                                            class="form-select form-select-sm @error('domaine') is-invalid @enderror">
                                                            <option value="">-- Sélectionnez un domaine --</option>
                                                            @foreach ($domaines as $domaine)
                                                                <option value="{{ $domaine->name }}">{{ $domaine->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('domaine')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label for="module" class="form-label">MODULE OU SPECIALITE <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" name="module" id="module_operateur"
                                                            class="form-control form-control-sm @error('module') is-invalid @enderror"
                                                            placeholder="Module ou spécialité" />
                                                        <div id="moduleList"></div>
                                                    </div>

                                                    <!-- NIVEAU QUALIFICATION & EMPLOI OU METIER côte à côte -->
                                                    <div class="col-md-6">
                                                        <label for="niveau_qualification" class="form-label">TITRE OU NIVEAU
                                                            DE
                                                            QUALIFICATION <span class="text-danger">*</span></label>
                                                        <select name="niveau_qualification"
                                                            class="form-select form-select-sm @error('niveau_qualification') is-invalid @enderror"
                                                            aria-label="Select" id="select-field-niveau_qualification"
                                                            data-placeholder="Choisir qualification">
                                                            <option value="">{{ old('niveau_qualification') }}</option>
                                                            <option value="Pré-qualification">Pré-qualification</option>
                                                            <option value="Renforcement de capacités">Renforcement de capacités
                                                            </option>
                                                            <option value="Qualification">Qualification</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label for="categorie" class="form-label">CATEGORIE
                                                            PROFESSIONNELLE</label>
                                                        <input type="text" name="categorie" placeholder="Niveau de qualification"
                                                            class="form-control form-control-sm @error('categorie') is-invalid @enderror" />

                                                        <p class="small fst-italic mb-0"
                                                            style="white-space: normal; word-wrap: break-word;">
                                                            {{ __("Préciser la catégorie professionnelle, l'emploi ou le métier correspondant lorsqu'il s'agit d'une pré-qualification ou qualification") }}
                                                        </p>
                                                    </div>

                                                    <!-- Bouton -->
                                                    <div class="col-12 text-center">
                                                        <button type="submit"
                                                            class="btn btn-outline-success btn-sm">Enregistrer</button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endcan

                                    </form><!-- End module -->
                                @endcan

                                <div class="col-12 mb-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="card-title">DOMAINES DE COMPETENCES OU PROGRAMMES DE FORMATION</h5>
                                        <span class="card-title d-flex align-items-baseline">Statut
                                            :&nbsp;
                                            <span class="{{ $operateur?->statut_agrement }} text-white btn-sm">
                                                {{ $operateur?->statut_agrement }}</span>
                                            @can('agrement-view')
                                                <div class="filter">
                                                    <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                                            class="bi bi-three-dots"></i></a>
                                                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                                        @can('fichesynthese-view')
                                                            <li>
                                                                <form action="{{ route('ficheSyntheseOperateur') }}" method="post"
                                                                    target="_blank">
                                                                    @csrf
                                                                    <input type="hidden" name="id"
                                                                        value="{{ $operateur?->id }}">
                                                                    <button class="btn btn-sm mx-1">Fiche synthèse</button>
                                                                </form>
                                                            </li>
                                                        @endcan
                                                        @can('lettreagrement-view')
                                                            <li>
                                                                <form action="{{ route('lettreOperateur') }}" method="post"
                                                                    target="_blank">
                                                                    @csrf
                                                                    <input type="hidden" name="id"
                                                                        value="{{ $operateur?->id }}">
                                                                    <button class="btn btn-sm mx-1">Lettre agrément</button>
                                                                </form>
                                                            </li>
                                                        @endcan
                                                    </ul>
                                                </div>
                                            @endcan
                                        </span>
                                    </div>
                                    <div class="row g-3">
                                        <table
                                            class="table table-bordered table-hover datatables align-middle justify-content-center"
                                            id="table-operateurModules">
                                            <thead>
                                                <tr>
                                                    <th scope="col" width='2%'>N°</th>
                                                    <th scope="col">DOMAINE</th>
                                                    <th scope="col">MODULE</th>
                                                    <th scope="col">NIVEAU DE QUALIFICATION</th>
                                                    <th scope="col">CATEGORIE PROFESSIONNELLE</th>
                                                    <th class="text-center">STATUT</th>
                                                    @can('devenir-operateur-agrement-show')
                                                        <th class="text-center" width='2%'>ACTIONS
                                                        </th>
                                                    @endcan
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $i = 1; ?>
                                                @foreach ($operateur?->operateurmodules as $operateurmodule)
                                                    <tr>
                                                        <td style="text-align: center;">{{ $i++ }}</td>
                                                        <td>{{ $operateurmodule?->domaine }}</td>
                                                        <td>{{ $operateurmodule?->module }}</td>
                                                        <td>{{ $operateurmodule?->niveau_qualification }}</td>
                                                        <td>{{ $operateurmodule?->categorie }}</td>
                                                        <td style="text-align: center;">
                                                            <span
                                                                class="{{ $operateurmodule?->statut }}">{{ $operateurmodule?->statut }}</span>
                                                        </td>
                                                        @can('devenir-operateur-agrement-show')
                                                            <td style="text-align: center;">
                                                                <div class="d-flex justify-content-center">
                                                                    @can('devenir-operateur-agrement-update')
                                                                        <button class="btn btn-warning text-white btn-sm me-1"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#EditOperateurmoduleModal{{ $operateurmodule->id }}"
                                                                            title="Modifier">
                                                                            <i class="bi bi-pencil-square"></i>
                                                                        </button>
                                                                    @endcan

                                                                    @can('devenir-operateur-agrement-delete')
                                                                        <form
                                                                            action="{{ route('operateurmodules.destroy', $operateurmodule) }}"
                                                                            method="POST">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="submit"
                                                                                class="btn btn-danger btn-sm show_confirm"
                                                                                title="Supprimer">
                                                                                <i class="bi bi-trash"></i>
                                                                            </button>
                                                                        </form>
                                                                    @endcan
                                                                </div>
                                                            </td>
                                                        @endcan
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    {{-- </form> --}}
                                </div>
                            </div>
                        </div>
                        <!-- Edit Operateur Module -->
                        @foreach ($operateur?->operateurmodules as $operateurmodule)
                            <div class="modal fade" id="EditOperateurmoduleModal{{ $operateurmodule->id }}"
                                tabindex="-1" role="dialog"
                                aria-labelledby="EditOperateurmoduleModalLabel{{ $operateurmodule->id }}"
                                aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form method="POST"
                                            action="{{ route('operateurmodules.update', $operateurmodule) }}"
                                            enctype="multipart/form-data" class="needs-validation" novalidate>
                                            @csrf
                                            @method('PATCH')

                                            <div class="card shadow-lg border-0">
                                                <div class="card-header bg-default text-center py-2 rounded-top">
                                                    <h4 class="mb-0">Modification</h4>
                                                </div>

                                                <div class="card-body row g-4 px-4">
                                                    <input type="hidden" name="id"
                                                        value="{{ $operateurmodule->id }}">
                                                    <input type="hidden" name="operateur"
                                                        value="{{ $operateurmodule->operateur->id }}">

                                                    {{-- Domaine --}}
                                                    <div class="col-12">
                                                        <label for="domaine" class="form-label">Domaine <span
                                                                class="text-danger">*</span></label>
                                                        <select name="domaine" id="select-field-civilite"
                                                            class="form-select form-select-sm @error('domaine') is-invalid @enderror"
                                                            required>
                                                            <option value="">-- Sélectionnez un domaine --</option>
                                                            @foreach ($domaines as $domaine)
                                                                <option value="{{ $domaine->name }}"
                                                                    {{ old('domaine', $operateurmodule->domaine) == $domaine->name ? 'selected' : '' }}>
                                                                    {{ $domaine->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('domaine')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

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

                                                    {{-- Niveau de qualification --}}
                                                    <div class="col-12">
                                                        <label for="niveau_qualification" class="form-label">Niveau de
                                                            qualification <span class="text-danger">*</span></label>
                                                        <select name="niveau_qualification"
                                                            class="form-select form-select-sm @error('niveau_qualification') is-invalid @enderror"
                                                            id="select-field-niveau_qualification-update" required>
                                                            <option disabled selected>Choisir un niveau</option>
                                                            @foreach (['Pré-qualification', 'Renforcement de capacités', 'Qualification'] as $niveau)
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

                                                    {{-- Catégorie --}}
                                                    <div class="col-12">
                                                        <label for="categorie" class="form-label">Catégorie
                                                            professionnelle</label>
                                                        <input type="text" name="categorie"
                                                            value="{{ old('categorie', $operateurmodule->categorie) }}"
                                                            class="form-control form-control-sm @error('categorie') is-invalid @enderror"
                                                            placeholder="Catégorie">
                                                        @error('categorie')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                </div>

                                                <div
                                                    class="card-footer d-flex justify-content-end gap-2 p-3 bg-light border-top">
                                                    <button type="button" class="btn btn-secondary btn-sm"
                                                        data-bs-dismiss="modal">
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
                    </div>
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
