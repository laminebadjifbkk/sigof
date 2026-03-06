@extends('layout.user-layout')
@section('title', 'ONFP | CREATION DETF')
@section('space-work')
    <section class="section register">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="mb-0">Créer une nouvelle formation (DETF)</h3>
                <a href="{{ route('detfs.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left-circle"></i> Retour
                </a>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('detfs.store') }}" method="POST">
                        @csrf

                        <div class="row g-3">

                            <div class="col-md-12">
                                <label for="titre2" class="form-label">Bénéficiaires<span class="text-danger">
                                        *</span></label>
                                <input type="text" name="titre2" id="titre2" placeholder="Bénéficiaires"
                                    class="form-control form-control-sm @error('titre2') is-invalid @enderror"
                                    value="{{ old('titre2') }}">
                                @error('titre2')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="titre1" class="form-label">Intitulé<span class="text-danger">
                                        *</span></label>
                                <input type="text" name="titre1" id="titre1" placeholder="Intitulé"
                                    class="form-control form-control-sm @error('titre1') is-invalid @enderror"
                                    value="{{ old('titre1') }}">
                                @error('titre1')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- <div class="col-md-6">
                                <label for="date1" class="form-label">Date</label>
                                <input type="date" name="date1" id="date1" class="form-control form-control-sm"
                                    value="{{ old('date1') }}">
                            </div> --}}

                            <div class="col-md-6">
                                <label for="pv_commission" class="form-label">PV commission<span class="text-danger">
                                        *</span></label>
                                <input type="text" name="pv_commission" id="pv_commission" placeholder="Pv commission choix opérateurs"
                                    class="form-control form-control-sm @error('pv_commission') is-invalid @enderror"
                                    value="{{ old('pv_commission') }}">
                                @error('pv_commission')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="lieu_formation" class="form-label">Lieu formation<span class="text-danger">
                                        *</span></label>
                                <input type="text" name="lieu_formation" id="lieu_formation" placeholder="Lieu exacte de la formation"
                                    class="form-control form-control-sm @error('lieu_formation') is-invalid @enderror"
                                    value="{{ old('lieu_formation') }}">
                                @error('lieu_formation')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="periode_formation" class="form-label">Période formation<span
                                        class="text-danger">
                                        *</span></label>
                                <input type="text" name="periode_formation" id="periode_formation" placeholder="Le mois et l'année"
                                    class="form-control form-control-sm @error('periode_formation') is-invalid @enderror"
                                    value="{{ old('periode_formation') }}">
                                @error('periode_formation')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <label for="operateurs_id" class="form-label">
                                    Opérateurs <span class="text-danger">*</span>
                                </label>
                                <select name="operateurs_id" id="select-field-operateurs_id"
                                    class="form-select form-select-sm @error('operateurs_id') is-invalid @enderror">
                                    <option value="">-- Choisir un opérateur --</option>
                                    @foreach ($operateurs as $operateur)
                                        <option value="{{ $operateur->id }}"
                                            {{ old('operateurs_id') == $operateur->id ? 'selected' : '' }}>
                                            {{ $operateur?->user?->operateur }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('operateurs_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <label for="ingenieurs_id" class="form-label">
                                    Ingénieur <span class="text-danger">
                                        *</span></label>
                                <select name="ingenieurs_id" id="select-field-ingenieurs_id"
                                    class="form-select form-select-sm @error('ingenieurs_id') is-invalid @enderror">
                                    <option value="">-- Choisir un ingénieur --</option>
                                    @foreach ($ingenieurs as $ingenieur)
                                        <option value="{{ $ingenieur->id }}"
                                            {{ old('ingenieurs_id') == $ingenieur->id ? 'selected' : '' }}>
                                            {{ $ingenieur?->user?->firstname . ' ' . $ingenieur?->user?->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('ingenieurs_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>

                        <div class="d-flex gap-2 mt-3">
                            <button type="submit" class="btn btn-success btn-sm">
                                <i class="bi bi-check-circle"></i> Créer DETF
                            </button>
                            <a href="{{ route('detfs.index') }}" class="btn btn-secondary btn-sm">
                                <i class="bi bi-x-circle"></i> Annuler
                            </a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
