@extends('layout.user-layout')

@section('title', 'ONFP | Modifier une inscription')

@section('space-work')
    @can('formulaire-edit')
        <section class="section">
            <div class="row justify-content-center">
                <div class="col-lg-12 col-md-12">

                    <div class="card shadow-sm">
                        <div class="card-header bg-warning text-white">
                            <h5 class="mb-0">Modifier l’inscription</h5>
                        </div>

                        <div class="card-body">
                            {{-- Message de succès ou d’erreur --}}
                            @if (session('status'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('status') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Fermer"></button>
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

                            <form action="{{ route('formulaires.update', $formulaire->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="row g-3">
                                    @foreach ($labels as $field => $label)
                                        @if (in_array($field, $fileFields))
                                            {{-- Champs fichiers --}}
                                            <div class="col-lg-4 col-md-4">
                                                <label class="form-label fw-semibold">{{ $label }}</label>
                                                <input type="file" name="{{ $field }}"
                                                    class="form-control form-control-sm">

                                                {{-- Fichier existant --}}
                                                @if ($formulaire->$field)
                                                    <div class="mt-2">
                                                        <a href="{{ asset('storage/' . $formulaire->$field) }}" target="_blank"
                                                            class="btn btn-outline-primary btn-sm">
                                                            <i class="bi bi-file-earmark-arrow-down"></i> Voir fichier
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                        @elseif ($field === 'date_naissance')
                                            <div class="col-lg-4 col-md-4">
                                                <label class="form-label fw-semibold">{{ $label }}</label>
                                                <input type="date" name="{{ $field }}"
                                                    class="form-control form-control-sm"
                                                    value="{{ old($field, optional($formulaire->date_naissance)->format('Y-m-d')) }}"
                                                    required>
                                            </div>
                                        @elseif ($field === 'civilite')
                                            <div class="col-lg-4 col-md-4">
                                                <label class="form-label fw-semibold">{{ $label }}</label>
                                                <select name="{{ $field }}" class="form-select form-select-sm" required>
                                                    <option value="">-- Sélectionnez --</option>
                                                    <option value="M."
                                                        {{ old($field, $formulaire->$field) == 'M.' ? 'selected' : '' }}>
                                                        M.</option>
                                                    <option value="Mme"
                                                        {{ old($field, $formulaire->$field) == 'Mme' ? 'selected' : '' }}>
                                                        Mme</option>
                                                </select>
                                            </div>
                                        @elseif ($field === 'statut')
                                            <div class="col-lg-4 col-md-4">
                                                <label class="form-label fw-semibold">{{ $label }}</label>
                                                <select name="{{ $field }}" class="form-select form-select-sm" required>
                                                    <option value="">-- Sélectionnez --</option>
                                                    <option value="Nouvelle"
                                                        {{ old($field, $formulaire->$field) == 'Nouvelle' ? 'selected' : '' }}>
                                                        Nouvelle</option>
                                                    <option value="Conforme"
                                                        {{ old($field, $formulaire->$field) == 'Conforme' ? 'selected' : '' }}>
                                                        Conforme</option>
                                                    <option value="Non conforme"
                                                        {{ old($field, $formulaire->$field) == 'Non conforme' ? 'selected' : '' }}>
                                                        Non conforme</option>
                                                    <option value="Sélectionnée"
                                                        {{ old($field, $formulaire->$field) == 'Sélectionnée' ? 'selected' : '' }}>
                                                        Sélectionnée</option>
                                                    <option value="Rejetée"
                                                        {{ old($field, $formulaire->$field) == 'Rejetée' ? 'selected' : '' }}>
                                                        Rejetée</option>
                                                </select>
                                            </div>
                                        @else
                                            {{-- Champs texte --}}
                                            <div class="col-lg-4 col-md-4">
                                                <label class="form-label fw-semibold">{{ $label }}</label>
                                                <input type="text" name="{{ $field }}"
                                                    class="form-control form-control-sm"
                                                    value="{{ old($field, $formulaire->$field) }}">
                                            </div>
                                        @endif
                                    @endforeach

                                    {{-- Etablissement --}}
                                    <div class="col-lg-4 col-md-4">
                                        <label class="form-label fw-semibold">Nom de l’établissement</label>
                                        <textarea name="nom_etablissement" class="form-control form-control-sm" rows="2">{{ old('nom_etablissement', $formulaire->nom_etablissement) }}</textarea>

                                        {{-- @if ($formulaire->nom_etablissement)
                                            <div class="mt-2">
                                                <div class="alert alert-secondary p-2">
                                                    {!! nl2br(e($formulaire->nom_etablissement)) !!}
                                                </div>
                                            </div>
                                        @endif --}}
                                    </div>

                                    {{-- Sigle --}}
                                    <div class="col-lg-4 col-md-4">
                                        <label class="form-label fw-semibold">Sigle</label>
                                        <input type="text" name="autre_2" class="form-control form-control-sm"
                                            placeholder="Ex : UCAD" value="{{ old('autre_2', $formulaire->autre_2) }}">
                                    </div>

                                    {{-- Certificat (fichier) --}}
                                    <div class="col-lg-4 col-md-4">
                                        <label class="form-label fw-semibold">Certificat d’inscription</label>
                                        <input type="file" name="certificat_file" class="form-control form-control-sm">
                                        @if ($formulaire->certificat_file)
                                            <div class="mt-2">
                                                <a href="{{ asset('storage/' . $formulaire->certificat_file) }}"
                                                    target="_blank" class="btn btn-outline-primary btn-sm">
                                                    <i class="bi bi-file-earmark-arrow-down"></i> Voir certificat
                                                </a>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Montant ONFP --}}
                                    <div class="col-lg-4 col-md-4">
                                        <label class="form-label fw-semibold">Montant pris en charge</label>
                                        <input type="text" name="montant_onfp" class="form-control form-control-sm"
                                            placeholder="Ex : 750000"
                                            value="{{ old('montant_onfp', $formulaire->montant_onfp) }}">
                                    </div>
                                </div>

                                <hr class="dropdown-divider mt-4">
                                <h5 class="text-primary">Informations complémentaires</h5>

                                <div class="row g-3">

                                    {{-- Responsable établissement --}}
                                    <div class="col-lg-4 col-md-4">
                                        <label class="form-label fw-semibold">Type responsable établissement</label>
                                        <input type="text" name="responsable_etablieement"
                                            class="form-control form-control-sm"
                                            placeholder="Ex : Monsieur le Directeur, Madame la Directrice, etc."
                                            value="{{ old('responsable_etablieement', $formulaire->responsable_etablieement) }}">
                                    </div>

                                    {{-- Adresse établissement --}}
                                    <div class="col-lg-4 col-md-4">
                                        <label class="form-label fw-semibold">Adresse établissement</label>
                                        <input type="text" name="adresse_etablessement"
                                            class="form-control form-control-sm" placeholder="Ex:Dakar"
                                            value="{{ old('adresse_etablessement', $formulaire->adresse_etablessement) }}">
                                    </div>

                                    {{-- Téléphone établissement --}}
                                    <div class="col-lg-4 col-md-4">
                                        <label class="form-label fw-semibold">Téléphone établissement</label>
                                        <input type="text" name="telephone_etablissement" placeholder="33 800 00 00"
                                            class="form-control form-control-sm"
                                            value="{{ old('telephone_etablissement', $formulaire->telephone_etablissement) }}">
                                    </div>

                                    {{-- Année scolaire --}}
                                    <div class="col-lg-4 col-md-4">
                                        <label class="form-label fw-semibold">Année scolaire</label>
                                        <input type="text" name="annee_scolaire" class="form-control form-control-sm"
                                            placeholder="2025-2026"
                                            value="{{ old('annee_scolaire', $formulaire?->annee_scolaire ?? date('Y') . '-' . (date('Y') + 1)) }}">
                                    </div>

                                    {{-- Type apprenant --}}
                                    <div class="col-lg-4 col-md-4">
                                        <label class="form-label fw-semibold">Type apprenant</label>
                                        <input type="text" name="autre_1" class="form-control form-control-sm"
                                            placeholder="étudiant(e), apprenant(e)"
                                            value="{{ old('autre_1', $formulaire?->autre_1) }}">
                                    </div>

                                    {{-- Statut certificat (affichage simple) --}}
                                    <div class="col-lg-4 col-md-4">
                                        <label class="form-label fw-semibold">Statut du certificat</label>
                                        <select name="statut_certificat" class="form-select form-select-sm" required>
                                            <option value="">-- Sélectionnez --</option>
                                            <option value="Nouveau"
                                                {{ old('statut_certificat', $formulaire->statut_certificat) == 'Nouveau' ? 'selected' : '' }}>
                                                Nouveau
                                            </option>
                                            <option value="Validé"
                                                {{ old('statut_certificat', $formulaire->statut_certificat) == 'Validé' ? 'selected' : '' }}>
                                                Validé
                                            </option>
                                            <option value="Rejeté"
                                                {{ old('statut_certificat', $formulaire->statut_certificat) == 'Rejeté' ? 'selected' : '' }}>
                                                Rejeté
                                            </option>
                                            <option value="Attente"
                                                {{ old('statut_certificat', $formulaire->statut_certificat) == 'Attente' ? 'selected' : '' }}>
                                                Attente
                                            </option>
                                            <option value="Téléchargé"
                                                {{ old('statut_certificat', $formulaire->statut_certificat) == 'Téléchargé' ? 'selected' : '' }}>
                                                Téléchargé
                                            </option>
                                            <option value=""
                                                {{ old('statut_certificat', $formulaire->statut_certificat) == 'Aucun' ? 'selected' : '' }}>
                                                Aucun
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                {{-- Boutons --}}
                                <div class="mt-4 d-flex justify-content-between">
                                    <a href="{{ route('formulaires.show', $formulaire->id) }}"
                                        class="btn btn-secondary btn-sm">
                                        Retour à la liste
                                    </a>
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="bi bi-save"></i> Enregistrer les modifications
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    @endcan
@endsection
