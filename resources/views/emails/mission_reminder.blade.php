<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Rappel mission ({{ $type }}) – {{ $mission->reference }}</title>
</head>

<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
<p>
    Bonjour {{ trim(($employee->user->firstname ?? '') . ' ' . ($employee->user->name ?? '')) ?: 'cher(e) collègue' }},
</p>


    <p>
        Ceci est un <strong>rappel de mission</strong> prévu
        <strong>{{ $type }}</strong>.
        Merci de prendre toutes les dispositions nécessaires.
    </p>

    <h3 style="color: #004080;">Détails de la mission :</h3>

    <ul style="list-style: none; padding-left: 0;">
        <li><strong>Référence :</strong> {{ $mission->reference }}</li>
        <li><strong>Type :</strong> {{ $mission->typeMission->libelle ?? '-' }}</li>
        <li><strong>Objet :</strong> {{ $mission->objet ?? 'Non défini' }}</li>
        <li>
            <strong>Date de départ :</strong>
            {{ $mission->date_depart?->format('d/m/Y H:i') ?? '-' }}
        </li>
        <li>
            <strong>Date de retour :</strong>
            {{ $mission->date_retour?->format('d/m/Y') ?? '-' }}
        </li>
        <li>
            <strong>Durée :</strong>
            @if ($mission->date_depart && $mission->date_retour)
                {{ $mission->date_depart->diffInDays($mission->date_retour) }} jour(s)
            @else
                -
            @endif
        </li>
        <li>
            <strong>Destination :</strong>
            {{ $mission?->lieu_arrivee ?? '-' }}
        </li>
        <li>
            <strong>Itinéraire :</strong>
            {{ $mission?->itineraire ?? '-' }}
        </li>
    </ul>

    <p>
        Merci de veiller au bon déroulement de cette mission.
    </p>

    <p>
        <strong>L’équipe du Parc Automobile ONFP</strong>
    </p>

    {{-- Footer --}}
    <hr style="border: none; border-top: 1px solid #ccc; margin: 20px 0;">

    <p style="font-size: 12px; color: #666;">
        Ceci est un mail automatique. Merci de ne pas répondre directement à ce message.
    </p>

    @include('emails.footer_mail')

</body>

</html>
