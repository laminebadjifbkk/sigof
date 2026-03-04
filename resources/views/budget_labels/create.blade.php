@extends('layout.user-layout')
@section('title', 'ONFP | Ajouter un libellé')
@section('space-work')
    <div class="container">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @can('detf-create')
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="mb-0">
                    Ajouter un libellé budgétaire
                </h3>
                <a href="{{ route('budget-labels.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left-circle"></i> Retour
                </a>
            </div>
        @endcan
        <form action="{{ route('budget-labels.store') }}" method="POST">
            @csrf
            @include('budget_labels._form')
            <br>
            <button type="submit" class="btn btn-sm btn-success">Enregistrer</button>
            <a href="{{ route('budget-labels.index') }}" class="btn btn-sm btn-secondary">Annuler</a>
        </form>
    </div>
@endsection
