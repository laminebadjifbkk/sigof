@extends('layout.user-layout')
@section('title', 'ONFP - Modifier une mission')

@section('space-work')
    <section class="section register">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="mb-0">Modifier la mission : {{ $mission->reference }}</h1>
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
                    <form action="{{ route('parc-missions.update', $mission) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="reference" class="form-label">Référence</label>
                            <input type="text" name="reference" class="form-control form-control-sm"
                                value="{{ old('reference', $mission->reference) }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="objet" class="form-label">Objet</label>
                            <input type="text" name="objet" class="form-control form-control-sm"
                                value="{{ old('objet', $mission->objet) }}" required>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="lieu_depart" class="form-label">Lieu de départ</label>
                                <input type="text" name="lieu_depart" class="form-control form-control-sm"
                                    value="{{ old('lieu_depart', $mission->lieu_depart) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="lieu_arrivee" class="form-label">Lieu d’arrivée</label>
                                <input type="text" name="lieu_arrivee" class="form-control form-control-sm"
                                    value="{{ old('lieu_arrivee', $mission->lieu_arrivee) }}" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="date_depart" class="form-label">Date de départ</label>
                                <input type="date" name="date_depart" class="form-control form-control-sm"
                                    value="{{ old('date_depart', $mission->date_depart?->format('Y-m-d')) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="date_retour" class="form-label">Date de retour</label>
                                <input type="date" name="date_retour" class="form-control form-control-sm"
                                    value="{{ old('date_retour', $mission->date_retour?->format('Y-m-d')) }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="vehicule_id" class="form-label">Véhicule</label>
                            <select name="vehicule_id" class="form-select form-select-sm">
                                <option value="">-- Aucun véhicule --</option>
                                @foreach ($vehicules as $vehicule)
                                    <option value="{{ $vehicule->id }}"
                                        {{ old('vehicule_id', $mission->vehicule_id) == $vehicule->id ? 'selected' : '' }}>
                                        {{ $vehicule->immatriculation }} - {{ $vehicule->marque }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="chauffeur_id" class="form-label">Chauffeur</label>
                            <select name="chauffeur_id" class="form-select form-select-sm">
                                <option value="">-- Aucun chauffeur --</option>
                                @foreach ($chauffeurs as $chauffeur)
                                    <option value="{{ $chauffeur->id }}"
                                        {{ old('chauffeur_id', $mission->chauffeur_id) == $chauffeur->id ? 'selected' : '' }}>
                                        {{ $chauffeur->nom }} {{ $chauffeur->prenom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="statut" class="form-label">Statut</label>
                            <select name="statut" id="statut" class="form-select form-select-sm" required>
                                <option value="">-- Choisir un statut --</option>
                                <option value="planifiee"
                                    {{ old('statut', $mission->statut) == 'planifiee' ? 'selected' : '' }}>Planifiée
                                </option>
                                <option value="en_cours"
                                    {{ old('statut', $mission->statut) == 'en_cours' ? 'selected' : '' }}>En cours</option>
                                <option value="terminee"
                                    {{ old('statut', $mission->statut) == 'terminee' ? 'selected' : '' }}>Terminée</option>
                                <option value="annulee"
                                    {{ old('statut', $mission->statut) == 'annulee' ? 'selected' : '' }}>Annulée</option>
                            </select>
                            @error('statut')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="distance_km" class="form-label">Distance (km)</label>
                            <input type="number" name="distance_km" class="form-control form-control-sm"
                                value="{{ old('distance_km', $mission->distance_km) }}" min="0">
                        </div>

                        <div class="mb-3">
                            <label for="indemnites_total" class="form-label">Indemnités totales</label>
                            <input type="number" step="0.01" name="indemnites_total"
                                class="form-control form-control-sm"
                                value="{{ old('indemnites_total', $mission->indemnites_total) }}" min="0">
                        </div>
                        {{-- <div class="mb-3">
                            <label for="employees" class="form-label">Employés affectés</label>
                            <select name="employees[]" id="employees" class="form-select form-select-sm" multiple>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}"
                                        {{ in_array($employee->id, old('employees', $mission->employees->pluck('id')->toArray())) ? 'selected' : '' }}>
                                        {{ $employee->matricule }} - {{ $employee->nom }} {{ $employee->prenom }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Maintenez CTRL (ou CMD sur Mac) pour sélectionner plusieurs
                                employés.</small>
                        </div> --}}
                        {{-- <div class="mb-3">
                            <label for="employees" class="form-label">Employés affectés</label>
                            <select name="employees[]" id="multiple-select-field" class="form-select" multiple
                                data-placeholder="Choisir un ou plusieurs employés">
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}"
                                        {{ in_array($employee->id, old('employees', $mission->employees->pluck('id')->toArray())) ? 'selected' : '' }}>
                                        {{ $employee->matricule }} - {{ $employee?->user?->firstname }}
                                        {{ $employee?->user?->name }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Maintenez CTRL (ou CMD sur Mac) pour sélectionner plusieurs
                                employés.</small>
                        </div> --}}
                        @foreach ($employees as $employee)
                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input type="checkbox" name="employees[{{ $employee->id }}][id]"
                                            value="{{ $employee->id }}"
                                            {{ $mission->employees->contains($employee->id) ? 'checked' : '' }}>
                                        <label class="form-check-label">
                                            {{ $employee?->user?->matricule }} - {{ $employee?->user?->firstname }} {{ $employee?->user?->name }}
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <select name="employees[{{ $employee->id }}][role]"
                                        class="form-select form-select-sm">
                                        <option value="participant"
                                            {{ $mission->employees->find($employee->id)?->pivot->role == 'participant' ? 'selected' : '' }}>
                                            Participant</option>
                                        <option value="responsable"
                                            {{ $mission->employees->find($employee->id)?->pivot->role == 'responsable' ? 'selected' : '' }}>
                                            Responsable</option>
                                        <option value="observateur"
                                            {{ $mission->employees->find($employee->id)?->pivot->role == 'observateur' ? 'selected' : '' }}>
                                            Observateur</option>
                                    </select>
                                </div>
                            </div>
                        @endforeach
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success btn-sm">
                                <i class="bi bi-check-circle"></i> Enregistrer les modifications
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
