@extends('layout.user-layout')
@section('title', 'ONFP - Activités quotidiennes')

@section('space-work')
    <section class="section">
        <div class="container">

            <div class="row mb-1">
                <!-- Total activités -->
                <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                    <div class="card shadow-sm text-center p-2" style="min-height:140px; border-radius:10px;">
                        <h6 class="card-title mb-2 text-truncate" title="Total activités">Activités total</h6>
                        <div class="d-flex flex-column align-items-center justify-content-center mb-2">
                            {{-- <div class="rounded-circle bg-primary text-white d-flex justify-content-center align-items-center mb-1"
                                style="width:36px; height:36px; font-size:1rem;">
                                <i class="bi bi-flag"></i>
                            </div> --}}
                            <span class="h6 mb-0" style="font-size:1rem;">{{ $totalActivites }}</span>
                        </div>
                        <div class="mb-2">
                            <div class="progress" style="height:6px; border-radius:3px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width:100%;"></div>
                            </div>
                            <small class="text-muted">100%</small>
                        </div>
                        <a href="{{ route('activites-quotidiennes.index') }}" class="btn btn-outline-primary btn-sm w-100"
                            style="font-size:0.75rem;">
                            Voir plus <i class="bi bi-arrow-right-short"></i>
                        </a>
                    </div>
                </div>

                <!-- Activités année en cours -->
                <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                    <div class="card shadow-sm text-center p-2" style="min-height:140px; border-radius:10px;">
                        <h6 class="card-title mb-2 text-truncate" title="Année {{ now()->year }}">Année
                            {{ now()->year }}</h6>
                        <div class="d-flex flex-column align-items-center justify-content-center mb-2">
                            {{-- <div class="rounded-circle bg-success text-white d-flex justify-content-center align-items-center mb-1"
                                style="width:36px; height:36px; font-size:1rem;">
                                <i class="bi bi-calendar"></i>
                            </div> --}}
                            <span class="h6 mb-0" style="font-size:1rem;">{{ $activitesAnnee }}</span>
                        </div>
                        @php
                            $percentAnnee = $totalActivites ? round(($activitesAnnee * 100) / $totalActivites, 1) : 0;
                        @endphp
                        <div class="mb-2">
                            <div class="progress" style="height:6px; border-radius:3px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width:{{ $percentAnnee }}%;">
                                </div>
                            </div>
                            <small class="text-muted">{{ $percentAnnee }}%</small>
                        </div>
                        <a href="{{ route('activites-quotidiennes.index', ['annee' => now()->year]) }}"
                            class="btn btn-outline-primary btn-sm w-100" style="font-size:0.75rem;">
                            Voir plus <i class="bi bi-arrow-right-short"></i>
                        </a>
                    </div>
                </div>

                <!-- Statuts -->
                @foreach ($groupes as $statut_s => $items)
                    <div class="col-6 col-sm-4 col-md-3 col-lg-2 mb-3">
                        <div class="card shadow-sm text-center p-2" style="min-height:120px; border-radius:10px;">
                            <h6 class="card-title mb-2 text-truncate" title="{{ $statut_s }}">
                                {{ $labels[$statut_s] ?? ucfirst($statut_s) }}
                            </h6>
                            <div class="d-flex flex-column align-items-center justify-content-center mb-2">
                                {{-- <div class="rounded-circle bg-primary text-white d-flex justify-content-center align-items-center mb-1"
                                    style="width:36px; height:36px; font-size:1rem;">
                                    <i class="bi bi-flag"></i>
                                </div> --}}
                                <span class="h6 mb-0" style="font-size:1rem;">{{ $items->count() }}</span>
                            </div>
                            <div class="mb-2">
                                <div class="progress" style="height:6px; border-radius:3px;">
                                    <div class="progress-bar bg-success" role="progressbar"
                                        style="width:{{ $statutPourcentages[$statut_s]['percent'] ?? 0 }}%;"></div>
                                </div>
                                <small class="text-muted">{{ $statutPourcentages[$statut_s]['percent'] ?? 0 }}%</small>
                            </div>
                            <a href="{{ route('activites-quotidiennes.index', ['statut' => $statut_s]) }}"
                                class="btn btn-outline-primary btn-sm w-100" style="font-size:0.75rem;">
                                Voir plus <i class="bi bi-arrow-right-short"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="mb-3 d-flex gap-2 flex-wrap">
                <a href="{{ route('activites-quotidiennes.index') }}" class="btn btn-secondary btn-sm">Toutes</a>
                <a href="{{ route('activites-quotidiennes.index', ['filter' => 'today']) }}"
                    class="btn btn-primary btn-sm">Aujourd'hui</a>
                <a href="{{ route('activites-quotidiennes.index', ['filter' => 'week']) }}"
                    class="btn btn-info btn-sm">Cette semaine</a>
            </div>

            <div class="pt-1">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">

                    {{-- Titre à gauche --}}
                    <div class="d-flex align-items-center gap-2">
                        <h6 class="mb-0 text-muted fw-semibold text-uppercase">
                            Liste des activités
                        </h6>
                    </div>

                    <div class="d-flex align-items-center gap-2 text-info fw-semibold">
                        <i class="bi bi-list-ul me-1"></i>
                        <span>
                            Affichage :
                            <span class="text-dark">{{ $affichees }}</span>
                            sur
                            <span class="text-dark">{{ $total }}</span> activités
                        </span>
                    </div>

                    {{-- Boutons à droite --}}
                    @can('activite-create')
                        <div class="d-flex align-items-center gap-2">
                            <a href="{{ route('activites-quotidiennes.create') }}" class="btn btn-sm btn-primary">
                                Créer activité
                            </a>
                        </div>
                    @endcan

                </div>
            </div>
            <!-- Tableau des activités -->
            <div class="card shadow-sm">
                <div class="card-body table-responsive">
                    <table class="table table-sm table-hover align-middle" id="table-activite">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Titre</th>
                                <th>Agent</th>
                                <th>Date</th>
                                <th>HD</th>
                                <th>HF</th>
                                <th>Priorité</th>
                                {{-- <th>Statut</th> --}}
                                <th class="text-center" width="12%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($activites as $activite)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $activite->titre }}</td>
                                    <td>{{ $activite->user->firstname ?? '' }} {{ $activite->user->name ?? '' }}</td>
                                    <td>{{ $activite->date_activite?->format('d/m/Y') }}</td>
                                    <td>{{ $activite->heure_debut?->format('H:i') }}</td>
                                    <td>{{ $activite->heure_fin?->format('H:i') }}</td>
                                    <td>
                                        <span class="badge-activite {{ ucfirst($activite->priorite) }}">
                                            {{ $labels[$activite->priorite] ?? $activite->priorite }}
                                        </span>
                                    </td>
                                    {{-- <td>
                                        <span class="badge-activite {{ ucfirst($activite->statut) }}">
                                            {{ $labels[$activite->statut] ?? $activite->statut }}
                                        </span>
                                    </td> --}}

                                    <td class="text-center">
                                        <span class="d-flex align-items-baseline justify-content-center gap-1">
                                            <a href="{{ route('activites-quotidiennes.show', $activite->id) }}"
                                                class="btn btn-sm btn-info">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('activites-quotidiennes.edit', $activite->id) }}"
                                                class="btn btn-sm btn-warning">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <form action="{{ route('activites-quotidiennes.destroy', $activite->id) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="btn btn-sm btn-danger show_confirm"
                                                    title="Supprimer">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">Aucune activité trouvée</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </section>
@endsection


@push('scripts')
    <script>
        new DataTable('#table-activite', {
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
