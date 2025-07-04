@extends('layout.user-layout')
@section('title', 'Modification utilisateur')
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
                <div class="col-lg-12 col-md-12 d-flex flex-column align-items-center justify-content-center">
                    <div class="card mb-3">

                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-12 pt-2">
                                    <span class="d-flex mt-2 align-items-baseline"><a href="{{ route('user.index') }}"
                                            class="btn btn-success btn-sm" title="retour"><i
                                                class="bi bi-arrow-counterclockwise"></i></a>&nbsp;
                                        <p> | Liste des utilisateurs</p>
                                    </span>
                                </div>
                            </div>
                            <div class="text-center">
                                <h5 class="card-title pb-0 fs-4">Modification</h5>
                                <p class="small">Saisissez les nouvelles informations pour effectuer la modification.</p>
                            </div>
                            <form method="post" action="{{ route('users.update', $user) }}"
                                enctype="multipart/form-data" class="row g-3">
                                @csrf
                                @method('patch')
                                <div class="col-12 col-md-6 col-lg-4 mb-0">
                                    <label for="civilite" class="form-label">Civilité<span
                                            class="text-danger mx-1">*</span></label>
                                    <select name="civilite" class="form-select  @error('civilite') is-invalid @enderror"
                                        aria-label="Select" id="select-field-civilite" data-placeholder="Choisir civilité">
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

                                <div class="col-12 col-md-6 col-lg-4 mb-0">
                                    <label for="cin" class="form-label">CIN</label>
                                    <input name="cin" type="text"
                                        class="form-control form-control-sm @error('cin') is-invalid @enderror"
                                        id="cin" value="{{ $user?->cin ?? old('cin') }}" autocomplete="off"
                                        placeholder="Ex: 1 099 2005 00012" minlength="16" maxlength="17">
                                    @error('cin')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                {{-- <div class="col-12 col-md-6 col-lg-4 mb-0">
                                    <label for="username" class="form-label">Username<span
                                            class="text-danger mx-1">*</span></label>
                                    <input type="text" name="username" value="{{ $user->username ?? old('username') }}"
                                        class="form-control form-control-sm @error('username') is-invalid @enderror"
                                        id="username" placeholder="username">
                                    @error('username')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div> --}}

                                <div class="col-12 col-md-6 col-lg-4 mb-0">
                                    <label for="firstname" class="form-label">Prénom<span
                                            class="text-danger mx-1">*</span></label>
                                    <input type="text" name="firstname"
                                        value="{{ $user->firstname ?? old('firstname') }}"
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
                                    <input type="text" name="name" value="{{ $user->name ?? old('name') }}"
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
                                        value="{{ old('date_naissance', optional($user->date_naissance)->format('d/m/Y')) }}"
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
                                        value="{{ $user->lieu_naissance ?? old('lieu_naissance') }}"
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
                                            value="{{ old('email', $user->email ?? '') }}"
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
                                        id="telephone" value="{{ old('telephone', $user->telephone ?? '') }}"
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
                                    <input type="text" name="adresse" value="{{ $user->adresse ?? old('adresse') }}"
                                        class="form-control form-control-sm @error('adresse') is-invalid @enderror"
                                        id="adresse" placeholder="adresse">
                                    @error('adresse')
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
                                        <option value="{{ $user->situation_familiale ?? old('situation_familiale') }}">
                                            {{ $user->situation_familiale ?? old('situation_familiale') }}
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
                                        profesionnelle</label>
                                    <select name="situation_professionnelle"
                                        class="form-select  @error('situation_professionnelle') is-invalid @enderror"
                                        aria-label="Select" id="select-field-professionnelle"
                                        data-placeholder="Choisir situation professionnelle">
                                        <option
                                            value="{{ $user->situation_professionnelle ?? old('situation_professionnelle') }}">
                                            {{ $user->situation_professionnelle ?? old('situation_professionnelle') }}
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

                                {{--  <div class="col-12 col-md-6 col-lg-4 mb-0">
                                    <label for="password" class="form-label">Modifier mot de passe</label>
                                    <input type="password" name="password"
                                        class="form-control form-control-sm @error('password') is-invalid @enderror"
                                        id="password" placeholder="Votre mot de passe">
                                    <div class="invalid-feedback">
                                        @error('password')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div> --}}

                                <div class="col-12 col-md-12 col-lg-8 mb-0">
                                    <label for="roles" class="form-label">Roles</label>
                                    <select name="roles[]" class="form-select" aria-label="Select"
                                        id="multiple-select-field" multiple data-placeholder="Choisir un ou plusieurs roles">
                                        @foreach ($roles as $role)
                                            <option value="{{ $role }}"
                                                {{ in_array($role, $userRoles) ? 'selected' : '' }}>
                                                {{ $role ?? old('role') }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-6 col-lg-4 mb-0">
                                    <label for="profil" class="form-label">Image de profil</label>
                                    <input type="file" name="image" id="image"
                                        class="form-control @error('image') is-invalid @enderror btn btn-outline-info btn-sm">
                                    @error('image')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    <div class="col-xl-4">
                                        <div class="profile-card pt-4 d-flex flex-column">
                                            <img class="rounded-sm w-25" alt="Profil"
                                                src="{{ asset($user->getImage()) }}" width="20" height="auto">
                                        </div>
                                    </div>
                                </div>

                                <input type="hidden" name="newPassword" value="{{ $user->password }}">

                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary btn-sm">Sauvegarder les
                                        modifications</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
@endsection
