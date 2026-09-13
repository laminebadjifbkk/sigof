@extends('layout.user-layout')

@section('space-work')

<div class="container-fluid">

    {{-- En-tête --}}
    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="small text-muted">
                {{ $sousActivite->reference }}
            </div>

            <h4 class="mb-1">
                {{ $sousActivite->titre }}
            </h4>

            <div class="text-muted">
                Activité :
                <a href="{{ route(
                    'onfp.activites.show',
                    $activite
                ) }}">
                    {{ $activite->titre }}
                </a>
            </div>

        </div>

        <div class="d-flex gap-2">

            <a href="{{ route(
                'onfp.activites.sous-activites.edit',
                [$activite, $sousActivite]
            ) }}"
               class="btn btn-outline-warning">

                <i class="bi bi-pencil me-1"></i>
                Modifier

            </a>

            <a href="{{ route(
                'onfp.activites.sous-activites.taches.create',
                [$activite, $sousActivite]
            ) }}"
               class="btn btn-primary">

                <i class="bi bi-plus-circle me-1"></i>
                Nouvelle tâche

            </a>

        </div>

    </div>


    {{-- Informations --}}
    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-white">
            <h5 class="mb-0">
                Informations
            </h5>
        </div>

        <div class="card-body">

            <div class="row g-3">

                <div class="col-md-3">
                    <small class="text-muted d-block">
                        Statut
                    </small>

                    <strong>
                        {{ $sousActivite->statut }}
                    </strong>
                </div>

                <div class="col-md-3">
                    <small class="text-muted d-block">
                        Priorité
                    </small>

                    <strong>
                        {{ $sousActivite->priorite }}
                    </strong>
                </div>

                <div class="col-md-3">
                    <small class="text-muted d-block">
                        Début
                    </small>

                    <strong>
                        {{ $sousActivite->date_debut?->format('d/m/Y') ?? '-' }}
                    </strong>
                </div>

                <div class="col-md-3">
                    <small class="text-muted d-block">
                        Fin prévue
                    </small>

                    <strong>
                        {{ $sousActivite->date_fin_prevue?->format('d/m/Y') ?? '-' }}
                    </strong>
                </div>

            </div>

            <hr>

            <div class="mb-2">
                <strong>Progression</strong>
            </div>

            <div class="progress"
                 style="height:10px">

                <div class="progress-bar"
                     style="width: {{ $sousActivite->progression }}%">
                </div>

            </div>

            <div class="small text-muted mt-1">
                {{ $sousActivite->progression }} %
            </div>

        </div>

    </div>


    {{-- Tâches --}}
    <div class="card shadow-sm border-0">

        <div class="card-header bg-white py-3">

            <div class="d-flex justify-content-between align-items-center">

                <div>
                    <h5 class="mb-1">
                        <i class="bi bi-check2-square me-2"></i>
                        Tâches
                    </h5>

                    <small class="text-muted">
                        Tâches de cette sous-activité
                    </small>
                </div>

                <div class="d-flex gap-2">

                    <span class="badge bg-secondary">
                        {{ $sousActivite->taches->count() }}
                    </span>

                    <a href="{{ route(
                        'onfp.activites.sous-activites.taches.create',
                        [$activite, $sousActivite]
                    ) }}"
                       class="btn btn-sm btn-primary">

                        <i class="bi bi-plus"></i>

                    </a>

                </div>

            </div>

        </div>

        <div class="card-body">

            @forelse($sousActivite->taches as $tache)

                <div class="border rounded p-3 mb-3">

                    <div class="d-flex justify-content-between">

                        <div class="flex-grow-1">

                            <div class="fw-semibold">
                                {{ $tache->titre }}
                            </div>

                            <small class="text-muted">

                                {{ $tache->progression }} %

                                ·

                                {{ $tache->statut }}

                                @if($tache->date_echeance)

                                    · Échéance :
                                    {{ $tache->date_echeance->format('d/m/Y') }}

                                @endif

                            </small>

                            <div class="progress mt-2"
                                 style="height:6px">

                                <div class="progress-bar"
                                     style="width: {{ $tache->progression }}%">
                                </div>

                            </div>

                        </div>

                        <div>

                            <a href="{{ route(
                                'onfp.activites.sous-activites.taches.show',
                                [$activite, $sousActivite, $tache]
                            ) }}"
                               class="btn btn-sm btn-outline-primary">

                                <i class="bi bi-eye"></i>

                            </a>

                        </div>

                    </div>

                </div>

            @empty

                <div class="text-center text-muted py-4">

                    <i class="bi bi-check2-square fs-2 d-block mb-2"></i>

                    Aucune tâche pour cette sous-activité.

                </div>

            @endforelse

        </div>

    </div>

</div>

@endsection
