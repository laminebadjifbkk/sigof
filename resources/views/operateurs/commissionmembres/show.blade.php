@extends('layout.user-layout')
@section('title', 'ONFP | Liste des Membres du jury')
@section('space-work')
    @can('ingenieur-view')
        <section class="section register">
            <div class="row justify-content-center">
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="pagetitle">
                        {{-- <h1>Data Tables</h1> --}}
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('/home') }}">Accueil</a></li>
                                <li class="breadcrumb-item">Tables</li>
                                <li class="breadcrumb-item active">Données</li>
                            </ol>
                        </nav>
                    </div><!-- End Page Title -->
                    @if ($message = Session::get('status'))
                        <div class="alert alert-success bg-success text-light border-0 alert-dismissible fade show"
                            role="alert">
                            <strong>{{ $message }}</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if ($message = Session::get('danger'))
                        <div class="alert alert-danger bg-danger text-light border-0 alert-dismissible fade show"
                            role="alert">
                            <strong>{{ $message }}</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if ($errors->any())
                        @foreach ($errors->all() as $error)
                            <div class="alert alert-danger bg-danger text-light border-0 alert-dismissible fade show"
                                role="alert"><strong>{{ $error }}</strong></div>
                        @endforeach
                    @endif
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <!-- Bouton retour -->
                            <div class="d-flex align-items-center mb-3">
                                <a href="{{ route('commissionmembres.index') }}" class="btn btn-success btn-sm" title="Retour">
                                    <i class="bi bi-arrow-counterclockwise"></i>
                                </a>
                                <span class="ms-2">| Retour</span>
                            </div>

                            <!-- Titre -->
                            <h5 class="card-title">{{ $membre?->civilite . ' ' . $membre?->prenom . ' ' . $membre?->nom }}</h5>

                            <!-- Tableau -->
                            <div class="table-responsive">
                                <table class="table table-hover table-striped align-middle" id="table-membre">
                                    <thead class="table-primary text-center">
                                        <tr>
                                            <th>Commission agrément</th>
                                            <th>Session</th>
                                            <th>Date</th>
                                            <th>Lieu</th>
                                            <th>Fin agrément</th>
                                            <th>Opérateurs</th>
                                            <th>Statut</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($membre?->commissionagrements as $commission)
                                            <tr>
                                                <td>{{ $commission?->commission }}</td>
                                                <td class="text-center">{{ $commission?->session }}</td>
                                                <td class="text-center">{{ $commission?->date?->format('d/m/Y') }}</td>
                                                <td>{{ $commission?->lieu }}</td>
                                                <td>{{ $commission?->date?->translatedFormat('l d F Y') }}</td>
                                                <td class="text-center">
                                                    <span class="badge bg-info">{{ count($commission?->operateurs) }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <!-- Ajoutez ici un badge ou une icône pour représenter le statut -->
                                                    <span class="badge bg-secondary">À définir</span>
                                                </td>
                                                <td class="text-center">
                                                    @can('commission-show')
                                                        <div class="btn-group">
                                                            <a href="{{ route('commissionagrements.show', $commission?->id) }}"
                                                                class="btn btn-warning btn-sm" title="Voir détails">
                                                                <i class="bi bi-eye"></i>
                                                            </a>
                                                            @if (auth()?->user()?->hasRole('super-admin|admin'))
                                                                <button class="btn btn-secondary btn-sm dropdown-toggle"
                                                                    type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                                    <i class="bi bi-gear"></i>
                                                                </button>
                                                                <ul class="dropdown-menu dropdown-menu-end">
                                                                    @can('commission-update')
                                                                        <li>
                                                                            <button type="button" class="dropdown-item"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#EditagrementModal{{ $commission?->id }}">
                                                                                <i class="bi bi-pencil"></i> Modifier
                                                                            </button>
                                                                        </li>
                                                                    @endcan
                                                                    @can('commission-delete')
                                                                        <li>
                                                                            <form
                                                                                action="{{ url('commissionagrements', $commission?->id) }}"
                                                                                method="post">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                                <button type="submit"
                                                                                    class="dropdown-item show_confirm">
                                                                                    <i class="bi bi-trash"></i> Supprimer
                                                                                </button>
                                                                            </form>
                                                                        </li>
                                                                        <li>
                                                                            <hr class="dropdown-divider">
                                                                        </li>
                                                                        <li>
                                                                            <a class="dropdown-item"
                                                                                href="{{ route('jurycommissionagrements.jury', $commission?->id) }}">
                                                                                <i class="bi bi-people"></i> Membres du jury
                                                                            </a>
                                                                        </li>
                                                                    @endcan
                                                                </ul>
                                                            @endif
                                                        </div>
                                                    @endcan
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    @endcan
@endsection

@push('scripts')
    <script>
        new DataTable('#table-membre', {
            layout: {
                topStart: {
                    buttons: [ 'csv', 'excel', 'print'],
                }
            },
            "order": [
                [0, 'asc']
            ],
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
