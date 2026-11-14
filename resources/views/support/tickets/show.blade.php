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

            {{-- Commentaires --}}
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-chat-left-text me-2" style="color:#F28500"></i>
                        Échanges et commentaires
                    </h5>
                </div>

                <div class="card-body">

                    @forelse($ticket->commentaires as $commentaire)
                        <div class="border rounded p-3 mb-3">
                            <div class="d-flex justify-content-between flex-wrap mb-2">
                                <strong>
                                    {{ trim(($commentaire->user->firstname ?? '').' '.($commentaire->user->name ?? '')) ?: 'Utilisateur' }}
                                </strong>

                                <small class="text-muted">
                                    {{ $commentaire->created_at?->format('d/m/Y à H:i') }}
                                </small>
                            </div>

                            <div style="white-space:pre-wrap">{{ $commentaire->contenu }}</div>

                            <div class="mt-2">
                                @if($commentaire->visible_demandeur)
                                    <span class="badge bg-success-subtle text-success">
                                        Visible par le demandeur
                                    </span>
                                @else
                                    <span class="badge bg-warning-subtle text-dark">
                                        Note interne
                                    </span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-muted">Aucun commentaire pour le moment.</p>
                    @endforelse

                    @can('support.commentaires.create')
                        <hr class="my-4">

                        <form method="POST"
                            action="{{ route('support.tickets.commentaires.store', $ticket) }}">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    Ajouter un commentaire
                                </label>

                                <textarea name="contenu"
                                        class="form-control"
                                        rows="4"
                                        maxlength="5000"
                                        placeholder="Saisissez votre commentaire..."
                                        required>{{ old('contenu') }}</textarea>

                                @error('contenu')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            @can('support.commentaires.view')
                                <div class="form-check mb-3">
                                    <input type="hidden" name="visible_demandeur" value="0">

                                    <input class="form-check-input"
                                        type="checkbox"
                                        name="visible_demandeur"
                                        value="1"
                                        id="visible_demandeur"
                                        checked>

                                    <label class="form-check-label" for="visible_demandeur">
                                        Commentaire visible par le demandeur
                                    </label>
                                </div>
                            @endcan

                            <div class="text-end">
                                <button type="submit"
                                        class="btn text-white"
                                        style="background:#F28500">
                                    <i class="bi bi-send me-1"></i>
                                    Envoyer le commentaire
                                </button>
                            </div>
                        </form>
                    @endcan

                </div>
            </div>
        </div>

        @if(auth()->user()->can('support.interventions.create')
    && !auth()->user()->can('support.tickets.view-all')
    && (int) $ticket->user_assigne_id !== (int) auth()->id())
    {{-- Un technicien non affecté ne peut pas intervenir --}}
@elseif(auth()->user()->can('support.interventions.create')
    && !$ticket->statut?->est_terminal)

<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white">
        <h5 class="mb-0">
            <i class="fas fa-tools text-warning me-2"></i>
            Enregistrer une intervention
        </h5>
    </div>

    <div class="card-body">
        <form method="POST"
              action="{{ route('support.tickets.interventions.store', $ticket) }}">
            @csrf

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Date de l'intervention *</label>
                    <input type="datetime-local"
                           name="date_intervention"
                           class="form-control"
                           value="{{ old('date_intervention', now()->format('Y-m-d\TH:i')) }}"
                           required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Durée (minutes) *</label>
                    <input type="number"
                           name="duree_minutes"
                           class="form-control"
                           min="1"
                           value="{{ old('duree_minutes') }}"
                           placeholder="Ex. : 45"
                           required>
                </div>

                <div class="col-12 mb-3">
                    <label class="form-label">Diagnostic *</label>
                    <textarea name="diagnostic"
                              class="form-control"
                              rows="3"
                              required>{{ old('diagnostic') }}</textarea>
                </div>

                <div class="col-12 mb-3">
                    <label class="form-label">Travaux réalisés *</label>
                    <textarea name="travaux_realises"
                              class="form-control"
                              rows="3"
                              required>{{ old('travaux_realises') }}</textarea>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Résultat *</label>
                    <select name="resultat" class="form-select" required>
                        <option value="">Sélectionner un résultat</option>
                        <option value="RESOLU" @selected(old('resultat') === 'RESOLU')>
                            Résolu
                        </option>
                        <option value="NON_RESOLU" @selected(old('resultat') === 'NON_RESOLU')>
                            Non résolu
                        </option>
                        <option value="EN_ATTENTE" @selected(old('resultat') === 'EN_ATTENTE')>
                            En attente
                        </option>
                        <option value="A_SUIVRE" @selected(old('resultat') === 'A_SUIVRE')>
                            À suivre
                        </option>
                    </select>
                </div>

                <div class="col-12 mb-3">
                    <label class="form-label">Observations complémentaires</label>
                    <textarea name="observations"
                              class="form-control"
                              rows="2">{{ old('observations') }}</textarea>
                </div>
            </div>

            <button type="submit" class="btn text-white"
                    style="background-color:#F28500;">
                <i class="fas fa-save me-1"></i>
                Enregistrer l'intervention
            </button>
        </form>
    </div>
