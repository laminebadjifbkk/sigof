@extends('layout.user-layout')
@section('title', 'ONFP | Détail DETF')
@section('space-work')
    <section class="section">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="mb-0">Détail du DETF : {{ $detf->numero }}</h3>
                <a href="{{ route('detfs.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left-circle"></i> Retour
                </a>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                </div>
            @endif

            {{-- Informations principales --}}
            <div class="card shadow-sm border-0 mb-4">

                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle me-2 text-primary"></i>
                        Informations générales
                    </h5>
                </div>

                <div class="card-body">

                    <div class="row g-4">

                        {{-- Intitulé --}}
                        <div class="col-md-12">
                            <small class="text-muted">
                                <i class="bi bi-card-text me-1"></i> Intitulé de la formation
                            </small>
                            <div class="fw-semibold">
                                {{ $detf->titre1 ?? '-' }}
                            </div>
                        </div>

                        {{-- Bénéficiaires --}}
                        <div class="col-md-12">
                            <small class="text-muted">Bénéficiaires
                            </small>
                            <div class="fw-semibold">
                                {{ $detf->titre2 ?? '-' }}
                            </div>
                        </div>

                        {{-- Opérateur --}}
                        <div class="col-md-8">
                            <small class="text-muted">Opérateur</small>

                            <div class="fw-semibold">
                                {{ $detf->operateur?->user?->display_operateur ?? '-' }}
                            </div>
                        </div>

                        {{-- Ingénieur --}}
                        <div class="col-md-4">
                            <small class="text-muted">Ingénieur responsable
                            </small>
                            <div class="fw-semibold">
                                {{ $detf->ingenieur?->user?->firstname ?? '' }}
                                {{ $detf->ingenieur?->user?->name ?? '' }}
                            </div>
                        </div>

                        {{-- Niveau --}}
                        <div class="col-md-4">
                            <small class="text-muted">Niveau de qualification
                            </small>
                            <div>
                                {{ $detf->niveau_qualification ?? '-' }}
                                </span>
                            </div>
                        </div>

                        {{-- Lieu --}}
                        <div class="col-md-4">
                            <small class="text-muted">Lieu de formation
                            </small>
                            <div class="fw-semibold">
                                {{ $detf->lieu_de_formation ?? '-' }}
                            </div>
                        </div>

                        {{-- Période --}}
                        <div class="col-md-4">
                            <small class="text-muted">Période de formation
                            </small>
                            <div class="fw-semibold">
                                {{ $detf->periode_de_formation ?? '-' }}
                            </div>
                        </div>

                        {{-- Date PV --}}
                        <div class="col-md-4">
                            <small class="text-muted"> Date PV commission
                            </small>
                            <div class="fw-semibold">
                                {{ $detf->date1?->format('d/m/Y') ?? '-' }}
                            </div>
                        </div>

                        {{-- Etat --}}
                        <div class="col-md-4">
                            <small class="text-muted"> État
                            </small>
                            <div>
                                <span class="etat-btn {{ $detf?->etat }}">
                                    {{ $detf?->etat }}
                                </span>
                            </div>
                        </div>

                    </div>

                    <a href="{{ route('detfs.edit', $detf->id) }}" class="btn btn-secondary btn-sm mt-3">
                        <i class="bi bi-pencil-square"></i> Modifier
                    </a>

                </div>
            </div>

            {{-- Budget --}}
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>BUDGET PREVISIONNEL </h5>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Rubriques</th>
                                <th>Libellé</th>
                                <th>Unité</th>
                                {{-- <th>Type</th> --}}
                                <th>Quantité</th>
                                <th>Prix Unitaire</th>
                                <th>Montant</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($budgetItems as $item)
                                <tr>
                                    <td>-</td>
                                    <td>{{ $item?->label?->libelle }}</td>
                                    <td>{{ $item?->unite }}</td>
                                    {{-- <td>{{ $item?->label?->type }}</td> --}}
                                    <td>{{ $item?->quantite }}</td>
                                    <td>{{ number_format($item?->prix_unitaire, 0, ',', ' ') }}</td>
                                    <td>{{ number_format($item?->montant, 0, ',', ' ') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Aucune ligne budgétaire ajoutée</td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if ($budgetItems->count() > 0)
                            <tfoot>
                                <tr>
                                    <th colspan="5" class="text-end">Total</th>
                                    <th>
                                        {{ number_format($budgetItems->sum('montant'), 0, ',', ' ') }}
                                    </th>
                                </tr>
                            </tfoot>
                        @endif
                    </table>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <a href="{{ route('detfs.budget.edit', $detf->id) }}" class="btn btn-primary btn-sm mt-3">
                            <i class="bi bi-pencil-square"></i> Compléter / Modifier le budget
                        </a>
                        <a href="{{ route('detfs.export.word', $detf->id) }}" class="btn btn-success btn-sm mt-3">
                            <i class="bi bi-file-earmark-word"></i> Télécharger le budget Word
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
