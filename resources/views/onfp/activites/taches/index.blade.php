@extends('layout.user-layout')

@section('title', 'Tâches')

@section('space-work')

    <div class="container-fluid py-4">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
            </div>
        @endif

        {{-- ==========================================================
        EN-TÊTE
    =========================================================== --}}
        <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">

            <div>

                <div class="mb-2">

                    <a href="{{ route('onfp.activites.show', $activite) }}" class="btn btn-sm btn-light border">
                        <i class="bi bi-arrow-left me-1"></i>
                        Retour à l'activité
                    </a>

                </div>

                <h2 class="fw-bold mb-1">
                    <i class="fas fa-check-square text-primary me-2"></i>
                    Gestion des tâches
                </h2>

                <div class="text-muted">

                    Activité :
                    <strong>
                        {{ $activite->reference ?? '-' }}
                    </strong>

                    @if ($activite->titre)
                        - {{ $activite->titre }}
                    @endif

                </div>

                @if ($sousActivite)

                    <div class="mt-2">

                        <span class="badge bg-info-subtle text-info border">
                            <i class="fas fa-layer-group me-1"></i>

                            Sous-activité :
                            {{ $sousActivite->reference ?? '-' }}

                            @if ($sousActivite->titre)
                                - {{ $sousActivite->titre }}
                            @endif
                        </span>

                    </div>

                @endif

            </div>


            <a href="{{ route($routePrefix . '.create', $routeParams) }}" class="btn btn-sm btn-primary">
                <i class="fas fa-plus me-1"></i>
                Nouvelle tâche
            </a>

        </div>


        {{-- ==========================================================
        INDICATEURS
    =========================================================== --}}
        <div class="row g-3 mb-4">

            <div class="col-6 col-xl-2">
                <div class="card border-0 shadow-sm h-100 stat-card">
                    <div class="card-body">
                        <div class="text-muted small">Total</div>
                        <div class="fs-3 fw-bold">{{ $totalTaches }}</div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-xl-2">
                <div class="card border-0 shadow-sm h-100 stat-card">
                    <div class="card-body">
                        <div class="text-muted small">À faire</div>
                        <div class="fs-3 fw-bold text-secondary">{{ $aFaire }}</div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-xl-2">
                <div class="card border-0 shadow-sm h-100 stat-card">
                    <div class="card-body">
                        <div class="text-muted small">En cours</div>
                        <div class="fs-3 fw-bold text-primary">{{ $enCours }}</div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-xl-2">
                <div class="card border-0 shadow-sm h-100 stat-card">
                    <div class="card-body">
                        <div class="text-muted small">Terminées</div>
                        <div class="fs-3 fw-bold text-success">{{ $terminees }}</div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-xl-2">
                <div class="card border-0 shadow-sm h-100 stat-card">
                    <div class="card-body">
                        <div class="text-muted small">En retard</div>
                        <div class="fs-3 fw-bold text-danger">{{ $enRetard }}</div>
                    </div>
                </div>
            </div>

        </div>


        {{-- ==========================================================
        LISTE
    =========================================================== --}}
        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white border-bottom py-3">

                <div class="d-flex justify-content-between align-items-center">

                    <div>
                        <h5 class="mb-0 fw-bold">
                            Liste des tâches
                        </h5>

                        <small class="text-muted">
                            Suivi opérationnel des actions à réaliser
                        </small>
                    </div>

                </div>

            </div>


            {{-- <div class="card-body p-0">

                @if ($taches->count())

                    <div class="table-responsive d-none d-lg-block">

                        <table class="table table-hover align-middle mb-0">

                            <thead class="table-light">

                                <tr>

                                    <th class="ps-4">
                                        Tâche
                                    </th>

                                    <th>
                                        Statut
                                    </th>

                                    <th>
                                        Priorité
                                    </th>

                                    <th style="width: 180px;">
                                        Progression
                                    </th>

                                    <th>
                                        Échéance
                                    </th>

                                    <th class="text-end pe-4">
                                        Actions
                                    </th>

                                </tr>

                            </thead>

                            <tbody>

                                @foreach ($taches as $tache)
                                    <tr>

                                        <td class="ps-4">

                                            <div class="fw-semibold">

                                                <a href="{{ route($routePrefix . '.show', $tache->route_params) }}"
                                                    class="text-decoration-none text-dark">
                                                    {{ $tache->titre }}
                                                </a>

                                            </div>

                                            @if ($tache->reference)
                                                <small class="text-muted">
                                                    {{ $tache->reference }}
                                                </small>
                                            @endif

                                        </td>


                                        <td>

                                            <span
                                                class="badge {{ $statusClasses[$tache->statut] ?? 'bg-light text-dark' }} px-2 py-1">

                                                {{ $statusLabels[$tache->statut] ?? ucfirst($tache->statut) }}

                                            </span>

                                        </td>


                                        <td>

                                            <span
                                                class="fw-semibold {{ $priorityClasses[$tache->priorite] ?? 'text-secondary' }}">

                                                <i class="fas fa-flag me-1"></i>

                                                {{ $priorityLabels[$tache->priorite] ?? ucfirst($tache->priorite) }}

                                            </span>

                                        </td>


                                        <td>

                                            <div class="d-flex justify-content-between small mb-1">

                                                <span>
                                                    Progression
                                                </span>

                                                <strong>
                                                    {{ $tache->progression_value }}%
                                                </strong>

                                            </div>

                                            <div class="progress" style="height: 7px;">
                                                <div class="progress-bar"
                                                    style="width: {{ $tache->progression_value }}%;"></div>
                                            </div>

                                        </td>


                                        <td>

                                        <td>

                                            @if ($tache->date_echeance)
                                                <span class="{{ $tache->en_retard ? 'text-danger fw-bold' : '' }}">

                                                    <i class="far fa-calendar-alt me-1"></i>

                                                    {{ $tache->date_echeance->format('d/m/Y') }}

                                                </span>

                                                @if ($tache->en_retard)
                                                    <div>
                                                        <small class="text-danger">
                                                            En retard
                                                        </small>
                                                    </div>
                                                @endif
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif

                                        </td>

                                        </td>


                                        <td class="text-end pe-4">

                                            <div class="btn-group">

                                                <a href="{{ route($routePrefix . '.show', $tache->route_params) }}"
                                                    class="btn btn-sm btn-outline-primary" title="Voir">
                                                    <i class="bi bi-eye"></i>
                                                </a>

                                                <a href="{{ route($routePrefix . '.edit', $tache->route_params) }}"
                                                    class="btn btn-sm btn-outline-secondary" title="Modifier">
                                                    <i class="bi bi-pencil"></i>
                                                </a>

                                            </div>

                                        </td>

                                    </tr>
                                @endforeach

                            </tbody>

                        </table>

                    </div>


                    <div class="d-lg-none p-3">

                        @foreach ($taches as $tache)
                            @php

                                $statusLabels = [
                                    'a_faire' => 'À faire',
                                    'en_cours' => 'En cours',
                                    'suspendue' => 'Suspendue',
                                    'terminee' => 'Terminée',
                                    'annulee' => 'Annulée',
                                ];

                                $statusClasses = [
                                    'a_faire' => 'bg-secondary-subtle text-secondary',
                                    'en_cours' => 'bg-primary-subtle text-primary',
                                    'suspendue' => 'bg-warning-subtle text-warning',
                                    'terminee' => 'bg-success-subtle text-success',
                                    'annulee' => 'bg-danger-subtle text-danger',
                                ];

                                $tache->progression_value = max(0, min(100, (int) ($tache->progression ?? 0)));

                                $retard =
                                    $tache->date_echeance &&
                                    $tache->date_echeance->isPast() &&
                                    !in_array($tache->statut, ['terminee', 'annulee']);

                                $tache->route_params = array_merge($routeParams, ['tache' => $tache]);

                            @endphp


                            <div class="task-mobile-card border rounded-3 p-3 mb-3">

                                <div class="d-flex justify-content-between gap-2">

                                    <div>

                                        <a href="{{ route($routePrefix . '.show', $tache->route_params) }}"
                                            class="fw-bold text-decoration-none text-dark">
                                            {{ $tache->titre }}
                                        </a>

                                        @if ($tache->reference)
                                            <div class="small text-muted">
                                                {{ $tache->reference }}
                                            </div>
                                        @endif

                                    </div>

                                    <span class="badge {{ $statusClasses[$tache->statut] ?? 'bg-light text-dark' }} h-100">
                                        {{ $statusLabels[$tache->statut] ?? ucfirst($tache->statut) }}
                                    </span>

                                </div>


                                <div class="mt-3">

                                    <div class="d-flex justify-content-between small mb-1">

                                        <span class="text-muted">
                                            Progression
                                        </span>

                                        <strong>
                                            {{ $tache->progression_value }}%
                                        </strong>

                                    </div>

                                    <div class="progress" style="height: 7px;">
                                        <div class="progress-bar" style="width: {{ $tache->progression_value }}%;"></div>
                                    </div>

                                </div>


                                <div class="row g-2 mt-2 small">

                                    <div class="col-6">

                                        <span class="text-muted">
                                            <i class="fas fa-flag me-1"></i>
                                            Priorité
                                        </span>

                                        <div class="fw-semibold">
                                            {{ ucfirst($tache->priorite ?? 'normale') }}
                                        </div>

                                    </div>

                                    <div class="col-6">

                                        <span class="text-muted">
                                            <i class="far fa-calendar-alt me-1"></i>
                                            Échéance
                                        </span>

                                        <div class="{{ $retard ? 'text-danger fw-bold' : 'fw-semibold' }}">

                                            {{ $tache->date_echeance ? $tache->date_echeance->format('d/m/Y') : '-' }}

                                        </div>

                                    </div>

                                </div>


                                <div class="d-flex gap-2 mt-3">

                                    <a href="{{ route($routePrefix . '.show', $tache->route_params) }}"
                                        class="btn btn-sm btn-light border flex-fill">
                                        <i class="fas fa-eye me-1"></i>
                                        Voir
                                    </a>

                                    <a href="{{ route($routePrefix . '.edit', $tache->route_params) }}"
                                        class="btn btn-sm btn-light border flex-fill">
                                        <i class="fas fa-edit me-1"></i>
                                        Modifier
                                    </a>

                                </div>

                            </div>
                        @endforeach

                    </div>
                @else
                    <div class="text-center py-5 px-3">

                        <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center mb-3"
                            style="width: 70px; height: 70px;">
                            <i class="fas fa-check-square fa-2x text-muted"></i>
                        </div>

                        <h5 class="fw-bold">
                            Aucune tâche
                        </h5>

                        <p class="text-muted mb-4">
                            Aucune tâche n'est encore enregistrée
                            pour ce périmètre.
                        </p>

                        <a href="{{ route($routePrefix . '.create', $routeParams) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus me-1"></i>
                            Créer la première tâche
                        </a>

                    </div>

                @endif

            </div> --}}

            <div class="card-body p-0">

                @if ($taches->count())

                    {{-- ==================================================
            VERSION DESKTOP
        =================================================== --}}
                    <div class="table-responsive d-none d-lg-block">

                        <table class="table table-hover align-middle mb-0">

                            <thead class="table-light">
                                <tr>

                                    <th class="ps-4">
                                        Tâche
                                    </th>

                                    <th>
                                        Statut
                                    </th>

                                    <th>
                                        Priorité
                                    </th>

                                    <th style="width: 180px;">
                                        Progression
                                    </th>

                                    <th>
                                        Échéance
                                    </th>

                                    <th class="text-end pe-4">
                                        Actions
                                    </th>

                                </tr>
                            </thead>

                            <tbody>

                                @foreach ($taches as $tache)
                                    <tr>

                                        {{-- ==========================
                                TÂCHE
                            =========================== --}}
                                        <td class="ps-4">

                                            <div class="fw-semibold">

                                                <a href="{{ route($routePrefix . '.show', $tache->route_params) }}"
                                                    class="text-decoration-none text-dark">

                                                    {{ $tache->titre }}

                                                </a>

                                            </div>

                                            @if ($tache->reference)
                                                <small class="text-muted">
                                                    {{ $tache->reference }}
                                                </small>
                                            @endif

                                        </td>


                                        {{-- ==========================
                                STATUT
                            =========================== --}}
                                        <td>

                                            <span class="badge {{ $tache->status_class }} px-2 py-1">

                                                {{ $tache->status_label }}

                                            </span>

                                        </td>


                                        {{-- ==========================
                                PRIORITÉ
                            =========================== --}}
                                        <td>

                                            <span class="fw-semibold {{ $tache->priority_class }}">

                                                <i class="fas fa-flag me-1"></i>

                                                {{ $tache->priority_label }}

                                            </span>

                                        </td>


                                        {{-- ==========================
                                PROGRESSION
                            =========================== --}}
                                        <td>

                                            <div class="d-flex justify-content-between small mb-1">

                                                <span>
                                                    Progression
                                                </span>

                                                <strong>
                                                    {{ $tache->progression_value }}%
                                                </strong>

                                            </div>

                                            <div class="progress" style="height: 7px;">

                                                <div class="progress-bar" role="progressbar"
                                                    style="width: {{ $tache->progression_value }}%;"
                                                    aria-valuenow="{{ $tache->progression_value }}" aria-valuemin="0"
                                                    aria-valuemax="100">
                                                </div>

                                            </div>

                                        </td>


                                        {{-- ==========================
                                ÉCHÉANCE
                            =========================== --}}
                                        <td>

                                            @if ($tache->date_echeance)
                                                <span class="{{ $tache->en_retard ? 'text-danger fw-bold' : '' }}">

                                                    <i class="far fa-calendar-alt me-1"></i>

                                                    {{ $tache->date_echeance->format('d/m/Y') }}

                                                </span>

                                                @if ($tache->en_retard)
                                                    <div>
                                                        <small class="text-danger">
                                                            En retard
                                                        </small>
                                                    </div>
                                                @endif
                                            @else
                                                <span class="text-muted">
                                                    -
                                                </span>
                                            @endif

                                        </td>


                                        {{-- ==========================
                                ACTIONS
                            =========================== --}}
                                        <td class="text-end pe-4">

                                            <div class="btn-group">

                                                <a href="{{ route($routePrefix . '.show', $tache->route_params) }}"
                                                    class="btn btn-sm btn-outline-primary" title="Voir">

                                                    <i class="bi bi-eye"></i>

                                                </a>

                                                <a href="{{ route($routePrefix . '.edit', $tache->route_params) }}"
                                                    class="btn btn-sm btn-outline-secondary" title="Modifier">

                                                    <i class="bi bi-pencil"></i>

                                                </a>

                                            </div>

                                        </td>

                                    </tr>
                                @endforeach

                            </tbody>

                        </table>

                    </div>


                    {{-- ==================================================
            VERSION MOBILE
        =================================================== --}}
                    <div class="d-lg-none p-3">

                        @foreach ($taches as $tache)
                            <div class="task-mobile-card border rounded-3 p-3 mb-3">

                                {{-- ==========================
                        TITRE + STATUT
                    =========================== --}}
                                <div class="d-flex justify-content-between gap-2">

                                    <div>

                                        <a href="{{ route($routePrefix . '.show', $tache->route_params) }}"
                                            class="fw-bold text-decoration-none text-dark">

                                            {{ $tache->titre }}

                                        </a>

                                        @if ($tache->reference)
                                            <div class="small text-muted">
                                                {{ $tache->reference }}
                                            </div>
                                        @endif

                                    </div>


                                    <span class="badge {{ $tache->status_class }} h-100">

                                        {{ $tache->status_label }}

                                    </span>

                                </div>


                                {{-- ==========================
                        PROGRESSION
                    =========================== --}}
                                <div class="mt-3">

                                    <div class="d-flex justify-content-between small mb-1">

                                        <span class="text-muted">
                                            Progression
                                        </span>

                                        <strong>
                                            {{ $tache->progression_value }}%
                                        </strong>

                                    </div>

                                    <div class="progress" style="height: 7px;">

                                        <div class="progress-bar" role="progressbar"
                                            style="width: {{ $tache->progression_value }}%;"
                                            aria-valuenow="{{ $tache->progression_value }}" aria-valuemin="0"
                                            aria-valuemax="100">
                                        </div>

                                    </div>

                                </div>


                                {{-- ==========================
                        PRIORITÉ + ÉCHÉANCE
                    =========================== --}}
                                <div class="row g-2 mt-2 small">

                                    <div class="col-6">

                                        <span class="text-muted">

                                            <i class="fas fa-flag me-1"></i>

                                            Priorité

                                        </span>

                                        <div class="fw-semibold {{ $tache->priority_class }}">

                                            {{ $tache->priority_label }}

                                        </div>

                                    </div>


                                    <div class="col-6">

                                        <span class="text-muted">

                                            <i class="far fa-calendar-alt me-1"></i>

                                            Échéance

                                        </span>

                                        <div class="{{ $tache->en_retard ? 'text-danger fw-bold' : 'fw-semibold' }}">

                                            {{ $tache->date_echeance ? $tache->date_echeance->format('d/m/Y') : '-' }}

                                        </div>

                                        @if ($tache->en_retard)
                                            <small class="text-danger">
                                                En retard
                                            </small>
                                        @endif

                                    </div>

                                </div>


                                {{-- ==========================
                        ACTIONS
                    =========================== --}}
                                <div class="d-flex gap-2 mt-3">

                                    <a href="{{ route($routePrefix . '.show', $tache->route_params) }}"
                                        class="btn btn-sm btn-light border flex-fill">

                                        <i class="fas fa-eye me-1"></i>

                                        Voir

                                    </a>

                                    <a href="{{ route($routePrefix . '.edit', $tache->route_params) }}"
                                        class="btn btn-sm btn-light border flex-fill">

                                        <i class="fas fa-edit me-1"></i>

                                        Modifier

                                    </a>

                                </div>

                            </div>
                        @endforeach

                    </div>
                @else
                    {{-- ==================================================
            AUCUNE TÂCHE
        =================================================== --}}
                    <div class="text-center py-5 px-3">

                        <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center mb-3"
                            style="width: 70px; height: 70px;">

                            <i class="fas fa-check-square fa-2x text-muted"></i>

                        </div>

                        <h5 class="fw-bold">
                            Aucune tâche
                        </h5>

                        <p class="text-muted mb-4">
                            Aucune tâche n'est encore enregistrée
                            pour ce périmètre.
                        </p>

                        <a href="{{ route($routePrefix . '.create', $routeParams) }}" class="btn btn-sm btn-primary">

                            <i class="fas fa-plus me-1"></i>

                            Créer la première tâche

                        </a>

                    </div>

                @endif

            </div>


            {{-- Pagination --}}
            @if (method_exists($taches, 'links'))
                <div class="card-footer bg-white border-top">
                    {{ $taches->links() }}
                </div>
            @endif

        </div>

    </div>


    @push('styles')
        <style>
            .stat-card {
                border-radius: 14px;
                transition: transform .15s ease, box-shadow .15s ease;
            }

            .stat-card:hover {
                transform: translateY(-2px);
            }

            .task-mobile-card {
                background: #fff;
            }

            .task-mobile-card:last-child {
                margin-bottom: 0 !important;
            }

            .progress {
                border-radius: 20px;
            }

            .table> :not(caption)>*>* {
                padding-top: .9rem;
                padding-bottom: .9rem;
            }
        </style>
    @endpush

@endsection
