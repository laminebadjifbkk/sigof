@extends('layout.user-layout')
@section('title', 'Choisir opérateur pour la formation collective')
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
                                    <p> | Liste de tous les opérateurs</p>
                                </span>
                            </div>
                        </div>
                        {{-- <h5><u><b>MODULE</b>:</u> {{ $collectivemodule?->module }}</h5>
                        <h5><u><b>REGION</b>:</u> {{ $localite->nom }}</h5>
                        <h5><u><b>OPERATEUR</b>:</u>
                            @if (!empty($formation?->operateur?->user?->username))
                                {{ $formation?->operateur?->user?->operateur . ' (' . $formation?->operateur?->user?->username . ')' }}
                            @endif
                        </h5> --}}
                        
                        <div class="p-1 mb-4 border rounded bg-light shadow-sm">
                            <div class="row text-center fw-semibold">
                                <div class="col-md-4 mb-2">
                                    <span class="text-secondary">📍 Région</span><br>
                                    <span class="fs-5 text-dark">{{ $localite->nom ?? 'Aucune' }}</span>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <span class="text-secondary">📘 Module</span><br>
                                    <span class="fs-5 text-dark">{{$collectivemodule?->module ?? 'Aucun' }}</span>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <span class="text-secondary">👥 Opérateur</span><br>
                                    <span class="fs-5 text-dark">{{ $formation?->operateur?->user?->username ?? 'Aucun' }}</span>
                                </div>
                            </div>
                        </div>
                        <form method="post"
                            action="{{ url('formationcollectiveoperateurs', ['$idformation' => $formation->id, '$idcollectivemodule' => $formation->collectivemodule->id, '$idlocalite' => $formation->departement->id]) }}"
                            enctype="multipart/form-data" class="row g-3">
                            @csrf
                            @method('PUT')
                            <div class="row mb-3">
                                {{-- <div class="form-check col-md-2 pt-5">
                                    <label for="#">Choisir tout</label>
                                    <input type="checkbox" class="form-check-input" id="checkAll">
                                </div> --}}
                                <div class="form-check col-md-12 pt-5">
                                    <table class="m-2 table datatables align-middle" id="table-operateurs">
                                        <thead>
                                            <tr>
                                                <th>N° agrément</th>
                                                <th>Opérateurs</th>
                                                <th>Sigle</th>
                                                <th class="text-center">Modules</th>
                                                <th class="text-center">Formations</th>
                                                <th><i class="bi bi-gear"></i></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i = 1; ?>
                                            @foreach ($operateurs as $operateur)
                                                {{-- @isset($operateur?->numero_agrement) --}}
                                                    <tr>
                                                        <td>
                                                            <input type="radio" name="operateur"
                                                                value="{{ $operateur?->id }}"
                                                                {{ in_array($operateur?->id, $operateurFormation) ? 'checked' : '' }}
                                                                class="form-check-input @error('operateur') is-invalid @enderror">
                                                            @error('operateur')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <div>{{ $message }}</div>
                                                                </span>
                                                                @enderror{{ $operateur?->numero_agrement }}
                                                            </td>
                                                            <td>{{ $operateur?->user?->operateur }}</td>
                                                            <td>{{ $operateur?->user?->username }}</td>
                                                            <td style="text-align: center;">
                                                                @foreach ($operateur?->operateurmodules as $operateurmodule)
                                                                    @if ($loop->last)
                                                                        <a href="#"><span
                                                                                class="badge bg-info">{{ $loop->count }}</span></a>
                                                                    @endif
                                                                @endforeach
                                                            </td>
                                                            <td class="text-center">
                                                                @foreach ($operateur?->formations as $formation)
                                                                    @if ($loop->last)
                                                                        <a href="#"><span
                                                                                class="badge bg-info">{{ $loop->count }}</span></a>
                                                                    @endif
                                                                @endforeach
                                                            </td>
                                                            <td>
                                                                <span class="d-flex align-items-baseline"><a
                                                                        href="{{ route('operateurs.show', $operateur) }}" target="_blank"
                                                                        class="btn btn-primary btn-sm" title="voir détails" target="_blank"><i
                                                                            class="bi bi-eye"></i></a>
                                                                    <div class="filter">
                                                                        <a class="icon" href="#"
                                                                            data-bs-toggle="dropdown"><i
                                                                                class="bi bi-three-dots"></i></a>
                                                                        <ul
                                                                            class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                                                            {{-- <li>
                                                                                <button type="button"
                                                                                    class="dropdown-item btn btn-sm mx-1"
                                                                                    data-bs-toggle="modal"
                                                                                    data-bs-target="#EditOperateurModal{{ $operateur }}">
                                                                                    <i class="bi bi-pencil" title="Modifier"></i>
                                                                                    Modifier
                                                                                </button>
                                                                            </li>
                                                                            <li>
                                                                                <form
                                                                                    action="{{ route('operateurs.destroy', $operateur) }}"
                                                                                    method="post">
                                                                                    @csrf
                                                                                    @method('DELETE')
                                                                                    <button type="submit"
                                                                                        class="dropdown-item show_confirm"
                                                                                        title="Supprimer"><i
                                                                                            class="bi bi-trash"></i>Supprimer</button>
                                                                                </form>
                                                                            </li> --}}
                                                                        </ul>
                                                                    </div>
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    {{-- @endisset --}}
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
            new DataTable('#table-operateurs', {
                /* layout: {
                    topStart: {
                        buttons: [ 'csv', 'excel', 'print'],
                    }
                }, */
                "lengthMenu": [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "Tout"]
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
