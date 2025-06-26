@extends('layout.user-layout')
@section('title', 'modification demande collective')
@section('space-work')
    <section class="section min-vh-0 d-flex flex-column align-items-center justify-content-center py-0">
        <div class="container-fluid">
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
                <div
                    class="col-12 col-md-12 col-lg-12 col-sm-12 col-xs-12 col-xxl-12 d-flex flex-column align-items-center justify-content-center">
                    <div class="card mb-3">

                        <div class="card-body">
                            @if (auth()->user()->hasAnyRole(['super-admin', 'admin', 'Ingenieur']))
                                <div class="row">
                                    <div class="col-sm-12 pt-2">
                                        <span class="d-flex mt-2 align-items-baseline">
                                            <a href="{{ route('collectives.index') }}" class="btn btn-success btn-sm"
                                                title="retour">
                                                <i class="bi bi-arrow-counterclockwise"></i>
                                            </a>&nbsp;
                                            <p> | Retour</p>
                                        </span>
                                    </div>
                                </div>
                            @elseif (auth()->user()->hasAnyRole(['Demandeur']))
                                <div class="row">
                                    <div class="col-sm-12 pt-2">
                                        <span class="d-flex mt-2 align-items-baseline">
                                            <a href="{{ route('demandesCollective') }}" class="btn btn-success btn-sm"
                                                title="retour">
                                                <i class="bi bi-arrow-counterclockwise"></i>
                                            </a>&nbsp;
                                            <p> | Retour</p>
                                        </span>
                                    </div>
                                </div>
                            @endif

                            <form method="post" action="{{ route('collectives.update', $collective) }}"
                                enctype="multipart/form-data" class="row g-3">
                                @csrf
                                @method('PUT')

                                {{-- <div class="col-12 col-md-8 col-lg-8 col-sm-12 col-xs-12 col-xxl-8">
                                    <label for="name" class="form-label">Nom de la structure<span
                                            class="text-danger mx-1">*</span></label>
                                    <textarea name="name" id="name" rows="1"
                                        class="form-control form-control-sm @error('name') is-invalid @enderror"
                                        placeholder="La raison sociale de l'opérateur">{{ $collective?->name ?? old('name') }}</textarea>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div> --}}

                                <div class="col-12 col-md-6 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                    <label for="numero" class="form-label">N° courrier<span
                                            class="text-danger mx-1">*</span></label>
                                    <input type="text" placeholder="Rechercher numéro courrier..."
                                        class="form-control form-control-sm @error('product') is-invalid @enderror"
                                        name="numero_courrier" id="numero"
                                        value="{{ $collective?->numero ?? old('numero_courrier') }}" required>
                                    <div id="productList"></div>
                                    @error('numero')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-12 col-md-8 col-lg-8 col-sm-12 col-xs-12 col-xxl-8">
                                    <label for="objet" class="form-label">Nom de la structure<span
                                            class="text-danger mx-1">*</span></label>
                                    <input type="text" placeholder="La raison sociale de l'opérateur"
                                        class="form-control form-control-sm @error('objet') is-invalid @enderror"
                                        name="name" id="objet" value="{{ $collective?->name ?? old('name') }}"
                                        required>
                                    @error('objet')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                    <label for="sigle" class="form-label">Sigle</label>
                                    <input type="text" name="sigle" value="{{ $collective?->sigle ?? old('sigle') }}"
                                        class="form-control form-control-sm @error('sigle') is-invalid @enderror"
                                        id="sigle" placeholder="Sigle ou abréviation">
                                    @error('sigle')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                    <label for="email" class="form-label">Email<span
                                            class="text-danger mx-1">*</span></label>
                                    <input type="email" name="email" value="{{ $collective?->email ?? old('email') }}"
                                        class="form-control form-control-sm @error('email') is-invalid @enderror"
                                        id="email" placeholder="Adresse email">
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                    <label for="fixe" class="form-label">Téléphone fixe</label>
                                    <input name="fixe" type="text" maxlength="12"
                                        class="form-control form-control-sm @error('fixe') is-invalid @enderror"
                                        id="fixe" value="{{ $collective?->fixe ?? old('fixe') }}" autocomplete="tel"
                                        placeholder="XX:XXX:XX:XX">
                                    @error('fixe')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                    <label for="telephone" class="form-label">Téléphone portable<span
                                            class="text-danger mx-1">*</span></label>
                                    <input name="telephone" type="text" maxlength="12"
                                        class="form-control form-control-sm @error('telephone') is-invalid @enderror"
                                        id="telephone" value="{{ $collective?->telephone ?? old('telephone') }}"
                                        autocomplete="tel" placeholder="XX:XXX:XX:XX">
                                    @error('telephone')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                    <label for="bp" class="form-label">Boite postal</label>
                                    <input type="text" name="bp" value="{{ $collective?->bp ?? old('bp') }}"
                                        class="form-control form-control-sm @error('bp') is-invalid @enderror"
                                        id="bp" placeholder="Boite postal">
                                    @error('bp')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                    <label for="statut" class="form-label">Statut juridique<span
                                            class="text-danger mx-1">*</span></label>
                                    <select name="statut" class="form-select  @error('statut') is-invalid @enderror"
                                        aria-label="Select" id="select-field-statut" data-placeholder="Choisir statut">
                                        <option value="{{ $collective?->statut_juridique ?? old('statut') }}">
                                            {{ $collective?->statut_juridique ?? old('statut') }}
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

                                <div class="col-12 col-md-6 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                    <label for="autre_statut" class="form-label">Si autre ?
                                        précisez</label>
                                    <input type="text" name="autre_statut"
                                        value="{{ $collective?->autre_statut_juridique ?? old('autre_statut') }}"
                                        class="form-control form-control-sm @error('autre_statut') is-invalid @enderror"
                                        id="autre_statut" placeholder="autre statut juridique">
                                    @error('autre_statut')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>
                                @can('collective-show')
                                    <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                        <label for="date_depot" class="form-label">Date dépot<span
                                                class="text-danger mx-1">*</span></label>
                                        <input type="date" name="date_depot"
                                            value="{{ date_format(date_create($collective?->date_depot), 'Y-m-d') ?? old('date_depot') }}"
                                            class="datepicker form-control form-control-sm @error('date_depot') is-invalid @enderror"
                                            id="date_depot" placeholder="jj/mm/aaaa">
                                        @error('date_depot')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>
                                @endcan

                                <div class="col-12 col-md-6 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                    <label for="departement" class="form-label">Département<span
                                            class="text-danger mx-1">*</span></label>
                                    <select name="departement"
                                        class="form-select form-select-sm @error('departement') is-invalid @enderror"
                                        aria-label="Select" id="select-field-departement" data-placeholder="Choisir">
                                        <option value="{{ $collective?->departement?->nom }}">
                                            {{ $collective?->departement?->nom }}</option>
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

                                <div class="col-12 col-md-12 col-lg-12 col-sm-12 col-xs-12 col-xxl-12">
                                    <label for="adresse" class="form-label">Adresse<span
                                            class="text-danger mx-1">*</span></label>
                                    <input type="text" name="adresse"
                                        value="{{ $collective?->adresse ?? old('adresse') }}"
                                        class="form-control form-control-sm @error('adresse') is-invalid @enderror"
                                        id="adresse" placeholder="Adresse exacte">
                                    @error('adresse')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                {{--  <div class="col-12 col-md-12 col-lg-12 col-sm-12 col-xs-12 col-xxl-4">
                                    <label for="module" class="form-label">Formation sollicitée<span
                                            class="text-danger mx-1">*</span></label>
                                    <select name="module" class="form-select  @error('module') is-invalid @enderror"
                                        aria-label="Select" id="select-field-module"
                                        data-placeholder="Choisir formation">
                                        <option value="{{ $collective?->module?->id }}">
                                            {{ $collective?->module?->name ?? old('module') }}
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

                                <div class="col-12 col-md-12 col-lg-12 col-sm-12 col-xs-12 col-xxl-12">
                                    <label for="description" class="form-label">Description de l'organisation<span
                                            class="text-danger mx-1">*</span></label>
                                    {{-- <textarea name="description" rows="2"
                                        class="form-control form-control-sm @error('description') is-invalid @enderror"
                                        placeholder="Description de l'organisation, de ses activités et de ses réalisations">
                                        {{ $collective?->description ?? old('description') }}
                                    </textarea> --}}
                                    <textarea name="description" id="description" rows="4"
                                        class="form-control form-control-sm @error('description') is-invalid @enderror"
                                        placeholder="Description de l'organisation, de ses activités et de ses réalisations">{{ $collective?->description ?? old('description') }}
                                    </textarea>

                                    @error('description')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-12 col-lg-12 col-sm-12 col-xs-12 col-xxl-12">
                                    <label for="projetprofessionnel" class="form-label">Projet professionnel<span
                                            class="text-danger mx-1">*</span></label>
                                    <textarea name="projetprofessionnel" id="projetprofessionnel" rows="4"
                                        class="form-control form-control-sm @error('projetprofessionnel') is-invalid @enderror"
                                        placeholder="Description détaillée du projet professionnel et de l'effet attendu après la formation">{{ $collective?->projetprofessionnel ?? old('projetprofessionnel') }}
                                    </textarea>

                                    @error('projetprofessionnel')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <hr class="dropdown-divider mt-3">

                                <div class="col-12 col-md-6 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                    <label for="civilite" class="form-label">Civilité responsable<span
                                            class="text-danger mx-1">*</span></label>
                                    <select name="civilite"
                                        class="form-select form-select-sm @error('civilite') is-invalid @enderror"
                                        aria-label="Select" id="select-field-civilite"
                                        data-placeholder="Choisir civilité">
                                        <option value="{{ $collective->civilite_responsable ?? old('civilite') }}">
                                            {{ $collective->civilite_responsable ?? old('civilite') }}
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
                                <div class="col-12 col-md-6 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                    <label for="prenom" class="form-label">Prénom responsable<span
                                            class="text-danger mx-1">*</span></label>
                                    <input type="text" name="prenom"
                                        value="{{ $collective->prenom_responsable ?? old('prenom') }}"
                                        class="form-control form-control-sm @error('prenom') is-invalid @enderror"
                                        id="prenom" placeholder="Prénom responsable">
                                    @error('prenom')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                    <label for="nom" class="form-label">Nom responsable<span
                                            class="text-danger mx-1">*</span></label>
                                    <input type="text" name="nom"
                                        value="{{ $collective->nom_responsable ?? old('nom') }}"
                                        class="form-control form-control-sm @error('nom') is-invalid @enderror"
                                        id="nom" placeholder="Nom responsable">
                                    @error('nom')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                    <label for="email_responsable" class="form-label">Adresse e-mail<span
                                            class="text-danger mx-1">*</span></label>
                                    <input type="email" name="email_responsable"
                                        value="{{ $collective->email_responsable ?? old('email_responsable') }}"
                                        class="form-control form-control-sm @error('email_responsable') is-invalid @enderror"
                                        id="email_responsable" placeholder="Adresse email responsable">
                                    @error('email_responsable')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                    <label for="telephone_responsable" class="form-label">Téléphone responsable<span
                                            class="text-danger mx-1">*</span></label>
                                    <input name="telephone_responsable" type="text" maxlength="12"
                                        class="form-control form-control-sm @error('telephone_responsable') is-invalid @enderror"
                                        id="telephone_responsable"
                                        value="{{ $collective->telephone_responsable ?? old('telephone_responsable') }}"
                                        autocomplete="tel" placeholder="XX:XXX:XX:XX">
                                    @error('telephone_responsable')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                    <label for="fonction_responsable" class="form-label">Fonction responsable<span
                                            class="text-danger mx-1">*</span></label>
                                    <input type="text" name="fonction_responsable"
                                        value="{{ $collective->fonction_responsable ?? old('fonction_responsable') }}"
                                        class="form-control form-control-sm @error('fonction_responsable') is-invalid @enderror"
                                        id="fonction_responsable" placeholder="Fonction responsable">
                                    @error('fonction_responsable')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>
                        </div>

                        <div class="text-center p-3">
                            <button type="submit" class="btn btn-primary btn-sm">Enregistrer les modifications</button>
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
