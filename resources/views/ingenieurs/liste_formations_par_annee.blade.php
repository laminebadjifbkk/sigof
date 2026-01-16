@extends('layout.user-layout')
@section('title', 'ONFP | DEMANDEURS INDIVIDUELS')
@section('space-work')
    <section class="section">
        <div class="row">
            <div class="col-12">
                @if ($message = Session::get('status'))
                    <div class="alert alert-success bg-success text-light border-0 alert-dismissible fade show"
                        role="alert">
                        <strong>{{ $message }}</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if ($message = Session::get('danger'))
                    <div class="alert alert-danger bg-danger text-light border-0 alert-dismissible fade show" role="alert">
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
                        <div class="pt-1">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">

                                {{-- Titre à gauche --}}
                                <div class="d-flex align-items-center gap-2">
                                    <h6 class="mb-0 text-muted fw-semibold text-uppercase">
                                        Liste des demandes individuelles
                                    </h6>
                                </div>

                            </div>
                            <h5>Formations individuelles – Année {{ $annee }}</h5>
                            @if ($individuelles->isNotEmpty())
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>CIN</th>
                                            <th>Prénom</th>
                                            <th>Nom</th>
                                            <th>Date nais.</th>
                                            <th>Lieu nais.</th>
                                            <th>Module</th>
                                            <th>Dépôt</th>
                                            <th>Statut</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($individuelles as $ind)
                                            <tr>
                                                <td>{{ $ind->user->cin }}</td>
                                                <td>{{ $ind->user->firstname }}</td>
                                                <td>{{ $ind->user->name }}</td>
                                                <td>{{ $ind->user->date_naissance?->format('d/m/Y') }}</td>
                                                <td>{{ $ind->user->lieu_naissance }}</td>
                                                <td>{{ $ind->module->name ?? '-' }}</td>
                                                <td>{{ $ind->date_depot?->format('d/m/Y') ?? 'Aucun' }}</td>
                                                <td>{{ $ind->statut }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="alert alert-info">Aucune demande individuelle pour l’année {{ $annee }}
                                </div>
                            @endif

                            <h5>Formations collectives – Année {{ $annee }}</h5>
                            @if ($collectives->isNotEmpty())
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Structure</th>
                                            <th>Téléphone</th>
                                            <th>Région</th>
                                            <th>Dépôt</th>
                                            <th>Modules</th>
                                            <th>Effectif</th>
                                            <th>Statut</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($collectives as $col)
                                            <tr>
                                                <td>{{ $col->name }} {{ $col->sigle ? '(' . $col->sigle . ')' : '' }}
                                                </td>
                                                <td>{{ $col->telephone }}</td>
                                                <td>{{ optional($col->departement->region)->nom }}</td>
                                                <td>{{ $col->date_depot?->format('d/m/Y') ?? '-' }}</td>
                                                <td>{{ $col->collectivemodules->count() }}</td>
                                                <td>{{ $col->listecollectives->count() }}</td>
                                                <td>{{ ucfirst($col->statut_demande) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="alert alert-info">Aucune demande collective pour l’année {{ $annee }}
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
        new DataTable('#table-individuelles', {
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
