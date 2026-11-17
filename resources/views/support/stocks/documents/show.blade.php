@extends('layout.user-layout')

@section('title', 'ONFP | Détail du document')

@section('space-work')
<div class="container-fluid py-3">

    @php
        $modifiable = auth()->user()->can('support.stocks.manage')
            && in_array($document->statut, ['BROUILLON', 'REJETEE'], true)
            && (int) $document->user_create_id === (int) auth()->id();

        $libelles = [
            'RECEPTION' => 'Réception',
            'SORTIE' => 'Sortie',
            'RETOUR' => 'Retour',
            'AJUSTEMENT_POSITIF' => 'Ajustement positif',
            'AJUSTEMENT_NEGATIF' => 'Ajustement négatif',
        ];

        $couleurs = [
            'BROUILLON' => 'secondary',
            'EN_ATTENTE_VALIDATION' => 'warning',
            'VALIDEE' => 'success',
            'REJETEE' => 'danger',
        ];

        $totalValeur = $document->lignes->sum('valeur_ligne');
        $totalQuantite = $document->lignes->sum('quantite');

        $destinataire = $document->employe
            ? 'Employé : '.trim(($document->employe->firstname ?? '').' '.($document->employe->name ?? ''))
            : ($document->direction->name ?? null);

        if (!$destinataire && $document->antenne) {
            $destinataire = 'Antenne : '.($document->antenne->name ?? $document->antenne->nom ?? '');
        }

        if (!$destinataire && $document->ticket) {
            $destinataire = 'Ticket : '.($document->ticket->reference ?? $document->ticket->id);
        }
    @endphp

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <h3 class="fw-bold mb-1">{{ $document->reference }}</h3>
            <p class="text-muted mb-0">
                {{ $libelles[$document->type_document] ?? $document->type_document }}
                · {{ $document->date_document?->format('d/m/Y') ?? '-' }}
            </p>
        </div>

        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('support.stocks.documents.index') }}"
               class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Liste
            </a>

            @if($modifiable)
                <a href="{{ route('support.stocks.documents.edit', $document->id) }}"
                   class="btn btn-outline-primary">
                    <i class="bi bi-pencil"></i> Modifier l'entête
                </a>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Une erreur est survenue :</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if($document->statut === 'REJETEE')
        <div class="alert alert-danger">
            <div class="fw-bold mb-1">
                <i class="bi bi-exclamation-triangle me-1"></i>
                Document rejeté
            </div>
            {{ $document->motif_rejet ?? 'Aucun motif renseigné.' }}

            @if($modifiable)
                <hr>
                Vous pouvez corriger l'entête et les lignes, puis soumettre à nouveau le document.
            @endif
        </div>
    @endif

    @if($document->statut === 'EN_ATTENTE_VALIDATION')
        <div class="alert alert-warning">
            <i class="bi bi-hourglass-split me-1"></i>
            Ce document est en attente de validation.
        </div>
    @endif

    @if($document->statut === 'VALIDEE')
        <div class="alert alert-success">
            <i class="bi bi-check-circle me-1"></i>
            Document validé. Les mouvements ont été appliqués au stock.
        </div>
    @endif

    {{-- Informations générales --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <strong><i class="bi bi-info-circle me-2"></i>Informations générales</strong>

            <span class="badge bg-{{ $couleurs[$document->statut] ?? 'secondary' }} fs-6">
                {{ str_replace('_', ' ', $document->statut) }}
            </span>
        </div>

        <div class="card-body">
            <div class="row g-4">

                <div class="col-md-3">
                    <small class="text-muted">Type de document</small>
                    <div class="fw-semibold">
                        {{ $libelles[$document->type_document] ?? $document->type_document }}
                    </div>
                </div>

                <div class="col-md-3">
                    <small class="text-muted">Date du document</small>
                    <div class="fw-semibold">
                        {{ $document->date_document?->format('d/m/Y') ?? '-' }}
                    </div>
                </div>

                <div class="col-md-3">
                    <small class="text-muted">Créateur</small>
                    <div class="fw-semibold">
                        {{ trim(($document->createur->firstname ?? '').' '.($document->createur->name ?? '')) ?: '-' }}
                    </div>
                </div>

                <div class="col-md-3">
                    <small class="text-muted">Validateur</small>
                    <div class="fw-semibold">
                        {{ $document->validateur
                            ? trim(($document->validateur->firstname ?? '').' '.$document->validateur->name)
                            : '-' }}
                    </div>
                </div>

                @if($document->date_soumission)
                    <div class="col-md-3">
                        <small class="text-muted">Date de soumission</small>
                        <div class="fw-semibold">
                            {{ $document->date_soumission->format('d/m/Y H:i') }}
                        </div>
                    </div>
                @endif

                @if($document->date_validation)
                    <div class="col-md-3">
                        <small class="text-muted">Date de validation</small>
                        <div class="fw-semibold">
                            {{ $document->date_validation->format('d/m/Y H:i') }}
                        </div>
                    </div>
                @endif

                @if($document->fournisseur)
                    <div class="col-md-6">
                        <small class="text-muted">Fournisseur</small>
                        <div class="fw-semibold">
                            {{ $document->fournisseur->raison_sociale }}
                        </div>
                    </div>
                @endif

                @if($destinataire)
                    <div class="col-md-6">
                        <small class="text-muted">Destinataire</small>
                        <div class="fw-semibold">{{ $destinataire }}</div>
                    </div>
                @endif

                @if($document->motif)
                    <div class="col-md-6">
                        <small class="text-muted">Motif</small>
                        <div>{{ $document->motif }}</div>
                    </div>
                @endif

                @if($document->observations)
                    <div class="col-12">
                        <small class="text-muted">Observations</small>
                        <div>{{ $document->observations }}</div>
                    </div>
                @endif

            </div>
        </div>
    </div>

    {{-- Indicateurs --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <small class="text-muted">Nombre de lignes</small>
                    <h4 class="fw-bold mb-0">{{ $document->lignes->count() }}</h4>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <small class="text-muted">Quantité totale</small>
                    <h4 class="fw-bold mb-0">
                        {{ number_format((float) $totalQuantite, 3, ',', ' ') }}
                    </h4>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <small class="text-muted">Valeur totale</small>
                    <h4 class="fw-bold mb-0 text-primary">
                        {{ number_format((float) $totalValeur, 2, ',', ' ') }} FCFA
                    </h4>
                </div>
            </div>
        </div>
    </div>

    {{-- Lignes du document --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <strong>
                <i class="bi bi-box-seam me-2"></i>Articles du document
            </strong>
            <span class="badge bg-primary">{{ $document->lignes->count() }} ligne(s)</span>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Article</th>
                        <th>Quantité</th>
                        <th>Unité</th>
                        <th>Coût unitaire</th>
                        <th>Valeur totale</th>
                        @if($modifiable)
                            <th>Actions</th>
                        @endif
                    </tr>
                </thead>

                <tbody>
                    @forelse($document->lignes as $ligne)
                        <tr>
                            <td>
                                <div class="fw-semibold">
                                    {{ $ligne->article->designation ?? 'Article supprimé' }}
                                </div>
                                <small class="text-muted">
                                    {{ $ligne->article->reference ?? '' }}
                                </small>

                                @if($ligne->ligneOrigine)
                                    <div class="small text-info mt-1">
                                        Sortie d'origine :
                                        {{ $ligne->ligneOrigine->document->reference ?? '-' }}
                                    </div>
                                @endif

                                @if($ligne->observations)
                                    <div class="small text-muted">
                                        {{ $ligne->observations }}
                                    </div>
                                @endif
                            </td>

                            <td class="fw-semibold">
                                {{ number_format((float) $ligne->quantite, 3, ',', ' ') }}
                            </td>

                            <td>{{ $ligne->article->unite->symbole ?? '-' }}</td>

                            <td class="text-nowrap">
                                {{ number_format((float) $ligne->cout_unitaire, 4, ',', ' ') }}
                                FCFA
                            </td>

                            <td class="fw-semibold text-nowrap">
                                {{ number_format((float) $ligne->valeur_ligne, 2, ',', ' ') }}
                                FCFA
                            </td>

                            @if($modifiable)
                                <td class="text-nowrap">
                                    <button type="button"
                                            class="btn btn-sm btn-outline-primary"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#modifier-ligne-{{ $ligne->id }}">
                                        <i class="bi bi-pencil"></i>
                                    </button>

                                    <form method="POST"
                                          action="{{ route('support.stocks.documents.lignes.destroy', [$document->id, $ligne->id]) }}"
                                          class="d-inline"
                                          onsubmit="return confirm('Supprimer définitivement cette ligne ?')">
                                        @csrf
                                        @method('DELETE')

                                        <button class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            @endif
                        </tr>

                        @if($modifiable)
                            <tr class="collapse bg-light" id="modifier-ligne-{{ $ligne->id }}">
                                <td colspan="6">
                                    <form method="POST"
                                          action="{{ route('support.stocks.documents.lignes.update', [$document->id, $ligne->id]) }}"
                                          class="p-2">
                                        @csrf
                                        @method('PUT')

                                        <div class="row g-2 align-items-end">

                                            <div class="col-md-4">
                                                <label class="form-label">Article</label>
                                                <select name="support_article_stock_id"
                                                        class="form-select" required>
                                                    @foreach($articles as $article)
                                                        <option value="{{ $article->id }}"
                                                            @selected($ligne->support_article_stock_id == $article->id)>
                                                            {{ $article->reference }} —
                                                            {{ $article->designation }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-2">
                                                <label class="form-label">Quantité</label>
                                                <input type="number"
                                                       name="quantite"
                                                       step="0.001"
                                                       min="0.001"
                                                       class="form-control"
                                                       value="{{ $ligne->quantite }}"
                                                       required>
                                            </div>

                                            <div class="col-md-2">
                                                <label class="form-label">Coût unitaire</label>
                                                <input type="number"
                                                       name="cout_unitaire"
                                                       step="0.0001"
                                                       min="0"
                                                       class="form-control"
                                                       value="{{ $ligne->cout_unitaire }}">
                                            </div>

                                            @if($document->type_document === 'RETOUR')
                                                <div class="col-md-4">
                                                    <label class="form-label">Sortie d'origine</label>
                                                    <select name="support_ligne_origine_id"
                                                            class="form-select" required>
                                                        <option value="">Sélectionner</option>
                                                        @foreach($lignesSortie as $origine)
                                                            <option value="{{ $origine->id }}"
                                                                @selected($ligne->support_ligne_origine_id == $origine->id)>
                                                                {{ $origine->document->reference ?? '-' }}
                                                                — {{ $origine->article->designation ?? '-' }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            @endif

                                            <div class="col-md-8">
                                                <label class="form-label">Observations</label>
                                                <input type="text"
                                                       name="observations"
                                                       class="form-control"
                                                       value="{{ $ligne->observations }}">
                                            </div>

                                            <div class="col-md-4">
                                                <button class="btn btn-primary w-100">
                                                    <i class="bi bi-check-lg"></i>
                                                    Enregistrer la ligne
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        @endif

                    @empty
                        <tr>
                            <td colspan="{{ $modifiable ? 6 : 5 }}"
                                class="text-center text-muted py-5">
                                <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                Aucune ligne pour ce document.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

                @if($document->lignes->isNotEmpty())
                    <tfoot class="table-light fw-bold">
                        <tr>
                            <td>TOTAL</td>
                            <td>{{ number_format((float) $totalQuantite, 3, ',', ' ') }}</td>
                            <td></td>
                            <td></td>
                            <td>{{ number_format((float) $totalValeur, 2, ',', ' ') }} FCFA</td>
                            @if($modifiable)
                                <td></td>
                            @endif
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>

    {{-- Ajouter une ligne --}}
    @if($modifiable)
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <strong><i class="bi bi-plus-circle me-2"></i>Ajouter un article</strong>
            </div>

            <div class="card-body">
                <form method="POST"
                      action="{{ route('support.stocks.documents.lignes.store', $document->id) }}">
                    @csrf

                    <div class="row g-3">

                        <div class="col-md-4">
                            <label class="form-label">Article *</label>
                            <select name="support_article_stock_id"
                                    class="form-select" required>
                                <option value="">Sélectionner un article</option>
                                @foreach($articles as $article)
                                    <option value="{{ $article->id }}"
                                        @selected(old('support_article_stock_id') == $article->id)>
                                        {{ $article->reference }} — {{ $article->designation }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Quantité *</label>
                            <input type="number"
                                   name="quantite"
                                   step="0.001"
                                   min="0.001"
                                   class="form-control"
                                   value="{{ old('quantite') }}"
                                   required>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Coût unitaire (FCFA)</label>
                            <input type="number"
                                   name="cout_unitaire"
                                   step="0.0001"
                                   min="0"
                                   class="form-control"
                                   value="{{ old('cout_unitaire', '0') }}">
                        </div>

                        @if($document->type_document === 'RETOUR')
                            <div class="col-md-3">
                                <label class="form-label">Sortie d'origine *</label>
                                <select name="support_ligne_origine_id"
                                        class="form-select" required>
                                    <option value="">Sélectionner</option>
                                    @foreach($lignesSortie as $origine)
                                        <option value="{{ $origine->id }}"
                                            @selected(old('support_ligne_origine_id') == $origine->id)>
                                            {{ $origine->document->reference ?? '-' }}
                                            — {{ $origine->article->designation ?? '-' }}
                                            — {{ $origine->quantite }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div class="col-12">
                            <label class="form-label">Observations</label>
                            <input type="text"
                                   name="observations"
                                   class="form-control"
                                   value="{{ old('observations') }}">
                        </div>
                    </div>

                    <button class="btn btn-primary mt-3">
                        <i class="bi bi-plus-lg"></i> Ajouter la ligne
                    </button>
                </form>
            </div>
        </div>

        {{-- Soumission --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <strong>Transmission pour validation</strong>
                    <div class="small text-muted">
                        Vérifiez les informations et les lignes avant la soumission.
                    </div>
                </div>

                <form method="POST"
                      action="{{ route('support.stocks.documents.soumettre', $document->id) }}"
                      onsubmit="return confirm('Confirmer la soumission de ce document ?')">
                    @csrf

                    <button class="btn btn-success"
                            {{ $document->lignes->isEmpty() ? 'disabled' : '' }}>
                        <i class="bi bi-send me-1"></i>
                        Soumettre à validation
                    </button>
                </form>
            </div>
        </div>
    @endif

    {{-- Validation et rejet --}}
    @if($document->statut === 'EN_ATTENTE_VALIDATION'
        && (int) $document->user_create_id !== (int) auth()->id()
        && auth()->user()->can('support.stocks.movement'))

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <strong><i class="bi bi-clipboard-check me-2"></i>Décision de validation</strong>
            </div>

            <div class="card-body">
                <div class="alert alert-info">
                    La validation applique définitivement les mouvements au stock.
                    Vérifiez les quantités et les articles avant de confirmer.
                </div>

                <div class="d-flex flex-wrap gap-3 align-items-start">

                    <form method="POST"
                          action="{{ route('support.stocks.documents.valider', $document->id) }}"
                          onsubmit="return confirm('Confirmer la validation et l’application des mouvements au stock ?')">
                        @csrf

                        <button class="btn btn-success">
                            <i class="bi bi-check-circle me-1"></i>
                            Valider le document
                        </button>
                    </form>

                    <form method="POST"
                          action="{{ route('support.stocks.documents.rejeter', $document->id) }}"
                          class="flex-grow-1">
                        @csrf

                        <label class="form-label">Motif du rejet *</label>

                        <div class="input-group">
                            <input type="text"
                                   name="motif_rejet"
                                   class="form-control"
                                   maxlength="5000"
                                   placeholder="Préciser les corrections attendues..."
                                   required>

                            <button class="btn btn-danger"
                                    onclick="return confirm('Confirmer le rejet de ce document ?')">
                                <i class="bi bi-x-circle me-1"></i>
                                Rejeter
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    @endif

    {{-- Mouvements générés --}}
    @if($document->statut === 'VALIDEE')
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <strong><i class="bi bi-arrow-left-right me-2"></i>Mouvements générés</strong>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Article</th>
                            <th>Type</th>
                            <th>Quantité avant</th>
                            <th>Quantité mouvementée</th>
                            <th>Quantité après</th>
                            <th>Coût unitaire</th>
                            <th>Valeur</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($document->mouvements as $mouvement)
                            <tr>
                                <td>{{ $mouvement->article->designation ?? '-' }}</td>
                                <td>{{ str_replace('_', ' ', $mouvement->type_mouvement) }}</td>
                                <td>{{ number_format((float) $mouvement->quantite_avant, 3, ',', ' ') }}</td>
                                <td class="fw-semibold">
                                    {{ number_format((float) $mouvement->quantite, 3, ',', ' ') }}
                                </td>
                                <td>{{ number_format((float) $mouvement->quantite_apres, 3, ',', ' ') }}</td>
                                <td>{{ number_format((float) $mouvement->cout_unitaire, 4, ',', ' ') }} FCFA</td>
                                <td>{{ number_format((float) $mouvement->valeur_mouvement, 2, ',', ' ') }} FCFA</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    Aucun mouvement associé à ce document.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

</div>
@endsection
