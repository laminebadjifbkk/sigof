@extends('layout.user-layout')
@section('title', 'Feuille de présence formation en ' . $formation?->name . ': ' . $emargement?->jour)
@section('space-work')
    <section class="section">
        <div class="row justify-content-center">
            <div class="col-lg-12">
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
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12 pt-0">
                                <span class="d-flex mt-0 align-items-baseline"><a
                                        href="{{ route('formations.show', $formation) }}" class="btn btn-success btn-sm"
                                        title="retour"><i class="bi bi-arrow-counterclockwise"></i></a>&nbsp;
                                    <p> | Feuille de présence</p>
                                </span>
                            </div>
                        </div>
                        <br>
                        <div class="d-flex justify-content-between align-items-center">
                            <h5><u><b>{{ $emargement?->jour }}</b></u></h5>
                            <span class="d-flex align-items-center gap-2">

                                @php
                                    $ids = $feuillepresences->pluck('id')->toArray();
                                    $encodedIds = urlencode(json_encode($ids));
                                @endphp

                                <a href="{{ url('ajouterDemandeursPresenceJour', [
                                    'idformation' => $formation->id,
                                    'idmodule' => $formation?->module?->id,
                                    'idlocalite' => $formation->departement->region->id,
                                    'idemargement' => $emargement->id,
                                ]) .
                                    '?ids=' .
                                    $encodedIds }}"
                                    class="btn btn-success btn-sm rounded-pill shadow-sm d-inline-flex align-items-center gap-1 px-3 py-1"
                                    title="Ajouter bénéficiaires" style="transition: all 0.3s ease;">
                                    <i class="bi bi-people"></i>
                                    <span>Ajouter</span>
                                </a>

                                <div class="dropdown">
                                    <a href="#" class="btn btn-light btn-sm" data-bs-toggle="dropdown"
                                        aria-expanded="false" title="Actions">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                        <li>
                                            <form action="{{ route('feuillePresenceTous') }}" method="post"
                                                class="px-3 py-1">
                                                @csrf
                                                <input type="hidden" name="idformation" value="{{ $formation->id }}">
                                                <input type="hidden" name="idmodule"
                                                    value="{{ $formation?->module?->id }}">
                                                <input type="hidden" name="idlocalite"
                                                    value="{{ $formation?->departement?->region?->id }}">
                                                <input type="hidden" name="idemargement" value="{{ $emargement?->id }}">
                                                <button type="submit" class="btn btn-sm w-100 show_confirm_valider">Pointer
                                                    tous</button>
                                            </form>
                                        </li>
                                        <li>
                                            <form action="{{ route('feuillePresenceJour') }}" method="post"
                                                target="_blank" class="px-3 py-1">
                                                @csrf
                                                <input type="hidden" name="idformation" value="{{ $formation->id }}">
                                                <input type="hidden" name="idmodule"
                                                    value="{{ $formation?->module?->id }}">
                                                <input type="hidden" name="idlocalite"
                                                    value="{{ $formation?->departement?->region?->id }}">
                                                <input type="hidden" name="idemargement" value="{{ $emargement?->id }}">
                                                <button type="submit" class="btn btn-sm w-100">Feuille présence</button>
                                            </form>
                                        </li>
                                        <li>
                                            <form action="{{ route('fichePresenceJour') }}" method="post" target="_blank"
                                                class="px-3 py-1">
                                                @csrf
                                                <input type="hidden" name="idformation" value="{{ $formation->id }}">
                                                <input type="hidden" name="idmodule"
                                                    value="{{ $formation?->module?->id }}">
                                                <input type="hidden" name="idlocalite"
                                                    value="{{ $formation?->departement?->region?->id }}">
                                                <input type="hidden" name="idemargement" value="{{ $emargement?->id }}">
                                                <button type="submit" class="btn btn-sm w-100">Fiche de suivi</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </span>
                        </div>
                        <br>
                        <form method="post"
                            action="{{ url('formationemargement', [
                                'idformation' => $formation?->id,
                                'idmodule' => $formation?->module?->id,
                                'idlocalite' => $formation?->departement?->region?->id,
                                'idemargement' => $emargement?->id,
                            ]) }}"
                            enctype="multipart/form-data" class="row g-3 mt-2">
                            @csrf
                            @method('PUT')
                            <div class="row mb-3">
                                <div class="form-check col-md-12">
                                    <table class="table datatables align-middle" id="table-individuelles">
                                        <thead>
                                            <tr>
                                                <th width="3%">N°</th>
                                                <th>Prénom</th>
                                                <th>NOM</th>
                                                <th>Date naissance</th>
                                                <th>Lieu naissance</th>
                                                <th>Département</th>
                                                <th style="text-align: center">Présence</th>
                                                @if (!empty($formation->projets_id))
                                                    <th>Projet</th>
                                                @endif
                                                <th width="3%"><i class="bi bi-gear"></i></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i = 1; ?>
                                            @foreach ($feuillepresences as $feuillepresence)
                                                <tr>
                                                    <td>{{ $i++ }}</td>
                                                    <td>{{ $feuillepresence?->individuelle?->user?->firstname }}</td>
                                                    <td>{{ $feuillepresence?->individuelle?->user?->name }}</td>
                                                    <td>{{ $feuillepresence?->individuelle?->user?->date_naissance?->format('d/m/Y') }}
                                                    </td>
                                                    <td>{{ $feuillepresence?->individuelle?->user?->lieu_naissance }}</td>
                                                    <td>{{ $feuillepresence?->individuelle?->departement?->nom }}</td>
                                                    <td class="text-center">
                                                        {{-- @foreach ($feuillepresence?->individuelle?->feuillepresences as $feuillepresence) --}}
                                                        @if (in_array($feuillepresence?->emargements_id, $feuillepresenceIndividuelle))
                                                            <span
                                                                class="badge 
                                                                        {{ $feuillepresence?->presence === 'Oui'
                                                                            ? 'bg-success'
                                                                            : ($feuillepresence?->presence === 'Non'
                                                                                ? 'bg-danger'
                                                                                : 'bg-default') }}">
                                                                {{ $feuillepresence?->presence }}
                                                            </span>
                                                        @endif
                                                        {{-- @endforeach --}}
                                                    </td>
                                                    @if (!empty($formation->projets_id))
                                                        <td>{{ $feuillepresence?->individuelle?->projet?->sigle }}</td>
                                                    @endif
                                                    <td>
                                                        <div class="d-flex align-items-baseline gap-2">
                                                            <a href="{{ route('individuelles.show', $feuillepresence?->individuelle) }}"
                                                                class="btn btn-primary btn-sm" title="Voir détails"
                                                                target="_blank" rel="noopener noreferrer">
                                                                <i class="bi bi-eye"></i>
                                                            </a>

                                                            <div class="dropdown">
                                                                <a href="#" class="btn btn-light btn-sm"
                                                                    data-bs-toggle="dropdown" aria-expanded="false"
                                                                    title="Actions">
                                                                    <i class="bi bi-three-dots-vertical"></i>
                                                                </a>
                                                                <ul
                                                                    class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                                                    <li>
                                                                        <button type="button" class="dropdown-item"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#PresenceModal{{ $feuillepresence?->individuelle->id }}">
                                                                            Pointer
                                                                        </button>
                                                                    </li>
                                                                    <li>
                                                                        <form
                                                                            action="{{ route('feuillepresences.destroy', $feuillepresence?->individuelle->id) }}"
                                                                            method="POST" class="m-0 p-0">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="submit"
                                                                                class="dropdown-item show_confirm"
                                                                                title="Supprimer">
                                                                                Supprimer
                                                                            </button>
                                                                        </form>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    {{--  <div class="text-center">
                                        <button type="submit" class="btn btn-outline-primary btn-sm"><i
                                                class="bi bi-check2-circle"></i>&nbsp;Sélectionner</button>
                                    </div> --}}
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        {{-- @foreach ($formation?->individuelles as $individuelle)
            <div class="modal fade" id="PresenceModal{{ $individuelle->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form method="post" action="{{ route('feuillepresences.update', $individuelle->id) }}"
                            enctype="multipart/form-data" class="row">
                            @csrf
                            @method('patch')
                            <div class="card-header text-center bg-gradient-default">
                                <h3 class="h4 text-black mb-0">
                                    {{ $individuelle?->user?->civilite . ' ' . $individuelle?->user?->firstname . ' ' . $individuelle?->user?->name }}
                                </h3>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="idemargement" value="{{ $emargement->id }}">
                                <input type="hidden" name="pointeur" value="0">
                                <label for="motif" class="form-label">Présence<span
                                        class="text-danger mx-1">*</span></label>
                                <select name="presence" class="form-select  @error('presence') is-invalid @enderror"
                                    aria-label="Select" id="select-field-feuille_presence" data-placeholder="Choisir">
                                    @foreach ($individuelle?->feuillepresences->unique('presence') as $feuillepresence)
                                        <option value="{{ $feuillepresence?->presence ?? old('presence') }}">
                                            {{ in_array($feuillepresence?->emargements_id, $feuillepresenceIndividuelle) ? $feuillepresence?->presence : '' }}
                                        </option>
                                    @endforeach
                                    <option value="Oui">Oui</option>
                                    <option value="Non">Non</option>
                                </select>
                                @error('motif')
                                    <span class="invalid-feedback" role="alert">
                                        <div>{{ $message }}</div>
                                    </span>
                                @enderror
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary btn-sm"
                                    data-bs-dismiss="modal">Fermer</button>
                                <button type="submit" class="btn btn-success btn-sm">Valider</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach --}}
        @foreach ($formation?->individuelles as $individuelle)
            <div class="modal fade" id="PresenceModal{{ $individuelle->id }}" tabindex="-1"
                aria-labelledby="presenceModalLabel{{ $individuelle->id }}" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content shadow rounded-3">
                        <form method="POST" action="{{ route('feuillepresences.update', $individuelle->id) }}"
                            enctype="multipart/form-data" class="needs-validation" novalidate>
                            @csrf
                            @method('PATCH')
                            <div class="modal-header bg-default rounded-top">
                                <h5 class="modal-title text-center w-100" id="presenceModalLabel{{ $individuelle->id }}">
                                    {{ $individuelle?->user?->civilite . ' ' . $individuelle?->user?->firstname . ' ' . $individuelle?->user?->name }}
                                </h5>
                            </div>

                            <div class="modal-body">
                                <input type="hidden" name="idemargement" value="{{ $emargement->id }}">
                                <input type="hidden" name="pointeur" value="0">

                                <div class="mb-3">
                                    <label for="selectPresence{{ $individuelle->id }}"
                                        class="form-label fw-semibold">Pointer <span class="text-danger">*</span></label>
                                    <select id="selectPresence{{ $individuelle->id }}" name="presence"
                                        class="form-select form-select-sm @error('presence') is-invalid @enderror"
                                        required>
                                        <option value="" disabled selected hidden>--Choisir--</option>
                                        @foreach ($individuelle?->feuillepresences->unique('presence') as $feuillepresence)
                                            <option value="{{ $feuillepresence?->presence }}">
                                                {{ in_array($feuillepresence?->emargements_id, $feuillepresenceIndividuelle) ? $feuillepresence?->presence : '' }}
                                            </option>
                                        @endforeach
                                        <option value="Oui">Oui</option>
                                        <option value="Non">Non</option>
                                        <option value="">Dépointer</option>
                                    </select>
                                    @error('presence')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="modal-footer d-flex justify-content-between">
                                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                                    <i class="bi bi-x-circle"></i> Fermer
                                </button>
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="bi bi-check-circle"></i> Valider
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach

    </section>
@endsection
