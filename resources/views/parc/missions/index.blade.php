@extends('layout.user-layout')
@section('title', 'ONFP - Liste des missions')

@section('space-work')
    <section class="section register">
        <div class="container">

            {{-- ===== Cartes de statistiques ===== --}}
            <div class="row mb-3">
                @include('parc.missions._stat-card', [
                    'title' => 'Missions total',
                    'count' => $totalMissions,
                    'percent' => 100,
                    'url' => route('parc-missions.index'),
                    'active' => !$statut && !$annee,
                ])

                {{-- @php
                    $percentAnnee = $totalMissions ? round(($missionsAnnee * 100) / $totalMissions, 1) : 0;
                @endphp
                @include('parc.missions._stat-card', [
                    'title' => 'Année ' . now()->year,
                    'count' => $missionsAnnee,
                    'percent' => $percentAnnee,
                    'url' => route('parc-missions.index', ['annee' => now()->year]),
                    'active' => (int) $annee === now()->year && !$statut,
                ]) --}}

                @foreach ($statutStats as $statutKey => $stat)
                    @include('parc.missions._stat-card', [
                        'title' => $labels[$statutKey] ?? ucfirst(str_replace('_', ' ', $statutKey)),
                        'count' => $stat['count'],
                        'percent' => $stat['percent'],
                        'url' => route('parc-missions.index', ['statut' => $statutKey]),
                        'active' => $statut === $statutKey,
                    ])
                @endforeach
            </div>

            {{-- ===== En-tête de la liste ===== --}}
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                <h3 class="mb-0">
                    Liste des missions
                    @if ($statut)
                        <span class="etat-btn {{ $statut }}">
                            {{ $labels[$statut] ?? ucfirst(str_replace('_', ' ', $statut)) }}
                        </span>
                    @endif
                    @if ($annee)
                        <span class="badge bg-secondary fs-6">Année {{ $annee }}</span>
                    @endif
                    @if ($statut || $annee)
                        <a href="{{ route('parc-missions.index') }}" class="btn btn-sm btn-outline-secondary ms-2">
                            <i class="bi bi-x-circle"></i> Réinitialiser
                        </a>
                    @endif
                </h3>

                @can('parc-mission-create')
                    <a href="{{ route('parc-missions.create') }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-plus-circle"></i> Ajouter une mission
                    </a>
                @endcan
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

            {{-- ===== Missions groupées par année puis par mois ===== --}}
            @forelse ($missions as $year => $months)
                <div class="accordion mb-3" id="accordion-{{ $loop->index }}">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }} fw-bold" type="button"
                                data-bs-toggle="collapse" data-bs-target="#year-{{ $loop->index }}">
                                <i class="bi bi-calendar3 me-2"></i> {{ $year }}
                                <span class="badge bg-primary ms-2">{{ $months->flatten()->count() }}</span>
                            </button>
                        </h2>
                        <div id="year-{{ $loop->index }}"
                            class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}">
                            <div class="accordion-body">

                                @foreach ($months as $month => $items)
                                    @php
                                        $monthName =
                                            $month === '00'
                                                ? 'Date non disponible'
                                                : ucfirst(
                                                    \Carbon\Carbon::create(2000, (int) $month, 1)
                                                        ->locale('fr')
                                                        ->translatedFormat('F'),
                                                );
                                    @endphp

                                    <h6 class="mt-4 mb-2 text-primary border-bottom pb-1">
                                        <i class="bi bi-calendar-month me-1"></i>
                                        {{ $monthName }} {{ $year }}
                                        <span class="badge bg-secondary">{{ $items->count() }}</span>
                                    </h6>

                                    <div class="table-responsive">
                                        <table class="table table-hover table-striped shadow-sm table-missions"
                                            data-title="Missions - {{ $monthName }} {{ $year }}">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th>Objet</th>
                                                    <th>Périodes</th>
                                                    <th class="text-center" width="12%">Véhicules</th>
                                                    <th class="text-center" width="12%">Agents</th>
                                                    <th class="text-center" width="12%">Statut</th>
                                                    <th class="text-center" width="12%">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($items as $mission)
                                                    @include('parc.missions._row', [
                                                        'mission' => $mission,
                                                        'labels' => $labels,
                                                    ])
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="alert alert-info">Aucune mission pour ces critères.</div>
            @endforelse
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        const dataTableLanguage = {
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
        };

        // Un DataTable par tableau (un tableau par mois)
        document.querySelectorAll('.table-missions').forEach(function(el) {
            const title = el.dataset.title || 'Missions';

            new DataTable(el, {
                ordering: false,
                layout: {
                    topStart: {
                        buttons: [{
                                extend: 'csv',
                                title: title
                            },
                            {
                                extend: 'excel',
                                title: title
                            },
                            {
                                extend: 'print',
                                title: title
                            }
                        ],
                    }
                },
                language: dataTableLanguage
            });
        });

        // "voir plus / voir moins" : délégation d'événement, pour que ça fonctionne
        // aussi sur les lignes des autres pages de DataTables
        document.addEventListener('click', function(e) {
            const link = e.target.closest('.toggle-text');
            if (!link) return;

            e.preventDefault();
            const td = link.closest('td');
            const shortText = td.querySelector('.short-text');
            const fullText = td.querySelector('.full-text');

            if (fullText.classList.contains('d-none')) {
                shortText.classList.add('d-none');
                fullText.classList.remove('d-none');
                link.textContent = 'voir moins';
            } else {
                fullText.classList.add('d-none');
                shortText.classList.remove('d-none');
                link.textContent = '...voir plus';
            }
        });
    </script>
@endpush
