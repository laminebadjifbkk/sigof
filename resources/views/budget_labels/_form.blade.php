<div class="row g-4">

    {{-- LIBELLE --}}
    <div class="col-md-6">
        <label for="libelle" class="form-label fw-semibold">
            <i class="bi bi-pencil-square"></i> Libellé
            <span class="text-danger">*</span>
        </label>

        <input type="text" name="libelle" id="libelle" placeholder="Libellé"
            class="form-control form-control-sm @error('libelle') is-invalid @enderror"
            value="{{ old('libelle', $budgetLabel->libelle ?? '') }}" required>

        @error('libelle')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    {{-- TYPE --}}
    <div class="col-md-6">
        <label for="type" class="form-label fw-semibold">
            <i class="bi bi-diagram-3"></i> Type de tableau
            <span class="text-danger">*</span>
        </label>

        <select name="type" id="type" class="form-select form-select-sm @error('type') is-invalid @enderror"
            required>

            <option value="">-- Sélectionner un type --</option>

            <option value="fournitures" {{ old('type', $budgetLabel->type ?? '') == 'fournitures' ? 'selected' : '' }}>
                Fournitures et supports pédagogiques
            </option>

            <option value="materiels" {{ old('type', $budgetLabel->type ?? '') == 'materiels' ? 'selected' : '' }}>
                Matériels pédagogiques
            </option>

            <option value="intrants" {{ old('type', $budgetLabel->type ?? '') == 'intrants' ? 'selected' : '' }}>
                Intrants pédagogiques
            </option>

            <option value="budget" {{ old('type', $budgetLabel->type ?? '') == 'budget' ? 'selected' : '' }}>
                Budget previsionnel
            </option>

        </select>

        @error('type')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    {{-- DESCRIPTION --}}
    <div class="col-12">
        <label for="description" class="form-label fw-semibold">
            <i class="bi bi-text-paragraph"></i> Description
        </label>

        <textarea name="description" id="description" rows="3"
            class="form-control form-control-sm @error('description') is-invalid @enderror">{{ old('description', $budgetLabel->description ?? '') }}</textarea>

        @error('description')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

</div>
