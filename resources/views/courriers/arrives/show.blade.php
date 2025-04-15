@extends('layout.user-layout')
@section('title', 'COURRIER ARRIVE | ' . $arrive?->courrier?->objet)
@section('space-work')
    <section class="section profile">
        <div class="container-fluid">
            <div class="row">
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
                            role="alert">
                            <strong>{{ $error }}</strong>
                        </div>
                    @endforeach
                @endif

                @hasrole('super-admin|courrier|a-courrier')
                    <span class="d-flex mt-2 align-items-baseline">
                        <a href="{{ route('arrives.index') }}" class="btn btn-success btn-sm" title="Retour">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>&nbsp;
                        <p> | Liste des courriers arrivés</p>
                    </span>
                    @elsehasrole('Employe|super-admin')
                    <span class="d-flex mt-2 align-items-baseline">
                        <a href="{{ route('mescourriers') }}" class="btn btn-info btn-sm" title="Retour">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>&nbsp;
                        <p> | Liste des courriers arrivés</p>
                    </span>
                @endhasrole

                <div class="col-12 col-md-12 col-lg-12 col-sm-12 col-xs-12 col-xxl-12">
                    <div class="card border-info mb-3">
                        <div class="card-body pt-3">
                            <ul class="nav nav-tabs nav-tabs-bordered">
                                <li class="nav-item">
                                    <button class="nav-link active" data-bs-toggle="tab"
                                        data-bs-target="#profile-overview">Courrier</button>
                                </li>

                                @can('update', $arrive)
                                    <li class="nav-item">
                                        <button class="nav-link" data-bs-toggle="tab"
                                            data-bs-target="#modifier_courrier">Modifier</button>
                                    </li>
                                @endcan

                                @hasrole('super-admin|courrier|a-courrier')
                                    @can('imputer', $arrive)
                                        <li class="nav-item">
                                            <button class="nav-link" data-bs-toggle="tab"
                                                data-bs-target="#imputer_courrier">Imputer</button>
                                        </li>
                                    @endcan
                                @endhasrole
                                <li class="nav-item">
                                    <button class="nav-link" data-bs-toggle="tab"
                                        data-bs-target="#profile-settings">Commentaires</button>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#audit">Audit</button>
                                </li>
                            </ul>

                            <div class="tab-content pt-0">
                                <div class="tab-pane fade show active profile-overview" id="profile-overview">
                                    @hasrole('super-admin|courrier|a-courrier')
                                        <div class="d-flex justify-content-between align-items-center mt-3">
                                            <h5 class="card-title"></h5>
                                            <form action="{{ route('couponArrive') }}" method="post" target="_blank">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $arrive?->id }}">
                                                <button class="btn btn-outline-primary btn-sm"><i class="fa fa-print"
                                                        aria-hidden="true"></i>Télécharger
                                                    coupon</button>
                                            </form>
                                        </div>
                                    @endhasrole

                                    <h5 class="card-title">Détails</h5>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label ">Objet</div>
                                        <div class="col-lg-9 col-md-8">{{ $arrive?->courrier?->objet }}</div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label ">N° courrier arrivé</div>
                                        <div class="col-lg-3 col-md-4">{{ $arrive?->numero_arrive }}</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label ">Date arrivé</div>
                                        <div class="col-lg-3 col-md-4">
                                            {{ $arrive?->courrier?->date_recep?->translatedFormat('l jS F Y') }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Date correspondance</div>
                                        <div class="col-lg-3 col-md-4">
                                            {{ $arrive?->courrier?->date_cores?->translatedFormat('l jS F Y') }}
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label ">N° correspondance</div>
                                        <div class="col-lg-3 col-md-4">{{ $arrive?->courrier?->numero_courrier }}</div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Année</div>
                                        <div class="col-lg-3 col-md-4">{{ $arrive?->courrier?->annee }}</div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Expéditeur</div>
                                        <div class="col-lg-3 col-md-4">{{ $arrive?->courrier?->expediteur }}</div>
                                    </div>
                                    <div class="row">
                                        @if (!empty($arrive?->courrier?->reference))
                                            <div class="col-lg-3 col-md-4 label">Référence</div>
                                            <div class="col-lg-3 col-md-4">{{ $arrive?->courrier?->reference }}</div>
                                        @endif
                                    </div>

                                    @if (!empty($arrive?->courrier?->numero_reponse))
                                        <div class="row">
                                            <div class="col-lg-3 col-md-4 label ">N° réponse</div>
                                            <div class="col-lg-3 col-md-4">{{ $arrive?->courrier?->numero_reponse }}</div>
                                            <div class="col-lg-3 col-md-4 label">Date réponse</div>
                                            <div class="col-lg-3 col-md-4">
                                                {{ $arrive?->courrier?->date_reponse?->format('d/m/Y') }}
                                            </div>
                                        </div>
                                    @endif

                                    @if (!empty($arrive?->courrier?->observation))
                                        <h5 class="card-title">Observations</h5>
                                        <p class="small fst-italic">{{ $arrive?->courrier?->observation }}.</p>
                                    @endif

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Imputation</div>
                                        <div class="col-lg-9 col-md-8">
                                            @if ($arrive?->employees && $arrive->employees->isNotEmpty())
                                                <?php $i = 1; ?>
                                                @foreach ($arrive->employees as $employee)
                                                    {{ $i++ }}. {!! $employee->user->firstname . ' ' . $employee->user->name !!}
                                                    @if ($employee?->fonction?->sigle)
                                                        <b>[{!! $employee?->fonction?->sigle ?? '' !!}]</b>
                                                    @endif
                                                    <br>
                                                @endforeach
                                            @else
                                                <div class="alert alert-info">Aucune imputation pour ce courrier</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Scan courrier</div>
                                        <div class="col-lg-9 col-md-8">
                                            @if (isset($arrive?->courrier?->file))
                                                <a href="{{ asset($arrive?->courrier?->getFile()) }}" target="_blank"
                                                    class="btn btn-primary btn-sm">
                                                    <i class="bi bi-download"></i> Télécharger le scan
                                                </a>
                                            @else
                                                <div class="alert alert-info mt-2">Aucun fichier disponible pour ce
                                                    courrier.</div>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Scan courrier</div>
                                        <div class="col-lg-9 col-md-8">
                                            @if ($arrive?->employees && $arrive->employees->isNotEmpty())
                                                <p>Fichier actuel : <a href="{{ asset($arrive?->courrier?->getFile()) }}"
                                                        target="_blank">Voir le fichier</a></p>

                                                <a title="télécharger le fichier joint" target="_blank"
                                                    href="{{ asset($arrive?->courrier?->getFile()) }}">
                                                    <i class="bi bi-download"></i>
                                                @else
                                                    <div class="alert alert-info">Aucun fichier joint</div>
                                            @endif
                                        </div>
                                    </div> --}}

                                </div>

                                <div class="tab-pane fade pt-3" id="profile-settings">
                                    <form method="POST" action="{{ route('comments.store', $arrive?->courrier) }}"
                                        class="mt-3">
                                        @csrf
                                        <div class="row mb-3">
                                            <label for="fullName"
                                                class="col-md-4 col-lg-3 col-form-label">Commentaires</label>
                                            <div class="col-md-8 col-lg-9">
                                                <div class="form-floating mb-3">
                                                    <textarea class="form-control @error('commentaire') is-invalid @enderror"
                                                        placeholder="Ecrire votre commentaire ici..." name="commentaire" id="commentaire" style="height: 100px;"></textarea>
                                                    <label for="floatingTextarea">Ecrire votre commentaire ici</label>
                                                </div>
                                                <small id="emailHelp" class="form-text text-muted">
                                                    @if ($errors?->has('commentaire'))
                                                        @foreach ($errors?->get('commentaire') as $message)
                                                            <p class="text-danger">{{ $message }}</p>
                                                        @endforeach
                                                    @endif
                                                </small>

                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="fullName" class="col-md-4 col-lg-3 col-form-label"></label>
                                            <div class="col-md-8 col-lg-9">
                                                <div class="form-floating mb-3">
                                                    <button type="submit" class="btn btn-primary btn-sm">Poster
                                                        commentaire</button>
                                                </div>
                                            </div>
                                        </div>

                                    </form>
                                    <hr>
                                    <h3 class="card-title text-center">Commentaires</h3>
                                    @forelse ($arrive?->courrier?->comments as $comment)
                                        <div class="card mt-2">
                                            <div class="card-body">
                                                <div>{!! $comment?->content !!}
                                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                                        {{-- <small>Posté le {!! Carbon\Carbon::parse($comment?->created_at)?->format('d/m/Y à H:i:s') !!}</small> --}}
                                                        <small>Posté le {!! Carbon\Carbon::parse($comment?->created_at)?->diffForHumans() !!}</small>
                                                        <span
                                                            class="badge bg-info mx-1">{!! $comment?->user?->firstname ?? '' !!}&nbsp;{!! $comment?->user?->name ?? '' !!}</span>
                                                    </div>
                                                </div>
                                                <div>
                                                    <solution-button></solution-button>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- Réponse aux commentaires --}}
                                        @foreach ($comment?->comments as $replayComment)
                                            <div class="row mb-3">
                                                <label for="" class="col-md-1 col-lg-1 col-form-label"></label>
                                                <div class="col-md-11 col-lg-11">
                                                    <div class="card form-floating mb-3">
                                                        <div class="card-body">
                                                            {!! $replayComment?->content !!}
                                                            <div
                                                                class="d-flex justify-content-between align-items-center mt-2">
                                                                <small>Posté le {!! Carbon\Carbon::parse($replayComment?->created_at)?->diffForHumans() !!}</small>
                                                                <span
                                                                    class="badge bg-primary mx-1">{!! $replayComment?->user?->firstname ?? '' !!}&nbsp;{!! $replayComment?->user?->name ?? '' !!}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach

                                        @auth
                                            <button class="btn btn-info btn-sm mt-0 mb-2" id="commentReplyId"
                                                onclick="toggleReplayComment({{ $comment?->id }})">
                                                Répondre
                                            </button>
                                            <form method="POST" action="{{ route('comments.storeReply', $comment) }}"
                                                class="ml-5 d-none" id="replayComment-{{ $comment?->id }}">
                                                @csrf



                                                <div class="row mb-3">
                                                    <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Réponse
                                                        commentaires</label>
                                                    <div class="col-md-8 col-lg-9">

                                                        <div class="form-floating mb-3">
                                                            <textarea class="form-control @error('replayComment') is-invalid @enderror" placeholder="Répondre à ce commentaire"
                                                                name="replayComment" id="replayComment" style="height: 100px;"></textarea>
                                                            <label for="floatingTextarea">Répondre à ce commentaire</label>
                                                        </div>
                                                        <small id="emailHelp" class="form-text text-muted">
                                                            @if ($errors?->has('replayComment'))
                                                                @foreach ($errors?->get('replayComment') as $message)
                                                                    <p class="text-danger">{{ $message }}</p>
                                                                @endforeach
                                                            @endif
                                                        </small>

                                                        <button class="btn btn-primary btn-sm m-2">
                                                            Répondre à ce commentaire
                                                        </button>
                                                    </div>
                                                </div>

                                                {{-- <div class="text-center">
                                        <button type="submit" class="btn btn-primary">Poster</button>
                                    </div> --}}


                                                {{-- <div class="form-group">
                                                <label for="replayComment"><b>Ma réponse</b></label>
                                                <textarea class="form-control @error('replayComment') is-invalid @enderror" name="replayComment" id="replayComment"
                                                    rows="3" placeholder="Répondre à ce commentaire"></textarea>
                                                <small id="emailHelp" class="form-text text-muted">
                                                    @if ($errors?->has('replayComment'))
                                                        @foreach ($errors?->get('replayComment') as $message)
                                                            <p class="text-danger">{{ $message }}</p>
                                                        @endforeach
                                                    @endif
                                                </small>
                                            </div>
                                            <button class="btn btn-primary btn-sm m-2">
                                                Répondre à ce commentaire
                                            </button> --}}
                                            </form>
                                        @endauth
                                        {{-- fin réponse aux commentaires --}}
                                    @empty

                                        <div class="alert alert-info">Aucun commentaire pour ce courrier</div>

                                    @endforelse
                                </div>
                                <div class="tab-pane fade pt-3" id="audit">
                                    <div class="border-info mb-3">
                                        <div class="card-header text-center">
                                            AUDIT
                                        </div>
                                        <div class="card-body profile-card pt-1 d-flex flex-column">
                                            <h5 class="card-title">Informations complémentaires</h5>
                                            <p>créé par <b>{{ $user_create_name }}</b>,
                                                {{ $arrive?->courrier?->created_at?->diffForHumans() }}</p>
                                            @if ($arrive?->courrier?->created_at != $courrier?->updated_at)
                                                <p>{{ 'modifié par ' }} <b> {{ $user_update_name }} </b>
                                                    {{ $courrier?->updated_at?->diffForHumans() }}</p>
                                            @else
                                                <p> jamais modifié</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade pt-3" id="modifier_courrier">
                                    <form method="post" action="{{ route('arrives.update', $arrive?->id) }}"
                                        enctype="multipart/form-data" class="row g-3">
                                        @csrf
                                        @method('PUT')
                                        <div class="col-12 col-md-6 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                            <label for="date_arrivee" class="form-label">Date arrivée<span
                                                    class="text-danger mx-1">*</span></label>
                                            <input type="date" name="date_arrivee"
                                                value="{{ $arrive?->courrier?->date_recep?->format('Y-m-d') ?? old('date_arrivee') }}"
                                                class="form-control form-control-sm @error('date_arrivee') is-invalid @enderror"
                                                id="date_arrivee" placeholder="Date arrivée">
                                            @error('date_arrivee')
                                                <span class="invalid-feedback" role="alert">
                                                    <div>{{ $message }}</div>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="col-12 col-md-6 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                            <label for="numero_arrive" class="form-label">Numéro<span
                                                    class="text-danger mx-1">*</span></label>
                                            <div class="input-group has-validation">
                                                <input type="number" min="0" name="numero_arrive"
                                                    value="{{ $arrive?->numero_arrive ?? old('numero_arrive') }}"
                                                    class="form-control form-control-sm @error('numero_arrive') is-invalid @enderror"
                                                    id="numero_arrive" placeholder="N° courrier arrivé">
                                                @error('numero_arrive')
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-6 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                            <label for="date_correspondance" class="form-label">Date correspondance<span
                                                    class="text-danger mx-1">*</span></label>
                                            <input type="date" name="date_correspondance"
                                                value="{{ $arrive?->courrier?->date_cores?->format('Y-m-d') ?? old('date_correspondance') }}"
                                                class="form-control form-control-sm @error('date_correspondance') is-invalid @enderror"
                                                id="date_correspondance" placeholder="nom">
                                            @error('date_correspondance')
                                                <span class="invalid-feedback" role="alert">
                                                    <div>{{ $message }}</div>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="col-12 col-md-6 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                            <label for="numero_courrier" class="form-label">Numéro
                                                correspondance</label>
                                            <div class="input-group has-validation">
                                                <input type="text" min="0" name="numero_courrier"
                                                    value="{{ $arrive?->courrier?->numero_courrier ?? old('numero_courrier') }}"
                                                    class="form-control form-control-sm @error('numero_courrier') is-invalid @enderror"
                                                    id="numero_courrier" placeholder="Numéro courrier">
                                                @error('numero_courrier')
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-6 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                            <label for="annee" class="form-label">Année<span
                                                    class="text-danger mx-1">*</span></label>
                                            <input type="number" min="2024" name="annee"
                                                value="{{ $arrive?->courrier?->annee ?? old('annee') }}"
                                                class="form-control form-control-sm @error('annee') is-invalid @enderror"
                                                id="annee" placeholder="Année">
                                            @error('annee')
                                                <span class="invalid-feedback" role="alert">
                                                    <div>{{ $message }}</div>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="col-12 col-md-6 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                            <label for="expediteur" class="form-label">Expéditeur<span
                                                    class="text-danger mx-1">*</span></label>
                                            <input type="text" name="expediteur"
                                                value="{{ $arrive?->courrier?->expediteur ?? old('expediteur') }}"
                                                class="form-control form-control-sm @error('expediteur') is-invalid @enderror"
                                                id="expediteur" placeholder="Expéditeur">
                                            @error('expediteur')
                                                <span class="invalid-feedback" role="alert">
                                                    <div>{{ $message }}</div>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="col-12 col-md-12 col-lg-12 col-sm-12 col-xs-12 col-xxl-12">
                                            <label for="objet" class="form-label">Objet<span
                                                    class="text-danger mx-1">*</span></label>
                                            {{-- <input type="text" name="objet"
                                                value="{{ $arrive?->courrier?->objet ?? old('objet') }}"
                                                class="form-control form-control-sm @error('objet') is-invalid @enderror"
                                                id="objet" placeholder="Objet"> --}}
                                            <textarea name="objet" id="objet" rows="4"
                                                class="form-control form-control-sm @error('objet') is-invalid @enderror" placeholder="Objet">{{ old('objet', $arrive?->courrier?->objet) }}</textarea>
                                            @error('objet')
                                                <span class="invalid-feedback" role="alert">
                                                    <div>{{ $message }}</div>
                                                </span>
                                            @enderror
                                            @error('objet')
                                                <span class="invalid-feedback" role="alert">
                                                    <div>{{ $message }}</div>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="col-12 col-md-6 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                            <label for="reference" class="form-label">Référence</label>
                                            <input type="text" name="reference"
                                                value="{{ $arrive?->courrier?->reference ?? old('reference') }}"
                                                class="form-control form-control-sm @error('reference') is-invalid @enderror"
                                                id="reference" placeholder="Référence">
                                            @error('reference')
                                                <span class="invalid-feedback" role="alert">
                                                    <div>{{ $message }}</div>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="col-12 col-md-6 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                            <label for="numero_reponse" class="form-label">Numéro réponse</label>
                                            <input type="number" min="0" name="numero_reponse"
                                                value="{{ $arrive?->courrier?->numero_reponse ?? old('numero_reponse') }}"
                                                class="form-control form-control-sm @error('numero_reponse') is-invalid @enderror"
                                                id="numero_reponse" placeholder="Numéro réponse">
                                            @error('numero_reponse')
                                                <span class="invalid-feedback" role="alert">
                                                    <div>{{ $message }}</div>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="col-12 col-md-6 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                            <label for="date_reponse" class="form-label">Date réponse</label>
                                            <input type="date" min="0" name="date_reponse"
                                                value="{{ $arrive?->courrier?->date_reponse?->format('Y-m-d') ?? old('date_reponse') }}"
                                                class="form-control form-control-sm @error('date_reponse') is-invalid @enderror"
                                                id="date_reponse" placeholder="Numéro réponse">
                                            @error('date_reponse')
                                                <span class="invalid-feedback" role="alert">
                                                    <div>{{ $message }}</div>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="col-12 col-md-12 col-lg-12 col-sm-12 col-xs-12 col-xxl-12">
                                            <label for="observation" class="form-label">Observations </label>
                                            <textarea name="observation" id="observation" rows="2" class="form-control form-control-sm"
                                                placeholder="Observations">{{ old('observation', $arrive?->courrier?->observation) }}</textarea>
                                            @error('observation')
                                                <span class="invalid-feedback" role="alert">
                                                    <div>{{ $message }}</div>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="col-12 col-md-6 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                            <label for="legende" class="form-label">Légende</label>
                                            <input type="text" name="legende"
                                                value="{{ $arrive?->courrier?->legende ?? old('legende') }}"
                                                class="form-control form-control-sm @error('legende') is-invalid @enderror"
                                                id="legende" placeholder="Le nom du fichier scanné">
                                            @error('legende')
                                                <span class="invalid-feedback" role="alert">
                                                    <div>{{ $message }}</div>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="col-12 col-md-6 col-lg-4 col-sm-12 col-xs-12 col-xxl-4">
                                            <label for="reference" class="form-label">Joindre courrier</label>
                                            <input type="file" name="file" id="file"
                                                accept=".jpg, .jpeg, .png, .svg, .gif"
                                                class="form-control @error('file') is-invalid @enderror btn btn-secondary btn-sm">
                                            @error('file')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                            @error('reference')
                                                <span class="invalid-feedback" role="alert">
                                                    <div>{{ $message }}</div>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-12 col-md-6 col-lg-1 col-sm-12 col-xs-12 col-xxl-1">
                                            @if (!empty($arrive?->courrier?->file))
                                                <label for="reference" class="form-label">Fichier</label><br>
                                                <a class="btn btn-outline-secondary btn-sm"
                                                    title="télécharger le fichier joint" target="_blank"
                                                    href="{{ asset($arrive?->courrier?->getFile()) }}">
                                                    <i class="bi bi-download"></i>
                                                </a>
                                            @endif
                                            {{-- <img class="w-25" alt="courrier"
                                        src="{{ asset($arrive?->courrier?->getFile()) }}" width="50"
                                        height="auto"> --}}
                                        </div>

                                        <div class="text-center">
                                            <button type="submit"
                                                class="btn btn-outline-success btn-sm">Modifier</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="tab-pane fade pt-3" id="imputer_courrier">
                                    <div class="col-lg-12">
                                        <div class="col-sm-12 col-md-12 pt-2">

                                            <div class="d-flex justify-content-between align-items-center mt-3">
                                                <h3>
                                                    Imputation
                                                </h3>
                                                <form action="{{ route('couponArrive') }}" method="post"
                                                    target="_blank">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $arrive?->id }}">
                                                    <button class="btn btn-outline-primary btn-sm"><i class="fa fa-print"
                                                            aria-hidden="true"></i>Télécharger
                                                        coupon</button>
                                                </form>
                                            </div>
                                            @csrf
                                            <div class="row form-row pt-3">
                                                <div class="pb-1"><b>Expéditeur:</b>
                                                    {{ $arrive?->courrier?->expediteur }}</div>
                                                <div class="pb-3"><b>Objet:</b> {{ $arrive?->courrier?->objet }}
                                                </div>
                                                <div class="col-xs-6 col-sm-6 col-md-6">
                                                    <div class="form-group">
                                                        <label for="">Employé</label>
                                                        <input type="text" placeholder="rechercher employé..."
                                                            class="form-control form-control-sm @error('product') is-invalid @enderror"
                                                            name="product" id="product" value=""
                                                            @required(true)>
                                                        <div class="col-lg-6" id="productList">
                                                        </div>
                                                        @error('product')
                                                            <span class="invalid-feedback" role="alert">
                                                                <div>{{ $message }}</div>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label for="">Centre de responsabilité</label>
                                                        <input type="text" placeholder="Centre de responsabilité"
                                                            class="form-control form-control-sm @error('direction') is-invalid @enderror"
                                                            name="direction" id="direction" value=""
                                                            @required(true) @readonly(true)>
                                                        @error('direction')
                                                            <span class="invalid-feedback" role="alert">
                                                                <div>{{ $message }}</div>
                                                            </span>
                                                        @enderror
                                                        <input type="hidden" name="id_direction" id="id_direction"
                                                            value="" @readonly(true)>
                                                    </div>
                                                </div>

                                                <input type="hidden" placeholder="ID"
                                                    class="form-control form-control-sm @error('id_emp') is-invalid @enderror"
                                                    name="id_emp" id="id_emp" value="0.0" min="0">
                                                <input type="hidden" placeholder="imp"
                                                    class="form-control form-control-sm @error('imp') is-invalid @enderror"
                                                    name="imp" id="imp" value="1">

                                                <div class="col-xs-12 col-sm-12 col-md-12 pt-3 text-center">
                                                    <button id="addMore" class="btn btn-success btn-sm"><i
                                                            class="fa fa-plus"
                                                            aria-hidden="true"></i>&nbsp;Ajouter</button>
                                                </div>

                                                {{--   </div>
                                                </div> --}}
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="col-lg-12">
                                            <form method="post" action="{{ route('arrives.update', $arrive?->id) }}"
                                                enctype="multipart/form-data" class="row g-3">
                                                @csrf
                                                @method('PUT')
                                                {{-- {!! Form::open(['url' => 'arrives/' . $arrive?->id, 'method' => 'PATCH', 'files' => true]) !!}
                                        @csrf --}}
                                                <div class="table-responsive">
                                                    <table class="table table-bordered" style="display: none;">
                                                        <thead>
                                                            <tr>
                                                                <th style="width: 50%">Direction<span
                                                                        class="text-danger mx-1">*</span></th>
                                                                <th>Responsable<span class="text-danger mx-1">*</span></th>
                                                                <th style="width: 5%">#</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="addRow" class="addRow">
                                                        </tbody>
                                                        <tbody>
                                                            <tr>
                                                                <td colspan="1" class="">
                                                                    {{-- <strong>{!! Form::label('Actions attendues') !!}</strong>
                                                                {!! Form::select(
                                                                    'description',
                                                                    [
                                                                        'Urgent' => 'Urgent',
                                                                        'M\'en parler' => 'M\'en parler',
                                                                        'Etudes et Avis' => 'Etudes et Avis',
                                                                        'Répondre' => 'Répondre',
                                                                        'Suivi' => 'Suivi',
                                                                        'Information' => 'Information',
                                                                        'Diffusion' => 'Diffusion',
                                                                        'Attribution' => 'Attribution',
                                                                        'Classement' => 'Classement',
                                                                    ],
                                                                    $arrive?->courrier?->description,
                                                                    [
                                                                        'placeholder' => 'Choisir une instruction...',
                                                                        'class' => 'form-control form-control-sm font-italic',
                                                                        'required' => 'required',
                                                                        'id' => 'description',
                                                                    ],
                                                                ) !!}
                    
                                                                <small id="emailHelp" class="form-text text-muted">
                                                                    @if ($errors?->has('description'))
                                                                        @foreach ($errors?->get('description') as $message)
                                                                            <p class="text-danger">{{ $message }}</p>
                                                                        @endforeach
                                                                    @endif
                                                                </small> --}}
                                                                    <strong><label for="description"
                                                                            class="form-label">Actions
                                                                            attendues<span
                                                                                class="text-danger mx-1">*</span></label></strong>
                                                                    <select name="description"
                                                                        class="form-select font-italic @error('description') is-invalid @enderror"
                                                                        aria-label="Select" id="select-field-familiale"
                                                                        data-placeholder="Choisir une instruction..."
                                                                        @required(true)>
                                                                        <option
                                                                            value="{{ $arrive?->courrier?->description }}">
                                                                            {{ $arrive?->courrier?->description ?? old('description') }}
                                                                        </option>
                                                                        <option value="Urgent">
                                                                            Urgent
                                                                        </option>
                                                                        <option value="M'en parler">
                                                                            M'en parler
                                                                        </option>
                                                                        <option value="Etudes et Avis">
                                                                            Etudes et Avis
                                                                        </option>
                                                                        <option value="Répondre">
                                                                            Répondre
                                                                        </option>
                                                                        <option value="Suivi">
                                                                            Suivi
                                                                        </option>
                                                                        <option value="Information">
                                                                            Information
                                                                        </option>
                                                                        <option value="Diffusion">
                                                                            Diffusion
                                                                        </option>
                                                                        <option value="Attribution">
                                                                            Attribution
                                                                        </option>
                                                                        <option value="Classement">
                                                                            Classement
                                                                        </option>
                                                                        <option value="Pour rappel">
                                                                            Pour rappel
                                                                        </option>
                                                                    </select>
                                                                    @error('description')
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <div>{{ $message }}</div>
                                                                        </span>
                                                                    @enderror
                                                                </td>
                                                                <td colspan="1">
                                                                    <strong><label
                                                                            for="date_imp">{{ __('Date imputation') }}<span
                                                                                class="text-danger mx-1">*</span></label></strong>
                                                                    <input id="date_imp"
                                                                        {{ $errors?->has('date_imp') ? 'is-invalid' : '' }}
                                                                        type="date"
                                                                        class="form-control form-control-sm @error('date_imp') is-invalid @enderror"
                                                                        name="date_imp" placeholder="Date imputation"
                                                                        required
                                                                        value="{{ optional($arrive?->courrier?->date_imp)?->format('Y-m-d') ?? old('date_imp') }}"
                                                                        autocomplete="date_imp">
                                                                    @error('date_imp')
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <div>{{ $message }}</div>
                                                                        </span>
                                                                    @enderror
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                        <tbody>
                                                            <tr>
                                                                <td colspan="4">
                                                                    <strong><label
                                                                            for="observation">{{ __('Observations') }}</label></strong>
                                                                    <textarea name="observation" id="observation" rows="2" class="form-control form-control-sm"
                                                                        placeholder="observation">{{ old('observation', $arrive?->courrier?->observation) }}</textarea>
                                                                    @error('observation')
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <div>{{ $message }}</div>
                                                                        </span>
                                                                    @enderror
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                        <tbody>
                                                            <tr>
                                                                <td colspan="4" class="text-center">
                                                                    {{-- {!! Form::submit('Imputer', ['class' => 'btn btn-outline-primary pull-right']) !!} --}}

                                                                    <div class="text-center">
                                                                        <button type="submit"
                                                                            class="btn btn-outline-primary btn-sm pull-right">Imputer</button>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>

                                                    </table>
                                                </div>
                                            </form>
                                            {{-- {!! Form::close() !!} --}}
                                            <div>
                                                <h5 class="card-title">Imputation employés</h5>
                                                {{-- <p>Le tableau de tous les employés.</p> --}}
                                                <table class="table datatables align-middle" id="table-employes">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Matricule</th>
                                                            <th>Prénom</th>
                                                            <th>Nom</th>
                                                            <th>E-mail</th>
                                                            <th>Téléphone</th>
                                                            <th>Direction</th>
                                                            {{-- @if (auth()?->user()?->hasRole('super-admin'))
                                                                <th>#</th>
                                                            @endif --}}
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php $i = 1; ?>
                                                        @foreach ($arrive?->employees as $employe)
                                                            <tr>
                                                                <th scope="row"><img class="rounded-circle w-20"
                                                                        alt="Profil"
                                                                        src="{{ asset($employe?->user?->getImage()) }}"
                                                                        width="40" height="auto">
                                                                </th>
                                                                {{-- <td>{{ $i++ }}</td> --}}
                                                                <td>{{ $employe?->matricule }}</td>
                                                                <td>{{ $employe?->user?->firstname }}</td>
                                                                <td>{{ $employe?->user?->name }}</td>
                                                                <td><a
                                                                        href="mailto:{{ $employe?->user?->email }}">{{ $employe?->user?->email }}</a>
                                                                </td>
                                                                <td><a
                                                                        href="tel:+221{{ $employe?->user?->telephone }}">{{ $employe?->user?->telephone }}</a>
                                                                </td>
                                                                <td>{{ $employe?->direction?->name }}</td>
                                                                @if (auth()?->user()?->hasRole('super-admin'))
                                                                    {{-- <td>
                                                                        <span class="d-flex mt-2 align-items-baseline"><a
                                                                                href="{{ route('employes.show', $employe?->id) }}"
                                                                                class="btn btn-success btn-sm mx-1"
                                                                                title="voir détails"><i
                                                                                    class="bi bi-eye"></i></a>
                                                                            <div class="filter">
                                                                                <a class="icon" href="#"
                                                                                    data-bs-toggle="dropdown"><i
                                                                                        class="bi bi-three-dots"></i></a>
                                                                                <ul
                                                                                    class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                                                                </ul>
                                                                            </div>
                                                                        </span>
                                                                    </td> --}}
                                                                @endif
                                                            </tr>
                                                        @endforeach

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
                                        <link rel="stylesheet"
                                            href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
                                        <script src="//code.jquery.com/jquery.js"></script>
                                        <script src="https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.7.6/handlebars.min.js"></script>
                                        <script id="document-template" type="text/x-handlebars-template">
                                        <tr class="delete_add_more_item" id="delete_add_more_item">    
                                            <td>
                                                <input type="hidden" name="id_emp[]" value="@{{ id_emp }}" required placeholder="Id Employé" class="form-control form-control-sm">
                                                <input type="text" name="product[]" value="@{{ product }}" required placeholder="Employé" class="form-control form-control-sm" readonly>                            
                                                <input type="hidden" name="imp" value="@{{ imp }}">
                                            </td>
                                            <td>
                                                <input type="text" class="direction form-control form-control-sm" name="direction[]" value="@{{ direction }}" required min="1" placeholder="Le nom du responsable" readonly>
                                                <input type="hidden" class="direction form-control form-control-sm" name="id_direction[]" value="@{{ id_direction }}" required min="1" placeholder="Le nom du responsable">
                                          </td>
                                            <td>
                                            <i class="removeaddmore" style="cursor:pointer;color:red;" title="supprimer"><i class="bi bi-trash"></i></i>
                                            </td>    
                                        </tr>
                                        </script>
                                        <script type="text/javascript">
                                            $(document).on('click', '#addMore', function() {
                                                $('.table').show();
                                                var product = $("#product").val();
                                                var id_emp = $("#id_emp").val();
                                                var direction = $("#direction").val();
                                                var id_direction = $("#id_direction").val();
                                                var imp = $("#imp").val();
                                                var source = $("#document-template").html();
                                                var template = Handlebars.compile(source);
                                                var data = {
                                                    product: product,
                                                    id_emp: id_emp,
                                                    direction: direction,
                                                    id_direction: id_direction,
                                                    imp: imp,
                                                }
                                                var html = template(data);
                                                $("#addRow").append(html)
                                                total_ammount_price();
                                            });
                                            $(document).on('click', '.removeaddmore', function(event) {
                                                $(this).closest('.delete_add_more_item').remove();
                                                total_ammount_price();
                                            });

                                            $('#product').keyup(function() {
                                                var query = $(this).val();
                                                if (query != '') {
                                                    var _token = $('input[name="_token"]').val();
                                                    $.ajax({
                                                        url: "{{ route('arrive.fetch') }}",
                                                        method: "POST",
                                                        data: {
                                                            query: query,
                                                            _token: _token
                                                        },
                                                        success: function(data) {
                                                            $('#productList').fadeIn();
                                                            $('#productList').html(data);
                                                        }
                                                    });
                                                }
                                            });
                                            $(document).on('click', 'li', function() {
                                                $('#product').val($(this).text());
                                                $('#id_emp').val($(this).data("id"));
                                                $('#direction').val($(this).data("direction"));
                                                $('#id_direction').val($(this).data("iddirection"));
                                                $('#productList').fadeOut();
                                            });
                                        </script>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        function toggleReplayComment(id) {
            let element = document.getElementById('replayComment-' + id);
            element.classList.toggle('d-none');
        }
    </script>
@endpush
