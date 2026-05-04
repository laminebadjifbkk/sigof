@extends('layout.user-layout')
{{-- @section('title', 'ONFP | SUIVI INDIVIDUEL')
@section('space-work')
    <div class="container">
        <h1>Liste des suivis individuels</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <a href="{{ route('individuels.create') }}" class="btn btn-primary mb-3">Ajouter un suivi</a>

        @if ($suivis->isEmpty())
            <p>Aucun suivi individuel trouvé.</p>
        @else
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Individuelle</th>
                        <th>Activité</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($suivis as $suivi)
                        <tr>
                            <td>{{ $suivi->id }}</td>
                            <td>{{ $suivi->individuelle?->user?->firstname . ' ' . $suivi?->individuelle?->user?->name ?? 'Non défini' }}
                            </td>
                            <td>{{ $suivi->activite_principale ?? 'N/A' }}</td>
                            <td>{{ $suivi->created_at->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('individuels.show', $suivi->id) }}" class="btn btn-info btn-sm">Voir</a>
                                <a href="{{ route('individuels.edit', $suivi->id) }}"
                                    class="btn btn-warning btn-sm">Modifier</a>
                                <form action="{{ route('individuels.destroy', $suivi->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm"
                                        onclick="return confirm('Voulez-vous vraiment supprimer ?')">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection --}}


@section('title', 'ONFP | SUIVI INDIVIDUEL')

@section('space-work')
    <section class="section register">
        <div class="container">

            @can('parc-suivi-create')
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="mb-0">
                        Liste des suivis individuels
                        <span class="etat-btn">

                            @if (!empty(request('statut')))
                                {{-- Si un statut est présent dans l'URL --}}
                                {{ $labels[request('statut')] ?? ucfirst(str_replace('_', ' ', request('statut'))) }}
                            @elseif(request('annee'))
                                {{-- Si une année est présente dans l'URL --}}
                                de l'année {{ request('annee') }}
                            @endif
                        </span>
                    </h3>
                    {{-- <a href="{{ route('parc-missions.create') }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-plus-circle"></i> Ajouter une suivi
                    </a> --}}
                </div>
            @endcan

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

            <div class="table-responsive">
                <table class="table table-hover table-striped shadow-sm" id="table-suivi-individuel">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-center">CIN</th>
                            <th class="text-center">NAME</th>
                            <th class="text-center">Date naissance</th>
                            <th class="text-center">Lieu naissance</th>
                            <th class="text-center" width="12%">Téléphone</th>
                            <th class="text-center" width="12%">Statut</th>
                            <th class="text-center" width="8%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($suivis as $suivi)
                            <tr>
                                <td class="text-center">{{ $suivi->individuelle?->user?->cin ?? '-' }}</td>
                                <td class="text-center">
                                    {{ $suivi->individuelle?->user?->firstname . ' ' . $suivi?->individuelle?->user?->name ?? '-' }}
                                </td>
                                <td class="text-center">
                                    {{ $suivi->individuelle?->user?->date_naissance->format('d/m/Y') ?? '-' }}</td>
                                <td class="text-center">{{ $suivi->individuelle?->user?->lieu_naissance ?? '-' }}</td>
                                <td class="text-center">{{ $suivi->individuelle?->user?->telephone ?? '-' }}</td>
                                <td class="text-center">
                                    <span class="{{ $suivi->statut ?? '-' }}">{{ $suivi->statut ?? '-' }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="d-flex align-items-baseline justify-content-center gap-1">
                                        <a href="#" class="btn btn-sm btn-info">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        {{-- <a href="#"
                                            class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form action="{{ route('parc-missions.destroy', $suivi->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="id" value="{{ $suivi->id }}">
                                            <button type="submit" class="btn btn-sm btn-danger show_confirm"
                                                {{ $suivi->employees_count > 0 ? 'disabled' : '' }}
                                                title="{{ $suivi->employees_count > 0 ? 'Mission déjà assignée à des employés' : 'Supprimer la suivi' }}"
                                                data-id="{{ $suivi->id }}">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form> --}}
                                    </span>
                                </td>
                                <!-- Modal -->
                                <div class="modal fade" id="objetModal{{ $suivi->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Objet de la suivi</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                {{ $suivi->objet }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        new DataTable('#table-suivi-individuel', {
            ordering: false,
            layout: {
                topStart: {
                    buttons: ['csv', 'excel', 'print'],
                }
            },
            language: {
                "sProcessing": "Traitement en cours...",
                "sSearch": "Rechercher&nbsp;:",
                "sLengthMenu": "Afficher _MENU_ éléments",
                "sInfo": "Affichage de l'élément _START_ à _END_ sur _TOTAL_ éléments",
                "sInfoEmpty": "Affichage de l'élément 0 à 0 sur 0 élément",
                "sInfoFiltered": "(filtré de _MAX_ éléments au total)",
                "sLoadingRecords": "Chargement en cours...",
                "sZeroRecords": "Aucun élément à afficher",
                "sEmptyTable": "Aucune donnée disponible dans le tableau",
                "oPaginate": {
                    "sFirst": "Premier",
                    "sPrevious": "Précédent",
                    "sNext": "Suivant",
                    "sLast": "Dernier"
                },
                "oAria": {
                    "sSortAscending": ": activer pour trier la colonne par ordre croissant",
                    "sSortDescending": ": activer pour trier la colonne par ordre décroissant"
                },
                "select": {
                    "rows": {
                        _: "%d lignes sélectionnées",
                        0: "Aucune ligne sélectionnée",
                        1: "1 ligne sélectionnée"
                    }
                }
            }
        });
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.toggle-text').forEach(function(link) {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const td = this.closest('td');
                    const shortText = td.querySelector('.short-text');
                    const fullText = td.querySelector('.full-text');

                    if (fullText.classList.contains('d-none')) {
                        shortText.classList.add('d-none');
                        fullText.classList.remove('d-none');
                        this.textContent = 'voir moins';
                    } else {
                        fullText.classList.add('d-none');
                        shortText.classList.remove('d-none');
                        this.textContent = '...voir plus';
                    }
                });
            });
        });
    </script>
@endpush
