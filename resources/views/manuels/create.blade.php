@extends('layout.user-layout')
@section('title', 'ONFP | MANUELS')
@section('space-work')

    <div class="pagetitle">
        {{-- <h1>Data Tables</h1> --}}
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/home') }}">Accueil</a></li>
                <li class="breadcrumb-item">Tables</li>
                <li class="breadcrumb-item active">Données</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <section class="section">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                @if ($message = Session::get('status'))
                    <div class="alert alert-success bg-success text-light border-0 alert-dismissible fade show"
                        role="alert">
                        <strong>{{ $message }}</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if ($message = Session::get('danger'))
                    <div class="alert alert-danger bg-danger text-light border-0 alert-dismissible fade show"
                        role="alert">
                        <strong>{{ $message }}</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <div class="card">
                    <div class="card-body">
                        <span class="d-flex mt-2 align-items-baseline"><a href="{{ route('manuels.index') }}"
                                class="btn btn-success btn-sm" title="retour"><i
                                    class="bi bi-arrow-counterclockwise"></i></a>&nbsp;Liste des manuels
                        </span>

                        <div class="pt-0 pb-0">
                            <h5 class="card-title text-center pb-0 fs-4">Enregistrement</h5>
                            <p class="text-center small">Ajouter un nouveau manuel</p>
                        </div>
                        <form action="{{ route('manuels.store') }}" method="POST" enctype="multipart/form-data"
                            class="row g-3">
                            @csrf


                            <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                <label for="auteur" class="form-label">Auteur<span
                                        class="text-danger mx-1">*</span></label>
                                <div class="input-group has-validation">
                                    <input type="text" name="auteur" value="{{ old('auteur') }}"
                                        class="form-control form-control-sm @error('auteur') is-invalid @enderror"
                                        id="auteur" placeholder="Auteur">
                                    @error('auteur')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>
                            </div>


                            <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                <label for="title" class="form-label">Titre du manuel<span
                                        class="text-danger mx-1">*</span></label>
                                <div class="input-group has-validation">
                                    <input type="text" name="title" value="{{ old('title') }}"
                                        class="form-control form-control-sm @error('title') is-invalid @enderror"
                                        id="title" placeholder="Titre du manuel">
                                    @error('title')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                <label for="file" class="form-label">Fichier PDF<span
                                        class="text-danger mx-1">*</span></label>
                                <div class="input-group has-validation">
                                    <input type="file" name="file" value="{{ old('file') }}"
                                        class="form-control form-control-sm @error('file') is-invalid @enderror"
                                        id="file" placeholder="Fichier PDF">
                                    @error('file')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>
                            </div>


                            <div class="col-12 col-md-12 col-lg-12 col-sm-12 col-xs-12 col-xxl-12">
                                <label for="description" class="form-label">Description<span
                                        class="text-danger mx-1">*</span></label>
                                <textarea name="description" id="description" rows="2"
                                    class="form-control form-control-sm @error('description') is-invalid @enderror" placeholder="Description du manuel">{{ old('description') }}</textarea>
                                @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <div>{{ $message }}</div>
                                    </span>
                                @enderror
                            </div>
                    </div>

                    <div class="modal-footer mt-5">
                        <button type="submit" class="btn btn-primary btn-sm">Ajouter</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        </div>
    </section>

@endsection
