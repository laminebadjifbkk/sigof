@extends('layout.user-layout')
@section('title', 'ONFP | Profil')
@section('space-work')
    <div class="pagetitle">
        <h1>Profil</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Accueil</a></li>
                <li class="breadcrumb-item">Operateur</li>
                <li class="breadcrumb-item active">Profil</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section profile">
        <div class="row justify-content-center">
            {{-- Début Photo de profil --}}
            <div class="col-12 col-md-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                            {{-- <img src="assets/img/profile-img.jpg" alt="Profile" class="rounded-circle"> --}}
                            <a href="#" data-bs-toggle="modal" data-bs-target="#ShowProfilImage{{ $user?->id }}"
                                class="profile-image-wrapper
               {{ $user?->last_activity && \Carbon\Carbon::parse($user?->last_activity)->diffInMinutes(now()) < 5
                   ? 'online'
                   : 'offline' }}">

                                <img src="{{ asset($user?->getImage()) }}" alt="Profil" class="profile-image">
                            </a>
                            <h2 class="pt-1 d-flex flex-column align-items-center text-center">
                                {{ $user?->username }}
                                <br>
                                @if ($user?->last_activity && \Carbon\Carbon::parse($user->last_activity)->diffInMinutes(now()) < 5)
                                    <span class="text-success">En ligne</span>
                                @else
                                    <span class="text-danger">Hors ligne</span>
                                    ({{ \Carbon\Carbon::parse($user->last_activity)->diffForHumans() }})
                                @endif
                            </h2>

                            <div class="social-links mt-2">
                                @foreach (['twitter' => 'twitter', 'facebook' => 'facebook', 'instagram' => 'instagram', 'linkedin' => 'linkedin'] as $platform => $icon)
                                    @if (!empty($user?->$platform))
                                        <a href="{{ $user->$platform }}" class="{{ $platform }}" target="_blank">
                                            <i class="bi bi-{{ $icon }}"></i>
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <section class="section dashboard">
                    <div class="row">
                        <div class="col-12">
                            <div class="card info-card sales-card">
                                <a href="{{ route('devenirOperateur') }}">
                                    <div class="card-body">
                                        <h5 class="card-title">Agréments <span>| opérateur</span></h5>
                                        <div class="d-flex align-items-center">
                                            <div
                                                class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                                <i class="bi bi-person-plus-fill"></i>
                                            </div>
                                            <div class="ps-3">
                                                <h6>{{ $user?->operateurs()->count() ?? 0 }}</h6>
                                                @can('agrement-ouvert')
                                                    <span class="text-success small fw-bold">
                                                        <span class="text-uppercase">ouverts</span></span>
                                                @elsecan('agrement-fermer')
                                                    <span class="text-danger small fw-bold">
                                                        <span class="text-uppercase">fermés</span></span>
                                                @endcan
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </section> --}}
                <section class="section dashboard position-relative">
                    <div class="row">
                        <div class="col-12">
                            <div class="card info-card sales-card hover-shadow cursor-pointer position-relative">

                                <!-- Message défilant si aucune demande -->
                                @if (($user?->operateurs()->count() ?? 0) == 0)
                                    <div class="scrolling-message-wrapper position-relative w-100 overflow-hidden p-2 mb-2"
                                        style="background-color: #fff3f3; border: 1px solid #ff4d4f; border-radius: 5px;">
                                        <span class="scrolling-message text-danger fw-bold">
                                            ⚠️ Vous n'avez pas encore fait de demande d'agrément ! Cliquez sur "Agréments" pour
                                            postuler.
                                        </span>
                                    </div>
                                @endif

                                <a href="{{ route('devenirOperateur') }}" class="text-decoration-none">
                                    <div class="card-body">
                                        <h5 class="card-title">
                                            Agréments <span>| opérateur</span>
                                        </h5>
                                        <div class="d-flex align-items-center">
                                            <div
                                                class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-primary text-white">
                                                <i class="bi bi-person-plus-fill fs-3"></i>
                                            </div>
                                            <div class="ps-3">
                                                <h6 class="mb-1">{{ $user?->operateurs()->count() ?? 0 }}</h6>
                                                @can('agrement-ouvert')
                                                    <span class="text-success small fw-bold text-uppercase">ouverts</span>
                                                @elsecan('agrement-fermer')
                                                    <span class="text-danger small fw-bold text-uppercase">fermés</span>
                                                @endcan
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </section>

            </div>
            {{-- Fin Photo de profil --}}

            {{-- Début aperçu --}}
            <div class="col-12 col-md-8 col-lg-8">
                <div class="flex items-center gap-4">
                    <div class="card">
                        @if ($message = Session::get('status'))
                            <div class="alert alert-success bg-success text-light border-0 alert-dismissible fade show"
                                role="alert">
                                <strong>{{ $message }}</strong>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        @if ($message = Session::get('message'))
                            <div class="alert alert-success bg-success text-light border-0 alert-dismissible fade show"
                                role="alert">
                                <strong>{{ $message }}</strong>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        @if ($errors->updatePassword->get('current_password'))
                            <div class="alert alert-danger bg-danger text-light border-0 alert-dismissible fade show"
                                role="alert">
                                <strong><x-input-error :messages="$errors->updatePassword->get('current_password')" /></strong>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        @if ($errors->any())
                            @foreach ($errors->all() as $error)
                                <div class="alert alert-danger bg-danger text-light border-0 alert-dismissible fade show"
                                    role="alert"><strong>{{ $error }}</strong></div>
                            @endforeach
                        @endif
                        <div class="card-body pt-3">
                            <!-- Bordered Tabs -->
                            <ul class="nav nav-tabs nav-tabs-bordered align-items-center gap-2">

                                <li class="nav-item">
                                    <button class="nav-link active d-flex align-items-center gap-1" data-bs-toggle="tab"
                                        data-bs-target="#profile-overview">
                                        <i class="bi bi-person"></i>
                                        <span>Profil</span>
                                    </button>
                                </li>

                                <li class="nav-item">
                                    <button class="nav-link d-flex align-items-center gap-1" data-bs-toggle="tab"
                                        data-bs-target="#profile-edit">
                                        <i class="bi bi-pencil-square"></i>
                                        <span>Modifier</span>
                                    </button>
                                </li>

                                <li class="nav-item">
                                    <button class="nav-link d-flex align-items-center gap-1" data-bs-toggle="tab"
                                        data-bs-target="#profile-change-password">
                                        <i class="bi bi-shield-lock"></i>
                                        <span>Mot de passe</span>
                                    </button>
                                </li>

                                <li class="nav-item">
                                    <button class="nav-link d-flex align-items-center gap-1" data-bs-toggle="tab"
                                        data-bs-target="#files">
                                        <i class="bi bi-folder"></i>
                                        <span>Fichiers</span>
                                    </button>
                                </li>

                                @if ($user?->operateurs())
                                    <li class="nav-item">
                                        <button class="nav-link d-flex align-items-center gap-1" data-bs-toggle="tab"
                                            data-bs-target="#agrements">
                                            <i class="bi bi-award"></i>
                                            <span>Agréments</span>
                                        </button>
                                    </li>
                                @endif

                            </ul>
                            <div class="tab-content pt-2">
                                <div class="tab-pane fade show active profile-overview" id="profile-overview">
                                    <h5 class="card-title">À propos</h5>
                                    <p class="small fst-italic">
                                        créé, {{ $user?->created_at->diffForHumans() }}
                                    </p>

                                    <div class="row">
                                        <div class="col-12 col-md-4 label">
                                            Informations personnelles
                                        </div>
                                        <div class="col-12 col-md-8">
                                            @if ($user->is_complete)
                                                <span class="badge bg-success text-white">Complètes</span>
                                            @else
                                                <span class="badge bg-warning text-white">
                                                    Incomplètes, cliquez sur modifier profil pour compléter
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    @if (!empty($user?->operateur))
                                        <div class="row">
                                            <div class="col-12 col-md-4 label">
                                                Opérateur</div>
                                            <div class="col-12 col-md-8">
                                                {{ $user?->display_operateur }}</div>
                                        </div>
                                    @endif

                                    @if (!empty($user?->email))
                                        <div class="row">
                                            <div class="col-12 col-md-4 label">Email
                                            </div>
                                            <div class="col-12 col-md-8"><a
                                                    href="mailto:{{ $user?->email }}">{{ $user?->email }}</a>
                                            </div>
                                        </div>
                                    @endif

                                    @if (!empty($user?->telephone))
                                        <div class="row">
                                            <div class="col-12 col-md-4 label">Téléphone</div>
                                            <div class="col-12 col-md-8">
                                                @if ($user?->fixe)
                                                    <a href="tel:+221{{ $user->fixe }}">{{ $user->fixe }}</a>
                                                @endif

                                                @if ($user?->fixe && $user?->telephone)
                                                    &nbsp;|&nbsp;
                                                @endif

                                                @if ($user?->telephone)
                                                    <a href="tel:+221{{ $user->telephone }}">{{ $user->telephone }}</a>
                                                @endif

                                                @if (!$user?->fixe && !$user?->telephone)
                                                    <span>Aucun numéro disponible</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                    @if (!empty($user?->adresse))
                                        <div class="row">
                                            <div class="col-12 col-md-4 label">
                                                Adresse</div>
                                            <div class="col-12 col-md-8">
                                                {{ $user?->adresse }}</div>
                                        </div>
                                    @endif

                                    <hr>

                                    <h5 class="card-title">Responsable</h5>

                                    {{-- @if (!empty($user?->civilite))
                                        <div class="row">
                                            <div class="col-12 col-md-4 label">
                                                Civilité</div>
                                            <div class="col-12 col-md-8">
                                                {{ $user?->civilite }}</div>
                                        </div>
                                    @endif --}}

                                    @if (!empty($user?->firstname))
                                        <div class="row">
                                            <div class="col-12 col-md-4 label">
                                                Nom</div>
                                            <div class="col-12 col-md-8">
                                                {{ $user?->civilite . ' ' . $user?->firstname . ' ' . $user?->name }}</div>
                                        </div>
                                    @endif

                                    {{-- @if (!empty($user?->name))
                                        <div class="row">
                                            <div class="col-12 col-md-4 label">Nom
                                            </div>
                                            <div class="col-12 col-md-8">
                                                {{ $user?->name }}</div>
                                        </div>
                                    @endif --}}

                                    @if (!empty($user?->telephone))
                                        <div class="row">
                                            <div class="col-12 col-md-4 label">
                                                Téléphone</div>
                                            <div class="col-12 col-md-8"><a
                                                    href="tel:+221{{ $user?->telephone }}">{{ $user?->telephone }}</a>
                                            </div>
                                        </div>
                                    @endif

                                    @if (!empty($user?->email_responsable))
                                        <div class="row">
                                            <div class="col-12 col-md-4 label">Email
                                            </div>
                                            <div class="col-12 col-md-8"><a
                                                    href="mailto:{{ $user?->email_responsable }}">{{ $user?->email_responsable }}</a>
                                            </div>
                                        </div>
                                    @endif

                                    @if (!empty($user?->fonction_responsable))
                                        <div class="row">
                                            <div class="col-12 col-md-4 label">
                                                Fonction</div>
                                            <div class="col-12 col-md-8">
                                                {{ $user?->fonction_responsable }}
                                            </div>
                                        </div>
                                    @endif

                                </div>
                            </div>
                            {{-- Fin aperçu --}}
                            <div class="tab-content pt-2">
                                {{-- Début Edition --}}
                                <div class="tab-pane fade profile-edit pt-3" id="profile-edit">
                                    <form method="post" action="{{ route('profile.updated', $user->uuid) }}"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method('patch')
                                        <h5 class="card-title">Modification du profil</h5>

                                        <div class="row mb-3">
                                            <label for="profileImage" class="col-12 col-md-4 col-form-label">
                                                LOGO
                                            </label>
                                            {{-- <div class="col-md-8 col-lg-9"> --}}
                                            <div class="col-12 col-md-8">
                                                <img class="rounded-circle w-25" alt="Profil"
                                                    src="{{ asset($user->getImage()) }}" width="50" height="auto">

                                                <div class="pt-2 d-flex align-items-center gap-2">
                                                    <div class="form-group mb-0">
                                                        <input type="hidden" name="idUser"
                                                            value="{{ $user->id }}">
                                                        <label for="image" class="btn btn-primary btn-sm text-white"
                                                            title="LOGO">
                                                            <i class="bi bi-upload"></i>
                                                            <input type="file" name="image" id="image"
                                                                accept=".jpg, .jpeg, .png, .svg, .gif"
                                                                class="form-control d-none @error('image') is-invalid @enderror">
                                                        </label>
                                                        @error('image')
                                                            <span class="text-danger d-block">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                    @auth
                                                        @if (optional($user)?->image)
                                                            <div class="form-group mb-0">
                                                                <label for="delete-image"
                                                                    class="btn btn-danger btn-sm text-white show_confirmDeleteImage"
                                                                    data-url="{{ route('profile.image.destroy') }}"
                                                                    title="Supprimer l'image de profil">
                                                                    <i class="bi bi-trash"></i>
                                                                    <input type="button" id="delete-image" class="d-none">
                                                                </label>
                                                            </div>
                                                        @endif
                                                    @endauth
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Operateur --}}
                                        <div class="row mb-3">
                                            <label for="operateur" class="col-md-4 col-lg-3 col-form-label">Opérateur<span
                                                    class="text-danger mx-1">*</span>
                                            </label>
                                            <div class="col-md-8 col-lg-9">
                                                <div class="pt-2">
                                                    <input name="operateur" type="text"
                                                        class="form-control form-control-sm @error('operateur') is-invalid @enderror"
                                                        id="operateur"
                                                        value="{{ $user?->operateur ?? old('operateur') }}"
                                                        autocomplete="operateur" placeholder="Operateur">
                                                </div>
                                                @error('operateur')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>


                                        {{-- Sigle --}}
                                        <div class="row mb-3">
                                            <label for="username" class="col-md-4 col-lg-3 col-form-label">Sigle
                                            </label>
                                            <div class="col-md-8 col-lg-9">
                                                <div class="pt-2">
                                                    <input name="username" type="text"
                                                        class="form-control form-control-sm @error('username') is-invalid @enderror"
                                                        id="username" value="{{ $user->username ?? old('username') }}"
                                                        autocomplete="username" placeholder="Sigle">
                                                </div>
                                                @error('username')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        {{-- categorie --}}
                                        <div class="row mb-3">
                                            <label for="categorie" class="col-md-4 col-lg-3 col-form-label">Catégorie<span
                                                    class="text-danger mx-1">*</span>
                                            </label>
                                            <div class="col-md-8 col-lg-9">
                                                <div class="pt-2">
                                                    <select name="categorie"
                                                        class="form-select @error('categorie') is-invalid @enderror"
                                                        id="categorie" {{ !empty($user?->categorie) ? 'disabled' : '' }}>

                                                        <option value="{{ $user?->categorie ?? old('categorie') }}">
                                                            {{ $user?->categorie ?? old('categorie') }}
                                                        </option>

                                                        <option value="Public">Public</option>
                                                        <option value="Privé">Privé</option>
                                                    </select>

                                                    @if (!empty($user?->categorie))
                                                        <small class="text-muted">La catégorie n’est pas
                                                            modifiable.</small>
                                                    @endif

                                                    @if (!empty($user?->categorie))
                                                        <input type="hidden" name="categorie"
                                                            value="{{ $user->categorie }}">
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        {{-- N° RCCM / Ninea --}}
                                        <div class="row mb-3">
                                            <label for="ninea" class="col-md-4 col-lg-3 col-form-label">N° Ninea<span
                                                    class="text-danger mx-1">*</span></label>
                                            <div class="col-md-8 col-lg-9">
                                                <input type="text" name="ninea"
                                                    value="{{ $user?->ninea ?? old('ninea') }}"
                                                    class="form-control form-control-sm @error('ninea') is-invalid @enderror"
                                                    id="ninea" placeholder="Votre ninéa / Numéro RCCM">
                                                @error('ninea')
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        {{-- Statut juridique --}}
                                        <div class="row mb-3">
                                            <label for="statut" class="col-md-4 col-lg-3 col-form-label">Statut
                                                juridique<span class="text-danger mx-1">*</span>
                                            </label>
                                            <div class="col-md-8 col-lg-9">
                                                <div class="pt-2">
                                                    <select name="statut"
                                                        class="form-select  @error('statut') is-invalid @enderror"
                                                        aria-label="Select" id="statut-operateur"
                                                        data-placeholder="Choisir">
                                                        <option value="{{ $user?->statut ?? old('statut') }}">
                                                            {{ $user?->statut ?? old('statut') }}
                                                        </option>
                                                        <option value="GIE">
                                                            GIE
                                                        </option>
                                                        <option value="Association">
                                                            Association
                                                        </option>
                                                        <option value="Entreprise individuelle">
                                                            Entreprise individuelle
                                                        </option>
                                                        <option value="SA">
                                                            SA
                                                        </option>
                                                        <option value="SAS">
                                                            SAS
                                                        </option>
                                                        <option value="SUARL">
                                                            SUARL
                                                        </option>
                                                        <option value="SARL">
                                                            SARL
                                                        </option>
                                                        <option value="SNC">
                                                            SNC
                                                        </option>
                                                        <option value="SCS">
                                                            SCS
                                                        </option>
                                                        <option value="Etablissement public">
                                                            Etablissement public
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
                                            </div>
                                        </div>

                                        {{-- Email --}}
                                        <div class="row mb-3">
                                            <label for="Email" class="col-md-4 col-lg-3 col-form-label">Email<span
                                                    class="text-danger mx-1">*</span></label>
                                            <div class="col-md-8 col-lg-9">
                                                <input name="email" type="email"
                                                    class="form-control form-control-sm @error('email') is-invalid @enderror"
                                                    id="Email" value="{{ $user->email ?? old('email') }}"
                                                    autocomplete="email" placeholder="Adresse e-mail"
                                                    {{ !empty($user?->email) ? 'readonly' : '' }}>
                                                @if (!empty($user?->email))
                                                    <small class="text-muted">L’adresse e-mail n’est pas
                                                        modifiable.</small>
                                                @endif
                                                @error('email')
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        {{-- Telephone fixe --}}
                                        <div class="row mb-3">
                                            <label for="fixe" class="col-md-4 col-lg-3 col-form-label">Téléphone
                                                fixe</label>
                                            <div class="col-md-8 col-lg-9">
                                                <input name="fixe" type="text" maxlength="12"
                                                    class="form-control form-control-sm @error('fixe') is-invalid @enderror"
                                                    id="fixe" value="{{ old('fixe', $user->fixe ?? '') }}"
                                                    autocomplete="tel" placeholder="Téléphone fixe">
                                                @error('fixe')
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        {{-- Telephone portable --}}
                                        <div class="row mb-3">
                                            <label for="telephone" class="col-md-4 col-lg-3 col-form-label">
                                                Portable<span class="text-danger mx-1">*</span></label>
                                            <div class="col-md-8 col-lg-9">
                                                <input name="telephone" type="text" maxlength="12"
                                                    class="form-control form-control-sm @error('telephone') is-invalid @enderror"
                                                    id="telephone" value="{{ old('telephone', $user->telephone ?? '') }}"
                                                    autocomplete="tel" placeholder="Téléphone portable">
                                                @error('telephone')
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        {{-- Adresse --}}
                                        <div class="row mb-3">
                                            <label for="adresse" class="col-md-4 col-lg-3 col-form-label">Adresse<span
                                                    class="text-danger mx-1">*</span></label>
                                            <div class="col-md-8 col-lg-9">
                                                <input name="adresse" type="adresse"
                                                    class="form-control form-control-sm @error('adresse') is-invalid @enderror"
                                                    id="adresse" value="{{ $user->adresse ?? old('adresse') }}"
                                                    autocomplete="adresse" placeholder="Adresse de résidence">
                                                @error('adresse')
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        {{-- web --}}
                                        <div class="row mb-3">
                                            <label for="web" class="col-md-4 col-lg-3 col-form-label">Site
                                                web</label>
                                            <div class="col-md-8 col-lg-9">
                                                <input name="web" type="web"
                                                    class="form-control form-control-sm @error('web') is-invalid @enderror"
                                                    id="web" value="{{ $user->web ?? old('web') }}"
                                                    autocomplete="web" placeholder="lien de votre site web">
                                                @error('web')
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        {{-- facebook --}}
                                        <div class="row mb-3">
                                            <label for="facebook" class="col-md-4 col-lg-3 col-form-label">Facebook
                                                profil</label>
                                            <div class="col-md-8 col-lg-9">
                                                <input name="facebook" type="facebook"
                                                    class="form-control form-control-sm @error('facebook') is-invalid @enderror"
                                                    id="facebook" value="{{ $user->facebook ?? old('facebook') }}"
                                                    autocomplete="facebook" placeholder="lien de votre compte facebook">
                                                @error('facebook')
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        {{-- twitter --}}
                                        <div class="row mb-3">
                                            <label for="twitter" class="col-md-4 col-lg-3 col-form-label">X profil (ex
                                                twitter)</label>
                                            <div class="col-md-8 col-lg-9">
                                                <input name="twitter" type="twitter"
                                                    class="form-control form-control-sm @error('twitter') is-invalid @enderror"
                                                    id="twitter" value="{{ $user->twitter ?? old('twitter') }}"
                                                    autocomplete="twitter"
                                                    placeholder="lien de votre compte x (ex twitter)">
                                                @error('twitter')
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        {{-- instagram --}}
                                        <div class="row mb-3">
                                            <label for="instagram" class="col-md-4 col-lg-3 col-form-label">Instagram
                                                profil</label>
                                            <div class="col-md-8 col-lg-9">
                                                <input name="instagram" type="instagram"
                                                    class="form-control form-control-sm @error('instagram') is-invalid @enderror"
                                                    id="instagram" value="{{ $user->instagram ?? old('instagram') }}"
                                                    autocomplete="instagram" placeholder="lien de votre compte instagram">
                                                @error('instagram')
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        {{-- linkedin --}}
                                        <div class="row mb-3">
                                            <label for="linkedin" class="col-md-4 col-lg-3 col-form-label">Linkedin
                                                profil</label>
                                            <div class="col-md-8 col-lg-9">
                                                <input name="linkedin" type="linkedin"
                                                    class="form-control form-control-sm @error('linkedin') is-invalid @enderror"
                                                    id="linkedin" value="{{ $user->linkedin ?? old('linkedin') }}"
                                                    autocomplete="linkedin" placeholder="lien de votre ompte linkedin">
                                                @error('linkedin')
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <hr>
                                        <h5 class="card-title">Personne responsable</h5>
                                        <div class="row mb-3">
                                            <label for="Civilité" class="col-md-4 col-lg-3 col-form-label">Civilité<span
                                                    class="text-danger mx-1">*</span>
                                            </label>
                                            <div class="col-md-8 col-lg-9">
                                                <div class="pt-2">
                                                    <select name="civilite"
                                                        class="form-select form-select-sm @error('civilite') is-invalid @enderror"
                                                        aria-label="Select" id="select-field-civilite"
                                                        data-placeholder="Choisir civilité">
                                                        <option value="{{ $user->civilite ?? old('civilite') }}">
                                                            {{ $user->civilite ?? old('civilite') }}
                                                        </option>
                                                        <option value="M.">
                                                            Monsieur
                                                        </option>
                                                        <option value="Mme">
                                                            Madame
                                                        </option>
                                                    </select>
                                                    @error('civilite')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>


                                        {{-- Prénom --}}
                                        <div class="row mb-3">
                                            <label for="firstname" class="col-md-4 col-lg-3 col-form-label">Prénom<span
                                                    class="text-danger mx-1">*</span>
                                            </label>
                                            <div class="col-md-8 col-lg-9">
                                                <div class="pt-2">
                                                    <input name="firstname" type="text"
                                                        class="form-control form-control-sm @error('firstname') is-invalid @enderror"
                                                        id="firstname" value="{{ $user->firstname ?? old('firstname') }}"
                                                        autocomplete="firstname" placeholder="Votre prénom">
                                                </div>
                                                @error('firstname')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        {{-- Nom --}}
                                        <div class="row mb-3">
                                            <label for="name" class="col-md-4 col-lg-3 col-form-label">Nom<span
                                                    class="text-danger mx-1">*</span></label>
                                            <div class="col-md-8 col-lg-9">
                                                <input name="name" type="text"
                                                    class="form-control form-control-sm @error('name') is-invalid @enderror"
                                                    id="name" value="{{ $user->name ?? old('name') }}"
                                                    autocomplete="name" placeholder="Votre Nom">
                                                @error('name')
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        {{-- telephone_parent --}}
                                        <div class="row mb-3">
                                            <label for="telephone_parent"
                                                class="col-md-4 col-lg-3 col-form-label">Téléphone<span
                                                    class="text-danger mx-1">*</span></label>
                                            <div class="col-md-8 col-lg-9">
                                                <input name="telephone_parent" type="text" maxlength="12"
                                                    class="form-control form-control-sm @error('telephone_parent') is-invalid @enderror"
                                                    id="telephone_parent"
                                                    value="{{ old('telephone_parent', $user->telephone_parent ?? '') }}"
                                                    autocomplete="tel" placeholder="Téléphone responsable">
                                                @error('telephone_parent')
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        {{-- Email --}}
                                        <div class="row mb-3">
                                            <label for="email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                                            <div class="col-md-8 col-lg-9">
                                                <input name="email_responsable" type="email"
                                                    class="form-control form-control-sm @error('email_responsable') is-invalid @enderror"
                                                    id="email_responsable"
                                                    value="{{ $user->email_responsable ?? old('email_responsable') }}"
                                                    autocomplete="email_responsable" placeholder="Email responsable">
                                                @error('email_responsable')
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        {{-- fonction --}}
                                        <div class="row mb-3">
                                            <label for="fonction_responsable"
                                                class="col-md-4 col-lg-3 col-form-label">Fonction<span
                                                    class="text-danger mx-1">*</span></label>
                                            <div class="col-md-8 col-lg-9">
                                                <input name="fonction_responsable" type="text"
                                                    class="form-control form-control-sm @error('fonction_responsable') is-invalid @enderror"
                                                    id="fonction_responsable"
                                                    value="{{ $user->fonction_responsable ?? old('fonction_responsable') }}"
                                                    autocomplete="fonction_responsable"
                                                    placeholder="fonction responsable">
                                                @error('fonction_responsable')
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="text-center">
                                            <button type="submit" class="btn btn-primary btn-sm">Sauvegarder les
                                                modifications</button>
                                        </div>
                                        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                                            <div>
                                                <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                                                    {{ __('Votre adresse e-mail n\'est pas vérifiée.') }}
                                                <form method="POST" action="{{ route('verification.send') }}">
                                                    @csrf

                                                    <div>
                                                        <button type="submit"
                                                            class="btn btn-outline-primary">{{ __('Cliquez ici pour renvoyer l\'e-mail de vérification.') }}</button>

                                                    </div>
                                                </form>
                                                </p>

                                                @if (session('status') === 'verification-link-sent')
                                                    <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                                                        {{ __('Un nouveau lien de vérification a été envoyé à votre adresse e-mail.') }}
                                                    </p>
                                                @endif
                                            </div>
                                        @endif
                                        <!-- End Profile Edit Form -->
                                    </form>
                                </div>
                            </div>
                            <div class="tab-content pt-2">
                                {{-- Fin Edition --}}
                                <div class="tab-pane fade pt-3" id="profile-change-password">
                                    <!-- Change Password Form -->
                                    <form method="post" action="{{ route('password.update') }}">
                                        {{-- Début Modification mot de passe --}}
                                        <div class="flex items-center gap-4">
                                            <!-- Bordered Tabs -->
                                            <div class="tab-pane fade show profile-overview">
                                                <h5 class="card-title">Modification du mot de passe</h5>
                                                <!-- Change Password Form -->
                                                @csrf
                                                @method('put')
                                                <div class="row mb-3">
                                                    <label for="update_password_current_password"
                                                        class="col-md-4 col-lg-4 col-form-label label">Mot de
                                                        passe actuel<span class="text-danger mx-1">*</span></label>
                                                    <div class="col-md-8 col-lg-8">
                                                        <input name="current_password" type="password"
                                                            class="form-control form-control-sm @error('current_password') is-invalid @enderror"
                                                            id="update_password_current_password"
                                                            placeholder="Votre mot de passe actuel"
                                                            autocomplete="current-password">
                                                        {{-- <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" /> --}}
                                                    </div>
                                                </div>
                                                <!-- Mot de passe -->
                                                <div class="row mb-3">
                                                    <label for="password"
                                                        class="col-md-4 col-lg-4 col-form-label label">Mot
                                                        de
                                                        passe<span class="text-danger mx-1">*</span></label>
                                                    <div class="col-md-8 col-lg-8">
                                                        <input type="password" name="password"
                                                            class="form-control form-control-sm @error('password') is-invalid @enderror"
                                                            id="password" placeholder="Votre mot de passe"
                                                            value="{{ old('password') }}" autocomplete="new-password">
                                                        <div class="invalid-feedback">
                                                            @error('password')
                                                                {{ $message }}
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Mot de passe de confirmation -->
                                                <div class="row mb-3">
                                                    <label for="password_confirmation"
                                                        class="col-md-4 col-lg-4 col-form-label label">Confirmez<span
                                                            class="text-danger mx-1">*</span></label>
                                                    <div class="col-md-8 col-lg-8">
                                                        <input type="password" name="password_confirmation"
                                                            class="form-control form-control-sm @error('password_confirmation') is-invalid @enderror"
                                                            id="password_confirmation"
                                                            placeholder="Confimez votre mot de passe"
                                                            value="{{ old('password_confirmation') }}"
                                                            autocomplete="new-password_confirmation">
                                                        <div class="invalid-feedback">
                                                            @error('password_confirmation')
                                                                {{ $message }}
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="text-center">
                                                    <button type="submit" class="btn btn-primary btn-sm">Changer
                                                        le mot de
                                                        passe</button>
                                                </div>
                                                <!-- End Change Password Form -->
                                            </div>
                                        </div>
                                        {{-- Fin Modification mot de passe --}}
                                    </form><!-- End Change Password Form -->
                                </div>
                            </div><!-- End Bordered Tabs -->

                            <div class="tab-content">
                                {{-- Début Edition --}}
                                <div class="tab-pane fade files" id="files">
                                    <div class="card-body">
                                        {{-- MESSAGE D'ALERTE --}}
                                        <div class="alert alert-warning text-center mb-3">
                                            ⚠️ Cet onglet ne sert pas à déposer des fichiers. Veuillez aller dans
                                            <strong>Agréments</strong> ou dans <strong>Devenir opérateur</strong> pour
                                            téléverser vos documents.
                                        </div>

                                        <div class="card-header bg-light">
                                            <h5 class="mb-0">
                                                <i class="bi bi-folder2-open me-2"></i>
                                                Fichiers joints
                                            </h5>
                                        </div>
                                        @if ($files->isNotEmpty())
                                            <div class="table-responsive">

                                                <table class="table table-hover table-bordered align-middle datatables"
                                                    id="table-files">

                                                    <thead class="table-light">
                                                        <tr>
                                                            <th width="5%" class="text-center">N°</th>
                                                            <th>Légende</th>
                                                            <th width="10%" class="text-center">Sigle</th>
                                                            <th width="10%" class="text-center">Fichier</th>
                                                            <th width="10%" class="text-center">Statut</th>
                                                            <th width="10%" class="text-center">Supprimer</th>

                                                            @hasanyrole('super-admin|admin|DIOF')
                                                                <th width="10%" class="text-center">Valider</th>
                                                                <th width="10%" class="text-center">Rejeter</th>
                                                            @endhasanyrole
                                                        </tr>
                                                    </thead>

                                                    <tbody>

                                                        @foreach ($files as $i => $file)
                                                            <tr>

                                                                <td class="text-center">{{ $i + 1 }}</td>

                                                                <td class="text-start">
                                                                    {{ $labels[$file->legende] ?? $file->legende }}
                                                                </td>
                                                                <td class="text-center">
                                                                    {{ $labels[$file->sigle] ?? $file->sigle }}
                                                                </td>

                                                                {{-- DOWNLOAD --}}
                                                                <td class="text-center">

                                                                    <a href="{{ asset($file->getFichier()) }}"
                                                                        target="_blank"
                                                                        class="btn btn-outline-secondary btn-sm">

                                                                        <i class="bi bi-download"></i>

                                                                    </a>

                                                                </td>


                                                                {{-- STATUT --}}
                                                                <td class="text-center">

                                                                    @php
                                                                        $statut = $file->statut ?? 'Attente';

                                                                        $badge = match ($statut) {
                                                                            'Validé' => 'success',
                                                                            'Rejeté', 'Invalide' => 'danger',
                                                                            default => 'secondary',
                                                                        };
                                                                    @endphp

                                                                    <span class="badge bg-{{ $badge }}">
                                                                        {{ $statut }}
                                                                    </span>

                                                                </td>


                                                                {{-- DELETE --}}
                                                                <td class="text-center">

                                                                    @if ($file->statut !== 'Validé')
                                                                        <form action="{{ route('fileDestroy') }}"
                                                                            method="POST" class="d-inline">

                                                                            @csrf
                                                                            @method('put')

                                                                            <input type="hidden" name="idFile"
                                                                                value="{{ $file->id }}">

                                                                            <button
                                                                                class="btn btn-outline-danger btn-sm show_confirm">
                                                                                <i class="bi bi-trash"></i>
                                                                            </button>

                                                                        </form>
                                                                    @endif

                                                                </td>


                                                                {{-- ADMIN ACTIONS --}}
                                                                @hasanyrole('super-admin|admin|DIOF')
                                                                    <td class="text-center">

                                                                        <form action="{{ route('fileValidate') }}"
                                                                            method="POST">
                                                                            @csrf
                                                                            @method('put')

                                                                            <input type="hidden" name="idFile"
                                                                                value="{{ $file->id }}">

                                                                            <button
                                                                                class="btn btn-outline-success btn-sm show_confirm_valider">
                                                                                <i class="bi bi-check-circle"></i>
                                                                            </button>

                                                                        </form>

                                                                    </td>

                                                                    <td class="text-center">

                                                                        <form action="{{ route('fileInvalide') }}"
                                                                            method="POST">
                                                                            @csrf
                                                                            @method('put')

                                                                            <input type="hidden" name="idFile"
                                                                                value="{{ $file->id }}">

                                                                            <button
                                                                                class="btn btn-outline-warning btn-sm show_confirm_rejeter">
                                                                                <i class="bi bi-x-circle"></i>
                                                                            </button>

                                                                        </form>

                                                                    </td>
                                                                @endhasanyrole

                                                            </tr>
                                                        @endforeach

                                                    </tbody>

                                                </table>

                                            </div>
                                        @else
                                            <div class="alert alert-info text-center mb-0">
                                                Aucun fichier joint
                                            </div>
                                        @endif

                                    </div>
                                </div>
                            </div>

                            <div class="tab-content">
                                <div class="tab-pane fade profile-edit" id="agrements">
                                    <h5 class="card-title text-center">AGREMENTS</h5>
                                    <div class="row mb-3">
                                        @if ($user?->operateurs?->isNotEmpty())
                                            <?php $i = 1; ?>
                                            @foreach ($user?->operateurs->sortByDesc('created_at') as $operateur)
                                                <div class="col-12">
                                                    <h5 class="card-title">Agrément : {{ $i++ }}
                                                        {{-- {{ $operateur?->annee_agrement?->format('Y') }} --}}
                                                    </h5>
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-hover datatables"
                                                            id="table-iles">
                                                            <thead>
                                                                <tr>
                                                                    {{-- <th width='15%' class="text-center">N° agrément
                                                                    </th> --}}
                                                                    <th>Responsable</th>
                                                                    {{-- <th class="text-center">Contact</th> --}}
                                                                    <th class="text-center">Modules</th>
                                                                    <th class="text-center">Formations</th>
                                                                    <th width="15%" class="text-center">Statut</th>
                                                                    <th width='5%'><i class="bi bi-gear"></i></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>

                                                                {{-- <td style="text-align: center">
                                                                    {{ $operateur?->numero_agrement }}
                                                                </td> --}}
                                                                <td>{{ $operateur?->user?->civilite . ' ' . $operateur?->user?->firstname . ' ' . $operateur?->user?->name }}
                                                                </td>
                                                                {{-- <td>
                                                                    @if ($operateur?->user?->fixe)
                                                                        <a
                                                                            href="tel:+221{{ $operateur?->user?->fixe }}">{{ $operateur?->user?->fixe }}</a>
                                                                        <br>
                                                                    @endif
                                                                    <a
                                                                        href="tel:+221{{ $operateur?->user?->telephone }}">{{ $operateur?->user?->telephone }}</a>
                                                                </td> --}}
                                                                <td style="text-align: center;">
                                                                    {{-- @foreach ($operateur->operateurmodules as $operateurmodule)
                                                                    @if ($loop->last)
                                                                        <a href="#"><span
                                                                                class="badge bg-info">{{ $loop->count }}</span></a>
                                                                    @endif
                                                                @endforeach --}}
                                                                    <span
                                                                        class="badge bg-info">{{ $operateur?->operateurmodules->count() }}</span>
                                                                </td>
                                                                <td class="text-center">
                                                                    @foreach ($operateur->formations as $formation)
                                                                        @if ($loop->last)
                                                                            <a href="#"><span
                                                                                    class="badge bg-info">{{ $loop->count }}</span></a>
                                                                        @endif
                                                                    @endforeach
                                                                </td>
                                                                <td style="text-align: center;"><span
                                                                        class="{{ $operateur?->statut_agrement }}">
                                                                        {{ $operateur?->statut_agrement }}</span></td>
                                                                <td>
                                                                    <span class="d-flex align-items-baseline"><a
                                                                            href="{{ route('operateurs.show', $operateur) }}"
                                                                            class="btn btn-primary btn-sm" target="_blank"
                                                                            title="voir détails"><i
                                                                                class="bi bi-eye"></i></a>
                                                                    </span>
                                                                </td>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="alert alert-info">
                                                <p class="text-muted">Vous n'avez aucune formation pour l'instant !
                                                </p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="ShowProfilImage{{ Auth::id() }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title mx-auto">
                            {{ $user?->username ?? '' }}
                        </h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="col-12">
                            <img src="{{ asset($user->getImage() ?? 'images/default.png') }}"
                                class="d-block w-100 main-image rounded-4"
                                alt="{{ $user?->legende ?? 'Photo de profil' }}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Fermer</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.getElementById('categorie').addEventListener('change', function(e) {
            if ("{{ $user?->categorie }}") {
                this.value = "{{ $user?->categorie }}";
            }
        });
    </script>
@endpush
