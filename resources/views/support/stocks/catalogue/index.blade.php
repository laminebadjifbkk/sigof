@extends('layout.user-layout')

@section('space-work')
<div class="container-fluid py-3">

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h3 class="mb-1">
                <i class="bi bi-box-seam text-warning"></i>
                {{ $config['titre'] }}
            </h3>
            <div class="text-muted">Gestion des stocks informatiques</div>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('support.stocks.dashboard') }}"
               class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Tableau de bord
            </a>

            @can('support.stocks.manage')
                <a href="{{ route('support.stocks.catalogue.create', $type) }}"
                   class="btn btn-warning">
                    <i class="bi bi-plus-circle"></i> Ajouter
                </a>
            @endcan
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            <i class="bi bi-exclamation-triangle me-2"></i>
            {{ session('error') }}
        </div>
    @endif

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" class="row g-2 align-items-end">

                <div class="col-md-6">
                    <label class="form-label">Recherche</label>
                    <input type="text"
                           name="recherche"
                           class="form-control"
                           value="{{ request('recherche') }}"
                           placeholder="Code, désignation, libellé, fournisseur...">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Statut</label>
                    <select name="statut" class="form-select">
                        <option value="">Tous les statuts</option>
                        <option value="actif" @selected(request('statut') === 'actif')>
                            Actifs
                        </option>
                        <option value="inactif" @selected(request('statut') === 'inactif')>
                            Inactifs
                        </option>
                        <option value="supprime" @selected(request('statut') === 'supprime')>
                            Supprimés
                        </option>
                    </select>
                </div>

                <div class="col-md-3 d-flex gap-2">
                    <button class="btn btn-dark">
                        <i class="bi bi-search"></i> Rechercher
                    </button>

                    <a href="{{ route('support.stocks.catalogue.index', $type) }}"
                       class="btn btn-outline-secondary">
                        Effacer
                    </a>
                </div>

            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <span class="fw-semibold">
                <i class="bi bi-list-ul me-2"></i>
                Liste des {{ strtolower($config['titre']) }}
            </span>

            <span class="badge bg-light text-dark border">
                {{ $elements->total() }} résultat(s)
            </span>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">
                <tr>
                    @if($type === 'categories')
                        <th>Code</th>
                        <th>Libellé</th>
                        <th>Description</th>

                    @elseif($type === 'unites')
                        <th>Code</th>
                        <th>Unité</th>
                        <th>Symbole</th>
                        <th>Décimales</th>

                    @elseif($type === 'fournisseurs')
                        <th>Code</th>
                        <th>Raison sociale</th>
                        <th>Contact</th>
                        <th>Téléphone</th>
                        <th>E-mail</th>
                        <th>NINEA</th>

                    @else
                        <th>Référence</th>
                        <th>Désignation</th>
                        <th>Catégorie</th>
                        <th>Unité</th>
                        <th>Quantité disponible</th>
                        <th>Seuil minimum</th>
                    @endif

                    <th>Statut</th>
                    <th class="text-end">Actions</th>
                </tr>
                </thead>

                <tbody>
                @forelse($elements as $element)

                    @php
                        $supprime = method_exists($element, 'trashed')
                            && $element->trashed();
                    @endphp

                    <tr class="{{ $supprime ? 'table-secondary' : '' }}">

                        @if($type === 'categories')
                            <td>{{ $element->code }}</td>
                            <td class="fw-semibold">{{ $element->libelle }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($element->description, 65) }}</td>

                        @elseif($type === 'unites')
                            <td>{{ $element->code }}</td>
                            <td class="fw-semibold">{{ $element->libelle }}</td>
                            <td>{{ $element->symbole }}</td>
                            <td>{{ $element->autorise_decimales ? 'Oui' : 'Non' }}</td>

                        @elseif($type === 'fournisseurs')
                            <td>{{ $element->code }}</td>
                            <td>
                                <div class="fw-semibold">{{ $element->raison_sociale }}</div>
                                @if($element->adresse)
                                    <small class="text-muted">{{ $element->adresse }}</small>
                                @endif
                            </td>
                            <td>{{ $element->nom_contact ?: '—' }}</td>
                            <td>{{ $element->telephone ?: '—' }}</td>
                            <td>{{ $element->email ?: '—' }}</td>
                            <td>{{ $element->numero_ninea ?: '—' }}</td>

                        @else
                            <td class="fw-semibold">{{ $element->reference }}</td>
                            <td>{{ $element->designation }}</td>
                            <td>{{ $element->categorie?->libelle ?? '—' }}</td>
                            <td>{{ $element->unite?->symbole ?? $element->unite?->libelle ?? '—' }}</td>
                            <td>
                                {{ number_format((float) ($element->stock?->quantite ?? 0), 3, ',', ' ') }}
                            </td>
                            <td>
                                {{ number_format((float) $element->stock_minimum, 3, ',', ' ') }}
                            </td>
                        @endif

                        <td>
                            @if($supprime)
                                <span class="badge bg-danger">Supprimé</span>
                            @elseif($element->actif)
                                <span class="badge bg-success">Actif</span>
                            @else
                                <span class="badge bg-secondary">Inactif</span>
                            @endif
                        </td>

                        <td class="text-end text-nowrap">

                            @can('support.stocks.manage')

                                @if($supprime)

                                    <form method="POST"
                                          action="{{ route('support.stocks.catalogue.toggle', [$type, $element->id]) }}"
                                          class="d-inline"
                                          onsubmit="return confirm('Restaurer cet élément et le réactiver ?')">
                                        @csrf
                                        @method('PATCH')

                                        <button type="submit"
                                                class="btn btn-sm btn-outline-success"
                                                title="Restaurer">
                                            <i class="bi bi-arrow-counterclockwise"></i>
                                            Restaurer
                                        </button>
                                    </form>

                                @else

                                    <a href="{{ route('support.stocks.catalogue.edit', [$type, $element->id]) }}"
                                       class="btn btn-sm btn-outline-primary"
                                       title="Modifier">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <form method="POST"
                                          action="{{ route('support.stocks.catalogue.toggle', [$type, $element->id]) }}"
                                          class="d-inline"
                                          onsubmit="return confirm('Confirmer le changement de statut ?')">
                                        @csrf
                                        @method('PATCH')

                                        <button type="submit"
                                                class="btn btn-sm btn-outline-secondary"
                                                title="{{ $element->actif ? 'Désactiver' : 'Activer' }}">
                                            <i class="bi bi-power"></i>
                                        </button>
                                    </form>

                                @endif

                            @endcan

                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-5">
                            <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                            Aucun élément enregistré pour ce filtre.
                        </td>
                    </tr>
                @endforelse
                </tbody>

            </table>
        </div>

        <div class="card-footer bg-white">
            {{ $elements->links() }}
        </div>

    </div>
</div>
@endsection
