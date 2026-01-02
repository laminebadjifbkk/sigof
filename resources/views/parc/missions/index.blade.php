@extends('layout.user-layout')
@section('title', 'ONFP - Liste des missions')

@section('space-work')
    <section class="section register">
        <div class="container">
            <div class="row mb-4">
                <!-- Total missions -->
                <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                    <div class="card shadow-sm text-center p-2" style="min-height: 140px; border-radius: 10px;">
                        <h6 class="card-title mb-2 text-truncate" title="Total missions" style="font-size:0.85rem;">
                            Total
                        </h6>
                        <div class="d-flex flex-column align-items-center justify-content-center mb-2">
                            <div class="rounded-circle bg-primary text-white d-flex justify-content-center align-items-center mb-1"
                                style="width:36px; height:36px; font-size:1rem;">
                                <i class="bi bi-flag"></i>
                            </div>
                            <span class="h6 mb-0" style="font-size:1rem;">{{ $totalMissions }}</span>
                            <small class="text-muted" style="font-size:0.7rem;">mission(s)</small>
                        </div>

                        <!-- Barre de pourcentage -->
                        <div class="mb-2">
                            <div class="progress" style="height:6px; border-radius:3px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: 100%;"></div>
                            </div>
                            <small class="text-muted">100%</small>
                        </div>

                        <!-- Bouton voir plus -->
                        <a href="{{ route('parc-missions.index') }}" class="btn btn-outline-primary btn-sm w-100"
                            style="font-size:0.75rem;">
                            Voir plus <i class="bi bi-arrow-right-short"></i>
                        </a>
                    </div>
                </div>

                <!-- Missions de l'année en cours -->
                <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                    <div class="card shadow-sm text-center p-2" style="min-height: 140px; border-radius: 10px;">
                        <h6 class="card-title mb-2 text-truncate" title="Missions cette année" style="font-size:0.85rem;">
                            Cette année
                        </h6>
                        <div class="d-flex flex-column align-items-center justify-content-center mb-2">
                            <div class="rounded-circle bg-success text-white d-flex justify-content-center align-items-center mb-1"
                                style="width:36px; height:36px; font-size:1rem;">
                                <i class="bi bi-calendar"></i>
                            </div>
                            <span class="h6 mb-0" style="font-size:1rem;">{{ $missionsAnnee }}</span>
                            <small class="text-muted" style="font-size:0.7rem;">mission(s)</small>
                        </div>

                        <!-- Barre de pourcentage -->
                        <div class="mb-2">
                            @php
                                $percentAnnee = $totalMissions ? round(($missionsAnnee * 100) / $totalMissions, 1) : 0;
                            @endphp
                            <div class="progress" style="height:6px; border-radius:3px;">
                                <div class="progress-bar bg-success" role="progressbar"
                                    style="width: {{ $percentAnnee }}%;"></div>
                            </div>
                            <small class="text-muted">{{ $percentAnnee }}%</small>
                        </div>

                        <!-- Bouton voir plus -->
                        <a href="{{ route('parc-missions.index', ['annee' => now()->year]) }}"
                            class="btn btn-outline-primary btn-sm w-100" style="font-size:0.75rem;">
                            Voir plus <i class="bi bi-arrow-right-short"></i>
                        </a>
                    </div>
                </div>

                @foreach ($groupes as $statut => $items)
                    <div class="col-6 col-sm-4 col-md-3 col-lg-2 mb-3">
                        <div class="card shadow-sm text-center p-2" style="min-height: 120px; border-radius: 10px;">

                            <!-- Statut -->
                            <h6 class="card-title mb-2 text-truncate" title="{{ $statut }}"
                                style="font-size: 0.85rem;">
                                {{ $statut }}
                            </h6>

                            <!-- Nombre et icône -->
                            <div class="d-flex flex-column align-items-center justify-content-center mb-2">
                                <div class="rounded-circle bg-primary text-white d-flex justify-content-center align-items-center mb-1"
                                    style="width: 36px; height: 36px; font-size: 1rem;">
                                    <i class="bi bi-flag"></i>
                                </div>
                                <span class="h6 mb-0" style="font-size: 1rem;">
                                    {{ $items->count() }}
                                </span>
                                <small class="text-muted" style="font-size: 0.7rem;">mission(s)</small>
                            </div>

                            <!-- Pourcentage -->
                            <div class="mb-2">
                                <div class="progress" style="height:6px; border-radius:3px;">
                                    <div class="progress-bar bg-success" role="progressbar"
                                        style="width: {{ $statutPourcentages[$statut]['percent'] }}%;"></div>
                                </div>
                                <small class="text-muted">{{ $statutPourcentages[$statut]['percent'] }}%</small>
                            </div>

                            <!-- Bouton voir plus -->
                            <a href="{{ route('parc-missions.index', ['statut' => $statut]) }}"
                                class="btn btn-outline-primary btn-sm w-100" style="font-size: 0.75rem;">
                                Voir plus <i class="bi bi-arrow-right-short"></i>
                            </a>

                        </div>
                    </div>
                @endforeach
            </div>

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
                        <th class="text-center" width="12%">Véhicules</th>
                        <th class="text-center" width="12%">Agents</th>
                        <th class="text-center" width="12%">Statut</th>
                        <th class="text-center" width="12%">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($missions as $mission)
                        <tr>
                            {{--  <td>{{ $mission->reference }}</td> --}}
                            <td>
                                <span class="short-text">{{ Str::limit($mission->objet, 25) }}</span>
                                <span class="full-text d-none">{{ $mission->objet }}</span>

                                @if (strlen($mission->objet) > 25)
                                    <a href="#" class="toggle-text">...voir plus</a>
                                @endif
                            </td>

                            {{-- <td>{{ $mission->lieu_depart }} → {{ $mission->lieu_arrivee }}</td> --}}
                            <td>
                                {{ $mission->date_depart->format('d/m/Y') }}
                                @if ($mission->date_retour)
                                    -{{ $mission->date_retour->format('d/m/Y') }}
                                @endif
                            </td>
                            <td class="text-center">{{ $mission?->vehicules?->count() }}</td>
                            <td class="text-center">{{ $mission->employees->count() }}</td>
                            <td class="text-center">
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
                                    <a href="{{ route('parc-missions.show', $mission->id) }}"
                                        class="btn btn-sm btn-info">
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
                            <!-- Modal -->
                            <div class="modal fade" id="objetModal{{ $mission->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Objet de la mission</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            {{ $mission->objet }}
                                        </div>
                                    </div>
                                </div>
                            </div>
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
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.toggle-text').forEach(function(link) {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const td = this.closest('td');
                    const shortText = td.querySelector('.short-text');
                    const fullText = td.querySelector('.full-text');

                    if (fullText.classList.contains('d-none')) {
                        shortText.classList.add('d-none');
                        fullText.classList.remove('d-none');
                        this.textContent = 'voir moins';
                    } else {
                        fullText.classList.add('d-none');
                        shortText.classList.remove('d-none');
                        this.textContent = '...voir plus';
                    }
                });
            });
        });
    </script>
@endpush
