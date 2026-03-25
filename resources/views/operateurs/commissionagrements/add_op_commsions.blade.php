@extends('layout.user-layout')
@section('title', 'Choix des opérateurs dans la commission')
@section('space-work')
    <section class="section">
        <div class="row justify-content-center">
            <div class="col-lg-12">

                {{-- Messages succès / erreurs --}}
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

                        {{-- Header avec lien retour et nom commission --}}
                        <div class="d-flex align-items-center mb-3">
                            <a href="{{ route('commissionagrements.show', $commissionagrement->id) }}"
                                class="btn btn-success btn-sm me-2">
                                <i class="bi bi-arrow-counterclockwise"></i> Retour
                            </a>
                            <p class="mb-0">Commission : <strong>{{ $commissionagrement?->commission }}</strong></p>
                        </div>

                        {{-- Opérateurs choisis --}}
                        @if ($commissionagrement->operateurs->isNotEmpty())
                            <div class="p-2 mb-4 border rounded bg-light shadow-sm text-center fw-semibold">
                                Opérateurs choisis : <span
                                    class="fs-5 text-dark">{{ count($commissionagrement->operateurs) }}</span>
                            </div>
                        @endif

                        {{-- Vérification si la liste est vide --}}
                        @if ($operateurs->isEmpty())
                            <div class="alert alert-info bg-info text-light border-0 alert-dismissible fade show"
                                role="alert">
                                <strong>Aucun opérateur n'est encore imputé à cet agrément.</strong>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @else
                            {{-- Formulaire principal PUT --}}
                            <form method="POST"
                                action="{{ route('commisionagrement.give', ['idcommissionagrement' => $commissionagrement->id]) }}">
                                @csrf
                                @method('PUT') {{-- Laravel transformera en PUT correctement --}}

                                <div class="table-responsive">
                                    <table class="table datatables align-middle">
                                        <thead class="table-primary">
                                            <tr>
                                                <th width="3%">
                                                    <input type="checkbox" class="form-check-input" id="checkAll">
                                                </th>
                                                <th>N°</th>
                                                <th>N° agrément</th>
                                                <th>Opérateurs</th>
                                                {{-- <th>Sigle</th> --}}
                                                <th class="text-center">Modules</th>
                                                <th class="text-center">Type</th>
                                                <th class="text-center">Catégorie</th>
                                                <th width="2%"><i class="bi bi-gear"></i></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($operateurs as $i => $operateur)
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" name="operateurs[]"
                                                            value="{{ $operateur->id }}"
                                                            class="form-check-input choisir-tout-checkbox"
                                                            {{ in_array($operateur->id, $operateursSelectionnes ?? []) ? 'checked' : '' }}>
                                                    </td>
                                                    <td>{{ $i + 1 }}</td>
                                                    <td>{{ $operateur->numero_agrement }}</td>
                                                    <td>{{ $operateur?->user?->display_operateur }}</td>
                                                    {{-- <td>{{ $operateur?->user?->username }}</td> --}}
                                                    <td class="text-center">
                                                        @if ($operateur?->operateurmodules->isNotEmpty())
                                                            <span
                                                                class="badge bg-info">{{ $operateur->operateurmodules->count() }}</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        <span
                                                            class="{{ $operateur->type_demande }}">{{ $operateur->type_demande }}</span>
                                                    </td>
                                                    <td>
                                                        {{ $operateur?->user?->categorie }}
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center gap-1">
                                                            {{-- Voir détails --}}
                                                            <a href="{{ route('operateurs.show', $operateur) }}"
                                                                class="btn btn-primary btn-sm" title="Voir détails">
                                                                <i class="bi bi-eye"></i>
                                                            </a>

                                                            {{-- Modifier --}}
                                                            <a href="{{ route('operateurs.update', $operateur) }}"
                                                                class="btn btn-secondary btn-sm" title="Modifier">
                                                                <i class="bi bi-pencil"></i>
                                                            </a>

                                                            {{-- Détacher (ouvre nouvelle fenêtre) --}}
                                                            <a href="{{ route('commisionagrement.detachOperateur', [
                                                                'commission' => $commissionagrement->id,
                                                                'operateur' => $operateur->id,
                                                            ]) }}"
                                                                target="_blank" class="btn btn-outline-danger btn-sm"
                                                                onclick="return confirm('Voulez-vous vraiment détacher cet opérateur ?');"
                                                                title="Détacher">
                                                                <i class="bi bi-x-circle"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                {{-- Bouton Mettre à jour --}}
                                <div class="text-center mt-3">
                                    <button type="submit" class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-check2-circle"></i> Mettre à jour la commission
                                    </button>
                                </div>
                            </form>

                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
