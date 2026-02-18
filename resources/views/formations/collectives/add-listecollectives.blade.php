@extends('layout.user-layout')
@section('title', 'Formation | Choisir demandeurs collectifs')
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
                            role="alert"><strong>{{ $error }}</strong></div>
                    @endforeach
                @endif
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12 pt-0">
                                <span class="d-flex mt-0 align-items-baseline"><a
                                        href="{{ route('formations.show', $formation) }}" class="btn btn-success btn-sm"
                                        title="retour"><i class="bi bi-arrow-counterclockwise"></i></a>&nbsp;
                                    <p> | Liste des formations</p>
                                </span>
                            </div>
                        </div>
                        {{-- <h5><u><b>Module</b></u> : {{ $collectivemodule?->module }}</h5>
                        <h5><u><b>Région</b></u> : {{ $localite?->nom }}</h5>
                        <h5><u><b>Sélectionnés</b></u> : {{ $candidatsretenus?->count() ?? '' }}</h5> --}}

                        <div class="p-3 mb-4 border rounded bg-light shadow-sm">
                            <div class="row text-center fw-semibold">
                                <div class="col-md-4 mb-2">
                                    <span class="text-secondary">Région</span><br>
                                    <span class="fs-5 text-dark">{{ $localite?->nom ?? 'Aucune' }}</span>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <span class="text-secondary">Module</span><br>
                                    <span class="fs-5 text-dark">{{ $collectivemodule?->module ?? 'Aucun' }}</span>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <span class="text-secondary">Effectif</span><br>
                                    <span class="fs-5 text-dark">{{ $candidatsretenus?->count() ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                        <form method="post"
                            action="{{ url('formationdemandeurscollectives', ['$idformation' => $formation->id, '$idcollectivemodule' => $formation->collectivemodule->id, '$idlocalite' => $formation->departement->region->id]) }}"
                            enctype="multipart/form-data" class="row g-3">
                            @csrf
                            @method('PUT')
                            <div class="row mb-3 border rounded bg-light shadow-sm p-3">
                                <div class="form-check col-md-2 pt-5">
                                    <label for="#">Choisir tout</label>
                                    <input type="checkbox" class="form-check-input" id="checkAll">
                                </div>
                                <div></div>
                                <div class="form-check col-md-12">
                                    <table class="m-2 table datatables align-middle" id="table-individuelles">
                                        <thead>
                                            <tr>
                                                <th scope="col"></th>
                                                {{-- <th scope="col">CIN</th> --}}
                                                {{-- <th scope="col">Civilité</th> --}}
                                                {{-- <th scope="col">Prénom</th> --}}
                                                <th scope="col">Name</th>
                                                <th scope="col">Date naissance</th>
                                                <th scope="col">Lieu naissance</th>
                                                <th scope="col">Téléphone</th>
                                                <th scope="col">Niveau étude</th>
                                                {{-- <th scope="col">ID</th> --}}
                                                {{-- <th scope="col">Module</th> --}}
                                                <th class="text-center" width="5%">Statut</th>
                                                <th class="text-center" width="5%"><i class="bi bi-gear"></i></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i = 1; ?>
                                            @foreach ($listecollectives as $listecollective)
                                                <tr>
                                                    <td>
                                                        {{--  <label for="liste_{{ $listecollective->id }}"> --}}
                                                        <input type="checkbox" name="listecollectives[]"
                                                            value="{{ $listecollective->id }}"
                                                            {{ in_array($listecollective->formations_id, $listecollectiveFormation) ? 'checked' : '' }}
                                                            class="form-check-input @error('listecollectives') is-invalid @enderror">
                                                        {{ $i++ }}
                                                        @error('listecollectives')
                                                            <span class="invalid-feedback" role="alert">
                                                                <div>{{ $message }}</div>
                                                            </span>
                                                        @enderror
                                                        {{--  </label> --}}
                                                    </td>
                                                    {{-- <td>{{ $listecollective?->cin }}</td>
                                                    <td>{{ $listecollective?->civilite }}</td> --}}
                                                    <td>{{ $listecollective?->civilite . ' ' . $listecollective?->prenom . ' ' . $listecollective?->nom }}
                                                    </td>
                                                    {{-- <td>{{ $listecollective?->nom }}</td> --}}
                                                    <td>{{ $listecollective?->date_naissance->format('d/m/Y') }}
                                                    </td>
                                                    <td>{{ $listecollective?->lieu_naissance }}</td>
                                                    <td>{{ $listecollective?->telephone }}</td>
                                                    <td>{{ $listecollective?->niveau_etude }}</td>
                                                    {{-- <td>{{ $listecollective?->formations_id }}</td> --}}
                                                    {{-- <td>{{ $listecollective?->collectivemodule?->module }}</td> --}}
                                                    <td>
                                                        <span
                                                            class="{{ $listecollective?->statut }}">{{ $listecollective?->statut }}</span>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="d-flex align-items-baseline">
                                                            <a href="{{ route('listecollectives.show', $listecollective) }}"
                                                                class="btn btn-primary btn-sm" title="voir détails"
                                                                target="_blank"><i class="bi bi-eye"></i></a>
                                                            <div class="filter">
                                                                <a class="icon" href="#"
                                                                    data-bs-toggle="dropdown"><i
                                                                        class="bi bi-three-dots"></i></a>
                                                                <ul
                                                                    class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                                                    <li><a class="dropdown-item btn btn-sm"
                                                                            href="{{ route('listecollectives.edit', $listecollective) }}"
                                                                            class="mx-1" title="Modifier"><i
                                                                                class="bi bi-pencil"></i>Modifier</a>
                                                                    </li>
                                                                    {{-- <form
                                                                                action="{{ route('listecollectives.destroy', $listecollective->id) }}"
                                                                                method="post">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                                <button type="submit"
                                                                                    class="dropdown-item show_confirm"
                                                                                    title="Supprimer"><i
                                                                                        class="bi bi-trash"></i>Supprimer</button>
                                                                            </form> --}}
                                                                </ul>
                                                            </div>
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-outline-primary btn-sm"><i
                                                class="bi bi-check2-circle"></i>&nbsp;Sélectionner</button>
                                    </div>
                                </div>
                            </div>
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
            ordering: false, // désactive le tri automatique
            paging: false, // 🔹 Désactive la pagination
            info: false, // 🔹 Supprime le texte "Affichage de X à Y..."
            pageLength: -1, // 🔹 Affiche toutes les lignes
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
