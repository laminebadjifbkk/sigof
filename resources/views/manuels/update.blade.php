@extends('layout.user-layout')
@section('title', 'ONFP | Modifier le manuel')
@section('space-work')

    <div class="pagetitle">
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/home') }}">Accueil</a></li>
                <li class="breadcrumb-item"><a href="{{ route('manuels.index') }}">Liste des manuels</a></li>
                <li class="breadcrumb-item active">Modifier le manuel</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <span class="d-flex mt-2 align-items-baseline"><a href="{{ route('manuels.index') }}"
                                class="btn btn-success btn-sm" title="retour"><i
                                    class="bi bi-arrow-counterclockwise"></i></a>&nbsp;Liste des manuels
                        </span>
                        <h5 class="card-title text-center">Modifier le manuel</h5>

                        <form action="{{ route('manuels.update', $manuel->id) }}" method="POST"
                            enctype="multipart/form-data" class="row g-3">
                            @csrf
                            @method('PUT')

                            <div class="col-6">
                                <label for="title" class="form-label">Titre du manuel<span
                                        class="text-danger mx-1">*</span></label>
                                <input type="text" name="title" class="form-control form-control-sm"
                                    value="{{ old('title', $manuel->title) }}" placeholder="Titre">
                            </div>

                            <div class="col-6">
                                <label for="auteur" class="form-label">Auteur<span
                                        class="text-danger mx-1">*</span></label>
                                <input type="text" name="auteur" class="form-control form-control-sm"
                                    value="{{ old('auteur', $manuel->author) }}" placeholder="Auteur">
                            </div>

                            <div class="col-12">
                                <label for="description" class="form-label">Description<span
                                        class="text-danger mx-1">*</span></label>
                                <textarea name="description" class="form-control form-control-sm"  placeholder="Description du manuel">{{ old('description', $manuel->description) }}</textarea>
                            </div>

                            <div class="col-12">
                                <label for="file" class="form-label">Fichier PDF (facultatif)</label>
                                <input type="file" name="file" class="form-control form-control-sm">
                                @if ($manuel->filename)
                                    <p>Fichier actuel : <a href="{{ asset('storage/manuels/' . $manuel->filename) }}"
                                            target="_blank">Voir le fichier</a></p>
                                @endif
                            </div>

                            <div class="modal-footer mt-5">
                                <button type="submit" class="btn btn-primary btn-sm">Mettre à jour</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
