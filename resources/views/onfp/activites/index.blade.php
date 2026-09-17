@extends('layout.user-layout')

@section('title', 'Activités')

@section('space-work')

    <div class="container-fluid">

        {{-- ============================================================
        HEADER
        ============================================================= --}}

        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">

            <div>
                <h1 class="h3 mb-1">Pilotage des activités</h1>
                <p class="text-muted mb-0">Suivi des activités et actions de l'ONFP</p>
            </div>

            <a href="{{ route('onfp.activites.create') }}" class="btn btn-sm btn-primary">
                <i class="bi bi-plus-circle me-1"></i>
                Nouvelle activité
            </a>

        </div>


        {{-- ============================================================
        MESSAGE
        ============================================================= --}}

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
            </div>
        @endif


        {{-- ============================================================
        STATISTIQUES PRINCIPALES
        ============================================================= --}}

        {{-- <div class="row row-cols-2 row-cols-md-3 row-cols-xl-5 g-3 mb-4"> --}}

        <div class="row row-cols-5 g-3 mb-4">

            {{-- Total --}}
            <div class="col">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-muted small mb-1">Total activités</div>
                                <h3 class="mb-0">{{ $totalActivites }}</h3>
                            </div>
                            <div class="fs-2 text-primary">
                                <i class="bi bi-kanban"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- À faire --}}
            <div class="col">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-muted small mb-1">À faire</div>
                                <h3 class="mb-0">{{ $activitesAFaire }}</h3>
                            </div>
                            <div class="fs-2 text-secondary">
                                <i class="bi bi-hourglass"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- En cours --}}
            <div class="col">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-muted small mb-1">En cours</div>
                                <h3 class="mb-0">{{ $activitesEnCours }}</h3>
                            </div>
                            <div class="fs-2 text-primary">
                                <i class="bi bi-arrow-repeat"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Terminées --}}
            <div class="col">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-muted small mb-1">Terminées</div>
                                <h3 class="mb-0">{{ $activitesTerminees }}</h3>
                            </div>
                            <div class="fs-2 text-success">
                                <i class="bi bi-check-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- À risque --}}
            <div class="col">
                <div
                    class="card shadow-sm h-100 border-0 {{ $activitesRisque > 0 ? 'border-danger border-opacity-25' : '' }}">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-muted small mb-1">À risque</div>
                                <h3 class="mb-0 text-danger">{{ $activitesRisque }}</h3>
                            </div>
                            <div class="fs-2 text-danger">
                                <i class="bi bi-exclamation-triangle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- ============================================================
        SYNTHÈSE
        ============================================================= --}}

        <div class="row g-3 mb-4">

            {{-- Progression globale --}}
            <div class="col-lg-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <h5 class="mb-1">Progression globale</h5>
                                <small class="text-muted">Progression moyenne des activités</small>
                            </div>
                            <strong class="fs-4">{{ $progressionMoyenne }}%</strong>
                        </div>

                        <div class="progress" style="height: 12px;">
                            <div class="progress-bar" role="progressbar" style="width: {{ $progressionMoyenne }}%"
                                aria-valuenow="{{ $progressionMoyenne }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Répartition des statuts --}}
            <div class="col-lg-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <h5 class="mb-3">État des activités</h5>

                        <div class="d-flex flex-wrap gap-2">
                            @foreach ($statuts as $key => $meta)
                                <span
                                    class="badge bg-{{ $meta['badge'] }} px-3 py-2 {{ $meta['badge'] === 'warning' ? 'text-dark' : '' }}">
                                    {{ $meta['label'] }} :
                                    {{ $statutCounts[$key] ?? 0 }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

        </div>


        {{-- ============================================================
        FILTRES
        ============================================================= --}}

        <div class="card shadow-sm mb-4">

            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-funnel me-1"></i>
                        Filtrer les activités
                    </h5>

                    @if ($hasActiveFilters)
                        <a href="{{ route('onfp.activites.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-x-circle me-1"></i>
                            Réinitialiser
                        </a>
                    @endif
                </div>
            </div>

            <div class="card-body">
                <form method="GET" action="{{ route('onfp.activites.index') }}">

                    {{-- On conserve le tri en cours lors d'un nouveau filtre --}}
                    <input type="hidden" name="sort" value="{{ $sort }}">
                    <input type="hidden" name="direction" value="{{ $sortDirection }}">

                    <div class="row g-3">

                        {{-- Recherche --}}
                        <div class="col-md-4">
                            <label class="form-label">Recherche</label>
                            <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                                placeholder="Référence, titre, responsable...">
                        </div>

                        {{-- Direction --}}
                        <div class="col-md-2">
                            <label class="form-label">Direction</label>
                            <select name="direction_id" class="form-select form-select-sm">
                                <option value="">Toutes</option>
                                @foreach ($directions as $direction)
                                    <option value="{{ $direction->id }}" @selected(request('direction_id') == $direction->id)>
                                        {{ $direction->sigle ?: $direction->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Type --}}
                        <div class="col-md-2">
                            <label class="form-label">Type</label>
                            <select name="type_id" class="form-select form-select-sm">
                                <option value="">Tous</option>
                                @foreach ($types as $type)
                                    <option value="{{ $type->id }}" @selected(request('type_id') == $type->id)>
                                        {{ $type->libelle }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Statut --}}
                        <div class="col-md-2">
                            <label class="form-label">Statut</label>
                            <select name="statut" class="form-select form-select-sm">
                                <option value="">Tous</option>
                                @foreach ($statuts as $key => $meta)
                                    <option value="{{ $key }}" @selected(request('statut') === $key)>
                                        {{ $meta['label'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Priorité --}}
                        <div class="col-md-2">
                            <label class="form-label">Priorité</label>
                            <select name="priorite" class="form-select form-select-sm">
                                <option value="">Toutes</option>
                                @foreach ($priorites as $key => $label)
                                    <option value="{{ $key }}" @selected(request('priorite') === $key)>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Santé --}}
                        <div class="col-md-2">
                            <label class="form-label">Santé</label>
                            <select name="etat_sante" class="form-select form-select-sm">
                                <option value="">Tous</option>
                                @foreach ($etatsSante as $key => $meta)
                                    <option value="{{ $key }}" @selected(request('etat_sante') === $key)>
                                        {{ $meta['label'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Éléments par page --}}
                        <div class="col-md-2">
                            <label class="form-label">Par page</label>
                            <select name="per_page" class="form-select form-select-sm" onchange="this.form.submit()">
                                @foreach ($perPageOptions as $option)
                                    <option value="{{ $option }}" @selected($perPage == $option)>
                                        {{ $option }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Bouton --}}
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-sm btn-primary w-100" title="Rechercher">
                                <i class="bi bi-search me-1"></i>
                                Rechercher
                            </button>
                        </div>

                    </div>
                </form>
            </div>

        </div>


        {{-- ============================================================
        TABLEAU
        ============================================================= --}}

        <div class="card shadow-sm">

            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">Activités</h5>
                        <small class="text-muted">Liste des activités correspondant aux critères</small>
                    </div>
                    <span class="badge bg-secondary">{{ $activites->total() }}</span>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">
                        <tr>
                            @foreach ([
            'reference' => 'Référence',
            'titre' => 'Activité',
        ] as $column => $label)
                                <th>
                                    <a href="{{ $sortLink($column) }}"
                                        class="text-decoration-none text-reset d-inline-flex align-items-center gap-1">
                                        {{ $label }}
                                        <i class="bi {{ $sortIcon($column) }} small"></i>
                                    </a>
                                </th>
                            @endforeach

                            <th>Direction</th>
                            <th>Responsable</th>

                            <th>
                                <a href="{{ $sortLink('progression') }}"
                                    class="text-decoration-none text-reset d-inline-flex align-items-center gap-1">
                                    Progression
                                    <i class="bi {{ $sortIcon('progression') }} small"></i>
                                </a>
                            </th>

                            <th>
                                <a href="{{ $sortLink('statut') }}"
                                    class="text-decoration-none text-reset d-inline-flex align-items-center gap-1">
                                    Statut
                                    <i class="bi {{ $sortIcon('statut') }} small"></i>
                                </a>
                            </th>

                            <th>
                                <a href="{{ $sortLink('priorite') }}"
                                    class="text-decoration-none text-reset d-inline-flex align-items-center gap-1">
                                    Priorité
                                    <i class="bi {{ $sortIcon('priorite') }} small"></i>
                                </a>
                            </th>

                            <th>Santé</th>

                            <th>
                                <a href="{{ $sortLink('date_fin_prevue') }}"
                                    class="text-decoration-none text-reset d-inline-flex align-items-center gap-1">
                                    Échéance
                                    <i class="bi {{ $sortIcon('date_fin_prevue') }} small"></i>
                                </a>
                            </th>

                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($activites as $activite)

                            @php
                                $principal = $activite->responsables->firstWhere('is_principal', true);
                                $statutMeta = $statuts[$activite->statut] ?? [
                                    'label' => $activite->statut,
                                    'badge' => 'secondary',
                                ];
                                $santeMeta = $etatsSante[$activite->etat_sante] ?? [
                                    'label' => $activite->etat_sante,
                                    'badge' => 'secondary',
                                ];
                                $echeanceDepassee =
                                    $activite->date_fin_prevue &&
                                    $activite->date_fin_prevue->isPast() &&
                                    !in_array($activite->statut, ['terminee', 'annulee'], true);
                            @endphp

                            <tr>

                                {{-- Référence --}}
                                <td><strong>{{ $activite->reference }}</strong></td>

                                {{-- Activité --}}
                                <td>
                                    <a href="{{ route('onfp.activites.show', $activite) }}"
                                        class="text-decoration-none fw-semibold">
                                        {{ $activite->titre }}
                                    </a>
                                    @if ($activite->type)
                                        <div><small class="text-muted">{{ $activite->type->libelle }}</small></div>
                                    @endif
                                </td>

                                {{-- Direction --}}
                                <td>
                                    @if ($activite->direction)
                                        <span>{{ $activite->direction->sigle ?: $activite->direction->name }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>

                                {{-- Responsable --}}
                                <td>
                                    @if ($principal && $principal->employee)
                                        {{ $principal->employee->user?->firstname . ' ' . $principal->employee->user?->name ?? $principal->employee->matricule }}
                                    @else
                                        <span class="text-muted">Non affecté</span>
                                    @endif
                                </td>

                                {{-- Progression --}}
                                <td style="min-width: 130px">
                                    <div class="d-flex justify-content-between">
                                        <small>{{ $activite->progression }}%</small>
                                    </div>
                                    <div class="progress" style="height: 6px">
                                        <div class="progress-bar" role="progressbar"
                                            style="width: {{ $activite->progression }}%"
                                            aria-valuenow="{{ $activite->progression }}" aria-valuemin="0"
                                            aria-valuemax="100"></div>
                                    </div>
                                </td>

                                {{-- Statut --}}
                                <td>
                                    <span
                                        class="badge bg-{{ $statutMeta['badge'] }} {{ $statutMeta['badge'] === 'warning' ? 'text-dark' : '' }}">
                                        {{ $statutMeta['label'] }}
                                    </span>
                                </td>

                                {{-- Priorité --}}
                                <td>
                                    <span
                                        class="text-capitalize">{{ $priorites[$activite->priorite] ?? $activite->priorite }}</span>
                                </td>

                                {{-- Santé --}}
                                <td>
                                    <span
                                        class="badge bg-{{ $santeMeta['badge'] }} {{ $santeMeta['badge'] === 'warning' ? 'text-dark' : '' }}">
                                        {{ $santeMeta['label'] }}
                                    </span>
                                </td>

                                {{-- Échéance --}}
                                <td>
                                    @if ($activite->date_fin_prevue)
                                        <span class="{{ $echeanceDepassee ? 'text-danger fw-semibold' : '' }}">
                                            {{ $activite->date_fin_prevue->format('d/m/Y') }}
                                        </span>
                                        @if ($echeanceDepassee)
                                            <i class="bi bi-exclamation-circle text-danger ms-1"
                                                title="Échéance dépassée"></i>
                                        @endif
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>

                                {{-- Actions --}}
                                <td class="text-end">
                                    <div class="btn-group">
                                        <a href="{{ route('onfp.activites.show', $activite) }}"
                                            class="btn btn-sm btn-outline-primary" title="Voir">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <a href="{{ route('onfp.activites.edit', $activite) }}"
                                            class="btn btn-sm btn-outline-secondary" title="Modifier">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <form method="POST" action="{{ route('onfp.activites.destroy', $activite) }}">
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

                        @empty

                            <tr>
                                <td colspan="10" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                        <h5>Aucune activité trouvée</h5>
                                        <p>
                                            @if ($hasActiveFilters)
                                                Aucune activité ne correspond aux critères sélectionnés.
                                            @else
                                                Commencez par créer une première activité.
                                            @endif
                                        </p>

                                        @if ($hasActiveFilters)
                                            <a href="{{ route('onfp.activites.index') }}"
                                                class="btn btn-sm btn-outline-secondary me-2">
                                                Réinitialiser les filtres
                                            </a>
                                        @endif

                                        <a href="{{ route('onfp.activites.create') }}" class="btn btn-sm btn-primary">
                                            Nouvelle activité
                                        </a>
                                    </div>
                                </td>
                            </tr>

                        @endforelse
                    </tbody>

                </table>
            </div>

            {{-- Pagination --}}
            @if ($activites->hasPages())
                <div class="card-footer d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        Affichage de {{ $activites->firstItem() }} à {{ $activites->lastItem() }}
                        sur {{ $activites->total() }} activité(s)
                    </small>
                    {{ $activites->links() }}
                </div>
            @endif

        </div>

    </div>


    {{-- ================================================================
    STYLE LOCAL
    ================================================================= --}}

    <style>
        .onfp-orange {
            background-color: #F28500 !important;
            color: #fff !important;
        }
    </style>

@endsection
