@extends('layout.user-layout')
@section('title', 'ONFP | DEMANDES ' . $annee)
@section('space-work')
    @can('inscriptioncontact-view')
        @php
            $total = collect($statutPourcentages)->sum('count');
            $statuts = collect($statutPourcentages)->sortByDesc('count');
            $regions = collect($groupes)->sortDesc();

            // Même palette que la page des années
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
            .an-top {
                display: flex;
                flex-wrap: wrap;
                justify-content: space-between;
                align-items: flex-end;
                gap: 12px;
                margin-bottom: 20px;
            }

            .an-top h3 {
                margin: 4px 0 0;
                font-weight: 600;
                color: #012970;
            }

            .an-back {
                font-size: .88rem;
                text-decoration: none;
            }

            .an-total {
                background: #fff;
                border: 1px solid #e3e8ef;
                border-radius: 10px;
                padding: 10px 18px;
                text-align: right;
                line-height: 1.1;
            }

            .an-total strong {
                display: block;
                font-size: 1.7rem;
                font-weight: 600;
                color: #012970;
            }

            .an-total span {
                font-size: .8rem;
                color: #6b7785;
            }

            .an-section-title {
                font-size: 1rem;
                font-weight: 600;
                color: #012970;
                margin: 8px 0 12px;
            }

            .an-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(190px, 1fr));
                gap: 14px;
                margin-bottom: 28px;
            }

            .an-card {
                background: #fff;
                border: 1px solid #e3e8ef;
                border-left: 5px solid var(--c);
                border-radius: 10px;
                padding: 14px 16px;
                display: flex;
                flex-direction: column;
            }

            .an-card h5 {
                margin: 0 0 8px;
                font-size: .95rem;
                font-weight: 600;
                color: #1c2b3a;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }

            .an-card .nb {
                font-size: 1.8rem;
                font-weight: 600;
                line-height: 1;
                color: #1c2b3a;
            }

            .an-card .sub {
                font-size: .78rem;
                color: #6b7785;
                margin: 4px 0 10px;
            }

            .an-meter {
                height: 6px;
                background: #edf0f4;
                border-radius: 999px;
                overflow: hidden;
                margin-bottom: 12px;
            }

            .an-meter i {
                display: block;
                height: 100%;
                background: var(--c);
            }

            .an-card .btn {
                margin-top: auto;
                font-size: .85rem;
            }

            .an-table-wrap {
                background: #fff;
                border: 1px solid #e3e8ef;
                border-radius: 10px;
                overflow: hidden;
            }

            .an-table-wrap table {
                margin: 0;
            }

            .an-table-wrap thead th {
                font-size: .82rem;
                text-transform: uppercase;
                letter-spacing: .03em;
                color: #6b7785;
                background: #f6f9ff;
                border-bottom: 1px solid #e3e8ef;
            }

            .an-part {
                display: flex;
                align-items: center;
                gap: 10px;
                min-width: 160px;
            }

            .an-part .an-meter {
                flex: 1;
                margin: 0;
            }

            .an-part small {
                width: 42px;
                text-align: right;
                color: #6b7785;
            }
        </style>

        <section class="section">
            <div class="an-top">
                <div>
                    <a href="{{ route('formulaires.index') }}" class="an-back">
                        <i class="bi bi-arrow-left"></i> Toutes les années scolaires
                    </a>
                    <h3>Prises en charge {{ $annee }}</h3>
                </div>
                <div class="an-total">
                    <strong>{{ $totalFormulaires }}</strong>
                    <span>demande(s) cette année</span>
                </div>
            </div>

            {{-- Par statut --}}
            <h4 class="an-section-title">Répartition par statut</h4>
            <div class="an-grid">
                {{-- @foreach ($statuts as $statut => $items)
                    @php
                        $pct = $statutPourcentages[$statut]['percent'];
                    @endphp
                    <div class="an-card" style="--c: {{ $couleur($statut) }};">
                        <h5 title="{{ $statut }}">{{ $statut }}</h5>
                        <div class="nb">{{ number_format($items->count(), 0, '', ' ') }}</div>
                        <div class="sub">demandeur(s) · {{ $pct }}%</div>
                        <div class="an-meter" role="img" aria-label="{{ $pct }}%">
                            <i style="width: {{ $pct }}%"></i> --}}
                @foreach ($statuts as $statut => $data)
                    @php $pct = $data['percent']; @endphp
                    <div class="an-card" style="--c: {{ $couleur($statut) }};">
                        <h5 title="{{ $statut }}">{{ $statut }}</h5>
                        <div class="nb">{{ number_format($data['count'], 0, '', ' ') }}</div>
                        <div class="sub">demandeur(s) · {{ $pct }}%</div>
                    </div>
                    <a href="{{ route('formulaires.showstatut', ['statut' => $statut, 'annee_scolaire' => $annee]) }}"
                        class="btn btn-outline-primary btn-sm">
                        Voir plus <i class="bi bi-arrow-right-short"></i>
                    </a>
            </div>
            @endforeach
            </div>

            {{-- Par région --}}
            <h4 class="an-section-title">Répartition par région</h4>
            <div class="an-table-wrap table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th style="width: 60px;" class="text-center">N°</th>
                            <th>Région</th>
                            <th class="text-end">Effectif</th>
                            <th>Part</th>
                            <th style="width: 80px;" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- @foreach ($regions as $region => $items)
                            @php $part = $total > 0 ? round(($items->count() / $total) * 100, 1) : 0; @endphp
                            <tr>
                                <td class="text-center text-muted">{{ $loop->iteration }}</td>
                                <td class="fw-semibold">{{ $region }}</td>
                                <td class="text-end">{{ number_format($items->count(), 0, '', ' ') }}</td> --}}
                        @foreach ($regions as $region => $count)
                            @php $part = $total > 0 ? round(($count / $total) * 100, 1) : 0; @endphp
                            <tr>
                                <td class="text-center text-muted">{{ $loop->iteration }}</td>
                                <td class="fw-semibold">{{ $region }}</td>
                                <td class="text-end">{{ number_format($count, 0, '', ' ') }}</td>
                                <td>
                                    <div class="an-part" style="--c: #0d6efd;">
                                        <div class="an-meter"><i style="width: {{ $part }}%"></i></div>
                                        <small>{{ $part }}%</small>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('formulaires.showregion', ['region' => $region, 'annee' => $annee]) }}"
                                        class="btn btn-warning btn-sm" title="Voir les détails de {{ $region }}">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Modal générer un rapport (inchangé) --}}
            @php
                $champsRecherche = [
                    ['prenom', 'Prénom', 'text', 'prenom', 'Prénom', ''],
                    ['nom', 'Nom', 'text', 'nom', 'Nom', ''],
                    ['cin', 'N° CIN', 'text', 'cin2', 'Ex: 1099200500012', 'minlength=9 maxlength=14 autocomplete=off'],
                    [
                        'telephone',
                        'Téléphone',
                        'text',
                        'telephone_responsable',
                        'Téléphone',
                        'maxlength=12 autocomplete=tel',
                    ],
                    ['email', 'Email', 'email', 'email', 'email@email.com', ''],
                    ['lieu_naissance', 'Lieu naissance', 'text', 'lieu_naissance', 'Lieu de naissance', ''],
                ];
            @endphp
            <div class="modal fade" id="generate_rapport" tabindex="-1" role="dialog" aria-labelledby="generate_rapportLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Générer une recherche<span class="text-danger mx-1">*</span></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form method="post" action="{{ route('formulaires.report') }}">
                            @csrf
                            <div class="modal-body">
                                <div class="row g-3">
                                    @foreach ($champsRecherche as [$name, $label, $type, $id, $placeholder, $extra])
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="{{ $id }}"
                                                    class="form-label">{{ $label }}</label>
                                                <input type="{{ $type }}" name="{{ $name }}"
                                                    id="{{ $id }}" value="{{ old($name) }}"
                                                    placeholder="{{ $placeholder }}"
                                                    class="form-control form-control-sm @error($name) is-invalid @enderror"
                                                    {!! $extra !!}>
                                                @error($name)
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Fermer</button>
                                <button type="submit"
                                    class="btn btn-primary btn-block submit_rapport btn-sm">Rechercher</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    @endcan
@endsection
