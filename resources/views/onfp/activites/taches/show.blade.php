@extends('layout.user-layout')

@section('title', $tache->reference ?? 'Détail de la tâche')

@section('space-work')

    @php

        $sousActivite = $sousActivite ?? ($tache->sousActivite ?? null);

        $isNested = $sousActivite !== null;

        $routePrefix = $isNested ? 'onfp.activites.sous-activites.taches' : 'onfp.activites.taches';

        $routeParams = $isNested
            ? [
                'activite' => $activite,
                'sousActivite' => $sousActivite,
            ]
            : [
                'activite' => $activite,
            ];

        $routeParamsWithTask = array_merge($routeParams, ['tache' => $tache]);

        /*
    |--------------------------------------------------------------------------
    | Statut
    |--------------------------------------------------------------------------
    */
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

        /*
    |--------------------------------------------------------------------------
    | Priorité
    |--------------------------------------------------------------------------
    */
        $priorityLabels = [
            'basse' => 'Basse',
            'normale' => 'Normale',
            'haute' => 'Haute',
            'urgente' => 'Urgente',
        ];

        $priorityClasses = [
            'basse' => 'text-secondary',
            'normale' => 'text-primary',
            'haute' => 'text-warning',
            'urgente' => 'text-danger',
        ];

        $progression = max(0, min(100, (int) ($tache->progression ?? 0)));

        /*
    |--------------------------------------------------------------------------
    | Retard
    |--------------------------------------------------------------------------
    */
        $retard =
            $tache->date_echeance &&
            $tache->date_echeance->isPast() &&
            !in_array($tache->statut, ['terminee', 'annulee']);

    @endphp


    <div class="container-fluid py-4">

        {{-- ==========================================================
        EN-TÊTE
    =========================================================== --}}
        <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">

            <div>

                <div class="mb-2">
                    <a href="{{ route($routePrefix . '.index', $routeParams) }}" class="text-decoration-none text-muted">
                        <i class="bi bi-arrow-left me-1"></i>
                        Retour aux tâches
                    </a>
                </div>


                <div class="d-flex flex-wrap align-items-center gap-2 mb-2">

                    <span class="badge {{ $statusClasses[$tache->statut] ?? 'bg-light text-dark' }} px-3 py-2">

                        {{ $statusLabels[$tache->statut] ?? ucfirst($tache->statut) }}

                    </span>


                    <span class="badge bg-light text-dark border px-3 py-2">

                        <i class="fas fa-flag {{ $priorityClasses[$tache->priorite] ?? 'text-secondary' }} me-1"></i>

                        {{ $priorityLabels[$tache->priorite] ?? ucfirst($tache->priorite) }}

                    </span>


                    @if ($retard)
                        <span class="badge bg-danger-subtle text-danger px-3 py-2">

                            <i class="fas fa-exclamation-triangle me-1"></i>

                            En retard

                        </span>
                    @endif

                </div>


                <h2 class="fw-bold mb-1">
                    {{ $tache->titre }}
                </h2>


                @if ($tache->reference)
                    <div class="text-muted">

                        <i class="fas fa-hashtag me-1"></i>

                        {{ $tache->reference }}

                    </div>
                @endif

            </div>


            <div class="d-flex flex-wrap gap-2">

                <a href="{{ route($routePrefix . '.edit', $routeParamsWithTask) }}" class="btn btn-sm btn-warning">
                    <i class="fas fa-edit me-1"></i>
                    Modifier
                </a>

            </div>

        </div>


        {{-- ==========================================================
        CONTEXTE
    =========================================================== --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-6">

                        <div class="text-muted small mb-1">
                            Activité
                        </div>

                        <a href="{{ route('onfp.activites.show', $activite) }}" class="text-decoration-none fw-semibold">

                            <i class="fas fa-tasks text-primary me-1"></i>

                            {{ $activite->reference ?? '-' }}

                            @if ($activite->titre)
                                - {{ $activite->titre }}
                            @endif

                        </a>

                    </div>


                    <div class="col-md-6">

                        <div class="text-muted small mb-1">
                            Sous-activité
                        </div>

                        @if ($sousActivite)

                            <span class="fw-semibold">

                                <i class="fas fa-layer-group text-info me-1"></i>

                                {{ $sousActivite->reference ?? '-' }}

                                @if ($sousActivite->titre)
                                    - {{ $sousActivite->titre }}
                                @endif

                            </span>
                        @else
                            <span class="text-muted">
                                Tâche directement rattachée à l'activité
                            </span>

                        @endif

                    </div>

                </div>

            </div>

        </div>


        <div class="row g-4">

            {{-- ======================================================
            COLONNE PRINCIPALE
        ======================================================= --}}
            <div class="col-lg-8">


                {{-- ==================================================
                DESCRIPTION
            =================================================== --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white border-bottom">

                        <h5 class="mb-0 fw-bold">

                            <i class="fas fa-align-left text-primary me-2"></i>

                            Description

                        </h5>

                    </div>


                    <div class="card-body">

                        @if ($tache->description)
                            <div class="task-description">
                                {!! nl2br(e($tache->description)) !!}
                            </div>
                        @else
                            <p class="text-muted mb-0">
                                Aucune description renseignée.
                            </p>
                        @endif

                    </div>

                </div>


                {{-- ==================================================
                PROGRESSION
            =================================================== --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white border-bottom">

                        <h5 class="mb-0 fw-bold">

                            <i class="fas fa-chart-line text-success me-2"></i>

                            Progression

                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center mb-2">

                            <span class="text-muted">
                                Niveau d'avancement
                            </span>

                            <strong class="fs-5">
                                {{ $progression }} %
                            </strong>

                        </div>


                        <div class="progress" style="height: 14px;">

                            <div class="progress-bar" role="progressbar" style="width: {{ $progression }}%;"
                                aria-valuenow="{{ $progression }}" aria-valuemin="0" aria-valuemax="100"></div>

                        </div>


                        <div class="row text-center mt-4">

                            <div class="col-4">

                                <div class="text-muted small">
                                    Début
                                </div>

                                <strong>
                                    {{ $tache->date_debut ? $tache->date_debut->format('d/m/Y') : '-' }}
                                </strong>

                            </div>


                            <div class="col-4">

                                <div class="text-muted small">
                                    Échéance
                                </div>

                                <strong class="{{ $retard ? 'text-danger' : '' }}">

                                    {{ $tache->date_echeance ? $tache->date_echeance->format('d/m/Y') : '-' }}

                                </strong>

                            </div>


                            <div class="col-4">

                                <div class="text-muted small">
                                    Réalisation
                                </div>

                                <strong class="text-success">

                                    {{ $tache->date_realisation ? $tache->date_realisation->format('d/m/Y') : '-' }}

                                </strong>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- ==================================================
                RESPONSABLES
            =================================================== --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white border-bottom">

                        <h5 class="mb-0 fw-bold">

                            <i class="fas fa-users text-primary me-2"></i>

                            Responsables et suiveurs

                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="row g-4">


                            {{-- Responsables --}}
                            <div class="col-md-6">

                                <h6 class="fw-bold mb-3">
                                    <i class="fas fa-user-tie text-primary me-1"></i>
                                    Responsables
                                </h6>


                                @if (isset($tache->responsables) && $tache->responsables->count())

                                    <div class="list-group list-group-flush">

                                        @foreach ($tache->responsables as $responsable)
                                            @php
                                                $employee = $responsable->employee ?? null;

                                                $employeeName = $employee
                                                    ? trim(($employee->prenom ?? '') . ' ' . ($employee->nom ?? ''))
                                                    : null;

                                                $employeeName =
                                                    $employeeName ?:
                                                    $employee->name ?? ($employee->matricule ?? 'Employé');
                                            @endphp

                                            <div class="list-group-item px-0">

                                                <div class="d-flex align-items-center gap-2">

                                                    <div class="employee-avatar">
                                                        <i class="fas fa-user"></i>
                                                    </div>

                                                    <div>

                                                        <div class="fw-semibold">
                                                            {{ $employeeName }}
                                                        </div>

                                                        @if ($employee?->matricule)
                                                            <small class="text-muted">
                                                                {{ $employee->matricule }}
                                                            </small>
                                                        @endif

                                                    </div>

                                                </div>

                                            </div>
                                        @endforeach

                                    </div>
                                @else
                                    <div class="text-muted small">
                                        Aucun responsable affecté.
                                    </div>

                                @endif

                            </div>


                            {{-- Suiveurs --}}
                            <div class="col-md-6">

                                <h6 class="fw-bold mb-3">

                                    <i class="fas fa-eye text-info me-1"></i>

                                    Suiveurs

                                </h6>


                                @if (isset($tache->suiveurs) && $tache->suiveurs->count())

                                    <div class="list-group list-group-flush">

                                        @foreach ($tache->suiveurs as $suiveur)
                                            @php
                                                $employee = $suiveur->employee ?? null;

                                                $employeeName = $employee
                                                    ? trim(($employee->prenom ?? '') . ' ' . ($employee->nom ?? ''))
                                                    : null;

                                                $employeeName =
                                                    $employeeName ?:
                                                    $employee->name ?? ($employee->matricule ?? 'Employé');
                                            @endphp

                                            <div class="list-group-item px-0">

                                                <div class="d-flex align-items-center gap-2">

                                                    <div class="employee-avatar bg-info-subtle text-info">
                                                        <i class="fas fa-eye"></i>
                                                    </div>

                                                    <div>

                                                        <div class="fw-semibold">
                                                            {{ $employeeName }}
                                                        </div>

                                                        @if ($employee?->matricule)
                                                            <small class="text-muted">
                                                                {{ $employee->matricule }}
                                                            </small>
                                                        @endif

                                                    </div>

                                                </div>

                                            </div>
                                        @endforeach

                                    </div>
                                @else
                                    <div class="text-muted small">
                                        Aucun suiveur affecté.
                                    </div>

                                @endif

                            </div>

                        </div>

                    </div>

                </div>


                {{-- ==================================================
                OBSERVATION
            =================================================== --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white border-bottom">

                        <h5 class="mb-0 fw-bold">

                            <i class="fas fa-comment-alt text-secondary me-2"></i>

                            Observation

                        </h5>

                    </div>


                    <div class="card-body">

                        @if ($tache->observation)
                            <div class="observation-box">

                                <i class="fas fa-quote-left text-muted me-2"></i>

                                {!! nl2br(e($tache->observation)) !!}

                            </div>
                        @else
                            <p class="text-muted mb-0">
                                Aucune observation renseignée.
                            </p>
                        @endif

                    </div>

                </div>

            </div>


            {{-- ======================================================
            COLONNE DROITE
        ======================================================= --}}
            <div class="col-lg-4">


                {{-- ==================================================
                SYNTHÈSE
            =================================================== --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white border-bottom">

                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-info-circle text-primary me-2"></i>
                            Synthèse
                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="summary-item">

                            <span class="text-muted">
                                Statut
                            </span>

                            <span class="badge {{ $statusClasses[$tache->statut] ?? 'bg-light text-dark' }}">

                                {{ $statusLabels[$tache->statut] ?? ucfirst($tache->statut) }}

                            </span>

                        </div>


                        <div class="summary-item">

                            <span class="text-muted">
                                Priorité
                            </span>

                            <strong class="{{ $priorityClasses[$tache->priorite] ?? 'text-secondary' }}">

                                <i class="fas fa-flag me-1"></i>

                                {{ $priorityLabels[$tache->priorite] ?? ucfirst($tache->priorite) }}

                            </strong>

                        </div>


                        <div class="summary-item">

                            <span class="text-muted">
                                Progression
                            </span>

                            <strong>
                                {{ $progression }} %
                            </strong>

                        </div>


                        <div class="summary-item">

                            <span class="text-muted">
                                Échéance
                            </span>

                            <strong class="{{ $retard ? 'text-danger' : '' }}">

                                {{ $tache->date_echeance ? $tache->date_echeance->format('d/m/Y') : '-' }}

                            </strong>

                        </div>


                        <div class="summary-item">

                            <span class="text-muted">
                                Réalisation
                            </span>

                            <strong class="text-success">

                                {{ $tache->date_realisation ? $tache->date_realisation->format('d/m/Y') : '-' }}

                            </strong>

                        </div>

                    </div>

                </div>


                {{-- ==================================================
                DATES
            =================================================== --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white border-bottom">

                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-calendar-alt text-warning me-2"></i>
                            Planning
                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="timeline-item">

                            <div class="timeline-icon bg-primary-subtle text-primary">
                                <i class="fas fa-play"></i>
                            </div>

                            <div>

                                <div class="small text-muted">
                                    Date de début
                                </div>

                                <strong>

                                    {{ $tache->date_debut ? $tache->date_debut->format('d/m/Y') : 'Non définie' }}

                                </strong>

                            </div>

                        </div>


                        <div class="timeline-item">

                            <div class="timeline-icon bg-warning-subtle text-warning">
                                <i class="fas fa-hourglass-half"></i>
                            </div>

                            <div>

                                <div class="small text-muted">
                                    Date d'échéance
                                </div>

                                <strong class="{{ $retard ? 'text-danger' : '' }}">

                                    {{ $tache->date_echeance ? $tache->date_echeance->format('d/m/Y') : 'Non définie' }}

                                </strong>

                            </div>

                        </div>


                        <div class="timeline-item mb-0">

                            <div class="timeline-icon bg-success-subtle text-success">
                                <i class="fas fa-check"></i>
                            </div>

                            <div>

                                <div class="small text-muted">
                                    Date de réalisation
                                </div>

                                <strong class="text-success">

                                    {{ $tache->date_realisation ? $tache->date_realisation->format('d/m/Y') : 'Non réalisée' }}

                                </strong>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- ==================================================
                DOCUMENTS
            =================================================== --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white border-bottom">

                        <h5 class="mb-0 fw-bold">

                            <i class="fas fa-paperclip text-secondary me-2"></i>

                            Documents

                        </h5>

                    </div>


                    <div class="card-body">

                        @if (isset($tache->documents) && $tache->documents->count())
                            <div class="small text-muted mb-2">

                                {{ $tache->documents->count() }}
                                document(s) lié(s) à cette tâche.

                            </div>
                        @else
                            <div class="text-muted small mb-3">
                                Aucun document directement lié à cette tâche.
                            </div>
                        @endif


                        <a href="{{ route('onfp.activites.documents.index', $activite) }}"
                            class="btn btn-sm btn-light border w-100">

                            <i class="fas fa-folder-open me-1"></i>

                            Gérer les documents de l'activité

                        </a>

                    </div>

                </div>


                {{-- ==================================================
                ACTIONS
            =================================================== --}}
                <div class="card border-0 shadow-sm">

                    <div class="card-header bg-white border-bottom">

                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-cog text-secondary me-2"></i>
                            Actions
                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="d-grid gap-2">

                            <a href="{{ route($routePrefix . '.edit', $routeParamsWithTask) }}"
                                class="btn btn-sm btn-warning">

                                <i class="fas fa-edit me-1"></i>

                                Modifier la tâche

                            </a>


                            <form action="{{ route($routePrefix . '.destroy', $routeParamsWithTask) }}" method="POST">

                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn btn-sm btn-outline-danger w-100 show_confirm">

                                    <i class="fas fa-trash-alt me-1"></i>

                                    Supprimer la tâche

                                </button>

                            </form>


                            <a href="{{ route($routePrefix . '.index', $routeParams) }}"
                                class="btn btn-sm btn-light border">

                                <i class="fas fa-list me-1"></i>

                                Retour à la liste

                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    @push('styles')
        <style>
            .task-description {
                line-height: 1.8;
                color: #495057;
                white-space: normal;
            }

            .observation-box {
                background: #f8f9fa;
                border-left: 4px solid #adb5bd;
                border-radius: 8px;
                padding: 1rem 1.2rem;
                line-height: 1.7;
                color: #495057;
            }

            .progress {
                border-radius: 20px;
                background-color: #e9ecef;
            }

            .progress-bar {
                border-radius: 20px;
            }

            .summary-item {
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 1rem;
                padding: .85rem 0;
                border-bottom: 1px solid #f1f3f5;
            }

            .summary-item:last-child {
                border-bottom: 0;
            }

            .employee-avatar {
                width: 38px;
                height: 38px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                background: #e7f1ff;
                color: #0d6efd;
                flex-shrink: 0;
            }

            .timeline-item {
                display: flex;
                gap: .85rem;
                padding-bottom: 1.25rem;
                position: relative;
            }

            .timeline-item:not(:last-child)::after {
                content: "";
                position: absolute;
                left: 18px;
                top: 38px;
                bottom: 0;
                width: 1px;
                background: #dee2e6;
            }

            .timeline-icon {
                width: 36px;
                height: 36px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
                position: relative;
                z-index: 1;
            }

            @media (max-width: 767.98px) {

                .summary-item {
                    align-items: flex-start;
                    flex-direction: column;
                    gap: .25rem;
                }

            }
        </style>
    @endpush

@endsection
