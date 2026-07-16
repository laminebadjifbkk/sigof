@extends('layouts.app')

@section('title', 'Inscription')

@section('content')
    <div class="register-wrap">
        <div class="container">
            <div class="register-head">
                <p class="eyebrow" style="justify-content:center;">Inscription traducteur</p>
                <h2>Créez votre profil YLP</h2>
                <p style="color:var(--gray-700); margin-top:8px;">Quatre étapes pour rejoindre le vivier des traducteurs
                    Dakar 2026.</p>
            </div>

            <div class="stepper" id="stepper">
                <div class="step-item current" data-step="1">
                    <div class="step-circle">1</div><span class="step-label">Profil</span>
                </div>
                <div class="step-line"></div>
                <div class="step-item" data-step="2">
                    <div class="step-circle">2</div><span class="step-label">Langues</span>
                </div>
                <div class="step-line"></div>
                <div class="step-item" data-step="3">
                    <div class="step-circle">3</div><span class="step-label">Disponibilité</span>
                </div>
                <div class="step-line"></div>
                <div class="step-item" data-step="4">
                    <div class="step-circle">4</div><span class="step-label">Documents</span>
                </div>
            </div>

            <div class="register-card">
                {{-- Le stepper est géré en JS (sigof.js) : "Étape suivante" avance visuellement,
             et c'est le clic final ("Envoyer ma candidature") qui doit soumettre ce
             formulaire vers votre contrôleur (adapter regNext dans sigof.js si besoin). --}}
                <form id="registerForm" method="POST" action="{{ route('register.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="reg-step active" data-step="1">
                        <h3>Informations personnelles</h3>
                        <div class="field-row">
                            <div class="field"><label>Prénom</label><input type="text" name="prenom"
                                    value="{{ old('prenom') }}" placeholder="Awa"></div>
                            <div class="field"><label>Nom</label><input type="text" name="nom"
                                    value="{{ old('nom') }}" placeholder="Diop"></div>
                        </div>
                        <div class="field"><label>Adresse e-mail</label><input type="email" name="email"
                                value="{{ old('email') }}" placeholder="awa.diop@exemple.sn"></div>
                        <div class="field-row">
                            <div class="field"><label>Téléphone</label><input type="tel" name="telephone"
                                    value="{{ old('telephone') }}" placeholder="+221 77 000 00 00"></div>
                            <div class="field"><label>Date de naissance</label><input type="date" name="date_naissance"
                                    value="{{ old('date_naissance') }}"></div>
                        </div>
                        <p class="lang-note" style="margin-top:2px;">Programme ouvert aux candidats de 21 à 35 ans à la date
                            de clôture des inscriptions.</p>
                    </div>

                    <div class="reg-step" data-step="2">
                        <h3>Langue de spécialisation (LV1)</h3>
                        <div class="field"><label>Langue choisie - niveau C1 requis</label>
                            <select name="langue_specialisation" id="specLang">
                                <option value="anglais_bilingue">Anglais (profil bilingue) - 3 postes - C1</option>
                                <option value="arabe">Arabe - 6 postes - C1</option>
                                <option value="espagnol" selected>Espagnol - 7 postes - C1</option>
                                <option value="portugais">Portugais - 4 postes - C1</option>
                                <option value="chinois">Chinois (Mandarin) - 4 postes - C1</option>
                                <option value="japonais">Japonais - 4 postes - C1</option>
                                <option value="coreen">Coréen - 2 postes - C1</option>
                                <option value="allemand">Allemand - 4 postes - C1</option>
                                <option value="russe">Russe - 2 postes - C1</option>
                                <option value="italien">Italien - 4 postes - C1</option>
                            </select>
                        </div>
                        <div class="field-row">
                            <div class="field"><label>Certification obtenue (si applicable)</label><input type="text"
                                    name="certification" value="{{ old('certification') }}"
                                    placeholder="Ex : DELE C1, HSK 5…"></div>
                            <div class="field"><label>Diplôme le plus élevé</label>
                                <select name="diplome">
                                    <option value="licence">Licence</option>
                                    <option value="master">Master</option>
                                    <option value="doctorat">Doctorat</option>
                                    <option value="certification">Certification linguistique reconnue</option>
                                </select>
                            </div>
                        </div>
                        <h3 style="margin-top:24px;">Autres langues</h3>
                        <div class="field-row">
                            <div class="field"><label>Langue maternelle</label>
                                <select name="langue_maternelle">
                                    <option value="wolof">Wolof</option>
                                    <option value="francais">Français</option>
                                    <option value="pulaar">Pulaar</option>
                                    <option value="serere">Sérère</option>
                                    <option value="autre">Autre</option>
                                </select>
                            </div>
                            <div class="field"><label>Niveau de français - C1 minimum</label>
                                <select name="niveau_francais">
                                    <option value="c1">C1</option>
                                    <option value="c2" selected>C2 / Bilingue</option>
                                </select>
                            </div>
                        </div>
                        <div class="field"><label>Langue vivante 2 (LV2) - niveau B2 minimum</label>
                            <select name="langue_vivante_2">
                                <option value="anglais">Anglais</option>
                                <option value="espagnol">Espagnol</option>
                                <option value="arabe">Arabe</option>
                                <option value="portugais">Portugais</option>
                                <option value="aucune">Aucune</option>
                            </select>
                        </div>
                    </div>

                    <div class="reg-step" data-step="3">
                        <h3>Disponibilité et affectation</h3>
                        <div class="field-row">
                            <div class="field"><label>Disponible à partir du</label><input type="date"
                                    name="disponible_debut" value="{{ old('disponible_debut') }}"></div>
                            <div class="field"><label>Disponible jusqu'au</label><input type="date"
                                    name="disponible_fin" value="{{ old('disponible_fin') }}"></div>
                        </div>
                        <div class="field"><label>Zone / site préféré</label>
                            <select name="zone">
                                <option value="diamniadio">Diamniadio Olympic Stadium</option>
                                <option value="dakar_centre">Dakar centre</option>
                                <option value="saly">Saly - Petite Côte</option>
                                <option value="indifferent">Indifférent</option>
                            </select>
                        </div>
                        <div class="field"><label>Délégation ou discipline souhaitée (optionnel)</label><input
                                type="text" name="delegation_souhaitee" value="{{ old('delegation_souhaitee') }}"
                                placeholder="Ex : Beach handball, Athlétisme…"></div>
                    </div>

                    <div class="reg-step" data-step="4">
                        <h3>Documents justificatifs</h3>

                        {{-- <label class="upload-box" for="file-identite" style="display:block; cursor:pointer;">
                            <strong>Pièce d'identité</strong>Glissez un fichier ou cliquez pour parcourir (PDF, JPG - 5 Mo
                            max)
                            <input type="file" id="file-identite" name="piece_identite" accept=".pdf,.jpg,.jpeg,.png"
                                hidden>
                        </label> --}}
                        <label class="upload-box" for="file-identite" style="display:block; cursor:pointer;">
                            <strong>Pièce d'identité</strong><br>
                            <span class="file-name">Glissez un fichier ou cliquez pour parcourir (PDF, JPG, JPEG, PNG - 5 Mo
                                max)</span>

                            <input type="file" id="file-identite" name="piece_identite" accept=".pdf,.jpg,.jpeg,.png"
                                hidden>
                        </label>
                        <label class="upload-box" for="file-diplome" style="display:block; cursor:pointer;">
                            <strong>Diplôme (Licence / Master ou équivalent)</strong><br>
                            <span class="file-name">Glissez un fichier ou cliquez pour parcourir (PDF, JPG, JPEG, PNG - 5 Mo
                                max)</span>
                            <input type="file" id="file-diplome" name="diplome_fichier" accept=".pdf,.jpg,.jpeg,.png"
                                hidden>
                        </label>
                        <label class="upload-box" for="file-certif" style="display:block; cursor:pointer;">
                            <strong>Certification linguistique</strong><br>
                            <span class="file-name">Glissez un fichier ou cliquez pour parcourir (TOEIC, DELE, HSK, JLPT… selon la langue choisie (PDF
                            - 5 Mo max))</span>
                            <input type="file" id="file-certif" name="certification_fichier" accept=".pdf" hidden>
                        </label>
                        <label class="upload-box" for="file-cv" style="display:block; cursor:pointer;">
                            <strong>CV à jour</strong><br>
                            <span class="file-name">Glissez un fichier ou cliquez pour parcourir (PDF, JPG, JPEG, PNG - 5 Mo
                                max)</span>
                            <input type="file" id="file-cv" name="cv" accept=".pdf" hidden>
                        </label>

                        <label class="checkline" style="font-size:13.5px;">
                            <input type="checkbox" name="attestation" value="1">
                            J'atteste l'exactitude des informations fournies et j'accepte la charte du programme ONFP ×
                            COJO.
                        </label>
                    </div>

                    <div class="reg-actions">
                        <button type="button" class="btn btn-ghost btn-sm" id="regBack">Précédent</button>
                        <button type="button" class="btn btn-primary btn-sm" id="regNext">Étape suivante</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
