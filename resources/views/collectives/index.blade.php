@extends('layout.user-layout')
@section('title', 'ONFP | DEMANDES COLLECTIVES')
@section('space-work')
    @can('collective-view')
        <div class="pagetitle">
            {{-- <h1>Data Tables</h1> --}}
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/home') }}">Accueil</a></li>
                    <li class="breadcrumb-item">Tables</li>
                    <li class="breadcrumb-item active">Demandes collectives</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->
        <section class="section dashboard">
            <div class="row">
                <!-- Left side columns -->
                <div class="col-lg-12">
                    <div class="row">
                        <!-- Sales Card -->
                        <div class="col-12 col-md-6 col-lg-3 col-sm-12 col-xs-12 col-xxl-3">
                            <div class="card info-card sales-card">
                                <div class="filter">
                                    <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                            class="bi bi-three-dots"></i></a>
                                </div>
                                <a href="#">
                                    <div class="card-body">
                                        <h5 class="card-title">Collectives<span> | aujourd'hui</span></h5>
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
                                                {{-- <span class="text-muted small pt-2 ps-1">increase</span> --}}
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3 col-sm-12 col-xs-12 col-xxl-3">
                            <div class="card info-card sales-card">
                                <div class="filter">
                                    <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                            class="bi bi-three-dots"></i></a>
                                </div>
                                <a href="#">
                                    <div class="card-body">
                                        <h5 class="card-title">Collectives <span>| toutes</span></h5>
                                        <div class="d-flex align-items-center">
                                            <div
                                                class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                                <i class="bi bi-file-earmark-text"></i>
                                            </div>
                                            <div class="ps-3">
                                                <h6>
                                                    <span class="text-primary">{{ count($collectives) ?? '0' }}</span>
                                                </h6>
                                                <span class="text-success small pt-1 fw-bold">Toutes</span>
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
                                role="alert"><strong>{{ $error }}</strong></div>
                        @endforeach
                    @endif
                    <div class="card">
                        <div class="card-body">
                            @can('collective-create')
                                <div class="pt-1">
                                    <button type="button" class="btn btn-outline-primary btn-sm float-end btn-rounded"
                                        data-bs-toggle="modal" data-bs-target="#AddCollectiveModal">Ajouter</button>
                                </div>
                            @endcan
                            <h5 class="card-title">Liste des demandeurs collectifs</h5>
                            {{-- 
                            @foreach ($collectives as $collective)
                            @endforeach
                            @if (!empty($collective))
                                <table class="table datatables align-middle" id="table-collectives">
                                    <thead>
                                        <tr>
                                            <th>N° DEM.</th>
                                            <th>Nom structure</th>
                                            <th>E-mail</th>
                                            <th>Téléphone</th>
                                            <th>Région</th>
                                            <th width="15%" class="text-center">Dépôt</th>
                                            <th class="text-center">Modules</th>
                                            <th class="text-center">Effectif</th>
                                            <th class="text-center">Statut</th>
                                            <th class="text-center">#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1; ?>
                                        @foreach ($collectives as $collective)
                                            @if (!empty($collective?->numero))
                                                <tr>
                                                    <td>{{ $collective?->numero }}
                                                    </td>
                                                    <td>{{ $collective?->name }}
                                                        @if (!empty($collective?->sigle))
                                                            {{ '(' . $collective?->sigle . ')' }}
                                                        @endif
                                                    </td>
                                                    <td><a
                                                            href="mailto:{{ $collective?->user?->email }}">{{ $collective?->user?->email }}</a>
                                                    </td>
                                                    <td><a
                                                            href="tel:+221{{ $collective?->telephone }}">{{ $collective?->telephone }}</a>
                                                    </td>
                                                    <td>{{ $collective->departement?->region?->nom }}</td>
                                                    <td class="text-center">
                                                        @if ($collective?->date_depot)
                                                            {{ \Carbon\Carbon::parse($collective->date_depot)->diffForHumans() }}
                                                        @else
                                                            Aucun
                                                        @endif
                                                    </td>
                                                    <td class="text-center">{{ count($collective->collectivemodules) }}</td>
                                                    <td class="text-center">{{ count($collective->listecollectives) }}</td>
                                                    <td>
                                                        <span
                                                            class="{{ $collective?->statut_demande }}">{{ $collective?->statut_demande }}</span>
                                                    </td>
                                                    <td>
                                                        @can('collective-show')
                                                            <span class="d-flex align-items-baseline"><a
                                                                    href="{{ route('collectives.show', $collective->id) }}"
                                                                    class="btn btn-primary btn-sm" title="voir détails"><i
                                                                        class="bi bi-eye"></i></a>
                                                                <div class="filter">
                                                                    <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                                                            class="bi bi-three-dots"></i></a>
                                                                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                                                        @can('collective-update')
                                                                            <li><a class="dropdown-item btn btn-sm"
                                                                                    href="{{ route('collectives.edit', $collective->id) }}"
                                                                                    class="mx-1" title="Modifier"><i
                                                                                        class="bi bi-pencil"></i>Modifier</a>
                                                                            </li>
                                                                        @endcan
                                                                        @can('collective-delete')
                                                                            <li>
                                                                                <form
                                                                                    action="{{ route('collectives.destroy', $collective->id) }}"
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
                                                        @endcan
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="alert alert-info">Aucune demande collective reçue pour l'instant !</div>
                            @endif --}}

                            @if ($collectives->isNotEmpty())
                                <table class="table datatables align-middle" id="table-collectives">
                                    <thead>
                                        <tr>
                                            <th>N°</th>
                                            <th width="35%">Nom structure</th>
                                            <th>E-mail</th>
                                            <th>Téléphone</th>
                                            <th>Région</th>
                                            <th width="15%" class="text-center">Dépôt</th>
                                            <th class="text-center">Modules</th>
                                            <th class="text-center">Effectif</th>
                                            <th class="text-center">Statut</th>
                                            <th class="text-center">#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($collectives as $collective)
                                            <tr>
                                                <td>{{ $collective?->numero }}</td>
                                                <td>
                                                    {{ $collective?->name }}
                                                    @if ($collective?->sigle)
                                                        ({{ $collective?->sigle }})
                                                    @endif
                                                </td>
                                                <td><a
                                                        href="mailto:{{ optional($collective?->user)?->email }}">{{ optional($collective?->user)?->email }}</a>
                                                </td>
                                                <td><a
                                                        href="tel:+221{{ $collective?->telephone }}">{{ $collective?->telephone }}</a>
                                                </td>
                                                <td>{{ optional(optional($collective?->departement)?->region)?->nom }}</td>
                                                <td class="text-center">
                                                    {{-- {{ $collective?->date_depot ? \Carbon\Carbon::parse($collective?->date_depot)?->diffForHumans() : 'Aucun' }} --}}
                                                    {{ $collective?->date_depot ? \Carbon\Carbon::parse($collective->date_depot)->format('d/m/Y') : 'Aucun' }}
                                                </td>
                                                <td class="text-center">{{ $collective?->collectivemodules?->count() }}</td>
                                                <td class="text-center">{{ $collective?->listecollectives?->count() }}</td>
                                                <td><span
                                                        class="{{ $collective?->statut_demande }}">{{ $collective?->statut_demande }}</span>
                                                </td>
                                                <td>
                                                    @can('collective-show')
                                                        <span class="d-flex align-items-baseline">
                                                            <a href="{{ route('collectives.show', $collective) }}"
                                                                class="btn btn-primary btn-sm" title="Voir détails">
                                                                <i class="bi bi-eye"></i>
                                                            </a>
                                                            <div class="filter">
                                                                <a class="icon" href="#" data-bs-toggle="dropdown">
                                                                    <i class="bi bi-three-dots"></i>
                                                                </a>
                                                                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                                                    @can('collective-update')
                                                                        <li>
                                                                            <a class="dropdown-item btn btn-sm"
                                                                                href="{{ route('collectives.edit', $collective) }}"
                                                                                title="Modifier">
                                                                                <i class="bi bi-pencil"></i> Modifier
                                                                            </a>
                                                                        </li>
                                                                    @endcan
                                                                    @can('collective-delete')
                                                                        <li>
                                                                            <form
                                                                                action="{{ route('collectives.destroy', $collective) }}"
                                                                                method="post">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                                <button type="submit"
                                                                                    class="dropdown-item show_confirm"
                                                                                    title="Supprimer">
                                                                                    <i class="bi bi-trash"></i> Supprimer
                                                                                </button>
                                                                            </form>
                                                                        </li>
                                                                    @endcan
                                                                </ul>
                                                            </div>
                                                        </span>
                                                    @endcan
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="alert alert-info">Aucune demande collective reçue pour l'instant !</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div
                class="col-12 d-flex flex-column align-items-center justify-content-center">
                <div class="modal fade" id="AddCollectiveModal" tabindex="-1">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <form method="post" action="{{ route('addCollective') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="card-header text-center bg-gradient-default">
                                    <h1 class="h4 text-black mb-0">Ajouter une nouvelle demande collective</h1>
                                </div>
                                <div class="modal-body">
                                    <div class="row g-3">

                                        <div class="col-12 col-md-6 col-lg-4">
                                            <label for="numero" class="form-label">N° courrier<span
                                                    class="text-danger mx-1">*</span></label>
                                            <input type="text" placeholder="Rechercher numéro courrier..."
                                                class="form-control form-control-sm @error('product') is-invalid @enderror"
                                                name="numero_courrier" id="numero" required>
                                            <div id="productList"></div>
                                            @error('numero')
                                                <span class="invalid-feedback" role="alert">
                                                    <div>{{ $message }}</div>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-12 col-md-8">
                                            <label for="objet" class="form-label">Nom de la structure<span
                                                    class="text-danger mx-1">*</span></label>
                                            <input type="text" placeholder="La raison sociale de l'opérateur"
                                                class="form-control form-control-sm @error('objet') is-invalid @enderror"
                                                name="name" id="objet" value="" required>
                                            @error('objet')
                                                <span class="invalid-feedback" role="alert">
                                                    <div>{{ $message }}</div>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="col-12 col-md-4">
                                            <label for="sigle" class="form-label">Sigle</label>
                                            <input type="text" name="sigle" value="{{ old('sigle') }}"
                                                class="form-control form-control-sm @error('sigle') is-invalid @enderror"
                                                id="sigle" placeholder="Sigle ou abréviation">
                                            @error('sigle')
                                                <span class="invalid-feedback" role="alert">
                                                    <div>{{ $message }}</div>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="col-12 col-md-6 col-lg-4">
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

                                        <div class="col-12 col-md-6 col-lg-4">
                                            <label for="fixe" class="form-label">Téléphone fixe</label>
                                            <input name="fixe" type="text" maxlength="12"
                                                class="form-control form-control-sm @error('fixe') is-invalid @enderror"
                                                id="fixe" value="{{ old('fixe') }}" autocomplete="tel"
                                                placeholder="XX:XXX:XX:XX">
                                            @error('fixe')
                                                <span class="invalid-feedback" role="alert">
                                                    <div>{{ $message }}</div>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="col-12 col-md-6 col-lg-4">
                                            <label for="telephone" class="form-label">Téléphone portable<span
                                                    class="text-danger mx-1">*</span></label>
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

                                        <div class="col-12 col-md-6 col-lg-4">
                                            <label for="bp" class="form-label">Boite postal</label>
                                            <input type="text" name="bp" value="{{ old('bp') }}"
                                                class="form-control form-control-sm @error('bp') is-invalid @enderror"
                                                id="bp" placeholder="Boite postal">
                                            @error('bp')
                                                <span class="invalid-feedback" role="alert">
                                                    <div>{{ $message }}</div>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="col-12 col-md-6 col-lg-4">
                                            <label for="statut" class="form-label">Statut juridique<span
                                                    class="text-danger mx-1">*</span></label>
                                            <select name="statut" class="form-select  @error('statut') is-invalid @enderror"
                                                aria-label="Select" id="select-field-statut-col"
                                                data-placeholder="Choisir statut">
                                                <option value="{{ old('statut') }}">
                                                    {{ old('statut') }}
                                                </option>
                                                <option value="GIE">
                                                    GIE
                                                </option>
                                                <option value="Association">
                                                    Association
                                                </option>
                                                <option value="Entreprise">
                                                    Entreprise
                                                </option>
                                                <option value="Institution publique">
                                                    Institution publique
                                                </option>
                                                <option value="Institution privée">
                                                    Institution privée
                                                </option>
                                                <option value="Autre">
                                                    Autre
                                                </option>
                                            </select>
                                            @error('statut')
                                                <span class="invalid-feedback" role="alert">
                                                    <div>{{ $message }}</div>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="col-12 col-md-6 col-lg-4">
                                            <label for="autre_statut" class="form-label">Si autre ?
                                                précisez</label>
                                            <input type="text" name="autre_statut" value="{{ old('autre_statut') }}"
                                                class="form-control form-control-sm @error('autre_statut') is-invalid @enderror"
                                                id="autre_statut" placeholder="autre statut juridique">
                                            @error('autre_statut')
                                                <span class="invalid-feedback" role="alert">
                                                    <div>{{ $message }}</div>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                            <label for="date_depot" class="form-label">Date dépot<span
                                                    class="text-danger mx-1">*</span></label>
                                            <input type="date" name="date_depot" value="{{ old('date_depot') }}"
                                                class="datepicker form-control form-control-sm @error('date_depot') is-invalid @enderror"
                                                id="date_depot" placeholder="jj/mm/aaaa">
                                            @error('date_depot')
                                                <span class="invalid-feedback" role="alert">
                                                    <div>{{ $message }}</div>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="col-12 col-md-6 col-lg-4">
                                            <label for="departement" class="form-label">Département<span
                                                    class="text-danger mx-1">*</span></label>
                                            <select name="departement"
                                                class="form-select form-select-sm @error('departement') is-invalid @enderror"
                                                aria-label="Select" id="select-field-departement-col"
                                                data-placeholder="Choisir">
                                                <option value="{{ old('departement') }}">{{ old('departement') }}</option>
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

                                        <div class="col-12">
                                            <label for="adresse" class="form-label">Adresse<span
                                                    class="text-danger mx-1">*</span></label>
                                            <textarea name="adresse" id="adresse" rows="1"
                                                class="form-control form-control-sm @error('adresse') is-invalid @enderror" placeholder="Adresse exacte">{{ old('description') }}</textarea>
                                            @error('adresse')
                                                <span class="invalid-feedback" role="alert">
                                                    <div>{{ $message }}</div>
                                                </span>
                                            @enderror
                                        </div>

                                        {{-- <div class="col-12 col-md-6 col-lg-3 col-sm-12 col-xs-12 col-xxl-3">
                                        <label for="module" class="form-label">Formation sollicitée<span
                                                class="text-danger mx-1">*</span></label>
                                        <select name="module" class="form-select  @error('module') is-invalid @enderror"
                                            aria-label="Select" id="select-field-module-col"
                                            data-placeholder="Choisir formation">
                                            <option value="">
                                                {{ old('module') }}
                                            </option>
                                            @foreach ($modules as $module)
                                                <option value="{{ $module->id }}">
                                                    {{ $module->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('module')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div> --}}

                                        <div class="col-12">
                                            <label for="description" class="form-label">Description de l'organisation<span
                                                    class="text-danger mx-1">*</span></label>
                                            <textarea name="description" id="description" rows="4"
                                                class="form-control form-control-sm @error('description') is-invalid @enderror"
                                                placeholder="Description de l'organisation, de ses activités et de ses réalisations">{{ old('description') }}</textarea>

                                            @error('description')
                                                <span class="invalid-feedback" role="alert">
                                                    <div>{{ $message }}</div>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="col-12">
                                            <label for="projetprofessionnel" class="form-label">Projet professionnel<span
                                                    class="text-danger mx-1">*</span></label>
                                            <textarea name="projetprofessionnel" id="projetprofessionnel" rows="4"
                                                class="form-control form-control-sm @error('projetprofessionnel') is-invalid @enderror"
                                                placeholder="Description détaillée du projet professionnel et de l'effet attendu après la formation">{{ old('projetprofessionnel') }}</textarea>

                                            @error('projetprofessionnel')
                                                <span class="invalid-feedback" role="alert">
                                                    <div>{{ $message }}</div>
                                                </span>
                                            @enderror
                                        </div>

                                        <hr class="dropdown-divider mt-3">

                                        <div class="col-12 col-md-6 col-lg-4">
                                            <label for="civilite" class="form-label">Civilité responsable<span
                                                    class="text-danger mx-1">*</span></label>
                                            <select name="civilite"
                                                class="form-select form-select-sm @error('civilite') is-invalid @enderror"
                                                aria-label="Select" id="select-field-civilite-col"
                                                data-placeholder="Choisir civilité">
                                                <option value="{{ old('civilite') }}">
                                                    {{ old('civilite') }}
                                                </option>
                                                <option value="Monsieur">
                                                    Monsieur
                                                </option>
                                                <option value="Madame">
                                                    Madame
                                                </option>
                                            </select>
                                            @error('civilite')
                                                <span class="invalid-feedback" role="alert">
                                                    <div>{{ $message }}</div>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="col-12 col-md-6 col-lg-4">
                                            <label for="prenom" class="form-label">Prénom responsable<span
                                                    class="text-danger mx-1">*</span></label>
                                            <input type="text" name="prenom" value="{{ old('prenom') }}"
                                                class="form-control form-control-sm @error('prenom') is-invalid @enderror"
                                                id="prenom" placeholder="Prénom responsable">
                                            @error('prenom')
                                                <span class="invalid-feedback" role="alert">
                                                    <div>{{ $message }}</div>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="col-12 col-md-6 col-lg-4">
                                            <label for="nom" class="form-label">Nom responsable<span
                                                    class="text-danger mx-1">*</span></label>
                                            <input type="text" name="nom" value="{{ old('nom') }}"
                                                class="form-control form-control-sm @error('nom') is-invalid @enderror"
                                                id="nom" placeholder="Nom responsable">
                                            @error('nom')
                                                <span class="invalid-feedback" role="alert">
                                                    <div>{{ $message }}</div>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="col-12 col-md-6 col-lg-4">
                                            <label for="email_responsable" class="form-label">Adresse e-mail<span
                                                    class="text-danger mx-1">*</span></label>
                                            <input type="email" name="email_responsable"
                                                value="{{ old('email_responsable') }}"
                                                class="form-control form-control-sm @error('email_responsable') is-invalid @enderror"
                                                id="email_responsable" placeholder="Adresse email responsable">
                                            @error('email_responsable')
                                                <span class="invalid-feedback" role="alert">
                                                    <div>{{ $message }}</div>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="col-12 col-md-6 col-lg-4">
                                            <label for="telephone_responsable" class="form-label">Téléphone responsable<span
                                                    class="text-danger mx-1">*</span></label>
                                            <input name="telephone_responsable" type="text" maxlength="12"
                                                class="form-control form-control-sm @error('telephone_responsable') is-invalid @enderror"
                                                id="telephone_responsable" value="{{ old('telephone_responsable') }}"
                                                autocomplete="tel" placeholder="XX:XXX:XX:XX">
                                            @error('telephone_responsable')
                                                <span class="invalid-feedback" role="alert">
                                                    <div>{{ $message }}</div>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="col-12 col-md-6 col-lg-4">
                                            <label for="fonction_responsable" class="form-label">Fonction responsable<span
                                                    class="text-danger mx-1">*</span></label>
                                            <input type="text" name="fonction_responsable"
                                                value="{{ old('fonction_responsable') }}"
                                                class="form-control form-control-sm @error('fonction_responsable') is-invalid @enderror"
                                                id="fonction_responsable" placeholder="Fonction responsable">
                                            @error('fonction_responsable')
                                                <span class="invalid-feedback" role="alert">
                                                    <div>{{ $message }}</div>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="modal-footer mt-5">
                                        <button type="button" class="btn btn-secondary btn-sm"
                                            data-bs-dismiss="modal">Fermer</button>
                                        <button type="submit" class="btn btn-primary btn-sm">Enregistrer</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endcan
@endsection
@push('scripts')
    <script>
        new DataTable('#table-collectives', {
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
    <script type="text/javascript">
        $(document).ready(function() {
            $('#numero').keyup(function() {
                var query = $(this).val().trim();
                if (query !== '') {
                    $.ajax({
                        url: "{{ route('collectives.fetch') }}", // Changez cette URL si nécessaire
                        method: "POST",
                        data: {
                            query: query,
                            _token: "{{ csrf_token() }}"
                        },
                        dataType: "json",
                        success: function(response) {
                            if (response.html) {
                                $('#productList').fadeIn().html(response.html);
                            } else {
                                $('#productList').fadeOut();
                            }
                        }
                    });
                } else {
                    $('#productList').fadeOut();
                }
            });

            $(document).on('click', 'li', function() {
                var selectedNumero = $(this).text();
                var selectedId = $(this).data("id");

                $('#numero').val(selectedNumero); // Remplir le champ numéro
                $('#id').val(selectedId);

                // Appeler l'API pour récupérer l'objet associé au numéro sélectionné
                $.ajax({
                    url: "{{ route('getObjetByNumero') }}", // Définir une route pour récupérer l'objet
                    method: "GET",
                    data: {
                        numero: selectedNumero
                    },
                    success: function(response) {
                        $('#objet').val(response
                            .objet); // Remplir automatiquement le champ 'objet'
                        $('#date_depot').val(response
                            .date_depot); // Remplir automatiquement le champ 'objet'
                    },
                    error: function() {
                        alert('Erreur lors de la récupération de l\'objet.');
                    }
                });

                $('#productList').fadeOut();
            });
        });
    </script>
@endpush
