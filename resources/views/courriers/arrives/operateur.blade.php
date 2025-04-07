@extends('layout.user-layout')
@section('title', 'ONFP - Courriers agréments opérateurs')
@section('space-work')

    <div class="pagetitle">
        {{-- <h1>Data Tables</h1> --}}
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Accueil</a></li>
                <li class="breadcrumb-item">Tables</li>
                <li class="breadcrumb-item active">Courriers agrément opérateurs</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="row">
            <!-- Left side columns -->
            <div class="col-12 col-md-12 col-lg-12 col-sm-12 col-xs-12 col-xxl-12">
                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <div class="alert alert-danger bg-danger text-light border-0 alert-dismissible fade show"
                            role="alert">
                            <strong>{{ $error }}</strong>
                        </div>
                    @endforeach
                @endif
                <div class="row">
                    <!-- Sales Card -->
                    <div class="col-12 col-md-4 col-lg-3 col-sm-12 col-xs-12 col-xxl-3">
                        <div class="card info-card sales-card">
                            {{-- <div class="filter">
                                <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                        class="bi bi-three-dots"></i></a>
                            </div> --}}
                            <a href="#">
                                <div class="card-body">
                                    <h5 class="card-title">Courriers <span>| Agréments</span></h5>
                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-calendar-check-fill"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6>
                                                <span class="text-primary">{{ $count_today ?? '0' }}</span>
                                            </h6>
                                            <span class="text-success small pt-1 fw-bold">Aujourd'hui</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 col-lg-3 col-sm-12 col-xs-12 col-xxl-3">
                        <div class="card info-card sales-card">
                            {{--  <div class="filter">
                                <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                        class="bi bi-three-dots"></i></a>
                            </div> --}}
                            <a href="#">
                                <div class="card-body">
                                    <h5 class="card-title">Courriers <span>| Agréments</span></h5>
                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-file-earmark-text"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6>
                                                <span class="text-primary">{{ $count_arrives }}</span>
                                            </h6>
                                            <span class="text-success small pt-1 fw-bold">Tous</span>
                                            {{-- <span class="text-muted small pt-2 ps-1">increase</span> --}}
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <div class="col-12 col-md-4 col-lg-3 col-sm-12 col-xs-12 col-xxl-3">
                        <div class="card info-card sales-card">
                            {{-- <div class="filter">
                                <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                        class="bi bi-three-dots"></i></a>
                            </div> --}}
                            <a href="{{ url('arrives') }}">
                                <div class="card-body">
                                    <h5 class="card-title">Courriers <span>| Arrivés</span></h5>
                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-file-earmark-text"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6>
                                                <span class="text-primary">{{ $count_courriers_arrives }}</span>
                                            </h6>
                                            <span class="text-success small pt-1 fw-bold">Tous</span>
                                            {{-- <span class="text-muted small pt-2 ps-1">increase</span> --}}
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="row">
            <div class="col-12 col-md-12 col-lg-12 col-sm-12 col-xs-12 col-xxl-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mt-0">
                            <span class="d-flex mt-2 align-items-baseline"><a href="{{ route('courriers.index') }}"
                                    class="btn btn-success btn-sm" title="retour"><i
                                        class="bi bi-arrow-counterclockwise"></i></a>&nbsp;Liste des courriers
                            </span>
                            <span class="d-flex align-items-baseline">
                                <button type="button" class="btn btn-outline-primary btn-sm float-end"
                                    data-bs-toggle="modal" data-bs-target="#addCourrierOperateur">Ajouter</button>
                            </span>
                        </div>
                        @foreach ($arrives as $arrive)
                        @endforeach
                        @if (!empty($arrive))
                            <h5 class="card-title">{{ $title }}</h5>
                            <table class="table datatables align-middle" id="table-arrives">
                                <thead>
                                    <tr>
                                        <th class="text-center">N°</th>
                                        <th class="text-center">Date arrivé</th>
                                        <th class="text-center">N° correspondance</th>
                                        <th class="text-center">Date correspondance</th>
                                        <th>Expéditeur</th>
                                        <th>Objet</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; ?>
                                    @foreach ($arrives as $arrive)
                                        <tr>
                                            <td style="text-align: center;">{{ $arrive?->numero_arrive }}</td>
                                            {{-- Date reception = date arrivée --}}
                                            <td style="text-align: center;">
                                                {{ $arrive?->courrier?->date_recep?->format('d/m/Y') }} </td>
                                            <td style="text-align: center;">{{ $arrive?->courrier?->numero_courrier }}</td>
                                            <td style="text-align: center;">
                                                {{ $arrive?->courrier?->date_cores?->format('d/m/Y') }} </td>
                                            {{-- <td class="text-center">{{ $arrive->numero }}</td> --}}
                                            <td>{{ $arrive?->courrier?->expediteur }}</td>
                                            <td>{{ $arrive?->courrier?->objet }}</td>
                                            <td>
                                                <span class="d-flex align-items-baseline"><a
                                                        href="{{ route('arrives.show', $arrive?->id) }}"
                                                        class="btn btn-success btn-sm" title="voir détails"><i
                                                            class="bi bi-eye"></i></a>
                                                    <div class="filter">
                                                        <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                                                class="bi bi-three-dots"></i></a>
                                                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                                            <li><a class="dropdown-item btn btn-sm"
                                                                    href="{{ route('arrives.edit', $arrive?->id) }}"
                                                                    class="mx-1"><i class="bi bi-pencil"></i>
                                                                    Modifier</a>
                                                            </li>
                                                            {{-- <li><a class="dropdown-item btn btn-sm"
                                                                href="{{ url('arrive-imputations', ['id' => $arrive->id]) }}"
                                                                class="mx-1">Imputer</a>
                                                        </li> --}}
                                                            {{--  <li><a class="dropdown-item btn btn-sm"
                                                                href="{!! url('coupon-arrive', ['$id' => $arrive->id]) !!}" class="mx-1"
                                                                target="_blank">Imprimer</a>
                                                        </li> --}}
                                                            @can('arrive-delete')
                                                                <li>
                                                                    <form action="{{ route('arrives.destroy', $arrive?->id) }}"
                                                                        method="post">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit"
                                                                            class="dropdown-item show_confirm"><i
                                                                                class="bi bi-trash"></i>Supprimer</button>
                                                                    </form>
                                                                </li>
                                                            @endcan
                                                        </ul>
                                                    </div>
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="alert alert-info mt-3">Aucun courrier agrément enregistré pour le moment !!!</div>
                        @endif
                        {{-- <p>Le tableau des courriers arrivés</p> --}}
                        <!-- Table with stripped rows -->

                        <!-- End Table with stripped rows -->
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="addCourrierOperateur" tabindex="-1" role="dialog"
            aria-labelledby="addCourrierOperateurLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    {{--  <div class="pt-0 pb-0">
                        <h5 class="card-title text-center pb-0 fs-4">Enregistrement</h5>
                        <p class="text-center small">enregister un nouveau courrier opérateur</p>
                    </div> --}}

                    <div class="card-header text-center bg-gradient-default">
                        <h1 class="h4 text-black mb-0">ajouter un nouveau courrier opérateur</h1>
                    </div>
                    {{-- <div class="modal-header">
                        <h5 class="modal-title">Ajouter un nouveau courrier opérateur</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div> --}}
                    {{-- <form method="post" action="{{ route('arrives.store') }}" enctype="multipart/form-data"
                        class="row g-3"> --}}
                    <form method="post" action="{{ route('addCourrierOperateur') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="row g-3">

                                <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                    <label for="objet" class="form-label">Objet<span
                                            class="text-danger mx-1">*</span></label>
                                    <textarea name="objet" id="objet" rows="1"
                                        class="form-control form-control-sm @error('objet') is-invalid @enderror" placeholder="Objet">{{ 'Demande agrément opérateur' ?? old('objet') }}</textarea>
                                    @error('objet')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                    <label for="numero_arrive" class="form-label">Numéro courrier<span
                                            class="text-danger mx-1">*</span></label>
                                    <div class="input-group has-validation">
                                        <input type="number" min="0" name="numero_arrive"
                                            value="{{ $numCourrier ?? old('numero_arrive') }}"
                                            class="form-control form-control-sm @error('numero_arrive') is-invalid @enderror"
                                            id="numero_arrive" placeholder="Numéro courrier">
                                        @error('numero_arrive')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                    <label for="numero_dossier" class="form-label">Numéro dossier<span
                                            class="text-danger mx-1">*</span></label>
                                    <div class="input-group has-validation">
                                        <input type="number" min="0" name="numero_dossier"
                                            value="{{ $numDossier ?? old('numero_dossier') }}"
                                            class="form-control form-control-sm @error('numero_dossier') is-invalid @enderror"
                                            id="numero_dossier" placeholder="Numéro dossier">
                                        @error('numero_dossier')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                    <label for="date_arrivee" class="form-label">Date arrivée<span
                                            class="text-danger mx-1">*</span></label>
                                    <input type="date" name="date_arrivee" value="{{ old('date_arrivee') }}"
                                        class="datepicker form-control form-control-sm @error('date_arrivee') is-invalid @enderror"
                                        id="date_arrivee" placeholder="jj/mm/aaaa">
                                    @error('date_arrivee')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                    <label for="date_correspondance" class="form-label">Date correspondance<span
                                            class="text-danger mx-1">*</span></label>
                                    <input type="date" name="date_correspondance"
                                        value="{{ old('date_correspondance') }}"
                                        class="datepicker form-control form-control-sm @error('date_correspondance') is-invalid @enderror"
                                        id="date_correspondance" placeholder="jj/mm/aaaa">
                                    @error('date_correspondance')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                    <label for="annee" class="form-label">Année<span
                                            class="text-danger mx-1">*</span></label>
                                    <input type="number" min="2024" name="annee"
                                        value="{{ $anneeEnCours ?? old('annee') }}"
                                        class="form-control form-control-sm @error('annee') is-invalid @enderror"
                                        id="annee" placeholder="Année">
                                    @error('annee')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-12 col-lg-8 col-sm-12 col-xs-12 col-xxl-8">
                                    <label for="expediteur" class="form-label">Opérateur<span
                                            class="text-danger mx-1">*</span></label>
                                    <textarea name="expediteur" id="expediteur" rows="1"
                                        class="form-control form-control-sm @error('expediteur') is-invalid @enderror" placeholder="Expéditeur">{{ old('expediteur') }}</textarea>
                                    @error('expediteur')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                    <label for="sigle" class="form-label">Sigle<span
                                            class="text-danger mx-1">*</span></label>
                                    <textarea name="sigle" id="sigle" rows="1"
                                        class="form-control form-control-sm @error('sigle') is-invalid @enderror" placeholder="Sigle">{{ old('sigle') }}</textarea>
                                    @error('sigle')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>


                                <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                    <label for="email" class="form-label">Email<span
                                            class="text-danger mx-1">*</span></label>
                                    <input type="email" name="email" value="{{ old('email') }}"
                                        class="form-control form-control-sm @error('email') is-invalid @enderror"
                                        id="email" placeholder="Adresse email">
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                    <label for="fixe" class="form-label">Téléphone<span
                                            class="text-danger mx-1">*</span></label>
                                    <input type="number" min="0" name="fixe" value="{{ old('fixe') }}"
                                        class="form-control form-control-sm @error('fixe') is-invalid @enderror"
                                        id="fixe" placeholder="3xxxxxxxx">
                                    @error('fixe')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                    <label for="type_demande" class="form-label">TYPE<span
                                            class="text-danger mx-1">*</span></label>
                                    <select name="type_demande"
                                        class="form-select form-select-sm @error('type_demande') is-invalid @enderror"
                                        aria-label="Select" id="select-field" data-placeholder="Choisir type de demande">
                                        <option value="{{ old('type_demande') }}">
                                            {{ old('type_demande') }}
                                        </option>
                                        <option value="Nouvelle">
                                            Nouvelle
                                        </option>
                                        <option value="Renouvellement">
                                            Renouvellement
                                        </option>
                                    </select>
                                    @error('type_demande')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-12 col-lg-12 col-sm-12 col-xs-12 col-xxl-12">
                                    <label for="observation" class="form-label">Observations</label>
                                    <textarea name="observation" id="observation" rows="1"
                                        class="form-control form-control-sm @error('date_reponse') is-invalid @enderror" placeholder="Observations">{{ old('observation') }}</textarea>
                                    @error('observation')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary btn-sm"
                                        data-bs-dismiss="modal">Fermer</button>
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary btn-sm">Enregistrer</button>
                                    </div>
                                </div>
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
        new DataTable('#table-arrives', {
            layout: {
                topStart: {
                    buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
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
