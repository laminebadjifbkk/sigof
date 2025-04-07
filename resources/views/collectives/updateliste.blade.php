@extends('layout.user-layout')
@section('title', 'ONFP - Liste membres')
@section('space-work')
    <section class="section">

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
                <div class="col-lg-12 col-md-12 d-flex flex-column align-items-center justify-content-center">
                    <div class="card mb-0">
                        @can('collective-view')
                            <span class="nav-link"><a
                                    href="{{ route('collectivemodules.show', $listecollective?->collectivemodule?->id) }}"
                                    class="btn btn-secondary btn-sm" title="retour"><i
                                        class="bi bi-arrow-counterclockwise"></i></a>
                            </span>
                        @endcan
                        <div class="card-body">
                            <span class="d-flex align-items-baseline"><a
                                    href="{{ route('collectivemodules.show', $listecollective?->collectivemodule?->id) }}"
                                    class="btn btn-success btn-sm" title="retour"><i
                                        class="bi bi-arrow-counterclockwise"></i></a>&nbsp;
                                <p> | retour</p>
                            </span>
                            <form method="post" action="{{ url('listecollectives/' . $listecollective->id) }}"
                                enctype="multipart/form-data" class="row g-3">
                                @csrf
                                @method('PUT')
                                <div class="row g-3">
                                    <input type="hidden" name="collective" value="{{ $listecollective->collective->id }}">
                                    <div class="col-12 col-md-4 col-lg-4 mb-0">
                                        <label for="cin" class="form-label">CIN<span
                                                class="text-danger mx-1">*</span></label>
                                        <input name="cin" type="text"
                                            class="form-control form-control-sm @error('cin') is-invalid @enderror"
                                            id="cin" value="{{ old('cin') ?? $listecollective?->cin }}"
                                            autocomplete="off" placeholder="Ex: 1 099 2005 00012" minlength="16"
                                            maxlength="17" required>
                                        @error('cin')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-4 col-lg-4 mb-0">
                                        <label for="civilite" class="form-label">Civilité<span
                                                class="text-danger mx-1">*</span></label>
                                        <select name="civilite"
                                            class="form-select form-select-sm @error('civilite') is-invalid @enderror"
                                            aria-label="Select" id="select-field-civilite"
                                            data-placeholder="Choisir civilité">
                                            <option value="{{ $listecollective?->civilite ?? old('civilite') }}">
                                                {{ $listecollective?->civilite ?? old('civilite') }}
                                            </option>
                                            <option value="M.">
                                                M.
                                            </option>
                                            <option value="Mme">
                                                Mme
                                            </option>
                                        </select>
                                        @error('civilite')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-12 col-md-4 col-lg-4 mb-0">
                                        <label for="firstname" class="form-label">Prénom<span
                                                class="text-danger mx-1">*</span></label>
                                        <input type="text" name="firstname"
                                            value="{{ $listecollective?->prenom ?? old('firstname') }}"
                                            class="form-control form-control-sm @error('firstname') is-invalid @enderror"
                                            id="firstname" placeholder="prénom">
                                        @error('firstname')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-4 col-lg-4 mb-0">
                                        <label for="name" class="form-label">Nom<span
                                                class="text-danger mx-1">*</span></label>
                                        <input type="text" name="name"
                                            value="{{ $listecollective?->nom ?? old('name') }}"
                                            class="form-control form-control-sm @error('name') is-invalid @enderror"
                                            id="name" placeholder="nom">
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-4 col-lg-4 mb-0">
                                        <label for="date_naissance" class="form-label">Date naissance<span
                                                class="text-danger mx-1">*</span></label>
                                        <input type="text" name="date_naissance"
                                            value="{{ old('date_naissance', optional($listecollective?->date_naissance)->format('d/m/Y')) }}"
                                            class="form-control form-control-sm @error('date_naissance') is-invalid @enderror"
                                            id="datepicker" placeholder="JJ/MM/AAAA" autocomplete="bday">
                                        @error('date_naissance')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-4 col-lg-4 mb-0">
                                        <label for="name" class="form-label">Lieu naissance<span
                                                class="text-danger mx-1">*</span></label>
                                        <input name="lieu_naissance" type="text"
                                            class="form-control form-control-sm @error('lieu_naissance') is-invalid @enderror"
                                            id="lieu_naissance"
                                            value="{{ $listecollective?->lieu_naissance ?? old('lieu_naissance') }}"
                                            autocomplete="lieu_naissance" placeholder="Lieu naissance">
                                        @error('lieu_naissance')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <input type="hidden" name="module"
                                        value="{{ $listecollective?->collectivemodule->id }}">

                                    <div class="col-12 col-md-4 col-lg-4 mb-0">
                                        <label for="telephone" class="form-label">Téléphone</label>
                                        <input name="telephone" type="text" maxlength="12"
                                            class="form-control form-control-sm @error('telephone') is-invalid @enderror"
                                            id="telephone"
                                            value="{{ old('telephone', $listecollective->telephone ?? '') }}"
                                            autocomplete="tel" placeholder="XX:XXX:XX:XX">
                                        @error('telephone')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-4 col-lg-4 mb-0">
                                        <label for="Niveau étude" class="form-label">Niveau étude</label>
                                        <select name="niveau_etude"
                                            class="form-select  @error('niveau_etude') is-invalid @enderror"
                                            aria-label="Select" id="select-field-niveau_etude"
                                            data-placeholder="Choisir niveau étude">
                                            <option value="{{ $listecollective?->niveau_etude ?? old('niveau_etude') }}">
                                                {{ $listecollective?->niveau_etude ?? old('niveau_etude') }}
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

                                    {{-- <div class="col-12 col-md-4 col-lg-4 mb-0">
                                        <label for="Statut" class="form-label">Statut</label>
                                        <select name="statut"
                                            class="form-select  @error('statut') is-invalid @enderror"
                                            aria-label="Select" id="select-field-statut"
                                            data-placeholder="Choisir Statut">
                                            <option value="{{ $listecollective?->statut ?? old('statut') }}">
                                                {{ $listecollective?->statut ?? old('statut') }}
                                            </option>
                                            <option value="Nouvelle">
                                                Nouvelle
                                            </option>
                                        </select>
                                        @error('statut')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div> --}}

                                    <div class="col-12 col-md-12 col-lg-12 mb-0">
                                        <label for="experience" class="form-label">Expériences</label>
                                        <textarea name="experience" id="experience" rows="1"
                                            class="form-control form-control-sm @error('experience') is-invalid @enderror"
                                            placeholder="Expériences ou stages">{{ $listecollective?->experience ?? old('experience') }}</textarea>
                                        @error('experience')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-12 col-lg-12 mb-0">
                                        <label for="autre_experience" class="form-label">Autres expériences</label>
                                        <textarea name="autre_experience" id="autre_experience" rows="1"
                                            class="form-control form-control-sm @error('autre_experience') is-invalid @enderror"
                                            placeholder="Autres expériences">{{ $listecollective?->autre_experience ?? old('autre_experience') }}</textarea>
                                        @error('autre_experience')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-12 col-lg-12 mb-0">
                                        <label for="details" class="form-label">Commentaires</label>
                                        <textarea name="details" id="details" rows="1"
                                            class="form-control form-control-sm @error('details') is-invalid @enderror" placeholder="Autres expériences">{{ $listecollective?->details ?? old('details') }}</textarea>
                                        @error('details')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="modal-footer text-center">
                                        <button type="submit" class="btn btn-outline-primary btn-sm">Enregister les
                                            modifications</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
