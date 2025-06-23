@extends('layout.user-layout')
@section('title', 'MODIFICATION | ABE & LETTRE EVALUATION')
@section('space-work')
    @can('employe-update')
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

                                        <div class="col-12 col-md-12 col-lg-12 col-sm-12 col-xs-12 col-xxl-12">
                                            <label for="formation" class="form-label">Formation<span
                                                    class="text-danger mx-1">*</span></label>
                                            <select name="formation" id="formationSelected"
                                                class="form-select form-select-sm @error('formation') is-invalid @enderror"
                                                aria-label="Sélection de la formation" data-placeholder="Choisir">

                                                <option value="{{ $lettrevaluation?->formation?->id ?? old('formation') }}"
                                                    {{ $lettrevaluation?->formation?->numero_convention ?? old('formation') }}>
                                                </option>

                                                @foreach ($formations as $formation)
                                                    <option value="{{ $formation->id }}"
                                                        {{ old('formation', $lettrevaluation?->formation->id) == $formation->id ? 'selected' : '' }}>
                                                        {{ $formation->numero_convention }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('formation')
                                                <span class="invalid-feedback" role="alert">
                                                    <div>{{ $message }}</div>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="col-12 col-md-12 col-lg-12 col-sm-12 col-xs-12 col-xxl-12">
                                            <label for="onfpevaluateur" class="form-label">Evaluateur ONFP<span
                                                    class="text-danger mx-1">*</span></label>
                                            <select name="onfpevaluateur"
                                                class="form-select form-select-sm @error('onfpevaluateur') is-invalid @enderror"
                                                aria-label="Select" id="onfpevaluateurSelected" data-placeholder="Choisir">
                                                <option
                                                    value="{{ $lettrevaluation?->formation->onfpevaluateur?->id ?? old('onfpevaluateur') }}">
                                                    {{ $lettrevaluation?->formation->onfpevaluateur->name ?? old('onfpevaluateur') }}
                                                </option>
                                                @foreach ($onfpevaluateurs as $onfpevaluateur)
                                                    <option value="{{ $onfpevaluateur->id }}">
                                                        {{ $onfpevaluateur?->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('onfpevaluateur')
                                                <span class="invalid-feedback" role="alert">
                                                    <div>{{ $message }}</div>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="col-12 col-md-12 col-lg-12 col-sm-12 col-xs-12 col-xxl-12">
                                            <label for="evaluateur" class="form-label">Evaluateur<span
                                                    class="text-danger mx-1">*</span></label>
                                            <select name="evaluateur"
                                                class="form-select form-select-sm @error('evaluateur') is-invalid @enderror"
                                                aria-label="Select" id="evaluateurSelected" data-placeholder="Choisir">
                                                <option
                                                    value="{{ $lettrevaluation?->formation->evaluateur->id ?? old('evaluateur') }}">
                                                    {{ $lettrevaluation?->formation->evaluateur->name ?? old('evaluateur') }}
                                                </option>
                                                @foreach ($lettres as $lettre)
                                                    <option value="{{ $lettre?->evaluateur?->id }}">
                                                        {{ $lettre?->evaluateur?->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('evaluateur')
                                                <span class="invalid-feedback" role="alert">
                                                    <div>{{ $message }}</div>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="col-12 col-md-12 col-lg-12 col-sm-12 col-xs-12 col-xxl-12">
                                            <label for="contenu" class="form-label">Commentaires</label>
                                            <textarea name="contenu" id="contenu" rows="5"
                                                class="form-control form-control-sm @error('contenu') is-invalid @enderror" placeholder="Commentaires">{{ old('contenu') }}</textarea>
                                            @error('contenu')
                                                <span class="invalid-feedback" role="alert">
                                                    <div>{{ $message }}</div>
                                                </span>
                                            @enderror
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
