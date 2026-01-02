@extends('layout.user-layout')
@section('title', 'ONFP - Mise à jour des employés de la mission')

@section('space-work')
    <section class="section register">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="mb-0">Employés de la mission : {{ $mission->reference }}</h1>
                <a href="{{ route('parc-missions.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left-circle"></i> Retour à la liste
                </a>
            </div>

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

            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('parc-missions.employees.update', $mission) }}" method="POST">
                        @csrf
                        @method('PUT')

                        @foreach ($employees as $employee)
                            @php
                                // Récupérer le pivot pour cet employé
                                $pivot = $mission->employees->find($employee->id)?->pivot;
                            @endphp

                            <div class="row mb-2 align-items-center">
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input type="checkbox" class="employee-checkbox"
                                            name="employees[{{ $employee->id }}][id]" value="{{ $employee->id }}"
                                            {{ $pivot ? 'checked' : '' }}>
                                        <label class="form-check-label">
                                            {{ $employee?->matricule }} - {{ $employee?->user?->firstname }}
                                            {{ $employee?->user?->name }}
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <select name="employees[{{ $employee->id }}][role]" class="form-select form-select-sm">
                                        <option value="participant"
                                            {{ $pivot && $pivot->role == 'participant' ? 'selected' : '' }}>Participant
                                        </option>
                                        <option value="chauffeur"
                                            {{ $pivot && $pivot->role == 'chauffeur' ? 'selected' : '' }}>Chauffeur
                                        </option>
                                        <option value="responsable"
                                            {{ $pivot && $pivot->role == 'responsable' ? 'selected' : '' }}>Responsable
                                        </option>
                                        <option value="observateur"
                                            {{ $pivot && $pivot->role == 'observateur' ? 'selected' : '' }}>Observateur
                                        </option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <select name="employees[{{ $employee->id }}][vehicule_id]"
                                        class="form-select form-select-sm">
                                        <option value="">-- Aucun véhicule --</option>
                                        @foreach ($mission->vehicules as $vehicule)
                                            <option value="{{ $vehicule->id }}"
                                                {{ $pivot && $pivot->vehicule_id == $vehicule->id ? 'selected' : '' }}>
                                                {{ $vehicule->immatriculation }} - {{ $vehicule->marque }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endforeach


                        <div class="d-flex gap-2 mt-3">
                            <button type="submit" class="btn btn-success btn-sm">
                                <i class="bi bi-check-circle"></i> Mettre à jour
                            </button>
                            <a href="{{ route('parc-missions.index') }}" class="btn btn-secondary btn-sm">
                                <i class="bi bi-x-circle"></i> Annuler
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
        document.querySelectorAll('#select-all').forEach(btn => btn.addEventListener('click', function() {
            document.querySelectorAll('.employee-checkbox').forEach(cb => cb.checked = true);
        }));

        document.querySelectorAll('#deselect-all').forEach(btn => btn.addEventListener('click', function() {
            document.querySelectorAll('.employee-checkbox').forEach(cb => cb.checked = false);
        }));
    </script>
@endpush
