@extends('layout.user-layout')
@section('title', 'ONFP - Détails du véhicule')

@section('space-work')
    <section class="section register">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="mb-0">Détails du véhicule</h3>
                <a href="{{ route('parc-vehicules.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left-circle"></i> Retour à la liste
                </a>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-dark text-white">
                    <strong>{{ $vehicule?->immatriculation }}</strong> - {{ $vehicule?->marque }} {{ $vehicule?->modele }}
                </div>
                <div class="card-body">
                    <table class="table table-striped table-hover align-middle">
                        <tbody>
                            <tr>
                                <th>Missions en {{ now()->year }}</th>
                                <td>
                                    <span class="badge bg-primary">{{ $vehiculeMissionsCount }}</span>
                                </td>
                            </tr>

                            <tr>
                                <th>Missions total</th>
                                <td class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-secondary">
                                        {{ $vehicule?->missions->count() }}
                                    </span>

                                    {{-- Bouton Voir les missions, aligné complètement à droite --}}
                                    @if ($vehicule?->missions?->isNotEmpty())
                                        <a href="{{ route('vehicules.missions.show', $vehicule->id) }}"
                                            class="btn btn-sm btn-info ms-auto">
                                            <i class="bi bi-eye"></i> Voir
                                        </a>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th style="width: 30%">Immatriculation</th>
                                <td>{{ $vehicule?->immatriculation }}</td>
                            </tr>
                            <tr>
                                <th>Marque</th>
                                <td>{{ $vehicule?->marque }}</td>
                            </tr>
                            <tr>
                                <th>Modèle</th>
                                <td>{{ $vehicule?->modele }}</td>
                            </tr>
                            <tr>
                                <th>Année</th>
                                <td>{{ $vehicule?->annee }}</td>
                            </tr>
                            <tr>
                                <th>Catégorie</th>
                                <td>{{ $vehicule?->categorie }}</td>
                            </tr>
                            <tr>
                                <th>Énergie</th>
                                <td>{{ $vehicule?->energie }}</td>
                            </tr>
                            <tr>
                                <th>Kilométrage actuel</th>
                                <td>{{ $vehicule?->kilometrage_actuel }} km</td>
                            </tr>
                            <tr>
                                <th>État</th>
                                <td>
                                    <span
                                        class="badge 
                                    @if ($vehicule?->etat == 'operationnel') bg-success 
                                    @elseif($vehicule?->etat == 'maintenance') bg-warning 
                                    @else bg-danger @endif">
                                        {{ ucfirst($vehicule?->etat) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Assurance expire le</th>
                                <td>{{ $vehicule?->assurance_expire_le?->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <th>Visite technique expire le</th>
                                <td>{{ $vehicule?->visite_technique_expire_le?->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <th>Chauffeur affecté</th>
                                <td>
                                    {{ $vehicule?->chauffeur ? $vehicule?->chauffeur?->nom . ' ' . $vehicule?->chauffeur?->prenom : 'Aucun' }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('parc-vehicules.edit', $vehicule?->id) }}" class="btn btn-warning btn-sm">
                    <i class="bi bi-pencil-square"></i> Modifier
                </a>

                @php
                    $missionsCount = $vehicule?->missions?->count() ?? 0;
                @endphp
                <form action="{{ route('parc-vehicules.destroy', $vehicule?->id) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm show_confirm"
                        {{ $missionsCount > 0 ? 'disabled' : '' }}
                        title="{{ $missionsCount > 0 ? 'Véhicule affecté à des missions' : 'Supprimer le chauffeur' }}">
                        <i class="bi bi-trash"></i> Supprimer
                    </button>
                </form>
            </div>
        </div>
    </section>
@endsection
