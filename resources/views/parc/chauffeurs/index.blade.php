@extends('layout.user-layout')
@section('title', 'ONFP - Liste des chauffeurs')

@section('space-work')
    <section class="section register">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="mb-0">Liste des chauffeurs</h1>
                <a href="{{ route('parc-chauffeurs.create') }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-person-plus"></i> Ajouter un chauffeur
                </a>
            </div>

            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <table class="table table-hover table-striped shadow-sm" id="table-parc-chauffeur">
                <thead class="table-dark">
                    <tr>
                        <th class="text-center">Matricule</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Téléphone</th>
                        <th class="text-center" width="12%">Statut</th>
                        <th class="text-center" width="12%">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($chauffeurs as $chauffeur)
                        <tr>
                            <td class="text-center">{{ $chauffeur->matricule }}</td>
                            <td>{{ $chauffeur->nom }}</td>
                            <td>{{ $chauffeur->prenom }}</td>
                            <td>{{ $chauffeur->telephone }}</td>
                            <td class="text-center">
                                <span class="badge {{ $chauffeur->statut == 'actif' ? 'bg-success' : 'bg-danger' }}">
                                    {{ ucfirst($chauffeur->statut) }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="d-flex align-items-baseline justify-content-center gap-1">
                                    <a href="{{ route('parc-chauffeurs.show', $chauffeur->id) }}"
                                        class="btn btn-sm btn-info btn-sm">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('parc-chauffeurs.edit', $chauffeur->id) }}"
                                        class="btn btn-sm btn-warning btn-sm">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('parc-chauffeurs.destroy', $chauffeur->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger btn-sm show_confirm">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endsection


@push('scripts')
    <script>
        new DataTable('#table-parc-chauffeur', {
            ordering: false, // désactive le tri automatique
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
