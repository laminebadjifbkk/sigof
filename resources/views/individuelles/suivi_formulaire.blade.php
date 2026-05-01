@extends('layout.user-layout')

@section('title', 'ONFP - QUESTIONNAIRE DE SUIVI POST-FORMATION (INDIVIDUEL)')

@section('space-work')
    <section class="section">
        <div class="container">

            <div class="card shadow-sm">

                {{-- HEADER --}}
                <div
                    class="card-body bg-light border-bottom d-flex justify-content-between align-items-center flex-wrap gap-3">

                    <div>
                        <h6 class="mb-1">
                            QUESTIONNAIRE DE SUIVI POST-FORMATION (INDIVIDUEL)
                        </h6>

                        <small class="text-muted">
                            Module : {{ $individuelle?->module?->name }}
                        </small>
                    </div>

                    <a href="{{ url('/individuelles/suivi/modules') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left"></i> Retour
                    </a>

                </div>

                {{-- FORM --}}
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">

                            <strong>Veuillez corriger les erreurs suivantes :</strong>

                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>

                        </div>
                    @endif

                    <form action="{{ route('individuelles.suivi.store') }}" method="POST">
                        @csrf

                        {{-- ===================== --}}
                        {{-- 1. INFORMATIONS --}}
                        {{-- ===================== --}}
                        <h6 class="fw-bold text-primary mb-3">1. Informations générales</h6>

                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="form-label">Prénom</label>
                                <input type="text" class="form-control form-control-sm" value="{{ $user->firstname }}"
                                    readonly>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Nom</label>
                                <input type="text" class="form-control form-control-sm" value="{{ $user->name }}"
                                    readonly>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="text" class="form-control form-control-sm" value="{{ $user->email }}"
                                    readonly>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Téléphone</label>
                                <input type="text" class="form-control form-control-sm" value="{{ $user->telephone }}"
                                    readonly>
                            </div>

                        </div>

                        {{-- ===================== --}}
                        {{-- 2. SITUATION --}}
                        {{-- ===================== --}}
                        <h6 class="fw-bold text-primary mt-4 mb-3">2. Situation professionnelle</h6>

                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="form-label">Situation actuelle</label><span class="text-danger"> *</span>
                                <select name="situation_actuelle"
                                    class="form-select form-select-sm @error('situation_actuelle') is-invalid @enderror"
                                    id="situationSelect">

                                    <option value="">-- Sélectionner --</option>

                                    <option value="employe" {{ old('situation_actuelle') == 'employe' ? 'selected' : '' }}>
                                        Employé(e)
                                    </option>

                                    <option value="auto_emploi"
                                        {{ old('situation_actuelle') == 'auto_emploi' ? 'selected' : '' }}>
                                        Auto-emploi / Entrepreneur(e)
                                    </option>

                                    <option value="stage" {{ old('situation_actuelle') == 'stage' ? 'selected' : '' }}>
                                        Stage
                                    </option>

                                    <option value="recherche"
                                        {{ old('situation_actuelle') == 'recherche' ? 'selected' : '' }}>
                                        Recherche d’emploi
                                    </option>

                                    <option value="etudes" {{ old('situation_actuelle') == 'etudes' ? 'selected' : '' }}>
                                        Études
                                    </option>

                                    <option value="autre" {{ old('situation_actuelle') == 'autre' ? 'selected' : '' }}>
                                        Autre
                                    </option>

                                </select>
                                @error('situation_actuelle')
                                    <span class="invalid-feedback" role="alert">
                                        <div>{{ $message }}</div>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Délai d’emploi</label><span class="text-danger"> *</span>
                                <select name="delai_emploi"
                                    class="form-select form-select-sm @error('delai_emploi') is-invalid @enderror">

                                    <option value="">-- Sélectionner --</option>

                                    <option value="moins_3" {{ old('delai_emploi') == 'moins_3' ? 'selected' : '' }}>
                                        Moins de 3 mois
                                    </option>

                                    <option value="3_6" {{ old('delai_emploi') == '3_6' ? 'selected' : '' }}>
                                        3 à 6 mois
                                    </option>

                                    <option value="plus_6" {{ old('delai_emploi') == 'plus_6' ? 'selected' : '' }}>
                                        Plus de 6 mois
                                    </option>

                                    <option value="aucun" {{ old('delai_emploi') == 'aucun' ? 'selected' : '' }}>
                                        Pas encore d’emploi
                                    </option>

                                </select>
                                @error('delai_emploi')
                                    <span class="invalid-feedback" role="alert">
                                        <div>{{ $message }}</div>
                                    </span>
                                @enderror
                            </div>

                        </div>


                        {{-- EMPLOI --}}
                        <div id="bloc-emploi" class="mt-3" style="display:none;">

                            <div class="row g-3">

                                <div class="col-md-6">
                                    <label class="form-label">Entreprise / activité</label><span class="text-danger">
                                        *</span>
                                    <input type="text" name="entreprise"
                                        class="form-control form-control-sm @error('entreprise') is-invalid @enderror">
                                    @error('entreprise')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Secteur</label><span class="text-danger"> *</span>
                                    <input type="text" name="secteur"
                                        class="form-control form-control-sm @error('secteur') is-invalid @enderror">
                                    @error('secteur')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Lien formation</label><span class="text-danger"> *</span>
                                    <select name="lien_formation"
                                        class="form-select form-select-sm @error('lien_formation') is-invalid @enderror">
                                        <option value="direct">Direct</option>
                                        <option value="partiel">Partiel</option>
                                        <option value="aucun">Aucun</option>
                                    </select>
                                    @error('lien_formation')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Revenus </label><span class="text-danger"> *</span>
                                    <select name="revenus"
                                        class="form-select form-select-sm @error('revenus') is-invalid @enderror">
                                        <option value="moins50">-50 000 FCFA</option>
                                        <option value="50_100">50-100 000</option>
                                        <option value="100_200">100-200 000</option>
                                        <option value="plus200">+200 000</option>
                                    </select>
                                    @error('revenus')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                            </div>

                        </div>


                        {{-- ===================== --}}
                        {{-- 3. APPRECIATION --}}
                        {{-- ===================== --}}
                        <h6 class="fw-bold text-primary mt-4 mb-3">3. Appréciation</h6>

                        <div class="mb-3">
                            <label>Formation adaptée au marché ?</label><span class="text-danger"> *</span><br>

                            <label><input type="radio" name="marche" value="oui"> Oui</label>
                            <label class="ms-3"><input type="radio" name="marche" value="partiel"> Partiel</label>
                            <label class="ms-3"><input type="radio" name="marche" value="non"> Non</label>
                            @error('marche')
                                <span class="invalid-feedback" role="alert">
                                    <div>{{ $message }}</div>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <textarea name="raison_marche" class="form-control form-control-sm @error('raison_marche') is-invalid @enderror"
                                rows="2" placeholder="Pourquoi ?"></textarea>
                            @error('raison_marche')
                                <span class="invalid-feedback" role="alert">
                                    <div>{{ $message }}</div>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label>Recommandation (recommanderiez-vous cette formation à d’autres jeunes) ? </label><span
                                class="text-danger"> *</span><br>

                            <label><input type="radio" name="recommandation" value="oui"> Oui</label>
                            <label class="ms-3"><input type="radio" name="recommandation" value="non">
                                Non</label>
                            <label class="ms-3"><input type="radio" name="recommandation" value="peut-etre">
                                Peut-être</label>
                            @error('recommandation')
                                <span class="invalid-feedback" role="alert">
                                    <div>{{ $message }}</div>
                                </span>
                            @enderror
                        </div>


                        {{-- ===================== --}}
                        {{-- 4. DIFFICULTÉS --}}
                        {{-- ===================== --}}
                        <h6 class="fw-bold text-primary mt-4 mb-3">4. Difficultés</h6>

                        <div class="row">
                            @foreach ($difficultes as $diff)
                                <div class="col-md-6">
                                    <label>
                                        <input type="checkbox" name="difficultes[]" value="{{ $diff }}">
                                        {{ $diff }}
                                    </label>
                                </div>
                            @endforeach
                        </div>


                        {{-- ===================== --}}
                        {{-- 5. BESOINS --}}
                        {{-- ===================== --}}
                        <h6 class="fw-bold text-primary mt-4 mb-3">5. Besoins</h6>

                        <div class="row">
                            @foreach ($besoins as $besoin)
                                <div class="col-md-6">
                                    <label>
                                        <input type="checkbox" name="besoins[]" value="{{ $besoin }}">
                                        {{ $besoin }}
                                    </label>
                                </div>
                            @endforeach
                        </div>


                        {{-- ===================== --}}
                        {{-- 6. DIPLOME --}}
                        {{-- ===================== --}}
                        <h6 class="fw-bold text-primary mt-4 mb-3">6. Diplôme / Attestation</h6>

                        <div class="mb-3">
                            <label>Avez-vous retiré votre diplôme ou attestation ? </label><span class="text-danger">
                                *</span><br>

                            <label><input type="radio" name="diplome" value="1"> Oui</label>
                            <label class="ms-3"><input type="radio" name="diplome" value="0"> Non</label>
                        </div>

                        <textarea name="raison_diplome"
                            class="form-control form-control-sm @error('raison_diplome') is-invalid @enderror mb-3" rows="2"
                            placeholder="Si non, pourquoi ?"></textarea>

                        @error('raison_diplome')
                            <span class="invalid-feedback" role="alert">
                                <div>{{ $message }}</div>
                            </span>
                        @enderror


                        {{-- ===================== --}}
                        {{-- 7. COMMENTAIRES --}}
                        {{-- ===================== --}}
                        <h6 class="fw-bold text-primary mt-4 mb-3">7. Commentaires</h6>

                        <textarea name="commentaires" class="form-control form-control-sm @error('commentaires') is-invalid @enderror"
                            rows="3"></textarea>

                        @error('commentaires')
                            <span class="invalid-feedback" role="alert">
                                <div>{{ $message }}</div>
                            </span>
                        @enderror


                        {{-- SUBMIT --}}
                        <div class="mt-4 text-end">
                            <button type="submit" class="btn btn-success btn-sm">
                                <i class="bi bi-check-circle me-1"></i> Envoyer
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const situation = document.getElementById('situationSelect');
            const blocEmploi = document.getElementById('bloc-emploi');

            function toggleBloc() {
                if (situation.value === 'employe' || situation.value === 'auto_emploi') {
                    blocEmploi.style.display = 'block';
                } else {
                    blocEmploi.style.display = 'none';
                }
            }

            situation.addEventListener('change', toggleBloc);

            // au chargement (cas old() après validation)
            toggleBloc();

        });
    </script>
@endpush
