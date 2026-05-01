@extends('layout.user-layout')

@section('title', 'ONFP - QUESTIONNAIRE DE SUIVI POST-FORMATION (INDIVIDUEL)')

@section('space-work')
    <section class="section">
        <div class="container">

            <div class="card shadow-sm">

                {{-- HEADER --}}
                <div
                    class="card-body bg-light border-bottom d-flex justify-content-between align-items-center flex-wrap gap-3">

                    <h5 class="mb-0">
                        QUESTIONNAIRE DE SUIVI POST-FORMATION (INDIVIDUEL)
                    </h5>

                    <a href="{{ url('/demandesIndividuelles') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left"></i> Retour
                    </a>

                </div>

                {{-- FORM --}}
                <div class="card-body">

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

                            {{-- FORMATION --}}
                            <div class="col-md-12">
                                <label class="form-label">Formation suivie</label>

                                <select name="individuelle_id" class="form-select form-select-sm" required>
                                    <option value="">-- Sélectionner la formation --</option>

                                    @foreach ($modulesFormes as $individuelle)
                                        <option value="{{ $individuelle->id }}">
                                            {{ $individuelle->module->name ?? ($individuelle->module->libelle ?? '-') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                        </div>


                        {{-- ===================== --}}
                        {{-- 2. SITUATION --}}
                        {{-- ===================== --}}
                        <h6 class="fw-bold text-primary mt-4 mb-3">2. Situation professionnelle</h6>

                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="form-label">Situation actuelle</label>
                                <select name="situation_actuelle" class="form-select form-select-sm" id="situationSelect">
                                    <option value="">-- Sélectionner --</option>
                                    <option value="employe">Employé(e)</option>
                                    <option value="auto_emploi">Auto-emploi / Entrepreneur(e)</option>
                                    <option value="stage">Stage</option>
                                    <option value="recherche">Recherche d’emploi</option>
                                    <option value="etudes">Études</option>
                                    <option value="autre">Autre</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Délai d’emploi</label>
                                <select name="delai_emploi" class="form-select form-select-sm">
                                    <option value="">-- Sélectionner --</option>
                                    <option value="moins_3">Moins de 3 mois</option>
                                    <option value="3_6">3 à 6 mois</option>
                                    <option value="plus_6">Plus de 6 mois</option>
                                    <option value="aucun">Pas encore d’emploi</option>
                                </select>
                            </div>

                        </div>


                        {{-- EMPLOI --}}
                        <div id="bloc-emploi" class="mt-3" style="display:none;">

                            <div class="row g-3">

                                <div class="col-md-6">
                                    <label class="form-label">Entreprise / activité</label>
                                    <input type="text" name="entreprise" class="form-control form-control-sm">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Secteur</label>
                                    <input type="text" name="secteur" class="form-control form-control-sm">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Lien formation</label>
                                    <select name="lien_formation" class="form-select form-select-sm">
                                        <option value="direct">Direct</option>
                                        <option value="partiel">Partiel</option>
                                        <option value="aucun">Aucun</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Revenus</label>
                                    <select name="revenus" class="form-select form-select-sm">
                                        <option value="moins50">-50 000 FCFA</option>
                                        <option value="50_100">50-100 000</option>
                                        <option value="100_200">100-200 000</option>
                                        <option value="plus200">+200 000</option>
                                    </select>
                                </div>

                            </div>

                        </div>


                        {{-- ===================== --}}
                        {{-- 3. APPRECIATION --}}
                        {{-- ===================== --}}
                        <h6 class="fw-bold text-primary mt-4 mb-3">3. Appréciation</h6>

                        <div class="mb-3">
                            <label>Formation adaptée au marché ?</label><br>

                            <label><input type="radio" name="marche" value="oui"> Oui</label>
                            <label class="ms-3"><input type="radio" name="marche" value="partiel"> Partiel</label>
                            <label class="ms-3"><input type="radio" name="marche" value="non"> Non</label>
                        </div>

                        <div class="mb-3">
                            <textarea name="raison_marche" class="form-control form-control-sm" rows="2" placeholder="Pourquoi ?"></textarea>
                        </div>

                        <div class="mb-3">
                            <label>Recommandation</label><br>

                            <label><input type="radio" name="recommandation" value="oui"> Oui</label>
                            <label class="ms-3"><input type="radio" name="recommandation" value="non">
                                Non</label>
                            <label class="ms-3"><input type="radio" name="recommandation" value="peut-etre">
                                Peut-être</label>
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
                        <h6 class="fw-bold text-primary mt-4 mb-3">6. Diplôme</h6>

                        <div class="mb-3">
                            <label>Retiré ?</label><br>

                            <label><input type="radio" name="diplome" value="oui"> Oui</label>
                            <label class="ms-3"><input type="radio" name="diplome" value="non"> Non</label>
                        </div>

                        <textarea name="raison_diplome" class="form-control form-control-sm mb-3" rows="2"
                            placeholder="Si non, pourquoi ?"></textarea>


                        {{-- ===================== --}}
                        {{-- 7. COMMENTAIRES --}}
                        {{-- ===================== --}}
                        <h6 class="fw-bold text-primary mt-4 mb-3">7. Commentaires</h6>

                        <textarea name="commentaires" class="form-control form-control-sm" rows="3"></textarea>


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
