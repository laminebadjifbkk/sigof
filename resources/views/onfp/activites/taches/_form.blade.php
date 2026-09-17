@php
    // Pré-remplissage : priorité à old() (erreurs de validation),
    // sinon valeurs de $tache si on est en édition, sinon valeurs par défaut.
    $isEditing = isset($tache) && $tache->exists;

    $selectedResponsables = old(
        'responsables',
        $isEditing
            ? ($tache->responsables?->pluck('employee_id')->toArray() ?? [])
            : []
    );

    $selectedSuiveurs = old(
        'suiveurs',
        $isEditing
            ? ($tache->suiveurs?->pluck('employee_id')->toArray() ?? [])
            : []
    );
@endphp

<div class="row g-4">

    {{-- Colonne principale --}}
    <div class="col-lg-8">

        {{-- Informations --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">
                    <i class="fas fa-info-circle text-primary me-2"></i>
                    Informations de la tâche
                </h5>
            </div>

            <div class="card-body">

                <div class="mb-3">

                    <label class="form-label fw-semibold">
                        Titre <span class="text-danger">*</span>
                    </label>

                    <input type="text" name="titre"
                        value="{{ old('titre', $tache->titre ?? '') }}"
                        class="form-control form-control-sm @error('titre') is-invalid @enderror"
                        placeholder="Ex. Préparer le rapport de mission" required>

                    @error('titre')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                </div>


                <div class="mb-3">

                    <label class="form-label fw-semibold">
                        Référence
                    </label>

                    <input type="text" name="reference"
                        value="{{ old('reference', $tache->reference ?? '') }}"
                        class="form-control form-control-sm @error('reference') is-invalid @enderror"
                        placeholder="Générée automatiquement si vide">

                    @error('reference')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                </div>


                <div class="mb-3">

                    <label class="form-label fw-semibold">
                        Description
                    </label>

                    <textarea name="description" rows="5"
                        class="form-control form-control-sm @error('description') is-invalid @enderror"
                        placeholder="Décrire précisément le travail à réaliser...">{{ old('description', $tache->description ?? '') }}</textarea>

                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                </div>


                <div class="mb-3">

                    <label class="form-label fw-semibold">
                        Observation
                    </label>

                    <textarea name="observation" rows="3"
                        class="form-control form-control-sm @error('observation') is-invalid @enderror"
                        placeholder="Observations ou informations complémentaires...">{{ old('observation', $tache->observation ?? '') }}</textarea>

                    @error('observation')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                </div>

            </div>

        </div>


        {{-- Dates --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">
                    <i class="far fa-calendar-alt text-primary me-2"></i>
                    Planification
                </h5>
            </div>

            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-4">

                        <label class="form-label fw-semibold">
                            Date de début
                        </label>

                        <input type="date" name="date_debut"
                            value="{{ old('date_debut', optional($tache->date_debut ?? null)->format('Y-m-d')) }}"
                            class="form-control form-control-sm @error('date_debut') is-invalid @enderror">

                        @error('date_debut')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                    </div>


                    <div class="col-md-4">

                        <label class="form-label fw-semibold">
                            Date d'échéance
                        </label>

                        <input type="date" name="date_echeance"
                            value="{{ old('date_echeance', optional($tache->date_echeance ?? null)->format('Y-m-d')) }}"
                            class="form-control form-control-sm @error('date_echeance') is-invalid @enderror">

                        @error('date_echeance')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                    </div>


                    <div class="col-md-4">

                        <label class="form-label fw-semibold">
                            Date de réalisation
                        </label>

                        <input type="date" name="date_realisation"
                            value="{{ old('date_realisation', optional($tache->date_realisation ?? null)->format('Y-m-d')) }}"
                            class="form-control form-control-sm @error('date_realisation') is-invalid @enderror">

                        @error('date_realisation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                    </div>

                </div>

            </div>

        </div>


        {{-- Responsables --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">
                    <i class="fas fa-users text-primary me-2"></i>
                    Responsables et suivi
                </h5>
            </div>

            <div class="card-body">

                <div class="mb-4">

                    <label class="form-label fw-semibold">
                        Responsables
                    </label>

                    <select name="responsables[]" class="form-select" multiple size="6"
                        id="responsable_principal" data-placeholder="Choisir un ou plusieurs responsables">

                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}"
                                @selected(in_array($employee->id, $selectedResponsables))>

                                {{ $employee->user->firstname }}
                                {{ $employee->user->name }}

                                @if($employee->matricule)
                                    - {{ $employee->matricule }}
                                @endif

                            </option>
                        @endforeach

                    </select>

                    <div class="form-text">
                        Maintenez Ctrl pour sélectionner plusieurs agents.
                    </div>

                </div>


                <div>

                    <label class="form-label fw-semibold">
                        Agents de suivi
                    </label>

                    <select name="suiveurs[]" class="form-select" multiple size="6" id="suiveurs"
                        data-placeholder="Choisir un ou plusieurs agents de suivi">

                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}"
                                @selected(in_array($employee->id, $selectedSuiveurs))>

                                {{ $employee->user->firstname }}
                                {{ $employee->user->name }}

                                @if($employee->matricule)
                                    - {{ $employee->matricule }}
                                @endif

                            </option>
                        @endforeach

                    </select>

                    <div class="form-text">
                        Agents chargés du suivi de l'avancement.
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Colonne droite --}}
    <div class="col-lg-4">

        {{-- Rattachement --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">
                    <i class="fas fa-sitemap text-primary me-2"></i>
                    Rattachement
                </h5>
            </div>

            <div class="card-body">

                <div class="mb-3">

                    <label class="form-label text-muted small">
                        Activité
                    </label>

                    <div class="fw-semibold">
                        {{ $activite->reference }}
                    </div>

                    <div class="small text-muted">
                        {{ $activite->titre }}
                    </div>

                </div>


                @if(isset($sousActivite) && $sousActivite)
                    <div>

                        <label class="form-label text-muted small">
                            Sous-activité
                        </label>

                        <div class="fw-semibold">
                            {{ $sousActivite->titre }}
                        </div>

                    </div>
                @else
                    <div class="alert alert-light border small mb-0">
                        Cette tâche est directement rattachée
                        à l'activité.
                    </div>
                @endif

            </div>

        </div>


        {{-- État --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">
                    <i class="fas fa-sliders-h text-primary me-2"></i>
                    État
                </h5>
            </div>

            <div class="card-body">

                <div class="mb-3">

                    <label class="form-label fw-semibold">
                        Statut
                    </label>

                    <select name="statut" class="form-select @error('statut') is-invalid @enderror">

                        @php
                            $statutActuel = old('statut', $tache->statut ?? 'a_faire');
                        @endphp

                        <option value="a_faire" @selected($statutActuel === 'a_faire')>
                            À faire
                        </option>

                        <option value="en_cours" @selected($statutActuel === 'en_cours')>
                            En cours
                        </option>

                        <option value="suspendue" @selected($statutActuel === 'suspendue')>
                            Suspendue
                        </option>

                        <option value="terminee" @selected($statutActuel === 'terminee')>
                            Terminée
                        </option>

                        <option value="annulee" @selected($statutActuel === 'annulee')>
                            Annulée
                        </option>

                    </select>

                    @error('statut')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                </div>


                <div class="mb-3">

                    <label class="form-label fw-semibold">
                        Priorité
                    </label>

                    <select name="priorite" class="form-select @error('priorite') is-invalid @enderror">

                        @php
                            $prioriteActuelle = old('priorite', $tache->priorite ?? 'normale');
                        @endphp

                        <option value="basse" @selected($prioriteActuelle === 'basse')>
                            Basse
                        </option>

                        <option value="normale" @selected($prioriteActuelle === 'normale')>
                            Normale
                        </option>

                        <option value="haute" @selected($prioriteActuelle === 'haute')>
                            Haute
                        </option>

                        <option value="urgente" @selected($prioriteActuelle === 'urgente')>
                            Urgente
                        </option>

                    </select>

                    @error('priorite')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                </div>


                <div>

                    <label class="form-label fw-semibold">
                        Progression
                    </label>

                    <div class="input-group">

                        <input type="number" name="progression"
                            value="{{ old('progression', $tache->progression ?? 0) }}"
                            min="0" max="100"
                            class="form-control form-control-sm @error('progression') is-invalid @enderror">

                        <span class="input-group-text">%</span>

                    </div>

                    @error('progression')
                        <div class="text-danger small mt-1">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

            </div>

        </div>

    </div>

</div>