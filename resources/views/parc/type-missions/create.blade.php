@extends('layout.user-layout')
@section('title', 'ONFP - Ajouter type mission')

@section('space-work')
<section class="section register">
    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1>Ajouter un type de mission</h1>
            <a href="{{ route('parc-type-missions.index') }}" class="btn btn-outline-secondary btn-sm">
                Retour
            </a>
        </div>

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
                <form action="{{ route('parc-type-missions.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Libellé <span class="text-danger"> *</span></label>
                        <input type="text" name="libelle"
                               class="form-control form-control-sm @error('libelle') is-invalid @enderror"
                               value="{{ old('libelle') }}" placeholder="ONFP">
                        @error('libelle') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <button class="btn btn-success btn-sm">
                        <i class="bi bi-check-circle"></i> Enregistrer
                    </button>
                </form>
            </div>
        </div>

    </div>
</section>
@endsection
