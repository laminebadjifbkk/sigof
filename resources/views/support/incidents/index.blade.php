@extends('layout.user-layout')

@section('title', 'ONFP | Gestion des incidents')

@section('space-work')
<div class="container-fluid py-3">

    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
        <div>
            <h3 class="fw-bold mb-1">Gestion des incidents</h3>
            <p class="text-muted mb-0">
                Consultez et suivez les incidents affectant le système informatique de l'ONFP.
            </p>
        </div>

        @can('support.incidents.create')
            <a href="{{ route('support.incidents.create') }}"
               class="btn btn-sm text-white mt-2 mt-md-0"
               style="background:#F28500">
                <i class="bi bi-plus-circle me-1"></i>
                Déclarer un incident
            </a>
        @endcan
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('support.incidents.index') }}">
                <div class="row g-3 align-items-end">

                    <div class="col-md-4">
                        <label class="form-label">Recherche</label>
                        <input
                            type="text"
                            name="recherche"
                            class="form-control form-control-sm"
                            value="{{ request('recherche') }}"
                            placeholder="Référence, intitulé ou description"
                        >
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Statut</label>
                        <select name="statut" class="form-select form-select-sm">
                            <option value="">Tous les statuts</option>
                            @foreach($statuts as $statut)
                                <option
                                    value="{{ $statut->code }}"
                                    @selected(request('statut') == $statut->code)
                                >
                                    {{ $statut->libelle }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Priorité</label>
                        <select name="priorite" class="form-select form-select-sm">
                            <option value="">Toutes les priorités</option>
                            @foreach($priorites as $priorite)
                                <option
                                    value="{{ $priorite->id }}"
                                    @selected(request('priorite') == $priorite->id)
                                >
                                    {{ $priorite->libelle }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2 d-flex gap-2">
                        <button class="btn btn-sm btn-primary flex-fill" type="submit">
                            <i class="bi bi-search"></i> Filtrer
                        </button>

                        <a href="{{ route('support.incidents.index') }}"
                           class="btn btn-sm btn-outline-secondary"
                           title="Réinitialiser">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold">
                Incidents enregistrés ({{ $incidents->total() }})
            </h5>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">
                    <tr>
                        <th>Référence</th>
                        <th>Intitulé</th>
                        <th>Type</th>
                        <th>Déclarant</th>
                        <th>Responsable</th>
                        <th>Priorité</th>
                        <th>Statut</th>
                        <th>Détecté le</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($incidents as $incident)
                        <tr>
                            <td class="fw-semibold text-nowrap">
                                {{ $incident->reference }}
                            </td>

                            <td>
                                {{ \Illuminate\Support\Str::limit($incident->titre, 40) }}
                            </td>

                            <td>
                                {{ $incident->type->libelle ?? '-' }}
                            </td>

                            <td>
                                {{ trim(
                                    ($incident->declarant->firstname ?? '') . ' ' .
                                    ($incident->declarant->name ?? '')
                                ) ?: '-' }}
                            </td>

                            <td>
                                @if($incident->responsable)
                                    {{ trim(
                                        ($incident->responsable->firstname ?? '') . ' ' .
                                        ($incident->responsable->name ?? '')
                                    ) }}
                                @else
                                    <span class="text-muted">Non affecté</span>
                                @endif
                            </td>

                            <td>
                                {{ $incident->priorite->libelle ?? '-' }}
                            </td>

                            <td>
                                <span class="badge bg-light text-dark border">
                                    {{ $incident->statut->libelle ?? '-' }}
                                </span>
                            </td>

                            <td class="text-nowrap">
                                {{ $incident->date_detection?->format('d/m/Y H:i') }}
                            </td>

                            <td>
                                <a
                                    href="{{ route('support.incidents.show', $incident) }}"
                                    class="btn btn-sm btn-outline-primary"
                                    title="Consulter l'incident"
                                >
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                Aucun incident ne correspond à votre recherche.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        <div class="card-body">
            {{ $incidents->links() }}
        </div>

    </div>
</div>
@endsection