</div>
@endif

<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white">
        <h5 class="mb-0">
            <i class="fas fa-history text-warning me-2"></i>
            Historique des interventions
            <span class="badge bg-secondary">{{ $ticket->interventions->count() }}</span>
        </h5>
    </div>

    <div class="card-body">
        @forelse($ticket->interventions as $intervention)
            <div class="border rounded p-3 mb-3">
                <div class="d-flex justify-content-between flex-wrap mb-2">
                    <strong>
                        {{ $intervention->user->firstname ?? '' }}
                        {{ $intervention->user->name ?? 'Technicien' }}
                    </strong>

                    <small class="text-muted">
                        {{ $intervention->date_intervention->format('d/m/Y à H:i') }}
                    </small>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <small class="text-muted d-block">Diagnostic</small>
                        {{ $intervention->diagnostic }}
                    </div>

                    <div class="col-md-6">
                        <small class="text-muted d-block">Travaux réalisés</small>
                        {{ $intervention->travaux_realises }}
                    </div>

                    <div class="col-md-4">
                        <small class="text-muted d-block">Durée</small>
                        {{ $intervention->duree_lisible }}
                    </div>

                    <div class="col-md-4">
                        <small class="text-muted d-block">Résultat</small>
                        @php
                            $couleurs = [
                                'RESOLU' => 'success',
                                'NON_RESOLU' => 'danger',
                                'EN_ATTENTE' => 'warning',
                                'A_SUIVRE' => 'info',
                            ];

                            $libelles = [
                                'RESOLU' => 'Résolu',
                                'NON_RESOLU' => 'Non résolu',
                                'EN_ATTENTE' => 'En attente',
                                'A_SUIVRE' => 'À suivre',
                            ];
                        @endphp

                        <span class="badge bg-{{ $couleurs[$intervention->resultat] ?? 'secondary' }}">
                            {{ $libelles[$intervention->resultat] ?? $intervention->resultat }}
                        </span>
                    </div>

                    @if($intervention->observations)
                        <div class="col-12">
                            <small class="text-muted d-block">Observations</small>
                            {{ $intervention->observations }}
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center text-muted py-4">
                <i class="fas fa-tools fa-2x mb-2"></i>
                <p class="mb-0">Aucune intervention enregistrée pour ce ticket.</p>
            </div>
        @endforelse
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

            @can('support.tickets.assign')
                @if(!$ticket->statut->est_terminal)
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fw-bold">
                                <i class="bi bi-person-check me-2" style="color:#F28500"></i>
                                Affecter un technicien
                            </h5>
                        </div>

                        <div class="card-body">
                            @if($techniciens->isEmpty())
                                <div class="alert alert-warning mb-0">
                                    Aucun utilisateur ne possède actuellement le rôle
                                    technicien-support.
                                </div>
                            @else
                                <form method="POST"
                                    action="{{ route('support.tickets.assign', $ticket) }}">
                                    @csrf

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">
                                            Technicien
                                        </label>

                                        <select name="technicien_id" class="form-select" required>
                                            <option value="">Sélectionner un technicien</option>

                                            @foreach($techniciens as $technicien)
                                                <option value="{{ $technicien->id }}"
                                                    @selected($ticket->user_assigne_id == $technicien->id)>
                                                    {{ $technicien->firstname }} {{ $technicien->name }}
                                                </option>
                                            @endforeach
                                        </select>

                                        @error('technicien_id')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">
                                            Motif de l'affectation
                                        </label>

                                        <textarea name="motif"
                                                class="form-control"
                                                rows="3"
                                                placeholder="Préciser éventuellement le motif de l'affectation...">{{ old('motif') }}</textarea>

                                        @error('motif')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <button type="submit"
                                            class="btn text-white w-100"
                                            style="background:#F28500">
                                        <i class="bi bi-person-check me-1"></i>
                                        {{ $ticket->user_assigne_id ? 'Réaffecter le ticket' : 'Affecter le ticket' }}
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endif
            @endcan

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
