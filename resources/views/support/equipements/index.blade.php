@extends('layout.user-layout')

@section('title', 'ONFP | Parc informatique')

@section('space-work')
<div class="container-fluid py-3">

    {{-- EN-TÊTE --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <h3 class="fw-bold mb-1">Parc informatique</h3>
            <p class="text-muted mb-0">
                Gestion et suivi des équipements informatiques de l'ONFP.
            </p>
        </div>

        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('support.dashboard') }}"
               class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Support informatique
            </a>

            @can('support.equipements.create')
                <a href="{{ route('support.equipements.create') }}"
                   class="btn btn-sm text-white"
                   style="background:#F28500">
                    <i class="bi bi-plus-circle me-1"></i>
                    Nouvel équipement
                </a>
            @endcan
        </div>
    </div>

    {{-- MESSAGES --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle me-2"></i>
            {{ $errors->first() }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- INDICATEURS --}}
    @php
        $indicateurs = [
            [
                'libelle' => 'Total équipements',
                'valeur' => $stats['total'],
                'icone' => 'bi-pc-display',
                'couleur' => '#0d6efd',
            ],
            [
                'libelle' => 'En stock',
                'valeur' => $stats['en_stock'],
                'icone' => 'bi-box-seam',
                'couleur' => '#198754',
            ],
            [
                'libelle' => 'Affectés',
                'valeur' => $stats['affectes'],
                'icone' => 'bi-person-check',
                'couleur' => '#F28500',
            ],
            [
                'libelle' => 'En maintenance',
                'valeur' => $stats['en_maintenance'],
                'icone' => 'bi-tools',
                'couleur' => '#6f42c1',
            ],
            [
                'libelle' => 'Réformés',
                'valeur' => $stats['reformes'],
                'icone' => 'bi-archive',
                'couleur' => '#6c757d',
            ],
            [
                'libelle' => 'Perdus',
                'valeur' => $stats['perdus'],
                'icone' => 'bi-exclamation-triangle',
                'couleur' => '#dc3545',
            ],
        ];
    @endphp

    <div class="row g-3 mb-4">
        @foreach($indicateurs as $indicateur)
            <div class="col-6 col-md-4 col-xl-2">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1">
                                {{ $indicateur['libelle'] }}
                            </p>
                            <h3 class="fw-bold mb-0">
                                {{ $indicateur['valeur'] }}
                            </h3>
                        </div>

                        <div class="rounded-circle p-3"
                             style="background:{{ $indicateur['couleur'] }}18;
                                    color:{{ $indicateur['couleur'] }}">
                            <i class="bi {{ $indicateur['icone'] }} fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- FILTRES --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="fw-bold mb-0">
                <i class="bi bi-funnel me-2" style="color:#F28500"></i>
                Recherche et filtres
            </h5>
        </div>

        <div class="card-body">
            <form method="GET" action="{{ route('support.equipements.index') }}">
                <div class="row g-3 align-items-end">

                    <div class="col-lg-4 col-md-6">
                        <label class="form-label">Recherche</label>
                        <input type="text"
                               name="recherche"
                               class="form-control form-control-sm"
                               value="{{ request('recherche') }}"
                               placeholder="Référence, désignation, marque, modèle, série...">
                    </div>

                    <div class="col-lg-2 col-md-6">
                        <label class="form-label">Catégorie</label>
                        <select name="categorie" class="form-select form-select-sm">
                            <option value="">Toutes les catégories</option>
                            @foreach($categories as $categorie)
                                <option value="{{ $categorie->id }}"
                                    @selected(request('categorie') == $categorie->id)>
                                    {{ $categorie->libelle }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-2 col-md-4">
                        <label class="form-label">Statut</label>
                        <select name="statut" class="form-select form-select-sm">
                            <option value="">Tous les statuts</option>
                            <option value="EN_STOCK" @selected(request('statut') === 'EN_STOCK')>
                                En stock
                            </option>
                            <option value="AFFECTE" @selected(request('statut') === 'AFFECTE')>
                                Affecté
                            </option>
                            <option value="EN_MAINTENANCE" @selected(request('statut') === 'EN_MAINTENANCE')>
                                En maintenance
                            </option>
                            <option value="REFORME" @selected(request('statut') === 'REFORME')>
                                Réformé
                            </option>
                            <option value="PERDU" @selected(request('statut') === 'PERDU')>
                                Perdu
                            </option>
                        </select>
                    </div>

                    <div class="col-lg-2 col-md-4">
                        <label class="form-label">État physique</label>
                        <select name="etat_physique" class="form-select form-select-sm">
                            <option value="">Tous les états</option>
                            <option value="BON" @selected(request('etat_physique') === 'BON')>
                                Bon
                            </option>
                            <option value="MOYEN" @selected(request('etat_physique') === 'MOYEN')>
                                Moyen
                            </option>
                            <option value="MAUVAIS" @selected(request('etat_physique') === 'MAUVAIS')>
                                Mauvais
                            </option>
                            <option value="HORS_SERVICE" @selected(request('etat_physique') === 'HORS_SERVICE')>
                                Hors service
                            </option>
                        </select>
                    </div>

                    <div class="col-lg-2 col-md-4 d-flex gap-2">
                        <button type="submit" class="btn btn-sm btn-primary flex-fill">
                            <i class="bi bi-search me-1"></i> Filtrer
                        </button>

                        <a href="{{ route('support.equipements.index') }}"
                           class="btn btn-sm btn-outline-secondary"
                           title="Réinitialiser les filtres">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    </div>

                </div>
            </form>
        </div>
    </div>

    {{-- TABLEAU DES ÉQUIPEMENTS --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="fw-bold mb-0">
                Équipements enregistrés
                <span class="badge bg-secondary ms-1">
                    {{ $equipements->total() }}
                </span>
            </h5>

            <small class="text-muted">
                {{ $equipements->firstItem() ?? 0 }}
                à
                {{ $equipements->lastItem() ?? 0 }}
                sur {{ $equipements->total() }}
            </small>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">
                    <tr>
                        <th>Référence</th>
                        <th>Équipement</th>
                        <th>Catégorie</th>
                        <th>Numéro de série</th>
                        <th>État physique</th>
                        <th>Statut</th>
                        <th>Détenteur / Emplacement</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($equipements as $equipement)

                        @php
                            $affectation = $equipement->affectationActive;

                            $detenteur = '-';

                            if ($affectation) {
                                if ($affectation->employee) {
                                    $detenteur = trim(
                                        ($affectation->employee->user->firstname ?? '')
                                        . ' '
                                        . ($affectation->employee->user->name ?? '')
                                    );

                                    if (!$detenteur) {
                                        $detenteur = 'Employé : '
                                            . $affectation->employee->matricule;
                                    }
                                } elseif ($affectation->direction) {
                                    $detenteur = $affectation->direction->name
                                        ?? 'Direction';
                                } elseif ($affectation->antenne) {
                                    $detenteur = $affectation->antenne->name
                                        ?? 'Antenne';
                                }
                            } else {
                                $detenteur = $equipement->emplacement ?: 'Non renseigné';
                            }

                            $couleursStatut = [
                                'EN_STOCK' => 'success',
                                'AFFECTE' => 'primary',
                                'EN_MAINTENANCE' => 'warning',
                                'REFORME' => 'secondary',
                                'PERDU' => 'danger',
                            ];

                            $libellesStatut = [
                                'EN_STOCK' => 'En stock',
                                'AFFECTE' => 'Affecté',
                                'EN_MAINTENANCE' => 'En maintenance',
                                'REFORME' => 'Réformé',
                                'PERDU' => 'Perdu',
                            ];

                            $libellesEtat = [
                                'BON' => 'Bon',
                                'MOYEN' => 'Moyen',
                                'MAUVAIS' => 'Mauvais',
                                'HORS_SERVICE' => 'Hors service',
                            ];

                            $couleursEtat = [
                                'BON' => 'success',
                                'MOYEN' => 'warning',
                                'MAUVAIS' => 'danger',
                                'HORS_SERVICE' => 'dark',
                            ];
                        @endphp

                        <tr>
                            <td class="text-nowrap">
                                <strong>{{ $equipement->reference }}</strong>
                            </td>

                            <td>
                                <div class="fw-semibold">
                                    {{ $equipement->designation }}
                                </div>
                                <small class="text-muted">
                                    {{ $equipement->marque ?? '' }}
                                    {{ $equipement->modele ?? '' }}
                                </small>
                            </td>

                            <td>
                                {{ $equipement->categorie->libelle ?? '-' }}
                            </td>

                            <td class="text-nowrap">
                                {{ $equipement->numero_serie ?: '-' }}
                            </td>

                            <td>
                                <span class="badge bg-{{ $couleursEtat[$equipement->etat_physique] ?? 'secondary' }}">
                                    {{ $libellesEtat[$equipement->etat_physique] ?? $equipement->etat_physique }}
                                </span>
                            </td>

                            <td>
                                <span class="badge bg-{{ $couleursStatut[$equipement->statut] ?? 'secondary' }}">
                                    {{ $libellesStatut[$equipement->statut] ?? $equipement->statut }}
                                </span>
                            </td>

                            <td>
                                <span class="small">{{ $detenteur }}</span>
                            </td>

                            <td class="text-center">
                                <a href="{{ route('support.equipements.show', $equipement) }}"
                                   class="btn btn-sm btn-outline-primary"
                                   title="Consulter la fiche">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-5">
                                <i class="bi bi-pc-display fs-2 d-block mb-2"></i>
                                Aucun équipement ne correspond à votre recherche.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        <div class="card-body">
            {{ $equipements->links() }}
        </div>
    </div>

</div>
@endsection
