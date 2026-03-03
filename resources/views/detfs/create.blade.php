@extends('layout.user-layout')
@section('title', 'ONFP | CREATION DETF')
@section('space-work')
    <section class="section register">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="mb-0">Créer une nouvelle formation (DETF)</h1>
                <a href="{{ route('detfs.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left-circle"></i> Retour à la liste
                </a>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('detfs.store') }}" method="POST">
                        @csrf

                        <div class="row g-3">

                            <div class="col-md-6">
                                <label for="titre1" class="form-label">Titre 1</label>
                                <input type="text" name="titre1" id="titre1" class="form-control form-control-sm"
                                    value="{{ old('titre1') }}">
                            </div>

                            <div class="col-md-6">
                                <label for="titre2" class="form-label">Titre 2</label>
                                <input type="text" name="titre2" id="titre2" class="form-control form-control-sm"
                                    value="{{ old('titre2') }}">
                            </div>

                            <div class="col-md-6">
                                <label for="date1" class="form-label">Date</label>
                                <input type="date" name="date1" id="date1" class="form-control form-control-sm"
                                    value="{{ old('date1') }}">
                            </div>

                            <div class="col-md-6">
                                <label for="operateurs_id" class="form-label">Opérateur (ID)</label>
                                <input type="number" name="operateurs_id" id="operateurs_id"
                                    class="form-control form-control-sm" value="{{ old('operateurs_id') }}">
                            </div>

                        </div>

                        <div class="d-flex gap-2 mt-3">
                            <button type="submit" class="btn btn-success btn-sm">
                                <i class="bi bi-check-circle"></i> Créer DETF
                            </button>
                            <a href="{{ route('detfs.index') }}" class="btn btn-secondary btn-sm">
                                <i class="bi bi-x-circle"></i> Annuler
                            </a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
