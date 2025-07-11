@extends('layout.user-layout')
@section('title', 'ONFP | CATÉGORIES')
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
                <div class="card">
                    <div class="card-body">
                        <div class="pt-2 d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0 fw-semibold text-primary">
                                <i class="bi bi-tags-fill me-1"></i> Catégories
                            </h5>
                            <a href="{{ route('operateurcategories.create') }}"
                                class="btn btn-sm btn-primary rounded-pill shadow-sm">
                                <i class="bi bi-plus-circle me-1"></i> Ajouter
                            </a>
                        </div>
                        {{-- <p>Le tableau de toutes les catégories du système.</p> --}}
                        <!-- Table with stripped rows -->
                        <table class="table datatables align-middle" id="table-categories">
                            <thead>
                                <tr>
                                    {{-- <th width="3%">N°</th> --}}
                                    <th>catégories</th>
                                    <th class="text-center" width="5%">Opérateurs</th>
                                    <th width="5%">#</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; ?>
                                @foreach ($categories as $categorie)
                                    <tr>
                                        {{-- <td>{{ $i++ }}</td> --}}
                                        <td>{{ $categorie->name }}</td>
                                        <td class="text-center">{{ count($categorie?->operateurs) }}</td>
                                        <td>
                                            <span class="d-flex mt-2 align-items-baseline">
                                                <a href="{{ route('operateurcategories.edit', $categorie->id) }}"
                                                    class="btn btn-success btn-sm" title="Modifier"><i
                                                        class="bi bi-pencil-square"></i></a>
                                                <a href="{{ route('operateurcategories.show', $categorie->id) }}"
                                                    class="btn btn-warning btn-sm" title="Voir"><i class="bi bi-eye"></i>
                                                </a>
                                                <form action="{{ route('operateurcategories.destroy', $categorie->id) }}"
                                                    method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm show_confirm"
                                                        title="Supprimer"><i class="bi bi-trash"></i></button>
                                                </form>
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
    </section>

@endsection
@push('scripts')
    <script>
        new DataTable('#table-categories', {
            layout: {
                topStart: {
                    buttons: ['csv', 'excel', 'print'],
                }
            },
            "order": [
                [1, 'desc']
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
