<!-- End Edit Operateur-->
<!-- Edit Operateur Module -->
@foreach ($operateur?->operateurmodules as $operateurmodule)
    <div class="modal fade" id="EditOperateurmoduleModal{{ $operateurmodule->id }}" tabindex="-1" role="dialog"
        aria-labelledby="EditOperateurmoduleModalLabel{{ $operateurmodule->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="POST" action="{{ route('operateurmodules.update', $operateurmodule) }}"
                    enctype="multipart/form-data" class="needs-validation" novalidate>
                    @csrf
                    @method('PATCH')

                    <div class="card shadow-lg border-0">
                        <div class="card-header bg-default text-center py-2 rounded-top">
                            <h4 class="mb-0">Modification</h4>
                        </div>

                        <div class="card-body row g-4 px-4">
                            <input type="hidden" name="id" value="{{ $operateurmodule->id }}">
                            <input type="hidden" name="operateur" value="{{ $operateurmodule->operateur->id }}">

                            {{-- Domaine --}}
                            <div class="col-12">
                                <label for="domaine" class="form-label">Domaine <span
                                        class="text-danger">*</span></label>
                                <select name="domaine" id="select-field-civilite"
                                    class="form-select form-select-sm @error('domaine') is-invalid @enderror" required>
                                    <option value="">-- Sélectionnez un domaine --</option>
                                    @foreach ($domaines as $domaine)
                                        <option value="{{ $domaine->name }}"
                                            {{ old('domaine', $operateurmodule->domaine) == $domaine->name ? 'selected' : '' }}>
                                            {{ $domaine->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('domaine')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Module --}}
                            <div class="col-12">
                                <label for="module" class="form-label">Module <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="module" id="module_operateur_edit"
                                    value="{{ old('module', $operateurmodule->module) }}"
                                    class="form-control form-control-sm @error('module') is-invalid @enderror"
                                    placeholder="Nom du module" required>
                                <div id="moduleListEdit"></div>
                                @error('module')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Niveau de qualification --}}
                            <div class="col-12">
                                <label for="niveau_qualification" class="form-label">Niveau de qualification <span
                                        class="text-danger">*</span></label>
                                <select name="niveau_qualification"
                                    class="form-select form-select-sm @error('niveau_qualification') is-invalid @enderror"
                                    id="select-field-niveau_qualification-update" required>
                                    <option disabled selected>Choisir un niveau</option>
                                    @foreach (['Pré-qualification', 'Renforcement de capacités', 'Qualification'] as $niveau)
                                        <option value="{{ $niveau }}"
                                            {{ old('niveau_qualification', $operateurmodule->niveau_qualification) == $niveau ? 'selected' : '' }}>
                                            {{ $niveau }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('niveau_qualification')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Catégorie --}}
                            <div class="col-12">
                                <label for="categorie" class="form-label">Catégorie professionnelle</label>
                                <input type="text" name="categorie"
                                    value="{{ old('categorie', $operateurmodule->categorie) }}"
                                    class="form-control form-control-sm @error('categorie') is-invalid @enderror"
                                    placeholder="Catégorie">
                                @error('categorie')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        <div class="card-footer d-flex justify-content-end gap-2 p-3 bg-light border-top">
                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                                <i class="bi bi-x-circle"></i> Fermer
                            </button>
                            <button type="submit" class="btn btn-success btn-sm">
                                <i class="bi bi-check-circle"></i> Enregistrer les modifications
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach
<!-- End Edit Operateur Module-->
<!-- The Modal Delete -->
@foreach ($operateur?->operateurmodules as $operateurmodule)
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
                    <form method="post" action="{{ route('operateurmodules.destroy', $operateurmodule->id) }}">
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
    <div class="modal fade" id="addobservations" tabindex="-1" role="dialog" aria-labelledby="addobservationsLabel"
        aria-hidden="true">
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
