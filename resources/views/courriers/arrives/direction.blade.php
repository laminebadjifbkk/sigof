@extends('layout.user-layout')
@section('title', 'ONFP | COURRIERS ARRIVES')
@section('space-work')

    <div class="pagetitle">
        {{-- <h1>Data Tables</h1> --}}
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Accueil</a></li>
                <li class="breadcrumb-item">Tables</li>
                <li class="breadcrumb-item active">Liste des courriers arrivés</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="table-responsive">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">

                                <div>
                                    <h4 class="mb-0 fw-bold text-primary">
                                        Courriers Arrivés
                                    </h4>
                                    <small class="text-muted">
                                        Gestion et suivi des courriers entrants
                                    </small>
                                </div>

                                <div class="d-flex align-items-center gap-2 text-info fw-semibold">
                                    <i class="bi bi-inboxes"></i>
                                    Affichage :
                                    <span class="text-dark">{{ $affichees }}</span>
                                    sur
                                    <span class="text-dark">{{ $total }}</span>
                                </div>

                                <div class="d-flex gap-2">
                                    <a href="{{ route('courriers.direction') }}"
                                        class="btn btn-outline-success btn-sm rounded-pill">
                                        <i class="bi bi-arrow-left"></i> Retour
                                    </a>

                                    @can('arrive-create')
                                        {{-- <a href="{{ route('arrives.create') }}" class="btn btn-primary btn-sm rounded-pill">
                                            <i class="bi bi-plus-circle"></i> Ajouter
                                        </a> --}}

                                        <button class="btn btn-outline-secondary btn-sm rounded-pill" data-bs-toggle="modal"
                                            data-bs-target="#generate_rapport">
                                            Rechercher
                                        </button>
                                    @endcan
                                </div>

                            </div>
                            <table class="table table-bordered table-striped align-middle">
                                <thead class="table-primary">
                                    <tr>
                                        <th scope="col" style="width: 50px;">N°</th>
                                        <th scope="col">Années</th>
                                        <th scope="col" class="text-center">Courriers reçus</th>
                                        <th scope="col" style="width: 120px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="missions-container">
                                    @foreach ($groupes as $index => $items)
                                        <tr>
                                            <td>
                                                {{ ($groupes->currentPage() - 1) * $groupes->perPage() + $loop->iteration }}
                                            </td>
                                            <td>{{ $items->annee }}</td>
                                            <td class="text-center">{{ number_format($items->total, 0, '', ' ') }}</td>
                                            <td>
                                                {{-- <a href="{{ route('arrives.parAnnee', ['annee' => $items->annee]) }}"
                                                    class="btn btn-sm btn-outline-primary d-flex align-items-center justify-content-center gap-1">
                                                    Voir plus <i class="bi bi-arrow-right-short"></i>
                                                </a> --}}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Bouton Load More --}}
                        @if ($groupes->hasMorePages())
                            <div class="text-center mt-3">
                                <a href="{{ $groupes->nextPageUrl() }}" id="loadMoreBtn" class="btn btn-info btn-sm">
                                    Voir plus
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        @if ($arrives->isNotEmpty())
                            <div class="table-responsive">
                                <table class="table datatables align-middle" id="table-arrives">
                                    <thead>
                                        <tr>
                                            <th class="text-center" width='8%'>N°</th>
                                            {{-- <th class="text-center"width='8%'>Date arrivé</th> --}}
                                            <th>Expéditeur</th>
                                            <th>Objet</th>
                                            <th>Imputation</th>
                                            <th width='2%'>#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($arrives as $arrive)
                                            <tr>
                                                <td class="text-center">{{ $arrive?->numero_arrive }}</td>
                                                {{-- <td class="text-center">
                                                    {{ $arrive?->courrier?->date_recep?->format('d/m/Y') }}
                                                </td> --}}
                                                <td>{{ $arrive?->courrier?->expediteur }}</td>
                                                <td>{{ $arrive?->courrier?->objet }}</td>
                                                {{-- <td>
                                                    @if ($arrive?->employees && $arrive->employees->isNotEmpty())
                                                        <ul class="mb-0 ps-3">
                                                            @foreach ($arrive->employees as $index => $employee)
                                                                <li>
                                                                    <strong>{{ $employee?->user?->firstname . ' ' . $employee?->user?->name ?? '' }}</strong>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @else
                                                        <span class="badge bg-info text-dark">Aucune</span>
                                                    @endif
                                                </td> --}}
                                                <td>
                                                    @if ($arrive?->employees && $arrive->employees->isNotEmpty())
                                                        <div class="small">
                                                            @foreach ($arrive->employees as $employee)
                                                                <span class="badge bg-light text-dark border me-1 mb-1">
                                                                    {{ $employee?->user?->firstname . ' ' . $employee?->user?->name }}
                                                                </span>
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        <span class="badge bg-info text-dark">Aucune</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-baseline">
                                                        <a href="{{ route('arrives.show', $arrive?->id) }}"
                                                            class="btn btn-success btn-sm" title="voir détails">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                        <div class="filter">
                                                            <a class="icon" href="#" data-bs-toggle="dropdown">
                                                                <i class="bi bi-three-dots"></i>
                                                            </a>
                                                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                                                <li><a class="dropdown-item btn btn-sm"
                                                                        href="{{ route('arrives.edit', $arrive?->id) }}">
                                                                        <i class="bi bi-pencil"></i> Modifier</a></li>
                                                                @can('delete', $arrive)
                                                                    @can('arrive-delete')
                                                                        <li>
                                                                            <form
                                                                                action="{{ route('arrives.destroy', $arrive?->id) }}"
                                                                                method="post">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                                <button type="submit"
                                                                                    class="dropdown-item show_confirm">
                                                                                    <i class="bi bi-trash"></i> Supprimer
                                                                                </button>
                                                                            </form>
                                                                        </li>
                                                                    @endcan
                                                                @endcan
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="alert alert-info mt-3">Aucun courrier arrivé enregistré pour le moment !!!</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        </div>

        <div class="modal fade" id="addCourrierArrive" tabindex="-1" role="dialog"
            aria-labelledby="addCourrierArriveLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">

                    <div class="card-header text-center bg-gradient-default">
                        <h1 class="h4 text-black mb-0">Ajouter un nouveau courrier arrivé</h1>
                    </div>
                    <form method="post" action="{{ route('arrives.store') }}" enctype="multipart/form-data"
                        class="row g-3">
                        @csrf
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                    <label for="date_arrivee" class="form-label">Date arrivée<span
                                            class="text-danger mx-1">*</span></label>
                                    <input type="date" name="date_arrivee" value="{{ old('date_arrivee') }}"
                                        class="datepicker form-control form-control-sm @error('date_arrivee') is-invalid @enderror"
                                        id="date_arrivee" placeholder="jj/mm/aaaa">
                                    @error('date_arrivee')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                    <label for="numero_arrive" class="form-label">Numéro<span
                                            class="text-danger mx-1">*</span></label>
                                    <div class="input-group has-validation">
                                        <input type="number" min="0" name="numero_arrive"
                                            value="{{ $numCourrier ?? old('numero_arrive') }}"
                                            class="form-control form-control-sm @error('numero_arrive') is-invalid @enderror"
                                            id="numero_arrive" placeholder="Numéro courrier">
                                        @error('numero_arrive')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                    <label for="date_correspondance" class="form-label">Date correspondance<span
                                            class="text-danger mx-1">*</span></label>
                                    <input type="date" name="date_correspondance"
                                        value="{{ old('date_correspondance') }}"
                                        class="datepicker form-control form-control-sm @error('date_correspondance') is-invalid @enderror"
                                        id="date_correspondance" placeholder="jj/mm/aaaa">
                                    @error('date_correspondance')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-12 col-lg-4 col-md-12 col-sm-12 col-xs-12 col-xxl-4">
                                    <label for="numero_courrier" class="form-label">Numéro de correspondance</label>
                                    <textarea name="numero_courrier" id="numero_courrier" rows="1"
                                        class="form-control form-control-sm @error('numero_courrier') is-invalid @enderror"
                                        placeholder="Numéro de correspondance">{{ old('numero_courrier') }}</textarea>

                                    @error('numero_courrier')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                    <label for="annee" class="form-label">Année<span
                                            class="text-danger mx-1">*</span></label>
                                    <input type="number" min="2024" name="annee"
                                        value="{{ $anneeEnCours ?? old('annee') }}"
                                        class="form-control form-control-sm @error('annee') is-invalid @enderror"
                                        id="annee" placeholder="Année">
                                    @error('annee')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label for="expediteur" class="form-label">
                                        Expéditeur <span class="text-danger">*</span>
                                    </label>
                                    <textarea name="expediteur" id="expediteur" rows="2"
                                        class="form-control form-control-sm @error('expediteur') is-invalid @enderror" placeholder="Expéditeur">{{ old('expediteur') }}</textarea>

                                    @error('expediteur')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="objet" class="form-label">Objet<span
                                            class="text-danger mx-1">*</span></label>
                                    <textarea name="objet" id="objet" rows="2"
                                        class="form-control form-control-sm @error('objet') is-invalid @enderror" placeholder="Objet">{{ old('objet') }}</textarea>
                                    @error('objet')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                    <label for="reference" class="form-label">Référence</label>
                                    <input type="text" name="reference" value="{{ old('reference') }}"
                                        class="form-control form-control-sm @error('reference') is-invalid @enderror"
                                        id="reference" placeholder="Référence">
                                    @error('reference')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                    <label for="numero_reponse" class="form-label">Numéro réponse</label>
                                    <input type="number" min="0" name="numero_reponse"
                                        value="{{ old('numero_reponse') }}"
                                        class="form-control form-control-sm @error('numero_reponse') is-invalid @enderror"
                                        id="numero_reponse" placeholder="Numéro réponse">
                                    @error('numero_reponse')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                    <label for="date_reponse" class="form-label">Date réponse</label>
                                    <input type="date" min="0" name="date_reponse"
                                        value="{{ old('date_reponse') }}"
                                        class="datepicker form-control form-control-sm @error('date_reponse') is-invalid @enderror"
                                        id="date_reponse" placeholder="jj/mm/aaaa">
                                    @error('date_reponse')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="observation" class="form-label">Observations</label>
                                    <textarea name="observation" id="observation" rows="2"
                                        class="form-control form-control-sm @error('observation') is-invalid @enderror" placeholder="Observations">{{ old('observation') }}</textarea>
                                    @error('observation')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary btn-sm"
                                        data-bs-dismiss="modal">Fermer</button>
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary btn-sm">Enregistrer</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="addCourrierOperateur" tabindex="-1" role="dialog"
            aria-labelledby="addCourrierOperateurLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    {{--  <div class="pt-0 pb-0">
                        <h5 class="card-title text-center pb-0 fs-4">Enregistrement</h5>
                        <p class="text-center small">enregister un nouveau courrier arrivé</p>
                    </div> --}}

                    <div class="modal-header">
                        <h5 class="modal-title">Ajouter un nouveau courrier arrivé</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    {{-- <form method="post" action="{{ route('arrives.store') }}" enctype="multipart/form-data"
                        class="row g-3"> --}}
                    <form method="post" action="{{ route('addCourrierOperateur') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="row g-3">

                                <div class="col-12 col-md-12 col-lg-8 col-sm-12 col-xs-12 col-xxl-8">
                                    <label for="objet" class="form-label">Objet<span
                                            class="text-danger mx-1">*</span></label>
                                    <textarea name="objet" id="objet" rows="1"
                                        class="form-control form-control-sm @error('objet') is-invalid @enderror" placeholder="Objet">{{ old('objet') }}</textarea>
                                    @error('objet')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                    <label for="numero_arrive" class="form-label">Numéro<span
                                            class="text-danger mx-1">*</span></label>
                                    <div class="input-group has-validation">
                                        <input type="number" min="0" name="numero_arrive"
                                            value="{{ $numCourrier ?? old('numero_arrive') }}"
                                            class="form-control form-control-sm @error('numero_arrive') is-invalid @enderror"
                                            id="numero_arrive" placeholder="Numéro de correspondance">
                                        @error('numero_arrive')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                    <label for="date_arrivee" class="form-label">Date arrivée<span
                                            class="text-danger mx-1">*</span></label>
                                    <input type="date" name="date_arrivee" value="{{ old('date_arrivee') }}"
                                        class="form-control form-control-sm @error('date_arrivee') is-invalid @enderror"
                                        id="date_arrivee" placeholder="Date arrivée">
                                    @error('date_arrivee')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                    <label for="date_correspondance" class="form-label">Date correspondance<span
                                            class="text-danger mx-1">*</span></label>
                                    <input type="date" name="date_correspondance"
                                        value="{{ old('date_correspondance') }}"
                                        class="form-control form-control-sm @error('date_correspondance') is-invalid @enderror"
                                        id="date_correspondance" placeholder="nom">
                                    @error('date_correspondance')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                {{-- <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                    <label for="numero_courrier" class="form-label">Numéro correspondance<span
                                            class="text-danger mx-1">*</span></label>
                                    <div class="input-group has-validation">
                                        <input type="text" min="0" name="numero_courrier"
                                            value="{{ old('numero_courrier') }}"
                                            class="form-control form-control-sm @error('numero_courrier') is-invalid @enderror"
                                            id="numero_courrier" placeholder="Numéro de correspondance">
                                        @error('numero_courrier')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>
                                </div> --}}

                                <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                    <label for="annee" class="form-label">Année<span
                                            class="text-danger mx-1">*</span></label>
                                    <input type="number" min="2024" name="annee"
                                        value="{{ $anneeEnCours ?? old('annee') }}"
                                        class="form-control form-control-sm @error('annee') is-invalid @enderror"
                                        id="annee" placeholder="Année">
                                    @error('annee')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-12 col-lg-8 col-sm-12 col-xs-12 col-xxl-8">
                                    <label for="expediteur" class="form-label">Opérateur<span
                                            class="text-danger mx-1">*</span></label>
                                    <textarea name="expediteur" id="expediteur" rows="1"
                                        class="form-control form-control-sm @error('expediteur') is-invalid @enderror" placeholder="Expéditeur">{{ old('expediteur') }}</textarea>
                                    @error('expediteur')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                    <label for="sigle" class="form-label">Sigle</label>
                                    <textarea name="sigle" id="sigle" rows="1"
                                        class="form-control form-control-sm @error('sigle') is-invalid @enderror" placeholder="Sigle">{{ old('sigle') }}</textarea>
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
                                    <label for="fixe" class="form-label">Téléphone<span
                                            class="text-danger mx-1">*</span></label>
                                    <input type="number" min="0" name="fixe" value="{{ old('fixe') }}"
                                        class="form-control form-control-sm @error('fixe') is-invalid @enderror"
                                        id="fixe" placeholder="3xxxxxxxx">
                                    @error('fixe')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-12 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                    <label for="type_demande" class="form-label">TYPE<span
                                            class="text-danger mx-1">*</span></label>
                                    <select name="type_demande"
                                        class="form-select form-select-sm @error('type_demande') is-invalid @enderror"
                                        aria-label="Select" id="select-field" data-placeholder="Choisir type de demande">
                                        <option value="{{ old('type_demande') }}">
                                            {{ old('type_demande') }}
                                        </option>
                                        <option value="Nouvelle">
                                            Nouvelle
                                        </option>
                                        <option value="Renouvellement">
                                            Renouvellement
                                        </option>
                                    </select>
                                    @error('type_demande')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                {{-- <div class="col-12 col-md-12 col-lg-6 col-sm-12 col-xs-12 col-xxl-12">
                                    <label for="reference" class="form-label">Référence</label>
                                    <input type="text" name="reference" value="{{ old('reference') }}"
                                        class="form-control form-control-sm @error('reference') is-invalid @enderror"
                                        id="reference" placeholder="Référence">
                                    @error('reference')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-12 col-lg-6 col-sm-12 col-xs-12 col-xxl-12">
                                    <label for="numero_reponse" class="form-label">Numéro réponse</label>
                                    <input type="number" min="0" name="numero_reponse"
                                        value="{{ old('numero_reponse') }}"
                                        class="form-control form-control-sm @error('numero_reponse') is-invalid @enderror"
                                        id="numero_reponse" placeholder="Numéro réponse">
                                    @error('numero_reponse')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-12 col-lg-6 col-sm-12 col-xs-12 col-xxl-12">
                                    <label for="date_reponse" class="form-label">Date réponse</label>
                                    <input type="date" min="0" name="date_reponse"
                                        value="{{ old('date_reponse') }}"
                                        class="form-control form-control-sm @error('date_reponse') is-invalid @enderror"
                                        id="date_reponse" placeholder="Numéro réponse">
                                    @error('date_reponse')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div> --}}

                                <div class="col-12">
                                    <label for="observation" class="form-label">Observations</label>
                                    <textarea name="observation" id="observation" rows="1"
                                        class="form-control form-control-sm @error('date_reponse') is-invalid @enderror" placeholder="Observations">{{ old('observation') }}</textarea>
                                    @error('observation')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary btn-sm"
                                        data-bs-dismiss="modal">Fermer</button>
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary btn-sm">Enregistrer</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
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
                    <form method="post" action="{{ route('arrives.report') }}">
                        @csrf
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="numero" class="form-label">Numero</label>
                                                <input type="text" name="numero" value="{{ old('numero') }}"
                                                    class="form-control form-control-sm @error('numero') is-invalid @enderror"
                                                    id="numero" placeholder="Numero">
                                                @error('numero')
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
                                                <label for="objet" class="form-label">Objet</label>
                                                <input type="text" name="objet" value="{{ old('objet') }}"
                                                    class="form-control form-control-sm @error('objet') is-invalid @enderror"
                                                    id="objet" placeholder="Objet">
                                                @error('objet')
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
                                                <label for="expediteur" class="form-label">Expéditeur</label>
                                                <input type="text" name="expediteur" value="{{ old('expediteur') }}"
                                                    class="form-control form-control-sm @error('expediteur') is-invalid @enderror"
                                                    id="expediteur" placeholder="Expéditeur">
                                                @error('expediteur')
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

@endsection
@push('scripts')
    <script>
        new DataTable('#table-arrives', {
            ordering: false,
            layout: {
                topStart: {
                    buttons: ['csv', 'excel', 'print'],
                }
            },
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

        document.addEventListener('DOMContentLoaded', function() {

            const loadMoreBtn = document.getElementById('loadMoreBtn');
            const missionsContainer = document.getElementById('missions-container');

            if (!loadMoreBtn) return;

            loadMoreBtn.addEventListener('click', function(e) {
                e.preventDefault();

                fetch(this.href)
                    .then(res => res.text())
                    .then(html => {

                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');

                        const newRows = doc.querySelectorAll('#missions-container tr');

                        newRows.forEach(row => {
                            missionsContainer.appendChild(row);
                        });

                        const newBtn = doc.getElementById('loadMoreBtn');

                        if (newBtn) {
                            this.href = newBtn.href;
                        } else {
                            this.remove();
                        }
                    })
                    .catch(err => console.error('Erreur chargement :', err));
            });
        });
    </script>
@endpush
