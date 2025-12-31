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
                <div class="card-header bg-success text-white">
                    <strong>Statistiques</strong>
                </div>
                <div class="card-body">
                    <p>
                        Nombre de missions réalisées en {{ now()->year }} :
                        <span class="badge bg-primary">{{ $missionsCount }}</span>
                    </p>
                </div>
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
                                @foreach ($mission->employees as $employee)
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
                                            <span class="d-flex mt-2 align-items-baseline"><a
                                                    href="{{ route('employes.show', $employee) }}"
                                                    class="btn btn-info btn-sm mx-1" title="voir détails" target="_blank"><i
                                                        class="bi bi-eye"></i></a>
                                            </span>
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

            <div class="d-flex gap-2">
                <a href="{{ route('parc-missions.edit', $mission->id) }}" class="btn btn-warning btn-sm">
                    <i class="bi bi-pencil-square"></i> Modifier
                </a>

                <a href="{{ route('parc-missions.employees.edit', $mission->id) }}" class="btn btn-info btn-sm">
                    <i class="bi bi-people"></i> Employés
                </a>

                <a href="{{ route('parc-missions.pdf', $mission->id) }}" class="btn btn-success btn-sm" target="_blank">
                    <i class="bi bi-file-earmark-pdf"></i> Ordres de mission
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
