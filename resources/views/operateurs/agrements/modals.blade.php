@foreach ($operateurs as $operateur)
    <div class="modal fade" id="EditOperateurModal{{ $operateur->id }}" tabindex="-1"
        aria-labelledby="EditOperateurModalLabel{{ $operateur->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="POST" action="{{ route('operateurs.updated', $operateur->uuid) }}"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    {{-- En-tête --}}
                    <div class="card-header text-center bg-white border-bottom py-3">
                        <h4 class="text-primary fw-bold mb-0">
                            <i class="bi bi-pencil-square me-2 text-dark"></i> Modification opérateur
                        </h4>
                    </div>

                    {{-- Corps --}}
                    <div class="modal-body px-4 pt-4">
                        <input type="hidden" name="id" value="{{ $operateur->id }}">

                        <div class="row g-4">

                            <input name="type_demande" type="hidden" value="Nouvelle">

                            {{-- Département --}}
                            <div class="col-12">
                                <label for="departement" class="form-label fw-semibold">Département <span
                                        class="text-danger">*</span></label>
                                <select name="departement" id="select-field-departement-update"
                                    class="form-select form-select-sm @error('departement') is-invalid @enderror">
                                    <option value="{{ $operateur->departement?->nom }}">
                                        {{ $operateur->departement?->nom }}</option>
                                    @foreach ($departements as $departement)
                                        <option value="{{ $departement->nom }}">{{ $departement->nom }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('departement')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Date visa quitus --}}
                            <div class="col-12">
                                <label for="date_quitus" class="form-label fw-semibold">Date visa quitus</label>
                                <input type="date" name="date_quitus"
                                    value="{{ old('date_quitus', optional($operateur?->debut_quitus)->format('Y-m-d')) }}"
                                    class="form-control form-control-sm @error('date_quitus') is-invalid @enderror"
                                    placeholder="JJ/MM/AAAA" autocomplete="bday">

                                @error('date_quitus')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Pied de formulaire --}}
                    <div class="modal-footer bg-light py-3 px-4 mt-4">
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i> Fermer
                        </button>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="bi bi-save me-1"></i> Enregistrer les modifications
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach
<!-- Edit Operateur Module -->
@foreach ($operateur->operateurmodules as $operateurmodule)
    <div class="modal fade" id="EditOperateurmoduleModal{{ $operateurmodule->id }}" tabindex="-1" role="dialog"
        aria-labelledby="EditOperateurmoduleModalLabel{{ $operateurmodule->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="post" action="{{ route('operateurmodules.update', $operateurmodule) }}"
                    enctype="multipart/form-data" class="row g-3">
                    @csrf
                    @method('patch')

                    <div class="modal-header" id="EditOperateurmoduleModalLabel{{ $operateurmodule->id }}">
                        <h5 class="modal-title">Modification module opérateur</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" name="id" value="{{ $operateurmodule->id }}">

                        {{-- Module --}}
                        <div class="col-12 mb-3">
                            <label for="module_operateur_edit" class="form-label">Module
                                <span class="text-danger mx-1">*</span>
                            </label>
                            <input type="text" name="module" id="module_operateur_edit"
                                value="{{ old('module', $operateurmodule->module) }}"
                                class="form-control form-control-sm @error('module') is-invalid @enderror"
                                placeholder="module">
                            <div id="moduleListEdit"></div>
                            @error('module')
                                <span class="invalid-feedback" role="alert">
                                    <div>{{ $message }}</div>
                                </span>
                            @enderror
                        </div>

                        {{-- Domaine --}}
                        <div class="col-12 mb-3">
                            <label for="domaine_operateur_edit" class="form-label">Domaine
                                <span class="text-danger mx-1">*</span>
                            </label>
                            <input type="text" name="domaine" id="domaine_operateur_edit"
                                value="{{ old('domaine', $operateurmodule->domaine) }}"
                                class="form-control form-control-sm @error('domaine') is-invalid @enderror"
                                placeholder="domaine">
                            @error('domaine')
                                <span class="invalid-feedback" role="alert">
                                    <div>{{ $message }}</div>
                                </span>
                            @enderror
                        </div>

                        {{-- Catégorie --}}
                        <div class="col-12 mb-3">
                            <label for="categorie_operateur_edit" class="form-label">Catégorie
                                <span class="text-danger mx-1">*</span>
                            </label>
                            <input type="text" name="categorie" id="categorie_operateur_edit"
                                value="{{ old('categorie', $operateurmodule->categorie) }}"
                                class="form-control form-control-sm @error('categorie') is-invalid @enderror"
                                placeholder="catégorie">
                            @error('categorie')
                                <span class="invalid-feedback" role="alert">
                                    <div>{{ $message }}</div>
                                </span>
                            @enderror
                        </div>

                        {{-- Niveau de qualification --}}
                        <div class="col-12 mb-3">
                            <label for="niveau_qualification_operateur_edit" class="form-label">Niveau de
                                qualification
                                <span class="text-danger mx-1">*</span>
                            </label>
                            <select name="niveau_qualification" id="niveau_qualification_operateur_edit"
                                class="form-select form-select-sm selectpicker @error('niveau_qualification') is-invalid @enderror"
                                data-live-search="true" data-placeholder="Choisir niveau qualification">

                                <option value="">Choisir</option>
                                <option value="Pré-qualification"
                                    {{ old('niveau_qualification', $operateurmodule->niveau_qualification) == 'Pré-qualification' ? 'selected' : '' }}>
                                    Pré-qualification
                                </option>
                                <option value="Renforcement de capacités"
                                    {{ old('niveau_qualification', $operateurmodule->niveau_qualification) == 'Renforcement de capacités' ? 'selected' : '' }}>
                                    Renforcement de capacités
                                </option>
                                <option value="Qualification"
                                    {{ old('niveau_qualification', $operateurmodule->niveau_qualification) == 'Qualification' ? 'selected' : '' }}>
                                    Qualification
                                </option>
                            </select>
                            @error('niveau_qualification')
                                <span class="invalid-feedback" role="alert">
                                    <div>{{ $message }}</div>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm"
                            data-bs-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-primary btn-sm">Modifier</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

