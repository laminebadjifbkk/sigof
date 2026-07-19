@extends('layouts.dashboard')

@section('title', 'Détail de la session')

@section('content')
<div class="dash-topbar">
    <div>
        <h2>{{ $session->nom }}</h2>
        <p class="muted-sub">{{ $session->date_debut->format('d/m/Y') .' - '. $session->date_fin->format('d/m/Y') }} · {{ $session->lieu ?? 'Lieu non défini' }}</p>
    </div>
    <div class="topbar-right">
        <span class="status-pill {{ $session->statut_classe }}">{{ $session->statut_label }}</span>
    </div>
</div>

@if (session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

@if (session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="panel">
    <h3>Informations générales</h3>
    <div class="detail-grid">
        <div class="detail-item">
            <span class="detail-label">Langue</span>
            <span class="detail-value">{{ $session->langueSpecialisation->nom ?? 'Non spécifiée' }}</span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Formateur</span>
            <span class="detail-value">{{ $session->formateur ?? '—' }}</span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Lieu</span>
            <span class="detail-value">{{ $session->lieu ?? '—' }}</span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Participants</span>
            <span class="detail-value">{{ $session->participants->count() }}</span>
        </div>
    </div>
    @if ($session->description)
    <p style="margin-top:16px;">{{ $session->description }}</p>
    @endif
</div>

<!-- <div class="panel">
    <h3>Traducteurs affectés</h3>
    <div class="table-responsive">
        <table class="data-table table align-middle">
            <thead>
                <tr>
                    <th>Candidat</th>
                    <th>Langue (LV1)</th>
                    <th>Statut formation</th>
                    <th>Attestation</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($session->participants as $participant)
                <tr>
                    <td>{{ $participant->candidature->user->firstname }} {{ $participant->candidature->user->name }}</td>
                    <td>{{ $participant->candidature->langueSpecialisation->nom }}</td>
                    <td><span class="status-pill {{ $participant->statut_formation_classe }}">{{ $participant->statut_formation_label }}</span></td>
                    <td>
                        @if ($participant->attestation_path)
                        <a href="{{ asset('storage/' . $participant->attestation_path) }}" target="_blank">Voir</a>
                        @else
                        -
                        @endif
                    </td>
                    <td>
                        <form action="{{ route('sessions-formation.retirer', [$session, $participant]) }}" method="POST"
                            onsubmit="return confirm('Retirer ce traducteur de la session ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-ghost btn-sm" style="color:#c0392b;">Retirer</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="empty-row">Aucun traducteur affecté à cette session pour le moment.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div> -->


<div class="panel">
    <h3>Traducteurs affectés</h3>
    <div class="table-responsive">
        <table class="data-table table align-middle">
            <thead>
                <tr>
                    <th>Candidat</th>
                    <th>Langue (LV1)</th>
                    <th>Statut formation</th>
                    <th>Évaluation</th>
                    <th>Attestation</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($session->participants as $participant)
                <tr>
                    <td>{{ $participant->candidature->user->firstname }} {{ $participant->candidature->user->name }}</td>
                    <td>{{ $participant->candidature->langueSpecialisation->nom }}</td>
                    <td><span class="status-pill {{ $participant->statut_formation_classe }}">{{ $participant->statut_formation_label }}</span></td>
                    <td>
                        @if ($participant->resultat_evaluation)
                        <span class="status-pill {{ $participant->resultat_evaluation_classe }}">
                            {{ $participant->resultat_evaluation_label }}
                            @if ($participant->note_evaluation)
                            ({{ $participant->note_evaluation }}/20)
                            @endif
                        </span>
                        @else
                        <span class="text-muted">Non évalué</span>
                        @endif
                    </td>
                    <td>
                        @if ($participant->attestation_path)
                        <a href="{{ asset('storage/' . $participant->attestation_path) }}" target="_blank">Voir</a>
                        @else
                        -
                        @endif
                    </td>
                    <td class="d-flex gap-1">
                        <button type="button" class="btn btn-sm btn-outline" data-bs-toggle="modal" data-bs-target="#evalModal{{ $participant->id }}">
                            {{ $participant->resultat_evaluation ? 'Modifier' : 'Évaluer' }}
                        </button>
                        <form action="{{ route('sessions-formation.retirer', [$session, $participant]) }}" method="POST"
                            onsubmit="return confirm('Retirer ce traducteur de la session ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-ghost btn-sm" style="color:#c0392b;">Retirer</button>
                        </form>
                    </td>
                </tr>

                <div class="modal fade" id="evalModal{{ $participant->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="{{ route('sessions-formation.evaluer', [$session, $participant]) }}" method="POST">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title">Évaluer {{ $participant->candidature->user->firstname }} {{ $participant->candidature->user->name }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="field">
                                        <label for="note_evaluation_{{ $participant->id }}">Note (/20)</label>
                                        <input type="number" step="0.01" min="0" max="20" name="note_evaluation"
                                            id="note_evaluation_{{ $participant->id }}" class="form-control"
                                            value="{{ $participant->note_evaluation }}">
                                    </div>

                                    <div class="field" style="margin-top:12px;">
                                        <label for="resultat_evaluation_{{ $participant->id }}">Résultat</label>
                                        <select name="resultat_evaluation" id="resultat_evaluation_{{ $participant->id }}" class="form-control" required>
                                            <option value="">-- Sélectionner --</option>
                                            <option value="reussi" @selected($participant->resultat_evaluation === 'reussi')>Réussi</option>
                                            <option value="rattrapage" @selected($participant->resultat_evaluation === 'rattrapage')>Rattrapage</option>
                                            <option value="echoue" @selected($participant->resultat_evaluation === 'echoue')>Échoué</option>
                                        </select>
                                    </div>

                                    <div class="field" style="margin-top:12px;">
                                        <label for="commentaire_evaluation_{{ $participant->id }}">Commentaire</label>
                                        <textarea name="commentaire_evaluation" id="commentaire_evaluation_{{ $participant->id }}"
                                            class="form-control" rows="3">{{ $participant->commentaire_evaluation }}</textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-ghost btn-sm" data-bs-dismiss="modal">Annuler</button>
                                    <button type="submit" class="btn btn-primary btn-sm">Enregistrer l'évaluation</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
               <!--  <tr>
                    <td colspan="6" class="empty-row">Aucun traducteur affecté à cette session pour le moment.</td>
                </tr>
                @endforelse -->
            </tbody>
        </table>
    </div>
</div>

<div class="panel">
    <h3>Affecter des traducteurs à cette session</h3>

    @if ($candidaturesDisponibles->isEmpty())
    <p class="muted-sub">
        Aucun candidat validé disponible pour affectation
        @if ($session->langue_specialisation_id)
        (langue : {{ $session->langueSpecialisation->nom }})
        @endif
        pour le moment.
    </p>
    @else
    <form action="{{ route('sessions-formation.affecter', $session) }}" method="POST">
        @csrf

        <div class="field">
            <label>Sélectionnez les candidats à affecter</label>

            @if ($candidaturesDisponibles->isEmpty())
            <p class="muted-sub">Aucun candidat disponible pour affectation.</p>
            @else
            <div class="table-responsive">
                <table class="data-table table align-middle">
                    <thead>
                        <tr>
                            <th style="width:40px;">
                                <input type="checkbox" id="selectAllCandidats">
                            </th>
                            <th>Candidat</th>
                            <th>Langue (LV1)</th>
                            <th>Zone souhaitée</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($candidaturesDisponibles as $candidature)
                        <tr>
                            <td>
                                <input type="checkbox" name="candidature_ids[]" value="{{ $candidature->id }}" class="candidat-checkbox">
                            </td>
                            <td>
                                <div class="row-name">
                                    <span class="mini-avatar">
                                        {{ Str::upper(Str::substr($candidature->user->firstname, 0, 1) . Str::substr($candidature->user->name, 0, 1)) }}
                                    </span>
                                    {{ $candidature->user->firstname }} {{ $candidature->user->name }}
                                </div>
                            </td>
                            <td>{{ $candidature->langueSpecialisation->nom }}</td>
                            <td>{{ $candidature->zone_label }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif

            @error('candidature_ids') <span class="field-error">{{ $message }}</span> @enderror
        </div>

        <div class="reg-actions" style="justify-content:flex-end; margin-top:16px;">
            <button type="submit" class="btn btn-primary btn-sm">Affecter les traducteurs sélectionnés</button>
        </div>
    </form>
    @endif
</div>

<div class="panel">
    <div class="reg-actions" style="justify-content:space-between;">
        <a href="{{ route('sessions-formation.index') }}" class="btn btn-ghost btn-sm">Retour à la liste</a>
        <div style="display:flex; gap:10px;">
            <a href="{{ route('sessions-formation.edit', $session) }}" class="btn btn-outline btn-sm">Modifier</a>
            <form action="{{ route('sessions-formation.destroy', $session) }}" method="POST"
                onsubmit="return confirm('Confirmer la suppression de cette session ?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAll = document.getElementById('selectAllCandidats');
        if (!selectAll) return;

        const checkboxes = document.querySelectorAll('.candidat-checkbox');

        selectAll.addEventListener('change', function() {
            checkboxes.forEach(cb => cb.checked = selectAll.checked);
        });
    });
</script>
@endpush