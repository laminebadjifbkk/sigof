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

                <div class="row mb-3">
                    <div class="col-md-12 col-sm-12 mb-3">
                        <label for="titre">Titre <span class="text-danger">*</span></label>
                        <input type="text" name="titre"
                            class="form-control form-control-sm @error('titre') is-invalid @enderror"
                            value="{{ old('titre', $activitequotidienne->titre) }}" required>
                        @error('titre')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-12 col-sm-12 mb-3">
                        <label for="description">Description</label>
                        <textarea name="description" class="form-control form-control-sm @error('description') is-invalid @enderror">{{ old('description', $activitequotidienne->description) }}</textarea>
                        @error('description')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Agent --}}
                    <div class="col-md-6 col-sm-12 mb-3">
                        <label class="form-label">Agent concerné <span class="text-danger">*</span></label>
                        <select name="user_id" class="form-select form-select-sm @error('user_id') is-invalid @enderror"
                            required id="select-field-employe">
                            <option value="">-- Choisir un agent --</option>
                            @foreach ($employes as $employe)
                                <option value="{{ $employe->user->id }}"
                                    {{ old('user_id', $activitequotidienne->user_id) == $employe->user->id ? 'selected' : '' }}>
                                    {{ $employe->user->firstname }} {{ $employe->user->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-3 col-sm-6 mb-3">
                        <label class="form-label" for="priorite">Priorité <span class="text-danger">*</span></label>
                        <select name="priorite" class="form-select form-select-sm" required>
                            <option value="">-- Choisir --</option>
                            <option value="faible"
                                {{ old('priorite', $activitequotidienne->priorite) == 'faible' ? 'selected' : '' }}>Faible
                            </option>
                            <option value="normale"
                                {{ old('priorite', $activitequotidienne->priorite) == 'normale' ? 'selected' : '' }}>
                                Normale
                            </option>
                            <option value="urgente"
                                {{ old('priorite', $activitequotidienne->priorite) == 'urgente' ? 'selected' : '' }}>
                                Urgente
                            </option>
                        </select>
                    </div>

                    <div class="col-md-3 col-sm-6 mb-3">
                        <label class="form-label" for="statut">Statut <span class="text-danger">*</span></label>
                        <select name="statut" class="form-select form-select-sm" required>
                            @foreach ($labels as $key => $label)
                                <option value="{{ $key }}"
                                    {{ $activitequotidienne->statut == $key ? 'selected' : '' }}>
                                    {{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 col-sm-6">
                        <label class="form-label" for="date_activite">Date activité <span
                                class="text-danger">*</span></label>
                        <input type="date" name="date_activite"
                            class="form-control form-control-sm @error('date_activite') is-invalid @enderror"
                            value="{{ old('date_activite', optional($activitequotidienne->date_activite)->format('Y-m-d')) }}"
                            required>
                    </div>

                    <div class="col-md-3 col-sm-6 mb-3">
                        <label class="form-label">Heure début</label>
                        <input type="time" name="heure_debut"
                            class="form-control form-control-sm @error('heure_debut') is-invalid @enderror"
                            value="{{ old('heure_debut', optional($activitequotidienne->heure_debut)->format('H:i')) }}">
                        @error('heure_debut')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-3 col-sm-6 mb-3">
                        <label class="form-label">Heure fin</label>
                        <input type="time" name="heure_fin"
                            class="form-control form-control-sm @error('heure_fin') is-invalid @enderror"
                            value="{{ old('heure_fin', optional($activitequotidienne->heure_fin)->format('H:i')) }}">
                        @error('heure_fin')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                </div>

                <button type="submit" class="btn btn-sm btn-success">Mettre à jour</button>
                <a href="{{ route('activites-quotidiennes.index') }}" class="btn btn-sm btn-secondary">Annuler</a>
            </form>
        </div>
    </section>

@endsection