@foreach ($operateur->operateurmodules as $operateurmodule)
    <div class="modal" id="myModal{{ $operateurmodule->id }}">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Confirmation</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    Êtes-vous sûre de bien vouloir supprimer ?
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <form method="post" action="{{ route('operateurmodules.destroy', $operateurmodule) }}">
                        @csrf
                        @method('DELETE')
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                Non</button>
                            <button class="btn btn-danger">
                                <i class="bi bi-trash"></i> Oui
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach

@foreach ($operateurs as $operateur)
    <div class="modal fade" id="RejetAgrementModal{{ $operateur->id }}" tabindex="-1"
        aria-labelledby="RejetAgrementModalLabel{{ $operateur->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content shadow-lg rounded-3">

                <form method="POST" action="{{ route('validationAgrement', ['id' => $operateur->id]) }}">
                    @csrf
                    @method('PUT')

                    <div class="modal-header bg-light border-bottom-0">
                        <h5 class="modal-title fw-bold text-info" id="RejetAgrementModalLabel{{ $operateur->id }}">
                            Traitement de la demande
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Fermer"></button>
                    </div>

                    <div class="modal-body">
                        {{-- Champ Statut --}}
                        <div class="mb-3">
                            <label for="statut-{{ $operateur->id }}" class="form-label">
                                Statut de la demande
                            </label>
                            @php
                                $selectedStatut = old('statut', $operateur->statut_agrement);
                            @endphp
                            <select name="statut" id="statut-{{ $operateur->id }}"
                                class="form-select form-select-sm @error('statut') is-invalid @enderror" autofocus>
                                <option value="" disabled {{ !$selectedStatut ? 'selected' : '' }}>
                                    Sélectionner
                                </option>
                                <option value="À corriger" {{ $selectedStatut === 'À corriger' ? 'selected' : '' }}>
                                    À corriger
                                </option>
                                <option value="Conforme" {{ $selectedStatut === 'Conforme' ? 'selected' : '' }}>
                                    Conforme
                                </option>
                                <option value="Non conforme"
                                    {{ $selectedStatut === 'Non conforme' ? 'selected' : '' }}>
                                    Non conforme
                                </option>
                                <option value="liste attente"
                                    {{ $selectedStatut === 'liste attente' ? 'selected' : '' }}>En
                                    liste attente</option>
                                <option value="Indisponible"
                                    {{ $selectedStatut === 'Indisponible' ? 'selected' : '' }}>
                                    Indisponible</option>
                                <option value="Disponible" {{ $selectedStatut === 'Disponible' ? 'selected' : '' }}>
                                    Disponible</option>
                                <option value="Abandon" {{ $selectedStatut === 'Abandon' ? 'selected' : '' }}>
                                    Abandon</option>
                                <option value="Injoignable" {{ $selectedStatut === 'Injoignable' ? 'selected' : '' }}>
                                    Injoignable</option>

                            </select>
                            @error('statut')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Champ Commentaires --}}
                        <div class="mb-3">
                            <label for="motif-{{ $operateur->id }}" class="form-label">
                                Commentaires ou remarques
                            </label>
                            @php
                                $lastValidation = collect($operateur->validationoperateurs)
                                    ->sortByDesc('created_at')
                                    ->first();
                            @endphp
                            <textarea name="motif" id="motif-{{ $operateur->id }}" rows="5"
                                class="form-control form-control-sm @error('motif') is-invalid @enderror"
                                placeholder="Indiquez les raisons ou recommandations">{{ old('motif', $lastValidation?->motif) }}</textarea>
                            @error('motif')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="modal-footer border-top-0">
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                            Annuler
                        </button>
                        <button type="submit" class="btn btn-info btn-sm">
                            Soumettre
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endforeach

