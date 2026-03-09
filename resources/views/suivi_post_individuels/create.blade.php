@extends('layout.user-layout')
@section('title', 'ONFP | SUIVI INDIVIDUEL')
@section('space-work')
    <div class="container">
        <h1>Ajouter un suivi individuel</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('individuels.store') }}" method="POST">
            @csrf

            <!-- Individuelle -->
            <div class="mb-3">
                <label for="individuelle_id" class="form-label">Individuelle</label>
                <select name="individuelle_id" id="individuelle_id" class="form-control">
                    <option value="">-- Sélectionner --</option>
                    @foreach ($individuelles as $individuelle)
                        <option value="{{ $individuelle->id }}">
                            {{ $individuelle?->user?->firstname . ' ' . $individuelle?->user?->name ?? $individuelle?->user?->username }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Situation actuelle -->
            <div class="mb-3">
                <label for="situation" class="form-label">Situation actuelle</label>
                <input type="text" name="situation" id="situation" class="form-control" value="{{ old('situation') }}">
            </div>

            <!-- Temps d'emploi -->
            <div class="mb-3">
                <label for="temps_emploi" class="form-label">Temps d'emploi</label>
                <input type="text" name="temps_emploi" id="temps_emploi" class="form-control"
                    value="{{ old('temps_emploi') }}">
            </div>

            <!-- Entreprise -->
            <div class="mb-3">
                <label for="entreprise" class="form-label">Entreprise</label>
                <input type="text" name="entreprise" id="entreprise" class="form-control"
                    value="{{ old('entreprise') }}">
            </div>

            <!-- Secteur -->
            <div class="mb-3">
                <label for="secteur" class="form-label">Secteur</label>
                <input type="text" name="secteur" id="secteur" class="form-control" value="{{ old('secteur') }}">
            </div>

            <!-- Lien avec la formation -->
            <div class="mb-3">
                <label for="lien" class="form-label">Lien avec la formation</label>
                <input type="text" name="lien" id="lien" class="form-control" value="{{ old('lien') }}">
            </div>

            <!-- Revenu -->
            <div class="mb-3">
                <label for="revenu" class="form-label">Revenu</label>
                <input type="text" name="revenu" id="revenu" class="form-control" value="{{ old('revenu') }}">
            </div>

            <!-- Formation sur le marché -->
            <div class="mb-3">
                <label for="formation_marche" class="form-label">Formation sur le marché</label>
                <input type="text" name="formation_marche" id="formation_marche" class="form-control"
                    value="{{ old('formation_marche') }}">
            </div>

            <!-- Compétences utilisées -->
            <div class="mb-3">
                <label for="competences" class="form-label">Compétences utilisées</label>
                <input type="text" name="competences" id="competences" class="form-control"
                    value="{{ old('competences') }}">
            </div>

            <!-- Recommandé -->
            <div class="mb-3">
                <label for="recommande" class="form-label">Recommandé</label>
                <input type="text" name="recommande" id="recommande" class="form-control"
                    value="{{ old('recommande') }}">
            </div>

            <!-- Difficultés (multi-sélection) -->
            <div class="mb-3">
                <label for="difficultes" class="form-label">Difficultés</label>
                <select name="difficultes[]" id="difficultes" class="form-control" multiple>
                    <option value="financieres">Financières</option>
                    <option value="techniques">Techniques</option>
                    <option value="organisation">Organisation</option>
                </select>
            </div>

            <!-- Besoins (multi-sélection) -->
            <div class="mb-3">
                <label for="besoins" class="form-label">Besoins</label>
                <select name="besoins[]" id="besoins" class="form-control" multiple>
                    <option value="formation">Formation</option>
                    <option value="accompagnement">Accompagnement</option>
                    <option value="financement">Financement</option>
                </select>
            </div>

            <!-- Diplôme retiré -->
            <div class="mb-3">
                <label class="form-label">Diplôme retiré</label>

                <div class="form-check">
                    <input type="radio" name="diplome" value="1" class="form-check-input">
                    <label class="form-check-label">Oui</label>
                </div>

                <div class="form-check">
                    <input type="radio" name="diplome" value="0" class="form-check-input">
                    <label class="form-check-label">Non</label>
                </div>

            </div>

            <!-- Commentaires -->
            <div class="mb-3">
                <label for="commentaires" class="form-label">Commentaires</label>
                <textarea name="commentaires" id="commentaires" class="form-control">{{ old('commentaires') }}</textarea>
            </div>

            <button type="submit" class="btn btn-success">Enregistrer</button>
            <a href="{{ route('individuels.index') }}" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
@endsection
