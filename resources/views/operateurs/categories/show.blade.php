@extends('layout.user-layout')
@section('title', 'ONFP', ' | Liste des opérateurs ayant la categorie ' . $categorie->name)
@section('space-work')
    <div class="pagetitle">
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
                            <div class="col-sm-12 pt-0">
                                <div class="d-flex align-items-center gap-2 mt-3">
                                    <a href="{{ route('operateurcategories.index') }}"
                                        class="btn btn-outline-success btn-sm rounded-pill shadow-sm"
                                        title="Retour à la liste">
                                        <i class="bi bi-arrow-counterclockwise me-1"></i> Retour
                                    </a>
                                    <span class="text-muted small">Catégorie sélectionnée</span>
                                </div>
                            </div>

                            <h5 class="card-title mt-4 fw-semibold text-info">
                                <i class="bi bi-list-ul me-1"></i> Liste des opérateurs ayant la catégorie : <span
                                    class="text-dark">{{ $categorie?->name }}</span>
                            </h5>
                            @if ($categorie?->operateurs->isNotEmpty())
                                <table class="table datatables align-middle" id="table-employes">
                                    <thead>
                                        <tr>
                                            <th width="15%">N° agrément</th>
                                            <th width="40%">Opérateurs</th>
                                            <th>Sigle</th>
                                            <th>Telephone</th>
                                            <th>Région</th>
                                            <th>Responsable</th>
                                            <th class="text-center">Modules</th>
                                            <th width="15%" class="text-center">Statut</th>
                                            <th width="2%"><i class="bi bi-gear"></i></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1; ?>
                                        @foreach ($operateurs as $operateur)
                                            <tr>
                                                <td>{{ $operateur?->numero_agrement }}</td>
                                                <td>{{ $operateur?->user?->operateur }}</td>
                                                <td>{{ $operateur?->user?->username }}</td>
                                                <td>
                                                    <a href="tel:+221{{ $operateur?->user?->fixe }}">
                                                        {{ $operateur?->user?->fixe }}<br>
                                                        {{ $operateur?->user?->telephone }}
                                                    </a>
                                                </td>
                                                <td>{{ $operateur?->region?->nom }}</td>
                                                <td>{{ $operateur?->user?->firstname . ' ' . $operateur?->user?->name }}
                                                </td>
                                                <td style="text-align: center;">
                                                    @foreach ($operateur->operateurmodules as $operateurmodule)
                                                        @if ($loop->last)
                                                            <a href="#"><span
                                                                    class="badge bg-info">{{ $loop->count }}</span></a>
                                                        @endif
                                                    @endforeach
                                                </td>
                                                <td style="text-align: center;"><span
                                                        class="{{ $operateur?->statut_agrement }}">
                                                        {{ $operateur?->statut_agrement }}</span></td>
                                                <td>
                                                    <span class="d-flex align-items-baseline"><a
                                                            href="{{ route('operateurs.show', $operateur) }}"
                                                            class="btn btn-primary btn-sm" title="voir détails"><i
                                                                class="bi bi-eye"></i></a>
                                                        <div class="filter">
                                                            <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                                                    class="bi bi-three-dots"></i></a>
                                                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                                                <li>
                                                                    <a class="dropdown-item btn btn-sm"
                                                                        href="{{ route('operateurs.edit', $operateur) }}"
                                                                        class="mx-1" title="Modifier"><i
                                                                            class="bi bi-pencil"></i>Modifier</a>
                                                                </li>
                                                                <li>
                                                                    <form
                                                                        action="{{ route('operateurs.destroy', $operateur) }}"
                                                                        method="post">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit"
                                                                            class="dropdown-item show_confirm"
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
                                <!-- End Table with stripped rows -->
                            @else
                                <div class="alert alert-info">Aucun opérateur dans cette catégorie pour l'instant !</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
    </section>

@endsection
@push('scripts')
    <script>
        new DataTable('#table-employes', {
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
