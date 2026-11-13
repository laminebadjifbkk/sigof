@extends('layout.user-layout')

@section('title', 'ONFP | Support informatique')

@section('space-work')
<div class="container-fluid py-3">

    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
        <div>
            <h3 class="fw-bold mb-1">Support informatique</h3>
            <p class="text-muted mb-0">Tableau de bord de suivi des demandes informatiques</p>
        </div>
        @can('support.tickets.create')
            <a href="{{ route('support.tickets.create') }}" class="btn text-white"
               style="background:#F28500">
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

    <div class="row g-3 mb-4">
        @php
            $indicateurs = [
                ['Total des tickets', $stats['total'], 'bi-ticket-perforated', '#0d6efd'],
                ['Nouveaux', $stats['nouveaux'], 'bi-inbox', '#F28500'],
                ['En cours', $stats['en_cours'], 'bi-gear', '#198754'],
                ['En attente', $stats['en_attente'], 'bi-hourglass-split', '#6f42c1'],
                ['Résolus', $stats['resolus'], 'bi-check-circle', '#20a39e'],
            ];
        @endphp

        @foreach($indicateurs as [$libelle, $valeur, $icone, $couleur])
            <div class="col-6 col-md-4 col-xl">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1 small">{{ $libelle }}</p>
                            <h3 class="fw-bold mb-0">{{ $valeur }}</h3>
                        </div>
                        <div class="rounded-circle p-3" style="background:{{ $couleur }}18;color:{{ $couleur }}">
                            <i class="bi {{ $icone }} fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">Derniers tickets enregistrés</h5>
            <a href="{{ route('support.tickets.index') }}" class="btn btn-sm btn-outline-secondary">
                Voir tous les tickets
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Référence</th>
                        <th>Objet</th>
                        <th>Demandeur</th>
                        <th>Priorité</th>
                        <th>Statut</th>
                        <th>Date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ticketsRecents as $ticket)
                        <tr>
                            <td class="fw-semibold">{{ $ticket->reference }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($ticket->objet, 45) }}</td>
                            <td>{{ trim(($ticket->demandeur->firstname ?? '').' '.($ticket->demandeur->name ?? '')) ?: 'Non renseigné' }}</td>
                            <td>{{ $ticket->priorite->libelle ?? '-' }}</td>
                            <td>
                                <span class="badge bg-light text-dark border">
                                    {{ $ticket->statut->libelle ?? '-' }}
                                </span>
                            </td>
                            <td>{{ $ticket->date_soumission?->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('support.tickets.show', $ticket) }}"
                                   class="btn btn-sm btn-outline-primary" title="Consulter">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                Aucun ticket enregistré pour le moment.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
