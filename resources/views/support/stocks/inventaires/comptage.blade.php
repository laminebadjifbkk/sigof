@extends('layout.user-layout')

@section('space-work')
<div class="container-fluid">
    <div class="mb-4">
        <a href="{{ route('support.stocks.inventaires.show', $inventaire->id) }}" class="text-muted">
            <i class="fas fa-arrow-left"></i> Retour à l'inventaire
        </a>
        <h3 class="mt-2">Comptage physique</h3>
        <p class="text-muted mb-0">{{ $inventaire->reference }} — {{ $inventaire->libelle }}</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Référence / Article</th>
                        <th>Quantité théorique</th>
                        <th>Quantité comptée</th>
                        <th>Écart</th>
                        <th>Enregistrer</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($inventaire->lignes as $ligne)
                        <tr>
                            <td>
                                <strong>{{ $ligne->article?->reference }}</strong><br>
                                <small class="text-muted">{{ $ligne->article?->designation }}</small>
                            </td>
                            <td>{{ number_format((float)$ligne->quantite_theorique, 3, ',', ' ') }}
                                {{ $ligne->article?->unite?->libelle }}
                            </td>
                            <td style="min-width:180px">
                                <form method="POST"
                                      action="{{ route('support.stocks.inventaires.comptage.store', $inventaire->id) }}"
                                      class="d-flex gap-2 align-items-center">
                                    @csrf
                                    <input type="hidden" name="article_id" value="{{ $ligne->support_article_stock_id }}">
                                    <input type="number" name="quantite_comptee"
                                           class="form-control form-control-sm"
                                           min="0" step="0.001"
                                           value="{{ old('quantite_comptee', $ligne->quantite_comptee) }}"
                                           required>
                            </td>
                            <td>
                                @if($ligne->ecart !== null)
                                    <span class="{{ (float)$ligne->ecart < 0 ? 'text-danger' : ((float)$ligne->ecart > 0 ? 'text-success' : '') }}">
                                        {{ number_format((float)$ligne->ecart, 3, ',', ' ') }}
                                    </span>
                                @else — @endif
                            </td>
                            <td style="min-width:240px">
                                    <input type="text" name="observations"
                                           class="form-control form-control-sm mb-2"
                                           placeholder="Observation (facultatif)"
                                           value="{{ $ligne->observations }}">
                                    <button class="btn btn-sm btn-primary">Enregistrer</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('support.stocks.inventaires.show', $inventaire->id) }}"
           class="btn btn-light">Terminer les comptages</a>
    </div>
</div>
@endsection
