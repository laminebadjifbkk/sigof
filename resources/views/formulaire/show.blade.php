@extends('layout.user-layout')
@section('title', 'ONFP | INSCRIPTION DÉTAILLÉE')
@section('space-work')
    @can('inscriptioncontact-view')

        <section class="section register">
            <div class="row justify-content-center">
                <div class="col-12">

                    {{-- Alertes --}}
                    @if ($message = Session::get('status'))
                        <div class="alert alert-success bg-success text-light border-0 alert-dismissible fade show"
                            role="alert">
                            <strong>{{ $message }}</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if ($message = Session::get('danger'))
                        <div class="alert alert-danger bg-danger text-light border-0 alert-dismissible fade show" role="alert">
                            <strong>{{ $message }}</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if ($errors->any())
                        @foreach ($errors->all() as $error)
                            <div class="alert alert-danger bg-danger text-light border-0 alert-dismissible fade show"
                                role="alert">
                                <strong>{{ $error }}</strong>
                            </div>
                        @endforeach
                    @endif

                    {{-- Tableau inscriptions --}}
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title mb-3">INSCRIPTIONS DÉTAILLÉES</h5>
                            <div class="table-responsive">
                                <table class="table datatables table-bordered table-hover align-middle text-center"
                                    id="table-inscriptions">
                                    <thead class="table-primary">
                                        <tr>
                                            @foreach ($labels as $key => $label)
                                                <th>{{ $label }}</th>
                                            @endforeach
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($formulaires as $inscription)
                                            <tr>
                                                {{-- @foreach (array_keys($labels) as $field)
                                                    <td>
                                                        @if (in_array($field, ['cin_file', 'facture_file', 'cv', 'diplome']))
                                                            @php
                                                                $fileUrl = $inscription->getFileUrl($field);
                                                            @endphp
                                                            @if ($fileUrl)
                                                                <a href="{{ $fileUrl }}" target="_blank"
                                                                    class="btn btn-outline-secondary btn-sm"
                                                                    title="Télécharger">
                                                                    <i class="bi bi-download"></i>
                                                                </a>
                                                            @else
                                                                -
                                                            @endif
                                                        @else
                                                            {{ $inscription->$field ?? '-' }}
                                                        @endif
                                                    </td>
                                                @endforeach --}}
                                                @foreach (array_keys($labels) as $field)
                                                    <td>
                                                        @if (in_array($field, ['cin_file', 'facture_file', 'cv', 'diplome']))
                                                            @php
                                                                $fileUrl = $inscription->getFileUrl($field);
                                                            @endphp
                                                            @if ($fileUrl)
                                                                <a href="{{ $fileUrl }}" target="_blank"
                                                                    class="btn btn-outline-secondary btn-sm"
                                                                    title="Télécharger">
                                                                    <i class="bi bi-download"></i>
                                                                </a>
                                                            @else
                                                                -
                                                            @endif

                                                            {{-- Cas spécifique pour la date de naissance --}}
                                                        @elseif ($field === 'date_naissance' && $inscription->date_naissance)
                                                            {{ \Carbon\Carbon::parse($inscription->date_naissance)->format('d/m/Y') }}

                                                            {{-- Tous les autres champs normaux --}}
                                                        @else
                                                            {{ $inscription->$field ?? '-' }}
                                                        @endif
                                                    </td>
                                                @endforeach
                                                <td>
                                                    <div class="btn-group">
                                                        <button class="btn btn-warning btn-sm viewInscriptionBtn"
                                                            data-id="{{ $inscription->id }}">
                                                            <i class="bi bi-eye"></i>
                                                        </button>
                                                        <button class="btn btn-info btn-sm editInscriptionBtn"
                                                            data-id="{{ $inscription->id }}">
                                                            <i class="bi bi-pencil-square"></i>
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

                    {{-- Modal Visualisation --}}
                    <div class="modal fade" id="viewInscriptionModal" tabindex="-1" aria-labelledby="viewInscriptionLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header bg-warning text-white">
                                    <h5 class="modal-title" id="viewInscriptionLabel">Détails de l’inscription</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Fermer"></button>
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

                    {{-- Modal Modification --}}
                    <div class="modal fade" id="editInscriptionModal" tabindex="-1" aria-labelledby="editInscriptionLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header bg-warning text-white">
                                    <h5 class="modal-title" id="editInscriptionLabel">Modifier l’inscription</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Fermer"></button>
                                </div>
                                <form method="POST" id="editInscriptionForm" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <div class="row g-3">
                                            @foreach ($labels as $field => $label)
                                                @if (in_array($field, ['cin_file', 'facture_file', 'cv', 'diplome']))
                                                    <div class="col-md-6">
                                                        <label class="form-label">{{ $label }}</label>
                                                        <input type="file" name="{{ $field }}" class="form-control">
                                                    </div>
                                                @elseif($field === 'email')
                                                    <div class="col-md-6">
                                                        <label class="form-label">{{ $label }}</label>
                                                        <input type="email" name="{{ $field }}" class="form-control"
                                                            required>
                                                    </div>
                                                @else
                                                    <div class="col-md-6">
                                                        <label class="form-label">{{ $label }}</label>
                                                        <input type="text" name="{{ $field }}" class="form-control"
                                                            {{ in_array($field, ['telephone', 'telephone_secondaire', 'montant_inscription', 'montant_mensualite', 'montant_unique', 'duree']) ? '' : 'required' }}>
                                                    </div>
                                                @endif
                                            @endforeach
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

                </div>
            </div>
        </section>

    @endcan
