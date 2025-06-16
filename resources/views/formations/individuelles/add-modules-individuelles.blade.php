@extends('layout.user-layout')
@section('title', 'ajouter module à cette formation')
@section('space-work')
    <section class="section">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                @if ($message = Session::get('status'))
                    <div class="alert alert-success bg-success text-light border-0 alert-dismissible fade show"
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
                        <div class="row">
                            <div class="col-sm-12 pt-0">
                                <span class="d-flex mt-0 align-items-baseline"><a
                                        href="{{ route('formations.show', $formation) }}" class="btn btn-success btn-sm"
                                        title="retour"><i class="bi bi-arrow-counterclockwise"></i></a>&nbsp;
                                    <p> | Détails formation</p>
                                </span>
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary float-end btn-rounded" data-bs-toggle="modal"
                            data-bs-target="#AddIndividuelModal">
                            <i class="bi bi-plus" title="Ajouter"></i>
                        </button>
                        {{-- <h5><u><b>MODULE</b>:</u> {{ $formation->module?->name ?? 'Aucun module' }}</h5>
                        <h5><u><b>REGION</b>:</u> {{ $localite->nom ?? 'Aucune région' }}</h5> --}}
                        <div class="p-1 mb-4 border rounded bg-light shadow-sm">
                            <div class="row text-center fw-semibold">
                                <div class="col-md-4 mb-2">
                                    <span class="text-secondary">📍 Région</span><br>
                                    <span class="fs-5 text-dark">{{ $region->nom ?? 'Aucune' }}</span>
                                </div>
                                <div class="col-md-8 mb-2">
                                    <span class="text-secondary">📘 Module</span><br>
                                    <span class="fs-5 text-dark">{{ $module->name ?? 'Aucun' }}</span>
                                </div>
                            </div>
                        </div>
                        <form method="post" action="{{ url('formationmodules', ['$idformation' => $formation->id]) }}"
                            enctype="multipart/form-data" class="row g-3">
                            @csrf
                            @method('PUT')
                            <div class="row mb-3 border rounded bg-light shadow-sm">
                                {{-- <div class="form-check col-md-2 pt-5">
                                    <label for="#">Choisir tout</label>
                                    <input type="checkbox" class="form-check-input" id="checkAll">
                                </div> --}}
                                <div class="form-check col-md-12 pt-5">
                                    <table class="m-2 table datatables align-middle" id="table-modules">
                                        <thead>
                                            <tr>
                                                <th>Modules</th>
                                                <th>Domaines</th>
                                                {{-- <th class="text-center" scope="col">Effectif</th> --}}
                                                <th width="3%"><i class="bi bi-gear"></i></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i = 1; ?>
                                            @foreach ($modules as $module)
                                                <tr>
                                                    <td>
                                                        <input type="radio" name="module" value="{{ $module?->id }}"
                                                            {{ in_array($module->id, $moduleFormation) ? 'checked' : '' }}
                                                            class="form-check-input @error('module') is-invalid @enderror">
                                                        @error('module')
                                                            <span class="invalid-feedback" role="alert">
                                                                <div>{{ $message }}</div>
                                                            </span>
                                                        @enderror
                                                        {{ $module->name }}
                                                    </td>
                                                    <td>{{ $module?->domaine?->name }}</td>
                                                    {{-- <td style="text-align: center;">
                                                        @if ($module->individuelles->isNotEmpty())
                                                            <a href="{{ route('modules.show', $module) }}">
                                                                <span
                                                                    class="badge bg-info">{{ $module->individuelles->count() }}</span>
                                                            </a>
                                                        @endif
                                                    </td> --}}
                                                    <td style="text-align: center;">
                                                        <span class="d-flex mt-2 align-items-baseline"><a
                                                                href="{{ route('modules.show', $module) }}"
                                                                class="btn btn-success btn-sm mx-1" title="Voir détails">
                                                                <i class="bi bi-eye"></i></a>
                                                            <div class="filter">
                                                                <a class="icon" href="#"
                                                                    data-bs-toggle="dropdown"><i
                                                                        class="bi bi-three-dots"></i></a>
                                                                <ul
                                                                    class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                                                    <li>
                                                                        <button type="button"
                                                                            class="dropdown-item btn btn-sm mx-1"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#EditRegionModal{{ $module->id }}">
                                                                            <i class="bi bi-pencil" title="Modifier"></i>
                                                                            Modifier
                                                                        </button>
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
                                <div class="text-center">
                                    <button type="submit" class="btn btn-outline-primary btn-sm"><i
                                            class="bi bi-check2-circle"></i>&nbsp;Sélectionner</button>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        {{-- Add module --}}
        {{-- <div class="modal fade" id="AddIndividuelModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="post" action="{{ url('addModule') }}" enctype="multipart/form-data" class="row g-3">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title"><i class="bi bi-plus" title="Ajouter"></i> Ajouter un nouveau module
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="form-floating mb-3">
                                <input type="text" name="name" id="module_name" class="form-control form-control-sm"
                                    placeholder="Enter module" />
                                <div id="countryList"></div>
                                {{ csrf_field() }}

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <div>{{ $message }}</div>
                                    </span>
                                @enderror
                                <label for="floatingInput">Module</label>
                            </div>
                            <div class="form-floating mb-3">
                                <select name="domaine" class="form-select  @error('domaine') is-invalid @enderror"
                                    aria-label="Select" id="select-field-domaine-indiv"
                                    data-placeholder="Choisir domaine">
                                    <option value="{{ old('domaine') }}">
                                        {{ old('domaine') }}
                                    </option>
                                    @foreach ($domaines as $domaine)
                                        <option value="{{ $domaine->id }}">
                                            {{ $domaine->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('domaine')
                                    <span class="invalid-feedback" role="alert">
                                        <div>{{ $message }}</div>
                                    </span>
                                @enderror
                                <label for="floatingInput">Domaine</label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-sm"
                                data-bs-dismiss="modal">Fermer</button>
                            <button type="submit" class="btn btn-primary btn-sm">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div> --}}

        <!-- Edit Module -->
        {{-- @foreach ($modules as $module)
            <div class="modal fade" id="EditRegionModal{{ $module->id }}" tabindex="-1" role="dialog"
                aria-labelledby="EditRegionModalLabel{{ $module->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form method="post" action="{{ route('modules.update', $module) }}"
                            enctype="multipart/form-data" class="row g-3">
                            @csrf
                            @method('patch')
                            <div class="modal-header" id="EditRegionModalLabel{{ $module->id }}">
                                <h5 class="modal-title"><i class="bi bi-pencil" title="Ajouter"></i> Modifier module</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="id" value="{{ $module->id }}">
                                <div class="form-floating mb-3">
                                    <input type="text" name="name" value="{{ $module->name ?? old('name') }}"
                                        class="form-control form-control-sm @error('name') is-invalid @enderror"
                                        id="name" placeholder="Module" autofocus>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                    <label for="floatingInput">Module</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <select name="domaine" class="form-select  @error('domaine') is-invalid @enderror"
                                        aria-label="Select" id="select-field-domaine-module"
                                        data-placeholder="Choisir domaine">
                                        <option value="{{ $module?->domaine?->id }}">
                                            {{ $module?->domaine?->name ?? old('domaine') }}
                                        </option>
                                        @foreach ($domaines as $domaine)
                                            <option value="{{ $domaine->id }}">
                                                {{ $domaine->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('domaine')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                    <label for="floatingInput">Domaine</label>
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
    </section>
@endsection
@push('scripts')
    <script>
        new DataTable('#table-modules', {
            lengthMenu: [
                [5, 10, 25, 50, 100, -1],
                [5, 10, 25, 50, 100, "Tout"]
            ],
            "order": [
                [2, 'desc']
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
