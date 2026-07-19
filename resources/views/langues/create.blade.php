@extends('layouts.dashboard')

@section('title', 'Ajouter une langue')

@section('content')
<div class="dash-topbar">
    <div>
        <a href="{{ route('langues.index') }}" class="btn btn-sm btn-outline">&larr; Retour</a>
        <h2>Ajouter une langue</h2>
    </div>
</div>

<div class="panel">
    <form action="{{ route('langues.store') }}" method="POST">
        @csrf
        @include('langues._form')
        <button type="submit" class="btn btn-primary mt-3">Enregistrer</button>
    </form>
</div>
@endsection