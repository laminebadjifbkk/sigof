@extends('layout.user-layout')
@section('title', 'ONFP | PRISES EN CHARGE PAR ANNÉE SCOLAIRE')
@section('space-work')
    @can('inscriptioncontact-view')
        <section class="section register">
            <div class="row justify-content-center">
                <h4 class="card-title">
                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-1 p-3 bg-light rounded shadow-sm">
                        <span>Demandes de prise en charge par année scolaire</span>
                        <span>{{ $totalFormulaires }}</span>
                    </div>
                </h4>

                <div class="col-12">
                    <div class="row">
                        @forelse ($annees as $annee => $data)
                            <div class="col-12 col-md-6 col-xl-4">
                                <div class="card info-card sales-card shadow-sm">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h5 class="card-title p-0 m-0">{{ $annee }}</h5>
                                            <div class="text-end">
                                                <h4 class="mb-0">{{ number_format($data['total'], 0, '', ' ') }}</h4>
                                                <span class="text-muted small">demandeur(s)</span>
                                            </div>
                                        </div>

                                        <ul class="list-group list-group-flush small mb-3">
                                            @foreach ($data['statuts'] as $statut => $nb)
                                                <li class="list-group-item d-flex justify-content-between px-0 py-1">
                                                    <span class="text-truncate"
                                                        title="{{ $statut }}">{{ $statut }}</span>
                                                    <span
                                                        class="badge bg-light text-dark">{{ number_format($nb, 0, '', ' ') }}</span>
                                                </li>
                                            @endforeach
                                        </ul>

                                        <a href="{{ route('formulaires.annee', $annee) }}"
                                            class="btn btn-outline-primary btn-sm w-100">
                                            Voir les demandes <i class="bi bi-arrow-right-short"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="alert alert-info">Aucune demande enregistrée pour le moment.</div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </section>
    @endcan
@endsection
