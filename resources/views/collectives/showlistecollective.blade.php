@extends('layout.user-layout')
@section('title', 'Détails ' . $listecollective?->prenom . ' ' . $listecollective?->nom)
@section('space-work')
    <section class="section min-vh-0 d-flex flex-column align-items-center justify-content-center py-0 section profile">
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
                <div class="col-12 col-lg-12 col-md-12 d-flex flex-column align-items-center justify-content-center">
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <span class="d-flex mt-2 align-items-baseline"><a
                                        href="{{ route('collectives.show', $listecollective?->collective) }}"
                                        class="btn btn-secondary btn-sm" title="retour"><i
                                            class="bi bi-arrow-counterclockwise"></i></a>&nbsp;
                                    <p> | retour</p>
                                </span>
                            </div>
                            <div class="tab-pane fade show active profile-overview" id="profile-overview">
                                <form method="post" action="{{ route('listecollectives.update', $listecollective) }}"
                                    enctype="multipart/form-data" class="row g-3">
                                    @csrf
                                    @method('PUT')

                                    <h5 class="card-title">Détails structure</h5>
                                    <div class="col-12 col-md-9 mb-2">
                                        <div class="label mb-2">Nom structure</div>
                                        <div>{{ $listecollective?->collective?->name }}</div>
                                    </div>
                                    <div class="col-12 col-md-3 mb-2">
                                        <div class="label mb-2">Sigle</div>
                                        <div>{{ $listecollective?->collective?->sigle }}</div>
                                    </div>
                                    <div class="col-12 col-md-3 mb-2">
                                        <div class="label mb-2">Numéro dossier</div>
                                        <div>{{ $listecollective?->collective?->numero }}</div>
                                    </div>
                                    <div class="col-12 col-md-3 mb-2">
                                        <div class="label mb-2">Statut juridique</div>
                                        <div>{{ $listecollective?->collective?->statut_juridique }}</div>
                                    </div>
                                    <div class="col-12 col-md-3 mb-2">
                                        <div class="label mb-2">Région</div>
                                        <div>{{ $listecollective?->collective?->departement->region->nom }}</div>
                                    </div>
                                    <div class="col-12 col-md-3 mb-2">
                                        <div class="label mb-2">Département</div>
                                        <div>{{ $listecollective?->collective?->departement->nom }}
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-3 mb-2">
                                        <div class="label mb-2">Adresse exacte</div>
                                        <div>{{ $listecollective?->collective?->adresse }}
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-3 mb-2">
                                        <div class="label mb-2">Téléphone</div>
                                        <div><a
                                                href="tel:+221{{ $listecollective?->collective?->telephone }}">{{ $listecollective?->collective?->telephone }}</a>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-3 mb-2">
                                        <div class="label mb-2">Fixe</div>
                                        <div><a
                                                href="tel:+221{{ $listecollective?->collective?->fixe }}">{{ $listecollective?->collective?->fixe }}</a>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-3 mb-2">
                                        <div class="label mb-2">Email</div>
                                        <div><a
                                                href="mailto:{{ $listecollective?->collective?->email }}">{{ $listecollective?->collective?->email }}</a>
                                        </div>
                                    </div>
                                    <div class="col-12 mb-2">
                                        <div class="label"> Projet professionnel</div>
                                        <div>
                                            {!! '- ' .
                                                implode(
                                                    '- ',
                                                    array_map(
                                                        fn($line) => nl2br(e($line)),
                                                        explode("\n", ucfirst($listecollective?->collective?->projetprofessionnel)),
                                                    ),
                                                ) !!}
                                        </div>
                                    </div>
                                    <hr>

                                    <h5 class="card-title">Détails demandeur</h5>

                                    <div class="col-12 mb-2">
                                        <div class="label mb-2">Formation sollicitée (module)</div>
                                        <div>{{ $listecollective?->collectivemodule?->module }}</div>
                                    </div>

                                    <div class="col-12 col-md-3 mb-2">
                                        <div class="label mb-2">N° CIN</div>
                                        <div>{{ $listecollective?->cin }}</div>
                                    </div>

                                    <div class="col-12 col-md-3 mb-2">
                                        <div class="label mb-2">Civilité</div>
                                        <div>{{ $listecollective?->civilite }}</div>
                                    </div>

                                    <div class="col-12 col-md-3 mb-2">
                                        <div class="label mb-2">Prénom</div>
                                        <div>{{ $listecollective?->prenom }}</div>
                                    </div>

                                    <div class="col-12 col-md-3 mb-2">
                                        <div class="label mb-2">Nom</div>
                                        <div>{{ $listecollective?->nom }}</div>
                                    </div>

                                    <div class="col-12 col-md-3 mb-2">
                                        <div for="date_naissance" class="label mb-2">Date naissance</div>
                                        <div>{{ $listecollective?->date_naissance?->format('d/m/Y') }}</div>
                                    </div>

                                    <div class="col-12 col-md-3 mb-2">
                                        <div class="label mb-2">Lieu naissance</div>
                                        <div>{{ $listecollective?->lieu_naissance }}</div>
                                    </div>
                                    @if ($listecollective?->telephone)
                                        <div class="col-12 col-md-3 mb-2">
                                            <div class="label mb-2">Telephone</div>
                                            <div><a
                                                    href="tel:+221{{ $listecollective?->telephone }}">{{ $listecollective?->telephone }}</a>
                                            </div>
                                        </div>
                                    @endif

                                    @if ($listecollective?->niveau_etude)
                                        <div class="col-12 col-md-3 mb-2">
                                            <div class="label mb-2">Niveau étude</div>
                                            <div>{{ $listecollective?->niveau_etude }}</div>
                                        </div>
                                    @endif

                                    @if ($listecollective?->experienc)
                                        <div class="col-12 col-md-3 mb-2">
                                            <div class="label mb-2">Expérience</div>
                                            <div>{{ $listecollective?->experience }}</div>
                                        </div>
                                    @endif

                                    @if ($listecollective?->autre_experience)
                                        <div class="col-12 col-md-3 mb-2">
                                            <div class="label mb-2">Autres experience</div>
                                            <div>{{ $listecollective?->autre_experience }}</div>
                                        </div>
                                    @endif

                                    <div class="col-12 col-md-3 mb-2">
                                        <div class="label mb-2">Statut</div>
                                        <div>
                                            <span class="{{ $listecollective?->statut }} text-white">
                                                {{ $listecollective?->statut }}
                                            </span>
                                        </div>
                                    </div>

                                    @if ($listecollective?->details)
                                        <div class="col-12 col-md-3 mb-2">
                                            <div class="label mb-2">Détails</div>
                                            <div>{{ $listecollective?->details }}</div>
                                        </div>
                                    @endif
                                    <div class="d-flex justify-content-center">
                                        <a href="{{ route('listecollectives.edit', $listecollective) }}"
                                            class="btn btn-outline-primary btn-sm text-primary d-flex align-items-center"
                                            title="Voir détails">
                                            <i class="bi bi-pencil me-2"></i>Modifier
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
@endsection
