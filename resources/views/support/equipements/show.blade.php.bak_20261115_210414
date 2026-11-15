@extends('layout.user-layout')

@section('title', 'ONFP | Fiche équipement')

@section('space-work')
<div class="container-fluid py-3">

    {{-- EN-TÊTE --}}
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
        <div>
            <a href="{{ route('support.equipements.index') }}"
               class="text-decoration-none text-muted">
                <i class="bi bi-arrow-left me-1"></i>
                Retour au parc informatique
            </a>

            <h3 class="fw-bold mt-2 mb-1">{{ $equipement->designation }}</h3>
            <div class="text-muted">
                Référence : <strong>{{ $equipement->reference }}</strong>
            </div>
        </div>

        <div class="d-flex flex-wrap gap-2">
            @if($peutModifier)
                <a href="{{ route('support.equipements.edit', $equipement) }}"
                   class="btn btn-outline-secondary">
                    <i class="bi bi-pencil me-1"></i> Modifier
                </a>
            @endif

            @if($peutReformer && !in_array($equipement->statut, ['REFORME', 'PERDU'], true))
                <button class="btn btn-outline-danger"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#formReforme">
                    <i class="bi bi-archive me-1"></i> Réformer
                </button>
            @endif
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
            <strong>Une erreur est survenue :</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $erreur)
                    <li>{{ $erreur }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- FORMULAIRE DE REFORME --}}
    @if($peutReformer && !in_array($equipement->statut, ['REFORME', 'PERDU'], true))
        <div class="collapse mb-4" id="formReforme">
            <div class="card border-danger shadow-sm">
                <div class="card-header bg-danger text-white fw-bold">
                    Confirmation de réforme
                </div>
                <div class="card-body">
                    <p>
                        Cette opération fera passer l'équipement au statut
                        <strong>Réformé</strong>. Elle n'est pas réversible
                        depuis cette fiche.
                    </p>

                    <form method="POST"
                          action="{{ route('support.equipements.reformer', $equipement) }}"
                          onsubmit="return confirm('Confirmer la réforme de cet équipement ?');">
                        @csrf
                        @method('PATCH')

                        <div class="mb-3">
                            <label for="motif_reforme" class="form-label">
                                Motif de réforme
                            </label>
                            <textarea name="motif" id="motif_reforme"
                                      class="form-control" rows="2"
                                      maxlength="1000"></textarea>
                        </div>

                        <button type="submit" class="btn btn-danger">
                            Confirmer la réforme
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- SITUATION GENERALE --}}
    @php
        $statutsLibelles = [
            'EN_STOCK' => 'En stock',
            'AFFECTE' => 'Affecté',
            'EN_MAINTENANCE' => 'En maintenance',
            'REFORME' => 'Réformé',
            'PERDU' => 'Perdu',
        ];

        $etatsLibelles = [
            'BON' => 'Bon',
            'MOYEN' => 'Moyen',
            'MAUVAIS' => 'Mauvais',
            'HORS_SERVICE' => 'Hors service',
        ];

        $statutCouleurs = [
            'EN_STOCK' => 'success',
            'AFFECTE' => 'primary',
            'EN_MAINTENANCE' => 'warning',
            'REFORME' => 'secondary',
            'PERDU' => 'danger',
        ];

        $affectationActive = $equipement->affectationActive;
        $peutFaireAffectation = $peutAffecter
            && $equipement->statut === 'EN_STOCK'
            && !$affectationActive
            && $equipement->etat_physique !== 'HORS_SERVICE';

        $peutFaireRetour = $peutAffecter
            && $equipement->statut === 'AFFECTE'
            && $affectationActive;

        $peutFaireTransfert = $peutTransferer
            && $equipement->statut === 'AFFECTE'
            && $affectationActive;
    @endphp

    <div class="row g-3 mb-4">

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small mb-2">Statut opérationnel</div>
                    <span class="badge bg-{{ $statutCouleurs[$equipement->statut] ?? 'secondary' }} fs-6">
                        {{ $statutsLibelles[$equipement->statut] ?? $equipement->statut }}
                    </span>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small mb-2">État physique</div>
                    <strong>
                        {{ $etatsLibelles[$equipement->etat_physique] ?? $equipement->etat_physique }}
                    </strong>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small mb-2">Catégorie</div>
                    <strong>{{ $equipement->categorie?->libelle ?? 'Non renseignée' }}</strong>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small mb-2">Emplacement</div>
                    <strong>{{ $equipement->emplacement ?: 'Non renseigné' }}</strong>
                </div>
            </div>
        </div>

    </div>

    <div class="row g-4">

        {{-- COLONNE PRINCIPALE --}}
        <div class="col-lg-8">

            {{-- IDENTIFICATION --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0">
                        <i class="bi bi-pc-display me-2" style="color:#F28500"></i>
                        Identification et caractéristiques
                    </h5>
                </div>

                <div class="card-body">
                    <div class="row g-3">

                        <div class="col-md-6">
                            <div class="text-muted small">Référence</div>
                            <strong>{{ $equipement->reference }}</strong>
                        </div>

                        <div class="col-md-6">
                            <div class="text-muted small">Désignation</div>
                            <strong>{{ $equipement->designation }}</strong>
                        </div>

                        <div class="col-md-4">
                            <div class="text-muted small">Marque</div>
                            {{ $equipement->marque ?: '—' }}
                        </div>

                        <div class="col-md-4">
                            <div class="text-muted small">Modèle</div>
                            {{ $equipement->modele ?: '—' }}
                        </div>

                        <div class="col-md-4">
                            <div class="text-muted small">Numéro de série</div>
                            {{ $equipement->numero_serie ?: '—' }}
                        </div>

                        <div class="col-md-4">
                            <div class="text-muted small">Date d'acquisition</div>
                            {{ $equipement->date_acquisition?->format('d/m/Y') ?? '—' }}
                        </div>

                        <div class="col-md-4">
                            <div class="text-muted small">Fournisseur</div>
                            {{ $equipement->fournisseur ?: '—' }}
                        </div>

                        <div class="col-md-4">
                            <div class="text-muted small">Prix d'acquisition</div>
                            {{ $equipement->prix_acquisition !== null
                                ? number_format((float) $equipement->prix_acquisition, 0, ',', ' ') . ' FCFA'
                                : '—' }}
                        </div>

                        <div class="col-md-4">
                            <div class="text-muted small">Fin de garantie</div>
                            {{ $equipement->date_fin_garantie?->format('d/m/Y') ?? '—' }}
                        </div>

                        <div class="col-12">
                            <div class="text-muted small">Description technique</div>
                            <div class="mt-1">
                                {!! $equipement->description
                                    ? nl2br(e($equipement->description))
                                    : '<span class="text-muted">Aucune description</span>' !!}
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="text-muted small">Observations</div>
                            <div class="mt-1">
                                {!! $equipement->observations
                                    ? nl2br(e($equipement->observations))
                                    : '<span class="text-muted">Aucune observation</span>' !!}
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- AFFECTATION ACTIVE --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0">
                        <i class="bi bi-person-check me-2" style="color:#F28500"></i>
                        Détenteur actuel
                    </h5>
                </div>

                <div class="card-body">
                    @if($affectationActive)
                        @php
                            $cibleActuelle = $affectationActive->employee
                                ? trim(($affectationActive->employee->user?->firstname ?? '') . ' ' .
                                       ($affectationActive->employee->user?->name ?? ''))
                                : ($affectationActive->direction?->name
                                    ?? $affectationActive->antenne?->name
                                    ?? 'Destinataire non renseigné');
                        @endphp

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="text-muted small">Destinataire</div>
                                <strong>{{ $cibleActuelle }}</strong>

                                @if($affectationActive->employee?->matricule)
                                    <div class="text-muted small">
                                        Matricule : {{ $affectationActive->employee->matricule }}
                                    </div>
                                @endif
                            </div>

                            <div class="col-md-6">
                                <div class="text-muted small">Type de destinataire</div>
                                <strong>
                                    @if($affectationActive->employee)
                                        Employé
                                    @elseif($affectationActive->direction)
                                        Direction
                                    @elseif($affectationActive->antenne)
                                        Antenne
                                    @else
                                        Non renseigné
                                    @endif
                                </strong>
                            </div>

                            @if($affectationActive->employee?->direction)
                                <div class="col-md-6">
                                    <div class="text-muted small">Direction de l'employé</div>
                                    {{ $affectationActive->employee->direction->name ?? '—' }}
                                </div>
                            @endif

                            <div class="col-md-6">
                                <div class="text-muted small">Date d'affectation</div>
                                {{ $affectationActive->date_debut?->format('d/m/Y à H:i') ?? '—' }}
                            </div>

                            <div class="col-md-6">
                                <div class="text-muted small">État à la remise</div>
                                {{ $etatsLibelles[$affectationActive->etat_remise] ?? $affectationActive->etat_remise ?? '—' }}
                            </div>

                            @if($affectationActive->observations)
                                <div class="col-12">
                                    <div class="text-muted small">Observations</div>
                                    {{ $affectationActive->observations }}
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="text-muted">
                            <i class="bi bi-inbox me-2"></i>
                            Cet équipement n'a actuellement aucun détenteur.
                        </div>
                    @endif
                </div>
            </div>

            {{-- OPERATIONS : AFFECTATION / RETOUR / TRANSFERT --}}
            @if($peutFaireAffectation || $peutFaireRetour || $peutFaireTransfert)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="fw-bold mb-0">
                            <i class="bi bi-arrow-left-right me-2" style="color:#F28500"></i>
                            Opérations sur l'équipement
                        </h5>
                    </div>

                    <div class="card-body">

                        {{-- AFFECTER --}}
                        @if($peutFaireAffectation)
                            <div class="mb-4">
                                <h6 class="fw-bold">Nouvelle affectation</h6>

                                <form method="POST"
                                      action="{{ route('support.equipements.affecter', $equipement) }}">
                                    @csrf

                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label class="form-label">Affecter à</label>
                                            <select class="form-select destinataire-select"
                                                    name="type_destinataire"
                                                    data-target="affectation"
                                                    required>
                                                <option value="">Choisir...</option>
                                                <option value="employee">Un employé</option>
                                                <option value="direction">Une direction</option>
                                                <option value="antenne">Une antenne</option>
                                            </select>
                                        </div>

                                        <div class="col-md-8 destinataire-field"
                                             id="affectation_employee" style="display:none">
                                            <label class="form-label">Employé</label>
                                            <select class="form-select"
                                                    name="employee_id">
                                                <option value="">Sélectionner un employé</option>
                                                @foreach($employes as $employe)
                                                    <option value="{{ $employe->id }}">
                                                        {{ $employe->matricule ?: 'Sans matricule' }}
                                                        —
                                                        {{ trim(($employe->user?->firstname ?? '') . ' ' .
                                                            ($employe->user?->name ?? '')) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-8 destinataire-field"
                                             id="affectation_direction" style="display:none">
                                            <label class="form-label">Direction</label>
                                            <select class="form-select" name="direction_id">
                                                <option value="">Sélectionner une direction</option>
                                                @foreach($directions as $direction)
                                                    <option value="{{ $direction->id }}">
                                                        {{ $direction->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-8 destinataire-field"
                                             id="affectation_antenne" style="display:none">
                                            <label class="form-label">Antenne</label>
                                            <select class="form-select" name="antenne_id">
                                                <option value="">Sélectionner une antenne</option>
                                                @foreach($antennes as $antenne)
                                                    <option value="{{ $antenne->id }}">
                                                        {{ $antenne->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">État à la remise</label>
                                            <select name="etat_remise" class="form-select">
                                                @foreach($etatsLibelles as $code => $libelle)
                                                    <option value="{{ $code }}"
                                                        @selected($code === $equipement->etat_physique)>
                                                        {{ $libelle }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-8">
                                            <label class="form-label">Motif</label>
                                            <input type="text" name="motif"
                                                   class="form-control"
                                                   maxlength="255"
                                                   value="Affectation initiale">
                                        </div>

                                        <div class="col-12">
                                            <label class="form-label">Observations</label>
                                            <textarea name="observations"
                                                      class="form-control"
                                                      rows="2"></textarea>
                                        </div>

                                        <div class="col-12">
                                            <button type="submit"
                                                    class="btn text-white"
                                                    style="background:#F28500">
                                                <i class="bi bi-person-plus me-1"></i>
                                                Confirmer l'affectation
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @endif

                        {{-- RETOUR --}}
                        @if($peutFaireRetour)
                            <hr>
                            <div class="mb-4">
                                <h6 class="fw-bold">Retour au parc informatique</h6>

                                <form method="POST"
                                      action="{{ route('support.equipements.retourner', $equipement) }}"
                                      onsubmit="return confirm('Confirmer le retour de cet équipement au parc ?');">
                                    @csrf
                                    @method('PATCH')

                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label class="form-label">État au retour</label>
                                            <select name="etat_retour" class="form-select" required>
                                                @foreach($etatsLibelles as $code => $libelle)
                                                    <option value="{{ $code }}"
                                                        @selected($code === $equipement->etat_physique)>
                                                        {{ $libelle }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-8">
                                            <label class="form-label">Motif du retour</label>
                                            <input type="text" name="motif_retour"
                                                   class="form-control" maxlength="255"
                                                   placeholder="Ex. Fin d'affectation, remplacement...">
                                        </div>

                                        <div class="col-12">
                                            <label class="form-label">Observations</label>
                                            <textarea name="observations"
                                                      class="form-control" rows="2"></textarea>
                                        </div>

                                        <div class="col-12">
                                            <button type="submit" class="btn btn-outline-primary">
                                                <i class="bi bi-box-arrow-in-left me-1"></i>
                                                Enregistrer le retour
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @endif

                        {{-- TRANSFERT --}}
                        @if($peutFaireTransfert)
                            <hr>
                            <div>
                                <h6 class="fw-bold">Transférer vers un autre destinataire</h6>

                                <form method="POST"
                                      action="{{ route('support.equipements.transferer', $equipement) }}"
                                      onsubmit="return confirm('Confirmer le transfert de cet équipement ?');">
                                    @csrf

                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label class="form-label">Nouveau destinataire</label>
                                            <select class="form-select destinataire-select"
                                                    name="type_destinataire"
                                                    data-target="transfert"
                                                    required>
                                                <option value="">Choisir...</option>
                                                <option value="employee">Un employé</option>
                                                <option value="direction">Une direction</option>
                                                <option value="antenne">Une antenne</option>
                                            </select>
                                        </div>

                                        <div class="col-md-8 destinataire-field"
                                             id="transfert_employee" style="display:none">
                                            <label class="form-label">Nouvel employé</label>
                                            <select class="form-select" name="employee_id">
                                                <option value="">Sélectionner un employé</option>
                                                @foreach($employes as $employe)
                                                    <option value="{{ $employe->id }}">
                                                        {{ $employe->matricule ?: 'Sans matricule' }}
                                                        —
                                                        {{ trim(($employe->user?->firstname ?? '') . ' ' .
                                                            ($employe->user?->name ?? '')) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-8 destinataire-field"
                                             id="transfert_direction" style="display:none">
                                            <label class="form-label">Nouvelle direction</label>
                                            <select class="form-select" name="direction_id">
                                                <option value="">Sélectionner une direction</option>
                                                @foreach($directions as $direction)
                                                    <option value="{{ $direction->id }}">
                                                        {{ $direction->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-8 destinataire-field"
                                             id="transfert_antenne" style="display:none">
                                            <label class="form-label">Nouvelle antenne</label>
                                            <select class="form-select" name="antenne_id">
                                                <option value="">Sélectionner une antenne</option>
                                                @foreach($antennes as $antenne)
                                                    <option value="{{ $antenne->id }}">
                                                        {{ $antenne->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">État au départ</label>
                                            <select name="etat_retour" class="form-select">
                                                @foreach($etatsLibelles as $code => $libelle)
                                                    <option value="{{ $code }}"
                                                        @selected($code === $equipement->etat_physique)>
                                                        {{ $libelle }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">État à la remise</label>
                                            <select name="etat_remise" class="form-select">
                                                @foreach($etatsLibelles as $code => $libelle)
                                                    <option value="{{ $code }}"
                                                        @selected($code === $equipement->etat_physique)>
                                                        {{ $libelle }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-8">
                                            <label class="form-label">Motif du transfert</label>
                                            <input type="text" name="motif"
                                                   class="form-control" maxlength="255"
                                                   value="Transfert d'équipement">
                                        </div>

                                        <div class="col-12">
                                            <label class="form-label">Observations</label>
                                            <textarea name="observations"
                                                      class="form-control" rows="2"></textarea>
                                        </div>

                                        <div class="col-12">
                                            <button type="submit" class="btn btn-outline-primary">
                                                <i class="bi bi-arrow-left-right me-1"></i>
                                                Confirmer le transfert
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @endif

                    </div>
                </div>
            @endif

            {{-- HISTORIQUE DES AFFECTATIONS --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0">
                        <i class="bi bi-clock-history me-2" style="color:#F28500"></i>
                        Historique des affectations
                    </h5>
                </div>

                <div class="card-body">
                    @forelse($equipement->affectations as $affectation)
                        @php
                            $nomDestinataire = $affectation->employee
                                ? trim(($affectation->employee->user?->firstname ?? '') . ' ' .
                                       ($affectation->employee->user?->name ?? ''))
                                : ($affectation->direction?->name
                                    ?? $affectation->antenne?->name
                                    ?? 'Destinataire non renseigné');
                        @endphp

                        <div class="border-start border-3 ps-3 pb-3 mb-3"
                             style="border-color:#F28500 !important">
                            <div class="d-flex justify-content-between flex-wrap gap-2">
                                <strong>{{ $nomDestinataire }}</strong>

                                @if($affectation->date_fin)
                                    <span class="badge bg-secondary">Terminée</span>
                                @else
                                    <span class="badge bg-success">En cours</span>
                                @endif
                            </div>

                            <div class="text-muted small mt-1">
                                Du {{ $affectation->date_debut?->format('d/m/Y H:i') ?? '—' }}
                                au {{ $affectation->date_fin?->format('d/m/Y H:i') ?? 'Aujourd'hui' }}
                            </div>

                            @if($affectation->employee?->matricule)
                                <div class="small">
                                    Matricule : {{ $affectation->employee->matricule }}
                                </div>
                            @endif

                            <div class="small mt-1">
                                État à la remise :
                                {{ $etatsLibelles[$affectation->etat_remise] ?? $affectation->etat_remise ?? '—' }}
                                @if($affectation->etat_retour)
                                    — État au retour :
                                    {{ $etatsLibelles[$affectation->etat_retour] ?? $affectation->etat_retour }}
                                @endif
                            </div>

                            @if($affectation->motif_retour)
                                <div class="small text-muted">
                                    Motif de fin : {{ $affectation->motif_retour }}
                                </div>
                            @endif

                            @if($affectation->observations)
                                <div class="small text-muted">
                                    {{ $affectation->observations }}
                                </div>
                            @endif
                        </div>
                    @empty
                        <p class="text-muted mb-0">
                            Aucun historique d'affectation disponible.
                        </p>
                    @endforelse
                </div>
            </div>

            {{-- MOUVEMENTS --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0">
                        <i class="bi bi-arrow-left-right me-2" style="color:#F28500"></i>
                        Historique des mouvements
                    </h5>
                </div>

                <div class="card-body">
                    @forelse($equipement->mouvements as $mouvement)
                        @php
                            $libellesMouvements = [
                                'AFFECTATION' => 'Affectation',
                                'RETOUR' => 'Retour au parc',
                                'TRANSFERT' => 'Transfert',
                            ];
                        @endphp

                        <div class="border-bottom pb-3 mb-3">
                            <div class="d-flex justify-content-between flex-wrap gap-2">
                                <strong>
                                    {{ $libellesMouvements[$mouvement->type_mouvement] ?? $mouvement->type_mouvement }}
                                </strong>
                                <span class="text-muted small">
                                    {{ $mouvement->date_mouvement?->format('d/m/Y à H:i') ?? '—' }}
                                </span>
                            </div>

                            @if($mouvement->affectationOrigine)
                                <div class="small mt-1">
                                    Origine :
                                    @if($mouvement->affectationOrigine->employee)
                                        {{ trim(($mouvement->affectationOrigine->employee->user?->firstname ?? '') . ' ' .
                                            ($mouvement->affectationOrigine->employee->user?->name ?? '')) }}
                                    @elseif($mouvement->affectationOrigine->direction)
                                        {{ $mouvement->affectationOrigine->direction->name }}
                                    @elseif($mouvement->affectationOrigine->antenne)
                                        {{ $mouvement->affectationOrigine->antenne->name }}
                                    @else
                                        Non renseignée
                                    @endif
                                </div>
                            @endif

                            @if($mouvement->affectationDestination)
                                <div class="small">
                                    Destination :
                                    @if($mouvement->affectationDestination->employee)
                                        {{ trim(($mouvement->affectationDestination->employee->user?->firstname ?? '') . ' ' .
                                            ($mouvement->affectationDestination->employee->user?->name ?? '')) }}
                                    @elseif($mouvement->affectationDestination->direction)
                                        {{ $mouvement->affectationDestination->direction->name }}
                                    @elseif($mouvement->affectationDestination->antenne)
                                        {{ $mouvement->affectationDestination->antenne->name }}
                                    @else
                                        Non renseignée
                                    @endif
                                </div>
                            @endif

                            @if($mouvement->motif)
                                <div class="small text-muted mt-1">
                                    Motif : {{ $mouvement->motif }}
                                </div>
                            @endif

                            @if($mouvement->observations)
                                <div class="small text-muted">
                                    {{ $mouvement->observations }}
                                </div>
                            @endif

                            @if($mouvement->createur)
                                <div class="text-muted small mt-1">
                                    Enregistré par :
                                    {{ $mouvement->createur->firstname }}
                                    {{ $mouvement->createur->name }}
                                </div>
                            @endif
                        </div>
                    @empty
                        <p class="text-muted mb-0">
                            Aucun mouvement enregistré.
                        </p>
                    @endforelse
                </div>
            </div>

            {{-- HISTORIQUE TECHNIQUE --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0">
                        <i class="bi bi-journal-text me-2" style="color:#F28500"></i>
                        Historique technique
                    </h5>
                </div>

                <div class="card-body">
                    @forelse($equipement->historiques as $historique)
                        <div class="border-bottom pb-3 mb-3">
                            <div class="d-flex justify-content-between flex-wrap gap-2">
                                <strong>{{ str_replace('_', ' ', $historique->action) }}</strong>
                                <span class="text-muted small">
                                    {{ $historique->created_at?->format('d/m/Y à H:i') }}
                                </span>
                            </div>

                            @if($historique->description)
                                <div class="mt-1">{{ $historique->description }}</div>
                            @endif

                            @if($historique->champ)
                                <div class="small text-muted">
                                    Champ concerné : {{ $historique->champ }}
                                </div>
                            @endif

                            @if($historique->user)
                                <div class="small text-muted">
                                    Par {{ $historique->user->firstname }}
                                    {{ $historique->user->name }}
                                </div>
                            @endif
                        </div>
                    @empty
                        <p class="text-muted mb-0">
                            Aucun historique technique disponible.
                        </p>
                    @endforelse
                </div>
            </div>

        </div>

        {{-- COLONNE LATERALE --}}
        <div class="col-lg-4">

            {{-- INFORMATIONS ADMINISTRATIVES --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0">Informations administratives</h5>
                </div>

                <div class="card-body">
                    <div class="mb-3">
                        <div class="text-muted small">Créé par</div>
                        <strong>
                            {{ $equipement->createur
                                ? $equipement->createur->firstname . ' ' . $equipement->createur->name
                                : '—' }}
                        </strong>
                    </div>

                    <div class="mb-3">
                        <div class="text-muted small">Date de création</div>
                        {{ $equipement->created_at?->format('d/m/Y à H:i') ?? '—' }}
                    </div>

                    <div class="mb-3">
                        <div class="text-muted small">Dernière modification</div>
                        {{ $equipement->updated_at?->format('d/m/Y à H:i') ?? '—' }}
                    </div>

                    <div>
                        <div class="text-muted small">Modifié par</div>
                        <strong>
                            {{ $equipement->modificateur
                                ? $equipement->modificateur->firstname . ' ' . $equipement->modificateur->name
                                : '—' }}
                        </strong>
                    </div>
                </div>
            </div>

            {{-- AIDE --}}
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="fw-bold">
                        <i class="bi bi-info-circle me-2" style="color:#F28500"></i>
                        Règles de gestion
                    </h6>

                    <ul class="small text-muted mb-0 ps-3">
                        <li>Un équipement ne peut avoir qu'une seule affectation active.</li>
                        <li>Une affectation concerne un employé, une direction ou une antenne.</li>
                        <li>Un transfert clôture l'affectation précédente et en crée une nouvelle.</li>
                        <li>Un retour remet l'équipement dans le parc ou en maintenance selon son état.</li>
                        <li>La réforme est une opération distincte de la suppression.</li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- GESTION DES DESTINATAIRES --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.destinataire-select').forEach(function (select) {
        const cible = select.dataset.target;

        function actualiserChamps() {
            ['employee', 'direction', 'antenne'].forEach(function (type) {
                const bloc = document.getElementById(cible + '_' + type);

                if (!bloc) return;

                const actif = select.value === type;
                bloc.style.display = actif ? '' : 'none';

                const champ = bloc.querySelector('select');

                if (champ) {
                    champ.disabled = !actif;
                    champ.required = actif;
                    if (!actif) champ.value = '';
                }
            });
        }

        select.addEventListener('change', actualiserChamps);
        actualiserChamps();
    });
});
</script>
@endsection
