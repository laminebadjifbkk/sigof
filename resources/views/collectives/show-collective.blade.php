@extends('layout.user-layout')
@section('title', 'Mon dossier de demandes collectives')
@section('space-work')
    <section class="section">
        <div class="row justify-content-center">
            <div class="col-12 col-md-12 col-lg-12">
                @if ($message = Session::get('status'))
                    <div class="alert alert-danger bg-danger text-light border-0 alert-dismissible fade show" region="alert">
                        <strong>{{ $message }}</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if ($message = Session::get('success'))
                    <div class="alert alert-success bg-success text-light border-0 alert-dismissible fade show"
                        region="alert">
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
                    <div class="card-header text-center bg-gradient-default">
                        <h1 class="h4 text-black mb-0">DEMANDES COLLECTIVES</h1>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mt-0">
                            <span class="d-flex align-items-baseline"><a href="{{ url('/profil') }}"
                                    class="btn btn-success btn-sm" title="retour"><i
                                        class="bi bi-arrow-counterclockwise"></i></a>&nbsp;
                                <p> | retour</p>
                            </span>
                            <button type="button" class="btn btn-info btn-sm">
                                <span class="badge bg-white text-info">{{ $collective_total }}/1</span>
                            </button>
                        </div>
                        <h5 class="card-title">
                            Bienvenue {{ Auth::user()->civilite . ' ' . Auth::user()->name }}</h5>
                        <!-- demande -->
                        {{-- <form method="post" action="#" enctype="multipart/form-data" class="row g-3"> --}}
                        <table class="table table-bordered table-hover table-borderless">
                            <thead>
                                <tr>
                                    <th scope="col">N° demande</th>
                                    <th scope="col">Nom structure</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Téléphone</th>
                                    <th scope="col">Localité</th>
                                    <th scope="col">Statut</th>
                                    <th class="col"><i class="bi bi-gear"></i></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach (Auth::user()?->collectives as $collective)
                                    @isset($collective?->numero)
                                        <tr>
                                            <td>{{ $collective?->numero }}
                                            </td>
                                            <td>{{ $collective?->name }}
                                                @isset($collective?->sigle)
                                                    {{ '(' . $collective?->sigle . ')' }}
                                                @endisset
                                            </td>
                                            <td><a href="mailto:{{ $collective->email }}">{{ $collective->email }}</a>
                                            </td>
                                            <td><a
                                                    href="tel:+221{{ $collective->telephone }}">{{ $collective->telephone }}</a>
                                            </td>
                                            <td>{{ $collective->departement?->region?->nom }}</td>
                                            <td>
                                                <span
                                                    class="{{ $collective?->statut_demande }}">{{ $collective?->statut_demande }}</span>
                                            </td>
                                            <td>
                                                @can('view', $collective)
                                                    <span class="d-flex align-items-baseline"><a
                                                            href="{{ route('collectives.show', $collective->id) }}"
                                                            class="btn btn-primary btn-sm" title="voir détails"><i
                                                                class="bi bi-eye"></i></a>
                                                        <div class="filter">
                                                            <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                                                    class="bi bi-three-dots"></i></a>
                                                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                                                @can('update', $collective)
                                                                    <li><a class="dropdown-item btn btn-sm"
                                                                            href="{{ route('collectives.edit', $collective->id) }}"
                                                                            class="mx-1" title="Modifier"><i
                                                                                class="bi bi-pencil"></i>Modifier</a>
                                                                    </li>
                                                                @endcan
                                                                @can('delete', $collective)
                                                                    <li>
                                                                        <form
                                                                            action="{{ route('collectives.destroy', $collective->id) }}"
                                                                            method="post">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="submit" class="dropdown-item show_confirm"
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
                                    @endisset
                                @endforeach
                            </tbody>
                        </table>
                        {{-- </form> --}}
                    </div>
                </div>
                <div class="row mb-3 pt-5">
                    <h5 class="card-title col-12 col-md-4 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                        FICHIERS JOINTS</h5>
                    <div class="col-12 col-md-8 col-lg-8 col-sm-12 col-xs-12 col-xxl-8">
                        <table class="table table-bordered table-hover datatables" id="table-iles">
                            <thead>
                                <tr class="text-center">
                                    <th style="width: 5%">N°</th>
                                    <th>Légende</th>
                                    <th style="width: 10%">Fichier</th>
                                    <th style="width: 10%">Statut</th>
                                    <th style="width: 10%">Supprimer</th>
                                    @hasanyrole('super-admin|admin|DIOF')
                                        <th style="width: 10%">Valider</th>
                                        <th style="width: 10%">Rejeter</th>
                                    @endhasanyrole
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- <?php $i = 1; ?>
                                @foreach ($files as $file)
                                    <tr>
                                        <td class="text-center">{{ $i++ }}</td>
                                        <td>{{ $file?->legende }}</td>
                                        <td class="text-center">
                                            <a class="btn btn-default btn-sm" title="télécharger le fichier joint"
                                                target="_blank" href="{{ asset($file->getFichier()) }}">
                                                <i class="bi bi-download"></i>
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            <form action="{{ route('fileDestroy') }}" method="post">
                                                @csrf
                                                @method('put')
                                                <input type="hidden" name="idFile" value="{{ $file->id }}">
                                                <button type="submit" style="background:none;border:0px;"
                                                    class="show_confirm" title="retirer">
                                                    <span class="badge border-danger border-1 text-danger">
                                                        <i class="bi bi-trash"></i>
                                                    </span>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach --}} @php $i = 1; @endphp
                                    @foreach ($files as $file)
                                    <tr class="text-center align-middle">
                                        <td>{{ $i++ }}</td>
                                        <td>{{ $file->legende }}</td>
                                        <td>
                                            <a class="btn btn-outline-secondary btn-sm" title="Télécharger"
                                                target="_blank" href="{{ asset($file->getFichier()) }}">
                                                <i class="bi bi-download"></i>
                                            </a>
                                        </td>
                                        <td>
                                            @php
                                                $statut = $file->statut ?? 'Attente';
                                                $badgeClass = match ($statut) {
                                                    'Validé' => 'success',
                                                    'Rejeté', 'Invalide' => 'danger',
                                                    default => 'secondary',
                                                };
                                            @endphp
                                            <span class="badge bg-{{ $badgeClass }}">{{ $statut }}</span>
                                        </td>
                                        {{-- Supprimer --}}
                                        <td>
                                            @if ($file->statut !== 'Validé')
                                                <form action="{{ route('fileDestroy') }}" method="post"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('put')
                                                    <input type="hidden" name="idFile"
                                                        value="{{ $file->id }}">
                                                    <button type="submit"
                                                        class="btn btn-outline-danger btn-sm show_confirm"
                                                        title="Supprimer">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                        @hasanyrole('super-admin|admin|DIOF')
                                            {{-- Valider --}}
                                            <td>
                                                <form action="{{ route('fileValidate') }}" method="post"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('put')
                                                    <input type="hidden" name="idFile"
                                                        value="{{ $file->id }}">
                                                    <button type="submit"
                                                        class="btn btn-outline-success btn-sm show_confirm_valider"
                                                        title="Valider">
                                                        <i class="bi bi-check-circle"></i>
                                                    </button>
                                                </form>
                                            </td>
                                            {{-- Invalider --}}
                                            <td>
                                                <form action="{{ route('fileInvalide') }}" method="post"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('put')
                                                    <input type="hidden" name="idFile"
                                                        value="{{ $file->id }}">
                                                    <button type="submit"
                                                        class="btn btn-outline-warning btn-sm show_confirm_rejeter"
                                                        title="Invalider">
                                                        <i class="bi bi-x-circle"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        @endhasanyrole
                                    </tr> @endforeach
                                    </tbody>
                        </table>
                    </div>
                </div>
                <form method="post" action="{{ route('files.update', Auth::user()?->uuid) }}"
                    enctype="multipart/form-data">
                    @csrf
                    @method('patch')
                    <h5 class="card-title">JOINDRE LES DOCUMENTS DE VOTRE STRUCTURE</h5>
                    <input type="hidden" name="idUser" value="{{ Auth::user()?->id }}">
                    <span style="color:red;">NB:</span>
                    <span>Seul l'acte de création </span><span style="color:red;"> est
                        exigé</span>.
                    <div class="row mb-3 mt-3">
                        <label for="legende"
                            class="col-12 col-md-4 col-lg-4 col-sm-12 col-xs-12 col-xxl-4 col-form-label">LEGENDE<span
                                class="text-danger mx-1">*</span></label>
                        <div class="col-12 col-md-8 col-lg-8 col-sm-12 col-xs-12 col-xxl-8">
                            <select name="legende" class="form-select  @error('legende') is-invalid @enderror"
                                aria-label="Select" id="select-field-file" data-placeholder="Choisir">
                                <option value="{{ old('legende') }}">

                                </option>
                                @foreach ($user_files as $file)
                                    <option value="{{ $file?->id }}">
                                        {{ $file?->legende }}
                                    </option>
                                @endforeach
                            </select>
                            @error('legende')
                                <span class="invalid-feedback" role="alert">
                                    <div>{{ $message }}</div>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="file"
                            class="col-12 col-md-4 col-lg-4 col-sm-12 col-xs-12 col-xxl-4 col-form-label">FICHIER<span
                                class="text-danger mx-1">*</span></label>
                        <div class="col-12 col-md-8 col-lg-8 col-sm-12 col-xs-12 col-xxl-8">
                            <div class="pt-2">
                                <input type="file" name="file" id="file"
                                    class="form-control @error('file') is-invalid @enderror btn btn-primary btn-sm">
                                @error('file')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="file"
                            class="col-12 col-md-4 col-lg-4 col-sm-12 col-xs-12 col-xxl-4 col-form-label"><span
                                class="text-danger mx-1"></span></label>
                        <div class="col-12 col-md-8 col-lg-8 col-sm-12 col-xs-12 col-xxl-8">
                            <div class="pt-2">
                                <button type="submit" class="btn btn-info btn-sm text-white">ENREGISTRER</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        {{-- Ajouter un autre choix --}}
        <div class="col-lg-12 col-md-12 d-flex flex-column align-items-center justify-content-center">
            <div class="modal fade" id="AddCollectiveModal" tabindex="-1">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <form method="post" action="{{ route('collectives.store') }}" enctype="multipart/form-data">
                            @csrf
                            {{-- <div class="modal-header">
                                <h5 class="modal-title"><i class="bi bi-plus" title="Ajouter"></i> Ajouter une demande
                                    collective</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div> --}}
                            <div class="card-header text-center bg-gradient-default">
                                <h1 class="h4 text-black mb-0"> ajouter une nouvelle demande collective</h1>
                            </div>
                            <div class="modal-body">
                                <div class="row g-3">
                                    <div class="col-12 col-md-12 col-lg-12 mb-0">
                                        <label for="name" class="form-label">Nom de la structure<span
                                                class="text-danger mx-1">*</span></label>
                                        <textarea name="name" id="name" rows="1"
                                            class="form-control form-control-sm @error('name') is-invalid @enderror"
                                            placeholder="La raison sociale de l'opérateur">{{ old('name') }}</textarea>
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-4 col-lg-4 mb-0">
                                        <label for="sigle" class="form-label">Sigle<span
                                                class="text-danger mx-1">*</span></label>
                                        <input type="text" name="sigle" value="{{ old('sigle') }}"
                                            class="form-control form-control-sm @error('sigle') is-invalid @enderror"
                                            id="sigle" placeholder="Sigle ou abréviation">
                                        @error('sigle')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-4 col-lg-4 mb-0">
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

                                    <div class="col-12 col-md-4 col-lg-4 mb-0">
                                        <label for="fixe" class="form-label">Téléphone fixe</label>
                                        <input type="number" name="fixe" value="{{ old('fixe') }}"
                                            class="form-control form-control-sm @error('fixe') is-invalid @enderror"
                                            id="fixe" placeholder="3xxxxxxxx">
                                        @error('fixe')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-4 col-lg-4 mb-0">
                                        <label for="telephone" class="form-label">Téléphone portable<span
                                                class="text-danger mx-1">*</span></label>
                                        <input type="number" min="0" name="telephone"
                                            value="{{ old('telephone') }}"
                                            class="form-control form-control-sm @error('telephone') is-invalid @enderror"
                                            id="telephone" placeholder="7xxxxxxxx">
                                        @error('telephone')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-4 col-lg-4 mb-0">
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

                                    <div class="col-12 col-md-4 col-lg-4 mb-0">
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

                                    <div class="col-12 col-md-4 col-lg-4 mb-0">
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
                                    <div class="col-12 col-md-4 col-lg-4 mb-0">
                                        <label for="departement" class="form-label">Département<span
                                                class="text-danger mx-1">*</span></label>
                                        <select name="departement"
                                            class="form-select form-select-sm @error('departement') is-invalid @enderror"
                                            aria-label="Select" id="select-field-departement-col"
                                            data-placeholder="Choisir">
                                            <option>{{ old('departement') }}</option>
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

                                    <div class="col-12 col-md-4 col-lg-4 mb-0">
                                        <label for="adresse" class="form-label">Adresse<span
                                                class="text-danger mx-1">*</span></label>
                                        <input type="text" name="adresse" value="{{ old('adresse') }}"
                                            class="form-control form-control-sm @error('adresse') is-invalid @enderror"
                                            id="adresse" placeholder="Adresse exacte">
                                        @error('adresse')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    {{-- <div class="col-12 col-md-12 col-lg-12 mb-0">
                                        <label for="module" class="form-label">Formation sollicitée<span
                                                class="text-danger mx-1">*</span></label>
                                        <input type="text" name="module" value="{{ old('module_name') }}"
                                            class="form-control form-control-sm @error('module_name') is-invalid @enderror"
                                            id="module_name" placeholder="Nom du module" autofocus>
                                        <div id="countryList"></div>
                                        {{ csrf_field() }}
                                        @error('module')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div> --}}

                                    <div class="col-12 col-md-12 col-lg-12 mb-0">
                                        <label for="description" class="form-label">Description de l'organisation<span
                                                class="text-danger mx-1">*</span></label>
                                        <textarea name="description" id="description" rows="2"
                                            class="form-control form-control-sm @error('description') is-invalid @enderror"
                                            placeholder="Description de l'organisation, de ses activités et de ses réalisations">{{ old('description') }}</textarea>

                                        @error('description')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-12 col-lg-12 mb-0">
                                        <label for="projetprofessionnel" class="form-label">Projet professionnel<span
                                                class="text-danger mx-1">*</span></label>
                                        <textarea name="projetprofessionnel" id="projetprofessionnel" rows="2"
                                            class="form-control form-control-sm @error('projetprofessionnel') is-invalid @enderror"
                                            placeholder="Description détaillée du projet professionnel et de l'effet attendu après la formation">{{ old('projetprofessionnel') }}</textarea>

                                        @error('projetprofessionnel')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <hr class="dropdown-divider mt-3">

                                    <div class="col-12 col-md-4 col-lg-4 mb-0">
                                        <label for="civilite" class="form-label">Civilité responsable<span
                                                class="text-danger mx-1">*</span></label>
                                        <select name="civilite"
                                            class="form-select form-select-sm @error('civilite') is-invalid @enderror"
                                            aria-label="Select" id="select-field-civilite"
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
                                    <div class="col-12 col-md-4 col-lg-4 mb-0">
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

                                    <div class="col-12 col-md-4 col-lg-4 mb-0">
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

                                    <div class="col-12 col-md-4 col-lg-4 mb-0">
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

                                    <div class="col-12 col-md-4 col-lg-4 mb-0">
                                        <label for="telephone_responsable" class="form-label">Téléphone responsable<span
                                                class="text-danger mx-1">*</span></label>
                                        <input type="number" min="0" name="telephone_responsable"
                                            value="{{ old('telephone_responsable') }}"
                                            class="form-control form-control-sm @error('telephone_responsable') is-invalid @enderror"
                                            id="telephone_responsable" placeholder="7xxxxxxxx">
                                        @error('telephone_responsable')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-4 col-lg-4 mb-0">
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
@endsection
