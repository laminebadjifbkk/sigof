@extends('layout.user-layout')

@section('title', 'ONFP | Détail du ticket')

@section('space-work')

    <div class="container-fluid py-3">

        {{-- EN-TÊTE --}}
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
            <div>
                <a href="{{ route('support.tickets.index') }}" class="text-decoration-none text-muted">
                    <i class="bi bi-arrow-left me-1"></i>
                    Retour aux tickets
                </a>

                <h3 class="fw-bold mt-2 mb-1">{{ $ticket->reference }}</h3>
                <p class="text-muted mb-0">{{ $ticket->objet }}</p>
            </div>

            @if (
                $ticket->user_assigne_id == auth()->id() &&
                    auth()->user()->can('support.tickets.take-charge') &&
                    $ticket->statut &&
                    !in_array($ticket->statut->code, ['EN_COURS', 'RESOLU', 'CLOTURE', 'REJETE']))
                <form method="POST" action="{{ route('support.tickets.take-charge', $ticket) }}">
                    @csrf
                    <button class="btn text-white" style="background:#F28500" type="submit">
                        <i class="bi bi-play-circle me-1"></i>
                        Prendre en charge
                    </button>
                </form>
            @endif
        </div>

        {{-- MESSAGES --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-triangle me-2"></i>
                {{ $errors->first() }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-4">

            {{-- ===================================================== --}}
            {{-- COLONNE PRINCIPALE --}}
            {{-- ===================================================== --}}
            <div class="col-lg-8">

                {{-- DESCRIPTION DE LA DEMANDE --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-file-earmark-text me-2" style="color:#F28500"></i>
                            Description de la demande
                        </h5>
                    </div>

                    <div class="card-body">

                        <div class="row g-3 mb-3">

                            <div class="col-md-6">
                                <small class="text-muted d-block mb-1">Catégorie</small>
                                <strong>{{ $ticket->categorie->libelle ?? '-' }}</strong>
                            </div>

                            <div class="col-md-6">
                                <small class="text-muted d-block mb-1">Type de demande</small>
                                <strong>{{ $ticket->type->libelle ?? '-' }}</strong>
                            </div>

                            <div class="col-md-6">
                                <small class="text-muted d-block mb-1">Priorité</small>
                                <strong>{{ $ticket->priorite->libelle ?? '-' }}</strong>
                            </div>

                            <div class="col-md-6">
                                <small class="text-muted d-block mb-1">Statut actuel</small>
                                <span class="badge bg-light text-dark border">
                                    {{ $ticket->statut->libelle ?? '-' }}
                                </span>
                            </div>

                        </div>

                        <hr>

                        <div class="small text-muted fw-semibold mb-2">
                            Description détaillée
                        </div>

                        <div style="white-space:pre-wrap; overflow-wrap:anywhere">{{ $ticket->description }}</div>

                    </div>
                </div>


                {{-- HISTORIQUE DU TICKET --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-clock-history me-2" style="color:#F28500"></i>
                            Historique du ticket
                        </h5>
                    </div>

                    <div class="card-body">

                        @forelse($ticket->historiques as $historique)
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
                                <p class="mb-0 mt-2">Aucun historique disponible.</p>
                            </div>
                        @endforelse

                    </div>
                </div>


                {{-- COMMENTAIRES --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-chat-left-text me-2" style="color:#F28500"></i>
                            Échanges et commentaires
                            <span class="badge bg-secondary ms-1">
                                {{ $ticket->commentaires->count() }}
                            </span>
                        </h5>
                    </div>

                    <div class="card-body">

                        @forelse($ticket->commentaires as $commentaire)
                            <div class="border rounded-3 p-3 mb-3">

                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">

                                    <strong>
                                        <i class="bi bi-person-circle text-muted me-1"></i>
                                        {{ trim(($commentaire->user->firstname ?? '') . ' ' . ($commentaire->user->name ?? '')) ?: 'Utilisateur' }}
                                    </strong>

                                    <small class="text-muted">
                                        {{ $commentaire->created_at?->format('d/m/Y à H:i') }}
                                    </small>

                                </div>

                                <div style="white-space:pre-wrap; overflow-wrap:anywhere">{{ $commentaire->contenu }}</div>

                                <div class="mt-3">
                                    @if ($commentaire->visible_demandeur)
                                        <span class="badge bg-success-subtle text-success">
                                            <i class="bi bi-eye me-1"></i>
                                            Visible par le demandeur
                                        </span>
                                    @else
                                        <span class="badge bg-warning-subtle text-dark">
                                            <i class="bi bi-lock me-1"></i>
                                            Note interne
                                        </span>
                                    @endif
                                </div>

                            </div>

                        @empty
                            <div class="text-center text-muted py-3">
                                <i class="bi bi-chat-left-text fs-3"></i>
                                <p class="mb-0 mt-2">Aucun commentaire pour le moment.</p>
                            </div>
                        @endforelse


                        {{-- FORMULAIRE COMMENTAIRE --}}
                        @can('support.commentaires.create')

                            <hr class="my-4">

                            <form method="POST" action="{{ route('support.tickets.commentaires.store', $ticket) }}">
                                @csrf

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">
                                        Ajouter un commentaire
                                    </label>

                                    <textarea name="contenu" class="form-control @error('contenu') is-invalid @enderror" rows="4" maxlength="5000"
                                        placeholder="Saisissez votre commentaire..." required>{{ old('contenu') }}</textarea>

                                    @error('contenu')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                @can('support.commentaires.view')
                                    <div class="form-check mb-3">
                                        <input type="hidden" name="visible_demandeur" value="0">

                                        <input class="form-check-input" type="checkbox" name="visible_demandeur" value="1"
                                            id="visible_demandeur" checked>

                                        <label class="form-check-label" for="visible_demandeur">
                                            Commentaire visible par le demandeur
                                        </label>
                                    </div>
                                @endcan

                                <div class="text-end">
                                    <button type="submit" class="btn text-white" style="background:#F28500">
                                        <i class="bi bi-send me-1"></i>
                                        Envoyer le commentaire
                                    </button>
                                </div>

                            </form>

                        @endcan

                    </div>
                </div>


                {{-- ================================================= --}}
                {{-- INTERVENTIONS --}}
                {{-- ================================================= --}}

                {{-- FORMULAIRE D'INTERVENTION --}}
                @if (auth()->user()->can('support.interventions.create') &&
                        (auth()->user()->can('support.tickets.view-all') || (int) $ticket->user_assigne_id === (int) auth()->id()) &&
                        !$ticket->statut?->est_terminal)
                    <div class="card border-0 shadow-sm mb-4">

                        <div class="card-header bg-white py-3">
                            <h5 class="mb-1 fw-bold">
                                <i class="bi bi-tools me-2" style="color:#F28500"></i>
                                Enregistrer une intervention
                            </h5>

                            <small class="text-muted">
                                Renseignez le diagnostic et les travaux effectués.
                            </small>
                        </div>

                        <div class="card-body">

                            <form method="POST" action="{{ route('support.tickets.interventions.store', $ticket) }}">
                                @csrf

                                <div class="row g-3">

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            Date de l'intervention <span class="text-danger">*</span>
                                        </label>

                                        <input type="datetime-local" name="date_intervention"
                                            class="form-control @error('date_intervention') is-invalid @enderror"
                                            value="{{ old('date_intervention', now()->format('Y-m-d\TH:i')) }}" required>

                                        @error('date_intervention')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            Durée en minutes <span class="text-danger">*</span>
                                        </label>

                                        <input type="number" name="duree_minutes"
                                            class="form-control @error('duree_minutes') is-invalid @enderror"
                                            min="1" max="100000" value="{{ old('duree_minutes') }}"
                                            placeholder="Ex. : 45" required>

                                        @error('duree_minutes')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label fw-semibold">
                                            Diagnostic <span class="text-danger">*</span>
                                        </label>

                                        <textarea name="diagnostic" class="form-control @error('diagnostic') is-invalid @enderror" rows="3"
                                            maxlength="5000" placeholder="Décrivez l'origine du problème et les vérifications effectuées..." required>{{ old('diagnostic') }}</textarea>

                                        @error('diagnostic')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label fw-semibold">
                                            Travaux réalisés <span class="text-danger">*</span>
                                        </label>

                                        <textarea name="travaux_realises" class="form-control @error('travaux_realises') is-invalid @enderror" rows="3"
                                            maxlength="10000" placeholder="Décrivez les opérations effectuées..." required>{{ old('travaux_realises') }}</textarea>

                                        @error('travaux_realises')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            Résultat <span class="text-danger">*</span>
                                        </label>

                                        <select name="resultat"
                                            class="form-select @error('resultat') is-invalid @enderror" required>

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

                                        @error('resultat')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label fw-semibold">
                                            Observations complémentaires
                                        </label>

                                        <textarea name="observations" class="form-control" rows="2" maxlength="5000"
                                            placeholder="Informations complémentaires éventuelles...">{{ old('observations') }}</textarea>
                                    </div>

                                </div>

                                <div class="text-end mt-4">
                                    <button type="submit" class="btn text-white" style="background:#F28500">
                                        <i class="bi bi-save me-1"></i>
                                        Enregistrer l'intervention
                                    </button>
                                </div>

                            </form>

                        </div>
                    </div>
                @endif


                {{-- HISTORIQUE DES INTERVENTIONS --}}
                @can('support.interventions.view')

                    <div class="card border-0 shadow-sm mb-4">

                        <div class="card-header bg-white py-3">
                            <div class="d-flex justify-content-between align-items-center">

                                <h5 class="mb-0 fw-bold">
                                    <i class="bi bi-tools me-2" style="color:#F28500"></i>
                                    Historique des interventions
                                </h5>

                                <span class="badge rounded-pill text-white" style="background:#F28500">
                                    {{ $ticket->interventions->count() }}
                                </span>

                            </div>
                        </div>

                        <div class="card-body">

                            @forelse($ticket->interventions as $intervention)
                                @php
                                    $resultats = [
                                        'RESOLU' => [
                                            'libelle' => 'Résolu',
                                            'couleur' => 'success',
                                            'icone' => 'bi-check-circle',
                                        ],
                                        'NON_RESOLU' => [
                                            'libelle' => 'Non résolu',
                                            'couleur' => 'danger',
                                            'icone' => 'bi-x-circle',
                                        ],
                                        'EN_ATTENTE' => [
                                            'libelle' => 'En attente',
                                            'couleur' => 'warning',
                                            'icone' => 'bi-hourglass-split',
                                        ],
                                        'A_SUIVRE' => [
                                            'libelle' => 'À suivre',
                                            'couleur' => 'info',
                                            'icone' => 'bi-arrow-repeat',
                                        ],
                                    ];

                                    $resultat = $resultats[$intervention->resultat] ?? [
                                        'libelle' => $intervention->resultat,
                                        'couleur' => 'secondary',
                                        'icone' => 'bi-info-circle',
                                    ];
                                @endphp

                                <div class="border rounded-3 p-3 mb-3">

                                    {{-- EN-TÊTE DE L'INTERVENTION --}}
                                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">

                                        <div>
                                            <div class="fw-bold">
                                                <i class="bi bi-person-circle text-muted me-1"></i>

                                                {{ trim(($intervention->user->firstname ?? '') . ' ' . ($intervention->user->name ?? '')) ?: 'Technicien' }}
                                            </div>

                                            <small class="text-muted">
                                                <i class="bi bi-calendar-event me-1"></i>
                                                {{ $intervention->date_intervention?->format('d/m/Y à H:i') ?? '-' }}
                                            </small>
                                        </div>

                                        <span
                                            class="badge bg-{{ $resultat['couleur'] }}-subtle text-{{ $resultat['couleur'] }}">
                                            <i class="bi {{ $resultat['icone'] }} me-1"></i>
                                            {{ $resultat['libelle'] }}
                                        </span>

                                    </div>

                                    <hr class="my-3">

                                    {{-- DIAGNOSTIC --}}
                                    <div class="mb-3">
                                        <div class="small text-muted fw-semibold mb-1">
                                            <i class="bi bi-search me-1"></i>
                                            Diagnostic
                                        </div>

                                        <div style="white-space:pre-wrap; overflow-wrap:anywhere">
                                            {{ $intervention->diagnostic }}</div>
                                    </div>

                                    {{-- TRAVAUX --}}
                                    <div class="mb-3">
                                        <div class="small text-muted fw-semibold mb-1">
                                            <i class="bi bi-wrench-adjustable me-1"></i>
                                            Travaux réalisés
                                        </div>

                                        <div style="white-space:pre-wrap; overflow-wrap:anywhere">
                                            {{ $intervention->travaux_realises }}</div>
                                    </div>

                                    <div class="row g-3">

                                        <div class="col-md-6">
                                            <div class="small text-muted fw-semibold mb-1">
                                                <i class="bi bi-clock me-1"></i>
                                                Durée de l'intervention
                                            </div>

                                            <strong>{{ $intervention->duree_lisible }}</strong>
                                        </div>

                                        @if ($intervention->observations)
                                            <div class="col-12">
                                                <div class="small text-muted fw-semibold mb-1">
                                                    <i class="bi bi-chat-left-text me-1"></i>
                                                    Observations
                                                </div>

                                                <div style="white-space:pre-wrap; overflow-wrap:anywhere">
                                                    {{ $intervention->observations }}</div>
                                            </div>
                                        @endif

                                    </div>

                                </div>

                            @empty

                                <div class="text-center text-muted py-4">
                                    <i class="bi bi-tools fs-2"></i>
                                    <p class="mb-0 mt-2">
                                        Aucune intervention enregistrée pour ce ticket.
                                    </p>
                                </div>
                            @endforelse

                        </div>
                    </div>

                @endcan

            </div>


            {{-- ===================================================== --}}
            {{-- COLONNE LATERALE --}}
            {{-- ===================================================== --}}
            <div class="col-lg-4">

                {{-- INFORMATIONS GENERALES --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-info-circle me-2" style="color:#F28500"></i>
                            Informations générales
                        </h5>
                    </div>

                    <div class="card-body">

                        <p class="mb-1 small text-muted">Demandeur</p>
                        <p class="fw-semibold">
                            {{ trim(($ticket->demandeur->firstname ?? '') . ' ' . ($ticket->demandeur->name ?? '')) ?: '-' }}
                        </p>

                        <hr class="my-3">

                        <p class="mb-1 small text-muted">Technicien affecté</p>
                        <p class="fw-semibold">
                            {{ $ticket->assigne
                                ? trim(($ticket->assigne->firstname ?? '') . ' ' . ($ticket->assigne->name ?? ''))
                                : 'En attente d’affectation' }}
                        </p>

                        <hr class="my-3">

                        <p class="mb-1 small text-muted">Date de soumission</p>
                        <p class="fw-semibold">
                            {{ $ticket->date_soumission?->format('d/m/Y à H:i') ?? '-' }}
                        </p>

                        <p class="mb-1 small text-muted">Prise en charge</p>
                        <p class="fw-semibold">
                            {{ $ticket->date_prise_en_charge?->format('d/m/Y à H:i') ?? 'Non prise en charge' }}
                        </p>

                        <p class="mb-1 small text-muted">Résolution</p>
                        <p class="fw-semibold">
                            {{ $ticket->date_resolution?->format('d/m/Y à H:i') ?? '-' }}
                        </p>

                        <p class="mb-1 small text-muted">Clôture</p>
                        <p class="fw-semibold mb-0">
                            {{ $ticket->date_cloture?->format('d/m/Y à H:i') ?? '-' }}
                        </p>

                    </div>
                </div>


                {{-- AFFECTATION DU TECHNICIEN --}}
                @can('support.tickets.assign')

                    @if (!$ticket->statut?->est_terminal)

                        <div class="card border-0 shadow-sm mb-4">

                            <div class="card-header bg-white py-3">
                                <h5 class="mb-0 fw-bold">
                                    <i class="bi bi-person-check me-2" style="color:#F28500"></i>
                                    Affecter un technicien
                                </h5>
                            </div>

                            <div class="card-body">

                                @if ($techniciens->isEmpty())
                                    <div class="alert alert-warning mb-0">
                                        <i class="bi bi-exclamation-triangle me-1"></i>
                                        Aucun utilisateur ne possède actuellement le rôle
                                        technicien-support.
                                    </div>
                                @else
                                    <form method="POST" action="{{ route('support.tickets.assign', $ticket) }}">
                                        @csrf

                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">
                                                Technicien
                                            </label>

                                            <select name="technicien_id" class="form-select" required>

                                                <option value="">
                                                    Sélectionner un technicien
                                                </option>

                                                @foreach ($techniciens as $technicien)
                                                    <option value="{{ $technicien->id }}" @selected($ticket->user_assigne_id == $technicien->id)>

                                                        {{ $technicien->firstname }} {{ $technicien->name }}

                                                    </option>
                                                @endforeach

                                            </select>

                                            @error('technicien_id')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">
                                                Motif de l'affectation
                                            </label>

                                            <textarea name="motif" class="form-control" rows="3"
                                                placeholder="Préciser éventuellement le motif de l'affectation...">{{ old('motif') }}</textarea>

                                            @error('motif')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <button type="submit" class="btn text-white w-100" style="background:#F28500">
                                            <i class="bi bi-person-check me-1"></i>

                                            {{ $ticket->user_assigne_id ? 'Réaffecter le ticket' : 'Affecter le ticket' }}
                                        </button>

                                    </form>
                                @endif

                            </div>
                        </div>

                    @endif

                @endcan


                {{-- HISTORIQUE DES AFFECTATIONS --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-people me-2" style="color:#F28500"></i>
                            Historique des affectations
                        </h5>
                    </div>

                    <div class="card-body">

                        @forelse($ticket->affectations as $affectation)
                            <div class="mb-3 pb-3 border-bottom">

                                <div class="fw-semibold">
                                    <i class="bi bi-person-check me-1 text-muted"></i>

                                    {{ trim(($affectation->nouveauTechnicien->firstname ?? '') . ' ' . ($affectation->nouveauTechnicien->name ?? '')) ?: 'Technicien' }}
                                </div>

                                <div class="small text-muted mt-1">
                                    Affecté le
                                    {{ $affectation->date_affectation?->format('d/m/Y à H:i') ?? '-' }}
                                </div>

                                @if ($affectation->motif)
                                    <div class="small mt-2">
                                        {{ $affectation->motif }}
                                    </div>
                                @endif

                            </div>

                        @empty

                            <div class="text-center text-muted py-3">
                                <i class="bi bi-people fs-3"></i>
                                <p class="mb-0 mt-2">
                                    Aucune affectation enregistrée.
                                </p>
                            </div>
                        @endforelse

                    </div>
                </div>

            </div>

        </div>
    </div>

@endsection
