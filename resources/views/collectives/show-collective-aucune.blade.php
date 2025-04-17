@extends('layout.user-layout')
@section('title', 'Mon dossier de demandes collectives')
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
                            @if (!empty(Auth::user()->cin))
                                <button type="button" class="btn btn-primary btn-sm float-end btn-rounded"
                                    data-bs-toggle="modal" data-bs-target="#AddCollectiveModal">
                                    {{-- <i class="bi bi-plus" title="Ajouter"></i> --}} Ajouter
                                </button>
                            @endif
                        </div>
                        @if (!empty(Auth::user()->civilite))
                            <h5 class="card-title">
                                Bonjour
                                {{ Auth::user()->civilite . ' ' . Auth::user()->firstname . ' ' . Auth::user()->name }}
                            </h5>
                        @endif
                        @if (isset(Auth::user()->cin))
                            <div class="alert alert-warning">Vous n'avez aucune demande collective pour le moment !
                            </div>
                            <div class="alert alert-info">
                                NB : Les formations collectives s'adressent aux structures organisées telles que :
                                <ul>
                                    <li>Les groupements</li>
                                    <li>Les associations</li>
                                    <li>Les GIE (Groupements d’Intérêt Économique)</li>
                                    <li>Les établissements</li>
                                </ul>
                                Pour en bénéficier, la structure doit impérativement disposer d’un <b>statut juridique
                                    reconnu</b> et fournir les <b>pièces justificatives officielles</b>.
                                <br><br>
                                Nos formations se font généralement avec des groupes de <b>20 bénéficiaires en moyenne</b>.
                            </div>
                        @else
                            {{-- <h5 class="card-title">Informations personnelles : <a href="{{ route('profil') }}"><span
                                        class="badge bg-warning text-white">Incomplètes</span></a>, cliquez <a
                                    href="{{ route('profil') }}">ici</a> pour modifier votre profil</h5> --}}
                            <div class="text-center">
                                <h5 class="card-title pb-0 fs-4">Complétez d'abord votre profil</h5>
                                <p class="small">Vous devez compléter votre profil avant de pouvoir soumettre une demande
                                    de formation.</p>
                            </div>

                            <form method="post" action="{{ route('profile.update', Auth::user()->uuid) }}"
                                enctype="multipart/form-data" class="row g-3">
                                @csrf
                                @method('patch')
                                <input type="hidden" name="idUser" value="{{ Auth::user()->id }}">
                                <div class="col-12 col-md-6 col-lg-4 mb-0">
                                    <label for="civilite" class="form-label">Civilité<span
                                            class="text-danger mx-1">*</span></label>
                                    <select name="civilite" class="form-select  @error('civilite') is-invalid @enderror"
                                        aria-label="Select" id="select-field-civilite" data-placeholder="Choisir civilité">
                                        <option value="{{ Auth::user()?->civilite ?? old('civilite') }}">
                                            {{ Auth::user()?->civilite ?? old('civilite') }}
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

                                <div class="col-12 col-md-6 col-lg-4 mb-0">
                                    <label for="cin" class="form-label">CIN</label>
                                    <input name="cin" type="text"
                                        class="form-control form-control-sm @error('cin') is-invalid @enderror"
                                        id="cin" value="{{ $user->cin ?? old('cin') }}" autocomplete="off"
                                        placeholder="Ex: 1 099 2005 00012" minlength="16" maxlength="17" required>
                                    @error('cin')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6 col-lg-4 mb-0">
                                    <label for="username" class="form-label">Username<span
                                            class="text-danger mx-1">*</span></label>
                                    <input type="text" name="username"
                                        value="{{ Auth::user()?->username ?? old('username') }}"
                                        class="form-control form-control-sm @error('username') is-invalid @enderror"
                                        id="username" placeholder="username">
                                    @error('username')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6 col-lg-4 mb-0">
                                    <label for="firstname" class="form-label">Prénom<span
                                            class="text-danger mx-1">*</span></label>
                                    <input type="text" name="firstname"
                                        value="{{ Auth::user()?->firstname ?? old('firstname') }}"
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
                                    <input type="text" name="name" value="{{ Auth::user()?->name ?? old('name') }}"
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
                                        value="{{ old('date_naissance', optional(Auth::user()?->date_naissance)->format('d/m/Y')) }}"
                                        class="form-control form-control-sm @error('date_naissance') is-invalid @enderror"
                                        id="datepicker" placeholder="JJ/MM/AAAA" autocomplete="bday">
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6 col-lg-4 mb-0">
                                    <label for="lieu_naissance" class="form-label">Lieu naissance<span
                                            class="text-danger mx-1">*</span></label>
                                    <input type="text" name="lieu_naissance"
                                        value="{{ Auth::user()?->lieu_naissance ?? old('lieu_naissance') }}"
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
                                            value="{{ old('email', Auth::user()?->email ?? '') }}" readonly
                                            class="form-control form-control-sm @error('email') is-invalid @enderror"
                                            id="email" placeholder="email">
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
                                        id="telephone" value="{{ old('telephone', Auth::user()?->telephone ?? '') }}"
                                        autocomplete="tel" placeholder="XX:XXX:XX:XX">
                                    @error('telephone')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6 col-lg-4 mb-0">
                                    <label for="adresse" class="form-label">Adresse<span
                                            class="text-danger mx-1">*</span></label>
                                    <input type="text" name="adresse"
                                        value="{{ Auth::user()?->adresse ?? old('adresse') }}"
                                        class="form-control form-control-sm @error('adresse') is-invalid @enderror"
                                        id="adresse" placeholder="adresse">
                                    @error('adresse')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6 col-lg-4 mb-0">
                                    <label for="situation_familiale" class="form-label">Situation familiale<span
                                            class="text-danger mx-1">*</span></label>
                                    <select name="situation_familiale"
                                        class="form-select  @error('situation_familiale') is-invalid @enderror"
                                        aria-label="Select" id="select-field-familiale"
                                        data-placeholder="Choisir situation familiale">
                                        <option
                                            value="{{ Auth::user()?->situation_familiale ?? old('situation_familiale') }}">
                                            {{ Auth::user()?->situation_familiale ?? old('situation_familiale') }}
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
                                        <option value="Divorsé(e)">
                                            Divorsé(e)
                                        </option>
                                    </select>
                                    @error('situation_familiale')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6 col-lg-4 mb-0">
                                    <label for="situation_profesionnelle" class="form-label">Situation
                                        profesionnelle<span class="text-danger mx-1">*</span></label>
                                    <select name="situation_professionnelle"
                                        class="form-select  @error('situation_professionnelle') is-invalid @enderror"
                                        aria-label="Select" id="select-field-professionnelle"
                                        data-placeholder="Choisir situation professionnelle">
                                        <option
                                            value="{{ Auth::user()?->situation_professionnelle ?? old('situation_professionnelle') }}">
                                            {{ Auth::user()?->situation_professionnelle ?? old('situation_professionnelle') }}
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
                                        <option value="chercheur emploi">
                                            chercheur emploi
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
                                    <label for="adresse" class="form-label">Facebook</label>
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

                                <div class="col-12 col-md-6 col-lg-4 mb-0">
                                    <label for="adresse" class="form-label">X (ex twitter)</label>
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

                                <div class="col-12 col-md-6 col-lg-4 mb-0">
                                    <label for="adresse" class="form-label">Instagram</label>
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

                                <div class="col-12 col-md-6 col-lg-4 mb-0">
                                    <label for="adresse" class="form-label">Linkedin</label>
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

                                <input type="hidden" name="newPassword" value="{{ Auth::user()?->password }}">

                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary btn-sm">Sauvegarder les
                                        modifications</button>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div
            class="col-12 col-md-12 col-lg-12 col-sm-12 col-xs-12 col-xxl-12 d-flex flex-column align-items-center justify-content-center">
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
                                <h1 class="h4 text-black mb-0">Ajouter une demande collective</h1>
                            </div>
                            <div class="modal-body">
                                <div class="row g-3">
                                    <div class="col-12 col-md-12 col-lg-12 col-sm-12 col-xs-12 col-xxl-12 mb-0">
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

                                    <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
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

                                    <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
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

                                    <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
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

                                    <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
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

                                    <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
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

                                    <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
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

                                    <div class="col-12 col-md-12 col-lg-12 col-sm-12 col-xs-12 col-xxl-12  mb-0">
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

                                    <div class="col-12 col-md-12 col-lg-12 col-sm-12 col-xs-12 col-xxl-12  mb-0">
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

                                    <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
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
                                    <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
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

                                    <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
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

                                    <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
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

                                    <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
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

                                    <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
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
