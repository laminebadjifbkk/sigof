@extends('layout.user-layout')

@section('title', 'ONFP | Détail du ticket')

@section('space-work')
<div class="container-fluid py-3">

    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
        <div>
            <a href="{{ route('support.tickets.index') }}" class="text-decoration-none">
                <i class="bi bi-arrow-left"></i> Retour aux tickets
            </a>
            <h3 class="fw-bold mt-2 mb-1">{{ $ticket->reference }}</h3>
            <p class="text-muted mb-0">{{ $ticket->objet }}</p>
        </div>

        @if($ticket->user_assigne_id == auth()->id()
            && auth()->user()->can('support.tickets.take-charge')
            && $ticket->statut
            && !in_array($ticket->statut->code, ['EN_COURS', 'RESOLU', 'CLOTURE', 'REJETE']))
            <form method="POST" action="{{ route('support.tickets.take-charge', $ticket) }}">
                @csrf
                <button class="btn text-white" style="background:#F28500" type="submit">
                    <i class="bi bi-play-circle me-1"></i> Prendre en charge
                </button>
            </form>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">Description de la demande</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <small class="text-muted d-block">Catégorie</small>
                            <strong>{{ $ticket->categorie->libelle ?? '-' }}</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Type</small>
                            <strong>{{ $ticket->type->libelle ?? '-' }}</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Priorité</small>
                            <strong>{{ $ticket->priorite->libelle ?? '-' }}</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Statut actuel</small>
                            <span class="badge bg-light text-dark border">
                                {{ $ticket->statut->libelle ?? '-' }}
                            </span>
                        </div>
                    </div>
                    <hr>
                    <div class="text-muted small mb-2">Description</div>
                    <div style="white-space:pre-wrap">{{ $ticket->description }}</div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">Historique du ticket</h5>
                </div>
                <div class="card-body">
                    @forelse($ticket->historiques as $historique)
                        <div class="d-flex gap-3 pb-3 mb-3 border-bottom">
                            <div class="rounded-circle bg-light p-2 text-primary">
                                <i class="bi bi-clock-history"></i>
                            </div>
                            <div>
                                <strong>{{ $historique->description ?? $historique->action }}</strong>
                                <div class="small text-muted">
                                    {{ trim(($historique->user->firstname ?? '').' '.($historique->user->name ?? '')) ?: 'Système' }}
                                    — {{ $historique->created_at?->format('d/m/Y à H:i') }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted mb-0">Aucun historique disponible.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">Informations générales</h5>
                </div>
                <div class="card-body">
                    <p class="mb-1 small text-muted">Demandeur</p>
                    <p class="fw-semibold">
                        {{ trim(($ticket->demandeur->firstname ?? '').' '.($ticket->demandeur->name ?? '')) ?: '-' }}
                    </p>

                    <p class="mb-1 small text-muted">Technicien affecté</p>
                    <p class="fw-semibold">
                        {{ $ticket->assigne
                            ? trim(($ticket->assigne->firstname ?? '').' '.($ticket->assigne->name ?? ''))
                            : 'En attente d’affectation' }}
                    </p>

                    <p class="mb-1 small text-muted">Date de soumission</p>
                    <p class="fw-semibold">{{ $ticket->date_soumission?->format('d/m/Y à H:i') ?? '-' }}</p>

                    <p class="mb-1 small text-muted">Prise en charge</p>
                    <p class="fw-semibold">{{ $ticket->date_prise_en_charge?->format('d/m/Y à H:i') ?? 'Non prise en charge' }}</p>

                    <p class="mb-1 small text-muted">Résolution</p>
                    <p class="fw-semibold">{{ $ticket->date_resolution?->format('d/m/Y à H:i') ?? '-' }}</p>

                    <p class="mb-1 small text-muted">Clôture</p>
                    <p class="fw-semibold mb-0">{{ $ticket->date_cloture?->format('d/m/Y à H:i') ?? '-' }}</p>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">Affectations</h5>
                </div>
                <div class="card-body">
                    @forelse($ticket->affectations as $affectation)
                        <div class="mb-3 pb-3 border-bottom">
                            <strong>
                                {{ trim(($affectation->nouveauTechnicien->firstname ?? '').' '.($affectation->nouveauTechnicien->name ?? '')) ?: 'Technicien' }}
                            </strong>
                            <div class="small text-muted">
                                Affecté le {{ $affectation->date_affectation?->format('d/m/Y à H:i') }}
                            </div>
                            @if($affectation->motif)
                                <div class="small mt-1">{{ $affectation->motif }}</div>
                            @endif
                        </div>
                    @empty
                        <p class="text-muted mb-0">Aucune affectation enregistrée.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
