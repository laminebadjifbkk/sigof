@extends('layout.user-layout')
@section('title', 'ONFP | SUIVI INDIVIDUEL')
@section('space-work')
    <div class="container">
        <h1>Liste des Suivis Individuels</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <a href="{{ route('individuels.create') }}" class="btn btn-primary mb-3">Ajouter un suivi</a>

        @if ($suivis->isEmpty())
            <p>Aucun suivi individuel trouvé.</p>
        @else
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Individuelle</th>
                        <th>Activité</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($suivis as $suivi)
                        <tr>
                            <td>{{ $suivi->id }}</td>
                            <td>{{ $suivi->individuelle?->user?->firstname . ' ' . $suivi?->individuelle?->user?->name ?? 'Non défini' }}
                            </td>
                            <td>{{ $suivi->activite_principale ?? 'N/A' }}</td>
                            <td>{{ $suivi->created_at->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('individuels.show', $suivi->id) }}" class="btn btn-info btn-sm">Voir</a>
                                <a href="{{ route('individuels.edit', $suivi->id) }}"
                                    class="btn btn-warning btn-sm">Modifier</a>
                                <form action="{{ route('individuels.destroy', $suivi->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm"
                                        onclick="return confirm('Voulez-vous vraiment supprimer ?')">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
