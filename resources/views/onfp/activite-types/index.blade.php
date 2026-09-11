@extends('layout.user-layout')

@section('title', 'Types d’activité')

@section('space-work')

<div class="container-fluid py-4">

    {{-- En-tête --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>
            <h3 class="mb-1">
                <i class="bi bi-tags me-2"></i>
                Types d’activité
            </h3>

            <p class="text-muted mb-0">
                Gestion des catégories d’activités de l’ONFP
            </p>
        </div>

        <a href="{{ route('onfp.activite-types.create') }}"
           class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>
            Nouveau type
        </a>

    </div>


    {{-- Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle me-2"></i>
            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    @endif


    {{-- Filtres --}}
    <div class="card shadow-sm border-0 mb-4">

        <div class="card-body">

            <form method="GET"
                  action="{{ route('onfp.activite-types.index') }}">

                <div class="row g-3 align-items-end">

                    {{-- Recherche --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Recherche
                        </label>

                        <input type="text"
                               name="search"
                               class="form-control"
                               value="{{ request('search') }}"
                               placeholder="Code, libellé ou description...">

                    </div>


                    {{-- Statut --}}
                    <div class="col-md-3">

                        <label class="form-label">
                            Statut
                        </label>

                        <select name="actif"
                                class="form-select">

                            <option value="">
                                Tous
                            </option>

                            <option value="1"
                                {{ request('actif') === '1' ? 'selected' : '' }}>
                                Actifs
                            </option>

                            <option value="0"
                                {{ request('actif') === '0' ? 'selected' : '' }}>
                                Inactifs
                            </option>

                        </select>

                    </div>


                    {{-- Boutons --}}
                    <div class="col-md-3">

                        <div class="d-flex gap-2">

                            <button type="submit"
                                    class="btn btn-primary">
                                <i class="bi bi-search me-1"></i>
                                Rechercher
                            </button>

                            <a href="{{ route('onfp.activite-types.index') }}"
                               class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-counterclockwise"></i>
                            </a>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- Tableau --}}
    <div class="card shadow-sm border-0">

        <div class="card-header bg-white py-3">

            <div class="d-flex justify-content-between align-items-center">

                <strong>
                    Liste des types
                </strong>

                <span class="badge bg-secondary">
                    {{ $types->total() }}
                </span>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>
                            <th width="80">Ordre</th>
                            <th width="150">Code</th>
                            <th>Libellé</th>
                            <th>Description</th>
                            <th width="120">Activités</th>
                            <th width="110">Statut</th>
                            <th width="150" class="text-end">Actions</th>
                        </tr>

                    </thead>


                    <tbody>

                    @forelse($types as $type)

                        <tr>

                            <td>
                                <span class="badge bg-light text-dark">
                                    {{ $type->ordre }}
                                </span>
                            </td>


                            <td>
                                <code>
                                    {{ $type->code }}
                                </code>
                            </td>


                            <td>
                                <strong>
                                    {{ $type->libelle }}
                                </strong>
                            </td>


                            <td>

                                @if($type->description)

                                    <span class="text-muted">
                                        {{ Str::limit($type->description, 80) }}
                                    </span>

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            <td>

                                <span class="badge bg-info-subtle text-info">
                                    {{ $type->activites()->count() }}
                                </span>

                            </td>


                            <td>

                                @if($type->actif)

                                    <span class="badge bg-success">
                                        Actif
                                    </span>

                                @else

                                    <span class="badge bg-secondary">
                                        Inactif
                                    </span>

                                @endif

                            </td>


                            <td class="text-end">

                                <div class="btn-group">

                                    <a href="{{ route('onfp.activite-types.edit', $type) }}"
                                       class="btn btn-sm btn-outline-primary"
                                       title="Modifier">

                                        <i class="bi bi-pencil"></i>

                                    </a>


                                    @if(!$type->activites()->exists())

                                        <form method="POST"
                                              action="{{ route('onfp.activite-types.destroy', $type) }}"
                                              onsubmit="return confirm('Voulez-vous vraiment supprimer ce type ?');">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                    title="Supprimer">

                                                <i class="bi bi-trash"></i>

                                            </button>

                                        </form>

                                    @else

                                        <button type="button"
                                                class="btn btn-sm btn-outline-secondary"
                                                disabled
                                                title="Type utilisé">

                                            <i class="bi bi-lock"></i>

                                        </button>

                                    @endif

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="7"
                                class="text-center py-5">

                                <div class="text-muted">

                                    <i class="bi bi-inbox fs-1 d-block mb-3"></i>

                                    Aucun type d’activité trouvé.

                                </div>

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        @if($types->hasPages())

            <div class="card-footer bg-white">

                {{ $types->links() }}

            </div>

        @endif

    </div>

</div>

@endsection
