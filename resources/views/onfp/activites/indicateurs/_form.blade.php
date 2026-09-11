<div class="row g-4">

    {{-- Code --}}
    <div class="col-md-4">

        <label for="code" class="form-label">
            Code
        </label>

        <input type="text"
               name="code"
               id="code"
               value="{{ old('code', $indicateur->code ?? '') }}"
               class="form-control @error('code') is-invalid @enderror"
               placeholder="Ex. : NB_FORMATEURS"
               maxlength="50">

        @error('code')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

        <div class="form-text">
            Code interne permettant d'identifier l'indicateur.
        </div>

    </div>


    {{-- Libellé --}}
    <div class="col-md-8">

        <label for="libelle" class="form-label">
            Libellé de l'indicateur <span class="text-danger">*</span>
        </label>

        <input type="text"
               name="libelle"
               id="libelle"
               value="{{ old('libelle', $indicateur->libelle ?? '') }}"
               class="form-control @error('libelle') is-invalid @enderror"
               placeholder="Ex. : Nombre de bénéficiaires formés"
               required>

        @error('libelle')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Description --}}
    <div class="col-12">

        <label for="description" class="form-label">
            Description
        </label>

        <textarea
            name="description"
            id="description"
            rows="3"
            class="form-control @error('description') is-invalid @enderror"
            placeholder="Décrivez précisément ce que mesure cet indicateur..."
        >{{ old('description', $indicateur->description ?? '') }}</textarea>

        @error('description')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Cible --}}
    <div class="col-md-4">

        <label for="valeur_cible" class="form-label">
            Valeur cible <span class="text-danger">*</span>
        </label>

        <input type="number"
               name="valeur_cible"
               id="valeur_cible"
               value="{{ old('valeur_cible', $indicateur->valeur_cible ?? '') }}"
               class="form-control @error('valeur_cible') is-invalid @enderror"
               min="0"
               step="0.01"
               placeholder="Ex. : 100"
               required>

        @error('valeur_cible')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Réalisé --}}
    <div class="col-md-4">

        <label for="valeur_realisee" class="form-label">
            Valeur réalisée
        </label>

        <input type="number"
               name="valeur_realisee"
               id="valeur_realisee"
               value="{{ old('valeur_realisee', $indicateur->valeur_realisee ?? 0) }}"
               class="form-control @error('valeur_realisee') is-invalid @enderror"
               min="0"
               step="0.01"
               placeholder="Ex. : 75">

        @error('valeur_realisee')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Unité --}}
    <div class="col-md-4">

        <label for="unite" class="form-label">
            Unité
        </label>

        <input type="text"
               name="unite"
               id="unite"
               value="{{ old('unite', $indicateur->unite ?? '') }}"
               class="form-control @error('unite') is-invalid @enderror"
               placeholder="Ex. : personnes, %, dossiers, FCFA">

        @error('unite')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Sens --}}
    <div class="col-md-6">

        <label for="sens" class="form-label">
            Sens de l'indicateur <span class="text-danger">*</span>
        </label>

        <select name="sens"
                id="sens"
                class="form-select @error('sens') is-invalid @enderror"
                required>

            <option value="">
                Sélectionner...
            </option>

            <option value="croissant"
                {{ old('sens', $indicateur->sens ?? 'croissant') === 'croissant' ? 'selected' : '' }}>
                Croissant — plus la valeur augmente, mieux c'est
            </option>

            <option value="decroissant"
                {{ old('sens', $indicateur->sens ?? '') === 'decroissant' ? 'selected' : '' }}>
                Décroissant — moins la valeur est élevée, mieux c'est
            </option>

        </select>

        @error('sens')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Date de référence --}}
    <div class="col-md-6">

        <label for="date_reference" class="form-label">
            Date de référence
        </label>

        <input type="date"
               name="date_reference"
               id="date_reference"
               value="{{ old('date_reference', isset($indicateur->date_reference) ? $indicateur->date_reference->format('Y-m-d') : '') }}"
               class="form-control @error('date_reference') is-invalid @enderror">

        @error('date_reference')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

        <div class="form-text">
            Date à laquelle la valeur réalisée est constatée.
        </div>

    </div>


    {{-- Observation --}}
    <div class="col-12">

        <label for="observation" class="form-label">
            Observation
        </label>

        <textarea
            name="observation"
            id="observation"
            rows="3"
            class="form-control @error('observation') is-invalid @enderror"
            placeholder="Expliquez éventuellement l'écart entre la cible et la valeur réalisée..."
        >{{ old('observation', $indicateur->observation ?? '') }}</textarea>

        @error('observation')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>

</div>
