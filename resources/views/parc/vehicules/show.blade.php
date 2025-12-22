@extends('layout.user-layout')
@section('title', 'ONFP - Détails du véhicule')

@section('space-work')
    <section class="section register">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="mb-0">Détails du véhicule</h1>
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
                <form action="{{ route('parc-vehicules.destroy', $vehicule?->id) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm show_confirm">
                        <i class="bi bi-trash"></i> Supprimer
                    </button>
                </form>
            </div>
        </div>
    </section>
@endsection
