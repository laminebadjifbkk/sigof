@extends('layouts.dashboard')

@section('title', 'Détail de la session')

@section('content')
<div class="dash-topbar">
    <div>
        <h2>{{ $session->nom }}</h2>
        <p class="muted-sub">{{ $session->date_debut->format('d/m/Y') }} → {{ $session->date_fin->format('d/m/Y') }} · {{ $session->lieu ?? 'Lieu non défini' }}</p>
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

<div class="panel">
    <h3>Traducteurs affectés</h3>
    <div class="table-responsive">
        <table class="data-table table align-middle">
            <thead>
                <tr>
                    <th>Candidat</th>
                    <th>Langue (LV1)</th>
                    <th>Statut formation</th>
                    <th>Attestation</th>
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
                        —
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="empty-row">Aucun traducteur affecté à cette session pour le moment.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="panel">
    <div class="reg-actions" style="justify-content:space-between;">
        <a href="{{ route('sessions-formation.index') }}" class="btn btn-ghost btn-sm">← Retour à la liste</a>
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