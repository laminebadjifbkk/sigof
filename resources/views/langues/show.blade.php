@extends('layouts.dashboard')

@section('title', $langue->nom)

@section('content')
<div class="dash-topbar">
    <div>
        <a href="{{ route('langues.index') }}" class="btn btn-sm btn-outline">&larr; Retour</a>
        <h2>{{ $langue->nom }}</h2>
    </div>
    <div>
        <a href="{{ route('candidatures.parLangue', $langue) }}" class="btn btn-sm btn-outline">
            Voir les candidats
        </a>
    </div>
    <div class="topbar-right">
        <a href="{{ route('langues.edit', $langue) }}" class="btn btn-outline">Modifier</a>
    </div>
</div>

<div class="panel">
    <table class="table">
        <tr>
            <th>Code</th>
            <td>{{ $langue->code }}</td>
        </tr>
        <tr>
            <th>Postes disponibles</th>
            <td>{{ $langue->postes_disponibles }}</td>
        </tr>
        <tr>
            <th>Niveau langue requis</th>
            <td>{{ $langue->niveau_langue_requis }}</td>
        </tr>
        <tr>
            <th>Niveau français requis</th>
            <td>{{ $langue->niveau_francais_requis }}</td>
        </tr>
        <tr>
            <th>Diplôme minimum</th>
            <td>{{ $langue->diplome_minimum }}</td>
        </tr>
        <tr>
            <th>Certification recommandée</th>
            <td>{{ $langue->certification_recommandee ?? '—' }}</td>
        </tr>
    </table>
</div>
@endsection