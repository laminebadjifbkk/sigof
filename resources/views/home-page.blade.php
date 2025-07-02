@extends('layout.user-layout')
@section('space-work')
    @php
        $user = auth()->user();
    @endphp
    @if ($user->hasAnyRole(['super-admin', 'admin', 'DIOF', 'DEC', 'Ingenieur', 'Employe']))
        <section class="section dashboard">
            <div class="row">
                <!-- Left side columns -->
                {{-- <div class="col-lg-12">
                <div class="row">
                    <h1>{{ $chart1->options['chart_title'] }}</h1>
                    {!! $chart1->renderHtml() !!}
                </div>
            </div> --}}
                {{-- <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $annee_lettre }}</h5>

                        <canvas id="barChart" style="max-height: 400px;"></canvas>
                        <script>
                            document.addEventListener("DOMContentLoaded", () => {
                                new Chart(document.querySelector('#barChart'), {
                                    type: 'bar',
                                    data: {
                                        labels: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août',
                                            'Septembre', 'Octobre', 'Novembre', 'Décembre'
                                        ],
                                        datasets: [{
                                            label: 'Diagramme à barres',
                                            data: [{{ $janvier }}, {{ $fevrier }}, {{ $mars }},
                                                {{ $avril }}, {{ $mai }}, {{ $juin }},
                                                {{ $juillet }}, {{ $aout }}, {{ $septembre }},
                                                {{ $octobre }}, {{ $novembre }}, {{ $decembre }}
                                            ],
                                            backgroundColor: [
                                                'rgba(255, 99, 132, 0.2)',
                                                'rgba(255, 159, 64, 0.2)',
                                                'rgba(255, 205, 86, 0.2)',
                                                'rgba(75, 192, 192, 0.2)',
                                                'rgba(54, 162, 235, 0.2)',
                                                'rgba(153, 102, 255, 0.2)',
                                                'rgba(201, 203, 207, 0.2)',
                                                'rgba(255, 99, 132, 0.2)',
                                                'rgba(255, 159, 64, 0.2)',
                                                'rgba(255, 205, 86, 0.2)',
                                                'rgba(75, 192, 192, 0.2)',
                                                'rgba(54, 162, 235, 0.2)',
                                            ],
                                            borderColor: [
                                                'rgb(255, 99, 132)',
                                                'rgb(255, 159, 64)',
                                                'rgb(255, 205, 86)',
                                                'rgb(75, 192, 192)',
                                                'rgb(54, 162, 235)',
                                                'rgb(153, 102, 255)',
                                                'rgb(201, 203, 207)',
                                                'rgb(255, 99, 132)',
                                                'rgb(255, 159, 64)',
                                                'rgb(255, 205, 86)',
                                                'rgb(75, 192, 192)',
                                                'rgb(54, 162, 235)',
                                            ],
                                            borderWidth: 1
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
            </div> --}}
                <div class="col-lg-12">
                    @if ($formations->isNotEmpty())
                        <div class="card">
                            {{-- <div class="card-body">
                                <h5 class="card-title">Formations en cours</h5>
                                @foreach ($formations as $formation)
                                    @if (!empty($formation->module->name))
                                        @if (!empty($formation->duree_formation) && count($formation->emargements) > 0)
                                            @php
                                                $progress = round(
                                                    (count($formation->emargements) / $formation->duree_formation) *
                                                        100,
                                                );
                                                // Déterminer la couleur en fonction du pourcentage
                                                if ($progress <= 20) {
                                                    $color = 'bg-danger'; // Rouge
                                                } elseif ($progress <= 40) {
                                                    $color = 'bg-warning'; // Jaune
                                                } elseif ($progress <= 60) {
                                                    $color = 'bg-info'; // Bleu clair
                                                } elseif ($progress <= 80) {
                                                    $color = 'bg-primary'; // Bleu foncé
                                                } else {
                                                    $color = 'bg-success'; // Vert
                                                }
                                            @endphp
                                            <!-- Nom de la formation -->
                                            <p class="mt-3"><strong> {{ $formation->module->name }}</strong></p>
                                            <p class="mt-3"><strong>
                                                    {{ $formation?->operateur?->user?->username }}</strong></p>
                                            <p class="mt-3"><strong> {{ $formation?->departement?->nom }}</strong></p>
                                            <div class="progress mt-0">
                                                <div class="progress-bar progress-bar-striped progress-bar-animated {{ $color }}"
                                                    role="progressbar" style="width: {{ $progress }}%"
                                                    aria-valuenow="{{ $progress }}" aria-valuemin="0"
                                                    aria-valuemax="100">
                                                    @if ($progress == 100)
                                                        terminée
                                                    @else
                                                        {{ $progress }}%
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    @elseif (!empty($formation->collectivemodule->module) && count($formation->emargementcollectives) > 0)
                                        @php
                                            $progress = round(
                                                (count($formation->emargementcollectives) /
                                                    $formation->duree_formation) *
                                                    100,
                                            );
                                            // Déterminer la couleur en fonction du pourcentage
                                            if ($progress <= 20) {
                                                $color = 'bg-danger'; // Rouge
                                            } elseif ($progress <= 40) {
                                                $color = 'bg-warning'; // Jaune
                                            } elseif ($progress <= 60) {
                                                $color = 'bg-info'; // Bleu clair
                                            } elseif ($progress <= 80) {
                                                $color = 'bg-primary'; // Bleu foncé
                                            } else {
                                                $color = 'bg-success'; // Vert
                                            }
                                        @endphp
                                        <!-- Nom de la formation -->
                                        <p class="mt-3"><strong> {{ $formation->collectivemodule->module }}</strong></p>
                                        <div class="progress mt-0">
                                            <div class="progress-bar progress-bar-striped progress-bar-animated {{ $color }}"
                                                role="progressbar" style="width: {{ $progress }}%"
                                                aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100">
                                                @if ($progress == 100)
                                                    terminée
                                                @else
                                                    {{ $progress }}%
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                    @endif
                                @endforeach
                            </div> --}}
                            <div class="card-body">
                                <h5 class="card-title">Formations en cours</h5>
                                @foreach ($formations as $formation)
                                    @php
                                        // Définir les variables communes
                                        $isIndividuelle =
                                            !empty($formation->module?->name) &&
                                            !empty($formation->duree_formation) &&
                                            $formation->emargements->count() > 0;
                                        $isCollective =
                                            !empty($formation->collectivemodule?->module) &&
                                            !empty($formation->duree_formation) &&
                                            $formation->emargementcollectives->count() > 0;

                                        $progress = null;
                                        $color = '';

                                        if ($isIndividuelle) {
                                            $progress = round(
                                                ($formation->emargements->count() / $formation->duree_formation) * 100,
                                            );
                                        } elseif ($isCollective) {
                                            $progress = round(
                                                ($formation->emargementcollectives->count() /
                                                    $formation->duree_formation) *
                                                    100,
                                            );
                                        }

                                        // Déterminer la couleur
                                        if (!is_null($progress)) {
                                            $color = match (true) {
                                                $progress <= 20 => 'bg-danger',
                                                $progress <= 40 => 'bg-warning',
                                                $progress <= 60 => 'bg-info',
                                                $progress <= 80 => 'bg-primary',
                                                default => 'bg-success',
                                            };
                                        }
                                    @endphp
                                    <div class="border rounded p-3 mb-3 shadow-sm bg-white">
                                        <h6 class="text-primary mb-3">
                                            <i class="bi bi-journal-code me-1"></i>
                                            Module :
                                            <a
                                                href="{{ route('formations.show', $formation) }}">{{ $isIndividuelle ? $formation?->module?->name : $formation?->collectivemodule?->module }}</a>
                                        </h6>
                                        <div class="row text-sm">
                                            <div class="col-md-8 mb-2">
                                                <i class="bi bi-person-circle me-1"></i>
                                                <strong>Opérateur :</strong>
                                                {{ $formation?->operateur?->user?->operateur . ' (' . $formation?->operateur?->user?->username . ')' }}
                                            </div>
                                            <div class="col-md-4 mb-2">
                                                <i class="bi bi-person-workspace me-1"></i>
                                                <strong>Ingénieur :</strong> {{ $formation?->ingenieur?->name ?? 'N/A' }}
                                            </div>
                                            <div class="col-md-4 mb-2">
                                                <i class="bi bi-calendar-event me-1"></i>
                                                <strong>Date début :</strong>
                                                <span class="{{ is_null($formation?->date_debut) ? 'text-danger' : '' }}">
                                                    {{ $formation?->date_debut?->format('d/m/Y') ?? 'Non définie' }}
                                                </span>
                                            </div>

                                            <div class="col-md-4 mb-2">
                                                <i class="bi bi-calendar-check me-1"></i>
                                                <strong>Date fin :</strong>
                                                <span class="{{ is_null($formation?->date_fin) ? 'text-danger' : '' }}">
                                                    {{ $formation?->date_fin?->format('d/m/Y') ?? 'Non définie' }}
                                                </span>
                                            </div>

                                            <div class="col-md-4 mb-2">
                                                <i class="bi bi-calendar-check me-1"></i>
                                                <strong>Date évaluation :</strong>
                                                <span class="{{ is_null($formation?->date_pv) ? 'text-danger' : '' }}">
                                                    {{ $formation?->date_pv?->format('d/m/Y') ?? 'Non définie' }}
                                                </span>
                                            </div>

                                            <div class="col-md-8 mb-2">
                                                <i class="bi bi-geo-alt-fill me-1"></i>
                                                <strong>Lieu :</strong> {{ $formation?->lieu ?? 'N/A' }}
                                            </div>
                                            <div class="col-md-4 mb-2">
                                                <i class="bi bi-clock me-1"></i>
                                                <strong>Durée :</strong> {{ $formation?->duree_formation ?? '-' }}
                                                @if ($formation?->duree_formation === 1)
                                                    jour
                                                @elseif ($formation?->duree_formation > 1)
                                                    jours
                                                @endif
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
                            </div>
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
                                    <div class="filter">
                                        <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                                class="bi bi-three-dots"></i></a>
                                    </div>
                                    <a href="#">
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
                            <div class="col-12 col-sm-6 col-lg-3">
                                <div class="card info-card sales-card">
                                    <div class="filter">
                                        <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                                class="bi bi-three-dots"></i></a>
                                    </div>
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
                                                    {{-- <span class="text-muted small pt-2 ps-1">dont</span> --}}
                                                    <span
                                                        class="text-success small pt-1 fw-bold">{{ number_format($pourcentage_femmes, 2, ',', ' ') . '%' }}</span>
                                                    <span class="text-muted small pt-2 ps-1">f</span>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>

                            {{-- Demandes collectives --}}
                            <div class="col-12 col-sm-6 col-lg-3">
                                <div class="card info-card sales-card">
                                    <div class="filter">
                                        <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                                class="bi bi-three-dots"></i></a>
                                    </div>
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
                                                    {{-- <span class="text-muted small pt-2 ps-1">dont</span> --}}
                                                    <span
                                                        class="text-success small pt-1 fw-bold">{{ number_format($pourcentage_femmes_collective, 2, ',', ' ') . '%' }}</span>
                                                    <span class="text-muted small pt-2 ps-1">f</span>
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
                                    <div class="filter">
                                        <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                                class="bi bi-three-dots"></i></a>
                                    </div>
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
