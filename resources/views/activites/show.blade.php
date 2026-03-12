@extends('layout.user-layout')
@section('title', 'Détails activité')

@section('space-work')

    <section class="section">
        <div class="container">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0">Détails de l'activité</h4>

                <a href="{{ route('activites-quotidiennes.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left-circle"></i> Retour à la liste
                </a>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">

                    <div class="row mb-2">
                        <div class="col-md-3"><strong>Titre :</strong></div>
                        <div class="col-md-9">{{ $activitequotidienne->titre }}</div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-3"><strong>Description :</strong></div>
                        <div class="col-md-9">{{ $activitequotidienne->description }}</div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-3"><strong>Agent :</strong></div>
                        <div class="col-md-9">
                            {{ $activitequotidienne->user->firstname ?? '' }}
                            {{ $activitequotidienne->user->name ?? '' }}
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-3"><strong>Date :</strong></div>
                        <div class="col-md-9">
                            {{ optional($activitequotidienne->date_activite)->format('d/m/Y') ?? 'Non définie' }}
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-3"><strong>Heure début :</strong></div>
                        <div class="col-md-9">
                            {{ optional($activitequotidienne->heure_debut)->format('H:m') ?? 'Non définie' }}
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-3"><strong>Heure fin :</strong></div>
                        <div class="col-md-9">
                            {{ optional($activitequotidienne->heure_fin)->format('H:m') ?? 'Non définie' }}
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-3"><strong>Priorité :</strong></div>
                        <div class="col-md-9">
                            <span class="badge-activite {{ ucfirst($activitequotidienne->priorite) }}">
                                {{ $labels[$activitequotidienne->priorite] ?? $activitequotidienne->priorite }}
                            </span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3"><strong>Statut :</strong></div>
                        <div class="col-md-9">
                            <span class="badge-activite {{ ucfirst($activitequotidienne->statut) }}">
                                {{ $labels[$activitequotidienne->statut] ?? $activitequotidienne->statut }}
                            </span>
                        </div>
                    </div>

                </div>
            </div>

            <div class="mt-3">
                <a href="{{ route('activites-quotidiennes.edit', $activitequotidienne->id) }}"
                    class="btn btn-primary btn-sm">
                    <i class="bi bi-pencil-square"></i> Modifier
                </a>
            </div>

        </div>
    </section>

@endsection
