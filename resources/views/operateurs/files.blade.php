@can('upload-file-view')

    <div class="row">

        {{-- ================= DOCUMENTS A FOURNIR ================= --}}
        <div class="col-12 col-lg-6">

            <div class="card border-info shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0 text-info">
                        <i class="bi bi-info-circle me-2"></i>
                        Documents à fournir
                    </h5>
                </div>

                <div class="card-body">

                    {{-- PRIVÉ --}}
                    <h6 class="fw-bold text-primary">Pour le privé :</h6>

                    <ul class="list-unstyled ps-2 mb-4">
                        <li><i class="bi bi-check-circle text-success me-2"></i>Registre de commerce <span
                                class="text-danger">*</span></li>
                        <li><i class="bi bi-check-circle text-success me-2"></i>NINEA <span class="text-danger">*</span>
                        </li>
                        <li><i class="bi bi-check-circle text-success me-2"></i>Autorisation d'ouverture ministérielle <span
                                class="text-danger">*</span></li>
                        <li><i class="bi bi-check-circle text-success me-2"></i>Quitus fiscal <span
                                class="text-danger">*</span></li>
                        <li><i class="bi bi-check-circle text-success me-2"></i>Organigramme <span
                                class="text-danger">*</span></li>
                        <li><i class="bi bi-check-circle text-success me-2"></i>Acte de déclaration d'existence au niveau de
                            la Direction générale des Imôts <span class="text-danger">*</span></li>
                        <li><i class="bi bi-check-circle text-success me-2"></i>Attestation de non fonctionnaire ou carte de
                            retraite <span class="text-danger">*</span></li>
                        <li><i class="bi bi-check-circle text-success me-2"></i>Convention de partenariat ou contrat de
                            location <span class="text-danger">*</span></li>
                        <li><i class="bi bi-check-circle text-muted me-2"></i>Attestation de bonne exécution (ABE) <small
                                class="text-muted">(si disponible)</small></li>
                        <li><i class="bi bi-check-circle text-muted me-2"></i>Contrat de prestation <small
                                class="text-muted">(si disponible)</small></li>
                        <li><i class="bi bi-check-circle text-muted me-2"></i>Acte ou arrêté de création <small
                                class="text-muted">(si disponible)</small></li>
                    </ul>

                    {{-- PUBLIC --}}
                    <h6 class="fw-bold text-primary">Pour le public :</h6>

                    <ul class="list-unstyled ps-2">
                        <li><i class="bi bi-check-circle text-success me-2"></i>Acte ou arrêté de création <span
                                class="text-danger">*</span></li>
                        <li><i class="bi bi-check-circle text-success me-2"></i>Quitus fiscal <span
                                class="text-danger">*</span></li>
                        <li><i class="bi bi-check-circle text-success me-2"></i>Organigramme <span
                                class="text-danger">*</span></li>
                        <li><i class="bi bi-check-circle text-success me-2"></i>Décision de nomination <span
                                class="text-danger">*</span></li>
                        <li><i class="bi bi-check-circle text-success me-2"></i>Registre de commerce <span
                                class="text-danger">*</span></li>
                        <li><i class="bi bi-check-circle text-success me-2"></i>NINEA <span class="text-danger">*</span>
                        </li>
                    </ul>

                </div>
            </div>

        </div>


        {{-- ================= FORMULAIRE UPLOAD ================= --}}
        <div class="col-12 col-lg-6">

            <div class="card border-primary shadow-sm mb-4">

                <div class="card-header bg-light">
                    <h5 class="mb-0 text-primary">
                        <i class="bi bi-upload me-2"></i>
                        Joindre un document
                    </h5>
                </div>

                <div class="card-body">

                    <form method="POST" action="{{ route('files.update', $operateur?->user) }}"
                        enctype="multipart/form-data" class="row g-3">

                        @csrf
                        @method('patch')

                        <input type="hidden" name="idUser" value="{{ $operateur?->user->id }}">

                        {{-- LEGENDE --}}
                        <div class="col-12">

                            <label class="form-label fw-semibold">
                                Légende <span class="text-danger">*</span>
                            </label>

                            <select name="legende" class="form-select @error('legende') is-invalid @enderror"
                                id="select-field-file">

                                <option value="">Choisir un document</option>

                                @foreach ($user_files as $file)
                                    <option value="{{ $file->id }}">
                                        {{ $labels[$file->legende] ?? $file->legende }}
                                    </option>
                                @endforeach

                            </select>

                            @error('legende')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                        </div>


                        {{-- FICHIER --}}
                        <div class="col-12">

                            <label class="form-label fw-semibold">
                                Choisir un fichier <span class="text-danger">*</span>
                            </label>

                            <input type="file" name="file" class="form-control @error('file') is-invalid @enderror">

                            @error('file')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror

                        </div>


                        {{-- BOUTON --}}
                        <div class="col-12">

                            <button type="submit" class="btn btn-primary w-100">

                                <i class="bi bi-upload me-1"></i>
                                Téléverser le fichier

                            </button>

                        </div>

                    </form>

                </div>
            </div>

        </div>

    </div>


    {{-- ================= TABLE DES FICHIERS ================= --}}
    <div class="row pt-3">

        <div class="col-12">

            <div class="card shadow-sm">

                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="bi bi-folder2-open me-2"></i>
                        Fichiers joints
                    </h5>
                </div>

                <div class="card-body">

                    @if ($files->isNotEmpty())
                        <div class="table-responsive">

                            <table class="table table-hover table-bordered align-middle datatables" id="table-files">

                                <thead class="table-light">
                                    <tr>
                                        <th width="5%" class="text-center">N°</th>
                                        <th>Légende</th>
                                        <th width="10%" class="text-center">Sigle</th>
                                        <th width="10%" class="text-center">Fichier</th>
                                        <th width="10%" class="text-center">Statut</th>
                                        <th width="10%" class="text-center">Supprimer</th>

                                        @hasanyrole('super-admin|admin|DIOF')
                                            <th width="10%" class="text-center">Valider</th>
                                            <th width="10%" class="text-center">Rejeter</th>
                                        @endhasanyrole
                                    </tr>
                                </thead>

                                <tbody>

                                    @foreach ($files as $i => $file)
                                        <tr>

                                            <td class="text-center">{{ $i + 1 }}</td>

                                            <td class="text-start">
                                                {{ $labels[$file->legende] ?? $file->legende }}
                                            </td>
                                            <td class="text-center">
                                                {{ $labels[$file->sigle] ?? $file->sigle }}
                                            </td>

                                            {{-- DOWNLOAD --}}
                                            <td class="text-center">

                                                <a href="{{ asset($file->getFichier()) }}" target="_blank"
                                                    class="btn btn-outline-secondary btn-sm">

                                                    <i class="bi bi-download"></i>

                                                </a>

                                            </td>


                                            {{-- STATUT --}}
                                            <td class="text-center">

                                                @php
                                                    $statut = $file->statut ?? 'Attente';

                                                    $badge = match ($statut) {
                                                        'Validé' => 'success',
                                                        'Rejeté', 'Invalide' => 'danger',
                                                        default => 'secondary',
                                                    };
                                                @endphp

                                                <span class="badge bg-{{ $badge }}">
                                                    {{ $statut }}
                                                </span>

                                            </td>


                                            {{-- DELETE --}}
                                            <td class="text-center">

                                                @if ($file->statut !== 'Validé')
                                                    <form action="{{ route('fileDestroy') }}" method="POST"
                                                        class="d-inline">

                                                        @csrf
                                                        @method('put')

                                                        <input type="hidden" name="idFile"
                                                            value="{{ $file->id }}">

                                                        <button class="btn btn-outline-danger btn-sm show_confirm">
                                                            <i class="bi bi-trash"></i>
                                                        </button>

                                                    </form>
                                                @endif

                                            </td>


                                            {{-- ADMIN ACTIONS --}}
                                            @hasanyrole('super-admin|admin|DIOF')
                                                <td class="text-center">

                                                    <form action="{{ route('fileValidate') }}" method="POST">
                                                        @csrf
                                                        @method('put')

                                                        <input type="hidden" name="idFile" value="{{ $file->id }}">

                                                        <button class="btn btn-outline-success btn-sm show_confirm_valider">
                                                            <i class="bi bi-check-circle"></i>
                                                        </button>

                                                    </form>

                                                </td>

                                                <td class="text-center">

                                                    <form action="{{ route('fileInvalide') }}" method="POST">
                                                        @csrf
                                                        @method('put')

                                                        <input type="hidden" name="idFile" value="{{ $file->id }}">

                                                        <button class="btn btn-outline-warning btn-sm show_confirm_rejeter">
                                                            <i class="bi bi-x-circle"></i>
                                                        </button>

                                                    </form>

                                                </td>
                                            @endhasanyrole

                                        </tr>
                                    @endforeach

                                </tbody>

                            </table>

                        </div>
                    @else
                        <div class="alert alert-info text-center mb-0">
                            Aucun fichier joint
                        </div>
                    @endif

                </div>

            </div>

        </div>

    </div>

@endcan
