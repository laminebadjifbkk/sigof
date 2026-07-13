<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Rappel d'évaluation {{ $label }}</title>
</head>

<body style="font-family: Arial, sans-serif; line-height: 1.6;">
    {{-- <img src="cid:logo.png" alt="ONFP Logo" style="max-width: 150px; margin-bottom: 20px;"> --}}

    {{-- <h2>Rappel : Évaluation prévue {{ $label }}</h2> --}}

    <p>Bonjour chers collègues,</p>

    <p>
        L'évaluation de la formation en
        <strong>{{ $formation->module->name ?? ($formation?->collectivemodule?->module ?? 'Non disponible') }}</strong>
        est prévue <strong>{{ $label }}</strong>, soit le
        <strong>{{ \Carbon\Carbon::parse($formation?->date_pv)->format('d/m/Y') }}</strong>.
    </p>

    <h3>Détails de la formation :</h3>
    <ul>
        <li><strong>Intitulé :</strong>
            {{ $formation?->intitule ?? 'Non disponible' }}</li>
        <li><strong>Type demande :</strong> {{ $formation?->types_formation?->name ?? 'Non disponible' }}</li>
        <li><strong>Bénéficiaires :</strong> {{ $formation?->name }}</li>
        <li><strong>Lieu :</strong> {{ $formation?->lieu }}</li>
        <li><strong>Période :</strong>
            @if ($formation?->date_debut)
                du {{ \Carbon\Carbon::parse($formation->date_debut)->format('d/m/Y') }}
            @else
                Non définie
            @endif

            @if ($formation?->date_fin)
                au {{ \Carbon\Carbon::parse($formation->date_fin)->format('d/m/Y') }}
            @endif
        </li>
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
                {{ $formation?->operateur?->user?->display_operateur }}
        </li>
        <li><strong>Ingénieur en charge :</strong> {{ $formation?->ingenieur?->name ?? 'Non disponible' }}</li>
        <li><strong>Évaluateur(s) : </strong>
            @if ($formation?->evaluateurs && $formation->evaluateurs->count())
                {{ $formation->evaluateurs->map(fn($e) => $e->name . ' ' . $e->lastname)->implode(', ') }}
            @else
                Non disponible
            @endif
        </li>
        <li><strong>Évaluateur(s) ONFP : </strong>
            @if ($formation?->evaluateurs && $formation->onfpevaluateurs->count())
                {{ $formation->onfpevaluateurs->map(fn($e) => $e->name . ' ' . $e->lastname)->implode(', ') }}
            @else
                Non disponible
            @endif
        </li>
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
