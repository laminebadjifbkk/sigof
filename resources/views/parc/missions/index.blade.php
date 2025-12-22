@extends('layout.user-layout')
@section('title', 'ONFP - Liste des missions')

@section('space-work')
    <section class="section register">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="mb-0">Liste des missions</h1>
                <a href="{{ route('parc-missions.create') }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-plus-circle"></i> Ajouter une mission
                </a>
            </div>

            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <table class="table table-hover table-striped shadow-sm" id="table-parc-mission">
                <thead class="table-dark">
                    <tr>
                        {{-- <th>Référence</th> --}}
                        <th>Objet</th>
                        {{-- <th>Lieu</th> --}}
                        <th>Dates</th>
                        <th>Véhicule</th>
                        <th>Chauffeur</th>
                        <th>Statut</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($missions as $mission)
                        <tr>
                           {{--  <td>{{ $mission->reference }}</td> --}}
                            <td>{{ $mission->objet }}</td>
                            {{-- <td>{{ $mission->lieu_depart }} → {{ $mission->lieu_arrivee }}</td> --}}
                            <td>
                                {{ $mission->date_depart->format('d/m/Y') }}
                                @if ($mission->date_retour)
                                    - {{ $mission->date_retour->format('d/m/Y') }}
                                @endif
                            </td>
                            <td>{{ $mission->vehicule?->immatriculation ?? 'N/A' }}</td>
                            <td>{{ ($mission->chauffeur?->nom .' '.$mission->chauffeur?->prenom) ?? 'N/A' }}</td>
                            <td>
                                <span
                                    class="badge 
                                @switch($mission->statut)
                                    @case('planifiee') bg-secondary @break
                                    @case('en_cours') bg-warning @break
                                    @case('terminee') bg-success @break
                                    @case('annulee') bg-danger @break
                                    @default bg-info
                                @endswitch">
                                    {{ ucfirst($mission->statut) }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="d-flex align-items-baseline justify-content-center gap-1">
                                    <a href="{{ route('parc-missions.show', $mission->id) }}" class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('parc-missions.edit', $mission->id) }}"
                                        class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('parc-missions.destroy', $mission->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger show_confirm">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        new DataTable('#table-parc-mission', {
            ordering: false,
            layout: {
                topStart: {
                    buttons: ['csv', 'excel', 'print'],
                }
            },
            language: {
                "sProcessing": "Traitement en cours...",
                "sSearch": "Rechercher&nbsp;:",
                "sLengthMenu": "Afficher _MENU_ éléments",
                "sInfo": "Affichage de l'élément _START_ à _END_ sur _TOTAL_ éléments",
                "sInfoEmpty": "Affichage de l'élément 0 à 0 sur 0 élément",
                "sInfoFiltered": "(filtré de _MAX_ éléments au total)",
                "sLoadingRecords": "Chargement en cours...",
                "sZeroRecords": "Aucun élément à afficher",
                "sEmptyTable": "Aucune donnée disponible dans le tableau",
                "oPaginate": {
                    "sFirst": "Premier",
                    "sPrevious": "Précédent",
                    "sNext": "Suivant",
                    "sLast": "Dernier"
                },
                "oAria": {
                    "sSortAscending": ": activer pour trier la colonne par ordre croissant",
                    "sSortDescending": ": activer pour trier la colonne par ordre décroissant"
                },
                "select": {
                    "rows": {
                        _: "%d lignes sélectionnées",
                        0: "Aucune ligne sélectionnée",
                        1: "1 ligne sélectionnée"
                    }
                }
            }
        });
    </script>
@endpush
