@extends('layout.user-layout')
@section('title', remove_accents_uppercase('DOSSIER | AGREMENT'))
@section('space-work')
    <section class="section">
        <div class="row justify-content-center">
            <div class="col-12">
                @if ($message = Session::get('status'))
                    <div class="alert alert-danger bg-danger text-light border-0 alert-dismissible fade show" region="alert">
                        <strong>{{ $message }}</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if ($message = Session::get('success'))
                    <div class="alert alert-success bg-success text-light border-0 alert-dismissible fade show"
                        region="alert">
                        <strong>{{ $message }}</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <div class="alert alert-danger bg-danger text-light border-0 alert-dismissible fade show"
                            role="alert">
                            <strong>{{ $error }}</strong>
                        </div>
                    @endforeach
                @endif
                <div class="card">
                    <div class="card-body">
                        {{-- <div class="d-flex justify-content-between align-items-center mt-0">
                            <span class="d-flex mt-0 align-items-baseline"><a href="{{ url('/profil') }}"
                                    class="btn btn-success btn-sm" title="retour"><i
                                        class="bi bi-arrow-counterclockwise"></i></a>&nbsp;
                                <p> | Profil</p>
                            </span>
                            <button class="btn btn-info btn-sm">
                                <span class="badge bg-white text-info">{{ $operateurA->count() }}</span>
                            </button>
                            @can('devenir-operateur-agrement-ouvert')
                                @can('devenir-operateur-agrement-create')
                                    @can('agrement-ouvert')
                                        <button type="button" class="btn btn-warning btn-sm float-end btn-rounded"
                                            data-bs-toggle="modal" data-bs-target="#AddoperateurModal">
                                            Renouveler agrément
                                        </button>
                                    @elsecan('agrement-fermer')
                                        <span class="text-danger small fw-bold">Les agréments sont actuellement
                                            <span class="text-uppercase">fermés</span></span>
                                    @endcan
                                @endcan
                            @endcan
                        </div> --}}

                        <div class="shadow rounded-3">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center px-4 py-2">

                                <!-- Gauche -->
                                <div class="d-flex align-items-center gap-2">
                                    <a href="{{ url('/profil') }}" class="btn btn-outline-success btn-sm" title="Retour">
                                        <i class="bi bi-arrow-counterclockwise"></i>
                                    </a>
                                    <span class="fw-bold">| Profil</span>
                                </div>

                                <!-- Centre -->
                                <div>
                                    <button class="btn btn-info btn-sm position-relative">
                                        <i class="bi bi-person-badge"></i>
                                        <span
                                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-white text-info">
                                            {{ $operateurA?->count() }}
                                        </span>
                                    </button>
                                </div>
                                <!-- Droite -->
                                <div class="d-flex align-items-center gap-2">
                                    @can('devenir-operateur-agrement-ouvert')
                                        @can('devenir-operateur-agrement-create')
                                            @can('agrement-ouvert')
                                                {{-- <button type="button" class="btn btn-warning btn-sm fw-bold btn-rounded"
                                                    data-bs-toggle="modal" data-bs-target="#AddoperateurModal">
                                                    <i class="bi bi-arrow-repeat me-1"></i> Renouveler agrément
                                                </button> --}}
                                                {{-- @if ($diffYears !== null && $diffYears > 2)
                                                    <button type="button" class="btn btn-warning btn-sm fw-bold btn-rounded"
                                                        data-bs-toggle="modal" data-bs-target="#AddoperateurModal">
                                                        <i class="bi bi-arrow-repeat me-1"></i> Renouveler agrément
                                                    </button>
                                                @else
                                                    <button type="button" class="btn btn-success fw-bold small btn-sm fw-bold btn-rounded">
                                                        Votre agrément expire le {{ $dateExpiration }}
                                                    </button>
                                                @endif
                                            @elsecan('agrement-fermer')
                                                <span class="text-danger small fw-bold">
                                                    Les agréments sont actuellement <span class="text-uppercase">fermés</span>
                                                </span> --}}
                                                {{-- @if ($estExpire)
                                                    <div class="d-flex flex-column align-items-start gap-1">
                                                        <button type="button"
                                                            class="btn btn-danger fw-bold small btn-sm fw-bold btn-rounded">
                                                            Votre agrément est <strong>expiré</strong> depuis le
                                                            {{ $dateExpiration?->format('d/m/Y') }}
                                                        </button>
                                                        <button type="button" class="btn btn-warning btn-sm fw-bold btn-rounded"
                                                            data-bs-toggle="modal" data-bs-target="#AddoperateurModal">
                                                            <i class="bi bi-arrow-repeat me-1"></i> Renouveler agrément
                                                        </button>
                                                    </div>
                                                @else
                                                    @can('agrement-view-op')
                                                        <span class="text-success fw-bold small">
                                                            Votre agrément est encore valide jusqu'au
                                                            {{ $dateExpiration?->format('d/m/Y') }}
                                                        </span>
                                                    @endcan
                                                @endif --}}
                                                @if ($estExpire)
                                                    <div
                                                        class="alert alert-danger border-1 d-flex flex-column gap-2 p-3 shadow-sm rounded-3">
                                                        <div class="d-flex align-items-center">
                                                            <i class="bi bi-exclamation-triangle-fill me-2 fs-4 text-danger"></i>
                                                            <div>
                                                                <strong>Agrément expiré :</strong>
                                                                <span class="d-block small">
                                                                    La validité de votre agrément est arrivée à échéance depuis le
                                                                    <span
                                                                        class="fw-bold">{{ $dateExpiration?->format('d/m/Y') }}</span>.
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="text-end">
                                                            <button type="button"
                                                                class="btn btn-success btn-sm fw-semibold rounded-pill shadow-sm px-3"
                                                                data-bs-toggle="modal" data-bs-target="#AddoperateurModal">
                                                                <div class="d-flex align-items-center gap-1">
                                                                    <i class="bi bi-arrow-repeat"></i>
                                                                    <span>Cliquez ici pour déposer</span>
                                                                </div>
                                                            </button>
                                                        </div>
                                                    </div>
                                                @elseif($estExtension)
                                                    <div
                                                        class="alert alert-info border-1 d-flex flex-column gap-2 p-3 shadow-sm rounded-3">
                                                        <div class="d-flex align-items-center">
                                                            <i class="bi bi-exclamation-triangle-fill me-2 fs-4 text-info"></i>
                                                            <div>
                                                                <strong>Agrément toujours valide : </strong>
                                                                {{-- <span class="d-block small">
                                                                    Votre agrément est arrivée à échéance depuis le
                                                                    <span
                                                                        class="fw-bold">{{ $dateExpiration?->format('d/m/Y') }}</span>.
                                                                </span> --}}
                                                            </div>
                                                        </div>
                                                        <div class="text-end">
                                                            <button type="button"
                                                                class="btn btn-primary btn-sm fw-semibold rounded-pill shadow-sm px-3"
                                                                data-bs-toggle="modal" data-bs-target="#AddoperateurModal">
                                                                <div class="d-flex align-items-center gap-1">
                                                                    <i class="bi bi-arrow-repeat"></i>
                                                                    <span>Cliquez ici pour une extension</span>
                                                                </div>
                                                            </button>
                                                        </div>
                                                    </div>
                                                @else
                                                    @can('agrement-view-op')
                                                        @if ($dateExpiration)
                                                            {{-- <div
                                                                class="alert alert-success d-flex align-items-center p-2 small rounded-2 shadow-sm">
                                                                <i class="bi bi-check-circle-fill me-2 fs-5 text-success"></i>
                                                                <span>
                                                                    Votre agrément est encore valide jusqu’au
                                                                    <strong>{{ $dateExpiration?->format('d/m/Y') }}</strong>.
                                                                </span>
                                                            </div> --}}
                                                            <div
                                                                class="alert alert-success d-flex align-items-center p-2 small rounded-2 shadow-sm">
                                                                <i class="bi bi-check-circle-fill me-2 fs-5 text-success"></i>
                                                                <span>
                                                                    Votre demande d’agrément a été soumise avec succès.
                                                                    {{-- <strong>{{ $dateExpiration?->format('d/m/Y') }}</strong> --}}
                                                                </span>
                                                            </div>
                                                        @endif
                                                    @endcan
                                                @endif
                                            @endcan
                                        @endcan
                                    @endcan
                                </div>

                            </div>
                        </div>

                        @foreach ($operateurA as $operateur)
                            <div class="card mb-4 shadow-sm border-0 w-100">
                                <div class="card-header bg-white border-bottom py-3 px-4">
                                    <div class="row align-items-center gy-2">

                                        {{-- Type de demande --}}
                                        <div class="col-12 col-md-auto">
                                            <div class="d-flex align-items-center flex-wrap">
                                                <i class="bi bi-arrow-right-circle text-secondary me-2"></i>
                                                <span class="fst-italic">Type de demande :</span>
                                                <span class="ms-2 fw-semibold {{ $operateur?->type_demande }}">
                                                    {{ $operateur?->type_demande }}
                                                </span>
                                            </div>
                                        </div>

                                        {{-- Date agrément --}}
                                        @if ($operateur->commissionagrements->isNotEmpty())
                                            <div class="col-12 col-md text-md-center">
                                                <div class="d-flex flex-wrap align-items-center justify-content-md-center">
                                                    <i class="bi bi-building text-primary me-2"></i>
                                                    <span class="fw-bold">Date agrément :</span>

                                                    <span class="ms-2 text-primary">
                                                        @foreach ($operateur->commissionagrements as $commission)
                                                            {{ optional($commission->fin_commission)->format('d/m/Y') ?? 'Non définie' }}
                                                        @endforeach
                                                    </span>
                                                </div>
                                            </div>
                                        @endif

                                        {{-- Statut --}}
                                        <div class="col-12 col-md-auto text-md-end">
                                            <div class="d-flex align-items-center justify-content-md-end">
                                                <span class="fw-semibold text-muted me-2">Statut :</span>
                                                <span
                                                    class="badge {{ $operateur?->statut_agrement }} px-3 py-2 fs-6 shadow-sm rounded-pill">
                                                    {{ $operateur?->statut_agrement }}
                                                </span>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                {{-- <div class="card-body px-4">

                                    <div
                                        class="d-flex justify-content-between align-items-center border-bottom py-1 position-relative">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-journal-code text-info me-2 fs-5"></i>
                                            <span class="me-2">Modules</span>
                                        </div>

                                        <span
                                            class="badge {{ count($operateur->operateurmodules) === 0 ? 'bg-danger' : 'bg-info' }} position-absolute top-50 start-50 translate-middle-y"
                                            style="transform: translateX(-50%);">
                                            {{ count($operateur->operateurmodules) }}
                                        </span>

                                        <div>
                                            <a href="{{ route('operateurs.show', $operateur) }}" target="_blank"
                                                class="btn btn-sm btn-outline-success me-1" title="Ajouter/Modifier">
                                                <i class="bi bi-pencil-square me-1"></i> Ajouter / Modifier
                                            </a>
                                        </div>
                                    </div>

                                    <div
                                        class="d-flex justify-content-between align-items-center border-bottom py-1 position-relative">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-bookmark-check text-primary me-2"></i>Références
                                            <span
                                                class="badge {{ count($operateur->operateureferences) === 0 ? 'bg-danger' : 'bg-info' }} position-absolute top-50 start-50 translate-middle-y"
                                                style="transform: translateX(-50%);">{{ count($operateur->operateureferences) }}</span>
                                        </div>
                                        <div>
                                            <a href="{{ route('showReference', $operateur->uuid) }}" target="_blank"
                                                class="btn btn-sm btn-outline-success me-1" title="Ajouter/Modifier">
                                                <i class="bi bi-pencil-square me-1"></i> Ajouter / Modifier
                                            </a>
                                        </div>
                                    </div>

                                    <div
                                        class="d-flex justify-content-between align-items-center border-bottom py-1 position-relative">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-hdd-network text-warning me-2"></i>Équipements & Infrastructures
                                            <span
                                                class="badge {{ count($operateur->operateurequipements) === 0 ? 'bg-danger' : 'bg-info' }} position-absolute top-50 start-50 translate-middle-y"
                                                style="transform: translateX(-50%);">{{ count($operateur->operateurequipements) }}</span>
                                        </div>
                                        <div>
                                            <a href="{{ route('showEquipement', $operateur->uuid) }}" target="_blank"
                                                class="btn btn-sm btn-outline-success me-1" title="Ajouter/Modifier">
                                                <i class="bi bi-pencil-square me-1"></i> Ajouter / Modifier
                                            </a>
                                        </div>
                                    </div>

                                    <div
                                        class="d-flex justify-content-between align-items-center border-bottom py-1 position-relative">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-person-workspace text-success me-2"></i>Formateurs
                                            <span
                                                class="badge {{ count($operateur->operateurformateurs) === 0 ? 'bg-danger' : 'bg-info' }} position-absolute top-50 start-50 translate-middle-y"
                                                style="transform: translateX(-50%);">{{ count($operateur->operateurformateurs) }}</span>
                                        </div>
                                        <div>
                                            <a href="{{ route('showFormateur', $operateur->uuid) }}" target="_blank"
                                                class="btn btn-sm btn-outline-success me-1" title="Ajouter/Modifier">
                                                <i class="bi bi-pencil-square me-1"></i> Ajouter / Modifier
                                            </a>
                                        </div>
                                    </div>

                                    <div
                                        class="d-flex justify-content-between align-items-center border-bottom py-1 position-relative">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-geo-alt text-danger me-2"></i>Localités
                                            <span
                                                class="badge {{ count($operateur->operateurlocalites) === 0 ? 'bg-danger' : 'bg-info' }} position-absolute top-50 start-50 translate-middle-y"
                                                style="transform: translateX(-50%);">{{ count($operateur->operateurlocalites) }}</span>
                                        </div>
                                        <div>
                                            <a href="{{ route('showLocalite', $operateur->uuid) }}" target="_blank"
                                                class="btn btn-sm btn-outline-success me-1" title="Ajouter/Modifier">
                                                <i class="bi bi-pencil-square me-1"></i> Ajouter / Modifier
                                            </a>
                                        </div>
                                    </div>

                                    <div
                                        class="d-flex justify-content-between align-items-center border-bottom py-1 position-relative">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-info-circle text-secondary me-2"></i>État de la demande
                                            <span
                                                class="badge {{ $statut_demande === 'incomplète' ? 'bg-danger' : 'bg-success' }} position-absolute top-50 start-50 translate-middle-y"
                                                style="transform: translateX(-50%);">{{ $statut_demande }}</span>
                                        </div>
                                    </div>

                                    <div
                                        class="d-flex justify-content-between align-items-center border-bottom py-1 position-relative">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-file-earmark-text text-dark me-2"></i>
                                            Validité quitus
                                            @if ($operateur?->debut_quitus)
                                                <span
                                                    class="badge {{ ($diff?->y ?? 0) * 12 + ($diff?->m ?? 0) > 3 ? 'bg-danger' : 'bg-info' }} position-absolute top-50 start-50 translate-middle-y"
                                                    style="transform: translateX(-50%);">
                                                    {{ $diffText }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div
                                        class="d-flex justify-content-between align-items-center border-bottom py-1 position-relative">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-bookmark-check text-primary me-2"></i>Certifier informations
                                            @php
                                                $estCertifie = boolval($operateur->file8);
                                            @endphp

                                            <span
                                                class="badge {{ $estCertifie ? 'bg-success' : 'bg-danger' }} position-absolute top-50 start-50 translate-middle-y"
                                                style="transform: translateX(-50%);">
                                                {!! $estCertifie ? '<i class="bi bi-check-circle"></i> Oui' : '<i class="bi bi-x-circle"></i> Non' !!}
                                            </span>
                                        </div>
                                        <div>
                                            @if ($statut_demande === 'complète')
                                                <button type="button" class="btn btn-sm btn-outline-primary me-1"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#certificationModal{{ $operateur->id }}">
                                                    <i class="bi bi-pencil-square me-1"></i> Je certifie
                                                </button>
                                            @else
                                                <div class="alert alert-warning p-2 mb-0 d-flex gap-2">
                                                    <i class="bi bi-exclamation-triangle-fill text-warning"></i>
                                                    <div>
                                                        L'état de la demande doit être <strong>complète</strong> avant de
                                                        certifier.
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div> --}}

                                <div class="card-body px-4">

                                    @foreach ($sections as $section)
                                        <div
                                            class="d-flex justify-content-between align-items-center border-bottom py-1 position-relative">

                                            <div class="d-flex align-items-center">
                                                <i class="bi {{ $section['icon'] }} me-2"></i>
                                                {{ $section['label'] }}

                                                @if (isset($section['count']))
                                                    <span
                                                        class="badge {{ $section['badge'] ?? ($section['count'] === 0 ? 'bg-danger' : 'bg-info') }}
            position-absolute top-50 start-50 translate-middle-y"
                                                        style="transform: translateX(-50%);">
                                                        {{ $section['count'] }}
                                                    </span>
                                                @endif
                                            </div>

                                            <div>
                                                @if (!empty($section['route']))
                                                    <a href="{{ $section['route'] }}" target="_blank"
                                                        class="btn btn-sm btn-outline-success">
                                                        <i class="bi bi-pencil-square me-1"></i> Ajouter / Modifier
                                                    </a>
                                                @elseif(!empty($section['modal']))
                                                    <button class="btn btn-sm btn-outline-success" title="Modifier"
                                                        data-bs-toggle="modal" data-bs-target="#{{ $section['modal'] }}">
                                                        <i class="bi bi-pencil-square me-1"></i> Ajouter / Modifier
                                                    </button>
                                                @endif
                                            </div>

                                        </div>
                                    @endforeach

                                    {{-- ETAT DEMANDE --}}
                                    <div
                                        class="d-flex justify-content-between align-items-center border-bottom py-1 position-relative">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-info-circle text-secondary me-2"></i>
                                            État de la demande

                                            <span
                                                class="badge {{ $statut_demande === 'incomplète' ? 'bg-danger' : 'bg-success' }}
            position-absolute top-50 start-50 translate-middle-y"
                                                style="transform: translateX(-50%);">
                                                {{ $statut_demande }}
                                            </span>
                                        </div>
                                    </div>


                                    {{-- CERTIFICATION --}}
                                    @php $estCertifie = boolval($operateur->file8); @endphp

                                    <div
                                        class="d-flex justify-content-between align-items-center border-bottom py-1 position-relative">

                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-bookmark-check text-primary me-2"></i>
                                            Certifier informations

                                            <span
                                                class="badge {{ $estCertifie ? 'bg-success' : 'bg-danger' }}
            position-absolute top-50 start-50 translate-middle-y"
                                                style="transform: translateX(-50%);">
                                                {!! $estCertifie ? '<i class="bi bi-check-circle"></i> Oui' : '<i class="bi bi-x-circle"></i> Non' !!}
                                            </span>
                                        </div>

                                        <div>
                                            @if ($statut_demande === 'complète')
                                                <button type="button" class="btn btn-sm btn-outline-primary"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#certificationModal{{ $operateur->id }}">
                                                    <i class="bi bi-pencil-square me-1"></i>
                                                    Je certifie
                                                </button>
                                            @else
                                                <span class="badge bg-warning text-dark p-2">
                                                    <i class="bi bi-exclamation-triangle-fill me-1"></i>
                                                    Demande incomplète
                                                </span>
                                            @endif
                                        </div>

                                    </div>

                                </div>

                                @can('update', $operateur)
                                    <div
                                        class="card-footer bg-light text-center py-3 border-top d-flex justify-content-center gap-3">
                                        @can('devenir-operateur-agrement-delete')
                                            @can('delete', $operateur)
                                                <form action="{{ route('operateurs.destroy', $operateur) }}" method="post"
                                                    class="d-inline-block show_confirm">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm px-4"
                                                        title="Supprimer">
                                                        <i class="bi bi-trash me-1"></i> Supprimer
                                                    </button>
                                                </form>
                                            @endcan
                                        @endcan
                                    </div>
                                @endcan
                            </div>
                        @endforeach

                        @can('upload-file-view')
                            <!-- Liens utiles -->
                            <div class="card border-info mb-4">
                                <div class="card-body">
                                    <h5 class="card-title text-info">Les liens utiles</h5>
                                    <ul class="list-unstyled ps-3 mb-0">
                                        <li>
                                            <a href="https://demarche.mfprsp.com/#/connexion" target="_blank"
                                                class="text-decoration-none">
                                                Attestation de non fonctionnaire
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <hr>

                            <!-- Section formulaire upload -->
                            <h5 class="card-title mb-3">JOINDRE VOS SCANS DE DOSSIERS</h5>
                            <form method="post" action="{{ route('files.update', $operateur?->user) }}"
                                enctype="multipart/form-data" class="row g-3">
                                @csrf
                                @method('patch')
                                <input type="hidden" name="idUser" value="{{ $operateur?->user->id }}">

                                <!-- Liste des documents à fournir -->
                                <div class="col-12 col-lg-6">
                                    <div class="card border-info shadow-sm mb-4">
                                        <div class="card-body">
                                            <h5 class="card-title text-info mb-3">Veuillez fournir les documents suivants</h5>

                                            <!-- Privé -->
                                            <h6 class="fw-bold text-primary mt-2">Pour le privé :</h6>
                                            <ul class="list-unstyled ps-3 mb-3">
                                                <li><i class="bi bi-check-circle text-success me-2"></i>Quitus fiscal <span
                                                        class="text-danger">*</span></li>
                                                <li><i class="bi bi-check-circle text-success me-2"></i>Ninéa ou registre de
                                                    commerce <span class="text-danger">*</span></li>
                                                <li><i class="bi bi-check-circle text-success me-2"></i>Attestation de non
                                                    fonctionnaire ou carte de retraite <span class="text-danger">*</span></li>
                                                <li><i class="bi bi-check-circle text-success me-2"></i>Convention de
                                                    partenariat ou contrat de location à usage professionnel <span
                                                        class="text-danger">*</span></li>
                                                <li><i class="bi bi-check-circle text-muted me-2"></i>Acte de création, arrêté
                                                    de création ou récépissé <small class="text-muted">(si disponible)</small>
                                                </li>
                                            </ul>

                                            <!-- Public -->
                                            <h6 class="fw-bold text-primary mt-2">Pour le public :</h6>
                                            <ul class="list-unstyled ps-3 mb-0">
                                                <li><i class="bi bi-check-circle text-success me-2"></i>Acte de création ou
                                                    arrêté de création <span class="text-danger">*</span></li>
                                                <li><i class="bi bi-check-circle text-muted me-2"></i>Ninéa ou registre de
                                                    commerce <small class="text-muted">(si disponible)</small></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <!-- Upload Formulaire -->
                                <div class="col-12 col-lg-6">
                                    <div class="card border-primary shadow-sm mb-4">
                                        <div class="card-body">

                                            <!-- Sélection légende -->
                                            <div class="row mb-3 mt-3">
                                                <label for="legende" class="col-12 col-form-label">LEGENDE <span
                                                        class="text-danger">*</span></label>
                                                <div class="col-12">
                                                    <select name="legende"
                                                        class="form-select @error('legende') is-invalid @enderror"
                                                        id="select-field-file" data-placeholder="Choisir">
                                                        <option value="{{ old('legende') }}">{{ old('legende') }}</option>
                                                        @foreach ($user_files as $file)
                                                            <option value="{{ $file?->id }}">
                                                                {{ $labels[$file?->legende] ?? $file?->legende }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('legende')
                                                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Choisir fichier -->
                                            <div class="row mb-3 mt-3">
                                                <label for="file" class="col-12 col-form-label">CHOISIR FICHIER <span
                                                        class="text-danger">*</span></label>
                                                <div class="col-12">
                                                    <input type="file" name="file" id="file"
                                                        class="form-control @error('file') is-invalid @enderror btn btn-info btn-sm">
                                                    @error('file')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Bouton téléverser -->
                                            <div class="row mb-3">
                                                <div class="col-12">
                                                    <button type="submit" class="btn btn-primary btn-sm text-white">
                                                        <i class="bi bi-upload me-1"></i> Téléverser
                                                    </button>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </form>

                            <!-- Fichiers joints -->
                            <div class="row pt-2">
                                <h5 class="card-title col-12">FICHIERS JOINTS</h5>
                                @if ($files->isNotEmpty())
                                    <div class="col-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover datatables" id="table-files">
                                                <thead>
                                                    <tr class="text-center">
                                                        <th style="width: 5%">N°</th>
                                                        <th>Légende</th>
                                                        <th style="width: 10%">Fichier</th>
                                                        <th style="width: 10%">Statut</th>
                                                        <th style="width: 10%">Supprimer</th>
                                                        @hasanyrole('super-admin|admin|DIOF')
                                                            <th style="width: 10%">Valider</th>
                                                            <th style="width: 10%">Rejeter</th>
                                                        @endhasanyrole
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php $i = 1; @endphp
                                                    @foreach ($files as $file)
                                                        <tr class="text-center align-middle">
                                                            <td>{{ $i++ }}</td>
                                                            <td>{{ $labels[$file?->legende] ?? $file?->legende }}</td>
                                                            <td>
                                                                <a class="btn btn-outline-secondary btn-sm"
                                                                    title="Télécharger" target="_blank"
                                                                    href="{{ asset($file->getFichier()) }}">
                                                                    <i class="bi bi-download"></i>
                                                                </a>
                                                            </td>
                                                            <td>
                                                                @php
                                                                    $statut = $file->statut ?? 'Attente';
                                                                    $badgeClass = match ($statut) {
                                                                        'Validé' => 'success',
                                                                        'Rejeté', 'Invalide' => 'danger',
                                                                        default => 'secondary',
                                                                    };
                                                                @endphp
                                                                <span
                                                                    class="badge bg-{{ $badgeClass }}">{{ $statut }}</span>
                                                            </td>

                                                            <!-- Supprimer -->
                                                            <td>
                                                                @if ($file->statut !== 'Validé')
                                                                    <form action="{{ route('fileDestroy') }}" method="post"
                                                                        class="d-inline">
                                                                        @csrf
                                                                        @method('put')
                                                                        <input type="hidden" name="idFile"
                                                                            value="{{ $file->id }}">
                                                                        <button type="submit"
                                                                            class="btn btn-outline-danger btn-sm show_confirm"
                                                                            title="Supprimer">
                                                                            <i class="bi bi-trash"></i>
                                                                        </button>
                                                                    </form>
                                                                @endif
                                                            </td>

                                                            @hasanyrole('super-admin|admin|DIOF')
                                                                <!-- Valider -->
                                                                <td>
                                                                    <form action="{{ route('fileValidate') }}" method="post"
                                                                        class="d-inline">
                                                                        @csrf
                                                                        @method('put')
                                                                        <input type="hidden" name="idFile"
                                                                            value="{{ $file->id }}">
                                                                        <button type="submit"
                                                                            class="btn btn-outline-success btn-sm show_confirm_valider"
                                                                            title="Valider">
                                                                            <i class="bi bi-check-circle"></i>
                                                                        </button>
                                                                    </form>
                                                                </td>
                                                                <!-- Rejeter -->
                                                                <td>
                                                                    <form action="{{ route('fileInvalide') }}" method="post"
                                                                        class="d-inline">
                                                                        @csrf
                                                                        @method('put')
                                                                        <input type="hidden" name="idFile"
                                                                            value="{{ $file->id }}">
                                                                        <button type="submit"
                                                                            class="btn btn-outline-warning btn-sm show_confirm_rejeter"
                                                                            title="Invalider">
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
                                    </div>
                                @else
                                    <div class="alert alert-info text-center text-muted">Aucun fichier joint</div>
                                @endif
                            </div>
                        @endcan
                        @foreach ($operateurA as $operateur)
                            <div class="col-lg-12 d-flex flex-column align-items-center justify-content-center">
                                <div class="modal fade" id="validationViewModal{{ $operateur?->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="col-12">
                                                <table
                                                    class="table table-bordered table-hover table-borderless table-stripped">
                                                    <tr>
                                                        <td>Modules</td>
                                                        <td style="text-align: center;"><span
                                                                class="{{ $module_count }}">{{ count($operateur->operateurmodules) }}</span>
                                                        </td>
                                                        <td style="text-align: center;"><a
                                                                href="{{ route('operateurs.show', $operateur->id) }}"
                                                                class="btn btn-outline-primary btn-rounded btn-sm"
                                                                target="_blank">
                                                                <i class="bi bi-plus"
                                                                    title="Ajouter, Modifier, Supprimer"></i> </a></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Références professionnelles</td>
                                                        <td style="text-align: center;"><span
                                                                class="{{ $reference_count }}">{{ count($operateur->operateureferences) }}</span>
                                                        </td>
                                                        <td style="text-align: center;"><a
                                                                href="{{ route('showReference', ['uuid' => $operateur->uuid]) }}"
                                                                class="btn btn-outline-primary btn-rounded btn-sm"
                                                                target="_blank">
                                                                <i class="bi bi-plus"
                                                                    title="Ajouter, Modifier, Supprimer"></i> </a></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Infrastructures et Equipements</td>
                                                        <td style="text-align: center;"><span
                                                                class="{{ $equipement_count }}">{{ count($operateur->operateurequipements) }}</span>
                                                        </td>
                                                        <td style="text-align: center;"><a
                                                                href="{{ route('showEquipement', ['uuid' => $operateur->uuid]) }}"
                                                                class="btn btn-outline-primary btn-rounded btn-sm"
                                                                target="_blank">
                                                                <i class="bi bi-plus"
                                                                    title="Ajouter, Modifier, Supprimer"></i> </a></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Formateurs</td>
                                                        <td style="text-align: center;"><span
                                                                class="{{ $formateur_count }}">{{ count($operateur->operateurformateurs) }}</span>
                                                        </td>
                                                        <td style="text-align: center;"><a
                                                                href="{{ route('showFormateur', ['uuid' => $operateur->uuid]) }}"
                                                                class="btn btn-outline-primary btn-rounded btn-sm"
                                                                target="_blank">
                                                                <i class="bi bi-plus"
                                                                    title="Ajouter, Modifier, Supprimer"></i> </a></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Localités</td>
                                                        <td style="text-align: center;"><span
                                                                class="{{ $localite_count }}">{{ count($operateur->operateurlocalites) }}</span>
                                                        </td>
                                                        <td style="text-align: center;"><a
                                                                href="{{ route('showLocalite', ['uuid' => $operateur->uuid]) }}"
                                                                class="btn btn-outline-primary btn-rounded btn-sm"
                                                                target="_blank">
                                                                <i class="bi bi-plus"
                                                                    title="Ajouter, Modifier, Supprimer"></i> </a></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 d-flex flex-column align-items-center justify-content-center">
            <div class="modal fade" id="AddoperateurModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form method="POST" action="{{ route('renewOperateur') }}" enctype="multipart/form-data">
                            @csrf

                            {{-- En-tête du formulaire --}}
                            <div class="card-header bg-white border-bottom text-center py-4">
                                <h4 class="text-primary fw-bold mb-0">
                                    <i class="bi bi-arrow-repeat me-2 text-dark"></i>Demande agrément
                                </h4>
                            </div>

                            {{-- Corps du formulaire --}}
                            <div class="modal-body px-4 pt-4">
                                <div class="row g-4">
                                    {{-- Quitus fiscal --}}
                                    {{-- <div class="col-12">
                                        <label for="quitus" class="form-label fw-semibold">Quitus fiscal
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="file" name="quitus" id="quitus"
                                            accept=".jpg, .jpeg, .png, .svg, .gif"
                                            class="form-control form-control-sm @error('quitus') is-invalid @enderror">
                                        @error('quitus')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div> --}}

                                    <div class="col-12">
                                        <label for="type_demande" class="form-label">Type demande<span
                                                class="text-danger mx-1">*</span></label>
                                        <select name="type_demande"
                                            class="form-select form-select-sm @error('type_demande') is-invalid @enderror"
                                            aria-label="Select" id="select-field_type_demande"
                                            data-placeholder="Choisir">
                                            <option value="{{ old('type_demande') }}">
                                                {{ old('type_demande') }}
                                            </option>
                                            <option value="Nouvelle">
                                                Nouvelle
                                            </option>
                                            {{-- <option value="Renouvellement">
                                                Renouvellement
                                            </option> --}}
                                            <option value="Extension">
                                                Extension
                                            </option>
                                        </select>
                                        @error('type_demande')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                        <div class="form-text text-muted mt-2">
                                            <i class="bi bi-info-circle me-1"></i>
                                            Sélectionnez <strong>Nouvelle</strong> si votre dernier agrément remonte à plus
                                            de 4 ans. <br>
                                            <i class="bi bi-info-circle me-1"></i>
                                            Sélectionnez <strong>Extension</strong> si votre dernier agrément remonte à
                                            moins
                                            de 4 ans.
                                        </div>
                                    </div>
                                    {{-- Date du quitus --}}
                                    <div class="col-12">
                                        <label for="date_quitus" class="form-label fw-semibold">Date du visa
                                            quitus</label>
                                        <input type="text" name="date_quitus" id="datepicker"
                                            value="{{ old('date_quitus') }}"
                                            class="form-control form-control-sm @error('date_quitus') is-invalid @enderror"
                                            placeholder="JJ/MM/AAAA" autocomplete="bday">
                                        @error('date_quitus')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Pied de modal --}}
                            <div class="modal-footer bg-light mt-4 py-3 px-4">
                                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                                    <i class="bi bi-x-circle me-1"></i>Fermer
                                </button>
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="bi bi-check2-circle me-1"></i>Enregistrer
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @foreach ($operateurs as $operateur)
            <div class="modal fade" id="EditOperateurModal{{ $operateur->id }}" tabindex="-1"
                aria-labelledby="EditOperateurModalLabel{{ $operateur->id }}" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form method="POST" action="{{ route('operateurs.updated', $operateur->uuid) }}"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')

                            {{-- En-tête --}}
                            <div class="card-header text-center bg-white border-bottom py-3">
                                <h4 class="text-primary fw-bold mb-0">
                                    <i class="bi bi-pencil-square me-2 text-dark"></i> Modification opérateur
                                </h4>
                            </div>

                            {{-- Corps --}}
                            <div class="modal-body px-4 pt-4">
                                <input type="hidden" name="id" value="{{ $operateur->id }}">

                                <div class="row g-4">
                                    <input name="type_demande" type="hidden" value="Nouvelle">

                                    {{-- Département --}}
                                    <div class="col-12">
                                        <label for="departement" class="form-label fw-semibold">Département <span
                                                class="text-danger">*</span></label>
                                        <select name="departement" id="select-field-departement-update"
                                            class="form-select form-select-sm @error('departement') is-invalid @enderror">
                                            <option value="{{ $operateur->departement?->nom }}">
                                                {{ $operateur->departement?->nom }}</option>
                                            @foreach ($departements as $departement)
                                                <option value="{{ $departement->nom }}">{{ $departement->nom }}</option>
                                            @endforeach
                                        </select>
                                        @error('departement')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-12">
                                        <label for="date_quitus" class="form-label fw-semibold">Date visa quitus</label>
                                        <input type="date" name="date_quitus"
                                            value="{{ old('date_quitus', optional($operateur?->debut_quitus)->format('d/m/Y')) }}"
                                            class="form-control form-control-sm @error('date_quitus') is-invalid @enderror"
                                            placeholder="JJ/MM/AAAA" autocomplete="bday">

                                        @error('date_quitus')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Pied de formulaire --}}
                            <div class="modal-footer bg-light py-3 px-4 mt-4">
                                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                                    <i class="bi bi-x-circle me-1"></i> Fermer
                                </button>
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="bi bi-save me-1"></i> Enregistrer les modifications
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach

        <!-- Modal de certification -->
        @foreach ($operateurs as $operateur)
            <div class="modal fade" id="certificationModal{{ $operateur->id }}" tabindex="-1"
                aria-labelledby="certificationModalLabel{{ $operateur->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <form method="POST" action="{{ route('certifierOperateur', $operateur->uuid) }}">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header bg-warning text-white">
                                <h5 class="modal-title" id="certificationModalLabel{{ $operateur->id }}">Certification
                                    des informations</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                    aria-label="Fermer"></button>
                            </div>
                            {{-- <div class="modal-body">
                            <p>Veuillez écrire exactement cette phrase pour certifier :</p>
                            <blockquote class="fst-italic border-start ps-3 text-muted">
                                Je certifie que les informations que j'ai fournies sont correctes.
                            </blockquote>

                            <div class="mb-3">
                                <label for="certification_phrase" class="form-label">Votre phrase :</label>
                                <input type="text" class="form-control" id="certification_phrase"
                                    name="certification_phrase" placeholder="Tapez la phrase de certification ici..."
                                    required>
                            </div>
                        </div> --}}
                            {{-- <div class="modal-body">
                                <p>Veuillez cocher la case suivante pour certifier :</p>
                                <div class="form-check border rounded p-3 bg-light">
                                    <input class="form-check-input @error('certification_phrase') is-invalid @enderror"
                                        type="checkbox" id="certification_checkbox" name="certification_phrase"
                                        value="Je certifie que les informations que j'ai fournies sont correctes.">
                                    <label class="form-check-label fst-italic text-muted" for="certification_checkbox">
                                        @error('certification_phrase')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        Je certifie que les informations que j'ai fournies sont correctes.
                                    </label>
                                </div>
                            </div> --}}
                            <div class="modal-body">
                                <p class="mb-2">Veuillez cocher la case suivante pour certifier :</p>

                                <div class="alert alert-warning py-2 small">
                                    Une fois certifiée, vous ne pourrez plus modifier ni supprimer cette demande.
                                </div>

                                <div class="form-check border rounded p-3 bg-light">
                                    <input class="form-check-input @error('certification_phrase') is-invalid @enderror"
                                        type="checkbox" id="certification_checkbox" name="certification_phrase"
                                        value="Je certifie que les informations que j'ai fournies sont correctes.">
                                    <label class="form-check-label fst-italic text-muted" for="certification_checkbox">
                                        Je certifie que les informations que j'ai fournies sont correctes.
                                    </label>
                                    @error('certification_phrase')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary btn-sm"
                                    data-bs-dismiss="modal">Fermer</button>
                                <button type="submit" class="btn btn-success btn-sm">Certifier</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach
    </section>
@endsection
