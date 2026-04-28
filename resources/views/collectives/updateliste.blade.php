@extends('layout.user-layout')
@section('title', 'Modification ' . $listecollective?->civilite . ' ' . $listecollective?->prenom . ' ' .
    $listecollective?->nom)
@section('space-work')
    <section class="section">

        <div class="container">
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
                    <div class="card mb-0">
                        <div class="card-body">

                            {{-- <span class="nav-link"><a
                                    href="{{ route('collectivemodules.show', $listecollective?->collectivemodule) }}"
                                    class="btn btn-secondary btn-sm" title="retour"><i
                                        class="bi bi-arrow-counterclockwise"></i></a>
                            </span>
                            <span class="d-flex align-items-baseline"><a
                                    href="{{ route('collectivemodules.show', $listecollective?->collectivemodule) }}"
                                    class="btn btn-success btn-sm" title="retour"><i
                                        class="bi bi-arrow-counterclockwise"></i></a>&nbsp;
                                <p> | retour</p>
                            </span> --}}

                            @php
                                $userRoles = Auth::user()->roles->pluck('name')->toArray(); // Récupère les rôles de l'utilisateur
                            @endphp

                            @if (in_array('super-admin', $userRoles) || in_array('admin', $userRoles))
                                <!-- Si l'utilisateur a le rôle 'super-admin' ou 'admin', afficher ce bouton -->
                                <span class="nav-link">
                                    <a href="{{ route('collectivemodules.show', $listecollective?->collectivemodule) }}"
                                        class="btn btn-secondary btn-sm" title="retour">
                                        <i class="bi bi-arrow-counterclockwise"></i>
                                    </a>
                                </span>
                            @else
                                <!-- Sinon, afficher l'autre bouton pour le rôle 'Demandeur' -->
                                <span class="d-flex align-items-baseline">
                                    <a href="{{ route('collectivemodules.show', $listecollective?->collectivemodule) }}"
                                        class="btn btn-success btn-sm" title="retour">
                                        <i class="bi bi-arrow-counterclockwise"></i>
                                    </a>&nbsp;
                                    <p> | retour</p>
                                </span>
                            @endif

                            <form method="post" action="{{ route('listecollectives.update', $listecollective) }}"
                                enctype="multipart/form-data" class="row g-3">
                                @csrf
                                @method('PUT')
                                <div class="row g-3">
                                    <input type="hidden" name="collective" value="{{ $listecollective->collective }}">
                                    {{-- <div class="col-12 col-md-4 mb-0">
                                        <label for="cin" class="form-label">CIN<span
                                                class="text-danger mx-1">*</span></label>
                                        <input name="cin" type="text"
                                            class="form-control form-control-sm @error('cin') is-invalid @enderror"
                                            id="cin" value="{{ old('cin') ?? $listecollective?->cin }}"
                                            autocomplete="off" placeholder="Ex: 1099200500012" minlength="16"
                                            maxlength="17" required>
                                        @error('cin')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div> --}}

                                    <div class="col-12 col-md-4 mb-0">
                                        <label class="form-label">
                                            Type de pièce <span class="text-danger mx-1">*</span>
                                        </label>
                                        <select name="type_piece" id="type_piece" class="form-select form-select-sm">
                                            <option value="">-- Choisir --</option>
                                            <option value="cni"
                                                {{ (old('type_piece') ?? $listecollective?->type_piece) === 'cni' ? 'selected' : '' }}>
                                                Carte nationale</option>
                                            @can('voir-extrait')
                                                <option value="extrait"
                                                    {{ (old('type_piece') ?? $listecollective?->type_piece) === 'extrait' ? 'selected' : '' }}>
                                                    Extrait de naissance</option>
                                            @endcan
                                            <option value="passeport"
                                                {{ (old('type_piece') ?? $listecollective?->type_piece) === 'passeport' ? 'selected' : '' }}>
                                                Passeport</option>
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-4 mb-0">
                                        <label for="num_piece" class="form-label" id="numero_piece_label">
                                            Numéro de la pièce <span class="text-danger mx-1">*</span>
                                        </label>
                                        <input name="cin" type="text"
                                            class="form-control form-control-sm @error('cin') is-invalid @enderror"
                                            id="num_piece"
                                            value="{{ old('cin') ?? str_replace(' ', '', $listecollective?->cin) }}"
                                            autocomplete="off" placeholder="Ex : 1099200500012" minlength="13"
                                            maxlength="14">
                                        @error('cin')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-4 mb-0">
                                        <label for="civilite" class="form-label">Civilité<span
                                                class="text-danger mx-1">*</span></label>
                                        <select name="civilite"
                                            class="form-select form-select-sm @error('civilite') is-invalid @enderror"
                                            aria-label="Select" id="select-field-civilite"
                                            data-placeholder="Choisir civilité">
                                            <option value="{{ $listecollective?->civilite ?? old('civilite') }}">
                                                {{ $listecollective?->civilite ?? old('civilite') }}
                                            </option>
                                            <option value="M.">
                                                M.
                                            </option>
                                            <option value="Mme">
                                                Mme
                                            </option>
                                        </select>
                                        @error('civilite')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-12 col-md-4 mb-0">
                                        <label for="firstname" class="form-label">Prénom<span
                                                class="text-danger mx-1">*</span></label>
                                        <input type="text" name="firstname"
                                            value="{{ $listecollective?->prenom ?? old('firstname') }}"
                                            class="form-control form-control-sm @error('firstname') is-invalid @enderror"
                                            id="firstname" placeholder="prénom">
                                        @error('firstname')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-4 mb-0">
                                        <label for="name" class="form-label">Nom<span
                                                class="text-danger mx-1">*</span></label>
                                        <input type="text" name="name"
                                            value="{{ $listecollective?->nom ?? old('name') }}"
                                            class="form-control form-control-sm @error('name') is-invalid @enderror"
                                            id="name" placeholder="nom">
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-4 mb-0">
                                        <label for="date_naissance" class="form-label">Date naissance<span
                                                class="text-danger mx-1">*</span></label>
                                        <input type="text" name="date_naissance" min="00"
                                            value="{{ old('date_naissance', optional($listecollective?->date_naissance)->format('d/m/Y')) }}"
                                            class="form-control form-control-sm @error('date_naissance') is-invalid @enderror"
                                            id="datepicker" placeholder="JJ/MM/AAAA" autocomplete="bday">
                                        @error('date_naissance')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-4 mb-0">
                                        <label for="name" class="form-label">Lieu naissance<span
                                                class="text-danger mx-1">*</span></label>
                                        <input name="lieu_naissance" type="text"
                                            class="form-control form-control-sm @error('lieu_naissance') is-invalid @enderror"
                                            id="lieu_naissance"
                                            value="{{ $listecollective?->lieu_naissance ?? old('lieu_naissance') }}"
                                            autocomplete="lieu_naissance" placeholder="Lieu naissance">
                                        @error('lieu_naissance')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <input type="hidden" name="module"
                                        value="{{ $listecollective?->collectivemodule->id }}">

                                    <div class="col-12 col-md-4 mb-0">
                                        <label for="telephone" class="form-label">Téléphone<span
                                                class="text-danger mx-1">*</span></label>
                                        <input name="telephone" type="text" maxlength="9"
                                            class="form-control form-control-sm @error('telephone') is-invalid @enderror"
                                            id="telephone"
                                            value="{{ old('telephone', $listecollective->telephone ?? '') }}"
                                            autocomplete="tel" placeholder="Téléphone">
                                        @error('telephone')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-4 mb-0">
                                        <label for="Niveau étude" class="form-label">Niveau étude<span
                                                class="text-danger mx-1">*</span></label>
                                        <select name="niveau_etude"
                                            class="form-select  @error('niveau_etude') is-invalid @enderror"
                                            aria-label="Select" id="select-field-niveau_etude"
                                            data-placeholder="Choisir niveau étude">
                                            <option value="{{ $listecollective?->niveau_etude ?? old('niveau_etude') }}">
                                                {{ $listecollective?->niveau_etude ?? old('niveau_etude') }}
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

                                    {{-- <div class="col-12 col-md-4 mb-0">
                                        <label for="Statut" class="form-label">Statut</label>
                                        <select name="statut"
                                            class="form-select  @error('statut') is-invalid @enderror"
                                            aria-label="Select" id="select-field-statut"
                                            data-placeholder="Choisir Statut">
                                            <option value="{{ $listecollective?->statut ?? old('statut') }}">
                                                {{ $listecollective?->statut ?? old('statut') }}
                                            </option>
                                            <option value="Nouvelle">
                                                Nouvelle
                                            </option>
                                        </select>
                                        @error('statut')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div> --}}

                                    <div class="col-12 mb-0">
                                        <label for="experience" class="form-label">Expériences<span
                                                class="text-danger mx-1">*</span></label>
                                        <textarea name="experience" id="experience" rows="1"
                                            class="form-control form-control-sm @error('experience') is-invalid @enderror"
                                            placeholder="Expériences ou stages">{{ $listecollective?->experience ?? old('experience') }}</textarea>
                                        @error('experience')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-12 mb-0">
                                        <label for="autre_experience" class="form-label">Autres expériences</label>
                                        <textarea name="autre_experience" id="autre_experience" rows="1"
                                            class="form-control form-control-sm @error('autre_experience') is-invalid @enderror"
                                            placeholder="Autres expériences">{{ $listecollective?->autre_experience ?? old('autre_experience') }}</textarea>
                                        @error('autre_experience')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-12 mb-0">
                                        <label for="details" class="form-label">Commentaires</label>
                                        <textarea name="details" id="details" rows="1"
                                            class="form-control form-control-sm @error('details') is-invalid @enderror" placeholder="Autres expériences">{{ $listecollective?->details ?? old('details') }}</textarea>
                                        @error('details')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="modal-footer text-center">
                                        <button type="submit" class="btn btn-outline-primary btn-sm">Enregister les
                                            modifications</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const typePiece = document.getElementById('type_piece');
            const numeroInput = document.getElementById('num_piece');
            const numeroLabel = document.getElementById('numero_piece_label');

            // 🔹 Fonction qui met à jour le label, placeholder et contraintes
            function updateNumeroPiece(type, value = '') {
                // On garde la valeur actuelle si fournie
                if (value) {
                    numeroInput.value = value;
                }

                switch (type) {
                    case 'cni':
                        numeroLabel.innerHTML = 'Numéro de la carte nationale <span class="required">*</span>';
                        numeroInput.placeholder = 'Ex : 1099200500012';
                        numeroInput.setAttribute('minlength', 13);
                        numeroInput.setAttribute('maxlength', 14);
                        numeroInput.setAttribute('pattern', '[A-Za-z0-9]{13,14}');
                        break;

                    case 'extrait':
                        numeroLabel.innerHTML = 'Numéro de l’extrait de naissance <span class="required">*</span>';
                        numeroInput.placeholder = 'Ex : 00345/2010';
                        numeroInput.setAttribute('minlength', 10);
                        numeroInput.setAttribute('maxlength', 10);
                        numeroInput.setAttribute('pattern', '[A-Za-z0-9/]{10}');
                        break;

                    case 'passeport':
                        numeroLabel.innerHTML = 'Numéro du passeport <span class="required">*</span>';
                        numeroInput.placeholder = 'Ex : A12345678';
                        numeroInput.setAttribute('minlength', 9);
                        numeroInput.setAttribute('maxlength', 9);
                        numeroInput.removeAttribute('pattern');
                        break;

                    default:
                        numeroLabel.innerHTML = 'Numéro de la pièce <span class="required">*</span>';
                        numeroInput.placeholder = '';
                        numeroInput.removeAttribute('minlength');
                        numeroInput.removeAttribute('maxlength');
                        numeroInput.removeAttribute('pattern');
                        break;
                }
            }

            // 🔹 Fonction pour détecter le type de pièce depuis la valeur
            function detectTypeFromValue(value) {
                value = value.replace(/\s+/g, '');
                const length = value.length;

                if (value.includes('/') && length === 10) return 'extrait';
                if (length === 9) return 'passeport';
                if (length === 13 || length === 14) return 'cni';

                return null;
            }

            // 🔹 Initialisation au chargement
            const initialValue = numeroInput.value;
            const detectedType = detectTypeFromValue(initialValue);

            if (detectedType) {
                typePiece.value = detectedType;
                updateNumeroPiece(detectedType, initialValue);
            } else {
                updateNumeroPiece(typePiece.value, initialValue);
            }

            // 🔹 Changement dynamique du select
            typePiece.addEventListener('change', function() {
                updateNumeroPiece(this.value);
            });

            // 🔹 Détection automatique pendant la saisie du CIN
            numeroInput.addEventListener('input', function() {
                const detected = detectTypeFromValue(this.value);
                if (detected && typePiece.value !== detected) {
                    typePiece.value = detected;
                    updateNumeroPiece(detected);
                }

                // Limiter la saisie côté front selon maxlength
                const max = this.getAttribute('maxlength');
                if (max && this.value.length > max) {
                    this.value = this.value.slice(0, max);
                }
            });
        });
    </script>
@endpush
