@extends('layout.user-layout')
@section('title', 'ONFP | MODIFICATION DEMANDE EN ' . strtoupper($individuelle?->module?->name))
@section('space-work')
    <section class="section min-vh-0 d-flex flex-column align-items-center justify-content-center py-0">
        <div class="container">
            <div class="row justify-content-center">
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
                <div class="col-12 d-flex flex-column align-items-center justify-content-center">
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-12 pt-2">

                                    @hasrole('Demandeur')
                                        <span class="d-flex mt-2 align-items-baseline">
                                            <a href="{{ route('demandesIndividuelle') }}" class="btn btn-success btn-sm"
                                                title="retour">
                                                <i class="bi bi-arrow-counterclockwise"></i>
                                            </a>&nbsp;
                                            <p> | Dossier personnel</p>
                                        </span>
                                    @endhasrole

                                    @hasanyrole('super-admin|admin')
                                        <span class="d-flex mt-2 align-items-baseline">
                                            <a href="{{ route('individuelles.show', $individuelle) }}"
                                                class="btn btn-success btn-sm" title="retour">
                                                <i class="bi bi-arrow-counterclockwise"></i>
                                            </a>&nbsp;
                                            <p> | retour</p>
                                        </span>
                                    @endhasanyrole

                                </div>
                            </div>
                            <form method="post" action="{{ route('individuelles.update', $individuelle) }}"
                                enctype="multipart/form-data" class="row g-3">
                                @csrf
                                @method('PUT')
                                <div class="col-12">
                                    <h5 class="text-primary fw-semibold mb-3 fw-bold mt-3 mb-3 border-bottom pb-2">
                                        Informations personnelles
                                    </h5>
                                </div>
                                <div class="col-12 col-md-6 col-lg-4 mb-0">
                                    <label for="civilite" class="form-label">Civilité<span
                                            class="text-danger mx-1">*</span></label>
                                    <select name="civilite" class="form-select  @error('civilite') is-invalid @enderror"
                                        aria-label="Select" id="select-field-civilite" data-placeholder="Choisir civilité">
                                        <option value="{{ $individuelle?->user?->civilite ?? old('civilite') }}">
                                            {{ $individuelle?->user?->civilite ?? old('civilite') }}
                                        </option>
                                        <option value="M.">
                                            Monsieur
                                        </option>
                                        <option value="Mme">
                                            Madame
                                        </option>
                                    </select>
                                    @error('civilite')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-4 mb-0">
                                    <label class="form-label">
                                        Type de pièce <span class="text-danger mx-1">*</span>
                                    </label>
                                    <select name="type_piece" id="type_piece" class="form-select form-select-sm">
                                        <option value="">-- Choisir --</option>
                                        <option value="cni"
                                            {{ (old('type_piece') ?? $individuelle?->user?->type_piece) === 'cni' ? 'selected' : '' }}>
                                            Carte nationale</option>
                                        @can('voir-extrait')
                                            <option value="extrait"
                                                {{ (old('type_piece') ?? $individuelle?->user?->type_piece) === 'extrait' ? 'selected' : '' }}>
                                                Extrait de naissance</option>
                                        @endcan
                                        <option value="passeport"
                                            {{ (old('type_piece') ?? $individuelle?->user?->type_piece) === 'passeport' ? 'selected' : '' }}>
                                            Passeport</option>
                                    </select>
                                </div>

                                {{-- <div class="col-12 col-md-6 col-lg-4 mb-0">
                                    <label for="cin" class="form-label">CIN</label>
                                    <input name="cin" type="text"
                                        class="form-control form-control-sm @error('cin') is-invalid @enderror"
                                        id="cin" value="{{ $individuelle?->user?->cin ?? old('cin') }}"
                                        autocomplete="off" placeholder="Ex: 1099200500012" minlength="9" maxlength="14">
                                    @error('cin')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div> --}}


                                <div class="col-12 col-md-4 mb-0">
                                    <label for="num_piece" class="form-label" id="numero_piece_label">
                                        Numéro de la pièce <span class="text-danger mx-1">*</span>
                                    </label>
                                    <input name="cin" type="text"
                                        class="form-control form-control-sm @error('cin') is-invalid @enderror"
                                        id="num_piece"
                                        value="{{ old('cin') ?? str_replace(' ', '', $individuelle?->user?->cin) }}"
                                        autocomplete="off" placeholder="Ex : 1099200500012" minlength="13" maxlength="14">
                                    @error('cin')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6 col-lg-4 mb-0">
                                    <label for="firstname" class="form-label">Prénom<span
                                            class="text-danger mx-1">*</span></label>
                                    <input type="text" name="firstname"
                                        value="{{ $individuelle?->user?->firstname ?? old('firstname') }}"
                                        class="form-control form-control-sm @error('firstname') is-invalid @enderror"
                                        id="firstname" placeholder="prénom">
                                    @error('firstname')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6 col-lg-4 mb-0">
                                    <label for="name" class="form-label">Nom<span
                                            class="text-danger mx-1">*</span></label>
                                    <input type="text" name="name"
                                        value="{{ $individuelle?->user?->name ?? old('name') }}"
                                        class="form-control form-control-sm @error('name') is-invalid @enderror"
                                        id="name" placeholder="nom">
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6 col-lg-4 mb-0">
                                    <label for="date naissance" class="form-label">Date naissance</label>
                                    <input type="text" name="date_naissance"
                                        value="{{ old('date_naissance', optional($individuelle?->user?->date_naissance)->format('d/m/Y')) }}"
                                        class="form-control form-control-sm @error('date_naissance') is-invalid @enderror"
                                        id="datepicker" placeholder="JJ/MM/AAAA" autocomplete="bday">
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6 col-lg-4 mb-0">
                                    <label for="lieu_naissance" class="form-label">Lieu naissance</label>
                                    <input type="text" name="lieu_naissance"
                                        value="{{ $individuelle?->user?->lieu_naissance ?? old('lieu_naissance') }}"
                                        class="form-control form-control-sm @error('lieu_naissance') is-invalid @enderror"
                                        id="lieu_naissance" placeholder="Lieu de naissance">
                                    @error('lieu_naissance')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6 col-lg-4 mb-0">
                                    <label for="email" class="form-label">email<span
                                            class="text-danger mx-1">*</span></label>
                                    <div class="input-group has-validation">
                                        {{-- <span class="input-group-text" id="email">@</span> --}}
                                        <input type="email" name="email"
                                            value="{{ old('email', $individuelle?->user?->email ?? '') }}"
                                            class="form-control form-control-sm @error('email') is-invalid @enderror"
                                            id="email" placeholder="email" readonly>
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12 col-md-6 col-lg-4 mb-0">
                                    <label for="telephone" class="form-label">Téléphone<span
                                            class="text-danger mx-1">*</span></label>
                                    <input name="telephone" type="text" maxlength="12"
                                        class="form-control form-control-sm @error('telephone') is-invalid @enderror"
                                        id="telephone"
                                        value="{{ old('telephone', $individuelle?->user?->telephone ?? '') }}"
                                        autocomplete="tel" placeholder="XX:XXX:XX:XX">
                                    @error('telephone')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6 col-lg-4 mb-0">
                                    <label for="situation_familiale" class="form-label">Situation familiale</label>
                                    <select name="situation_familiale"
                                        class="form-select  @error('situation_familiale') is-invalid @enderror"
                                        aria-label="Select" id="select-field-familiale"
                                        data-placeholder="Choisir situation familiale">
                                        <option
                                            value="{{ $individuelle?->user?->situation_familiale ?? old('situation_familiale') }}">
                                            {{ $individuelle?->user?->situation_familiale ?? old('situation_familiale') }}
                                        </option>
                                        <option value="Marié(e)">
                                            Marié(e)
                                        </option>
                                        <option value="Célibataire">
                                            Célibataire
                                        </option>
                                        <option value="Veuf(ve)">
                                            Veuf(ve)
                                        </option>
                                        <option value="Divorcé(e)">
                                            Divorcé(e)
                                        </option>
                                    </select>
                                    @error('situation_familiale')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6 col-lg-4 mb-0">
                                    <label for="situation_professionnelle" class="form-label">Situation
                                        professionnelle</label>
                                    <select name="situation_professionnelle"
                                        class="form-select  @error('situation_professionnelle') is-invalid @enderror"
                                        aria-label="Select" id="select-field-professionnelle"
                                        data-placeholder="Choisir situation professionnelle">
                                        <option
                                            value="{{ $individuelle?->user?->situation_professionnelle ?? old('situation_professionnelle') }}">
                                            {{ $individuelle?->user?->situation_professionnelle ?? old('situation_professionnelle') }}
                                        </option>
                                        <option value="Employé(e)">
                                            Employé(e)
                                        </option>
                                        <option value="Informel">
                                            Informel
                                        </option>
                                        <option value="Elève ou étudiant">
                                            Elève ou étudiant
                                        </option>
                                        <option value="chercheur d'emploi">
                                            chercheur d'emploi
                                        </option>
                                        <option value="Stage ou période essai">
                                            Stage ou période essai
                                        </option>
                                        <option value="Entrepreneur ou freelance">
                                            Entrepreneur ou freelance
                                        </option>
                                    </select>
                                    @error('situation_professionnelle')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6 col-lg-4 mb-0">
                                    <label for="adresse" class="form-label">Adresse<span
                                            class="text-danger mx-1">*</span></label>
                                    <input type="text" name="adresse"
                                        value="{{ $individuelle?->user?->adresse ?? old('adresse') }}"
                                        class="form-control form-control-sm @error('adresse') is-invalid @enderror"
                                        id="adresse" placeholder="adresse">
                                    @error('adresse')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>
                                <div class="text-primary fw-semibold mb-1 col-12 mt-4">
                                    <h5 class="fw-bold border-top pt-2">
                                        Informations formation
                                    </h5>
                                </div>
                                <div class="col-12">
                                    <label for="module" class="form-label">Formation sollicitée (module)<span
                                            class="text-danger mx-1">*</span></label>
                                    <input type="text" name="module"
                                        value="{{ $individuelle?->module?->name ?? old('module_name') }}"
                                        class="form-control form-control-sm @error('module_name') is-invalid @enderror"
                                        id="module_name" placeholder="Formation choisie">
                                    <div id="countryList"></div>
                                    {{ csrf_field() }}
                                    @error('module')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-12 col-md-12 col-lg-6 col-sm-12 col-xs-12 col-xxl-6">
                                    <label for="localite" class="form-label">Département<span
                                            class="text-danger mx-1">*</span></label>
                                    <select name="localite" class="form-select  @error('localite') is-invalid @enderror"
                                        aria-label="Select" id="select-field-departement" data-placeholder="Choisir">
                                        <option value="{{ $individuelle?->departement?->nom ?? old('localite') }}">
                                            {{ $individuelle?->departement?->nom ?? old('localite') }}</option>
                                        @foreach ($departements as $departement)
                                            <option value="{{ $departement->nom }}">
                                                {{ $departement->nom }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('localite')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-12 col-lg-6 col-sm-12 col-xs-12 col-xxl-6">
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
                                {{-- <div class="col-12 col-md-12 col-lg-6 col-sm-12 col-xs-12 col-xxl-6">
                                    <label for="numero" class="form-label">numero<span
                                            class="text-danger mx-1">*</span></label>
                                    <input type="text" name="numero"
                                        value="{{ $individuelle?->numero ?? old('numero') }}"
                                        class="form-control form-control-sm @error('numero') is-invalid @enderror"
                                        id="numero" placeholder="numero">
                                    @error('numero')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div> --}}
                                <div class="col-12 col-md-12 col-lg-6 col-sm-12 col-xs-12 col-xxl-4">
                                    <label for="telephone_secondaire" class="form-label">Téléphone secondaire<span
                                            class="text-danger mx-1">*</span></label>
                                    <input name="telephone_secondaire" type="text" size="9"
                                        class="form-control form-control-sm @error('telephone_secondaire') is-invalid @enderror"
                                        id="telephone_secondaire"
                                        value="{{ old('telephone_secondaire', str_replace(' ', '', $individuelle?->telephone) ?? '') }}"
                                        autocomplete="tel" placeholder="XX:XXX:XX:XX">
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
                                        aria-label="Select" id="select-field-niveau_etude"
                                        data-placeholder="Choisir niveau étude">
                                        <option value="{{ $individuelle->niveau_etude }}">
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
                                        aria-label="Select" id="select-field-diplome_academique"
                                        data-placeholder="Choisir diplôme académique">
                                        <option value="{{ $individuelle->diplome_academique }}">
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
                                    <label for="etablissement_academique" class="form-label">Etablissement
                                        académique</label>
                                    <input type="text" name="etablissement_academique"
                                        value="{{ $individuelle->etablissement_academique ?? old('etablissement_academique') }}"
                                        class="form-control form-control-sm @error('etablissement_academique') is-invalid @enderror"
                                        id="etablissement_academique" placeholder="Etablissement obtention">
                                    @error('etablissement_academique')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-12 col-lg-6 col-sm-12 col-xs-12 col-xxl-4">
                                    <label for="autre_diplome_academique" class="form-label">Si autre ? précisez</label>
                                    <input type="text" name="autre_diplome_academique"
                                        value="{{ $individuelle->autre_diplome_academique ?? old('autre_diplome_academique') }}"
                                        class="form-control form-control-sm @error('autre_diplome_academique') is-invalid @enderror"
                                        id="autre_diplome_academique" placeholder="autre diplôme académique">
                                    @error('autre_diplome_academique')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-12 col-lg-6 col-sm-12 col-xs-12 col-xxl-4">
                                    <label for="option_diplome_academique" class="form-label">Option du diplôme</label>
                                    <input type="text" name="option_diplome_academique"
                                        value="{{ $individuelle->option_diplome_academique ?? old('option_diplome_academique') }}"
                                        class="form-control form-control-sm @error('option_diplome_academique') is-invalid @enderror"
                                        id="option_diplome_academique" placeholder="Ex: Mathématiques">
                                    @error('option_diplome_academique')
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
                                        aria-label="Select" id="select-field-diplome_professionnel"
                                        data-placeholder="Choisir diplôme professionnel">
                                        <option value="{{ $individuelle->diplome_professionnel }}">
                                            {{ $individuelle->diplome_professionnel ?? old('diplome_professionnel') }}
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
                                        value="{{ $individuelle->autre_diplome_professionnel ?? old('autre_diplome_professionnel') }}"
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
                                        value="{{ $individuelle->etablissement_professionnel ?? old('etablissement_professionnel') }}"
                                        class="form-control form-control-sm @error('etablissement_professionnel') is-invalid @enderror"
                                        id="etablissement_professionnel" placeholder="Etablissement obtention">
                                    @error('etablissement_professionnel')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-12 col-lg-6 col-sm-12 col-xs-12 col-xxl-4">
                                    <label for="specialite_diplome_professionnel" class="form-label">Spécialité</label>
                                    <input type="text" name="specialite_diplome_professionnel"
                                        value="{{ $individuelle->specialite_diplome_professionnel ?? old('specialite_diplome_professionnel') }}"
                                        class="form-control form-control-sm @error('specialite_diplome_professionnel') is-invalid @enderror"
                                        id="specialite_diplome_professionnel" placeholder="Ex: électricité">
                                    @error('specialite_diplome_professionnel')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-12 col-lg-6 col-sm-12 col-xs-12 col-xxl-4">
                                    <label for="projet_poste_formation" class="form-label">Votre projet après la
                                        formation<span class="text-danger mx-1">*</span></label>
                                    <select name="projet_poste_formation"
                                        class="form-select  @error('projet_poste_formation') is-invalid @enderror"
                                        aria-label="Select" id="select-field-projet_poste_formation"
                                        data-placeholder="Choisir projet poste formation">
                                        <option value="{{ $individuelle->projet_poste_formation }}">
                                            {{ $individuelle->projet_poste_formation ?? old('projet_poste_formation') }}
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

                                @if (auth()->user()->hasRole('super-admin|admin'))
                                    <div class="col-12 col-md-12 col-lg-6 col-sm-12 col-xs-12 col-xxl-4">
                                        <label for="date_depot" class="form-label">Date dépot<span
                                                class="text-danger mx-1">*</span></label>
                                        <input type="text" name="date_depot"
                                            value="{{ old('date_depot', optional($individuelle->date_depot)->format('d/m/Y')) }}"
                                            class="form-control form-control-sm @error('date_depot') is-invalid @enderror"
                                            id="datepicker" placeholder="JJ/MM/AAAA" autocomplete="bday">
                                        @error('date_depot')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>
                                @else
                                    <div class="col-12 col-md-12 col-lg-6 col-sm-12 col-xs-12 col-xxl-4">
                                        <label for="date_depot" class="form-label">Date dépot<span
                                                class="text-danger mx-1">*</span></label>
                                        <input type="text" name="date_depot"
                                            value="{{ old('date_depot', optional($individuelle->date_depot)->format('d/m/Y')) }}"
                                            readonly
                                            class="form-control form-control-sm @error('date_depot') is-invalid @enderror"
                                            id="datepickers" placeholder="JJ/MM/AAAA" autocomplete="bday">
                                        @error('date_depot')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>
                                @endif

                                @can('projet-view')
                                    <div class="col-12">
                                        <label for="projet" class="form-label">Partenaire</label>
                                        <select name="projet" class="form-select  @error('projet') is-invalid @enderror"
                                            aria-label="Select" id="select-field-projet" data-placeholder="Choisir">
                                            <option>
                                                {{ $individuelle?->projet?->sigle ?? old('projet') }}
                                            </option>
                                            <option value="null">
                                                Aucun
                                            </option>
                                            @foreach ($projets as $projet)
                                                <option value="{{ $projet?->sigle }}">
                                                    {{ $projet?->name . ' (' . $projet?->sigle . ')' }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('projet')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>
                                @endcan

                                <div class="col-12">
                                    <label for="qualification" class="form-label">Qualification et autres diplômes</label>
                                    <textarea name="qualification" id="qualification" rows="2"
                                        class="form-control form-control-sm @error('qualification') is-invalid @enderror"
                                        placeholder="Qualification et autres diplômes">{{ $individuelle->qualification ?? old('qualification') }}</textarea>
                                    @error('qualification')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="experience" class="form-label">Expériences et stages</label>
                                    <textarea name="experience" id="experience" rows="2"
                                        class="form-control form-control-sm @error('experience') is-invalid @enderror"
                                        placeholder="Expériences ou stages">{{ $individuelle->experience ?? old('experience') }}</textarea>
                                    @error('experience')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="projetprofessionnel" class="form-label">Informations complémentaires sur
                                        le projet
                                        professionnel<span class="text-danger mx-1">*</span></label>
                                    <textarea name="projetprofessionnel" id="projetprofessionnel" rows="5"
                                        class="form-control form-control-sm @error('projetprofessionnel') is-invalid @enderror"
                                        placeholder="Si vous disposez déjà d'un projet professionnel, merci d'écrire son résumé en quelques lignes">{{ $individuelle->projetprofessionnel ?? old('projetprofessionnel') }}</textarea>
                                    @error('projetprofessionnel')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="text-center gap-2 p-3 bg-light border-top">
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="bi bi-check-circle"></i> Enregistrer les modifications
                                    </button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const typePiece = document.getElementById('type_piece');
            const numeroInput = document.getElementById('num_piece');
            const numeroLabel = document.getElementById('numero_piece_label');

            // 🔹 Fonction qui met à jour le label, placeholder et contraintes
            function updateNumeroPiece(type, value = '') {
                // On garde la valeur actuelle si fournie
                if (value) {
                    numeroInput.value = value;
                }

                switch (type) {
                    case 'cni':
                        numeroLabel.innerHTML = 'Numéro de la carte nationale <span class="required">*</span>';
                        numeroInput.placeholder = 'Ex : 1099200500012';
                        numeroInput.setAttribute('minlength', 13);
                        numeroInput.setAttribute('maxlength', 14);
                        numeroInput.setAttribute('pattern', '[A-Za-z0-9]{13,14}');
                        break;

                    case 'extrait':
                        numeroLabel.innerHTML = 'Numéro de l’extrait de naissance <span class="required">*</span>';
                        numeroInput.placeholder = 'Ex : 00345/2010';
                        numeroInput.setAttribute('minlength', 10);
                        numeroInput.setAttribute('maxlength', 10);
                        numeroInput.setAttribute('pattern', '[A-Za-z0-9/]{10}');
                        break;

                    case 'passeport':
                        numeroLabel.innerHTML = 'Numéro du passeport <span class="required">*</span>';
                        numeroInput.placeholder = 'Ex : A12345678';
                        numeroInput.setAttribute('minlength', 9);
                        numeroInput.setAttribute('maxlength', 9);
                        numeroInput.removeAttribute('pattern');
                        break;

                    default:
                        numeroLabel.innerHTML = 'Numéro de la pièce <span class="required">*</span>';
                        numeroInput.placeholder = '';
                        numeroInput.removeAttribute('minlength');
                        numeroInput.removeAttribute('maxlength');
                        numeroInput.removeAttribute('pattern');
                        break;
                }
            }

            // 🔹 Fonction pour détecter le type de pièce depuis la valeur
            function detectTypeFromValue(value) {
                value = value.replace(/\s+/g, '');
                const length = value.length;

                if (value.includes('/') && length === 10) return 'extrait';
                if (length === 9) return 'passeport';
                if (length === 13 || length === 14) return 'cni';

                return null;
            }

            // 🔹 Initialisation au chargement
            const initialValue = numeroInput.value;
            const detectedType = detectTypeFromValue(initialValue);

            if (detectedType) {
                typePiece.value = detectedType;
                updateNumeroPiece(detectedType, initialValue);
            } else {
                updateNumeroPiece(typePiece.value, initialValue);
            }

            // 🔹 Changement dynamique du select
            typePiece.addEventListener('change', function() {
                updateNumeroPiece(this.value);
            });

            // 🔹 Détection automatique pendant la saisie du CIN
            numeroInput.addEventListener('input', function() {
                const detected = detectTypeFromValue(this.value);
                if (detected && typePiece.value !== detected) {
                    typePiece.value = detected;
                    updateNumeroPiece(detected);
                }

                // Limiter la saisie côté front selon maxlength
                const max = this.getAttribute('maxlength');
                if (max && this.value.length > max) {
                    this.value = this.value.slice(0, max);
                }
            });
        });
    </script>
@endpush
