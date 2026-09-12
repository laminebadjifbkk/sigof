@extends('layout.user-layout')

@section('space-work')

    <div class="container-fluid">

        {{-- En-tête --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">

            <div>

                <h4 class="mb-1">
                    <i class="bi bi-diagram-3 me-2"></i>
                    {{ $sousActivite->titre }}
                </h4>

                <div class="text-muted">

                    Activité :
                    <a href="{{ route('onfp.activites.show', $activite) }}">
                        {{ $activite->titre }}
                    </a>

                </div>

            </div>


            <div class="d-flex gap-2">

                <a href="{{ route('onfp.activites.sous-activites.index', $activite) }}"
                    class="btn btn-sm btn-sm btn-outline-secondary">

                    <i class="bi bi-arrow-left me-1"></i>
                    Sous-activités

                </a>

                <a href="{{ route('onfp.activites.sous-activites.edit', [$activite, $sousActivite]) }}"
                    class="btn btn-sm btn-sm btn-primary">

                    <i class="bi bi-pencil me-1"></i>
                    Modifier

                </a>

            </div>

        </div>


        @if (session('success'))
            <div class="alert alert-success">
                <i class="bi bi-check-circle me-2"></i>
                {{ session('success') }}
            </div>
        @endif


        <div class="row g-4">

            {{-- Informations --}}
            <div class="col-lg-8">

                <div class="card border-0 shadow-sm">

                    <div class="card-header bg-white">

                        <h5 class="mb-0">
                            Informations
                        </h5>

                    </div>

                    <div class="card-body">

                        @if ($sousActivite->description)
                            <div class="mb-4">

                                <h6 class="text-muted">
                                    Description
                                </h6>

                                <div>
                                    {!! nl2br(e($sousActivite->description)) !!}
                                </div>

                            </div>
                        @endif


                        <div class="row g-3">

                            <div class="col-md-4">

                                <small class="text-muted d-block">
                                    Référence
                                </small>

                                <strong>
                                    {{ $sousActivite->reference ?? '—' }}
                                </strong>

                            </div>


                            <div class="col-md-4">

                                <small class="text-muted d-block">
                                    Statut
                                </small>

                                <strong>
                                    {{ ucfirst(str_replace('_', ' ', $sousActivite->statut)) }}
                                </strong>

                            </div>


                            <div class="col-md-4">

                                <small class="text-muted d-block">
                                    Priorité
                                </small>

                                <strong>
                                    {{ ucfirst($sousActivite->priorite) }}
                                </strong>

                            </div>

                        </div>


                        <hr>


                        <div class="mb-2 d-flex justify-content-between">

                            <span class="fw-semibold">
                                Progression
                            </span>

                            <span class="fw-bold">
                                {{ $sousActivite->progression }}%
                            </span>

                        </div>

                        <div class="progress" style="height: 10px;">

                            <div class="progress-bar" style="width: {{ $sousActivite->progression }}%;">
                            </div>

                        </div>

                    </div>

                </div>


                {{-- Tâches --}}
                <div class="card border-0 shadow-sm mt-4">

                    <div class="card-header bg-white">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>

                                <h5 class="mb-1">
                                    <i class="bi bi-list-check me-2"></i>
                                    Tâches
                                </h5>

                                <small class="text-muted">
                                    Tâches associées à cette sous-activité.
                                </small>

                            </div>

                            <a href="{{ route('onfp.activites.sous-activites.taches.create', [$activite, $sousActivite]) }}"
                                class="btn btn-sm btn-primary">
                                <i class="bi bi-plus-lg me-1"></i>
                                Nouvelle tâche
                            </a>

                        </div>

                    </div>


                    <div class="card-body">

                        @if ($sousActivite->taches->count())
                            <div class="list-group list-group-flush">

                                @foreach ($sousActivite->taches as $tache)
                                    <a href="{{ route('onfp.activites.taches.show', [$activite, $tache]) }}"
                                        class="list-group-item list-group-item-action px-0">

                                        <div class="d-flex justify-content-between align-items-center">

                                            <div>

                                                <div class="fw-semibold">
                                                    {{ $tache->titre }}
                                                </div>

                                                @if ($tache->reference)
                                                    <small class="text-muted">
                                                        {{ $tache->reference }}
                                                    </small>
                                                @endif

                                            </div>

                                            <span class="badge bg-light text-dark border">
                                                {{ $tache->progression }}%
                                            </span>

                                        </div>

                                    </a>
                                @endforeach

                            </div>
                        @else
                            <div class="text-center py-4">

                                <i class="bi bi-list-check fs-1 text-muted"></i>

                                <p class="text-muted mt-2 mb-3">
                                    Aucune tâche associée.
                                </p>

                                <a href="{{ route('onfp.activites.sous-activites.taches.create', [$activite, $sousActivite]) }}"
                                    class="btn btn-sm btn-primary">
                                    <i class="bi bi-plus-lg me-1"></i>
                                    Nouvelle tâche
                                </a>

                            </div>
                        @endif

                    </div>

                </div>

            </div>


            {{-- Résumé --}}
            <div class="col-lg-4">

                <div class="card border-0 shadow-sm">

                    <div class="card-header bg-white">

                        <h6 class="mb-0">
                            Synthèse
                        </h6>

                    </div>

                    <div class="card-body">

                        <div class="d-flex justify-content-between mb-3">

                            <span class="text-muted">
                                Tâches
                            </span>

                            <strong>
                                {{ $sousActivite->taches->count() }}
                            </strong>

                        </div>


                        <div class="d-flex justify-content-between mb-3">

                            <span class="text-muted">
                                Début
                            </span>

                            <strong>

                                {{ $sousActivite->date_debut ? $sousActivite->date_debut->format('d/m/Y') : '—' }}

                            </strong>

                        </div>


                        <div class="d-flex justify-content-between">

                            <span class="text-muted">
                                Fin prévue
                            </span>

                            <strong>

                                {{ $sousActivite->date_fin_prevue ? $sousActivite->date_fin_prevue->format('d/m/Y') : '—' }}

                            </strong>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection
