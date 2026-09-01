@extends('layouts.dashboard')

@section('title', 'Rapports')

@section('content')
    <div class="dash-topbar">
        <div>
            <h2>Rapports</h2>
            <p class="muted-sub">Suivi statistique - programme traducteurs Dakar 2026</p>
        </div>
        <div class="topbar-right">
            <a href="{{ route('rapports.export') }}" class="btn btn-outline btn-sm">Exporter (CSV)</a>
        </div>
    </div>

    <div class="panel">
        <h3>Filtrer l'export</h3>
        <form action="{{ route('rapports.export') }}" method="GET" class="export-filters">
            <div class="field">
                <label for="statut">Statut</label>
                <select name="statut" id="statut">
                    <option value="">Tous</option>
                    <option value="nouvelle">Nouvelle</option>
                    <option value="en_attente">En attente</option>
                    <option value="validee">Validée</option>
                    <option value="rejetee">Rejetée</option>
                    <option value="conforme">Conforme</option>
                    <option value="non_conforme">Non conforme</option>
                    <option value="forme">Formé</option>
                </select>
            </div>

            <div class="field">
                <label for="langue_specialisation_id">Langue</label>
                <select name="langue_specialisation_id" id="langue_specialisation_id">
                    <option value="">Toutes</option>
                    @foreach ($parLangue as $langue)
                        <option value="{{ $langue->id }}">{{ $langue->nom }}</option>
                    @endforeach
                </select>
            </div>

            <div class="field">
                <label for="zone">Zone</label>
                <select name="zone" id="zone">
                    <option value="">Toutes</option>
                    <option value="diamniadio">Diamniadio Olympic Stadium</option>
                    <option value="dakar_centre">Dakar centre</option>
                    <option value="saly">Saly - Petite Côte</option>
                    <option value="indifferent">Indifférent</option>
                </select>
            </div>

            <div class="field">
                <label for="date_debut">Du</label>
                <input type="date" name="date_debut" id="date_debut">
            </div>

            <div class="field">
                <label for="date_fin">Au</label>
                <input type="date" name="date_fin" id="date_fin">
            </div>

            <div class="field export-submit">
                <button type="submit" class="btn btn-primary btn-sm">Télécharger le CSV</button>
            </div>
        </form>
    </div>

    <!-- KPIs -->
    <div class="kpi-grid pt-3">
        <div class="kpi-card">
            <span class="kpi-label">Candidatures reçues</span>
            <span class="kpi-value">{{ $totalCandidatures }}</span>
            <span class="kpi-sub">{{ $candidaturesRecentes }} sur les 30 derniers jours</span>
        </div>
        <div class="kpi-card">
            <span class="kpi-label">Validées</span>
            <span class="kpi-value kpi-success">{{ $totalValidees }}</span>
            <span class="kpi-sub">Taux de validation : {{ $tauxValidation }}%</span>
        </div>
        <div class="kpi-card">
            <span class="kpi-label">En attente</span>
            <span class="kpi-value kpi-warning">{{ $totalEnAttente }}</span>
            <span class="kpi-sub">À traiter</span>
        </div>
        <div class="kpi-card">
            <span class="kpi-label">Rejetées</span>
            <span class="kpi-value kpi-danger">{{ $totalRejetees }}</span>
            <span class="kpi-sub">Non conformes</span>
        </div>
    </div>

    <div class="rapport-row">
        <!-- Répartition par statut -->
        <div class="panel rapport-col">
            <h3>Candidatures par statut</h3>
            <canvas id="chartStatut" height="220"></canvas>
        </div>

        <!-- Répartition par zone -->
        <div class="panel rapport-col">
            <h3>Candidatures par zone</h3>
            <canvas id="chartZone" height="220"></canvas>
        </div>
    </div>

    <!-- Répartition par langue -->
    <div class="panel">
        <h3>Candidatures par langue</h3>
        <div class="table-responsive">
            <table class="data-table table align-middle">
                <thead>
                    <tr>
                        <th>Langue</th>
                        <th>Postes disponibles</th>
                        <th>Candidatures reçues</th>
                        <th>Tension</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($parLangue as $langue)
                        @php
                            $tension =
                                $langue->postes_disponibles > 0
                                    ? round($langue->candidatures_count / $langue->postes_disponibles, 1)
                                    : 0;
                        @endphp
                        <tr>
                            <td>{{ $langue->nom }}</td>
                            <td>{{ $langue->postes_disponibles }}</td>
                            <td>{{ $langue->candidatures_count }}</td>
                            <td>
                                <span
                                    class="status-pill {{ $tension >= 3 ? 'status-rejetee' : ($tension >= 1 ? 'status-en-attente' : 'status-inconnu') }}">
                                    {{ $tension }}x
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="empty-row">Aucune donnée disponible.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Suivi des formations -->
    <div class="rapport-row">
        <div class="panel rapport-col">
            <h3>Statut des formations</h3>
            <canvas id="chartFormation" height="220"></canvas>
        </div>

        <div class="panel rapport-col">
            <h3>Résultats des évaluations</h3>
            <div class="kpi-grid kpi-grid-compact">
                <div class="kpi-card">
                    <span class="kpi-label">Réussis</span>
                    <span class="kpi-value kpi-success">{{ $totalReussis }}</span>
                </div>
                <div class="kpi-card">
                    <span class="kpi-label">Rattrapage</span>
                    <span class="kpi-value kpi-warning">{{ $totalRattrapage }}</span>
                </div>
                <div class="kpi-card">
                    <span class="kpi-label">Échoués</span>
                    <span class="kpi-value kpi-danger">{{ $totalEchoues }}</span>
                </div>
                <div class="kpi-card">
                    <span class="kpi-label">Total évalués</span>
                    <span class="kpi-value">{{ $totalFormations }}</span>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('scripts')
    <script>
        window.rapportData = {
            parStatut: @json($parStatut),
            parZone: @json($parZone),
            parStatutFormation: @json($parStatutFormation),
        };
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof Chart === 'undefined') {
                console.error('Chart.js ne s\'est pas chargé — vérifie le CDN ou la connexion internet.');
                return;
            }

            const data = window.rapportData;


            const palette = ['#2563eb', '#16a34a', '#d97706', '#dc2626', '#7c3aed', '#0891b2'];

            function renderChart(canvasId, type, labels, values, options = {}) {
                const el = document.getElementById(canvasId);
                if (!el) {
                    console.error(`Canvas #${canvasId} introuvable dans le DOM.`);
                    return;
                }
                if (!labels.length) {
                    el.closest('.panel').insertAdjacentHTML('beforeend',
                        '<p class="muted-sub">Aucune donnée disponible.</p>');
                    return;
                }
                new Chart(el, {
                    type,
                    data: {
                        labels,
                        datasets: [{
                            data: values,
                            backgroundColor: type === 'bar' ? '#2563eb' : palette,
                            borderRadius: type === 'bar' ? 6 : 0,
                        }]
                    },
                    options: {
                        plugins: {
                            legend: {
                                display: type !== 'bar',
                                position: 'bottom'
                            }
                        },
                        scales: type === 'bar' ? {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0
                                }
                            }
                        } : {},
                        ...options
                    }
                });
            }

            renderChart('chartStatut', 'doughnut', Object.keys(data.parStatut), Object.values(data.parStatut));
            renderChart('chartZone', 'bar', Object.keys(data.parZone), Object.values(data.parZone));
            renderChart('chartFormation', 'doughnut', Object.keys(data.parStatutFormation), Object.values(data
                .parStatutFormation));
        });
    </script>
@endpush
