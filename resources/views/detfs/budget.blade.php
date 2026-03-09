@extends('layout.user-layout')
@section('title', "ONFP | Budget DETF {$detf->numero}")

@section('space-work')
    <div class="container">

        {{-- Header et actions --}}
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

        {{-- Success message --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Formulaire Ajouter ligne --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card shadow-sm border-0">

                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <span class="fw-semibold">
                        <i class="bi bi-plus-circle me-1"></i>
                        Ajouter une ligne budgétaire
                    </span>

                    {{-- <a href="{{ route('budget-labels.create') }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-plus-circle me-1"></i> Ajouter label
                    </a> --}}
                    <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                        data-bs-target="#addBudgetLabelModal">
                        <i class="bi bi-plus-circle me-1"></i> Ajouter label
                    </a>
                </div>

                <div class="card-body">
                    <form action="{{ route('detfs.budget-items.store', $detf->id) }}" method="POST">
                        @csrf
                        <div class="row g-4">

                            {{-- Libellé --}}
                            <div class="col-md-8">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-tag"></i> Libellé <span class="text-danger">*</span>
                                </label>
                                <select name="label_id" class="form-select form-select-sm" id="select-field-lebelles_id">
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

                            {{-- Unité --}}
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-box"></i> Unité
                                </label>
                                <input type="text" name="unite" class="form-control form-control-sm"
                                    placeholder="Ex: Kit" value="{{ old('unite') }}">
                            </div>

                            {{-- Quantité --}}
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-123"></i> Quantité <span class="text-danger">*</span>
                                </label>
                                <input type="number" name="quantite" id="quantite" class="form-control form-control-sm"
                                    value="{{ old('quantite', 1) }}" min="0">
                            </div>

                            {{-- Prix unitaire --}}
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-cash"></i> Prix Unitaire <span class="text-danger">*</span>
                                </label>
                                <input type="number" name="prix_unitaire" id="prix_unitaire"
                                    class="form-control form-control-sm" value="{{ old('prix_unitaire', 0) }}"
                                    min="0">
                            </div>

                            {{-- Montant --}}
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-calculator"></i> Montant
                                </label>
                                <input type="text" id="montant" class="form-control form-control-sm bg-white" readonly>
                            </div>
                        </div>

                        <div class="mt-4 text-end">
                            <button type="submit" class="btn btn-sm btn-success px-4">
                                <i class="bi bi-check-circle"></i> Ajouter au budget
                            </button>
                        </div>
                    </form> <!-- formulaire ici -->
                </div>

            </div>
        </div>
        <div class="modal fade" id="addBudgetLabelModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <!-- Header -->
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="bi bi-plus-circle me-1"></i>
                            Ajouter un libellé budgétaire
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <!-- Body -->
                    <div class="modal-body">

                        <form action="{{ route('budget-labels.store') }}" method="POST">
                            @csrf

                            @include('budget_labels._form')

                            <div class="mt-3 d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">
                                    Annuler
                                </button>

                                <button type="submit" class="btn btn-sm btn-success">
                                    <i class="bi bi-check-circle"></i> Enregistrer
                                </button>
                            </div>

                        </form>

                    </div>

                </div>
            </div>
        </div>

        @php
            $totalGeneral = 0;
            $i = 1;
            $totalGroups = count($grouped);
        @endphp

        @foreach ($grouped as $type => $items)
            @php
                $sousTotal = $items->sum('montant');
                $totalGeneral += $sousTotal;
                $isLastGroup = $i == $totalGroups;
            @endphp

            <div class="card mb-4">

                <div class="card-header" style="background-color: #E0E0E0; color: #000000;">
                    <strong>{{ strtoupper($type) }}</strong>
                </div>

                <div class="card-body p-0">
                    <table class="table table-bordered mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>N°</th>
                                @if ($isLastGroup)
                                    <th>Rubriques</th>
                                @endif
                                <th>Libellé</th>
                                <th>Unité</th>
                                <th>Quantité</th>
                                <th>Prix Unitaire</th>
                                <th>Montant</th>
                                <th width="2%">#</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($items as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    @if ($isLastGroup)
                                        <td>-</td> <!-- colonne Rubriques vide pour TOTAL GENERAL -->
                                    @endif
                                    <td>{{ $item->label->libelle }}</td>
                                    <td>{{ $item->unite }}</td>
                                    <td>{{ $item->quantite }}</td>
                                    <td class="text-end">{{ number_format($item->prix_unitaire, 0, ',', ' ') }}</td>
                                    <td class="text-end">{{ number_format($item->montant, 0, ',', ' ') }}</td>
                                    <td>
                                        <div class="d-flex align-items-baseline"> <a
                                                href="{{ route('budget-items.edit', ['detf' => $detf->id, 'budget_item' => $item->id]) }}"
                                                class="btn btn-success btn-sm" title="Modifier"> <i
                                                    class="bi bi-pencil"></i> </a>
                                            <div class="filter"> <a class="icon" href="#"
                                                    data-bs-toggle="dropdown">
                                                    <i class="bi bi-three-dots"></i> </a>
                                                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                                    <li>
                                                        <form
                                                            action="{{ route('budget-items.destroy', ['detf' => $detf->id, 'budget_item' => $item->id]) }}"
                                                            method="post"> @csrf @method('DELETE') <button type="submit"
                                                                class="dropdown-item show_confirm"> <i
                                                                    class="bi bi-trash"></i> Supprimer </button> </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                            <tr class="{{ $isLastGroup ? 'bg-dark text-white fw-bold' : 'table-secondary fw-bold' }}">
                                <td colspan="{{ $isLastGroup ? 6 : 5 }}" class="text-end">
                                    {{ $isLastGroup ? 'TOTAL GENERAL' : 'Sous-total ' . $i }}
                                </td>
                                <td class="text-end">
                                    {{ number_format($sousTotal, 0, ',', ' ') }} FCFA
                                </td>
                                <td></td>
                            </tr>

                        </tbody>
                    </table>
                </div>

            </div>

            @php $i++; @endphp
        @endforeach

        {{-- Total général --}}
        {{-- <div class="card">
            <div class="card-body text-end">
                <h5 class="fw-bold">
                    TOTAL GÉNÉRAL : {{ number_format($totalGeneral, 0, ',', ' ') }} FCFA
                </h5>
            </div>
        </div> --}}

    </div>
@endsection

@push('scripts')
    <script>
        function calculerMontant() {
            let qte = parseFloat(document.getElementById('quantite').value) || 0;
            let pu = parseFloat(document.getElementById('prix_unitaire').value) || 0;
            document.getElementById('montant').value = (qte * pu).toLocaleString('fr-FR') + " FCFA";
        }

        document.getElementById('quantite').addEventListener('input', calculerMontant);
        document.getElementById('prix_unitaire').addEventListener('input', calculerMontant);
        calculerMontant();
    </script>
@endpush
