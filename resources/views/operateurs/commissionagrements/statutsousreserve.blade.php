@extends('layout.user-layout')
@section('title', 'OPERATEURS | ' . $commissionagrement?->commission)
@section('space-work')

    <section class="section">
        <div class="pagetitle">
            {{-- <h1>Data Tables</h1> --}}
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/home') }}">Accueil</a></li>
                    <li class="breadcrumb-item">Tables</li>
                    <li class="breadcrumb-item active">{{ $commissionagrement?->commission }}</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->
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
                        <span class="d-flex mt-2 align-items-baseline"><a
                                href="{{ route('commissionagrements.show', $commissionagrement?->id) }}"
                                class="btn btn-info btn-sm" title="retour"><i
                                    class="bi bi-arrow-counterclockwise"></i></a>&nbsp;
                            <p> | Retour</p>
                        </span>
                        <div
                            class="card-title d-flex flex-wrap justify-content-between align-items-center mb-4 p-3 bg-light rounded shadow-sm">
                            <span>{{ $commissionagrement?->commission }}</span>
                            <span class="d-flex align-items-baseline">
                                <span class="{{ $statut }} text-white">{{ $statut }}</span></span>
                        </div>
                        <div class="row mb-0">
                            <div class="col-md-12">
                                <div class="float-end mb-3">
                                    <div class="d-flex align-items-baseline gap-2">
                                        {{-- Export PV (Procès-verbal complet) --}}
                                        {{-- <a href="{{ route('commissionagrements.exportPV', ['statut' => $statut, 'commissionagrement' => $commissionagrement->id]) }}"
                                            class="btn btn-primary btn-sm">
                                            <i class="bi bi-journal-text"></i> Exporter le PV
                                        </a> --}}

                                        {{-- Export opérateurs en PDF --}}
                                        {{-- <a href="{{ route('operateurs.parStatutCommission.pdf', ['statut' => $statut, 'commission' => $commissionagrement->id]) }}"
                                            class="btn btn-danger btn-sm" title="Exporter la liste des opérateurs en PDF">
                                            <i class="bi bi-file-earmark-pdf"></i> Exporter opérateurs (PDF)
                                        </a> --}}

                                        {{-- Export opérateurs en Excel --}}
                                        <a href="{{ route('operateurs.parStatutCommission.excel', ['statut' => $statut, 'commission' => $commissionagrement->id]) }}"
                                            class="btn btn-success btn-sm"
                                            title="Exporter la liste des opérateurs en Excel">
                                            <i class="bi bi-file-earmark-excel"></i> Exporter opérateurs (Excel)
                                        </a>

                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table datatables align-middle" id="table-operateurs">
                                        <thead>
                                            <tr>
                                                <th width="5%" class="text-center">Dossier</th>
                                                <th width="15%">N° agrément</th>
                                                <th width="50%">Opérateurs</th>
                                                <th width="10%">Sigle</th>
                                                <th class="text-center">Modules</th>
                                                <th width="15%" class="text-center">Statut</th>
                                                <th><i class="bi bi-gear"></i></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i = 1; ?>
                                            @foreach ($operateurs as $operateur)
                                                {{-- @if (!empty($operateur?->numero_agrement)) --}}
                                                <tr>
                                                    <td class="text-center">{{ $operateur?->numero_dossier }}</td>
                                                    <td>{{ $operateur?->numero_agrement }}</td>
                                                    <td>{{ $operateur?->user?->operateur }}</td>
                                                    <td>{{ $operateur?->user?->username }}</td>
                                                    {{-- <td style="text-align: center;">
                                                        @foreach ($operateur?->operateurmodules as $operateurmodule)
                                                            @if ($loop->last)
                                                                <a href="#"><span
                                                                        class="badge bg-info">{{ $loop->count }}</span></a>
                                                            @endif
                                                        @endforeach
                                                    </td> --}}
                                                    <td class="text-center">
                                                        @if ($operateur?->operateurmodules?->count())
                                                            <a href="#"><span
                                                                    class="badge bg-warning">{{ $operateur->operateurmodules->count() }}</span></a>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        <span
                                                            class="{{ $operateur->statut_agrement }}">{{ $operateur->statut_agrement }}</span>
                                                    </td>
                                                    <td>
                                                        <span class="d-flex align-items-baseline"><a
                                                                href="{{ route('agrements', ['id' => $operateur?->id]) }}"
                                                                class="btn btn-primary btn-sm" title="voir détails"><i
                                                                    class="bi bi-eye"></i></a>
                                                            <div class="filter">
                                                                <a class="icon" href="#"
                                                                    data-bs-toggle="dropdown"><i
                                                                        class="bi bi-three-dots"></i></a>
                                                                <ul
                                                                    class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                                                    {{--  <li>
                                                                            <button type="button"
                                                                                class="dropdown-item btn btn-sm mx-1"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#EditOperateurModal{{ $operateur?->id }}">
                                                                                <i class="bi bi-pencil" title="Modifier"></i>
                                                                                Modifier
                                                                            </button>
                                                                        </li> --}}
                                                                    <form
                                                                        action="{{ route('retirerOperateur', ['id' => $operateur->id]) }}"
                                                                        method="post">
                                                                        @csrf
                                                                        @method('PUT')
                                                                        <button
                                                                            class="show_confirm_retirer btn btn-sm mx-1"><i
                                                                                class="bi bi-reply-fill"
                                                                                title="Retirer"></i>&nbsp;Retirer</button>
                                                                    </form>

                                                                </ul>
                                                            </div>
                                                        </span>
                                                    </td>
                                                </tr>
                                                {{-- @endif --}}
                                            @endforeach
                                        </tbody>
                                    </table>
                                    </table>
                                </div>
                            </div>
                            {{-- </form> --}}
                        </div>
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
                    buttons: [ 'csv', 'excel', 'print'],
                }
            }, */
            "lengthMenu": [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "Tout"]
            ],
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
