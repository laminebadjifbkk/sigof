@extends('layout.user-layout')
@section('title', 'SIGOF | Formations ' . $libelle)
@section('space-work')

    <div class="pagetitle">
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Accueil</a></li>
                <li class="breadcrumb-item">Tables</li>
                <li class="breadcrumb-item active">Données</li>
            </ol>
        </nav>
    </div>
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
                            role="alert">{{ $error }}</div>
                    @endforeach
                @endif
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            @can('formation-create')
                                <h5 class="card-title">Liste des formations {{ $libelle }}</h5>
                                @can('formation-create')
                                    <span class="d-flex align-items-baseline">
                                        <a href="#" class="btn btn-primary btn-sm float-end" data-bs-toggle="modal"
                                            data-bs-target="#AddFormationModal" title="Ajouter">Ajouter</a>
                                        <div class="filter">
                                            <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                                    class="bi bi-three-dots"></i></a>
                                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                                <li>
                                                    <button type="button" class="dropdown-item btn btn-sm" data-bs-toggle="modal"
                                                        data-bs-target="#generate_rapportFormation"></i>Générer
                                                        suivi-convention</button>
                                                </li>
                                            </ul>
                                        </div>
                                    </span>
                                @endcan
                            @endcan
                        </div>
                        @if ($formations->isNotEmpty())
                            <table class="table datatables table-bordered table-hover align-middle justify-content-center"
                                id="table-formations">
                                <thead class="table-success text-center">
                                    <tr>
                                        <th width='6%' class="text-center">Code</th>
                                        <th width='8%' class="text-center">Date créat.</th>
                                        <th width='8%' class="text-center">N° conv.</th>
                                        <th width='25%'>Bénéficiaires</th>
                                        <th width='15%'>Modules</th>
                                        <th width='17%'>Niveau qualif.</th>
                                        <th width='5%' class="text-center">Statut</th>
                                        <th width='5%'><i class="bi bi-gear"></i></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($formations as $formation)
                                        <tr>
                                            <td style="text-align: center">{{ $formation?->code }}</td>
                                            <td style="text-align: center">{{ $formation?->created_at?->format('d/m/Y') }}</td>
                                            <td style="text-align: center">{{ $formation?->numero_convention }}</td>
                                            <td>{{ $formation?->name ?? 'Non spécifié' }}</td>
                                            <td>
                                                <span
                                                    class="{{ $formation->module->name ?? ($formation->collectivemodule->module ?? 'Aucun') }}">
                                                    {{ $formation->module->name ?? ($formation->collectivemodule->module ?? 'Aucun') }}
                                                </span>
                                            </td>
                                            {{-- <td>{{ $formation->type_certification }}</td> --}}
                                            <td>{{ $formation?->titre ?? $formation?->referentiel?->titre }}</td>
                                            <td class="text-center">
                                                <a><span
                                                        class="{{ $formation->statut }}">{{ $formation->statut }}</span></a>
                                            </td>
                                            <td>
                                                @can('formation-show')
                                                    <span class="d-flex align-items-baseline">
                                                        <a href="{{ route('formations.show', $formation) }}"
                                                            class="btn btn-primary btn-sm" title="Voir détails">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                        <div class="filter">
                                                            <a class="icon" href="#" data-bs-toggle="dropdown">
                                                                <i class="bi bi-three-dots"></i>
                                                            </a>
                                                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                                                @can('formation-update')
                                                                    <li>
                                                                        <a class="dropdown-item btn btn-sm"
                                                                            href="{{ route('formations.edit', $formation) }}"
                                                                            title="Modifier">
                                                                            <i class="bi bi-pencil"></i> Modifier
                                                                        </a>
                                                                    </li>
                                                                @endcan
                                                                @can('formation-delete')
                                                                    <li>
                                                                        <form
                                                                            action="{{ route('formations.destroy', $formation) }}"
                                                                            method="POST">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="submit"
                                                                                class="dropdown-item show_confirm"
                                                                                title="Supprimer">
                                                                                <i class="bi bi-trash"></i> Supprimer
                                                                            </button>
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
                        @else
                            <div class="alert alert-info">Aucune formation créée pour l'instant !</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
@push('scripts')
    <script>
        new DataTable('#table-formations', {
            layout: {
                topStart: {
                    buttons: ['excel', 'pdf', 'print'],
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
