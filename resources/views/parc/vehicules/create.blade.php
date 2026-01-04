@extends('layout.user-layout')
@section('title', 'ONFP - Ajouter un véhicule')

@section('space-work')
    <section class="section register">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="mb-0">Ajouter un véhicule</h1>
                <a href="{{ route('parc-vehicules.index') }}" class="btn btn-outline-secondary btn-sm">
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
                    <form action="{{ route('parc-vehicules.store') }}" method="POST">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="immatriculation" class="form-label">Immatriculation<span
                                        class="text-danger mx-1">*</span></label>
                                <input type="text" name="immatriculation"
                                    class="form-control form-control-sm @error('immatriculation') is-invalid @enderror"
                                    value="{{ old('immatriculation') }}" placeholder="DK-1987-EP38">
                                @error('immatriculation')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="marque" class="form-label">Marque<span
                                        class="text-danger mx-1">*</span></label>
                                <input type="text" name="marque"
                                    class="form-control form-control-sm @error('marque') is-invalid @enderror"
                                    value="{{ old('marque') }}" placeholder="Toyota">
                                @error('marque')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="modele" class="form-label">Modèle</label>
                                <input type="text" name="modele"
                                    class="form-control form-control-sm @error('modele') is-invalid @enderror"
                                    value="{{ old('modele') }}" placeholder="RAV4">
                                @error('modele')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="annee" class="form-label">
                                    Année <span class="text-danger mx-1">*</span>
                                </label>
                                <input type="number" name="annee"
                                    class="form-control form-control-sm @error('annee') is-invalid @enderror"
                                    value="{{ old('annee', $vehicule->annee ?? '') }}" placeholder="2020" min="2010"
                                    max="{{ date('Y') }}">
                                @error('annee')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="etat" class="form-label">État<span class="text-danger mx-1">*</span></label>
                                <select name="etat"
                                    class="form-select form-select-sm @error('etat') is-invalid @enderror">
                                    <option value="operationnel" {{ old('etat') == 'operationnel' ? 'selected' : '' }}>
                                        Opérationnel</option>
                                    <option value="maintenance" {{ old('etat') == 'maintenance' ? 'selected' : '' }}>
                                        Maintenance</option>
                                    <option value="hors_service" {{ old('etat') == 'hors_service' ? 'selected' : '' }}>Hors
                                        service</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="chauffeur_id" class="form-label">Chauffeur affecté</label>
                                <select name="chauffeur_id" class="form-select form-select-sm">
                                    <option value="">-- Aucun chauffeur --</option>
                                    @foreach ($chauffeurs as $chauffeur)
                                        <option value="{{ $chauffeur->id }}"
                                            {{ old('chauffeur_id', $vehicule->chauffeur_id ?? '') == $chauffeur->id ? 'selected' : '' }}>
                                            {{ $chauffeur->nom }} {{ $chauffeur->prenom }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="categorie" class="form-label">Catégorie</label>
                                <input type="text" name="categorie" class="form-control form-control-sm"
                                    value="{{ old('categorie') }}" placeholder="Ex: Voiture particulière">
                            </div>
                            <div class="col-md-6">
                                <label for="energie" class="form-label">Énergie</label>
                                <select name="energie" class="form-select form-select-sm">
                                    <option value="">-- Choisir --</option>
                                    <option value="diesel" {{ old('energie') == 'diesel' ? 'selected' : '' }}>Diesel
                                    </option>
                                    <option value="essence" {{ old('energie') == 'essence' ? 'selected' : '' }}>
                                        Essence</option>
                                    <option value="hybride" {{ old('energie') == 'hybride' ? 'selected' : '' }}>
                                        Hybride</option>
                                    <option value="electrique" {{ old('energie') == 'electrique' ? 'selected' : '' }}>
                                        Électrique</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="kilometrage_actuel" class="form-label">Kilométrage actuel</label>
                                <input type="number" name="kilometrage_actuel" id="kilometrage_actuel"
                                    class="form-control form-control-sm @error('kilometrage_actuel') is-invalid @enderror"
                                    min="0" value="{{ old('kilometrage_actuel', 0) }}" min="0">
                                @error('kilometrage_actuel')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="assurance_expire_le" class="form-label">Assurance expire le</label>
                                <input type="date" name="assurance_expire_le" class="form-control form-control-sm"
                                    value="{{ old('assurance_expire_le') }}">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="visite_technique_expire_le" class="form-label">Visite technique expire
                                    le</label>
                                <input type="date" name="visite_technique_expire_le"
                                    class="form-control form-control-sm" value="{{ old('visite_technique_expire_le') }}">
                            </div>

                            <div class="col-md-6">
                                <label for="capacite_reservoir" class="form-label">Capacité réservoir (en litre)</label>
                                <input type="number" name="capacite_reservoir" class="form-control form-control-sm"
                                    value="{{ old('capacite_reservoir', 0) }}" placeholder="Ex: 200" min="0"
                                    step="1">
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success btn-sm">
                                <i class="bi bi-check-circle"></i> Enregistrer
                            </button>
                            <a href="{{ route('parc-vehicules.index') }}" class="btn btn-secondary btn-sm">
                                <i class="bi bi-x-circle"></i> Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
