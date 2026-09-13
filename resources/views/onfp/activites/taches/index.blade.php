@extends('layout.user-layout')

@section('space-work')
    <div class="container-fluid">

        {{-- En-tête --}}
        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>
                <h4 class="mb-1">
                    <i class="bi bi-briefcase me-2"></i>
                    Gestion des activités
                </h4>

                <div class="text-muted">
                    Pilotage et suivi des activités de l'ONFP
                </div>
            </div>

            <a href="{{ route('onfp.activites.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i>
                Nouvelle activité
            </a>

        </div>


        {{-- Statistiques --}}
        <div class="row g-3 mb-4">

            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="text-muted small">
                            Total activités
                        </div>
                        <div class="fs-3 fw-bold">
                            {{ $activites->total() }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="text-muted small">
                            En cours
                        </div>
                        <div class="fs-3 fw-bold text-primary">
                            {{ $activitesEnCours ?? 0 }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="text-muted small">
                            Terminées
                        </div>
                        <div class="fs-3 fw-bold text-success">
                            {{ $activitesTerminees ?? 0 }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="text-muted small">
                            En retard
                        </div>
                        <div class="fs-3 fw-bold text-danger">
                            {{ $activitesEnRetard ?? 0 }}
                        </div>
                    </div>
                </div>
            </div>

        </div>


        {{-- Tableau --}}
        <div class="card border-0 shadow-sm">

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-hover align-middle">

                        <thead>
                            <tr>
                                <th>Référence</th>
                                <th>Activité</th>
                                <th>Direction</th>
                                <th>Statut</th>
                                <th>Progression</th>
                                <th>Fin prévue</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($activites as $activite)
                                <tr>

                                    <td>
                                        <span class="fw-semibold">
                                            {{ $activite->reference }}
                                        </span>
                                    </td>

                                    <td>
                                        <div class="fw-semibold text-break">
                                            {{ $activite->titre }}
                                        </div>

                                        @if ($activite->type)
                                            <small class="text-muted">
                                                {{ $activite->type->libelle }}
                                            </small>
                                        @endif
                                    </td>

                                    <td>
                                        {{ $activite->direction->sigle ?? ($activite->direction->name ?? '-') }}
                                    </td>

                                    <td>
                                        {{ $activite->statut }}
                                    </td>

                                    <td style="min-width:150px">

                                        <div class="progress" style="height:7px">

                                            <div class="progress-bar" style="width: {{ $activite->progression }}%">
                                            </div>

                                        </div>

                                        <small class="text-muted">
                                            {{ $activite->progression }} %
                                        </small>

                                    </td>

                                    <td>
                                        @if ($activite->date_fin_prevue)
                                            {{ $activite->date_fin_prevue->format('d/m/Y') }}
                                        @else
                                            -
                                        @endif
                                    </td>

                                    <td class="text-end">

                                        <a href="{{ route('onfp.activites.show', $activite) }}"
                                            class="btn btn-sm btn-outline-primary" title="Voir">

                                            <i class="bi bi-eye"></i>

                                        </a>

                                        <a href="{{ route('onfp.activites.edit', $activite) }}"
                                            class="btn btn-sm btn-outline-warning" title="Modifier">

                                            <i class="bi bi-pencil"></i>

                                        </a>

                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="7" class="text-center text-muted py-5">

                                        <i class="bi bi-inbox fs-2 d-block mb-2"></i>

                                        Aucune activité enregistrée.

                                    </td>
                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>

                <div class="mt-3">
                    {{ $activites->links() }}
                </div>

            </div>

        </div>

    </div>
@endsection
