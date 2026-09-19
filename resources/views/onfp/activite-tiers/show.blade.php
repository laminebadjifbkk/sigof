@extends('layout.user-layout')

@section('title', $tiers->nom)

@section('space-work')

    <div class="container-fluid py-4">

        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">

            <div class="d-flex align-items-center">

                <a href="{{ route('onfp.tiers.index') }}"
                    class="btn btn-sm btn-outline-secondary me-3" title="Retour">
                    <i class="bi bi-arrow-left"></i>
                </a>

                <div>
                    <h4 class="mb-0">{{ $tiers->nom }}</h4>
                    <p class="text-muted mb-0 mt-1">
                        {{ $tiers->fonction ?: 'Fonction non renseignée' }}
                        @if ($tiers->organisation)
                            · {{ $tiers->organisation }}
                        @endif
                    </p>
                </div>

            </div>

            <a href="{{ route('onfp.tiers.edit', $tiers) }}" class="btn btn-sm btn-primary">
                <i class="bi bi-pencil me-1"></i>
                Modifier
            </a>

        </div>


        <div class="row g-4">

            <div class="col-lg-8">

                <div class="card shadow-sm border-0 mb-4">

                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0">
                            <i class="bi bi-diagram-3 me-2"></i>
                            Activités associées
                        </h5>
                    </div>

                    <div class="card-body">

                        @forelse ($tiers->activiteTiers as $activiteTier)
                            <div class="border rounded p-3 mb-3">

                                <div class="d-flex justify-content-between align-items-start">

                                    <div>
                                        <a href="{{ route('onfp.activites.show', $activiteTier->activite) }}"
                                            class="fw-semibold text-decoration-none">
                                            {{ $activiteTier->activite->titre ?? 'Activité supprimée' }}
                                        </a>

                                        @if ($activiteTier->role)
                                            <div class="small text-muted mt-1">
                                                Rôle : {{ $activiteTier->role }}
                                            </div>
                                        @endif

                                        @if ($activiteTier->observation)
                                            <div class="small text-muted mt-1">
                                                {{ $activiteTier->observation }}
                                            </div>
                                        @endif
                                    </div>

                                </div>

                            </div>
                        @empty
                            <div class="text-center text-muted py-4">
                                <i class="bi bi-diagram-3 fs-2 d-block mb-2"></i>
                                Ce tiers n'est associé à aucune activité pour le moment.
                            </div>
                        @endforelse

                    </div>

                </div>

            </div>


            <div class="col-lg-4">

                <div class="card shadow-sm border-0 mb-4">

                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0">
                            <i class="bi bi-info-circle me-2"></i>
                            Coordonnées
                        </h5>
                    </div>

                    <div class="card-body">

                        <div class="mb-3">
                            <div class="text-muted small">Type</div>
                            <div class="fw-semibold">
                                {{ \App\Models\OnfpTiers::TYPES[$tiers->type] ?? ($tiers->type ?: '-') }}
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="text-muted small">Téléphone</div>
                            <div class="fw-semibold">{{ $tiers->telephone ?: '-' }}</div>
                        </div>

                        <div class="mb-3">
                            <div class="text-muted small">Email</div>
                            <div class="fw-semibold">{{ $tiers->email ?: '-' }}</div>
                        </div>

                        <div class="mb-3">
                            <div class="text-muted small">Adresse</div>
                            <div class="fw-semibold">{{ $tiers->adresse ?: '-' }}</div>
                        </div>

                        <div>
                            <div class="text-muted small">État</div>
                            @if ($tiers->actif)
                                <span class="badge bg-success-subtle text-success">Actif</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary">Inactif</span>
                            @endif
                        </div>

                    </div>

                </div>

                @if ($tiers->observation)
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0">
                                <i class="bi bi-chat-left-text me-2"></i>
                                Observation
                            </h5>
                        </div>
                        <div class="card-body">
                            {!! nl2br(e($tiers->observation)) !!}
                        </div>
                    </div>
                @endif

            </div>

        </div>

    </div>

@endsection
