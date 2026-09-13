@php
    $tache = $tache ?? null;
    $activite = $activite ?? null;
    $sousActivite = $sousActivite ?? null;

    /*
    |--------------------------------------------------------------------------
    | Valeurs par défaut
    |--------------------------------------------------------------------------
    */
    $progression = old('progression', $tache->progression ?? 0);

    $dateDebut = old(
        'date_debut',
        isset($tache?->date_debut) ? $tache->date_debut->format('Y-m-d') : null
    );

    $dateEcheance = old(
        'date_echeance',
        isset($tache?->date_echeance) ? $tache->date_echeance->format('Y-m-d') : null
    );

    $dateRealisation = old(
        'date_realisation',
        isset($tache?->date_realisation) ? $tache->date_realisation->format('Y-m-d') : null
    );

    /*
    |--------------------------------------------------------------------------
    | Responsables sélectionnés
    |--------------------------------------------------------------------------
    */
    $selectedResponsables = old(
        'responsables',
        $tache && isset($tache->responsables)
            ? $tache->responsables->pluck('employee_id')->toArray()
            : []
    );

    /*
    |--------------------------------------------------------------------------
    | Suiveurs sélectionnés
    |--------------------------------------------------------------------------
    */
    $selectedSuiveurs = old(
        'suiveurs',
        $tache && isset($tache->suiveurs)
            ? $tache->suiveurs->pluck('employee_id')->toArray()
            : []
    );

    /*
    |--------------------------------------------------------------------------
    | Libellé employé
    |--------------------------------------------------------------------------
    */
    $employeeLabel = function ($employee) {
        $name = trim(
            ($employee->prenom ?? '') . ' ' . ($employee->nom ?? '')
        );

        if ($name !== '') {
            return $name;
        }

        if (!empty($employee->name)) {
            return $employee->name;
        }

        if (!empty($employee->matricule)) {
            return $employee->matricule;
        }

        return 'Employé #' . $employee->id;
    };
@endphp


