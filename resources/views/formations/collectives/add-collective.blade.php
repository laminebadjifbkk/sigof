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
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12 pt-0">
                                <span class="d-flex mt-0 align-items-baseline"><a
                                        href="{{ route('formations.show', $formation) }}" class="btn btn-success btn-sm"
                                        title="retour"><i class="bi bi-arrow-counterclockwise"></i></a>&nbsp;
                                    <p> | Retour</p>
                                </span>
                            </div>
                        </div>

                        <div class="p-1 mb-4 border rounded bg-light shadow-sm">
                            <div class="row text-center fw-semibold">
                                <div class="col-md-4 mb-2">
                                    <span class="text-secondary">Région</span><br>
                                    <span class="fs-5 text-dark">{{ $localite->nom ?? 'Aucune' }}</span>
                                </div>
                                @if (!empty($formation?->collective?->module))
                                    <div class="col-md-4 mb-2">
                                        <span class="text-secondary">Structure</span><br>
                                        <span
                                            class="fs-5 text-dark">{{ $formation?->collective?->collective?->name_with_sigle ?? 'Aucun' }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <form method="post" action="{{ url('formationcollectives', ['$idformation' => $formation->id]) }}"
                            enctype="multipart/form-data" class="row g-3">
                            @csrf
                            @method('PUT')
                            <div class="row mb-3 border rounded bg-light shadow-sm p-3">
                                <div class="col-md-12 pt-5">
                                    <div class="table-responsive">
                                        <table class="m-2 table datatables align-middle" id="table-modules">
                                            <thead>
                                                <tr>
                                                    <th>Structure</th>
                                                    <th>Modules</th>
                                                    <th>Responsable</th>
                                                    <th width="3%"><i class="bi bi-gear"></i></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $i = 1; ?>
                                                @foreach ($collectives as $collective)
                                                    <tr>
                                                        <td>
                                                            {{ $collective?->name_with_sigle }}
                                                        </td>
                                                        <td>
                                                            @php $count = $collective?->collectivemodules?->count() ?? 0; @endphp
                                                            <span class="badge bg-info">{{ $count }}</span>
                                                        </td>
                                                        <td>

                                                        </td>
                                                        <td>
                                                            <span class="d-flex mt-2 align-items-baseline">
                                                                <a href="{{ route('collectivemoduleformations', [
                                                                    'idformation' => $formation->id,
                                                                    'idlocalite' => $formation->departement->region->id,
                                                                    'idcollective' => $collective->id,
                                                                ]) }}"
                                                                    class="btn btn-success btn-sm mx-1"
                                                                    title="Voir détails">
                                                                    <i class="bi bi-eye"></i>
                                                                </a>
                                                            </span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-outline-primary btn-sm"><i
                                            class="bi bi-check2-circle"></i>&nbsp;Sélectionner</button>
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
        new DataTable('#table-modules', {
            lengthMenu: [
                [5, 10, 25, 50, 100, -1],
                [5, 10, 25, 50, 100, "Tout"]
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
