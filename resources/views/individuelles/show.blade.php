@extends('layout.user-layout')
@section('title', 'DETAILS DEMANDE DE ' . $individuelle->user->firstname . ' ' . $individuelle->user->name)
@section('space-work')
    <section class="section min-vh-0 d-flex flex-column align-items-center justify-content-center py-0 section profile">
        <div class="container-fluid">
            <div class="row justify-content-center">

                @foreach ($errors->all() as $error)
                    <div class="alert alert-danger bg-danger text-light border-0 alert-dismissible fade show" role="alert">
                        <strong>{{ $error }}</strong>
                    </div>
                @endforeach

                <div class="col-12 d-flex flex-column align-items-center justify-content-center">
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                @php
                                    $isAdmin = auth()
                                        ->user()
                                        ->hasRole(['super-admin', 'admin', 'DIOF', 'DEC', 'Ingenieur']);
                                    /* $route = $isAdmin ? route('individuelles.index') : route('demandesIndividuelle'); */
                                    $route = $isAdmin ? route('individuelles.index') : route('profil');
                                    $btnClass = $isAdmin ? 'btn-secondary' : 'btn-info';
                                @endphp

                                <span class="d-flex mt-2 align-items-baseline">
                                    <a href="{{ $route }}" class="btn {{ $btnClass }} btn-sm" title="Retour">
                                        <i class="bi bi-arrow-counterclockwise"></i>
                                    </a>&nbsp;
                                    <p> | Retour</p>
                                </span>

                                @php
                                    $validations = $individuelle?->validationindividuelles;
                                @endphp

                                @if ($validations && $validations->isNotEmpty())
                                    @hasanyrole('super-admin|admin|DIOF|ADIOF|Ingenieur')
                                        <span class="d-flex mt-2 align-items-baseline">
                                            <nav class="header-nav ms-auto">
                                                <ul class="d-flex align-items-center">
                                                    <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
                                                        <i class="bi bi-chat-left-text m-1"></i>
                                                        <span class="badge bg-success badge-number"
                                                            title="{{ $individuelle?->statut }}">
                                                            {{ $individuelle?->validationindividuelles->count() }}
                                                        </span>
                                                    </a>
                                                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow messages">
                                                        <li class="dropdown-header">
                                                            Vous avez {{ $individuelle?->validationindividuelles->count() }}
                                                            validation(s)
                                                        </li>
                                                        <li>
                                                            <hr class="dropdown-divider">
                                                        </li>
                                                        @foreach ($individuelle?->validationindividuelles->sortByDesc('created_at')->take(2) as $validationindividuelle)
                                                            <li class="message-item">
                                                                <div>
                                                                    <p><span
                                                                            class="{{ $validationindividuelle->action }}">{{ $validationindividuelle->action }}</span>
                                                                    </p>
                                                                    <p>
                                                                        @if (
                                                                            $validationindividuelle->created_at >= \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', '2025-05-16 22:40:00') &&
                                                                                $validationindividuelle->created_at <= \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', '2025-05-16 22:50:00'))
                                                                            Système
                                                                        @else
                                                                            {{ $validationindividuelle->user->firstname . ' ' . $validationindividuelle->user->name }}
                                                                        @endif
                                                                    </p>
                                                                    <p>{!! $validationindividuelle->created_at->diffForHumans() !!}</p>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <hr class="dropdown-divider">
                                                            </li>
                                                        @endforeach
                                                        <li class="dropdown-footer">
                                                            <form action="{{ route('validationmessage') }}" method="post"
                                                                target="_blank">
                                                                @csrf
                                                                <input type="hidden" name="id"
                                                                    value="{{ $individuelle?->id }}">
                                                                <button class="btn btn-sm mx-1">Voir
                                                                    toutes les validations</button>
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </ul>
                                            </nav>
                                        </span>
                                    @endhasanyrole
                                    @hasrole('Demandeur')
                                        @if (!empty($individuelle->projets_id))
                                            @if ($individuelle->projet?->statut !== 'ouvert')
                                                <span class="d-flex mt-2 align-items-baseline">
                                                    <nav class="header-nav ms-auto">
                                                        <ul class="d-flex align-items-center">
                                                            <a class="nav-link nav-icon" href="#"
                                                                data-bs-toggle="dropdown">
                                                                <i class="bi bi-chat-left-text m-1"></i>
                                                                <span class="badge bg-success badge-number"
                                                                    title="{{ $individuelle?->statut }}">
                                                                    {{ $individuelle?->validationindividuelles->count() }}
                                                                </span>
                                                            </a>
                                                            <ul
                                                                class="dropdown-menu dropdown-menu-end dropdown-menu-arrow messages">
                                                                <li class="dropdown-header">
                                                                    Vous avez
                                                                    {{ $individuelle?->validationindividuelles->count() }}
                                                                    validation(s)
                                                                </li>
                                                                <li>
                                                                    <hr class="dropdown-divider">
                                                                </li>
                                                                @foreach ($individuelle?->validationindividuelles->sortByDesc('created_at')->take(2) as $validationindividuelle)
                                                                    <li class="message-item">
                                                                        <div>
                                                                            <p><span
                                                                                    class="{{ $validationindividuelle->action }}">{{ $validationindividuelle->action }}</span>
                                                                            </p>
                                                                            <p>{!! $validationindividuelle->created_at->diffForHumans() !!}</p>
                                                                        </div>
                                                                    </li>
                                                                    <li>
                                                                        <hr class="dropdown-divider">
                                                                    </li>
                                                                @endforeach
                                                                <li class="dropdown-footer">
                                                                    <form action="{{ route('validationmessage') }}"
                                                                        method="post" target="_blank">
                                                                        @csrf
                                                                        <input type="hidden" name="id"
                                                                            value="{{ $individuelle?->id }}">
                                                                        <button class="btn btn-sm mx-1">Voir
                                                                            toutes les validations</button>
                                                                    </form>
                                                                </li>
                                                            </ul>
                                                        </ul>
                                                    </nav>
                                                </span>
                                            @else
                                            @endif
                                        @else
                                            <span class="d-flex mt-2 align-items-baseline">
                                                <nav class="header-nav ms-auto">
                                                    <ul class="d-flex align-items-center">
                                                        <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
                                                            <i class="bi bi-chat-left-text m-1"></i>
                                                            <span class="badge bg-success badge-number"
                                                                title="{{ $individuelle?->statut }}">
                                                                {{ $individuelle?->validationindividuelles->count() }}
                                                            </span>
                                                        </a>
                                                        <ul
                                                            class="dropdown-menu dropdown-menu-end dropdown-menu-arrow messages">
                                                            <li class="dropdown-header">
                                                                Vous avez
                                                                {{ $individuelle?->validationindividuelles->count() }}
                                                                validation(s)
                                                            </li>
                                                            <li>
                                                                <hr class="dropdown-divider">
                                                            </li>
                                                            @foreach ($individuelle?->validationindividuelles->sortByDesc('created_at')->take(2) as $validationindividuelle)
                                                                <li class="message-item">
                                                                    <div>
                                                                        <p><span
                                                                                class="{{ $validationindividuelle->action }}">{{ $validationindividuelle->action }}</span>
                                                                        </p>
                                                                        <p>{!! $validationindividuelle->created_at->diffForHumans() !!}</p>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <hr class="dropdown-divider">
                                                                </li>
                                                            @endforeach
                                                            <li class="dropdown-footer">
                                                                <form action="{{ route('validationmessage') }}" method="post"
                                                                    target="_blank">
                                                                    @csrf
                                                                    <input type="hidden" name="id"
                                                                        value="{{ $individuelle?->id }}">
                                                                    <button class="btn btn-sm mx-1">Voir
                                                                        toutes les validations</button>
                                                                </form>
                                                            </li>
                                                        </ul>
                                                    </ul>
                                                </nav>
                                            </span>
                                        @endif
                                    @endrole
                                @endif


                                <span class="d-flex align-items-baseline">
                                    @hasanyrole('super-admin|admin|DIOF|ADIOF|Ingenieur')
                                        <span class="{{ $individuelle?->statut }}">{{ $individuelle?->statut }}</span>
                                    @endhasanyrole

                                    @hasrole('Demandeur')
                                        @if (!empty($individuelle->projets_id))
                                            @if ($individuelle->projet?->statut === 'ouvert')
                                                <span class="btn btn-info btn-sm text-white d-inline-flex align-items-center">
                                                    <i class="bi bi-check-circle me-1"></i> Enregistrée
                                                </span>
                                            @else
                                                <span class="{{ $individuelle?->statut }}">{{ $individuelle?->statut }}</span>
                                            @endif
                                        @else
                                            <span class="{{ $individuelle?->statut }}">{{ $individuelle?->statut }}</span>
                                        @endif
                                    @endhasrole
                                    @can('valider-demande')
                                        @hasanyrole('super-admin|admin|DIOF|ADIOF|Ingenieur')
                                            <div class="filter">
                                                <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                                        class="bi bi-three-dots"></i></a>
                                                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                                    <li>
                                                        <button class="btn btn-sm mx-1" data-bs-toggle="modal"
                                                            data-bs-target="#RejetDemandeModal">Validation</button>
                                                    </li>
                                                    <li>
                                                        <button class="btn btn-sm mx-1" data-bs-toggle="modal"
                                                            data-bs-target="#NoteDemandeModal">Notation</button>
                                                    </li>
                                                </ul>
                                            </div>
                                        @endhasanyrole
                                    @endcan
                                </span>
                            </div>

                            <div class="tab-pane fade show active profile-overview" id="profile-overview">
                                <form method="post" action="{{ route('individuelles.show', $individuelle) }}"
                                    enctype="multipart/form-data" class="row g-3">
                                    @csrf
                                    @method('PUT')
                                    @foreach ([
            'Formation sollicitée' => $individuelle?->module?->name,
            'Numéro' => $individuelle?->numero,
            'Civilité' => $individuelle?->user?->civilite,
            'N° CIN' => $individuelle?->user?->cin,
            'Prénom' => $individuelle?->user?->firstname,
            'Nom' => $individuelle?->user?->name,
            'Date naissance' => $individuelle?->user?->date_naissance?->format('d/m/Y'),
            'Lieu naissance' => $individuelle?->user?->lieu_naissance,
            'Adresse' => $individuelle?->user?->adresse,
            'Email' => '<a href="mailto:' . $individuelle?->user?->email . '">' . $individuelle?->user?->email . '</a>',
            'Téléphone personnel' => '<a href="tel:+221' . $individuelle?->user?->telephone . '">' . $individuelle?->user?->telephone . '</a>',
            'Téléphone secondaire' => '<a href="tel:+221' . $individuelle?->telephone . '">' . $individuelle?->telephone . '</a>',
            'Lieu de formation' => $individuelle?->departement?->nom,
        ] as $label => $value)
                                        <div class="col-12 col-md-4 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                            <div class="label">{{ $label }}</div>
                                            <div>{!! $value !!}</div>
                                        </div>
                                    @endforeach

                                    @if (!empty($individuelle?->niveau_etude))
                                        <div class="col-12 col-md-4 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                            <div class="label">Niveau étude</div>
                                            <div>{{ $individuelle?->niveau_etude }}</div>
                                        </div>
                                    @endif

                                    @if (!empty($individuelle?->diplome_academique))
                                        <div class="col-12 col-md-4 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                            <div class="label">Diplôme académique</div>
                                            <div>{{ $individuelle?->diplome_academique }}</div>
                                        </div>
                                    @endif

                                    @if (!empty($individuelle?->autre_diplome_academique))
                                        <div class="col-12 col-md-4 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                            <div class="label">Autre diplôme académique</div>
                                            <div>{{ $individuelle?->autre_diplome_academique }}</div>
                                        </div>
                                    @endif

                                    @if (!empty($individuelle?->option_diplome_academique))
                                        <div class="col-12 col-md-4 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                            <div class="label">Option diplôme académique</div>
                                            <div>{{ $individuelle?->option_diplome_academique }}</div>
                                        </div>
                                    @endif

                                    @if (!empty($individuelle?->etablissement_academique))
                                        <div class="col-12 col-md-4 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                            <div class="label">Etablissement d'obtention</div>
                                            <div>{{ $individuelle?->etablissement_academique }}</div>
                                        </div>
                                    @endif

                                    @if (!empty($individuelle?->diplome_professionnel))
                                        <div class="col-12 col-md-4 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                            <div class="label">Diplôme professionnel</div>
                                            <div>{{ $individuelle?->diplome_professionnel }}</div>
                                        </div>
                                    @endif

                                    @if (!empty($individuelle?->autre_diplome_professionnel))
                                        <div class="col-12 col-md-4 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                            <div class="label">Autre diplôme professionnel</div>
                                            <div>{{ $individuelle?->autre_diplome_professionnel }}</div>
                                        </div>
                                    @endif

                                    @if (!empty($individuelle?->specialite_diplome_professionnel))
                                        <div class="col-12 col-md-4 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                            <div class="label">Spécialité</div>
                                            <div>{{ $individuelle?->specialite_diplome_professionnel }}</div>
                                        </div>
                                    @endif

                                    @if (!empty($individuelle?->etablissement_professionnel))
                                        <div class="col-12 col-md-4 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                            <div class="label">Etablissement</div>
                                            <div>{{ $individuelle?->etablissement_professionnel }}</div>
                                        </div>
                                    @endif

                                    @if (!empty($individuelle?->projet_poste_formation))
                                        <div class="col-12 col-md-4 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                            <div class="label">Projet poste formation</div>
                                            <div>{{ $individuelle?->projet_poste_formation }}</div>
                                        </div>
                                    @endif

                                    @if (!empty($individuelle?->qualification))
                                        <div class="col-12 col-md-12 col-lg-12 col-sm-12 col-xs-12 col-xxl-12">
                                            <div class="label">Qualification</div>
                                            <div>{{ $individuelle?->qualification }}</div>
                                        </div>
                                    @endif

                                    @if (!empty($individuelle?->experience))
                                        <div class="col-12 col-md-12 col-lg-12 col-sm-12 col-xs-12 col-xxl-12">
                                            <div class="label">Expérience</div>
                                            <div>
                                                {!! '- ' .
                                                    implode('- ', array_map(fn($line) => nl2br(e($line)), explode("\n", ucfirst($individuelle?->experience)))) !!}
                                            </div>
                                        </div>
                                    @endif

                                    @if (!empty($individuelle?->projetprofessionnel))
                                        <div class="col-12 col-md-12 col-lg-12 col-sm-12 col-xs-12 col-xxl-12">
                                            <div class="label">Projet professionnel</div>
                                            <div>
                                                {!! '- ' .
                                                    implode('- ', array_map(fn($line) => nl2br(e($line)), explode("\n", ucfirst($individuelle?->projetprofessionnel)))) !!}
                                            </div>
                                        </div>
                                    @endif

                                    @if (!empty($individuelle->projet))
                                        <div class="col-12 col-md-12 col-lg-12 col-sm-12 col-xs-12 col-xxl-12">
                                            <div class="label">{{ $individuelle->projet->type_projet }}</div>
                                            <div>{{ $individuelle->projet->sigle }}</div>
                                        </div>
                                    @endif
                                    @if (!empty($individuelle?->note))
                                        @php
                                            $note = floatval($individuelle->note);
                                            $noteClass = match (true) {
                                                $note < 5 => 'text-danger fw-bold', // Rouge pour < 5
                                                $note < 7 => 'text-warning fw-bold', // Orange pour 5-6.9
                                                $note < 9 => 'text-primary fw-bold', // Bleu pour 7-8.9
                                                default => 'text-success fw-bold', // Vert pour 9-10
                                            };
                                            $formattedNote =
                                                fmod($note, 1) == 0.0
                                                    ? number_format($note, 0, ',', ' ') // Pas de décimale si entier
                                                    : number_format($note, 1, ',', ' '); // Une décimale sinon
                                        @endphp

                                        <div class="col-12 col-md-4 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                            <div class="label">Note étude dossier</div>
                                            <div>
                                                <span class="{{ $noteClass }}">
                                                    {{ $formattedNote }}/10
                                                </span>
                                            </div>
                                        </div>
                                    @endif

                                    @if ($individuelle->projet)
                                    @else
                                        <div class="text-center">
                                            <a href="{{ route('individuelles.edit', $individuelle) }}"
                                                class="btn btn-primary btn-sm text-white" title="Modifier">Modifier</a>
                                        </div>
                                    @endif

                                </form>
                                <div class="pt-5">
                                    <div class="col-md-6">
                                        @if ($message = Session::get('status'))
                                            <div class="alert alert-success bg-success text-light border-0 alert-dismissible fade show"
                                                role="alert">
                                                <strong>{{ $message }}</strong>
                                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                    aria-label="Close"></button>
                                            </div>
                                        @endif
                                        <h5 class="mb-3 text-uppercase fw-bold">📎 Fichiers joints</h5>

                                        @php
                                            $fichiersDisponibles = $files->filter(fn($file) => !empty($file->file));
                                        @endphp

                                        @if ($fichiersDisponibles->isNotEmpty())
                                            <div class="d-grid gap-2">
                                                @foreach ($fichiersDisponibles as $index => $file)
                                                    @php
                                                        $statut = $file->statut ?? 'Attente';
                                                        $badgeClass = match ($statut) {
                                                            'Validé' => 'success',
                                                            'Rejeté', 'Invalide' => 'danger',
                                                            default => 'secondary',
                                                        };
                                                        $numero = $index + 1;
                                                    @endphp

                                                    <div class="card shadow-sm border rounded-3">
                                                        <div
                                                            class="card-body py-2 px-3 d-flex justify-content-between align-items-center">
                                                            <div class="me-3">
                                                                <h6 class="mb-1 small fw-semibold">
                                                                    {{ $numero }}. {{ $file->legende }}
                                                                </h6>
                                                                <span
                                                                    class="badge bg-{{ $badgeClass }}">{{ $statut }}</span>

                                                                @if ($file->statut !== 'Validé')
                                                                    <form action="{{ route('fileDestroy') }}"
                                                                        method="post" class="d-inline">
                                                                        @csrf
                                                                        @method('put')
                                                                        <input type="hidden" name="idFile"
                                                                            value="{{ $file->id }}">
                                                                        <button type="submit"
                                                                            class="btn btn-outline-danger btn-sm show_confirm"
                                                                            title="Supprimer">
                                                                            <i class="bi bi-trash"></i>
                                                                        </button>
                                                                    </form>
                                                                @endif

                                                                @hasanyrole('super-admin|admin|DIOF|ADIOF|Ingenieur')
                                                                    @if ($file->statut !== 'Validé')
                                                                        <form action="{{ route('fileValidate') }}"
                                                                            method="post" class="d-inline">
                                                                            @csrf
                                                                            @method('put')
                                                                            <input type="hidden" name="idFile"
                                                                                value="{{ $file->id }}">
                                                                            <button type="submit"
                                                                                class="btn btn-outline-success btn-sm show_confirm_valider"
                                                                                title="Valider">
                                                                                <i class="bi bi-check-circle"></i>
                                                                            </button>
                                                                        </form>
                                                                        <form action="{{ route('fileInvalide') }}"
                                                                            method="post" class="d-inline">
                                                                            @csrf
                                                                            @method('put')
                                                                            <input type="hidden" name="idFile"
                                                                                value="{{ $file->id }}">
                                                                            <button type="submit"
                                                                                class="btn btn-outline-warning btn-sm show_confirm_rejeter"
                                                                                title="Invalider">
                                                                                <i class="bi bi-x-circle"></i>
                                                                            </button>
                                                                        </form>
                                                                    @endif

                                                                    @if ($file->statut === 'Validé')
                                                                        <form action="{{ route('fileInvalide') }}"
                                                                            method="post" class="d-inline">
                                                                            @csrf
                                                                            @method('put')
                                                                            <input type="hidden" name="idFile"
                                                                                value="{{ $file->id }}">
                                                                            <button type="submit"
                                                                                class="btn btn-outline-warning btn-sm show_confirm_rejeter"
                                                                                title="Invalider">
                                                                                <i class="bi bi-x-circle"></i>
                                                                            </button>
                                                                        </form>
                                                                    @endif
                                                                    {{-- Infos de modification --}}
                                                                    <span class="text-muted small ms-2">
                                                                        Dernière modification le
                                                                        {{ $file?->updated_at?->format('d/m/Y à H:i:s') }}
                                                                    </span>
                                                                @endhasanyrole
                                                            </div>
                                                            <a href="{{ asset($file->getFichier()) }}"
                                                                class="btn btn-outline-info btn-sm d-flex align-items-center"
                                                                target="_blank" title="Visualiser le fichier">
                                                                <i class="bi bi-eye me-1"></i> Visualiser
                                                            </a>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="alert alert-info mt-2 mb-0 py-2 px-3 small">Aucun fichier
                                                disponible pour le moment.</div>
                                        @endif
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="RejetDemandeModal" tabindex="-1" aria-labelledby="RejetDemandeLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content shadow-lg rounded-3">
                        <form method="POST" action="{{ route('validation-individuelles.destroy', $individuelle?->id) }}"
                            enctype="multipart/form-data" class="row g-3 p-3">
                            @csrf
                            @method('DELETE')

                            <div class="modal-header bg-light border-bottom-0">
                                <h5 class="modal-title fw-bold text-danger" id="RejetDemandeLabel">Traitement de la
                                    demande</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Fermer"></button>
                            </div>

                            <div class="modal-body">

                                <div class="mb-3">
                                    <label for="statut" class="form-label">Statut de la
                                        demande<span class="text-danger mx-1">*</span></label>
                                    @php
                                        $selectedStatut = old('statut', $individuelle->statut);
                                    @endphp

                                    <select name="statut" id="statut" class="form-select form-select-sm" required>
                                        <option value="" disabled {{ !$selectedStatut ? 'selected' : '' }}>--
                                            Sélectionner un statut --</option>
                                        <option value="Attente" {{ $selectedStatut === 'Attente' ? 'selected' : '' }}>En
                                            attente</option>
                                        <option value="À corriger"
                                            {{ $selectedStatut === 'À corriger' ? 'selected' : '' }}>À corriger</option>
                                        <option value="Conforme" {{ $selectedStatut === 'Conforme' ? 'selected' : '' }}>
                                            Conforme</option>
                                        <option value="Non conforme"
                                            {{ $selectedStatut === 'Non conforme' ? 'selected' : '' }}>
                                            Non conforme</option>
                                        <option value="Validée" {{ $selectedStatut === 'Validée' ? 'selected' : '' }}>
                                            Validée</option>
                                        {{-- <option value="Non validé"
                                            {{ $selectedStatut === 'Non validé' ? 'selected' : '' }}>Non validé</option> --}}
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="motif" class="form-label">Commentaires ou remarques<span
                                            class="text-danger mx-1">(*)</span></label>
                                    @php
                                        $lastValidation = collect($individuelle?->validationindividuelles)
                                            ->sortByDesc('created_at')
                                            ->first();
                                    @endphp
                                    <textarea name="motif" id="motif" rows="5"
                                        class="form-control form-control-sm @error('motif') is-invalid @enderror"
                                        placeholder="Indiquez les raisons ou recommandations">{{ old('motif', $lastValidation?->motif) }}</textarea>

                                    @error('motif')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                            </div>

                            <div class="modal-footer border-top-0">
                                <button type="button" class="btn btn-outline-secondary btn-sm"
                                    data-bs-dismiss="modal">Annuler</button>
                                <button type="submit" class="btn btn-outline-danger btn-sm">Soumettre</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="NoteDemandeModal" tabindex="-1" aria-labelledby="NoteDemandeLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content shadow-lg rounded-3">
                        <form method="POST" action="{{ route('individuelles.update-note', $individuelle?->id) }}"
                            enctype="multipart/form-data" class="row g-3 p-3">
                            @csrf
                            @method('PUT')

                            <div class="modal-header bg-light border-bottom-0">
                                <h5 class="modal-title fw-bold text-primary" id="NoteDemandeLabel">Traitement de la
                                    demande</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Fermer"></button>
                            </div>

                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="note" class="form-label">Note <span
                                            class="text-danger">*</span></label>
                                    <input type="number" name="note" id="note"
                                        class="form-control form-control-sm @error('note') is-invalid @enderror"
                                        placeholder="Attribuez une note entre 0 et 10" min="0" max="10"
                                        step="0.1" value="{{ old('note', $individuelle?->note) }}">

                                    @error('note')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>
                            </div>


                            <div class="modal-footer border-top-0">
                                <button type="button" class="btn btn-outline-secondary btn-sm"
                                    data-bs-dismiss="modal">Annuler</button>
                                <button type="submit" class="btn btn-outline-primary btn-sm">Enregistrer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

    </section>
@endsection
