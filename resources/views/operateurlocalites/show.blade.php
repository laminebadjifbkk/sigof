@extends('layout.user-layout')
@section('title', remove_accents_uppercase($operateur?->user?->username) . ' | ' .
    remove_accents_uppercase('localités'))
@section('space-work')

    <section class="section register">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="pagetitle">

                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Accueil</a></li>
                            <li class="breadcrumb-item">Tables</li>
                            <li class="breadcrumb-item active">localités</li>
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
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0 text-uppercase fw-bold text-primary">
                                <i class="bi bi-person-lines-fill me-2"></i> LOCALITES
                            </h5>
                            @can('devenir-operateur-agrement-ouvert')
                                @can('agrement-visible-par-op')
                                    <button type="button" class="btn btn-primary btn-sm d-flex align-items-center shadow-sm"
                                        data-bs-toggle="modal" data-bs-target="#AddlocaliteModal">
                                        <i class="bi bi-plus-circle me-2"></i> Ajouter
                                    </button>
                                @endcan
                            @endcan
                        </div>

                        <table
                            class="table table-bordered table-hover datatables align-middle justify-content-center table-borderless">
                            <thead class="table-primary text-center">
                                <tr>
                                    <th class="text-center" width="2%">N°</th>
                                    <th>LOCALITE</th>
                                    <th>REGION</th>
                                    <th class="text-center" width="2%"><i class="bi bi-gear"></i></th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                <?php $i = 1; ?>
                                @foreach ($operateur->operateurlocalites as $operateurlocalite)
                                    <tr>
                                        <td style="text-align: center;">{{ $i++ }}</td>
                                        <td>{{ $operateurlocalite->name }}</td>
                                        <td>{{ $operateurlocalite->region }}</td>
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
                                                                    data-bs-target="#EditoperateurlocaliteModal{{ $operateurlocalite->id }}">
                                                                    <i class="bi bi-pencil" title="Modifier"></i> Modifier
                                                                </button>
                                                            </li>
                                                            <li>
                                                                <form
                                                                    action="{{ route('operateurlocalites.destroy', $operateurlocalite->id) }}"
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
                    </div>
                </div>

            </div>
        </div>

        <div class="modal fade" id="AddlocaliteModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content shadow-lg border-0 rounded-4 overflow-hidden">

                    <form method="POST" action="{{ route('operateurlocalites.store') }}" class="p-3">
                        @csrf

                        <!-- HEADER -->
                        <div class="bg-info text-white text-center py-3">
                            <h5 class="mb-0 text-uppercase fw-bold">
                                <i class="bi bi-geo-alt-fill me-2"></i> Zones d’intervention
                            </h5>
                        </div>

                        <input type="hidden" name="operateur" value="{{ $operateur->id }}">

                        <div class="modal-body">

                            <!-- INFO -->
                            <div class="alert alert-info py-2 small">
                                Choisissez une région spécifique ou ajoutez toutes les régions en un seul clic.
                            </div>

                            <input type="hidden" name="all_regions" id="all_regions_input" value="">

                            <!-- OPTION TOUTES LES REGIONS -->
                            <div class="border rounded-3 p-3 bg-light">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>National</strong><br>
                                        <small class="text-muted">
                                            Ajouter toutes les régions comme zones d’intervention
                                        </small>
                                    </div>

                                    {{-- <button type="submit" name="all_regions" value="1" class="btn btn-success btn-sm"
                                        onclick="return confirmAllRegions()">
                                        <i class="bi bi-globe"></i> Ajouter toutes les régions
                                    </button> --}}
                                    <button type="button" class="btn btn-success btn-sm"
                                        onclick="confirmAllRegions(event)">
                                        <i class="bi bi-globe"></i> Ajouter toutes les régions
                                    </button>
                                </div>
                            </div>

                            <!-- CHAMP LOCALITE -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    Zone d’intervention
                                </label>
                                <input type="text" name="name" value="{{ old('name') }}"
                                    class="form-control form-control-sm @error('name') is-invalid @enderror"
                                    placeholder="Ex: Dakar, Thiès, Saint-Louis...">

                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- SELECT REGION -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    Région
                                </label>
                                <select name="region"
                                    class="form-select form-select-sm @error('region') is-invalid @enderror">
                                    <option value="">-- Choisir une région --</option>
                                    @foreach ($regions as $region)
                                        <option value="{{ $region->nom }}">{{ $region->nom }}</option>
                                    @endforeach
                                </select>

                                @error('region')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        <!-- FOOTER -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                                Fermer
                            </button>

                            <button type="submit" class="btn btn-info btn-sm text-white">
                                <i class="bi bi-save2"></i> Ajouter
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>

        <!-- End Add Formateur-->
        <!-- Edit Formateur -->
        @foreach ($operateurlocalites as $operateurlocalite)
            <div class="modal fade" id="EditoperateurlocaliteModal{{ $operateurlocalite->id }}" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form method="post" action="{{ route('operateurlocalites.update', $operateurlocalite->id) }}"
                            enctype="multipart/form-data" class="row g-3">
                            @csrf
                            @method('patch')
                            <div class="card-header text-center bg-gradient-default">
                                <h1 class="h4 text-black mb-0">MODIFICATION</h1>
                            </div>
                            <input type="hidden" name="operateur" value="{{ $operateur->id }}">
                            <div class="modal-body">
                                <div class="col-12 mb-2">
                                    <label for="name" class="form-label">Zone d'intervention<span
                                            class="text-danger mx-1">*</span></label>
                                    <input type="text" name="name"
                                        value="{{ $operateurlocalite->name ?? old('name') }}"
                                        class="form-control form-control-sm @error('name') is-invalid @enderror"
                                        placeholder="Localités (régions, départements, communes)">
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12 mb-2">
                                    <label for="region" class="form-label">Region<span
                                            class="text-danger mx-1">*</span></label>
                                    <select name="region" class="form-select  @error('region') is-invalid @enderror"
                                        aria-label="Select" id="select-field-operateur-localite"
                                        data-placeholder="Choisir la région">
                                        <option value="{{ $operateurlocalite->region }}">{{ $operateurlocalite->region }}
                                        </option>
                                        @foreach ($regions as $region)
                                            <option value="{{ $region->nom }}">
                                                {{ $region->nom }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('region')
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

    <script>
        function confirmAllRegions(e) {
            e.preventDefault();

            Swal.fire({
                title: 'Ajouter toutes les régions ?',
                text: "Toutes les régions seront ajoutées comme zones d’intervention.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Oui, ajouter',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {

                    // activer le champ hidden
                    document.getElementById('all_regions_input').value = 1;

                    // soumettre le formulaire
                    e.target.closest('form').submit();
                }
            });
        }
    </script>
@endpush
