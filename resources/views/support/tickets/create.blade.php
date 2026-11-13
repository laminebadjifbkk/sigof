@extends('layout.user-layout')

@section('title', 'ONFP | Nouveau ticket')

@section('space-work')
<div class="container-fluid py-3">

    <div class="mb-4">
        <a href="{{ route('support.tickets.index') }}" class="text-decoration-none">
            <i class="bi bi-arrow-left"></i> Retour aux tickets
        </a>
        <h3 class="fw-bold mt-2 mb-1">Créer un ticket</h3>
        <p class="text-muted mb-0">Décrivez votre problème ou votre besoin d'assistance informatique.</p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Veuillez corriger les erreurs suivantes :</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('support.tickets.store') }}">
                @csrf

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Catégorie <span class="text-danger">*</span></label>
                        <select name="support_category_id" id="support_category_id" class="form-select" required>
                            <option value="">Sélectionner une catégorie</option>
                            @foreach($categories as $categorie)
                                <option value="{{ $categorie->id }}"
                                    @selected(old('support_category_id') == $categorie->id)>
                                    {{ $categorie->libelle }}
                                </option>
                            @endforeach
                        </select>
                        @error('support_category_id') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Type de demande <span class="text-danger">*</span></label>
                        <select name="support_type_id" id="support_type_id" class="form-select" required>
                            <option value="">Sélectionner un type</option>
                            @foreach($types as $type)
                                <option value="{{ $type->id }}"
                                        data-category="{{ $type->support_category_id }}"
                                        @selected(old('support_type_id') == $type->id)>
                                    {{ $type->libelle }}
                                </option>
                            @endforeach
                        </select>
                        @error('support_type_id') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-8">
                        <label class="form-label fw-semibold">Objet de la demande <span class="text-danger">*</span></label>
                        <input type="text" name="objet" class="form-control" maxlength="200"
                               value="{{ old('objet') }}" placeholder="Résumez votre problème en quelques mots" required>
                        @error('objet') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Priorité souhaitée <span class="text-danger">*</span></label>
                        <select name="support_priorite_id" class="form-select" required>
                            <option value="">Sélectionner</option>
                            @foreach($priorites as $priorite)
                                <option value="{{ $priorite->id }}"
                                    @selected(old('support_priorite_id') == $priorite->id)>
                                    {{ $priorite->libelle }}
                                </option>
                            @endforeach
                        </select>
                        @error('support_priorite_id') <div class="text-danger small">{{ $message }}</div> @enderror
                        <small class="text-muted">La priorité pourra être ajustée par le support.</small>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Description détaillée <span class="text-danger">*</span></label>
                        <textarea name="description" class="form-control" rows="6"
                                  placeholder="Décrivez le problème, les circonstances, les messages d'erreur éventuels et les actions déjà effectuées..."
                                  required>{{ old('description') }}</textarea>
                        @error('description') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('support.tickets.index') }}" class="btn btn-light">Annuler</a>
                    <button type="submit" class="btn text-white" style="background:#F28500">
                        <i class="bi bi-send me-1"></i> Soumettre le ticket
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const categorie = document.getElementById('support_category_id');
    const type = document.getElementById('support_type_id');

    function filtrerTypes() {
        const categorieId = categorie.value;
        let selectionValide = false;

        Array.from(type.options).forEach(function (option, index) {
            if (index === 0) {
                option.hidden = false;
                return;
            }

            const visible = option.dataset.category === categorieId;
            option.hidden = !visible;

            if (option.selected && visible) {
                selectionValide = true;
            }
        });

        if (!selectionValide) {
            type.value = '';
        }
    }

    categorie.addEventListener('change', filtrerTypes);
    filtrerTypes();
});
</script>
@endpush
