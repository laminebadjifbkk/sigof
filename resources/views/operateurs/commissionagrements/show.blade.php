@extends('layout.user-layout')
@section('title', $commissionagrement?->commission)
@section('space-work')
    <section class="section dashboard">
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
        <div class="row">
            <!-- Left side columns -->
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-12 col-md-4 col-lg-2 col-sm-12 col-xs-12 col-xxl-2">
                        <div class="card info-card revenue-card shadow-sm" style="max-width: 220px;">
                            <div class="card-body p-2">
                                <h5 class="card-title text-truncate mb-1" title="operateurs" style="font-size: 1rem;">
                                    {{ $commissionagrement?->commission }}
                                </h5>
                                <div class="d-flex align-items-center mb-2">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-primary text-white"
                                        style="width: 32px; height: 32px; font-size: 1.25rem;">
                                        <i class="bi bi-people"></i>
                                    </div>
                                    <div class="ps-2">
                                        <h6 class="mb-0" style="font-size: 0.9rem;">
                                            {{ number_format(count($commissionagrement?->operateurs), 0, '', ' ') }}</h6>
                                        <span class="text-muted small">opérateur(s)</span>
                                    </div>
                                </div>

                                <a href="{{ route('commissionagrements.show', $commissionagrement->id) }}"
                                    class="btn btn-outline-primary btn-sm w-100 d-flex align-items-center justify-content-center py-1"
                                    style="font-size: 0.85rem; gap: 6px;">
                                    Voir plus <i class="bi bi-arrow-right-short"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    @foreach ($groupesStatutAgrement as $statut => $items)
                        <div class="col-12 col-md-4 col-lg-2 col-sm-12 col-xs-12 col-xxl-2">
                            <div class="card info-card sales-card shadow-sm" style="max-width: 220px;">
                                <div class="card-body p-2">
                                    <h5 class="card-title text-truncate mb-1" title="{{ $statut }}"
                                        style="font-size: 1rem;">
                                        {{ $statut }}
                                    </h5>
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-primary text-white"
                                            style="width: 32px; height: 32px; font-size: 1.25rem;">
                                            <i class="bi bi-people"></i>
                                        </div>
                                        <div class="ps-2">
                                            <h6 class="mb-0" style="font-size: 0.9rem;">
                                                {{ number_format($items->count(), 0, '', ' ') }}</h6>
                                            <span class="text-muted small">opérateur(s)</span>
                                        </div>
                                    </div>
                                    <a href="{{ route('operateurs.parStatutCommission', ['statut' => $statut, 'commission' => $commissionagrement->id]) }}"
                                        target="_blank"
                                        class="btn btn-outline-primary btn-sm w-100 d-flex align-items-center justify-content-center py-1"
                                        style="font-size: 0.85rem; gap: 6px;">
                                        Voir plus <i class="bi bi-arrow-right-short"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
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
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="d-flex mt-0 align-items-baseline"><a
                                    href="{{ route('commissionagrements.index', $commissionagrement->id) }}"
                                    class="btn btn-success btn-sm" title="retour"><i
                                        class="bi bi-arrow-counterclockwise"></i></a>&nbsp;
                                <p> | {{ $commissionagrement?->commission }}</p>
                            </span>
                            <h5 class="card-title">
                                <a href="{{ route('addopCommission', ['id' => $commissionagrement->id]) }}"
                                    class="btn btn-success btn-sm" title="ajouter">intégrer opérateurs</a>
                            </h5>
                        </div>
                        <div class="row mb-0">
                            <div class="col-md-12 pt-5">
                                <div class="table-responsive">
                                    <table class="table datatables align-middle" id="table-operateurs">
                                        <thead>
                                            <tr>
                                                <th width="5%" class="text-center">Dossier</th>
                                                <th width="15%">N° agrément</th>
                                                <th width="50%">Opérateurs</th>
                                                <th class="text-center">Modules</th>
                                                <th width="15%" class="text-center">Statut</th>
                                                <th><i class="bi bi-gear"></i></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i = 1; ?>
                                            @foreach ($commissionagrement?->operateurs as $operateur)
                                                <tr>
                                                    <td class="text-center">{{ $operateur?->numero_dossier }}</td>
                                                    <td>{{ $operateur?->numero_agrement }}</td>
                                                    <td>{{ $operateur?->user?->display_operateur }}</td>
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
                                                                    <form
                                                                        action="{{ route('retirerOperateurCommission', ['idoperateur' => $operateur->id, 'idcommission' => $commissionagrement->id]) }}"
                                                                        method="post">
                                                                        @csrf
                                                                        @method('PUT')
                                                                        <button
                                                                            class="show_confirm_retirer btn btn-sm mx-1"><i
                                                                                class="bi bi-reply-fill"
                                                                                title="Retirer"></i>&nbsp;Retirer de la
                                                                            commission</button>
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
                                </div>
                            </div>
                            {{-- </form> --}}
                        </div>
                    </div>
                </div>
            </div>
    </section>
@endsection
@push('scripts')
    <script>
        new DataTable('#table-operateurs', {
            "lengthMenu": [
                [5, 10, 25, 50, 100, -1],
                [5, 10, 25, 50, 100, "Tout"]
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
