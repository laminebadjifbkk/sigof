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
                            <span class="mini-avatar">{{ Str::upper(Str::substr($c?->user?->firstname, 0, 1) . Str::substr($c?->user?->name, 0, 1)) }}</span>
                            {{ $c?->user?->firstname }} {{ $c?->user?->name }}
                        </div>
                    </td>
                    <td>{{ $c->niveau_francais }}</td>
                    <td>{{ $c->zone_label }}</td>
                    <td>
                        <span class="status-pill {{ $c->statut_classe }}">{{ $c->statut_label }}</span>
                    </td>
                    <td>
                        <a href="{{ route('candidatures.show', $c) }}" class="btn btn-sm btn-outline">
                            Voir
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="empty-row">Aucune candidature pour cette langue.</td>
                </tr>
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