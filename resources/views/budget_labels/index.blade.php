@extends('layout.user-layout')
@section('title', 'ONFP | Libellés budgétaires')
@section('space-work')
    <div class="container">
       
        @can('detf-create')
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="mb-0">
                    Libellés budgétaires
                </h3>
                <a href="{{ route('budget-labels.create') }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-plus-circle"></i> Ajouter
                </a>
            </div>
        @endcan

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Libellé</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($labels as $label)
                    <tr>
                        <td>{{ $label->id }}</td>
                        <td>{{ $label->libelle }}</td>
                        <td>{{ $label->description }}</td>
                        <td>
                            <a href="{{ route('budget-labels.edit', $label) }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('budget-labels.destroy', $label) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('Voulez-vous vraiment supprimer ce libellé ?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $labels->links() }}
    </div>
@endsection
