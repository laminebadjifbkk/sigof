@extends('layout.user-layout')
@section('title', 'ONFP | CALS')
@section('space-work')

    <section class="section register">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="pagetitle">
                    {{-- <h1>Data Tables</h1> --}}
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Accueil</a></li>
                            <li class="breadcrumb-item">Tables</li>
                            <li class="breadcrumb-item active">Commisions</li>
                        </ol>
                    </nav>
                </div>
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
                @if ($errors?->any())
                    @foreach ($errors?->all() as $error)
                        <div class="alert alert-danger bg-danger text-light border-0 alert-dismissible fade show"
                            role="alert"><strong>{{ $error }}</strong></div>
                    @endforeach
                @endif
                <div class="card">
                    <div class="card-body">
                        @can('commission-create')
                            <div class="pt-1">
                                <button type="button" class="btn btn-primary btn-sm float-end btn-rounded"
                                    data-bs-toggle="modal" data-bs-target="#AddagrementModal">
                                    Ajouter
                                </button>
                            </div>
                        @endcan
                        {{-- @endcan --}}
                        <h5 class="card-title">COMMISIONS AGREMENTS</h5>
                        <div class="table-responsive">
                            <table class="table datatables align-middle justify-content-center" id="table-agrements">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th class="text-center">Session</th>
                                        <th class="text-center">Date campagne</th>
                                        <th class="text-center">Date commission</th>
                                        <th>Lieu</th>
                                        <th width="5%" class="text-center">Operateurs</th>
                                        <th width="8%" class="text-center">Statut</th>
                                        <th width="5%" class="text-center" scope="col"><i class="bi bi-gear"></i>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; ?>
                                    @foreach ($commissionagrements as $commissionagrement)
                                        <tr>
                                            <td>{{ $commissionagrement?->commission }}</td>
                                            <td style="text-align: center;">{{ $commissionagrement?->session }}</td>
                                            <td style="text-align: center;">
                                                @if ($commissionagrement?->date_ouverture && $commissionagrement?->date_fermeture)
                                                    Du {{ $commissionagrement->date_ouverture->format('d/m/Y') }} au
                                                    {{ $commissionagrement->date_fermeture->format('d/m/Y') }}
                                                @elseif ($commissionagrement?->date_ouverture)
                                                    À partir du {{ $commissionagrement->date_ouverture->format('d/m/Y') }}
                                                @elseif ($commissionagrement?->date_fermeture)
                                                    Jusqu’au {{ $commissionagrement->date_fermeture->format('d/m/Y') }}
                                                @else
                                                @endif
                                            </td>

                                            <td style="text-align: center;">
                                                @if ($commissionagrement?->debut_commission && $commissionagrement?->fin_commission)
                                                    Du {{ $commissionagrement->debut_commission->format('d/m/Y') }} au
                                                    {{ $commissionagrement->fin_commission->format('d/m/Y') }}
                                                @elseif ($commissionagrement?->debut_commission)
                                                    À partir du
                                                    {{ $commissionagrement->debut_commission->format('d/m/Y') }}
                                                @elseif ($commissionagrement?->fin_commission)
                                                    Jusqu’au {{ $commissionagrement->fin_commission->format('d/m/Y') }}
                                                @else
                                                @endif
                                            </td>
                                            <td>{{ $commissionagrement?->lieu }}</td>
                                            {{-- <td>{{ $commissionagrement?->date?->translatedFormat('l d F Y') }}
                                        </td> --}}
                                            <td style="text-align: center;">
                                                @foreach ($commissionagrement?->operateurs as $operateur)
                                                    @if ($loop?->last)
                                                        <span class="badge bg-info">{{ $loop?->count }}</span>
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td><span
                                                    class="{{ $commissionagrement?->statut }}">{{ $commissionagrement?->statut }}</span>
                                            </td>
                                            <td style="text-align: center;">
                                                @can('commission-show')
                                                    <span class="d-flex mt-2 align-items-baseline"><a
                                                            href="{{ route('commissionagrements.show', $commissionagrement?->id) }}"
                                                            class="btn btn-warning btn-sm mx-1" title="Voir détails">
                                                            <i class="bi bi-eye"></i></a>
                                                        @if (auth()?->user()?->hasRole('super-admin|admin'))
                                                            <div class="filter">
                                                                <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                                                        class="bi bi-three-dots"></i></a>
                                                                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                                                    {{-- <form action="{{ route('ficheSynthese') }}" method="post"
                                                                    target="_blank">
                                                                    @csrf
                                                                    <input type="hidden" name="id"
                                                                        value="{{ $commissionagrement?->id }}">
                                                                    <button type="submit"
                                                                        class="dropdown-item btn btn-sm mx-1"><i
                                                                            class="bi bi-file-earmark-pdf-fill"
                                                                            title="Fiche synthèse"></i>Fiche synthèse</button>
                                                                </form> --}}
                                                                    @can('commission-update')
                                                                        <li>
                                                                            <button type="button"
                                                                                class="dropdown-item btn btn-sm mx-1"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#EditagrementModal{{ $commissionagrement?->id }}">
                                                                                <i class="bi bi-pencil" title="Modifier"></i>
                                                                                Modifier
                                                                            </button>
                                                                        </li>
                                                                    @endcan
                                                                    @can('commission-delete')
                                                                        <li>
                                                                            <form
                                                                                action="{{ url('commissionagrements', $commissionagrement?->id) }}"
                                                                                method="post">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                                <button type="submit"
                                                                                    class="dropdown-item show_confirm"><i
                                                                                        class="bi bi-trash"></i>Supprimer</button>
                                                                            </form>
                                                                        </li>
                                                                        <hr>
                                                                        <li>
                                                                            <a class="dropdown-item btn btn-sm"
                                                                                href="{{ route('jurycommissionagrements.jury', $commissionagrement?->id) }}"
                                                                                class="mx-1" title="Modifier"><i
                                                                                    class="bi bi-people"></i>Membres du jury</a>

                                                                            {{--  <button type="button" class="dropdown-item btn btn-sm mx-1"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#EditagrementModal{{ $commissionagrement?->id }}">
                                                                            <i class="bi bi-people" title="Membres"></i> Membres
                                                                            jury
                                                                        </button> --}}
                                                                        </li>
                                                                    @endcan
                                                                </ul>
                                                            </div>
                                                        @endif
                                                    </span>
                                                @endcan
                                            </td>

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
        <!-- Add agrement -->
        <div class="modal fade" id="AddagrementModal" tabindex="-1">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <form method="post" action="{{ route('commissionagrements.store') }}" enctype="multipart/form-data"
                        class="row g-3">
                        @csrf
                        <div class="card-header text-center bg-gradient-default">
                            <h1 class="h4 text-black mb-0">AJOUTER COMMISSION</h1>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">

                                <div class="col-6">
                                    <label for="commission" class="form-label">Commission<span
                                            class="text-danger mx-1">*</span></label>
                                    <input type="text" name="commission" value="{{ old('commission') }}"
                                        class="form-control form-control-sm @error('commission') is-invalid @enderror"
                                        id="commission" placeholder="commission">
                                    @error('commission')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-6">
                                    <label for="date_ouverture" class="form-label">Date ouverture<span
                                            class="text-danger mx-1">*</span></label>
                                    <input type="date" name="date_ouverture" value="{{ old('date_ouverture') }}"
                                        class="form-control form-control-sm @error('date_ouverture') is-invalid @enderror"
                                        id="date_ouverture" placeholder="date_ouverture">
                                    @error('date_ouverture')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-6">
                                    <label for="date_fermeture" class="form-label">Date ferméture<span
                                            class="text-danger mx-1">*</span></label>
                                    <input type="date" name="date_fermeture" value="{{ old('date_fermeture') }}"
                                        class="form-control form-control-sm @error('date_fermeture') is-invalid @enderror"
                                        id="date_fermeture" placeholder="date_fermeture">
                                    @error('date_fermeture')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-6">
                                    <label for="session" class="form-label">Session<span
                                            class="text-danger mx-1">*</span></label>
                                    <select name="session"
                                        class="form-select form-select-sm  @error('session') is-invalid @enderror"
                                        aria-label="Select" id="select-field" data-placeholder="Choisir session">
                                        <option value="">
                                            {{ old('session') }}
                                        </option>
                                        <option value="Normale">
                                            Normale
                                        </option>
                                        <option value="Remplacement">
                                            Remplacement
                                        </option>
                                    </select>
                                </div>

                                <div class="col-6">
                                    <label for="annee" class="form-label">Année<span
                                            class="text-danger mx-1">*</span></label>
                                    <input type="number" min="2020" name="annee" value="{{ old('annee') }}"
                                        class="form-control form-control-sm @error('annee') is-invalid @enderror"
                                        id="annee" placeholder="Ex: 2024">
                                    @error('annee')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-6">
                                    <label for="date_agrement" class="form-label">Date commission<span
                                            class="text-danger mx-1">*</span></label>
                                    <input type="date" name="date_agrement" value="{{ old('date_agrement') }}"
                                        class="form-control form-control-sm @error('date_agrement') is-invalid @enderror"
                                        id="date_agrement" placeholder="Date">
                                    @error('date_agrement')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-6">
                                    <label for="region" class="form-label">Région<span
                                            class="text-danger mx-1">*</span></label>
                                    <input type="text" name="region" value="{{ old('region') }}"
                                        class="form-control form-control-sm @error('region') is-invalid @enderror"
                                        id="region" placeholder="Ex: Dakar">
                                    @error('region')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-6">
                                    <label for="lieu" class="form-label">Lieu commission</label>
                                    <input type="text" name="lieu" value="{{ old('lieu') }}"
                                        class="form-control form-control-sm @error('lieu') is-invalid @enderror"
                                        id="lieu" placeholder="Adressse exacte de la commission">
                                    @error('lieu')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                            </div>
                            <div class="modal-footer mt-5">
                                <button type="button" class="btn btn-secondary btn-sm"
                                    data-bs-dismiss="modal">Fermer</button>
                                <button type="submit" class="btn btn-primary btn-sm">Ajouter</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- End Add agrement-->

        <!-- Edit agrement -->
        @foreach ($commissionagrements as $commissionagrement)
            <div class="modal fade" id="EditagrementModal{{ $commissionagrement?->id }}" tabindex="-1" role="dialog"
                aria-labelledby="EditagrementModalLabel{{ $commissionagrement?->id }}" aria-hidden="true">
                <div class="modal-dialog modal-xl" role="document">
                    <div class="modal-content">
                        <form method="post" action="{{ route('commissionagrements.update', $commissionagrement?->id) }}"
                            enctype="multipart/form-data" class="row g-3">
                            @csrf
                            @method('patch')
                            <div class="card-header text-center bg-gradient-default">
                                <h1 class="h4 text-black mb-0">MODIFICATION COMMISSION</h1>
                            </div>
                            <div class="modal-body">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label">Commission<span
                                                class="text-danger mx-1">*</span></label>
                                        <input type="text" name="commission"
                                            value="{{ $commissionagrement?->commission ?? old('commission') }}"
                                            class="form-control form-control-sm @error('commission') is-invalid @enderror"
                                            id="commission" placeholder="Commission">
                                        @error('commission')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-6">
                                        <label for="date_ouverture" class="form-label">Date ouverture<span
                                                class="text-danger mx-1">*</span></label>
                                        <input type="date" name="date_ouverture"
                                            value="{{ $commissionagrement?->date_ouverture?->format('Y-m-d') ?? old('date_ouverture') }}"
                                            class="form-control form-control-sm @error('date_ouverture') is-invalid @enderror"
                                            id="date_ouverture" placeholder="date_ouverture">
                                        @error('date_ouverture')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-6">
                                        <label for="date_fermeture" class="form-label">Date ferméture<span
                                                class="text-danger mx-1">*</span></label>
                                        <input type="date" name="date_fermeture"
                                            value="{{ $commissionagrement?->date_fermeture?->format('Y-m-d') ?? old('date_fermeture') }}"
                                            class="form-control form-control-sm @error('date_fermeture') is-invalid @enderror"
                                            id="date_fermeture" placeholder="date_fermeture">
                                        @error('date_fermeture')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-6">
                                        <label class="form-label">Session<span class="text-danger mx-1">*</span></label>
                                        <select name="session"
                                            class="form-select form-select-sm @error('session') is-invalid @enderror"
                                            aria-label="Select" id="select-field" data-placeholder="Choisir session">
                                            <option value="{{ $commissionagrement?->session ?? old('session') }}">
                                                {{ $commissionagrement?->session ?? old('session') }}
                                            </option>
                                            <option value="Normale">
                                                Normale
                                            </option>
                                            <option value="Remplacement">
                                                Remplacement
                                            </option>
                                        </select>
                                        @error('session')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-6">
                                        <label class="form-label">Année<span class="text-danger mx-1">*</span></label>
                                        <input type="number" min="2020" name="annee"
                                            value="{{ $commissionagrement?->annee ?? old('annee') }}"
                                            class="form-control form-control-sm @error('annee') is-invalid @enderror"
                                            id="annee" placeholder="Ex: 2024">
                                        @error('annee')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-6">
                                        <label class="form-label">Date début commission</label>
                                        <input type="date" name="debut_commission"
                                            value="{{ $commissionagrement?->debut_commission?->format('Y-m-d') ?? old('debut_commission') }}"
                                            class="form-control form-control-sm @error('debut_commission') is-invalid @enderror"
                                            id="debut_commission" placeholder="Date">
                                        @error('debut_commission')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-6">
                                        <label class="form-label">Date fin commission</label>
                                        <input type="date" name="fin_commission"
                                            value="{{ $commissionagrement?->fin_commission?->format('Y-m-d') ?? old('fin_commission') }}"
                                            class="form-control form-control-sm @error('fin_commission') is-invalid @enderror"
                                            id="fin_commission" placeholder="Date">
                                        @error('fin_commission')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-6">
                                        <label class="form-label">Région</label>
                                        <input type="text" name="description"
                                            value="{{ $commissionagrement?->description ?? old('description') }}"
                                            class="form-control form-control-sm @error('description') is-invalid @enderror"
                                            id="description" placeholder="Ex: Dakar">
                                        @error('description')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-6">
                                        <label class="form-label">Lieu commission</label>
                                        <input type="text" name="lieu"
                                            value="{{ $commissionagrement?->lieu ?? old('lieu') }}"
                                            class="form-control form-control-sm @error('lieu') is-invalid @enderror"
                                            id="lieu" placeholder="Adresse exacte de la commission">
                                        @error('lieu')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    {{-- <div class="col-6">
                                        <label class="form-label">Date fin agrément</label>
                                        <input type="date" name="date"
                                            value="{{ $commissionagrement?->date?->format('Y-m-d') ?? old('date') }}"
                                            class="form-control form-control-sm @error('date') is-invalid @enderror"
                                            id="date" placeholder="date">
                                        @error('date')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div> --}}

                                    <div class="col-6">
                                        <label class="form-label">statut<span class="text-danger mx-1">*</span></label>
                                        <select name="statut"
                                            class="form-select form-select-sm @error('statut') is-invalid @enderror"
                                            aria-label="Select" id="select-field" data-placeholder="Choisir statut">
                                            <option value="{{ $commissionagrement?->statut ?? old('statut') }}">
                                                {{ $commissionagrement?->statut ?? old('statut') }}
                                            </option>
                                            <option value="Ouvert">
                                                Ouvert
                                            </option>
                                            <option value="Fermé">
                                                Fermé
                                            </option>
                                            <option value="En attente">
                                                En attente
                                            </option>
                                            <option value="En cours">
                                                En cours
                                            </option>
                                            <option value="Expiré">
                                                Expiré
                                            </option>
                                        </select>
                                        @error('statut')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-6">
                                        <label for="membre" class="form-label">Président(e)</label>
                                        <select name="membre"
                                            class="form-select form-select-sm @error('membre') is-invalid @enderror"
                                            aria-label="Sélectionner le président" id="select-field-membre"
                                            data-placeholder="Choisir le président" aria-describedby="membre-error">

                                            {{-- Option par défaut --}}
                                            <option value="" disabled selected>Choisir le président</option>

                                            {{-- Liste des employés --}}
                                            @foreach ($commissionmembres as $membre)
                                                <option value="{{ $membre->id }}"
                                                    {{ isset($commissionagrement?->chef_id) && $commissionagrement->chef_id == $membre->id ? 'selected' : '' }}>
                                                    {{ $membre->civilite . ' ' . $membre->prenom . ' ' . $membre->nom }}
                                                </option>
                                            @endforeach
                                        </select>

                                        @error('membre')
                                            <span class="invalid-feedback" role="alert" id="membre-error">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-6">
                                        <label for="secretaire" class="form-label">Secrétaire</label>
                                        <select name="secretaire"
                                            class="form-select form-select-sm @error('secretaire') is-invalid @enderror"
                                            aria-label="Sélectionner le secrétaire" id="select-field-secretaire"
                                            data-placeholder="Choisir le secrétaire" aria-describedby="secretaire-error">

                                            {{-- Option par défaut --}}
                                            <option value="" disabled selected>Choisir le secrétaire</option>

                                            {{-- Liste des employés --}}
                                            @foreach ($commissionmembreSecretaire as $secretaire)
                                                <option value="{{ $secretaire->id }}"
                                                    {{ isset($commissionagrement?->secretaire_id) && $commissionagrement->secretaire_id == $secretaire->id ? 'selected' : '' }}>
                                                    {{ $secretaire->civilite . ' ' . $secretaire->prenom . ' ' . $secretaire->nom }}
                                                </option>
                                            @endforeach
                                        </select>

                                        @error('secretaire')
                                            <span class="invalid-feedback" role="alert" id="secretaire-error">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <label for="recommendations" class="form-label">Recommandations</label>
                                        <textarea name="recommendations" id="recommendations" rows="5"
                                            class="form-control form-control-sm @error('recommendations') is-invalid @enderror"
                                            placeholder="Mettre les recommandations ici">{{ old('recommendations', $commissionagrement?->recommandations) }}</textarea>

                                        @error('recommendations')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>


                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary btn-sm"
                                    data-bs-dismiss="modal">Fermer</button>
                                <button type="submit" class="btn btn-primary btn-sm">Enregister les
                                    modifications</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </section>

@endsection
@push('scripts')
    <script>
        new DataTable('#table-agrements', {
            layout: {
                topStart: {
                    buttons: ['csv', 'excel', 'print'],
                }
            },
            "order": [
                [0, 'desc']
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
