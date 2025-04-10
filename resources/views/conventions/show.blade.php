@extends('layout.user-layout')
@section('title', 'ONFP | ' . $convention->name)
@section('space-work')
    @can('referentiel-view')
        <section class="section register">
            <div class="row justify-content-center">
                <div class="col-12 col-md-12 col-lg-12 col-sm-12 col-xs-12 col-xxl-12">
                    <div class="pagetitle">
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('/home') }}">Accueil</a></li>
                                <li class="breadcrumb-item">Tables</li>
                                <li class="breadcrumb-item active">Données</li>
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
                            <p class="#">{{ $convention->name }}</p>
                            <table class="table datatables align-middle justify-content-center" id="table-files">
                                <thead>
                                    <tr>
                                        <th width="18%">Intitulé</th>
                                        <th>Titre</th>
                                        <th>Catégorie</th>
                                        {{-- <th>Convention</th> --}}
                                        <th width="35%">Référence</th>
                                        <th width="2%" class="text-center">#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; ?>
                                    @foreach ($convention->referentiels as $referentiel)
                                        <tr>
                                            <td>{{ $referentiel?->intitule }}</td>
                                            <td>{{ $referentiel?->titre }}</td>
                                            <td>{{ $referentiel?->categorie }}</td>
                                            {{-- <td>{{ $referentiel?->convention?->name }}</td> --}}
                                            <td>{{ $referentiel?->reference }}</td>
                                            <td style="text-align: center;">
                                                @can('referentiel-show')
                                                    <span class="d-flex align-items-baseline">
                                                        @can('referentiel-update')
                                                            <a href="{{ route('referentiels.edit', $referentiel->id) }}"
                                                                class="btn btn-warning btn-sm mx-1 text-white" title="Modifier">
                                                                <i class="bi bi-pencil"></i></a>
                                                        @endcan
                                                        <div class="filter">
                                                            <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                                                    class="bi bi-three-dots"></i></a>
                                                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                                                @can('referentiel-delete')
                                                                    <li>
                                                                        <form action="{{ url('referentiels', $referentiel->id) }}"
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
        new DataTable('#table-files', {
            layout: {
                topStart: {
                    buttons: ['excel'],
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
