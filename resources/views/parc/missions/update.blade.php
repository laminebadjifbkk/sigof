@extends('layout.user-layout')
@section('title', 'ONFP - Modifier une mission')

@section('space-work')
<section class="section register">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="mb-0">Modifier la mission : {{ $mission->reference }}</h1>
            <a href="{{ route('parc-missions.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left-circle"></i> Retour à la liste
            </a>
        </div>

        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
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
                <form action="{{ route('parc-missions.update', $mission) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Référence --}}
                    <div class="mb-3">
                        <label class="form-label">Référence</label>
                        <input type="text" name="reference" class="form-control form-control-sm"
                               value="{{ $mission->reference }}" readonly>
                    </div>

                    {{-- Type mission --}}
                    <div class="mb-3">
                        <label class="form-label">Type de mission</label>
                        <select name="type_mission_id" class="form-select form-select-sm">
                            <option value="">-- Choisir --</option>
                            @foreach ($typesMissions as $type)
                                <option value="{{ $type->id }}"
                                    {{ old('type_mission_id', $mission->type_mission_id) == $type->id ? 'selected' : '' }}>
                                    {{ $type->libelle }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Objet --}}
                    <div class="mb-3">
                        <label class="form-label">Objet <span class="text-danger"> *</span></label>
                        <input type="text" name="objet"
                               class="form-control form-control-sm @error('objet') is-invalid @enderror"
                               value="{{ old('objet', $mission->objet) }}">
                        @error('objet') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    {{-- Lieux --}}
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Lieu de départ <span class="text-danger"> *</span></label>
                            <input type="text" name="lieu_depart" class="form-control form-control-sm"
                                   value="{{ old('lieu_depart', $mission->lieu_depart) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Lieu d’arrivée <span class="text-danger"> *</span></label>
                            <input type="text" name="lieu_arrivee" class="form-control form-control-sm"
                                   value="{{ old('lieu_arrivee', $mission->lieu_arrivee) }}">
                        </div>
                    </div>

                    {{-- Itinéraire --}}
                    <div class="mb-3">
                        <label class="form-label">Itinéraire<span class="text-danger"> *</span></label>
                        <input type="text" name="itineraire" class="form-control form-control-sm"
                               value="{{ old('itineraire', $mission->itineraire) }}">
                    </div>

                    {{-- Département / Région --}}
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Département</label>
                            <input type="text" name="departement" class="form-control form-control-sm"
                                   value="{{ old('departement', $mission->departement) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Région</label>
                            <input type="text" name="region" class="form-control form-control-sm"
                                   value="{{ old('region', $mission->region) }}">
                        </div>
                    </div>

                    {{-- Dates --}}
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Date départ <span class="text-danger"> *</span></label>
                            <input type="date" name="date_depart" class="form-control form-control-sm"
                                   value="{{ old('date_depart', $mission->date_depart?->format('Y-m-d')) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Date retour <span class="text-danger"> *</span></label>
                            <input type="date" name="date_retour" class="form-control form-control-sm"
                                   value="{{ old('date_retour', $mission->date_retour?->format('Y-m-d')) }}">
                        </div>
                    </div>

                    {{-- Finances --}}
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Taux journalier</label>
                            <input type="number" step="0.01" name="taux_journalier"
                                   class="form-control form-control-sm"
                                   value="{{ old('taux_journalier', $mission->taux_journalier) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Frais déplacement</label>
                            <input type="number" step="0.01" name="frais_deplacement"
                                   class="form-control form-control-sm"
                                   value="{{ old('frais_deplacement', $mission->frais_deplacement) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Avance</label>
                            <input type="number" step="0.01" name="avance"
                                   class="form-control form-control-sm"
                                   value="{{ old('avance', $mission->avance) }}">
                        </div>
                    </div>

                    {{-- Indemnités calculées --}}
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Indemnités totales</label>
                            <input type="number" class="form-control form-control-sm"
                                   value="{{ $mission->indemnites_total }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Reliquat</label>
                            <input type="number" class="form-control form-control-sm"
                                   value="{{ $mission->reliquat }}" readonly>
                        </div>
                    </div>

                    {{-- Statut --}}
                    <div class="mb-3">
                        <label class="form-label">Statut <span class="text-danger"> *</span></label>
                        <select name="statut" class="form-select form-select-sm">
                            <option value="planifiee" {{ old('statut',$mission->statut)=='planifiee'?'selected':'' }}>Planifiée</option>
                            <option value="en_cours" {{ old('statut',$mission->statut)=='en_cours'?'selected':'' }}>En cours</option>
                            <option value="cloturee" {{ old('statut',$mission->statut)=='cloturee'?'selected':'' }}>Clôturée</option>
                            <option value="annulee" {{ old('statut',$mission->statut)=='annulee'?'selected':'' }}>Annulée</option>
                        </select>
                    </div>

                    {{-- Actions --}}
                    <div class="d-flex gap-2">
                        <button class="btn btn-success btn-sm">
                            <i class="bi bi-check-circle"></i> Mettre à jour
                        </button>
                        <a href="{{ route('parc-missions.index') }}" class="btn btn-secondary btn-sm">
                            Annuler
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</section>
@endsection
