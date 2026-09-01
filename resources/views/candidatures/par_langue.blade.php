@extends('layouts.dashboard')

@section('title', 'Candidatures - ' . $langue->nom)

@section('content')
    <div class="dash-topbar">
        <div>
            <a href="{{ route('candidatures.index') }}" class="btn btn-sm btn-outline">&larr; Retour aux langues</a>
            <h2>Candidatures - {{ $langue->nom }}</h2>
            <p class="muted-sub">{{ $candidatures->count() }} candidature(s) reçue(s)</p>
        </div>
    </div>

    {{--     <div class="kpi-grid">
        <div class="kpi-card">
            <div class="kpi-top">
                <span class="kpi-label">Candidatures totales</span>
                <span class="kpi-dot" style="background:var(--navy)"></span>
            </div>
            <div class="kpi-num">{{ $total }}</div>
        </div>

        @foreach ($kpis as $index => $kpi)
            <div class="kpi-card">
                <div class="kpi-top">
                    <span class="kpi-label">{{ $kpi['label'] }}</span>
                    <span class="kpi-dot" style="background:{{ $couleurs[$index % count($couleurs)] }}"></span>
                </div>
                <div class="kpi-num">{{ $kpi['count'] }}</div>
                <div class="kpi-delta" style="color:{{ $couleurs[$index % count($couleurs)] }};">
                    {{ $kpi['pourcentage'] }} % du total
                </div>
            </div>
        @endforeach
    </div> --}}

    <div class="kpi-grid">
        <a href="{{ route('candidatures.parLangue', $langue) }}"
            class="kpi-card-link {{ request('statut') ? '' : 'active' }}">
            <div class="kpi-card">
                <div class="kpi-top">
                    <span class="kpi-label">Candidatures totales</span>
                    <span class="kpi-dot" style="background:var(--navy)"></span>
                </div>
                <div class="kpi-num">{{ $total }}</div>
                <div class="kpi-delta">100 %</div>
            </div>
        </a>

        @foreach ($kpis as $index => $kpi)
            <a href="{{ route('candidatures.parLangue', ['langue' => $langue, 'statut' => $kpi['label']]) }}"
                class="kpi-card-link {{ request('statut') === $kpi['label'] ? 'active' : '' }}">
                <div class="kpi-card">
                    <div class="kpi-top">
                        <span class="kpi-label">{{ $kpi['label'] }}</span>
                        <span class="kpi-dot" style="background:{{ $couleurs[$index % count($couleurs)] }}"></span>
                    </div>
                    <div class="kpi-num">{{ $kpi['count'] }}</div>
                    <div class="kpi-delta" style="color:{{ $couleurs[$index % count($couleurs)] }};">
                        {{ $kpi['pourcentage'] }} % du total
                    </div>
                </div>
            </a>
        @endforeach
    </div>

    <div class="panel">
        <div class="table-responsive">
            <h3>Liste des candidatures</h3>
            <table class="data-table table datatables align-middle" id="dataTableCandidature">
                <thead>
                    <tr>
                        <th>Candidat</th>
                        <th>Niveau</th>
                        <th>Zone</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($candidatures as $c)
                        <tr>
                            <td>
                                <div class="row-name">
                                    <span
                                        class="mini-avatar">{{ Str::upper(Str::substr($c?->user?->firstname, 0, 1) . Str::substr($c?->user?->name, 0, 1)) }}</span>
                                    {{ $c?->user?->firstname }} {{ $c?->user?->name }}
                                </div>
                            </td>
                            <td>{{ $c->niveau_francais }}</td>
                            <td>{{ $c->zone_label }}</td>
                            <td>
                                <span class="status-pill {{ $c->statut_classe }}">{{ $c->statut_label }}</span>
                            </td>
                            <td>
                                <a href="{{ route('candidatures.show', $c) }}" class="btn btn-sm btn-outline"
                                    target="_blank">
                                    Voir
                                </a>
                            </td>
                        </tr>
                    @empty
                        <!-- <tr>
                                <td colspan="5" class="empty-row">Aucune candidature pour cette langue.</td>
                            </tr> -->
                    @endforelse
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
                "sSearch": "Rechercher&nbsp;:",
                "sLengthMenu": "Afficher _MENU_ &eacute;l&eacute;ments",
                "sInfo": "Affichage de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
                "sInfoEmpty": "Affichage de l'&eacute;l&eacute;ment 0 &agrave; 0 sur 0 &eacute;l&eacute;ment",
                "sEmptyTable": "Aucune candidature disponible.",
                "oPaginate": {
                    "sFirst": "Premier",
                    "sPrevious": "Pr&eacute;c&eacute;dent",
                    "sNext": "Suivant",
                    "sLast": "Dernier"
                }
            }
        });
    </script>
@endpush
