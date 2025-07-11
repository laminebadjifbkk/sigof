@extends('layout.user-layout')
@section('title', 'ABE | LETTRE EVALUATION')
@section('space-work')
    @can('lettrevaluation-view')
        <section class="section register">
            <div class="row justify-content-center">
                <div class="col-12">
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
                                <h5 class="card-title mb-0">Lettre d'évaluation et ABE</h5>
                                @can('lettrevaluation-create')
                                    <button type="button" class="btn btn-primary btn-sm btn-rounded" data-bs-toggle="modal"
                                        data-bs-target="#AddlettreEvaluationModal">
                                        <i class="bi bi-plus-circle"></i>
                                        Ajouter
                                    </button>
                                @endcan
                            </div>
                            @if ($lettrevaluations->isNotEmpty())
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped align-middle" id="table-jury">
                                        <thead class="table-primary text-center">
                                            <tr>
                                                <th>Operateur</th>
                                                <th>Module formation</th>
                                                <th>Lieu</th>
                                                <th>Responsable</th>
                                                <th>Evaluateur(s)</th>
                                                <th>Evaluteur(s) ONFP</th>
                                                {{-- <th>Formation</th> --}}
                                                <th>Date évaluation</th>
                                                {{-- <th>Ingénieur</th> --}}
                                                {{-- <th>ABE</th>
                                                <th>Lettre</th> --}}
                                                <th>Statut</th>
                                                <th width="2%">#</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($lettrevaluations as $lettrevaluation)
                                                <tr>
                                                    <td>{{ $lettrevaluation?->formation?->operateur?->user?->username }}</td>
                                                    <td>
                                                        {{ $lettrevaluation?->formation->module->name ?? ($lettrevaluation?->formation->collectivemodule->module ?? 'Aucun') }}</span>
                                                    </td>
                                                    <td>{{ $lettrevaluation?->formation?->lieu ?? 'Aucun' }} </td>
                                                    <td>{{ $lettrevaluation?->titre }}</td>
                                                    <td>
                                                        @foreach ($lettrevaluation?->formation?->evaluateurs as $index => $evaluateur)
                                                            <li>
                                                                {{ $evaluateur?->name . ' ' . $evaluateur->lastname ?? 'Aucun' }}
                                                            </li>
                                                        @endforeach
                                                    </td>
                                                    <td>
                                                        @foreach ($lettrevaluation?->formation?->onfpevaluateurs as $index => $onfpevaluateur)
                                                            <li>
                                                                {{ $onfpevaluateur->name . ' ' . $onfpevaluateur->lastname ?? 'Aucun' }}
                                                            </li>
                                                        @endforeach
                                                    </td>
                                                    <td>{{ $lettrevaluation?->formation?->date_pv?->format('d/m/Y') ?? 'Aucun' }}
                                                    </td>
                                                    {{-- <td>{{ $lettrevaluation?->formation?->ingenieur?->name ?? 'Aucun' }} </td> --}}
                                                    <td>
                                                        @if ($lettrevaluation?->execution_statut == 1)
                                                            <span class="badge bg-success">Oui</span>
                                                        @else
                                                            <span class="badge bg-danger">Non</span>
                                                        @endif
                                                    </td>
                                                    {{-- Actions --}}
                                                    <td>
                                                        <div class="d-flex align-items-center gap-2">

                                                            <!-- Bouton Voir détails -->
                                                            <a href="{{ route('lettrevaluations.show', $lettrevaluation->id) }}"
                                                                class="btn btn-warning btn-sm" title="Voir détails">
                                                                <i class="bi bi-eye"></i>
                                                            </a>

                                                            <!-- Dropdown pour les actions supplémentaires -->
                                                            <div class="dropdown">
                                                                <a href="#" class="btn btn-sm btn-light"
                                                                    data-bs-toggle="dropdown" aria-expanded="false"
                                                                    title="Plus d'actions">
                                                                    <i class="bi bi-three-dots-vertical"></i>
                                                                </a>
                                                                <ul class="dropdown-menu dropdown-menu-end">

                                                                    <!-- Modifier -->
                                                                    @can('formation-update')
                                                                        <li>
                                                                            <a class="dropdown-item"
                                                                                href="{{ route('lettrevaluations.edit', $lettrevaluation->id) }}">
                                                                                <i class="bi bi-pencil"></i> Modifier
                                                                            </a>
                                                                        </li>
                                                                    @endcan

                                                                    <!-- Télécharger la lettre -->
                                                                    {{-- <li>
                                                                        <a href="{{ route('lettrevaluations.show', $lettrevaluation->id) }}"
                                                                            class="dropdown-item" target="_blank">
                                                                            <i class="bi bi-download"></i> Télécharger lettre
                                                                        </a>
                                                                    </li> --}}
                                                                    {{-- <li>
                                                                        <a href="{{ route('lettreMission.telecharger', $lettrevaluation->id) }}"
                                                                            class="dropdown-item" target="_blank">
                                                                            <i class="bi bi-receipt"></i> Télécharger lettre
                                                                        </a>
                                                                    </li> --}}

                                                                    <!-- Télécharger l'ABE -->
                                                                    @php
                                                                        $formation = $lettrevaluation?->formation;
                                                                        $isIndividuel = $formation?->module?->name;
                                                                        $isCollectif =
                                                                            $formation?->collectivemodule?->module;
                                                                    @endphp

                                                                    <li>
                                                                        @if ($isIndividuel || $isCollectif)
                                                                            <form
                                                                                action="{{ route($isIndividuel ? 'abeEvaluationlettre' : 'abeEvaluationCollettre', ['idformation' => $formation->id]) }}"
                                                                                method="POST" target="_blank">
                                                                                @csrf
                                                                                <button type="submit" class="dropdown-item">
                                                                                    <i class="bi bi-download"></i> Télécharger
                                                                                    ABE
                                                                                </button>
                                                                            </form>
                                                                            {{-- @else
                                                                            <span class="dropdown-item text-danger">
                                                                                <i class="bi bi-exclamation-triangle"></i> Aucun
                                                                                ABE
                                                                            </span> --}}
                                                                        @endif
                                                                    </li>

                                                                    <!-- Télécharger demande de paiement -->
                                                                    {{-- <li>
                                                                        <a href="{{ route('demandePaiement.telecharger', $lettrevaluation->id) }}"
                                                                            class="dropdown-item" target="_blank">
                                                                            <i class="bi bi-receipt"></i> Télécharger DP
                                                                        </a>
                                                                    </li> --}}

                                                                    <!-- Supprimer -->
                                                                    @can('lettrevaluation-delete')
                                                                        <li>
                                                                            <form
                                                                                action="{{ route('lettrevaluations.destroy', $lettrevaluation->id) }}"
                                                                                method="POST" class="dropdown-item show_confirm">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                                <button type="submit"
                                                                                    class="dropdown-item text-danger">
                                                                                    <i class="bi bi-trash"></i> Supprimer
                                                                                </button>
                                                                            </form>
                                                                        </li>
                                                                    @endcan

                                                                </ul>
                                                            </div>
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

                                    <div class="col-12">
                                        <label for="formation" class="form-label">Formation<span
                                                class="text-danger mx-1">*</span></label>
                                        <select name="formation"
                                            class="form-select form-select-sm @error('formation') is-invalid @enderror"
                                            aria-label="Select" id="formationSelect" data-placeholder="Choisir">
                                            <option value="{{ old('formation') }}">
                                                {{ old('formation') }}
                                            </option>
                                            @foreach ($formations as $formation)
                                                <option value="{{ $formation->id }}">
                                                    {{ $formation->name }}
                                                    @if ($formation->numero_convention)
                                                        - {{ $formation->numero_convention }}
                                                    @endif
                                                    @if ($formation?->operateur?->user?->username)
                                                        - {{ $formation?->operateur?->user?->username }}
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('formation')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    {{-- <div class="col-12">
                                        <label for="onfpevaluateur" class="form-label">Initateur de la lettre<span
                                                class="text-danger mx-1">*</span></label>
                                        <select name="onfpevaluateur"
                                            class="form-select form-select-sm @error('onfpevaluateur') is-invalid @enderror"
                                            aria-label="Select" id="onfpevaluateurSelect" data-placeholder="Choisir">
                                            <option value="{{ old('onfpevaluateur') }}">
                                                {{ old('onfpevaluateur') }}
                                            </option>
                                            @foreach ($onfpevaluateurs as $onfpevaluateur)
                                                <option value="{{ $onfpevaluateur->id }}">
                                                    {{ $onfpevaluateur->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('onfpevaluateur')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <label for="evaluateur" class="form-label">Evaluateur<span
                                                class="text-danger mx-1">*</span></label>
                                        <select name="evaluateur"
                                            class="form-select form-select-sm @error('evaluateur') is-invalid @enderror"
                                            aria-label="Select" id="evaluateurSelect" data-placeholder="Choisir">
                                            <option value="{{ old('evaluateur') }}">
                                                {{ old('evaluateur') }}
                                            </option>
                                            @foreach ($evaluateurs as $evaluateur)
                                                <option value="{{ $evaluateur->id }}">
                                                    {{ $evaluateur->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('evaluateur')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div> --}}

                                    <div class="col-12">
                                        <label for="contenu" class="form-label">Commenataires</label>
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
            @foreach ($lettrevaluations as $lettrevaluation)
                <div class="modal fade" id="EditABEModal{{ $lettrevaluation->id }}" tabindex="-1" role="dialog"
                    aria-labelledby="EditABEModalLabel{{ $lettrevaluation->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="post" action="{{ route('lettrevaluations.update', $lettrevaluation->id) }}"
                                enctype="multipart/form-data" class="row g-3">
                                @csrf
                                @method('patch')
                                <div class="card-header text-center bg-gradient-default">
                                    <h1 class="h4 text-black mb-0">Modifier membre</h1>
                                </div>
                                <input type="hidden" name="id" value="{{ $lettrevaluation->id }}">
                                <div class="modal-body">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label for="civilite" class="form-label">Civilité<span
                                                    class="text-danger mx-1">*</span></label>
                                            <select name="civilite"
                                                class="form-select form-select-sm @error('civilite') is-invalid @enderror"
                                                aria-label="Select" id="civiliteMembre" data-placeholder="Choisir civilité">
                                                <option value="{{ old('civilite', $lettrevaluation->civilite ?? '') }}">
                                                    {{ old('civilite', $lettrevaluation->civilite ?? '') }}</option>
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

                                        <div class="col-12">
                                            <label for="prenom" class="form-label">Prénom<span
                                                    class="text-danger mx-1">*</span></label>
                                            <input type="text" name="prenom"
                                                value="{{ old('prenom', $lettrevaluation->prenom ?? '') }}"
                                                class="form-control form-control-sm @error('prenom') is-invalid @enderror"
                                                id="prenom" placeholder="prenom">
                                            @error('prenom')
                                                <span class="invalid-feedback" role="alert">
                                                    <div>{{ $message }}</div>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="col-12">
                                            <label for="nom" class="form-label">Nom<span
                                                    class="text-danger mx-1">*</span></label>
                                            <input type="text" name="nom"
                                                value="{{ old('nom', $lettrevaluation->nom ?? '') }}"
                                                class="form-control form-control-sm @error('nom') is-invalid @enderror"
                                                id="nom" placeholder="nom">
                                            @error('nom')
                                                <span class="invalid-feedback" role="alert">
                                                    <div>{{ $message }}</div>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="col-12">
                                            <label for="fonction" class="form-label">Fonction<span
                                                    class="text-danger mx-1">*</span></label>
                                            <input type="text" name="fonction"
                                                value="{{ old('fonction', $lettrevaluation->fonction ?? '') }}"
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
