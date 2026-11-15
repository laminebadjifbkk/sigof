@extends('layout.user-layout')

@section('title', 'ONFP | Détail de l’incident')

@section('space-work')

    <div class="container-fluid py-3">

        {{-- EN-TÊTE --}}
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
            <div>
                <a href="{{ route('support.incidents.index') }}" class="text-decoration-none text-muted">
                    <i class="bi bi-arrow-left me-1"></i>
                    Retour aux incidents
                </a>

                <h3 class="fw-bold mt-2 mb-1">
                    {{ $incident->reference }}
                </h3>

                <p class="text-muted mb-0">
                    {{ $incident->titre }}
                </p>
            </div>

            <div class="d-flex gap-2 flex-wrap">
                @if($incident->statut)
                    <span class="badge bg-light text-dark border fs-6">
                        {{ $incident->statut->libelle }}
                    </span>
                @endif

                @if($incident->priorite)
                    <span class="badge bg-warning text-dark fs-6">
                        {{ $incident->priorite->libelle }}
                    </span>
                @endif
            </div>
        </div>

        {{-- MESSAGES --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-triangle me-2"></i>
                {{ $errors->first() }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-4">

            {{-- COLONNE PRINCIPALE --}}
            <div class="col-lg-8">

                {{-- DESCRIPTION --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-exclamation-triangle me-2" style="color:#F28500"></i>
                            Description de l'incident
                        </h5>
                    </div>

                    <div class="card-body">

                        <div class="row g-3 mb-3">

                            <div class="col-md-6">
                                <small class="text-muted d-block mb-1">
                                    Référence
                                </small>
                                <strong>{{ $incident->reference }}</strong>
                            </div>

                            <div class="col-md-6">
                                <small class="text-muted d-block mb-1">
                                    Type d'incident
                                </small>
                                <strong>{{ $incident->type->libelle ?? '-' }}</strong>
                            </div>

                            <div class="col-md-6">
                                <small class="text-muted d-block mb-1">
                                    Priorité
                                </small>
                                <strong>{{ $incident->priorite->libelle ?? '-' }}</strong>
                            </div>

                            <div class="col-md-6">
                                <small class="text-muted d-block mb-1">
                                    Statut actuel
                                </small>
                                <strong>{{ $incident->statut->libelle ?? '-' }}</strong>
                            </div>

                            <div class="col-md-6">
                                <small class="text-muted d-block mb-1">
                                    Date de détection
                                </small>
                                <strong>
                                    {{ $incident->date_detection?->format('d/m/Y à H:i') ?? '-' }}
                                </strong>
                            </div>

                            <div class="col-md-6">
                                <small class="text-muted d-block mb-1">
                                    Date de déclaration
                                </small>
                                <strong>
                                    {{ $incident->date_declaration?->format('d/m/Y à H:i') ?? '-' }}
                                </strong>
                            </div>

                        </div>

                        <hr>

                        <div class="small text-muted fw-semibold mb-2">
                            Description détaillée
                        </div>

                        <div style="white-space:pre-wrap; overflow-wrap:anywhere">{{ $incident->description }}</div>

                        @if($incident->cause)
                            <hr>
                            <div class="small text-muted fw-semibold mb-2">
                                Cause identifiée
                            </div>
                            <div style="white-space:pre-wrap; overflow-wrap:anywhere">{{ $incident->cause }}</div>
                        @endif

                        @if($incident->solution)
                            <hr>
                            <div class="small text-muted fw-semibold mb-2">
                                Solution apportée
                            </div>
                            <div style="white-space:pre-wrap; overflow-wrap:anywhere">{{ $incident->solution }}</div>
                        @endif

                    </div>
                </div>

                {{-- HISTORIQUE --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-clock-history me-2" style="color:#F28500"></i>
                            Historique de l'incident

                            <span class="badge bg-secondary ms-1">
                                {{ $incident->historiques->count() }}
                            </span>
                        </h5>
                    </div>

                    <div class="card-body">

                        @forelse($incident->historiques as $historique)

                            <div class="d-flex gap-3 pb-3 mb-3 border-bottom">

                                <div class="rounded-circle bg-light p-2 text-warning flex-shrink-0"
                                    style="width:40px;height:40px;text-align:center">
                                    <i class="bi bi-clock-history"></i>
                                </div>

                                <div class="flex-grow-1">

                                    <strong>
                                        {{ $historique->description ?? $historique->action }}
                                    </strong>

                                    <div class="small text-muted mt-1">

                                        <i class="bi bi-person me-1"></i>

                                        {{ trim(($historique->user->firstname ?? '') . ' ' . ($historique->user->name ?? '')) ?: 'Système' }}

                                        <span class="mx-1">—</span>

                                        <i class="bi bi-calendar3 me-1"></i>

                                        {{ $historique->created_at?->format('d/m/Y à H:i') }}

                                    </div>

                                </div>

                            </div>

                        @empty

                            <div class="text-center text-muted py-3">
                                <i class="bi bi-clock-history fs-3"></i>
                                <p class="mb-0 mt-2">
                                    Aucun historique disponible.
                                </p>
                            </div>

                        @endforelse

                    </div>
                </div>

                {{-- TICKETS ASSOCIES --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div
                        class="card-header bg-white py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">

                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-ticket-perforated me-2" style="color:#F28500"></i>
                            Tickets associés

                            <span class="badge bg-secondary ms-1">
                                {{ $incident->tickets->count() }}
                            </span>
                        </h5>

                        @if($peutModifierIncident)
                            <button type="button" class="btn btn-sm text-white" style="background-color:#F28500"
                                data-bs-toggle="collapse" data-bs-target="#associationTickets">

                                <i class="bi bi-link-45deg me-1"></i>
                                Associer des tickets

                            </button>
                        @endif

                    </div>

                    <div class="card-body">

                        @forelse($incident->tickets as $ticket)

                            <div
                                class="d-flex justify-content-between align-items-center flex-wrap gap-2 border rounded-3 p-3 mb-2">

                                <div>
                                    <strong>{{ $ticket->reference }}</strong>

                                    <div class="text-muted small">
                                        {{ $ticket->objet }}
                                    </div>
                                </div>

                                <div class="d-flex align-items-center gap-2">

                                    @if($ticket->statut)
                                        <span class="badge bg-light text-dark border">
                                            {{ $ticket->statut->libelle }}
                                        </span>
                                    @endif

                                    <a href="{{ route('support.tickets.show', $ticket) }}"
                                        class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-eye me-1"></i>
                                        Consulter
                                    </a>

                                    @if($peutModifierIncident)
                                                            <form method="POST" action="{{ route('support.incidents.tickets.retirer', [
                                            'incident' => $incident,
                                            'ticket' => $ticket
                                        ]) }}"
                                                                onsubmit="return confirm('Voulez-vous retirer ce ticket de cet incident ?');">

                                                                @csrf
                                                                @method('DELETE')

                                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                                    title="Retirer l'association">

                                                                    <i class="bi bi-link-45deg"></i>
                                                                    Retirer

                                                                </button>

                                                            </form>
                                    @endif

                                </div>

                            </div>

                        @empty

                            <div class="text-center text-muted py-3">
                                <i class="bi bi-ticket-perforated fs-3"></i>
                                <p class="mb-0 mt-2">
                                    Aucun ticket associé à cet incident.
                                </p>
                            </div>

                        @endforelse

                    </div>


                    @if($peutModifierIncident)
                        <div class="collapse {{ request()->filled('recherche_tickets') ? 'show' : '' }}"
                            id="associationTickets">

                            <div class="card-body border-top bg-light">

                                <h6 class="fw-bold mb-3">
                                    <i class="bi bi-link-45deg me-1"></i>
                                    Sélectionner les tickets à associer
                                </h6>

                                <form method="GET" action="{{ route('support.incidents.show', $incident) }}" class="mb-3">

                                    <div class="input-group">

                                        <input type="text" name="recherche_tickets" class="form-control"
                                            placeholder="Référence ou objet du ticket..."
                                            value="{{ request('recherche_tickets') }}">

                                        <button class="btn btn-outline-secondary" type="submit">
                                            <i class="bi bi-search"></i>
                                            Rechercher
                                        </button>

                                    </div>

                                </form>

                                <form method="POST" action="{{ route('support.incidents.tickets.associer', $incident) }}">

                                    @csrf

                                    @forelse($ticketsDisponibles as $ticketDisponible)

                                        <label class="d-flex align-items-start gap-2 border rounded-3 p-3 mb-2 bg-white">

                                            <input type="checkbox" name="ticket_ids[]" value="{{ $ticketDisponible->id }}"
                                                class="form-check-input mt-1">

                                            <span class="flex-grow-1">

                                                <strong>
                                                    {{ $ticketDisponible->reference }}
                                                </strong>

                                                <span class="text-muted small d-block">
                                                    {{ $ticketDisponible->objet }}
                                                </span>

                                                <span class="small text-muted">
                                                    Statut :
                                                    {{ $ticketDisponible->statut->libelle ?? '-' }}
                                                </span>

                                            </span>

                                        </label>

                                    @empty

                                        <div class="alert alert-info mb-3">
                                            Aucun ticket disponible à associer.
                                        </div>

                                    @endforelse

                                    @if($ticketsDisponibles->count())

                                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3">

                                            <button type="submit" class="btn btn-sm text-white" style="background-color:#F28500">

                                                <i class="bi bi-link-45deg me-1"></i>
                                                Confirmer l'association

                                            </button>

                                            <span class="small text-muted">
                                                {{ $ticketsDisponibles->total() }} ticket(s) disponible(s)
                                            </span>

                                        </div>

                                        <div class="mt-3">
                                            {{ $ticketsDisponibles->links() }}
                                        </div>

                                    @endif

                                </form>

                            </div>

                        </div>
                    @endif

                </div>

            </div>

            {{-- COLONNE LATERALE --}}
            <div class="col-lg-4">

                {{-- DECLARANT --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-person-circle me-2" style="color:#F28500"></i>
                            Déclarant
                        </h5>
                    </div>

                    <div class="card-body">

                        <strong>
                            {{ trim(($incident->declarant->firstname ?? '') . ' ' . ($incident->declarant->name ?? '')) ?: '-' }}
                        </strong>

                        @if($incident->declarant?->email)
                            <div class="small text-muted mt-1">
                                <i class="bi bi-envelope me-1"></i>
                                {{ $incident->declarant->email }}
                            </div>
                        @endif

                        @if($incident->declarant?->telephone)
                            <div class="small text-muted mt-1">
                                <i class="bi bi-telephone me-1"></i>
                                {{ $incident->declarant->telephone }}
                            </div>
                        @endif

                    </div>
                </div>

                {{-- RESPONSABLE --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-person-gear me-2" style="color:#F28500"></i>
                            Responsable
                        </h5>
                    </div>

                    <div class="card-body">

                        @if($incident->responsable)

                            <strong>
                                {{ trim(($incident->responsable->firstname ?? '') . ' ' . ($incident->responsable->name ?? '')) }}
                            </strong>

                            @if($incident->responsable->email)
                                <div class="small text-muted mt-1">
                                    <i class="bi bi-envelope me-1"></i>
                                    {{ $incident->responsable->email }}
                                </div>
                            @endif

                        @else

                            <span class="text-muted">
                                <i class="bi bi-person-dash me-1"></i>
                                Aucun responsable affecté
                            </span>

                        @endif

                        {{-- AFFECTATION DU RESPONSABLE --}}
                        @can('support.incidents.manage')

                            @if($incident->statut?->code !== 'CLOTURE')

                                <hr>

                                <form method="POST" action="{{ route('support.incidents.responsable.update', $incident) }}">

                                    @csrf
                                    @method('PATCH')

                                    <label for="user_responsable_id" class="form-label fw-semibold">
                                        Affecter un responsable
                                    </label>

                                    <select name="user_responsable_id" id="user_responsable_id" class="form-select" required>

                                        <option value="">Choisir un technicien ou responsable</option>

                                        @foreach($responsablesDisponibles as $responsable)
                                            <option value="{{ $responsable->id }}"
                                                @selected($incident->user_responsable_id == $responsable->id)>
                                                {{ trim($responsable->firstname . ' ' . $responsable->name) }}
                                                @if($responsable->email)
                                                    — {{ $responsable->email }}
                                                @endif
                                            </option>
                                        @endforeach

                                    </select>

                                    <button type="submit" class="btn btn-sm btn-warning mt-3">
                                        <i class="bi bi-person-check me-1"></i>
                                        Affecter
                                    </button>

                                </form>

                            @endif

                        @endcan

                    </div>
                </div>

                {{-- SUIVI --}}
<div class="card border-0 shadow-sm mb-4">

    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold">
            <i class="bi bi-calendar-check me-2" style="color:#F28500"></i>
            Suivi de l'incident
        </h5>
    </div>

    <div class="card-body">

        {{-- DATES DE SUIVI --}}
        <div class="mb-3">
            <small class="text-muted d-block">
                Prise en charge
            </small>
            <strong>
                {{ $incident->date_prise_en_charge?->format('d/m/Y à H:i') ?? 'Non prise en charge' }}
            </strong>
        </div>

        <div class="mb-3">
            <small class="text-muted d-block">
                Résolution
            </small>
            <strong>
                {{ $incident->date_resolution?->format('d/m/Y à H:i') ?? 'Non résolu' }}
            </strong>
        </div>

        <div class="mb-3">
            <small class="text-muted d-block">
                Clôture
            </small>
            <strong>
                {{ $incident->date_cloture?->format('d/m/Y à H:i') ?? 'Non clôturé' }}
            </strong>
        </div>

        {{-- ACTIONS DE SUIVI --}}
        @php
            $utilisateur = auth()->user();

            $peutGererIncident = $utilisateur->can('support.incidents.manage');

            $peutSuivreIncident = $peutGererIncident
                || (
                    $utilisateur->can('support.incidents.update')
                    && $incident->user_responsable_id == $utilisateur->id
                );

            $statutActuel = $incident->statut?->code;

            $transitionsPossibles = [
                'DECLARE' => ['QUALIFIE'],
                'QUALIFIE' => ['EN_COURS'],
                'EN_COURS' => ['EN_ATTENTE', 'RESOLU'],
                'EN_ATTENTE' => ['EN_COURS'],
                'RESOLU' => $peutGererIncident ? ['CLOTURE'] : [],
                'CLOTURE' => [],
            ];

            $statutsPossibles = $transitionsPossibles[$statutActuel] ?? [];
        @endphp

        @if($peutSuivreIncident && count($statutsPossibles) > 0)

            <hr>

            <form method="POST"
                  action="{{ route('support.incidents.statut.update', $incident) }}">

                @csrf
                @method('PATCH')

                <div class="mb-3">
                    <label for="statut_code" class="form-label fw-semibold">
                        Faire évoluer le statut
                    </label>

                    <select name="statut_code"
                            id="statut_code"
                            class="form-select"
                            required>

                        <option value="">Choisir une action</option>

                        @foreach($statutsPossibles as $code)
                            <option value="{{ $code }}">
                                @switch($code)
                                    @case('QUALIFIE')
                                        Qualifier l'incident
                                        @break

                                    @case('EN_COURS')
                                        Prendre en charge / Reprendre
                                        @break

                                    @case('EN_ATTENTE')
                                        Mettre en attente
                                        @break

                                    @case('RESOLU')
                                        Déclarer comme résolu
                                        @break

                                    @case('CLOTURE')
                                        Clôturer définitivement
                                        @break
                                @endswitch
                            </option>
                        @endforeach

                    </select>
                </div>

                <div class="mb-3">
                    <label for="motif_attente" class="form-label">
                        Motif de mise en attente
                    </label>
                    <textarea name="motif_attente"
                              id="motif_attente"
                              class="form-control"
                              rows="2"
                              placeholder="Préciser pourquoi l'incident est mis en attente..."></textarea>
                </div>

                <div class="mb-3">
                    <label for="cause" class="form-label">
                        Cause de l'incident
                    </label>
                    <textarea name="cause"
                              id="cause"
                              class="form-control"
                              rows="2"
                              placeholder="Préciser la cause identifiée..."></textarea>
                </div>

                <div class="mb-3">
                    <label for="solution" class="form-label">
                        Solution apportée
                    </label>
                    <textarea name="solution"
                              id="solution"
                              class="form-control"
                              rows="2"
                              placeholder="Décrire la solution mise en œuvre..."></textarea>
                </div>

                <button type="submit" class="btn btn-warning w-100">
                    <i class="bi bi-arrow-repeat me-1"></i>
                    Enregistrer le changement
                </button>

            </form>

        @elseif($statutActuel === 'CLOTURE')

            <hr>

            <div class="alert alert-success mb-0">
                <i class="bi bi-check-circle me-1"></i>
                Cet incident est définitivement clôturé.
            </div>

        @endif

    </div>
</div>

            </div>

        </div>

    </div>

@endsection
