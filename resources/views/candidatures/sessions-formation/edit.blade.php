@extends('layouts.dashboard')

@section('title', 'Modifier la session')

@section('content')
<div class="dash-topbar">
    <div>
        <h2>Modifier la session</h2>
        <p class="muted-sub">{{ $session->nom }}</p>
    </div>
</div>

@if ($errors->any())
<div class="alert alert-danger alert-list">
    <strong>Veuillez corriger les erreurs suivantes :</strong>
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="panel">
    <form action="{{ route('sessions-formation.update', $session) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="field">
            <label>Nom de la session</label>
            <input type="text" name="nom" value="{{ old('nom', $session->nom) }}">
            @error('nom') <span class="field-error">{{ $message }}</span> @enderror
        </div>

        <div class="field-row">
            <div class="field">
                <label>Langue concernée</label>
                <select name="langue_specialisation_id">
                    <option value="">-- Toutes langues / non spécifié --</option>
                    @foreach ($languesSpecialisations as $langue)
                    <option value="{{ $langue->id }}" @selected(old('langue_specialisation_id', $session->langue_specialisation_id) == $langue->id)>{{ $langue->nom }}</option>
                    @endforeach
                </select>
                @error('langue_specialisation_id') <span class="field-error">{{ $message }}</span> @enderror
            </div>
            <div class="field">
                <label>Formateur</label>
                <input type="text" name="formateur" value="{{ old('formateur', $session->formateur) }}">
                @error('formateur') <span class="field-error">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="field-row">
            <div class="field">
                <label>Lieu</label>
                <input type="text" name="lieu" value="{{ old('lieu', $session->lieu) }}">
                @error('lieu') <span class="field-error">{{ $message }}</span> @enderror
            </div>
            <div class="field">
                <label>Statut</label>
                <select name="statut">
                    <option value="planifiee" @selected(old('statut', $session->statut) == 'planifiee')>Planifiée</option>
                    <option value="en_cours" @selected(old('statut', $session->statut) == 'en_cours')>En cours</option>
                    <option value="terminee" @selected(old('statut', $session->statut) == 'terminee')>Terminée</option>
                    <option value="annulee" @selected(old('statut', $session->statut) == 'annulee')>Annulée</option>
                </select>
                @error('statut') <span class="field-error">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="field-row">
            <div class="field">
                <label>Date de début</label>
                <input type="date" name="date_debut" value="{{ old('date_debut', $session->date_debut?->format('Y-m-d')) }}">
                @error('date_debut') <span class="field-error">{{ $message }}</span> @enderror
            </div>
            <div class="field">
                <label>Date de fin</label>
                <input type="date" name="date_fin" value="{{ old('date_fin', $session->date_fin?->format('Y-m-d')) }}">
                @error('date_fin') <span class="field-error">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="field">
            <label>Description</label>
            <textarea name="description" rows="3">{{ old('description', $session->description) }}</textarea>
            @error('description') <span class="field-error">{{ $message }}</span> @enderror
        </div>

        <div class="reg-actions" style="justify-content:space-between; margin-top:24px;">
            <a href="{{ route('sessions-formation.show', $session) }}" class="btn btn-ghost btn-sm">Annuler</a>
            <button type="submit" class="btn btn-primary btn-sm">Enregistrer les modifications</button>
        </div>
    </form>
</div>
@endsection