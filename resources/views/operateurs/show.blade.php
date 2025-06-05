@extends('layout.user-layout')
@section('title', 'OPERATEUR | ' . remove_accents_uppercase($operateur?->user?->username))
@section('space-work')
    @can('operateur-show')
        <section
            class="section profile min-vh-0 d-flex flex-column align-items-center justify-content-center py-0 section profile">
            <div class="container-fluid">
                <div class="pagetitle">
                    {{-- <h1>Data Tables</h1> --}}
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Accueil</a></li>
                            <li class="breadcrumb-item">Tables</li>
                            <li class="breadcrumb-item active">{{ $operateur?->user?->username }}</li>
                        </ol>
                    </nav>
                </div><!-- End Page Title -->
                <div class="row justify-content-center">
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
                    <div class="flex items-center gap-4">
                        <div class="card">
                            <div class="card-body">
                                <ul class="nav nav-tabs nav-tabs-bordered">
                                    @can('operateur-view')
                                        <li class="nav-item">
                                            <span class="nav-link"><a href="{{ route('operateurs.index', $operateur?->id) }}"
                                                    class="btn btn-secondary btn-sm" title="retour"><i
                                                        class="bi bi-arrow-counterclockwise"></i></a>
                                            </span>
                                        </li>
                                    @endcan
                                    <li class="nav-item">
                                        <button class="nav-link" data-bs-toggle="tab"
                                            data-bs-target="#profile-overview">Opérateur</button>
                                    </li>

                                    <li class="nav-item">
                                        <button class="nav-link active" data-bs-toggle="tab"
                                            data-bs-target="#module-overview">Module
                                        </button>
                                    </li>

                                    <li class="nav-item">
                                        <button class="nav-link" data-bs-toggle="tab"
                                            data-bs-target="#references-overview">Références</button>
                                    </li>

                                    <li class="nav-item">
                                        <button class="nav-link" data-bs-toggle="tab"
                                            data-bs-target="#equipement-overview">Equipements</button>
                                    </li>

                                    <li class="nav-item">
                                        <button class="nav-link" data-bs-toggle="tab"
                                            data-bs-target="#formateur-overview">Formateurs</button>
                                    </li>

                                    {{-- <li class="nav-item">
                                        <button class="nav-link" data-bs-toggle="tab"
                                            data-bs-target="#localites-overview">Localités</button>
                                    </li> --}}
                                    @foreach ($operateur?->formations as $item)
                                    @endforeach
                                    @if (!empty($item))
                                        <li class="nav-item">
                                            <button class="nav-link" data-bs-toggle="tab"
                                                data-bs-target="#formation-overview">Formations</button>
                                        </li>
                                    @endif

                                </ul>
                                <div class="d-flex justify-content-between align-items-center">
                                </div>
                                {{-- Détail opérateur --}}
                                <div class="tab-content pt-0">
                                    <div class="tab-pane fade profile-overview pt-3" id="profile-overview">
                                        <form method="post" action="#" enctype="multipart/form-data" class="row g-3">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h5 class="card-title">Opérateur</h5>
                                            </div>
                                            <div class="col-12 col-md-12 col-lg-12 mb-0">
                                                <div class="label">Raison sociale</div>
                                                <div>{{ $operateur?->user?->operateur }}</div>
                                            </div>
                                            <div class="col-12 col-md-4 col-lg-4 mb-0">
                                                <div class="label">Sigle</div>
                                                <div>{{ $operateur?->user?->username }}</div>
                                            </div>
                                            <div class="col-12 col-md-4 col-lg-4 mb-0">
                                                <div class="label">Région</div>
                                                <div>{{ $operateur?->region?->nom }}</div>
                                            </div>

                                            @if (!empty($operateur?->departement?->nom))
                                                <div class="col-12 col-md-4 col-lg-4 mb-0">
                                                    <div class="label">Département</div>
                                                    <div>{{ $operateur?->departement?->nom }}</div>
                                                </div>
                                            @endif

                                            <div class="col-12 col-md-4 col-lg-4 mb-0">
                                                <div class="label">Numéro agrément</div>
                                                <div>{{ $operateur?->numero_agrement }}</div>
                                            </div>
                                            <div class="col-12 col-md-4 col-lg-4 mb-0">
                                                <div class="label">Adresse email</div>
                                                <div><a
                                                        href="mailto:{{ $operateur?->user?->email }}">{{ $operateur?->user?->email }}</a>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-4 col-lg-4 mb-0">
                                                <div class="label">Téléphone fixe</div>
                                                <div><a
                                                        href="tel:+221{{ $operateur?->user?->fixe }}">{{ $operateur?->user?->fixe }}</a>
                                                </div>
                                            </div>
                                            @if (!empty($operateur?->user?->bp))
                                                <div class="col-12 col-md-4 col-lg-4 mb-0">
                                                    <div class="label">Boite postale</div>
                                                    <div>{{ $operateur?->user?->bp }}</div>
                                                </div>
                                            @endif
                                            @if (!empty($operateur?->user?->categorie))
                                                <div class="col-12 col-md-4 col-lg-4 mb-0">
                                                    <div class="label">Catégorie</div>
                                                    <div>{{ $operateur?->user?->categorie }}</div>
                                                </div>
                                            @endif

                                            @if (!empty($operateur?->statut))
                                                <div class="col-12 col-md-4 col-lg-4 mb-0">
                                                    <div class="label">Statut juridique</div>
                                                    <div>{{ $operateur?->statut }}</div>
                                                </div>
                                            @endif

                                            @if (!empty($operateur?->autre_statut))
                                                <div class="col-12 col-md-4 col-lg-4 mb-0">
                                                    <div class="label">Autre statut</div>
                                                    <div>{{ $operateur?->autre_statut }}</div>
                                                </div>
                                            @endif

                                            @if (!empty($operateur?->user?->adresse))
                                                <div class="col-12 col-md-4 col-lg-4 mb-0">
                                                    <div class="label">Adrese</div>
                                                    <div>{{ $operateur?->user?->adresse }}</div>
                                                </div>
                                            @endif

                                            @if (!empty($operateur?->user?->rccm))
                                                <div class="col-12 col-md-4 col-lg-4 mb-0">
                                                    <div class="label">RCCM/Ninea</div>
                                                    <div>{{ $operateur?->user?->rccm }}</div>
                                                </div>
                                            @endif

                                            @if (!empty($operateur?->user?->ninea))
                                                <div class="col-12 col-md-4 col-lg-4 mb-0">
                                                    <div class="label">N° RCCM/Ninea</div>
                                                    <div>{{ $operateur?->user?->ninea }}</div>
                                                </div>
                                            @endif

                                            @if (!empty($operateur?->debut_quitus))
                                                <div class="col-12 col-md-4 col-lg-4 mb-0">
                                                    <div class="label">Quitus</div>
                                                    <div>
                                                        @if (!empty($operateur?->debut_quitus))
                                                            <a class="btn btn-outline-secondary btn-sm"
                                                                title="télécharger le quitus" target="_blank"
                                                                href="{{ asset($operateur?->getQuitus()) }}">
                                                                <i class="bi bi-file-image"></i>
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif

                                            @if (!empty($operateur?->debut_quitus))
                                                <div class="col-12 col-md-4 col-lg-4">
                                                    <div class="label">Durée quitus</div>
                                                    <div>
                                                        {{ $operateur?->debut_quitus?->diffForHumans(['parts' => 3, 'join' => ', ']) }}
                                                    </div>
                                                </div>
                                            @endif

                                            <h5 class="card-title">Personne morale</h5>

                                            @if (!empty($operateur?->user?->civilite))
                                                <div class="col-12 col-md-4 col-lg-4">
                                                    <div class="label">Civilité</div>
                                                    <div>{{ $operateur?->user?->civilite }}</div>
                                                </div>
                                            @endif

                                            @if (!empty($operateur?->user?->firstname))
                                                <div class="col-12 col-md-4 col-lg-4 mb-0">
                                                    <div class="label">Prénom</div>
                                                    <div>{{ $operateur?->user?->firstname }}</div>
                                                </div>
                                            @endif

                                            @if (!empty($operateur?->user?->name))
                                                <div class="col-12 col-md-4 col-lg-4 mb-0">
                                                    <div class="label">Nom</div>
                                                    <div>{{ $operateur?->user?->name }}</div>
                                                </div>
                                            @endif

                                            @if (!empty($operateur?->user?->email_responsable))
                                                <div class="col-12 col-md-4 col-lg-4 mb-0">
                                                    <div class="label">Email</div>
                                                    <div><a
                                                            href="mailto:{{ $operateur?->user?->email_responsable }}">{{ $operateur?->user?->email_responsable }}</a>
                                                    </div>
                                                </div>
                                            @endif

                                            @if (!empty($operateur?->user?->fonction_responsable))
                                                <div class="col-12 col-md-4 col-lg-4 mb-0">
                                                    <div class="label">Téléphone</div>
                                                    <div><a
                                                            href="tel:+221{{ $operateur?->user?->fonction_responsable }}">{{ $operateur?->user?->telephone }}</a>
                                                    </div>
                                                </div>
                                            @endif

                                            @if (!empty($operateur?->user?->fonction_responsable))
                                                <div class="col-12 col-md-4 col-lg-4 mb-0">
                                                    <div class="label">Fonction</div>
                                                    <div>{{ $operateur?->user?->fonction_responsable }}
                                                    </div>
                                                </div>
                                            @endif

                                            <div class="text-center">
                                                <a href="{{ route('operateurs.edit', $operateur) }}"
                                                    class="btn btn-outline-primary btn-sm" title="Modifier">Modifier cet
                                                    opérateur</a>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                {{-- Détail représentant --}}
                                <div class="tab-content pt-2">
                                    <div class="tab-pane fade profile-overview pt-3" id="references-overview">
                                        <form method="post" action="#" enctype="multipart/form-data" class="row g-3">
                                            @csrf
                                            @method('PUT')
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h5 class="card-title">EXPERIENCES ET REFERENCES PROFESSIONNELLES</h5>
                                                @can('devenir-operateur-agrement-ouvert')
                                                    @can('agrement-visible-par-op')
                                                        <h5 class="card-title">
                                                            <a href="{{ route('showReference', ['id' => $operateur?->id]) }}"
                                                                class="btn btn-outline-primary float-end btn-rounded btn-sm"
                                                                target="_blank">Ajouter</a>
                                                        </h5>
                                                    @endcan
                                                @endcan
                                            </div>

                                            <table
                                                class="table table-bordered table-hover datatables align-middle justify-content-center table-borderless">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">ORGANISME</th>
                                                        {{-- <th scope="col">CONTACTS</th> --}}
                                                        <th scope="col">PERIODE</th>
                                                        <th scope="col">DESCRIPTION DES INTERVENTIONS</th>
                                                        {{-- <th class="col"><i class="bi bi-gear"></i></th> --}}
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $i = 1; ?>
                                                    @foreach ($operateur?->operateureferences as $operateureference)
                                                        <tr>
                                                            <td>{{ $operateureference?->organisme }}</td>
                                                            {{-- <td>{{ $operateureference?->contact }}</td> --}}
                                                            <td>{{ $operateureference?->periode }}</td>
                                                            <td>{{ $operateureference?->description }}</td>
                                                            {{-- <td style="text-align: center;">
                                                            <span class="d-flex mt-2 align-items-baseline"><a
                                                                    href="#"
                                                                    class="btn btn-warning btn-sm mx-1"
                                                                    title="Voir détails">
                                                                    <i class="bi bi-eye"></i></a>
                                                                <div class="filter">
                                                                    <a class="icon" href="#"
                                                                        data-bs-toggle="dropdown"><i
                                                                            class="bi bi-three-dots"></i></a>
                                                                    <ul
                                                                        class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                                                        <li>
                                                                            <button type="button"
                                                                                class="dropdown-item btn btn-sm mx-1"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#EditoperateureferenceModal{{ $operateureference->id }}">
                                                                                <i class="bi bi-pencil"
                                                                                    title="Modifier"></i> Modifier
                                                                            </button>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </span>
                                                        </td> --}}
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </form>
                                    </div>
                                </div>
                                <div class="tab-content pt-2">
                                    <div class="tab-pane fade profile-overview pt-3" id="equipement-overview">
                                        <form method="post" action="#" enctype="multipart/form-data" class="row g-3">
                                            @csrf
                                            @method('PUT')
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h5 class="card-title">INFRASTRUCTURES / EQUIPEMENTS</h5>
                                                @can('devenir-operateur-agrement-ouvert')
                                                    @can('agrement-visible-par-op')
                                                        <h5 class="card-title">
                                                            <a href="{{ route('showEquipement', ['id' => $operateur?->id]) }}"
                                                                class="btn btn-outline-primary float-end btn-rounded btn-sm"
                                                                target="_blank">
                                                                <i class="bi bi-plus" title="Ajouter, Modifier, Supprimer"></i> </a>
                                                            {{-- <button type="button" class="btn btn-outline-primary btn-sm"
                                                    data-bs-toggle="modal" data-bs-target="#AddRefModal">
                                                    <i class="bi bi-plus" title="Ajouter une référence"></i>
                                                </button> --}}
                                                        </h5>
                                                    @endcan
                                                @endcan
                                            </div>
                                            <table
                                                class="table table-bordered table-hover datatables align-middle justify-content-center table-borderless">
                                                <thead>
                                                    <tr>
                                                        <th>DESIGNATION</th>
                                                        <th class="text-center">QUANTITE</th>
                                                        <th class="text-center">ETAT</th>
                                                        <th class="text-center">TYPE</th>
                                                        {{-- <th class="text-center"><i class="bi bi-gear"></i></th> --}}
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $i = 1; ?>
                                                    @foreach ($operateur?->operateurequipements as $operateurequipement)
                                                        <tr>
                                                            <td>{{ $operateurequipement->designation }}</td>
                                                            <td style="text-align: center;">
                                                                {{ $operateurequipement->quantite }}</td>
                                                            <td style="text-align: center;">{{ $operateurequipement->etat }}
                                                            </td>
                                                            <td style="text-align: center;">{{ $operateurequipement->type }}
                                                            </td>
                                                            {{-- <td>
                                                            <span class="d-flex align-items-baseline">
                                                                <a href="{{ route('operateurequipements.show', $operateurequipement->id) }}"
                                                                    class="btn btn-primary btn-sm" title="voir détails"><i
                                                                        class="bi bi-eye"></i></a>
                                                                <div class="filter">
                                                                    <a class="icon" href="#"
                                                                        data-bs-toggle="dropdown"><i
                                                                            class="bi bi-three-dots"></i></a>
                                                                    <ul
                                                                        class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                                                        <button class="dropdown-item btn btn-sm mx-1"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#EditoperateurequipementModal{{ $operateurequipement->id }}">Modifier
                                                                        </button>
                                                                        <form
                                                                            action="{{ route('operateurequipements.destroy', $operateurequipement->id) }}"
                                                                            method="post">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="submit"
                                                                                class="dropdown-item show_confirm"
                                                                                title="Supprimer">Supprimer</button>
                                                                        </form>
                                                                    </ul>
                                                                </div>
                                                            </span>
                                                        </td> --}}
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </form>
                                    </div>
                                </div>
                                <div class="tab-content pt-2">
                                    <div class="tab-pane fade profile-overview pt-3" id="formateur-overview">
                                        <form method="post" action="#" enctype="multipart/form-data" class="row g-3">
                                            @csrf
                                            @method('PUT')
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h5 class="card-title">FORMATEURS</h5>
                                                @can('devenir-operateur-agrement-ouvert')
                                                    @can('agrement-visible-par-op')
                                                        <h5 class="card-title">
                                                            <a href="{{ route('showFormateur', ['id' => $operateur?->id]) }}"
                                                                class="btn btn-outline-primary float-end btn-rounded btn-sm"
                                                                target="_blank">
                                                                <i class="bi bi-plus" title="Ajouter, Modifier, Supprimer"></i> </a>
                                                            {{-- <button type="button" class="btn btn-outline-primary btn-sm"
                                                    data-bs-toggle="modal" data-bs-target="#AddRefModal">
                                                    <i class="bi bi-plus" title="Ajouter une référence"></i>
                                                </button> --}}
                                                        </h5>
                                                    @endcan
                                                @endcan
                                            </div>
                                            <table
                                                class="table table-bordered table-hover datatables align-middle justify-content-center">
                                                <thead>
                                                    <tr>
                                                        <th>PRENOM(S) ET NOM</th>
                                                        <th>CHAMPS PROFESSIONNELS</th>
                                                        <th class="text-center">NOMBRE D'ANNEES D'EXPERIENCE</th>
                                                        <th>REFERENCES</th>
                                                        {{-- <th class="text-center"><i class="bi bi-gear"></i></th> --}}
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $i = 1; ?>
                                                    @foreach ($operateur?->operateurformateurs as $operateurformateur)
                                                        <tr>
                                                            <td>{{ $operateurformateur->name }}</td>
                                                            <td>{{ $operateurformateur->domaine }}</td>
                                                            <td style="text-align: center;">
                                                                {{ $operateurformateur->nbre_annees_experience }}</td>
                                                            <td>{{ $operateurformateur->references }}</td>
                                                            {{-- <td style="text-align: center;">
                                                            <span class="d-flex align-items-baseline">
                                                                <a href="{{ route('operateurformateurs.show', $operateurformateur->id) }}"
                                                                    class="btn btn-primary btn-sm" title="voir détails"><i
                                                                        class="bi bi-eye"></i></a>
                                                                <div class="filter">
                                                                    <a class="icon" href="#"
                                                                        data-bs-toggle="dropdown"><i
                                                                            class="bi bi-three-dots"></i></a>
                                                                    <ul
                                                                        class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                                                        <button class="dropdown-item btn btn-sm mx-1"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#EditoperateurformateurModal{{ $operateurformateur->id }}">Modifier
                                                                        </button>
                                                                        <form
                                                                            action="{{ route('operateurformateurs.destroy', $operateurformateur->id) }}"
                                                                            method="post">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="submit"
                                                                                class="dropdown-item show_confirm"
                                                                                title="Supprimer">Supprimer</button>
                                                                        </form>
                                                                    </ul>
                                                                </div>
                                                            </span>
                                                        </td> --}}
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </form>
                                    </div>
                                </div>
                                <div class="tab-content pt-2">
                                    <div class="tab-pane fade profile-overview pt-3" id="localites-overview">
                                        <form method="post" action="#" enctype="multipart/form-data" class="row g-3">
                                            @csrf
                                            @method('PUT')
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h5 class="card-title">LOCALITES</h5>
                                                @can('devenir-operateur-agrement-ouvert')
                                                    @can('agrement-visible-par-op')
                                                        <h5 class="card-title">
                                                            <a href="{{ route('showLocalite', ['id' => $operateur?->id]) }}"
                                                                class="btn btn-outline-primary float-end btn-rounded btn-sm"
                                                                target="_blank">
                                                                <i class="bi bi-plus" title="Ajouter, Modifier, Supprimer"></i> </a>
                                                        </h5>
                                                    @endcan
                                                @endcan
                                            </div>
                                            <table
                                                class="table table-bordered table-hover datatables align-middle justify-content-center">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center">N°</th>
                                                        <th>LOCALITE</th>
                                                        <th>REGION</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $i = 1; ?>
                                                    @foreach ($operateur?->operateurlocalites as $operateurlocalite)
                                                        <tr>
                                                            <td style="text-align: center;">{{ $i++ }}</td>
                                                            <td>{{ $operateurlocalite->name }}</td>
                                                            <td>{{ $operateurlocalite->region }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </form>
                                    </div>
                                </div>
                                {{-- Détail Modules --}}
                                {{-- class show et active pour l'affichage par défaut --}}
                                <div class="tab-content pt-2">
                                    <div class="tab-pane fade show active profile-overview pt-3" id="module-overview">
                                        @can('agrement-visible-par-op')
                                            <form method="post" action="{{ url('operateurmodules') }}"
                                                enctype="multipart/form-data" class="row g-3">
                                                @csrf
                                                @can('devenir-operateur-agrement-ouvert')
                                                    <div class="col-12 col-md-12 col-lg-12 mb-0">
                                                        <table class="table table-bordered table-hover" id="dynamicAddRemove">

                                                            <tr>
                                                                <th>MODULE OU SPECIALITE<span class="text-danger mx-1">*</span></th>
                                                                <th>NIVEAU QUALIFICATION<span class="text-danger mx-1">*</span>
                                                                </th>
                                                            </tr>
                                                            <tr>
                                                                <input type="hidden" name="operateur"
                                                                    value="{{ $operateur?->id }}">
                                                                <td>
                                                                    <input type="text" name="module" id="module_operateur"
                                                                        class="form-control form-control-sm @error('module') is-invalid @enderror"
                                                                        placeholder="Module ou spécialité" />
                                                                    <div id="moduleList"></div>
                                                                    {{ csrf_field() }}
                                                                    <p class="small fst-italic">
                                                                        <small>{{ __('Le nombre de modules est limité à deux') }}</small>
                                                                        <small>
                                                                            {{ __(' sauf pour les établissements publics ') }}</small>
                                                                    </p>
                                                                </td>
                                                                <td><input type="text" name="categorie"
                                                                        placeholder="Niveau de qualification"
                                                                        class="form-control form-control-sm @error('categorie') is-invalid @enderror" />
                                                                    <p class="small fst-italic">
                                                                        <small>{{ __("Préciser le niveau de qualification,l'emploi ou le métier correspondant lorsqu'il s'agit d'une pré-qualification ou qualification") }}</small><br>
                                                                    </p>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th>DOMAINE<span class="text-danger mx-1">*</span></th>
                                                                <th>QUALIFICATION CORRESPONDANTE
                                                                    <span class="text-danger mx-1">*</span>
                                                                </th>
                                                            </tr>
                                                            <tr>
                                                                <td><input type="text" name="domaine"
                                                                        placeholder="Domaine d'intervention"
                                                                        class="form-control form-control-sm @error('domaine') is-invalid @enderror" />
                                                                </td>
                                                                <td>
                                                                    <select name="niveau_qualification"
                                                                        class="form-select form-select-sm @error('niveau_qualification') is-invalid @enderror"
                                                                        aria-label="Select" id="select-field-civilite"
                                                                        data-placeholder="Choisir qualification">
                                                                        <option value="">
                                                                            {{ old('niveau_qualification') }}
                                                                        </option>
                                                                        <option value="Initiation">
                                                                            Initiation
                                                                        </option>
                                                                        <option value="Pré-qualification">
                                                                            Pré-qualification
                                                                        </option>
                                                                        <option value="Qualification">
                                                                            Qualification
                                                                        </option>
                                                                    </select>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                        <div class="text-center">
                                                            <button type="submit" class="btn btn-outline-success btn-sm"><i
                                                                    class="bi bi-printer"></i> Enregistrer</button>
                                                        </div>
                                                    </div>
                                                @endcan

                                            </form><!-- End module -->
                                        @endcan

                                        <div class="col-12 col-md-12 col-lg-12 mb-0">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h5 class="card-title">DOMAINES DE COMPETENCES OU PROGRAMMES DE FORMATION</h5>
                                                <span class="card-title d-flex align-items-baseline">Statut
                                                    :&nbsp;
                                                    <span class="{{ $operateur?->statut_agrement }} text-white btn-sm">
                                                        {{ $operateur?->statut_agrement }}</span>
                                                    @can('operateur-view')
                                                        <div class="filter">
                                                            <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                                                    class="bi bi-three-dots"></i></a>
                                                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                                                <li>
                                                                    <form action="{{ route('ficheSyntheseOperateur') }}"
                                                                        method="post" target="_blank">
                                                                        @csrf
                                                                        <input type="hidden" name="id"
                                                                            value="{{ $operateur?->id }}">
                                                                        <button class="btn btn-sm mx-1">Fiche synthèse</button>
                                                                    </form>
                                                                </li>
                                                                <li>
                                                                    <form action="{{ route('lettreOperateur') }}" method="post"
                                                                        target="_blank">
                                                                        @csrf
                                                                        <input type="hidden" name="id"
                                                                            value="{{ $operateur?->id }}">
                                                                        <button class="btn btn-sm mx-1">Lettre agrément</button>
                                                                    </form>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    @endcan

                                                </span>
                                            </div>
                                            <div class="row g-3">
                                                <table
                                                    class="table table-bordered table-hover datatables align-middle justify-content-center"
                                                    id="table-operateurModules">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col" width='2%'>N°</th>
                                                            <th scope="col">DOMAINE</th>
                                                            <th scope="col">MODULE</th>
                                                            <th scope="col">NIVEAU QUALIFICATION</th>
                                                            <th scope="col">QUALIFICATION</th>
                                                            <th class="text-center">STATUT</th>
                                                            @can('devenir-operateur-agrement-show')
                                                                <th class="text-center" width='2%'><i class="bi bi-gear"></i>
                                                                </th>
                                                            @endcan
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php $i = 1; ?>
                                                        @foreach ($operateur?->operateurmodules as $operateurmodule)
                                                            <tr>
                                                                <td style="text-align: center;">{{ $i++ }}</td>
                                                                <td>{{ $operateurmodule?->domaine }}</td>
                                                                <td>{{ $operateurmodule?->module }}</td>
                                                                <td>{{ $operateurmodule?->categorie }}</td>
                                                                <td>{{ $operateurmodule?->niveau_qualification }}</td>
                                                                <td style="text-align: center;">
                                                                    <span
                                                                        class="{{ $operateurmodule?->statut }}">{{ $operateurmodule?->statut }}</span>
                                                                </td>
                                                                @can('devenir-operateur-agrement-show')
                                                                    <td style="text-align: center;">
                                                                        <div class="d-flex justify-content-center">
                                                                            @can('devenir-operateur-agrement-update')
                                                                                <button class="btn btn-warning text-white btn-sm"
                                                                                    data-bs-toggle="modal"
                                                                                    data-bs-target="#EditOperateurmoduleModal{{ $operateurmodule->id }}"
                                                                                    title="Modifier">
                                                                                    <i class="bi bi-pencil-square"></i>
                                                                                </button>
                                                                            @endcan

                                                                            @can('devenir-operateur-agrement-delete')
                                                                                <form
                                                                                    action="{{ route('operateurmodules.destroy', $operateurmodule) }}"
                                                                                    method="POST">
                                                                                    @csrf
                                                                                    @method('DELETE')
                                                                                    <button type="submit"
                                                                                        class="btn btn-danger btn-sm show_confirm"
                                                                                        title="Supprimer">
                                                                                        <i class="bi bi-trash"></i>
                                                                                    </button>
                                                                                </form>
                                                                            @endcan
                                                                        </div>
                                                                    </td>
                                                                @endcan
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            {{-- </form> --}}
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-content">
                                    <div class="tab-pane fade profile-overview pt-0" id="formation-overview">
                                        <h5 class="card-title">FORMATIONS</h5>
                                        <table
                                            class="table table-bordered table-hover datatables  align-middle justify-content-center"
                                            id="table-formations">
                                            <thead>
                                                <tr>
                                                    <th width="5%">Code</th>
                                                    <th width="8%">Type</th>
                                                    <th>Intitulé formation</th>
                                                    <th>Localité</th>
                                                    <th>Modules</th>
                                                    <th width="5%">Effectif</th>
                                                    <th width="5%">Statut</th>
                                                    @can('formation-show')
                                                        <th class="text-center"><i class="bi bi-gear"></i></th>
                                                    @endcan
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $i = 1; ?>
                                                @foreach ($operateur?->formations as $formation)
                                                    <tr>
                                                        <td>{{ $formation?->code }}</td>
                                                        <td>{{ $formation->types_formation?->name }}</td>
                                                        <td>{{ $formation?->name }}</td>
                                                        <td>{{ $formation->departement?->region?->nom }}</td>
                                                        @if (!empty($formation?->collectivemodule?->module))
                                                            <td>{{ $formation->collectivemodule->module }}</td>
                                                            <td class="text-center">
                                                                <span
                                                                    class="text-primary fw-bold">{{ $formation?->listecollectives?->count() ?? 0 }}</span>
                                                            </td>
                                                        @elseif(!empty($formation?->module?->name))
                                                            <td>{{ $formation->module->name }}</td>
                                                            <td class="text-center">
                                                                <span
                                                                    class="text-primary fw-bold">{{ $formation?->individuelles?->count() ?? 0 }}</span>
                                                            </td>
                                                        @endif
                                                        <td><a href="#">
                                                                <span
                                                                    class="{{ $formation?->statut }}">{{ $formation?->statut }}</span>
                                                            </a>
                                                        </td>
                                                        @can('formation-show')
                                                            <td>
                                                                <span class="d-flex align-items-baseline"><a
                                                                        href="{{ route('formations.show', $formation) }}"
                                                                        class="btn btn-primary btn-sm" title="voir détails"><i
                                                                            class="bi bi-eye"></i></a>
                                                                    <div class="filter">
                                                                        <a class="icon" href="#"
                                                                            data-bs-toggle="dropdown"><i
                                                                                class="bi bi-three-dots"></i></a>
                                                                        <ul
                                                                            class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                                                            <li><a class="dropdown-item btn btn-sm"
                                                                                    href="{{ route('formations.edit', $formation) }}"
                                                                                    class="mx-1" title="Modifier"><i
                                                                                        class="bi bi-pencil"></i>Modifier</a>
                                                                            </li>
                                                                            <li>
                                                                                <form
                                                                                    action="{{ route('formations.destroy', $formation) }}"
                                                                                    method="post">
                                                                                    @csrf
                                                                                    @method('DELETE')
                                                                                    <button type="submit"
                                                                                        class="dropdown-item show_confirm"
                                                                                        title="Supprimer"><i
                                                                                            class="bi bi-trash"></i>Supprimer</button>
                                                                                </form>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </span>
                                                            </td>
                                                        @endcan
                                                    </tr>
                                                @endforeach

                                            </tbody>
                                        </table>
                                        <!-- End Table with stripped rows -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Edit Operateur-->
            <!-- Edit Operateur Module -->
            @foreach ($operateur?->operateurmodules as $operateurmodule)
                <div class="modal fade" id="EditOperateurmoduleModal{{ $operateurmodule->id }}" tabindex="-1"
                    role="dialog" aria-labelledby="EditOperateurmoduleModalLabel{{ $operateurmodule->id }}"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <form method="POST" action="{{ route('operateurmodules.update', $operateurmodule) }}"
                                enctype="multipart/form-data" class="needs-validation" novalidate>
                                @csrf
                                @method('PATCH')

                                <div class="card shadow-lg border-0">
                                    <div class="card-header bg-default text-center py-2 rounded-top">
                                        <h4 class="mb-0">✏️ Modification</h4>
                                    </div>

                                    <div class="card-body row g-4 px-4">
                                        <input type="hidden" name="id" value="{{ $operateurmodule->id }}">
                                        <input type="hidden" name="operateur"
                                            value="{{ $operateurmodule->operateur->id }}">

                                        {{-- Module --}}
                                        <div class="col-12">
                                            <label for="module" class="form-label">Module <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="module" id="module_operateur_edit"
                                                value="{{ old('module', $operateurmodule->module) }}"
                                                class="form-control form-control-sm @error('module') is-invalid @enderror"
                                                placeholder="Nom du module" required>
                                            <div id="moduleListEdit"></div>
                                            @error('module')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- Domaine --}}
                                        <div class="col-12">
                                            <label for="domaine" class="form-label">Domaine <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="domaine"
                                                value="{{ old('domaine', $operateurmodule->domaine) }}"
                                                class="form-control form-control-sm @error('domaine') is-invalid @enderror"
                                                placeholder="Domaine du module" required>
                                            @error('domaine')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- Catégorie --}}
                                        <div class="col-12">
                                            <label for="categorie" class="form-label">Catégorie <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="categorie"
                                                value="{{ old('categorie', $operateurmodule->categorie) }}"
                                                class="form-control form-control-sm @error('categorie') is-invalid @enderror"
                                                placeholder="Catégorie" required>
                                            @error('categorie')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- Niveau de qualification --}}
                                        <div class="col-12">
                                            <label for="niveau_qualification" class="form-label">Niveau de qualification <span
                                                    class="text-danger">*</span></label>
                                            <select name="niveau_qualification"
                                                class="form-select form-select-sm @error('niveau_qualification') is-invalid @enderror"
                                                id="select-field-niveau_qualification-update" required>
                                                <option disabled selected>Choisir un niveau</option>
                                                @foreach (['Initiation', 'Pré-qualification', 'Qualification'] as $niveau)
                                                    <option value="{{ $niveau }}"
                                                        {{ old('niveau_qualification', $operateurmodule->niveau_qualification) == $niveau ? 'selected' : '' }}>
                                                        {{ $niveau }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('niveau_qualification')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="card-footer d-flex justify-content-end gap-2 p-3 bg-light border-top">
                                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                                            <i class="bi bi-x-circle"></i> Fermer
                                        </button>
                                        <button type="submit" class="btn btn-success btn-sm">
                                            <i class="bi bi-check-circle"></i> Enregistrer les modifications
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
            <!-- End Edit Operateur Module-->
            <!-- The Modal Delete -->
            @foreach ($operateur?->operateurmodules as $operateurmodule)
                <div class="modal" id="myModal{{ $operateurmodule->id }}">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h4 class="modal-title">Confirmation</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <!-- Modal body -->
                            <div class="modal-body">
                                Êtes-vous sûre de bien vouloir supprimer ?
                            </div>

                            <!-- Modal footer -->
                            <div class="modal-footer">
                                <form method="post" action="{{ route('operateurmodules.destroy', $operateurmodule->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                            Non</button>
                                        <button class="btn btn-danger">
                                            <i class="bi bi-trash"></i> Oui
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </section>
    @endcan
@endsection
@push('scripts')
    <script>
        new DataTable('#table-operateurModules', {
            layout: {
                topStart: {
                    buttons: ['csv', 'excel', 'print'],
                }
            },
            "order": [
                [0, 'asc']
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
