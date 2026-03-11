@extends('layout.user-layout')
@section('title', 'Détails activité')
@section('space-work')

    <section class="section">
        <div class="container">
            <h1>Détails de l'activité</h1>

            <div class="card shadow-sm p-3">
                <p><strong>Titre :</strong> {{ $activitequotidienne->titre }}</p>
                <p><strong>Description :</strong> {{ $activitequotidienne->description }}</p>
                <p><strong>Agent :</strong> {{ $activitequotidienne->user->firstname ?? '' }}
                    {{ $activitequotidienne->user->name ?? '' }}</p>
                <p><strong>Date :</strong>
                    {{ optional($activitequotidienne->date_activite)->format('d/m/Y') ?? 'Non définie' }}</p>
                <p><strong>Priorité :</strong> {{ ucfirst($activitequotidienne->priorite) }}</p>
                <p><strong>Statut :</strong> {{ $labels[$activitequotidienne->statut] ?? $activitequotidienne->statut }}</p>

                <a href="{{ route('activites-quotidiennes.index') }}" class="btn btn-secondary btn-sm mt-2">Retour</a>
            </div>
        </div>
    </section>

@endsection
