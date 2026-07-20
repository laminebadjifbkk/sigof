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
        <!-- <div class="avatar-bubble">{{ Auth::check() ? Str::upper(Str::substr(Auth::user()->firstname, 0, 1)).''.Str::upper(Str::substr(Auth::user()->name, 0, 1)) : 'FN' }}</div> -->
    </div>
</div>

{{-- Les valeurs ci-dessous sont des exemples statiques : remplacez-les par vos
       variables passées depuis le contrôleur (ex: $kpis, $languageStats, $candidatures) --}}
<!-- <div class="kpi-grid">
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
</div> -->

<div class="kpi-grid">
    <div class="kpi-card">
        <div class="kpi-top"><span class="kpi-label">Candidatures totales</span><span class="kpi-dot"
                style="background:var(--navy)"></span></div>
        <div class="kpi-num">{{ $kpis['total'] }}</div>
        <div class="kpi-delta">+{{ $kpis['aujourdhui'] }} aujourd'hui</div>
        {{-- <div class="kpi-delta">+{{ $kpis['nouvelles_semaine'] }} cette semaine
    </div> --}}
</div>
<div class="kpi-card">
    <div class="kpi-top"><span class="kpi-label">Validées</span><span class="kpi-dot"
            style="background:var(--green)"></span></div>
    <div class="kpi-num">{{ $kpis['validees'] }}</div>
    <div class="kpi-delta">{{ $kpis['total'] > 0 ? round(($kpis['validees'] / $kpis['total']) * 100) : 0 }} % du total</div>
</div>
<div class="kpi-card">
    <div class="kpi-top"><span class="kpi-label">En attente</span><span class="kpi-dot"
            style="background:var(--gold)"></span></div>
    <div class="kpi-num">{{ $kpis['attente'] }}</div>
    <!-- <div class="kpi-delta" style="color:#8A6416;">À traiter</div> -->
    <div class="kpi-delta" style="color:#8A6416;">{{ $kpis['total'] > 0 ? round(($kpis['attente'] / $kpis['total']) * 100) : 0 }} % à traiter du total</div>
</div>
<div class="kpi-card">
    <div class="kpi-top"><span class="kpi-label">Rejetées</span><span class="kpi-dot"
            style="background:var(--brick)"></span></div>
    <div class="kpi-num">{{ $kpis['rejete'] }}</div>
    <div class="kpi-delta" style="color:var(--brick);">{{ $kpis['total'] > 0 ? round(($kpis['rejete'] / $kpis['total']) * 100) : 0 }} % du total</div>
</div>
<!-- <div class="kpi-card">
        <div class="kpi-top"><span class="kpi-label">Traducteurs mobilisés</span><span class="kpi-dot"
                style="background:var(--brick)"></span></div>
        <div class="kpi-num">{{ $kpis['mobilises'] }}</div>
        <div class="kpi-delta" style="color:var(--brick);">Sur {{ config('candidatures.objectif_traducteurs', 40) }} requis</div>
    </div> -->
</div>

