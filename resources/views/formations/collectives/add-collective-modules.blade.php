@extends('layout.user-layout')
@section('title', 'Formation collective - Sélectionner un module')
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
                                    <p> | Détails formation</p>
                                </span>
                            </div>
                        </div>
                        {{--  <h5><u><b>MODULE</b>:</u> {{ $formation?->collectivemodule?->module  ?? 'Aucun module' }}</h5>
                        <h5><u><b>REGION</b>:</u> {{ $localite->nom ?? 'Aucune région' }}</h5> --}}

                        <div class="p-1 mb-4 border rounded bg-light shadow-sm">
                            <div class="row text-center fw-semibold">
                                <div class="col-md-4 mb-2">
                                    <span class="text-secondary">📍 Région</span><br>
                                    <span class="fs-5 text-dark">{{ $localite->nom ?? 'Aucune' }}</span>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <span class="text-secondary">📘 Module</span><br>
                                    <span
                                        class="fs-5 text-dark">{{ $formation?->collectivemodule?->module ?? 'Aucun' }}</span>
                                </div>
                                @if (!empty($formation?->collectivemodule?->module))
                                    <div class="col-md-4 mb-2">
                                        <span class="text-secondary">👥 Structure</span><br>
                                        <span
                                            class="fs-5 text-dark">{{ $formation?->collectivemodule?->collective?->name ?? 'Aucun' }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <form method="post"
                            action="{{ url('formationcollectivemodules', ['$idformation' => $formation->id]) }}"
                            enctype="multipart/form-data" class="row g-3">
                            @csrf
                            @method('PUT')
                            <div class="row mb-3 border rounded bg-light shadow-sm p-3">
                                <div class="form-check col-md-12 pt-5">
                                    <table class="m-2 table datatables align-middle" id="table-modules">
                                        <thead>
                                            <tr>
                                                <th width="30%">Structure</th>
                                                {{-- <th>E-mail</th>
                                                <th>Téléphone</th>
                                                <th>Localité</th> --}}
                                                <th>Modules</th>
                                                <th class="text-center" scope="col">Effectif</th>
                                                <th class="text-center" scope="col">Formations</th>
                                                <th class="text-center" scope="col">Statut</th>
                                                <th width="3%"><i class="bi bi-gear"></i></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i = 1; ?>
                                            @foreach ($collectivemodules as $collectivemodule)
                                                <tr>
                                                    <td>
                                                        <input type="radio" name="collectivemodule"
                                                            value="{{ $collectivemodule?->id }}"
                                                            {{ in_array($collectivemodule?->id, $collectivemoduleFormation) ? 'checked' : '' }}
                                                            class="form-check-input @error('collectivemodule') is-invalid @enderror">
                                                        @error('collectivemodule')
                                                            <span class="invalid-feedback" role="alert">
                                                                <div>{{ $message }}</div>
                                                            </span>
                                                        @enderror
                                                        {{ $collectivemodule?->collective?->name . ' (' . $collectivemodule?->collective?->sigle . ')' }}
                                                    </td>
                                                    {{--  <td><a
                                                            href="mailto:{{ $collectivemodule?->collective?->user?->email }}">{{ $collectivemodule?->collective?->user?->email }}</a>
                                                    </td>
                                                    <td><a
                                                            href="tel:+221{{ $collectivemodule?->collective?->telephone }}">{{ $collectivemodule?->collective?->telephone }}</a>
                                                    </td>
                                                    <td>{{ $collectivemodule?->collective?->departement?->region?->nom }}
                                                    </td> --}}
                                                    <td>{{ $collectivemodule?->module }}</td>
                                                    <td class="text-center">
                                                        @php $count = $collectivemodule?->listecollectives?->count() ?? 0; @endphp
                                                        <span class="badge bg-info">{{ $count }}</span>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge bg-primary">
                                                            {{ count($collectivemodule?->formations) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="{{ $collectivemodule?->statut }}">
                                                            {{ $collectivemodule?->statut }}
                                                        </span>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="d-flex mt-2 align-items-baseline"><a
                                                                href="{{ route('collectivemodules.show', $collectivemodule) }}"
                                                                class="btn btn-success btn-sm mx-1" title="Voir détails">
                                                                <i class="bi bi-eye"></i></a>
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-outline-primary btn-sm"><i
                                            class="bi bi-check2-circle"></i>&nbsp;Sélectionner</button>
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
        new DataTable('#table-modules', {
            lengthMenu: [
                [5, 10, 25, 50, 100, -1],
                [5, 10, 25, 50, 100, "Tout"]
            ],
            "order": [
                [2, 'desc']
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
