{{-- @extends('layout.user-layout')
@section('title', 'ONFP - Ajouter un chauffeur')

@section('space-work')
    <section class="section register">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="mb-0">Ajouter un chauffeur</h3>
                <a href="{{ route('parc-chauffeurs.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left-circle"></i> Retour à la liste
                </a>
            </div>

            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                </div>
            @endif

            <!-- Affichage global des erreurs -->
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
                    <form action="{{ route('parc-chauffeurs.store') }}" method="POST">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="matricule" class="form-label">Matricule <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="matricule"
                                    class="form-control form-control-sm @error('matricule') is-invalid @enderror"
                                    value="{{ old('matricule') }}" placeholder="matricule">
                                @error('matricule')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
                                <input type="text" name="nom"
                                    class="form-control form-control-sm @error('nom') is-invalid @enderror"
                                    value="{{ old('nom') }}" placeholder="Diallo">
                                @error('nom')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="prenom" class="form-label">Prénom</label>
                                <input type="text" name="prenom" class="form-control form-control-sm"
                                    value="{{ old('prenom') }}" placeholder="Mamadou">
                            </div>
                            <div class="col-md-6">
                                <label for="telephone" class="form-label">Téléphone</label>
                                <input type="text" name="telephone" class="form-control form-control-sm"
                                    value="{{ old('telephone') }}" placeholder="77 123 45 67">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="permis_numero" class="form-label">Numéro du permis</label>
                                <input type="text" name="permis_numero" class="form-control form-control-sm"
                                    value="{{ old('permis_numero') }}" placeholder="PER-12345">
                            </div>
                            <div class="col-md-6">
                                <label for="permis_categories" class="form-label">Catégories du permis</label>
                                <input type="text" name="permis_categories" class="form-control form-control-sm"
                                    value="{{ old('permis_categories') }}" placeholder="B, C, D">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="permis_expire_le" class="form-label">Expiration du permis</label>
                                <input type="date" name="permis_expire_le" class="form-control form-control-sm"
                                    value="{{ old('permis_expire_le') }}">
                            </div>

                            <div class="col-md-6">
                                <label for="statut" class="form-label">Statut <span class="text-danger">*</span></label>
                                <select name="statut" class="form-select form-select-sm">
                                    <option value="actif" {{ old('statut') == 'actif' ? 'selected' : '' }}>Actif</option>
                                    <option value="indisponible" {{ old('statut') == 'indisponible' ? 'selected' : '' }}>
                                        Indisponible
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-sm btn-success">
                                <i class="bi bi-check-circle"></i> Enregistrer
                            </button>
                            <a href="{{ route('parc-chauffeurs.index') }}" class="btn btn-sm btn-secondary">
                                <i class="bi bi-x-circle"></i> Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
 --}}

@extends('layout.user-layout')
@section('title', 'ONFP - Ajouter un chauffeur')

@section('space-work')
    <section class="section register">
        <div class="container">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="mb-0">Ajouter un chauffeur</h3>
                <a href="{{ route('parc-chauffeurs.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left-circle"></i> Retour
                </a>
            </div>

            {{-- Messages --}}
            @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
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
                    <form action="{{ route('parc-chauffeurs.store') }}" method="POST">
                        @csrf

                        {{-- Sélection employé --}}
                        <div class="mb-3">
                            <label class="form-label">Employé <span class="text-danger">*</span></label>
                            <select name="employe_id" id="employe_id"
                                class="form-select form-select-sm w-100 @error('employe_id') is-invalid @enderror"
                                data-placeholder="Sélectionner un employé">
                                <option value="">-- Sélectionner un employé --</option>
                                @foreach ($employes as $employe)
                                    <option value="{{ $employe->id }}" data-matricule="{{ $employe->matricule }}"
                                        data-nom="{{ $employe->user->name }}" data-prenom="{{ $employe->user->firstname }}"
                                        data-telephone="{{ $employe->user->telephone }}">
                                        {{ $employe->user->name . ' ' . $employe->user->firstname . ' - ' . $employe->matricule }}

                                    </option>
                                @endforeach
                            </select>
                            @error('employe_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Infos employé (readonly) --}}
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Matricule</label>
                                <input type="text" id="matricule" class="form-control form-control-sm" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nom</label>
                                <input type="text" id="nom" class="form-control form-control-sm" readonly>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Prénom</label>
                                <input type="text" id="prenom" class="form-control form-control-sm" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Téléphone</label>
                                <input type="text" id="telephone" class="form-control form-control-sm">
                            </div>
                        </div>

                        {{-- Infos chauffeur --}}
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Numéro du permis <span class="text-danger">*</span></label>
                                <input type="text" name="permis_numero" class="form-control form-control-sm"
                                    value="{{ old('permis_numero') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Catégories du permis</label>
                                <input type="text" name="permis_categories" class="form-control form-control-sm"
                                    value="{{ old('permis_categories') }}">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Expiration du permis <span class="text-danger">*</span></label>
                                <input type="date" name="permis_expire_le" class="form-control form-control-sm"
                                    value="{{ old('permis_expire_le') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Statut <span class="text-danger">*</span></label>
                                <select name="statut" class="form-select form-select-sm">
                                    <option value="actif">Actif</option>
                                    <option value="disponible">disponible</option>
                                    <option value="indisponible">Indisponible</option>
                                </select>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-sm btn-success">
                                <i class="bi bi-check-circle"></i> Enregistrer
                            </button>
                            <a href="{{ route('parc-chauffeurs.index') }}" class="btn btn-sm btn-secondary">
                                Annuler
                            </a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(function() {

            const $employe = $('#employe_id');

            if (!$employe.length) {
                console.error('Select employe_id introuvable');
                return;
            }

            // Init Select2
            if ($.fn.select2) {
                $employe.select2({
                    theme: "bootstrap-5",
                    width: '100%',
                    placeholder: "Sélectionner un employé",
                    allowClear: true
                });
            } else {
                console.error('Select2 non chargé');
            }

            // Fonction commune
            function remplirChamps(option) {
                const data = option.dataset || {};

                $('#matricule').val(data.matricule || '');
                $('#nom').val(data.nom || '');
                $('#prenom').val(data.prenom || '');
                $('#telephone').val(data.telephone || '');
            }

            // Select2
            $employe.on('select2:select', function(e) {
                remplirChamps(e.params.data.element);
            });

            // Fallback natif (sécurité)
            $employe.on('change', function() {
                const option = this.options[this.selectedIndex];
                if (option) remplirChamps(option);
            });

        });
    </script>
@endpush
