@extends('layout.user-layout')

@section('space-work')

    <div class="container-fluid">

        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">

            <div>

                <h4 class="mb-1">
                    <i class="bi bi-diagram-3 me-2"></i>
                    Sous-activités
                </h4>

                <div class="text-muted">
                    Activité :
                    <strong>{{ $activite->titre }}</strong>
                </div>

            </div>

            <div class="d-flex gap-2">

                <a href="{{ route('onfp.activites.show', $activite) }}" class="btn btn-sm btn-outline-secondary">

                    <i class="bi bi-arrow-left me-1"></i>
                    Retour

                </a>

                <a href="{{ route('onfp.activites.sous-activites.create', $activite) }}"
                    class="btn btn-sm btn-primary">

                    <i class="bi bi-plus-lg me-1"></i>
                    Nouvelle sous-activité

                </a>

            </div>

        </div>


        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">

                <i class="bi bi-check-circle me-2"></i>

                {{ session('success') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert">
                </button>

            </div>
        @endif


        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">

                <i class="bi bi-exclamation-triangle me-2"></i>

                {{ session('error') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert">
                </button>

            </div>
        @endif


        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white">

                <h5 class="mb-1">
                    Liste des sous-activités
                </h5>

                <small class="text-muted">
                    Décomposition opérationnelle de l'activité.
                </small>

            </div>


            <div class="card-body p-0">

                @if ($sousActivites->count())
                    <div class="table-responsive">

                        <table class="table table-hover align-middle mb-0">

                            <thead class="table-light">

                                <tr>

                                    <th class="ps-4">
                                        Sous-activité
                                    </th>

                                    <th>
                                        Statut
                                    </th>

                                    <th>
                                        Priorité
                                    </th>

                                    <th>
                                        Progression
                                    </th>

                                    <th>
                                        Échéance
                                    </th>

                                    <th>
                                        Tâches
                                    </th>

                                    <th class="text-end pe-4">
                                        Actions
                                    </th>

                                </tr>

                            </thead>

                            <tbody>

                                @foreach ($sousActivites as $sousActivite)
                                    @php

                                        $statutLabels = [
                                            'a_faire' => 'À faire',
                                            'en_cours' => 'En cours',
                                            'suspendue' => 'Suspendue',
                                            'terminee' => 'Terminée',
                                            'annulee' => 'Annulée',
                                        ];

                                        $statutClasses = [
                                            'a_faire' => 'secondary',
                                            'en_cours' => 'primary',
                                            'suspendue' => 'warning',
                                            'terminee' => 'success',
                                            'annulee' => 'danger',
                                        ];

                                        $prioriteLabels = [
                                            'basse' => 'Basse',
                                            'normale' => 'Normale',
                                            'haute' => 'Haute',
                                            'urgente' => 'Urgente',
                                        ];

                                    @endphp

                                    <tr>

                                        <td class="ps-4">

                                            <div class="fw-semibold">

                                                {{ $sousActivite->titre }}

                                            </div>

                                            @if ($sousActivite->reference)
                                                <small class="text-muted">

                                                    {{ $sousActivite->reference }}

                                                </small>
                                            @endif

                                        </td>


                                        <td>

                                            <span
                                                class="badge bg-{{ $statutClasses[$sousActivite->statut] ?? 'secondary' }}">

                                                {{ $statutLabels[$sousActivite->statut] ?? $sousActivite->statut }}

                                            </span>

                                        </td>


                                        <td>

                                            <span class="small">

                                                {{ $prioriteLabels[$sousActivite->priorite] ?? $sousActivite->priorite }}

                                            </span>

                                        </td>


                                        <td style="min-width: 160px;">

                                            <div class="d-flex justify-content-between mb-1">

                                                <small>
                                                    Progression
                                                </small>

                                                <small class="fw-semibold">
                                                    {{ $sousActivite->progression }}%
                                                </small>

                                            </div>

                                            <div class="progress" style="height: 7px;">

                                                <div class="progress-bar" role="progressbar"
                                                    style="width: {{ $sousActivite->progression }}%;">

                                                </div>

                                            </div>

                                        </td>


                                        <td>

                                            @if ($sousActivite->date_fin_prevue)
                                                {{ $sousActivite->date_fin_prevue->format('d/m/Y') }}
                                            @else
                                                <span class="text-muted">
                                                    —
                                                </span>
                                            @endif

                                        </td>


                                        <td>

                                            <span class="badge bg-light text-dark border">

                                                {{ $sousActivite->taches_count }}

                                            </span>

                                        </td>


                                        <td class="text-end pe-4">

                                            <div class="btn-group">

                                                <a href="{{ route('onfp.activites.sous-activites.show', [$activite, $sousActivite]) }}"
                                                    class="btn btn-sm btn-outline-primary" title="Voir">

                                                    <i class="bi bi-eye"></i>

                                                </a>

                                                <a href="{{ route('onfp.activites.sous-activites.edit', [$activite, $sousActivite]) }}"
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
                @else
                    <div class="text-center py-5">

                        <i class="bi bi-diagram-3 display-5 text-muted"></i>

                        <h5 class="mt-3">
                            Aucune sous-activité
                        </h5>

                        <p class="text-muted">
                            Cette activité ne possède pas encore de sous-activité.
                        </p>

                        <a href="{{ route('onfp.activites.sous-activites.create', $activite) }}"
                            class="btn btn-sm btn-primary">

                            <i class="bi bi-plus-lg me-1"></i>
                            Créer une sous-activité

                        </a>

                    </div>
                @endif

            </div>


            @if ($sousActivites->hasPages())
                <div class="card-footer bg-white">

                    {{ $sousActivites->links() }}

                </div>
            @endif

        </div>

    </div>

@endsection
