@extends('layout.user-layout')

@section('space-work')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h4 class="mb-1">Historique des mouvements</h4>
            <p class="text-muted mb-0">Recherche et traçabilité des entrées, sorties et ajustements</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('support.stocks.rapports.mouvements.excel', request()->query()) }}" class="btn btn-success btn-sm">Excel</a>
            <a href="{{ route('support.stocks.rapports.mouvements.pdf', request()->query()) }}" class="btn btn-danger btn-sm">PDF</a>
        </div>
    </div>

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Date début</label>
                    <input type="date" name="date_debut" class="form-control" value="{{ request('date_debut') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Date fin</label>
                    <input type="date" name="date_fin" class="form-control" value="{{ request('date_fin') }}">
                </div>
                <div class="col-md-3">
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
                    <label class="form-label">Type de mouvement</label>
                    <select name="type_mouvement" class="form-select">
                        <option value="">Tous les types</option>
                        @foreach($types as $type)
                            <option value="{{ $type }}" @selected(request('type_mouvement') == $type)>{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 d-flex gap-2">
                    <button class="btn btn-primary">Rechercher</button>
                    <a href="{{ route('support.stocks.rapports.mouvements') }}" class="btn btn-outline-secondary">Réinitialiser</a>
                    <a href="{{ route('support.stocks.rapports.index') }}" class="btn btn-outline-dark">État général</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover table-striped align-middle mb-0">
                <thead>
                    <tr><th>Date</th><th>Document</th><th>Type</th><th>Article</th>
                        <th class="text-end">Quantité</th><th class="text-end">Coût unitaire</th>
                        <th class="text-end">Valeur</th><th class="text-end">Avant</th><th class="text-end">Après</th></tr>
                </thead>
                <tbody>
                    @forelse($mouvements as $m)
                        <tr>
                            <td class="text-nowrap">{{ \Carbon\Carbon::parse($m->date_mouvement)->format('d/m/Y H:i') }}</td>
                            <td>{{ $m->document_reference ?? '-' }}</td>
                            <td>{{ $m->type_mouvement }}</td>
                            <td>{{ $m->reference }} - {{ $m->designation }}</td>
                            <td class="text-end">{{ number_format((float)$m->quantite, 3, ',', ' ') }}</td>
                            <td class="text-end">{{ number_format((float)$m->cout_unitaire, 4, ',', ' ') }}</td>
                            <td class="text-end">{{ number_format((float)$m->valeur_mouvement, 0, ',', ' ') }}</td>
                            <td class="text-end">{{ number_format((float)$m->quantite_avant, 3, ',', ' ') }}</td>
                            <td class="text-end">{{ number_format((float)$m->quantite_apres, 3, ',', ' ') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="text-center text-muted py-4">Aucun mouvement pour ces critères.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-body">{{ $mouvements->links() }}</div>
    </div>
</div>
@endsection
