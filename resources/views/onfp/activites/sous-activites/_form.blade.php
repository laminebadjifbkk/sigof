<div class="row g-3">

    <div class="col-md-4">

        <label class="form-label">
            Référence
        </label>

        <input type="text" name="reference" class="form-control form-control-sm @error('reference') is-invalid @enderror"
            value="{{ old('reference', $sousActivite->reference ?? '') }}" placeholder="Ex. SOUS-ACT-001">

        @error('reference')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    <div class="col-md-8">

        <label class="form-label">
            Titre <span class="text-danger">*</span>
        </label>

        <input type="text" name="titre" class="form-control form-control-sm @error('titre') is-invalid @enderror"
            value="{{ old('titre', $sousActivite->titre ?? '') }}"
            placeholder="Ex. Élaboration du plan de mise en œuvre" required>

        @error('titre')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    <div class="col-12">

        <label class="form-label">
            Description
        </label>

        <textarea name="description" rows="4" class="form-control form-control-sm"
            placeholder="Décrivez brièvement le contenu, les objectifs ou les résultats attendus de cette sous-activité...">{{ old('description', $sousActivite->description ?? '') }}</textarea>

    </div>


    <div class="col-md-3">

        <label class="form-label">
            Statut
        </label>

        <select name="statut" class="form-select form-select">

            @foreach ([
        'a_faire' => 'À faire',
        'en_cours' => 'En cours',
        'suspendue' => 'Suspendue',
        'terminee' => 'Terminée',
        'annulee' => 'Annulée',
    ] as $value => $label)
                <option value="{{ $value }}" @selected(old('statut', $sousActivite->statut ?? 'a_faire') === $value)>
                    {{ $label }}
                </option>
            @endforeach

        </select>

    </div>


    <div class="col-md-3">

        <label class="form-label">
            Priorité
        </label>

        <select name="priorite" class="form-select form-select">

            @foreach ([
        'basse' => 'Basse',
        'normale' => 'Normale',
        'haute' => 'Haute',
        'urgente' => 'Urgente',
    ] as $value => $label)
                <option value="{{ $value }}" @selected(old('priorite', $sousActivite->priorite ?? 'normale') === $value)>
                    {{ $label }}
                </option>
            @endforeach

        </select>

    </div>


    <div class="col-md-2">

        <label class="form-label">
            Progression
        </label>

        <input type="number" name="progression" min="0" max="100" class="form-control form-control-sm"
            value="{{ old('progression', $sousActivite->progression ?? 0) }}" placeholder="0 à 100">

    </div>


    <div class="col-md-2">

        <label class="form-label">
            Début
        </label>

        <input type="date" name="date_debut" class="form-control form-control-sm"
            value="{{ old('date_debut', isset($sousActivite->date_debut) ? $sousActivite->date_debut->format('Y-m-d') : '') }}">

    </div>


    <div class="col-md-2">

        <label class="form-label">
            Fin prévue
        </label>

        <input type="date" name="date_fin_prevue" class="form-control form-control-sm"
            value="{{ old(
                'date_fin_prevue',
                isset($sousActivite->date_fin_prevue) ? $sousActivite->date_fin_prevue->format('Y-m-d') : '',
            ) }}">

    </div>


    <div class="col-12">

        <label class="form-label">
            Observation
        </label>

        <textarea name="observation" rows="3" class="form-control form-control-sm"
            placeholder="Ajoutez une observation, une contrainte ou une information particulière...">{{ old('observation', $sousActivite->observation ?? '') }}</textarea>

    </div>

</div>
