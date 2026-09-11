@extends('layout.user-layout')

@section('title', $activite->reference)

@section('space-work')

<div class="container-fluid py-4">

    {{-- En-tête --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div class="d-flex align-items-center">

            <a href="{{ route('onfp.activites.index') }}"
               class="btn btn-sm btn-outline-secondary me-3">

                <i class="bi bi-arrow-left"></i>

            </a>

            <div>

                <div class="d-flex align-items-center gap-2">

                    <h3 class="mb-0">
                        {{ $activite->titre }}
                    </h3>

                    <span class="badge bg-light text-dark border">
                        {{ $activite->reference }}
                    </span>

                </div>

                <p class="text-muted mb-0 mt-1">
                    {{ $activite->type?->libelle ?? 'Type non défini' }}
                    @if($activite->direction)
                        · {{ $activite->direction->sigle ?: $activite->direction->name }}
                    @endif
                </p>

            </div>

        </div>


        <div class="d-flex gap-2">

            <a href="{{ route('onfp.activites.edit', $activite) }}"
               class="btn btn-sm btn-primary">

                <i class="bi bi-pencil me-1"></i>
                Modifier

            </a>

        </div>

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


    <div class="row g-4">

        {{-- COLONNE PRINCIPALE --}}
        <div class="col-lg-8">


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

                        <div class="fw-semibold fs-5">
                            {{ $activite->titre }}
                        </div>

                    </div>


                    <div class="mb-4">

                        <label class="text-muted small">
                            Description
                        </label>

                        @if($activite->description)

                            <div class="mt-1">
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

                        @if($activite->observation)

                            <div class="mt-1">
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


                    <div class="progress"
                         style="height: 12px;">

                        <div class="progress-bar"
                             role="progressbar"
                             style="width: {{ $activite->progression }}%;"
                             aria-valuenow="{{ $activite->progression }}"
                             aria-valuemin="0"
                             aria-valuemax="100">
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

                        <div class="col-md-4">

                            <div class="text-muted small">
                                Date d'enclenchement
                            </div>

                            <div class="fw-semibold">
                                {{ $activite->date_enclenchement?->format('d/m/Y') ?? '—' }}
                            </div>

                        </div>


                        <div class="col-md-4">

                            <div class="text-muted small">
                                Exécution prévue
                            </div>

                            <div class="fw-semibold">
                                {{ $activite->date_execution_prevue?->format('d/m/Y') ?? '—' }}
                            </div>

                        </div>


                        <div class="col-md-4">

                            <div class="text-muted small">
                                Fin prévue
                            </div>

                            <div class="fw-semibold">
                                {{ $activite->date_fin_prevue?->format('d/m/Y') ?? '—' }}
                            </div>

                        </div>


                        <div class="col-md-4">

                            <div class="text-muted small">
                                Exécution réelle
                            </div>

                            <div class="fw-semibold">
                                {{ $activite->date_execution_reelle?->format('d/m/Y') ?? '—' }}
                            </div>

                        </div>


                        <div class="col-md-4">

                            <div class="text-muted small">
                                Fin réelle
                            </div>

                            <div class="fw-semibold">
                                {{ $activite->date_fin_reelle?->format('d/m/Y') ?? '—' }}
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
                                ($responsable->employee?->user?->name ?? '')
                            );
                        @endphp

                        <div class="d-flex align-items-center mb-3">

                            <div class="rounded-circle bg-primary text-white
                                        d-flex align-items-center justify-content-center me-3"
                                 style="width:42px;height:42px;">

                                <i class="bi bi-person"></i>

                            </div>


                            <div class="flex-grow-1">

                                <div class="fw-semibold">

                                    {{ $nomResponsable !== ''
                                        ? $nomResponsable
                                        : ($responsable->employee?->matricule ?? 'Employé inconnu') }}

                                    @if($responsable->is_principal)

                                        <span class="badge bg-primary ms-2">
                                            Principal
                                        </span>

                                    @endif

                                </div>

                                <div class="text-muted small">

                                    {{ $responsable->employee?->matricule }}

                                    {{ $responsable->role ?? 'Responsable' }}

                                    @if($responsable->employee?->direction)
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


                <div class="card-body">

                    @forelse($activite->suiveurs as $suiveur)

                        @php
                            $nomSuiveur = trim(
                                ($suiveur->employee?->user?->firstname ?? '') .
                                ' ' .
                                ($suiveur->employee?->user?->name ?? '')
                            );
                        @endphp

                        <span class="badge bg-light text-dark border me-2 mb-2 px-3 py-2">

                            <i class="bi bi-person me-1"></i>

                            {{ $nomSuiveur !== ''
                                ? $nomSuiveur
                                : ($suiveur->employee?->matricule ?? 'Employé inconnu') }}

                        </span>

                    @empty

                        <span class="text-muted">
                            Aucun agent de suivi affecté.
                        </span>

                    @endforelse

                </div>

            </div>


            {{-- Sous-activités --}}
            <div class="card shadow-sm border-0 mb-4">

                <div class="card-header bg-white py-3">

                    <div class="d-flex justify-content-between align-items-center">

                        <h5 class="mb-0">
                            <i class="bi bi-diagram-3 me-2"></i>
                            Sous-activités
                        </h5>

                        <span class="badge bg-secondary">
                            {{ $activite->sousActivites->count() }}
                        </span>

                    </div>

                </div>

                <div class="card-body">

                    @forelse($activite->sousActivites as $sousActivite)

                        <div class="border rounded p-3 mb-3">

                            <div class="fw-semibold">
                                {{ $sousActivite->titre }}
                            </div>

                            <div class="small text-muted">
                                {{ $sousActivite->progression }}%
                                ·
                                {{ $sousActivite->statut }}
                            </div>

                        </div>

                    @empty

                        <span class="text-muted">
                            Aucune sous-activité pour le moment.
                        </span>

                    @endforelse

                </div>

            </div>


            {{-- Tâches --}}
            <div class="card shadow-sm border-0 mb-4">

                <div class="card-header bg-white py-3">

                    <div class="d-flex justify-content-between align-items-center">

                        <h5 class="mb-0">
                            <i class="bi bi-check2-square me-2"></i>
                            Tâches
                        </h5>

                        <span class="badge bg-secondary">
                            {{ $activite->taches->count() }}
                        </span>

                    </div>

                </div>

                <div class="card-body">

                    @forelse($activite->taches as $tache)

                        <div class="border rounded p-3 mb-3">

                            <div class="fw-semibold">
                                {{ $tache->titre }}
                            </div>

                            <div class="small text-muted">

                                {{ $tache->progression }}%

                                ·

                                {{ $tache->statut }}

                                @if($tache->date_echeance)
                                    · Échéance :
                                    {{ $tache->date_echeance->format('d/m/Y') }}
                                @endif

                            </div>

                        </div>

                    @empty

                        <span class="text-muted">
                            Aucune tâche pour le moment.
                        </span>

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

                    @forelse(
                        $activite->historiques->sortByDesc('created_at')
                        as $historique
                    )

                        <div class="border-start ps-3 mb-4">

                            <div class="fw-semibold">
                                {{ ucfirst($historique->action) }}
                            </div>

                            <div class="small text-muted mb-1">

                                {{ $historique->created_at?->format('d/m/Y H:i') }}

                                @if($historique->employee)
                                    ·
                                    {{ $historique->employee->matricule }}
                                @endif

                            </div>

                            @if($historique->description)

                                <div>
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
        <div class="col-lg-4">


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

                        @php

                            $statutClasses = [
                                'a_faire'   => 'bg-secondary',
                                'en_cours'  => 'bg-primary',
                                'suspendue' => 'bg-warning text-dark',
                                'terminee'  => 'bg-success',
                                'annulee'   => 'bg-danger',
                            ];

                            $statutLabels = [
                                'a_faire'   => 'À faire',
                                'en_cours'  => 'En cours',
                                'suspendue' => 'Suspendue',
                                'terminee'  => 'Terminée',
                                'annulee'   => 'Annulée',
                            ];

                        @endphp

                        <span class="badge {{ $statutClasses[$activite->statut] ?? 'bg-secondary' }} px-3 py-2">

                            {{ $statutLabels[$activite->statut] ?? $activite->statut }}

                        </span>

                    </div>


                    <div class="mb-4">

                        <div class="text-muted small mb-1">
                            État de santé
                        </div>

                        @php

                            $santeClasses = [
                                'normal'       => 'bg-success',
                                'a_surveiller' => 'bg-warning text-dark',
                                'risque'       => 'bg-danger bg-opacity-75',
                                'critique'     => 'bg-danger',
                            ];

                            $santeLabels = [
                                'normal'       => 'Normal',
                                'a_surveiller' => 'À surveiller',
                                'risque'       => 'Risque',
                                'critique'     => 'Critique',
                            ];

                        @endphp

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

                        <div class="fw-semibold">
                            {{ $activite->reference }}
                        </div>

                    </div>


                    <div class="mb-3">

                        <div class="text-muted small">
                            Direction
                        </div>

                        <div class="fw-semibold">
                            {{ $activite->direction?->sigle ?: ($activite->direction?->name ?? '—') }}
                        </div>

                    </div>


                    <div class="mb-3">

                        <div class="text-muted small">
                            Type
                        </div>

                        <div class="fw-semibold">
                            {{ $activite->type?->libelle ?? '—' }}
                        </div>

                    </div>


                    <div>

                        <div class="text-muted small">
                            Créée le
                        </div>

                        <div class="fw-semibold">
                            {{ $activite->created_at?->format('d/m/Y H:i') ?? '—' }}
                        </div>

                    </div>

                </div>

            </div>


            {{-- Indicateurs --}}
            <div class="card shadow-sm border-0 mb-4">

                <div class="card-header bg-white py-3">

                    <div class="d-flex justify-content-between align-items-center">

                        <h5 class="mb-0">
                            <i class="bi bi-graph-up me-2"></i>
                            Indicateurs
                        </h5>

                        <span class="badge bg-secondary">
                            {{ $activite->indicateurs->count() }}
                        </span>

                    </div>

                </div>

                <div class="card-body">

                    @forelse($activite->indicateurs as $indicateur)

                        <div class="mb-3">

                            <div class="fw-semibold">
                                {{ $indicateur->libelle }}
                            </div>

                            <div class="small text-muted">

                                Cible :
                                {{ $indicateur->valeur_cible }}

                                @if($indicateur->unite)
                                    {{ $indicateur->unite }}
                                @endif

                                · Réalisé :
                                {{ $indicateur->valeur_realisee }}

                            </div>

                        </div>

                    @empty

                        <span class="text-muted">
                            Aucun indicateur.
                        </span>

                    @endforelse

                </div>

            </div>


            {{-- Documents --}}
            <div class="card shadow-sm border-0 mb-4">

                <div class="card-header bg-white py-3">

                    <div class="d-flex justify-content-between align-items-center">

                        <h5 class="mb-0">
                            <i class="bi bi-paperclip me-2"></i>
                            Documents
                        </h5>

                        <span class="badge bg-secondary">
                            {{ $activite->documents->count() }}
                        </span>

                    </div>

                </div>

                <div class="card-body">

                    @forelse($activite->documents as $document)

                        <div class="mb-2">

                            <div class="fw-semibold">
                                {{ $document->nom_original }}
                            </div>

                            <small class="text-muted">
                                {{ $document->type }}
                            </small>

                        </div>

                    @empty

                        <span class="text-muted">
                            Aucun document.
                        </span>

                    @endforelse

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

                    <a href="{{ route('onfp.activites.edit', $activite) }}"
                       class="btn btn-sm btn-primary">

                        <i class="bi bi-pencil me-2"></i>
                        Modifier l'activité

                    </a>


                    <a href="{{ route('onfp.activites.index') }}"
                       class="btn btn-sm btn-outline-secondary">

                        <i class="bi bi-list me-2"></i>
                        Retour à la liste

                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection
