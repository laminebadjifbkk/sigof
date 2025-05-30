@extends('layout.user-layout')
@section('title', 'ONFP | FONCTIONS')
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
    <section class="section fst-italic">
        <div class="row justify-content-center">
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
                            role="alert">
                            <strong>{{ $error }}</strong>
                        </div>
                    @endforeach
                @endif
                <div class="card">
                    <div class="card-body">
                        @can('fonction-create')
                            <div class="pt-0">
                                <a href="{{ route('fonctions.create') }}"
                                    class="btn btn-primary btn-sm float-end btn-rounded">Ajouter</a>
                            </div>
                        @endcan
                        <h5 class="card-title">Liste des fonctions</h5>
                        {{-- <p>Le tableau de toutes les fonctions du système.</p> --}}
                        <!-- Table with stripped rows -->
                        <table class="table datatables align-middle" id="table-fonctions">
                            <thead>
                                <tr>
                                    <th width="5%" class="text-center">N°</th>
                                    <th>Fonctions</th>
                                    <th class="text-center">Sigle</th>
                                    <th class="text-center" width="5%">Employés</th>
                                    <th width="5%">#</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; ?>
                                @foreach ($fonctions as $fonction)
                                    <tr>
                                        <td class="text-center">{{ $i++ }}</td>
                                        <td>{{ $fonction->name }}</td>
                                        <td class="text-center">{{ $fonction->sigle }}</td>
                                        <td class="text-center">{{ count($fonction->employees) }}</td>
                                        <td>
                                            @can('fonction-show')
                                                <span class="d-flex mt-2 align-items-baseline">
                                                    @can('fonction-update')
                                                        <a href="{{ url('fonctions/' . $fonction->id . '/edit') }}"
                                                            class="btn btn-success btn-sm" title="Modifier"><i
                                                                class="bi bi-pencil-square"></i>
                                                        </a>
                                                    @endcan
                                                    @can('fonction-show')
                                                        <a href="{{ route('fonctions.show', $fonction->id) }}"
                                                            class="btn btn-warning btn-sm" title="Voir"><i class="bi bi-eye"></i>
                                                        </a>
                                                    @endcan
                                                    @can('fonction-delete')
                                                        <form action="{{ url('fonctions', $fonction->id) }}" method="post">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm show_confirm"
                                                                title="Supprimer"><i class="bi bi-trash"></i></button>
                                                        </form>
                                                    @endcan
                                                </span>
                                            @endcan
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
    </section>

@endsection
@push('scripts')
    <script>
        new DataTable('#table-fonctions', {
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