<div class="task-form">

    {{-- ==========================================================
        CONTEXTE
    =========================================================== --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-bottom">
            <h6 class="mb-0 fw-bold">
                <i class="fas fa-link text-primary me-2"></i>
                Rattachement de la tâche
            </h6>
        </div>

        <div class="card-body">

            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label text-muted small">
                        Activité
                    </label>

                    <div class="form-control bg-light">
                        <i class="fas fa-tasks text-primary me-2"></i>

                        {{ $activite->reference ?? '—' }}

                        @if($activite?->titre)
                            — {{ $activite->titre }}
                        @endif
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label text-muted small">
                        Sous-activité
                    </label>

                    <div class="form-control bg-light">

                        @if($sousActivite)
                            <i class="fas fa-layer-group text-info me-2"></i>

                            {{ $sousActivite->reference ?? '—' }}

                            @if($sousActivite->titre)
                                — {{ $sousActivite->titre }}
                            @endif
                        @else
                            <i class="fas fa-folder-open text-secondary me-2"></i>
                            Tâche directement rattachée à l'activité
                        @endif

                    </div>
                </div>

            </div>

        </div>
    </div>


    {{-- ==========================================================
        INFORMATIONS PRINCIPALES
    =========================================================== --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white border-bottom">
            <h6 class="mb-0 fw-bold">
                <i class="fas fa-info-circle text-primary me-2"></i>
                Informations de la tâche
            </h6>
        </div>

        <div class="card-body">

            <div class="row g-3">

                {{-- Référence --}}
                <div class="col-md-4">

                    <label for="reference" class="form-label fw-semibold">
                        Référence
                    </label>

                    <input
                        type="text"
                        name="reference"
                        id="reference"
                        class="form-control @error('reference') is-invalid @enderror"
                        value="{{ old('reference', $tache->reference ?? '') }}"
                        placeholder="Ex. T-001"
                    >

                    @error('reference')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                    <small class="text-muted">
                        Laisser vide pour générer automatiquement une référence.
                    </small>

                </div>


                {{-- Titre --}}
                <div class="col-md-8">

                    <label for="titre" class="form-label fw-semibold">
                        Titre de la tâche <span class="text-danger">*</span>
                    </label>

                    <input
                        type="text"
                        name="titre"
                        id="titre"
                        class="form-control @error('titre') is-invalid @enderror"
                        value="{{ old('titre', $tache->titre ?? '') }}"
                        placeholder="Ex. Préparer le rapport de mission"
                        required
                    >

                    @error('titre')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Description --}}
                <div class="col-12">

                    <label for="description" class="form-label fw-semibold">
                        Description
                    </label>

                    <textarea
                        name="description"
                        id="description"
                        rows="5"
                        class="form-control @error('description') is-invalid @enderror"
                        placeholder="Décrire précisément le travail à réaliser..."
                    >{{ old('description', $tache->description ?? '') }}</textarea>

                    @error('description')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

            </div>

        </div>
    </div>


    {{-- ==========================================================
        ETAT / PRIORITE / PROGRESSION
    =========================================================== --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white border-bottom">
            <h6 class="mb-0 fw-bold">
                <i class="fas fa-chart-line text-success me-2"></i>
                État d'avancement
            </h6>
        </div>

        <div class="card-body">

            <div class="row g-4">

                {{-- Statut --}}
                <div class="col-md-4">

                    <label for="statut" class="form-label fw-semibold">
                        Statut <span class="text-danger">*</span>
                    </label>

                    <select
                        name="statut"
                        id="statut"
                        class="form-select @error('statut') is-invalid @enderror"
                        required
                    >

                        @php
                            $statutValue = old('statut', $tache->statut ?? 'a_faire');
                        @endphp

                        <option value="a_faire" {{ $statutValue === 'a_faire' ? 'selected' : '' }}>
                            À faire
                        </option>

                        <option value="en_cours" {{ $statutValue === 'en_cours' ? 'selected' : '' }}>
                            En cours
                        </option>

                        <option value="suspendue" {{ $statutValue === 'suspendue' ? 'selected' : '' }}>
                            Suspendue
                        </option>

                        <option value="terminee" {{ $statutValue === 'terminee' ? 'selected' : '' }}>
                            Terminée
                        </option>

                        <option value="annulee" {{ $statutValue === 'annulee' ? 'selected' : '' }}>
                            Annulée
                        </option>

                    </select>

                    @error('statut')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Priorité --}}
                <div class="col-md-4">

                    <label for="priorite" class="form-label fw-semibold">
                        Priorité <span class="text-danger">*</span>
                    </label>

                    @php
                        $prioriteValue = old('priorite', $tache->priorite ?? 'normale');
                    @endphp

                    <select
                        name="priorite"
                        id="priorite"
                        class="form-select @error('priorite') is-invalid @enderror"
                        required
                    >

                        <option value="basse" {{ $prioriteValue === 'basse' ? 'selected' : '' }}>
                            Basse
                        </option>

                        <option value="normale" {{ $prioriteValue === 'normale' ? 'selected' : '' }}>
                            Normale
                        </option>

                        <option value="haute" {{ $prioriteValue === 'haute' ? 'selected' : '' }}>
                            Haute
                        </option>

                        <option value="urgente" {{ $prioriteValue === 'urgente' ? 'selected' : '' }}>
                            Urgente
                        </option>

                    </select>

                    @error('priorite')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Progression --}}
                <div class="col-md-4">

                    <label for="progression" class="form-label fw-semibold">
                        Progression
                    </label>

                    <div class="input-group">

                        <input
                            type="number"
                            name="progression"
                            id="progression"
                            min="0"
                            max="100"
                            step="1"
                            value="{{ $progression }}"
                            class="form-control @error('progression') is-invalid @enderror"
                        >

                        <span class="input-group-text">
                            %
                        </span>

                    </div>

                    @error('progression')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

            </div>


            {{-- Barre de progression --}}
            <div class="mt-4">

                <input
                    type="range"
                    id="progressionRange"
                    class="form-range"
                    min="0"
                    max="100"
                    value="{{ $progression }}"
                >

                <div class="progress" style="height: 8px;">

                    <div
                        id="progressionBar"
                        class="progress-bar"
                        role="progressbar"
                        style="width: {{ $progression }}%;"
                    ></div>

                </div>

            </div>

        </div>
    </div>


    {{-- ==========================================================
        PLANIFICATION
    =========================================================== --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white border-bottom">
            <h6 class="mb-0 fw-bold">
                <i class="fas fa-calendar-alt text-warning me-2"></i>
                Planification et réalisation
            </h6>
        </div>

        <div class="card-body">

            <div class="row g-3">

                <div class="col-md-4">

                    <label for="date_debut" class="form-label fw-semibold">
                        Date de début
                    </label>

                    <input
                        type="date"
                        name="date_debut"
                        id="date_debut"
                        class="form-control @error('date_debut') is-invalid @enderror"
                        value="{{ $dateDebut }}"
                    >

                    @error('date_debut')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                <div class="col-md-4">

                    <label for="date_echeance" class="form-label fw-semibold">
                        Date d'échéance
                    </label>

                    <input
                        type="date"
                        name="date_echeance"
                        id="date_echeance"
                        class="form-control @error('date_echeance') is-invalid @enderror"
                        value="{{ $dateEcheance }}"
                    >

                    @error('date_echeance')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                <div class="col-md-4">

                    <label for="date_realisation" class="form-label fw-semibold">
                        Date de réalisation
                    </label>

                    <input
                        type="date"
                        name="date_realisation"
                        id="date_realisation"
                        class="form-control @error('date_realisation') is-invalid @enderror"
                        value="{{ $dateRealisation }}"
                    >

                    @error('date_realisation')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

            </div>

        </div>
    </div>


    {{-- ==========================================================
        RESPONSABLES / SUIVEURS
    =========================================================== --}}
    <div class="row g-4 mb-4">

        {{-- Responsables --}}
        <div class="col-lg-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-user-tie text-primary me-2"></i>
                        Responsables
                    </h6>
                </div>

                <div class="card-body">

                    <label for="responsables" class="form-label">
                        Responsables de la tâche
                    </label>

                    <select
                        name="responsables[]"
                        id="responsables"
                        class="form-select @error('responsables') is-invalid @enderror"
                        multiple
                        size="7"
                    >

                        @forelse(($employees ?? collect()) as $employee)

                            @php
                                $label = $employeeLabel($employee);
                            @endphp

                            <option
                                value="{{ $employee->id }}"
                                {{ in_array($employee->id, $selectedResponsables) ? 'selected' : '' }}
                            >
                                {{ $label }}
                                @if(!empty($employee->matricule))
                                    — {{ $employee->matricule }}
                                @endif
                            </option>

                        @empty

                            <option disabled>
                                Aucun employé disponible
                            </option>

                        @endforelse

                    </select>

                    @error('responsables')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                    @enderror

                    <small class="text-muted">
                        Maintenir <strong>Ctrl</strong> ou <strong>Cmd</strong> pour sélectionner plusieurs personnes.
                    </small>

                </div>

            </div>

        </div>


        {{-- Suiveurs --}}
        <div class="col-lg-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-eye text-info me-2"></i>
                        Suiveurs
                    </h6>
                </div>

                <div class="card-body">

                    <label for="suiveurs" class="form-label">
                        Agents chargés du suivi
                    </label>

                    <select
                        name="suiveurs[]"
                        id="suiveurs"
                        class="form-select @error('suiveurs') is-invalid @enderror"
                        multiple
                        size="7"
                    >

                        @forelse(($employees ?? collect()) as $employee)

                            @php
                                $label = $employeeLabel($employee);
                            @endphp

                            <option
                                value="{{ $employee->id }}"
                                {{ in_array($employee->id, $selectedSuiveurs) ? 'selected' : '' }}
                            >
                                {{ $label }}
                                @if(!empty($employee->matricule))
                                    — {{ $employee->matricule }}
                                @endif
                            </option>

                        @empty

                            <option disabled>
                                Aucun employé disponible
                            </option>

                        @endforelse

                    </select>

                    @error('suiveurs')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                    @enderror

                    <small class="text-muted">
                        Les suiveurs reçoivent les informations de suivi de la tâche.
                    </small>

                </div>

            </div>

        </div>

    </div>


    {{-- ==========================================================
        OBSERVATION
    =========================================================== --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white border-bottom">
            <h6 class="mb-0 fw-bold">
                <i class="fas fa-comment-alt text-secondary me-2"></i>
                Observation
            </h6>
        </div>

        <div class="card-body">

            <textarea
                name="observation"
                id="observation"
                rows="4"
                class="form-control @error('observation') is-invalid @enderror"
                placeholder="Ajouter une observation ou une information complémentaire..."
            >{{ old('observation', $tache->observation ?? '') }}</textarea>

            @error('observation')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

        </div>
    </div>

</div>


@push('styles')
<style>
    .task-form .card {
        border-radius: 14px;
    }

    .task-form .card-header {
        padding: 1rem 1.25rem;
    }

    .task-form .form-control,
    .task-form .form-select {
        border-radius: 9px;
    }

    .task-form .form-control:focus,
    .task-form .form-select:focus {
        box-shadow: 0 0 0 .2rem rgba(13, 110, 253, .08);
    }

    .task-form select[multiple] {
        min-height: 170px;
    }

    .task-form .progress {
        border-radius: 20px;
    }

    .task-form .progress-bar {
        transition: width .2s ease;
    }
</style>
@endpush


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const numberInput = document.getElementById('progression');
    const rangeInput = document.getElementById('progressionRange');
    const progressBar = document.getElementById('progressionBar');

    if (numberInput && rangeInput && progressBar) {

        function updateProgression(value) {

            value = parseInt(value ?? 0);

            if (isNaN(value)) {
                value = 0;
            }

            value = Math.max(0, Math.min(100, value));

            numberInput.value = value;
            rangeInput.value = value;
            progressBar.style.width = value + '%';
        }

        rangeInput.addEventListener('input', function () {
            updateProgression(this.value);
        });

        numberInput.addEventListener('input', function () {
            updateProgression(this.value);
        });
    }

});
</script>
@endpush
