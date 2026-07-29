@extends('layout.user-layout')
@section('title', 'ONFP | FORMATIONS')
@section('space-work')

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

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
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
                            role="alert">{{ $error }}</div>
                    @endforeach
                @endif

                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5>{{ $annee }}</h5>
                            <a href="{{ route('showAttestations') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-arrow-left-circle"></i> Retour à la liste
                            </a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped align-middle">
                                <thead class="table-primary">
                                    <tr>
                                        <th>N°</th>
                                        <th>Régions</th>
                                        <th class="text-center">Effectifs</th>
                                        <th class="text-center">%</th>
                                        <th width="15%" class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i = 1; @endphp
                                    @foreach ($groupes as $row)
                                        <tr>
                                            <td>{{ $i++ }}</td>
                                            <td>{{ $row->nom }}</td>
                                            <td class="text-center">{{ number_format($row->total, 0, '', ' ') }}</td>
                                            <td class="text-center">
                                                {{ $total ? round(($row->total * 100) / $total, 1) : 0 }} %
                                            </td>
                                            <td>
                                                <a href="{{ route('attestations.attestationsParAnneeRegion', [
                                                    'annee' => $annee,
                                                    'region' => $row->nom,
                                                ]) }}"
                                                    class="btn btn-sm btn-outline-primary d-flex align-items-center justify-content-center gap-1">
                                                    Afficher <i class="bi bi-arrow-right-short"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">

                        <div class="pt-1">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">

                                {{-- Titre à gauche --}}
                                <div class="d-flex align-items-center gap-2">
                                    <h6 class="mb-0 text-muted fw-semibold text-uppercase">
                                        Liste des formations
                                    </h6>
                                </div>

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
                                @can('formation-create')
                                    <div class="d-flex align-items-center gap-2">
                                        <a href="#" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#AddFormationModal" title="Ajouter une formation">
                                            Ajouter
                                        </a>
                                        {{-- @can('suivi-convention')
                                            <div class="dropdown">
                                                <a href="#" class="btn btn-sm btn-light" data-bs-toggle="dropdown"
                                                    title="Options">
                                                    <i class="bi bi-three-dots-vertical"></i>
                                                </a>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <button type="button" class="dropdown-item" data-bs-toggle="modal"
                                                            data-bs-target="#generate_rapportFormation">
                                                            <i class="bi bi-file-earmark-text"></i> Générer suivi-convention
                                                        </button>
                                                    </li>
                                                </ul>
                                            </div> --}}
                                        @endcan
                                    </div>
                                @endcan

                            </div>
                        </div>
                        @if ($formations->isNotEmpty())
                            <div class="table-responsive">
                                <table
                                    class="table datatables table-bordered table-hover align-middle justify-content-center"
                                    id="table-formations">
                                    <thead>
                                        <tr>
                                            <th>Bénéficiaires</th>
                                            {{-- <th width='10%'>Région</th> --}}
                                            <th>Modules</th>
                                            <th>Opérateurs</th>
                                            <th width='5%' class="text-center">Attestations</th>
                                            <th width='3%'><i class="bi bi-gear"></i></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1; ?>
                                        @foreach ($formations as $formation)
                                            <tr>
                                                <td>{{ $formation?->name }}</td>
                                                {{-- <td>{{ $formation->departement?->region?->nom }}</td> --}}
                                                <td>
                                                    {{ $formation?->module?->name ?? ($formation?->collectivemodule?->module ?? '') }}
                                                </td>
                                                <td>{{ $formation?->operateur?->user?->display_operateur ?? ' ' }}</td>
                                                <td class="text-center"><a><span
                                                            class="{{ $formation?->attestation }}">{{ $formation?->attestation }}</span></a>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <!-- Bouton Voir détails -->
                                                        <a href="{{ route('formations.show', $formation) }}"
                                                            class="btn btn-primary btn-sm" title="Voir les détails"
                                                            target="_blank">
                                                            <i class="bi bi-eye"></i>
                                                        </a>

                                                        <!-- Bouton Statuer l'attestation -->
                                                        <button class="btn btn-warning btn-sm mx-1" data-bs-toggle="modal"
                                                            data-bs-target="#statuerAttestationModal-{{ $formation->id }}"
                                                            title="Statuer l'attestation">
                                                            <i class="bi bi-arrow-left-right"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                            <div class="modal fade" id="statuerAttestationModal-{{ $formation->id }}"
                                                tabindex="-1" aria-labelledby="changerModuleLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                    <form action="{{ route('attestations.check', $formation->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="changerModuleLabel">
                                                                    {{ $formation?->name }}</h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Fermer"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label for="attestation"
                                                                        class="form-label">{{ $formation->module->name ?? ($formation->collectivemodule->module ?? 'Aucun') }}</label>

                                                                    <select name="attestation" id="attestation"
                                                                        class="form-select form-select-sm" required>
                                                                        <option value="" disabled
                                                                            {{ empty($formation?->attestation) ? 'selected' : '' }}>
                                                                            -- Choisir --
                                                                        </option>

                                                                        @foreach ($statuts as $value => $label)
                                                                            <option value="{{ $value }}"
                                                                                {{ $formation?->attestation === $value ? 'selected' : '' }}>
                                                                                {{ $label }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>

                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary btn-sm"
                                                                    data-bs-dismiss="modal">Fermer</button>
                                                                <button type="submit"
                                                                    class="btn btn-success btn-sm">Valider</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info">Aucune formation créée pour l'instant !</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 d-flex flex-column align-items-center justify-content-center">
            <div class="modal fade" id="AddFormationModal" tabindex="-1">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <form method="post" action="{{ route('formations.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="card-header text-center bg-gradient-default">
                                <h1 class="h4 text-black mb-0">CRÉER FORMATION</h1>
                            </div>
                            <div class="modal-body">
                                <div class="row g-3">
                                    <div class="col-12 col-md-12 col-lg-12 col-sm-12 col-xs-12 col-xxl-12">
                                        <label for="intitule" class="form-label">Intitulé<span
                                                class="text-danger mx-1">*</span></label>
                                        <textarea name="intitule" id="intitule" rows="1"
                                            class="form-control form-control-sm @error('intitule') is-invalid @enderror"
                                            placeholder="ex : Technique de coupe-couture">{{ old('intitule') }}</textarea>
                                        @error('intitule')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-12 col-md-12 col-lg-8 col-sm-12 col-xs-12 col-xxl-8">
                                        <label for="name" class="form-label">Bénéficiaires<span
                                                class="text-danger mx-1">*</span></label>
                                        <textarea name="name" id="name" rows="1"
                                            class="form-control form-control-sm @error('name') is-invalid @enderror" placeholder="Bénéficiaires">{{ old('name') }}</textarea>
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                        <label for="code" class="form-label">Code<span
                                                class="text-danger mx-1">*</span></label>
                                        <div class="input-group has-validation">
                                            <input type="text" name="code"
                                                value="{{ $numFormation ?? old('code') }}"
                                                class="form-control form-control-sm @error('code') is-invalid @enderror"
                                                id="code" placeholder="code">
                                            @error('code')
                                                <span class="invalid-feedback" role="alert">
                                                    <div>{{ $message }}</div>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                        <label for="departement" class="form-label">Département<span
                                                class="text-danger mx-1">*</span></label>
                                        <select name="departement"
                                            class="form-select  @error('departement') is-invalid @enderror"
                                            aria-label="Select" id="select-field-departement-modal"
                                            data-placeholder="Choisir département">
                                            <option value="{{ old('departement') }}">
                                                {{ old('departement') }}
                                            </option>
                                            @foreach ($departements as $departement)
                                                <option value="{{ $departement->nom }}">
                                                    {{ $departement->nom }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('departement')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                        <label for="lieu" class="form-label">Lieu formation<span
                                                class="text-danger mx-1">*</span></label>
                                        <input type="text" name="lieu" value="{{ old('lieu') }}"
                                            class="form-control form-control-sm @error('lieu') is-invalid @enderror"
                                            id="lieu" placeholder="Lieu formation">
                                        @error('lieu')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                        <label for="types_formation" class="form-label">Type demande<span
                                                class="text-danger mx-1">*</span></label>
                                        <select name="types_formation"
                                            class="form-select  @error('types_formation') is-invalid @enderror"
                                            aria-label="Select" id="select-field-types_formation"
                                            data-placeholder="Choisir type formation">
                                            <option value="{{ old('types_formation') }}">
                                                {{ old('types_formation') }}
                                            </option>
                                            @foreach ($types_formations as $types_formation)
                                                <option value="{{ $types_formation->name }}">
                                                    {{ $types_formation->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('types_formation')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                        <label for="type_certification" class="form-label">Type certification<span
                                                class="text-danger mx-1">*</span></label>
                                        <select name="type_certification"
                                            class="form-select  @error('type_certification') is-invalid @enderror"
                                            aria-label="Select" id="select-field-type_certification"
                                            data-placeholder="Choisir niveau de qualification">
                                            <option value="{{ old('type_certification') }}">
                                                {{ old('type_certification') }}
                                            </option>
                                            <option value="Titre">
                                                Titre
                                            </option>
                                            <option value="Attestation">
                                                Attestation
                                            </option>
                                        </select>
                                        @error('type_certification')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                        <label for="date_debut" class="form-label">Date début</label>
                                        <input type="date" name="date_debut" value="{{ old('date_debut') }}"
                                            class="datepicker form-control form-control-sm @error('date_debut') is-invalid @enderror"
                                            id="date_debut" placeholder="jj/mm/aaaa">
                                        @error('date_debut')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                        <label for="date_fin" class="form-label">Date fin</label>
                                        <input type="date" name="date_fin" value="{{ old('date_fin') }}"
                                            class="datepicker form-control form-control-sm @error('date_fin') is-invalid @enderror"
                                            id="date_fin" placeholder="jj/mm/aaaa">
                                        @error('date_fin')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                        <label for="prevue_h" class="form-label">Effectif prévu homme</label>
                                        <input type="number" name="prevue_h" min="0" max="25"
                                            value="{{ old('prevue_h') }}"
                                            class="form-control form-control-sm @error('prevue_h') is-invalid @enderror"
                                            id="prevue_h" placeholder="Effectif homme">
                                        @error('prevue_h')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                        <label for="prevue_f" class="form-label">Effectif prévu femme</label>
                                        <input type="number" name="prevue_f" min="0" max="25"
                                            value="{{ old('prevue_f') }}"
                                            class="form-control form-control-sm @error('prevue_f') is-invalid @enderror"
                                            id="prevue_f" placeholder="Effectif femme">
                                        @error('prevue_f')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                        <label for="frais_operateurs" class="form-label">Frais opérateur</label>
                                        <input type="number" name="frais_operateurs" min="0" step="0.001"
                                            value="{{ old('frais_operateurs') }}"
                                            class="form-control form-control-sm @error('frais_operateurs') is-invalid @enderror"
                                            id="frais_operateurs" placeholder="Frais opérateur">
                                        @error('frais_operateurs')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                        <label for="frais_add" class="form-label">Frais additionels</label>
                                        <input type="number" name="frais_add" min="0" step="0.001"
                                            value="{{ old('frais_add') }}"
                                            class="form-control form-control-sm @error('frais_add') is-invalid @enderror"
                                            id="frais_add" placeholder="Frais additionels">
                                        @error('frais_add')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                        <label for="autes_frais" class="form-label">Autres frais</label>
                                        <input type="number" name="autes_frais" min="0" step="0.001"
                                            value="{{ old('autes_frais') }}"
                                            class="form-control form-control-sm @error('autes_frais') is-invalid @enderror"
                                            id="autes_frais" placeholder="Autres frais">
                                        @error('autes_frais')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                </div>
                                <div class="modal-footer mt-5">
                                    <button type="button" class="btn btn-secondary btn-sm"
                                        data-bs-dismiss="modal">Fermer</button>
                                    <button type="submit" class="btn btn-primary btn-sm">Créer formation</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="generate_rapportFormation" tabindex="-1" role="dialog"
            aria-labelledby="generate_rapportFormationLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="card-header text-center bg-gradient-default">
                        <h1 class="h4 text-black mb-0">Générer rapport</h1>
                    </div>
                    <form method="post" action="{{ route('formations.reports') }}">
                        @csrf
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="annee" class="form-label">Année<span
                                            class="text-danger mx-1">*</span></label>
                                    <select name="annee"
                                        class="form-select form-select-sm @error('annee') is-invalid @enderror"
                                        aria-label="Select" id="select-field-formation-annee-rapport"
                                        data-placeholder="Choisir année">
                                        <option value="{{ old('annee') }}">
                                            {{ old('annee') }}
                                        </option>
                                        @foreach ($formations_annee as $anneeformation)
                                            <option value="{{ $anneeformation->annee }}">
                                                {{ $anneeformation->annee }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('annee')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="region" class="form-label">Statut<span
                                            class="text-danger mx-1">*</span></label>
                                    <select name="statut"
                                        class="form-select form-select-sm @error('statut') is-invalid @enderror"
                                        aria-label="Select" id="select-field-formation-region-rapport"
                                        data-placeholder="Choisir statut">
                                        <option value="{{ old('statut') }}">
                                            {{ old('statut') }}
                                        </option>
                                        <option value="Tous">Tous</option>
                                        @foreach ($formations_statut as $statutformation)
                                            <option value="{{ $statutformation->statut }}">
                                                {{ $statutformation->statut }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('statut')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="pole" class="form-label">
                                        Pôle <span class="text-danger mx-1">*</span>
                                    </label>
                                    <select name="pole_id"
                                        class="form-select form-select-sm @error('pole_id') is-invalid @enderror"
                                        id="select-field-formation-pole-rapport" data-placeholder="Choisir pôle">

                                        <option value="">-- Choisir un pôle --</option>

                                        <option value="Tous" {{ old('pole_id') == 'Tous' ? 'selected' : '' }}>
                                            Tous
                                        </option>

                                        @foreach ($poles as $pole)
                                            <option value="{{ $pole->id }}"
                                                {{ old('pole_id') == $pole->id ? 'selected' : '' }}>
                                                {{ $pole->name ?? $pole->code }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @error('pole_id')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary btn-sm"
                                    data-bs-dismiss="modal">Fermer</button>
                                <div class="text-center">
                                    <button type="submit"
                                        class="btn btn-primary btn-block submit_rapport btn-sm">Générer</button>
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
        new DataTable('#table-formations', {
            layout: {
                topStart: {
                    buttons: ['excel', 'pdf', 'print'],
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
        document.addEventListener('DOMContentLoaded', function() {

            const loadMoreBtn = document.getElementById('loadMoreBtn');
            const missionsContainer = document.getElementById('missions-container');

            if (!loadMoreBtn) return;

            loadMoreBtn.addEventListener('click', function(e) {
                e.preventDefault();

                fetch(this.href)
                    .then(res => res.text())
                    .then(html => {

                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');

                        const newRows = doc.querySelectorAll('#missions-container tr');

                        newRows.forEach(row => {
                            missionsContainer.appendChild(row);
                        });

                        const newBtn = doc.getElementById('loadMoreBtn');

                        if (newBtn) {
                            this.href = newBtn.href;
                        } else {
                            this.remove();
                        }
                    })
                    .catch(err => console.error('Erreur chargement :', err));
            });
        });
    </script>
@endpush
