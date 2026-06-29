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

                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-body text-center">
                                        {{-- <i class="bi bi-geo-alt-fill fs-2 text-primary"></i> --}}
                                        <div class="text-muted small mt-0">Région</div>
                                        <div class="fw-bold fs-5">
                                            {{ $localite->nom ?? 'Aucune' }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-body text-center">
                                        {{-- <i class="bi bi-journal-bookmark-fill fs-2 text-success"></i> --}}
                                        <div class="text-muted small mt-0">Module</div>
                                        <div class="fw-bold fs-5">
                                            {{ $formation?->collectivemodule?->module ?? 'Aucun' }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- @if (!empty($formation?->collectivemodule?->module))
                                <div class="col-md-4">
                                    <div class="card border-0 shadow-sm h-100">
                                        <div class="card-body text-center">
                                            <i class="bi bi-building-fill fs-2 text-warning"></i>
                                            <div class="text-muted small mt-2">Structure</div>
                                            <div class="fw-bold fs-5">
                                                {{ $formation?->collectivemodule?->collective?->name_with_sigle ?? 'Aucune' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif --}}
                        </div>

                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white py-3">
                                <h5 class="mb-0">
                                    <i class="bi bi-diagram-3-fill text-primary"></i>
                                    Structure : {{ $formation?->collectivemodule?->collective?->name_with_sigle ?? 'Aucune' }}
                                </h5>
                            </div>

                            <div class="card-body">
                                <form method="post"
                                    action="{{ url('formationcollectivemodules', ['$idformation' => $formation->id]) }}"
                                    enctype="multipart/form-data">

                                    @csrf
                                    @method('PUT')

                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle">
                                            <thead class="table-light">
                                                <tr>
                                                   {{--  <th width="35%">Structure</th> --}}
                                                    <th>Module</th>
                                                    <th class="text-center">Effectif</th>
                                                    <th class="text-center">Formations</th>
                                                    <th class="text-center">Statut</th>
                                                    <th width="80" class="text-center">
                                                        <i class="bi bi-gear"></i>
                                                    </th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @foreach ($collectivemodules as $collectivemodule)
                                                    <tr>

                                                        <td>
                                                            <div class="d-flex align-items-center">

                                                                <input type="radio" name="collectivemodule"
                                                                    value="{{ $collectivemodule->id }}"
                                                                    class="form-check-input me-3"
                                                                    {{ in_array($collectivemodule->id, $collectivemoduleFormation) ? 'checked' : '' }}>

                                                                {{-- <div>
                                                                    <div class="fw-semibold">
                                                                        {{ $collectivemodule?->collective?->name_with_sigle }}
                                                                    </div>

                                                                    <small class="text-muted">
                                                                        Module collectif
                                                                    </small>
                                                                </div> --}}
                                                            </div>
                                                        </td>

                                                        <td>
                                                            <span class="fw-semibold">
                                                                {{ $collectivemodule?->module }}
                                                            </span>
                                                        </td>

                                                        <td class="text-center">
                                                            <span class="badge rounded-pill bg-info">
                                                                {{ $collectivemodule?->listecollectives?->count() ?? 0 }}
                                                            </span>
                                                        </td>

                                                        <td class="text-center">
                                                            <span class="badge rounded-pill bg-primary">
                                                                {{ $collectivemodule?->formations?->count() ?? 0 }}
                                                            </span>
                                                        </td>

                                                        <td class="text-center">
                                                            <span class="{{ $collectivemodule->statut }}">
                                                                {{ ucfirst($collectivemodule->statut) }}
                                                            </span>
                                                        </td>

                                                        <td class="text-center">
                                                            <a href="{{ route('collectivemodules.show', $collectivemodule) }}"
                                                                class="btn btn-outline-success btn-sm"
                                                                data-bs-toggle="tooltip" title="Voir les détails">
                                                                <i class="bi bi-eye"></i>
                                                            </a>
                                                        </td>

                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    @error('collectivemodule')
                                        <div class="alert alert-danger mt-3">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                    <div class="text-center mt-4">
                                        <button type="submit" class="btn btn-outline-primary px-4">
                                            <i class="bi bi-check2-circle me-1"></i>
                                            Sélectionner ce module
                                        </button>
                                    </div>

                                </form>
                            </div>
                        </div>
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
