@extends('layout.user-layout')

@section('title', 'ONFP | Gestion des stocks')

@section('space-work')
    <div class="container-fluid py-3">

        <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
            <div>
                <h3 class="fw-bold mb-1">Gestion des stocks informatiques</h3>
                <p class="text-muted mb-0">
                    Tableau de bord – Parc informatique et consommables
                </p>
            </div>
            <div class="d-flex gap-2">
                <span class="badge text-dark border bg-white p-2">
                    <i class="bi bi-box-seam me-1"></i> Stock central
                </span>
            </div>
        </div>

        <div class="row g-3 mb-4">

            @php
                $indicateurs = [
                    [
                        'Articles actifs',
                        number_format($stats['articles'], 0, ',', ' '),
                        'bi-box-seam',
                        '#0d6efd'
                    ],
                    [
                        'Quantité en stock',
                        number_format((float) $stats['quantite'], 3, ',', ' '),
                        'bi-stack',
                        '#198754'
                    ],
                    [
                        'Valeur du stock',
                        number_format((float) $stats['valeur'], 0, ',', ' ') . ' FCFA',
                        'bi-cash-stack',
                        '#F28500'
                    ],
                    [
                        'Articles sous seuil',
                        number_format($stats['sous_seuil'], 0, ',', ' '),
                        'bi-exclamation-triangle',
                        '#dc3545'
                    ],
                    [
                        'Documents en attente',
                        number_format($stats['documents_attente'], 0, ',', ' '),
                        'bi-hourglass-split',
                        '#6f42c1'
                    ],
                    [
                        'Inventaires actifs',
                        number_format($stats['inventaires'], 0, ',', ' '),
                        'bi-clipboard-check',
                        '#20a39e'
                    ],
                ];
            @endphp

            @foreach($indicateurs as [$libelle, $valeur, $icone, $couleur])
                <div class="col-6 col-md-4 col-xl-2">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div class="overflow-hidden">
                                <p class="text-muted mb-2 small">{{ $libelle }}</p>
                                <h5 class="fw-bold mb-0 text-break">{{ $valeur }}</h5>
                            </div>
                            <div class="rounded-circle p-3 ms-2 flex-shrink-0"
                                style="background:{{ $couleur }}18;color:{{ $couleur }}">
                                <i class="bi {{ $icone }} fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>

        {{-- Articles sous le seuil minimum --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">
                    <i class="bi bi-exclamation-triangle text-danger me-2"></i>
                    Alertes de stock
                </h5>
                <span class="badge bg-danger">
                    {{ $stats['sous_seuil'] }} article(s)
                </span>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Référence</th>
                            <th>Désignation</th>
                            <th>Quantité disponible</th>
                            <th>Seuil minimum</th>
                            <th>Unité</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($listeArticlesSousSeuil as $article)
                            <tr>
                                <td class="fw-semibold">{{ $article->reference }}</td>
                                <td>{{ $article->designation }}</td>
                                <td class="text-danger fw-bold">
                                    {{ number_format((float) ($article->quantite ?? 0), 3, ',', ' ') }}
                                </td>
                                <td>
                                    {{ number_format((float) $article->stock_minimum, 3, ',', ' ') }}
                                </td>
                                <td>{{ $article->unite ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    <i class="bi bi-check-circle text-success me-1"></i>
                                    Aucun article sous le seuil minimum.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row g-4">

            {{-- Derniers mouvements --}}
            <div class="col-12 col-xl-7">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0">
                            <i class="bi bi-arrow-left-right me-2"></i>
                            Derniers mouvements
                        </h5>
                        <span class="badge bg-primary">
                            {{ $mouvementsRecents->count() }} opérations
                        </span>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Article</th>
                                    <th>Type</th>
                                    <th>Quantité</th>
                                    <th>Document</th>
                                    <th>Effectué par</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($mouvementsRecents as $mouvement)
                                    @php
                                        $type = strtoupper($mouvement->type_mouvement ?? '');

                                        $couleur = match ($type) {
                                            'ENTREE', 'RECEPTION', 'RETOUR',
                                            'AJUSTEMENT_POSITIF' => 'success',
                                            'SORTIE', 'AJUSTEMENT_NEGATIF' => 'danger',
                                            default => 'secondary',
                                        };
                                    @endphp

                                    <tr>
                                        <td class="text-nowrap">
                                            {{ $mouvement->date_mouvement?->format('d/m/Y H:i') ?? '-' }}
                                        </td>

                                        <td>
                                            <div class="fw-semibold">
                                                {{ $mouvement->article->designation ?? 'Article supprimé' }}
                                            </div>
                                            <small class="text-muted">
                                                {{ $mouvement->article->reference ?? '' }}
                                            </small>
                                        </td>

                                        <td>
                                            <span class="badge bg-{{ $couleur }}">
                                                {{ str_replace('_', ' ', $type) }}
                                            </span>
                                        </td>

                                        <td class="fw-semibold text-nowrap">
                                            {{ number_format((float) $mouvement->quantite, 3, ',', ' ') }}
                                        </td>

                                        <td>
                                            {{ $mouvement->document->reference ?? '-' }}
                                        </td>

                                        <td class="text-nowrap">
                                            @if($mouvement->createur)
                                                {{ trim(($mouvement->createur->firstname ?? '') . ' ' . ($mouvement->createur->name ?? '')) ?: $mouvement->createur->name }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">
                                            Aucun mouvement enregistré.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Derniers documents --}}
            <div class="col-12 col-xl-5">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0">
                            <i class="bi bi-file-earmark-text me-2"></i>
                            Derniers documents
                        </h5>
                        <span class="badge bg-primary">
                            {{ $documentsRecents->count() }} documents
                        </span>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Référence</th>
                                    <th>Type</th>
                                    <th>Statut</th>
                                    <th>Date</th>
                                    <th>Créé par</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($documentsRecents as $document)
                                    @php
                                        $couleursStatut = [
                                            'BROUILLON' => 'secondary',
                                            'EN_ATTENTE_VALIDATION' => 'warning',
                                            'VALIDEE' => 'success',
                                            'REJETEE' => 'danger',
                                        ];

                                        $couleurStatut = $couleursStatut[$document->statut] ?? 'secondary';
                                    @endphp

                                    <tr>
                                        <td class="fw-semibold">
                                            {{ $document->reference }}
                                        </td>

                                        <td>
                                            {{ str_replace('_', ' ', $document->type_document) }}
                                        </td>

                                        <td>
                                            <span class="badge bg-{{ $couleurStatut }}">
                                                {{ str_replace('_', ' ', $document->statut) }}
                                            </span>

                                            @if($document->statut === 'REJETEE' && $document->motif_rejet)
                                                <div class="small text-danger mt-1" title="{{ $document->motif_rejet }}">
                                                    Motif de rejet disponible
                                                </div>
                                            @endif
                                        </td>

                                        <td class="text-nowrap">
                                            {{ $document->date_document?->format('d/m/Y') ?? '-' }}
                                        </td>

                                        <td class="text-nowrap">
                                            @if($document->createur)
                                                {{ trim(($document->createur->firstname ?? '') . ' ' . ($document->createur->name ?? '')) ?: $document->createur->name }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">
                                            Aucun document enregistré.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
