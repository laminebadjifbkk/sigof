@extends('layout.user-layout')

@section('space-work')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-4">
        <div>
            <a href="{{ route('support.stocks.inventaires.index') }}" class="text-muted">
                <i class="fas fa-arrow-left"></i> Retour aux inventaires
            </a>
            <h3 class="mt-2 mb-1">{{ $inventaire->reference }}</h3>
            <p class="text-muted mb-0">{{ $inventaire->libelle }}</p>
        </div>
        <span class="badge bg-{{ [
            'BROUILLON'=>'secondary',
            'EN_COURS'=>'info',
            'EN_ATTENTE_VALIDATION'=>'warning',
            'REJETEE'=>'danger',
            'VALIDEE'=>'success'
        ][$inventaire->statut] ?? 'secondary' }} fs-6">
            {{ str_replace('_', ' ', $inventaire->statut) }}
        </span>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="card mb-3">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-2"><strong>Date de début :</strong><br>{{ $inventaire->date_debut?->format('d/m/Y') }}</div>
                <div class="col-md-3 mb-2"><strong>Créateur :</strong><br>{{ $inventaire->createur?->firstname }} {{ $inventaire->createur?->name }}</div>
                <div class="col-md-3 mb-2"><strong>Soumis le :</strong><br>{{ $inventaire->date_soumission?->format('d/m/Y H:i') ?? '—' }}</div>
                <div class="col-md-3 mb-2"><strong>Validé le :</strong><br>{{ $inventaire->date_validation?->format('d/m/Y H:i') ?? '—' }}</div>
            </div>
            @if($inventaire->description)
                <hr><strong>Description :</strong><p class="mb-0">{{ $inventaire->description }}</p>
            @endif
            @if($inventaire->motif_rejet)
                <div class="alert alert-danger mt-3 mb-0">
                    <strong>Motif du rejet :</strong><br>{{ $inventaire->motif_rejet }}
                </div>
            @endif
        </div>
    </div>

    @if($inventaire->statut === 'BROUILLON' && $estCreateur)
        <div class="card mb-3">
            <div class="card-header fw-bold">Sélection des articles à inventorier</div>
            <div class="card-body">
                <form method="POST" action="{{ route('support.stocks.inventaires.demarrer', $inventaire->id) }}">
                    @csrf
                    <p class="text-muted">Coche les articles à inclure. Leurs quantités théoriques seront enregistrées et leurs stocks verrouillés au démarrage.</p>
                    <div class="table-responsive" style="max-height:380px;overflow:auto">
                        <table class="table table-sm table-hover">
                            <thead><tr><th></th><th>Référence</th><th>Désignation</th><th>Unité</th></tr></thead>
                            <tbody>
                                @forelse($articles as $article)
                                    <tr>
                                        <td><input type="checkbox" name="article_ids[]" value="{{ $article->id }}"></td>
                                        <td>{{ $article->reference }}</td>
                                        <td>{{ $article->designation }}</td>
                                        <td>{{ $article->unite?->libelle ?? '—' }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center">Aucun article actif.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <button class="btn btn-primary" onclick="return confirm('Démarrer cet inventaire et verrouiller les stocks sélectionnés ?')">
                        Démarrer l'inventaire
                    </button>
                </form>
            </div>
        </div>
    @endif

    <div class="card mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <strong>Articles inventoriés ({{ $inventaire->lignes->count() }})</strong>
            @if(in_array($inventaire->statut, ['EN_COURS','REJETEE'], true) && $estCreateur)
                <a href="{{ route('support.stocks.inventaires.comptage', $inventaire->id) }}"
                   class="btn btn-sm btn-primary">Saisir les comptages</a>
            @endif
        </div>
        <div class="card-body table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Référence</th><th>Article</th><th>Théorique</th>
                        <th>Compté</th><th>Écart</th><th>Compteur</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($inventaire->lignes as $ligne)
                        <tr>
                            <td>{{ $ligne->article?->reference }}</td>
                            <td>{{ $ligne->article?->designation }}</td>
                            <td>{{ number_format((float)$ligne->quantite_theorique, 3, ',', ' ') }}</td>
                            <td>
                                {{ $ligne->quantite_comptee === null ? '—' : number_format((float)$ligne->quantite_comptee, 3, ',', ' ') }}
                            </td>
                            <td>
                                @if($ligne->ecart !== null)
                                    <span class="{{ (float)$ligne->ecart < 0 ? 'text-danger' : ((float)$ligne->ecart > 0 ? 'text-success' : '') }}">
                                        {{ number_format((float)$ligne->ecart, 3, ',', ' ') }}
                                    </span>
                                @else — @endif
                            </td>
                            <td>{{ $ligne->compteur?->firstname }} {{ $ligne->compteur?->name }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted">Les articles seront affichés après le démarrage.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($inventaire->statut === 'EN_COURS' && $estCreateur)
        <form method="POST" action="{{ route('support.stocks.inventaires.soumettre', $inventaire->id) }}" class="mb-3">
            @csrf
            <button class="btn btn-warning"
                    onclick="return confirm('Soumettre cet inventaire pour validation ? Tous les articles doivent être comptés.')">
                Soumettre pour validation
            </button>
        </form>
    @elseif($inventaire->statut === 'REJETEE' && $estCreateur)
        <form method="POST" action="{{ route('support.stocks.inventaires.soumettre', $inventaire->id) }}" class="mb-3">
            @csrf
            <button class="btn btn-warning"
                    onclick="return confirm('Soumettre à nouveau cet inventaire après correction ?')">
                Soumettre à nouveau après correction
            </button>
        </form>
    @endif

    @if($peutValider)
        <div class="card border-warning mb-4">
            <div class="card-header fw-bold">Décision de validation</div>
            <div class="card-body">
                <p>La validation régularisera les écarts et clôturera l'inventaire. Cette opération doit être effectuée par un utilisateur différent du créateur.</p>
                <div class="d-flex flex-wrap gap-2">
                    <form method="POST" action="{{ route('support.stocks.inventaires.valider', $inventaire->id) }}">
                        @csrf
                        <button class="btn btn-success"
                                onclick="return confirm('Confirmer la validation et la régularisation des stocks ?')">
                            Valider l'inventaire
                        </button>
                    </form>
                    <button class="btn btn-outline-danger" data-bs-toggle="collapse" data-bs-target="#formRejet">
                        Rejeter l'inventaire
                    </button>
                </div>
                <div class="collapse mt-3" id="formRejet">
                    <form method="POST" action="{{ route('support.stocks.inventaires.rejeter', $inventaire->id) }}">
                        @csrf
                        <label class="form-label">Motif du rejet *</label>
                        <textarea name="motif_rejet" class="form-control mb-2" rows="3" required maxlength="5000"></textarea>
                        <button class="btn btn-danger">Confirmer le rejet</button>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
