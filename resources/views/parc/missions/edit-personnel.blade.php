@extends('layout.user-layout')
@section('title', 'ONFP - Mise à jour du personnel de la mission')

@section('space-work')
    <section class="section register">
        <div class="container">

            {{-- En-tête --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="mb-0">Personnel de la mission : {{ $mission->reference }}</h3>
                <a href="{{ route('parc-missions.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left-circle"></i> Retour à la liste
                </a>
            </div>

            {{-- Messages de succès --}}
            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                </div>
            @endif

            {{-- Affichage des erreurs --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Card principale --}}
            <div class="card shadow-sm">
                <div class="card-body">

                    {{-- Boutons de sélection rapide --}}
                    <div class="mb-3">
                        <button type="button" id="select-all" class="btn btn-sm btn-info">Tout sélectionner</button>
                        <button type="button" id="deselect-all" class="btn btn-sm btn-outline-secondary">Tout
                            désélectionner</button>

                        {{-- Ordres de mission --}}
                        @can('parc-odre-mission-edit')
                            <a href="{{ route('parc-missions.pdf', $mission->id) }}" class="btn btn-success btn-sm"
                                target="_blank">
                                <i class="bi bi-file-earmark-pdf"></i> Ordres de mission
                            </a>
                        @endcan
                    </div>

                    {{-- Formulaire --}}
                    <form action="{{ route('parc-missions.personnel.update', $mission) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- ================== CHAUFFEURS ================== --}}
                        <h5 class="mt-3">Chauffeurs</h5>

                        <div class="table-responsive">
                            <table class="table table-bordered" id="table-parc-mission">
                                <thead>
                                    <tr>
                                        <th>Chauffeurs</th>
                                        {{-- <th>Statut</th> --}}
                                        <th>Véhicule</th>
                                        <th class="text-center">Dernière mission</th>
                                        <th class="text-center">Gain-{{ now()->year }}</th>
                                        <th class="text-center">Nuitées-Mois</th>
                                        <th class="text-center">Nuitées-Année</th>
                                        <th class="text-center" width="5%">Missions</th>
                                        <th class="text-center" width="5%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($chauffeurs as $chauffeur)
                                        @php
                                            // Missions de l'année
$missions = $chauffeur->employee->parcmissions;

// Date de retour la plus récente pour tri et affichage
$lastMission = $missions->sortByDesc('date_retour')->first();

// Montant total des missions
$totalMontant = $missions->sum('indemnites_total');

// Nombre de missions
$missionsCount = $missions->count();

// Pour les checkboxes et véhicules
$pivot = $mission->employees->find($chauffeur->employee_id)?->pivot;
$isChecked = $missionChauffeurs
    ->pluck('id')
    ->contains($chauffeur->employee_id);

// Pour modal : 5 dernières missions
$lastMissions = $missions->sortByDesc('date_depart')->take(5);
$missions = $chauffeur->employee->parcmissions;

// Missions de l'année en cours
                                            $missionsYear = $missions->filter(function ($mission) {
                                                return $mission->date_depart->year === now()->year;
                                            });

                                            // Missions du mois en cours
                                            $missionsMonth = $missions->filter(function ($mission) {
                                                return $mission->date_depart->month === now()->month &&
                                                    $mission->date_depart->year === now()->year;
                                            });

                                            // Comptages
                                            $missionsYearCount = $missionsYear->count();
                                            $missionsMonthCount = $missionsMonth->count();

                                            $nuiteesParMois = [];
                                            $nuiteesParAn = [];

                                            foreach ($missions as $mission) {
                                                foreach ($mission->nuitees_par_mois as $mois => $nb) {
                                                    $nuiteesParMois[$mois] = ($nuiteesParMois[$mois] ?? 0) + $nb;
                                                }

                                                foreach ($mission->nuitees_par_an as $anneeKey => $nb) {
                                                    $nuiteesParAn[$anneeKey] = ($nuiteesParAn[$anneeKey] ?? 0) + $nb;
                                                }
                                            }
                                            $currentMonth = now()->format('Y-m');
                                            $currentYear = now()->year;

                                            $nuiteesMonthCount = $nuiteesParMois[$currentMonth] ?? 0;
                                            $nuiteesYearCount = $nuiteesParAn[$currentYear] ?? 0;
                                        @endphp
                                        <tr>
                                            {{-- Checkbox Chauffeur --}}
                                            <td>
                                                <input type="checkbox" class="chauffeur-checkbox"
                                                    name="chauffeurs[{{ $chauffeur->id }}][selected]" value="1"
                                                    {{ $isChecked ? 'checked' : '' }}>
                                                {{ $chauffeur->employee->user->firstname }}
                                                {{ $chauffeur->employee->user->name }}
                                            </td>
                                            {{-- <td>
                                                <span class="etat-btn {{ $chauffeur?->statut }}">
                                                    {{ ucfirst(str_replace('fie', 'fié', str_replace('_', ' ', $chauffeur->statut))) }}
                                                </span>
                                            </td> --}}

                                            {{-- Select Véhicule --}}
                                            <td>
                                                <select name="chauffeurs[{{ $chauffeur->id }}][vehicule_id]"
                                                    class="form-select form-select-sm">
                                                    <option value="">-- Aucun véhicule --</option>
                                                    @foreach ($mission->vehicules as $vehicule)
                                                        <option value="{{ $vehicule->id }}"
                                                            {{ $pivot?->vehicule_id == $vehicule->id ? 'selected' : '' }}>
                                                            {{ $vehicule->immatriculation }} - {{ $vehicule->marque }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>

                                            {{-- Dernière mission --}}
                                            <td class="text-center">
                                                @if ($lastMission)
                                                    <span class="badge bg-info">
                                                        {{ $lastMission->date_retour->format('Y-m-d') }}
                                                    </span>
                                                @else
                                                    <span class="text-muted"></span>
                                                @endif
                                            </td>

                                            {{-- Montant annuel --}}
                                            <td class="text-center">
                                                <span class="badge bg-success">
                                                    {{ number_format($totalMontant, 0, ',', ' ') }}
                                                </span>
                                            </td>

                                            {{-- Missions du mois --}}
                                            {{-- <td class="text-center">
                                                <span class="badge bg-primary">{{ $missionsMonthCount }}</span>
                                            </td> --}}

                                            {{-- Missions de l'année --}}
                                            {{-- <td class="text-center">
                                                <span class="badge bg-success">{{ $missionsYearCount }}</span>
                                            </td> --}}

                                            <td class="text-center">
                                                <span class="badge bg-warning text-dark">
                                                    {{ $nuiteesMonthCount }}
                                                </span>
                                            </td>

                                            <td class="text-center">
                                                <span class="badge bg-dark">
                                                    {{ $nuiteesYearCount }}
                                                </span>
                                            </td>

                                            {{-- Nombre de missions --}}
                                            <td class="text-center">
                                                <span class="badge bg-secondary">{{ $missionsCount }}</span>
                                            </td>

                                            {{-- Actions --}}
                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal"
                                                    data-bs-target="#missionsModal{{ $chauffeur->id }}">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                            </td>
                                        </tr>

                                        {{-- Modal des missions --}}
                                        <div class="modal fade" id="missionsModal{{ $chauffeur->id }}" tabindex="-1"
                                            aria-labelledby="missionsModalLabel{{ $chauffeur->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-xl">
                                                <div class="modal-content">
                                                    {{-- Header --}}
                                                    <div class="modal-header bg-info text-white rounded-top-4">
                                                        <h5 class="modal-title"
                                                            id="missionsModalLabel{{ $chauffeur->id }}">
                                                            Missions de {{ $chauffeur->employee->user->firstname }}
                                                            {{ $chauffeur->employee->user->name }}
                                                        </h5>
                                                        <button type="button" class="btn-close btn-close-white"
                                                            data-bs-dismiss="modal" aria-label="Fermer"></button>
                                                    </div>

                                                    {{-- Body --}}
                                                    <div class="modal-body">
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

                        <div class="d-flex gap-2 m-3 justify-content-center">
                            <button class="btn btn-success btn-sm">
                                <i class="bi bi-check-circle"></i> Enregistrer
                            </button>
                            <a href="{{ route('parc-missions.show', $mission->id) }}" class="btn btn-secondary btn-sm">
                                Annuler
                            </a>
                        </div>

                        {{-- ================== EMPLOYÉS ================== --}}
                        <h5 class="mt-4">Autres employés</h5>

                        @foreach ($employees as $employee)
                            @php
                                $pivot = $mission->employees->find($employee->id)?->pivot;
                            @endphp

                            <div class="row mb-2 align-items-center">
                                <div class="col-md-8">
                                    <input type="checkbox" name="employees[{{ $employee->id }}][selected]"
                                        value="1" {{ $pivot ? 'checked' : '' }}>
                                    {{-- {{ $employee->matricule }} - --}}
                                    {{ $employee->user->name }} {{ $employee->user->firstname }},
                                    {{ $employee?->fonction?->name }}
                                </div>

                                <div class="col-md-2">
                                    <select name="employees[{{ $employee->id }}][role]"
                                        class="form-select form-select-sm">
                                        <option value="">Aucun</option>
                                        <option value="participant"
                                            {{ $pivot?->role === 'participant' ? 'selected' : '' }}>Participant</option>
                                        <option value="responsable"
                                            {{ $pivot?->role === 'responsable' ? 'selected' : '' }}>Responsable</option>
                                        <option value="observateur"
                                            {{ $pivot?->role === 'observateur' ? 'selected' : '' }}>Observateur</option>
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <select name="employees[{{ $employee->id }}][vehicule_id]"
                                        class="form-select form-select-sm">
                                        <option value="">-- Aucun véhicule --</option>
                                        @foreach ($mission->vehicules as $vehicule)
                                            <option value="{{ $vehicule->id }}"
                                                {{ $pivot?->vehicule_id == $vehicule->id ? 'selected' : '' }}>
                                                {{ $vehicule->immatriculation }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endforeach

                        {{-- <button class="btn btn-success btn-sm mt-3">
                            <i class="bi bi-check-circle"></i> Enregistrer
                        </button>
                        <a href="{{ route('parc-missions.show', $mission->id) }}" class="btn btn-secondary btn-sm">
                            Annuler
                        </a> --}}

                        <div class="d-flex gap-2 m-3 justify-content-center">
                            <button class="btn btn-success btn-sm">
                                <i class="bi bi-check-circle"></i> Enregistrer
                            </button>
                            <a href="{{ route('parc-missions.show', $mission->id) }}" class="btn btn-secondary btn-sm">
                                Annuler
                            </a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialiser DataTables sans recherche ni pagination
            new DataTable('#table-parc-mission', {
                ordering: true,
                order: [
                    [2, 'asc']
                ], // tri par colonne "Dernière mission"
                searching: false, // désactive la recherche
                paging: false, // désactive la pagination
                info: false // désactive le texte "affichage 1 à x sur y"
            });

            // Boutons de sélection/désélection
            const selectAllBtn = document.getElementById('select-all');
            const deselectAllBtn = document.getElementById('deselect-all');

            selectAllBtn.addEventListener('click', () => {
                document.querySelectorAll('.chauffeur-checkbox').forEach(cb => cb.checked = true);
            });

            deselectAllBtn.addEventListener('click', () => {
                document.querySelectorAll('.chauffeur-checkbox').forEach(cb => cb.checked = false);
            });
        });
    </script>
@endpush
