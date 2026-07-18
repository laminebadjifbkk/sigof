@extends('layouts.dashboard')

@section('title', 'Modifier la candidature')

@section('content')
<div class="dash-topbar">
    <div>
        <h2>Modifier la candidature</h2>
        <p class="muted-sub">{{ $candidature->user->firstname }} {{ $candidature->user->name }} — Candidature #{{ str_pad($candidature->id, 6, '0', STR_PAD_LEFT) }}</p>
    </div>
    <div class="topbar-right">
        <span class="status-pill {{ $candidature->statut_classe }}">{{ $candidature->statut_label }}</span>
    </div>
</div>

@if (session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

@if (session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif

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
    <div class="detail-item" style="margin-bottom:20px;">
        <span class="detail-label">Informations non modifiables ici</span>
        <span class="detail-value">
            {{ $candidature->user->email }} · {{ $candidature->user->telephone }} ·
            Langue de spécialisation : {{ $candidature->langueSpecialisation->nom }}
        </span>
        <p style="font-size:12.5px; color:var(--gray-500,#888); margin-top:4px;">
            Pour modifier le profil ou la langue de spécialisation, contactez un administrateur système.
        </p>
    </div>

    <form action="{{ route('candidatures.update', $candidature->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <h3>Langues</h3>
        <div class="field-row">
            <div class="field">
                <label>Diplôme le plus élevé</label>
                <select name="diplome">
                    <option value="licence" @selected(old('diplome', $candidature->diplome) == 'licence')>Licence</option>
                    <option value="master" @selected(old('diplome', $candidature->diplome) == 'master')>Master</option>
                    <option value="doctorat" @selected(old('diplome', $candidature->diplome) == 'doctorat')>Doctorat</option>
                    <option value="certification" @selected(old('diplome', $candidature->diplome) == 'certification')>Certification linguistique reconnue</option>
                </select>
                @error('diplome') <span class="field-error">{{ $message }}</span> @enderror
            </div>
            <div class="field">
                <label>Langue maternelle</label>
                <select name="langue_maternelle">
                    <option value="wolof" @selected(old('langue_maternelle', $candidature->langue_maternelle) == 'wolof')>Wolof</option>
                    <option value="francais" @selected(old('langue_maternelle', $candidature->langue_maternelle) == 'francais')>Français</option>
                    <option value="pulaar" @selected(old('langue_maternelle', $candidature->langue_maternelle) == 'pulaar')>Pulaar</option>
                    <option value="serere" @selected(old('langue_maternelle', $candidature->langue_maternelle) == 'serere')>Sérère</option>
                    <option value="diola" @selected(old('langue_maternelle', $candidature->langue_maternelle) == 'diola')>Diola</option>
                    <option value="autre" @selected(old('langue_maternelle', $candidature->langue_maternelle) == 'autre')>Autre</option>
                </select>
                @error('langue_maternelle') <span class="field-error">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="field-row">
            <div class="field">
                <label>Niveau de français</label>
                <select name="niveau_francais">
                    <option value="c1" @selected(old('niveau_francais', $candidature->niveau_francais) == 'c1')>C1</option>
                    <option value="c2" @selected(old('niveau_francais', $candidature->niveau_francais) == 'c2')>C2 / Bilingue</option>
                </select>
                @error('niveau_francais') <span class="field-error">{{ $message }}</span> @enderror
            </div>
            <div class="field">
                <label>Langue vivante 2 (LV2)</label>
                <select name="langue_vivante_2">
                    <option value="" @selected(!old('langue_vivante_2', $candidature->langue_vivante_2))>-- Aucune --</option>
                    <option value="anglais" @selected(old('langue_vivante_2', $candidature->langue_vivante_2) == 'anglais')>Anglais</option>
                    <option value="espagnol" @selected(old('langue_vivante_2', $candidature->langue_vivante_2) == 'espagnol')>Espagnol</option>
                    <option value="arabe" @selected(old('langue_vivante_2', $candidature->langue_vivante_2) == 'arabe')>Arabe</option>
                    <option value="portugais" @selected(old('langue_vivante_2', $candidature->langue_vivante_2) == 'portugais')>Portugais</option>
                    <option value="aucune" @selected(old('langue_vivante_2', $candidature->langue_vivante_2) == 'aucune')>Aucune</option>
                </select>
                @error('langue_vivante_2') <span class="field-error">{{ $message }}</span> @enderror
            </div>
        </div>

        <h3 style="margin-top:24px;">Disponibilité et affectation</h3>
        <div class="field-row">
            <div class="field">
                <label>Disponible à partir du</label>
                <input type="date" name="disponible_debut"
                    value="{{ old('disponible_debut', $candidature->disponible_debut?->format('Y-m-d')) }}">
                @error('disponible_debut') <span class="field-error">{{ $message }}</span> @enderror
            </div>
            <div class="field">
                <label>Disponible jusqu'au</label>
                <input type="date" name="disponible_fin"
                    value="{{ old('disponible_fin', $candidature->disponible_fin?->format('Y-m-d')) }}">
                @error('disponible_fin') <span class="field-error">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="field">
            <label>Zone / site préféré</label>
            <select name="zone">
                <option value="diamniadio" @selected(old('zone', $candidature->zone) == 'diamniadio')>Diamniadio Olympic Stadium</option>
                <option value="dakar_centre" @selected(old('zone', $candidature->zone) == 'dakar_centre')>Dakar centre</option>
                <option value="saly" @selected(old('zone', $candidature->zone) == 'saly')>Saly - Petite Côte</option>
                <option value="indifferent" @selected(old('zone', $candidature->zone) == 'indifferent')>Indifférent</option>
            </select>
            @error('zone') <span class="field-error">{{ $message }}</span> @enderror
        </div>

        <div class="field">
            <label>Délégation ou discipline souhaitée</label>
            <input type="text" name="delegation_souhaitee"
                value="{{ old('delegation_souhaitee', $candidature->delegation_souhaitee) }}"
                placeholder="Ex : Beach handball, Athlétisme…">
            @error('delegation_souhaitee') <span class="field-error">{{ $message }}</span> @enderror
        </div>

        <h3 style="margin-top:24px;">Documents justificatifs</h3>
        <p style="font-size:12.5px; color:var(--gray-500,#888); margin-bottom:12px;">
            Laissez vide pour conserver le fichier actuel. Sélectionnez un fichier pour le remplacer.
        </p>

        @foreach ($currentFiles as $field => $info)
        <div class="field" style="margin-bottom:16px;">
            <label>{{ $info['label'] }}</label>

            @if ($info['path'])
            <div style="margin-bottom:6px;">
                <a href="{{ asset('storage/' . $info['path']) }}" target="_blank" class="btn btn-ghost btn-sm">
                    📄 Voir le fichier actuel
                </a>
            </div>
            @else
            <p style="font-size:12.5px; color:var(--gray-500,#888); margin-bottom:6px;">Aucun fichier actuellement.</p>
            @endif

            <input type="file" name="{{ $field }}" accept=".pdf,.jpg,.jpeg,.png">
            @error($field) <span class="field-error">{{ $message }}</span> @enderror
        </div>
        @endforeach

        <!-- <div class="reg-actions" style="justify-content:space-between; margin-top:24px;">
            <a href="{{ route('candidatures.show', $candidature->id) }}" class="btn btn-ghost btn-sm">← Annuler</a>
            <button type="submit" class="btn btn-primary btn-sm">Enregistrer les modifications</button>
        </div> -->

        <div class="reg-actions" style="justify-content:space-between; margin-top:24px;">
            <a href="{{ route('candidatures.show', $candidature->id) }}" class="btn btn-ghost btn-sm">← Annuler</a>
            <button type="submit" class="btn btn-primary btn-sm">Enregistrer les modifications</button>
        </div>
    </form>
</div>
@endsection