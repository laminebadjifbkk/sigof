@extends('layout.user-layout')

@section('space-work')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h3 class="mb-1">Inventaires physiques</h3>
            <p class="text-muted mb-0">Suivi des opérations de comptage et de régularisation des stocks.</p>
        </div>
        @can('support.stocks.manage')
            <a href="{{ route('support.stocks.inventaires.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nouvel inventaire
            </a>
        @endcan
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" class="row g-2">
                <div class="col-md-6">
                    <input type="text" name="recherche" class="form-control"
                           value="{{ request('recherche') }}"
                           placeholder="Référence ou libellé">
                </div>
                <div class="col-md-4">
                    <select name="statut" class="form-control">
                        <option value="">Tous les statuts</option>
                        @foreach(['BROUILLON','EN_COURS','EN_ATTENTE_VALIDATION','REJETEE','VALIDEE'] as $statut)
                            <option value="{{ $statut }}" @selected(request('statut') === $statut)>
                                {{ str_replace('_', ' ', $statut) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button class="btn btn-primary w-100">Filtrer</button>
                    <a href="{{ route('support.stocks.inventaires.index') }}" class="btn btn-light">Effacer</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Référence</th>
                        <th>Libellé</th>
                        <th>Date de début</th>
                        <th>Articles</th>
                        <th>Créateur</th>
                        <th>Statut</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($inventaires as $inventaire)
                        @php
                            $couleurs = [
                                'BROUILLON' => 'secondary',
                                'EN_COURS' => 'info',
                                'EN_ATTENTE_VALIDATION' => 'warning',
                                'REJETEE' => 'danger',
                                'VALIDEE' => 'success',
                            ];
                        @endphp
                        <tr>
                            <td class="fw-bold">{{ $inventaire->reference }}</td>
                            <td>{{ $inventaire->libelle }}</td>
                            <td>{{ $inventaire->date_debut?->format('d/m/Y') }}</td>
                            <td>{{ $inventaire->lignes_count }}</td>
                            <td>{{ $inventaire->createur?->firstname }} {{ $inventaire->createur?->name }}</td>
                            <td>
                                <span class="badge bg-{{ $couleurs[$inventaire->statut] ?? 'secondary' }}">
                                    {{ str_replace('_', ' ', $inventaire->statut) }}
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('support.stocks.inventaires.show', $inventaire->id) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    Consulter
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">Aucun inventaire trouvé.</td></tr>
                    @endforelse
                </tbody>
            </table>
            {{ $inventaires->links() }}
        </div>
    </div>
</div>
@endsection
