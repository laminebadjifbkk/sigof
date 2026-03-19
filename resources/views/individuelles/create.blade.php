@extends('layout.user-layout')

@section('title', 'ONFP - Nouvelle demande individuelle')

@section('space-work')
    <section class="section">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <a href="{{ route('individuelles.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Retour à la liste
                </a>

                <h4 class="mb-0 text-center flex-grow-1">
                    Ajouter une nouvelle demande individuelle
                </h4>

                {{-- Spacer pour équilibrer le centrage --}}
                <div style="width:120px"></div>
            </div>


            {{-- Erreurs globales --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('addIndividuelle') }}" enctype="multipart/form-data" id="wizardForm">
                @csrf

                {{-- Progress bar --}}
                <div class="progress mb-4">
                    <div class="progress-bar" id="progressBar" style="width:20%"></div>
                </div>

                {{-- ================= STEP 1 – FORMATION ================= --}}
                <div class="step">
                    <h5 class="mb-3">Formation</h5>

                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">
                                Formation sollicitée <span class="required">*</span>
                            </label>
                            <input type="text" name="module" value="{{ old('module_name') }}"
                                class="form-control form-control-sm @error('module_name') is-invalid @enderror"
                                id="module_name" placeholder="Formation sollicitée" autofocus>
                            <div id="countryList"></div>
                            {{ csrf_field() }}
                            @error('module')
                                <span class="invalid-feedback" role="alert">
                                    <div>{{ $message }}</div>
                                </span>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                Lieu de formation (Département) <span class="required">*</span>
                            </label>
                            <select name="departement"
                                class="form-select form-select-sm @error('departement') is-invalid @enderror"
                                aria-label="Select" id="select-field-departement-indiv"
                                data-placeholder="Choisir la localité">
                                <option value="{{ old('departement') }}">{{ old('departement') }}
                                </option>
                                @foreach ($departements as $departement)
                                    <option value="{{ $departement->nom }}">
                                        {{ $departement->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                Date de dépôt <span class="required">*</span>
                            </label>

                            <input type="datetime-local" name="date_depot" id="date_depot"
                                class="form-control form-control-sm @error('date_depot') is-invalid @enderror"
                                value="{{ old('date_depot', now()->format('Y-m-d\TH:i')) }}"
                                placeholder="yyyy-mm-dd HH:MM">

                            @error('date_depot')
                                <span class="invalid-feedback" role="alert">
                                    <div>{{ $message }}</div>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- ================= STEP 2 – IDENTITÉ & PIÈCE ================= --}}
                <div class="step d-none">
                    <h5 class="mb-3">Identité & Pièce</h5>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">
                                Civilité <span class="required">*</span>
                            </label>
                            <select name="civilite"
                                class="form-select form-select-smform-select-form-select-smsm @error('civilite') is-invalid @enderror"
                                aria-label="Select" id="select-field-civilite-indiv" data-placeholder="Choisir civilité">
                                <option value="{{ old('civilite') }}">
                                    {{ old('civilite') }}
                                </option>
                                <option value="M.">
                                    Monsieur
                                </option>
                                <option value="Mme">
                                    Madame
                                </option>
                            </select>
                            @error('civilite')
                                <span class="invalid-feedback" role="alert">
                                    <div>{{ $message }}</div>
                                </span>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">
                                Prénom <span class="required">*</span>
                            </label>
                            <input type="text" name="firstname" value="{{ old('firstname') }}"
                                class="form-control form-control-sm @error('firstname') is-invalid @enderror" id="firstname"
                                placeholder="prénom">
                            @error('firstname')
                                <span class="invalid-feedback" role="alert">
                                    <div>{{ $message }}</div>
                                </span>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">
                                Nom <span class="required">*</span>
                            </label>
                            <input type="text" name="lastname" value="{{ old('lastname') }}"
                                class="form-control form-control-sm @error('lastname') is-invalid @enderror" id="lastname"
                                placeholder="nom">
                            @error('lastname')
                                <span class="invalid-feedback" role="alert">
                                    <div>{{ $message }}</div>
                                </span>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">
                                Date de naissance <span class="required">*</span>
                            </label>
                            <input type="text" name="date_naissance" value="{{ old('date_naissance') }}"
                                class="form-control form-control-sm @error('date_naissance') is-invalid @enderror"
                                id="datepicker" placeholder="JJ/MM/AAAA" autocomplete="bday">
                            @error('date_naissance')
                                <span class="invalid-feedback" role="alert">
                                    <div>{{ $message }}</div>
                                </span>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">
                                Lieu de naissance <span class="required">*</span>
                            </label>
                            <input name="lieu_naissance" type="text"
                                class="form-control form-control-sm @error('lieu_naissance') is-invalid @enderror"
                                id="lieu_naissance" value="{{ old('lieu_naissance') }}" autocomplete="lieu_naissance"
                                placeholder="Lieu naissance">
                            @error('lieu_naissance')
                                <span class="invalid-feedback" role="alert">
                                    <div>{{ $message }}</div>
                                </span>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">
                                Type de pièce <span class="required">*</span>
                            </label>
                            <select name="type_piece" id="type_piece" class="form-select form-select-sm">
                                <option value="">-- Choisir --</option>
                                <option value="cni">Carte nationale</option>
                                <option value="extrait">Extrait de naissance</option>
                                <option value="passeport">Passeport</option>
                            </select>
                        </div>

                        <div class="col-md-4 d-none" id="numero_piece_wrapper">
                            <label class="form-label" id="numero_piece_label">
                                Numéro de la pièce <span class="required">*</span>
                            </label>
                            <input name="cin" type="text"
                                class="form-control form-control-sm @error('cin') is-invalid @enderror" id="num_cin"
                                value="{{ old('cin') }}" autocomplete="off" placeholder="Ex: 1099200500012"
                                minlength="9" maxlength="14" required>
                            @error('cin')
                                <span class="invalid-feedback" role="alert">
                                    <div>{{ $message }}</div>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- ================= STEP 3 – COORDONNÉES ================= --}}
                <div class="step d-none">
                    <h5 class="mb-3">Coordonnées & Situation</h5>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">
                                Téléphone personnel<span class="required">*</span>
                            </label>
                            <input name="telephone" type="text" maxlength="9"
                                class="form-control form-control-sm @error('telephone') is-invalid @enderror"
                                id="telephone" value="{{ old('telephone') }}" autocomplete="tel"
                                placeholder="Téléphone">
                            @error('telephone')
                                <span class="invalid-feedback" role="alert">
                                    <div>{{ $message }}</div>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">
                                Téléphone secondaire
                            </label>
                            <input name="telephone_secondaire" type="text" maxlength="9"
                                class="form-control form-control-sm @error('telephone_secondaire') is-invalid @enderror"
                                id="telephone_s" value="{{ old('telephone_secondaire') }}" autocomplete="tel"
                                placeholder="Téléphone">
                            @error('telephone_secondaire')
                                <span class="invalid-feedback" role="alert">
                                    <div>{{ $message }}</div>
                                </span>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Email<span class="required">*</span></label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                class="form-control form-control-sm @error('email') is-invalid @enderror" id="email"
                                placeholder="email">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <div>{{ $message }}</div>
                                </span>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">
                                Adresse <span class="required">*</span>
                            </label>
                            <input type="text" name="adresse" value="{{ old('adresse') }}"
                                class="form-control form-control-sm @error('adresse') is-invalid @enderror"
                                id="adresse" placeholder="adresse">
                            @error('adresse')
                                <span class="invalid-feedback" role="alert">
                                    <div>{{ $message }}</div>
                                </span>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">
                                Situation familiale <span class="required">*</span>
                            </label>

                            <select name="situation_familiale"
                                class="form-select form-select-sm @error('situation_familiale') is-invalid @enderror"
                                aria-label="Select" id="select-field-familiale-indiv"
                                data-placeholder="Choisir situation familiale">
                                <option value="{{ old('situation_familiale') }}">
                                    {{ old('situation_familiale') }}
                                </option>
                                <option value="Marié(e)">
                                    Marié(e)
                                </option>
                                <option value="Célibataire">
                                    Célibataire
                                </option>
                                <option value="Veuf(ve)">
                                    Veuf(ve)
                                </option>
                                <option value="Divorcé(e)">
                                    Divorcé(e)
                                </option>
                            </select>
                            @error('situation_familiale')
                                <span class="invalid-feedback" role="alert">
                                    <div>{{ $message }}</div>
                                </span>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">
                                Situation professionnelle <span class="required">*</span>
                            </label>

                            <select name="situation_professionnelle"
                                class="form-select form-select-sm @error('situation_professionnelle') is-invalid @enderror"
                                aria-label="Select" id="select-field-professionnelle-indiv"
                                data-placeholder="Choisir situation professionnelle">
                                <option value="{{ old('situation_professionnelle') }}">
                                    {{ old('situation_professionnelle') }}
                                </option>
                                <option value="Employé(e)">
                                    Employé(e)
                                </option>
                                <option value="Informel">
                                    Informel
                                </option>
                                <option value="Elève ou étudiant">
                                    Elève ou étudiant
                                </option>
                                <option value="Chercheur emploi">
                                    Chercheur emploi
                                </option>
                                <option value="Stage ou période essai">
                                    Stage ou période essai
                                </option>
                                <option value="Entrepreneur ou freelance">
                                    Entrepreneur ou freelance
                                </option>
                            </select>
                            @error('situation_professionnelle')
                                <span class="invalid-feedback" role="alert">
                                    <div>{{ $message }}</div>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- ================= STEP 4 – ÉTUDES ================= --}}
                <div class="step d-none">
                    <h5 class="mb-3">Études & Diplômes</h5>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">
                                Niveau d’étude <span class="required">*</span>
                            </label>
                            <select name="niveau_etude"
                                class="form-select form-select-sm @error('niveau_etude') is-invalid @enderror"
                                aria-label="Select" id="select-field-niveau_etude-indiv"
                                data-placeholder="Choisir niveau étude">
                                <option value="{{ old('niveau_etude') }}">
                                    {{ old('niveau_etude') }}
                                </option>
                                <option value="Aucun">
                                    Aucun
                                </option>
                                <option value="Arabe">
                                    Arabe
                                </option>
                                <option value="Elementaire">
                                    Elementaire
                                </option>
                                <option value="Secondaire">
                                    Secondaire
                                </option>
                                <option value="Moyen">
                                    Moyen
                                </option>
                                <option value="Supérieur">
                                    Supérieur
                                </option>
                            </select>
                            @error('niveau_etude')
                                <span class="invalid-feedback" role="alert">
                                    <div>{{ $message }}</div>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">
                                Diplôme académique <span class="required">*</span>
                            </label>
                            <select name="diplome_academique"
                                class="form-select form-select-sm @error('diplome_academique') is-invalid @enderror"
                                aria-label="Select" id="select-field-diplome_academique-indiv"
                                data-placeholder="Choisir diplôme académique">
                                <option value="{{ old('diplome_academique') }}">
                                    {{ old('diplome_academique') }}
                                </option>
                                <option value="Aucun">
                                    Aucun
                                </option>
                                <option value="Arabe">
                                    Arabe
                                </option>
                                <option value="CFEE">
                                    CFEE
                                </option>
                                <option value="BFEM">
                                    BFEM
                                </option>
                                <option value="BAC">
                                    BAC
                                </option>
                                <option value="Licence">
                                    Licence
                                </option>
                                <option value="Master 2">
                                    Master 2
                                </option>
                                <option value="Doctorat">
                                    Doctorat
                                </option>
                                <option value="Autre">
                                    Autre
                                </option>
                            </select>
                            @error('diplome_academique')
                                <span class="invalid-feedback" role="alert">
                                    <div>{{ $message }}</div>
                                </span>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">
                                Etablissement académique
                            </label>

                            <input type="text" name="etablissement_academique"
                                value="{{ old('etablissement_academique') }}"
                                class="form-control form-control-sm @error('etablissement_academique') is-invalid @enderror"
                                id="etablissement_academique" placeholder="Etablissement obtention">
                            @error('etablissement_academique')
                                <span class="invalid-feedback" role="alert">
                                    <div>{{ $message }}</div>
                                </span>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">
                                Si autre ? précisez
                            </label>
                            <input type="text" name="autre_diplome_academique"
                                value="{{ old('autre_diplome_academique') }}"
                                class="form-control form-control-sm @error('autre_diplome_academique') is-invalid @enderror"
                                id="autre_diplome_academique" placeholder="autre diplôme académique">
                            @error('autre_diplome_academique')
                                <span class="invalid-feedback" role="alert">
                                    <div>{{ $message }}</div>
                                </span>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="option_diplome_academique" class="form-label">Option du
                                diplôme</label>
                            <input type="text" name="option_diplome_academique"
                                value="{{ old('option_diplome_academique') }}"
                                class="form-control form-control-sm @error('option_diplome_academique') is-invalid @enderror"
                                id="option_diplome_academique" placeholder="Ex: Mathématiques">
                            @error('option_diplome_academique')
                                <span class="invalid-feedback" role="alert">
                                    <div>{{ $message }}</div>
                                </span>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="diplome_pro" class="form-label">Diplôme professionnel<span
                                    class="required">*</span></label>
                            <select name="diplome_professionnel"
                                class="form-select form-select-sm @error('diplome_professionnel') is-invalid @enderror"
                                aria-label="Select" id="select-field-diplome_professionnel-indiv"
                                data-placeholder="Choisir diplôme professionnel">
                                <option value="{{ old('diplome_professionnel') }}">
                                    {{ old('diplome_professionnel') }}
                                </option>
                                <option value="Aucun">
                                    Aucun
                                </option>
                                <option value="CAP">
                                    CAP
                                </option>
                                <option value="BEP">
                                    BEP
                                </option>
                                <option value="BT">
                                    BT
                                </option>
                                <option value="BTS">
                                    BTS
                                </option>
                                <option value="CPS">
                                    CPS
                                </option>
                                <option value="L3 Pro">
                                    L3 Pro
                                </option>
                                <option value="DTS">
                                    DTS
                                </option>
                                <option value="Autre">
                                    Autre
                                </option>
                            </select>
                            @error('diplome_professionnel')
                                <span class="invalid-feedback" role="alert">
                                    <div>{{ $message }}</div>
                                </span>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="autre_diplome_professionnel" class="form-label">Si autre ?
                                précisez</label>
                            <input type="text" name="autre_diplome_professionnel"
                                value="{{ old('autre_diplome_professionnel') }}"
                                class="form-control form-control-sm @error('autre_diplome_professionnel') is-invalid @enderror"
                                id="autre_diplome_professionnel"
                                placeholder="autre diplôme professionnel ou attestations">
                            @error('autre_diplome_professionnel')
                                <span class="invalid-feedback" role="alert">
                                    <div>{{ $message }}</div>
                                </span>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="etablissement_professionnel" class="form-label">Etablissement
                                professionnel</label>
                            <input type="text" name="etablissement_professionnel"
                                value="{{ old('etablissement_professionnel') }}"
                                class="form-control form-control-sm @error('etablissement_professionnel') is-invalid @enderror"
                                id="etablissement_professionnel" placeholder="Etablissement obtention">
                            @error('etablissement_professionnel')
                                <span class="invalid-feedback" role="alert">
                                    <div>{{ $message }}</div>
                                </span>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Spécialité</label>
                            <input type="text" name="specialite_diplome_professionnel"
                                value="{{ old('specialite_diplome_professionnel') }}"
                                class="form-control form-control-sm @error('specialite_diplome_professionnel') is-invalid @enderror"
                                id="specialite_diplome_professionnel" placeholder="Ex: électricité">
                            @error('specialite_diplome_professionnel')
                                <span class="invalid-feedback" role="alert">
                                    <div>{{ $message }}</div>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- ================= STEP 5 – PROJET ================= --}}
                <div class="step d-none">
                    <h5 class="mb-3">Projet</h5>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">
                                Projet professionnel après la formation <span class="required">*</span>
                            </label>
                            <select name="projet_poste_formation"
                                class="form-select form-select-sm @error('projet_poste_formation') is-invalid @enderror"
                                aria-label="Select" id="select-field-projet_poste_formation-indiv"
                                data-placeholder="Choisir projet">
                                <option value="{{ old('projet_poste_formation') }}">
                                    {{ old('projet_poste_formation') }}
                                </option>
                                <option value="Poursuivre mes études">
                                    Poursuivre mes études
                                </option>
                                <option value="Chercher un emploi">
                                    Chercher un emploi
                                </option>
                                <option value="Lancer mon entreprise">
                                    Lancer mon entreprise
                                </option>
                                <option value="Retourner dans mon entreprise">
                                    Retourner dans mon entreprise
                                </option>
                                <option value="Aucun de ces projets">
                                    Aucun de ces projets
                                </option>
                            </select>
                            @error('projet_poste_formation')
                                <span class="invalid-feedback" role="alert">
                                    <div>{{ $message }}</div>
                                </span>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="qualification" class="form-label">Qualification et autres
                                diplômes</label>
                            <textarea name="qualification" id="qualification" rows="1"
                                class="form-control form-control-sm @error('qualification') is-invalid @enderror"
                                placeholder="Qualification et autres diplômes">{{ old('qualification') }}</textarea>
                            @error('qualification')
                                <span class="invalid-feedback" role="alert">
                                    <div>{{ $message }}</div>
                                </span>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <label for="experience" class="form-label">Expériences et stages</label>
                            <textarea name="experience" id="experience" rows="1"
                                class="form-control form-control-sm @error('experience') is-invalid @enderror"
                                placeholder="Expériences ou stages">{{ old('experience') }}</textarea>
                            @error('experience')
                                <span class="invalid-feedback" role="alert">
                                    <div>{{ $message }}</div>
                                </span>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <label for="projetprofessionnel" class="form-label">Informations
                                complémentaires
                                sur
                                le projet
                                professionnel<span class="required">*</span></label>
                            <textarea name="projetprofessionnel" id="projetprofessionnel" rows="10"
                                class="form-control form-control-sm @error('projetprofessionnel') is-invalid @enderror"
                                placeholder="Si vous disposez déjà d'un projet professionnel, merci d'écrire son résumé en quelques lignes">{{ old('projetprofessionnel') }}</textarea>
                            @error('projetprofessionnel')
                                <span class="invalid-feedback" role="alert">
                                    <div>{{ $message }}</div>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- ================= STEP 6 – RÉCAPITULATIF ================= --}}
                <div class="step d-none">
                    <h5 class="mb-3">📋 Récapitulatif de la demande</h5>

                    <div id="recap" class="border rounded p-3 bg-light"></div>
                </div>

                {{-- Navigation --}}
                <div class="d-flex justify-content-between mt-4">
                    <button type="button" class="btn btn-secondary btn-sm" id="prevBtn">Précédent</button>
                    <button type="button" class="btn btn-primary btn-sm" id="nextBtn">Suivant</button>
                    <button type="submit" class="btn btn-success btn-sm submitBtn d-none" id="submitBtn">
                        <i class="bi bi-check-circle"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </section>
