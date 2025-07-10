@extends('layout.user-layout')
@section('title', 'ONFP', ' | ' . $evaluateur?->name . ' ' . $evaluateur?->lastname)
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
                                    <a href="{{ route('lettrevaluations.index') }}"
                                        class="btn btn-outline-success btn-sm rounded-pill shadow-sm"
                                        title="Retour à la liste">
                                        <i class="bi bi-arrow-counterclockwise me-1"></i> Retour
                                    </a>
                                    <span class="text-muted small">Lettres évaluations & ABE</span>
                                </div>
                            </div>

                            <h5 class="card-title mt-4 fw-semibold text-info">
                                <i class="bi bi-list-ul me-1"></i> Liste des formations de : <span
                                    class="text-dark">{{ $evaluateur?->name . ' ' . $evaluateur?->lastname }}</span>
                            </h5>
                            @if ($evaluateur?->formations->isNotEmpty())
                                <table class="table datatables align-middle" id="table-employes">
                                    <thead>
                                        <tr>
                                            <th width='6%' class="text-center">Code</th>
                                            <th width='8%' class="text-center">N° conv.</th>
                                            <th width='25%'>Bénéficiaires</th>
                                            <th width='15%'>Modules</th>
                                            <th width='15%'>Niveau qualif.</th>
                                            <th width='10%' class="text-center">Opérateurs</th>
                                            <th width='5%' class="text-center">Statut</th>
                                            @can('formation-show')
                                                <th width='3%'><i class="bi bi-gear"></i></th>
                                            @endcan
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1; ?>
                                        @foreach ($evaluateur?->formations as $formation)
                                            <tr>
                                                <td style="text-align: center">{{ $formation?->code }}</td>
                                                <td style="text-align: center">{{ $formation?->numero_convention }}</td>
                                                <td>{{ $formation?->name ?? ' ' }}</td>
                                                <td>
                                                    <span
                                                        class="{{ $formation->module->name ?? ($formation->collectivemodule->module ?? 'Aucun') }}">
                                                        {{ $formation->module->name ?? ($formation->collectivemodule->module ?? 'Aucun') }}
                                                    </span>
                                                </td>
                                                <td>{{ $formation?->titre ?? $formation?->referentiel?->titre }}</td>
                                                <td class="text-center">
                                                    {{ $formation?->operateur?->user?->username ?? ' ' }}
                                                </td>
                                                <td class="text-center">
                                                    <a><span
                                                            class="{{ $formation->statut }}">{{ $formation->statut }}</span></a>
                                                </td>
                                                @can('formation-show')
                                                    <td>
                                                        <div class="d-flex align-items-center gap-2">
                                                            <!-- Bouton Voir détails -->
                                                            <a href="{{ route('formations.show', $formation) }}"
                                                                class="btn btn-primary btn-sm" title="Voir les détails">
                                                                <i class="bi bi-eye"></i>
                                                            </a>

                                                            <!-- Menu déroulant d'actions -->
                                                            <div class="dropdown">
                                                                <a href="#" class="btn btn-sm btn-light"
                                                                    data-bs-toggle="dropdown" aria-expanded="false"
                                                                    title="Plus d'actions">
                                                                    <i class="bi bi-three-dots-vertical"></i>
                                                                </a>
                                                                <ul class="dropdown-menu dropdown-menu-end">
                                                                    @can('formation-update')
                                                                        <li>
                                                                            <a href="{{ route('formations.edit', $formation) }}"
                                                                                class="dropdown-item">
                                                                                <i class="bi bi-pencil"></i> Modifier
                                                                            </a>
                                                                        </li>
                                                                    @endcan
                                                                    @can('formation-delete')
                                                                        <li>
                                                                            <form
                                                                                action="{{ route('formations.destroy', $formation) }}"
                                                                                method="POST"
                                                                                onsubmit="return confirm('Confirmer la suppression de cette formation ?')">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                                <button type="submit"
                                                                                    class="dropdown-item text-danger">
                                                                                    <i class="bi bi-trash"></i> Supprimer
                                                                                </button>
                                                                            </form>
                                                                        </li>
                                                                    @endcan
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </td>
                                                @endcan
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <!-- End Table with stripped rows -->
                            @else
                                <div class="alert alert-info">Aucune formation pour l'instant !</div>
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
