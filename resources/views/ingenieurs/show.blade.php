@extends('layout.user-layout')
@section('title', 'ONFP | Formations ' . $ingenieur->name)
@section('space-work')

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

    @can('ingenieur-show')
        <section class="section">
            <div class="row">
                <div class="col-lg-12">
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
                                role="alert">{{ $error }}</div>
                        @endforeach
                    @endif
                    <div class="card">
                        <div class="card-body">
                            <span class="d-flex align-items-baseline"><a href="{{ route('ingenieurs.index') }}"
                                    class="btn btn-success btn-sm" title="retour"><i
                                        class="bi bi-arrow-counterclockwise"></i></a>&nbsp;
                                <p> | retour</p>
                            </span>
                            <h5 class="card-title">Liste des formations de {{ $ingenieur->name }}</h5>
                            @if ($ingenieur->formations->isNotEmpty())
                            <table class="table datatables" id="table-formations">
                                <thead>
                                    <tr>
                                        <th class="text-center">Code</th>
                                        <th>Type</th>
                                        <th>Intitulé formation</th>
                                        <th>Localité</th>
                                        <th>Modules</th>
                                        <th class="text-center">Statut</th>
                                        <th width='3%'>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; ?>
                                    @foreach ($ingenieur->formations as $formation)
                                        <tr>
                                            <td class="text-center">{{ $formation?->code }}</td>
                                            <td><a href="#">{{ $formation->types_formation?->name }}</a></td>
                                            <td>{{ $formation?->name }}</td>
                                            <td>{{ $formation->departement?->region?->nom }}</td>
                                            <td>
                                                @isset($formation?->module?->name)
                                                    {{ $formation?->module?->name }}
                                                @endisset
                                                @isset($formation?->collectivemodule?->module)
                                                    {{ $formation?->collectivemodule?->module }}
                                                @endisset
                                            </td>
                                            <td class="text-center"><a href="#"><span
                                                        class="{{ $formation?->statut }}">{{ $formation?->statut }}</span></a>
                                            </td>
                                            <td>
                                                <span class="d-flex align-items-baseline"><a
                                                        href="{{ route('formations.show', $formation->id) }}"
                                                        class="btn btn-primary btn-sm" title="voir détails"><i
                                                            class="bi bi-eye"></i></a>
                                                    <div class="filter">
                                                        <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                                                class="bi bi-three-dots"></i></a>
                                                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                                            <li>
                                                                <button type="button" class="dropdown-item btn btn-sm mx-1"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#EditingenieurModal{{ $ingenieur->id }}">
                                                                    <i class="bi bi-pencil" title="Modifier"></i> Modifier
                                                                </button>
                                                            </li>
                                                            <li>
                                                                <form
                                                                    action="{{ route('ingenieurs.destroy', $ingenieur->id) }}"
                                                                    method="post">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="dropdown-item show_confirm"
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
                            @else
                                <div class="alert alert-info bg-warning text-light border-0 alert-dismissible fade show"
                                    role="alert">
                                    <strong>Aucune formation attribuée à cet ingénieur.</strong>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <span class="d-flex align-items-baseline"><a href="{{ route('ingenieurs.index') }}"
                                    class="btn btn-success btn-sm" title="retour"><i
                                        class="bi bi-arrow-counterclockwise"></i></a>&nbsp;
                                <p> | retour</p>
                            </span>
                            <h5 class="card-title">Liste des demandes collectives imputées à {{ $ingenieur->name }}</h5>
                            @if ($ingenieur->collectives->isNotEmpty())
                                <table class="table table-striped table-hover datatables" id="table-collectives">
                                    <thead>
                                        <tr>
                                            <th>N° DEM.</th>
                                            <th>Nom structure</th>
                                            <th>E-mail</th>
                                            <th>Téléphone</th>
                                            <th>Région</th>
                                            <th class="text-center">Modules</th>
                                            <th class="text-center">Effectif</th>
                                            <th class="text-center">Statut</th>
                                            <th class="text-center">#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($ingenieur->collectives as $collective)
                                            <tr>
                                                <td>{{ $collective?->numero }}</td>
                                                <td>
                                                    {{ $collective?->name }}
                                                    @if (!empty($collective?->sigle))
                                                        ({{ $collective?->sigle }})
                                                    @endif
                                                </td>
                                                <td>
                                                    <a
                                                        href="mailto:{{ $collective->user->email }}">{{ $collective->user->email }}</a>
                                                </td>
                                                <td>
                                                    <a
                                                        href="tel:+221{{ $collective->telephone }}">{{ $collective->telephone }}</a>
                                                </td>
                                                <td>{{ $collective->departement?->region?->nom }}</td>
                                                <td class="text-center">{{ count($collective->collectivemodules) }}</td>
                                                <td class="text-center">{{ count($collective->listecollectives) }}</td>
                                                <td class="text-center">
                                                    <span
                                                        class="{{ $collective?->statut_demande }}">{{ $collective?->statut_demande }}</span>
                                                </td>
                                                <td class="text-center">
                                                    @can('collective-show')
                                                        <div class="d-flex align-items-center">
                                                            <a href="{{ route('collectives.show', $collective) }}"
                                                                class="btn btn-primary btn-sm me-1" title="Voir détails">
                                                                <i class="bi bi-eye"></i>
                                                            </a>
                                                            <div class="dropdown">
                                                                <a href="#" class="btn btn-light btn-sm dropdown-toggle"
                                                                    data-bs-toggle="dropdown">
                                                                    <i class="bi bi-three-dots"></i>
                                                                </a>
                                                                <ul class="dropdown-menu dropdown-menu-end">
                                                                    @can('collective-update')
                                                                        <li>
                                                                            <a href="{{ route('collectives.edit', $collective) }}"
                                                                                class="dropdown-item">
                                                                                <i class="bi bi-pencil"></i> Modifier
                                                                            </a>
                                                                        </li>
                                                                    @endcan
                                                                    @can('collective-delete')
                                                                        <li>
                                                                            <form
                                                                                action="{{ route('collectives.destroy', $collective) }}"
                                                                                method="POST">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                                <button type="submit"
                                                                                    class="dropdown-item show_confirm">
                                                                                    <i class="bi bi-trash"></i> Supprimer
                                                                                </button>
                                                                            </form>
                                                                        </li>
                                                                    @endcan
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    @endcan
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="alert alert-info bg-info text-light border-0 alert-dismissible fade show"
                                    role="alert">
                                    <strong>Aucune demande collective n'est encore imputée à cet ingénieur.</strong>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>

            <!-- Edit ingenieur -->
            @foreach ($ingenieurs as $ingenieur)
                <div class="modal fade" id="EditingenieurModal{{ $ingenieur->id }}" tabindex="-1" role="dialog"
                    aria-labelledby="EditingenieurModalLabel{{ $ingenieur->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="post" action="{{ route('ingenieurs.update', $ingenieur->id) }}"
                                enctype="multipart/form-data" class="row g-3">
                                @csrf
                                @method('patch')
                                <div class="modal-header" id="EditingenieurModalLabel{{ $ingenieur->id }}">
                                    <h5 class="modal-title"><i class="bi bi-pencil" title="Ajouter"></i> Modifier région</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <input type="hidden" name="id" value="{{ $ingenieur->id }}">
                                <div class="modal-body">
                                    <div class="form-floating mb-3">
                                        <input type="text" name="matricule"
                                            value="{{ $ingenieur->matricule ?? old('matricule') }}"
                                            class="form-control form-control-sm @error('matricule') is-invalid @enderror"
                                            id="matricule" placeholder="Matricule" autofocus>
                                        @error('matricule')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                        <label for="floatingInput">Matricule</label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="text" name="name" value="{{ $ingenieur->name ?? old('name') }}"
                                            class="form-control form-control-sm @error('name') is-invalid @enderror"
                                            id="name" placeholder="Ingénieur" autofocus>
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                        <label for="floatingInput">Ingénieur<span class="text-danger mx-1">*</span></label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="text" name="initiale"
                                            value="{{ $ingenieur->initiale ?? old('initiale') }}"
                                            class="form-control form-control-sm @error('initiale') is-invalid @enderror"
                                            id="initiale" placeholder="initiale">
                                        @error('initiale')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                        <label for="floatingInput">Initiale<span class="text-danger mx-1">*</span></label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="text" name="specialite"
                                            value="{{ $ingenieur->specialite ?? old('specialite') }}"
                                            class="form-control form-control-sm @error('specialite') is-invalid @enderror"
                                            id="specialite" placeholder="specialite">
                                        @error('specialite')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                        <label for="floatingInput">Spécialité</label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="text" name="fonction"
                                            value="{{ $ingenieur->fonction ?? old('fonction') }}"
                                            class="form-control form-control-sm @error('fonction') is-invalid @enderror"
                                            id="fonction" placeholder="fonction">
                                        @error('specialite')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                        <label for="floatingInput">Fonction<span class="text-danger mx-1">*</span></label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="text" name="email" value="{{ $ingenieur->email ?? old('email') }}"
                                            class="form-control form-control-sm @error('email') is-invalid @enderror"
                                            id="email" placeholder="email">
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                        <label for="floatingInput">Email<span class="text-danger mx-1">*</span></label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="text" name="telephone"
                                            value="{{ $ingenieur->telephone ?? old('telephone') }}"
                                            class="form-control form-control-sm @error('telephone') is-invalid @enderror"
                                            id="telephone" placeholder="Telephone">
                                        @error('telephone')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                        <label for="floatingInput">Telephone<span class="text-danger mx-1">*</span></label>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                    <button type="submit" class="btn btn-primary"><i class="bi bi-printer"></i>
                                        Modifier</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
            <!-- End Edit ingenieur-->
        </section>
    @endcan
@endsection
@push('scripts')
    <script>
        new DataTable('#table-formations', {
            layout: {
                topStart: {
                    buttons: ['csv', 'excel', 'print'],
                }
            },
            "order": [
                [0, 'desc']
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
        new DataTable('#table-collectives', {
            layout: {
                topStart: {
                    buttons: ['csv', 'excel', 'print'],
                }
            },
            "order": [
                [0, 'desc']
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
