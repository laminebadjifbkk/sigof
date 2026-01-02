@extends('layout.user-layout')
@section('title', 'ONFP - Modifier un chauffeur')

@section('space-work')
    <section class="section register">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="mb-0">Modifier le chauffeur</h1>
                <a href="{{ route('parc-chauffeurs.index') }}" class="btn btn-sm btn-outline-secondary">
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
                    <form action="{{ route('parc-chauffeurs.update', $chauffeur->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="matricule" class="form-label">Matricule <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="matricule"
                                    class="form-control form-control-sm @error('matricule') is-invalid @enderror"
                                    value="{{ old('matricule', $chauffeur->matricule) }}" placeholder="matricule">
                                @error('matricule')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
                                <input type="text" name="nom"
                                    class="form-control form-control-sm @error('nom') is-invalid @enderror"
                                    value="{{ old('nom', $chauffeur->nom) }}" placeholder="Diallo">
                                @error('nom')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="prenom" class="form-label">Prénom</label>
                                <input type="text" name="prenom" class="form-control form-control-sm"
                                    value="{{ old('prenom', $chauffeur->prenom) }}" placeholder="Mamadou">
                            </div>
                            <div class="col-md-6">
                                <label for="telephone" class="form-label">Téléphone</label>
                                <input type="text" name="telephone" class="form-control form-control-sm"
                                    value="{{ old('telephone', $chauffeur->telephone) }}" placeholder="77 123 45 67">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="permis_numero" class="form-label">Numéro du permis</label>
                                <input type="text" name="permis_numero" class="form-control form-control-sm"
                                    value="{{ old('permis_numero', $chauffeur->permis_numero) }}" placeholder="PER-12345">
                            </div>
                            <div class="col-md-6">
                                <label for="permis_categories" class="form-label">Catégories du permis</label>
                                <input type="text" name="permis_categories" class="form-control form-control-sm"
                                    value="{{ old('permis_categories', $chauffeur->permis_categories) }}"
                                    placeholder="B, C, D">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="permis_expire_le" class="form-label">Expiration du permis</label>
                                <input type="date" name="permis_expire_le" class="form-control form-control-sm"
                                    value="{{ old('permis_expire_le', $chauffeur->permis_expire_le_formatted) }}">
                            </div>
                            <div class="col-md-6">
                                <label for="statut" class="form-label">Statut <span class="text-danger">*</span></label>
                                <select name="statut" class="form-select form-select-sm">
                                    <option value="actif"
                                        {{ old('statut', $chauffeur->statut) == 'actif' ? 'selected' : '' }}>Actif</option>
                                    <option value="inactif"
                                        {{ old('statut', $chauffeur->statut) == 'indisponible' ? 'selected' : '' }}>
                                        Indisponible
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-sm btn-success">
                                <i class="bi bi-check-circle"></i> Mettre à jour
                            </button>
                            <a href="{{ route('parc-chauffeurs.index') }}" class="btn btn-sm btn-secondary">
                                <i class="bi bi-x-circle"></i> Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
