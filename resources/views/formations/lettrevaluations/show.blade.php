@extends('layout.user-layout')
@section('title', 'ONFP', ' | ' . $evaluateur?->name . ' ' . $evaluateur?->lastname)
@section('space-work')
    <div class="pagetitle">
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/home') }}">Accueil</a></li>
                <li class="breadcrumb-item">Tables</li>
                <li class="breadcrumb-item active">Données</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
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
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                                {{-- Bouton retour et titre secondaire --}}
                                <div class="d-flex align-items-center gap-3">
                                    <a href="{{ route('lettrevaluations.index') }}"
                                        class="btn btn-outline-success btn-sm rounded-pill shadow-sm"
                                        title="Retour à la liste">
                                        <i class="bi bi-arrow-counterclockwise me-1"></i> Retour
                                    </a>
                                    <span class="text-muted small">Lettres évaluations & ABE</span>
                                </div>

                                {{-- Titre Formation --}}
                                <h5 class="mb-0 fw-semibold text-info">
                                    <i class="bi bi-list-ul me-1"></i> Formation :
                                    <span class="text-dark">{{ $formation?->name }}</span>
                                </h5>

                                {{-- Actions droite --}}
                                <div class="d-flex flex-wrap gap-2">
                                    <a href="{{ route('formations.evaluations.edit', $formation->id) }}"
                                        class="btn btn-sm btn-outline-warning d-flex align-items-center gap-1"
                                        title="Modifier les lettres">
                                        <i class="bi bi-pencil"></i> Modifier
                                    </a>

                                    <a href="{{ route('formations.evaluations.download', $formation->id) }}"
                                        class="btn btn-sm btn-outline-primary d-flex align-items-center gap-1"
                                        title="Télécharger les lettres de mission" target="_blank">
                                        <i class="bi bi-download"></i> Lettres
                                    </a>

                                    <a href="{{ route('formations.paiement.download', $formation->id) }}"
                                        class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1"
                                        title="Télécharger la demande de paiement" target="_blank">
                                        <i class="bi bi-receipt"></i> Demande de paiement
                                    </a>
                                </div>
                            </div>

                            <hr class="my-4">

                            {{-- Tableau des évaluateurs --}}
                            @if ($formation?->evaluateurs->isNotEmpty())
                                <table class="table datatables align-middle" id="table-employes">
                                    <thead>
                                        <tr>
                                            <th>Evaluateur(s)</th>
                                            <th class="text-center">N° lettre</th>
                                            <th class="text-center">Date lettre</th>
                                            <th class="text-center" width="5%">Formation</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1; ?>
                                        @foreach ($formation?->evaluateurs as $evaluateur)
                                            <tr>
                                                <td>{{ $evaluateur?->name . ' ' . $evaluateur->lastname ?? 'Aucun' }}</td>
                                                <td class="text-center">
                                                    {{ $evaluateur?->pivot?->numero_lettre ?? 'Aucun' }}
                                                </td>
                                                <td class="text-center">
                                                    {{ $evaluateur?->pivot?->date_lettre
                                                        ? \Carbon\Carbon::parse($evaluateur->pivot->date_lettre)->format('d/m/Y')
                                                        : '-' }}
                                                </td>
                                                @can('formation-show')
                                                    <td>
                                                        <div class="d-flex align-items-center gap-2">
                                                            <!-- Bouton Voir détails -->
                                                            <a href="{{ route('formations.show', $formation) }}"
                                                                class="btn btn-primary btn-sm" title="Voir les détails">
                                                                <i class="bi bi-eye"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                @endcan
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="alert alert-info">Aucune information pour l'instant !</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
    </section>

@endsection
@push('scripts')
    <script>
        new DataTable('#table-employes', {
            layout: {
                topStart: {
                    buttons: ['csv', 'excel', 'print'],
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
