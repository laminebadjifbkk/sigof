@extends('layout.user-layout')
@section('title', 'ONFP - Formations de ' . $evaluateur->name)
@section('space-work')

    <div class="pagetitle">
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Accueil</a></li>
                <li class="breadcrumb-item">Évaluateurs</li>
                <li class="breadcrumb-item active">{{ $evaluateur->name }}</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row justify-content-center">
            <div class="col-lg-10">

                {{-- Messages flash --}}
                @if ($message = Session::get('status'))
                    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                        <i class="bi bi-check-circle me-2"></i><strong>{{ $message }}</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if ($message = Session::get('danger'))
                    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i><strong>{{ $message }}</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                            <i class="bi bi-x-circle me-2"></i>{{ $error }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endforeach
                @endif

                {{-- Informations de l’évaluateur --}}
                {{-- <div class="card shadow-sm mb-4 border-0 rounded-4">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center rounded-top-4">
                        <h5 class="mb-0"><i class="bi bi-person-badge me-2"></i>Informations de l’évaluateur</h5>
                        <a href="{{ route('evaluateurs.index') }}" class="btn btn-light btn-sm">
                            <i class="bi bi-arrow-left-circle"></i> Retour
                        </a>
                    </div>

                    <div class="card-body">
                        <div class="row g-3 align-items-center">
                            <div class="col-md-3 text-center">
                                <img src="{{ asset('assets/img/profile-default.png') }}" class="rounded-circle shadow-sm mb-3"
                                    alt="Photo de profil" width="110">
                                <p class="text-muted mb-0">{{ $evaluateur->fonction ?? '—' }}</p>
                            </div>

                            <div class="col-md-9">
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <strong>Prénom :</strong> {{ $evaluateur->name ?? '—' }}
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Nom :</strong> {{ $evaluateur->lastname ?? '—' }}
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <strong>Email :</strong> <a href="mailto:{{ $evaluateur?->email }}">{{ $evaluateur?->email }}</a>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Téléphone :</strong> {{ $evaluateur->telephone ?? '—' }}
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-12">
                                        <strong>Adresse :</strong> {{ $evaluateur->adresse ?? '—' }}
                                    </div>
                                </div>
                                @if ($evaluateur->scan_cv)
                                    <div class="mt-2">
                                        <a href="{{ asset('storage/' . $evaluateur->scan_cv) }}" target="_blank"
                                            class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-file-earmark-text"></i> Voir le CV
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div> --}}

                <div class="card shadow-sm mb-4 border-0 rounded-4">
                    <div
                        class="card-header bg-warning text-white d-flex justify-content-between align-items-center rounded-top-4">
                        <h5 class="mb-0"><i class="bi bi-person-badge me-2"></i>Informations de l’évaluateur</h5>
                        <a href="{{ route('evaluateurs.index') }}" class="btn btn-light btn-sm">
                            <i class="bi bi-arrow-left-circle"></i> Retour
                        </a>
                    </div>

                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <strong>Prénom :</strong> {{ $evaluateur->name ?? '—' }}
                            </div>
                            <div class="col-md-6">
                                <strong>Nom :</strong> {{ $evaluateur->lastname ?? '—' }}
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-12">
                                <strong>Fonction / Spécialité :</strong> {{ $evaluateur->fonction ?? '—' }}
                            </div>
                            <div class="col-md-6">
                                <strong>Email :</strong>
                                <a href="mailto:{{ $evaluateur?->email }}">{{ $evaluateur?->email ?? '—' }}</a>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <strong>Téléphone :</strong> <a
                                    href="tel:+221{{ $evaluateur->telephone ?? '—' }}">{{ $evaluateur->telephone ?? '—' }}</a>
                            </div>
                            <div class="col-md-12">
                                <strong>Adresse :</strong> {{ $evaluateur->adresse ?? '—' }}
                            </div>
                        </div>
                        @if ($evaluateur->scan_cv)
                            <div class="mt-2">
                                <a href="{{ asset('storage/' . $evaluateur->scan_cv) }}" target="_blank"
                                    class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-file-earmark-text"></i> Voir le CV
                                </a>
                            </div>
                        @else
                            <div class="alert alert-info mt-2 mb-0">
                                <i class="bi bi-info-circle me-2"></i> Aucun CV disponible pour le moment.
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Liste des formations --}}
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-secondary text-white rounded-top-4">
                        <h5 class="mb-0"><i class="bi bi-mortarboard me-2"></i>Formations de
                            {{ $evaluateur->name . ' ' . $evaluateur->lastname }}</h5>
                    </div>
                    <div class="card-body">
                        @if ($evaluateur->formations->isNotEmpty())
                            <div class="table-responsive">
                                <table class="table table-hover table-striped align-middle" id="table-formations">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="text-center">N° Conv.</th>
                                            <th>Type</th>
                                            <th>Intitulé</th>
                                            <th>Localité</th>
                                            <th>Module</th>
                                            <th class="text-center">Statut</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($evaluateur->formations as $formation)
                                            <tr>
                                                <td class="text-center fw-bold">{{ $formation?->numero_convention }}</td>
                                                <td>{{ $formation?->types_formation?->name }}</td>
                                                <td>{{ $formation?->name }}</td>
                                                <td>{{ $formation?->departement?->region?->nom }}</td>
                                                <td>
                                                    {{ $formation->module?->name ?? ($formation->collectivemodule?->module ?? '—') }}
                                                </td>
                                                <td class="text-center">
                                                    <span class="{{ $formation->statut }}">
                                                        {{ ucfirst($formation->statut) }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <a href="{{ route('formations.show', $formation) }}"
                                                        class="btn btn-outline-primary btn-sm" title="Voir détails" target="_blank">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info text-center mb-0">
                                <i class="bi bi-info-circle me-2"></i> Aucune formation disponible pour le moment.
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </section>

@endsection

@push('scripts')
    <script>
        new DataTable('#table-formations', {
            layout: {
                topStart: {
                    buttons: ['csv', 'excel', 'print'],
                }
            },
            order: [
                [0, 'desc']
            ],
            language: {
                "sProcessing": "Traitement en cours...",
                "sSearch": "Rechercher :",
                "sLengthMenu": "Afficher _MENU_ éléments",
                "sInfo": "Affichage de _START_ à _END_ sur _TOTAL_ éléments",
                "sInfoEmpty": "Aucun élément",
                "sInfoFiltered": "(filtré de _MAX_ éléments)",
                "sLoadingRecords": "Chargement...",
                "sZeroRecords": "Aucun résultat trouvé",
                "sEmptyTable": "Aucune donnée disponible",
                "oPaginate": {
                    "sFirst": "Premier",
                    "sPrevious": "Précédent",
                    "sNext": "Suivant",
                    "sLast": "Dernier"
                }
            }
        });
    </script>
@endpush
