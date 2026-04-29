<!DOCTYPE html>
<html lang="fr">

@php

    $dateDebut = \Carbon\Carbon::parse($formation?->date_debut)->startOfDay();
    $today = \Carbon\Carbon::today();

    $diff = $dateDebut->diffInDays($today, false); // négatif si passé

    if ($diff === 0) {
        $label = 'Aujourd’hui';
    } elseif ($diff === 1) {
        $label = 'Hier';
    } elseif ($diff > 1 && $diff <= 5) {
        $label = "Il y a $diff jours";
    } elseif ($diff > 5) {
        $label = "Il y a $diff jours"; // ou juste afficher la date
    } else {
        // pour le futur (optionnel, tu peux juste mettre une date brute)
        $label = 'le ';
        /* $label = $dateDebut->format('d/m/Y'); */
    }
@endphp

<head>
    <meta charset="UTF-8">
    <title>Démarrage formation {{ $label }}</title>
</head>

<body style="font-family: Arial, sans-serif; line-height: 1.6;">
    {{-- <img src="cid:logo.png" alt="ONFP Logo" style="max-width: 150px; margin-bottom: 20px;"> --}}

    {{-- <h2>Rappel : Évaluation prévue {{ $label }}</h2> --}}

    <p>Bonjour chers collègues,</p>

    {{--  <p>
        La DIOF vous informe du démarrage de la formation en
        <strong>{{ $formation->module->name ?? ($formation?->collectivemodule?->module ?? 'Non disponible') }}</strong>
        {{ $label === "Aujourd'hui" ? 'aujourd’hui' : 'Demain' }}
        <strong>{{ \Carbon\Carbon::parse($formation->date_debut)->format('d/m/Y') }}</strong>.
    </p> --}}

    <p>
        La DIOF vous informe du démarrage de la formation en
        <strong>{{ $formation->module->name ?? ($formation?->collectivemodule?->module ?? 'Non disponible') }}</strong>
        {{ strtolower($label) }}
        <strong>{{ $dateDebut->format('d/m/Y') }}</strong>.
    </p>

    {{--  <p>
        Une évaluation de formation est prévue <strong>{{ $label }}</strong>, soit le
        <strong>{{ \Carbon\Carbon::parse($formation->date_fin)->format('d/m/Y') }}</strong>.
    </p> --}}

    <h3>Détails de la formation :</h3>
    <ul>
        <li><strong>Intitulé :</strong>
            {{ $formation?->intitule ?? 'Non disponible' }}</li>
        <li><strong>Type demande :</strong> {{ $formation?->types_formation?->name ?? 'Non disponible' }}</li>
        <li><strong>Bénéficiaires :</strong> {{ $formation?->name }}</li>
        <li><strong>Lieu :</strong> {{ $formation?->lieu }}</li>
        {{-- <li><strong>Période :</strong>
            @php
                $dateDebut = $formation?->date_debut;
                $dateFin = $formation?->date_fin;
            @endphp

            @if ($dateDebut && $dateFin)
                Du {{ \Carbon\Carbon::parse($dateDebut)->format('d/m/Y') }}
                au {{ \Carbon\Carbon::parse($dateFin)->format('d/m/Y') }}
            @elseif ($dateDebut && !$dateFin)
                À partir du {{ \Carbon\Carbon::parse($dateDebut)->format('d/m/Y') }}
            @elseif (!$dateDebut && $dateFin)
                Jusqu’au {{ \Carbon\Carbon::parse($dateFin)->format('d/m/Y') }}
            @else
                Non définie
            @endif
        </li> --}}
        <li><strong>Période :</strong> {{ $periode }}</li>

        <li><strong>Durée :</strong> {{ $formation?->duree_formation }}
            @if ($formation?->duree_formation === 1)
                jour
            @elseif ($formation?->duree_formation > 1)
                jours
            @else
                Non définie
            @endif
        </li>
        <li><strong>Opérateur :</strong>
            @if ($formation?->operateur?->user?->username)
                {{ $formation?->operateur?->user?->display_operateur }}
            @else
                Non disponible
            @endif
        </li>
        <li><strong>Lieu formation :</strong>
            @if ($formation?->operateur?->user?->adresse)
                {{ $formation?->lieu . ' - ' . $formation?->operateur?->user?->adresse }}
            @else
                Non disponible
            @endif
        </li>
        <li><strong>Ingénieur en charge :</strong> {{ $formation?->ingenieur?->name ?? 'Non disponible' }}</li>
        <li><strong>Agent de suivi :</strong> {{ $formation?->suivi_dossier ?? 'Non disponible' }}</li>
    </ul>

    <p>
        <a href="{{ route('formations.show', $formation) }}"
            style="display: inline-block; padding: 10px 20px; background-color: #004080; color: white; text-decoration: none; border-radius: 4px;">
            Voir la fiche formation
        </a>
    </p>

    <p>Merci de prendre les dispositions nécessaires.</p>

    <p><strong>L'équipe de l'ONFP</strong></p>

    @include('emails.footer_mail')

</body>

</html>
