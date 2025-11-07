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
                                <table  class="table datatables align-middle" id="table-inscriptions">
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
                                                <td class="text-center">
                                                    <div class="btn-group">
                                                        {{-- Bouton Voir --}}
                                                        <a href="{{ route('formulaires.show', $inscription->id) }}"
                                                            class="btn btn-warning btn-sm" title="Voir les détails">
                                                            <i class="bi bi-eye"></i>
                                                        </a>

                                                        {{-- Bouton menu déroulant --}}
                                                        <button type="button"
                                                            class="btn btn-light btn-sm dropdown-toggle dropdown-toggle-split"
                                                            data-bs-toggle="dropdown" aria-expanded="false">
                                                            <span class="visually-hidden">Actions</span>
                                                        </button>

                                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                                            {{-- Lien Modifier --}}
                                                            <li>
                                                                <a href="{{ route('formulaires.edit', $inscription->id) }}"
                                                                    class="dropdown-item text-primary"
                                                                    title="Modifier les détails">
                                                                    <i class="bi bi-pencil-square me-2"></i> Modifier
                                                                </a>
                                                            </li>

                                                            {{-- Formulaire Supprimer --}}
                                                            <li>
                                                                <form
                                                                    action="{{ route('formulaires.destroy', $inscription->id) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit"
                                                                        class="dropdown-item text-danger show_confirm">
                                                                        <i class="bi bi-trash me-2"></i> Supprimer
                                                                    </button>
                                                                </form>
                                                            </li>
                                                        </ul>
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
        </section>

    @endcan

@endsection
@push('scripts')
    <script>
        new DataTable('#table-inscriptions', {
            layout: {
                topStart: {
                    buttons: ['csv', 'excel', 'print'],
                }
            },
            "order": [
                [0, 'desc']
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
