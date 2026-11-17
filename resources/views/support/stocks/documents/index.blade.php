@extends('layout.user-layout')

@section('title', 'ONFP | Documents de stock')

@section('space-work')
<div class="container-fluid py-3">

    @php
        $types = [
            'RECEPTION' => 'Réception',
            'SORTIE' => 'Sortie',
            'RETOUR' => 'Retour',
            'AJUSTEMENT_POSITIF' => 'Ajustement positif',
            'AJUSTEMENT_NEGATIF' => 'Ajustement négatif',
        ];

        $couleurs = [
            'BROUILLON' => 'secondary',
            'EN_ATTENTE_VALIDATION' => 'warning',
            'VALIDEE' => 'success',
            'REJETEE' => 'danger',
            'ANNULEE' => 'dark',
        ];
    @endphp

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <h3 class="fw-bold mb-1">Documents de stock</h3>
            <p class="text-muted mb-0">
                Gestion des réceptions, sorties, retours et ajustements
            </p>
        </div>

        <a href="{{ route('support.stocks.dashboard') }}"
           class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Tableau de bord
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @can('support.stocks.manage')
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <strong><i class="bi bi-plus-circle me-2"></i>Nouveau document</strong>
            </div>
            <div class="card-body">
                <div class="row g-2">
                    @foreach($types as $type => $libelle)
                        <div class="col-6 col-md-4 col-xl">
                            <a href="{{ route('support.stocks.documents.create', $type) }}"
                               class="btn btn-outline-primary w-100 h-100 py-3">
                                <i class="bi bi-plus-circle d-block fs-4 mb-1"></i>
                                {{ $libelle }}
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endcan

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">

                <div class="col-md-4">
                    <label class="form-label">Recherche</label>
                    <input type="text"
                           name="recherche"
                           class="form-control"
                           value="{{ request('recherche') }}"
                           placeholder="Référence ou motif">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Type de document</label>
                    <select name="type" class="form-select">
                        <option value="">Tous les types</option>
                        @foreach($types as $key => $label)
                            <option value="{{ $key }}" @selected(request('type') === $key)>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Statut</label>
                    <select name="statut" class="form-select">
                        <option value="">Tous les statuts</option>
                        @foreach($couleurs as $statut => $couleur)
                            <option value="{{ $statut }}" @selected(request('statut') === $statut)>
                                {{ str_replace('_', ' ', $statut) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2 d-flex align-items-end gap-2">
                    <button class="btn btn-primary flex-grow-1">
                        <i class="bi bi-search"></i> Filtrer
                    </button>
                    <a href="{{ route('support.stocks.documents.index') }}"
                       class="btn btn-outline-secondary">
                        <i class="bi bi-x-lg"></i>
                    </a>
                </div>

            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <strong>Liste des documents</strong>
            <span class="badge bg-primary">{{ $documents->total() }} document(s)</span>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Référence</th>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Fournisseur / Motif</th>
                        <th>Créateur</th>
                        <th>Statut</th>
                        <th>Validateur</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($documents as $document)
                        @php
                            $couleur = $couleurs[$document->statut] ?? 'secondary';
                        @endphp

                        <tr>
                            <td class="fw-semibold text-nowrap">
                                {{ $document->reference }}
                            </td>

                            <td class="text-nowrap">
                                {{ $document->date_document?->format('d/m/Y') ?? '-' }}
                            </td>

                            <td>
                                {{ str_replace('_', ' ', $document->type_document) }}
                            </td>

                            <td>
                                {{ $document->fournisseur->raison_sociale ?? $document->motif ?? '-' }}
                            </td>

                            <td>
                                {{ trim(($document->createur->firstname ?? '').' '.($document->createur->name ?? '')) ?: '-' }}
                            </td>

                            <td>
                                <span class="badge bg-{{ $couleur }}">
                                    {{ str_replace('_', ' ', $document->statut) }}
                                </span>

                                @if($document->statut === 'REJETEE')
                                    <div class="small text-danger mt-1">
                                        À corriger
                                    </div>
                                @endif
                            </td>

                            <td>
                                {{ $document->validateur
                                    ? trim(($document->validateur->firstname ?? '').' '.$document->validateur->name)
                                    : '-' }}
                            </td>

                            <td class="text-end text-nowrap">
                                <a href="{{ route('support.stocks.documents.show', $document->id) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i> Consulter
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-5">
                                <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                Aucun document trouvé.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer bg-white">
            {{ $documents->links() }}
        </div>
    </div>

</div>
@endsection
