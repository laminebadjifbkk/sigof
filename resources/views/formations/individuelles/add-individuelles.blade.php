@extends('layout.user-layout')
@section('title', 'Choisir bénéficiaires à la formation en ' . $formation->module->name)
@section('space-work')
    <section class="section">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                @if ($message = Session::get('status'))
                    <div class="alert alert-success bg-success text-light border-0 alert-dismissible fade show"
                        role="alert">
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
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12 pt-0">
                                <span class="d-flex mt-0 align-items-baseline">
                                    <a href="{{ route('formations.show', $formation) }}" class="btn btn-success btn-sm"
                                        title="Retour">
                                        <i class="bi bi-arrow-counterclockwise"></i>
                                    </a>&nbsp;
                                    <p> | Liste des bénéficiaires</p>
                                </span>
                            </div>
                        </div>
                        <div class="p-1 mb-4 border rounded bg-light shadow-sm">
                            <div class="row text-center fw-semibold">
                                <div class="col-md-4 mb-2">
                                    <span class="text-secondary">📍 Région</span><br>
                                    <span class="fs-5 text-dark">{{ $region->nom ?? 'Aucune' }}</span>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <span class="text-secondary">📘 Module</span><br>
                                    <span class="fs-5 text-dark">{{ $formation?->module?->name ?? 'Aucun' }}</span>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <span class="text-secondary">👥 Effectif</span><br>
                                    <span class="fs-5 text-dark">{{ $candidatsretenus?->count() ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                        <form method="post"
                            action="{{ url('formationdemandeurs', ['idformation' => $formation->id, 'idmodule' => $formation->module->id, 'idlocalite' => $formation->departement->id]) }}"
                            enctype="multipart/form-data" class="row g-3 mt-2">
                            @csrf
                            @method('PUT')

                            @if ($individuelles->isNotEmpty())
                                <div class="form-check col-md-12 border rounded bg-light shadow-sm p-3">
                                    <table class="m-2 table datatables align-middle" id="table-individuelles">
                                        <thead>
                                            <tr>
                                                <th><input type="checkbox" class="form-check-input" id="checkAll"> N°</th>
                                                {{-- <th>Civilité</th> --}}
                                                <th>Name</th>
                                                {{-- <th>Date naissance</th>
                                                <th>Lieu naissance</th> --}}
                                                <th>Département</th>
                                                <th>Module</th>
                                                <th>Note</th>
                                                <th>Statut</th>
                                                @if (!empty($formation->projets_id))
                                                    <th>Projet</th>
                                                @endif
                                                <th width='5%'><i class="bi bi-gear"></i></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i = 1; ?>
                                            @foreach ($individuelles as $individuelle)
                                                {{-- @if (!empty($individuelle?->numero)) --}}
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" name="individuelles[]"
                                                            value="{{ $individuelle->id }}"
                                                            {{ in_array($individuelle->formations_id, $individuelleFormation) ? 'checked' : '' }}
                                                            {{ in_array($individuelle->formations_id, $individuelleFormationCheck) ? 'disabled' : '' }}
                                                            class="form-check-input @error('individuelles') is-invalid @enderror">
                                                        {{ $i++ }}
                                                        @error('individuelles')
                                                            <span class="invalid-feedback"
                                                                role="alert">{{ $message }}</span>
                                                        @enderror
                                                    </td>
                                                    {{-- <td>{{ $individuelle?->user?->civilite }}</td> --}}
                                                    <td>
                                                        {{ $individuelle?->user?->firstname . ' ' . $individuelle?->user?->name }}
                                                        - {{ $individuelle?->user?->date_naissance?->format('d/m/Y') }}
                                                        - {{ $individuelle?->user?->lieu_naissance }}
                                                    </td>
                                                    {{-- <td>{{ $individuelle?->user?->firstname . ' ' . $individuelle?->user?->name }}
                                                    </td>
                                                    <td>{{ $individuelle?->user->date_naissance?->format('d/m/Y') }}
                                                    </td>
                                                    <td>{{ $individuelle?->user->lieu_naissance }}</td> --}}
                                                    <td>{{ $individuelle?->departement->nom }}</td>
                                                    <td>{{ $individuelle?->module->name }}</td>
                                                    <td>{{ $individuelle?->note }}</td>
                                                    <td><span
                                                            class="{{ $individuelle?->statut }}">{{ $individuelle?->statut }}</span>
                                                    </td>
                                                    @if (!empty($formation->projets_id))
                                                        <td>{{ $individuelle?->projet?->sigle }}</td>
                                                    @endif
                                                    <td>
                                                        <span class="d-flex align-items-baseline">
                                                            <a href="{{ route('individuelles.show', $individuelle) }}"
                                                                class="btn btn-primary btn-sm" title="Voir détails"
                                                                target="_blank">
                                                                <i class="bi bi-eye"></i>
                                                            </a>
                                                            <div class="filter">
                                                                <a class="icon" href="#"
                                                                    data-bs-toggle="dropdown"><i
                                                                        class="bi bi-three-dots"></i></a>
                                                                <ul
                                                                    class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                                                    <li>
                                                                        <a class="dropdown-item btn btn-sm"
                                                                            href="{{ route('individuelles.edit', $individuelle) }}"
                                                                            title="Modifier">
                                                                            <i class="bi bi-pencil"></i> Modifier
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <form
                                                                            action="{{ route('individuelles.destroy', $individuelle) }}"
                                                                            method="post">
                                                                            @csrf
                                                                            <button type="submit"
                                                                                class="dropdown-item show_confirm"
                                                                                title="Supprimer">
                                                                                <i class="bi bi-trash"></i> Supprimer
                                                                            </button>
                                                                        </form>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </span>
                                                    </td>
                                                </tr>
                                                {{-- @endif --}}
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-outline-primary btn-sm"><i
                                                class="bi bi-check2-circle"></i> Sélectionner</button>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-warning mt-5">Aucun demandeur disponible pour le moment !!!</div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        new DataTable('#table-individuelles', {
            /* layout: {
                topStart: {
                    buttons: ['csv', 'excel', 'print'],
                }
            }, */
            paging: false, // 🔹 Désactive la pagination
            info: false, // 🔹 Supprime le texte "Affichage de X à Y..."
            pageLength: -1, // 🔹 Affiche toutes les lignes
            "order": [
                [4, 'desc']
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
