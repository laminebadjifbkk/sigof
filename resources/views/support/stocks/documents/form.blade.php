@extends('layout.user-layout')

@section('title', 'ONFP | Document de stock')

@section('space-work')

    @php
        $document = $document ?? null;
        $edition = $edition ?? ($document !== null);
    @endphp
<div class="container-fluid py-3">

    @php
        $edition = isset($document);
        $libelles = [
            'RECEPTION' => 'Réception',
            'SORTIE' => 'Sortie',
            'RETOUR' => 'Retour',
            'AJUSTEMENT_POSITIF' => 'Ajustement positif',
            'AJUSTEMENT_NEGATIF' => 'Ajustement négatif',
        ];
    @endphp

    <div class="mb-4">
        <h3 class="fw-bold">{{ $edition ? 'Modifier le document' : 'Nouveau document de stock' }}</h3>
        <p class="text-muted mb-0">
            {{ $edition ? $document?->reference : ($libelles[$type] ?? $type) }}
        </p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form method="POST"
                  action="{{ $edition
                    ? route('support.stocks.documents.update', $document?->id)
                    : route('support.stocks.documents.store') }}">
                @csrf
                @if($edition)
                    @method('PUT')
                @else
                    <input type="hidden" name="type_document" value="{{ $type }}">
                @endif

                <div class="row g-3">
                    @if($edition)
                        <div class="col-md-6">
                            <label class="form-label">Type de document</label>
                            <input class="form-control"
                                   value="{{ $libelles[$type] ?? $type }}" disabled>
                        </div>
                    @endif

                    <div class="col-md-6">
                        <label class="form-label">Date du document *</label>
                        <input type="date" name="date_document" class="form-control"
                               value="{{ old('date_document', isset($document) ? $document?->date_document?->format('Y-m-d') : now()->format('Y-m-d')) }}"
                               required>
                    </div>

                    @if($type === 'RECEPTION')
                        <div class="col-md-6">
                            <label class="form-label">Fournisseur *</label>
                            <select name="support_fournisseur_stock_id" class="form-select" required>
                                <option value="">Sélectionner un fournisseur</option>
                                @foreach($fournisseurs as $fournisseur)
                                    <option value="{{ $fournisseur->id }}"
                                        @selected(old('support_fournisseur_stock_id', $document?->support_fournisseur_stock_id ?? '') == $fournisseur->id)>
                                        {{ $fournisseur->raison_sociale }}
                                    </option>
                                @endforeach
                            </select>
                            @if($fournisseurs->isEmpty())
                                <small class="text-danger">
                                    Aucun fournisseur actif. Créez d'abord un fournisseur dans le catalogue.
                                </small>
                            @endif
                        </div>
                    @endif

                    @if($type === 'SORTIE')
                        @php
                            $employeDocument = $document?->employe ?? null;

                            $typeEntiteInitial = old(
                                'entite_type',
                                $document?->direction_id
                                    ? 'direction'
                                    : ($document?->antenne_id
                                        ? 'antenne'
                                        : ($employeDocument?->directions_id
                                            ? 'direction'
                                            : ($employeDocument?->antennes?->first()?->id
                                                ? 'antenne'
                                                : '')))
                            );

                            $idEntiteInitial = old(
                                'entite_id',
                                $document?->direction_id
                                    ?: ($document?->antenne_id
                                        ?: ($typeEntiteInitial === 'direction'
                                            ? ($employeDocument?->directions_id ?? '')
                                            : ($employeDocument?->antennes?->first()?->id ?? '')))
                            );

                            $employeInitial = old(
                                'employee_id',
                                $document?->employee_id ?? ''
                            );
                        @endphp

                        <div class="col-12">
                            <div class="alert alert-info mb-0">
                                <strong>Destinataire de la sortie</strong><br>
                                Choisissez une direction ou une antenne. Vous pourrez
                                ensuite sélectionner un employé rattaché à cette entité,
                                ou laisser le champ employé vide pour désigner directement
                                l'entité.
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="entite_type" class="form-label">Type d'entité *</label>
                            <select name="entite_type" id="entite_type" class="form-select" required>
                                <option value="">Choisir un type</option>
                                <option value="direction" @selected($typeEntiteInitial === 'direction')>
                                    Direction
                                </option>
                                <option value="antenne" @selected($typeEntiteInitial === 'antenne')>
                                    Antenne
                                </option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="entite_id" class="form-label">Direction / Antenne *</label>
                            <select name="entite_id"
                                    id="entite_id"
                                    class="form-select"
                                    data-url-template="{{ route('support.stocks.documents.employes', ['type' => '__TYPE__', 'id' => '__ID__']) }}"
                                    required>
                                <option value="">Choisir une entité</option>

                                <optgroup label="Directions" data-type="direction">
                                    @foreach($directions as $direction)
                                        <option value="{{ $direction->id }}"
                                            @selected($typeEntiteInitial === 'direction' && (string) $idEntiteInitial === (string) $direction->id)>
                                            {{ $direction->name }}{{ $direction->sigle ? ' ('.$direction->sigle.')' : '' }}
                                        </option>
                                    @endforeach
                                </optgroup>

                                <optgroup label="Antennes" data-type="antenne">
                                    @foreach($antennes as $antenne)
                                        <option value="{{ $antenne->id }}"
                                            @selected($typeEntiteInitial === 'antenne' && (string) $idEntiteInitial === (string) $antenne->id)>
                                            {{ $antenne->name }}{{ $antenne->code ? ' ('.$antenne->code.')' : '' }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="employee_id" class="form-label">Employé bénéficiaire</label>
                            <select name="employee_id"
                                    id="employee_id"
                                    class="form-select"
                                    data-selected="{{ $employeInitial }}">
                                <option value="">Chargement des employés...</option>
                            </select>
                            <small class="text-muted">
                                Laissez vide pour attribuer directement la sortie à l'entité.
                            </small>
                        </div>

                        <div class="col-md-6">
                            <label for="support_ticket_id" class="form-label">Ticket associé (facultatif)</label>
                            <select name="support_ticket_id" id="support_ticket_id" class="form-select">
                                <option value="">Aucun ticket associé</option>
                                @foreach($tickets as $ticket)
                                    <option value="{{ $ticket->id }}"
                                        @selected(old('support_ticket_id', $document?->support_ticket_id ?? '') == $ticket->id)>
                                        {{ $ticket->reference }} — {{ $ticket->objet }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="col-md-6">
                        <label class="form-label">Motif</label>
                        <input type="text" name="motif" maxlength="255"
                               class="form-control"
                               value="{{ old('motif', $document?->motif ?? '') }}">
                    </div>

                    <div class="col-12">
                        <label class="form-label">Observations</label>
                        <textarea name="observations" rows="3"
                                  class="form-control">{{ old('observations', $document?->observations ?? '') }}</textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ $edition
                        ? route('support.stocks.documents.show', $document?->id)
                        : route('support.stocks.documents.index') }}"
                       class="btn btn-outline-secondary">
                        Annuler
                    </a>

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg"></i>
                        {{ $edition ? 'Enregistrer les modifications' : 'Créer le document' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@if($type === 'SORTIE')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const typeSelect = document.getElementById('entite_type');
    const entiteSelect = document.getElementById('entite_id');
    const employeSelect = document.getElementById('employee_id');

    if (!typeSelect || !entiteSelect || !employeSelect) {
        return;
    }

    const urlTemplate = entiteSelect.dataset.urlTemplate;
    const employeInitial = employeSelect.dataset.selected || '';

    function filtrerEntites() {
        const type = typeSelect.value;

        entiteSelect.querySelectorAll('optgroup').forEach(function (groupe) {
            groupe.hidden = groupe.dataset.type !== type;
        });

        const option = entiteSelect.selectedOptions[0];
        if (option && option.parentElement.dataset.type !== type) {
            entiteSelect.value = '';
        }
    }

    async function chargerEmployes(selection = '') {
        const type = typeSelect.value;
        const id = entiteSelect.value;

        employeSelect.innerHTML = '<option value="">Chargement...</option>';
        employeSelect.disabled = true;

        if (!type || !id) {
            employeSelect.innerHTML =
                '<option value="">Sélectionnez d’abord une entité</option>';
            return;
        }

        const url = urlTemplate
            .replace('__TYPE__', encodeURIComponent(type))
            .replace('__ID__', encodeURIComponent(id));

        try {
            const response = await fetch(url, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error('Erreur HTTP ' + response.status);
            }

            const employes = await response.json();

            employeSelect.innerHTML =
                '<option value="">Aucun employé — destinataire direct de l’entité</option>';

            employes.forEach(function (employe) {
                const option = document.createElement('option');
                option.value = employe.id;
                option.textContent = employe.label;

                if (String(employe.id) === String(selection)) {
                    option.selected = true;
                }

                employeSelect.appendChild(option);
            });

            if (employes.length === 0) {
                employeSelect.options[0].textContent =
                    'Aucun employé rattaché à cette entité';
            }

            employeSelect.disabled = false;
        } catch (error) {
            console.error('Chargement des employés impossible :', error);
            employeSelect.innerHTML =
                '<option value="">Erreur de chargement des employés</option>';
        }
    }

    typeSelect.addEventListener('change', function () {
        filtrerEntites();
        entiteSelect.value = '';
        chargerEmployes();
    });

    entiteSelect.addEventListener('change', function () {
        chargerEmployes();
    });

    filtrerEntites();

    if (typeSelect.value && entiteSelect.value) {
        chargerEmployes(employeInitial);
    } else {
        employeSelect.innerHTML =
            '<option value="">Sélectionnez d’abord une entité</option>';
    }
});
</script>
@endif

@endsection
