@extends('layouts.dashboard')

@section('title', 'Modifier la langue')

@section('content')
    <div class="dash-topbar">
        <div>
            <a href="{{ route('langues.index') }}" class="btn btn-sm btn-outline">&larr; Retour</a>
            <h2>Modifier {{ $langue->nom }}</h2>
        </div>
    </div>

    <div class="panel">
        <form action="{{ route('langues.update', $langue) }}" method="POST">
            @csrf
            @method('PUT')
            @include('langues._form')
            @can('langues.update')
                <button type="submit" class="btn btn-primary mt-3">Mettre à jour</button>
            @endcan
        </form>
    </div>
@endsection
