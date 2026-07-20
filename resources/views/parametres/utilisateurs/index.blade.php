@extends('layouts.dashboard')

@section('title', 'Utilisateurs')

@section('content')
<div class="dash-topbar">
    <div>
        <h2>Utilisateurs</h2>
        <p class="muted-sub">Comptes admin et candidats</p>
    </div>
    <div class="topbar-right">
        <a href="{{ route('utilisateurs.create') }}" class="btn btn-primary">+ Ajouter un admin</a>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="panel">
    <div class="table-responsive">
        <table class="data-table table datatables align-middle" id="dataTableUtilisateurs">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>E-mail</th>
                    <th>Type</th>
                    <th>Rôle</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($utilisateurs as $u)
                <tr>
                    <td>{{ $u->civilite }} {{ $u->firstname }} {{ $u->name }}</td>
                    <td>{{ $u->email }}</td>
                    <td>
                        @if ($u->candidatures_count > 0)
                        <span class="status-pill status-inconnu">Candidat</span>
                        @else
                        <span class="status-pill status-validee">Admin</span>
                        @endif
                    </td>
                    <td>
                        @forelse ($u->roles as $role)
                        <span class="status-pill status-en-attente">{{ $role->name }}</span>
                        @empty
                        <span class="text-muted">-</span>
                        @endforelse
                    </td>
                    <td class="d-flex gap-1">
                        <a href="{{ route('utilisateurs.edit', $u) }}" class="btn btn-sm btn-outline" title="Modifier">
                            <i class="bi bi-pencil"></i>
                        </a>
                        @if ($u->id !== auth()->id())
                        <form action="{{ route('utilisateurs.destroy', $u) }}" method="POST" onsubmit="return confirm('Supprimer ce compte ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="empty-row">Aucun utilisateur pour le moment.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    new DataTable('#dataTableUtilisateurs', {
        ordering: false,
        layout: {
            topStart: {
                buttons: ['csv', 'excel', 'print']
            }
        },
        language: {
            "sSearch": "Rechercher&nbsp;:",
            "sLengthMenu": "Afficher _MENU_ &eacute;l&eacute;ments",
            "sInfo": "Affichage de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
            "sEmptyTable": "Aucun utilisateur disponible pour le moment.",
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