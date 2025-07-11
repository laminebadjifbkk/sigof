@extends('layout.user-layout')
@section('title', 'MODIFICATION | ABE & LETTRE EVALUATION')
@section('space-work')
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
                            {{-- <form action="{{ route('formations.evaluations.update', $formation->id) }}" method="POST">
                                @csrf
                                @foreach ($formation->evaluateurs as $evaluateur)
                                    <h5>{{ $evaluateur->name }} {{ $evaluateur->lastname }}</h5>

                                    <input type="hidden" name="evaluations[{{ $evaluateur->id }}][evaluateur_id]"
                                        value="{{ $evaluateur->id }}">

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Numéro de la lettre</label>
                                            <input type="text" name="evaluations[{{ $evaluateur->id }}][numero_lettre]"
                                                value="{{ old('evaluations.' . $evaluateur->id . '.numero_lettre', $evaluateur->pivot->numero_lettre) }}"
                                                class="form-control">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Date de la lettre</label>
                                            <input type="date" name="evaluations[{{ $evaluateur->id }}][date_lettre]"
                                                value="{{ old('evaluations.' . $evaluateur->id . '.date_lettre', $evaluateur->pivot->date_lettre) }}"
                                                class="form-control">
                                        </div>
                                    </div>
                                @endforeach
                                <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                            </form> --}}

                            <form method="post" action="{{ route('formations.evaluations.update', $formation->id) }}"
                                enctype="multipart/form-data" class="row g-3">
                                @csrf

                                @foreach ($formation->evaluateurs as $evaluateur)
                                    <h5 class="mt-4">{{ $evaluateur->name }} {{ $evaluateur->lastname }}</h5>

                                    <input type="hidden" name="evaluations[{{ $evaluateur->id }}][evaluateur_id]"
                                        value="{{ $evaluateur->id }}">

                                    <div class="row g-3">
                                        <div class="col-6">
                                            <label class="form-label">N° lettre de mission<span
                                                    class="text-danger mx-1">*</span></label>
                                            <input type="text" name="evaluations[{{ $evaluateur->id }}][numero_lettre]"
                                                value="{{ old('evaluations.' . $evaluateur->id . '.numero_lettre', $evaluateur->pivot->numero_lettre) }}"
                                                class="form-control @error('evaluations.' . $evaluateur->id . '.numero_lettre') is-invalid @enderror"
                                                placeholder="Ex: 000876">
                                            @error('evaluations.' . $evaluateur->id . '.numero_lettre')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-6">
                                            <label class="form-label">Date lettre mission<span
                                                    class="text-danger mx-1">*</span></label>
                                            <input type="date" name="evaluations[{{ $evaluateur->id }}][date_lettre]"
                                                value="{{ old('evaluations.' . $evaluateur->id . '.date_lettre', $evaluateur->pivot->date_lettre) }}"
                                                class="form-control @error('evaluations.' . $evaluateur->id . '.date_lettre') is-invalid @enderror">
                                            @error('evaluations.' . $evaluateur->id . '.date_lettre')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                @endforeach

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
@endsection
