@extends('layout.user-layout')
@section('title', 'ONFP | INSCRIPTION DÉTAILLÉE')
@section('space-work')
    @can('inscriptioncontact-view')
        <section class="section register">
            <div class="row justify-content-center">

                {{-- <span class="d-flex mt-2 align-items-baseline"><a href="{{ url('/formulaires') }}" class="btn btn-info btn-sm"
                        title="retour"><i class="bi bi-arrow-counterclockwise"></i></a>&nbsp;
                    <p> | Retour</p>
                </span> --}}
                {{-- <h4 class="card-title">
                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 p-3 bg-light rounded shadow-sm">
                        <span>{{ $projet->sigle }}</span>
                        <span class="{{ $statut }} text-white">{{ $statut }}</span>
                    </div>
                </h4> --}}
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-primary">
                        <tr>
                            <th style="width: 50px;" class="text-center">N°</th>
                            <th>Région</th>
                            {{-- <th>Effectif</th> --}}
                            <th style="width: 50px;" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($groupes as $index => $items)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $index }}</td>
                                {{-- <td>{{ number_format($items->count(), 0, '', ' ') }}</td> --}}
                                <td class="text-center">
                                    <div class="btn-group">
                                        {{-- Bouton Voir --}}
                                        <a href="{{ route('formulaires.showregion', $index) }}"
                                            class="btn btn-warning btn-sm" title="Voir les détails">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{-- Tableau inscriptions --}}
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="pt-1">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">

                                {{-- Titre à gauche --}}
                                <div class="d-flex align-items-center gap-2">
                                    <h6 class="mb-0 text-muted fw-semibold text-uppercase">
                                        Liste des demandes prises en charge
                                    </h6>
                                </div>

                                {{-- Total au centre --}}
                                @php
                                    $affichees = $formulaires?->count(); // à adapter si tu fais une pagination
                                    $total = $totalFormulaires ?? ($formulaires?->total() ?? $formulaires?->count()); // en cas de pagination avec ->total()
                                @endphp

                                <div class="d-flex align-items-center gap-2 text-info fw-semibold">
                                    <i class="bi bi-list-ul me-1"></i>
                                    <span>
                                        Affichage :
                                        <span class="text-dark">{{ $affichees }}</span>
                                        sur
                                        <span class="text-dark">{{ $total }}</span> demandes
                                    </span>
                                </div>

                                {{-- Boutons à droite --}}
                                @can('formulaire-create')
                                    <div class="d-flex align-items-center gap-2">
                                        <a href="{{ route('formulaire.create') }}" class="btn btn-sm btn-primary">
                                            Ajouter
                                        </a>
                                        <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="modal"
                                            data-bs-target="#generate_rapport">
                                            Rechercher plus
                                        </button>
                                    </div>
                                @endcan

                            </div>
                        </div>

                        {{-- Export opérateurs en Excel --}}
                        {{-- <span class="mb-3 d-inline-block">
                            <a href="{{ route('prisencharge.excel') }}" class="btn btn-success btn-sm"
                                title="Exporter la liste">
                                <i class="bi bi-file-earmark-excel"></i> Exporter prises en charge (Excel)
                            </a>
                        </span> --}}

                        <div class="table-responsive">
                            <table class="table datatables align-middle" id="table-inscriptions">
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
                                            @foreach (array_keys($labels) as $field)
                                                <td>
                                                    @if (in_array($field, ['cin_file', 'facture_file', 'cv', 'diplome']))
                                                        @php
                                                            $fileUrl = $inscription->getFileUrl($field);
                                                        @endphp
                                                        @if ($fileUrl)
                                                            <a href="{{ $fileUrl }}" target="_blank"
                                                                class="btn btn-outline-secondary btn-sm" title="Télécharger">
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
                                                                class="dropdown-item text-primary" title="Modifier les détails">
                                                                <i class="bi bi-pencil-square me-2"></i> Modifier
                                                            </a>
                                                        </li>

                                                        {{-- Formulaire Supprimer --}}
                                                        <li>
                                                            <form action="{{ route('formulaires.destroy', $inscription->id) }}"
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

            <div class="modal fade" id="generate_rapport" tabindex="-1" role="dialog" aria-labelledby="generate_rapportLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Générer une recherche<span class="text-danger mx-1">*</span></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form method="post" action="{{ route('formulaires.report') }}">
                            @csrf
                            <div class="modal-body">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="prenom" class="form-label">Prénom</label>
                                                    <input type="text" name="prenom" value="{{ old('prenom') }}"
                                                        class="form-control form-control-sm @error('prenom') is-invalid @enderror"
                                                        id="prenom" placeholder="Prénom">
                                                    @error('prenom')
                                                        <span class="invalid-feedback" role="alert">
                                                            <div>{{ $message }}</div>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="nom" class="form-label">Nom</label>
                                                    <input type="text" name="nom" value="{{ old('nom') }}"
                                                        class="form-control form-control-sm @error('nom') is-invalid @enderror"
                                                        id="nom" placeholder="Nom">
                                                    @error('nom')
                                                        <span class="invalid-feedback" role="alert">
                                                            <div>{{ $message }}</div>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="cin" class="form-label">N° CIN</label>
                                                    <input name="cin" type="text"
                                                        class="form-control form-control-sm @error('cin') is-invalid @enderror"
                                                        id="cin2" value="{{ old('cin') }}" autocomplete="off"
                                                        placeholder="Ex: 1 099 2005 00012" minlength="16" maxlength="17">
                                                    @error('cin')
                                                        <span class="invalid-feedback" role="alert">
                                                            <div>{{ $message }}</div>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="telephone" class="form-label">Téléphone</label>
                                                    <input name="telephone" type="text" maxlength="12"
                                                        class="form-control form-control-sm @error('telephone') is-invalid @enderror"
                                                        id="telephone_responsable" value="{{ old('telephone') }}"
                                                        autocomplete="tel" placeholder="XX:XXX:XX:XX">
                                                    @error('telephone')
                                                        <span class="invalid-feedback" role="alert">
                                                            <div>{{ $message }}</div>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="email" class="form-label">Email</label>
                                                    <input type="email" name="email" value="{{ old('email') }}"
                                                        class="form-control form-control-sm @error('email') is-invalid @enderror"
                                                        id="email" placeholder="email@email.com">
                                                    @error('email')
                                                        <span class="invalid-feedback" role="alert">
                                                            <div>{{ $message }}</div>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="lieu_naissance" class="form-label">Lieu naissance</label>
                                                    <input type="text" name="lieu_naissance"
                                                        value="{{ old('lieu_naissance') }}"
                                                        class="form-control form-control-sm @error('lieu_naissance') is-invalid @enderror"
                                                        id="lieu_naissance" placeholder="Lieu de naissance">
                                                    @error('lieu_naissance')
                                                        <span class="invalid-feedback" role="alert">
                                                            <div>{{ $message }}</div>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary btn-sm"
                                            data-bs-dismiss="modal">Fermer</button>
                                        <div class="text-center">
                                            <button type="submit"
                                                class="btn btn-primary btn-block submit_rapport btn-sm">Rechercher</button>
                                        </div>
                                    </div>
                                </div>
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
        new DataTable('#table-inscriptions', {
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
