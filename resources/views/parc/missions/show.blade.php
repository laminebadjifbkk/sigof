@extends('layout.user-layout')
@section('title', 'ONFP - Détails de la mission')

@section('space-work')
    <section class="section register">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="mb-0">Détails de la mission</h1>
                <a href="{{ route('parc-missions.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left-circle"></i> Retour à la liste
                </a>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-dark text-white">
                    @if ($mission->vehicule)
                        <strong>{{ $mission->vehicule->immatriculation }}</strong> -
                        {{ $mission->vehicule->marque }} {{ $mission->vehicule->modele }}
                    @else
                        <strong>Véhicule non affecté</strong>
                    @endif
                </div>
                <div class="card-body">
                    <table class="table table-striped table-hover align-middle">
                        <tbody>
                            <tr>
                                <th>Référence</th>
                                <td>{{ $mission->reference }}</td>
                            </tr>
                            <tr>
                                <th>Objet</th>
                                <td>{{ $mission->objet }}</td>
                            </tr>
                            <tr>
                                <th>Lieu</th>
                                <td>{{ $mission->lieu_depart }} → {{ $mission->lieu_arrivee }}</td>
                            </tr>
                            <tr>
                                <th>Dates</th>
                                <td>{{ $mission->date_depart->format('d/m/Y') }} -
                                    {{ $mission->date_retour?->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <th>Durée</th>
                                <td>{{ $mission->nombre_jours }} jours</td>
                            </tr>
                            <tr>
                                <th>Véhicule</th>
                                <td>{{ $mission->vehicule?->immatriculation ?? 'Non affecté' }}</td>
                            </tr>
                            <tr>
                                <th>Chauffeur</th>
                                <td>{{ $mission->chauffeur?->nom ?? 'Non affecté' }}</td>
                            </tr>
                            <tr>
                                <th>Statut</th>
                                <td>{{ ucfirst($mission->statut) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('parc-missions.edit', $mission->id) }}" class="btn btn-warning btn-sm">
                    <i class="bi bi-pencil-square"></i> Modifier
                </a>
                <form action="{{ route('parc-missions.destroy', $mission->id) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm show_confirm">
                        <i class="bi bi-trash"></i> Supprimer
                    </button>
                </form>
            </div>
        </div>
    </section>
@endsection
