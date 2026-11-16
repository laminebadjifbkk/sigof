
@extends('layout.user-layout')

@section('space-work')
<div class="container-fluid py-3">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">
                {{ isset($element) ? 'Modifier' : 'Ajouter' }} : {{ $config['singulier'] }}
            </h3>
            <div class="text-muted">Gestion des stocks informatiques</div>
        </div>

        <a href="{{ route('support.stocks.catalogue.index', $type) }}"
           class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Retour à la liste
        </a>
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

            <form method="POST"
                  id="catalogueForm"
                  action="{{ isset($element)
                    ? route('support.stocks.catalogue.update', [$type, $element->id])
                    : route('support.stocks.catalogue.store', $type) }}">

                @csrf

                @if(isset($element))
                    @method('PUT')
                @endif

                @if($type === 'articles' && isset($element))
                    <div class="mb-3">
                        <label class="form-label">Référence article</label>
                        <input class="form-control"
                               value="{{ $element->reference }}"
                               disabled>
                    </div>
                @endif

                @if($type === 'articles')
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        Les seuils permettent de surveiller les niveaux de stock.
                        Le stock maximum doit être supérieur ou égal au seuil minimum.
                    </div>
                @endif

                <div class="row g-3">

                    @foreach($config['champs'] as $name => $field)

                        <div class="{{ $field['type'] === 'textarea' ? 'col-12' : 'col-md-6' }}">

                            <label class="form-label" for="{{ $name }}">
                                {{ $field['label'] }}
                                @if(!empty($field['required']))
                                    <span class="text-danger">*</span>
                                @endif
                            </label>

                            @if($field['type'] === 'textarea')

                                <textarea
                                    name="{{ $name }}"
                                    id="{{ $name }}"
                                    rows="3"
                                    class="form-control @error($name) is-invalid @enderror"
                                    @if(!empty($field['required'])) required @endif
                                >{{ old($name, $element->$name ?? '') }}</textarea>

                            @elseif($field['type'] === 'checkbox')

                                <div class="form-check mt-2">
                                    <input
                                        type="checkbox"
                                        name="{{ $name }}"
                                        value="1"
                                        class="form-check-input"
                                        id="{{ $name }}"
                                        @checked(old($name, $element->$name ?? false))
                                    >
                                    <label class="form-check-label" for="{{ $name }}">
                                        Oui
                                    </label>
                                </div>

                            @elseif($field['type'] === 'categorie')

                                <select
                                    name="{{ $name }}"
                                    id="{{ $name }}"
                                    class="form-select @error($name) is-invalid @enderror"
                                    required
                                >
                                    <option value="">Sélectionner une catégorie</option>

                                    @foreach($categories as $categorie)
                                        <option
                                            value="{{ $categorie->id }}"
                                            @selected(old($name, $element->$name ?? '') == $categorie->id)
                                        >
                                            {{ $categorie->libelle }}
                                        </option>
                                    @endforeach
                                </select>

                            @elseif($field['type'] === 'unite')

                                <select
                                    name="{{ $name }}"
                                    id="{{ $name }}"
                                    class="form-select @error($name) is-invalid @enderror"
                                    required
                                >
                                    <option value="">Sélectionner une unité</option>

                                    @foreach($unites as $unite)
                                        <option
                                            value="{{ $unite->id }}"
                                            @selected(old($name, $element->$name ?? '') == $unite->id)
                                        >
                                            {{ $unite->libelle }} ({{ $unite->symbole }})
                                        </option>
                                    @endforeach
                                </select>

                            @else

                                <input
                                    type="{{ $field['type'] }}"
                                    name="{{ $name }}"
                                    id="{{ $name }}"
                                    class="form-control @error($name) is-invalid @enderror"
                                    value="{{ old($name, $element->$name ?? '') }}"
                                    @if(isset($field['step']))
                                        step="{{ $field['step'] }}"
                                    @endif
                                    @if(!empty($field['required'])) required @endif
                                    @if($field['type'] === 'number') min="0" @endif
                                >

                            @endif

                            @error($name)
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror

                        </div>

                    @endforeach

                </div>

                @if($type === 'articles')
                    <div id="stockMaximumError"
                         class="alert alert-danger mt-3 d-none">
                        Le stock maximum doit être supérieur ou égal au stock minimum.
                    </div>
                @endif

                <hr class="my-4">

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('support.stocks.catalogue.index', $type) }}"
                       class="btn btn-light">
                        Annuler
                    </a>

                    <button type="submit" class="btn btn-warning" id="submitButton">
                        <i class="bi bi-check-circle"></i>
                        {{ isset($element) ? 'Enregistrer les modifications' : 'Enregistrer' }}
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

@if($type === 'articles')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const minimum = document.getElementById('stock_minimum');
    const maximum = document.getElementById('stock_maximum');
    const error = document.getElementById('stockMaximumError');
    const form = document.getElementById('catalogueForm');

    function verifierSeuils() {
        const min = minimum.value;
        const max = maximum.value;

        const invalide =
            min !== '' &&
            max !== '' &&
            Number(max) < Number(min);

        maximum.classList.toggle('is-invalid', invalide);
        error.classList.toggle('d-none', !invalide);

        return !invalide;
    }

    minimum.addEventListener('input', verifierSeuils);
    maximum.addEventListener('input', verifierSeuils);

    form.addEventListener('submit', function (event) {
        if (!verifierSeuils()) {
            event.preventDefault();
            maximum.focus();
        }
    });
});
</script>
@endif
@endsection
