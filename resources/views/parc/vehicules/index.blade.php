@extends('layout.user-layout')
@section('title', 'Liste des véhicules')

@section('space-work')
    <section class="section register">
        <div class="container">
            <div class="row g-3 mb-4">

                <!-- Card total véhicules -->
                <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                    <div class="card shadow-sm text-center p-2" style="min-height: 140px; border-radius: 10px;">
                        <h6 class="card-title mb-2 text-truncate" title="Total véhicules" style="font-size:0.85rem;">Véhicules
                            total</h6>
                        <div class="d-flex flex-column align-items-center justify-content-center mb-2">
                            <div class="rounded-circle bg-primary text-white d-flex justify-content-center align-items-center mb-1"
                                style="width:28px; height:28px; font-size:1rem;">
                                <i class="bi bi-car-front"></i>
                            </div>
                            <span class="h6 mb-0" style="font-size:1rem;">{{ $totalVehicules }}</span>
                            {{-- <small class="text-muted" style="font-size:0.7rem;">véhicule(s)</small> --}}
                        </div>
                        <div class="mb-2">
                            <div class="progress" style="height:6px; border-radius:3px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: 100%;"></div>
                            </div>
                            <small class="text-muted">100%</small>
                        </div>
                        <a href="{{ route('parc-vehicules.index') }}" class="btn btn-outline-primary btn-sm w-100"
                            style="font-size:0.75rem;">
                            Voir plus <i class="bi bi-arrow-right-short"></i>
                        </a>
                    </div>
                </div>

                <!-- Cards par état -->
                @foreach ($groupes as $etat => $items)
                    @php
                        $percent = $etatPourcentages[$etat]['percent'];
                    @endphp
                    <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                        <div class="card shadow-sm text-center p-2" style="min-height: 140px; border-radius: 10px;">
                            <h6 class="card-title mb-2 text-truncate" title="Véhicules" style="font-size:0.85rem;">
                                {{-- {{ ucfirst($etat) }} --}}
                                Véhicules
                            </h6>

                            <!-- Badge état -->
                            <span class="etat-btn {{ $etat }}">
                                {{ ucfirst(str_replace('_', ' ', $etat)) }}
                            </span>

                            <!-- Nombre de véhicules -->
                            <div class="d-flex flex-column align-items-center justify-content-center mb-2 mt-2">
                                <span class="h6 mb-0" style="font-size:1rem;">{{ $items->count() }}</span>
                                {{-- <small class="text-muted" style="font-size:0.7rem;">véhicule(s)</small> --}}
                            </div>

                            <!-- Barre de pourcentage -->
                            <div class="mb-2">
                                <div class="progress" style="height:6px; border-radius:3px;">
                                    <div class="progress-bar bg-success" role="progressbar"
                                        style="width: {{ $percent }}%;"></div>
                                </div>
                                <small class="text-muted">{{ $percent }}%</small>
                            </div>

                            <!-- Bouton Voir plus -->
                            <a href="{{ route('parc-vehicules.index', ['etat' => $etat]) }}"
                                class="btn btn-outline-primary btn-sm w-100" style="font-size:0.75rem;">
                                Voir plus <i class="bi bi-arrow-right-short"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="mb-0">Liste des véhicules <span
                        class="etat-btn {{ $etatVehicule }}">{{ str_replace('_', ' ', $etatVehicule) }}</span>
                </h3>
                <a href="{{ route('parc-vehicules.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle"></i> Ajouter un véhicule
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

            <div class="table-responsive">
                <table class="table table-hover table-striped shadow-sm" id="table-parc-vehicule">
                    <thead class="table-dark">
                        <tr>
                            <th>Matricule</th>
                            <th>Marque</th>
                            <th>Chauffeur</th>
                            {{-- <th>Modèle</th> --}}
                            <th class="text-center" width="5%">Année</th>
                            <th class="text-center" width="8%">Kilométrage</th>
                            <th class="text-center" width="8%">Assurance</th>
                            <th class="text-center" width="12%">Visite</th>
                            {{-- <th class="text-center" width="12%">État</th> --}}
                            <th class="text-center" width="12%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($vehicules as $vehicule)
                            <tr>
                                <td>{{ $vehicule->immatriculation }}</td>
                                <td>{{ $vehicule->marque }}</td>
                                <td>{{ $vehicule?->chauffeur?->employee?->user?->firstname . ' ' . $vehicule?->chauffeur?->employee?->user?->name }}
                                </td>
                                {{-- <td>{{ $vehicule->modele }}</td> --}}
                                <td class="text-center">{{ $vehicule->annee }}</td>
                                <td class="text-center">{{ number_format($vehicule?->kilometrage_actuel, 0, ',', ' ') }}
                                </td>
                                <td class="text-center">
                                    {{ $vehicule?->assurance_expire_le ? $vehicule->assurance_expire_le->format('d/m/Y') : '-' }}
                                </td>
                                <td class="text-center">
                                    {{ $vehicule?->visite_technique_expire_le ? $vehicule->visite_technique_expire_le->format('d/m/Y') : '-' }}
                                </td>
                                {{-- <td class="text-center">
                                <span
                                    class="badge 
                                @if ($vehicule->etat == 'operationnel') bg-success 
                                @elseif($vehicule->etat == 'maintenance') bg-warning 
                                @else bg-danger @endif">
                                    {{ ucfirst($vehicule->etat) }}
                                </span>
                            </td> --}}
                                <td class="text-center">
                                    <span class="d-flex align-items-baseline justify-content-center gap-1">
                                        <a href="{{ route('parc-vehicules.show', $vehicule->id) }}"
                                            class="btn btn-info btn-sm">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('parc-vehicules.edit', $vehicule->id) }}"
                                            class="btn btn-warning btn-sm">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>

                                        @php
                                            $missionsCount = $vehicule?->missions?->count() ?? 0;
                                        @endphp
                                        <form action="{{ route('parc-vehicules.destroy', $vehicule->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm show_confirm"
                                                {{ $missionsCount > 0 ? 'disabled' : '' }}
                                                title="{{ $missionsCount > 0 ? 'Véhicule affecté à des missions' : 'Supprimer le chauffeur' }}">
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
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        new DataTable('#table-parc-vehicule', {
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
