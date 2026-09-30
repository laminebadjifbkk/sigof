@extends('layout.user-layout')
@section('title', 'ONFP | DEMANDES ' . $annee)
@section('space-work')
    @can('inscriptioncontact-view')
        <section class="section register">
            <div class="row justify-content-center">
                <h4 class="card-title">
                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-1 p-3 bg-light rounded shadow-sm gap-2">
                        <span>
                            <a href="{{ route('formulaires.index') }}" class="btn btn-outline-secondary btn-sm me-2">
                                <i class="bi bi-arrow-left"></i> Années
                            </a>
                            Liste des demandes prises en charge – {{ $annee }}
                        </span>
                        <span>{{ $totalFormulaires }}</span>
                    </div>
                </h4>

                {{-- Cartes par statut --}}
                <div class="col-12">
                    <div class="row">
                        @foreach ($grouperStatut as $statut => $items)
                            <div class="col-12 col-md-4 col-lg-2 col-sm-12 col-xs-12 col-xxl-2">
                                <div class="card info-card sales-card shadow-sm" style="max-width: 220px;">
                                    <div class="card-body p-2">
                                        <h5 class="card-title text-truncate mb-1" title="{{ $statut }}"
                                            style="font-size: 1rem;">
                                            {{ $statut }}
                                        </h5>

                                        <div class="d-flex align-items-center mb-2">
                                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-primary text-white"
                                                style="width: 32px; height: 32px; font-size: 1.25rem;">
                                                <i class="bi bi-people"></i>
                                            </div>

                                            <div class="ps-2">
                                                <h6 class="mb-0" style="font-size: 0.9rem;">
                                                    {{ number_format($items->count(), 0, '', ' ') }}
                                                </h6>
                                                <span class="text-muted small">demandeur(s)</span><br>
                                                <span class="badge bg-light text-dark mt-1" style="font-size: 0.75rem;">
                                                    {{ $statutPourcentages[$statut]['percent'] }}%
                                                </span>
                                            </div>
                                        </div>

                                        <div class="d-flex flex-wrap gap-2 mt-2">
                                            <a href="{{ route('formulaires.showstatut', $statut) }}"
                                                class="btn btn-outline-primary btn-sm d-flex align-items-center justify-content-center py-1"
                                                style="font-size: 0.85rem; gap: 6px; flex: 1 1 48%;">
                                                Voir plus <i class="bi bi-arrow-right-short"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Tableau par région --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-primary">
                            <tr>
                                <th style="width: 50px;" class="text-center">N°</th>
                                <th>Région</th>
                                <th style="width: 50px;" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($groupes as $region => $items)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $region }}</td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="{{ route('formulaires.showregion', $region) }}"
                                                class="btn btn-warning btn-sm" title="Voir les détails">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Modal générer un rapport (inchangé) --}}
            @php
                $champsRecherche = [
                    ['prenom', 'Prénom', 'text', 'prenom', 'Prénom', ''],
                    ['nom', 'Nom', 'text', 'nom', 'Nom', ''],
                    ['cin', 'N° CIN', 'text', 'cin2', 'Ex: 1099200500012', 'minlength=9 maxlength=14 autocomplete=off'],
                    ['telephone', 'Téléphone', 'text', 'telephone_responsable', 'Téléphone', 'maxlength=12 autocomplete=tel'],
                    ['email', 'Email', 'email', 'email', 'email@email.com', ''],
                    ['lieu_naissance', 'Lieu naissance', 'text', 'lieu_naissance', 'Lieu de naissance', ''],
                ];
            @endphp
            <div class="modal fade" id="generate_rapport" tabindex="-1" role="dialog"
                aria-labelledby="generate_rapportLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Générer une recherche<span class="text-danger mx-1">*</span></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form method="post" action="{{ route('formulaires.report') }}">
                            @csrf
                            <div class="modal-body">
                                <div class="row g-3">
                                    @foreach ($champsRecherche as [$name, $label, $type, $id, $placeholder, $extra])
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="{{ $id }}" class="form-label">{{ $label }}</label>
                                                <input type="{{ $type }}" name="{{ $name }}" id="{{ $id }}"
                                                    value="{{ old($name) }}" placeholder="{{ $placeholder }}"
                                                    class="form-control form-control-sm @error($name) is-invalid @enderror"
                                                    {!! $extra !!}>
                                                @error($name)
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary btn-sm"
                                    data-bs-dismiss="modal">Fermer</button>
                                <button type="submit"
                                    class="btn btn-primary btn-block submit_rapport btn-sm">Rechercher</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    @endcan
@endsection
