@php
    $tag = $tag ?? null;
@endphp

@csrf

@if ($tag)
    @method('PUT')
@endif

<div class="mb-3">
    <label class="form-label fw-semibold">
        Nom <span class="text-danger">*</span>
    </label>

    <input type="text" name="nom" value="{{ old('nom', $tag->nom ?? '') }}"
        class="form-control form-control-sm @error('nom') is-invalid @enderror"
        placeholder="Ex. Prioritaire, Urgent, Formation...">

    @error('nom')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror

    @if ($tag)
        <div class="form-text">
            Slug actuel : <code>{{ $tag->slug }}</code> (régénéré automatiquement si vous changez le nom)
        </div>
    @endif
</div>

<div class="mb-3">
    <label class="form-label fw-semibold">
        Couleur
    </label>

    <input type="color" name="couleur" value="{{ old('couleur', $tag->couleur ?? '#6c757d') }}"
        class="form-control form-control-sm form-control-color @error('couleur') is-invalid @enderror">

    @error('couleur')
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label fw-semibold">
        Description
    </label>

    <textarea name="description" rows="3"
        class="form-control form-control-sm @error('description') is-invalid @enderror">{{ old('description', $tag->description ?? '') }}</textarea>

    @error('description')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="form-check form-switch mb-3">
    <input type="hidden" name="actif" value="0">
    <input type="checkbox" name="actif" value="1" id="actifTag" class="form-check-input"
        @checked(old('actif', $tag->actif ?? true))>
    <label class="form-check-label" for="actifTag">
        Tag actif
    </label>
</div>

<div class="d-flex justify-content-end gap-2 mt-4">
    <a href="{{ route('onfp.activite-tags.index') }}" class="btn btn-sm btn-light border">
        Annuler
    </a>
    <button type="submit" class="btn btn-sm btn-primary">
        {{ $tag ? 'Enregistrer les modifications' : 'Créer le tag' }}
    </button>
</div>