@foreach ($operateurs as $operateur)
    <div class="modal fade" id="addobservations" tabindex="-1" role="dialog"
        aria-labelledby="addobservationsLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Observations</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" action="{{ route('observations', ['id' => $operateur->id]) }}"
                    enctype="multipart/form-data" class="row g-3">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row g-3">


                            <div class="col-12">
                                <label for="visite_conformite" class="form-label">Visite conformité<span
                                        class="text-danger mx-1">*</span></label>
                                <select name="visite_conformite"
                                    class="form-select form-select-sm @error('visite_conformite') is-invalid @enderror"
                                    aria-label="Select" id="select-field-visite_conformite"
                                    data-placeholder="Choisir">
                                    <option value="{{ $operateur?->visite_conformite ?? old('visite_conformite') }}">
                                        {{ $operateur?->visite_conformite ?? old('visite_conformite') }}
                                    </option>
                                    <option value="Oui">
                                        Oui
                                    </option>
                                    <option value="Non">
                                        Non
                                    </option>
                                </select>
                                @error('visite_conformite')
                                    <span class="invalid-feedback" role="alert">
                                        <div>{{ $message }}</div>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="observation" class="form-label">Observations<span
                                        class="text-danger mx-1">*</span></label>
                                <textarea name="observation" id="observation" rows="10"
                                    class="form-control form-control-sm @error('date_reponse') is-invalid @enderror" placeholder="Observations">{{ $operateur?->observations ?? old('observation') }}</textarea>
                                @error('observation')
                                    <span class="invalid-feedback" role="alert">
                                        <div>{{ $message }}</div>
                                    </span>
                                @enderror
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary btn-sm"
                                    data-bs-dismiss="modal">Fermer</button>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary btn-sm">Enregistrer</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

@foreach ($operateur?->operateurformateurs as $operateurformateur)
    <div class="modal fade" id="ValidationFormateurModal{{ $operateurformateur->id }}" tabindex="-1"
        aria-labelledby="ValidationFormateurModalLabel{{ $operateurformateur->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content shadow-lg rounded-3">

                <form method="POST" action="{{ route('validationFormateur', ['id' => $operateurformateur->id]) }}">
                    @csrf
                    @method('PUT')

                    <div class="modal-header bg-light border-bottom-0">
                        <h5 class="modal-title fw-bold text-info"
                            id="ValidationFormateurModalLabel{{ $operateurformateur->id }}">
                            Validation formateur
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Fermer"></button>
                    </div>

                    <div class="modal-body">
                        {{-- Champ Statut --}}
                        <div class="mb-3">
                            <label for="statut-{{ $operateurformateur->id }}" class="form-label">
                                Statut de la demande
                            </label>
                            @php
                                $selectedStatut = old('statut', $operateurformateur?->statut);
                            @endphp
                            <select name="statut" id="statut-{{ $operateurformateur->id }}"
                                class="form-select form-select-sm @error('statut') is-invalid @enderror" autofocus>
                                <option value="" {{ !$selectedStatut ? 'selected' : '' }}>
                                    Choisir
                                </option>
                                <option value="Oui" {{ $selectedStatut === 'Oui' ? 'selected' : '' }}>
                                    Oui
                                </option>
                                <option value="Non">

                                    Non
                                </option>
                            </select>
                            @error('statut')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Champ Commentaires --}}
                        <div class="mb-3">
                            <label for="motif-{{ $operateurformateur->id }}" class="form-label">
                                Commentaires ou remarques
                            </label>
                            @php
                                $lastValidation = collect($operateurformateur?->validationoperateurformateurs)
                                    ->sortByDesc('created_at')
                                    ->first();
                            @endphp
                            <textarea name="motif" id="motif-{{ $operateurformateur->id }}" rows="5"
                                class="form-control form-control-sm @error('motif') is-invalid @enderror"
                                placeholder="Indiquez les raisons ou recommandations">{{ old('motif', $lastValidation?->motif) }}</textarea>
                            @error('motif')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="modal-footer border-top-0">
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                            Annuler
                        </button>
                        <button type="submit" class="btn btn-info btn-sm">
                            Soumettre
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endforeach

