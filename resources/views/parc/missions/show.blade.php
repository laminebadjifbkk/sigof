@extends('layout.user-layout')
@section('title', 'ONFP - Détails de la mission')

@section('space-work')
    <section class="section register">
        <div class="container">

            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="mb-0">Détails de la mission</h1>
                <a href="{{ route('parc-missions.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left-circle"></i> Retour à la liste
                </a>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-dark text-white">
                    <strong>Référence : {{ $mission->reference }}</strong>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-hover align-middle">
                        <tbody>
                            <tr>
                                <th>Type de mission</th>
                                <td>{{ $mission->typeMission?->libelle ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Objet</th>
                                <td>{{ $mission->objet }}</td>
                            </tr>
                            <tr>
                                <th>Lieu</th>
                                <td>{{ $mission->lieu_depart }} → {{ $mission->lieu_arrivee }}</td>
                            </tr>
                            @if (!empty($mission?->region))
                                <tr>
                                    <th>Région(s)</th>
                                    <td>{{ $mission?->region }}</td>
                                </tr>
                            @endif
                            <tr>
                                <th>Itinéraire</th>
                                <td>{{ $mission->itineraire }}</td>
                            </tr>
                            <tr>
                                <th>Distance</th>
                                <td>{{ number_format($mission?->distance_km, 0, ',', ' ') . ' km' }}</td>
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
                                <th>Taux journalier</th>
                                <td>{{ number_format($mission->taux_journalier, 0, ',', ' ') }} F CFA</td>
                            </tr>

                            <tr>
                                <th>Indemnité totale</th>
                                <td>{{ number_format($mission->indemnites_total, 0, ',', ' ') }} F CFA</td>
                            </tr>
                            <tr>
                                <th>Avance</th>
                                <td>{{ number_format($mission->avance ?? 0, 0, ',', ' ') }} F CFA</td>
                            </tr>
                            <tr>
                                <th>Reliquat</th>
                                <td>{{ number_format($mission->reliquat, 0, ',', ' ') }} F CFA</td>
                            </tr>

                            <tr>
                                <th>Véhicules</th>
                                <td>
                                    {{ $mission->vehicules->isEmpty() ? '-' : $mission->vehicules->count() }}
                                </td>
                            </tr>

                            <tr>
                                <th>Agents</th>
                                <td>
                                    {{ $mission->employees->isEmpty() ? '-' : $mission->employees->count() }}
                                </td>
                            </tr>

                            <tr>
                                <th>Statut</th>
                                <td>{{ ucfirst($mission->statut) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- ✅ Nouvelle section pour les employés -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <strong>Employés</strong>
                </div>
                <div class="card-body">
                    @if ($mission->employees->isEmpty())
                        <p class="text-muted">Aucun employé affecté à cette mission.</p>
                    @else
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Matricule</th>
                                    <th>Name</th>
                                    <th>Direction</th>
                                    <th>Fonction</th>
                                    <th class="text-center" width="12%">NB Missions</th>
                                    <th class="text-center" width="12%">Rôle</th>
                                    <th class="text-center" width="3%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($employees as $employee)
                                    <tr>
                                        <td>{{ $employee?->matricule }}</td>
                                        <td>{{ $employee?->user?->name }} {{ $employee?->user?->firstname }}</td>
                                        <td>{{ $employee?->direction?->name }}</td>
                                        <td>{{ $employee?->fonction?->name }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-primary">
                                                {{ $employee->parcmissions->where('date_depart', '>=', now()->startOfYear())->count() }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span
                                                class="badge 
                                            @switch($employee->pivot->role)
                                                @case('responsable') bg-primary @break
                                                @case('participant') bg-success @break
                                                @case('observateur') bg-secondary @break
                                                @default bg-dark
                                            @endswitch">
                                                {{ ucfirst($employee->pivot->role) }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            @can('employe-show')
                                                <span class="d-flex mt-2 align-items-baseline"><a
                                                        href="{{ route('employes.show', $employee) }}"
                                                        class="btn btn-info btn-sm mx-1" title="voir détails" target="_blank"><i
                                                            class="bi bi-eye"></i></a>
                                                </span>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>

            {{-- <div class="d-flex gap-2">
                <a href="{{ route('parc-missions.edit', $mission->id) }}" class="btn btn-warning btn-sm">
                    <i class="bi bi-pencil-square"></i> Modifier
                </a>
                <form action="{{ route('parc-missions.destroy', $mission->id) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm show_confirm">
                        <i class="bi bi-trash"></i> Supprimer
                    </button>
                </form>
            </div> --}}

            {{-- @can('parc-mission-update')
                <div class="d-flex gap-2">
                    <a href="{{ route('parc-missions.edit', $mission->id) }}" class="btn btn-warning btn-sm">
                        <i class="bi bi-pencil-square"></i> Modifier
                    </a>
                @endcan

                @can('parc-mission-update')
                    <a href="{{ route('parc-missions.employees.edit', $mission->id) }}" class="btn btn-info btn-sm">
                        <i class="bi bi-people"></i> Employé(s)
                    </a>
                @endcan

                @can('parc-mission-update')
                    <a href="{{ route('parc-missions.vehicules.edit', $mission->id) }}" class="btn btn-secondary btn-sm">
                        <i class="bi bi-truck"></i> Véhicule(s)
                    </a>
                @endcan

                @can('parc-odre-mission-edit')
                    <a href="{{ route('parc-missions.pdf', $mission->id) }}" class="btn btn-success btn-sm" target="_blank">
                        <i class="bi bi-file-earmark-pdf"></i> Ordres de mission
                    </a>
                @endcan

                @can('parc-mission-delete')
                    <form action="{{ route('parc-missions.destroy', $mission->id) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm show_confirm">
                            <i class="bi bi-trash"></i> Supprimer
                        </button>
                    </form>
                @endcan --}}
            {{-- Boutons d'actions pour la mission --}}
            <div class="d-flex flex-wrap gap-2 mb-4">

                {{-- Gestion générale de la mission --}}
                @can('parc-mission-update')
                    <a href="{{ route('parc-missions.edit', $mission->id) }}" class="btn btn-warning btn-sm">
                        <i class="bi bi-pencil-square"></i> Modifier
                    </a>
                @endcan

                @can('parc-mission-delete')
                    <form action="{{ route('parc-missions.destroy', $mission->id) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm show_confirm">
                            <i class="bi bi-trash"></i> Supprimer
                        </button>
                    </form>
                @endcan

                {{-- Gestion des employés / agents --}}
                @can('parc-mission-update')
                    <a href="{{ route('parc-missions.employees.edit', $mission->id) }}" class="btn btn-info btn-sm">
                        <i class="bi bi-people"></i> Ajouter / Modifier Agents
                    </a>
                @endcan

                {{-- Gestion des chauffeurs --}}
                @can('parc-mission-update')
                    <a href="{{ route('parc-missions.chauffeurs.edit', $mission->id) }}" class="btn btn-secondary btn-sm">
                        <i class="bi bi-truck"></i> Ajouter / Modifier Chauffeurs
                    </a>
                @endcan

                {{-- Gestion véhicules --}}
                @can('parc-mission-update')
                    <a href="{{ route('parc-missions.vehicules.edit', $mission->id) }}" class="btn btn-secondary btn-sm">
                        <i class="bi bi-truck"></i> Ajouter / Modifier Véhicules
                    </a>
                @endcan

                {{-- Ordres de mission --}}
                @can('parc-odre-mission-edit')
                    <a href="{{ route('parc-missions.pdf', $mission->id) }}" class="btn btn-success btn-sm" target="_blank">
                        <i class="bi bi-file-earmark-pdf"></i> Ordres de mission
                    </a>
                @endcan

            </div>

        </div>
        </div>
    </section>
@endsection
