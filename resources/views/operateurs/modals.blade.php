{{-- ===================== MODALS ===================== --}}
{{-- Add / Extension Operateur --}}
<div class="modal fade" id="AddoperateurModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="{{ route('renewOperateur') }}" enctype="multipart/form-data">
                @csrf
                <div class="card-header bg-white border-bottom text-center py-4">
                    <h4 class="text-primary fw-bold mb-0">
                        <i class="bi bi-arrow-repeat me-2 text-dark"></i> Extension
                    </h4>
                </div>
                <div class="modal-body px-4 pt-4">
                    <div class="row g-4">
                        <div class="col-12">
                            <label for="date_quitus" class="form-label fw-semibold">Date du visa quitus</label>
                            <input type="text" name="date_quitus" id="datepicker" value="{{ old('date_quitus') }}"
                                class="form-control form-control-sm @error('date_quitus') is-invalid @enderror"
                                placeholder="JJ/MM/AAAA" autocomplete="bday">
                            @error('date_quitus')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light mt-4 py-3 px-4">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i> Fermer
                    </button>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="bi bi-check2-circle me-1"></i> Extension
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Operateur --}}
@foreach ($operateurs as $operateur)
    <div class="modal fade" id="EditOperateurModal{{ $operateur->id }}" tabindex="-1"
        aria-labelledby="EditOperateurModalLabel{{ $operateur->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="POST" action="{{ route('operateurs.updated', $operateur->uuid) }}"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <div class="card-header text-center bg-white border-bottom py-3">
                        <h4 class="text-primary fw-bold mb-0">
                            <i class="bi bi-pencil-square me-2 text-dark"></i> Modification
                        </h4>
                    </div>
                    <div class="modal-body px-4 pt-4">
                        <input type="hidden" name="id" value="{{ $operateur->id }}">
                        <input name="type_demande" type="hidden" value="Nouvelle">
                        <div class="row g-4">
                            <div class="col-12">
                                <label for="departement" class="form-label fw-semibold">Département <span
                                        class="text-danger">*</span></label>
                                <select name="departement" id="select-field-departement-update"
                                    class="form-select form-select-sm @error('departement') is-invalid @enderror">
                                    <option value="{{ $operateur->departement?->nom }}">
                                        {{ $operateur->departement?->nom }}</option>
                                    @foreach ($departements as $departement)
                                        <option value="{{ $departement->nom }}">{{ $departement->nom }}</option>
                                    @endforeach
                                </select>
                                @error('departement')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
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

{{-- Certification Operateur --}}
@foreach ($operateurs as $operateur)
    <div class="modal fade" id="certificationModal{{ $operateur->id }}" tabindex="-1"
        aria-labelledby="certificationModalLabel{{ $operateur->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('certifierOperateur', $operateur->uuid) }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header bg-warning text-white">
                        <h5 class="modal-title" id="certificationModalLabel{{ $operateur->id }}">Certification des
                            informations</h5>
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
                                type="checkbox" id="certification_checkbox_{{ $operateur->id }}"
                                name="certification_phrase"
                                value="Je certifie que les informations que j'ai fournies sont correctes.">
                            <label class="form-check-label fst-italic text-muted"
                                for="certification_checkbox_{{ $operateur->id }}">
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
