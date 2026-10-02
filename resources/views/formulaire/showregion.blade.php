@extends('layout.user-layout')
@section('title', $region . ' ' . $annee . ' | liste des demandes prises en charge')
@section('space-work')
    @can('inscriptioncontact-view')
        @php
            $total = $grouperegions->sum(fn($items) => $items->count());
            $statuts = $grouperegions->sortByDesc(fn($items) => $items->count());

            // Même palette que les autres pages
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
            .rg-top {
                display: flex;
                flex-wrap: wrap;
                justify-content: space-between;
                align-items: flex-end;
                gap: 12px;
                margin-bottom: 20px;
            }

            .rg-top h3 {
                margin: 4px 0 0;
                font-weight: 600;
                color: #012970;
            }

            .rg-top h3 small {
                font-size: .6em;
                font-weight: 600;
                color: #198754;
                background: #d1e7dd;
                padding: 3px 10px;
                border-radius: 999px;
                vertical-align: middle;
                margin-left: 6px;
            }

            .rg-back {
                font-size: .88rem;
                text-decoration: none;
            }

            .rg-total {
                background: #fff;
                border: 1px solid #e3e8ef;
                border-radius: 10px;
                padding: 10px 18px;
                text-align: right;
                line-height: 1.1;
            }

            .rg-total strong {
                display: block;
                font-size: 1.7rem;
                font-weight: 600;
                color: #012970;
            }

            .rg-total span {
                font-size: .8rem;
                color: #6b7785;
            }

            .rg-title {
                font-size: 1rem;
                font-weight: 600;
                color: #012970;
                margin: 8px 0 12px;
            }

            .rg-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
                gap: 14px;
                margin-bottom: 28px;
            }

            .rg-card {
                background: #fff;
                border: 1px solid #e3e8ef;
                border-left: 5px solid var(--c);
                border-radius: 10px;
                padding: 14px 16px;
                display: flex;
                flex-direction: column;
            }

            .rg-card h5 {
                margin: 0 0 8px;
                font-size: .95rem;
                font-weight: 600;
                color: #1c2b3a;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }

            .rg-card .nb {
                font-size: 1.8rem;
                font-weight: 600;
                line-height: 1;
                color: #1c2b3a;
            }

            .rg-card .sub {
                font-size: .78rem;
                color: #6b7785;
                margin: 4px 0 10px;
            }

            .rg-meter {
                height: 6px;
                background: #edf0f4;
                border-radius: 999px;
                overflow: hidden;
                margin-bottom: 12px;
            }

            .rg-meter i {
                display: block;
                height: 100%;
                background: var(--c);
            }

            .rg-actions {
                display: flex;
                gap: 8px;
                margin-top: auto;
            }

            .rg-actions .btn {
                flex: 1;
                font-size: .82rem;
                white-space: nowrap;
            }

            .rg-table-wrap {
                background: #fff;
                border: 1px solid #e3e8ef;
                border-radius: 10px;
                padding: 16px;
            }

            .rg-table-wrap thead th {
                font-size: .82rem;
                text-transform: uppercase;
                letter-spacing: .03em;
                color: #6b7785;
            }
        </style>

        <section class="section">
            <div class="rg-top">
                <div>
                    <a href="{{ route('formulaires.annee', $annee) }}" class="rg-back">
                        <i class="bi bi-arrow-left"></i> Retour à l'année {{ $annee }}
                    </a>
                    <h3>Région de {{ $region }} <small>{{ $annee }}</small></h3>
                </div>
                <div class="rg-total">
                    <strong>{{ $formulaireCount }}</strong>
                    <span>demande(s) dans cette région</span>
                </div>
            </div>

            {{-- Par statut --}}
            <h4 class="rg-title">Répartition par statut</h4>
            <div class="rg-grid">
                @foreach ($statuts as $statut => $items)
                    @php $pct = $total > 0 ? round(($items->count() / $total) * 100, 1) : 0; @endphp
                    <div class="rg-card" style="--c: {{ $couleur($statut) }};">
                        <h5 title="{{ $statut }}">{{ $statut }}</h5>
                        <div class="nb">{{ number_format($items->count(), 0, '', ' ') }}</div>
                        <div class="sub">demandeur(s) · {{ $pct }}%</div>
                        <div class="rg-meter" role="img" aria-label="{{ $pct }}%">
                            <i style="width: {{ $pct }}%"></i>
                        </div>
                        <div class="rg-actions">
                            <a href="{{ route('prisencharge.parStatut', ['statut' => $statut, 'region' => $region, 'annee' => $annee]) }}"
                                class="btn btn-outline-primary btn-sm">
                                Voir plus <i class="bi bi-arrow-right-short"></i>
                            </a>
                            @can('exporter-view')
                                <a href="{{ route('prisencharge.excel', ['statut' => $statut, 'region' => $region, 'annee' => $annee]) }}"
                                    class="btn btn-outline-success btn-sm" title="Exporter la liste">
                                    <i class="bi bi-file-earmark-excel"></i> Excel
                                </a>
                            @endcan
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Par diplôme visé --}}
            <h4 class="rg-title">Répartition par diplôme visé</h4>
            <div class="rg-table-wrap">
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="table-inscriptions">
                        <thead>
                            <tr>
                                <th width="5%" class="text-center">N°</th>
                                <th>Diplôme visé</th>
                                <th width="8%" class="text-center">Effectif</th>
                                <th width="8%" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($groupes as $diplome_vise => $items)
                                <tr>
                                    <td class="text-center text-muted">{{ $loop->iteration }}</td>
                                    <td class="fw-semibold">{{ $diplome_vise }}</td>
                                    <td class="text-center">{{ $items->count() }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('formulaires.showregiondiplome', ['region' => $region, 'diplome_vise' => $diplome_vise, 'annee' => $annee]) }}"
                                            class="btn btn-warning btn-sm" title="Voir les détails" target="_blank">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Modal générer un rapport --}}
            @php
                $champsRecherche = [
                    ['prenom', 'Prénom', 'text', 'prenom', 'Prénom', ''],
                    ['nom', 'Nom', 'text', 'nom', 'Nom', ''],
                    ['cin', 'N° CIN', 'text', 'cin2', 'Ex: 1099200500012', 'minlength=9 maxlength=14 autocomplete=off'],
                    ['telephone', 'Téléphone', 'text', 'telephone_responsable', 'Téléphone', 'maxlength=12 autocomplete=tel'],
                    ['email', 'Email', 'email', 'email', 'email@email.com', ''],
                    ['lieu_naissance', 'Lieu naissance', 'text', 'lieu_naissance', 'Lieu de naissance', ''],
                ];
            @endphp
            <div class="modal fade" id="generate_rapport" tabindex="-1" role="dialog"
                aria-labelledby="generate_rapportLabel" aria-hidden="true">
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
                                                <label for="{{ $id }}" class="form-label">{{ $label }}</label>
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
@push('scripts')
    <script>
        new DataTable('#table-inscriptions', {
            ordering: true,
            order: [
                [2, 'desc']
            ],
            layout: {
                topStart: {
                    buttons: ['csv', 'excel', 'print'],
                }
            },
            language: {
                "sProcessing": "Traitement en cours...",
                "sSearch": "Rechercher&nbsp;:",
                "sLengthMenu": "Afficher _MENU_ &eacute;l&eacute;ments",
                "sInfo": "Affichage de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
                "sInfoEmpty": "Affichage de l'&eacute;l&eacute;ment 0 &agrave; 0 sur 0 &eacute;l&eacute;ment",
                "sInfoFiltered": "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
                "sInfoPostFix": "",
                "sLoadingRecords": "Chargement en cours...",
                "sZeroRecords": "Aucun &eacute;l&eacute;ment &agrave; afficher",
                "sEmptyTable": "Aucune donn&eacute;e disponible dans le tableau",
                "oPaginate": {
                    "sFirst": "Premier",
                    "sPrevious": "Pr&eacute;c&eacute;dent",
                    "sNext": "Suivant",
                    "sLast": "Dernier"
                },
                "oAria": {
                    "sSortAscending": ": activer pour trier la colonne par ordre croissant",
                    "sSortDescending": ": activer pour trier la colonne par ordre d&eacute;croissant"
                }
            }
        });
    </script>
@endpush
