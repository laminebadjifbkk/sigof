@extends('layout.user-layout')
@section('title', 'ONFP | Détail de l’inscription')
@section('space-work')
    @can('inscriptioncontact-view')
        <section class="section">
            <div class="row justify-content-center">
                <div class="col-lg-12 col-md-12">

                    @foreach ($errors->all() as $error)
                        <div class="alert alert-danger bg-danger text-light border-0 alert-dismissible fade show" role="alert">
                            <strong>{{ $error }}</strong>
                        </div>
                    @endforeach

                    <div class="card shadow-sm">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Détails de l’inscription</h5>
                            @hasanyrole('super-admin|admin|DIOF|ADIOF|Ingenieur')
                                @if ($formulaire?->historiques->count() > '0')
                                    <span class="d-flex mt-2 align-items-baseline">
                                        <nav class="header-nav ms-auto">
                                            <ul class="d-flex align-items-center">
                                                <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
                                                    <i class="bi bi-chat-left-text m-1"></i>
                                                    <span class="badge bg-success badge-number" title="{{ $formulaire?->statut }}">
                                                        {{ $formulaire?->historiques->count() }}
                                                    </span>
                                                </a>
                                                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow messages">
                                                    <li class="dropdown-header">
                                                        Vous avez
                                                        {{ $formulaire?->historiques->count() }}
                                                        validation(s)
                                                    </li>
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    @foreach ($formulaire?->historiques->sortByDesc('created_at')->take(2) as $history)
                                                        <li class="message-item">
                                                            <div>
                                                                <p><span
                                                                        class="{{ $history->statut }}">{{ $history->statut }}</span>
                                                                </p>
                                                                <p>{!! $history->created_at->diffForHumans() !!}</p>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <hr class="dropdown-divider">
                                                        </li>
                                                    @endforeach
                                                    <li class="dropdown-footer">
                                                        <form action="{{ route('validationhistoriquepc') }}" method="post"
                                                            target="_blank">
                                                            @csrf
                                                            <input type="hidden" name="id" value="{{ $formulaire?->id }}">
                                                            <button class="btn btn-sm mx-1">Voir
                                                                toutes les validations</button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </ul>
                                        </nav>
                                    </span>
                                @endif
                                <span class="d-flex align-items-baseline float-end">
                                    <span class="{{ $formulaire?->statut }}">{{ $formulaire?->statut }}</span>
                                    <div class="filter">
                                        <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                                class="bi bi-three-dots"></i></a>
                                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                            <li>
                                                <button class="btn btn-sm mx-1" data-bs-toggle="modal"
                                                    data-bs-target="#validationDemande">Validation</button>
                                            </li>
                                            {{-- <li>
                                                <button class="btn btn-sm mx-1" data-bs-toggle="modal"
                                                    data-bs-target="#NoteDemandeModal">Notation</button>
                                            </li> --}}
                                        </ul>
                                    </div>
                                </span>
                            @endhasanyrole
                        </div>
                        <div class="card-body">
                            @php
                                $labels = [
                                    'cin' => 'Numéro CIN',
                                    'civilite' => 'Civilité',
                                    'prenom' => 'Prénom',
                                    'nom' => 'Nom',
                                    'date_naissance' => 'Date naissance',
                                    'lieu_naissance' => 'Lieu naissance',
                                    'email' => 'Adresse e-mail',
                                    'telephone' => 'Téléphone',
                                    'telephone_secondaire' => 'Téléphone secondaire',
                                    'adresse' => 'Adresse',
                                    'dernier_diplome' => 'Dernier diplôme obtenu',
                                    'nom_etablissement' => 'Établissement',
                                    'region' => 'Région',
                                    'formation' => 'Formation sollicitée',
                                    'diplome_vise' => 'Diplôme visé',
                                    'montant_inscription' => 'Montant inscription',
                                    'montant_mensualite' => 'Montant mensualité',
                                    'montant_unique' => 'Montant unique',
                                    'duree' => 'Durée (en années)',
                                    'handicape' => 'Situation de handicap',
                                    'type_handicap' => 'Type de handicap',
                                    'orphelin' => 'Orphelin',
                                    'type_orphelin' => 'Type d’orphelinat',
                                    'cin_file' => 'Copie CIN',
                                    'facture_file' => 'Facture',
                                    'cv' => 'CV',
                                    'diplome' => 'Diplôme',
                                    'statut' => 'Statut',
                                ];

                                $fileFields = ['cin_file', 'facture_file', 'cv', 'diplome'];
                            @endphp

                            <div class="row g-3">
                                @foreach ($labels as $field => $label)
                                    <div class="col-md-4">
                                        <strong>{{ $label }} :</strong><br>

                                        @if (in_array($field, $fileFields))
                                            @if (!empty($formulaire->$field))
                                                <a href="{{ asset('storage/' . $formulaire->$field) }}" target="_blank"
                                                    class="btn btn-outline-primary btn-sm mt-1">
                                                    <i class="bi bi-file-earmark-arrow-down"></i> Ouvrir
                                                </a>
                                            @else
                                                <span class="text-muted">Aucun fichier</span>
                                            @endif
                                        @elseif ($field === 'date_naissance' && $formulaire->$field)
                                            {{ \Carbon\Carbon::parse($formulaire->$field)->format('d/m/Y') }}
                                        @else
                                            {{ $formulaire->$field ?? '-' }}
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            {{-- Boutons --}}
                            <div class="mt-4 d-flex justify-content-between">
                                <a href="{{ route('formulaires.index') }}" class="btn btn-secondary btn-sm">
                                    Retour à la liste
                                </a>
                                <a href="{{ route('formulaires.edit', $formulaire->id) }}" class="btn btn-warning btn-sm">
                                    Modifier
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Modals --}}
            <div class="modal fade" id="validationDemande" tabindex="-1" aria-labelledby="validationDemandeLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content shadow-lg rounded-3">
                        <form method="POST" action="{{ route('formulaires.validationPriseEnCharge', $formulaire?->id) }}"
                            enctype="multipart/form-data" class="row g-3 p-3">
                            @csrf
                            @method('PUT')

                            <div class="modal-header bg-light border-bottom-0">
                                <h5 class="modal-title fw-bold text-info" id="validationDemandeLabel">Traitement de la
                                    demande</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                            </div>

                            <div class="modal-body">

                                <div class="mb-3">
                                    <label for="statut" class="form-label">Statut de la
                                        demande<span class="text-danger mx-1">*</span></label>
                                    @php
                                        $selectedStatut = old('statut', $formulaire->statut);
                                    @endphp

                                    <select name="statut" id="statut" class="form-select form-select-sm" required>
                                        <option value="" disabled {{ !$selectedStatut ? 'selected' : '' }}>--
                                            Sélectionner un statut --</option>
                                        <option value="Nouvelle" {{ $selectedStatut === 'Nouvelle' ? 'selected' : '' }}>
                                            Nouvelle</option>
                                        <option value="Sélectionné" {{ $selectedStatut === 'Sélectionné' ? 'selected' : '' }}>
                                            Sélectionné</option>
                                        <option value="Conforme" {{ $selectedStatut === 'Conforme' ? 'selected' : '' }}>
                                            Conforme</option>
                                        <option value="Non conforme"
                                            {{ $selectedStatut === 'Non conforme' ? 'selected' : '' }}>
                                            Non conforme</option>
                                        <option value="Validée" {{ $selectedStatut === 'Validée' ? 'selected' : '' }}>
                                            Validée</option>
                                        <option value="liste attente"
                                            {{ $selectedStatut === 'liste attente' ? 'selected' : '' }}>En
                                            liste attente</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="motif" class="form-label">Explications</label>

                                    <textarea name="motif" id="motif" rows="5"
                                        class="form-control form-control-sm @error('motif') is-invalid @enderror" placeholder="Indiquez les raisons">{{ old('motif', $formulaire?->motif) }}</textarea>

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
                                <button type="submit" class="btn btn-outline-info btn-sm">Soumettre</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    @endcan
@endsection
