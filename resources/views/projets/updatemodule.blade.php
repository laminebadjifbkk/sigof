@extends('layout.user-layout')
@section('title', 'Modification ' . $projetmodule?->module . ' du projet ' . $projet?->sigle)
@section('space-work')
    <section class="section min-vh-0 d-flex flex-column align-items-center justify-content-center py-0">
        <div class="container-fluid">
            <div class="row justify-content-center">
                @if ($message = Session::get('status'))
                    <div class="alert alert-success bg-success text-light border-0 alert-dismissible fade show"
                        role="alert">
                        <strong>{{ $message }}</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <div class="alert alert-danger bg-danger text-light border-0 alert-dismissible fade show"
                            role="alert"><strong>{{ $error }}</strong></div>
                    @endforeach
                @endif
                <div class="col-lg-12 col-md-12 d-flex flex-column align-items-center justify-content-center">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <span class="d-flex mt-2 align-items-baseline"><a
                                            href="{{ route('projets.show', $projetmodule->projet) }}"
                                            class="btn btn-success btn-sm" title="retour"><i
                                                class="bi bi-arrow-counterclockwise"></i></a>&nbsp;
                                        <p> | {{ $projet?->sigle }}</p>
                                    </span>
                                </div>
                            </div>
                            <h5 class="card-title text-center pb-0 fs-4">Modification du module
                                {{ $projetmodule?->module }}</h5>
                            <form method="post" action="{{ route('projetmodules.update', $projetmodule) }}"
                                enctype="multipart/form-data" class="p-3">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <input type="hidden" name="projet" value="{{ $projet?->id }}">

                                    <div class="row g-3">
                                        <div class="col-md-12">
                                            <label for="module_name" class="form-label">Module<span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="module" id="module_name"
                                                class="form-control form-control-sm"
                                                value="{{ old('module', $projetmodule?->module) }}" required>
                                            <div id="countryList"></div>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="domaine" class="form-label">Domaine<span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="domaine" id="domaine"
                                                class="form-control form-control-sm" value="{{ $projetmodule?->domaine }}"
                                                required>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="effectif" class="form-label">Effectif prévu</label>
                                            <input type="number" min="0" name="effectif" id="effectif"
                                                class="form-control form-control-sm"
                                                value="{{ old('effectif', $projetmodule?->effectif) }}">
                                        </div>
                                        <div class="col-md-12">
                                            <label for="localites" class="form-label">Sélectionner les localités</label>
                                            <select name="projetlocalites[]" class="form-select" aria-label="Select"
                                                id="multiple-select-field" multiple
                                                data-placeholder="Choisir une ou plusieurs localités">
                                                @foreach ($projetlocalites as $projetlocalite)
                                                    <option value="{{ $projetlocalite->localite }}"
                                                        {{ in_array($projetlocalite->localite, $moduleLocalites) ? 'selected' : '' }}>
                                                        {{ $projetlocalite->localite }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>


                                        <div class="col-md-12">
                                            <label for="description" class="form-label">Description du module</label>
                                            <textarea name="description" id="description" rows="5" class="form-control form-control-sm"
                                                placeholder="Modifier la description du module...">{{ old('description', $projetmodule?->description) }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    @if (auth()->user()->hasRole(['super-admin', 'admin']))
                                        <button type="submit" class="btn btn-warning btn-sm">
                                            <i class="bi bi-pencil-square me-1"></i> Sauvegarder les modifications
                                        </button>
                                    @endif
                                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                                        <i class="bi bi-x-circle me-1"></i> Fermer
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
@endsection
