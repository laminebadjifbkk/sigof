@extends('layout.user-layout')
@section('title', 'ONFP | MODULES COLLECTIVES')
@section('space-work')
    @can('module-view')
        <section class="section">
            <div class="row justify-content-center">
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="pagetitle">
                        {{-- <h1>Data Tables</h1> --}}
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('/home') }}">Accueil</a></li>
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
                            {{-- @can('role-create') --}}
                            {{-- <div class="pt-1">
                            <a href="{{ route('modules.create') }}" class="btn btn-primary float-end btn-rounded"><i
                                    class="fas fa-plus"></i>
                                <i class="bi bi-person-plus" title="Ajouter"></i> </a>
                        </div> --}}
                            {{-- @endcan --}}
                            {{-- <button type="button" class="btn btn-primary float-end btn-rounded" data-bs-toggle="modal"
                            data-bs-target="#AddIndividuelModal">
                            <i class="bi bi-plus" title="Ajouter"></i>
                        </button> --}}
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title">MODULES DEMANDES COLLECTIVES</h5>
                            </div>
                            <table class="table datatables align-middle justify-content-center" id="table-modules">
                                <thead>
                                    <tr>
                                        <th>Modules</th>
                                        <th>Demandeurs</th>
                                        <th class="text-center" scope="col">#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; ?>
                                    @foreach ($collectivemodules as $collectivemodule)
                                        <tr>
                                            <td>{{ $collectivemodule?->module }}</td>
                                            <td>{{ $collectivemodule?->collective?->name }}</td>
                                            <td style="text-align: center;">
                                                @can('module-show')
                                                    <span class="d-flex mt-2 align-items-baseline"><a href="#"
                                                            class="btn btn-success btn-sm mx-1" title="Voir détails">
                                                            <i class="bi bi-eye"></i></a>
                                                        <div class="filter">
                                                            <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                                                    class="bi bi-three-dots"></i></a>
                                                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                                                @can('module-delete')
                                                                    <li>
                                                                        <form
                                                                            action="{{ route('collectivemodules.destroy', $collectivemodule) }}"
                                                                            method="post">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="submit" class="dropdown-item show_confirm"><i
                                                                                    class="bi bi-trash"></i>Supprimer</button>
                                                                        </form>
                                                                    </li>
                                                                @endcan
                                                            </ul>
                                                        </div>
                                                    </span>
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endcan
@endsection

@push('scripts')
    <script>
        new DataTable('#table-modules', {
            layout: {
                topStart: {
                    buttons: ['csv', 'excel', 'print'],
                }
            },
            /* "order": [
                [5, 'desc']
            ], */
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
