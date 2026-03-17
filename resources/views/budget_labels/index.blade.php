@extends('layout.user-layout')
@section('title', 'ONFP | Libellés budgétaires')
@section('space-work')
    <div class="container">

        @can('detf-create')
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="mb-0">
                    Libellés budgétaires
                </h3>
                <a href="{{ route('budget-labels.create') }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-plus-circle"></i> Ajouter
                </a>
            </div>
        @endcan

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered table-striped" id="table_budget">
            <thead>
                <tr>
                    <th width="5%" class="text-center">N°</th>
                    <th width="25%">Libellé</th>
                    <th width="10%" class="text-center">Type</th>
                    <th>Description</th>
                    <th width="2">#</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($labels as $label)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $label->libelle }}</td>
                        <td class="text-center">{{ $label->type }}</td>
                        <td>{{ $label->description }}</td>
                        <td>
                            <div class="d-flex align-items-baseline">
                                <a href="{{ route('budget-labels.edit', $label) }}" class="btn btn-success btn-sm"
                                    title="Modifier">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <div class="filter">
                                    <a class="icon" href="#" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                        <li>
                                            <form action="{{ route('budget-labels.destroy', $label) }}" method="post">
                                                @csrf
                                                @method('DELETE')

                                                <input type="hidden" name="label" value="{{ $label }}">
                                                <button type="submit" class="dropdown-item show_confirm">
                                                    <i class="bi bi-trash"></i> Supprimer
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

@push('scripts')
    <script>
        new DataTable('#table_budget', {
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
