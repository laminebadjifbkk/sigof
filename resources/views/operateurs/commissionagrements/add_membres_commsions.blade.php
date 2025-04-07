@extends('layout.user-layout')
@section('title', 'ajouter dans la formation')
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
                <div class="card shadow-sm">
                    <div class="card-body">
                        <!-- Bouton retour -->
                        <div class="d-flex align-items-center mb-3">
                            <a href="{{ route('commissionagrements.index') }}" class="btn btn-success btn-sm"
                                title="Retour">
                                <i class="bi bi-arrow-counterclockwise"></i>
                            </a>
                            <span class="ms-2">| {{ $commissionagrement?->commission }}</span>
                        </div>

                        @isset($commissionagrement->operateurs)
                            <h5 class="pt-2"><u><b>Membres du jury</b> :</u>
                                <span class="badge bg-secondary">{{ count($commissionagrement?->commissionmembres) }}</span>
                            </h5>
                        @endisset

                        <!-- Formulaire d'ajout des membres -->
                        <form method="post" action="{{ route('addMembreJury', $commissionagrement->id) }}"
                            enctype="multipart/form-data" class="row g-3">
                            @csrf
                            @method('patch')

                            <!-- Tableau des membres -->
                            <div class="table-responsive">
                                <table class="table table-hover table-striped align-middle" id="table-operateurs">
                                    <thead class="table-primary text-center">
                                        <tr>
                                            <th><input type="checkbox" class="form-check-input" id="checkAll"></th>
                                            <th>Civilité</th>
                                            <th>Prénom</th>
                                            <th>Nom</th>
                                            <th>Fonction</th>
                                            <th>Structure</th>
                                            <th>Email</th>
                                            <th>Téléphone</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($membres as $membre)
                                            <tr>
                                                <td class="text-center">
                                                    <input type="checkbox" name="membres[]" value="{{ $membre?->id }}"
                                                        {{ in_array($membre?->id, $membreJury) ? 'checked' : '' }}
                                                        class="form-check-input membre-checkbox">
                                                    @error('membres')
                                                        <span class="invalid-feedback" role="alert">
                                                            <div>{{ $message }}</div>
                                                        </span>
                                                    @enderror
                                                </td>
                                                <td>{{ $membre->civilite }}</td>
                                                <td>{{ $membre->prenom }}</td>
                                                <td>{{ $membre->nom }}</td>
                                                <td>{{ $membre->fonction }}</td>
                                                <td>{{ $membre->structure }}</td>
                                                <td><a href="mailto:{{ $membre->email }}">{{ $membre->email }}</a></td>
                                                <td class="text-center">
                                                    <a
                                                        href="tel:+221{{ $membre->telephone }}">{{ $membre->telephone }}</a>
                                                </td>
                                                <td class="text-center">
                                                    <a href="{{ route('commissionmembres.show', $membre->id) }}"
                                                        class="btn btn-warning btn-sm" title="Voir détails">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Bouton de soumission -->
                            <div class="text-center mt-3">
                                <button type="submit" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-check2-circle"></i>&nbsp;Sélectionner
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script>
        new DataTable('#table-operateurs', {
            /* layout: {
                topStart: {
                    buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
                }
            }, */
            "lengthMenu": [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "Tout"]
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
