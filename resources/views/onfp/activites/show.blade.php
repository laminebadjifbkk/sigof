@extends('layout.user-layout')

@section('title', $activite->reference)

@section('space-work')

    <div class="container-fluid py-4">

        {{-- En-tête --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">

            <div class="d-flex align-items-center flex-grow-1" style="min-width: 0;">

                <a href="{{ route('onfp.activites.index') }}" class="btn btn-sm btn-outline-secondary me-3 flex-shrink-0">

                    <i class="bi bi-arrow-left"></i>

                </a>

                <div style="min-width: 0;">

                    <div class="d-flex align-items-center gap-2 flex-wrap">

                        <h3 class="mb-0 text-break" style="min-width: 0;">
                            {{ $activite->titre }}
                        </h3>

                        <span class="badge bg-light text-dark border flex-shrink-0">
                            {{ $activite->reference }}
                        </span>

                    </div>

                    <p class="text-muted mb-0 mt-1 text-break">
                        {{ $activite->type?->libelle ?? 'Type non défini' }}
                        @if ($activite->direction)
                            · {{ $activite->direction->sigle ?: $activite->direction->name }}
                        @endif
                    </p>

                </div>

            </div>


            <div class="d-flex gap-2 flex-shrink-0">

                <a href="{{ route('onfp.activites.edit', $activite) }}" class="btn btn-sm btn-primary">

                    <i class="bi bi-pencil me-1"></i>
                    Modifier

                </a>

            </div>

        </div>

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

            <a href="{{ route('onfp.activites.create') }}" class="btn btn-sm btn-primary">
                <i class="bi bi-plus-circle me-1"></i>
                Nouvelle activité
            </a>

        </div>

        {{-- Messages --}}
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
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif


        {{-- ==============================================================
            BANDE DE STATISTIQUES RAPIDES
            Vue d'ensemble en un coup d'œil du contenu de l'activité.
        =============================================================== --}}
        <div class="row g-2 g-md-3 mb-4">

            @php
                $nbTachesDirectes = $activite->taches->whereNull('sous_activite_id')->count();
                $nbTiers = $activite->tiers->count();
            @endphp

            <div class="col-6 col-md-2">
                <a href="{{ route('onfp.activites.sous-activites.index', $activite) }}"
                    class="card border-0 shadow-sm h-100 text-decoration-none quick-stat-card">
                    <div class="card-body text-center py-3">
                        <i class="bi bi-diagram-3 text-primary fs-4"></i>
                        <div class="fs-5 fw-bold mt-1">{{ $activite->sousActivites->count() }}</div>
                        <div class="small text-muted">Sous-activités</div>
                    </div>
                </a>
            </div>

            <div class="col-6 col-md-2">
                <a href="{{ route('onfp.activites.taches.index', $activite) }}"
                    class="card border-0 shadow-sm h-100 text-decoration-none quick-stat-card">
                    <div class="card-body text-center py-3">
                        <i class="bi bi-check2-square text-primary fs-4"></i>
                        <div class="fs-5 fw-bold mt-1">{{ $nbTachesDirectes }}</div>
                        <div class="small text-muted">Tâches</div>
                    </div>
                </a>
            </div>

            <div class="col-6 col-md-2">
                <a href="{{ route('onfp.activites.indicateurs.index', $activite) }}"
                    class="card border-0 shadow-sm h-100 text-decoration-none quick-stat-card">
                    <div class="card-body text-center py-3">
                        <i class="bi bi-graph-up text-primary fs-4"></i>
                        <div class="fs-5 fw-bold mt-1">{{ $activite->indicateurs->count() }}</div>
                        <div class="small text-muted">Indicateurs</div>
                    </div>
                </a>
            </div>

            <div class="col-6 col-md-2">
                <a href="{{ route('onfp.activites.documents.index', $activite) }}"
                    class="card border-0 shadow-sm h-100 text-decoration-none quick-stat-card">
                    <div class="card-body text-center py-3">
                        <i class="bi bi-folder2-open text-primary fs-4"></i>
                        <div class="fs-5 fw-bold mt-1">{{ $activite->documents->count() }}</div>
                        <div class="small text-muted">Documents</div>
                    </div>
                </a>
            </div>

            <div class="col-6 col-md-2">
                <a href="{{ route('onfp.tiers.index', $activite) }}"
                    class="card border-0 shadow-sm h-100 text-decoration-none quick-stat-card">
                    <div class="card-body text-center py-3">
                        <i class="bi bi-person-vcard text-primary fs-4"></i>
                        <div class="fs-5 fw-bold mt-1">{{ $nbTiers }}</div>
                        <div class="small text-muted">Tiers</div>
                    </div>
                </a>
            </div>

            <div class="col-6 col-md-2">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center py-3">
                        <i class="bi bi-people text-primary fs-4"></i>
                        <div class="fs-5 fw-bold mt-1">{{ $activite->responsables->count() }}</div>
                        <div class="small text-muted">Responsables</div>
                    </div>
                </div>
            </div>

        </div>


        <div class="row g-4">

            {{-- COLONNE PRINCIPALE --}}
            <div class="col-lg-8" style="min-width: 0;">


                {{-- Informations générales --}}
                <div class="card shadow-sm border-0 mb-4">

                    <div class="card-header bg-white py-3">

                        <h5 class="mb-0">
                            <i class="bi bi-info-circle me-2"></i>
                            Informations générales
                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="mb-4">

                            <label class="text-muted small">
                                Titre de l'activité
                            </label>

                            <div class="fw-semibold fs-5 text-break">
                                {{ $activite->titre }}
                            </div>

                        </div>


                        <div class="mb-4">

                            <label class="text-muted small">
                                Description
                            </label>

                            @if ($activite->description)
                                <div class="mt-1 text-break">
                                    {!! nl2br(e($activite->description)) !!}
                                </div>
                            @else
                                <span class="text-muted">
                                    Aucune description renseignée.
                                </span>
                            @endif

                        </div>


                        <div>

                            <label class="text-muted small">
                                Observation
                            </label>

                            @if ($activite->observation)
                                <div class="mt-1 text-break">
                                    {!! nl2br(e($activite->observation)) !!}
                                </div>
                            @else
                                <span class="text-muted">
                                    Aucune observation.
                                </span>
                            @endif

                        </div>

                    </div>

                </div>


                {{-- Progression --}}
                <div class="card shadow-sm border-0 mb-4">

                    <div class="card-header bg-white py-3">

                        <h5 class="mb-0">
                            <i class="bi bi-bar-chart me-2"></i>
                            Progression
                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="d-flex justify-content-between mb-2">

                            <span class="fw-semibold">
                                Avancement
                            </span>

                            <span class="fw-bold">
                                {{ $activite->progression }} %
                            </span>

                        </div>


                        <div class="progress" style="height: 12px;">

                            <div class="progress-bar" role="progressbar"
                                style="width: {{ min($activite->progression, 100) }}%;"
                                aria-valuenow="{{ $activite->progression }}" aria-valuemin="0" aria-valuemax="100">
                            </div>

                        </div>

                    </div>

                </div>


                {{-- Dates --}}
                <div class="card shadow-sm border-0 mb-4">

                    <div class="card-header bg-white py-3">

                        <h5 class="mb-0">
                            <i class="bi bi-calendar3 me-2"></i>
                            Planification et échéances
                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="row g-4">

                            <div class="col-6 col-md-4">

                                <div class="text-muted small">
                                    Date d'enclenchement
                                </div>

                                <div class="fw-semibold">
                                    {{ $activite->date_enclenchement?->format('d/m/Y') ?? '-' }}
                                </div>

                            </div>


                            <div class="col-6 col-md-4">

                                <div class="text-muted small">
                                    Exécution prévue
                                </div>

                                <div class="fw-semibold">
                                    {{ $activite->date_execution_prevue?->format('d/m/Y') ?? '-' }}
                                </div>

                            </div>


                            <div class="col-6 col-md-4">

                                <div class="text-muted small">
                                    Fin prévue
                                </div>

                                <div class="fw-semibold">
                                    {{ $activite->date_fin_prevue?->format('d/m/Y') ?? '-' }}
                                </div>

                            </div>


                            <div class="col-6 col-md-4">

                                <div class="text-muted small">
                                    Exécution réelle
                                </div>

                                <div class="fw-semibold">
                                    {{ $activite->date_execution_reelle?->format('d/m/Y') ?? '-' }}
                                </div>

                            </div>


                            <div class="col-6 col-md-4">

                                <div class="text-muted small">
                                    Fin réelle
                                </div>

                                <div class="fw-semibold">
                                    {{ $activite->date_fin_reelle?->format('d/m/Y') ?? '-' }}
                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Responsables --}}
                <div class="card shadow-sm border-0 mb-4">

                    <div class="card-header bg-white py-3">

                        <h5 class="mb-0">
                            <i class="bi bi-people me-2"></i>
                            Responsables
                        </h5>

                    </div>


                    <div class="card-body">

                        @forelse($activite->responsables as $responsable)

                            @php
                                $nomResponsable = trim(
                                    ($responsable->employee?->user?->firstname ?? '') .
                                        ' ' .
                                        ($responsable->employee?->user?->name ?? ''),
                                );
                            @endphp

                            <div class="d-flex align-items-center mb-3">

                                <div class="rounded-circle bg-primary text-white
                                        d-flex align-items-center justify-content-center me-3 flex-shrink-0"
                                    style="width:42px;height:42px;">

                                    <i class="bi bi-person"></i>

                                </div>


                                <div class="flex-grow-1" style="min-width: 0;">

                                    <div class="fw-semibold text-break">

                                        {{ $nomResponsable !== '' ? $nomResponsable : $responsable->employee?->matricule ?? 'Employé inconnu' }}

                                        @if ($responsable->is_principal)
                                            <span class="badge bg-primary ms-2">
                                                Principal
                                            </span>
                                        @endif

                                    </div>

                                    <div class="text-muted small text-break">

                                        {{ $responsable->employee?->matricule }}

                                        {{ $responsable->role ?? 'Responsable' }}

                                        @if ($responsable->employee?->direction)
                                            ·
                                            {{ $responsable->employee->direction->sigle }}
                                        @endif

                                    </div>

                                </div>

                            </div>

                        @empty

                            <div class="text-muted">
                                Aucun responsable affecté.
                            </div>
                        @endforelse

                    </div>

                </div>


                {{-- Suiveurs --}}
                <div class="card shadow-sm border-0 mb-4">

                    <div class="card-header bg-white py-3">

                        <h5 class="mb-0">
                            <i class="bi bi-eye me-2"></i>
                            Agents de suivi
                        </h5>

                    </div>


                    <div class="card-body d-flex flex-wrap">

                        @forelse($activite->suiveurs as $suiveur)

                            @php
                                $nomSuiveur = trim(
                                    ($suiveur->employee?->user?->firstname ?? '') .
                                        ' ' .
                                        ($suiveur->employee?->user?->name ?? ''),
                                );
                            @endphp

                            <span class="badge bg-light text-dark border me-2 mb-2 px-3 py-2 text-break"
                                style="white-space: normal; max-width: 100%;">

                                <i class="bi bi-person me-1"></i>

                                {{ $nomSuiveur !== '' ? $nomSuiveur : $suiveur->employee?->matricule ?? 'Employé inconnu' }}

                            </span>

                        @empty

                            <span class="text-muted">
                                Aucun agent de suivi affecté.
                            </span>
                        @endforelse

                    </div>

                </div>


                {{-- ==============================================================
                    TIERS INTERVENANTS
                =============================================================== --}}
                <div class="card shadow-sm border-0 mb-4">

                    <div class="card-header bg-white py-3">

                        <div class="d-flex justify-content-between align-items-center gap-2">

                            <h5 class="mb-0">
                                <i class="bi bi-person-vcard me-2"></i>
                                Tiers intervenants
                            </h5>

                            <div class="d-flex align-items-center gap-2">

                                <span class="badge bg-secondary">
                                    {{ $activite->tiers->count() }}
                                </span>

                                <a href="{{ route('onfp.tiers.index', $activite) }}"
                                    class="btn btn-sm btn-outline-primary" title="Gérer les tiers">
                                    <i class="bi bi-list-ul me-1"></i>
                                    Gérer
                                </a>

                            </div>

                        </div>

                    </div>

                    <div class="card-body">

                        @forelse ($activite->tiers as $activiteTier)
                            <div class="d-flex align-items-center mb-3">

                                <div class="rounded-circle bg-info text-white
                                        d-flex align-items-center justify-content-center me-3 flex-shrink-0"
                                    style="width:42px;height:42px;">
                                    <i class="bi bi-person-badge"></i>
                                </div>

                                <div class="flex-grow-1" style="min-width: 0;">

                                    <div class="fw-semibold text-break">
                                        {{ $activiteTier->tiers?->nom ?? 'Tiers supprimé' }}
                                    </div>

                                    <div class="text-muted small text-break">

                                        @if ($activiteTier->role)
                                            {{ $activiteTier->role }}
                                        @endif

                                        @if ($activiteTier->tiers?->organisation)
                                            · {{ $activiteTier->tiers->organisation }}
                                        @endif

                                        @if (!$activiteTier->role && !$activiteTier->tiers?->organisation)
                                            <span class="fst-italic">Rôle non précisé</span>
                                        @endif

                                    </div>

                                </div>

                            </div>
                        @empty
                            <div class="text-muted">
                                Aucun tiers intervenant affecté.
                            </div>
                        @endforelse

                    </div>

                </div>


                {{-- Sous-activités --}}
                <div class="card shadow-sm border-0 mb-4">

                    <div class="card-header bg-white py-3">

                        <div class="d-flex justify-content-between align-items-center gap-2">

                            <h5 class="mb-0">
                                <i class="bi bi-diagram-3 me-2"></i>
                                Sous-activités
                            </h5>

                            <div class="d-flex align-items-center gap-2">

                                <span class="badge bg-secondary">
                                    {{ $activite->sousActivites->count() }}
                                </span>

                                <a href="{{ route('onfp.activites.sous-activites.index', $activite) }}"
                                    class="btn btn-sm btn-outline-primary" title="Gérer les sous-activités">

                                    <i class="bi bi-list-ul me-1"></i>
                                    Gérer

                                </a>

                            </div>

                        </div>

                    </div>


                    <div class="card-body">

                        @forelse($activite->sousActivites->take(5) as $sousActivite)
                            <div class="border rounded p-3 mb-3">

                                <div class="d-flex justify-content-between align-items-start gap-2">

                                    <div style="min-width: 0;">

                                        <div class="fw-semibold text-break">

                                            {{ $sousActivite->titre }}

                                        </div>

                                        @if ($sousActivite->reference)
                                            <div class="small text-muted">

                                                {{ $sousActivite->reference }}

                                            </div>
                                        @endif

                                    </div>


                                    <a href="{{ route('onfp.activites.sous-activites.show', [$activite, $sousActivite]) }}"
                                        class="btn btn-sm btn-outline-secondary flex-shrink-0"
                                        title="Voir la sous-activité">

                                        <i class="bi bi-eye"></i>

                                    </a>

                                </div>


                                <div class="mt-3">

                                    <div class="d-flex justify-content-between small mb-1">

                                        <span class="text-muted">
                                            Progression
                                        </span>

                                        <strong>
                                            {{ $sousActivite->progression }} %
                                        </strong>

                                    </div>


                                    <div class="progress" style="height: 7px;">

                                        <div class="progress-bar" role="progressbar"
                                            style="width: {{ min($sousActivite->progression, 100) }}%;"
                                            aria-valuenow="{{ $sousActivite->progression }}" aria-valuemin="0"
                                            aria-valuemax="100">
                                        </div>

                                    </div>

                                </div>


                                <div class="mt-2">

                                    @php

                                        $statutLabels = [
                                            'a_faire' => 'À faire',
                                            'en_cours' => 'En cours',
                                            'suspendue' => 'Suspendue',
                                            'terminee' => 'Terminée',
                                            'annulee' => 'Annulée',
                                        ];

                                        $statutClasses = [
                                            'a_faire' => 'bg-secondary',
                                            'en_cours' => 'bg-primary',
                                            'suspendue' => 'bg-warning text-dark',
                                            'terminee' => 'bg-success',
                                            'annulee' => 'bg-danger',
                                        ];

                                    @endphp

                                    <span class="badge {{ $statutClasses[$sousActivite->statut] ?? 'bg-secondary' }}">

                                        {{ $statutLabels[$sousActivite->statut] ?? $sousActivite->statut }}

                                    </span>

                                </div>

                            </div>

                        @empty

                            <div class="text-center py-3">

                                <i class="bi bi-diagram-3 text-muted fs-2"></i>

                                <div class="text-muted mt-2">
                                    Aucune sous-activité pour le moment.
                                </div>

                            </div>
                        @endforelse


                        @if ($activite->sousActivites->count() > 5)
                            <div class="text-center mt-3">

                                <a href="{{ route('onfp.activites.sous-activites.index', $activite) }}"
                                    class="btn btn-sm btn-outline-secondary">

                                    Voir toutes les
                                    {{ $activite->sousActivites->count() }}
                                    sous-activités

                                    <i class="bi bi-arrow-right ms-1"></i>

                                </a>

                            </div>
                        @endif

                    </div>

                </div>

                {{-- =========================TÂCHES========================= --}}
                <div class="card shadow-sm border-0 mb-4">

                    <div class="card-header bg-white py-3">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>
                                <h5 class="mb-1">
                                    <i class="bi bi-check2-square me-2"></i>
                                    Tâches
                                </h5>

                                <small class="text-muted">
                                    Tâches directement rattachées à cette activité
                                </small>
                            </div>
                            <div class="d-flex align-items-center gap-2">

                                <span class="badge bg-secondary">
                                    {{ $nbTachesDirectes }}
                                </span>

                                <a href="{{ route('onfp.activites.taches.index', ['activite' => $activite]) }}"
                                    class="btn btn-sm btn-outline-primary" title="Voir toutes les tâches">

                                    <i class="bi bi-list-ul me-1"></i>
                                    Gérer

                                </a>

                            </div>

                        </div>

                    </div>

                    <div class="card-body">

                        @forelse($activite->taches->whereNull('sous_activite_id') as $tache)
                            <div class="border rounded p-3 mb-3">

                                <div class="d-flex justify-content-between align-items-start gap-3">

                                    {{-- Informations --}}
                                    <div class="flex-grow-1 min-width-0">

                                        <div class="fw-semibold text-break mb-1">
                                            {{ $tache->titre }}
                                        </div>

                                        @if ($tache->reference)
                                            <div class="small text-muted mb-2">
                                                Réf. : {{ $tache->reference }}
                                            </div>
                                        @endif

                                        <div class="small text-muted">

                                            <span>
                                                {{ $tache->progression }}%
                                            </span>

                                            <span class="mx-1">·</span>

                                            <span>
                                                {{ $tache->statut }}
                                            </span>

                                            @if ($tache->date_echeance)
                                                <span class="mx-1">·</span>

                                                <span>
                                                    Échéance :
                                                    {{ $tache->date_echeance->format('d/m/Y') }}
                                                </span>
                                            @endif

                                        </div>

                                        {{-- Barre de progression --}}
                                        <div class="progress mt-2" style="height: 6px;">

                                            <div class="progress-bar" role="progressbar"
                                                style="width: {{ $tache->progression }}%;"
                                                aria-valuenow="{{ $tache->progression }}" aria-valuemin="0"
                                                aria-valuemax="100">
                                            </div>

                                        </div>

                                    </div>

                                    {{-- Actions --}}
                                    <div class="d-flex gap-1 flex-shrink-0">

                                        <a href="{{ route('onfp.activites.taches.show', [$activite, $tache]) }}"
                                            class="btn btn-sm btn-outline-primary" title="Voir la tâche">

                                            <i class="bi bi-eye"></i>

                                        </a>

                                        <a href="{{ route('onfp.activites.taches.edit', [$activite, $tache]) }}"
                                            class="btn btn-sm btn-outline-warning" title="Modifier">

                                            <i class="bi bi-pencil"></i>

                                        </a>

                                    </div>

                                </div>

                            </div>

                        @empty

                            <div class="text-center py-4">

                                <i class="bi bi-check2-square fs-2 text-muted"></i>

                                <p class="text-muted mb-3 mt-2">
                                    Aucune tâche directement rattachée à cette activité.
                                </p>

                                <a href="{{ route('onfp.activites.taches.create', ['activite' => $activite]) }}"
                                    class="btn btn-sm btn-primary">

                                    <i class="bi bi-plus-circle me-1"></i>
                                    Nouvelle tâche

                                </a>

                            </div>
                        @endforelse

                    </div>

                </div>


                {{-- Historique --}}
                <div class="card shadow-sm border-0">

                    <div class="card-header bg-white py-3">

                        <h5 class="mb-0">
                            <i class="bi bi-clock-history me-2"></i>
                            Historique
                        </h5>

                    </div>

                    <div class="card-body">

                        @forelse ($activite->historiques->sortByDesc('created_at') as $historique)
                            <div class="border-start ps-3 mb-4">

                                <div class="fw-semibold">
                                    {{ ucfirst($historique->action) }}
                                </div>

                                <div class="small text-muted mb-1">
                                    {{ $historique->created_at?->format('d/m/Y H:i') }}
                                    @if ($historique->employee)
                                        ·
                                        {{ $historique?->employee?->user?->firstname . ' ' . $historique?->employee?->user?->name . ', ' . $historique?->employee?->fonction?->name }}
                                    @endif
                                </div>

                                @if ($historique->action === 'modification' && $historique->ancien_statut !== $historique->nouveau_statut)
                                    <div class="text-break">
                                        Statut :
                                        {{ $statuts[$historique->ancien_statut]['label'] ?? $historique->ancien_statut }}
                                        →
                                        {{ $statuts[$historique->nouveau_statut]['label'] ?? $historique->nouveau_statut }}
                                    </div>
                                @endif

                                @if (
                                    $historique->action === 'modification' &&
                                        (int) $historique->ancienne_progression !== (int) $historique->nouvelle_progression)
                                    <div class="text-break">
                                        Progression :
                                        {{ $historique->ancienne_progression }}% →
                                        {{ $historique->nouvelle_progression }}%
                                    </div>
                                @endif

                                @if ($historique->action !== 'modification' && $historique->description)
                                    <div class="text-break">
                                        {{ $historique->description }}
                                    </div>
                                @endif

                            </div>
                        @empty

                            <span class="text-muted">
                                Aucun historique.
                            </span>
                        @endforelse

                    </div>

                </div>

            </div>


            {{-- COLONNE DROITE --}}
            <div class="col-lg-4" style="min-width: 0;">


                {{-- État de l'activité --}}
                <div class="card shadow-sm border-0 mb-4">

                    <div class="card-header bg-white py-3">

                        <h5 class="mb-0">
                            <i class="bi bi-activity me-2"></i>
                            État de l'activité
                        </h5>

                    </div>


                    <div class="card-body">


                        <div class="mb-4">

                            <div class="text-muted small mb-1">
                                Statut
                            </div>

                            <span class="badge {{ $statutClasses[$activite->statut] ?? 'bg-secondary' }} px-3 py-2">

                                {{ $statutLabels[$activite->statut] ?? $activite->statut }}

                            </span>

                        </div>


                        <div class="mb-4">

                            <div class="text-muted small mb-1">
                                État de santé
                            </div>

                            <span class="badge {{ $santeClasses[$activite->etat_sante] ?? 'bg-secondary' }} px-3 py-2">

                                {{ $santeLabels[$activite->etat_sante] ?? $activite->etat_sante }}

                            </span>

                        </div>


                        <div>

                            <div class="text-muted small mb-1">
                                Priorité
                            </div>

                            <span class="badge bg-light text-dark border px-3 py-2">

                                {{ ucfirst($activite->priorite ?? 'normale') }}

                            </span>

                        </div>

                    </div>

                </div>


                {{-- Informations administratives --}}
                <div class="card shadow-sm border-0 mb-4">

                    <div class="card-header bg-white py-3">

                        <h5 class="mb-0">
                            <i class="bi bi-building me-2"></i>
                            Informations
                        </h5>

                    </div>


                    <div class="card-body">


                        <div class="mb-3">

                            <div class="text-muted small">
                                Référence
                            </div>

                            <div class="fw-semibold text-break">
                                {{ $activite->reference }}
                            </div>

                        </div>


                        <div class="mb-3">

                            <div class="text-muted small">
                                Direction
                            </div>

                            <div class="fw-semibold text-break">
                                {{ $activite->direction?->sigle ?: $activite->direction?->name ?? '-' }}
                            </div>

                        </div>


                        <div class="mb-3">

                            <div class="text-muted small">
                                Type
                            </div>

                            <div class="fw-semibold text-break">
                                {{ $activite->type?->libelle ?? '-' }}
                            </div>

                        </div>


                        <div>

                            <div class="text-muted small">
                                Créée le
                            </div>

                            <div class="fw-semibold">
                                {{ $activite->created_at?->format('d/m/Y H:i') ?? '-' }}
                            </div>

                        </div>

                    </div>

                </div>


                {{-- Indicateurs --}}
                <div class="card shadow-sm border-0 mb-4">

                    <div class="card-header bg-white py-3">

                        <div class="d-flex justify-content-between align-items-center gap-2">

                            <h5 class="mb-0">
                                <i class="bi bi-graph-up me-2"></i>
                                Indicateurs
                            </h5>

                            <div class="d-flex align-items-center gap-2 flex-shrink-0">

                                <span class="badge bg-secondary">
                                    {{ $activite->indicateurs->count() }}
                                </span>

                                <a href="{{ route('onfp.activites.indicateurs.create', $activite) }}"
                                    class="btn btn-sm btn-primary" title="Ajouter un indicateur">

                                    <i class="bi bi-plus-lg"></i>

                                </a>

                            </div>

                        </div>

                    </div>


                    <div class="card-body">

                        @forelse($activite->indicateurs as $indicateur)
                            @php
                                $pourcentage = (float) ($indicateur->pourcentage ?? 0);
                            @endphp

                            <div class="mb-4">

                                <div class="d-flex justify-content-between align-items-start gap-2">

                                    <div class="fw-semibold text-break" style="min-width: 0;">
                                        {{ $indicateur->libelle }}
                                    </div>

                                    <strong class="flex-shrink-0">
                                        {{ number_format($pourcentage, 0, ',', ' ') }} %
                                    </strong>

                                </div>


                                <div class="small text-muted mb-2 text-break">

                                    Cible :
                                    {{ number_format($indicateur->valeur_cible, 2, ',', ' ') }}

                                    @if ($indicateur->unite)
                                        {{ $indicateur->unite }}
                                    @endif

                                    · Réalisé :
                                    {{ number_format($indicateur->valeur_realisee ?? 0, 2, ',', ' ') }}

                                </div>


                                <div class="progress" style="height: 8px;">

                                    <div class="progress-bar" style="width: {{ min($pourcentage, 100) }}%;">
                                    </div>

                                </div>

                            </div>

                        @empty

                            <div class="text-center py-3">

                                <i class="bi bi-bar-chart text-muted fs-3"></i>

                                <div class="text-muted mt-2">
                                    Aucun indicateur.
                                </div>

                                <a href="{{ route('onfp.activites.indicateurs.create', $activite) }}"
                                    class="btn btn-sm btn-outline-primary mt-2">

                                    <i class="bi bi-plus-lg me-1"></i>
                                    Ajouter un indicateur

                                </a>

                            </div>
                        @endforelse


                        @if ($activite->indicateurs->count() > 0)
                            <div class="text-center mt-3">

                                <a href="{{ route('onfp.activites.indicateurs.index', $activite) }}"
                                    class="btn btn-sm btn-outline-secondary">

                                    Voir tous les indicateurs

                                </a>

                            </div>
                        @endif

                    </div>

                </div>

                {{-- Documents --}}
                <div class="card border-0 shadow-sm mt-4">

                    <div class="card-header bg-white border-bottom">

                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                            <div style="min-width: 0;">
                                <h5 class="mb-1">
                                    <i class="bi bi-folder2-open me-2"></i>
                                    Documents
                                </h5>

                                <small class="text-muted">
                                    Pièces et documents associés à cette activité.
                                </small>
                            </div>

                            <a href="{{ route('onfp.activites.documents.create', $activite) }}"
                                class="btn btn-sm btn-primary flex-shrink-0">

                                <i class="bi bi-plus-lg me-1"></i>
                                Ajouter

                            </a>

                        </div>

                    </div>


                    <div class="card-body">

                        @if ($activite->documents->count())

                            <div class="row g-3">

                                @foreach ($activite->documents->take(5) as $document)
                                    @php
                                        $extension = strtolower(pathinfo($document->nom_original, PATHINFO_EXTENSION));

                                        $icon = match ($extension) {
                                            'pdf' => 'bi-file-earmark-pdf text-danger',
                                            'doc', 'docx' => 'bi-file-earmark-word text-primary',
                                            'xls', 'xlsx' => 'bi-file-earmark-excel text-success',
                                            'ppt', 'pptx' => 'bi-file-earmark-ppt text-warning',
                                            'jpg', 'jpeg', 'png' => 'bi-file-earmark-image text-info',
                                            default => 'bi-file-earmark text-secondary',
                                        };
                                    @endphp

                                    <div class="col-12" style="min-width: 0;">

                                        <div class="border rounded p-3" style="min-width: 0;">

                                            <div class="d-flex align-items-start gap-3">

                                                <div class="fs-2 flex-shrink-0">
                                                    <i class="bi {{ $icon }}"></i>
                                                </div>

                                                <div class="flex-grow-1" style="min-width: 0;">

                                                    <div class="fw-semibold text-truncate"
                                                        title="{{ $document->nom_original }}">

                                                        {{ $document->nom_original }}

                                                    </div>


                                                    <div
                                                        class="small text-muted mt-1 d-flex align-items-center flex-wrap gap-1">

                                                        <span class="text-truncate" style="max-width: 100%;">
                                                            {{ $document->type }}
                                                        </span>

                                                        @if ($document->document_final)
                                                            <span class="badge bg-success flex-shrink-0">
                                                                Final
                                                            </span>
                                                        @endif

                                                    </div>

                                                </div>

                                            </div>


                                            <div class="mt-3">

                                                <a href="{{ route('onfp.activites.documents.view', [$activite, $document]) }}"
                                                    class="btn btn-sm btn-outline-primary" target="_blank"
                                                    title="Visualiser le document">

                                                    <i class="bi bi-eye me-1"></i>
                                                    Visualiser

                                                </a>

                                            </div>

                                        </div>

                                    </div>
                                @endforeach

                            </div>


                            @if ($activite->documents->count() > 5)
                                <div class="text-center mt-4">

                                    <a href="{{ route('onfp.activites.documents.index', $activite) }}"
                                        class="btn btn-sm btn-outline-secondary">

                                        Voir les {{ $activite->documents->count() }} documents

                                        <i class="bi bi-arrow-right ms-1"></i>

                                    </a>

                                </div>
                            @endif
                        @else
                            <div class="text-center py-4">

                                <i class="bi bi-folder2 display-5 text-muted"></i>

                                <p class="text-muted mt-3 mb-3">
                                    Aucun document n'est encore associé à cette activité.
                                </p>

                                <a href="{{ route('onfp.activites.documents.create', $activite) }}"
                                    class="btn btn-sm btn-primary">

                                    <i class="bi bi-plus-lg me-1"></i>
                                    Ajouter un document

                                </a>

                            </div>

                        @endif

                    </div>

                </div>


                {{-- Actions --}}
                <div class="card shadow-sm border-0">

                    <div class="card-header bg-white py-3">

                        <h5 class="mb-0">
                            <i class="bi bi-lightning me-2"></i>
                            Actions
                        </h5>

                    </div>


                    <div class="card-body d-grid gap-2">

                        <a href="{{ route('onfp.activites.edit', $activite) }}" class="btn btn-sm btn-primary">

                            <i class="bi bi-pencil me-2"></i>
                            Modifier l'activité

                        </a>


                        <a href="{{ route('onfp.activites.index') }}" class="btn btn-sm btn-outline-secondary">

                            <i class="bi bi-list me-2"></i>
                            Retour à la liste

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

    @push('styles')
        <style>
            .quick-stat-card {
                transition: transform .15s ease, box-shadow .15s ease;
                color: inherit;
            }

            .quick-stat-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .1) !important;
            }
        </style>
    @endpush

@endsection
