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
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">

                            {{-- Retour + Titre --}}
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
                                <div class="d-flex align-items-center gap-2">
                                    <a href="{{ route('lettrevaluations.show', $formation?->lettrevaluation->id) }}"
                                        class="btn btn-outline-success btn-sm rounded-pill shadow-sm"
                                        title="Retour à la liste">
                                        <i class="bi bi-arrow-counterclockwise"></i>
                                    </a>
                                    <h6 class="mb-0 text-muted fw-semibold text-uppercase">Lettre de mission & ABE</h6>
                                </div>

                                <h5 class="mb-0 fw-semibold text-info">
                                    <i class="bi bi-list-ul me-1"></i> Formation : <span
                                        class="text-dark">{{ $formation->name }}</span>
                                </h5>
                            </div>

                            {{-- Formulaire --}}
                            <form method="post" action="{{ route('formations.evaluations.update', $formation->id) }}"
                                enctype="multipart/form-data" class="row g-3">
                                @csrf

                                @foreach ($formation->evaluateurs as $evaluateur)
                                    <div class="border rounded p-3 mb-3 bg-light-subtle">
                                        <h6 class="fw-bold text-primary mb-3">
                                            <i class="bi bi-person-circle me-1"></i>
                                            {{ $evaluateur->name }} {{ $evaluateur->lastname }}
                                        </h6>

                                        <input type="hidden" name="evaluations[{{ $evaluateur->id }}][evaluateur_id]"
                                            value="{{ $evaluateur->id }}">

                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">N° lettre de mission <span
                                                        class="text-danger">*</span></label>
                                                <input type="text"
                                                    name="evaluations[{{ $evaluateur->id }}][numero_lettre]"
                                                    value="{{ old('evaluations.' . $evaluateur->id . '.numero_lettre', $evaluateur->pivot->numero_lettre) }}"
                                                    class="form-control form-control-sm @error('evaluations.' . $evaluateur->id . '.numero_lettre') is-invalid @enderror"
                                                    placeholder="Ex: 000876">
                                                @error('evaluations.' . $evaluateur->id . '.numero_lettre')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">Date de la lettre <span
                                                        class="text-danger">*</span></label>
                                                <input type="date"
                                                    name="evaluations[{{ $evaluateur->id }}][date_lettre]"
                                                    value="{{ old('evaluations.' . $evaluateur->id . '.date_lettre', $evaluateur->pivot->date_lettre) }}"
                                                    class="form-control form-control-sm @error('evaluations.' . $evaluateur->id . '.date_lettre') is-invalid @enderror">
                                                @error('evaluations.' . $evaluateur->id . '.date_lettre')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                <div class="text-end mt-4 border-top pt-3">
                                    <button type="submit" class="btn btn-success btn-sm px-4">
                                        <i class="bi bi-check-circle me-1"></i> Enregistrer les modifications
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
