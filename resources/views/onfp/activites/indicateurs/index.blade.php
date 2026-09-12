@extends('layout.user-layout')

@section('title', 'Indicateurs - ' . $activite->reference)

@section('space-work')

<div class="container-fluid py-4">

    {{-- En-tête --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div class="d-flex align-items-center">

            <a href="{{ route('onfp.activites.show', $activite) }}"
               class="btn btn-sm btn-outline-secondary me-3">

                <i class="bi bi-arrow-left"></i>

            </a>

            <div>

                <h3 class="mb-1">
                    Indicateurs
                </h3>

                <p class="text-muted mb-0">
                    {{ $activite->reference }} - {{ $activite->titre }}
                </p>

            </div>

        </div>


        <a href="{{ route('onfp.activites.indicateurs.create', $activite) }}"
           class="btn btn-sm btn-primary">

            <i class="bi bi-plus-lg me-1"></i>

            Ajouter un indicateur

        </a>

    </div>


    {{-- Messages --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="bi bi-check-circle me-2"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- Résumé --}}
    <div class="row g-3 mb-4">

        <div class="col-md-4">

            <div class="card shadow-sm border-0">

                <div class="card-body">

                    <div class="text-muted small">
                        Nombre d'indicateurs
                    </div>

                    <div class="fs-3 fw-bold">
                        {{ $indicateurs->count() }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-4">

            <div class="card shadow-sm border-0">

                <div class="card-body">

                    <div class="text-muted small">
                        Indicateurs renseignés
                    </div>

                    <div class="fs-3 fw-bold">
                        {{ $indicateurs->whereNotNull('valeur_realisee')->count() }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-4">

            <div class="card shadow-sm border-0">

                <div class="card-body">

                    <div class="text-muted small">
                        Progression moyenne
                    </div>

                    <div class="fs-3 fw-bold">
                        {{ number_format($indicateurs->avg('pourcentage') ?? 0, 2, ',', ' ') }} %
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Liste --}}
    <div class="card shadow-sm border-0">

        <div class="card-header bg-white py-3">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="mb-0">
                    <i class="bi bi-graph-up me-2"></i>
                    Liste des indicateurs
                </h5>

                <span class="badge bg-secondary">
                    {{ $indicateurs->count() }}
                </span>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>Indicateur</th>

                            <th>Cible</th>

                            <th>Réalisé</th>

                            <th width="250">Progression</th>

                            <th>Date</th>

                            <th width="150"
                                class="text-end">
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    @forelse($indicateurs as $indicateur)

                        @php
                            $pourcentage = (float) ($indicateur->pourcentage ?? 0);

                            if ($pourcentage >= 100) {
                                $progressClass = 'bg-success';
                            } elseif ($pourcentage >= 75) {
                                $progressClass = 'bg-primary';
                            } elseif ($pourcentage >= 50) {
                                $progressClass = 'bg-warning';
                            } else {
                                $progressClass = 'bg-danger';
                            }
                        @endphp

                        <tr>

                            {{-- Indicateur --}}
                            <td>

                                <div class="fw-semibold">

                                    {{ $indicateur->libelle }}

                                </div>

                                @if($indicateur->code)

                                    <div class="small text-muted">

                                        {{ $indicateur->code }}

                                    </div>

                                @endif

                            </td>


                            {{-- Cible --}}
                            <td>

                                <strong>
                                    {{ number_format($indicateur->valeur_cible, 2, ',', ' ') }}
                                </strong>

                                @if($indicateur->unite)

                                    <span class="text-muted">
                                        {{ $indicateur->unite }}
                                    </span>

                                @endif

                            </td>


                            {{-- Réalisé --}}
                            <td>

                                <strong>
                                    {{ number_format($indicateur->valeur_realisee ?? 0, 2, ',', ' ') }}
                                </strong>

                                @if($indicateur->unite)

                                    <span class="text-muted">
                                        {{ $indicateur->unite }}
                                    </span>

                                @endif

                            </td>


                            {{-- Progression --}}
                            <td>

                                <div class="d-flex justify-content-between mb-1">

                                    <small class="fw-semibold">
                                        {{ number_format($pourcentage, 2, ',', ' ') }} %
                                    </small>

                                </div>

                                <div class="progress"
                                     style="height: 8px;">

                                    <div class="progress-bar {{ $progressClass }}"
                                         role="progressbar"
                                         style="width: {{ min($pourcentage, 100) }}%;"
                                         aria-valuenow="{{ $pourcentage }}"
                                         aria-valuemin="0"
                                         aria-valuemax="100">
                                    </div>

                                </div>

                            </td>


                            {{-- Date --}}
                            <td>

                                {{ $indicateur->date_reference?->format('d/m/Y') ?? '-' }}

                            </td>


                            {{-- Actions --}}
                            <td class="text-end">

                                <div class="btn-group">

                                    <a href="{{ route('onfp.activites.indicateurs.edit', [$activite, $indicateur]) }}"
                                       class="btn btn-sm btn-sm btn-outline-primary"
                                       title="Modifier">

                                        <i class="bi bi-pencil"></i>

                                    </a>


                                    <form method="POST"
                                          action="{{ route('onfp.activites.indicateurs.destroy', [$activite, $indicateur]) }}"
                                          onsubmit="return confirm('Voulez-vous vraiment supprimer cet indicateur ?');">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-sm btn-sm btn-outline-danger"
                                                title="Supprimer">

                                            <i class="bi bi-trash"></i>

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="6"
                                class="text-center py-5">

                                <i class="bi bi-bar-chart fs-1 text-muted d-block mb-3"></i>

                                <div class="text-muted mb-3">
                                    Aucun indicateur n'est encore associé à cette activité.
                                </div>

                                <a href="{{ route('onfp.activites.indicateurs.create', $activite) }}"
                                   class="btn btn-sm btn-primary">

                                    <i class="bi bi-plus-lg me-1"></i>
                                    Ajouter le premier indicateur

                                </a>

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
