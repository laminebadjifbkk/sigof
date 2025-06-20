@extends('layout.user-layout')
@section('title', 'ONFP | RECHERCHE MODULES OPERATEURS')
@section('space-work')

    <section class="section register">
        <div class="row justify-content-center">
            <div class="col-12 col-md-12 col-lg-12">
                <div class="pagetitle">
                    {{-- <h1>Data Tables</h1> --}}
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Accueil</a></li>
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
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <h5 class="card-title mb-0">Liste des opérateurs agréé en : </h5>{{ $module }}
                            @can('rapport-operateur-view')
                                <a href="#"
                                    class="btn btn-outline-primary btn-sm d-flex align-items-center gap-1 px-3 py-2 rounded-pill shadow-sm"
                                    data-bs-toggle="modal" data-bs-target="#generate_rapport_module_region"
                                    title="Générer rapports">
                                    <i class="bi bi-search"></i> Rechercher plus
                                </a>
                            @endcan
                        </div>
                        <table class="table datatables table-bordered table-hover align-middle justify-content-center"
                            id="table-operateurModules">
                            <thead>
                                <tr>
                                    <th>Numéro</th>
                                    <th>Operateurs</th>
                                    <th>Sigle</th>
                                    <th>Email</th>
                                    <th>Telephone</th>
                                    {{-- <th>Région</th>
                                    <th>Adresse</th>
                                    <th class="text-center">Modules</th>
                                    <th class="text-center">Formations</th> --}}
                                    <th>Responsable</th>
                                    <th class="text-center">Statut</th>
                                    <th width="2%"><i class="bi bi-gear"></i></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; ?>
                                @foreach ($operateurs as $operateur)
                                    <tr>
                                        <td>{{ $operateur?->numero_agrement }}</td>
                                        <td>{{ $operateur?->user?->operateur }}</td>
                                        <td>{{ $operateur?->user?->username }}</td>
                                        <td><a
                                                href="mailto:{{ $operateur?->user?->email }}">{{ $operateur?->user?->email }}</a>
                                        </td>
                                        <td>
                                            <a href="tel:+221{{ $operateur?->user?->fixe }}">
                                                {{ $operateur?->user?->fixe }}<br>
                                                {{ $operateur?->user?->telephone }}
                                            </a>
                                        </td>
                                        {{-- <td>{{ $operateur?->region?->nom }}</td>
                                        <td>{{ $operateur?->user?->adresse }}</td>
                                        <td style="text-align: center;">
                                            @foreach ($operateur->operateurmodules as $operateurmodule)
                                                @if ($loop->last)
                                                    <a href="#"><span
                                                            class="badge bg-info">{{ $loop->count }}</span></a>
                                                @endif
                                            @endforeach
                                        </td> --}}
                                        {{-- <td class="text-center">
                                            @foreach ($operateur->formations as $formation)
                                                @if ($loop->last)
                                                    <a href="#"><span
                                                            class="badge bg-info">{{ $loop->count }}</span></a>
                                                @endif
                                            @endforeach
                                        </td> --}}
                                        <td>{{ $operateur?->user?->firstname . ' ' . $operateur?->user?->name }}
                                        </td>
                                        <td style="text-align: center;"><span class="{{ $operateur?->statut_agrement }}">
                                                {{ $operateur?->statut_agrement }}</span></td>
                                        <td>
                                            <span class="d-flex align-items-baseline"><a
                                                    href="{{ route('operateurs.show', $operateur) }}"
                                                    class="btn btn-primary btn-sm" title="voir détails"><i
                                                        class="bi bi-eye"></i></a>
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <!-- End Table with stripped rows -->
                    </div>
                </div>

            </div>
        </div>

        <div class="modal fade" id="generate_rapport_module_region" tabindex="-1" role="dialog"
            aria-labelledby="generate_rappor_module_regionLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form method="post" action="{{ route('operateurmodules.rapport') }}">
                        @csrf
                        <div class="card shadow-lg border-0">
                            <div class="card-header bg-default text-center py-2 rounded-top">
                                <h4 class="mb-0"><i class="bi bi-search"></i>Rechercher opérateur par module</h4>
                            </div>
                            <div class="card-body row g-4 px-4">
                                <div class="modal-body">
                                    <div class="row g-3">
                                        <div class="col-12 col-md-12 col-lg-12 col-sm-12 col-xs-12 col-xxl-12">
                                            <div class="row">
                                                <div class="col-12 col-md-12 col-lg-12 col-sm-12 col-xs-12 col-xxl-12">
                                                    <div class="form-group">
                                                        <label for="module" class="form-label">Module</label>
                                                        <input type="text" name="module" id="module_operateur"
                                                            class="form-control form-control-sm"
                                                            placeholder="Module ou spécialité" />
                                                        <div id="moduleList"></div>
                                                        {{ csrf_field() }}
                                                    </div>
                                                </div>
                                                {{-- <div class="col-12 col-md-12 col-lg-12 col-sm-12 col-xs-12 col-xxl-12">
                                            <div class="form-group">
                                                <label for="operateur" class="form-label">Opérateur</label>
                                                <select name="operateur"
                                                    class="form-select  @error('operateur') is-invalid @enderror"
                                                    aria-label="Select" id="select-field-region-module-rapport"
                                                    data-placeholder="Choisir">
                                                    <option value=""></option>
                                                    @foreach ($operateurs as $op)
                                                        <option value="{{ $op?->id }}">
                                                            {{ $op?->user?->username }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('operateur')
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div> --}}
                                                {{-- <div class="col-12 col-md-12 col-lg-12 col-sm-12 col-xs-12 col-xxl-12">
                                            <div class="form-group">
                                                <label for="statut" class="form-label">Statut</label>
                                                <select name="statut"
                                                    class="form-select form-select-sm @error('statut') is-invalid @enderror"
                                                    aria-label="Select" id="select-field-statut-rappo"
                                                    data-placeholder="Choisir">
                                                    <option value="{{ old('statut') }}">
                                                        {{ old('statut') }}
                                                    </option>

                                                    @foreach ($module_statuts as $module)
                                                        <option value="{{ $module?->statut }}">
                                                            {{ $module?->statut }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('statut')
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div> --}}
                                            </div>

                                        </div>
                                        {{-- <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary btn-sm"
                                        data-bs-dismiss="modal">Fermer</button>
                                    <div class="text-center">
                                        <button type="submit"
                                            class="btn btn-primary btn-block submit_rapport btn-sm">Envoyer</button>
                                    </div>
                                </div> --}}
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer bg-light border-0 rounded-bottom-4 d-flex justify-content-between">
                                <button type="button" class="btn btn-outline-secondary btn-sm px-4 py-2 rounded-pill"
                                    data-bs-dismiss="modal">
                                    <i class="bi bi-x-circle"></i> Fermer
                                </button>
                                <button type="submit"
                                    class="btn btn-success btn-sm px-4 py-2 rounded-pill submit_rapport shadow-sm">
                                    <i class="bi bi-search"></i> Rechercher
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

@endsection
@push('scripts')
    <script>
        new DataTable('#table-operateurModules', {
            layout: {
                topStart: {
                    buttons: ['csv', 'excel', 'print'],
                }
            },
            "order": [
                [0, 'asc']
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
