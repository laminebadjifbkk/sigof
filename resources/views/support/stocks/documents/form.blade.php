@extends('layout.user-layout')

@section('title', 'ONFP | Document de stock')

@section('space-work')
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
            {{ $edition ? $document->reference : ($libelles[$type] ?? $type) }}
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
                    ? route('support.stocks.documents.update', $document->id)
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
                               value="{{ old('date_document', isset($document) ? $document->date_document?->format('Y-m-d') : now()->format('Y-m-d')) }}"
                               required>
                    </div>

                    @if($type === 'RECEPTION')
                        <div class="col-md-6">
                            <label class="form-label">Fournisseur *</label>
                            <select name="support_fournisseur_stock_id" class="form-select" required>
                                <option value="">Sélectionner un fournisseur</option>
                                @foreach($fournisseurs as $fournisseur)
                                    <option value="{{ $fournisseur->id }}"
                                        @selected(old('support_fournisseur_stock_id', $document->support_fournisseur_stock_id ?? '') == $fournisseur->id)>
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
                        <div class="col-12">
                            <div class="alert alert-info mb-0">
                                Une sortie doit avoir exactement un destinataire.
                                Renseignez un seul des identifiants ci-dessous.
                            </div>
                        </div>

                        @foreach([
                            'employee_id' => 'Identifiant de l’employé',
                            'direction_id' => 'Identifiant de la direction',
                            'antenne_id' => 'Identifiant de l’antenne',
                            'support_ticket_id' => 'Identifiant du ticket'
                        ] as $field => $label)
                            <div class="col-md-6">
                                <label class="form-label">{{ $label }}</label>
                                <input type="number" min="1" name="{{ $field }}"
                                       class="form-control"
                                       value="{{ old($field, $document->$field ?? '') }}">
                            </div>
                        @endforeach
                    @endif

                    <div class="col-md-6">
                        <label class="form-label">Motif</label>
                        <input type="text" name="motif" maxlength="255"
                               class="form-control"
                               value="{{ old('motif', $document->motif ?? '') }}">
                    </div>

                    <div class="col-12">
                        <label class="form-label">Observations</label>
                        <textarea name="observations" rows="3"
                                  class="form-control">{{ old('observations', $document->observations ?? '') }}</textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ $edition
                        ? route('support.stocks.documents.show', $document->id)
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
@endsection
