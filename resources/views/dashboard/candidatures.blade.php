@extends('layouts.dashboard')

@section('title', "Vue d'ensemble")

@section('content')
    <div class="dash-topbar">
        <div>
            <h2>Vue d'ensemble</h2>
            <p class="muted-sub">Traitement des candidatures - programme traducteurs Dakar 2026</p>
        </div>
        <div class="topbar-right">
            <div class="search-box">
                <span>🔍</span>
                <input type="text" id="tableSearch" placeholder="Rechercher un candidat…">
            </div>
            <div class="avatar-bubble">{{ Auth::check() ? Str::upper(Str::substr(Auth::user()->name, 0, 2)) : 'FN' }}</div>
        </div>
    </div>

    {{-- Les valeurs ci-dessous sont des exemples statiques : remplacez-les par vos
       variables passées depuis le contrôleur (ex: $kpis, $languageStats, $candidatures) --}}
    {{-- <div class="kpi-grid">
        <div class="kpi-card">
            <div class="kpi-top"><span class="kpi-label">Candidatures totales</span><span class="kpi-dot"
                    style="background:var(--navy)"></span></div>
            <div class="kpi-num">{{ $kpis['total'] ?? 412 }}</div>
            <div class="kpi-delta">+38 cette semaine</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-top"><span class="kpi-label">Validées</span><span class="kpi-dot"
                    style="background:var(--green)"></span></div>
            <div class="kpi-num">{{ $kpis['validees'] ?? 248 }}</div>
            <div class="kpi-delta">60 % du total</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-top"><span class="kpi-label">En attente</span><span class="kpi-dot"
                    style="background:var(--gold)"></span></div>
            <div class="kpi-num">{{ $kpis['attente'] ?? 126 }}</div>
            <div class="kpi-delta" style="color:#8A6416;">À traiter</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-top"><span class="kpi-label">Traducteurs mobilisés</span><span class="kpi-dot"
                    style="background:var(--brick)"></span></div>
            <div class="kpi-num">{{ $kpis['mobilises'] ?? 97 }}</div>
            <div class="kpi-delta" style="color:var(--brick);">Sur 150 requis</div>
        </div>
    </div> --}}

    {{-- <div class="dash-grid">
        <div class="panel">
            <h3>Répartition par langue de spécialisation</h3>
            <p class="panel-sub">Candidatures reçues - 5 langues les plus demandées sur 10</p>
            <div class="bar-chart">
                <div class="bar-col"><span class="bar-val">132</span>
                    <div class="bar" style="height:100%; background:var(--gold);"></div><span
                        class="bar-label">Espagnol</span>
                </div>
                <div class="bar-col"><span class="bar-val">108</span>
                    <div class="bar" style="height:82%; background:var(--brick);"></div><span
                        class="bar-label">Arabe</span>
                </div>
                <div class="bar-col"><span class="bar-val">76</span>
                    <div class="bar" style="height:58%; background:var(--green);"></div><span
                        class="bar-label">Portugais</span>
                </div>
                <div class="bar-col"><span class="bar-val">61</span>
                    <div class="bar" style="height:46%; background:var(--navy);"></div><span
                        class="bar-label">Chinois</span>
                </div>
                <div class="bar-col"><span class="bar-val">54</span>
                    <div class="bar" style="height:41%; background:var(--black);"></div><span
                        class="bar-label">Anglais</span>
                </div>
            </div>
        </div>
        <div class="panel">
            <h3>Statut des candidatures</h3>
            <p class="panel-sub">Sur 412 dossiers reçus</p>
            <div class="donut-wrap">
                <svg width="140" height="140" viewBox="0 0 42 42">
                    <circle cx="21" cy="21" r="15.9" fill="transparent" stroke="var(--gray-200)"
                        stroke-width="6" />
                    <circle cx="21" cy="21" r="15.9" fill="transparent" stroke="var(--green)" stroke-width="6"
                        stroke-dasharray="60 40" stroke-dashoffset="25" transform="rotate(-90 21 21)" />
                    <circle cx="21" cy="21" r="15.9" fill="transparent" stroke="var(--gold)" stroke-width="6"
                        stroke-dasharray="30 70" stroke-dashoffset="-35" transform="rotate(-90 21 21)" />
                    <circle cx="21" cy="21" r="15.9" fill="transparent" stroke="var(--brick)" stroke-width="6"
                        stroke-dasharray="10 90" stroke-dashoffset="-65" transform="rotate(-90 21 21)" />
                </svg>
                <ul class="donut-legend">
                    <li><span class="sw" style="background:var(--green)"></span>Validées - 60 %</li>
                    <li><span class="sw" style="background:var(--gold)"></span>En attente - 30 %</li>
                    <li><span class="sw" style="background:var(--brick)"></span>Rejetées - 10 %</li>
                </ul>
            </div>
        </div>
    </div> --}}

    <div class="panel">
        <div class="table-responsive">
        <h3>Candidatures récentes</h3>
            <table class="data-table table datatables align-middle" id="dataTableCandidature">
                <thead>
                    <tr>
                        <th>Candidat</th>
                        <th>Langue (LV1)</th>
                        <th>Niveau</th>
                        <th>Zone</th>
                        <th>Statut</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($candidatures ?? [] as $c)
                        <tr>
                            <td>
                                <div class="row-name"><span
                                        class="mini-avatar">{{ Str::upper(Str::substr($c?->user?->firstname, 0, 1) . Str::substr($c?->user?->name, 0, 1)) }}</span>{{ $c?->user?->firstname }}
                                    {{ $c?->user?->name }}</div>
                            </td>
                            <td>
                                <div class="lang-tags"><span class="lang-tag">{{ $c->langue_specialisation }}</span><span
                                        class="lang-tag">Français</span></div>
                            </td>
                            <td>{{ $c->niveau }}</td>
                            <td>{{ $c->zone }}</td>
                            <td><span class="status-pill {{ $c->statut }}">{{ ucfirst($c->statut) }}</span></td>
                            <td>{{ $c->created_at->format('d/m/Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">Aucune candidature pour le moment.</td>
                        </tr>
                    @endforelse

                    {{-- <tr>
                    <td>
                        <div class="row-name"><span class="mini-avatar">AD</span>Awa Diop</div>
                    </td>
                    <td>
                        <div class="lang-tags"><span class="lang-tag">Espagnol</span><span
                                class="lang-tag">Français</span></div>
                    </td>
                    <td>C1</td>
                    <td>Diamniadio</td>
                    <td><span class="status-pill valide">Validée</span></td>
                    <td>12/07/2026</td>
                </tr>
                <tr>
                    <td>
                        <div class="row-name"><span class="mini-avatar">MS</span>Moussa Sarr</div>
                    </td>
                    <td>
                        <div class="lang-tags"><span class="lang-tag">Arabe</span><span class="lang-tag">Français</span>
                        </div>
                    </td>
                    <td>C1</td>
                    <td>Saly</td>
                    <td><span class="status-pill attente">En attente</span></td>
                    <td>11/07/2026</td>
                </tr>
                <tr>
                    <td>
                        <div class="row-name"><span class="mini-avatar">FN</span>Fatou Ndiaye</div>
                    </td>
                    <td>
                        <div class="lang-tags"><span class="lang-tag">Portugais</span><span
                                class="lang-tag">Français</span></div>
                    </td>
                    <td>B2</td>
                    <td>Dakar centre</td>
                    <td><span class="status-pill rejete">Rejetée</span></td>
                    <td>10/07/2026</td>
                </tr>
                <tr>
                    <td>
                        <div class="row-name"><span class="mini-avatar">IB</span>Ibrahima Ba</div>
                    </td>
                    <td>
                        <div class="lang-tags"><span class="lang-tag">Anglais (bilingue)</span><span
                                class="lang-tag">Français</span></div>
                    </td>
                    <td>C1</td>
                    <td>Diamniadio</td>
                    <td><span class="status-pill valide">Validée</span></td>
                    <td>09/07/2026</td>
                </tr>
                <tr>
                    <td>
                        <div class="row-name"><span class="mini-avatar">KG</span>Khady Gueye</div>
                    </td>
                    <td>
                        <div class="lang-tags"><span class="lang-tag">Chinois</span><span
                                class="lang-tag">Français</span></div>
                    </td>
                    <td>C1</td>
                    <td>Saly</td>
                    <td><span class="status-pill attente">En attente</span></td>
                    <td>08/07/2026</td>
                </tr> --}}
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        new DataTable('#dataTableCandidature', {
            ordering: false,
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
                },
                "select": {
                    "rows": {
                        _: "%d lignes sÃ©lÃ©ctionnÃ©es",
                        0: "Aucune ligne sÃ©lÃ©ctionnÃ©e",
                        1: "1 ligne sÃ©lÃ©ctionnÃ©e"
                    }
                }
            }
        });
    </script>
@endpush