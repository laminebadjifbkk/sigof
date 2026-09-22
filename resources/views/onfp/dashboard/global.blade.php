@extends('layout.user-layout')

@section('title', 'Tableau de bord - Activités')

@section('space-work')

    <div class="container-fluid py-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1">
                    <i class="bi bi-speedometer2 me-2"></i>
                    Tableau de bord des activités
                </h1>
                <p class="text-muted mb-0">
                    @if ($visionGlobale)
                        Vue d'ensemble - toutes Directions confondues
                    @else
                        Vue d'ensemble de votre Direction
                    @endif
                </p>
            </div>
            <a href="{{ route('onfp.activites.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-list-ul me-1"></i>
                Voir la liste détaillée
            </a>
        </div>

        {{-- ============================================================
        KPI GLOBAUX
        ============================================================= --}}
        {{-- <div class="row row-cols-2 row-cols-md-5 g-3 mb-4">

            <div class="col">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted small mb-1">Total activités</div>
                        <h3 class="mb-0">{{ $kpiGlobaux['total'] }}</h3>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted small mb-1">En cours</div>
                        <h3 class="mb-0 text-primary">{{ $kpiGlobaux['en_cours'] }}</h3>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted small mb-1">Terminées</div>
                        <h3 class="mb-0 text-success">{{ $kpiGlobaux['terminee'] }}</h3>
                    </div>
                </div>
            </div>

            <div class="col">
                <div
                    class="card border-0 shadow-sm h-100 {{ $kpiGlobaux['en_retard'] > 0 ? 'border-danger border-opacity-25' : '' }}">
                    <div class="card-body">
                        <div class="text-muted small mb-1">En retard</div>
                        <h3 class="mb-0 text-danger">{{ $kpiGlobaux['en_retard'] }}</h3>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted small mb-1">Progression moyenne</div>
                        <h3 class="mb-0">{{ $kpiGlobaux['progression_moyenne'] }}%</h3>
                    </div>
                </div>
            </div>

        </div> --}}

        <div class="row row-cols-2 row-cols-md-5 g-3 mb-4">

            <div class="col">
                <div class="card border-0 shadow-sm h-100 rounded-4 hover-card">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="icon-box rounded-circle bg-primary bg-opacity-10 text-primary flex-shrink-0">
                            <i class="bi bi-kanban fs-4"></i>
                        </div>
                        <div>
                            <div class="text-muted small mb-1">Total activités</div>
                            <h3 class="mb-0 fw-bold">{{ $kpiGlobaux['total'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card border-0 shadow-sm h-100 rounded-4 hover-card">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="icon-box rounded-circle bg-primary bg-opacity-10 text-primary flex-shrink-0">
                            <i class="bi bi-arrow-repeat fs-4"></i>
                        </div>
                        <div>
                            <div class="text-muted small mb-1">En cours</div>
                            <h3 class="mb-0 fw-bold text-primary">{{ $kpiGlobaux['en_cours'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card border-0 shadow-sm h-100 rounded-4 hover-card">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="icon-box rounded-circle bg-success bg-opacity-10 text-success flex-shrink-0">
                            <i class="bi bi-check-circle fs-4"></i>
                        </div>
                        <div>
                            <div class="text-muted small mb-1">Terminées</div>
                            <h3 class="mb-0 fw-bold text-success">{{ $kpiGlobaux['terminee'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div
                    class="card border-0 shadow-sm h-100 rounded-4 hover-card {{ $kpiGlobaux['en_retard'] > 0 ? 'border-start border-danger border-4' : '' }}">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="icon-box rounded-circle bg-danger bg-opacity-10 text-danger flex-shrink-0">
                            <i class="bi bi-exclamation-triangle fs-4"></i>
                        </div>
                        <div>
                            <div class="text-muted small mb-1">En retard</div>
                            <h3 class="mb-0 fw-bold text-danger">{{ $kpiGlobaux['en_retard'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card border-0 shadow-sm h-100 rounded-4 hover-card">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="icon-box rounded-circle bg-warning bg-opacity-10 text-warning flex-shrink-0">
                            <i class="bi bi-speedometer2 fs-4"></i>
                        </div>
                        <div>
                            <div class="text-muted small mb-1">Progression moyenne</div>
                            <h3 class="mb-0 fw-bold">{{ $kpiGlobaux['progression_moyenne'] }}%</h3>
                        </div>
                    </div>
                </div>
            </div>

        </div>


        {{-- ============================================================
        KPI FORMATIONS (module distinct des activités)
        ============================================================= --}}
        <div class="row row-cols-1 row-cols-md-2 g-3 mb-4">

            <div class="col">
                <div class="card border-0 shadow-sm h-100 rounded-4 hover-card">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="icon-box rounded-circle bg-info bg-opacity-10 text-info flex-shrink-0">
                            <i class="bi bi-mortarboard fs-4"></i>
                        </div>
                        <div>
                            <div class="text-muted small mb-1">Formations en cours</div>
                            <h3 class="mb-0 fw-bold text-info">{{ $formationsEnCours }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card border-0 shadow-sm h-100 rounded-4 hover-card">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="icon-box rounded-circle bg-success bg-opacity-10 text-success flex-shrink-0">
                            <i class="bi bi-people fs-4"></i>
                        </div>
                        <div>
                            <div class="text-muted small mb-1">Nombre de formés en {{ $anneeActuelle }}</div>
                            <h3 class="mb-0 fw-bold text-success">{{ $nbFormesAnnee }}</h3>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- ============================================================
        GRAPHIQUES
        ============================================================= --}}
        <div class="row g-3 mb-4">

            <div class="col-lg-8">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Activités par Direction (par statut)</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="chartParDirection" height="90"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Répartition globale par statut</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="chartRepartitionStatuts"></canvas>
                    </div>
                </div>
            </div>

        </div>


        {{-- ============================================================
        ACTIVITÉS URGENTES
        ============================================================= --}}
        @if ($activitesUrgentes->count())
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="bi bi-exclamation-triangle text-danger me-2"></i>
                        Activités à surveiller en priorité
                    </h5>
                    <small class="text-muted">En retard ou à risque/critique - triées par échéance la plus proche</small>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Activité</th>
                                    <th>Direction</th>
                                    <th>Responsable</th>
                                    <th>Échéance</th>
                                    <th>Progression</th>
                                    <th class="text-end pe-4">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($activitesUrgentes as $activite)
                                    @php
                                        $principal =
                                            $activite->responsables->firstWhere('is_principal', true) ??
                                            $activite->responsables->first();
                                        $enRetard = $activite->date_fin_prevue && $activite->date_fin_prevue->isPast();
                                    @endphp
                                    <tr>
                                        <td class="ps-4">
                                            <a href="{{ route('onfp.activites.show', $activite) }}"
                                                class="text-decoration-none fw-semibold">
                                                {{ $activite->titre }}
                                            </a>
                                            <div class="small text-muted">{{ $activite->reference }}</div>
                                        </td>
                                        <td>{{ $activite->direction?->sigle ?: $activite->direction?->name ?? '-' }}</td>
                                        <td>
                                            @if ($principal && $principal->employee)
                                                @php
                                                    $u = $principal->employee->user;
                                                    $nom = $u
                                                        ? trim($u->firstname . ' ' . $u->name)
                                                        : $principal->employee->matricule;
                                                @endphp
                                                {{ $nom }}
                                            @else
                                                <span class="text-muted">Non affecté</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($activite->date_fin_prevue)
                                                <span class="{{ $enRetard ? 'text-danger fw-semibold' : '' }}">
                                                    {{ $activite->date_fin_prevue->format('d/m/Y') }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td style="min-width: 100px">
                                            <div class="progress" style="height: 6px">
                                                <div class="progress-bar" style="width: {{ $activite->progression }}%">
                                                </div>
                                            </div>
                                            <small>{{ $activite->progression }}%</small>
                                        </td>
                                        <td class="text-end pe-4">
                                            <a href="{{ route('onfp.activites.show', $activite) }}"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif


        {{-- ============================================================
        TABLEAU DÉTAILLÉ PAR DIRECTION
        ============================================================= --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Détail par Direction</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Direction</th>
                                <th>Total</th>
                                <th>À faire</th>
                                <th>En cours</th>
                                <th>Terminées</th>
                                <th>En retard</th>
                                <th>À risque</th>
                                <th>Progression moy.</th>
                                <th class="text-center">Responsables actifs</th>
                                <th class="text-end pe-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($lignesDirections as $ligne)
                                <tr>
                                    <td class="ps-4 fw-semibold">
                                        {{ $ligne->direction->sigle ?: $ligne->direction->name }}
                                    </td>
                                    <td>{{ $ligne->total }}</td>
                                    <td>{{ $ligne->a_faire }}</td>
                                    <td>{{ $ligne->en_cours }}</td>
                                    <td>{{ $ligne->terminee }}</td>
                                    <td>
                                        @if ($ligne->en_retard > 0)
                                            <span class="badge bg-danger">{{ $ligne->en_retard }}</span>
                                        @else
                                            0
                                        @endif
                                    </td>
                                    <td>
                                        @if ($ligne->a_risque > 0)
                                            <span class="badge bg-warning text-dark">{{ $ligne->a_risque }}</span>
                                        @else
                                            0
                                        @endif
                                    </td>
                                    <td style="min-width: 100px">
                                        <div class="progress" style="height: 6px">
                                            <div class="progress-bar" style="width: {{ $ligne->progression_moyenne }}%">
                                            </div>
                                        </div>
                                        <small>{{ $ligne->progression_moyenne }}%</small>
                                    </td>
                                    <td class="text-center">{{ $ligne->nb_responsables }}</td>
                                    <td class="text-end pe-4">
                                        <a href="{{ route('onfp.activites.index', ['direction_id' => $ligne->direction->id]) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            Voir
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center py-4 text-muted">
                                        Aucune activité enregistrée pour le moment.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

@endsection

@push('scripts')
    <script>
        // Rechargement automatique toutes les 60 secondes — pratique pour
        // un affichage permanent sur écran de télévision (mode kiosque).
        setTimeout(function() {
            window.location.reload();
        }, 60000);
    </script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        const directions = @json($lignesDirections->map(fn($l) => $l->direction->sigle ?: $l->direction->name));
        const aFaire = @json($lignesDirections->pluck('a_faire'));
        const enCours = @json($lignesDirections->pluck('en_cours'));
        const terminee = @json($lignesDirections->pluck('terminee'));
        const suspendue = @json($lignesDirections->pluck('suspendue'));
        const annulee = @json($lignesDirections->pluck('annulee'));

        new Chart(document.getElementById('chartParDirection'), {
            type: 'bar',
            data: {
                labels: directions,
                datasets: [{
                        label: 'À faire',
                        data: aFaire,
                        backgroundColor: '#6c757d'
                    },
                    {
                        label: 'En cours',
                        data: enCours,
                        backgroundColor: '#0d6efd'
                    },
                    {
                        label: 'Terminées',
                        data: terminee,
                        backgroundColor: '#198754'
                    },
                    {
                        label: 'Suspendues',
                        data: suspendue,
                        backgroundColor: '#ffc107'
                    },
                    {
                        label: 'Annulées',
                        data: annulee,
                        backgroundColor: '#212529'
                    },
                ]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        stacked: true
                    },
                    y: {
                        stacked: true,
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        new Chart(document.getElementById('chartRepartitionStatuts'), {
            type: 'doughnut',
            data: {
                labels: ['À faire', 'En cours', 'Terminées', 'Suspendues', 'Annulées'],
                datasets: [{
                    data: [
                        {{ $lignesDirections->sum('a_faire') }},
                        {{ $lignesDirections->sum('en_cours') }},
                        {{ $lignesDirections->sum('terminee') }},
                        {{ $lignesDirections->sum('suspendue') }},
                        {{ $lignesDirections->sum('annulee') }},
                    ],
                    backgroundColor: ['#6c757d', '#0d6efd', '#198754', '#ffc107', '#212529'],
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
@endpush
