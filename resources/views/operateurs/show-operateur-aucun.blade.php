@extends('layout.user-layout')
@section('title', 'ONFP | AGREMENTS')
@section('space-work')
    <section class="section">
        <div class="row justify-content-center">
            <div class="col-12">
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
                        <h1 class="h4 text-black mb-0">DEMANDES AGREMENT</h1>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mt-0">
                            <span class="d-flex mt-2 align-items-baseline"><a href="{{ url('/profil') }}"
                                    class="btn btn-success btn-sm" title="retour"><i
                                        class="bi bi-arrow-counterclockwise"></i></a>&nbsp;
                                <p> | Profil</p>
                            </span>

                            @if ($hasRequiredFields)
                                @can('agrement-ouvert')
                                    <div class="d-flex justify-content-end">
                                        <button type="button"
                                            class="btn btn-outline-primary btn-sm rounded-pill px-4 shadow-sm d-flex align-items-center"
                                            data-bs-toggle="modal" data-bs-target="#AddoperateurModal">
                                            <i class="bi bi-plus-circle me-2"></i> Formuler une nouvelle demande
                                        </button>
                                    </div>
                                    {{-- @elsecan('agrement-fermer')
                                    <div class="alert alert-warning d-flex align-items-center small py-2 px-3 my-2"
                                        role="alert">
                                        <i class="bi bi-lock-fill me-2 text-danger fs-5"></i>
                                        <span class="text-danger fw-semibold text-uppercase">Les dépôts pour les agréments sont
                                            pour le moment clôturés</span>
                                    </div>
                                @endcan --}}
                                @else
                                    <div class="alert alert-warning d-flex align-items-center small py-2 px-3 my-2"
                                        role="alert">
                                        <i class="bi bi-lock-fill me-2 text-danger fs-5"></i>
                                        <span class="text-danger fw-semibold text-uppercase">
                                            Les dépôts pour les agréments sont pour le moment clôturés
                                        </span>
                                    </div>
                                @endcan
                            @endif

                        </div>

                        @if ($hasRequiredFields)
                            @can('agrement-ouvert')
                                <div class="alert alert-info">
                                    Vous n'avez aucune demande d'agrément pour le moment !!
                                </div>
                            @endcan
                        @else
                            {{-- <div class="text-center">
                                <h5 class="card-title pb-0 fs-4">Complétez d'abord votre profil</h5>
                                <p class="small">Vous devez compléter votre profil avant de pouvoir soumettre une demande
                                    de formation.</p>
                            </div> --}}

                            <h5 class="text-primary fw-semibold mb-3">Complétez d'abord les informations de la structure
                            </h5>

                            <form method="post" action="{{ route('profile.updated', Auth::user()->uuid) }}"
                                enctype="multipart/form-data" class="row g-3">
                                @csrf
                                @method('patch')
                                <input type="hidden" name="idUser" value="{{ Auth::user()->id }}">
                                <div class="col-12 col-md-12 mb-0">
                                    <label for="operateur" class="form-label">Dénomination<span
                                            class="text-danger mx-1">*</span></label>
                                    <input name="operateur" type="text"
                                        class="form-control form-control-sm @error('operateur') is-invalid @enderror"
                                        id="operateur" value="{{ $user?->operateur ?? old('operateur') }}"
                                        autocomplete="operateur" placeholder="Nom de votre structure">
                                    @error('operateur')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12 col-lg-6 col-md-12">
                                    <label for="username" class="form-label">Sigle ou Enseigne</label>
                                    <input name="username" type="text"
                                        class="form-control form-control-sm @error('username') is-invalid @enderror"
                                        id="username" value="{{ $user?->username ?? old('username') }}"
                                        autocomplete="username" placeholder="Sigle">
                                    @error('username')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12 col-lg-6 col-md-12">
                                    <label for="categorie" class="form-label">Type de structure<span
                                            class="text-danger mx-1">*</span></label>
                                    <select name="categorie"
                                        class="form-select form-select-sm @error('categorie') is-invalid @enderror"
                                        aria-label="Sélectionnez un statut" data-placeholder="Choisir catégorie">
                                        <option value="">-- Choisir --</option>
                                        <option value="Public"
                                            {{ old('categorie', $user?->categorie) == 'Public' ? 'selected' : '' }}>
                                            Public</option>
                                        <option value="Privé"
                                            {{ old('categorie', $user?->categorie) == 'Privé' ? 'selected' : '' }}>Privé
                                        </option>
                                        {{-- <option value="Autre"
                                            {{ old('categorie', $user?->categorie) == 'Autre' ? 'selected' : '' }}>Autre
                                        </option> --}}
                                    </select>
                                    @error('categorie')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                {{-- <div class="col-12 col-lg-6 col-md-12">
                                    <label for="rccm" class="form-label">RCCM/Ninea<span
                                            class="text-danger mx-1">*</span></label>
                                    <select name="rccm"
                                        class="form-select form-select-sm @error('rccm') is-invalid @enderror"
                                        aria-label="Select" id="rccm" data-placeholder="Choisir">
                                        <option value="{{ $user?->rccm ?? old('rccm') }}">
                                            {{ $user?->rccm ?? old('rccm') }}
                                        </option>
                                        <option value="Registre de commerce">
                                            Registre de commerce
                                        </option>
                                        <option value="Ninea">
                                            Ninea
                                        </option>
                                        <option value="Aucun">
                                            Aucun
                                        </option>
                                        <option value="Autre">
                                            Autre
                                        </option>
                                    </select>
                                    @error('rccm')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div> --}}

                                <div class="col-12 col-lg-6 col-md-12">
                                    <label for="ninea" class="form-label">N° Ninea<span
                                            class="text-danger mx-1">*</span></label>
                                    <input type="text" name="ninea" value="{{ $user?->ninea ?? old('ninea') }}"
                                        min="9" max="13"
                                        class="form-control form-control-sm @error('ninea') is-invalid @enderror"
                                        id="ninea" placeholder="Ex : 012345678 1R1">
                                    @error('ninea')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12 col-lg-6 col-md-12">
                                    <label for="statut" class="form-label">Statut juridique<span
                                            class="text-danger mx-1">*</span></label>
                                    <select name="statut"
                                        class="form-select form-select-sm @error('statut') is-invalid @enderror"
                                        aria-label="Sélectionnez un statut juridique" data-placeholder="Choisir">
                                        <option value="">-- Choisir --</option>

                                        @foreach ($statuts as $statut)
                                            <option value="{{ $statut }}"
                                                {{ $selected === $statut ? 'selected' : '' }}>
                                                {{ $statut }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('statut')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                {{-- <div class="col-12 col-lg-6 col-md-12">
                                    <label for="email" class="form-label">E-mail<span
                                            class="text-danger mx-1">*</span></label>
                                    <input name="email" type="email" readonly
                                        class="form-control form-control-sm @error('email') is-invalid @enderror"
                                        id="Email" value="{{ $user->email ?? old('email') }}" autocomplete="email"
                                        placeholder="Adresse e-mail">
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div> --}}

                                <div class="col-12 col-lg-6 col-md-12">
                                    <label for="fixe" class="form-label">Telephone fixe (ou principal)<span
                                            class="text-danger mx-1">*</span></label>
                                    <div class="input-group has-validation">
                                        <input name="fixe" type="text" size="9"
                                            class="form-control form-control-sm @error('fixe') is-invalid @enderror"
                                            id="fixe" value="{{ old('fixe', $user->fixe ?? '') }}"
                                            autocomplete="tel" placeholder="Téléphone">
                                        @error('fixe')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12 col-lg-6 col-md-12">
                                    <label for="telephone" class="form-label">Téléphone portable (ou secondaire)<span
                                            class="text-danger mx-1">*</span></label>
                                    <input name="telephone" type="text" size="9"
                                        class="form-control form-control-sm @error('telephone') is-invalid @enderror"
                                        id="telephone" value="{{ old('telephone', $user->telephone ?? '') }}"
                                        autocomplete="tel" placeholder="Téléphone">
                                    @error('telephone')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12 col-lg-6 col-md-12">
                                    <label for="adresse" class="form-label">Adresse<span
                                            class="text-danger mx-1">*</span></label>
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

                                <div class="col-12 col-lg-6 col-md-12">
                                    <label for="web" class="form-label">Site web</label>
                                    <input name="web" type="web"
                                        class="form-control form-control-sm @error('web') is-invalid @enderror"
                                        id="web" value="{{ $user->web ?? old('web') }}" autocomplete="web"
                                        placeholder="lien de votre site web">
                                    @error('web')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12 col-lg-6 col-md-12">
                                    <label for="Facebook" class="form-label">Facebook</label>
                                    <input name="facebook" type="facebook"
                                        class="form-control form-control-sm @error('facebook') is-invalid @enderror"
                                        id="facebook" value="{!! $user->facebook ?? old('facebook') !!}" autocomplete="facebook"
                                        placeholder="Entrez l'URL de votre compte facebook">
                                    @error('facebook')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12 col-lg-6 col-md-12">
                                    <label for="twitter" class="form-label">X (ex twitter)</label>
                                    <input name="twitter" type="twitter"
                                        class="form-control form-control-sm @error('twitter') is-invalid @enderror"
                                        id="twitter" value="{!! $user->twitter ?? old('twitter') !!}" autocomplete="twitter"
                                        placeholder="Entrez l'URL de votre compte X">
                                    @error('twitter')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12 col-lg-6 col-md-12">
                                    <label for="Instagram" class="form-label">Instagram</label>
                                    <input name="instagram" type="instagram"
                                        class="form-control form-control-sm @error('instagram') is-invalid @enderror"
                                        id="instagram" value="{!! $user->instagram ?? old('instagram') !!}" autocomplete="instagram"
                                        placeholder="Entrez l'URL de votre compte instagram">
                                    @error('instagram')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12 col-lg-6 col-md-12">
                                    <label for="Linkedin" class="form-label">Linkedin</label>
                                    <input name="linkedin" type="linkedin"
                                        class="form-control form-control-sm @error('linkedin') is-invalid @enderror"
                                        id="linkedin" value="{!! $user->linkedin ?? old('linkedin') !!}" autocomplete="linkedin"
                                        placeholder="Entrez l'URL de votre compte linkedin">
                                    @error('linkedin')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <hr class="dropdown-divider mt-4">
                                <h5 class="text-primary">Informations du responsable</h5>
                                {{-- <div class="col-12 col-lg-6 col-md-12">
                                    <label for="cin" class="form-label">CIN</label>
                                    <input name="cin" type="text"
                                        class="form-control form-control-sm @error('cin') is-invalid @enderror"
                                        id="cin" value="{{ $user?->cin ?? old('cin') }}" autocomplete="off"
                                        placeholder="Ex: 1099200500012" minlength="9" maxlength="14">
                                    @error('cin')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div> --}}

                                <div class="col-12 col-lg-6 col-md-12">
                                    <label for="civilite" class="form-label">Civilité<span
                                            class="text-danger mx-1">*</span></label>
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
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12 col-lg-6 col-md-12">
                                    <label for="firstname" class="form-label">Prénom<span
                                            class="text-danger mx-1">*</span></label>
                                    <input name="firstname" type="text"
                                        class="form-control form-control-sm @error('firstname') is-invalid @enderror"
                                        id="firstname" value="{{ $user->firstname ?? old('firstname') }}"
                                        autocomplete="firstname" placeholder="Votre prénom">
                                    @error('firstname')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12 col-lg-6 col-md-12">
                                    <label for="name" class="form-label">Nom<span
                                            class="text-danger mx-1">*</span></label>
                                    <input name="name" type="text"
                                        class="form-control form-control-sm @error('name') is-invalid @enderror"
                                        id="name" value="{{ $user->name ?? old('name') }}" autocomplete="name"
                                        placeholder="Votre nom">
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12 col-lg-6 col-md-12">
                                    <label for="telephone_parent" class="form-label">Téléphone<span
                                            class="text-danger mx-1">*</span></label>
                                    <input name="telephone_parent" type="text" size="9"
                                        class="form-control form-control-sm @error('telephone_parent') is-invalid @enderror"
                                        id="telephone_parent"
                                        value="{{ old('telephone_parent', $user->telephone_parent ?? '') }}"
                                        autocomplete="tel" placeholder="Téléphone">
                                    @error('telephone_parent')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12 col-lg-6 col-md-12">
                                    <label for="fonction_responsable" class="form-label">Fonction<span
                                            class="text-danger mx-1">*</span></label>
                                    <input name="fonction_responsable" type="text"
                                        class="form-control form-control-sm @error('fonction_responsable') is-invalid @enderror"
                                        id="fonction_responsable"
                                        value="{{ $user->fonction_responsable ?? old('fonction_responsable') }}"
                                        autocomplete="fonction_responsable" placeholder="fonction responsable">
                                    @error('fonction_responsable')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12 col-lg-6 col-md-12">
                                    <label for="email_responsable" class="form-label">Email</label>
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

                                <input type="hidden" name="newPassword" value="{{ Auth::user()?->password }}">

                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary btn-sm text-white">Sauvegarder les
                                        modifications</button>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @can('agrement-ouvert')
            <div class="col-12 d-flex flex-column align-items-center justify-content-center">
                <div class="modal fade" id="AddoperateurModal" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="card-header text-center bg-gradient-default">
                                <h1 class="h4 text-black mb-0">AJOUTER AGREMENT</h1>
                            </div>
                            <form method="post" action="{{ route('operateurs.store') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-body">
                                    <div class="row g-3">
                                        {{-- <div class="col-12">
                                            <label for="type_demande" class="form-label">Type demande<span
                                                    class="text-danger mx-1">*</span></label>
                                            <select name="type_demande"
                                                class="form-select form-select-sm @error('type_demande') is-invalid @enderror"
                                                aria-label="Select" id="select-field_type_demande"
                                                data-placeholder="Choisir type de demande">
                                                <option value="{{ old('type_demande') }}">
                                                    {{ old('type_demande') }}
                                                </option>
                                                <option value="Nouvelle">
                                                    Nouvelle
                                                </option>
                                                <option value="Renouvellement">
                                                    Renouvellement
                                                </option>
                                                <option value="Extension">
                                                    Extension
                                                </option>
                                            </select>
                                            @error('type_demande')
                                                <span class="invalid-feedback" role="alert">
                                                    <div>{{ $message }}</div>
                                                </span>
                                            @enderror
                                            <div class="form-text text-muted mt-2">
                                                <i class="bi bi-info-circle me-1"></i>
                                                Choisissez <strong>"Nouvelle"</strong> si vous effectuez une demande pour la
                                                première fois ou si votre
                                                précédent agrément a expiré sans renouvellement.
                                            </div>
                                        </div> --}}
                                        <input name="type_demande" type="hidden" value="Nouvelle">
                                        <div class="col-12">
                                            <label for="departement" class="form-label">Département<span
                                                    class="text-danger mx-1">*</span></label>
                                            <select name="departement"
                                                class="form-select form-select-sm @error('departement') is-invalid @enderror"
                                                aria-label="Select" id="select-field-departement_op"
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

                                        {{-- <div class="col-12">
                                            <label for="quitus" class="form-label">Quitus fiscal<span
                                                    class="text-danger mx-1">*</span></label>
                                            <input type="file" name="quitus" id="quitus"
                                                accept=".jpg, .jpeg, .png, .svg, .gif"
                                                class="form-control @error('quitus') is-invalid @enderror btn btn-outline-primary btn-sm">
                                            @error('quitus')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div> --}}

                                        <div class="col-12">
                                            <label for="date_quitus" class="form-label">Date visa quitus</label>
                                            <input type="text" name="date_quitus" value="{{ old('date_quitus') }}"
                                                class="form-control form-control-sm @error('date_quitus') is-invalid @enderror"
                                                id="datepicker" placeholder="JJ/MM/AAAA" autocomplete="bday">
                                            @error('date_quitus')
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
        @endcan
    </section>
@endsection
