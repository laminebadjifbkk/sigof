@extends('layout.user-layout')
@section('title', 'ONFP | Ajouter un libellé')
@section('space-work')
    <div class="container">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <h2>Ajouter un libellé budgétaire</h2>
        <form action="{{ route('budget-labels.store') }}" method="POST">
            @csrf
            @include('budget_labels._form')
            <button type="submit" class="btn btn-success">Enregistrer</button>
            <a href="{{ route('budget-labels.index') }}" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
@endsection
