@extends('layout.user-layout')

@section('title', 'ONFP | Liste des tickets')

@section('space-work')
<div class="container-fluid py-3">

    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
        <div>
            <h3 class="fw-bold mb-1">Gestion des tickets</h3>
            <p class="text-muted mb-0">Consultez et suivez les demandes d'assistance informatique.</p>
        </div>
        @can('support.tickets.create')
            <a href="{{ route('support.tickets.create') }}" class="btn text-white" style="background:#F28500">
                <i class="bi bi-plus-circle me-1"></i> Nouveau ticket
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
            <form method="GET" action="{{ route('support.tickets.index') }}">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">Référence</label>
                        <input type="text" name="reference" class="form-control"
                               value="{{ request('reference') }}" placeholder="Ex. SUP-2026">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Statut</label>
                        <select name="statut" class="form-select">
                            <option value="">Tous les statuts</option>
                            @foreach($statuts as $statut)
                                <option value="{{ $statut->id }}" @selected(request('statut') == $statut->id)>
                                    {{ $statut->libelle }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Priorité</label>
                        <select name="priorite" class="form-select">
                            <option value="">Toutes les priorités</option>
                            @foreach($priorites as $priorite)
                                <option value="{{ $priorite->id }}" @selected(request('priorite') == $priorite->id)>
                                    {{ $priorite->libelle }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 d-flex gap-2">
                        <button class="btn btn-primary flex-fill" type="submit">
                            <i class="bi bi-search"></i> Filtrer
                        </button>
                        <a href="{{ route('support.tickets.index') }}" class="btn btn-outline-secondary" title="Réinitialiser">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold">Tickets ({{ $tickets->total() }})</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Référence</th>
                        <th>Objet</th>
                        <th>Catégorie</th>
                        <th>Demandeur</th>
                        <th>Technicien</th>
                        <th>Priorité</th>
                        <th>Statut</th>
                        <th>Soumis le</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $ticket)
                        <tr>
                            <td class="fw-semibold text-nowrap">{{ $ticket->reference }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($ticket->objet, 35) }}</td>
                            <td>{{ $ticket->categorie->libelle ?? '-' }}</td>
                            <td>{{ trim(($ticket->demandeur->firstname ?? '').' '.($ticket->demandeur->name ?? '')) ?: '-' }}</td>
                            <td>
                                {{ $ticket->assigne
                                    ? trim(($ticket->assigne->firstname ?? '').' '.($ticket->assigne->name ?? ''))
                                    : 'Non affecté' }}
                            </td>
                            <td>{{ $ticket->priorite->libelle ?? '-' }}</td>
                            <td>
                                <span class="badge bg-light text-dark border">
                                    {{ $ticket->statut->libelle ?? '-' }}
                                </span>
                            </td>
                            <td class="text-nowrap">{{ $ticket->date_soumission?->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('support.tickets.show', $ticket) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                Aucun ticket ne correspond à votre recherche.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-body">
            {{ $tickets->links() }}
        </div>
    </div>
</div>
@endsection