<div class="dash-grid">
    <!-- <div class="panel">
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
    </div> -->
    <div class="panel">
        <h3>Répartition par langue de spécialisation</h3>
        <p class="panel-sub">Candidatures reçues - top {{ $languageStats->count() }} langues sur {{ \App\Models\LanguesSpecialisation::count() }}</p>
        <div class="bar-chart">
            @php
            $colors = ['var(--gold)', 'var(--brick)', 'var(--green)', 'var(--navy)', 'var(--black)'];
            @endphp
            @forelse ($languageStats as $index => $langue)
            <div class="bar-col">
                <span class="bar-val">{{ $langue['total'] }}</span>
                <div class="bar" style="height:{{ round(($langue['total'] / $maxLangueTotal) * 100) }}%; background:{{ $colors[$index] ?? 'var(--gray-400)' }};"></div>
                <span class="bar-label">{{ $langue['nom'] }}</span>
            </div>
            @empty
            <p class="muted-sub">Aucune candidature enregistrée pour le moment.</p>
            @endforelse
        </div>
    </div>

    <!-- <div class="panel">
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
    </div> -->
    <div class="panel">
        <h3>Statut des candidatures</h3>
        <p class="panel-sub">Sur {{ $kpis['total'] }} dossiers reçus</p>
        <div class="donut-wrap">
            @php
            // Conversion pourcentage -> longueur d'arc sur circonférence 100 (rayon 15.9 en SVG normalisé)
            $valideesLen = $statutStats['validees_pct'];
            $attenteLen = $statutStats['attente_pct'];
            $rejeteesLen = $statutStats['rejetees_pct'];

            $offsetAttente = 100 - $valideesLen;
            $offsetRejetees = 100 - $valideesLen - $attenteLen;
            @endphp
            <svg width="140" height="140" viewBox="0 0 42 42">
                <circle cx="21" cy="21" r="15.9" fill="transparent" stroke="var(--gray-200)" stroke-width="6" />
                <circle cx="21" cy="21" r="15.9" fill="transparent" stroke="var(--green)" stroke-width="6"
                    stroke-dasharray="{{ $valideesLen }} {{ 100 - $valideesLen }}" stroke-dashoffset="25"
                    transform="rotate(-90 21 21)" />
                <circle cx="21" cy="21" r="15.9" fill="transparent" stroke="var(--gold)" stroke-width="6"
                    stroke-dasharray="{{ $attenteLen }} {{ 100 - $attenteLen }}" stroke-dashoffset="{{ 25 - $valideesLen }}"
                    transform="rotate(-90 21 21)" />
                <circle cx="21" cy="21" r="15.9" fill="transparent" stroke="var(--brick)" stroke-width="6"
                    stroke-dasharray="{{ $rejeteesLen }} {{ 100 - $rejeteesLen }}" stroke-dashoffset="{{ 25 - $valideesLen - $attenteLen }}"
                    transform="rotate(-90 21 21)" />
            </svg>
            <ul class="donut-legend">
                <li><span class="sw" style="background:var(--green)"></span>Validées - {{ $statutStats['validees_pct'] }} %</li>
                <li><span class="sw" style="background:var(--gold)"></span>En attente - {{ $statutStats['attente_pct'] }} %</li>
                <li><span class="sw" style="background:var(--brick)"></span>Rejetées - {{ $statutStats['rejetees_pct'] }} %</li>
            </ul>
        </div>
    </div>
</div>

<div class="panel">
    <div class="table-responsive">
        <h3>Liste des candidatures</h3>
        <table class="data-table table datatables align-middle" id="dataTableCandidature">
            <thead>
                <tr>
                    <th>Candidat</th>
                    <th>Langue (LV1)</th>
                    <th>Niveau</th>
                    <!-- <th>Zone</th> -->
                    <th>Statut</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($candidatures ?? [] as $c)
                <tr>
                    <td>
                        <div class="row-name"><span
                                class="mini-avatar">{{ Str::upper(Str::substr($c?->user?->firstname, 0, 1) . Str::substr($c?->user?->name, 0, 1)) }}</span>
                                {{ $c?->user?->civilite }}
                                {{ $c?->user?->firstname }}
                                {{ $c?->user?->firstname }}
                            {{ $c?->user?->name }}
                        </div>
                    </td>
                    <td>
                        <div class="lang-tags">
                            <span class="lang-tag">{{ $c->langueSpecialisation->nom }}</span>
                            <span class="lang-tag">Français</span>
                        </div>
                    </td>
                    <td>{{ $c->niveau_francais }}</td>
                    <!-- <td>{{ $c->zone_label }}</td> -->
                    <td>
                        <!-- <span class="status-pill {{ $c->statut }}">{{ ucfirst($c->statut) }}</span> -->
                        <span class="status-pill {{ $c->statut_classe }}">{{ $c->statut_label }}</span>
                    </td>
                    <td>
                        <span title="{{ $c?->created_at?->format('d/m/Y à H:i') }}">
                            {{ $c?->created_at?->diffForHumans() }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('candidatures.show', $c) }}" class="btn btn-sm btn-outline">
                            Voir
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">Aucune candidature pour le moment.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection