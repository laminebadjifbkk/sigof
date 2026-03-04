@extends('layout.user-layout')
@section('title', 'ONFP | Modifier un libellé')
@section('space-work')
    <div class="container">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <h2>Modifier un libellé budgétaire</h2>
        <form action="{{ route('budget-labels.update', $budgetLabel) }}" method="POST">
            @csrf
            @method('PUT')
            @include('budget_labels._form')
            <button type="submit" class="btn btn-primary">Mettre à jour</button>
            <a href="{{ route('budget-labels.index') }}" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
@endsection
