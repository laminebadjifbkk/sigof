@extends('layout.user-layout')
@section('title', 'Modification categorie')
@section('space-work')
    <section class="section">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                @if ($message = Session::get('status'))
                    <div class="alert alert-success bg-success text-light border-0 alert-dismissible fade show"
                        role="alert">
                        <strong>{{ $message }}</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <div class="card">
                    <div class="card-body">
                        <div class="col-sm-12 pt-2">
                            <div class="d-flex align-items-center gap-2 mt-3">
                                <a href="{{ route('operateurcategories.index') }}"
                                    class="btn btn-outline-success btn-sm rounded-pill shadow-sm" title="Retour à la liste">
                                    <i class="bi bi-arrow-counterclockwise me-1"></i> Retour
                                </a>
                                <span class="text-muted small">| Catégorie sélectionnée</span>
                            </div>
                        </div>

                        <h5 class="card-title mt-4 fw-semibold text-warning">
                            <i class="bi bi-pencil-square me-1"></i> Modification d'une catégorie
                        </h5>
                        <!-- categorie -->
                        <form method="post" action="{{ route('operateurcategories.update', $categorie->id) }}"
                            enctype="multipart/form-data" class="row g-3">
                            @csrf
                            @method('PUT')
                            <div class="row mb-3">
                                <label for="name" class="form-label">categorie<span
                                        class="text-danger mx-1">*</span></label>
                                <input type="text" name="name" value="{{ $categorie->name ?? old('name') }}"
                                    class="form-control form-control-sm @error('name') is-invalid @enderror" id="name"
                                    placeholder="Nom categorie">
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <div>{{ $message }}</div>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-12 text-left mt-2">
                                <button type="submit" class="btn btn-sm btn-outline-success"><i
                                        class="far fa-save"></i>&nbsp;Modifier</button>
                            </div>
                            {{-- <div class="text-center">
                                <button type="submit" class="btn btn-primary">Modifier</button>
                            </div> --}}
                        </form><!-- End categorie -->
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
