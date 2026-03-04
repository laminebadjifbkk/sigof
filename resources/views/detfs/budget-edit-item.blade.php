@extends('layout.user-layout')
@section('title', "ONFP | Modifier une ligne budgétaire du DETF {$detf->numero}")
@section('space-work')
    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">Modifier une ligne budgétaire pour le DETF : {{ $detf->numero }}</h3>
            <a href="{{ route('detfs.budget.edit', $detf->id) }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left-circle"></i> Retour
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card mb-4">
            <div class="card-header bg-light text-dark">
                <strong>Modifier la ligne budgétaire</strong>
            </div>
            <div class="card-body">
                <form action="{{ route('budget-items.update', [$detf->id, $budgetItem->id]) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3 align-items-end">

                        <div class="col-md-6">
                            <label class="form-label">Libellé<span class="text-danger">
                                    *</span></label>
                            <select name="label_id" class="form-select form-select-sm">
                                <option value="">-- Choisir un libellé --</option>
                                @foreach ($labels as $label)
                                    <option value="{{ $label->id }}"
                                        {{ $budgetItem->budget_label_id == $label->id ? 'selected' : '' }}>
                                        {{ $label->libelle }} ({{ $label->type }})
                                    </option>
                                @endforeach
                            </select>
                            @error('label_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Unité</label>
                            <input type="text" name="unite" class="form-control form-control-sm"
                                value="{{ old('unite', $budgetItem->unite) }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Quantité <span class="text-danger">
                                    *</span></label>
                            <input type="number" name="quantite" class="form-control form-control-sm"
                                value="{{ old('quantite', $budgetItem->quantite) }}" min="0">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Prix Unitaire<span class="text-danger">
                                    *</span></label>
                            <input type="number" name="prix_unitaire" class="form-control form-control-sm"
                                value="{{ old('prix_unitaire', $budgetItem->prix_unitaire) }}" min="0"
                                step="0.01">
                        </div>

                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="bi bi-pencil-square"></i> Mettre à jour
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </div>

    </div>
@endsection
