@extends('layout.user-layout')

@section('title', 'Note de frais')

@section('space-work')

    @php
        $statutMeta = $statuts[$noteFrais->statut] ?? ['label' => $noteFrais->statut, 'badge' => 'secondary'];
    @endphp

    <div class="container-fluid">

        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
            <div>
                <h1 class="h3 mb-1">
                    Note {{ $noteFrais->type === 'ACOMPTE' ? "d'acompte" : 'définitive' }}
                    <span class="badge bg-{{ $statutMeta['badge'] }} {{ $statutMeta['badge'] === 'warning' ? 'text-dark' : '' }} align-middle">
                        {{ $statutMeta['label'] }}
                    </span>
                </h1>
                <p class="text-muted mb-0">
                    {{ $noteFrais->formation?->operateur?->user?->display_operateur }}
                    — {{ $noteFrais->formation?->intitule ?? $noteFrais->formation?->name }}
                </p>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('formations.notes-frais.pdf', $noteFrais) }}" target="_blank" class="btn btn-sm btn-outline-dark">
                    <i class="bi bi-file-earmark-pdf me-1"></i> PDF
                </a>

                @if ($noteFrais->statut === 'BROUILLON')
                    <a href="{{ route('formations.notes-frais.edit', $noteFrais) }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-pencil me-1"></i> Modifier
                    </a>

                    <form method="POST" action="{{ route('formations.notes-frais.soumettre', $noteFrais) }}">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-primary">
                            <i class="bi bi-send me-1"></i> Soumettre
                        </button>
                    </form>
                @endif

                @if (in_array($noteFrais->statut, ['SOUMISE', 'VALIDEE_DIOF']))
                    <form method="POST" action="{{ route('formations.notes-frais.valider', $noteFrais) }}">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-success">
                            <i class="bi bi-check-circle me-1"></i> Valider
                        </button>
                    </form>
                @endif

                @if ($noteFrais->statut === 'VALIDEE_DF')
                    <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal"
                        data-bs-target="#modal-paiement">
                        <i class="bi bi-cash-coin me-1"></i> Marquer payée
                    </button>
                @endif

                <a href="{{ route('formations.notes-frais.index') }}" class="btn btn-sm btn-outline-secondary">
                    Retour
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
            </div>
        @endif

        <div class="row g-4">

            {{-- Informations générales --}}
            <div class="col-lg-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white"><h5 class="mb-0">Informations</h5></div>
                    <div class="card-body">
                        <dl class="row mb-0 small">
                            <dt class="col-5">Session</dt>
                            <dd class="col-7">{{ $noteFrais->session_label ?: '—' }}</dd>

                            <dt class="col-5">Lieu</dt>
                            <dd class="col-7">{{ $noteFrais->lieu ?: '—' }}</dd>

                            <dt class="col-5">Bénéficiaires</dt>
                            <dd class="col-7">{{ $noteFrais->beneficiaires ?: '—' }}</dd>

                            @if ($noteFrais->type === 'DEFINITIVE')
                                <dt class="col-5">Période</dt>
                                <dd class="col-7">
                                    {{ optional($noteFrais->periode_debut)->format('d/m/Y') }}
                                    au {{ optional($noteFrais->periode_fin)->format('d/m/Y') }}
                                </dd>

                                <dt class="col-5">Note d'acompte liée</dt>
                                <dd class="col-7">
                                    @if ($noteFrais->noteAcompte)
                                        <a href="{{ route('formations.notes-frais.show', $noteFrais->noteAcompte) }}">
                                            Voir la note d'acompte
                                        </a>
                                    @else
                                        Aucune (100%)
                                    @endif
                                </dd>
                            @endif

                            <dt class="col-5">RIB</dt>
                            <dd class="col-7">{{ $noteFrais->banque_rib ?: '—' }}</dd>
                        </dl>
                    </div>
                </div>

                @if ($noteFrais->est_payee)
                    <div class="card shadow-sm mt-3 border-success">
                        <div class="card-header bg-success bg-opacity-10">
                            <h6 class="mb-0 text-success"><i class="bi bi-check-circle me-1"></i> Paiement effectué</h6>
                        </div>
                        <div class="card-body">
                            <dl class="row mb-0 small">
                                <dt class="col-5">Date</dt>
                                <dd class="col-7">{{ $noteFrais->date_paiement?->format('d/m/Y') }}</dd>

                                <dt class="col-5">Mode</dt>
                                <dd class="col-7">{{ $noteFrais->mode_paiement }}</dd>

                                <dt class="col-5">Référence</dt>
                                <dd class="col-7">{{ $noteFrais->reference_paiement }}</dd>

                                <dt class="col-5">Enregistré par</dt>
                                <dd class="col-7">{{ $noteFrais->payeur?->name }}</dd>
                            </dl>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Montants --}}
            <div class="col-lg-8">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white"><h5 class="mb-0">Rubriques et montants</h5></div>

                    <div class="table-responsive">
                        <table class="table table-sm table-bordered mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Rubrique</th>
                                    <th>Libellé</th>
                                    <th>Unité</th>
                                    <th class="text-end">Qte</th>
                                    <th class="text-end">PU</th>
                                    <th class="text-end">Montant</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($noteFrais->lignes as $ligne)
                                    <tr>
                                        <td>{{ $ligne->rubrique->groupe === 'PEDAGOGIQUE' ? 'Pédagogique' : 'Administratif' }}</td>
                                        <td>{{ $ligne->rubrique->libelle }}</td>
                                        <td>{{ $ligne->unite }}</td>
                                        <td class="text-end">{{ number_format($ligne->qte, 0, ',', ' ') }}</td>
                                        <td class="text-end">{{ number_format($ligne->pu, 0, ',', ' ') }}</td>
                                        <td class="text-end">{{ number_format($ligne->montant, 0, ',', ' ') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="table-light">
                                    <td colspan="5" class="text-end fw-bold">Sous total pédagogique</td>
                                    <td class="text-end fw-bold">{{ number_format($noteFrais->sous_total_pedagogique, 0, ',', ' ') }}</td>
                                </tr>
                                <tr class="table-light">
                                    <td colspan="5" class="text-end fw-bold">Sous total administratif</td>
                                    <td class="text-end fw-bold">{{ number_format($noteFrais->sous_total_administratif, 0, ',', ' ') }}</td>
                                </tr>
                                <tr class="table-secondary">
                                    <td colspan="5" class="text-end fw-bold">TOTAL FRAIS OPERATEUR (A)</td>
                                    <td class="text-end fw-bold">{{ number_format($noteFrais->total_frais_operateur, 0, ',', ' ') }}</td>
                                </tr>

                                @if ($noteFrais->type === 'ACOMPTE')
                                    <tr class="table-info">
                                        <td colspan="5" class="text-end fw-bold">ACOMPTE ({{ rtrim(rtrim(number_format($noteFrais->taux_acompte, 2), '0'), '.') }}%)</td>
                                        <td class="text-end fw-bold">{{ number_format($noteFrais->montant_acompte_demande, 0, ',', ' ') }}</td>
                                    </tr>
                                @else
                                    <tr class="table-light">
                                        <td colspan="5" class="text-end fw-bold">ACOMPTE RECU (B)</td>
                                        <td class="text-end fw-bold">{{ number_format($noteFrais->montant_acompte_recu, 0, ',', ' ') }}</td>
                                    </tr>
                                    <tr class="table-warning">
                                        <td colspan="5" class="text-end fw-bold">RELIQUAT (A - B)</td>
                                        <td class="text-end fw-bold">{{ number_format($noteFrais->reliquat, 0, ',', ' ') }}</td>
                                    </tr>
                                @endif
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

        </div>

    </div>

    {{-- Modal d'enregistrement du paiement --}}
    @if ($noteFrais->statut === 'VALIDEE_DF')
        <div class="modal fade" id="modal-paiement" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" action="{{ route('formations.notes-frais.marquer-payee', $noteFrais) }}">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Enregistrer le paiement</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="mode_paiement" class="form-label">Mode de paiement <span class="text-danger">*</span></label>
                                <select name="mode_paiement" id="mode_paiement" class="form-select form-select-sm" required>
                                    <option value="Virement">Virement</option>
                                    <option value="Chèque">Chèque</option>
                                    <option value="Espèces">Espèces</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="reference_paiement" class="form-label">Référence <span class="text-danger">*</span></label>
                                <input type="text" name="reference_paiement" id="reference_paiement"
                                    class="form-control form-control-sm" placeholder="N° de virement, de chèque..." required>
                            </div>
                            <div class="mb-3">
                                <label for="date_paiement" class="form-label">Date de paiement <span class="text-danger">*</span></label>
                                <input type="date" name="date_paiement" id="date_paiement"
                                    class="form-control form-control-sm" value="{{ now()->format('Y-m-d') }}" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-sm btn-success">Confirmer le paiement</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

@endsection
