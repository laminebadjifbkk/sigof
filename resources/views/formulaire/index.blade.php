@extends('layout.user-layout')
@section('title', 'ONFP | PRISES EN CHARGE PAR ANNÉE SCOLAIRE')
@section('space-work')
    @can('inscriptioncontact-view')
        @php
            $totalGlobal = $annees->sum('total');
            $anneeRecente = $annees->keys()->first(fn($k) => $k !== 'Non définie');
            $nbAnnees = $annees->keys()->reject(fn($k) => $k === 'Non définie')->count();

            // Couleur par statut (insensible à la casse)
            $couleur = function ($statut) {
                $s = mb_strtolower($statut);
                return match (true) {
                    str_contains($s, 'non conforme'), str_contains($s, 'rejet'), str_contains($s, 'refus') => '#dc3545',
                    str_contains($s, 'non défini') => '#adb5bd',
                    str_contains($s, 'nouvelle') => '#0d6efd',
                    str_contains($s, 'conforme') => '#0f9b7a',
                    str_contains($s, 'sélectionn') => '#198754',
                    str_contains($s, 'valid') => '#146c43',
                    str_contains($s, 'attente') => '#fd7e14',
                    default => '#6c757d',
                };
            };
        @endphp

        <style>
            .ann-stats {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 16px;
                margin-bottom: 24px;
            }

            .ann-stat {
                background: #fff;
                border: 1px solid #e3e8ef;
                border-radius: 10px;
                padding: 16px 18px;
                display: flex;
                align-items: center;
                gap: 14px;
            }

            .ann-stat-icon {
                width: 44px;
                height: 44px;
                border-radius: 10px;
                display: grid;
                place-items: center;
                font-size: 1.3rem;
                background: #e8f0fe;
                color: #0d6efd;
                flex: none;
            }

            .ann-stat-value {
                font-size: 1.5rem;
                font-weight: 600;
                line-height: 1.1;
                color: #012970;
            }

            .ann-stat-label {
                font-size: .85rem;
                color: #6b7785;
            }

            .ann-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
                gap: 20px;
            }

            .ann-card {
                background: #fff;
                border: 1px solid #e3e8ef;
                border-top: 4px solid #0d6efd;
                border-radius: 10px;
                padding: 20px;
                display: flex;
                flex-direction: column;
            }

            .ann-card--recente {
                border-top-color: #198754;
                box-shadow: 0 4px 18px rgba(1, 41, 112, .08);
            }

            .ann-card--alerte {
                border-top-color: #fd7e14;
                background: #fffaf3;
            }

            .ann-head {
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
                gap: 12px;
            }

            .ann-title {
                margin: 0;
                font-size: 1.35rem;
                font-weight: 600;
                color: #012970;
            }

            .ann-tag {
                display: inline-block;
                margin-top: 6px;
                padding: 2px 10px;
                border-radius: 999px;
                font-size: .75rem;
                font-weight: 600;
                background: #d1e7dd;
                color: #0a3622;
            }

            .ann-tag--alerte {
                background: #ffe5c7;
                color: #7a3e00;
            }

            .ann-total {
                text-align: right;
                line-height: 1;
            }

            .ann-total strong {
                display: block;
                font-size: 2.2rem;
                font-weight: 600;
                color: #1c2b3a;
            }

            .ann-total span {
                font-size: .8rem;
                color: #6b7785;
            }

            .ann-bar {
                display: flex;
                height: 10px;
                border-radius: 999px;
                overflow: hidden;
                background: #edf0f4;
                margin: 18px 0 14px;
            }

            .ann-bar i {
                display: block;
                height: 100%;
            }

            .ann-legend {
                list-style: none;
                margin: 0 0 18px;
                padding: 0;
                font-size: .92rem;
            }

            .ann-legend li {
                display: flex;
                align-items: center;
                gap: 10px;
                padding: 5px 0;
            }

            .ann-dot {
                width: 10px;
                height: 10px;
                border-radius: 50%;
                flex: none;
            }

            .ann-legend .nom {
                flex: 1;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }

            .ann-legend .nb {
                font-weight: 600;
            }

            .ann-legend .pct {
                width: 44px;
                text-align: right;
                color: #6b7785;
                font-size: .82rem;
            }

            .ann-card .btn {
                margin-top: auto;
            }
        </style>

        <section class="section">
            <div class="d-flex flex-wrap justify-content-between align-items-end mb-4 gap-2">
                <div>
                    <h3 class="mb-1" style="color:#012970;font-weight:600;">Prises en charge</h3>
                    <p class="text-muted mb-0">Choisissez une année scolaire pour consulter ses demandes.</p>
                </div>
            </div>

            {{-- Chiffres clés --}}
            <div class="ann-stats">
                <div class="ann-stat">
                    <div class="ann-stat-icon"><i class="bi bi-people"></i></div>
                    <div>
                        <div class="ann-stat-value">{{ $totalFormulaires }}</div>
                        <div class="ann-stat-label">demandes au total</div>
                    </div>
                </div>
                <div class="ann-stat">
                    <div class="ann-stat-icon"><i class="bi bi-calendar3"></i></div>
                    <div>
                        <div class="ann-stat-value">{{ $nbAnnees }}</div>
                        <div class="ann-stat-label">année(s) scolaire(s)</div>
                    </div>
                </div>
                @if ($anneeRecente)
                    <div class="ann-stat">
                        <div class="ann-stat-icon" style="background:#d1e7dd;color:#198754;"><i class="bi bi-graph-up"></i>
                        </div>
                        <div>
                            <div class="ann-stat-value">{{ number_format($annees[$anneeRecente]['total'], 0, '', ' ') }}</div>
                            <div class="ann-stat-label">demandes en {{ $anneeRecente }}</div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Cartes par année --}}
            <div class="ann-grid">
                @forelse ($annees as $annee => $data)
                    @php
                        $nonDefinie = $annee === 'Non définie';
                        $recente = $annee === $anneeRecente;
                        $part = $totalGlobal > 0 ? round(($data['total'] / $totalGlobal) * 100) : 0;
                    @endphp
                    <article class="ann-card {{ $nonDefinie ? 'ann-card--alerte' : ($recente ? 'ann-card--recente' : '') }}">
                        <div class="ann-head">
                            <div>
                                <h5 class="ann-title">{{ $annee }}</h5>
                                @if ($recente)
                                    <span class="ann-tag">Année récente</span>
                                @elseif ($nonDefinie)
                                    <span class="ann-tag ann-tag--alerte">Année à renseigner</span>
                                @endif
                            </div>
                            <div class="ann-total">
                                <strong>{{ number_format($data['total'], 0, '', ' ') }}</strong>
                                <span>demandeur(s) · {{ $part }}% du total</span>
                            </div>
                        </div>

                        {{-- Barre de répartition par statut --}}
                        <div class="ann-bar" role="img" aria-label="Répartition des statuts pour {{ $annee }}">
                            @foreach ($data['statuts'] as $statut => $nb)
                                <i style="width: {{ ($nb / $data['total']) * 100 }}%; background: {{ $couleur($statut) }};"
                                    title="{{ $statut }} : {{ $nb }}"></i>
                            @endforeach
                        </div>

                        <ul class="ann-legend">
                            @foreach ($data['statuts'] as $statut => $nb)
                                <li>
                                    <span class="ann-dot" style="background: {{ $couleur($statut) }};"></span>
                                    <span class="nom" title="{{ $statut }}">{{ $statut }}</span>
                                    <span class="nb">{{ number_format($nb, 0, '', ' ') }}</span>
                                    <span class="pct">{{ round(($nb / $data['total']) * 100) }}%</span>
                                </li>
                            @endforeach
                        </ul>

                        <a href="{{ route('formulaires.annee', $annee) }}"
                            class="btn {{ $recente ? 'btn-success' : 'btn-primary' }} btn-sm">
                            Voir les demandes <i class="bi bi-arrow-right-short"></i>
                        </a>
                    </article>
                @empty
                    <div class="alert alert-info">Aucune demande enregistrée pour le moment.</div>
                @endforelse
            </div>
        </section>
    @endcan
@endsection
