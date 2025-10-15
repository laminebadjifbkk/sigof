@extends('layout.user-layout')
@section('title', 'Formations de ' . Auth::user()->civilite . ' ' . Auth::user()->firstname . ' ' . Auth::user()->name)
@section('space-work')
    <section class="section profile">
        <div class="row justify-content-center">
            <div class="col-12">
                @if ($message = Session::get('status'))
                    <div class="alert alert-danger bg-danger text-light border-0 alert-dismissible fade show" region="alert">
                        <strong>{{ $message }}</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if ($message = Session::get('success'))
                    <div class="alert alert-success bg-success text-light border-0 alert-dismissible fade show"
                        region="alert">
                        <strong>{{ $message }}</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <div class="alert alert-danger bg-danger text-light border-0 alert-dismissible fade show"
                            role="alert">
                            <strong>{{ $error }}</strong>
                        </div>
                    @endforeach
                @endif
                <div class="card">
                    <div class="card-header text-center bg-gradient-default">
                        <h1 class="h4 text-black mb-0">Formations programmées</h1>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <span class="d-flex align-items-baseline"><a href="{{ route('profil') }}"
                                    class="btn btn-success btn-sm" title="retour"><i
                                        class="bi bi-arrow-counterclockwise"></i></a>&nbsp;
                                <p> | retour</p>
                            </span>
                        </div>

                        @if ($nouvelle_formations->isNotEmpty())
                            <h5 class="card-title"><strong><em>! <u>IMPORTANT</u></strong></em></h5>
                            <p class="fst-italic">Cher(e)
                                <strong><em>{{ Auth::user()->firstname . ' ' . Auth::user()->name }}</em></strong>, <br>
                                veuillez lire attentivement les informations relatives à la formation. Afin de garantir
                                votre place, il est essentiel de <span
                                    class="bg bg-success text-white"><em>confirmer</em></span>
                                votre présence. <br>
                                Si vous ne souhaitez pas y participer,
                                n’oubliez pas de <span class="bg bg-danger text-white"><em>décliner</em></span> votre
                                invitation.
                            </p>
                            <div class="tab-content pt-2">
                                <div class="tab-pane fade show active profile-overview" id="profile-overview">
                                    <?php $i = 1; ?>
                                    @foreach ($nouvelle_formations as $individuelle)
                                        <div class="row">
                                            <div class="card-title col-lg-3 col-md-4 label">
                                                Formation {{ $i++ }} :
                                            </div>
                                            <div class="card-title col-lg-9 col-md-8">
                                                @php
                                                    $moduleName =
                                                        $individuelle?->formation?->module?->name ??
                                                        ($individuelle?->formation?->collectivemodule?->module ??
                                                            'Aucun');
                                                @endphp

                                                {{ $moduleName }}
                                            </div>
                                            {{-- <div class="row">
                                            <div class="card-title col-lg-3 col-md-4 label">Conditions :
                                            </div>
                                            <p class="col-lg-9 col-md-8">
                                                {!! '- ' .
                                                    implode(
                                                        '- ',
                                                        array_map(fn($line) => nl2br(e($line)), explode("\n", ucfirst($individuelle?->formation?->conditions))),
                                                    ) !!}
                                            </p>
                                        </div> --}}

                                            {{-- Statut --}}
                                            <div class="row">
                                                <div class="col-lg-3 col-md-4 label">Statut</div>
                                                <div class="col-lg-9 col-md-8">
                                                    @php $confirmation = $individuelle?->confirmation; @endphp
                                                    @if ($confirmation)
                                                        <span class="{{ $confirmation }}">{{ $confirmation }}</span>
                                                    @else
                                                        <div class="alert alert-warning m-0">Aucune confirmation !</div>
                                                    @endif
                                                </div>
                                            </div>

                                            {{-- Motif de déclinaison --}}
                                            @if ($individuelle?->motif_declinaison)
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-4 label">Motif</div>
                                                    <div class="col-lg-9 col-md-8">
                                                        <p class="text-danger m-0">{{ $individuelle?->motif_declinaison }}
                                                        </p>
                                                    </div>
                                                </div>
                                            @endif

                                            {{-- Bénéficiaires --}}
                                            <div class="row">
                                                <div class="col-lg-3 col-md-4 label">Bénéficiaires</div>
                                                <div class="col-lg-9 col-md-8">
                                                    {{ $individuelle?->formation?->name ?? 'Non défini' }}
                                                </div>
                                            </div>

                                            {{-- Opérateur --}}
                                            {{-- <div class="row">
                                                <div class="col-lg-3 col-md-4 label">Opérateur</div>
                                                <div class="col-lg-9 col-md-8">
                                                    @php $operateurUser = $individuelle?->formation?->operateur?->user; @endphp
                                                    @if (!empty($operateurUser?->operateur))
                                                        {{ $operateurUser?->operateur }}
                                                        @if (!empty($operateurUser?->username))
                                                            <strong><em>({{ $operateurUser?->username }})</em></strong>
                                                        @endif
                                                    @else
                                                        Aucun
                                                    @endif
                                                </div>
                                            </div> --}}

                                            {{-- Date début --}}
                                            <div class="row">
                                                <div class="col-lg-3 col-md-4 label">Date début</div>
                                                <div class="col-lg-9 col-md-8">
                                                    {!! $individuelle?->formation?->date_debut
                                                        ? $individuelle?->formation?->date_debut->format('d/m/Y')
                                                        : '<span class="text-danger">Non définie</span>' !!}
                                                </div>
                                            </div>


                                            <div class="row">
                                                <div class="col-lg-3 col-md-4 label">Date fin</div>
                                                <div class="col-lg-9 col-md-8">
                                                    @php
                                                        $dateFin = $individuelle?->formation?->date_fin;
                                                    @endphp

                                                    {!! $dateFin ? $dateFin->format('d/m/Y') : '<span class="text-danger">Non définie</span>' !!}
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-3 col-md-4 label">Durée</div>
                                                <div class="col-lg-9 col-md-8">
                                                    @php
                                                        $duree = $individuelle?->formation?->duree_formation;
                                                    @endphp

                                                    @if ($duree)
                                                        {{ $duree }} {{ $duree == 1 ? 'jour' : 'jours' }}
                                                    @else
                                                        <span class="text-danger">Non définie</span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-3 col-md-4 label">Lieu formation</div>
                                                <div class="col-lg-9 col-md-8">
                                                    {{ $individuelle?->formation?->lieu ?? '<span class="text-danger">Non définie</span>' }}
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-3 col-md-4 label">Opérateur (formateur)</div>
                                                <div class="col-lg-9 col-md-8">
                                                    @php
                                                        $operateurUser = $individuelle?->formation?->operateur?->user;
                                                    @endphp

                                                    @if (!empty($operateurUser?->operateur))
                                                        {{ $operateurUser->operateur }}
                                                        (<strong>{{ $operateurUser->username }}</strong>)
                                                    @else
                                                        <span class="Non">Non définie</span>
                                                    @endif
                                                </div>
                                            </div>

                                            {{-- <div class="row">
                                                <div class="col-lg-3 col-md-4 label">Type de diplôme</div>
                                                @if (!empty($individuelle?->formation?->referentiel?->titre))
                                                    <div class="col-lg-9 col-md-8">
                                                        @if ($individuelle?->formation?->referentiel?->titre == 'Attestation')
                                                            {{ $individuelle?->formation?->referentiel?->titre }}
                                                        @else
                                                            {{ $individuelle?->formation?->referentiel?->titre }}
                                                            @if (!empty($individuelle?->formation?->referentiel?->categorie))
                                                                {{ ', ' . $individuelle?->formation?->referentiel?->categorie }}
                                                            @endif
                                                            @if (!empty($individuelle?->formation?->referentiel?->convention?->name))
                                                                {{ ', de la ' . $individuelle?->formation?->referentiel?->convention?->name }}
                                                            @endif
                                                        @endif
                                                    </div>
                                                @else
                                                    <div class="col-lg-9 col-md-8">Aucun</div>
                                                @endif
                                            </div> --}}
                                            <div class="row">
                                                <div class="col-lg-3 col-md-4 label">Type de diplôme</div>
                                                <div class="col-lg-9 col-md-8">
                                                    @php
                                                        $referentiel = $individuelle?->formation?->referentiel;
                                                    @endphp

                                                    @if (!empty($referentiel?->titre))
                                                        {{ $referentiel->titre }}

                                                        @if ($referentiel->titre !== 'Attestation')
                                                            @if (!empty($referentiel->categorie))
                                                                , {{ $referentiel->categorie }}
                                                            @endif
                                                            @if (!empty($referentiel->convention?->name))
                                                                , de la {{ $referentiel->convention->name }}
                                                            @endif
                                                        @endif
                                                    @else
                                                        <span class="Non">Non définie</span>
                                                    @endif
                                                </div>
                                            </div>

                                            {{-- <div class="d-flex align-items-baseline mb-2">
                                                @switch($individuelle?->confirmation)
                                                    @case('décliner')
                                                        <form action="{{ route('confirmer', $individuelle?->id) }}" method="post">
                                                            @csrf
                                                            @method('PUT')
                                                            <button
                                                                class="show_confirm_valider btn btn-success btn-sm mx-1">Confirmer</button>
                                                        </form>
                                                    @break

                                                    @case('confirmer')
                                                        <button class="btn btn-danger btn-sm mx-1" data-bs-toggle="modal"
                                                            data-bs-target="#declinerFormation{{ $individuelle?->id }}">
                                                            Décliner
                                                        </button>
                                                    @break

                                                    @default
                                                        <button class="btn btn-danger btn-sm mx-1" data-bs-toggle="modal"
                                                            data-bs-target="#declinerFormation{{ $individuelle?->id }}">
                                                            Décliner
                                                        </button>
                                                        <form action="{{ route('confirmer', $individuelle?->id) }}" method="post">
                                                            @csrf
                                                            @method('PUT')
                                                            <button
                                                                class="show_confirm_valider btn btn-success btn-sm mx-1">Confirmer</button>
                                                        </form>
                                                @endswitch
                                            </div> --}}
                                            <div class="d-flex align-items-baseline mb-2">
                                                @php $confirmation = $individuelle?->confirmation; @endphp

                                                @if ($confirmation === 'décliner' || is_null($confirmation))
                                                    <form action="{{ route('confirmer', $individuelle->id) }}"
                                                        method="post">
                                                        @csrf
                                                        @method('PUT')
                                                        <button
                                                            class="show_confirm_valider btn btn-success btn-sm mx-1">Confirmer</button>
                                                    </form>
                                                @endif

                                                @if ($confirmation === 'confirmer' || is_null($confirmation))
                                                    <button class="btn btn-danger btn-sm mx-1" data-bs-toggle="modal"
                                                        data-bs-target="#declinerFormation{{ $individuelle->id }}">
                                                        Décliner
                                                    </button>
                                                @endif
                                            </div>
                                            <div class="modal fade" id="declinerFormation{{ $individuelle?->id }}"
                                                tabindex="-1">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <form method="post"
                                                            action="{{ route('decliner', $individuelle?->id) }}"
                                                            enctype="multipart/form-data" class="row">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="card-header text-center bg-gradient-default">
                                                                <h1 class="h4 text-black mb-0">Indisponibilité</h1>
                                                            </div>
                                                            <div class="modal-body">
                                                                <label for="motif" class="form-label">Décliner
                                                                    formation<span class="text-danger mx-1">*</span></label>
                                                                <textarea name="motif" id="motif" rows="3"
                                                                    class="form-control form-control-sm @error('motif') is-invalid @enderror"
                                                                    placeholder="Expliquer pourquoi vous ne souhaitez pas y participer">{{ $individuelle?->motif_declinaison ?? old('motif') }}</textarea>
                                                                @error('motif')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <div>{{ $message }}</div>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary btn-sm"
                                                                    data-bs-dismiss="modal">Fermer</button>
                                                                <button type="submit"
                                                                    class="btn btn-danger btn-sm">Décliner</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="alert alert-info">Aucune notification de formation vous concernant pour l'instant !
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
