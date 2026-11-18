@extends('layout.user-layout')

@section('space-work')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <div>
            <h4 class="mb-1">Détail de la réception</h4>
            <p class="text-muted mb-0">{{ $reception->reference }}</p>
        </div>
        <a href="{{ route('support.stocks.approvisionnements.index') }}"
           class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Retour aux approvisionnements
        </a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white"><strong>Informations générales</strong></div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-5">Référence</dt>
                        <dd class="col-sm-7">{{ $reception->reference }}</dd>

                        <dt class="col-sm-5">Date du document</dt>
                        <dd class="col-sm-7">
                            {{ \Carbon\Carbon::parse($reception->date_document)->format('d/m/Y H:i') }}
                        </dd>

                        <dt class="col-sm-5">Statut</dt>
                        <dd class="col-sm-7">
                            <span class="badge bg-{{
                                $reception->statut === 'VALIDEE' ? 'success' :
                                ($reception->statut === 'REJETEE' ? 'danger' :
                                ($reception->statut === 'EN_ATTENTE_VALIDATION' ? 'warning' : 'secondary'))
                            }}">
                                {{ str_replace('_', ' ', $reception->statut) }}
                            </span>
                        </dd>

                        <dt class="col-sm-5">Motif</dt>
                        <dd class="col-sm-7">{{ $reception->motif ?: '-' }}</dd>

                        <dt class="col-sm-5">Observations</dt>
                        <dd class="col-sm-7">{{ $reception->observations ?: '-' }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white"><strong>Fournisseur</strong></div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-5">Code</dt>
                        <dd class="col-sm-7">{{ $reception->fournisseur_code ?: '-' }}</dd>

                        <dt class="col-sm-5">Raison sociale</dt>
                        <dd class="col-sm-7">{{ $reception->fournisseur ?: '-' }}</dd>

                        <dt class="col-sm-5">Contact</dt>
                        <dd class="col-sm-7">{{ $reception->nom_contact ?: '-' }}</dd>

                        <dt class="col-sm-5">Téléphone</dt>
                        <dd class="col-sm-7">{{ $reception->telephone ?: '-' }}</dd>

                        <dt class="col-sm-5">E-mail</dt>
                        <dd class="col-sm-7">{{ $reception->email ?: '-' }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="text-muted">Quantité totale réceptionnée</div>
                    <h4>{{ number_format((float) $totaux->quantite_totale, 3, ',', ' ') }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="text-muted">Montant total</div>
                    <h4>{{ number_format((float) $totaux->montant_total, 0, ',', ' ') }} FCFA</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <strong><i class="bi bi-box-seam"></i> Articles réceptionnés</strong>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-striped align-middle mb-0">
                <thead>
                    <tr>
                        <th>Référence</th>
                        <th>Désignation</th>
                        <th class="text-end">Quantité</th>
                        <th class="text-end">Coût unitaire</th>
                        <th class="text-end">Valeur ligne (FCFA)</th>
                        <th>Observations</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lignes as $ligne)
                        <tr>
                            <td>{{ $ligne->reference }}</td>
                            <td>{{ $ligne->designation }}</td>
                            <td class="text-end">
                                {{ number_format((float) $ligne->quantite, 3, ',', ' ') }}
                            </td>
                            <td class="text-end">
                                {{ number_format((float) $ligne->cout_unitaire, 4, ',', ' ') }}
                            </td>
                            <td class="text-end fw-semibold">
                                {{ number_format((float) $ligne->valeur_ligne, 0, ',', ' ') }}
                            </td>
                            <td>{{ $ligne->observations ?: '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                Aucune ligne dans cette réception.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <th colspan="2">Total</th>
                        <th class="text-end">
                            {{ number_format((float) $totaux->quantite_totale, 3, ',', ' ') }}
                        </th>
                        <th></th>
                        <th class="text-end">
                            {{ number_format((float) $totaux->montant_total, 0, ',', ' ') }}
                        </th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

</div>
@endsection
