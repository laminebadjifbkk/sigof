@extends('layout.user-layout')
@section('title', 'ONFP | ' . $cal?->commission)
@section('space-work')

    <div class="pagetitle">
        {{-- <h1>Data Tables</h1> --}}
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/home') }}">Accueil</a></li>
                <li class="breadcrumb-item">Tables</li>
                <li class="breadcrumb-item active">Données</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    {{-- <section class="section dashboard">
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <div class="col-12 col-md-4 col-lg-3 col-sm-12 col-xs-12 col-xxl-3">
                        <div class="card info-card revenue-card">
                            <a href="{{ route('operateurs.index') }}">
                                <div class="card-body">
                                    <h5 class="card-title">Demandes <span>| opérateurs</span></h5>
                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-people"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6>
                                                {{ count($operateurs) }}
                                            </h6>
                                            <span class="text-muted small pt-2 ps-1"></span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 col-lg-3 col-sm-12 col-xs-12 col-xxl-3">
                        <div class="card info-card sales-card">
                            <a href="{{ route('operateurs.agreer') }}">
                                <div class="card-body">
                                    <h5 class="card-title">Agréments <span>| en cours</span></h5>
                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-people"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6>
                                                {{ $operateur_agreer }}
                                            </h6>
                                            <span
                                                class="text-success small pt-1 fw-bold">{{ number_format($pourcentage_agreer, 2, ',', ' ') . '%' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 col-lg-3 col-sm-12 col-xs-12 col-xxl-3">
                        <div class="card info-card customers-card">
                            <a href="{{ route('operateurs.expirer') }}">
                                <div class="card-body">
                                    <h5 class="card-title">Agréments <span>| expirés</span></h5>
                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-people"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6>
                                                {{ $operateur_expirer }}
                                            </h6>
                                            <span
                                                class="text-success small pt-1 fw-bold">{{ number_format($pourcentage_expirer, 2, ',', ' ') . '%' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <div class="col-12 col-md-4 col-lg-3 col-sm-12 col-xs-12 col-xxl-3">
                        <div class="card info-card revenue-card">
                            <a href="#">
                                <div class="card-body">
                                    <h5 class="card-title">Nouvelles <span>| demandes</span></h5>
                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-people"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6>
                                                {{ $operateur_nouveau }}
                                            </h6>
                                            <span
                                                class="text-success small pt-1 fw-bold">{{ number_format($pourcentage_nouveau, 2, ',', ' ') . '%' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section> --}}

    <section class="section">
        <div class="row">
            <div class="col-12">
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
                    <span class="nav-link"><a href="{{ route('operateurs.index') }}" class="btn btn-secondary btn-sm"
                            title="retour"><i class="bi bi-arrow-counterclockwise"></i></a>
                    </span>
                    <div class="card-body">
                        @if (auth()->user()->hasRole('super-admin|admin|DEC'))
                            <div class="col-12 pt-2">
                                <div class="row">
                                    <div class="col-12 col-md-4 col-lg-2 col-sm-12 col-xs-12 col-xxl-2">
                                        <div class="card info-card revenue-card shadow-sm" style="max-width: 220px;">
                                            <div class="card-body p-2">
                                                <h5 class="card-title text-truncate mb-1" title="operateurs"
                                                    style="font-size: 1rem;">
                                                    {{ $cal?->commission }}
                                                </h5>
                                                <div class="d-flex align-items-center mb-2">
                                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-primary text-white"
                                                        style="width: 32px; height: 32px; font-size: 1.25rem;">
                                                        <i class="bi bi-people"></i>
                                                    </div>
                                                    <div class="ps-2">
                                                        <h6 class="mb-0" style="font-size: 0.9rem;">
                                                            {{ number_format(count($operateurs), 0, '', ' ') }}</h6>
                                                        <span class="text-muted small">opérateur(s)</span>
                                                    </div>
                                                </div>

                                                <a href="{{ route('operateurs.index') }}"
                                                    class="btn btn-outline-primary btn-sm w-100 d-flex align-items-center justify-content-center py-1"
                                                    style="font-size: 0.85rem; gap: 6px;">
                                                    Voir plus <i class="bi bi-arrow-right-short"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    @foreach ($groupesStatutAgrement as $statut => $items)
                                        <div class="col-12 col-md-4 col-lg-2 col-sm-12 col-xs-12 col-xxl-2">
                                            <div class="card info-card sales-card shadow-sm" style="max-width: 220px;">
                                                <div class="card-body p-2">
                                                    <h5 class="card-title text-truncate mb-1" title="{{ $statut }}"
                                                        style="font-size: 1rem;">
                                                        {{ $statut }}
                                                    </h5>
                                                    <div class="d-flex align-items-center mb-2">
                                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-primary text-white"
                                                            style="width: 32px; height: 32px; font-size: 1.25rem;">
                                                            <i class="bi bi-people"></i>
                                                        </div>
                                                        <div class="ps-2">
                                                            <h6 class="mb-0" style="font-size: 0.9rem;">
                                                                {{ number_format($items->count(), 0, '', ' ') }}</h6>
                                                            <span class="text-muted small">opérateur(s)</span>
                                                        </div>
                                                    </div>
                                                    <a href="{{ route('operateurs.parStatut', ['statut' => $statut]) }}"
                                                        target="_blank"
                                                        class="btn btn-outline-primary btn-sm w-100 d-flex align-items-center justify-content-center py-1"
                                                        style="font-size: 0.85rem; gap: 6px;">
                                                        Voir plus <i class="bi bi-arrow-right-short"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title">{{ $title }}</h5>
                                <span class="d-flex align-items-baseline">
                                    <a href="{{ route('operateurs.create') }}"
                                        class="btn btn-primary btn-sm float-end btn-rounded"><i
                                            class="fas fa-plus"></i>Ajouter</a>
                                    <div class="filter">
                                        <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                                class="bi bi-three-dots"></i></a>
                                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                            <li>
                                                <button type="button" class="dropdown-item btn btn-sm"
                                                    data-bs-toggle="modal" data-bs-target="#generate_rapport"></i>Rechercher
                                                    plus</button>
                                            </li>
                                            @hasrole('admin|super-admin')
                                                <li>
                                                    <form action="{{ route('importO') }}" method="get">
                                                        <button type="submit"
                                                            class="dropdown-item btn btn-sm">Importer</button>
                                                    </form>
                                                </li>
                                            @endhasrole
                                        </ul>
                                    </div>
                                </span>
                            </div> --}}
                        @endif
                        @if ($operateurs->isNotEmpty())
                            <div class="table-responsive">
                                <table
                                    class="table datatables table-bordered table-hover align-middle justify-content-center"
                                    id="table-operateurs">
                                    <thead>
                                        <tr>
                                            @can('afficher-dossier-operateur')
                                                <th width="3%" class="text-center">Dossier</th>
                                            @endcan
                                            <th width="15%" class="text-center">N° agrément</th>
                                            @can('afficher-operateur-name')
                                                <th width="40%">Opérateurs</th>
                                            @endcan
                                            <th>Sigle</th>
                                            @can('afficher-operateur-email')
                                                <th>Email</th>
                                            @endcan
                                            @can('afficher-operateur-telephone')
                                                <th>Telephone</th>
                                            @endcan
                                            <th>Région</th>
                                            @can('afficher-operateur-adresse')
                                                <th>Adresse</th>
                                            @endcan
                                            @can('afficher-operateur-responsable')
                                                <th>Responsable</th>
                                            @endcan
                                            @can('afficher-operateur-module')
                                                <th class="text-center">Modules</th>
                                            @endcan
                                            @can('afficher-operateur-formation')
                                                <th class="text-center">Formations</th>
                                            @endcan
                                            @can('afficher-operateur-statut')
                                                <th width="15%" class="text-center">Statut</th>
                                            @endcan
                                            @can('operateur-show')
                                                <th width="2%"><i class="bi bi-gear"></i></th>
                                            @endcan
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1; ?>
                                        @foreach ($operateurs as $operateur)
                                            <tr>
                                                @can('afficher-dossier-operateur')
                                                    <td class="text-center">{{ $operateur?->numero_dossier }}</td>
                                                @endcan
                                                <td>{{ $operateur?->numero_agrement }}</td>
                                                @can('afficher-operateur-name')
                                                    <td>{{ $operateur?->user?->operateur }}</td>
                                                @endcan
                                                <td>{{ $operateur?->user?->username }}</td>
                                                @can('afficher-operateur-email')
                                                    <td><a
                                                            href="mailto:{{ $operateur?->user?->email }}">{{ $operateur?->user?->email }}</a>
                                                    </td>
                                                @endcan
                                                @can('afficher-operateur-telephone')
                                                    <td><a href="tel:+221{{ $operateur?->user?->fixe }}">{{ $operateur?->user?->fixe . ' ' . $operateur?->user?->telephone }}
                                                        </a>
                                                    </td>
                                                @endcan
                                                <td>{{ $operateur?->region?->nom }}</td>
                                                @can('afficher-operateur-adresse')
                                                    <td>{{ $operateur?->user?->adresse }}</td>
                                                @endcan
                                                @can('afficher-operateur-responsable')
                                                    <td>{{ $operateur?->user?->firstname . ' ' . $operateur?->user?->name }}
                                                    </td>
                                                @endcan
                                                @can('afficher-operateur-module')
                                                    <td style="text-align: center;">
                                                        @foreach ($operateur->operateurmodules as $operateurmodule)
                                                            @if ($loop->last)
                                                                <a href="#"><span
                                                                        class="badge bg-info">{{ $loop->count }}</span></a>
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                @endcan
                                                @can('afficher-operateur-formation')
                                                    <td class="text-center">
                                                        @foreach ($operateur->formations as $formation)
                                                            @if ($loop->last)
                                                                <a href="#"><span
                                                                        class="badge bg-info">{{ $loop->count }}</span></a>
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                @endcan
                                                @can('afficher-operateur-statut')
                                                    <td style="text-align: center;"><span
                                                            class="{{ $operateur?->statut_agrement }}">
                                                            {{ $operateur?->statut_agrement }}</span></td>
                                                @endcan
                                                @can('operateur-show')
                                                    <td>
                                                        <span class="d-flex align-items-baseline"><a
                                                                href="{{ route('operateurs.show', $operateur) }}"
                                                                class="btn btn-primary btn-sm" title="voir détails"><i
                                                                    class="bi bi-eye"></i></a>
                                                            <div class="filter">
                                                                <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                                                        class="bi bi-three-dots"></i></a>
                                                                <ul
                                                                    class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                                                    @can('operateur-update')
                                                                        <li>
                                                                            <a class="dropdown-item btn btn-sm"
                                                                                href="{{ route('operateurs.edit', $operateur) }}"
                                                                                class="mx-1" title="Modifier"><i
                                                                                    class="bi bi-pencil"></i>Modifier</a>
                                                                        </li>
                                                                    @endcan
                                                                    @can('operateur-delete')
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
                                                                        </li>
                                                                    @endcan
                                                                </ul>
                                                            </div>
                                                        </span>
                                                    </td>
                                                @endcan
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>

                            </div>
                        @else
                            <div class="alert alert-info">Aucun opérateur enregistré pour l'instant !</div>
                        @endif
                    </div>
                </div>

            </div>
        </div>

        <div class="modal fade" id="generate_rapport" tabindex="-1" role="dialog"
            aria-labelledby="generate_rapportLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="card-header text-center bg-gradient-default">
                        <h1 class="h4 text-black mb-0">FAIRE UNE RECHERCHE</h1>
                    </div>
                    <form method="post" action="{{ route('operateurs.report') }}">
                        @csrf
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="operateur_name" class="form-label">Raison sociale</label>
                                                <input type="text" name="operateur_name"
                                                    value="{{ old('operateur_name') }}"
                                                    class="form-control form-control-sm @error('operateur_name') is-invalid @enderror"
                                                    id="operateur_name" placeholder="Raison sociale">
                                                @error('operateur_name')
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
                                                <label for="operateur_sigle" class="form-label">Sigle</label>
                                                <input type="text" name="operateur_sigle"
                                                    value="{{ old('operateur_sigle') }}"
                                                    class="form-control form-control-sm @error('operateur_sigle') is-invalid @enderror"
                                                    id="operateur_sigle" placeholder="Sigle">
                                                @error('operateur_sigle')
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
                                                <label for="numero_agrement" class="form-label">N° agrément</label>
                                                <input type="text" name="numero_agrement"
                                                    value="{{ old('numero_agrement') }}"
                                                    class="form-control form-control-sm @error('numero_agrement') is-invalid @enderror"
                                                    id="numero_agrement" placeholder="Numéro agrément">
                                                @error('numero_agrement')
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
                                                    id="telephone" value="{{ old('telephone') }}" autocomplete="tel"
                                                    placeholder="XX:XXX:XX:XX">
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

        <div class="modal fade" id="importOperateur" tabindex="-1" role="dialog"
            aria-labelledby="importOperateurLabel" aria-hidden="true" data-bs-backdrop="static"
            data-bs-keyboard="false">

            <div class="modal-dialog modal-xl">
                <div class="modal-content">

                    <!-- Header du Modal avec texte centré -->
                    {{-- <div class="modal-header bg-secondary text-white">
                        <h5 class="modal-title w-100 text-center" id="importOperateurLabel">
                            FAIRE UNE RECHERCHE
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div> --}}

                    <div class="card-header text-center bg-gradient-default">
                        <h1 class="h4 text-black mb-0">IMPORTER</h1>
                    </div>

                    <!-- Formulaire -->
                    <form method="post" action="{{ route('import.operateurs') }}" enctype="multipart/form-data"
                        class="p-3" novalidate>
                        @csrf

                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="file" class="form-label">
                                    Fichier (.XLSX, .CSV, .XLS) <span class="text-danger">*</span>
                                </label>
                                <input type="file" name="file" value="{{ old('file') }}"
                                    class="form-control form-control-sm @error('file') is-invalid @enderror"
                                    id="file" required>

                                @error('file')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Footer du Modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-sm"
                                data-bs-dismiss="modal">Fermer</button>
                            <button type="submit" class="btn btn-primary btn-sm text-white">Importer</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>

    </section>

@endsection
@push('scripts')
    <script>
        new DataTable('#table-operateurs', {
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