@endsection
@push('scripts')
    <style>
        .required {
            color: #dc3545;
            font-weight: bold;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            /* ===============================
             * WIZARD STEPS
             * =============================== */
            let currentStep = 0;

            const steps = document.querySelectorAll('.step');
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            const submitBtn = document.getElementById('submitBtn');
            const progressBar = document.getElementById('progressBar');

            function showStep(step) {
                steps.forEach((el, i) => {
                    el.classList.toggle('d-none', i !== step);
                });

                // Boutons
                if (prevBtn) prevBtn.style.display = step === 0 ? 'none' : 'inline-block';
                if (nextBtn) nextBtn.classList.toggle('d-none', step === steps.length - 1);
                if (submitBtn) submitBtn.classList.toggle('d-none', step !== steps.length - 1);

                // Progress bar
                if (progressBar) progressBar.style.width = ((step + 1) / steps.length) * 100 + '%';

                // Générer récap uniquement à la dernière étape
                if (step === steps.length - 1) {
                    generateRecap();
                }
            }

            function generateRecap() {
                const form = document.getElementById('wizardForm');
                const data = new FormData(form);

                let html = '<ul class="list-group">';
                data.forEach((value, key) => {
                    if (value && key !== '_token') {
                        html += `<li class="list-group-item">
                            <strong>${key}</strong> : ${value}
                         </li>`;
                    }
                });
                html += '</ul>';

                document.getElementById('recap').innerHTML = html;
            }

            nextBtn?.addEventListener('click', () => {
                if (currentStep < steps.length - 1) {
                    showStep(++currentStep);
                }
            });

            prevBtn?.addEventListener('click', () => {
                if (currentStep > 0) {
                    showStep(--currentStep);
                }
            });

            showStep(currentStep);


            /* ===============================
             * PIECE D'IDENTITE DYNAMIQUE
             * =============================== */
            const typePiece = document.getElementById('type_piece');
            const numeroWrapper = document.getElementById('numero_piece_wrapper');
            const numeroLabel = document.getElementById('numero_piece_label');
            const numeroInput = document.getElementById('num_cin');

            if (!typePiece || !numeroInput) return;

            typePiece.addEventListener('change', function() {

                if (!this.value) {
                    numeroWrapper.classList.add('d-none');
                    resetNumeroInput();
                    return;
                }

                numeroWrapper.classList.remove('d-none');
                resetNumeroInput();

                switch (this.value) {

                    case 'extrait':
                        numeroLabel.innerHTML =
                            'Numéro de l’extrait de naissance <span class="required">*</span>';
                        numeroInput.placeholder = 'Ex : 00450/2010';
                        numeroInput.setAttribute('minlength', 10);
                        numeroInput.setAttribute('maxlength', 10);
                        numeroInput.setAttribute('pattern', '[A-Za-z0-9/]{10}');
                        break;

                    case 'passeport':
                        numeroLabel.innerHTML = 'Numéro du passeport <span class="required">*</span>';
                        numeroInput.placeholder = 'Ex : A12345678';
                        numeroInput.setAttribute('minlength', 9);
                        numeroInput.setAttribute('maxlength', 9);
                        break;

                    case 'cni':
                        numeroLabel.innerHTML =
                            'Numéro de la carte nationale <span class="required">*</span>';
                        numeroInput.placeholder = 'Ex : 1099200500012';
                        numeroInput.setAttribute('minlength', 13);
                        numeroInput.setAttribute('maxlength', 14);
                        numeroInput.setAttribute('pattern', '[A-Za-z0-9]{13,14}');
                        break;
                }
            });

            function resetNumeroInput() {
                numeroInput.value = '';
                numeroInput.removeAttribute('minlength');
                numeroInput.removeAttribute('maxlength');
                numeroInput.removeAttribute('pattern');
            }

            /* ===============================
             * SECURITE LONGUEUR EN SAISIE
             * =============================== */
            numeroInput.addEventListener('input', function() {
                const max = this.getAttribute('maxlength');
                if (max && this.value.length > max) {
                    this.value = this.value.slice(0, max);
                }
            });

        });

        $(document).on('click', '.submitBtn', function(event) {
            event.preventDefault();

            const $btn = $(this);
            const form = $btn.closest("form");

            // 🔒 Empêche double clic
            if ($btn.prop('disabled')) return;

            Swal.fire({
                title: "Confirmer l'enregistrement ?",
                text: "Vous êtes sur le point d’enregistrer cette demande.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Oui, enregistrer",
                cancelButtonText: "Annuler",
                confirmButtonColor: "#004080",
                cancelButtonColor: "#6c757d",
                reverseButtons: true
            }).then((result) => {

                if (result.isConfirmed) {

                    // 🔒 Désactive le bouton
                    $btn.prop('disabled', true);

                    Swal.fire({
                        title: "Enregistrement...",
                        text: "Veuillez patienter",
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();

                            // Petit délai pour laisser l'UI respirer (optionnel mais propre)
                            setTimeout(() => {
                                form.submit();
                            }, 300);
                        }
                    });
                }
            });
        });
    </script>
@endpush
