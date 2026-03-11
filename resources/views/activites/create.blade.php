@extends('layout.user-layout')
@section('title', 'ONFP - Ajouter une activité quotidienne')

@section('space-work')
    <section class="section register">
        <div class="container">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="mb-0">Ajouter une activité quotidienne</h1>
                <a href="{{ route('activites-quotidiennes.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left-circle"></i> Retour à la liste
                </a>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
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

                    <form action="{{ route('activites-quotidiennes.store') }}" method="POST">
                        @csrf

                        <input type="hidden" name="created_by" value="{{ auth()->id() }}">

                        {{-- Titre --}}
                        <div class="mb-3">
                            <label class="form-label">Titre de l'activité <span class="text-danger">*</span></label>
                            <input type="text" name="titre"
                                class="form-control form-control-sm @error('titre') is-invalid @enderror"
                                value="{{ old('titre') }}" placeholder="Ex : Préparer rapport formation">
                            @error('titre')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control form-control-sm @error('description') is-invalid @enderror"
                                rows="3" placeholder="Description de l'activité">{{ old('description') }}</textarea>
                            @error('description')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Agent et priorité --}}
                        <div class="row mb-3">

                            <div class="col-md-6 col-sm-12">
                                <label class="form-label">Agent concerné <span class="text-danger">*</span></label>
                                <select name="user_id"
                                    class="form-select form-select-sm @error('user_id') is-invalid @enderror">
                                    <option value="">-- Choisir un agent --</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}"
                                            {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->firstname }} {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <label class="form-label">Priorité</label>
                                <select name="priorite"
                                    class="form-select form-select-sm @error('priorite') is-invalid @enderror">
                                    <option value="faible" {{ old('priorite') == 'faible' ? 'selected' : '' }}>Faible
                                    </option>
                                    <option value="normale"
                                        {{ old('priorite', 'normale') == 'normale' ? 'selected' : '' }}>
                                        Normale</option>
                                    <option value="urgente" {{ old('priorite') == 'urgente' ? 'selected' : '' }}>Urgente
                                    </option>
                                </select>
                                @error('priorite')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>

                        {{-- Date activité --}}
                        <div class="row mb-3">

                            <div class="col-md-6 col-sm-12">
                                <label class="form-label">Date de l'activité <span class="text-danger">*</span></label>
                                <input type="date" name="date_activite"
                                    class="form-control form-control-sm @error('date_activite') is-invalid @enderror"
                                    value="{{ old('date_activite', date('Y-m-d')) }}">
                                @error('date_activite')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-3 col-sm-6">
                                <label class="form-label">Heure début</label>
                                <input type="time" name="heure_debut"
                                    class="form-control form-control-sm @error('heure_debut') is-invalid @enderror"
                                    value="{{ old('heure_debut') }}">
                                @error('heure_debut')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-3 col-sm-6">
                                <label class="form-label">Heure fin</label>
                                <input type="time" name="heure_fin"
                                    class="form-control form-control-sm @error('heure_fin') is-invalid @enderror"
                                    value="{{ old('heure_fin') }}">
                                @error('heure_fin')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success btn-sm">
                                <i class="bi bi-check-circle"></i> Enregistrer
                            </button>

                            <a href="{{ route('activites-quotidiennes.index') }}" class="btn btn-secondary btn-sm">
                                <i class="bi bi-x-circle"></i> Annuler
                            </a>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </section>
@endsection
