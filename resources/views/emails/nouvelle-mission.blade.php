<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Nouvelle mission : {{ $mission->reference }}</title>
</head>

<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    {{-- Logo ONFP (optionnel) --}}
    {{-- <img src="cid:logo.png" alt="ONFP Logo" style="max-width: 150px; margin-bottom: 20px;"> --}}

    <p>Bonjour chers collègues,</p>

    <p>
        Une <strong>nouvelle mission</strong> a été créée dans le système de gestion du parc automobile.
        Merci de mettre les véhicules nécessaires à disposition.
    </p>

    <h3 style="color: #004080;">Détails de la mission :</h3>
    <ul style="list-style: none; padding-left: 0;">
        <li><strong>Référence :</strong> {{ $mission->reference }}</li>
        <li><strong>Objet :</strong> {{ $mission->objet ?? 'Non défini' }}</li>
        <li><strong>Date départ :</strong> {{ $mission->date_depart?->format('d/m/Y') ?? 'Non définie' }}</li>
        <li><strong>Date retour :</strong> {{ $mission->date_retour?->format('d/m/Y') ?? 'Non définie' }}</li>
        <li><strong>Durée :</strong>
            @if ($mission->date_depart && $mission->date_retour)
                {{ $mission->date_depart->diffInDays($mission->date_retour) + 1 }} jours
            @else
                Non définie
            @endif
        </li>
        <li><strong>Nombre de véhicules prévus :</strong> {{ $mission->autres ?? '—' }}</li>
    </ul>

    <p>
        <a href="{{ route('parc-missions.show', $mission) }}"
            style="display: inline-block; padding: 6px 12px; /* plus petit */
          font-size: 0.85rem; /* réduit la police */
          background-color: #004080;
          color: white;
          text-decoration: none;
          border-radius: 4px;
          font-weight: bold;">
            Voir la mission
        </a>
    </p>

    <p>Merci de prendre les dispositions nécessaires pour le bon déroulement de cette mission.</p>

    <p><strong>L'équipe du Parc Automobile ONFP</strong></p>

    {{-- Footer simple --}}
    <hr style="border: none; border-top: 1px solid #ccc; margin: 20px 0;">
    <p style="font-size: 12px; color: #666;">
        Ceci est un mail automatique. Veuillez ne pas répondre directement à ce message.
    </p>
    @include('emails.footer_mail')
</body>

</html>
