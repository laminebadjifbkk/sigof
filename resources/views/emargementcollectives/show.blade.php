@extends('layout.user-layout')
@section('title', 'Feuille de présence formation en ' . $formation?->name . ': ' . $emargementcollective?->jour)
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
                                        href="{{ route('formations.show', $formation->id) }}" class="btn btn-success btn-sm"
                                        title="retour"><i class="bi bi-arrow-counterclockwise"></i></a>&nbsp;
                                    <p> | Feuille de présence</p>
                                </span>
                            </div>
                        </div>
                        <br>
                        <div class="d-flex justify-content-between align-items-center">
                            <h5><u><b>{{ $emargementcollective?->jour }}</b></u></h5>
                            <span class="d-flex align-items-baseline">
                                <form action="{{ route('feuillePresenceColJour') }}" method="post" target="_blank">
                                    @csrf
                                    <input type="hidden" name="idformation" value="{{ $formation->id }}">
                                    <input type="hidden" name="idmodule" value="{{ $formation?->module?->id }}">
                                    <input type="hidden" name="idlocalite"
                                        value="{{ $formation?->departement?->region?->id }}">
                                    <input type="hidden" name="idemargement" value="{{ $emargementcollective?->id }}">
                                    <button class="btn btn-secondary btn-sm mx-1">Feuille présence</button>
                                </form>
                                <div class="filter">
                                    <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                            class="bi bi-three-dots"></i></a>
                                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                        <li>
                                            <form action="{{ route('feuillePresenceColTous') }}" method="post">
                                                @csrf
                                                <input type="hidden" name="idformation" value="{{ $formation->id }}">
                                                <input type="hidden" name="idmodule"
                                                    value="{{ $formation?->collectivemodule?->id }}">
                                                <input type="hidden" name="idlocalite"
                                                    value="{{ $formation?->departement?->region?->id }}">
                                                <input type="hidden" name="idemargement"
                                                    value="{{ $emargementcollective?->id }}">
                                                <button class="show_confirm_valider btn btn-sm mx-1">Pointer tous</button>
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
                                'idmodule' => $formation?->collectivemodule?->id,
                                'idlocalite' => $formation?->departement?->region?->id,
                                'idemargement' => $emargementcollective?->id,
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
                                                {{-- <th>CIN</th> --}}
                                                {{--  <th width="3%"> --}}
                                                {{-- <input type="checkbox" class="form-check-input" id="checkAll"> --}}
                                                {{--      Civilité
                                                </th> --}}
                                                <th>Prénom</th>
                                                <th>NOM</th>
                                                <th>Date naissance</th>
                                                <th>Lieu naissance</th>
                                                {{-- <th>Adresse</th> --}}
                                                {{-- <th>Module</th> --}}
                                                <th style="text-align: center">Présence</th>
                                                {{-- @if (!empty($formation->projets_id))
                                                    <th>Projet</th>
                                                @endif --}}
                                                <th width="3%"><i class="bi bi-gear"></i></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i = 1; ?>
                                            @foreach ($formation?->listecollectives as $listecollective)
                                                @if (!empty($listecollective?->cin))
                                                    <tr>
                                                        <td>{{ $i++ }}</td>
                                                        <td>{{ $listecollective?->prenom }}</td>
                                                        <td>{{ $listecollective?->nom }}</td>
                                                        <td>{{ $listecollective?->date_naissance->format('d/m/Y') }}
                                                        </td>
                                                        <td>{{ $listecollective?->lieu_naissance }}</td>
                                                        {{-- <td style="text-align: center">
                                                            @foreach ($listecollective?->feuillepresencecollectives as $feuillepresencecollective)
                                                                <span class="{{ $feuillepresencecollective?->presence }}">
                                                                    {{ in_array($feuillepresencecollective?->emargementcollectives_id, $feuillepresenceListecollective) ? $feuillepresencecollective?->presence : '' }}
                                                                </span>
                                                            @endforeach
                                                        </td> --}}
                                                        <td class="text-center">
                                                            @foreach ($listecollective?->feuillepresencecollectives as $feuillepresencecollective)
                                                                @if (in_array($feuillepresencecollective?->emargementcollectives_id, $feuillepresenceListecollective))
                                                                    <span
                                                                        class="badge 
                                                                        {{ $feuillepresencecollective?->presence === 'Oui'
                                                                            ? 'bg-success'
                                                                            : ($feuillepresencecollective?->presence === 'Non'
                                                                                ? 'bg-danger'
                                                                                : 'bg-default') }}">
                                                                        {{ $feuillepresencecollective?->presence }}
                                                                    </span>
                                                                @endif
                                                            @endforeach
                                                        </td>
                                                        <td>
                                                            <span class="d-flex align-items-baseline"><a
                                                                    href="{{ route('listecollectives.show', $listecollective->id) }}"
                                                                    class="btn btn-primary btn-sm" title="voir détails"
                                                                    target="_blanck"><i class="bi bi-eye"></i></a>
                                                                <div class="filter">
                                                                    <a class="icon" href="#"
                                                                        data-bs-toggle="dropdown"><i
                                                                            class="bi bi-three-dots"></i></a>
                                                                    <ul
                                                                        class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                                                        <li>
                                                                            <button type="button" class="dropdown-item"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#PresenceModal{{ $listecollective->id }}">Présence
                                                                            </button>
                                                                            {{-- <button type="button" class="dropdown-item btn btn-sm"
                                                                            data-bs-toggle="modal" data-bs-target="#generate_rapport"></i>Rechercher
                                                                            plus</button> --}}
                                                                        </li>
                                                                        <li>
                                                                            <form
                                                                                action="{{ route('feuillepresencecollectives.destroy', $listecollective->id) }}"
                                                                                method="post">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                                <button type="submit"
                                                                                    class="dropdown-item show_confirm"
                                                                                    title="Supprimer">Supprimer</button>
                                                                            </form>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </span>
                                                        </td>
                                                    </tr>
                                                @endif
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
        {{-- @foreach ($formation?->listecollectives as $listecollective)
            <div class="modal fade" id="PresenceModal{{ $listecollective->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form method="post"
                            action="{{ route('feuillepresencecollectives.update', $listecollective->id) }}"
                            enctype="multipart/form-data" class="row">
                            @csrf
                            @method('patch')
                            <div class="card-header text-center bg-gradient-default">
                                <h3 class="h4 text-black mb-0">
                                    {{ $listecollective?->prenom . ' ' . $listecollective?->nom }}
                                </h3>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="idemargement" value="{{ $emargementcollective->id }}">
                                <input type="hidden" name="pointeur" value="0">
                                <label for="motif" class="form-label">Présence<span
                                        class="text-danger mx-1">*</span></label>
                                <select name="presence" class="form-select  @error('presence') is-invalid @enderror"
                                    aria-label="Select" id="select-field-feuille_presence" data-placeholder="Choisir">
                                    @foreach ($listecollective?->feuillepresencecollectives->unique('presence') as $feuillepresencecollective)
                                        <option value="{{ $feuillepresencecollective?->presence ?? old('presence') }}">
                                            {{ in_array($feuillepresencecollective?->emargementcollectives_id, $feuillepresenceListecollective) ? $feuillepresencecollective?->presence : '' }}
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

        @foreach ($formation?->listecollectives as $listecollective)
            <div class="modal fade" id="PresenceModal{{ $listecollective->id }}" tabindex="-1"
                aria-labelledby="presenceModalLabel{{ $listecollective->id }}" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content shadow rounded-3">
                        <form method="POST"
                            action="{{ route('feuillepresencecollectives.update', $listecollective->id) }}"
                            enctype="multipart/form-data" class="needs-validation" novalidate>
                            @csrf
                            @method('PATCH')

                            <div class="modal-header bg-default rounded-top">
                                <h5 class="modal-title text-center w-100"
                                    id="presenceModalLabel{{ $listecollective->id }}">
                                    {{ $listecollective?->prenom . ' ' . $listecollective?->nom }}
                                </h5>
                            </div>

                            <div class="modal-body">
                                <input type="hidden" name="idemargement" value="{{ $emargementcollective->id }}">
                                <input type="hidden" name="pointeur" value="0">

                                <div class="mb-3">
                                    <label for="selectPresence{{ $listecollective->id }}"
                                        class="form-label fw-semibold">Présence <span class="text-danger">*</span></label>
                                    <select id="selectPresence{{ $listecollective->id }}" name="presence"
                                        class="form-select form-select-sm @error('presence') is-invalid @enderror" required>
                                        <option value="" disabled selected hidden>--Choisir--</option>
                                        @foreach ($listecollective?->feuillepresencecollectives->unique('presence') as $feuillepresencecollective)
                                            @if (in_array($feuillepresencecollective?->emargementcollectives_id, $feuillepresenceListecollective))
                                                <option value="{{ $feuillepresencecollective?->presence }}">
                                                    {{ $feuillepresencecollective?->presence }}
                                                </option>
                                            @endif
                                        @endforeach
                                        <option value="Oui">Oui</option>
                                        <option value="Non">Non</option>
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
