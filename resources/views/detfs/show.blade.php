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
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5>Informations générales</h5>
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Intitulé :</label>
                            <p>{{ $detf->titre1 }}</p>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Bénéficiaires :</label>
                            <p>{{ $detf->titre2 }}</p>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Opérateur :</label>
                            <p>{{ $detf->operateur?->user?->operateur ?? '-' }}</p>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Niveau de qualification :</label>
                            <p>{{ $detf->niveau_qualification ?? '-' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Ingénieur :</label>
                            <p>{{ $detf->ingenieur?->user?->firstname ?? '' }} {{ $detf->ingenieur?->user?->name ?? '' }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Date :</label>
                            <p>{{ $detf->date1?->format('d/m/Y') ?? '-' }}</p>
                        </div>
                    </div>
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
