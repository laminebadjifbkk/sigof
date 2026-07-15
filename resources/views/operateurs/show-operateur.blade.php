@extends('layout.user-layout')
@section('title', $user?->display_operateur)
@section('space-work')
    <section class="section">
        <div class="row justify-content-center">
            <div class="col-12">

                {{-- Messages --}}
                @if ($message = Session::get('status'))
                    <div class="alert alert-danger bg-danger text-light border-0 alert-dismissible fade show" role="alert">
                        <strong>{{ $message }}</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if ($message = Session::get('success'))
                    <div class="alert alert-success bg-success text-light border-0 alert-dismissible fade show"
                        role="alert">
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
                        <div class="shadow rounded-3">
                            <div class="card-header bg-light px-3 px-md-4 py-2">
                                <div class="row align-items-center g-2">
                                    {{-- Gauche --}}
                                    <div class="col-12 col-md-4">
                                        <div class="d-flex align-items-center gap-2">
                                            <a href="{{ url('/profil') }}"
                                                class="btn btn-outline-success btn-sm d-flex align-items-center justify-content-center gap-1"
                                                title="Retour">
                                                <i class="bi bi-arrow-counterclockwise"></i>
                                            </a>
                                            <span class="fw-bold">| Profil</span>
                                        </div>
                                    </div>
                                    {{-- Centre --}}
                                    <div class="col-12 col-md-4 text-md-center">
                                        <button
                                            class="btn btn-info btn-sm position-relative d-inline-flex align-items-center justify-content-center gap-1">
                                            <i class="bi bi-person-badge"></i>
                                            <span
                                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-white text-info">
                                                {{ $operateurs?->count() }}
                                            </span>
                                        </button>
                                    </div>
                                    {{-- Droite --}}

                                </div>
                            </div>
                        </div>
                    </div>

                    @php
                        // Identifier le dernier opérateur pour ce user
                        $dernierOperateur = $operateurs->first();
                    @endphp

                    {{-- Cartes opérateurs --}}
                    @foreach ($operateurs as $op)
                        @php
                            // Catégorie
                            $cat = $op->user?->categorie;

                            // Documents
                            $hasNinea = $files->contains(fn($file) => $file->sigle === 'Ninea');
                            $hasQuitus = $files->contains(fn($file) => $file->sigle === 'Quitus');
                            $hasAC = $files->contains(fn($file) => $file->sigle === 'AC');
                            $hasContrat = $files->contains(fn($file) => $file->sigle === 'Contrat');
                            $hasNF = $files->contains(fn($file) => $file->sigle === 'Non-fonctionnaire');

                            // Statut demande
                            $statut_demande = $op->profilEstComplet() ? 'complète' : 'incomplète';

                            // Quitus
                            $dateQuitus = $op->debut_quitus ? \Carbon\Carbon::parse($op->debut_quitus) : null;

                            $diffText = $dateQuitus ? $dateQuitus->locale('fr')->diffForHumans(now(), true) : 'N/A';

                            $diffInMonths = $dateQuitus ? $dateQuitus->diffInMonths(now()) : 0;

                            // Certification
                            $estCertifie = !empty($op->file8);

                            // Sections dynamiques

                            $sections = [
                                [
                                    'label' => 'Modules',
                                    'icon' => 'bi-journal-code text-info',
                                    'count' => $op->operateurmodules->count(),
                                    /* 'route' => route('operateurs.show', $op), */
                                    'route' => route('showModule', $op->uuid),
                                ],
                                [
                                    'label' => 'Références',
                                    'icon' => 'bi-bookmark-check text-primary',
                                    'count' => $op->operateureferences->count(),
                                    'route' => route('showReference', $op->uuid),
                                ],
                                [
                                    'label' => 'Équipements & Infrastructures',
                                    'icon' => 'bi-hdd-network text-warning',
                                    'count' => $op->operateurequipements->count(),
                                    'route' => route('showEquipement', $op->uuid),
                                ],
                                [
                                    'label' => 'Formateurs',
                                    'icon' => 'bi-person-workspace text-success',
                                    'count' => $op->operateurformateurs->count(),
                                    'route' => route('showFormateur', $op->uuid),
                                ],
                                [
                                    'label' => 'Localités',
                                    'icon' => 'bi-geo-alt text-danger',
                                    'count' => $op->operateurlocalites->count(),
                                    'route' => route('showLocalite', $op->uuid),
                                ],
                                [
                                    'label' => 'Validité quitus',
                                    'icon' => 'bi-file-earmark-text text-dark',
                                    'count' => $diffText,
                                    'badge' => $diffInMonths > 3 ? 'bg-danger' : 'bg-info',
                                    'modal' => "EditOperateurModal{$op->id}",
                                ],
                            ];

                            $showButton = $op->id === $dernierOperateur->id; // bouton seulement pour le dernier

                        @endphp

                        <div class="card mb-4 shadow-sm border-0 w-100">
                            {{-- <div class="card-header bg-white border-bottom py-3 px-4">
                                <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">

                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">

                                            <div class="col-12 col-md-auto">
                                                <div class="d-flex align-items-center flex-wrap">
                                                    <i class="bi bi-arrow-right-circle text-secondary me-2"></i>
                                                    <span class="fst-italic">Type :</span>
                                                    <span class="ms-2 fw-semibold {{ $op?->type_demande }}">
                                                        {{ $op?->type_demande }}
                                                    </span>
                                                </div>
                                            </div>

                                            @php
                                                $dates = $op->commissionagrements->pluck('fin_commission')->filter();
                                            @endphp

                                            @if ($dates->isNotEmpty())
                                                <div class="col-12 col-md text-md-center">
                                                    <div
                                                        class="d-flex flex-wrap align-items-center justify-content-md-center">
                                                        <i class="bi bi-building text-primary me-2"></i>
                                                        <span class="fw-bold">Date commission :</span>
                                                        <span class="ms-2 text-primary">
                                                            {{ $dates->map(fn($date) => \Carbon\Carbon::parse($date)->format('d/m/Y'))->implode(' - ') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @endif

                                            <div class="col-12 col-md-auto text-md-end">
                                                <span class="fw-semibold text-muted me-2">Statut :</span>
                                                <span
                                                    class="badge {{ $op?->statut_agrement }} px-3 py-2 fs-6 shadow-sm rounded-pill">
                                                    {{ $op?->statut_agrement }}
                                                </span>
                                            </div>

                                        </div>
                                    </div>

                                    @can('devenir-operateur-agrement-ouvert')
                                        @can('devenir-operateur-agrement-create')
                                            @can('agrement-ouvert')
                                                <div style="min-width: 280px; max-width: 320px;">
                                                    @if ($op->commissionagrements->isNotEmpty())
                                                        @if ($showButton)
                                                            @if ($op->est_expire)
                                                                <div class="alert alert-danger p-2 shadow-sm rounded-2 mb-0">
                                                                    <button type="button" class="btn btn-success btn-sm w-100 mb-1"
                                                                        data-bs-toggle="modal" data-bs-target="#AddoperateurModal">
                                                                        <i class="bi bi-arrow-repeat"></i>
                                                                        Faire une nouvelle demande
                                                                    </button>

                                                                    <small class="text-muted">
                                                                        Expiré depuis le
                                                                        <strong>{{ $op->date_expiration?->format('d/m/Y') }}</strong>
                                                                    </small>
                                                                </div>
                                                            @elseif($op->est_renouvellement)
                                                                <div class="alert alert-info p-2 shadow-sm rounded-2 mb-0">
                                                                    <button type="button" class="btn btn-primary btn-sm w-100 mb-1"
                                                                        data-bs-toggle="modal" data-bs-target="#AddoperateurModal">
                                                                        <i class="bi bi-arrow-repeat"></i>
                                                                        Renouvellement
                                                                    </button>

                                                                    <small class="text-muted">
                                                                        Agrément toujours valide
                                                                    </small>
                                                                </div>
                                                            @endif
                                                        @endif
                                                    @endif
                                                </div>
                                            @endcan
                                        @endcan
                                    @endcan

                                </div>
                            </div> --}}
                            <div class="card-header bg-white border-bottom py-3 px-4">
                                <div class="d-flex justify-content-between align-items-start flex-wrap gap-4">

                                    {{-- ================= LEFT ================= --}}
                                    <div class="flex-grow-1 d-flex flex-column gap-2">

                                        <div class="d-flex flex-wrap align-items-center gap-4">

                                            {{-- TYPE --}}
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-arrow-right-circle text-secondary me-2"></i>
                                                <span class="text-muted">Type :</span>
                                                <span class="ms-2 fw-semibold {{ $op?->type_demande }}">
                                                    {{ $op?->type_demande }}
                                                </span>
                                            </div>

                                            {{-- DATE --}}
                                            @php
                                                $dates = $op->commissionagrements->pluck('fin_commission')->filter();
                                            @endphp

                                            @if ($dates->isNotEmpty())
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-calendar-check text-primary me-2"></i>
                                                    <span class="text-muted">Commission :</span>
                                                    <span class="ms-2 text-primary fw-medium">
                                                        {{ $dates->map(fn($d) => \Carbon\Carbon::parse($d)->format('d/m/Y'))->implode(' - ') }}
                                                    </span>
                                                </div>
                                            @endif

                                        </div>

                                    </div>

                                    @can('voir-statut-agrement')
                                        {{-- ================= RIGHT ================= --}}
                                        <div class="d-flex align-items-start flex-wrap gap-3">

                                            {{-- STATUT --}}
                                            <div class="d-flex align-items-center">
                                                <span class="text-muted me-2">Statut : </span>
                                                <span
                                                    class="badge {{ $op?->statut_agrement }} px-3 py-2 rounded-pill shadow-sm">
                                                    {{ $op?->statut_agrement }}
                                                </span>
                                            </div>

                                            {{-- VALIDATIONS --}}
                                            @php
                                                $validations = $op?->validationoperateurs?->sortByDesc('created_at');
                                            @endphp

                                            <div class="dropdown">
                                                <a class="d-inline-flex align-items-center text-decoration-none" href="#"
                                                    data-bs-toggle="dropdown">
                                                    <i class="bi bi-chat-left-text me-2"></i>
                                                    <span class="badge bg-success">
                                                        {{ $validations->count() }}
                                                    </span>
                                                </a>

                                                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                                    <li class="dropdown-header">
                                                        {{ $validations->count() }} validation(s)
                                                    </li>

                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>

                                                    @forelse ($validations->take(3) as $v)
                                                        <li class="px-3 py-2 small">
                                                            <div class="{{ $v->action }}">{{ $v->action }}</div>

                                                            @can('show-observations')
                                                                <div class="text-muted">
                                                                    {{ $v->user->firstname }} {{ $v->user->name }}
                                                                </div>
                                                            @endcan

                                                            <div class="text-muted">
                                                                {{ $v->created_at->diffForHumans() }}
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <hr class="dropdown-divider">
                                                        </li>
                                                    @empty
                                                        <li class="px-3 py-2 text-muted small">
                                                            Aucune validation
                                                        </li>
                                                    @endforelse

                                                    <li class="text-center py-2">
                                                        <form action="{{ route('validationmessageop') }}" method="post"
                                                            target="_blank">
                                                            @csrf
                                                            <input type="hidden" name="id" value="{{ $op?->id }}">
                                                            <button class="btn btn-sm btn-light">
                                                                Voir plus
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>

                                            {{-- ACTIONS --}}
                                            @can('devenir-operateur-agrement-ouvert')
                                                @can('devenir-operateur-agrement-create')
                                                    @can('agrement-ouvert')
                                                        @if ($op->commissionagrements->isNotEmpty() && $showButton)
                                                            <div style="min-width: 240px; max-width: 280px;">

                                                                @if ($op->est_expire)
                                                                    <div class="alert alert-danger p-2 mb-0 shadow-sm">
                                                                        <button class="btn btn-success btn-sm w-100 mb-1"
                                                                            data-bs-toggle="modal" data-bs-target="#AddoperateurModal">
                                                                            <i class="bi bi-arrow-repeat"></i>
                                                                            Nouvelle demande
                                                                        </button>

                                                                        <small>
                                                                            Expiré le
                                                                            <strong>{{ $op->date_expiration?->format('d/m/Y') }}</strong>
                                                                        </small>
                                                                    </div>
                                                                @elseif ($op->est_sous_reserve || $op->est_rejete)
                                                                    <div class="alert alert-danger p-2 mb-0 shadow-sm">
                                                                        <button class="btn btn-success btn-sm w-100 mb-1"
                                                                            data-bs-toggle="modal" data-bs-target="#AddoperateurModalNew">
                                                                            <i class="bi bi-arrow-repeat"></i>
                                                                            Nouvelle demande
                                                                        </button>
                                                                    </div>
                                                                @elseif($op->est_renouvellement)
                                                                    <div class="alert alert-info p-2 mb-0 shadow-sm">
                                                                        <button class="btn btn-primary btn-sm w-100 mb-1"
                                                                            data-bs-toggle="modal" data-bs-target="#AddoperateurModal">
                                                                            <i class="bi bi-arrow-repeat"></i>
                                                                            Renouveler
                                                                        </button>

                                                                        <small>Agrément valide</small>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        @elseif ($showButton)
                                                            <div style="min-width: 240px; max-width: 280px;">

                                                                @if ($op->est_expire)
                                                                    <div class="alert alert-danger p-2 mb-0 shadow-sm">
                                                                        <button class="btn btn-success btn-sm w-100 mb-1"
                                                                            data-bs-toggle="modal" data-bs-target="#AddoperateurModal">
                                                                            <i class="bi bi-arrow-repeat"></i>
                                                                            Nouvelle demande
                                                                        </button>

                                                                        <small>
                                                                            Expiré le
                                                                            <strong>{{ $op->date_expiration?->format('d/m/Y') }}</strong>
                                                                        </small>
                                                                    </div>
                                                                @elseif ($op->est_sous_reserve || $op->est_rejete)
                                                                    <div class="alert alert-danger p-2 mb-0 shadow-sm">
                                                                        <button class="btn btn-success btn-sm w-100 mb-1"
                                                                            data-bs-toggle="modal" data-bs-target="#AddoperateurModalNew">
                                                                            <i class="bi bi-arrow-repeat"></i>
                                                                            Nouvelle demande
                                                                        </button>
                                                                    </div>
                                                                @elseif($op->est_renouvellement)
                                                                    <div class="alert alert-info p-2 mb-0 shadow-sm">
                                                                        <button class="btn btn-primary btn-sm w-100 mb-1"
                                                                            data-bs-toggle="modal" data-bs-target="#AddoperateurModal">
                                                                            <i class="bi bi-arrow-repeat"></i>
                                                                            Renouveler
                                                                        </button>

                                                                        <small>Agrément valide</small>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    @endcan
                                                @endcan
                                            @endcan

                                        </div>
                                    @endcan

                                </div>
                            </div>
                            <div class="card-body px-4">
                                @foreach ($sections as $section)
                                    <div
                                        class="d-flex justify-content-between align-items-center border-bottom py-1 position-relative">
                                        <div class="d-flex align-items-center">
                                            <i class="bi {{ $section['icon'] }} me-2"></i>
                                            {{ $section['label'] }}
                                            @if (isset($section['count']))
                                                <span
                                                    class="badge {{ $section['badge'] ?? ($section['count'] === 0 ? 'bg-danger' : 'bg-info') }} position-absolute top-50 start-50 translate-middle-y"
                                                    style="transform: translateX(-50%);">
                                                    {{ $section['count'] }}
                                                </span>
                                            @endif
                                        </div>
                                        <div>
                                            @if (
                                                !empty($section['route']) &&
                                                    in_array($op?->statut_agrement, [
                                                        'Nouveau',
                                                        'À corriger',
                                                        'rejeté',
                                                        'Non conforme',
                                                        'Conforme',
                                                        'Injoignable',
                                                        'Indisponible',
                                                        'Retiré',
                                                    ]))
                                                @can('voir-statut-agrement')
                                                    <a href="{{ $section['route'] }}" target="_blank"
                                                        class="btn btn-sm btn-outline-success">
                                                        <i class="bi bi-pencil-square me-1"></i> Ajouter / Modifier
                                                    </a>
                                                @endcan
                                            @elseif(!empty($section['modal']))
                                                <button class="btn btn-sm btn-outline-success" title="Modifier"
                                                    data-bs-toggle="modal" data-bs-target="#{{ $section['modal'] }}"
                                                    {{ $op?->statut_agrement === 'agréé' ? 'disabled' : '' }}>
                                                    <i class="bi bi-pencil-square me-1"></i> Ajouter / Modifier
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach

                                {{-- État demande --}}
                                <div
                                    class="d-flex justify-content-between align-items-center border-bottom py-1 position-relative">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-info-circle text-secondary me-2"></i>
                                        État de la demande
                                        <span
                                            class="badge {{ $statut_demande === 'incomplète' ? 'bg-danger' : 'bg-success' }} position-absolute top-50 start-50 translate-middle-y"
                                            style="transform: translateX(-50%);">
                                            {{ $statut_demande }}
                                        </span>
                                    </div>
                                    <div>
                                        <span
                                            class="badge {{ $estCertifie ? 'bg-success' : 'bg-danger' }} d-flex align-items-center">
                                            {!! $estCertifie
                                                ? '<i class="bi bi-check-circle me-1"></i> Dossier soumis'
                                                : '<i class="bi bi-x-circle me-1"></i> Dossier pas encore soumis' !!}
                                        </span>
                                    </div>
                                </div>

                                {{-- Certification --}}
                                <div
                                    class="d-flex flex-wrap justify-content-between align-items-center border-bottom py-2 gap-2">
                                    <div class="d-flex align-items-center gap-2 flex-grow-1 flex-wrap">
                                        <i class="bi bi-bookmark-check text-primary"></i>
                                        <span class="fw-semibold">Certifier pour soumettre</span>
                                    </div>

                                    <div class="flex-shrink-0">
                                        {{-- @if ($statut_demande === 'complète' && !in_array($op?->statut_agrement, ['agréé', 'rejeté', 'sous réserve'])) --}}
                                        @if ($statut_demande === 'complète')
                                            @if (isset($op->file8))
                                                <span
                                                    class="badge bg-info text-white d-inline-flex align-items-center px-2 py-1">
                                                    Demande déjà certifiée
                                                </span>
                                            @else
                                                <button type="button" class="btn btn-sm btn-outline-primary me-1"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#certificationModal{{ $op->id }}">
                                                    <i class="bi bi-pencil-square me-1"></i> Cliquez ici pour certifier et
                                                    soumettre votre
                                                    dossier
                                                </button>
                                            @endif
                                        @else
                                            <span
                                                class="badge bg-warning text-dark d-inline-flex align-items-center px-2 py-1">
                                                <i class="bi bi-exclamation-triangle-fill me-1"></i> Demande incomplète
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            @can('update', $op)
                                <div
                                    class="card-footer bg-light text-center py-3 border-top d-flex justify-content-center gap-3">
                                    @can('devenir-operateur-agrement-delete')
                                        @can('delete', $op)
                                            <form action="{{ route('operateurs.destroy', $op) }}" method="post"
                                                class="d-inline-block show_confirm">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm px-4" title="Supprimer">
                                                    <i class="bi bi-trash me-1"></i> Supprimer
                                                </button>
                                            </form>
                                        @endcan
                                    @endcan
                                </div>
                            @endcan
                        </div>
                    @endforeach

                    @include('operateurs.files-uploads')

                </div>
            </div>
        </div>

        {{-- Fichiers --}}
        @include('operateurs.files')

        {{-- Modals --}}
        @include('operateurs.modals')
    </section>
@endsection
