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

                                @php
                                    $labels = [
                                        'cin' => 'Numéro CIN',
                                        'civilite' => 'Civilité',
                                        'prenom' => 'Prénom',
                                        'nom' => 'Nom',
                                        'date_naissance' => 'Date naissance',
                                        'lieu_naissance' => 'Lieu naissance',
                                        'email' => 'Adresse e-mail',
                                        'telephone' => 'Téléphone',
                                        'telephone_secondaire' => 'Téléphone secondaire',
                                        'adresse' => 'Adresse',
                                        'dernier_diplome' => 'Dernier diplôme obtenu',
                                        'nom_etablissement' => 'Établissement',
                                        'region' => 'Région',
                                        'formation' => 'Formation sollicitée',
                                        'diplome_vise' => 'Diplôme visé',
                                        'montant_inscription' => 'Montant inscription',
                                        'montant_mensualite' => 'Montant mensualité',
                                        'montant_unique' => 'Montant unique',
                                        'duree' => 'Durée (en années)',
                                        'handicape' => 'Situation de handicap',
                                        'type_handicap' => 'Type de handicap',
                                        'orphelin' => 'Orphelin',
                                        'type_orphelin' => 'Type d’orphelinat',
                                        'cin_file' => 'Copie CIN',
                                        'facture_file' => 'Facture',
                                        'cv' => 'CV',
                                        'diplome' => 'Diplôme',
                                        'Statut' => 'Statut',
                                    ];

                                    $fileFields = ['cin_file', 'facture_file', 'cv', 'diplome'];
                                @endphp

                                <div class="row g-3">
                                    @foreach ($labels as $field => $label)
                                        @if (in_array($field, $fileFields))
                                            {{-- Champs fichiers --}}
                                            <div class="col-lg-4 col-md-4">
                                                <label class="form-label fw-semibold">{{ $label }}</label>
                                                <input type="file" name="{{ $field }}" class="form-control form-control-sm">

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
                                                <input type="date" name="{{ $field }}" class="form-control form-control-sm"
                                                    value="{{ old($field, $formulaire->$field) }}" required>
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
                                        @elseif ($field === 'Statut')
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
                                                <input type="text" name="{{ $field }}" class="form-control form-control-sm"
                                                    value="{{ old($field, $formulaire->$field) }}" required>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>

                                {{-- Boutons --}}
                                <div class="mt-4 d-flex justify-content-between">
                                    <a href="{{ route('formulaires.show', $formulaire->id) }}"
                                        class="btn btn-secondary btn-sm">
                                        Annuler
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
