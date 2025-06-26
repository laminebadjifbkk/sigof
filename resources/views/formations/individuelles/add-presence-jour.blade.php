@extends('layout.user-layout')
@section('title', 'Choisir bénéficiaires à la formation en ' . $formation->module->name)
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
                            role="alert">
                            <strong>{{ $error }}</strong>
                        </div>
                    @endforeach
                @endif
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12 pt-0">
                                <span class="d-flex mt-0 align-items-baseline">
                                    <a href="{{ route('formations.show', $formation) }}" class="btn btn-success btn-sm"
                                        title="Retour">
                                        <i class="bi bi-arrow-counterclockwise"></i>
                                    </a>&nbsp;
                                    <p> | Liste des bénéficiaires</p>
                                </span>
                            </div>
                        </div>
                        {{-- <div class="p-1 mb-4 border rounded bg-light shadow-sm">
                            <div class="row text-center fw-semibold">
                                <div class="col-md-4 mb-2">
                                    <span class="text-secondary">📍 Région</span><br>
                                    <span class="fs-5 text-dark">{{ $region->nom ?? 'Aucune' }}</span>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <span class="text-secondary">📘 Module</span><br>
                                    <span class="fs-5 text-dark">{{ $formation?->module?->name ?? 'Aucun' }}</span>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <span class="text-secondary">👥 Effectif</span><br>
                                    <span class="fs-5 text-dark">{{ $candidatsretenus?->count() ?? 0 }}</span>
                                </div>
                            </div>
                        </div> --}}

                        <div class="p-3 mb-4 border rounded bg-light shadow-sm">
                            <div class="row text-center fw-semibold">
                                <div class="col-md-2 mb-2">
                                    <span class="text-secondary">📅 Jour</span><br>
                                    <span class="fs-5 text-dark">
                                        {{ $emargement?->jour ?? 'Aucun' }}
                                    </span>
                                </div>
                                <div class="col-md-2 mb-2">
                                    <span class="text-secondary">📍 Région</span><br>
                                    <span class="fs-5 text-dark">{{ $localite?->nom ?? 'Aucune' }}</span>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <span class="text-secondary">📘 Module</span><br>
                                    <span class="fs-5 text-dark">{{ $module?->name ?? 'Aucun' }}</span>
                                </div>
                                <div class="col-md-2 mb-2">
                                    <span class="text-secondary">👥 Effectif</span><br>
                                    <span class="fs-5 text-dark">{{ $candidatsretenus?->count() ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                        <form method="post"
                            action="{{ url('ajouterDemandeursPresenceJour', [
                                'idformation' => $formation->id,
                                'idmodule' => $formation->module->id,
                                'idlocalite' => $formation->departement->id,
                                'idemargement' => $emargement->id,
                            ]) }}"
                            enctype="multipart/form-data" class="row g-3 mt-2">
                            @csrf
                            @method('PUT')

                            @if ($individuelles->isNotEmpty())

                                <div class="form-check col-md-12 border rounded bg-light shadow-sm p-3">
                                    <div class="form-check col-md-2 pt-5">
                                        <label for="#">Choisir tout</label>
                                        <input type="checkbox" class="form-check-input" id="checkAll">
                                    </div>
                                    <div></div>
                                    <table class="m-2 table datatables align-middle" id="table-individuelles">
                                        <thead>
                                            <tr>
                                                <th>N°</th>
                                                <th>Civilité</th>
                                                <th>Prénom et NOM</th>
                                                <th>Date naissance</th>
                                                <th>Lieu naissance</th>
                                                <th>Département</th>
                                                <th>Module</th>
                                                <th>Note</th>
                                                <th>Statut</th>
                                                @if (!empty($formation->projets_id))
                                                    <th>Projet</th>
                                                @endif
                                                <th width='5%'><i class="bi bi-gear"></i></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i = 1; ?>
                                            @foreach ($individuelles as $individuelle)
                                                @if (!empty($individuelle?->numero))
                                                    <tr>
                                                        {{-- <td>
                                                            <input type="checkbox" name="individuelles[]"
                                                                value="{{ $individuelle->id }}"
                                                                {{ in_array($individuelle->formations_id, $individuelleFormation) ? 'checked' : '' }}
                                                                {{ in_array($individuelle->formations_id, $individuelleFormationCheck) ? 'disabled' : '' }}
                                                                class="form-check-input @error('individuelles') is-invalid @enderror">
                                                            {{ $i++ }}
                                                            @error('individuelles')
                                                                <span class="invalid-feedback"
                                                                    role="alert">{{ $message }}</span>
                                                            @enderror
                                                        </td> --}}


                                                        <td>
                                                            {{-- <input type="checkbox" name="listecollectives[]"
                                                            value="{{ $listecollective->id }}"
                                                            {{ in_array($listecollective->id, $listecollectiveCochees) ? 'checked' : '' }}
                                                            class="form-check-input @error('listecollectives') is-invalid @enderror">
                                                        @error('listecollectives')
                                                            <span class="invalid-feedback" role="alert">
                                                                <div>{{ $message }}</div>
                                                            </span>
                                                        @enderror
                                                        {{ $i++ }} --}}
                                                            @php
                                                                $isChecked = in_array(
                                                                    $individuelle->id,
                                                                    $individuelleCochees,
                                                                );
                                                            @endphp

                                                            <label for="liste_{{ $individuelle->id }}">
                                                                <input id="liste_{{ $individuelle->id }}" type="checkbox"
                                                                    name="individuelles[]" value="{{ $individuelle->id }}"
                                                                    {{ $isChecked ? 'checked' : '' }}
                                                                    class="form-check-input @error('individuelles') is-invalid @enderror">
                                                                {{ $i++ }}
                                                            </label>
                                                        </td>
                                                        <td>{{ $individuelle?->user?->civilite }}</td>
                                                        <td>{{ $individuelle?->user?->firstname . ' ' . $individuelle?->user?->name }}
                                                        </td>
                                                        <td>{{ $individuelle?->user->date_naissance?->format('d/m/Y') }}
                                                        </td>
                                                        <td>{{ $individuelle?->user->lieu_naissance }}</td>
                                                        <td>{{ $individuelle?->departement->nom }}</td>
                                                        <td>{{ $individuelle?->module->name }}</td>
                                                        <td>{{ $individuelle?->note }}</td>
                                                        <td><span
                                                                class="{{ $individuelle?->statut }}">{{ $individuelle?->statut }}</span>
                                                        </td>
                                                        @if (!empty($formation->projets_id))
                                                            <td>{{ $individuelle?->projet?->sigle }}</td>
                                                        @endif
                                                        <td>
                                                            <span class="d-flex align-items-baseline">
                                                                <a href="{{ route('individuelles.show', $individuelle) }}"
                                                                    class="btn btn-primary btn-sm" title="Voir détails"
                                                                    target="_blank">
                                                                    <i class="bi bi-eye"></i>
                                                                </a>
                                                                <div class="filter">
                                                                    <a class="icon" href="#"
                                                                        data-bs-toggle="dropdown"><i
                                                                            class="bi bi-three-dots"></i></a>
                                                                    <ul
                                                                        class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                                                        <li>
                                                                            <a class="dropdown-item btn btn-sm"
                                                                                href="{{ route('individuelles.edit', $individuelle) }}"
                                                                                title="Modifier">
                                                                                <i class="bi bi-pencil"></i> Modifier
                                                                            </a>
                                                                        </li>
                                                                        <li>
                                                                            <form
                                                                                action="{{ route('individuelles.destroy', $individuelle) }}"
                                                                                method="post">
                                                                                @csrf
                                                                                <button type="submit"
                                                                                    class="dropdown-item show_confirm"
                                                                                    title="Supprimer">
                                                                                    <i class="bi bi-trash"></i> Supprimer
                                                                                </button>
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
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-outline-primary btn-sm"><i
                                                class="bi bi-check2-circle"></i>&nbsp;Ajouter à la fiche du
                                            {{ $emargement?->jour }}</button>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-warning mt-5">Aucun demandeur disponible pour le moment !!!</div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
