@extends('layout.user-layout')
@section('title', "ONFP | Budget DETF {$detf->numero}")

@section('space-work')
    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">
                Compléter le budget pour le DETF :
                <strong>{{ $detf->numero }}</strong>
            </h4>

            <div>
                <a href="{{ route('detfs.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left-circle"></i> Retour
                </a>

                <a href="{{ route('detfs.export.word', $detf->id) }}" class="btn btn-success btn-sm">
                    <i class="bi bi-file-earmark-word"></i> Export Word
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- ================= FORMULAIRE ================= --}}
        <div class="card shadow-sm border-0 mb-4">

            <div class="card-header bg-primary text-white">
                <i class="bi bi-plus-circle"></i>
                <strong> Ajouter une ligne budgétaire</strong>
            </div>

            <div class="card-body bg-light">

                <form action="{{ route('detfs.budget-items.store', $detf->id) }}" method="POST">
                    @csrf

                    <div class="row g-4">

                        {{-- LIBELLE --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-tag"></i> Libellé <span class="text-danger">
                                    *</span>
                            </label>
                            <select name="label_id" class="form-select form-select-sm">
                                <option value="">-- Sélectionner --</option>
                                @foreach ($labels as $label)
                                    <option value="{{ $label->id }}">
                                        {{ $label->libelle }}
                                        <small class="text-muted">({{ ucfirst($label->type) }})</small>
                                    </option>
                                @endforeach
                            </select>
                            @error('label_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- UNITE --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-box"></i> Unité
                            </label>
                            <input type="text" name="unite" class="form-control form-control-sm" placeholder="Ex: Kit"
                                value="{{ old('unite') }}">
                        </div>

                        {{-- QUANTITE --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-123"></i> Quantité<span class="text-danger">
                                    *</span>
                            </label>
                            <input type="number" name="quantite" id="quantite" class="form-control form-control-sm"
                                value="{{ old('quantite', 1) }}" min="0">
                        </div>

                        {{-- PRIX --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-cash"></i> Prix Unitaire<span class="text-danger">
                                    *</span>
                            </label>
                            <input type="number" name="prix_unitaire" id="prix_unitaire"
                                class="form-control form-control-sm" value="{{ old('prix_unitaire', 0) }}" min="0">
                        </div>

                        {{-- MONTANT AUTO --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-calculator"></i> Montant<span class="text-danger">
                                    *</span>
                            </label>
                            <input type="text" id="montant" class="form-control form-control-sm bg-white" readonly>
                        </div>

                    </div>

                    <div class="mt-4 text-end">
                        <button type="submit" class="btn btn-sm btn-success px-4">
                            <i class="bi bi-check-circle"></i> Ajouter au budget
                        </button>
                    </div>

                </form>

            </div>
        </div>

        {{-- ================= TABLEAUX PAR TYPE ================= --}}
        @php
            $grouped = $budgetItems->groupBy(fn($item) => $item->label->type ?? 'Autres');
            $totalGeneral = 0;
        @endphp

        @foreach ($grouped as $type => $items)
            @php
                $sousTotal = $items->sum('montant');
                $totalGeneral += $sousTotal;
            @endphp

            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <strong>{{ strtoupper($type) }}</strong>
                </div>

                <div class="card-body p-0">
                    <table class="table table-bordered mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Libellé</th>
                                <th>Unité</th>
                                <th>Quantité</th>
                                <th>Prix Unitaire</th>
                                <th>Montant</th>
                                <th width='2%'>#</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $item)
                                <tr>
                                    <td>{{ $item->label->libelle }}</td>
                                    <td>{{ $item->unite }}</td>
                                    <td>{{ $item->quantite }}</td>
                                    <td>{{ number_format($item->prix_unitaire, 0, ',', ' ') }}</td>
                                    <td>{{ number_format($item->montant, 0, ',', ' ') }}</td>

                                    <td>
                                        <div class="d-flex align-items-baseline">
                                            <a href="{{ route('budget-items.edit', ['detf' => $detf->id, 'budget_item' => $item->id]) }}"
                                                class="btn btn-success btn-sm" title="Modifier">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <div class="filter">
                                                <a class="icon" href="#" data-bs-toggle="dropdown">
                                                    <i class="bi bi-three-dots"></i>
                                                </a>
                                                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                                    <li>
                                                        <form
                                                            action="{{ route('budget-items.destroy', ['detf' => $detf->id, 'budget_item' => $item->id]) }}"
                                                            method="post">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item show_confirm">
                                                                <i class="bi bi-trash"></i> Supprimer
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                            <tr class="table-secondary fw-bold">
                                <td colspan="4" class="text-end">Sous-total</td>
                                <td>{{ number_format($sousTotal, 0, ',', ' ') }} FCFA</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach


        {{-- ================= TOTAL GENERAL ================= --}}
        <div class="card">
            <div class="card-body text-end">
                <h5 class="fw-bold">
                    TOTAL GÉNÉRAL :
                    {{ number_format($totalGeneral, 0, ',', ' ') }} FCFA
                </h5>
            </div>
        </div>

    </div>
@endsection


@push('scripts')
    <script>
        function calculerMontant() {
            let qte = parseFloat(document.getElementById('quantite').value) || 0;
            let pu = parseFloat(document.getElementById('prix_unitaire').value) || 0;
            document.getElementById('montant').value =
                (qte * pu).toLocaleString('fr-FR') + " FCFA";
        }

        document.getElementById('quantite').addEventListener('input', calculerMontant);
        document.getElementById('prix_unitaire').addEventListener('input', calculerMontant);

        calculerMontant();
    </script>
@endpush
