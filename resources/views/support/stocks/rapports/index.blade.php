@extends('layout.user-layout')

@section('space-work')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h4 class="mb-1">États et rapports de stock</h4>
            <p class="text-muted mb-0">Situation actuelle et valorisation du stock central</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('support.stocks.rapports.etat.excel', request()->query()) }}" class="btn btn-success btn-sm">
                <i class="bi bi-file-earmark-excel"></i> Excel
            </a>
            <a href="{{ route('support.stocks.rapports.etat.pdf', request()->query()) }}" class="btn btn-danger btn-sm">
                <i class="bi bi-file-earmark-pdf"></i> PDF
            </a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm"><div class="card-body">
                <div class="text-muted">Articles actifs</div>
                <h4>{{ number_format($totaux->nombre_articles ?? 0, 0, ',', ' ') }}</h4>
            </div></div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm"><div class="card-body">
                <div class="text-muted">Quantité totale</div>
                <h4>{{ number_format((float) ($totaux->quantite_totale ?? 0), 3, ',', ' ') }}</h4>
            </div></div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm"><div class="card-body">
                <div class="text-muted">Valeur totale du stock</div>
                <h4>{{ number_format((float) ($totaux->valeur_totale ?? 0), 0, ',', ' ') }} FCFA</h4>
            </div></div>
        </div>
    </div>

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Article</label>
                    <select name="article_id" class="form-select">
                        <option value="">Tous les articles</option>
                        @foreach($articles as $article)
                            <option value="{{ $article->id }}" @selected(request('article_id') == $article->id)>
                                {{ $article->reference }} - {{ $article->designation }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Catégorie</label>
                    <select name="categorie_id" class="form-select">
                        <option value="">Toutes les catégories</option>
                        @foreach($categories as $categorie)
                            <option value="{{ $categorie->id }}" @selected(request('categorie_id') == $categorie->id)>
                                {{ $categorie->libelle }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-5 d-flex gap-2">
                    <button class="btn btn-primary">Filtrer</button>
                    <a href="{{ route('support.stocks.rapports.index') }}" class="btn btn-outline-secondary">Réinitialiser</a>
                    <a href="{{ route('support.stocks.rapports.mouvements') }}" class="btn btn-outline-dark">Mouvements</a>
                    <a href="{{ route('support.stocks.rapports.alertes') }}" class="btn btn-outline-warning">Alertes</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white"><strong>État détaillé du stock</strong></div>
        <div class="table-responsive">
            <table class="table table-hover table-striped align-middle mb-0">
                <thead>
                    <tr>
                        <th>Référence</th><th>Désignation</th><th>Catégorie</th>
                        <th class="text-end">Quantité</th><th class="text-end">Seuil min.</th>
                        <th class="text-end">CMP</th><th class="text-end">Valeur (FCFA)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stocks as $stock)
                        <tr>
                            <td>{{ $stock->reference }}</td>
                            <td>{{ $stock->designation }}</td>
                            <td>{{ $stock->categorie ?? '-' }}</td>
                            <td class="text-end">{{ number_format((float)$stock->quantite, 3, ',', ' ') }}</td>
                            <td class="text-end">{{ number_format((float)$stock->stock_minimum, 3, ',', ' ') }}</td>
                            <td class="text-end">{{ number_format((float)$stock->cout_moyen_pondere, 4, ',', ' ') }}</td>
                            <td class="text-end">{{ number_format((float)$stock->valeur_stock, 0, ',', ' ') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">Aucun article trouvé.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-body">{{ $stocks->links() }}</div>
    </div>
</div>
@endsection
