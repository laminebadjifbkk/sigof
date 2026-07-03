@extends('layout.user-layout')
@section('space-work')
    @if ($user->hasAnyRole(['super-admin', 'admin', 'DIOF', 'DEC', 'Ingenieur', 'Employe']))
        <section class="section dashboard">
            <div class="row">
                {{-- <div class="col-12">
                    @if ($formations->isNotEmpty())
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-4">
                                    Formations en cours
                                    <span
                                        class="badge bg-warning text-white ms-2">{{ $formations->total() ?? $formations->count() }}</span>
                                </h5>

                                @foreach ($formations as $f)
                                    @php
                                        $formation = $f['formation'];
                                        $progress = $f['progress'];
                                        $color = $f['color'];
                                        $date = $f['date'];
                                        $isIndividuelle = $f['isIndividuelle'];
                                        $isCollective = $f['isCollective'];

                                        $libelle = $isIndividuelle
                                            ? $formation->module?->name
                                            : $formation->collectivemodule?->module;
                                    @endphp

                                    <div class="border rounded p-3 mb-4 shadow-sm bg-white">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h6 class="text-primary mb-3">
                                                Module :
                                                <a href="{{ route('formations.show', $formation) }}">
                                                    @if (!empty($libelle))
                                                        {{ $libelle }}
                                                    @else
                                                        <span class="btn btn-primary btn-sm" title="Voir les détails">
                                                            <i class="bi bi-eye"></i>
                                                        </span>
                                                    @endif
                                                </a>
                                            </h6>

                                            <span>
                                                <i class="bi bi-play-circle text-success me-1"></i>
                                                Démarrage :

                                                @if ($date)
                                                    @if ($date->isToday())
                                                        <span class="badge bg-success">Aujourd'hui</span>
                                                    @elseif ($date->isYesterday())
                                                        <span class="badge bg-warning">Hier</span>
                                                    @elseif ($date->diffInDays(\Carbon\Carbon::today()) < 7)
                                                        <span class="badge bg-primary">
                                                            Il y a {{ $date->diffInDays(\Carbon\Carbon::today()) }} jours
                                                        </span>
                                                    @else
                                                        @php
                                                            $diff = $date->diff(\Carbon\Carbon::today());
                                                            $parts = [];
                                                            if ($diff->y > 0) {
                                                                $parts[] = $diff->y . ' ' . Str::plural('an', $diff->y);
                                                            }
                                                            if ($diff->m > 0) {
                                                                $parts[] = $diff->m . ' mois';
                                                            }
                                                            if ($diff->d > 0) {
                                                                $parts[] =
                                                                    $diff->d . ' ' . Str::plural('jour', $diff->d);
                                                            }
                                                        @endphp
                                                        <span class="badge bg-secondary">Il y a
                                                            {{ implode(' ', $parts) }}</span>
                                                    @endif
                                                @else
                                                    <span class="badge bg-danger">Date non disponible</span>
                                                @endif
                                            </span>
                                        </div>

                                        <div class="row fs-sm">
                                            <div class="col-md-6 mb-2">
                                                <i class="bi bi-calendar-event me-1"></i>
                                                <strong>Période :</strong>
                                                <span class="{{ is_null($formation->date_debut) ? 'text-danger' : '' }}">
                                                    {{ 'Du ' . ($formation->date_debut?->format('d/m/Y') ?? 'Non définie') }}
                                                </span>
                                                <span class="{{ is_null($formation->date_fin) ? 'text-danger' : '' }}">
                                                    {{ ' au ' . ($formation->date_fin?->format('d/m/Y') ?? 'Non définie') }}
                                                </span>
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <i class="bi bi-calendar-check me-1"></i>
                                                <strong>Date évaluation :</strong>
                                                <span class="{{ is_null($formation->date_pv) ? 'text-danger' : '' }}">
                                                    {{ $formation->date_pv?->format('d/m/Y') ?? 'Non définie' }}
                                                </span>
                                            </div>

                                            <div class="col-md-6 mb-2">
                                                <i class="bi bi-geo-alt-fill me-1"></i>
                                                <strong>Lieu :</strong> {{ $formation->lieu ?? 'N/A' }}
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <i class="bi bi-clock me-1"></i>
                                                <strong>Durée :</strong> {{ $formation->duree_formation ?? '-' }}
                                                @if ($formation->duree_formation === 1)
                                                    jour
                                                @elseif ($formation->duree_formation > 1)
                                                    jours
                                                @endif
                                            </div>
                                            <div class="col-md-12 mb-2">
                                                <strong>Ingénieur : </strong>
                                                {{ $formation?->ingenieur?->user?->firstname && $formation?->ingenieur?->user?->name
                                                    ? $formation->ingenieur->user->firstname . ' ' . $formation->ingenieur->user->name
                                                    : 'Aucun' }}
                                            </div>
                                        </div>

                                        @if (!is_null($progress))
                                            <div class="progress mt-3" style="height: 20px;">
                                                <div class="progress-bar progress-bar-striped progress-bar-animated {{ $color }}"
                                                    role="progressbar" style="width: {{ $progress }}%"
                                                    aria-valuenow="{{ $progress }}" aria-valuemin="0"
                                                    aria-valuemax="100">
                                                    {{ $progress === 100 ? 'Terminée' : $progress . '%' }}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach

                                <div class="d-flex justify-content-center mt-4">
                                    {{ $formations->links('pagination.custom') }}
                                </div>

                            </div>
                        </div>
                    @endif
                </div> --}}

                <div class="col-12">
                    @if ($formations->isNotEmpty())
                        <div class="row g-4">

                            {{-- ============ Tableau : Suivi des formations en cours ============ --}}
                            <div class="col-lg-12">
                                <div class="card h-100 shadow-sm border-0 rounded-4">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-4">
                                            <h5 class="card-title mb-0">
                                                Formations en cours
                                                <span
                                                    class="badge bg-warning text-white ms-1">{{ $formations->total() ?? $formations->count() }}</span>
                                            </h5>
                                            @if (Route::has('formations.index'))
                                                <a href="{{ route('formations.index') }}"
                                                    class="text-decoration-none small fw-semibold">
                                                    Voir tout <i class="bi bi-arrow-right"></i>
                                                </a>
                                            @endif
                                        </div>

                                        <div class="table-responsive">
                                            <table class="table align-middle formations-table">
                                                <thead>
                                                    <tr class="text-uppercase text-muted small">
                                                        <th>Conv.</th>
                                                        <th>Formation</th>
                                                        {{-- <th>Ingénieur</th> --}}
                                                        <th>Progres.</th>
                                                        <th>Démar.</th>
                                                        <th>Fin</th>
                                                        <th>Éval.</th>
                                                        <th>J restants</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($formations as $f)
                                                        @php
                                                            $formation = $f['formation'];
                                                            $progress = $f['progress'];
                                                            $date = $f['dateDebut'];
                                                            $dateFin = $f['dateFin'];
                                                            $dateEval = $f['dateEval'];
                                                            $jours = $f['joursRestants'];

                                                            $libelle = $f['isIndividuelle']
                                                                ? $formation->module?->name
                                                                : $formation->collectivemodule?->module;

                                                            $badgeClass = match (true) {
                                                                is_null($progress) => 'bg-secondary text-white',
                                                                $progress <= 20 => 'bg-danger text-white',
                                                                $progress <= 40 => 'bg-warning text-white',
                                                                $progress <= 60 => 'bg-info text-white',
                                                                $progress <= 80 => 'bg-primary text-white',
                                                                default => 'bg-success text-white',
                                                            };

                                                            /* $badgeLabel = $isAVenir
                                                                ? 'À venir'
                                                                : (is_null($progress)
                                                                    ? 'N/A'
                                                                    : ($progress >= 100
                                                                        ? 'Terminée'
                                                                        : $progress . '%')); */

                                                            $joursClass = match (true) {
                                                                is_null($jours) => 'text-muted',
                                                                $jours < 0 => 'text-danger fw-semibold',
                                                                $jours <= 3 => 'text-warning fw-semibold',
                                                                default => 'text-body',
                                                            };

                                                            $joursLabel = match (true) {
                                                                is_null($jours) => '-',
                                                                $jours < 0 => abs($jours) . ' j de retard',
                                                                $jours === 0 => "Aujourd'hui",
                                                                default => $jours . ' j',
                                                            };
                                                        @endphp
                                                        <tr>
                                                            <td class="text-muted small">
                                                                {{ $formation?->numero_convention ?? '-' }}
                                                            </td>
                                                            <td class="fw-semibold">
                                                                {{ $libelle ?? 'N/A' }}
                                                                <div class="text-muted small fw-normal">
                                                                    <i class="bi bi-geo-alt"></i>
                                                                    {{ $formation?->lieu ?? 'N/A' }}
                                                                </div>
                                                                <div class="text-muted small fw-normal">
                                                                    <i class="bi bi-person"></i>
                                                                    {{ $formation?->ingenieur?->user?->firstname . ' ' . $formation?->ingenieur?->user?->name ?? 'N/A' }}
                                                                </div>
                                                            </td>
                                                            {{-- <td class="small">
                                                                {{ $formation?->ingenieur?->user?->firstname . ' ' . $formation?->ingenieur?->user?->name ?? 'N/A' }}
                                                            </td> --}}
                                                            <td>
                                                                {{-- <span class="badge rounded-pill {{ $badgeClass }}">
                                                                    {{ $badgeLabel }}
                                                                </span> --}}
                                                            </td>
                                                            <td class="small">
                                                                @if ($date)
                                                                    {{ $date->format('d/m/Y') }}
                                                                @else
                                                                    <span class="text-danger">Non définie</span>
                                                                @endif
                                                            </td>
                                                            <td class="small">
                                                                @if ($dateFin)
                                                                    {{ $dateFin->format('d/m/Y') }}
                                                                @else
                                                                    <span class="text-danger">Non définie</span>
                                                                @endif
                                                            </td>
                                                            <td class="small">
                                                                @if ($dateEval)
                                                                    {{ $dateEval?->format('d/m/Y') }}
                                                                @else
                                                                    <span class="text-muted">-</span>
                                                                @endif
                                                            </td>
                                                            <td class="small {{ $joursClass }}">
                                                                {{ $joursLabel }}
                                                            </td>
                                                            <td class="text-end">
                                                                <a href="{{ route('formations.show', $formation) }}"
                                                                    class="btn btn-outline-primary btn-sm rounded-pill px-3">
                                                                    Ouvrir
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="d-flex justify-content-center mt-3">
                                            {{ $formations->links('pagination.custom') }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- ============ Donut : Progression globale ============ --}}
                            {{-- <div class="col-lg-4">
                                @php
                                    $progressValues = $formations->pluck('progress')->filter(fn($p) => !is_null($p));
                                    $totalCount = max($progressValues->count(), 1);

                                    $termine = $progressValues->filter(fn($p) => $p >= 100)->count();
                                    $enCours = $progressValues->filter(fn($p) => $p > 0 && $p < 100)->count();
                                    $aVenir = $progressValues->filter(fn($p) => $p == 0)->count();

                                    $pctTermine = round(($termine / $totalCount) * 100);
                                    $pctEnCours = round(($enCours / $totalCount) * 100);
                                    $pctAVenir = 100 - $pctTermine - $pctEnCours;

                                    $avgProgress = $progressValues->count() ? round($progressValues->avg()) : 0;
                                @endphp

                                <div class="card h-100 shadow-sm border-0 rounded-4">
                                    <div class="card-body d-flex flex-column align-items-center">
                                        <h5 class="card-title align-self-start mb-4">Progression formations</h5>

                                        <div class="donut-chart mb-4"
                                            style="--p-terminee: {{ $pctTermine }}; --p-encours: {{ $pctEnCours }};">
                                            <div class="donut-inner">
                                                <span class="fs-3 fw-bold">{{ $avgProgress }}%</span>
                                                <span class="text-muted small">Complété</span>
                                            </div>
                                        </div>

                                        <ul class="list-unstyled w-100 small">
                                            <li class="d-flex justify-content-between align-items-center mb-2">
                                                <span><span class="legend-dot bg-success"></span> Modules validés</span>
                                                <strong>{{ $pctTermine }}%</strong>
                                            </li>
                                            <li class="d-flex justify-content-between align-items-center mb-2">
                                                <span><span class="legend-dot bg-warning"></span> En cours</span>
                                                <strong>{{ $pctEnCours }}%</strong>
                                            </li>
                                            <li class="d-flex justify-content-between align-items-center">
                                                <span><span class="legend-dot bg-light border"></span> À venir</span>
                                                <strong>{{ $pctAVenir }}%</strong>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div> --}}

                        </div>
                    @endif
                </div>

                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Graphique demandes individuelles</h5>

                            <canvas id="lineChart" style="min-height: 200px;"></canvas>
                            <script>
                                document.addEventListener("DOMContentLoaded", () => {
                                    new Chart(document.querySelector('#lineChart'), {
                                        type: 'line',
                                        data: {
                                            labels: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août',
                                                'Septembre', 'Octobre', 'Novembre', 'Décembre'
                                            ],
                                            datasets: [{
                                                label: 'Graphique linéaire',
                                                data: [{{ $janvier }}, {{ $fevrier }}, {{ $mars }},
                                                    {{ $avril }}, {{ $mai }}, {{ $juin }},
                                                    {{ $juillet }}, {{ $aout }}, {{ $septembre }},
                                                    {{ $octobre }}, {{ $novembre }}, {{ $decembre }}
                                                ],
                                                fill: false,
                                                borderColor: 'rgb(75, 192, 192)',
                                                tension: 0.1
                                            }]
                                        },
                                        options: {
                                            scales: {
                                                y: {
                                                    beginAtZero: true
                                                }
                                            }
                                        }
                                    });
                                });
                            </script>

                        </div>
                    </div>
                </div>
                {{-- <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Diagramme circulaire</h5>

                        <canvas id="pieChart" style="max-height: 365px;"></canvas>
                        <script>
                            document.addEventListener("DOMContentLoaded", () => {
                                new Chart(document.querySelector('#pieChart'), {
                                    type: 'pie',
                                    data: {
                                        labels: [
                                            'Masculin',
                                            'Féminin',
                                        ],
                                        datasets: [{
                                            label: 'Diagramme circulaire',
                                            data: [{{ $masculin }}, {{ $feminin }}],
                                            backgroundColor: [
                                                'rgb(255, 205, 86)',
                                                'rgb(54, 162, 235)',
                                            ],
                                            hoverOffset: 4
                                        }]
                                    }
                                });
                            });
                        </script>

                    </div>
                </div>
            </div> --}}
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Diagramme demandes individulles</h5>

                            <!-- Donut Chart -->
                            <div id="donutChart" style="min-height: 200px;" class="echart"></div>

                            <script>
                                document.addEventListener("DOMContentLoaded", () => {
                                    echarts.init(document.querySelector("#donutChart")).setOption({
                                        tooltip: {
                                            trigger: 'item'
                                        },
                                        legend: {
                                            top: '5%',
                                            left: 'center'
                                        },
                                        series: [{
                                            name: 'Access From',
                                            type: 'pie',
                                            radius: ['40%', '70%'],
                                            avoidLabelOverlap: false,
                                            label: {
                                                show: false,
                                                position: 'center'
                                            },
                                            emphasis: {
                                                label: {
                                                    show: true,
                                                    fontSize: '18',
                                                    fontWeight: 'bold'
                                                }
                                            },
                                            labelLine: {
                                                show: false
                                            },
                                            data: [{
                                                    value: {{ $masculin }},
                                                    name: 'Hommes'
                                                },
                                                {
                                                    value: {{ $feminin }},
                                                    name: 'Femmes'
                                                }
                                            ]
                                        }]
                                    });
                                });
                            </script>
                            <!-- End Donut Chart -->

                        </div>
                    </div>
                </div>
            </div>
        </section>

        @can('home-view')
            <section class="section dashboard">
                <div class="row">
                    <!-- Left side columns -->
                    <div class="col-12 col-xxl-12">
                        <div class="row">
                            <!-- Sales Card -->
                            <div class="col-12 col-sm-6 col-lg-3">
                                <div class="card info-card sales-card">
                                    <a href="{{ route('individuelles.index') }}">
                                        <div class="card-body">
                                            <h5 class="card-title">Demandes<span> | {{ date('d/m/Y') }}</span></h5>
                                            <div class="d-flex align-items-center">
                                                <div
                                                    class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                                    <i class="bi bi-calendar-check-fill"></i>
                                                </div>
                                                <div class="ps-3">
                                                    <h6>
                                                        <span
                                                            class="text-primary">{{ number_format($count_today, 0, '', ' ') }}</span>
                                                    </h6>
                                                    <span class="text-success small pt-1 fw-bold">Aujourd'hui</span>
                                                    {{-- <span class="text-muted small pt-2 ps-1">increase</span> --}}
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            {{-- <div class="col-12 col-sm-6 col-lg-3">
                            <div class="card info-card sales-card">
                                <div class="filter">
                                    <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                            class="bi bi-three-dots"></i></a>
                                </div>
                                <a href="{{ route('individuelles.index') }}">
                                    <div class="card-body">
                                        <h5 class="card-title">Individuelles <span>| toutes</span></h5>
                                        <div class="d-flex align-items-center">
                                            <div
                                                class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                                <i class="bi bi-file-earmark-text"></i>
                                            </div>
                                            <div class="ps-3">
                                                <h6>
                                                    <span
                                                        class="text-primary">{{ number_format(count($individuelles), 0, '', ' ') }}</span>
                                                </h6>
                                                <span class="text-success small pt-1 fw-bold">Toutes</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div> --}}

                            {{-- Demandes individuelles --}}
                            {{-- <div class="col-12 col-sm-6 col-lg-3">
                                <div class="card info-card sales-card">
                                    <a href="{{ route('individuelles.index') }}">
                                        <div class="card-body">
                                            <h5 class="card-title">Demandes <span>| individuelles</span></h5>
                                            <div class="d-flex align-items-center">
                                                <div
                                                    class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                                    <i class="bi bi-file-earmark-text"></i>
                                                </div>
                                                <div class="ps-3">
                                                    <h6>
                                                        <span
                                                            class="text-primary">{{ number_format($total_individuelle, 0, '', ' ') }}</span>
                                                    </h6>
                                                    <span
                                                        class="text-success small pt-1 fw-bold">{{ number_format($pourcentage_femmes, 2, ',', ' ') . '%' }}</span>
                                                    <span class="text-muted small pt-2 ps-1">femmes</span>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div> --}}

                            {{-- Demandes collectives --}}
                            {{-- <div class="col-12 col-sm-6 col-lg-3">
                                <div class="card info-card sales-card">
                                    <a href="{{ route('collectives.index') }}">
                                        <div class="card-body">
                                            <h5 class="card-title">Demandes <span>| collectives</span></h5>
                                            <div class="d-flex align-items-center">
                                                <div
                                                    class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                                    <i class="bi bi-file-earmark-text"></i>
                                                </div>
                                                <div class="ps-3">
                                                    <h6>
                                                        <span
                                                            class="text-primary">{{ number_format(count($collectives), 0, '', ' ') }}</span>
                                                    </h6>
                                                    <span class="text-muted small pt-2 ps-1">dont</span>
                                                    <span
                                                        class="text-success small pt-1 fw-bold">{{ number_format($pourcentage_femmes_collective, 2, ',', ' ') . '%' }}</span>
                                                    <span class="text-muted small pt-2 ps-1">femmes</span>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div> --}}

                            {{-- <div class="col-12 col-sm-6 col-lg-3">
                            <div class="card info-card sales-card">
                                <div class="filter">
                                    <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                            class="bi bi-three-dots"></i></a>
                                </div>
                                <a href="{{ route('showMasculin') }}">
                                    <div class="card-body">
                                        <h5 class="card-title">Individuelles <span>| hommes</span></h5>
                                        <div class="d-flex align-items-center">
                                            <div
                                                class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                                <i class="bi bi-file-earmark-text"></i>
                                            </div>
                                            <div class="ps-3">
                                                <h6>
                                                    <span
                                                        class="text-primary">{{ number_format($masculin, 0, '', ' ') }}</span>
                                                </h6>
                                                <span
                                                    class="text-success small pt-1 fw-bold">{{ number_format($pourcentage_hommes, 2, ',', ' ') . '%' }}</span>
                                                <span class="text-muted small pt-2 ps-1">Hommes</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div> --}}

                            {{-- <div class="col-12 col-sm-6 col-lg-3">
                            <div class="card info-card sales-card">
                                <div class="filter">
                                    <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                            class="bi bi-three-dots"></i></a>
                                </div>
                                <a href="{{ route('showFeminin') }}">
                                    <div class="card-body">
                                        <h5 class="card-title">Individuelles <span>| femmes</span></h5>
                                        <div class="d-flex align-items-center">
                                            <div
                                                class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                                <i class="bi bi-file-earmark-text"></i>
                                            </div>
                                            <div class="ps-3">
                                                <h6>
                                                    <span
                                                        class="text-primary">{{ number_format($feminin, 0, '', ' ') }}</span>
                                                </h6>
                                                <span
                                                    class="text-success small pt-1 fw-bold">{{ number_format($pourcentage_femmes, 2, ',', ' ') . '%' }}</span>
                                                <span class="text-muted small pt-2 ps-1">Femmes</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div> --}}
                            <div class="col-12 col-sm-6 col-lg-3">
                                <div class="card info-card sales-card">
                                    <a href="#">
                                        <div class="card-body">
                                            <h5 class="card-title">Agréments <span>| opérateurs</span></h5>
                                            <div class="d-flex align-items-center">
                                                <div
                                                    class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                                    <i class="bi bi-file-earmark-text"></i>
                                                </div>
                                                <div class="ps-3">
                                                    {{-- @php
                                                    $total = count($individuelles) + count($collectives);
                                                @endphp --}}

                                                    <h6>
                                                        <span
                                                            class="text-primary">{{ number_format($count_operateurs) }}</span>
                                                    </h6>
                                                    <span class="text-success small pt-1 fw-bold">agréés</span>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="section dashboard">
                <div class="row">

                    <!-- Left side columns -->
                    <div class="col-lg-12">
                        <div class="row">
                            <!-- Sales Card -->
                            @if ($user->hasAnyRole('super-admin', 'admin'))
                                <div class="col-12 col-sm-6 col-lg-3">
                                    <div class="card info-card sales-card">
                                        <a href="{{ route('user.index') }}">
                                            <div class="card-body">
                                                <h5 class="card-title">Utilisateurs <span>| Tous</span></h5>

                                                <div class="d-flex align-items-center">
                                                    <div
                                                        class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                                        <i class="bi bi-person-plus-fill"></i>
                                                    </div>
                                                    <div class="ps-3">
                                                        <h6>{{ number_format($total_user, 0, '', ' ') }}</h6>
                                                        <span
                                                            class="text-success small pt-1 fw-bold">{{ $email_verified_at . '%' }}</span>
                                                        <span class="text-muted small pt-2 ps-1">V</span>

                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div><!-- End Sales Card -->
                            @endif

                            @if ($user->hasAnyRole('super-admin', 'admin', 'courrier'))
                                <div class="col-12 col-sm-6 col-lg-3">
                                    <div class="card info-card sales-card">

                                        <a href="{{ route('arrives.index') }}">
                                            <div class="card-body">
                                                <h5 class="card-title">Courriers <span>| Arrivés</span></h5>

                                                <div class="d-flex align-items-center">
                                                    <div
                                                        class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                                        <i class="bi bi-envelope-open"></i>
                                                    </div>
                                                    <div class="ps-3">
                                                        <h6>{{ number_format($total_arrive, 0, '', ' ') }}</h6>
                                                        <span
                                                            class="text-success small pt-1 fw-bold">{{ number_format($pourcentage_arrive, 2, ',', ' ') . '%' }}</span>
                                                        {{-- <span class="text-muted small pt-2 ps-1">increase</span> --}}

                                                    </div>
                                                </div>
                                            </div>
                                        </a>

                                    </div>
                                </div><!-- End Sales Card -->
                                <div class="col-12 col-sm-6 col-lg-3">
                                    <div class="card info-card sales-card">

                                        <a href="{{ route('departs.index') }}">
                                            <div class="card-body">
                                                <h5 class="card-title">Courriers <span>| Départs</span></h5>

                                                <div class="d-flex align-items-center">
                                                    <div
                                                        class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                                        <i class="bi bi-envelope"></i>
                                                    </div>
                                                    <div class="ps-3">
                                                        <h6>{{ number_format($total_depart, 0, '', ' ') }}</h6>
                                                        <span
                                                            class="text-success small pt-1 fw-bold">{{ number_format($pourcentage_depart, 2, ',', ' ') . '%' }}</span>
                                                        {{-- <span class="text-muted small pt-2 ps-1">increase</span> --}}

                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div><!-- End Sales Card -->
                                <div class="col-12 col-sm-6 col-lg-3">
                                    <div class="card info-card sales-card">

                                        <a href="{{ route('internes.index') }}">
                                            <div class="card-body">
                                                <h5 class="card-title">Courriers <span>| Internes</span></h5>

                                                <div class="d-flex align-items-center">
                                                    <div
                                                        class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                                        <i class="bi bi-envelope"></i>
                                                    </div>
                                                    <div class="ps-3">
                                                        <h6>{{ number_format($total_interne, 0, '', ' ') }}</h6>
                                                        <span
                                                            class="text-success small pt-1 fw-bold">{{ number_format($pourcentage_interne, 2, ',', ' ') . '%' }}</span>
                                                        {{-- <span class="text-muted small pt-2 ps-1">increase</span> --}}

                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div><!-- End Sales Card -->
                            @endif

                        </div>
                    </div>
                </div>
            </section>
        @endcan
    @endif
@endsection
