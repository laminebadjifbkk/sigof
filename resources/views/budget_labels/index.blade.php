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
                    <th>N°</th>
                    <th width="25%">Libellé</th>
                    <th>Description</th>
                    <th width="2">#</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($labels as $label)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $label->libelle }}</td>
                        <td>{{ $label->description }}</td>
                        <td>
                            <div class="d-flex align-items-baseline">
                                <a href="{{ route('budget-labels.edit', $label) }}" class="btn btn-success btn-sm"
                                    title="Modifier">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <div class="filter">
                                    <a class="icon" href="#" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                        <li>
                                            <form action="{{ route('budget-labels.destroy', $label) }}" method="post">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item show_confirm">
                                                    <i class="bi bi-trash"></i> Supprimer
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $labels->links() }}
    </div>
@endsection
