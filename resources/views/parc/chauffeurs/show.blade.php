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

                    <!-- Missions badges style -->
                    <div class="row mb-4">
                        <!-- Missions de l'année -->
                        <div class="col-md-4 text-muted small">
                            Missions en {{ now()->year }} : <strong>{{ $chauffeurMissionsCount }}</strong>
                        </div>

                        <!-- Missions totales -->
                        <div class="col-md-4 text-muted small d-flex align-items-center">
                            Total missions :&nbsp;<strong> {{ $chauffeurMissionsTotal }}</strong>
                        </div>

                        <!-- Voir -->
                        <div class="col-md-4 text-muted small d-flex align-items-end">
                            @if ($chauffeurMissionsTotal > 0)
                                <a href="{{ route('chauffeurs.missions.show', $chauffeur->id) }}"
                                    class="btn btn-outline-info btn-sm ms-auto float-end">
                                    <i class="bi bi-eye"></i> Voir
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Infos personnelles -->
                    <table class="table table-striped table-hover align-middle">
                        <tbody>
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
                                    <span class="etat-btn {{ $chauffeur?->statut }}">
                                        {{ ucfirst(str_replace('_', ' ', $chauffeur?->statut)) }}
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

            @php
                $missionsCount = $chauffeur?->employee?->parcmissions?->count() ?? 0;
            @endphp

            <div class="d-flex gap-2">
                <a href="{{ route('parc-chauffeurs.edit', $chauffeur?->id) }}" class="btn btn-warning btn-sm">
                    <i class="bi bi-pencil-square"></i> Modifier
                </a>
                <form action="{{ route('parc-chauffeurs.destroy', $chauffeur?->id) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm show_confirm"
                        {{ $missionsCount > 0 ? 'disabled' : '' }}
                        title="{{ $missionsCount > 0 ? 'Chauffeur affecté à des missions' : 'Supprimer le chauffeur' }}">
                        <i class="bi bi-trash"></i> Supprimer
                    </button>
                </form>
            </div>
        </div>
    </section>
@endsection
