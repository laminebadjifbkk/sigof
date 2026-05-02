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
                    <div class="table-responsive">
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
                                    <td>{{ $mission->lieu_arrivee }}</td>
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
                                    <th>Nombre de jours</th>
                                    <td>
                                        <strong>{{ $mission->nuitees }} nuitée(s)</strong>

                                        <ul style="margin:5px 0; padding-left:15px;">
                                            @foreach ($mission->nuitees_par_mois ?? [] as $mois => $nb)
                                                <li>
                                                    {{ \Carbon\Carbon::createFromFormat('Y-m', $mois)->translatedFormat('F Y') }}
                                                    : {{ $nb }} nuitée(s)
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>
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
                                    <td>
                                        <span class="etat-btn {{ $mission->statut }}">
                                            {{ ucfirst(str_replace('ee', 'ée', str_replace('_', ' ', $mission->statut))) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Créée par</th>
                                    <td>
                                        {{ $mission?->creator ? $mission->creator->firstname . ' ' . $mission->creator->name : 'Fatou Boro DIOP' }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
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
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Matricule</th>
                                        <th>Name</th>
                                        <th>Direction</th>
                                        <th>Fonction</th>
                                        <th class="text-center" width="12%">Missions</th>
                                        <th class="text-center" width="12%">Rôle</th>
                                        <th class="text-center" width="3%">Actions</th>
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
                                                <span class="etat-btn {{ $employee->pivot->role ?? 'default-role' }}">
                                                    {{ ucfirst(str_replace('_', ' ', $employee->pivot->role ?? '')) }}
                                                </span>
                                            </td>
                                            {{-- <td class="text-center">
                                            @can('employe-show')
                                                <span class="d-flex mt-2 align-items-baseline"><a
                                                        href="{{ route('employes.show', $employee) }}"
                                                        class="btn btn-info btn-sm mx-1" title="voir détails" target="_blank"><i
                                                            class="bi bi-eye"></i></a>
                                                </span>
                                            @endcan
                                        </td> --}}

                                            {{-- Actions --}}
                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal"
                                                    data-bs-target="#missionsModal{{ $employee->id }}">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                            </td>
                                        </tr>

                                        {{-- Modal des missions --}}
                                        <div class="modal fade" id="missionsModal{{ $employee->id }}" tabindex="-1"
                                            aria-labelledby="missionsModalLabel{{ $employee->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-xl">
                                                <div class="modal-content">
                                                    {{-- Header --}}
                                                    <div class="modal-header bg-info text-white rounded-top-4">
                                                        <h5 class="modal-title" id="missionsModalLabel{{ $employee->id }}">
                                                            Missions de {{ $employee->user->firstname }}
                                                            {{ $employee->user->name }}
                                                        </h5>
                                                        <button type="button" class="btn-close btn-close-white"
                                                            data-bs-dismiss="modal" aria-label="Fermer"></button>
                                                    </div>
                                                    {{-- Body --}}
                                                    <div class="modal-body">
                                                        @php
                                                            $lastMissions = $employee->parcmissions
                                                                ->sortByDesc('date_depart')
                                                                ->take(5);
                                                        @endphp

                                                        @if ($lastMissions->count() > 0)
                                                            <div class="list-group">
                                                                @foreach ($lastMissions as $cm)
                                                                    <div
                                                                        class="list-group-item d-flex justify-content-between align-items-center border-bottom">
                                                                        <div>

                                                                            <div>
                                                                                <a href="{{ route('parc-missions.show', $cm->id) }}"
                                                                                    class="text-decoration-none">
                                                                                    <strong>{{ $cm->reference }}</strong>
                                                                                </a>
                                                                                - {{ $cm->objet }}
                                                                            </div>

                                                                            {{-- <strong>{{ $cm->objet }}</strong><br> --}}
                                                                            <small class="text-muted">Réf:
                                                                                {{ $cm->reference }}</small>
                                                                        </div>
                                                                        <div class="text-end">
                                                                            <span class="badge bg-secondary">
                                                                                Du {{ $cm->date_depart->format('d/m/Y') }}
                                                                                au
                                                                                {{ $cm->date_retour->format('d/m/Y') }}
                                                                            </span>
                                                                            <br>
                                                                            {{-- @php
                                                                            $now = now();
                                                                            if ($cm->date_retour < $now) {
                                                                                $status = [
                                                                                    'label' => 'Terminée',
                                                                                    'class' => 'bg-success',
                                                                                ];
                                                                            } elseif ($cm->date_depart > $now) {
                                                                                $status = [
                                                                                    'label' => 'À venir',
                                                                                    'class' => 'bg-warning text-dark',
                                                                                ];
                                                                            } else {
                                                                                $status = [
                                                                                    'label' => 'En cours',
                                                                                    'class' => 'bg-primary text-white',
                                                                                ];
                                                                            }
                                                                        @endphp

                                                                        <span class="badge {{ $status['class'] }}">
                                                                            {{ $status['label'] }}
                                                                        </span> --}}
                                                                            <span class="etat-btn {{ $cm->statut }}">
                                                                                {{ ucfirst(str_replace(['fie', 'ee', '_'], ['fié', 'ée', ' '], $cm->statut)) }}
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @else
                                                            <div class="alert alert-secondary text-center mb-0">
                                                                Aucune mission assignée.
                                                            </div>
                                                        @endif
                                                    </div>

                                                    {{-- Footer --}}
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-outline-secondary btn-sm"
                                                            data-bs-dismiss="modal">
                                                            Fermer
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
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
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm show_confirm"
                            {{ $employeesCount > 0 ? 'disabled' : '' }}
                            title="{{ $employeesCount > 0
                                ? 'Impossible de supprimer : mission déjà assignée à des employés'
                                : 'Supprimer la mission' }}">
                            <i class="bi bi-trash"></i> Supprimer
                        </button>
                    </form>
                @endcan

                {{-- Gestion des employés / agents --}}
                {{-- @can('parc-mission-update')
                    <a href="{{ route('parc-missions.employees.edit', $mission->id) }}" class="btn btn-info btn-sm">
                        <i class="bi bi-people"></i> Ajouter / Modifier Agents
                    </a>
                @endcan --}}

                {{-- Gestion des chauffeurs --}}
                {{-- @can('parc-mission-update')
                    <a href="{{ route('parc-missions.chauffeurs.edit', $mission->id) }}" class="btn btn-secondary btn-sm">
                        <i class="bi bi-truck"></i> Ajouter / Modifier Chauffeurs
                    </a>
                @endcan --}}

                {{-- Gestion des chauffeurs --}}
                @can('parc-mission-personnel-update')
                    <a href="{{ route('parc-missions.personnel.edit', $mission) }}" class="btn btn-info btn-sm">
                        <i class="bi bi-people-fill"></i> Ajouter / Modifier Personnel {{ $mission->id }}
                    </a>
                @endcan

                {{-- Gestion véhicules --}}
                @can('parc-vehicule-update')
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
