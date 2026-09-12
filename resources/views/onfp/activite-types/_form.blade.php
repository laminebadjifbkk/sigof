<div class="row g-4">

    {{-- Code --}}
    <div class="col-md-4">

        <label class="form-label">
            Code <span class="text-danger">*</span>
        </label>

        <input type="text"
               name="code"
               class="form-control form-control-sm @error('code') is-invalid @enderror"
               value="{{ old('code', $activiteType->code ?? '') }}"
               placeholder="EX : REUNION"
               maxlength="50"
               required>

        @error('code')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

        <div class="form-text">
            Code unique du type d’activité.
        </div>

    </div>


    {{-- Libellé --}}
    <div class="col-md-5">

        <label class="form-label">
            Libellé <span class="text-danger">*</span>
        </label>

        <input type="text"
               name="libelle"
               class="form-control form-control-sm @error('libelle') is-invalid @enderror"
               value="{{ old('libelle', $activiteType->libelle ?? '') }}"
               placeholder="Ex : Réunion"
               maxlength="150"
               required>

        @error('libelle')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Ordre --}}
    <div class="col-md-3">

        <label class="form-label">
            Ordre d’affichage
        </label>

        <input type="number"
               name="ordre"
               class="form-control form-control-sm @error('ordre') is-invalid @enderror"
               value="{{ old('ordre', $activiteType->ordre ?? 0) }}"
               min="0">

        @error('ordre')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Description --}}
    <div class="col-12">

        <label class="form-label">
            Description
        </label>

        <textarea name="description"
                  rows="4"
                  class="form-control form-control-sm @error('description') is-invalid @enderror"
                  placeholder="Description du type d’activité...">{{ old('description', $activiteType->description ?? '') }}</textarea>

        @error('description')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Actif --}}
    <div class="col-12">

        <div class="form-check form-switch">

            <input type="hidden"
                   name="actif"
                   value="0">

            <input class="form-check-input"
                   type="checkbox"
                   role="switch"
                   id="actif"
                   name="actif"
                   value="1"
                   {{ old('actif', $activiteType->actif ?? true) ? 'checked' : '' }}>

            <label class="form-check-label"
                   for="actif">

                Type actif

            </label>

        </div>

        <div class="form-text">
            Un type inactif ne sera plus proposé lors de la création d’une activité.
        </div>

    </div>

</div>
