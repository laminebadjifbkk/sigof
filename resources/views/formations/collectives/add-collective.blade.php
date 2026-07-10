@extends('layout.user-layout')
@section('title', 'Choisir structure')
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
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-3 fw-bold">
                            <i class="bi bi-buildings-fill text-primary me-2"></i>
                            Structures disponibles
                        </h5>

                        <div class="alert alert-info d-flex align-items-center mb-0 py-2">
                            <i class="bi bi-info-circle-fill me-2"></i>
                            <span>
                                Seules les structures dont la demande est au statut est différent de
                                <strong>« Nouvelle »</strong> sont disponibles pour la sélection.
                            </span>
                        </div>
                    </div>
                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table table-hover align-middle datatables" id="table-modules">

                                <thead class="table-light">
                                    <tr>
                                        <th>Structure</th>
                                        <th class="text-center">Modules</th>
                                        <th>Responsable</th>
                                        <th width="80" class="text-center">
                                        </th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($collectives as $collective)
                                        <tr>

                                            <td>
                                                <div class="fw-semibold">
                                                    {{ $collective->name_with_sigle }}
                                                </div>

                                                @if ($collective?->departement?->region?->nom)
                                                    <small class="text-muted">
                                                        {{ $collective->departement->region->nom }}
                                                    </small>
                                                @endif
                                            </td>

                                            <td class="text-center">
                                                <span class="badge rounded-pill bg-primary">
                                                    {{ $collective?->collectivemodules?->count() ?? 0 }}
                                                </span>
                                            </td>

                                            <td>
                                                {{ $collective?->prenom_responsable . ' ' . $collective?->nom_responsable ?? '-' }}
                                            </td>

                                            <td class="text-center">
                                                <a href="{{ route('collectivemoduleformations', [
                                                    'idformation' => $formation->id,
                                                    'idlocalite' => $formation->departement->region->id,
                                                    'idcollective' => $collective->id,
                                                ]) }}"
                                                    class="btn btn-outline-success btn-sm rounded-pill px-3"
                                                    title="Voir les modules">
                                                    {{-- <i class="bi bi-eye"></i> --}}
                                                    Ouvrir
                                                </a>
                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>

                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script>
        new DataTable('#table-modules', {
            ordering: true,
            layout: {
                topStart: {
                    buttons: ['excel', 'pdf', 'print'],
                }
            },
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
