@extends('layouts.dashboard')

@section('title', 'Modifier l\'utilisateur')

@section('content')
<div class="dash-topbar">
    <div>
        <a href="{{ route('utilisateurs.index') }}" class="btn btn-sm btn-outline">&larr; Retour</a>
        <h2>Modifier {{ $utilisateur->firstname }} {{ $utilisateur->name }}</h2>
    </div>
</div>

<div class="panel">
    <form action="{{ route('utilisateurs.update', $utilisateur) }}" method="POST">
        @csrf
        @method('PUT')

        @if ($estCandidat)
        <div class="field">
            <label>Civilité</label>
            <div class="field-row">
                <label class="radio-inline">
                    <input type="radio" name="civilite" value="M." {{ old('civilite', $utilisateur->civilite) === 'M.' ? 'checked' : '' }}>
                    M.
                </label>
                <label class="radio-inline">
                    <input type="radio" name="civilite" value="Mme" {{ old('civilite', $utilisateur->civilite) === 'Mme' ? 'checked' : '' }}>
                    Mme
                </label>
            </div>
            @error('civilite') <span class="field-error">{{ $message }}</span> @enderror
        </div>
        @endif

        <div class="form-grid">
            <div class="field">
                <label for="firstname">Prénom</label>
                <input type="text" name="firstname" id="firstname" class="form-control" value="{{ old('firstname', $utilisateur->firstname) }}" required>
                @error('firstname') <span class="field-error">{{ $message }}</span> @enderror
            </div>

            <div class="field">
                <label for="name">Nom</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $utilisateur->name) }}" required>
                @error('name') <span class="field-error">{{ $message }}</span> @enderror
            </div>

            <div class="field {{ !$estCandidat ? 'full-width' : '' }}">
                <label for="email">E-mail</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $utilisateur->email) }}" required>
                @error('email') <span class="field-error">{{ $message }}</span> @enderror
            </div>

            @if ($estCandidat)
            <div class="field">
                <label for="telephone">Téléphone</label>
                <input type="tel" name="telephone" id="telephone" class="form-control" value="{{ old('telephone', $utilisateur->telephone) }}" required>
                @error('telephone') <span class="field-error">{{ $message }}</span> @enderror
            </div>

            <div class="field">
                <label for="date_naissance">Date de naissance</label>
                <input type="date" name="date_naissance" id="date_naissance" class="form-control" value="{{ old('date_naissance', $utilisateur->date_naissance?->format('Y-m-d')) }}" required>
                @error('date_naissance') <span class="field-error">{{ $message }}</span> @enderror
            </div>

            <div class="field">
                <label for="lieu_naissance">Lieu de naissance</label>
                <input type="text" name="lieu_naissance" id="lieu_naissance" class="form-control" value="{{ old('lieu_naissance', $utilisateur->lieu_naissance) }}" required>
                @error('lieu_naissance') <span class="field-error">{{ $message }}</span> @enderror
            </div>

            <div class="field full-width">
                <label for="adresse">Adresse</label>
                <input type="text" name="adresse" id="adresse" class="form-control" value="{{ old('adresse', $utilisateur->adresse) }}" required>
                @error('adresse') <span class="field-error">{{ $message }}</span> @enderror
            </div>

            <div class="field full-width">
                <label for="region_id">Région</label>
                <select name="region_id" id="region_id" required>
                    <option value="">-- Sélectionner une région --</option>
                    @foreach ($regions as $region)
                    <option value="{{ $region->id }}" {{ (string) old('region_id', $utilisateur->region_id) === (string) $region->id ? 'selected' : '' }}>
                        {{ $region->nom }}
                    </option>
                    @endforeach
                </select>
                @error('region_id') <span class="field-error">{{ $message }}</span> @enderror
            </div>
            @else
            <div class="field full-width">
                <label for="role">Rôle</label>
                <select name="role" id="role" required>
                    @foreach ($roles as $role)
                    <option value="{{ $role->name }}" {{ $utilisateur->hasRole($role->name) ? 'selected' : '' }}>
                        {{ ucfirst(str_replace('-', ' ', $role->name)) }}
                    </option>
                    @endforeach
                </select>
                @error('role') <span class="field-error">{{ $message }}</span> @enderror
            </div>
            @endif
        </div>

        <button type="submit" class="btn btn-primary mt-3">Enregistrer les modifications</button>
    </form>
</div>
@endsection