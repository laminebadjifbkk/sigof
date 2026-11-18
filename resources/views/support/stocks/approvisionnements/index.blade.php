@extends('layout.user-layout')

@section('space-work')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <div>
            <h4 class="mb-1">Approvisionnements</h4>
            <p class="text-muted mb-0">
                Suivi des réceptions, des fournisseurs et des achats réalisés
            </p>
        </div>

        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('support.stocks.approvisionnements.excel', request()->query()) }}"
               class="btn btn-success btn-sm">
                <i class="bi bi-file-earmark-excel"></i> Excel
            </a>
            <a href="{{ route('support.stocks.approvisionnements.pdf', request()->query()) }}"
               class="btn btn-danger btn-sm">
                <i class="bi bi-file-earmark-pdf"></i> PDF
            </a>
            <a href="{{ route('support.stocks.documents.create', 'RECEPTION') }}"
               class="btn btn-primary btn-sm">
                <i class="bi bi-plus-circle"></i> Nouvelle réception
            </a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted">Fournisseurs actifs</div>
                    <h3 class="mb-0">
                        {{ number_format($nombreFournisseurs, 0, ',', ' ') }}
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted">Réceptions validées</div>
                    <h3 class="mb-0">
                        {{ number_format($indicateurs->nombre_receptions ?? 0, 0, ',', ' ') }}
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted">Quantités réceptionnées</div>
                    <h3 class="mb-0">
                        {{ number_format((float) ($indicateurs->quantite_totale ?? 0), 3, ',', ' ') }}
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted">Montant des achats validés</div>
                    <h3 class="mb-0 text-success">
                        {{ number_format((float) ($indicateurs->montant_total ?? 0), 0, ',', ' ') }}
                        <small class="fs-6">FCFA</small>
                    </h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white">
            <strong><i class="bi bi-funnel"></i> Filtres de recherche</strong>
        </div>
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">

                <div class="col-lg-3 col-md-6">
                    <label class="form-label">Fournisseur</label>
                    <select name="fournisseur_id" class="form-select">
                        <option value="">Tous les fournisseurs</option>
                        @foreach($fournisseurs as $fournisseur)
                            <option value="{{ $fournisseur->id }}"
                                @selected(request('fournisseur_id') == $fournisseur->id)>
                                {{ $fournisseur->code }} - {{ $fournisseur->raison_sociale }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-2 col-md-6">
                    <label class="form-label">Statut</label>
                    <select name="statut" class="form-select">
                        <option value="">Tous les statuts</option>
                        @foreach([
                            'BROUILLON' => 'Brouillon',
                            'EN_ATTENTE_VALIDATION' => 'En attente',
                            'VALIDEE' => 'Validée',
                            'REJETEE' => 'Rejetée',
                        ] as $value => $label)
                            <option value="{{ $value }}" @selected(request('statut') === $value)>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-2 col-md-6">
                    <label class="form-label">Date début</label>
                    <input type="date" name="date_debut" class="form-control"
                           value="{{ request('date_debut') }}">
                </div>

                <div class="col-lg-2 col-md-6">
                    <label class="form-label">Date fin</label>
                    <input type="date" name="date_fin" class="form-control"
                           value="{{ request('date_fin') }}">
                </div>

                <div class="col-lg-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i> Filtrer
                    </button>
                    <a href="{{ route('support.stocks.approvisionnements.index') }}"
                       class="btn btn-outline-secondary">
                        Réinitialiser
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <strong><i class="bi bi-trophy"></i> Classement des fournisseurs</strong>
            <small class="text-muted">Top 10 par montant réceptionné validé</small>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width:70px">Rang</th>
                        <th>Code</th>
                        <th>Fournisseur</th>
                        <th class="text-center">Réceptions</th>
                        <th class="text-end">Quantité</th>
                        <th class="text-end">Montant réceptionné</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($classement as $index => $fournisseur)
                        <tr>
                            <td>
                                @if($index === 0)
                                    <span class="badge bg-warning text-dark">1</span>
                                @else
                                    {{ $index + 1 }}
                                @endif
                            </td>
                            <td>{{ $fournisseur->code ?? '-' }}</td>
                            <td class="fw-semibold">
                                {{ $fournisseur->raison_sociale ?? 'Fournisseur supprimé' }}
                            </td>
                            <td class="text-center">{{ $fournisseur->nombre_receptions }}</td>
                            <td class="text-end">
                                {{ number_format((float) $fournisseur->quantite_totale, 3, ',', ' ') }}
                            </td>
                            <td class="text-end fw-semibold">
                                {{ number_format((float) $fournisseur->montant_total, 0, ',', ' ') }}
                                FCFA
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                Aucun achat validé pour les filtres sélectionnés.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <strong><i class="bi bi-receipt"></i> Historique des réceptions</strong>
        </div>

        <div class="table-responsive">
            <table class="table table-hover table-striped align-middle mb-0">
                <thead>
                    <tr>
                        <th>Référence</th>
                        <th>Date</th>
                        <th>Fournisseur</th>
                        <th>Statut</th>
                        <th class="text-center">Lignes</th>
                        <th class="text-end">Quantité</th>
                        <th class="text-end">Montant (FCFA)</th>
                        <th class="text-center">Détail</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($receptions as $reception)
                        <tr>
                            <td class="fw-semibold">{{ $reception->reference }}</td>
                            <td>
                                {{ \Carbon\Carbon::parse($reception->date_document)->format('d/m/Y') }}
                            </td>
                            <td>
                                <span class="fw-semibold">
                                    {{ $reception->fournisseur_code ?: '-' }}
                                </span>
                                <span class="text-muted"> - </span>
                                {{ $reception->fournisseur ?? 'Fournisseur supprimé' }}
                            </td>
                            <td>
                                @php
                                    $badge = match($reception->statut) {
                                        'VALIDEE' => 'success',
                                        'REJETEE' => 'danger',
                                        'EN_ATTENTE_VALIDATION' => 'warning',
                                        default => 'secondary',
                                    };
                                @endphp
                                <span class="badge bg-{{ $badge }}">
                                    {{ str_replace('_', ' ', $reception->statut) }}
                                </span>
                            </td>
                            <td class="text-center">{{ $reception->nombre_lignes }}</td>
                            <td class="text-end">
                                {{ number_format((float) $reception->quantite_totale, 3, ',', ' ') }}
                            </td>
                            <td class="text-end fw-semibold">
                                {{ number_format((float) $reception->montant_total, 0, ',', ' ') }}
                            </td>
                            <td class="text-center">
                                <a href="{{ route('support.stocks.approvisionnements.show', $reception->id) }}"
                                   class="btn btn-outline-primary btn-sm" title="Consulter la réception">
                                    <i class="bi bi-eye"></i>
                                    Voir
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                Aucune réception trouvée.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-body">
            {{ $receptions->links() }}
        </div>
    </div>

    <div class="alert alert-info mt-3 mb-0">
        <i class="bi bi-info-circle"></i>
        Les indicateurs financiers et le classement fournisseurs sont calculés
        exclusivement sur les réceptions validées. Les autres statuts restent
        visibles dans l'historique, mais ne sont pas comptabilisés comme achats réalisés.
    </div>

</div>
@endsection
