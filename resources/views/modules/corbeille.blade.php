@extends('layout.user-layout')
@section('title', 'ONFP | MODULES SUPPRIMES')
@section('space-work')

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
                            role="alert"><strong>{{ $error }}</strong></div>
                    @endforeach
                @endif
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12 pt-1">
                                <span class="d-flex mt-2 align-items-baseline"><a href="{{ route('modules.index') }}"
                                        class="btn btn-success btn-sm" title="retour"><i
                                            class="bi bi-arrow-counterclockwise"></i></a>&nbsp;
                                    <p> | Liste des modules</p>
                                </span>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            @can('user-create')
                                <h5 class="card-title">{{ $title }}</h5>
                                {{-- <span class="d-flex align-items-baseline">
                                    <a href="#" class="btn btn-primary btn-sm float-end" data-bs-toggle="modal"
                                        data-bs-target="#AddUserModal" title="Ajouter">Ajouter</a>
                                    <div class="filter">
                                        <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                                class="bi bi-three-dots"></i></a>
                                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                            <li>
                                                <button type="button" class="dropdown-item btn btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#generate_rapport"></i>Rechercher
                                                    plus</button>
                                            </li>
                                        </ul>
                                    </div>
                                </span> --}}
                            @endcan
                        </div>
                        @if ($modules->isNotEmpty())
                            <table class="table datatables align-middle" id="table-users">
                                <thead>
                                    <tr>
                                        <th>Modules</th>
                                        <th>Domaines</th>
                                        <th>Secteurs</th>
                                        <th>Niveau qualification</th>
                                        <th class="text-center" scope="col">Formations</th>
                                        <th class="text-center" scope="col">Demandes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; ?>
                                    @foreach ($modules as $module)
                                        <tr>
                                            <td>{{ $module->name }}</td>
                                            <td>{{ $module?->domaine?->name }}</td>
                                            <td>{{ $module?->domaine?->secteur?->name }}</td>
                                            <td>{{ $module?->niveau_qualification }}</td>
                                            <td style="text-align: center;">
                                                @foreach ($module->formations as $formation)
                                                    @if ($loop->last)
                                                        <a href="{{ route('formations.show' . $formation) }}"><span
                                                                class="badge bg-info">{{ $loop->count }}</span></a>
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td style="text-align: center;">
                                                @foreach ($module->individuelles as $individuelle)
                                                    @if ($loop->last)
                                                        <a href="{{ route('modules.show' . $module) }}"><span
                                                                class="badge bg-info">{{ $loop->count }}</span></a>
                                                    @endif
                                                @endforeach
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="alert alert-warning">
                                Aucun utilisateur trouvé.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script>
        new DataTable('#table-users', {
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
