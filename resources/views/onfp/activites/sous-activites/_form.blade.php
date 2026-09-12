<div class="row g-3">

    {{-- Référence --}}
    <div class="col-md-4">

        <label for="reference" class="form-label form-label-sm fw-semibold">
            Référence
        </label>

        <input type="text" name="reference" id="reference" value="{{ old('reference', $sousActivite->reference ?? '') }}"
            class="form-control form-control-sm @error('reference') is-invalid @enderror" placeholder="Ex. : SA-01">

        @error('reference')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Titre --}}
    <div class="col-md-8">

        <label for="titre" class="form-label form-label-sm fw-semibold">
            Titre
            <span class="text-danger">*</span>
        </label>

        <input type="text" name="titre" id="titre" value="{{ old('titre', $sousActivite->titre ?? '') }}"
            class="form-control form-control-sm @error('titre') is-invalid @enderror"
            placeholder="Ex. : Préparation et organisation de la réunion" required>

        @error('titre')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Description --}}
    <div class="col-12">

        <label for="description" class="form-label form-label-sm fw-semibold">
            Description
        </label>

        <textarea name="description" id="description" rows="4"
            class="form-control form-control-sm @error('description') is-invalid @enderror"
            placeholder="Décrivez les objectifs, le contenu et les résultats attendus de cette sous-activité...">{{ old('description', $sousActivite->description ?? '') }}</textarea>

        @error('description')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Statut --}}
    <div class="col-md-4">

        <label for="statut" class="form-label form-label-sm fw-semibold">
            Statut
            <span class="text-danger">*</span>
        </label>

        <select name="statut" id="statut" class="form-select form-select-sm" required>

            @foreach ([
        'a_faire' => 'À faire',
        'en_cours' => 'En cours',
        'suspendue' => 'Suspendue',
        'terminee' => 'Terminée',
        'annulee' => 'Annulée',
    ] as $value => $label)
                <option value="{{ $value }}"
                    {{ old('statut', $sousActivite->statut ?? 'a_faire') === $value ? 'selected' : '' }}>

                    {{ $label }}

                </option>
            @endforeach

        </select>

    </div>


    {{-- Priorité --}}
    <div class="col-md-4">

        <label for="priorite" class="form-label form-label-sm fw-semibold">
            Priorité
            <span class="text-danger">*</span>
        </label>

        <select name="priorite" id="priorite" class="form-select form-select-sm" required>

            @foreach ([
        'basse' => 'Basse',
        'normale' => 'Normale',
        'haute' => 'Haute',
        'urgente' => 'Urgente',
    ] as $value => $label)
                <option value="{{ $value }}"
                    {{ old('priorite', $sousActivite->priorite ?? 'normale') === $value ? 'selected' : '' }}>

                    {{ $label }}

                </option>
            @endforeach

        </select>

    </div>


    {{-- Progression --}}
    <div class="col-md-4">

        <label for="progression" class="form-label form-label-sm fw-semibold">
            Progression (%)
            <span class="text-danger">*</span>
        </label>

        <input type="number" name="progression" id="progression" min="0" max="100"
            value="{{ old('progression', $sousActivite->progression ?? 0) }}"
            class="form-control form-control-sm" required>

    </div>


    {{-- Date début --}}
    <div class="col-md-4">

        <label for="date_debut" class="form-label form-label-sm fw-semibold">
            Date de début
        </label>

        <input type="date" name="date_debut" id="date_debut"
            value="{{ old('date_debut', isset($sousActivite->date_debut) ? $sousActivite->date_debut->format('Y-m-d') : '') }}"
            class="form-control form-control-sm">

    </div>


    {{-- Date fin prévue --}}
    <div class="col-md-4">

        <label for="date_fin_prevue" class="form-label form-label-sm fw-semibold">
            Date de fin prévue
        </label>

        <input type="date" name="date_fin_prevue" id="date_fin_prevue"
            value="{{ old(
                'date_fin_prevue',
                isset($sousActivite->date_fin_prevue) ? $sousActivite->date_fin_prevue->format('Y-m-d') : '',
            ) }}"
            class="form-control form-control-sm">

    </div>


    {{-- Date fin réelle --}}
    <div class="col-md-4">

        <label for="date_fin_reelle" class="form-label form-label-sm fw-semibold">
            Date de fin réelle
        </label>

        <input type="date" name="date_fin_reelle" id="date_fin_reelle"
            value="{{ old(
                'date_fin_reelle',
                isset($sousActivite->date_fin_reelle) ? $sousActivite->date_fin_reelle->format('Y-m-d') : '',
            ) }}"
            class="form-control form-control-sm">

    </div>


    {{-- Observation --}}
    <div class="col-12">

        <label for="observation" class="form-label form-label-sm fw-semibold">
            Observation
        </label>

        <textarea name="observation" id="observation" rows="4" class="form-control form-control-sm"
            placeholder="Ajoutez les remarques, contraintes, points d'attention ou informations complémentaires...">{{ old('observation', $sousActivite->observation ?? '') }}</textarea>

    </div>

</div>
