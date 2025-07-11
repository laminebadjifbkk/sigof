@extends('layout.user-layout')
@section('title', 'ONFP | DEMANDEURS INDIVIDUELS')
@section('space-work')
    @can('individuelle-view')
        <div class="pagetitle">
            {{-- <h1>Data Tables</h1> --}}
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/home') }}">Accueil</a></li>
                    <li class="breadcrumb-item">Tables</li>
                    <li class="breadcrumb-item active">Demandes individuelles</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->
        <section class="section">
            <div class="row">
                <div class="col-12">
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
                            {{-- <div class="pt-1">
                                <div class="d-flex justify-content-between align-items-center">
                                    @can('individuelle-create')
                                        <h5 class="card-title">Demandes individuelles</h5>
                                        <h5 class="card-title">Demandes individuelles totales :</h5> <span
                                            class="badge bg-primary">{{ $individuelles->count() }}</span>
                                        @can('individuelle-create')
                                            <span class="d-flex align-items-baseline">
                                                <a href="#" class="btn btn-primary btn-sm float-end" data-bs-toggle="modal"
                                                    data-bs-target="#AddIndividuelModal" title="Ajouter">Ajouter</a>
                                                <div class="filter">
                                                    <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                                            class="bi bi-three-dots"></i></a>
                                                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                                        <li>
                                                            <button type="button" class="dropdown-item btn btn-sm"
                                                                data-bs-toggle="modal" data-bs-target="#generate_rapport">Rechercher
                                                                plus</button>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </span>
                                        @endcan
                                    @endcan
                                </div>
                            </div> --}}
                            <div class="pt-1">
                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">

                                    {{-- Titre à gauche --}}
                                    <div class="d-flex align-items-center gap-2">
                                        <h6 class="mb-0 text-muted fw-semibold text-uppercase">
                                            Liste des demandes individuelles
                                        </h6>
                                    </div>

                                    {{-- Total au centre --}}
                                    @php
                                        $affichees = $individuelles?->count(); // à adapter si tu fais une pagination
                                        $total =
                                            $totalIndividuelles ?? ($individuelles?->total() ?? $individuelles?->count()); // en cas de pagination avec ->total()
                                    @endphp

                                    <div class="d-flex align-items-center gap-2 text-info fw-semibold">
                                        <i class="bi bi-list-ul me-1"></i>
                                        <span>
                                            Affichage :
                                            <span class="text-dark">{{ $affichees }}</span>
                                            sur
                                            <span class="text-dark">{{ $total }}</span> demandes
                                        </span>
                                    </div>

                                    {{-- Boutons à droite --}}
                                    @can('individuelle-create')
                                        <div class="d-flex align-items-center gap-2">
                                            <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#AddIndividuelModal">
                                                Ajouter
                                            </a>
                                            <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="modal"
                                                data-bs-target="#generate_rapport">
                                                Rechercher plus
                                            </button>
                                        </div>
                                    @endcan

                                </div>
                            </div>
                            @if ($individuelles->isNotEmpty())
                                <table class="table datatables align-middle" id="table-individuelles">
                                    <thead>
                                        <tr>
                                            <th class="text-center">N°</th>
                                            <th width="20%" class="text-center">N° CIN</th>
                                            <th width="20%">Prénom & NOM</th>
                                            <th width="15%">Date nais.</th>
                                            <th width="15%">Lieu nais.</th>
                                            <th width="20%">Module</th>
                                            <th width="5%" class="text-center">Dépôt</th>
                                            <th class="text-center">Statut</th>
                                            <th class="text-center">#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            // Liste de classes Bootstrap ou personnalisées à alterner
                                            $availableColors = [
                                                'table-primary',
                                                'table-success',
                                                'table-warning',
                                                'table-info',
                                                'table-secondary',
                                            ];
                                            $sigleColors = []; // Association sigle => couleur
                                            $colorIndex = 0;
                                        @endphp

                                        @foreach ($individuelles as $individuelle)
                                            @php
                                                $sigle = $individuelle->projet?->sigle;
                                                $rowClass = ''; // par défaut : aucune classe

                                                if (!empty($sigle)) {
                                                    if (!isset($sigleColors[$sigle])) {
                                                        $sigleColors[$sigle] =
                                                            $availableColors[$colorIndex % count($availableColors)];
                                                        $colorIndex++;
                                                    }
                                                    $rowClass = $sigleColors[$sigle];
                                                }
                                            @endphp

                                            <tr class="{{ $rowClass }}">
                                                <td style="text-align: center">{{ $individuelle?->numero }}</td>
                                                <td style="text-align: center">{{ $individuelle?->user?->cin }}</td>
                                                <td>{{ $individuelle?->user?->firstname . ' ' . $individuelle?->user?->name }}
                                                </td>
                                                <td>{{ $individuelle?->user?->date_naissance?->format('d/m/Y') }}</td>
                                                <td>{{ $individuelle?->user?->lieu_naissance }}</td>
                                                <td>{{ $individuelle?->module?->name }}</td>
                                                <td class="text-center">
                                                    @if ($individuelle?->date_depot)
                                                        {{ $individuelle?->date_depot?->format('d/m/Y') }}
                                                    @else
                                                        Aucun
                                                    @endif
                                                </td>
                                                <td>
                                                    <span
                                                        class="{{ $individuelle?->statut }}">{{ $individuelle?->statut }}</span>
                                                </td>
                                                <td>
                                                    <span class="d-flex align-items-baseline">
                                                        <a href="{{ route('individuelles.show', $individuelle) }}"
                                                            class="btn btn-primary btn-sm" title="voir détails"><i
                                                                class="bi bi-eye"></i></a>
                                                        <div class="filter">
                                                            <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                                                    class="bi bi-three-dots"></i></a>
                                                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                                                @can('individuelle-update')
                                                                    <li><a class="dropdown-item btn btn-sm"
                                                                            href="{{ route('individuelles.edit', $individuelle) }}"><i
                                                                                class="bi bi-pencil"></i>Modifier</a></li>
                                                                @endcan
                                                                @can('individuelle-delete')
                                                                    <li>
                                                                        <form
                                                                            action="{{ route('individuelles.destroy', $individuelle) }}"
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
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="alert alert-info">Aucune demande individuelle reçue pour l'instant !</div>
                            @endif
                            <!-- End Table with stripped rows -->

                        </div>
                    </div>

                </div>
            </div>

            <div class="col-12 d-flex flex-column align-items-center justify-content-center">
                <div class="modal fade" id="AddIndividuelModal" tabindex="-1">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <form method="post" action="{{ route('addIndividuelle') }}" enctype="multipart/form-data">
                                @csrf

                                <div class="card shadow-lg border-0">
                                    <div class="card-header bg-default text-center py-2 rounded-top">
                                        <h4 class="mb-0">➕ Ajouter une nouvelle demande</h4>
                                    </div>

                                    <div class="card-body row g-4 px-4">
                                        <div class="row g-3">
                                            <div class="col-12 col-md-12 col-lg-8 col-sm-12 col-xs-12 col-xxl-8">
                                                <label for="module" class="form-label">Formation sollicitée (module)<span
                                                        class="text-danger mx-1">*</span></label>
                                                <input type="text" name="module" value="{{ old('module_name') }}"
                                                    class="form-control form-control-sm @error('module_name') is-invalid @enderror"
                                                    id="module_name" placeholder="Formation sollicitée" autofocus>
                                                <div id="countryList"></div>
                                                {{ csrf_field() }}
                                                @error('module')
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                                <label for="departement" class="form-label">Lieu de formation<span
                                                        class="text-danger mx-1">*</span></label>
                                                <select name="departement"
                                                    class="form-select form-select-sm @error('departement') is-invalid @enderror"
                                                    aria-label="Select" id="select-field-departement-indiv"
                                                    data-placeholder="Choisir la localité">
                                                    <option value="{{ old('departement') }}">{{ old('departement') }}
                                                    </option>
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
                                                <label for="civilite" class="form-label">Civilité<span
                                                        class="text-danger mx-1">*</span></label>
                                                <select name="civilite"
                                                    class="form-select form-select-smform-select-form-select-smsm @error('civilite') is-invalid @enderror"
                                                    aria-label="Select" id="select-field-civilite-indiv"
                                                    data-placeholder="Choisir civilité">
                                                    <option value="{{ old('civilite') }}">
                                                        {{ old('civilite') }}
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

                                            <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                                <label for="cin" class="form-label">N° CIN<span
                                                        class="text-danger mx-1">*</span></label>
                                                <input name="cin" type="text"
                                                    class="form-control form-control-sm @error('cin') is-invalid @enderror"
                                                    id="cin" value="{{ old('cin') }}" autocomplete="off"
                                                    placeholder="Ex: 1 099 2005 00012" minlength="16" maxlength="17"
                                                    required>
                                                @error('cin')
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                                <label for="firstname" class="form-label">Prénom<span
                                                        class="text-danger mx-1">*</span></label>
                                                <input type="text" name="firstname" value="{{ old('firstname') }}"
                                                    class="form-control form-control-sm @error('firstname') is-invalid @enderror"
                                                    id="firstname" placeholder="prénom">
                                                @error('firstname')
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                                <label for="lastname" class="form-label">Nom<span
                                                        class="text-danger mx-1">*</span></label>
                                                <input type="text" name="lastname" value="{{ old('lastname') }}"
                                                    class="form-control form-control-sm @error('lastname') is-invalid @enderror"
                                                    id="lastname" placeholder="nom">
                                                @error('lastname')
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                                <label for="date_naissance" class="form-label">Date naissance<span
                                                        class="text-danger mx-1">*</span></label>
                                                <input type="text" name="date_naissance"
                                                    value="{{ old('date_naissance') }}"
                                                    class="form-control form-control-sm @error('date_naissance') is-invalid @enderror"
                                                    id="datepicker" placeholder="JJ/MM/AAAA" autocomplete="bday">
                                                @error('date_naissance')
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                                <label for="name" class="form-label">Lieu naissance<span
                                                        class="text-danger mx-1">*</span></label>
                                                <input name="lieu_naissance" type="text"
                                                    class="form-control form-control-sm @error('lieu_naissance') is-invalid @enderror"
                                                    id="lieu_naissance" value="{{ old('lieu_naissance') }}"
                                                    autocomplete="lieu_naissance" placeholder="Lieu naissance">
                                                @error('lieu_naissance')
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
                                                    id="adresse" placeholder="adresse">
                                                @error('adresse')
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                                <label for="email" class="form-label">Email<span
                                                        class="text-danger mx-1">*</span></label>
                                                <div class="input-group has-validation">
                                                    {{-- <span class="input-group-text" id="email">@</span> --}}
                                                    <input type="email" name="email" value="{{ old('email') }}"
                                                        class="form-control form-control-sm @error('email') is-invalid @enderror"
                                                        id="email" placeholder="email">
                                                    @error('email')
                                                        <span class="invalid-feedback" role="alert">
                                                            <div>{{ $message }}</div>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                                <label for="telephone" class="form-label">Téléphone personnel<span
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
                                                <label for="telephone_secondaire" class="form-label">Téléphone
                                                    secondaire</label>
                                                <input name="telephone_secondaire" type="text" maxlength="12"
                                                    class="form-control form-control-sm @error('telephone_secondaire') is-invalid @enderror"
                                                    id="telephone_secondaire" value="{{ old('telephone_secondaire') }}"
                                                    autocomplete="tel" placeholder="XX:XXX:XX:XX">
                                                @error('telephone_secondaire')
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                                <label for="situation_familiale" class="form-label">Situation
                                                    familiale</label>
                                                <select name="situation_familiale"
                                                    class="form-select form-select-sm @error('situation_familiale') is-invalid @enderror"
                                                    aria-label="Select" id="select-field-familiale-indiv"
                                                    data-placeholder="Choisir situation familiale">
                                                    <option value="{{ old('situation_familiale') }}">
                                                        {{ old('situation_familiale') }}
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

                                            <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                                <label for="situation_profesionnelle" class="form-label">Situation
                                                    profesionnelle</label>
                                                <select name="situation_professionnelle"
                                                    class="form-select form-select-sm @error('situation_professionnelle') is-invalid @enderror"
                                                    aria-label="Select" id="select-field-professionnelle-indiv"
                                                    data-placeholder="Choisir situation professionnelle">
                                                    <option value="{{ old('situation_professionnelle') }}">
                                                        {{ old('situation_professionnelle') }}
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
                                                    <option value="Chercheur emploi">
                                                        Chercheur emploi
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

                                            <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                                <label for="date_depot" class="form-label">Date depot<span
                                                        class="text-danger mx-1">*</span></label>
                                                <input type="datetime-local" name="date_depot"
                                                    value="{{ old('date_depot') }}"
                                                    class="datepicker form-control form-control-sm @error('date_depot') is-invalid @enderror"
                                                    id="date_depot" placeholder="yyyy-mm-dd HH:MM">
                                                @error('date_depot')
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div>

                                            {{-- <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                        <label for="autre_module" class="form-label">Si autre formation ? précisez</label>
                                        <input type="text" name="autre_module" value="{{ old('autre_module') }}"
                                            class="form-control form-control-sm @error('autre_module') is-invalid @enderror"
                                            id="autre_module" placeholder="autre diplôme académique">
                                        @error('autre_module')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div> --}}

                                            <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                                <label for="Niveau étude" class="form-label">Niveau étude<span
                                                        class="text-danger mx-1">*</span></label>
                                                <select name="niveau_etude"
                                                    class="form-select form-select-sm @error('niveau_etude') is-invalid @enderror"
                                                    aria-label="Select" id="select-field-niveau_etude-indiv"
                                                    data-placeholder="Choisir niveau étude">
                                                    <option value="{{ old('niveau_etude') }}">
                                                        {{ old('niveau_etude') }}
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

                                            <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                                <label for="diplome_academique" class="form-label">Diplôme académique<span
                                                        class="text-danger mx-1">*</span></label>
                                                <select name="diplome_academique"
                                                    class="form-select form-select-sm @error('diplome_academique') is-invalid @enderror"
                                                    aria-label="Select" id="select-field-diplome_academique-indiv"
                                                    data-placeholder="Choisir diplôme académique">
                                                    <option value="{{ old('diplome_academique') }}">
                                                        {{ old('diplome_academique') }}
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

                                            <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                                <label for="etablissement_academique" class="form-label">Etablissement
                                                    académique</label>
                                                <input type="text" name="etablissement_academique"
                                                    value="{{ old('etablissement_academique') }}"
                                                    class="form-control form-control-sm @error('etablissement_academique') is-invalid @enderror"
                                                    id="etablissement_academique" placeholder="Etablissement obtention">
                                                @error('etablissement_academique')
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                                <label for="autre_diplome_academique" class="form-label">Si autre ?
                                                    précisez</label>
                                                <input type="text" name="autre_diplome_academique"
                                                    value="{{ old('autre_diplome_academique') }}"
                                                    class="form-control form-control-sm @error('autre_diplome_academique') is-invalid @enderror"
                                                    id="autre_diplome_academique" placeholder="autre diplôme académique">
                                                @error('autre_diplome_academique')
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                                <label for="option_diplome_academique" class="form-label">Option du
                                                    diplôme</label>
                                                <input type="text" name="option_diplome_academique"
                                                    value="{{ old('option_diplome_academique') }}"
                                                    class="form-control form-control-sm @error('option_diplome_academique') is-invalid @enderror"
                                                    id="option_diplome_academique" placeholder="Ex: Mathématiques">
                                                @error('option_diplome_academique')
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                                <label for="diplome_pro" class="form-label">Diplôme professionnel<span
                                                        class="text-danger mx-1">*</span></label>
                                                <select name="diplome_professionnel"
                                                    class="form-select form-select-sm @error('diplome_professionnel') is-invalid @enderror"
                                                    aria-label="Select" id="select-field-diplome_professionnel-indiv"
                                                    data-placeholder="Choisir diplôme professionnel">
                                                    <option value="{{ old('diplome_professionnel') }}">
                                                        {{ old('diplome_professionnel') }}
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

                                            <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                                <label for="autre_diplome_professionnel" class="form-label">Si autre ?
                                                    précisez</label>
                                                <input type="text" name="autre_diplome_professionnel"
                                                    value="{{ old('autre_diplome_professionnel') }}"
                                                    class="form-control form-control-sm @error('autre_diplome_professionnel') is-invalid @enderror"
                                                    id="autre_diplome_professionnel"
                                                    placeholder="autre diplôme professionnel ou attestations">
                                                @error('autre_diplome_professionnel')
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                                <label for="etablissement_professionnel" class="form-label">Etablissement
                                                    professionnel</label>
                                                <input type="text" name="etablissement_professionnel"
                                                    value="{{ old('etablissement_professionnel') }}"
                                                    class="form-control form-control-sm @error('etablissement_professionnel') is-invalid @enderror"
                                                    id="etablissement_professionnel" placeholder="Etablissement obtention">
                                                @error('etablissement_professionnel')
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                                <label for="specialite_diplome_professionnel"
                                                    class="form-label">Spécialité</label>
                                                <input type="text" name="specialite_diplome_professionnel"
                                                    value="{{ old('specialite_diplome_professionnel') }}"
                                                    class="form-control form-control-sm @error('specialite_diplome_professionnel') is-invalid @enderror"
                                                    id="specialite_diplome_professionnel" placeholder="Ex: électricité">
                                                @error('specialite_diplome_professionnel')
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="col-12 col-md-12 col-lg-8 col-sm-12 col-xs-12 col-xxl-8">
                                                <label for="projet_poste_formation" class="form-label">Votre projet après la
                                                    formation<span class="text-danger mx-1">*</span></label>
                                                <select name="projet_poste_formation"
                                                    class="form-select form-select-sm @error('projet_poste_formation') is-invalid @enderror"
                                                    aria-label="Select" id="select-field-projet_poste_formation-indiv"
                                                    data-placeholder="Choisir projet">
                                                    <option value="{{ old('projet_poste_formation') }}">
                                                        {{ old('projet_poste_formation') }}
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

                                            <div class="col-12">
                                                <label for="qualification" class="form-label">Qualification et autres
                                                    diplômes</label>
                                                <textarea name="qualification" id="qualification" rows="1"
                                                    class="form-control form-control-sm @error('qualification') is-invalid @enderror"
                                                    placeholder="Qualification et autres diplômes">{{ old('qualification') }}</textarea>
                                                @error('qualification')
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="col-12">
                                                <label for="experience" class="form-label">Expériences et stages</label>
                                                <textarea name="experience" id="experience" rows="1"
                                                    class="form-control form-control-sm @error('experience') is-invalid @enderror"
                                                    placeholder="Expériences ou stages">{{ old('experience') }}</textarea>
                                                @error('experience')
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="col-12">
                                                <label for="projetprofessionnel" class="form-label">Informations
                                                    complémentaires
                                                    sur
                                                    le projet
                                                    professionnel<span class="text-danger mx-1">*</span></label>
                                                <textarea name="projetprofessionnel" id="projetprofessionnel" rows="5"
                                                    class="form-control form-control-sm @error('projetprofessionnel') is-invalid @enderror"
                                                    placeholder="Si vous disposez déjà d'un projet professionnel, merci d'écrire son résumé en quelques lignes">{{ old('projetprofessionnel') }}</textarea>
                                                @error('projetprofessionnel')
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer d-flex justify-content-end gap-2 p-3 bg-light border-top">
                                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
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
            <div class="modal fade" id="generate_rapport" tabindex="-1" role="dialog"
                aria-labelledby="generate_rapportLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Générer une recherche<span class="text-danger mx-1">*</span></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form method="post" action="{{ route('individuelles.report') }}">
                            @csrf
                            <div class="modal-body">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="firstname" class="form-label">Prénom</label>
                                                    <input type="text" name="firstname" value="{{ old('firstname') }}"
                                                        class="form-control form-control-sm @error('firstname') is-invalid @enderror"
                                                        id="firstname" placeholder="Prénom">
                                                    @error('firstname')
                                                        <span class="invalid-feedback" role="alert">
                                                            <div>{{ $message }}</div>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="name" class="form-label">Nom</label>
                                                    <input type="text" name="name" value="{{ old('name') }}"
                                                        class="form-control form-control-sm @error('name') is-invalid @enderror"
                                                        id="name" placeholder="Nom">
                                                    @error('name')
                                                        <span class="invalid-feedback" role="alert">
                                                            <div>{{ $message }}</div>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="cin" class="form-label">N° CIN</label>
                                                    <input name="cin" type="text"
                                                        class="form-control form-control-sm @error('cin') is-invalid @enderror"
                                                        id="cin2" value="{{ old('cin') }}" autocomplete="off"
                                                        placeholder="Ex: 1 099 2005 00012" minlength="16" maxlength="17">
                                                    @error('cin')
                                                        <span class="invalid-feedback" role="alert">
                                                            <div>{{ $message }}</div>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="telephone" class="form-label">Téléphone</label>
                                                    <input name="telephone" type="text" maxlength="12"
                                                        class="form-control form-control-sm @error('telephone') is-invalid @enderror"
                                                        id="telephone_responsable" value="{{ old('telephone') }}"
                                                        autocomplete="tel" placeholder="XX:XXX:XX:XX">
                                                    @error('telephone')
                                                        <span class="invalid-feedback" role="alert">
                                                            <div>{{ $message }}</div>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="email" class="form-label">Email</label>
                                                    <input type="email" name="email" value="{{ old('email') }}"
                                                        class="form-control form-control-sm @error('email') is-invalid @enderror"
                                                        id="email" placeholder="email@email.com">
                                                    @error('email')
                                                        <span class="invalid-feedback" role="alert">
                                                            <div>{{ $message }}</div>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary btn-sm"
                                            data-bs-dismiss="modal">Fermer</button>
                                        <div class="text-center">
                                            <button type="submit"
                                                class="btn btn-primary btn-block submit_rapport btn-sm">Rechercher</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    @endcan
@endsection

@push('scripts')
    <script>
        new DataTable('#table-individuelles', {
            layout: {
                topStart: {
                    buttons: ['csv', 'excel', 'print'],
                }
            },
            "order": [
                [0, 'DESC']
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
@endpush
