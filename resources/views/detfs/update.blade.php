@extends('layout.user-layout')
@section('title', 'ONFP | MODIFICATION DETF')

@section('space-work')
    <section class="section register">
        <div class="container">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="mb-0">Modifier la formation (DETF)</h3>
                <a href="{{ route('detfs.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left-circle"></i> Retour
                </a>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
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

                    <form action="{{ route('detfs.update', $detf->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">

                            {{-- Titre 1 --}}
                            <div class="col-md-12">
                                <label for="titre1" class="form-label">Bénéficiaires<span class="text-danger">
                                        *</span></label>
                                <input type="text" name="titre1" id="titre1"
                                    class="form-control form-control-sm @error('titre2') is-invalid @enderror"
                                    value="{{ old('titre1', $detf->titre1) }}">
                                @error('titre2')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Titre 2 --}}
                            <div class="col-md-6">
                                <label for="titre2" class="form-label">Intitulé<span class="text-danger">
                                        *</span></label>
                                <input type="text" name="titre2" id="titre2" class="form-control form-control-sm"
                                    value="{{ old('titre2', $detf->titre2) }}">
                            </div>

                            <div class="col-md-6">
                                <label for="date_pv" class="form-label">Date pv choix opérateurs</label>
                                <input type="date" name="date_pv" id="date_pv"
                                    class="form-control form-control-sm @error('date_pv') is-invalid @enderror"
                                    value="{{ old('date_pv', $detf->date1?->format('Y-m-d')) }}">
                                @error('date_pv')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- <div class="col-md-6">
                                <label for="pv_commission" class="form-label">PV commission<span class="text-danger">
                                        *</span></label>
                                <input type="text" name="pv_commission" id="pv_commission"
                                    placeholder="Pv commission choix opérateurs"
                                    class="form-control form-control-sm @error('pv_commission') is-invalid @enderror"
                                    value="{{ old('pv_commission', $detf->pvchoixoperateur) }}">
                                @error('pv_commission')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div> --}}

                            <div class="col-md-6">
                                <label for="lieu_formation" class="form-label">Lieu formation<span class="text-danger">
                                        *</span></label>
                                <input type="text" name="lieu_formation" id="lieu_formation"
                                    placeholder="Lieu exacte de la formation"
                                    class="form-control form-control-sm @error('lieu_formation') is-invalid @enderror"
                                    value="{{ old('lieu_formation', $detf->lieu_de_formation) }}">
                                @error('lieu_formation')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="periode_formation" class="form-label">Période formation<span
                                        class="text-danger">
                                        *</span></label>
                                <input type="text" name="periode_formation" id="periode_formation"
                                    placeholder="Le mois et l'année"
                                    class="form-control form-control-sm @error('periode_formation') is-invalid @enderror"
                                    value="{{ old('periode_formation', $detf->periode_de_formation) }}">
                                @error('periode_formation')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Opérateurs --}}
                            <div class="col-md-6">
                                <label for="operateurs_id" class="form-label">
                                    Opérateur <span class="text-danger">*</span>
                                </label>
                                <select name="operateurs_id" id="select-field-operateurs_id"
                                    class="form-select form-select-sm @error('operateurs_id') is-invalid @enderror">
                                    <option value="">-- Choisir un opérateur --</option>
                                    @foreach ($operateurs as $operateur)
                                        <option value="{{ $operateur->id }}"
                                            {{ old('operateurs_id', $detf->operateurs_id) == $operateur->id ? 'selected' : '' }}>
                                            {{ $operateur?->user?->operateur }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('operateurs_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Ingénieurs --}}
                            <div class="col-md-6">
                                <label for="ingenieurs_id" class="form-label">
                                    Ingénieur <span class="text-danger">*</span>
                                </label>
                                <select name="ingenieurs_id" id="select-field-ingenieurs_id"
                                    class="form-select form-select-sm @error('ingenieurs_id') is-invalid @enderror">
                                    <option value="">-- Choisir un ingénieur --</option>
                                    @foreach ($ingenieurs as $ingenieur)
                                        <option value="{{ $ingenieur->id }}"
                                            {{ old('ingenieurs_id', $detf->ingenieurs_id) == $ingenieur->id ? 'selected' : '' }}>
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
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="bi bi-pencil-square"></i> Mettre à jour
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
