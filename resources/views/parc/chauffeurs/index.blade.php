@extends('layout.user-layout')
@section('title', 'ONFP - Liste des chauffeurs')

@section('space-work')
    <section class="section register">
        <div class="container">
            <div class="row mb-4">
                <!-- Total chauffeur -->
                <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                    <div class="card shadow-sm text-center p-2" style="min-height: 140px; border-radius: 10px;">
                        <h6 class="card-title mb-2 text-truncate" title="Total chauffeur" style="font-size:0.85rem;">
                            Total
                        </h6>
                        <div class="d-flex flex-column align-items-center justify-content-center mb-2">
                            <div class="rounded-circle bg-primary text-white d-flex justify-content-center align-items-center mb-1"
                                style="width:36px; height:36px; font-size:1rem;">
                                <i class="bi bi-flag"></i>
                            </div>
                            <span class="h6 mb-0" style="font-size:1rem;">{{ $totalChauffeurs }}</span>
                            <small class="text-muted" style="font-size:0.7rem;">chauffeur(s)</small>
                        </div>

                        <!-- Barre de pourcentage -->
                        <div class="mb-2">
                            <div class="progress" style="height:6px; border-radius:3px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: 100%;"></div>
                            </div>
                            <small class="text-muted">100%</small>
                        </div>

                        <!-- Bouton voir plus -->
                        <a href="{{ route('parc-chauffeurs.index') }}" class="btn btn-outline-primary btn-sm w-100"
                            style="font-size:0.75rem;">
                            Voir plus <i class="bi bi-arrow-right-short"></i>
                        </a>
                    </div>
                </div>
                @foreach ($groupes as $statut => $items)
                    <div class="col-6 col-sm-4 col-md-3 col-lg-2 mb-3">
                        <div class="card shadow-sm text-center p-2" style="min-height: 120px; border-radius: 10px;">

                            <!-- Statut -->
                            <h6 class="card-title mb-2 text-truncate" title="{{ $statut }}"
                                style="font-size: 0.85rem;">
                                {{ $statut }}
                            </h6>

                            <!-- Nombre et icône -->
                            <div class="d-flex flex-column align-items-center justify-content-center mb-2">
                                <div class="rounded-circle bg-primary text-white d-flex justify-content-center align-items-center mb-1"
                                    style="width: 36px; height: 36px; font-size: 1rem;">
                                    <i class="bi bi-flag"></i>
                                </div>
                                <span class="h6 mb-0" style="font-size: 1rem;">
                                    {{ $items->count() }}
                                </span>
                                <small class="text-muted" style="font-size: 0.7rem;">chauffeur(s)</small>
                            </div>

                            <!-- Pourcentage -->
                            <div class="mb-2">
                                <div class="progress" style="height:6px; border-radius:3px;">
                                    <div class="progress-bar bg-success" role="progressbar"
                                        style="width: {{ $statutPourcentages[$statut]['percent'] }}%;"></div>
                                </div>
                                <small class="text-muted">{{ $statutPourcentages[$statut]['percent'] }}%</small>
                            </div>

                            <!-- Bouton voir plus -->
                            <a href="{{ route('parc-chauffeurs.index', ['statut' => $statut]) }}"
                                class="btn btn-outline-primary btn-sm w-100" style="font-size: 0.75rem;">
                                Voir plus <i class="bi bi-arrow-right-short"></i>
                            </a>

                        </div>
                    </div>
                @endforeach
            </div>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="mb-0">Liste des chauffeurs</h3>
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
                        <th class="text-center" width="10%">Matricule</th>
                        <th>Nom</th>
                        {{-- <th>Prénom</th> --}}
                        {{-- <th>Téléphone</th> --}}
                        <th class="text-center" width="15%">Missions {{ now()->year }}</th>
                        <th class="text-center" width="12%">N° permis</th>
                        {{-- <th class="text-center" width="12%">Catégorie</th> --}}
                        <th>Permis expire</th>
                        {{-- <th class="text-center" width="5%">Statut</th> --}}
                        <th class="text-center" width="12%">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($chauffeurs as $chauffeur)
                        <tr>
                            <td class="text-center">{{ $chauffeur?->employee?->matricule }}</td>
                            <td>{{ $chauffeur?->employee?->user?->firstname . ' ' . $chauffeur?->employee?->user?->name }}</td>
                            <td class="text-center">
                                <span class="badge bg-secondary">
                                    {{ $chauffeur->missions_annee_count }}
                                </span>
                            </td>
                            {{-- <td>{{ $chauffeur->prenom }}</td> --}}
                            {{-- <td>{{ $chauffeur->telephone }}</td> --}}
                            <td class="text-center">{{ $chauffeur->permis_numero }}</td>
                            {{-- <td class="text-center">{{ $chauffeur->permis_categories }}</td> --}}
                            {{-- <td>{{ $chauffeur->permis_expire_le->format('d/m/Y') }}</td> --}}
                            <td>
                                <span class="{{ $chauffeur->permis_classe }}">
                                    {{ $chauffeur->permis_restant }}
                                </span>
                            </td>
                            {{-- <td class="text-center">
                                <span class="badge {{ $chauffeur->statut == 'actif' ? 'bg-success' : 'bg-danger' }}">
                                    {{ ucfirst($chauffeur->statut) }}
                                </span>
                            </td> --}}
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
            ordering: true, // désactive le tri automatique
            layout: {
                topStart: {
                    buttons: ['csv', 'excel', 'print'],
                }
            },
            /* "order": [
                [2, 'desc']
            ], */
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