@endsection

@push('scripts')
    <script>
        // DataTable
        new DataTable('#table-inscriptions', {
            ordering: false,
            language: {
                "sProcessing": "Traitement en cours...",
                "sSearch": "Rechercher&nbsp;:",
                "sLengthMenu": "Afficher _MENU_ éléments",
                "sInfo": "Affichage de l'élément _START_ à _END_ sur _TOTAL_ éléments",
                "sInfoEmpty": "Affichage de l'élément 0 à 0 sur 0 élément",
                "sInfoFiltered": "(filtré de _MAX_ éléments au total)",
                "oPaginate": {
                    "sFirst": "Premier",
                    "sPrevious": "Précédent",
                    "sNext": "Suivant",
                    "sLast": "Dernier"
                }
            }
        });

        // Voir détails
        $('#table-inscriptions').on('click', '.viewInscriptionBtn', function() {
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
                        detailsContainer.html('<div class="alert alert-danger">' + data.error +
                            '</div>');
                        return;
                    }
                    let html = '<table class="table table-bordered">';
                    for (const [key, label] of Object.entries(@json($labels))) {
                        html += '<tr><th>' + label + '</th><td>';
                        if (['cin_file', 'facture_file', 'cv', 'diplome'].includes(key) && data[key]) {
                            html +=
                                '<a href="{{ url('
                                                                                                                                                                                    ') }}/storage/' +
                                data[
                                    key] +
                                '" target="_blank" class="btn btn-sm btn-outline-primary">Télécharger</a>';
                        } else {
                            html += data[key] ?? '-';
                        }
                        html += '</td></tr>';
                    }
                    html += '</table>';
                    detailsContainer.html(html);
                },
                error: function(xhr) {
                    detailsContainer.html(
                        '<div class="alert alert-danger">Erreur lors du chargement des données.</div>'
                    );
                }
            });
        });

        // Modifier
        $('#table-inscriptions').on('click', '.editInscriptionBtn', function() {
            const id = $(this).data('id');
            const modal = $('#editInscriptionModal');
            const form = $('#editInscriptionForm');

            // Mettre à jour l'action du formulaire
            form.attr('action', "{{ url('/inscriptioncontacts') }}/" + id);

            // Afficher la modale
            modal.modal('show');

            // Charger les données via AJAX
            $.get("{{ url('/inscriptioncontacts') }}/" + id + "/details", function(data) {
                // Parcourir tous les champs
                for (const [key, label] of Object.entries(@json($labels))) {
                    const input = form.find('[name="' + key + '"]');
                    if (input.length) {
                        if (['cin_file', 'facture_file', 'cv', 'diplome'].includes(key)) {
                            // Les fichiers ne sont pas remplis par JS, on laisse vide pour upload
                            input.val('');
                        } else {
                            input.val(data[key] ?? '');
                        }
                    }
                }
            });
        });
    </script>
@endpush
