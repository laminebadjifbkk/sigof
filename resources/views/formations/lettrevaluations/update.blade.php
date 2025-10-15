@extends('layout.user-layout')
@section('title', 'MODIFICATION | ABE & LETTRE EVALUATION')
@section('space-work')
    @can('lettrevaluation-update')
        <section class="section min-vh-0 d-flex flex-column align-items-center justify-content-center py-0">
            <div class="container">
                <div class="row justify-content-center">
                    @if ($message = Session::get('status'))
                        <div class="alert alert-success bg-success text-light border-0 alert-dismissible fade show"
                            role="alert">
                            <strong>{{ $message }}</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if ($errors->any())
                        @foreach ($errors->all() as $error)
                            <div class="alert alert-danger bg-danger text-light border-0 alert-dismissible fade show"
                                role="alert"><strong>{{ $error }}</strong></div>
                        @endforeach
                    @endif
                    <div class="col-lg-12 col-md-12 d-flex flex-column align-items-center justify-content-center">
                        <div class="card mb-3">

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-12 pt-0">
                                        <span class="d-flex mt-2 align-items-baseline"><a
                                                href="{{ route('lettrevaluations.index') }}" class="btn btn-success btn-sm"
                                                title="retour"><i class="bi bi-arrow-counterclockwise"></i></a>&nbsp;
                                            <p> | LETTRE | ABE</p>
                                        </span>
                                    </div>
                                </div>
                                <form method="post" action="{{ route('lettrevaluations.update', $lettrevaluation->id) }}"
                                    enctype="multipart/form-data" class="row g-3">
                                    @csrf
                                    @method('patch')

                                    <div class="row g-3">

                                        <div class="col-12">
                                            <label for="formation" class="form-label">Formation<span
                                                    class="text-danger mx-1">*</span></label>
                                            <select name="formation" id="formationSelected"
                                                class="form-select form-select-sm @error('formation') is-invalid @enderror"
                                                aria-label="Sélection de la formation" data-placeholder="Choisir une formation">
                                                <option value="" disabled selected>-- Choisir une formation --</option>

                                                @foreach ($formations as $formation)
                                                    <option value="{{ $formation->id }}"
                                                        {{ old('formation', $lettrevaluation?->formation?->id) == $formation->id ? 'selected' : '' }}>
                                                        {{ $formation->name }}
                                                        @if ($formation->numero_convention)
                                                            - {{ $formation->numero_convention }}
                                                        @endif
                                                        @if ($formation?->operateur?->user?->username)
                                                            - {{ $formation?->operateur?->user?->username }}
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('formation')
                                                <span class="invalid-feedback" role="alert">
                                                    <div>{{ $message }}</div>
                                                </span>
                                            @enderror
                                        </div>

                                        {{-- <div class="col-6">
                                            <label for="onfpevaluateur" class="form-label">Evaluateur ONFP<span
                                                    class="text-danger mx-1">*</span></label>
                                            <select name="onfpevaluateur"
                                                class="form-select form-select-sm @error('onfpevaluateur') is-invalid @enderror"
                                                aria-label="Select" id="onfpevaluateurSelected" data-placeholder="Choisir">
                                                <option
                                                    value="{{ $lettrevaluation?->onfpevaluateur?->id ?? old('onfpevaluateur') }}">
                                                    {{ $lettrevaluation?->onfpevaluateur?->name . ' ' . $lettrevaluation?->onfpevaluateur?->lastname ?? old('onfpevaluateur') }}
                                                </option>
                                                @foreach ($onfpevaluateurs as $onfpevaluateur)
                                                    <option value="{{ $onfpevaluateur?->id }}">
                                                        {{ $onfpevaluateur?->name . ' ' . $onfpevaluateur?->lastname }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('onfpevaluateur')
                                                <span class="invalid-feedback" role="alert">
                                                    <div>{{ $message }}</div>
                                                </span>
                                            @enderror
                                        </div> --}}

                                        <div class="col-12">
                                            <label for="onfpevaluateur" class="form-label">Évaluateur ONFP
                                                <span class="text-danger mx-1">*</span>
                                            </label>
                                            {{-- <select name="onfpevaluateur" id="onfpevaluateurSelected"
                                                class="form-select form-select-sm @error('onfpevaluateur') is-invalid @enderror"
                                                aria-label="Select" data-placeholder="Choisir">
                                                <option value="">-- Sélectionner un évaluateur --</option>
                                                @foreach ($onfpevaluateurs as $onfpevaluateur)
                                                    <option value="{{ $onfpevaluateur->id }}"
                                                        @if (old('onfpevaluateur', $lettrevaluation?->onfpevaluateurs_id) == $onfpevaluateur->id) selected @endif>
                                                        {{ $onfpevaluateur?->name . ' ' . $onfpevaluateur?->lastname }}
                                                    </option>
                                                @endforeach
                                            </select> --}}
                                            
                                            <select name="onfpevaluateur[]" id="onfpevaluateurSelected"
                                                class="form-select form-select-sm @error('onfpevaluateur') is-invalid @enderror"
                                                multiple aria-label="Select" data-placeholder="Choisir">
                                                @foreach ($onfpevaluateurs as $onfpevaluateur)
                                                    <option value="{{ $onfpevaluateur->id }}"
                                                        @if (collect(old('onfpevaluateur', $lettrevaluation?->formation?->onfpevaluateurs?->pluck('id')->toArray()))->contains($onfpevaluateur->id)) selected @endif>
                                                        {{ $onfpevaluateur->name . ' ' . $onfpevaluateur->lastname }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('onfpevaluateur')
                                                <span class="invalid-feedback" role="alert">
                                                    <div>{{ $message }}</div>
                                                </span>
                                            @enderror
                                        </div>

                                        {{-- <div class="col-6">
                                            <label for="evaluateur" class="form-label">Evaluateur<span
                                                    class="text-danger mx-1">*</span></label>
                                            <select name="evaluateur"
                                                class="form-select form-select-sm @error('evaluateur') is-invalid @enderror"
                                                aria-label="Select" id="evaluateurSelected" data-placeholder="Choisir">
                                                <option
                                                    value="{{ $lettrevaluation?->formation->evaluateur->id ?? old('evaluateur') }}">
                                                    {{ $lettrevaluation?->evaluateur?->name . ' ' . $lettrevaluation?->evaluateur?->lastname ?? old('evaluateur') }}
                                                </option>
                                                @foreach ($evaluateurs as $evaluateur)
                                                    <option value="{{ $evaluateur?->id }}">
                                                        {{ $evaluateur?->name . ' ' . $evaluateur?->lastname }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('evaluateur')
                                                <span class="invalid-feedback" role="alert">
                                                    <div>{{ $message }}</div>
                                                </span>
                                            @enderror
                                        </div> --}}

                                        <div class="col-12">
                                            <label for="evaluateur" class="form-label">Évaluateur
                                                <span class="text-danger mx-1">*</span>
                                            </label>
                                            {{-- <select name="evaluateurs[]" id="evaluateurSelected"
                                                class="form-select form-select-sm @error('evaluateur') is-invalid @enderror"
                                                multiple aria-label="Select" data-placeholder="Choisir">
                                                <option value="">-- Sélectionner un évaluateur --</option>
                                                @foreach ($evaluateurs as $evaluateur)
                                                    <option value="{{ $evaluateur->id }}"
                                                        @if (old('evaluateur', $lettrevaluation?->evaluateurs_id) == $evaluateur->id) selected @endif>
                                                        {{ $evaluateur?->name . ' ' . $evaluateur?->lastname }}
                                                    </option>
                                                @endforeach
                                            </select> --}}
                                            <select name="evaluateur[]" id="evaluateurSelected"
                                                class="form-select form-select-sm @error('evaluateur') is-invalid @enderror"
                                                multiple aria-label="Select" data-placeholder="Choisir">
                                                @foreach ($evaluateurs as $evaluateur)
                                                    <option value="{{ $evaluateur->id }}"
                                                        @if (collect(old('evaluateur', $lettrevaluation?->formation?->evaluateurs?->pluck('id')->toArray()))->contains($evaluateur->id)) selected @endif>
                                                        {{ $evaluateur->name . ' ' . $evaluateur->lastname }}
                                                    </option>
                                                @endforeach
                                            </select>

                                            @error('evaluateur')
                                                <span class="invalid-feedback" role="alert">
                                                    <div>{{ $message }}</div>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="col-6">
                                            <label for="frais_evaluateur" class="form-label">Montant indemnité de membre</label>
                                            <input type="number" name="frais_evaluateur" min="0" step="0.001"
                                                value="{{ $lettrevaluation?->formation?->frais_evaluateur ?? old('frais_evaluateur') }}"
                                                class="form-control form-control-sm @error('frais_evaluateur') is-invalid @enderror"
                                                id="frais_evaluateur" placeholder="Montant indemnité de membre ">
                                            @error('frais_evaluateur')
                                                <span class="invalid-feedback" role="alert">
                                                    <div>{{ $message }}</div>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="col-6">
                                            <label for="date_pv" class="form-label">Date évaluation</label>
                                            <input type="date" name="date_pv"
                                                value="{{ $lettrevaluation?->formation?->date_pv?->format('Y-m-d') ?? old('date_pv') }}"
                                                class="datepicker form-control form-control-sm @error('date_pv') is-invalid @enderror"
                                                id="date_pv" placeholder="jj/mm/aaaa">
                                            @error('date_pv')
                                                <span class="invalid-feedback" role="alert">
                                                    <div>{{ $message }}</div>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="col-6">
                                            <label for="titre" class="form-label">Niveau qualification</label>
                                            <select name="titre" class="form-select  @error('titre') is-invalid @enderror"
                                                aria-label="Select" id="select-field-titre" data-placeholder="Choisir titre">
                                                <option>
                                                    {{ $lettrevaluation?->formation?->titre ?? ($lettrevaluation?->formation?->referentiel?->titre ?? old('titre')) }}
                                                </option>
                                                <option value="null">
                                                    Aucun
                                                </option>
                                                @foreach ($referentiels as $referentiel)
                                                    <option value="{{ $referentiel?->titre }}">
                                                        {{ $referentiel?->titre }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('titre')
                                                <span class="invalid-feedback" role="alert">
                                                    <div>{{ $message }}</div>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="col-6">
                                            <label for="type_certification" class="form-label">Type certification</label>
                                            <select name="type_certification"
                                                class="form-select  @error('type_certification') is-invalid @enderror"
                                                aria-label="Select" id="select-field-type_certification_update"
                                                data-placeholder="Choisir type certification">
                                                <option value="{{ $lettrevaluation?->formation?->type_certification }}">
                                                    {{ $lettrevaluation?->formation?->type_certification ?? old('type_certification') }}
                                                </option>
                                                <option value="{{ old('c') }}">
                                                    {{ old('type_certification') }}
                                                </option>
                                                <option value="Titre">
                                                    Titre
                                                </option>
                                                <option value="Attestation">
                                                    Attestation
                                                </option>
                                            </select>
                                            @error('type_certification')
                                                <span class="invalid-feedback" role="alert">
                                                    <div>{{ $message }}</div>
                                                </span>
                                            @enderror
                                        </div>

                                        {{-- <div class="col-6">
                                            <label for="lettre_mission_dec" class="form-label">N° lettre de mission</label>
                                            <input type="text" name="lettre_mission_dec"
                                                value="{{ $lettrevaluation?->lettre_mission_dec ?? old('lettre_mission_dec') }}"
                                                class="form-control form-control-sm @error('lettre_mission_dec') is-invalid @enderror"
                                                id="lettre_mission_dec" placeholder="Ex: 000876">
                                            @error('lettre_mission_dec')
                                                <span class="invalid-feedback" role="alert">
                                                    <div>{{ $message }}</div>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="col-6">
                                            <label for="date_lettre_dec" class="form-label">Date lettre mission</label>
                                            <input type="date" name="date_lettre_dec"
                                                value="{{ $lettrevaluation?->date_lettre_dec?->format('Y-m-d') ?? old('date_lettre_dec') }}"
                                                class="datepicker form-control form-control-sm @error('date_lettre_dec') is-invalid @enderror"
                                                id="date_lettre_dec" placeholder="jj/mm/aaaa">
                                            @error('date_lettre_dec')
                                                <span class="invalid-feedback" role="alert">
                                                    <div>{{ $message }}</div>
                                                </span>
                                            @enderror
                                        </div> --}}

                                        <div class="col-12">
                                            <label for="contenu" class="form-label">Commentaires</label>
                                            <textarea name="contenu" id="contenu" rows="3"
                                                class="form-control form-control-sm @error('contenu') is-invalid @enderror" placeholder="Commentaires">{{ old('contenu') }}</textarea>
                                            @error('contenu')
                                                <span class="invalid-feedback" role="alert">
                                                    <div>{{ $message }}</div>
                                                </span>
                                            @enderror
                                        </div>

                                    </div>
                                    <hr>

                                    <div class="row g-3">
                                        <div class="col-12">
                                            <div class="col-12 mb-3">
                                                {{-- Boutons radio pour le statut d'exécution --}}
                                                <div class="col-12 mb-3">
                                                    <label class="card-title">ABE | Exécution de l'opérateur</label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio"
                                                            name="execution_statut" id="execution_success" value="1"
                                                            {{ old('execution_statut', $lettrevaluation?->execution_statut) == '1' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="execution_success">
                                                            L'opérateur a exécuté avec succès
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio"
                                                            name="execution_statut" id="execution_failed" value="0"
                                                            {{ old('execution_statut', $lettrevaluation?->execution_statut) == '0' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="execution_failed">
                                                            L'opérateur n'a pas exécuté avec succès
                                                        </label>
                                                    </div>
                                                    @error('execution_statut')
                                                        <span class="text-danger small">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                            </div>
                                        </div>

                                        <div class="text-center gap-2 p-3 bg-light border-top">
                                            <button type="submit" class="btn btn-success btn-sm">
                                                <i class="bi bi-check-circle"></i> Enregistrer les modifications
                                            </button>
                                        </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>
    @endcan
@endsection
