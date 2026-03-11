@extends('layout.user-layout')
@section('title', 'ONFP - Activités quotidiennes')

@section('space-work')
    <section class="section">
        <div class="container">

            <div class="row mb-4">
                <!-- Total activités -->
                <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                    <div class="card shadow-sm text-center p-2" style="min-height:140px; border-radius:10px;">
                        <h6 class="card-title mb-2 text-truncate" title="Total activités">Activités total</h6>
                        <div class="d-flex flex-column align-items-center justify-content-center mb-2">
                            <div class="rounded-circle bg-primary text-white d-flex justify-content-center align-items-center mb-1"
                                style="width:36px; height:36px; font-size:1rem;">
                                <i class="bi bi-flag"></i>
                            </div>
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
                            <div class="rounded-circle bg-success text-white d-flex justify-content-center align-items-center mb-1"
                                style="width:36px; height:36px; font-size:1rem;">
                                <i class="bi bi-calendar"></i>
                            </div>
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
                                <div class="rounded-circle bg-primary text-white d-flex justify-content-center align-items-center mb-1"
                                    style="width:36px; height:36px; font-size:1rem;">
                                    <i class="bi bi-flag"></i>
                                </div>
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

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="mb-0">Activités quotidiennes</h1>
                <a href="{{ route('activites-quotidiennes.create') }}" class="btn btn-success btn-sm">
                    <i class="bi bi-plus-circle"></i> Ajouter une activité
                </a>
            </div>
            <!-- Tableau des activités -->
            <div class="card shadow-sm">
                <div class="card-body table-responsive">
                    <table class="table table-sm table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Titre</th>
                                <th>Agent</th>
                                <th>Date</th>
                                <th>Priorité</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($activites as $activite)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $activite->titre }}</td>
                                    <td>{{ $activite->user->firstname ?? '' }} {{ $activite->user->name ?? '' }}</td>
                                    <td>{{ $activite->date_activite->format('d/m/Y') }}</td>
                                    <td>
                                        <span class="{{ $activite->priorite }}">{{ $activite->priorite }}</span>
                                    </td>
                                    <td>
                                        <span class="{{ $activite->statut }}">
                                            {{ $labels[$activite->statut] ?? $activite->statut }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('activites-quotidiennes.show', $activite->id) }}"
                                            class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-eye"></i> Voir
                                        </a>
                                        <a href="{{ route('activites-quotidiennes.edit', $activite->id) }}"
                                            class="btn btn-outline-secondary btn-sm">
                                            <i class="bi bi-pencil"></i> Modifier
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">Aucune activité trouvée</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </section>
@endsection
