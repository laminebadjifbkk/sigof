@extends('layout.user-layout')

@section('title', 'ONFP | Déclarer un incident')

@section('space-work')
<div class="container-fluid py-3">

    <div class="mb-4">
        <a href="{{ route('support.incidents.index') }}" class="text-decoration-none">
            <i class="bi bi-arrow-left"></i> Retour aux incidents
        </a>

        <h3 class="fw-bold mt-2 mb-1">Déclarer un incident informatique</h3>
        <p class="text-muted mb-0">
            Signalez un événement technique susceptible d'affecter le fonctionnement
            des systèmes informatiques ou des services de l'ONFP.
        </p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Veuillez corriger les erreurs suivantes :</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">

            <form method="POST" action="{{ route('support.incidents.store') }}">
                @csrf

                <div class="row g-3">

                    <div class="col-md-8">
                        <label class="form-label fw-semibold">
                            Intitulé de l'incident <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="titre"
                            class="form-control form-control-sm"
                            maxlength="255"
                            value="{{ old('titre') }}"
                            placeholder="Ex. : Indisponibilité du réseau informatique au siège"
                            required
                        >

                        @error('titre')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">
                            Type d'incident <span class="text-danger">*</span>
                        </label>

                        <select
                            name="support_incident_type_id"
                            class="form-select form-select-sm"
                            required
                        >
                            <option value="">Sélectionner un type</option>

                            @foreach($types as $type)
                                <option
                                    value="{{ $type->id }}"
                                    @selected(old('support_incident_type_id') == $type->id)
                                >
                                    {{ $type->libelle }}
                                </option>
                            @endforeach
                        </select>

                        @error('support_incident_type_id')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Priorité <span class="text-danger">*</span>
                        </label>

                        <select
                            name="support_priorite_id"
                            class="form-select form-select-sm"
                            required
                        >
                            <option value="">Sélectionner une priorité</option>

                            @foreach($priorites as $priorite)
                                <option
                                    value="{{ $priorite->id }}"
                                    @selected(old('support_priorite_id') == $priorite->id)
                                >
                                    {{ $priorite->libelle }}
                                </option>
                            @endforeach
                        </select>

                        @error('support_priorite_id')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror

                        <small class="text-muted">
                            Évaluez l'impact de l'incident sur les activités de l'ONFP.
                        </small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Date et heure de détection <span class="text-danger">*</span>
                        </label>

                        <input
                            type="datetime-local"
                            name="date_detection"
                            class="form-control form-control-sm"
                            value="{{ old('date_detection', now()->format('Y-m-d\TH:i')) }}"
                            max="{{ now()->format('Y-m-d\TH:i') }}"
                            required
                        >

                        @error('date_detection')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">
                            Description détaillée <span class="text-danger">*</span>
                        </label>

                        <textarea
                            name="description"
                            class="form-control form-control-sm"
                            rows="7"
                            placeholder="Décrivez les circonstances de l'incident, les équipements ou services concernés, les utilisateurs affectés, les symptômes observés et les éventuels messages d'erreur..."
                            required
                        >{{ old('description') }}</textarea>

                        @error('description')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                <hr class="my-4">

                <div class="alert alert-light border small">
                    <i class="bi bi-info-circle me-1"></i>
                    La référence de l'incident et son statut initial seront générés
                    automatiquement après validation de la déclaration.
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a
                        href="{{ route('support.incidents.index') }}"
                        class="btn btn-sm btn-light"
                    >
                        Annuler
                    </a>

                    <button
                        type="submit"
                        class="btn btn-sm text-white"
                        style="background:#F28500"
                    >
                        <i class="bi bi-send me-1"></i>
                        Déclarer l'incident
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
