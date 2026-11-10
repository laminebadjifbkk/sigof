@extends('layout.user-layout')
@section('title', 'ONFP | INSCRIPTION PARTENAIRES')
@section('space-work')
    @can('inscriptioncontact-view')
        <section class="section register">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="pagetitle">
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

                            @forelse ($inscriptions as $year => $months)
                                <div class="accordion mb-3" id="accordion-{{ $loop->index }}">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }} fw-bold"
                                                type="button" data-bs-toggle="collapse"
                                                data-bs-target="#year-{{ $loop->index }}">
                                                <i class="bi bi-calendar3 me-2"></i> {{ $year }}
                                                <span class="badge bg-primary ms-2">{{ $months->flatten()->count() }}</span>
                                            </button>
                                        </h2>
                                        <div id="year-{{ $loop->index }}"
                                            class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}">
                                            <div class="accordion-body">

                                                @foreach ($months as $month => $items)
                                                    @php
                                                        $monthName =
                                                            $month === '00'
                                                                ? 'Date non disponible'
                                                                : ucfirst(
                                                                    \Carbon\Carbon::create(2000, (int) $month, 1)
                                                                        ->locale('fr')
                                                                        ->translatedFormat('F'),
                                                                );
                                                    @endphp

                                                    <h6 class="mt-4 mb-2 text-primary border-bottom pb-1">
                                                        <i class="bi bi-calendar-month me-1"></i>
                                                        {{ $monthName }} {{ $year }}
                                                        <span class="badge bg-secondary">{{ $items->count() }}</span>
                                                    </h6>

                                                    <div class="table-responsive">
                                                        <table
                                                            class="table table-inscriptions table-bordered table-hover align-middle"
                                                            data-title="Partenaires - {{ $monthName }} {{ $year }}">
                                                            <thead class="table-primary text-center">
                                                                <tr>
                                                                    <th width="30%">Structure</th>
                                                                    <th>Nom</th>
                                                                    <th>Fonction</th>
                                                                    <th>Email</th>
                                                                    <th>Téléphone</th>
                                                                    <th>Date</th>
                                                                    <th>Actions</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($items as $inscription)
                                                                    @include('inscriptioncontact._row', [
                                                                        'inscription' => $inscription,
                                                                    ])
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                @endforeach

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="alert alert-info">Aucune inscription.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal de visualisation -->
            <div class="modal fade" id="viewInscriptionModal" tabindex="-1" aria-labelledby="viewInscriptionLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-warning text-white">
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

            <!-- Modal de modification -->
            <div class="modal fade" id="editInscriptionModal" tabindex="-1" aria-labelledby="editInscriptionLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-warning text-white">
                            <h5 class="modal-title" id="editInscriptionLabel">Modifier l’inscription</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                        </div>
                        {{-- L'action est définie dynamiquement par le JS à l'ouverture de la modale --}}
                        <form method="POST" action="#" id="editInscriptionForm">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <label class="form-label">Structure</label>
                                        <select name="structure" id="editStructure" class="form-control select2" required>
                                            <option value="">-- Sélectionnez une structure --</option>
                                            @foreach ($structures as $group => $options)
                                                <optgroup label="{{ $group }}">
                                                    @foreach ($options as $option)
                                                        <option value="{{ $option }}">{{ $option }}</option>
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Nom</label>
                                        <input type="text" name="nom" class="form-control" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Fonction</label>
                                        <input type="text" name="fonction" class="form-control" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Téléphone</label>
                                        <input type="text" name="telephone" class="form-control" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control" required>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Commentaire</label>
                                        <textarea name="commentaire" class="form-control" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary btn-sm"
                                    data-bs-dismiss="modal">Fermer</button>
                                <button type="submit" class="btn btn-primary btn-sm">Enregistrer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    @endcan
@endsection

@push('scripts')
    <script>
        const dataTableLanguage = {
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
                    _: "%d lignes sélectionnées",
                    0: "Aucune ligne sélectionnée",
                    1: "1 ligne sélectionnée"
                }
            }
        };

        // Un DataTable par tableau (un tableau par mois)
        document.querySelectorAll('.table-inscriptions').forEach(function(el) {
            const title = el.dataset.title || 'Partenaires';

            new DataTable(el, {
                ordering: false, // désactive le tri automatique
                layout: {
                    topStart: {
                        buttons: [{
                                extend: 'csv',
                                title: title
                            },
                            {
                                extend: 'excel',
                                title: title
                            },
                            {
                                extend: 'print',
                                title: title
                            }
                        ],
                    }
                },
                language: dataTableLanguage
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Bouton Voir (délégation d’événement sur le document)
            $(document).on('click', '.viewInscriptionBtn', function() {
                const id = $(this).data('id');
                const modal = $('#viewInscriptionModal');
                const detailsContainer = $('#inscriptionDetails');

                modal.modal('show');
                detailsContainer.html(
                    '<div class="spinner-border text-warning" role="status"><span class="visually-hidden">Chargement...</span></div>'
                );

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
                        <tr><th>Fonction</th><td>${data.fonction ?? ''}</td></tr>
                        <tr><th>Téléphone</th><td>${data.telephone ?? ''}</td></tr>
                        <tr><th>Email</th><td>${data.email ?? ''}</td></tr>
                        <tr><th>Commentaire</th><td>${data.commentaire ?? ''}</td></tr>
                    </table>`;
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

            // Bouton Modifier (délégation d’événement sur le document)
            $(document).on('click', '.editInscriptionBtn', function() {
                const id = $(this).data('id');
                const modal = $('#editInscriptionModal');
                const form = $('#editInscriptionForm');

                $.get("{{ url('/inscriptioncontacts') }}/" + id + "/details", function(data) {
                    form.attr('action', "{{ url('/inscriptioncontacts') }}/" + id);
                    form.find('select[name="structure"]').val(data.structure).trigger('change');
                    form.find('input[name="nom"]').val(data.nom);
                    form.find('input[name="fonction"]').val(data.fonction);
                    form.find('input[name="telephone"]').val(data.telephone);
                    form.find('input[name="email"]').val(data.email);
                    form.find('textarea[name="commentaire"]').val(data.commentaire);
                    modal.modal('show');
                });
            });
        });
    </script>
@endpush
