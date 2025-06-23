@extends('layout.user-layout')
@section('title', 'ONFP | LETTRE EVALUATION')
@section('space-work')
    @can('ingenieur-view')
        <section class="section register">
            <div class="row justify-content-center">
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="pagetitle">
                        {{-- <h1>Data Tables</h1> --}}
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('/home') }}">Accueil</a></li>
                                <li class="breadcrumb-item">Tables</li>
                                <li class="breadcrumb-item active">Données</li>
                            </ol>
                        </nav>
                    </div><!-- End Page Title -->
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
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="card-title mb-0">Lettre d'évaluation</h5>
                                <button type="button" class="btn btn-primary btn-sm btn-rounded" data-bs-toggle="modal"
                                    data-bs-target="#AddlettreEvaluationModal">
                                    <i class="bi bi-plus-circle"></i>
                                    Ajouter lettre d'évaluation
                                </button>
                            </div>
                            @if ($lettres->isNotEmpty())
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped align-middle" id="table-jury">
                                        <thead class="table-primary text-center">
                                            <tr>
                                                <th>Initiateur</th>
                                                <th>Formation</th>
                                                <th>Operateur</th>
                                                <th>Evaluateur</th>
                                                <th width="8%">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($lettres as $lettre)
                                                <tr>
                                                    <td>{{ $lettre?->onfpevaluateur?->name }}</td>
                                                    <td>{{ $lettre?->formation?->name }}</td>
                                                    <td>{{ $lettre?->formation?->operateur?->user?->username }}</td>
                                                    <td>{{ $lettre?->evaluateur?->name }}</td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <a href="{{ route('lettrevaluations.show', $lettre->id) }}"
                                                                class="btn btn-warning btn-sm" title="Voir détails">
                                                                <i class="bi bi-eye"></i>
                                                            </a>
                                                            <button type="button" class="btn btn-secondary btn-sm"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#EditmembreModal{{ $lettre->id }}"
                                                                title="Modifier">
                                                                <i class="bi bi-pencil"></i>
                                                            </button>
                                                            <form action="{{ route('lettrevaluations.destroy', $lettre->id) }}"
                                                                method="post" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="btn btn-danger btn-sm show_confirm"
                                                                    title="Supprimer">
                                                                    <i class="bi bi-trash"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-info">Aucune lettre de mission pour l'évaluation créée pour l'instant !
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add lettre evaluation -->
            <div class="modal fade" id="AddlettreEvaluationModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form method="post" action="{{ route('lettrevaluations.store') }}" enctype="multipart/form-data"
                            class="row g-3">
                            @csrf

                            <div class="card-header text-center bg-gradient-default">
                                <h1 class="h4 text-black mb-0">AJOUTER LETTRE EVALUATION</h1>
                            </div>
                            <div class="modal-body">
                                <div class="row g-3">
                                    {{-- <div class="col-12 col-md-12 col-lg-12 col-sm-12 col-xs-12 col-xxl-12">
                                        <label for="titre" class="form-label">Titre<span
                                                class="text-danger mx-1">*</span></label>
                                        <input type="text" name="titre" value="{{ old('titre') }}"
                                            class="form-control form-control-sm @error('titre') is-invalid @enderror"
                                            id="titre" placeholder="Titre">
                                        @error('titre')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div> --}}

                                    <div class="col-12 col-md-12 col-lg-12 col-sm-12 col-xs-12 col-xxl-12">
                                        <label for="formation" class="form-label">Formation<span
                                                class="text-danger mx-1">*</span></label>
                                        <select name="formations_id"
                                            class="form-select form-select-sm @error('formations_id') is-invalid @enderror"
                                            aria-label="Select" id="formationSelect" data-placeholder="Choisir">
                                            <option value="{{ old('formations_id') }}">
                                                {{ old('formations_id') }}
                                            </option>
                                            @foreach ($formations as $formation)
                                                <option value="{{ $formation->id }}">
                                                    {{ $formation->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('formations_id')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-12 col-lg-12 col-sm-12 col-xs-12 col-xxl-12">
                                        <label for="onfpevaluateurs_id" class="form-label">Initateur de la lettre<span
                                                class="text-danger mx-1">*</span></label>
                                        <select name="onfpevaluateurs_id"
                                            class="form-select form-select-sm @error('onfpevaluateurs_id') is-invalid @enderror"
                                            aria-label="Select" id="onfpevaluateurSelect" data-placeholder="Choisir">
                                            <option value="{{ old('onfpevaluateurs_id') }}">
                                                {{ old('onfpevaluateurs_id') }}
                                            </option>
                                            @foreach ($onfpevaluateurs as $onfpevaluateur)
                                                <option value="{{ $onfpevaluateur->id }}">
                                                    {{ $onfpevaluateur->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('onfpevaluateurs_id')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-12 col-lg-12 col-sm-12 col-xs-12 col-xxl-12">
                                        <label for="evaluateurs_id" class="form-label">Evaluateur<span
                                                class="text-danger mx-1">*</span></label>
                                        <select name="evaluateurs_id"
                                            class="form-select form-select-sm @error('evaluateurs_id') is-invalid @enderror"
                                            aria-label="Select" id="evaluateurSelect" data-placeholder="Choisir">
                                            <option value="{{ old('evaluateurs_id') }}">
                                                {{ old('evaluateurs_id') }}
                                            </option>
                                            @foreach ($evaluateurs as $evaluateur)
                                                <option value="{{ $evaluateur->id }}">
                                                    {{ $evaluateur->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('evaluateurs_id')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-12 col-lg-12 col-sm-12 col-xs-12 col-xxl-12">
                                        <label for="contenu" class="form-label">Commenatires</label>
                                        <textarea name="contenu" id="contenu" rows="5"
                                            class="form-control form-control-sm @error('contenu') is-invalid @enderror" placeholder="Commentaires">{{ old('contenu') }}</textarea>
                                        @error('contenu')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                </div>
                                <div class="modal-footer mt-5">
                                    <button type="button" class="btn btn-secondary btn-sm"
                                        data-bs-dismiss="modal">Fermer</button>
                                    <button type="submit" class="btn btn-primary btn-sm">Etablir</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- End Add membre-->

            <!-- Edit membre -->
            @foreach ($lettres as $lettre)
                <div class="modal fade" id="EditmembreModal{{ $lettre->id }}" tabindex="-1" role="dialog"
                    aria-labelledby="EditmembreModalLabel{{ $lettre->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="post" action="{{ route('lettrevaluations.update', $lettre->id) }}"
                                enctype="multipart/form-data" class="row g-3">
                                @csrf
                                @method('patch')
                                <div class="card-header text-center bg-gradient-default">
                                    <h1 class="h4 text-black mb-0">Modifier membre</h1>
                                </div>
                                <input type="hidden" name="id" value="{{ $lettre->id }}">
                                <div class="modal-body">
                                    <div class="row g-3">
                                        <div class="col-12 col-md-12 col-lg-12 col-sm-12 col-xs-12 col-xxl-12">
                                            <label for="civilite" class="form-label">Civilité<span
                                                    class="text-danger mx-1">*</span></label>
                                            <select name="civilite"
                                                class="form-select form-select-sm @error('civilite') is-invalid @enderror"
                                                aria-label="Select" id="civiliteMembre" data-placeholder="Choisir civilité">
                                                <option value="{{ old('civilite', $lettre->civilite ?? '') }}">
                                                    {{ old('civilite', $lettre->civilite ?? '') }}</option>
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

                                        <div class="col-12 col-md-12 col-lg-12 col-sm-12 col-xs-12 col-xxl-12">
                                            <label for="prenom" class="form-label">Prénom<span
                                                    class="text-danger mx-1">*</span></label>
                                            <input type="text" name="prenom"
                                                value="{{ old('prenom', $lettre->prenom ?? '') }}"
                                                class="form-control form-control-sm @error('prenom') is-invalid @enderror"
                                                id="prenom" placeholder="prenom">
                                            @error('prenom')
                                                <span class="invalid-feedback" role="alert">
                                                    <div>{{ $message }}</div>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="col-12 col-md-12 col-lg-12 col-sm-12 col-xs-12 col-xxl-12">
                                            <label for="nom" class="form-label">Nom<span
                                                    class="text-danger mx-1">*</span></label>
                                            <input type="text" name="nom"
                                                value="{{ old('nom', $lettre->nom ?? '') }}"
                                                class="form-control form-control-sm @error('nom') is-invalid @enderror"
                                                id="nom" placeholder="nom">
                                            @error('nom')
                                                <span class="invalid-feedback" role="alert">
                                                    <div>{{ $message }}</div>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="col-12 col-md-12 col-lg-12 col-sm-12 col-xs-12 col-xxl-12">
                                            <label for="fonction" class="form-label">Fonction<span
                                                    class="text-danger mx-1">*</span></label>
                                            <input type="text" name="fonction"
                                                value="{{ old('fonction', $lettre->fonction ?? '') }}"
                                                class="form-control form-control-sm @error('fonction') is-invalid @enderror"
                                                id="fonction" placeholder="fonction">
                                            @error('fonction')
                                                <span class="invalid-feedback" role="alert">
                                                    <div>{{ $message }}</div>
                                                </span>
                                            @enderror
                                        </div>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary btn-sm"
                                            data-bs-dismiss="modal">Fermer</button>
                                        <button type="submit" class="btn btn-primary btn-sm">Modifier</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
            <!-- End Edit membre-->
        </section>
    @endcan
@endsection

@push('scripts')
    <script>
        new DataTable('#table-jury', {
            layout: {
                topStart: {
                    buttons: ['csv', 'excel'],
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
