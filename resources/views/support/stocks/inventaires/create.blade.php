@extends('layout.user-layout')

@section('space-work')
<div class="container-fluid">
    <div class="mb-4">
        <a href="{{ route('support.stocks.inventaires.index') }}" class="text-muted">
            <i class="fas fa-arrow-left"></i> Retour aux inventaires
        </a>
        <h3 class="mt-2">Créer un inventaire</h3>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('support.stocks.inventaires.store') }}">
                @csrf
                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label class="form-label">Libellé de l'inventaire *</label>
                        <input type="text" name="libelle" class="form-control"
                               value="{{ old('libelle') }}" maxlength="200" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Date de début *</label>
                        <input type="date" name="date_debut" class="form-control"
                               value="{{ old('date_debut', now()->format('Y-m-d')) }}" required>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label">Observations</label>
                        <textarea name="observations" class="form-control" rows="2">{{ old('observations') }}</textarea>
                    </div>
                </div>
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('support.stocks.inventaires.index') }}" class="btn btn-light">Annuler</a>
                    <button type="submit" class="btn btn-primary">Créer l'inventaire</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
