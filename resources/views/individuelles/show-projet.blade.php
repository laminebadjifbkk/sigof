@extends('layout.user-layout')
@section('title', 'ONFP | ' . $projet?->sigle)
@section('space-work')
    <section class="section">
        <div class="row justify-content-center">
            <div class="col-12 col-md-12 col-lg-12 col-sm-12 col-xs-12 col-xxl-12">
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
                    {{-- <div class="card-header text-center bg-gradient-default">
                        <h1 class="h4 text-black mb-0">{{ strtoupper($projet?->type_projet . ' ' . $projet?->sigle) }}</h1>
                    </div> --}}
                    <div class="card-header">
                        <h5 class="card-title">{{ strtoupper($projet?->type_projet . ' ' . $projet?->sigle) }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mt-0">
                            <span class="d-flex align-items-baseline"><a href="{{ url('/profil') }}"
                                    class="btn btn-success btn-sm" title="retour"><i
                                        class="bi bi-arrow-counterclockwise"></i></a>&nbsp;
                                <p> | Profil</p>
                            </span>
                            <button type="button" class="btn btn-info btn-sm">
                                <span class="badge bg-white text-info">{{ $individuelle_total }} sur 3</span>
                            </button>

                            @if (!empty(Auth::user()->cin) && !empty($statut))
                                @if ($individuelle_total < 3 && !empty(Auth::user()?->cin))
                                    <!-- Conteneur Flex Vertical pour les boutons -->
                                    {{-- <div class="d-flex flex-column align-items-start gap-2">
                                        <!-- Bouton Choisir Localité -->
                                        <button type="button"
                                            class="btn btn-primary btn-sm rounded-pill px-4 shadow-sm d-flex align-items-center gap-2"
                                            data-bs-toggle="modal" data-bs-target="#ChoisirLocaliteModal">
                                            <i class="bi bi-map-fill"></i>
                                            {{ __("Choisir d'abord la localité") }}
                                        </button>

                                        <!-- Bouton Formuler une autre demande -->
                                        <button type="button"
                                            class="btn btn-success btn-sm rounded-pill px-4 shadow-sm d-flex align-items-center gap-2"
                                            data-bs-toggle="modal" data-bs-target="#AddIndividuelleModal">
                                            <i class="bi bi-plus-circle-fill"></i>
                                            Formuler une autre demande
                                        </button>
                                    </div> --}}
                                @endif
                            @endif
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-0">
                            <h5 class="card-title">
                                Bonjour
                                {{ Auth::user()->civilite . ' ' . Auth::user()->firstname . ' ' . Auth::user()->name }}
                            </h5>
                        </div>
                        <table class="table table-bordered table-hover table-borderless">
                            <thead>
                                <tr>
                                    <th width="2%" class="text-center">N°</th>
                                    <th width="8%" class="text-center">Numéro</th>
                                    <th>Module</th>
                                    <th width="12%" class="text-center">Département</th>
                                    <th width="12%" class="text-center">Niveau étude</th>
                                    <th width="13%" class="text-center">Diplome académique</th>
                                    <th width="13%" class="text-center">Diplome professionnel</th>
                                    <th width="5%" class="text-center">Statut</th>
                                    <th style="width:5%;"><i class="bi bi-gear"></i></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; ?>
                                @foreach ($individuelles as $individuelle)
                                    @isset($individuelle->numero)
                                        <tr>
                                            <td class="text-center">{{ $i++ }}</td>
                                            <td class="text-center">{{ $individuelle?->numero }}</td>
                                            <td>{{ $individuelle?->module?->name }}</td>
                                            <td class="text-center">{{ $individuelle?->departement?->nom }}</td>
                                            <td class="text-center">{{ $individuelle?->niveau_etude }}</td>
                                            <td class="text-center">{{ $individuelle?->diplome_academique }}</td>
                                            <td class="text-center">{{ $individuelle?->diplome_professionnel }}</td>
                                            <td class="text-center">
                                                <span class="{{ $individuelle?->statut }}">{{ $individuelle?->statut }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="d-flex align-items-baseline">
                                                    <a href="{{ route('individuelles.show', $individuelle) }}"
                                                        class="btn btn-success btn-sm" title="voir détails"><i
                                                            class="bi bi-eye"></i></a>
                                                    <div class="filter">
                                                        <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                                                class="bi bi-three-dots"></i></a>
                                                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                                            <li><a class="dropdown-item btn btn-sm"
                                                                    href="{{ route('individuelles.edit', $individuelle) }}"
                                                                    class="mx-1" title="Modifier"><i
                                                                        class="bi bi-pencil"></i>Modifier</a>
                                                            </li>
                                                            <li>
                                                                <form
                                                                    action="{{ route('individuelles.destroy', $individuelle) }}"
                                                                    method="post">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="dropdown-item show_confirm"
                                                                        title="Supprimer"><i
                                                                            class="bi bi-trash"></i>Supprimer</button>
                                                                </form>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </span>
                                            </td>
                                        </tr>
                                    @endisset
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="card-title">{{ 'Nombre de modules : ' . $projet->projetmodules->count() }}</h5>
            </div>
            <div class="card-body">
                @if ($projet->projetmodules && $projet->projetmodules->count())
                    <ul class="list-group list-group-flush">
                        @foreach ($projet->projetmodules as $index => $module)
                            <li class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center gap-2" style="cursor: pointer;"
                                        data-bs-toggle="collapse" data-bs-target="#collapseModule-{{ $module->id }}">
                                        <i class="bi bi-chevron-down"></i> {{-- Icône flèche --}}
                                        <strong>{{ $index + 1 }}. {{ $module->module }}</strong>
                                    </div>

                                    <!-- Bouton Formuler une demande -->
                                    <button type="button"
                                        class="btn btn-outline-success btn-sm rounded-pill px-3 shadow-sm d-flex align-items-center gap-2"
                                        data-bs-toggle="modal" data-bs-target="#AddIndividuelleModal{{ $module->id }}">
                                        <i class="bi bi-plus-circle-fill"></i>
                                        Ajouter
                                    </button>
                                </div>

                                <!-- Zone de description cachée -->
                                <div class="collapse mt-3" id="collapseModule-{{ $module->id }}">
                                    <div class="text-muted">
                                        {!! '- ' . implode('- ', array_map(fn($line) => nl2br(e($line)), explode("\n", ucfirst($module->description)))) !!}
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="alert alert-info text-center text-muted">
                        Aucun module disponible.
                    </div>
                @endif
            </div>
        </div>

        @foreach (Auth::user()?->individuelles as $individuelle)
            @foreach ($projet->projetmodules as $index => $projetmodule)
                <div
                    class="col-12 col-md-12 col-lg-12 col-sm-12 col-xs-12 col-xxl-12 d-flex flex-column align-items-center justify-content-center">
                    <div class="modal fade" id="AddIndividuelleModal{{ $projetmodule->id }}" tabindex="-1">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <form method="post" action="{{ route('individuellesStore') }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="card-header text-center bg-gradient-default">
                                        <span class="text-black mb-0">➕ Formuler demande pour le module :
                                            {{ $projetmodule->module }}</span>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row g-3">
                                            <input type="hidden" value="{{ $projetmodule->module }}" name="module">
                                            {{-- <div class="col-12 col-md-12 col-lg-8 col-sm-12 col-xs-12 col-xxl-8">
                                                <label for="module" class="form-label">Formation sollicitée<span
                                                        class="text-danger mx-1">*</span></label>
                                                <input type="hidden" name="idprojet" value="{{ $projet?->id }}">
                                                <select name="module"
                                                    class="form-select  @error('module') is-invalid @enderror"
                                                    aria-label="Select" id="select-field-projetmodule-ind"
                                                    data-placeholder="Choisir module">
                                                    <option value="{{ old('module') }}">
                                                        {{ old('module') }}</option>
                                                    @foreach ($projet?->projetmodules as $projetmodule)
                                                        <option value="{{ $projetmodule->module }}">
                                                            {{ $projetmodule->module }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('module')
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div> --}}
                                            <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                                <label for="module_select_{{ $projetmodule->id }}" class="form-label">
                                                    Lieu de formation <span class="text-danger mx-1">*</span>
                                                </label>
                                            
                                                <select name="departement" id="module_select_{{ $projetmodule->id }}"
                                                    class="form-select form-select-sm @error('departement') is-invalid @enderror"
                                                    aria-label="Select">
                                                    
                                                    <!-- OPTION PLACEHOLDER -->
                                                    <option value="" disabled selected>Choisir la localité</option>
                                            
                                                    @foreach ($projetmodule->projetlocalites as $projetlocalite)
                                                        <option value="{{ $projetlocalite->localite }}"
                                                            {{ old('departement') == $projetlocalite->localite ? 'selected' : '' }}>
                                                            {{ $projetlocalite->localite }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            
                                                @error('departement')
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div>                                            

                                            <div class="col-12 col-md-12 col-lg-6 col-sm-12 col-xs-12 col-xxl-4">
                                                <label for="adresse" class="form-label">Adresse<span
                                                        class="text-danger mx-1">*</span></label>
                                                <input type="text" name="adresse"
                                                    value="{{ $individuelle?->adresse ?? old('adresse') }}"
                                                    class="form-control form-control-sm @error('adresse') is-invalid @enderror"
                                                    id="adresse" placeholder="adresse">
                                                @error('adresse')
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="col-12 col-md-12 col-lg-6 col-sm-12 col-xs-12 col-xxl-4">
                                                <label for="telephone_secondaire" class="form-label">Téléphone
                                                    secondaire<span class="text-danger mx-1">*</span></label>
                                                <input name="telephone_secondaire" type="text" maxlength="12"
                                                    class="form-control form-control-sm @error('telephone_secondaire') is-invalid @enderror"
                                                    id="telephone_secondaire" value="{{ old('telephone_secondaire') }}"
                                                    autocomplete="tel" placeholder="XX:XXX:XX:XX">
                                                {{-- <input type="number" name="telephone_secondaire" min="0"
                                                value="{{ $individuelle?->telephone ?? old('telephone_secondaire') }}"
                                                class="form-control form-control-sm @error('telephone_secondaire') is-invalid @enderror"
                                                id="telephone_secondaire" placeholder="7x xxx xx xx"> --}}
                                                @error('telephone_secondaire')
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="col-12 col-md-12 col-lg-6 col-sm-12 col-xs-12 col-xxl-4">
                                                <label for="Niveau étude" class="form-label">Niveau étude<span
                                                        class="text-danger mx-1">*</span></label>
                                                <select name="niveau_etude"
                                                    class="form-select  @error('niveau_etude') is-invalid @enderror"
                                                    aria-label="Select" id="select-field-niveau_etude-ind"
                                                    data-placeholder="Choisir niveau étude">
                                                    <option
                                                        value="{{ $individuelle->niveau_etude ?? old('niveau_etude') }}">
                                                        {{ $individuelle->niveau_etude ?? old('niveau_etude') }}
                                                    </option>
                                                    <option value="Aucun">
                                                        Aucun
                                                    </option>
                                                    <option value="Arabe">
                                                        Arabe
                                                    </option>
                                                    <option value="Elementaire">
                                                        Elementaire
                                                    </option>
                                                    <option value="Secondaire">
                                                        Secondaire
                                                    </option>
                                                    <option value="Moyen">
                                                        Moyen
                                                    </option>
                                                    <option value="Supérieur">
                                                        Supérieur
                                                    </option>
                                                </select>
                                                @error('niveau_etude')
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="col-12 col-md-12 col-lg-6 col-sm-12 col-xs-12 col-xxl-4">
                                                <label for="diplome_academique" class="form-label">Diplôme académique<span
                                                        class="text-danger mx-1">*</span></label>
                                                <select name="diplome_academique"
                                                    class="form-select  @error('diplome_academique') is-invalid @enderror"
                                                    aria-label="Select" id="select-field-diplome_academique-ind"
                                                    data-placeholder="Choisir diplôme académique">
                                                    <option
                                                        value="{{ $individuelle->diplome_academique ?? old('diplome_academique') }}">
                                                        {{ $individuelle->diplome_academique ?? old('diplome_academique') }}
                                                    </option>
                                                    <option value="Aucun">
                                                        Aucun
                                                    </option>
                                                    <option value="Arabe">
                                                        Arabe
                                                    </option>
                                                    <option value="CFEE">
                                                        CFEE
                                                    </option>
                                                    <option value="BFEM">
                                                        BFEM
                                                    </option>
                                                    <option value="BAC">
                                                        BAC
                                                    </option>
                                                    <option value="Licence">
                                                        Licence
                                                    </option>
                                                    <option value="Master 2">
                                                        Master 2
                                                    </option>
                                                    <option value="Doctorat">
                                                        Doctorat
                                                    </option>
                                                    <option value="Autre">
                                                        Autre
                                                    </option>
                                                </select>
                                                @error('diplome_academique')
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="col-12 col-md-12 col-lg-6 col-sm-12 col-xs-12 col-xxl-4">
                                                <label for="autre_diplome_academique" class="form-label">Si autre ?
                                                    précisez</label>
                                                <input type="text" name="autre_diplome_academique"
                                                    value="{{ $individuelle?->autre_diplome_academique ?? old('autre_diplome_academique') }}"
                                                    class="form-control form-control-sm @error('autre_diplome_academique') is-invalid @enderror"
                                                    id="autre_diplome_academique" placeholder="autre diplôme académique">
                                                @error('autre_diplome_academique')
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="col-12 col-md-12 col-lg-6 col-sm-12 col-xs-12 col-xxl-4">
                                                <label for="option_diplome_academique" class="form-label">Option du
                                                    diplôme</label>
                                                <input type="text" name="option_diplome_academique"
                                                    value="{{ $individuelle?->option_diplome_academique ?? old('option_diplome_academique') }}"
                                                    class="form-control form-control-sm @error('option_diplome_academique') is-invalid @enderror"
                                                    id="option_diplome_academique" placeholder="Ex: Mathématiques">
                                                @error('option_diplome_academique')
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="col-12 col-md-12 col-lg-6 col-sm-12 col-xs-12 col-xxl-4">
                                                <label for="etablissement_academique" class="form-label">Etablissement
                                                    académique</label>
                                                <input type="text" name="etablissement_academique"
                                                    value="{{ $individuelle?->etablissement_academique ?? old('etablissement_academique') }}"
                                                    class="form-control form-control-sm @error('etablissement_academique') is-invalid @enderror"
                                                    id="etablissement_academique" placeholder="Etablissement obtention">
                                                @error('etablissement_academique')
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="col-12 col-md-12 col-lg-6 col-sm-12 col-xs-12 col-xxl-4">
                                                <label for="diplome_pro" class="form-label">Diplôme professionnel<span
                                                        class="text-danger mx-1">*</span></label>
                                                <select name="diplome_professionnel"
                                                    class="form-select  @error('diplome_professionnel') is-invalid @enderror"
                                                    aria-label="Select" id="select-field-diplome_professionnel-ind"
                                                    data-placeholder="Choisir diplôme professionnel">
                                                    <option
                                                        value="{{ $individuelle?->diplome_professionnel ?? old('diplome_professionnel') }}">
                                                        {{ $individuelle?->diplome_professionnel ?? old('diplome_professionnel') }}
                                                    </option>
                                                    <option value="Aucun">
                                                        Aucun
                                                    </option>
                                                    <option value="CAP">
                                                        CAP
                                                    </option>
                                                    <option value="BEP">
                                                        BEP
                                                    </option>
                                                    <option value="BT">
                                                        BT
                                                    </option>
                                                    <option value="BTS">
                                                        BTS
                                                    </option>
                                                    <option value="CPS">
                                                        CPS
                                                    </option>
                                                    <option value="L3 Pro">
                                                        L3 Pro
                                                    </option>
                                                    <option value="DTS">
                                                        DTS
                                                    </option>
                                                    <option value="Autre">
                                                        Autre
                                                    </option>
                                                </select>
                                                @error('diplome_professionnel')
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="col-12 col-md-12 col-lg-6 col-sm-12 col-xs-12 col-xxl-4">
                                                <label for="autre_diplome_professionnel" class="form-label">Si autre ?
                                                    précisez</label>
                                                <input type="text" name="autre_diplome_professionnel"
                                                    value="{{ $individuelle?->autre_diplome_professionnel ?? old('autre_diplome_professionnel') }}"
                                                    class="form-control form-control-sm @error('autre_diplome_professionnel') is-invalid @enderror"
                                                    id="autre_diplome_professionnel"
                                                    placeholder="autre diplôme professionnel ou attestations">
                                                @error('autre_diplome_professionnel')
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="col-12 col-md-12 col-lg-6 col-sm-12 col-xs-12 col-xxl-4">
                                                <label for="etablissement_professionnel" class="form-label">Etablissement
                                                    professionnel</label>
                                                <input type="text" name="etablissement_professionnel"
                                                    value="{{ $individuelle?->etablissement_professionnel ?? old('etablissement_professionnel') }}"
                                                    class="form-control form-control-sm @error('etablissement_professionnel') is-invalid @enderror"
                                                    id="etablissement_professionnel"
                                                    placeholder="Etablissement obtention">
                                                @error('etablissement_professionnel')
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="col-12 col-md-12 col-lg-6 col-sm-12 col-xs-12 col-xxl-4">
                                                <label for="specialite_diplome_professionnel"
                                                    class="form-label">Spécialité</label>
                                                <input type="text" name="specialite_diplome_professionnel"
                                                    value="{{ $individuelle?->specialite_diplome_professionnel ?? old('specialite_diplome_professionnel') }}"
                                                    class="form-control form-control-sm @error('specialite_diplome_professionnel') is-invalid @enderror"
                                                    id="specialite_diplome_professionnel" placeholder="Ex: électricité">
                                                @error('specialite_diplome_professionnel')
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="col-12 col-md-12 col-lg-6 col-sm-12 col-xs-12 col-xxl-4">
                                                <label for="projet_poste_formation" class="form-label">Votre projet après
                                                    la
                                                    formation<span class="text-danger mx-1">*</span></label>
                                                <select name="projet_poste_formation"
                                                    class="form-select  @error('projet_poste_formation') is-invalid @enderror"
                                                    aria-label="Select" id="select-field-projet_poste_formation-ind"
                                                    data-placeholder="Choisir projet">
                                                    <option
                                                        value="{{ $individuelle?->projet_poste_formation ?? old('projet_poste_formation') }}">
                                                        {{ $individuelle?->projet_poste_formation ?? old('projet_poste_formation') }}
                                                    </option>
                                                    <option value="Poursuivre mes études">
                                                        Poursuivre mes études
                                                    </option>
                                                    <option value="Chercher un emploi">
                                                        Chercher un emploi
                                                    </option>
                                                    <option value="Lancer mon entreprise">
                                                        Lancer mon entreprise
                                                    </option>
                                                    <option value="Retourner dans mon entreprise">
                                                        Retourner dans mon entreprise
                                                    </option>
                                                    <option value="Aucun de ces projets">
                                                        Aucun de ces projets
                                                    </option>
                                                </select>
                                                @error('projet_poste_formation')
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="col-12 col-md-12 col-lg-12 col-sm-12 col-xs-12 col-xxl-12 mb-0">
                                                <label for="qualification" class="form-label">Qualification et autres
                                                    diplômes</label>
                                                <textarea name="qualification" id="qualification" rows="1"
                                                    class="form-control form-control-sm @error('qualification') is-invalid @enderror"
                                                    placeholder="Qualification et autres diplômes">{{ $individuelle?->qualification ?? old('qualification') }}</textarea>
                                                @error('qualification')
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="col-12 col-md-12 col-lg-12 col-sm-12 col-xs-12 col-xxl-12 mb-0">
                                                <label for="experience" class="form-label">Expériences et stages</label>
                                                <textarea name="experience" id="experience" rows="1"
                                                    class="form-control form-control-sm @error('experience') is-invalid @enderror"
                                                    placeholder="Expériences ou stages">{{ $individuelle?->experience ?? old('experience') }}</textarea>
                                                @error('experience')
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="col-12 col-md-12 col-lg-12 col-sm-12 col-xs-12 col-xxl-12 mb-0">
                                                <label for="projetprofessionnel" class="form-label">Informations
                                                    complémentaires
                                                    sur
                                                    le projet
                                                    professionnel<span class="text-danger mx-1">*</span></label>
                                                <textarea name="projetprofessionnel" id="projetprofessionnel" rows="2"
                                                    class="form-control form-control-sm @error('projetprofessionnel') is-invalid @enderror"
                                                    placeholder="Si vous disposez déjà d'un projet professionnel, merci d'écrire son résumé en quelques lignes">{{ $individuelle?->projetprofessionnel ?? old('projetprofessionnel') }}</textarea>
                                                @error('projetprofessionnel')
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        {{-- <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary btn-sm"
                                            data-bs-dismiss="modal">Fermer</button>
                                        <button type="submit" class="btn btn-primary btn-sm"><i
                                                class="bi bi-printer"></i>
                                            Ajouter</button>
                                    </div> --}}

                                        <div class="card-footer d-flex justify-content-end gap-2 p-3 bg-light border-top">
                                            <button type="button" class="btn btn-secondary btn-sm"
                                                data-bs-dismiss="modal">
                                                <i class="bi bi-x-circle"></i> Fermer
                                            </button>
                                            <button type="submit" class="btn btn-primary btn-sm">
                                                <i class="bi bi-save"></i> Enregistrer
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endforeach

        <!-- Modal Choisir Localité -->
        <div class="modal fade" id="ChoisirLocaliteModal" tabindex="-1" aria-labelledby="ChoisirLocaliteModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title" id="ChoisirLocaliteModalLabel">Choisir une localité</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <form id="form-choisir-localite">
                            <div class="mb-3">
                                <label for="localite-select" class="form-label">Sélectionnez la localité :</label>
                                <select class="form-select" id="localite-select" name="localite">
                                    <option value="">-- Choisir --</option>
                                    @foreach ($projet->projetlocalites as $localite)
                                        <option value="{{ $localite->id }}">{{ $localite->localite }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </form>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Fermer</button>
                        <button type="button" class="btn btn-primary btn-sm" id="btn-choisir-localite">Choisir</button>
                    </div>

                </div>
            </div>
        </div>

    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('[id^="module_select_"]').forEach(select => {
                select.addEventListener('change', function(e) {
                    let moduleId = e.target.id.split('_').pop();
                    let selectedLocalite = e.target.value;

                    console.log('Module sélectionné ID:', moduleId);
                    console.log('Localité choisie ID:', selectedLocalite);

                    // Ici tu peux faire ce que tu veux (ajax, afficher un champ caché, etc.)
                });
            });
        });
    </script>
@endpush
