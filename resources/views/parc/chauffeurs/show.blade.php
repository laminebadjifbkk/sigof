@extends('layout.user-layout')
@section('title', 'ONFP - Détails du chauffeur')

@section('space-work')
    <section class="section register">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="mb-0">Détails du chauffeur</h3>
                <a href="{{ route('parc-chauffeurs.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left-circle"></i> Retour à la liste
                </a>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-dark text-white">
                    <strong>{{ $chauffeur?->employee?->matricule }}</strong> -
                    {{ $chauffeur?->employee?->user?->firstname }}
                    {{ $chauffeur?->employee?->user?->name }}
                </div>
                <div class="card-body">
                    <table class="table table-striped table-hover align-middle">
                        <tbody>

                            <tr>
                                <th>Missions en {{ now()->year }}</th>
                                <td>
                                    <span class="badge bg-primary">{{ $chauffeurMissionsCount }}</span>
                                </td>
                            </tr>

                            <tr>
                                <th>Missions total</th>
                                <td class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-primary">
                                        {{ $chauffeur?->employee?->parcmissions->count() }}
                                    </span>

                                    {{-- Bouton Voir les missions, aligné complètement à droite --}}
                                    @if ($chauffeur?->employee?->parcmissions?->isNotEmpty())
                                        <a href="{{ route('chauffeurs.missions.show', $chauffeur->id) }}"
                                            class="btn btn-sm btn-info ms-auto">
                                            <i class="bi bi-eye"></i> Voir
                                        </a>
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <th style="width: 30%">Matricule</th>
                                <td>{{ $chauffeur?->employee?->matricule }}</td>
                            </tr>
                            <tr>
                                <th>Nom</th>
                                <td>{{ $chauffeur?->employee?->user?->firstname . ' ' . $chauffeur?->employee?->user?->name }}
                                </td>
                            </tr>
                            <tr>
                                <th>Téléphone</th>
                                <td>{{ $chauffeur?->employee?->user?->telephone }}</td>
                            </tr>
                            <tr>
                                <th>Numéro du permis</th>
                                <td>{{ $chauffeur?->permis_numero }}</td>
                            </tr>
                            <tr>
                                <th>Catégories du permis</th>
                                <td>{{ $chauffeur?->permis_categories }}</td>
                            </tr>
                            <tr>
                                <th>Expiration du permis</th>
                                <td>
                                    <span class="{{ $chauffeur->permis_classe }}">
                                        {{ $chauffeur->permis_restant }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Statut</th>
                                <td>
                                    <span class="badge {{ $chauffeur?->statut == 'actif' ? 'bg-success' : 'bg-danger' }}">
                                        {{ ucfirst($chauffeur?->statut) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Véhicules affectés</th>
                                <td>
                                    @if ($chauffeur?->vehicules && $chauffeur?->vehicules?->isNotEmpty())
                                        <ul class="mb-0">
                                            @foreach ($chauffeur?->vehicules as $vehicule)
                                                <li>{{ $vehicule?->immatriculation }} - {{ $vehicule?->marque }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <span class="text-muted">Aucun véhicule affecté</span>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('parc-chauffeurs.edit', $chauffeur?->id) }}" class="btn btn-warning btn-sm">
                    <i class="bi bi-pencil-square"></i> Modifier
                </a>
                <form action="{{ route('parc-chauffeurs.destroy', $chauffeur?->id) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm show_confirm">
                        <i class="bi bi-trash"></i> Supprimer
                    </button>
                </form>
            </div>
        </div>
    </section>
@endsection
