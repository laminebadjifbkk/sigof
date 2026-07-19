<div class="form-grid">
    <div class="field">
        <label for="nom">Nom de la langue</label>
        <input type="text" name="nom" id="nom" class="form-control" placeholder="Ex : Anglais" value="{{ old('nom', $langue->nom ?? '') }}" required>
        @error('nom') <span class="text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="field">
        <label for="code">Code</label>
        <input type="text" name="code" id="code" class="form-control" placeholder="Ex : anglais" value="{{ old('code', $langue->code ?? '') }}" required>
        @error('code') <span class="text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="field">
        <label for="postes_disponibles">Postes disponibles</label>
        <input type="number" name="postes_disponibles" id="postes_disponibles" class="form-control" min="0" placeholder="Ex : 5" value="{{ old('postes_disponibles', $langue->postes_disponibles ?? 0) }}" required>
        @error('postes_disponibles') <span class="text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="field">
        <label for="niveau_langue_requis">Niveau langue requis</label>
        <input type="text" name="niveau_langue_requis" id="niveau_langue_requis" class="form-control" placeholder="Ex : C1 - Avancé" value="{{ old('niveau_langue_requis', $langue->niveau_langue_requis ?? '') }}" required>
        @error('niveau_langue_requis') <span class="text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="field">
        <label for="niveau_francais_requis">Niveau français requis</label>
        <input type="text" name="niveau_francais_requis" id="niveau_francais_requis" class="form-control" placeholder="Ex : B2 - Intermédiaire" value="{{ old('niveau_francais_requis', $langue->niveau_francais_requis ?? '') }}" required>
        @error('niveau_francais_requis') <span class="text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="field">
        <label for="diplome_minimum">Diplôme minimum</label>
        <input type="text" name="diplome_minimum" id="diplome_minimum" class="form-control" placeholder="Ex : Licence en traduction" value="{{ old('diplome_minimum', $langue->diplome_minimum ?? '') }}" required>
        @error('diplome_minimum') <span class="text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="field full-width">
        <label for="certification_recommandee">Certification recommandée</label>
        <input type="text" name="certification_recommandee" id="certification_recommandee" class="form-control" placeholder="Ex : DALF C1 (facultatif)" value="{{ old('certification_recommandee', $langue->certification_recommandee ?? '') }}">
        @error('certification_recommandee') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
</div>