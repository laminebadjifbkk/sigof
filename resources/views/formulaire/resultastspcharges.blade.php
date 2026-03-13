<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ONFP - Formulaire de prise en charge</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    @php
        $currentStep = session('current_step', 0);
    @endphp

    <style>
        body {
            background: linear-gradient(135deg, #e9f4ff, #f8f9fa);
            font-family: 'Poppins', sans-serif;
        }

        .form-card {
            max-width: 750px;
            margin: 60px auto;
            background: #fff;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .step {
            display: none;
        }

        .step.active {
            display: block;
        }

        .step-indicators {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }

        .step-circle {
            width: 40px;
            height: 40px;
            background: #dee2e6;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: 600;
            color: #6c757d;
        }

        .step-circle.active {
            background: #F28500;
            color: #fff;
        }

        .btn-orange {
            background-color: #F28500;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 5px 15px;
            transition: 0.3s;
        }

        .btn-orange:hover {
            background-color: #d47200;
        }

        .recap-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e9ecef;
        }

        .recap-item span {
            font-weight: 600;
            color: #F28500;
        }

        h2 {
            color: #F28500;
            font-weight: 700;
        }
    </style>
</head>

<body>
    <div class="form-card">
        <h2 class="text-center mb-3">ONFP</h2>
        <h5 class="text-center text-secondary mb-5">
            Formulaire de demande de prise en charge
        </h5>
        {{-- <h2 class="text-center mb-4 text-uppercase text-dark"></h2> --}}

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="step-indicators mb-4">
            <div class="step-circle">1</div>
            <div class="step-circle">2</div>
            <div class="step-circle">3</div>
            <div class="step-circle">4</div>
        </div>

        <form id="multiStepForm" action="{{ route('formulaire.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="recaptcha_token" id="recaptcha_token">
            <!-- Étape 1 : Informations personnelles -->
            <div class="step" id="step1">
                <h4 class="text-center mb-3 text-secondary">Informations personnelles</h4>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>N° CIN <span class="text-danger">*</span></label>
                        {{-- <input type="text" name="cin" class="form-control form-control-sm"
                            value="{{ old('cin') }}" required minlength="9" maxlength="14"> --}}

                        <input name="cin" type="text"
                            class="form-control form-control-sm @error('cin') is-invalid @enderror" id="cin"
                            value="{{ old('cin') }}" autocomplete="off" placeholder="Ex: 1099200500012"
                            minlength="9" maxlength="14" required>
                        @error('cin')
                            <span class="invalid-feedback" role="alert">
                                <div>{{ $message }}</div>
                            </span>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Civilité <span class="text-danger">*</span></label>
                        <select name="civilite" class="form-control form-control-sm" required>
                            <option value="" disabled selected>-- Sélectionnez une civilité --</option>
                            <option value="M." {{ old('civilite') == 'M.' ? 'selected' : '' }}>M.</option>
                            <option value="Mme" {{ old('civilite') == 'Mme' ? 'selected' : '' }}>Mme</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Prénom <span class="text-danger">*</span></label>
                        <input type="text" name="prenom" class="form-control form-control-sm"
                            value="{{ old('prenom') }}" required placeholder="Ex: Mouhamadou">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Nom <span class="text-danger">*</span></label>
                        <input type="text" name="nom" class="form-control form-control-sm"
                            value="{{ old('nom') }}" required placeholder="Ex: Ndiaye">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Date de naissance <span class="text-danger">*</span></label>
                        <input type="date" name="date_naissance" class="form-control form-control-sm"
                            value="{{ old('date_naissance') }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Lieu de naissance <span class="text-danger">*</span></label>
                        <input type="text" name="lieu_naissance" class="form-control form-control-sm"
                            value="{{ old('lieu_naissance') }}" required placeholder="Ex: Dakar">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control form-control-sm"
                            value="{{ old('email') }}" required placeholder="Ex: email@email.com">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Téléphone principal <span class="text-danger">*</span></label>
                        {{-- <input type="text" name="telephone" class="form-control form-control-sm"
                            value="{{ old('telephone') }}" required> --}}

                        <input name="telephone" type="text" maxlength="12"
                            class="form-control form-control-sm @error('telephone') is-invalid @enderror" id="phone"
                            value="{{ old('telephone') }}" autocomplete="tel" placeholder="XX:XXX:XX:XX">
                        <div class="invalid-feedback">
                            @error('telephone')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Téléphone secondaire <span class="text-danger">*</span></label>
                        {{-- <input type="text" name="telephone_secondaire" class="form-control form-control-sm"
                            value="{{ old('telephone_secondaire') }}"> --}}
                            <input name="telephone_secondaire" type="text" maxlength="12"
                            class="form-control form-control-sm @error('telephone_secondaire') is-invalid @enderror" id="phonesecondaire"
                            value="{{ old('telephone_secondaire') }}" autocomplete="tel" placeholder="XX:XXX:XX:XX">
                        <div class="invalid-feedback">
                            @error('telephone_secondaire')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label>Adresse <span class="text-danger">*</span></label>
                        <input type="text" name="adresse" class="form-control form-control-sm"
                            value="{{ old('adresse') }}" required placeholder="Ex: Grand Dakar">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label>Dernier diplôme obtenu <span class="text-danger">*</span></label>
                        <input type="text" name="dernier_diplome" class="form-control form-control-sm"
                            value="{{ old('dernier_diplome') }}" required placeholder="Ex: BAC">
                    </div>
                </div>
                <div class="text-end">
                    <button type="button" class="btn-orange next-step">Suivant</button>
                </div>
            </div>

            <!-- Étape 2 : Établissement -->
            <div class="step" id="step2">
                <h4 class="text-center mb-3 text-secondary">Établissement d'accueil</h4>
                <div class="form-group mb-3">
                    <label>Nom de l'établissement <span class="text-danger">*</span></label>
                    <input type="text" name="nom_etablissement" class="form-control form-control-sm"
                        value="{{ old('nom_etablissement') }}" required placeholder="Ex: CNQP">
                </div>
                <div class="form-group mb-3">
                    <label>Région <span class="text-danger">*</span></label>
                    <select name="region" class="form-control form-control-sm" required>
                        <option value="" disabled selected>-- Sélectionnez une région --</option>
                        <option value="Dakar" {{ old('region') == 'Dakar' ? 'selected' : '' }}>Dakar</option>
                        <option value="Diourbel" {{ old('region') == 'Diourbel' ? 'selected' : '' }}>Diourbel</option>
                        <option value="Fatick" {{ old('region') == 'Fatick' ? 'selected' : '' }}>Fatick</option>
                        <option value="Kaffrine" {{ old('region') == 'Kaffrine' ? 'selected' : '' }}>Kaffrine</option>
                        <option value="Kaolack" {{ old('region') == 'Kaolack' ? 'selected' : '' }}>Kaolack</option>
                        <option value="Kédougou" {{ old('region') == 'Kédougou' ? 'selected' : '' }}>Kédougou</option>
                        <option value="Kolda" {{ old('region') == 'Kolda' ? 'selected' : '' }}>Kolda</option>
                        <option value="Louga" {{ old('region') == 'Louga' ? 'selected' : '' }}>Louga</option>
                        <option value="Matam" {{ old('region') == 'Matam' ? 'selected' : '' }}>Matam</option>
                        <option value="Saint-Louis" {{ old('region') == 'Saint-Louis' ? 'selected' : '' }}>Saint-Louis
                        </option>
                        <option value="Sédhiou" {{ old('region') == 'Sédhiou' ? 'selected' : '' }}>Sédhiou</option>
                        <option value="Tambacounda" {{ old('region') == 'Tambacounda' ? 'selected' : '' }}>Tambacounda
                        </option>
                        <option value="Thiès" {{ old('region') == 'Thiès' ? 'selected' : '' }}>Thiès</option>
                        <option value="Ziguinchor" {{ old('region') == 'Ziguinchor' ? 'selected' : '' }}>Ziguinchor
                        </option>
                    </select>
                </div>
                <div class="form-group mb-3">
                    <label>Formation sollicitée <span class="text-danger">*</span></label>
                    <input type="text" name="formation" class="form-control form-control-sm"
                        value="{{ old('formation') }}" required placeholder="Ex: Electromécanique">
                </div>
                <div class="form-group mb-3">
                    <label>Diplôme visé <span class="text-danger">*</span></label>
                    <select name="diplome_vise" class="form-control form-control-sm" required>
                        <option value="" disabled selected>-- Sélectionnez un diplôme --</option>
                        @foreach (['CPS', 'CAP', 'BEP', 'BT', 'BTS', 'Licence 3', 'Licence professionnelle', 'Autres certifications'] as $diplome)
                            <option value="{{ $diplome }}"
                                {{ old('diplome_vise') == $diplome ? 'selected' : '' }}>{{ $diplome }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Montant inscription <span class="text-danger">*</span></label>
                        <input type="number" name="montant_inscription" class="form-control form-control-sm"
                            value="{{ old('montant_inscription') }}" required min="0" placeholder="500 000">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Montant mensualité <span class="text-danger">*</span></label>
                        <input type="number" name="montant_mensualite" class="form-control form-control-sm"
                            value="{{ old('montant_mensualite') }}" required min="0" placeholder="70 000">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Durée (en années) <span class="text-danger">*</span></label>
                        <input type="number" name="duree" class="form-control form-control-sm"
                            value="{{ old('duree') }}" min="1" max="3" required placeholder="Ex: 2">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Montant unique</label>
                        <input type="number" name="montant_unique" class="form-control form-control-sm"
                            value="{{ old('montant_unique') }}" min="0" placeholder="Ex: 750 000">
                    </div>
                </div>
                <div class="text-end">
                    <button type="button" class="btn btn-secondary btn-sm prev-step">Précédent</button>
                    <button type="button" class="btn-orange next-step">Suivant</button>
                </div>
            </div>

            <!-- Étape 3 : Informations complémentaires -->
            <div class="step" id="step3">
                <h4 class="text-center mb-3 text-secondary">Informations complémentaires</h4>
                <div class="form-group mb-3">
                    <label>Êtes-vous une personne en situation de handicap ? <span class="text-danger">*</span></label>
                    <select name="handicape" id="handicape" class="form-control form-control-sm" required>
                        <option value="" disabled selected>-- Sélectionnez une option --</option>
                        <option value="non" {{ old('handicape') == 'non' ? 'selected' : '' }}>Non</option>
                        <option value="oui" {{ old('handicape') == 'oui' ? 'selected' : '' }}>Oui</option>
                    </select>
                </div>
                <div class="form-group mb-3" id="type_handicap_field"
                    style="display: {{ old('handicape') == 'oui' ? 'block' : 'none' }};">
                    <input type="text" name="type_handicap" class="form-control form-control-sm"
                        value="{{ old('type_handicap') }}" placeholder="Précisez le type de handicap">
                </div>
                <div class="form-group mb-3">
                    <label>Êtes-vous orphelin ? <span class="text-danger">*</span></label>
                    <select name="orphelin" id="orphelin" class="form-control form-control-sm" required>
                        <option value="" disabled selected>-- Sélectionnez une option --</option>
                        <option value="non" {{ old('orphelin') == 'non' ? 'selected' : '' }}>Non</option>
                        <option value="oui" {{ old('orphelin') == 'oui' ? 'selected' : '' }}>Oui</option>
                    </select>
                </div>
                <div class="form-group mb-3" id="type_orphelin_field"
                    style="display: {{ old('orphelin') == 'oui' ? 'block' : 'none' }};">
                    <select name="type_orphelin" class="form-control form-control-sm">
                        <option value="">Précisez : de père, de mère ou des deux</option>
                        <option value="père" {{ old('type_orphelin') == 'père' ? 'selected' : '' }}>De père</option>
                        <option value="mère" {{ old('type_orphelin') == 'mère' ? 'selected' : '' }}>De mère</option>
                        <option value="les deux" {{ old('type_orphelin') == 'les deux' ? 'selected' : '' }}>Des deux
                        </option>
                    </select>
                </div>

                <!-- 🧾 Nouveaux champs de fichiers -->
                <div class="form-group mb-3">
                    <label>Copie du N° CIN (format PDF ou image) <span class="text-danger">*</span></label>
                    <input type="file" name="cin_file" class="form-control form-control-sm"
                        accept=".pdf,.jpg,.jpeg,.png" required>
                </div>

                <div class="form-group mb-3">
                    <label>Facture proforma ONFP (format PDF ou image) <span class="text-danger">*</span></label>
                    <input type="file" name="facture_file" class="form-control form-control-sm"
                        accept=".pdf,.jpg,.jpeg,.png" required>
                </div>

                <div class="form-group mb-3">
                    <label>Diplôme (format PDF ou image) <span class="text-danger">*</span></label>
                    <input type="file" name="diplome" class="form-control form-control-sm"
                        accept=".pdf,.jpg,.jpeg,.png" required>
                </div>

                <div class="form-group mb-3">
                    <label>CV (format PDF ou image) <span class="text-danger">*</span></label>
                    <input type="file" name="cv" class="form-control form-control-sm"
                        accept=".pdf,.jpg,.jpeg,.png" required>
                </div>

                <div class="text-end">
                    <button type="button" class="btn btn-secondary btn-sm prev-step">Précédent</button>
                    <button type="button" class="btn-orange next-step">Suivant</button>
                </div>
            </div>

            <!-- Étape 4 : Récapitulatif -->
            <div class="step" id="step4">
                <h4 class="text-center mb-3 text-secondary">Récapitulatif</h4>
                <div id="recap-container" class="p-3 bg-light rounded"></div>
                <div class="text-end mt-4">
                    <button type="button" class="btn btn-secondary btn-sm prev-step">Précédent</button>
                    <button type="submit" class="btn-orange" id="submitBtn">Envoyer</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.select2').select2({
                width: '100%'
            });

            let currentStep = {{ $currentStep }};
            const steps = $(".step");
            const circles = $(".step-circle");

            function showStep(index) {
                steps.removeClass("active").eq(index).addClass("active");
                circles.removeClass("active").eq(index).addClass("active");
            }

            $(".next-step").click(function() {
                if (currentStep < steps.length - 1) currentStep++;
                showStep(currentStep);

                if (currentStep === 3) {
                    const labels = {
                        cin: "Numéro CIN",
                        civilite: "Civilité",
                        prenom: "Prénom",
                        nom: "Nom",
                        date_naissance: "Date de naissance",
                        lieu_naissance: "Lieu de naissance",
                        email: "Adresse e-mail",
                        telephone: "Téléphone principal",
                        telephone_secondaire: "Téléphone secondaire",
                        adresse: "Adresse",
                        dernier_diplome: "Dernier diplôme obtenu",
                        nom_etablissement: "Établissement",
                        region: "Région",
                        formation: "Formation sollicitée",
                        diplome_vise: "Diplôme visé",
                        montant_inscription: "Montant inscription",
                        montant_mensualite: "Montant mensualité",
                        montant_unique: "Montant unique",
                        duree: "Durée (en années)",
                        handicape: "Situation de handicap",
                        type_handicap: "Type de handicap",
                        orphelin: "Orphelin",
                        type_orphelin: "Type d’orphelinat",
                        cin_file: "Copie du N° CIN",
                        facture_file: "Facture proforma ONFP",
                        cv: "CV",
                        diplome: "Diplôme"
                    };

                    let recap = "";

                    // 🔸 Récupère les champs texte classiques
                    $("#multiStepForm").serializeArray().forEach(field => {
                        const name = field.name;
                        const value = field.value.trim();

                        if (
                            name === "_token" ||
                            name === "recaptcha_token" ||
                            value === ""
                        ) return;

                        if (name === "type_handicap" && $("#handicape").val() !== "oui") return;
                        if (name === "type_orphelin" && $("#orphelin").val() !== "oui") return;

                        recap +=
                            `<div class="recap-item"><span>${labels[name] || name} :</span> ${value}</div>`;
                    });

                    // 🔹 Récupère les fichiers
                    const cinFile = $('input[name="cin_file"]')[0].files[0];
                    const factureFile = $('input[name="facture_file"]')[0].files[0];
                    const diplomeFile = $('input[name="diplome"]')[0].files[0];
                    const cvFile = $('input[name="cv"]')[0].files[0];

                    if (cinFile) {
                        recap +=
                            `<div class="recap-item"><span>${labels.cin_file} :</span> ${cinFile.name}</div>`;
                    }

                    if (factureFile) {
                        recap +=
                            `<div class="recap-item"><span>${labels.facture_file} :</span> ${factureFile.name}</div>`;
                    }
                    if (diplomeFile) {
                        recap +=
                            `<div class="recap-item"><span>${labels.diplome} :</span> ${diplomeFile.name}</div>`;
                    }
                    if (cvFile) {
                        recap +=
                            `<div class="recap-item"><span>${labels.cv} :</span> ${cvFile.name}</div>`;
                    }

                    $("#recap-container").html(recap);
                }
            });

            $(".prev-step").click(function() {
                if (currentStep > 0) currentStep--;
                showStep(currentStep);
            });

            $("#handicape").change(function() {
                $(this).val() === "oui" ? $("#type_handicap_field").slideDown() : $("#type_handicap_field")
                    .slideUp().find("input").val('');
            });

            $("#orphelin").change(function() {
                $(this).val() === "oui" ? $("#type_orphelin_field").slideDown() : $("#type_orphelin_field")
                    .slideUp().find("select").val('');
            });

            showStep(currentStep);
        });
    </script>
    <!-- SweetAlert CSS/JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- reCAPTCHA -->
    <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            $("#submitBtn").on("click", function(e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Certification',
                    html: `
                <div style="text-align:left">
                    <p>Avant d’envoyer votre demande, veuillez certifier que toutes les informations fournies sont exactes et sincères.</p>
                    <div class="form-check" style="margin-top:10px;">
                        <input class="form-check-input" type="checkbox" id="confirmCheck">
                        <label class="form-check-label" for="confirmCheck">
                            Je certifie l’exactitude des informations renseignées.
                        </label>
                    </div>
                </div>
            `,
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonText: 'Confirmer l’envoi',
                    cancelButtonText: 'Annuler',
                    reverseButtons: true,
                    focusConfirm: false,
                    preConfirm: () => {
                        if (!document.getElementById('confirmCheck').checked) {
                            Swal.showValidationMessage(
                                'Veuillez cocher la case pour certifier vos informations.'
                            );
                            return false;
                        }
                        return true;
                    },
                    customClass: {
                        confirmButton: 'swal2-confirm-small', // classe personnalisée pour le bouton confirmer
                        cancelButton: 'swal2-cancel-small' // classe personnalisée pour le bouton annuler
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Envoi en cours...',
                            text: 'Veuillez patienter pendant la vérification.',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        grecaptcha.ready(function() {
                            grecaptcha.execute(
                                    '{{ config('services.recaptcha.site_key') }}', {
                                        action: 'submit'
                                    })
                                .then(function(token) {
                                    document.getElementById('recaptcha_token').value =
                                        token;
                                    document.getElementById('multiStepForm').submit();
                                });
                        });
                    }
                });
            });

        });
    </script>

    <style>
        /* Boutons SweetAlert réduits et couleur personnalisée */
        .swal2-confirm-small {
            padding: 0.25rem 0.5rem !important;
            font-size: 0.75rem !important;
            background-color: #F28500 !important;
            /* couleur verte pour confirmer */
            color: #fff !important;
        }

        .swal2-confirm-small:hover {
            background-color: #F28500 !important;
        }

        .swal2-cancel-small {
            padding: 0.25rem 0.5rem !important;
            font-size: 0.75rem !important;
        }
    </style>


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var telephoneInput = document.getElementById("phone");

            telephoneInput.addEventListener("input", function(e) {
                var value = e.target.value.replace(/\D/g, ""); // Supprime tout sauf les chiffres

                // Appliquer le format XX:XXX:XX:XX
                if (value.length > 2) value = value.slice(0, 2) + " " + value.slice(2);
                if (value.length > 6) value = value.slice(0, 6) + " " + value.slice(6);
                if (value.length > 9) value = value.slice(0, 9) + " " + value.slice(9, 11);

                e.target.value = value.slice(0, 12); // Limite à 12 caractères (avec les ":")
            });
        });
        document.addEventListener("DOMContentLoaded", function() {
            var telephoneInput = document.getElementById("phonesecondaire");

            telephoneInput.addEventListener("input", function(e) {
                var value = e.target.value.replace(/\D/g, ""); // Supprime tout sauf les chiffres

                // Appliquer le format XX:XXX:XX:XX
                if (value.length > 2) value = value.slice(0, 2) + " " + value.slice(2);
                if (value.length > 6) value = value.slice(0, 6) + " " + value.slice(6);
                if (value.length > 9) value = value.slice(0, 9) + " " + value.slice(9, 11);

                e.target.value = value.slice(0, 12); // Limite à 12 caractères (avec les ":")
            });
        });

        document.addEventListener("DOMContentLoaded", function() {
            var cinInput = document.getElementById("cin");

            cinInput.addEventListener("input", function(e) {
                var value = e.target.value.replace(/[^A-Za-z0-9]/g,
                    ""); // Supprimer tout sauf lettres et chiffres

                // Convertir toutes les lettres en majuscule si elles existent
                value = value.toUpperCase();

                // Appliquer le format: 1 chiffre - espace - 3 chiffres - espace - 4 chiffres - espace - 5 ou 6 chiffres
                if (value.length > 1) value = value.slice(0, 1) + " " + value.slice(
                    1); // 1er chiffre + espace
                if (value.length > 5) value = value.slice(0, 5) + " " + value.slice(
                    5); // 3 chiffres + espace
                if (value.length > 10) value = value.slice(0, 10) + " " + value.slice(
                    10); // 4 chiffres + espace

                // Limiter à 16 ou 17 caractères (espaces inclus)
                e.target.value = value.slice(0, 17); // 16 ou 17 caractères au total
            });
        });
    </script>
    @include('sweetalert::alert')

</body>

</html>
