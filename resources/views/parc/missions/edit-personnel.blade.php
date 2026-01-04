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
                    </div>

                    {{-- Formulaire --}}
                    <form action="{{ route('parc-missions.personnel.update', $mission) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- ================== CHAUFFEURS ================== --}}
                        <h5 class="mt-3">Chauffeurs</h5>

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Chauffeur</th>
                                    <th>Véhicule</th> {{-- Nouvelle colonne --}}
                                    <th class="text-center" width="5%">Missions</th>
                                    <th class="text-center" width="5%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($chauffeurs as $chauffeur)
                                    @php
                                        $pivot = $mission->employees->find($chauffeur->employee_id)?->pivot;
                                        $isChecked = $missionChauffeurs->pluck('id')->contains($chauffeur->employee_id);
                                        $missionsCount = $chauffeur->employee->parcmissions->count();
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

                                        {{-- Missions --}}
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
                                                    <h5 class="modal-title" id="missionsModalLabel{{ $chauffeur->id }}">
                                                        Missions de {{ $chauffeur->employee->user->firstname }}
                                                        {{ $chauffeur->employee->user->name }}
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white"
                                                        data-bs-dismiss="modal" aria-label="Fermer"></button>
                                                </div>
                                                {{-- Body --}}
                                                <div class="modal-body">
                                                    @php
                                                        $lastMissions = $chauffeur->employee->parcmissions
                                                            ->sortByDesc('date_depart')
                                                            ->take(5);
                                                    @endphp

                                                    @if ($lastMissions->count() > 0)
                                                        <div class="list-group">
                                                            @foreach ($lastMissions as $cm)
                                                                <div
                                                                    class="list-group-item d-flex justify-content-between align-items-center border-bottom">
                                                                    <div>
                                                                        <strong>{{ $cm->objet }}</strong><br>
                                                                        <small class="text-muted">Réf:
                                                                            {{ $cm->reference }}</small>
                                                                    </div>
                                                                    <div class="text-end">
                                                                        <span class="badge bg-secondary">
                                                                            Du {{ $cm->date_depart->format('d/m/Y') }} au
                                                                            {{ $cm->date_retour->format('d/m/Y') }}
                                                                        </span>

                                                                        @php
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

                        {{-- ================== EMPLOYÉS ================== --}}
                        <h5 class="mt-4">Autres employés</h5>

                        @foreach ($employees as $employee)
                            @php
                                $pivot = $mission->employees->find($employee->id)?->pivot;
                            @endphp

                            <div class="row mb-2 align-items-center">
                                <div class="col-md-4">
                                    <input type="checkbox" name="employees[{{ $employee->id }}][selected]" value="1"
                                        {{ $pivot ? 'checked' : '' }}>
                                    {{ $employee->matricule }} -
                                    {{ $employee->user->name }} {{ $employee->user->firstname }}
                                </div>

                                <div class="col-md-4">
                                    <select name="employees[{{ $employee->id }}][role]" class="form-select form-select-sm">
                                        <option value="">Aucun</option>
                                        <option value="participant"
                                            {{ $pivot?->role === 'participant' ? 'selected' : '' }}>Participant</option>
                                        <option value="responsable"
                                            {{ $pivot?->role === 'responsable' ? 'selected' : '' }}>Responsable</option>
                                        <option value="observateur"
                                            {{ $pivot?->role === 'observateur' ? 'selected' : '' }}>Observateur</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
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

                        <div class="d-flex gap-2">
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
