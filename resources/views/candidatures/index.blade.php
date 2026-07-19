@extends('layouts.dashboard')

@section('title', 'Candidatures')

@section('content')
<div class="dash-topbar">
    <div>
        <h2>Candidatures reçues</h2>
        <p class="muted-sub">Suivi des candidatures par langue - programme traducteurs Dakar 2026</p>
    </div>
</div>

<div class="panel">
    <div class="table-responsive">
        <h3>Liste des candidats par langue</h3>
        <table class="data-table table datatables align-middle" id="dataTableLangues">
            <thead>
                <tr>
                    <th>Langue</th>
                    <!-- <th>Code</th> -->
                    <th>Postes</th>
                    <th>Niveau</th>
                    <!-- <th>Français</th> -->
                    <th>Diplôme minimum</th>
                    <th>Certification recommandée</th>
                    <th>Candidatures</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($langues as $langue)
                <tr>
                    <td>
                        <div class="row-name">
                            {{ $langue->nom }}
                        </div>
                    </td>
                    <!-- <td>{{ $langue->code }}</td> -->
                    <td>{{ $langue->postes_disponibles }}</td>
                    <td>{{ $langue->niveau_langue_requis }}</td>
                    <!-- <td>{{ $langue->niveau_francais_requis }}</td> -->
                    <td>{{ $langue->diplome_minimum }}</td>
                    <td>{{ $langue->certification_recommandee }}</td>
                    <td>
                        <span class="status-pill">{{ $langue->candidatures_count }}</span>
                    </td>
                    <td>
                        <a href="{{ route('candidatures.parLangue', $langue) }}" class="btn btn-sm btn-outline">
                            Voir les candidats
                        </a>
                    </td>
                </tr>
                @empty
                <!-- <tr>
                    <td colspan="9" class="empty-row">Aucune candidature pour le moment.</td>
                </tr> -->
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    new DataTable('#dataTableLangues', {
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
            "sEmptyTable": "Aucune langue disponible pour le moment.",
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