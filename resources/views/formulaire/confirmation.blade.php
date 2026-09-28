@extends('layouts.app')

@section('title', 'Demande de prise en charge envoyée')

@section('content')
    @php
        $fcfa = fn($v) => number_format((float) $v, 0, ',', ' ') . ' FCFA';
        $oui = fn($v) => strtolower(trim((string) $v)) === 'oui';
        // CIN partiellement masqué (affichage + impression)
        $cinMasque = str_repeat('•', max(strlen($formulaire->cin) - 4, 0)) . substr($formulaire->cin, -4);
    @endphp

    <div class="register-wrap">
        <div class="container">
            <div class="confirmation-card" id="printable-receipt">
                <div class="confirmation-icon">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none">
                        <circle cx="12" cy="12" r="11" stroke="#2e7d32" stroke-width="1.5" fill="#e8f5e9" />
                        <path d="M7 12.5l3 3 7-7" stroke="#2e7d32" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </div>

                <h2>Demande de prise en charge envoyée avec succès</h2>
                <p style="color:var(--gray-700); margin-top:8px;">
                    Merci {{ $formulaire->civilite }} {{ $formulaire->prenom }} {{ $formulaire->nom }}, votre dossier a bien
                    été
                    enregistré. Votre demande sera examinée par l'ONFP et vous serez informé(e) à l'adresse
                    <strong>{{ $formulaire->email }}</strong>.
                </p>

                <div class="confirmation-ref">
                    <span class="ref-label">Numéro de dossier</span>
                    <span class="ref-value">#{{ str_pad($formulaire->id, 6, '0', STR_PAD_LEFT) }}</span>
                </div>

                {{-- Candidat --}}
                <div class="recap-block">
                    <h4>Informations du candidat</h4>
                    <p><strong>Nom complet :</strong> {{ $formulaire->civilite }} {{ $formulaire->prenom }}
                        {{ $formulaire->nom }}</p>
                    <p><strong>N° pièce d'identité :</strong> {{ $cinMasque }}</p>
                    <p><strong>Né(e) le :</strong> {{ \Carbon\Carbon::parse($formulaire->date_naissance)->format('d/m/Y') }}
                        à {{ $formulaire->lieu_naissance }}</p>
                    <p><strong>Téléphones :</strong> {{ $formulaire->telephone }} /
                        {{ $formulaire->telephone_secondaire }}</p>
                    <p><strong>Adresse :</strong> {{ $formulaire->adresse }}</p>
                    <p><strong>Dernier diplôme :</strong> {{ $formulaire->dernier_diplome }}</p>
                </div>

                {{-- Formation --}}
                <div class="recap-block">
                    <h4>Formation demandée</h4>
                    <p><strong>Formation :</strong> {{ $formulaire->formation }}</p>
                    <p><strong>Diplôme visé :</strong> {{ $formulaire->diplome_vise }}</p>
                    <p><strong>Durée :</strong> {{ $formulaire->duree }} {{ $formulaire->duree > 1 ? 'ans' : 'an' }}</p>
                    <p><strong>Région :</strong> {{ $formulaire->region }}</p>
                </div>

                {{-- Établissement --}}
                <div class="recap-block">
                    <h4>Établissement de formation</h4>
                    <p><strong>Nom :</strong> {{ $formulaire->nom_etablissement }}
                        @if ($formulaire->autre_2)
                            ({{ $formulaire->autre_2 }})
                        @endif
                    </p>
                    <p><strong>Adresse :</strong> {{ $formulaire->adresse_etablessement }}</p>
                    <p><strong>Téléphone :</strong> {{ $formulaire->telephone_etablissement }}</p>
                </div>

                {{-- Frais --}}
                <div class="recap-block">
                    <h4>Frais de scolarité (facture proforma)</h4>
                    <p><strong>Inscription :</strong> {{ $fcfa($formulaire->montant_inscription) }}</p>
                    <p><strong>Mensualité :</strong> {{ $fcfa($formulaire->montant_mensualite) }}</p>
                    @if ($formulaire->montant_unique)
                        <p><strong>Montant unique :</strong> {{ $fcfa($formulaire->montant_unique) }}</p>
                    @endif
                </div>

                {{-- Situation particulière --}}
                <div class="recap-block">
                    <h4>Situation particulière</h4>
                    <p><strong>Situation de handicap :</strong> {{ $formulaire->handicape }}
                        @if ($oui($formulaire->handicape) && $formulaire->type_handicap)
                            ({{ $formulaire->type_handicap }})
                        @endif
                    </p>
                    <p><strong>Orphelin(e) :</strong> {{ $formulaire->orphelin }}
                        @if ($oui($formulaire->orphelin) && $formulaire->type_orphelin)
                            ({{ $formulaire->type_orphelin }})
                        @endif
                    </p>
                </div>

                {{-- Pièces jointes --}}
                <div class="recap-block">
                    <h4>Pièces déposées</h4>
                    @foreach ([
            'cin_file' => "Copie de la pièce d'identité",
            'cv' => 'Curriculum vitae',
            'diplome' => 'Diplômes / certificats',
            'facture_file' => 'Facture proforma',
        ] as $champ => $libelle)
                        <p>
                            <span style="color:{{ $formulaire->$champ ? '#2e7d32' : '#c00000' }}; font-weight:700;">
                                {{ $formulaire->$champ ? '✔' : '✘' }}
                            </span>
                            {{ $libelle }}
                        </p>
                    @endforeach
                </div>

                <div class="recap-block">
                    <p><strong>Date de dépôt :</strong>
                        {{ $formulaire->created_at->timezone('Africa/Dakar')->format('d/m/Y à H:i') }}</p>
                    <p><strong>Statut :</strong> <span class="badge badge-pending">En attente d'examen</span></p>
                </div>

                <p style="color:var(--gray-700); font-size:13.5px; margin-top:16px;">
                    Conservez ce numéro de dossier, il pourra vous être demandé pour toute question relative à votre
                    demande.
                    Contact : onfp@onfp.sn · 33 827 92 51.
                </p>
            </div>

            <div class="reg-actions" style="justify-content:center; margin-top:24px;">
                <a href="{{ url('/') }}" class="btn btn-ghost btn-sm">Retour à l'accueil</a>
                &nbsp;
                <button type="button" class="btn btn-primary btn-sm" onclick="window.print()">Imprimer le
                    récapitulatif</button>
            </div>
        </div>
    </div>
@endsection
