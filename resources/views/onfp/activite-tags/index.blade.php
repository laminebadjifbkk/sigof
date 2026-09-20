@extends('layout.user-layout')

@section('title', 'Tags')

@section('space-work')
    <div class="container-fluid py-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-1"><i class="bi bi-tags me-2"></i>Tags</h4>
                <div class="text-muted">Catégorisation transverse des activités</div>
            </div>
            <a href="{{ route('onfp.activite-tags.create') }}" class="btn btn-sm btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Nouveau tag
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-6">
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="form-control" placeholder="Rechercher un tag...">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-outline-primary w-100">Filtrer</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">

                @if ($tags->count())
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Tag</th>
                                    <th>Activités</th>
                                    <th>État</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tags as $tag)
                                    <tr>
                                        <td class="ps-4">
                                            <span class="badge" style="background-color: {{ $tag->couleur ?: '#6c757d' }};">
                                                {{ $tag->nom }}
                                            </span>
                                            @if ($tag->description)
                                                <div class="small text-muted mt-1">{{ $tag->description }}</div>
                                            @endif
                                        </td>
                                        <td>{{ $tag->activites_count }}</td>
                                        <td>
                                            @if ($tag->actif)
                                                <span class="badge bg-success-subtle text-success">Actif</span>
                                            @else
                                                <span class="badge bg-secondary-subtle text-secondary">Inactif</span>
                                            @endif
                                        </td>
                                        <td class="text-end pe-4">
                                            <div class="btn-group">
                                                <a href="{{ route('onfp.activite-tags.edit', $tag) }}"
                                                    class="btn btn-sm btn-outline-warning">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form method="POST"
                                                    action="{{ route('onfp.activite-tags.destroy', $tag) }}"
                                                    onsubmit="return confirm('Supprimer ce tag ?');" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-tags fs-1 text-muted"></i>
                        <p class="text-muted mt-2">Aucun tag pour le moment.</p>
                    </div>
                @endif

            </div>
            @if ($tags->hasPages())
                <div class="card-footer bg-white">{{ $tags->links() }}</div>
            @endif
        </div>

    </div>
@endsection
