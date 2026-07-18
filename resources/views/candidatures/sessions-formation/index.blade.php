@extends('layouts.dashboard')

@section('title', 'Sessions de formation')

@section('content')
<div class="dash-topbar">
    <div>
        <h2>Sessions de formation</h2>
        <p class="muted-sub">Gestion des sessions de formation des traducteurs</p>
    </div>
    <div class="topbar-right">
        <a href="{{ route('sessions-formation.create') }}" class="btn btn-primary btn-sm">
            + Nouvelle session
        </a>
    </div>
</div>

@if (session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

@if (session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="panel">
    <div class="table-responsive">
        <table class="data-table table datatables align-middle" id="dataTableSessions">
            <thead>
                <tr>
                    <th>Session</th>
                    <th>Langue</th>
                    <th>Formateur</th>
                    <th>Lieu</th>
                    <th>Dates</th>
                    <th>Participants</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sessions as $session)
                <tr>
                    <td>{{ $session->nom }}</td>
                    <td>{{ $session->langueSpecialisation->nom ?? '—' }}</td>
                    <td>{{ $session->formateur ?? '—' }}</td>
                    <td>{{ $session->lieu ?? '—' }}</td>
                    <td>{{ $session->date_debut->format('d/m/Y') }} → {{ $session->date_fin->format('d/m/Y') }}</td>
                    <td>{{ $session->participants_count }}</td>
                    <td><span class="status-pill {{ $session->statut_classe }}">{{ $session->statut_label }}</span></td>
                    <td>
                        <a href="{{ route('sessions-formation.show', $session) }}" class="btn btn-sm btn-outline">Voir</a>
                    </td>
                </tr>
                @empty
                <!-- <tr>
                    <td colspan="8" class="empty-row">Aucune session de formation pour le moment.</td>
                </tr> -->
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    new DataTable('#dataTableSessions', {
        ordering: false,
        language: {
            "sSearch": "Rechercher&nbsp;:",
            "sLengthMenu": "Afficher _MENU_ &eacute;l&eacute;ments",
            "sInfo": "Affichage de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
            "sZeroRecords": "Aucun &eacute;l&eacute;ment &agrave; afficher",
            "sEmptyTable": "Aucune donn&eacute;e disponible dans le tableau",
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