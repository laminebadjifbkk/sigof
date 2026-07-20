@extends('layouts.app')

@section('title', 'Inscription')

@section('content')
    <div class="register-wrap">
        <div class="container">
            <div class="register-head">
                <p class="eyebrow" style="justify-content:center;">Inscription traducteur</p>
                <h2>Créez votre profil</h2>
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
                <div class="step-line"></div>
                <div class="step-item" data-step="5">
                    <div class="step-circle">5</div><span class="step-label">Récapitulatif</span>
                </div>
            </div>

            <div class="register-card">
                <form id="registerForm" method="POST" action="{{ route('inscription.store') }}"
                    enctype="multipart/form-data">
                    @csrf

                    @if (session('success'))
                        <div class="alert alert-success">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" style="flex-shrink:0;">
                                <circle cx="12" cy="12" r="11" class="alert-icon-bg" />
                                <path d="M7 12.5l3 3 7-7" stroke="#fff" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                            <span>{{ session('success') }}</span>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" style="flex-shrink:0;">
                                <circle cx="12" cy="12" r="11" class="alert-icon-bg" />
                                <path d="M8 8l8 8M16 8l-8 8" stroke="#fff" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                            <span>{{ session('error') }}</span>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger alert-list">
                            <strong>Veuillez corriger les erreurs suivantes avant de continuer :</strong>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="reg-step active" data-step="1">
                        <h3>Informations personnelles</h3>

                        <div class="field">
                            <label>Civilité</label>
                            <select name="civilite">
                                <option value="">-- Sélectionner --</option>
                                <option value="M." {{ old('civilite') === 'M.' ? 'selected' : '' }}>M.</option>
                                <option value="Mme" {{ old('civilite') === 'Mme' ? 'selected' : '' }}>Mme</option>
                            </select>
                            @error('civilite')
                                <span class="field-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="field-row">
                            <div class="field"><label>Prénom</label><input type="text" name="prenom"
                                    value="{{ old('prenom') }}" placeholder="Awa">
                                @error('prenom')
                                    <span class="field-error">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="field"><label>Nom</label><input type="text" name="nom"
                                    value="{{ old('nom') }}" placeholder="Diop">
                                @error('nom')
                                    <span class="field-error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="field"><label>Adresse e-mail</label><input type="email" name="email"
                                value="{{ old('email') }}" placeholder="awa.diop@exemple.sn">
                            @error('email')
                                <span class="field-error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="field-row">
                            <div class="field"><label>Téléphone</label><input type="tel" name="telephone"
                                    value="{{ old('telephone') }}" placeholder="77 000 00 00">
                                @error('telephone')
                                    <span class="field-error">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="field"><label>Date de naissance</label><input type="date" name="date_naissance"
                                    value="{{ old('date_naissance') }}">
                                @error('date_naissance')
                                    <span class="field-error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="field">
                            <label>Lieu de naissance</label>
                            <input type="text" name="lieu_naissance" value="{{ old('lieu_naissance') }}"
                                placeholder="Dakar">
                            @error('lieu_naissance')
                                <span class="field-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="field">
                            <label>Adresse</label>
                            <input type="text" name="adresse" value="{{ old('adresse') }}"
                                placeholder="Cité Keur Gorgui, Villa 12">
                            @error('adresse')
                                <span class="field-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="field">
                            <label>Région</label>
                            <select name="region_id">
                                <option value="">-- Sélectionner une région --</option>
                                @foreach ($regions as $region)
                                    <option value="{{ $region->id }}"
                                        {{ (string) old('region_id') === (string) $region->id ? 'selected' : '' }}>
                                        {{ $region->nom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('region_id')
                                <span class="field-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <p class="lang-note" style="margin-top:2px;">Programme ouvert aux candidats de 21 à 35 ans à la
                            date
                            de clôture des inscriptions.</p>
                    </div>

                    <div class="reg-step" data-step="2">
                        <h3>Langue de spécialisation (LV1)</h3>
                        <div class="field"><label>Langue choisie - niveau C1 requis</label>
                            <select name="langue_specialisation" id="specLang">
                                <option value="" disabled @selected(!old('langue_specialisation'))>-- Choisissez une langue --
                                </option>
                                @foreach ($languesSpecialisations as $langue)
                                    <option value="{{ $langue->code }}" @selected(old('langue_specialisation', '') == $langue->code)>
                                        {{ $langue->nom }}
                                        <!-- {{ $langue->nom }} - {{ $langue->postes_disponibles }} candidat{{ $langue->postes_disponibles > 1 ? 's' : '' }} à former - {{ $langue->niveau_langue_requis }} -->
                                    </option>
                                @endforeach
                            </select>
                            @error('langue_specialisation')
                                <span class="field-error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="field-row">
                            <div class="field"><label>Certification obtenue (si applicable)</label><input type="text"
                                    name="certification" value="{{ old('certification') }}"
                                    placeholder="Ex : DELE C1, HSK 5…"></div>
                            <div class="field"><label>Diplôme le plus élevé</label>
                                <select name="diplome">
                                    <option value="">-- Choisissez --</option>
                                    <option value="licence">Licence</option>
                                    <option value="master">Master</option>
                                    <option value="doctorat">Doctorat</option>
                                    <option value="certification">Certification linguistique reconnue</option>
                                </select>
                                @error('certification')
                                    <span class="field-error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <h3 style="margin-top:24px;">Autres langues</h3>
                        <div class="field-row">
                            <div class="field"><label>Langue maternelle</label>
                                <select name="langue_maternelle">
                                    <option value="">-- Choisissez --</option>
                                    <option value="wolof">Wolof</option>
                                    <option value="francais">Français</option>
                                    <option value="pulaar">Pulaar</option>
                                    <option value="serere">Sérère</option>
                                    <option value="diola">Diola</option>
                                    <option value="autre">Autre</option>
                                </select>
                                @error('langue_maternelle')
                                    <span class="field-error">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="field"><label>Niveau de français - C1 minimum</label>
                                <select name="niveau_francais">
                                    <option value="">-- Choisissez --</option>
                                    <option value="c1">C1</option>
                                    <option value="c2">C2 / Bilingue</option>
                                </select>
                                @error('niveau_francais')
                                    <span class="field-error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="field"><label>Langue vivante 2 (LV2) - niveau B2 minimum</label>
                            <select name="langue_vivante_2">
                                <option value="">-- Choisissez --</option>
                                <option value="anglais">Anglais</option>
                                <option value="espagnol">Espagnol</option>
                                <option value="arabe">Arabe</option>
                                <option value="portugais">Portugais</option>
                                <option value="aucune">Aucune</option>
                            </select>
                            @error('langue_vivante_2')
                                <span class="field-error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="reg-step" data-step="3">
                        <h3>Disponibilité et affectation</h3>
                        <div class="field-row">
                            <div class="field"><label>Disponible à partir du</label><input type="date"
                                    name="disponible_debut" value="{{ old('disponible_debut') }}"></div>
                            <div class="field"><label>Disponible jusqu'au</label><input type="date"
                                    name="disponible_fin" value="{{ old('disponible_fin') }}"></div>
                            @error('disponible_debut')
                                <span class="field-error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="field"><label>Zone / site préféré</label>
                            <select name="zone">
                                <option value="">-- Choisissez --</option>
                                <option value="diamniadio">Diamniadio Olympic Stadium</option>
                                <option value="dakar_centre">Dakar centre</option>
                                <option value="saly">Saly - Petite Côte</option>
                                <option value="indifferent">Indifférent</option>
                            </select>
                            @error('zone')
                                <span class="field-error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="field"><label>Délégation ou discipline souhaitée (optionnel)</label><input
                                type="text" name="delegation_souhaitee" value="{{ old('delegation_souhaitee') }}"
                                placeholder="Ex : Beach handball, Athlétisme…"></div>
                        @error('delegation_souhaitee')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="reg-step" data-step="4">
                        <h3>Documents justificatifs</h3>
                        <label class="upload-box" for="file-identite" style="display:block; cursor:pointer;">
                            <strong>Pièce d'identité</strong><br>
                            <span class="file-name">Glissez un fichier ou cliquez pour parcourir (PDF, JPG, JPEG, PNG - 5
                                Mo
                                max)</span>

                            <input type="file" id="file-identite" name="piece_identite" accept=".pdf,.jpg,.jpeg,.png"
                                hidden>
                            @error('piece_identite')
                                <span class="field-error">{{ $message }}</span>
                            @enderror
                        </label>
                        <label class="upload-box" for="file-diplome" style="display:block; cursor:pointer;">
                            <strong>Diplôme (Licence / Master ou équivalent)</strong><br>
                            <span class="file-name">Glissez un fichier ou cliquez pour parcourir (PDF, JPG, JPEG, PNG - 5
                                Mo
                                max)</span>
                            <input type="file" id="file-diplome" name="diplome_fichier" accept=".pdf,.jpg,.jpeg,.png"
                                hidden>
                            @error('file-diplome')
                                <span class="field-error">{{ $message }}</span>
                            @enderror
                        </label>
                        <label class="upload-box" for="file-certif" style="display:block; cursor:pointer;">
                            <strong>Certification linguistique</strong><br>
                            <span class="file-name">Glissez un fichier ou cliquez pour parcourir (TOEIC, DELE, HSK, JLPT…
                                selon la langue choisie (PDF
                                - 5 Mo max))</span>
                            <input type="file" id="file-certif" name="certification_fichier"
                                accept=".pdf,.jpg,.jpeg,.png" hidden>
                            @error('certification_fichier')
                                <span class="field-error">{{ $message }}</span>
                            @enderror
                        </label>
                        <label class="upload-box" for="file-cv" style="display:block; cursor:pointer;">
                            <strong>CV à jour</strong><br>
                            <span class="file-name">Glissez un fichier ou cliquez pour parcourir (PDF, JPG, JPEG, PNG - 5
                                Mo
                                max)</span>
                            <input type="file" id="file-cv" name="cv" accept=".pdf,.jpg,.jpeg,.png" hidden>
                            @error('cv')
                                <span class="field-error">{{ $message }}</span>
                            @enderror
                        </label>

                        <label class="checkline" style="font-size:13.5px;">
                            <input type="checkbox" name="attestation" value="1">
                            J'atteste l'exactitude des informations fournies et j'accepte la charte du programme ONFP ×
                            COJOJ.
                        </label>
                        @error('attestation')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="reg-actions">
                        <button type="button" class="btn btn-ghost btn-sm" id="regBack">Précédent</button>
                        <button type="button" class="btn btn-primary btn-sm" id="regNext">Étape suivante</button>
                        <button type="submit" class="btn btn-primary btn-sm" id="regSubmit"
                            style="display:none;">Envoyer ma candidature</button>
                    </div>

                    <div class="reg-step" data-step="5">
                        <h3>Récapitulatif de votre candidature</h3>
                        <p style="color:var(--gray-700); margin-bottom:16px;">
                            Vérifiez vos informations avant l'envoi définitif. Vous pouvez revenir en arrière pour corriger.
                        </p>
                        <div class="recap-wrapper">

                            <div class="recap-card">
                                <div class="recap-card-header">
                                    {{-- <span class="recap-icon">👤</span> --}}
                                    <h4>Profil</h4>
                                </div>
                                <div class="recap-grid">
                                    <div class="recap-item">
                                        <span class="recap-label">Civilité</span>
                                        <span class="recap-value" id="recap-civilite"></span>
                                    </div>
                                    <div class="recap-item">
                                        <span class="recap-label">Nom complet</span>
                                        <span class="recap-value" id="recap-nom"></span>
                                    </div>
                                    <div class="recap-item">
                                        <span class="recap-label">E-mail</span>
                                        <span class="recap-value" id="recap-email"></span>
                                    </div>
                                    <div class="recap-item">
                                        <span class="recap-label">Téléphone</span>
                                        <span class="recap-value" id="recap-telephone"></span>
                                    </div>
                                    <div class="recap-item">
                                        <span class="recap-label">Date de naissance</span>
                                        <span class="recap-value" id="recap-date_naissance"></span>
                                    </div>
                                    <div class="recap-item">
                                        <span class="recap-label">Lieu de naissance</span>
                                        <span class="recap-value" id="recap-lieu_naissance"></span>
                                    </div>
                                    <div class="recap-item recap-item-full">
                                        <span class="recap-label">Adresse</span>
                                        <span class="recap-value" id="recap-adresse"></span>
                                    </div>
                                    <div class="recap-item">
                                        <span class="recap-label">Région</span>
                                        <span class="recap-value" id="recap-region"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="recap-card">
                                <div class="recap-card-header">
                                    {{-- <span class="recap-icon">🌍</span> --}}
                                    <h4>Langues</h4>
                                </div>
                                <div class="recap-grid">
                                    <div class="recap-item">
                                        <span class="recap-label">Langue de spécialisation</span>
                                        <span class="recap-value" id="recap-langue_specialisation"></span>
                                    </div>
                                    <div class="recap-item">
                                        <span class="recap-label">Certification obtenue</span>
                                        <span class="recap-value" id="recap-certification"></span>
                                    </div>
                                    <div class="recap-item">
                                        <span class="recap-label">Diplôme</span>
                                        <span class="recap-value" id="recap-diplome"></span>
                                    </div>
                                    <div class="recap-item">
                                        <span class="recap-label">Langue maternelle</span>
                                        <span class="recap-value" id="recap-langue_maternelle"></span>
                                    </div>
                                    <div class="recap-item">
                                        <span class="recap-label">Niveau de français</span>
                                        <span class="recap-value" id="recap-niveau_francais"></span>
                                    </div>
                                    <div class="recap-item">
                                        <span class="recap-label">Langue vivante 2</span>
                                        <span class="recap-value" id="recap-langue_vivante_2"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="recap-card">
                                <div class="recap-card-header">
                                    {{-- <span class="recap-icon">📅</span> --}}
                                    <h4>Disponibilité</h4>
                                </div>
                                <div class="recap-grid">
                                    <div class="recap-item">
                                        <span class="recap-label">Du</span>
                                        <span class="recap-value" id="recap-disponible_debut"></span>
                                    </div>
                                    <div class="recap-item">
                                        <span class="recap-label">Au</span>
                                        <span class="recap-value" id="recap-disponible_fin"></span>
                                    </div>
                                    <div class="recap-item">
                                        <span class="recap-label">Zone</span>
                                        <span class="recap-value" id="recap-zone"></span>
                                    </div>
                                    <div class="recap-item">
                                        <span class="recap-label">Délégation souhaitée</span>
                                        <span class="recap-value" id="recap-delegation_souhaitee"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="recap-card">
                                <div class="recap-card-header">
                                    {{-- <span class="recap-icon">📎</span> --}}
                                    <h4>Documents</h4>
                                </div>
                                <div class="recap-doc-list">
                                    <div class="recap-doc">
                                        <span class="recap-doc-name">Pièce d'identité</span>
                                        <span class="recap-doc-file" id="recap-piece_identite"></span>
                                    </div>
                                    <div class="recap-doc">
                                        <span class="recap-doc-name">Diplôme</span>
                                        <span class="recap-doc-file" id="recap-diplome_fichier"></span>
                                    </div>
                                    <div class="recap-doc">
                                        <span class="recap-doc-name">Certification</span>
                                        <span class="recap-doc-file" id="recap-certification_fichier"></span>
                                    </div>
                                    <div class="recap-doc">
                                        <span class="recap-doc-name">CV</span>
                                        <span class="recap-doc-file" id="recap-cv"></span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
