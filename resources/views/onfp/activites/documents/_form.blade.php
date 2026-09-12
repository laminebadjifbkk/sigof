{{-- Fichier --}}
<div class="mb-4">

    <label for="document" class="form-label fw-semibold">
        Document
        <span class="text-danger">*</span>
    </label>

    <input type="file"
           name="document"
           id="document"
           class="form-control @error('document') is-invalid @enderror"
           required
           accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.zip">

    @error('document')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror

    <div class="form-text">
        Formats acceptés : PDF, Word, Excel, PowerPoint, JPG, PNG et ZIP.
        Taille maximale : 20 Mo.
    </div>

</div>


<div class="row g-3">

    {{-- Type --}}
    <div class="col-md-6">

        <label for="type" class="form-label fw-semibold">
            Type de document
            <span class="text-danger">*</span>
        </label>

        <select name="type"
                id="type"
                class="form-select form-select-sm @error('type') is-invalid @enderror"
                required>

            <option value="">
                Sélectionner un type
            </option>

            <option value="TDR"
                {{ old('type') === 'TDR' ? 'selected' : '' }}>
                TDR
            </option>

            <option value="Rapport"
                {{ old('type') === 'Rapport' ? 'selected' : '' }}>
                Rapport
            </option>

            <option value="PV"
                {{ old('type') === 'PV' ? 'selected' : '' }}>
                Procès-verbal (PV)
            </option>

            <option value="Courrier"
                {{ old('type') === 'Courrier' ? 'selected' : '' }}>
                Courrier
            </option>

            <option value="Photo"
                {{ old('type') === 'Photo' ? 'selected' : '' }}>
                Photo
            </option>

            <option value="Tableau"
                {{ old('type') === 'Tableau' ? 'selected' : '' }}>
                Tableau
            </option>

            <option value="Présentation"
                {{ old('type') === 'Présentation' ? 'selected' : '' }}>
                Présentation
            </option>

            <option value="Autre"
                {{ old('type') === 'Autre' ? 'selected' : '' }}>
                Autre
            </option>

        </select>

        @error('type')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Tâche --}}
    <div class="col-md-6">

        <label for="tache_id" class="form-label fw-semibold">
            Tâche associée
        </label>

        <select name="tache_id"
                id="tache_id"
                class="form-select form-select-sm @error('tache_id') is-invalid @enderror">

            <option value="">
                Document général de l'activité
            </option>

            @foreach($taches as $tache)

                <option value="{{ $tache->id }}"
                    {{ old('tache_id') == $tache->id ? 'selected' : '' }}>

                    {{ $tache->titre }}

                </option>

            @endforeach

        </select>

        @error('tache_id')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

        <div class="form-text">
            Facultatif. Sélectionnez une tâche si le document concerne
            spécifiquement celle-ci.
        </div>

    </div>


    {{-- Description --}}
    <div class="col-12">

        <label for="description" class="form-label fw-semibold">
            Description
        </label>

        <textarea name="description"
                  id="description"
                  rows="4"
                  class="form-control @error('description') is-invalid @enderror"
                  placeholder="Décrivez brièvement le contenu, le contexte ou l'utilité de ce document...">{{ old('description') }}</textarea>

        @error('description')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Document final --}}
    <div class="col-12">

        <div class="form-check form-switch">

            <input type="hidden"
                   name="document_final"
                   value="0">

            <input class="form-check-input"
                   type="checkbox"
                   role="switch"
                   name="document_final"
                   id="document_final"
                   value="1"
                   {{ old('document_final') ? 'checked' : '' }}>

            <label class="form-check-label fw-semibold"
                   for="document_final">

                Marquer comme document final

            </label>

        </div>

        <div class="form-text ms-4">

            Indique que ce fichier correspond à la version définitive
            du document.

        </div>

    </div>

</div>


{{-- Prévisualisation du fichier sélectionné --}}
<div class="mt-4">

    <div id="file-preview"
         class="alert alert-light border d-none">

        <div class="d-flex align-items-center gap-3">

            <i class="bi bi-file-earmark fs-3 text-primary"></i>

            <div>

                <div class="fw-semibold"
                     id="file-name">
                </div>

                <small class="text-muted"
                       id="file-size">
                </small>

            </div>

        </div>

    </div>

</div>


<script>
document.addEventListener('DOMContentLoaded', function () {

    const input = document.getElementById('document');
    const preview = document.getElementById('file-preview');
    const fileName = document.getElementById('file-name');
    const fileSize = document.getElementById('file-size');

    if (!input) {
        return;
    }

    input.addEventListener('change', function () {

        if (!this.files || !this.files.length) {

            preview.classList.add('d-none');

            return;
        }

        const file = this.files[0];

        fileName.textContent = file.name;

        const sizeMo = file.size / 1024 / 1024;

        fileSize.textContent =
            sizeMo.toFixed(2) + ' Mo';

        preview.classList.remove('d-none');
    });

});
</script>
