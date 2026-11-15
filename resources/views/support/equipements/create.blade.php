@extends('layout.user-layout')

@section('title', 'ONFP | Nouvel équipement')

@section('space-work')
<div class="container-fluid py-3">

    {{-- EN-TÊTE --}}
    <div class="mb-4">
        <a href="{{ route('support.equipements.index') }}"
           class="text-decoration-none text-muted">
            <i class="bi bi-arrow-left me-1"></i>
            Retour au parc informatique
        </a>

        <h3 class="fw-bold mt-2 mb-1">Enregistrer un équipement</h3>
        <p class="text-muted mb-0">
            Ajouter un équipement durable au patrimoine informatique de l'ONFP.
        </p>
    </div>

    {{-- ERREURS --}}
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <strong>Veuillez corriger les erreurs suivantes :</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $erreur)
                    <li>{{ $erreur }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('support.equipements.store') }}">
        @csrf

        {{-- IDENTIFICATION --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0">
                    <i class="bi bi-pc-display me-2" style="color:#F28500"></i>
                    Identification de l'équipement
                </h5>
            </div>

            <div class="card-body">
                <div class="row g-3">

                    <div class="col-md-6">
                        <label for="designation" class="form-label">
                            Désignation <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               id="designation"
                               name="designation"
                               class="form-control form-control-sm @error('designation') is-invalid @enderror"
                               value="{{ old('designation') }}"
                               maxlength="200"
                               placeholder="Ex. Ordinateur portable HP EliteBook"
                               required>
                        @error('designation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="support_categorie_equipement_id" class="form-label">
                            Catégorie <span class="text-danger">*</span>
                        </label>
                        <select id="support_categorie_equipement_id"
                                name="support_categorie_equipement_id"
                                class="form-select form-select-sm @error('support_categorie_equipement_id') is-invalid @enderror"
                                required>
                            <option value="">Sélectionner une catégorie</option>
                            @foreach($categories as $categorie)
                                <option value="{{ $categorie->id }}"
                                    @selected(old('support_categorie_equipement_id') == $categorie->id)>
                                    {{ $categorie->libelle }}
                                </option>
                            @endforeach
                        </select>
                        @error('support_categorie_equipement_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="marque" class="form-label">Marque</label>
                        <input type="text"
                               id="marque"
                               name="marque"
                               class="form-control form-control-sm @error('marque') is-invalid @enderror"
                               value="{{ old('marque') }}"
                               maxlength="100"
                               placeholder="Ex. HP, Dell, Lenovo">
                        @error('marque')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="modele" class="form-label">Modèle</label>
                        <input type="text"
                               id="modele"
                               name="modele"
                               class="form-control form-control-sm @error('modele') is-invalid @enderror"
                               value="{{ old('modele') }}"
                               maxlength="150"
                               placeholder="Ex. EliteBook 840 G11">
                        @error('modele')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="numero_serie" class="form-label">Numéro de série</label>
                        <input type="text"
                               id="numero_serie"
                               name="numero_serie"
                               class="form-control form-control-sm @error('numero_serie') is-invalid @enderror"
                               value="{{ old('numero_serie') }}"
                               maxlength="150"
                               placeholder="Numéro de série constructeur">
                        @error('numero_serie')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>
            </div>
        </div>

        {{-- ACQUISITION --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0">
                    <i class="bi bi-receipt me-2" style="color:#F28500"></i>
                    Informations d'acquisition
                </h5>
            </div>

            <div class="card-body">
                <div class="row g-3">

                    <div class="col-md-3">
                        <label for="date_acquisition" class="form-label">
                            Date d'acquisition
                        </label>
                        <input type="date"
                               id="date_acquisition"
                               name="date_acquisition"
                               class="form-control form-control-sm @error('date_acquisition') is-invalid @enderror"
                               value="{{ old('date_acquisition') }}">
                        @error('date_acquisition')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label for="fournisseur" class="form-label">Fournisseur</label>
                        <input type="text"
                               id="fournisseur"
                               name="fournisseur"
                               class="form-control form-control-sm @error('fournisseur') is-invalid @enderror"
                               value="{{ old('fournisseur') }}"
                               maxlength="200">
                        @error('fournisseur')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label for="prix_acquisition" class="form-label">
                            Prix d'acquisition (FCFA)
                        </label>
                        <input type="number"
                               id="prix_acquisition"
                               name="prix_acquisition"
                               class="form-control form-control-sm @error('prix_acquisition') is-invalid @enderror"
                               value="{{ old('prix_acquisition') }}"
                               min="0"
                               step="0.01"
                               placeholder="Ex. 450000">
                        @error('prix_acquisition')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label for="date_fin_garantie" class="form-label">
                            Fin de garantie
                        </label>
                        <input type="date"
                               id="date_fin_garantie"
                               name="date_fin_garantie"
                               class="form-control form-control-sm @error('date_fin_garantie') is-invalid @enderror"
                               value="{{ old('date_fin_garantie') }}">
                        @error('date_fin_garantie')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>
            </div>
        </div>

        {{-- ETAT ET LOCALISATION --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0">
                    <i class="bi bi-geo-alt me-2" style="color:#F28500"></i>
                    État physique et localisation
                </h5>
            </div>

            <div class="card-body">
                <div class="row g-3">

                    <div class="col-md-4">
                        <label for="etat_physique" class="form-label">
                            État physique initial
                        </label>
                        <select id="etat_physique"
                                name="etat_physique"
                                class="form-select form-select-sm @error('etat_physique') is-invalid @enderror">
                            <option value="BON" @selected(old('etat_physique', 'BON') === 'BON')>
                                Bon
                            </option>
                            <option value="MOYEN" @selected(old('etat_physique') === 'MOYEN')>
                                Moyen
                            </option>
                            <option value="MAUVAIS" @selected(old('etat_physique') === 'MAUVAIS')>
                                Mauvais
                            </option>
                            <option value="HORS_SERVICE" @selected(old('etat_physique') === 'HORS_SERVICE')>
                                Hors service
                            </option>
                        </select>
                        @error('etat_physique')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-8">
                        <label for="emplacement" class="form-label">
                            Emplacement actuel
                        </label>
                        <input type="text"
                               id="emplacement"
                               name="emplacement"
                               class="form-control form-control-sm @error('emplacement') is-invalid @enderror"
                               value="{{ old('emplacement') }}"
                               maxlength="200"
                               placeholder="Ex. Siège ONFP, salle informatique, bureau...">
                        @error('emplacement')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <div class="alert alert-info py-2 mb-0 small">
                            <i class="bi bi-info-circle me-2"></i>
                            Le nouvel équipement sera automatiquement enregistré
                            avec le statut <strong>En stock</strong>.
                            Son affectation à un employé, une direction ou une antenne
                            sera effectuée depuis sa fiche.
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- INFORMATIONS COMPLEMENTAIRES --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0">
                    <i class="bi bi-card-text me-2" style="color:#F28500"></i>
                    Informations complémentaires
                </h5>
            </div>

            <div class="card-body">
                <div class="row g-3">

                    <div class="col-md-6">
                        <label for="description" class="form-label">
                            Description technique
                        </label>
                        <textarea id="description"
                                  name="description"
                                  rows="4"
                                  class="form-control form-control-sm @error('description') is-invalid @enderror"
                                  placeholder="Caractéristiques techniques, configuration, spécifications...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="observations" class="form-label">
                            Observations
                        </label>
                        <textarea id="observations"
                                  name="observations"
                                  rows="4"
                                  class="form-control form-control-sm @error('observations') is-invalid @enderror"
                                  placeholder="Informations ou remarques complémentaires...">{{ old('observations') }}</textarea>
                        @error('observations')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>
            </div>
        </div>

        {{-- ACTIONS --}}
        <div class="d-flex justify-content-end gap-2 mb-4">
            <a href="{{ route('support.equipements.index') }}"
               class="btn btn-sm btn-outline-secondary">
                Annuler
            </a>

            <button type="submit" class="btn btn-sm text-white" style="background:#F28500">
                <i class="bi bi-save me-1"></i>
                Enregistrer l'équipement
            </button>
        </div>

    </form>
</div>
@endsection
