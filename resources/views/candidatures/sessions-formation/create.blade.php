@extends('layouts.dashboard')

@section('title', 'Nouvelle session de formation')

@section('content')
<div class="dash-topbar">
    <div>
        <h2>Nouvelle session de formation</h2>
        <p class="muted-sub">Créer une session pour former un groupe de traducteurs</p>
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
    <form action="{{ route('sessions-formation.store') }}" method="POST">
        @csrf

        <div class="field">
            <label>Nom de la session</label>
            <input type="text" name="nom" value="{{ old('nom') }}" placeholder="Ex : Formation traducteurs Espagnol - Diamniadio">
            @error('nom') <span class="field-error">{{ $message }}</span> @enderror
        </div>

        <div class="field-row">
            <div class="field">
                <label>Langue concernée (optionnel)</label>
                <select name="langue_specialisation_id">
                    <option value="">-- Toutes langues / non spécifié --</option>
                    @foreach ($languesSpecialisations as $langue)
                    <option value="{{ $langue->id }}" @selected(old('langue_specialisation_id')==$langue->id)>{{ $langue->nom }}</option>
                    @endforeach
                </select>
                @error('langue_specialisation_id') <span class="field-error">{{ $message }}</span> @enderror
            </div>
            <div class="field">
                <label>Formateur</label>
                <input type="text" name="formateur" value="{{ old('formateur') }}" placeholder="Nom du formateur">
                @error('formateur') <span class="field-error">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="field-row">
            <div class="field">
                <label>Lieu</label>
                <input type="text" name="lieu" value="{{ old('lieu') }}" placeholder="Ex : Diamniadio Olympic Stadium">
                @error('lieu') <span class="field-error">{{ $message }}</span> @enderror
            </div>
            <div class="field">
                <label>Statut</label>
                <select name="statut">
                    <option value="planifiee" @selected(old('statut', 'planifiee' )=='planifiee' )>Planifiée</option>
                    <option value="en_cours" @selected(old('statut')=='en_cours' )>En cours</option>
                    <option value="terminee" @selected(old('statut')=='terminee' )>Terminée</option>
                    <option value="annulee" @selected(old('statut')=='annulee' )>Annulée</option>
                </select>
                @error('statut') <span class="field-error">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="field-row">
            <div class="field">
                <label>Date de début</label>
                <input type="date" name="date_debut" value="{{ old('date_debut') }}">
                @error('date_debut') <span class="field-error">{{ $message }}</span> @enderror
            </div>
            <div class="field">
                <label>Date de fin</label>
                <input type="date" name="date_fin" value="{{ old('date_fin') }}">
                @error('date_fin') <span class="field-error">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="field">
            <label>Description (optionnel)</label>
            <textarea name="description" rows="3" placeholder="Programme, objectifs, contenu de la formation…">{{ old('description') }}</textarea>
            @error('description') <span class="field-error">{{ $message }}</span> @enderror
        </div>

        <div class="reg-actions" style="justify-content:space-between; margin-top:24px;">
            <a href="{{ route('sessions-formation.index') }}" class="btn btn-ghost btn-sm">Annuler</a>
            <button type="submit" class="btn btn-primary btn-sm">Créer la session</button>
        </div>
    </form>
</div>
@endsection