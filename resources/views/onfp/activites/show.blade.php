@extends('layouts.app')

@section('title', $activite->reference)

@section('content')

<div class="container-fluid">

    {{-- En-tête --}}
    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="mb-2">

                <span class="badge bg-secondary">
                    {{ $activite->reference }}
                </span>

            </div>

            <h1 class="h3 mb-1">
                {{ $activite->titre }}
            </h1>

            <p class="text-muted mb-0">

                @if($activite->direction)
                    {{ $activite->direction->sigle
                        ?: $activite->direction->name }}
                @endif

                @if($activite->type)
                    · {{ $activite->type->libelle }}
                @endif

            </p>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'onfp.activites.edit',
                    $activite
                ) }}"
                class="btn btn-outline-primary"
            >
                Modifier
            </a>

            <a
                href="{{ route(
                    'onfp.activites.index'
                ) }}"
                class="btn btn-outline-secondary"
            >
                Retour
            </a>

        </div>

    </div>


    {{-- Messages --}}
    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    <div class="row g-4">

        {{-- Colonne principale --}}
        <div class="col-lg-8">


            {{-- Progression --}}
            <div class="card shadow-sm mb-4">

                <div class="card-body">

                    <div class="d-flex justify-content-between mb-2">

                        <strong>
                            Progression
                        </strong>

                        <strong>
                            {{ $activite->progression }}%
                        </strong>

                    </div>

                    <div
                        class="progress"
                        style="height: 14px"
                    >

                        <div
                            class="progress-bar"
                            style="width: {{ $activite->progression }}%"
                        ></div>

                    </div>

                </div>

            </div>


            {{-- Description --}}
            <div class="card shadow-sm mb-4">

                <div class="card-header">
                    <h5 class="mb-0">
                        Description
                    </h5>
                </div>

                <div class="card-body">

                    @if($activite->description)

                        {!! nl2br(e($activite->description)) !!}

                    @else

                        <span class="text-muted">
                            Aucune description.
                        </span>

                    @endif

                </div>

            </div>


            {{-- Responsables --}}
            <div class="card shadow-sm mb-4">

                <div class="card-header">
                    <h5 class="mb-0">
                        Responsables
                    </h5>
                </div>

                <div class="card-body">

                    @forelse($activite->responsables as $responsable)

                        <div class="d-flex align-items-center mb-3">

                            <div
                                class="rounded-circle bg-light d-flex align-items-center justify-content-center me-3"
                                style="width:42px;height:42px"
                            >
                                {{ strtoupper(
                                    substr(
                                        $responsable->employee?->matricule
                                        ?? '?',
                                        0,
                                        1
                                    )
                                ) }}
                            </div>

                            <div>

                                <div class="fw-semibold">

                                    {{ $responsable->employee?->user?->name
                                        ?? $responsable->employee?->matricule
                                        ?? 'Employé inconnu' }}

                                    @if($responsable->is_principal)

                                        <span class="badge bg-primary ms-2">
                                            Principal
                                        </span>

                                    @endif

                                </div>

                                <small class="text-muted">

                                    {{ $responsable->employee?->matricule }}

                                    @if($responsable->employee?->direction)
                                        ·
                                        {{ $responsable
                                            ->employee
                                            ->direction
                                            ->sigle }}
                                    @endif

                                </small>

                            </div>

                        </div>

                    @empty

                        <span class="text-muted">
                            Aucun responsable affecté.
                        </span>

                    @endforelse

                </div>

            </div>


            {{-- Suiveurs --}}
            <div class="card shadow-sm mb-4">

                <div class="card-header">
                    <h5 class="mb-0">
                        Suiveurs
                    </h5>
                </div>

                <div class="card-body">

                    @forelse($activite->suiveurs as $suiveur)

                        <span class="badge bg-light text-dark me-2 mb-2">

                            {{ $suiveur->employee?->user?->name
                                ?? $suiveur->employee?->matricule }}

                        </span>

                    @empty

                        <span class="text-muted">
                            Aucun suiveur.
                        </span>

                    @endforelse

                </div>

            </div>


            {{-- Sous-activités --}}
            <div class="card shadow-sm mb-4">

                <div class="card-header">

                    <div class="d-flex justify-content-between">

                        <h5 class="mb-0">
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
            <div class="card shadow-sm mb-4">

                <div class="card-header">

                    <div class="d-flex justify-content-between">

                        <h5 class="mb-0">
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
            <div class="card shadow-sm">

                <div class="card-header">

                    <h5 class="mb-0">
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

                                {{ $historique->created_at?->format(
                                    'd/m/Y H:i'
                                ) }}

                                @if($historique->employee)
                                    ·
                                    {{ $historique
                                        ->employee
                                        ->matricule }}
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


        {{-- Colonne latérale --}}
        <div class="col-lg-4">


            {{-- Statut --}}
            <div class="card shadow-sm mb-4">

                <div class="card-header">
                    <h5 class="mb-0">
                        État de l'activité
                    </h5>
                </div>

                <div class="card-body">

                    @php
                        $statuts = [
                            'a_faire' => 'À faire',
                            'en_cours' => 'En cours',
                            'suspendue' => 'Suspendue',
                            'terminee' => 'Terminée',
                            'annulee' => 'Annulée',
                        ];

                        $santes = [
                            'normal' => 'Normal',
                            'a_surveiller' => 'À surveiller',
                            'risque' => 'Risque',
                            'critique' => 'Critique',
                        ];
                    @endphp

                    <div class="mb-3">

                        <small class="text-muted d-block">
                            Statut
                        </small>

                        <strong>
                            {{ $statuts[$activite->statut]
                                ?? $activite->statut }}
                        </strong>

                    </div>

                    <div class="mb-3">

                        <small class="text-muted d-block">
                            Priorité
                        </small>

                        <strong>
                            {{ ucfirst($activite->priorite) }}
                        </strong>

                    </div>

                    <div>

                        <small class="text-muted d-block">
                            État de santé
                        </small>

                        <strong>
                            {{ $santes[$activite->etat_sante]
                                ?? $activite->etat_sante }}
                        </strong>

                    </div>

                </div>

            </div>


            {{-- Dates --}}
            <div class="card shadow-sm mb-4">

                <div class="card-header">
                    <h5 class="mb-0">
                        Planning
                    </h5>
                </div>

                <div class="card-body">

                    <div class="mb-3">

                        <small class="text-muted d-block">
                            Enclenchement
                        </small>

                        {{ $activite->date_enclenchement
                            ? $activite->date_enclenchement->format('d/m/Y')
                            : '—' }}

                    </div>


                    <div class="mb-3">

                        <small class="text-muted d-block">
                            Exécution prévue
                        </small>

                        {{ $activite->date_execution_prevue
                            ? $activite->date_execution_prevue->format('d/m/Y')
                            : '—' }}

                    </div>


                    <div class="mb-3">

                        <small class="text-muted d-block">
                            Fin prévue
                        </small>

                        {{ $activite->date_fin_prevue
                            ? $activite->date_fin_prevue->format('d/m/Y')
                            : '—' }}

                    </div>


                    <div>

                        <small class="text-muted d-block">
                            Fin réelle
                        </small>

                        {{ $activite->date_fin_reelle
                            ? $activite->date_fin_reelle->format('d/m/Y')
                            : '—' }}

                    </div>

                </div>

            </div>


            {{-- Indicateurs --}}
            <div class="card shadow-sm mb-4">

                <div class="card-header">

                    <div class="d-flex justify-content-between">

                        <h5 class="mb-0">
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
            <div class="card shadow-sm">

                <div class="card-header">

                    <div class="d-flex justify-content-between">

                        <h5 class="mb-0">
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

        </div>

    </div>

</div>

@endsection
