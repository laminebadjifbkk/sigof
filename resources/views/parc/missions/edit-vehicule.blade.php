@extends('layout.user-layout')
@section('title', 'ONFP - Mise à jour des véhicules de la mission')

@section('space-work')
    <section class="section register">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="mb-0">Véhicules de la mission : {{ $mission->reference }}</h3>
                <a href="{{ route('parc-missions.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left-circle"></i> Retour à la liste
                </a>
            </div>

            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('parc-missions.vehicules.update', $mission) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Boutons sélectionner/désélectionner tout -->
                        <div class="mb-3">
                            <button type="button" id="select-all" class="btn btn-sm btn-info">
                                <i class="bi bi-check2-square"></i> Sélectionner tout
                            </button>
                            <button type="button" id="deselect-all" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-x-square"></i> Désélectionner tout
                            </button>
                        </div>

                        {{-- @foreach ($vehicules as $vehicule)
                            <div class="row mb-2 align-items-center">
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input type="checkbox" class="vehicle-checkbox"
                                            name="vehicules[{{ $vehicule->id }}][id]" value="{{ $vehicule->id }}"
                                            {{ $mission->vehicules->contains($vehicule->id) ? 'checked' : '' }}>
                                        <label class="form-check-label">
                                            {{ $vehicule->immatriculation }} - {{ $vehicule->marque }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        @endforeach --}}
                        @foreach ($vehicules as $vehicule)
                            <div class="row mb-2 align-items-center">
                                <div class="col-md-6">
                                    <div class="form-check">
                                        {{-- <input type="checkbox" class="vehicle-checkbox"
                                            name="vehicules[{{ $vehicule->id }}][id]" value="{{ $vehicule->id }}"
                                            {{ $mission->vehicules->contains($vehicule->id) ? 'checked' : '' }}> --}}
                                        <input type="checkbox" class="vehicle-checkbox"
                                            name="vehicules[{{ $vehicule->id }}][id]" value="{{ $vehicule->id }}"
                                            {{ $mission->vehicules->contains($vehicule->id) ? 'checked' : '' }}
                                            {{ $vehicule->etat === 'en_mission' ? 'disabled' : '' }}>

                                        <label class="form-check-label">
                                            {{ $vehicule->immatriculation }} - {{ $vehicule->marque }}
                                            <span class="{{ $vehicule->etat }}">
                                                - {{ ucfirst(str_replace('_', ' ', $vehicule->etat)) }}
                                            </span>

                                        </label>
                                    </div>
                                </div>

                                <!-- Nombre total de missions -->
                                <div class="col-md-3 text-muted small">
                                    Total missions : <strong>{{ $vehicule->missions_total }}</strong>
                                </div>

                                <!-- Missions de l'année -->
                                <div class="col-md-3 text-muted small">
                                    Missions en {{ now()->year }} : <strong>{{ $vehicule->missions_annee }}</strong>
                                </div>
                            </div>
                        @endforeach

                        <div class="d-flex gap-2 mt-3">
                            <button type="submit" class="btn btn-success btn-sm">
                                <i class="bi bi-check-circle"></i> Mettre à jour
                            </button>
                            <a href="{{ route('parc-missions.show', $mission->id) }}" class="btn btn-secondary btn-sm">
                                <i class="bi bi-x-circle"></i> Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.getElementById('select-all').addEventListener('click', function() {
            document.querySelectorAll('.vehicle-checkbox').forEach(cb => cb.checked = true);
        });

        document.getElementById('deselect-all').addEventListener('click', function() {
            document.querySelectorAll('.vehicle-checkbox').forEach(cb => cb.checked = false);
        });
    </script>
@endpush
