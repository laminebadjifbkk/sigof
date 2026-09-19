@extends('layout.user-layout')

@section('title', 'Annuaire des tiers')

@section('space-work')

    <div class="container-fluid py-4">

        {{-- En-tête --}}
        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>
                <h4 class="mb-1">
                    <i class="bi bi-person-vcard me-2"></i>
                    Annuaire des tiers
                </h4>
                <div class="text-muted">
                    Partenaires, prestataires et autres intervenants externes
                </div>
            </div>

            <a href="{{ route('onfp.tiers.create') }}" class="btn btn-sm btn-primary">
                <i class="bi bi-plus-circle me-1"></i>
                Nouveau tiers
            </a>

        </div>


        {{-- Messages --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-triangle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif


        {{-- Filtres --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">

                <form method="GET" action="{{ route('onfp.tiers.index') }}" class="row g-3 align-items-end">

                    <div class="col-md-5">
                        <label class="form-label small text-muted">Recherche</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="form-control form-control-sm" placeholder="Nom, organisation, email...">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label small text-muted">Type</label>
                        <select name="type" class="form-select form-select-sm">
                            <option value="">Tous les types</option>
                            @foreach ($types as $value => $label)
                                <option value="{{ $value }}" @selected(request('type') === $value)>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label small text-muted">État</label>
                        <select name="actif" class="form-select form-select-sm">
                            <option value="">Tous</option>
                            <option value="1" @selected(request('actif') === '1')>Actifs</option>
                            <option value="0" @selected(request('actif') === '0')>Inactifs</option>
                        </select>
                    </div>

                    <div class="col-md-2 d-grid">
                        <button type="submit" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-search me-1"></i>
                            Filtrer
                        </button>
                    </div>

                </form>

            </div>
        </div>


        {{-- Liste --}}
        <div class="card border-0 shadow-sm">

            <div class="card-body p-0">

                @if ($tiers->count())

                    <div class="table-responsive">

                        <table class="table table-hover align-middle mb-0">

                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Nom</th>
                                    <th>Type</th>
                                    <th>Organisation</th>
                                    <th>Contact</th>
                                    <th>État</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>

                            <tbody>

                                @foreach ($tiers as $tier)
                                    <tr>

                                        <td class="ps-4">
                                            <a href="{{ route('onfp.tiers.show', $tier) }}"
                                                class="fw-semibold text-decoration-none text-dark">
                                                {{ $tier->nom }}
                                            </a>
                                            @if ($tier->fonction)
                                                <div class="small text-muted">
                                                    {{ $tier->fonction }}
                                                </div>
                                            @endif
                                        </td>

                                        <td>
                                            @if ($tier->type)
                                                <span class="badge bg-light text-dark border">
                                                    {{ $types[$tier->type] ?? $tier->type }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>

                                        <td>
                                            {{ $tier->organisation ?: '-' }}
                                        </td>

                                        <td>
                                            <div class="small">
                                                @if ($tier->telephone)
                                                    <div>
                                                        <i class="bi bi-telephone me-1"></i>
                                                        {{ $tier->telephone }}
                                                    </div>
                                                @endif
                                                @if ($tier->email)
                                                    <div>
                                                        <i class="bi bi-envelope me-1"></i>
                                                        {{ $tier->email }}
                                                    </div>
                                                @endif
                                                @if (!$tier->telephone && !$tier->email)
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </div>
                                        </td>

                                        <td>
                                            @if ($tier->actif)
                                                <span class="badge bg-success-subtle text-success">Actif</span>
                                            @else
                                                <span class="badge bg-secondary-subtle text-secondary">Inactif</span>
                                            @endif
                                        </td>

                                        <td class="text-end pe-4">

                                            <div class="btn-group">

                                                <a href="{{ route('onfp.tiers.show', $tier) }}"
                                                    class="btn btn-sm btn-outline-primary" title="Voir">
                                                    <i class="bi bi-eye"></i>
                                                </a>

                                                <a href="{{ route('onfp.tiers.edit', $tier) }}"
                                                    class="btn btn-sm btn-outline-warning" title="Modifier">
                                                    <i class="bi bi-pencil"></i>
                                                </a>

                                                <form method="POST"
                                                    action="{{ route('onfp.tiers.destroy', $tier) }}" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger show_confirm"
                                                        title="Supprimer">
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
                        <i class="bi bi-person-vcard fs-1 text-muted"></i>
                        <p class="text-muted mt-2 mb-3">
                            Aucun tiers ne correspond à ces critères.
                        </p>
                        <a href="{{ route('onfp.tiers.create') }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-plus-circle me-1"></i>
                            Ajouter un tiers
                        </a>
                    </div>

                @endif

            </div>

            @if ($tiers->hasPages())
                <div class="card-footer bg-white border-top">
                    {{ $tiers->links() }}
                </div>
            @endif

        </div>

    </div>

@endsection
