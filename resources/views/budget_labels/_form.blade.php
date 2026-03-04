<div class="mb-3">
    <label for="libelle" class="form-label">Libellé <span class="text-danger">*</span></label>
    <input type="text" name="libelle" id="libelle"
           class="form-control @error('libelle') is-invalid @enderror"
           value="{{ old('libelle', $budgetLabel->libelle ?? '') }}" required>
    @error('libelle') <span class="text-danger">{{ $message }}</span> @enderror
</div>

<div class="mb-3">
    <label for="description" class="form-label">Description</label>
    <textarea name="description" id="description"
              class="form-control @error('description') is-invalid @enderror">{{ old('description', $budgetLabel->description ?? '') }}</textarea>
    @error('description') <span class="text-danger">{{ $message }}</span> @enderror
</div>