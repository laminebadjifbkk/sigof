@extends('layout.user-layout')
@section('title', "ONFP | Budget DETF {$detf->numero}")
@section('space-work')
    <div class="container">
        <h2>Compléter le budget pour le DETF : {{ $detf->numero }}</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Formulaire pour ajouter une ligne budgétaire --}}
        <form action="{{ route('detfs.budget-items.store', $detf->id) }}" method="POST" class="mb-4">
            @csrf
            <div class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label>Libellé</label>
                    <select name="label_id" class="form-select">
                        <option value="">-- Choisir un libellé --</option>
                        @foreach ($labels as $label)
                            <option value="{{ $label->id }}">{{ $label->libelle }}</option>
                        @endforeach
                    </select>
                    @error('label_id')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-md-2">
                    <label>Unité</label>
                    <input type="text" name="unite" class="form-control" value="{{ old('unite') }}">
                    @error('unite')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-md-2">
                    <label>Quantité</label>
                    <input type="number" name="quantite" class="form-control" value="{{ old('quantite', 1) }}"
                        min="0">
                    @error('quantite')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-md-2">
                    <label>Prix Unitaire (FCFA)</label>
                    <input type="number" name="prix_unitaire" class="form-control" value="{{ old('prix_unitaire', 0) }}"
                        min="0" step="0.01">
                    @error('prix_unitaire')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-success w-100">Ajouter</button>
                </div>
            </div>
        </form>

        {{-- Tableau des lignes déjà ajoutées --}}
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Libellé</th>
                    <th>Unité</th>
                    <th>Quantité</th>
                    <th>Prix Unitaire</th>
                    <th>Montant</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($budgetItems as $item)
                    <tr>
                        <td>{{ $item->label->libelle }}</td>
                        <td>{{ $item->unite }}</td>
                        <td>{{ $item->quantite }}</td>
                        <td>{{ number_format($item->prix_unitaire, 0, ',', ' ') }}</td>
                        <td>{{ number_format($item->montant, 0, ',', ' ') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
