@extends('layout.user-layout')
@section('title', 'ONFP | INSCRIPTION PARTENAIRES')
@section('space-work')
    @can('ingenieur-view')
        <section class="section register">
            <div class="row justify-content-center">
                <div class="col-12">
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
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="card-title mb-0">PARTENAIRES</h5>
                            </div>
                            <div class="table-responsive">
                                <table class="table datatables table-bordered table-hover align-middle justify-content-center"
                                    id="table-jury">
                                    <thead class="table-primary text-center">
                                        <tr>
                                            {{-- <th width="8%">Civilité</th>
                                            <th>Prénom</th> --}}
                                            <th>Nom</th>
                                            <th>Fonction</th>
                                            <th>Structure</th>
                                            <th>Email</th>
                                            <th>Téléphone</th>
                                            <th>Commentaires</th>
                                            <th>Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($inscriptions as $inscription)
                                            <tr>
                                                {{-- <td class="text-center">{{ $inscription?->civilite }}</td>
                                                <td>{{ $inscription?->prenom }}</td> --}}
                                                <td>{{ $inscription?->nom }}</td>
                                                <td>{{ $inscription?->fonction }}</td>
                                                <td>{{ $inscription?->structure }}</td>
                                                <td>
                                                    <a href="mailto:{{ $inscription?->email }}" class="text-decoration-none">
                                                        {{ $inscription?->email }}
                                                    </a>
                                                </td>
                                                <td class="text-center">
                                                    <a href="tel:+221{{ $inscription?->telephone }}"
                                                        class="text-decoration-none">
                                                        {{ $inscription?->telephone }}
                                                    </a>
                                                </td>
                                                <td>{{ $inscription?->commentaire }}</td>
                                                <td>
                                                    <span>
                                                        @php
                                                            $date = $inscription?->created_at
                                                                ? \Carbon\Carbon::parse($inscription?->created_at)
                                                                : null;
                                                        @endphp
                                                        <i class="bi bi-calendar-event text-success me-1"></i>
                                                        Créée :

                                                        @if ($date)
                                                            @if ($date->isToday())
                                                                <span class="badge bg-success">Aujourd'hui</span>
                                                            @elseif ($date->isYesterday())
                                                                <span class="badge bg-warning">Hier</span>
                                                            @elseif ($date->diffInDays(\Carbon\Carbon::today()) < 7)
                                                                <span class="badge bg-primary">
                                                                    Il y a {{ $date->diffInDays(\Carbon\Carbon::today()) }}
                                                                    jours
                                                                </span>
                                                            @else
                                                                @php
                                                                    $diff = $date->diff(\Carbon\Carbon::today());
                                                                    $ans = $diff->y;
                                                                    $mois = $diff->m;
                                                                    $jours = $diff->d;

                                                                    $parts = [];
                                                                    if ($ans > 0) {
                                                                        $parts[] =
                                                                            $ans .
                                                                            ' ' .
                                                                            \Illuminate\Support\Str::plural('an', $ans);
                                                                    }
                                                                    if ($mois > 0) {
                                                                        $parts[] = $mois . ' mois';
                                                                    } // "mois" invariable
                                                                    if ($jours > 0) {
                                                                        $parts[] =
                                                                            $jours .
                                                                            ' ' .
                                                                            \Illuminate\Support\Str::plural(
                                                                                'jour',
                                                                                $jours,
                                                                            );
                                                                    }
                                                                @endphp

                                                                <span class="badge bg-secondary">
                                                                    Il y a {{ implode(' ', $parts) }}
                                                                </span>
                                                            @endif
                                                        @else
                                                            <span class="badge bg-danger">Date non disponible</span>
                                                        @endif
                                                    </span>
                                                </td>
                                                {{-- <td class="text-center">
                                                    <div class="btn-group">
                                                        <a href="{{ route('inscriptioncontacts.show', $inscription) }}"
                                                            class="btn btn-warning btn-sm" title="Voir détails">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                    </div>
                                                </td> --}}
                                                <td class="text-center">
                                                    <div class="btn-group">
                                                        <button class="btn btn-warning btn-sm viewInscriptionBtn"
                                                            data-id="{{ $inscription->id }}" title="Voir détails">
                                                            <i class="bi bi-eye"></i>
                                                        </button>

                                                        <div class="filter">
                                                            <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                                                    class="bi bi-three-dots"></i></a>
                                                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                                                <form
                                                                    action="{{ route('inscriptioncontacts.destroy', $inscription->id) }}"
                                                                    method="post">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit"
                                                                        class="dropdown-item show_confirm">Supprimer</button>
                                                                </form>
                                                            </ul>
                                                        </div>
                                                    </div>
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
            <!-- Modal de visualisation -->
            <div class="modal fade" id="viewInscriptionModal" tabindex="-1" aria-labelledby="viewInscriptionLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-warning text-dark">
                            <h5 class="modal-title" id="viewInscriptionLabel">Détails de l’inscription</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                        </div>
                        <div class="modal-body">
                            <div id="inscriptionDetails" class="p-3 text-center">
                                <div class="spinner-border text-warning" role="status">
                                    <span class="visually-hidden">Chargement...</span>
                                </div>
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
        new DataTable('#table-jury', {
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('.viewInscriptionBtn').on('click', function() {
                const id = $(this).data('id');
                const modal = $('#viewInscriptionModal');
                const detailsContainer = $('#inscriptionDetails');

                // Affiche la modale avec le loader
                modal.modal('show');
                detailsContainer.html(
                    '<div class="spinner-border text-warning" role="status"><span class="visually-hidden">Chargement...</span></div>'
                );

                // Requête AJAX
                $.ajax({
                    url: "{{ url('/inscriptioncontacts') }}/" + id + "/details",
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        if (data.error) {
                            detailsContainer.html('<div class="alert alert-danger">' + data
                                .error + '</div>');
                            return;
                        }

                        let html = `
                    <table class="table table-bordered">
                        <tr><th>Structure</th><td>${data.structure ?? ''}</td></tr>
                        <tr><th>Nom</th><td>${data.nom ?? ''}</td></tr>
                        <tr><th>Téléphone</th><td>${data.telephone ?? ''}</td></tr>
                        <tr><th>Email</th><td>${data.email ?? ''}</td></tr>
                        <tr><th>Commentaire</th><td>${data.commentaire ?? ''}</td></tr>
                    </table>
                `;
                        detailsContainer.html(html);
                    },
                    error: function(xhr) {
                        detailsContainer.html(
                            '<div class="alert alert-danger">Erreur lors du chargement des données.</div>'
                        );
                        console.error(xhr.responseText);
                    }
                });
            });
        });
    </script>
@endpush
