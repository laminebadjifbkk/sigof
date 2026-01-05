@extends('layout.user-layout')
@section('title', 'ONFP - Ajouter une mission')

@section('space-work')
    <section class="section register">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="mb-0">Ajouter une mission</h1>
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
                    <form action="{{ route('parc-missions.store') }}" method="POST">
                        @csrf

                        {{-- Référence --}}
                        <div class="mb-3">
                            <label for="reference" class="form-label">Référence<span class="text-danger">
                                    *</span></label>
                            <input type="text" name="reference"
                                class="form-control form-control-sm @error('reference') is-invalid @enderror"
                                value="{{ old('reference', $reference) }}" placeholder="Ex: 2026-001" readonly>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 col-sm-12">
                                <label for="statut" class="form-label">Statut mission<span class="text-danger">
                                        *</span></label>
                                <select name="statut" id="statut"
                                    class="form-select form-select-sm @error('statut') is-invalid @enderror">
                                    <option value="">-- Choisir un statut --</option>
                                    <option value="planifiee"
                                        {{ old('statut', 'planifiee' ?? '') == 'planifiee' ? 'selected' : '' }}>
                                        Planifiée
                                    </option>

                                </select>
                                @error('statut')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <label for="type_mission_id" class="form-label">
                                    Type de mission <span class="text-danger">
                                        *</span></label>
                                <select name="type_mission_id"
                                    class="form-select form-select-sm @error('type_mission_id') is-invalid @enderror">
                                    <option value="">-- Choisir un type de mission --</option>
                                    @foreach ($typesMissions as $type)
                                        <option value="{{ $type->id }}"
                                            {{ old('type_mission_id') == $type->id ? 'selected' : '' }}>
                                            {{ $type->libelle }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('type_mission_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="objet" class="form-label">Objet<span class="text-danger"> *</span></label>
                            <input type="text" name="objet"
                                class="form-control form-control-sm @error('objet') is-invalid @enderror"
                                value="{{ old('objet') }}" placeholder="Ex: Mission de ...">
                            @error('objet')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 col-sm-12">
                                <label for="lieu_depart" class="form-label">Lieu de départ<span class="text-danger">
                                        *</span></label>
                                <input type="text" name="lieu_depart"
                                    class="form-control form-control-sm @error('lieu_depart') is-invalid @enderror"
                                    value="{{ old('lieu_depart') }}" placeholder="Ex: Dakar">
                                @error('lieu_depart')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <label for="lieu_arrivee" class="form-label">Lieu d’arrivée<span class="text-danger">
                                        *</span></label>
                                <input type="text" name="lieu_arrivee"
                                    class="form-control form-control-sm @error('lieu_arrivee') is-invalid @enderror"
                                    value="{{ old('lieu_arrivee') }}" placeholder="Ex: Saint-Louis">
                                @error('lieu_arrivee')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 col-sm-12">
                                <label for="itineraire" class="form-label">Itinéraire<span class="text-danger">
                                        *</span></label>
                                <input type="text" name="itineraire"
                                    class="form-control form-control-sm @error('itineraire') is-invalid @enderror"
                                    value="{{ old('itineraire') }}" placeholder="Ex: Dakar-Kaolack-Dakar">
                                @error('itineraire')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <label for="autres" class="form-label">Nombre de véhicules prévus<span
                                        class="text-danger">
                                        *</span></label>
                                <input type="number" name="autres"
                                    class="form-control form-control-sm @error('autres') is-invalid @enderror"
                                    value="{{ old('autres', 1) }}" placeholder="Ex: 1" min="1" step="1">
                                @error('autres')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 col-sm-12">
                                <label for="departement" class="form-label">Département(s)</label>
                                <input type="text" name="departement"
                                    class="form-control form-control-sm @error('departement') is-invalid @enderror"
                                    value="{{ old('departement') }}" placeholder="Ex: Dakar">
                                @error('departement')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <label for="region" class="form-label">Région(s)</label>
                                <input type="text" name="region"
                                    class="form-control form-control-sm @error('region') is-invalid @enderror"
                                    value="{{ old('region') }}" placeholder="Ex: Saint-Louis">
                                @error('region')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 col-sm-12">
                                <label for="date_depart" class="form-label">Date de départ<span class="text-danger">
                                        *</span></label>
                                <input type="date" name="date_depart"
                                    class="form-control form-control-sm @error('date_depart') is-invalid @enderror"
                                    value="{{ old('date_depart') }}">
                                @error('date_depart')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <label for="date_retour" class="form-label">Date de retour<span class="text-danger">
                                        *</span></label>
                                <input type="date" name="date_retour"
                                    class="form-control form-control-sm @error('date_retour') is-invalid @enderror"
                                    value="{{ old('date_retour') }}">
                                @error('date_retour')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- <div class="col-md-6 col-sm-12">
                                <label for="indemnite_mission" class="form-label">Indemnité de mission</label>
                                <input type="number" step="0.01" min="0" name="indemnite_mission"
                                    class="form-control form-control-sm @error('indemnite_mission') is-invalid @enderror"
                                    value="{{ old('indemnite_mission') }}" placeholder="Ex: 50000">
                                @error('indemnite_mission')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div> --}}
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 col-sm-12">
                                <label for="taux_journalier" class="form-label">Taux journalier</label>
                                <input type="number" step="0.01" min="0" name="taux_journalier"
                                    class="form-control form-control-sm @error('taux_journalier') is-invalid @enderror"
                                    value="{{ old('taux_journalier') }}" placeholder="Ex: 25000">
                                @error('taux_journalier')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <label for="frais_deplacement" class="form-label">Frais de déplacement</label>
                                <input type="number" step="0.01" min="0" name="frais_deplacement"
                                    class="form-control form-control-sm @error('frais_deplacement') is-invalid @enderror"
                                    value="{{ old('frais_deplacement', 0) }}" placeholder="Ex: 20000" min="0"
                                    step="1">
                                @error('frais_deplacement')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            {{-- <div class="col-md-6 col-sm-12">
                                <label for="reliquat" class="form-label">Reliquat</label>
                                <input type="number" step="0.01" min="0" name="reliquat"
                                    class="form-control form-control-sm @error('reliquat') is-invalid @enderror"
                                    value="{{ old('reliquat') }}" placeholder="Ex: 20000">
                                @error('reliquat')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div> --}}
                        </div>

                        <div class="row mb-3">

                            <div class="col-md-6 col-sm-12">
                                <label for="avance" class="form-label">Avance</label>
                                <input type="number" step="0.01" min="0" name="avance"
                                    class="form-control form-control-sm @error('avance') is-invalid @enderror"
                                    value="{{ old('avance', 0) }}" placeholder="Ex: 30000" min="0"
                                    step="1">
                                @error('avance')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            {{-- <div class="col-md-6 col-sm-12">
                                <label for="vehicule_id" class="form-label">Véhicule</label>
                                <select name="vehicule_id" class="form-select form-select-sm">
                                    <option value="">-- Aucun véhicule --</option>
                                    @foreach ($vehicules as $vehicule)
                                        <option value="{{ $vehicule->id }}">{{ $vehicule->immatriculation }} -
                                            {{ $vehicule->marque }}</option>
                                    @endforeach
                                </select>
                            </div> --}}

                            <!-- Nouveau champ Kilométrage actuel -->
                            <div class="col-md-6 col-sm-12">
                                <label for="distance_km" class="form-label">Distance (km)</label>
                                <input type="number" name="distance_km" id="distance_km"
                                    class="form-control form-control-sm @error('distance_km') is-invalid @enderror"
                                    min="0" value="{{ old('distance_km', 0) }}" placeholder="Ex: 125000 km">
                                @error('distance_km')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            {{-- <div class="col-md-6 col-sm-12">
                                <label for="chauffeur_id" class="form-label">Chauffeur affecté</label>
                                <select name="chauffeur_id" class="form-select form-select-sm">
                                    <option value="">-- Aucun chauffeur --</option>
                                    @foreach ($chauffeurs as $chauffeur)
                                        <option value="{{ $chauffeur->id }}">{{ $chauffeur->nom }}
                                            {{ $chauffeur->prenom }}
                                        </option>
                                    @endforeach
                                </select>
                            </div> --}}

                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success btn-sm">
                                <i class="bi bi-check-circle"></i> Enregistrer
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
