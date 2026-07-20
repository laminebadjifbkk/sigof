@extends('layouts.dashboard')

@section('title', 'Ajouter un utilisateur')

@section('content')
<div class="dash-topbar">
    <div>
        <a href="{{ route('utilisateurs.index') }}" class="btn btn-sm btn-outline">&larr; Retour</a>
        <h2>Ajouter un utilisateur admin</h2>
    </div>
</div>

<div class="panel">
    <form action="{{ route('utilisateurs.store') }}" method="POST">
        @csrf
        <div class="form-grid">
            <div class="field">
                <label for="firstname">Prénom</label>
                <input type="text" name="firstname" id="firstname" class="form-control" value="{{ old('firstname') }}" required>
                @error('firstname') <span class="field-error">{{ $message }}</span> @enderror
            </div>
            <div class="field">
                <label for="name">Nom</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
                @error('name') <span class="field-error">{{ $message }}</span> @enderror
            </div>
            <div class="field">
                <label for="email">E-mail</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required>
                @error('email') <span class="field-error">{{ $message }}</span> @enderror
            </div>
            <div class="field">
                <label for="password">Mot de passe</label>
                <input type="password" name="password" id="password" class="form-control" required>
                @error('password') <span class="field-error">{{ $message }}</span> @enderror
            </div>
            <div class="field full-width">
                <label for="role">Rôle</label>
                <select name="role" id="role" required>
                    <option value="">-- Sélectionner --</option>
                    @foreach ($roles as $role)
                    <option value="{{ $role->name }}" {{ old('role') === $role->name ? 'selected' : '' }}>
                        {{ ucfirst(str_replace('-', ' ', $role->name)) }}
                    </option>
                    @endforeach
                </select>
                @error('role') <span class="field-error">{{ $message }}</span> @enderror
            </div>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Créer le compte</button>
    </form>
</div>
@endsection