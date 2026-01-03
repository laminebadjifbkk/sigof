@extends('layout.user-layout')
@section('title', 'ONFP - Mise à jour des chauffeurs de la mission')

@section('space-work')
    <section class="section register">
        <div class="container">

            {{-- En-tête --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="mb-0">Chauffeurs de la mission : {{ $mission->reference }}</h3>
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
                                    <th class="text-center">Missions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($chauffeurs as $chauffeur)
                                    @php
                                        $isChecked = $missionChauffeurs->pluck('id')->contains($chauffeur->employee_id);
                                    @endphp
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="chauffeurs[{{ $chauffeur->id }}][selected]"
                                                value="1" {{ $isChecked ? 'checked' : '' }}>
                                            {{ $chauffeur->employee->user->firstname }}
                                            {{ $chauffeur->employee->user->name }}
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-secondary">
                                                {{ $chauffeur->employee->parcmissions->count() }}
                                            </span>
                                        </td>
                                    </tr>
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
                                    {{ $employee->user->firstname }} {{ $employee->user->name }}
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
