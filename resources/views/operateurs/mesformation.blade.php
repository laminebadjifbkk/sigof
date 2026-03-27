@extends('layout.user-layout')
@section('title', 'ONFP | OPERATEURS')
@section('space-work')

    <div class="pt-1">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">

            {{-- Titre à gauche --}}
            <div class="d-flex align-items-center gap-2">
                <h6 class="mb-0 text-muted fw-semibold text-uppercase">
                    Liste des formations
                </h6>
            </div>

            @if ($operateur->formations->isNotEmpty())
                <div class="d-flex align-items-center gap-2 text-info fw-semibold">

                    <a href="{{ url('/note_de_frais') }}"
                        class="btn btn-outline-primary btn-sm d-flex align-items-center gap-2" target="_blank">
                        <i class="bi bi-file-earmark-word-fill text-primary"></i>
                        Note de frais (Acompte & Définitive)
                    </a>
                </div>

                {{-- Boutons à droite --}}
                <div class="d-flex align-items-center gap-2">
                    <div class="d-flex align-items-center gap-3">

                        <a href="{{ url('/decharge_transport') }}"
                            class="btn btn-outline-primary btn-sm d-flex align-items-center gap-2" target="_blank">
                            <i class="bi bi-file-earmark-word-fill text-primary"></i>
                            Décharge transport
                        </a>

                    </div>
                </div>
            @endif
        </div>
    </div>

    @if ($formations->isNotEmpty())
        <div class="table-responsive">
            <table class="table datatables table-bordered table-hover align-middle justify-content-center"
                id="table-formations">
                <thead class="table-success text-center">
                    <tr>
                        {{-- <th width='6%' class="text-center">Code</th> --}}
                        <th width='8%' class="text-center">N° conv.</th>
                        <th width='25%'>Bénéficiaires</th>
                        <th width='15%'>Modules</th>
                        <th width='15%'>Niveau qualif.</th>
                        <th width='10%' class="text-center">Responsable</th>
                        <th width='5%' class="text-center">Effectif</th>
                        <th width='5%' class="text-center">Statut</th>
                        @can('formation-show')
                            <th width='3%'><i class="bi bi-gear"></i></th>
                        @endcan
                    </tr>
                </thead>
                <tbody>
                    @foreach ($formations as $formation)
                        <tr>
                            {{-- <td style="text-align: center">{{ $formation?->code }}</td> --}}
                            <td style="text-align: center">{{ $formation?->numero_convention }}</td>
                            <td>{{ $formation?->name ?? ' ' }}</td>
                            <td>
                                {{ $formation?->module?->name ?? ($formation?->collectivemodule?->module ?? '') }}
                            </td>
                            <td>{{ $formation?->titre ?? $formation?->referentiel?->titre }}</td>
                            <td class="text-center">
                                {{ $formation?->ingenieur?->user?->firstname . ' ' . $formation?->ingenieur?->user?->name ?? ' ' }}
                            </td>
                            <td class="text-center">
                                {{ $formation?->effectif_prevu }}
                            </td>
                            <td class="text-center">
                                <a><span class="{{ $formation->statut }}">{{ $formation->statut }}</span></a>
                            </td>
                            @can('formation-show')
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <a href="{{ route('formations.show', $formation) }}" class="btn btn-primary btn-sm"
                                            title="Voir les détails">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <div class="dropdown">
                                            <a href="#" class="btn btn-sm btn-light" data-bs-toggle="dropdown"
                                                aria-expanded="false" title="Plus d'actions">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </a>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                @can('formation-update')
                                                    <li>
                                                        <a href="{{ route('formations.edit', $formation) }}" class="dropdown-item">
                                                            <i class="bi bi-pencil"></i> Modifier
                                                        </a>
                                                    </li>
                                                @endcan
                                                @can('formation-delete')
                                                    <li>
                                                        <form action="{{ route('formations.destroy', $formation) }}" method="POST"
                                                            class="dropdown-item show_confirm">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger">
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
        </div>
    @else
        <div class="alert alert-info">Vous ne bénéficiez d'aucune formation pour l'instant !</div>
    @endif
@endsection

@push('scripts')
    <script>
        new DataTable('#table-formations', {
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