@foreach ($operateur?->operateurequipements as $operateurequipement)
    <div class="modal fade" id="ValidationEquipementModal{{ $operateurequipement->id }}" tabindex="-1"
        aria-labelledby="ValidationEquipementModalLabel{{ $operateurequipement->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content shadow-lg rounded-3">

                <form method="POST"
                    action="{{ route('validationEquipement', ['id' => $operateurequipement->id]) }}">
                    @csrf
                    @method('PUT')

                    <div class="modal-header bg-light border-bottom-0">
                        <h5 class="modal-title fw-bold text-info"
                            id="ValidationEquipementModalLabel{{ $operateurequipement->id }}">
                            Validation équipement
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Fermer"></button>
                    </div>

                    <div class="modal-body">
                        {{-- Champ Statut --}}
                        <div class="mb-3">
                            <label for="statut-{{ $operateurequipement->id }}" class="form-label">
                                Statut de la demande
                            </label>
                            @php
                                $selectedStatut = old('statut', $operateurequipement?->statut);
                            @endphp
                            <select name="statut" id="statut-{{ $operateurequipement->id }}"
                                class="form-select form-select-sm @error('statut') is-invalid @enderror" autofocus>
                                <option value="" {{ !$selectedStatut ? 'selected' : '' }}>
                                    Choisir
                                </option>
                                <option value="Oui" {{ $selectedStatut === 'Oui' ? 'selected' : '' }}>
                                    Oui
                                </option>
                                <option value="Non">

                                    Non
                                </option>

                            </select>
                            @error('statut')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Champ Commentaires --}}
                        <div class="mb-3">
                            <label for="motif-{{ $operateurequipement->id }}" class="form-label">
                                Commentaires ou remarques
                            </label>
                            @php
                                $lastValidation = collect($operateurequipement?->validationoperateurequipements)
                                    ->sortByDesc('created_at')
                                    ->first();
                            @endphp
                            <textarea name="motif" id="motif-{{ $operateurequipement->id }}" rows="5"
                                class="form-control form-control-sm @error('motif') is-invalid @enderror"
                                placeholder="Indiquez les raisons ou recommandations">{{ old('motif', $lastValidation?->motif) }}</textarea>
                            @error('motif')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="modal-footer border-top-0">
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                            Annuler
                        </button>
                        <button type="submit" class="btn btn-info btn-sm">
                            Soumettre
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endforeach
@foreach ($operateurs as $operateur)
    <div class="modal fade" id="certificationModal{{ $operateur->id }}" tabindex="-1"
        aria-labelledby="certificationModalLabel{{ $operateur->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('certifierOperateur', $operateur->uuid) }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header bg-warning text-white">
                        <h5 class="modal-title" id="certificationModalLabel{{ $operateur->id }}">Certification
                            des informations</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-2">Veuillez cocher la case suivante pour certifier :</p>

                        <div class="alert alert-warning py-2 small">
                            Une fois certifiée, vous ne pourrez plus modifier ni supprimer cette demande.
                        </div>

                        <div class="form-check border rounded p-3 bg-light">
                            <input class="form-check-input @error('certification_phrase') is-invalid @enderror"
                                type="checkbox" id="certification_checkbox" name="certification_phrase"
                                value="Je certifie que les informations que j'ai fournies sont correctes.">
                            <label class="form-check-label fst-italic text-muted" for="certification_checkbox">
                                Je certifie que les informations que j'ai fournies sont correctes.
                            </label>
                            @error('certification_phrase')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm"
                            data-bs-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-success btn-sm">Certifier</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endforeach
