@extends('layouts.app')

@section('title', 'Candidature envoyée')

@section('content')
<div class="register-wrap">
    <div class="container">
        <div class="confirmation-card" id="printable-receipt">
            <div class="confirmation-icon">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none">
                    <circle cx="12" cy="12" r="11" stroke="#2e7d32" stroke-width="1.5" fill="#e8f5e9" />
                    <path d="M7 12.5l3 3 7-7" stroke="#2e7d32" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </div>

            <h2>Candidature envoyée avec succès</h2>
            <p style="color:var(--gray-700); margin-top:8px;">
                Merci {{ $candidature->user->firstname }}, votre dossier a bien été enregistré. Vous recevrez une réponse
                par e-mail à l'adresse <strong>{{ $candidature->user->email }}</strong> une fois votre candidature examinée.
            </p>

            <div class="confirmation-ref">
                <span class="ref-label">Numéro de dossier</span>
                <span class="ref-value">#{{ str_pad($candidature->id, 6, '0', STR_PAD_LEFT) }}</span>
            </div>

            <div class="recap-block">
                <h4>Récapitulatif</h4>
                <p><strong>Langue de spécialisation :</strong> {{ $candidature->langueSpecialisation->nom }}</p>
                <p><strong>Zone souhaitée :</strong> {{ ucfirst(str_replace('_', ' ', $candidature->zone)) }}</p>
                <p><strong>Disponibilité :</strong>
                    du {{ $candidature->disponible_debut->format('d/m/Y') }}
                    au {{ $candidature->disponible_fin->format('d/m/Y') }}
                </p>
                <p><strong>Statut :</strong>
                    <span class="badge badge-pending">En attente d'examen</span>
                </p>
            </div>

            <p style="color:var(--gray-700); font-size:13.5px; margin-top:16px;">
                Conservez ce numéro de dossier, il pourra vous être demandé pour toute question relative à votre candidature.
            </p>
        </div>
        <div class="reg-actions" style="justify-content:center; margin-top:24px;">
            <a href="{{ route('ylphome') }}" class="btn btn-ghost btn-sm">Retour à l'accueil</a>
            &nbsp;
            <button type="button" class="btn btn-primary btn-sm" onclick="window.print()">Imprimer le récapitulatif</button>
        </div>
    </div>
</div>
@endsection