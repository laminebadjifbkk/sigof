@csrf

@if (isset($activite))
    @method('PUT')
@endif

<div class="row g-4">

    {{-- Direction --}}
    <div class="col-md-6">
        <label for="direction_id" class="form-label">
            Direction
        </label>

        <select name="direction_id" id="direction_id" class="form-select @error('direction_id') is-invalid @enderror">
            <option value="">-- Sélectionner une direction --</option>

            @foreach ($directions as $direction)
                <option value="{{ $direction->id }}" @selected(old('direction_id', $activite->direction_id ?? '') == $direction->id)>
                    {{ $direction->sigle ?: $direction->name }}
                    @if ($direction->sigle && $direction->name)
                        - {{ $direction->name }}
                    @endif
                </option>
            @endforeach
        </select>

        @error('direction_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>


    {{-- Type --}}
    <div class="col-md-6">
        <label for="type_id" class="form-label">
            Type d'activité
        </label>

        <select name="type_id" id="type_id" class="form-select @error('type_id') is-invalid @enderror">
            <option value="">-- Sélectionner un type --</option>

            @foreach ($types as $type)
                <option value="{{ $type->id }}" @selected(old('type_id', $activite->type_id ?? '') == $type->id)>
                    {{ $type->libelle }}
                </option>
            @endforeach
        </select>

        @error('type_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>


    {{-- Titre --}}
    <div class="col-12">
        <label for="titre" class="form-label">
            Titre de l'activité <span class="text-danger">*</span>
        </label>

        <input type="text" name="titre" id="titre" value="{{ old('titre', $activite->titre ?? '') }}"
            class="form-control form-control-sm @error('titre') is-invalid @enderror"
            placeholder="Ex. : Organisation de la réunion de coordination du Service Informatique" required>

        @error('titre')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>


    {{-- Description --}}
    <div class="col-12">
        <label for="description" class="form-label">
            Description
        </label>

        <textarea name="description" id="description" rows="4"
            class="form-control form-control-sm @error('description') is-invalid @enderror"
            placeholder="Décrivez le contexte, les objectifs, les principaux éléments et les résultats attendus de cette activité...">{{ old('description', $activite->description ?? '') }}</textarea>

        @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>


    {{-- Statut --}}
    <div class="col-md-4">
        <label for="statut" class="form-label">
            Statut <span class="text-danger">*</span>
        </label>

        <select name="statut" id="statut" class="form-select form-select-sm @error('statut') is-invalid @enderror"
            required>

            @foreach ($statuts as $value => $statutItem)
                <option value="{{ $value }}" @selected(old('statut', $activite->statut ?? 'a_faire') === $value)>
                    {{ $statutItem['label'] }}
                </option>
            @endforeach

        </select>

        @error('statut')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Priorité --}}
    <div class="col-md-4">
        <label for="priorite" class="form-label">
            Priorité <span class="text-danger">*</span>
        </label>

        <select name="priorite" id="priorite"
            class="form-select form-select-sm @error('priorite') is-invalid @enderror" required>

            @foreach ($priorites as $value => $label)
                <option value="{{ $value }}" @selected(old('priorite', $activite->priorite ?? 'normale') === $value)>
                    {{ $label }}
                </option>
            @endforeach

        </select>

        @error('priorite')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>


    {{-- État de santé --}}
    <div class="col-md-4">
        <label for="etat_sante" class="form-label">
            État de santé <span class="text-danger">*</span>
        </label>

        <select name="etat_sante" id="etat_sante"
            class="form-select form-select-sm @error('etat_sante') is-invalid @enderror" required>

            @foreach ($etatsSante as $value => $etat)
                <option value="{{ $value }}" @selected(old('etat_sante', $activite->etat_sante ?? 'normal') === $value)>
                    {{ $etat['label'] }}
                </option>
            @endforeach

        </select>

        @error('etat_sante')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>


    {{-- Progression --}}
    <div class="col-md-4">
        <label for="progression" class="form-label">
            Progression (%)
        </label>

        <input type="number" name="progression" id="progression" min="0" max="100"
            value="{{ old('progression', $activite->progression ?? 0) }}" class="form-control form-control-sm">
    </div>


    {{-- Date enclenchement --}}
    <div class="col-md-4">
        <label for="date_enclenchement" class="form-label">
            Date d'enclenchement
        </label>

        <input type="date" name="date_enclenchement" id="date_enclenchement"
            value="{{ old(
                'date_enclenchement',
                isset($activite->date_enclenchement) ? $activite->date_enclenchement->format('Y-m-d') : '',
            ) }}"
            class="form-control form-control-sm">
    </div>


    {{-- Date exécution prévue --}}
    <div class="col-md-4">
        <label for="date_execution_prevue" class="form-label">
            Exécution prévue
        </label>

        <input type="date" name="date_execution_prevue" id="date_execution_prevue"
            value="{{ old(
                'date_execution_prevue',
                isset($activite->date_execution_prevue) ? $activite->date_execution_prevue->format('Y-m-d') : '',
            ) }}"
            class="form-control form-control-sm">
    </div>


    {{-- Date fin prévue --}}
    <div class="col-md-4">
        <label for="date_fin_prevue" class="form-label">
            Date de fin prévue
        </label>

        <input type="date" name="date_fin_prevue" id="date_fin_prevue"
            value="{{ old('date_fin_prevue', isset($activite->date_fin_prevue) ? $activite->date_fin_prevue->format('Y-m-d') : '') }}"
            class="form-control form-control-sm">
    </div>


    @if (isset($activite))
        {{-- Date exécution réelle --}}
        <div class="col-md-4">
            <label for="date_execution_reelle" class="form-label">
                Date d'exécution réelle
            </label>

            <input type="date" name="date_execution_reelle" id="date_execution_reelle"
                value="{{ old(
                    'date_execution_reelle',
                    isset($activite->date_execution_reelle) ? $activite->date_execution_reelle->format('Y-m-d') : '',
                ) }}"
                class="form-control form-control-sm">
        </div>


        {{-- Date fin réelle --}}
        <div class="col-md-4">
            <label for="date_fin_reelle" class="form-label">
                Date de fin réelle
            </label>

            <input type="date" name="date_fin_reelle" id="date_fin_reelle"
                value="{{ old('date_fin_reelle', isset($activite->date_fin_reelle) ? $activite->date_fin_reelle->format('Y-m-d') : '') }}"
                class="form-control form-control-sm">
        </div>
    @endif



    {{-- Responsable principal --}}
    <div class="col-md-8">
        <label for="responsable_principal" class="form-label">
            Responsable principal
        </label>

        <select name="responsable_principal" id="responsable_principal" class="form-select form-select-sm"
            data-placeholder="Choisir un responsable principal">
            <option value="">-- Aucun --</option>

            @foreach ($employees as $employee)
                <option value="{{ $employee->id }}" @selected(old('responsable_principal', $responsablePrincipal ?? '') == $employee->id)>
                    {{ $employee->matricule }}
                    -
                    {{ trim(($employee->user?->firstname ?? '') . ' ' . ($employee->user?->name ?? '')) }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Responsables --}}
    <div class="col-md-6">
        <label for="responsables" class="form-label">
            Responsables
        </label>

        @php
            $selectedResponsables = old('responsables', $responsableIds ?? []);
        @endphp

        <select name="responsables[]" id="multiple-select-field" class="form-select form-select-sm" multiple
            size="8" data-placeholder="Choisir un ou plusieurs responsables">
            @foreach ($employees as $employee)
                <option value="{{ $employee->id }}" @selected(in_array($employee->id, $selectedResponsables))>
                    {{ $employee->matricule }}
                    -
                    {{ trim(($employee->user?->firstname ?? '') . ' ' . ($employee->user?->name ?? '')) }}
                    @if ($employee->direction)
                        ({{ $employee->direction->sigle }})
                    @endif
                </option>
            @endforeach
        </select>
    </div>


    {{-- Suiveurs --}}
    <div class="col-md-6">
        <label for="suiveurs" class="form-label">
            Suiveurs
        </label>

        @php
            $selectedSuiveurs = old('suiveurs', $suiveurIds ?? []);
        @endphp

        <select name="suiveurs[]" id="suiveurs" class="form-select form-select-sm" multiple size="8"
            data-placeholder="Choisir un ou plusieurs agents de suivi">
            @foreach ($employees as $employee)
                <option value="{{ $employee->id }}" @selected(in_array($employee->id, $selectedSuiveurs))>
                    {{ $employee->matricule }}
                    -
                    {{ trim(($employee->user?->firstname ?? '') . ' ' . ($employee->user?->name ?? '')) }}
                </option>
            @endforeach
        </select>
    </div>


    {{-- ==============================================================
        TIERS INTERVENANTS
        Visible uniquement en modification (isset($activite)) : un tiers
        se rattache à une activité déjà créée, jamais à la volée pendant
        la création — cohérent avec le principe qu'un tiers est une entité
        externe distincte, gérée depuis son propre annuaire (onfp.tiers).
    =============================================================== --}}
    @if (isset($activite))
        <div class="col-12">
            <hr class="my-2">
        </div>

        <div class="col-md-8">
            <label for="tiers_intervenants" class="form-label">
                Tiers intervenants
            </label>

            @php
                $selectedTiers = old('tiers_intervenants', $tierIds ?? []);
            @endphp

            <select name="tiers_intervenants[]" id="tiers_intervenants" class="form-select form-select-sm" multiple
                size="8" data-placeholder="Choisir un ou plusieurs tiers">
                @foreach ($tiers as $tier)
                    <option value="{{ $tier->id }}" @selected(in_array($tier->id, $selectedTiers))>
                        {{ $tier->nom }}
                        @if ($tier->organisation)
                            - {{ $tier->organisation }}
                        @endif
                        @if ($tier->fonction)
                            ({{ $tier->fonction }})
                        @endif
                    </option>
                @endforeach
            </select>

            <div class="form-text">
                Partenaires, prestataires ou autres intervenants externes associés à cette activité.
                Pour préciser le rôle de chacun, utilisez la page
                <a href="{{ route('onfp.activites.tiers.index', $activite) }}">Tiers de l'activité</a>
                après enregistrement.
            </div>
        </div>
    @endif


    {{-- Observation --}}
    <div class="col-12">
        <label for="observation" class="form-label">
            Observation
        </label>

        <textarea name="observation" id="observation" rows="4" class="form-control form-control-sm"
            placeholder="Saisissez les informations complémentaires, remarques, contraintes ou points particuliers concernant cette activité...">{{ old('observation', $activite->observation ?? '') }}</textarea>
    </div>

</div>

<hr class="my-4">

<div class="d-flex justify-content-between">

    <a href="{{ route('onfp.activites.index') }}" class="btn btn-sm btn-outline-secondary">
        Annuler
    </a>

    <button type="submit" class="btn btn-sm btn-primary">
        @if (isset($activite))
            Enregistrer les modifications
        @else
            Créer l'activité
        @endif
    </button>

</div>
