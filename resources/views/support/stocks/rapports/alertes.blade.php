@extends('layout.user-layout')

@section('space-work')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h4 class="mb-1">Alertes de stock</h4>
            <p class="text-muted mb-0">Articles sous le seuil minimum et ruptures de stock</p>
        </div>
        <a href="{{ route('support.stocks.rapports.index') }}" class="btn btn-outline-secondary">État général</a>
    </div>

    <div class="alert alert-warning">
        <strong>{{ $nombreRuptures }}</strong> article(s) en rupture totale de stock.
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
                <div class="col-md-4">
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
                <div class="col-md-4 d-flex gap-2">
                    <button class="btn btn-primary">Filtrer</button>
                    <a href="{{ route('support.stocks.rapports.alertes') }}" class="btn btn-outline-secondary">Effacer</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr><th>Référence</th><th>Article</th><th>Catégorie</th>
                        <th class="text-end">Disponible</th><th class="text-end">Seuil minimum</th><th>État</th></tr>
                </thead>
                <tbody>
                    @forelse($alertes as $article)
                        <tr>
                            <td>{{ $article->reference }}</td>
                            <td>{{ $article->designation }}</td>
                            <td>{{ $article->categorie ?? '-' }}</td>
                            <td class="text-end">{{ number_format((float)$article->quantite, 3, ',', ' ') }}</td>
                            <td class="text-end">{{ number_format((float)$article->stock_minimum, 3, ',', ' ') }}</td>
                            <td>
                                @if((float)$article->quantite <= 0)
                                    <span class="badge bg-danger">Rupture</span>
                                @else
                                    <span class="badge bg-warning text-dark">Sous le seuil</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-success py-4">Aucune alerte de stock.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-body">{{ $alertes->links() }}</div>
    </div>
</div>
@endsection
