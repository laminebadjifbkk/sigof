@extends('layouts.dashboard')

@section('title', 'Langues')

@section('content')
    <div class="dash-topbar">
        <div>
            <h2>Langues de spécialisation</h2>
            <p class="muted-sub">Gestion des langues - programme traducteurs Dakar 2026</p>
        </div>
        @can('langues.create')
            <div class="topbar-right">
                <a href="{{ route('langues.create') }}" class="btn btn-primary">+ Ajouter une langue</a>
            </div>
        @endcan
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="panel">
        <div class="table-responsive">
            <h3>Liste des langues</h3>
            <table class="data-table table datatables align-middle" id="dataTableLangues">
                <thead>
                    <tr>
                        <th>Langue</th>
                        <th>Code</th>
                        <th>Postes disponibles</th>
                        <th>Niveau langue requis</th>
                        <!-- <th>Niveau français requis</th> -->
                        <!-- <th>Diplôme minimum</th> -->
                        <th>Certification recommandée</th>
                        <th>Candidatures</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($langues as $langue)
                        <tr>
                            <td>{{ $langue->nom }}</td>
                            <td>{{ $langue->code }}</td>
                            <td>{{ $langue->postes_disponibles }}</td>
                            <td>{{ $langue->niveau_langue_requis }}</td>
                            <!-- <td>{{ $langue->niveau_francais_requis }}</td> -->
                            <!-- <td>{{ $langue->diplome_minimum }}</td> -->
                            <td>{{ $langue->certification_recommandee ?? '-' }}</td>
                            <td><span class="status-pill">{{ $langue->candidatures_count }}</span></td>
                            <!-- <td class="d-flex gap-1">
                                <a href="{{ route('langues.show', $langue) }}" class="btn btn-sm btn-outline">Voir</a>
                                <a href="{{ route('langues.edit', $langue) }}" class="btn btn-sm btn-outline">Modifier</a>
                                <form action="{{ route('langues.destroy', $langue) }}" method="POST" onsubmit="return confirm('Supprimer cette langue ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                                </form>
                            </td> -->
                            <td class="d-flex gap-1">
                                <a href="{{ route('langues.show', $langue) }}" class="btn btn-sm btn-outline"
                                    title="Voir">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('langues.edit', $langue) }}" class="btn btn-sm btn-outline"
                                    title="Modifier">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @can('langues.delete')
                                    <form action="{{ route('langues.destroy', $langue) }}" method="POST"
                                        onsubmit="return confirm('Supprimer cette langue ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <!-- <tr>
                            <td colspan="9" class="empty-row">Aucune langue enregistrée.</td>
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
