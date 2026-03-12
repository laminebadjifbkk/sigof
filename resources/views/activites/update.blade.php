@extends('layout.user-layout')
@section('title', 'Modifier une activité')
@section('space-work')

    <section class="section register">
        <div class="container">
            <h1>Modifier l'activité</h1>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('activites-quotidiennes.update', $activitequotidienne->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="titre">Titre <span class="text-danger">*</span></label>
                    <input type="text" name="titre" class="form-control"
                        value="{{ old('titre', $activitequotidienne->titre) }}" required>
                </div>

                <div class="mb-3">
                    <label for="description">Description</label>
                    <textarea name="description" class="form-control">{{ old('description', $activitequotidienne->description) }}</textarea>
                </div>

                <div class="row mb-3">

                    <div class="col-md-6 col-sm-6">
                        <label for="date_activite">Date <span class="text-danger">*</span></label>
                        <input type="date" name="date_activite" class="form-control"
                            value="{{ old('date_activite', optional($activitequotidienne->date_activite)->format('Y-m-d')) }}"
                            required>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <label class="form-label">Heure début</label>
                        <input type="time" name="heure_debut"
                            class="form-control form-control-sm @error('heure_debut') is-invalid @enderror"
                            value="{{ old('heure_debut', optional($activitequotidienne->heure_debut)->format('H:i')) }}">
                        @error('heure_debut')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <label class="form-label">Heure fin</label>
                        <input type="time" name="heure_fin"
                            class="form-control form-control-sm @error('heure_fin') is-invalid @enderror"
                            value="{{ old('heure_fin', optional($activitequotidienne->heure_fin)->format('H:i')) }}">
                        @error('heure_fin')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                </div>

                <div class="mb-3">
                    <label for="priorite">Priorité <span class="text-danger">*</span></label>
                    <select name="priorite" class="form-select" required>
                        <option value="faible"
                            {{ old('priorite', $activitequotidienne->priorite) == 'faible' ? 'selected' : '' }}>Faible
                        </option>
                        <option value="normale"
                            {{ old('priorite', $activitequotidienne->priorite) == 'normale' ? 'selected' : '' }}>Normale
                        </option>
                        <option value="urgente"
                            {{ old('priorite', $activitequotidienne->priorite) == 'urgente' ? 'selected' : '' }}>Urgente
                        </option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="statut">Statut <span class="text-danger">*</span></label>
                    <select name="statut" class="form-select" required>
                        @foreach ($labels as $key => $label)
                            <option value="{{ $key }}"
                                {{ $activitequotidienne->statut == $key ? 'selected' : '' }}>
                                {{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-sm btn-success">Mettre à jour</button>
                <a href="{{ route('activites-quotidiennes.index') }}" class="btn btn-sm btn-secondary">Annuler</a>
            </form>
        </div>
    </section>

@endsection
