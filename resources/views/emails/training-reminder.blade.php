<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rappel de Formation</title>
</head>

<body>
    {{-- <h3>Bonjour {{ $notifiable?->firstname }} {{ $notifiable?->name }} !</h3>

    <p>Nous vous rappelons que votre formation en <strong>{{ $formation?->module?->name }}</strong> débutera
        <strong>{{ $reminderType }}</strong>.
    </p>

    <p>Cette formation se déroulera à <strong>{{ $formation?->lieu }}</strong> du
        <strong>{{ $formation?->date_debut?->format('d/m/Y') }}</strong> au
        <strong>{{ $formation?->date_fin?->format('d/m/Y') }}</strong>, et sera animée par
        <strong>{{ $formation?->operateur?->user?->operateur }}
            ({{ $formation?->operateur?->user?->username }})</strong>.
    </p>

    <p>ℹPour bien vous préparer, nous vous recommandons de consulter <a href="sigof.onfp.sn">votre espace
            personnel</a>, où vous trouverez le calendrier détaillé de la formation ainsi que toutes les informations
        essentielles.</p>

    <p><strong>Important :</strong> Merci de vous connecter et de confirmer votre présence, d’être ponctuel(le) et pleinement disponible
        tout au long de la formation.</p>

    <p>Nous vous souhaitons une excellente formation et une expérience d’apprentissage enrichissante ! 🚀</p>

    <p><em>Pour toute question ou information complémentaire, n’hésitez pas à nous contacter.</em></p>


    @include('emails.footer_mail') --}}

    <h3>Bonjour {{ $notifiable?->firstname }} {{ $notifiable?->name }} !</h3>

    <p>Nous vous rappelons que votre formation en <strong>{{ $formation?->module?->name }}</strong> débutera
        <strong>{{ $reminderType }}</strong>.
    </p>
    <h3>Détails de la formation :</h3>
    <ul>
        <li><strong>Intitulé :</strong>
            {{ $formation?->intitule ?? 'Non disponible' }}</li>
        <li><strong>Type formation :</strong> {{ $formation?->types_formation?->name ?? 'Non disponible' }}</li>
        <li><strong>Bénéficiaires :</strong> {{ $formation?->name }}</li>
        <li><strong>Lieu :</strong> {{ $formation?->lieu }}</li>
        <li><strong>Période :</strong>
            @if ($formation?->date_debut)
                du {{ \Carbon\Carbon::parse($formation?->date_debut)->format('d/m/Y') }}
            @else
                Non définie
            @endif

            @if ($formation?->date_fin)
                au {{ \Carbon\Carbon::parse($formation?->date_fin)->format('d/m/Y') }}
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
            @if ($formation?->operateur?->user?->username)
                {{ $formation?->operateur?->user?->operateur . ' (' . $formation?->operateur?->user?->username . ')' }}
            @else
                Non disponible
            @endif
        </li>
        <li><strong>Lieu :</strong>
            @if ($formation?->lieu)
                {{ $formation?->lieu }}
            @else
                Non disponible
            @endif
        </li>
        <p>📌 <strong>Important :</strong> Merci de vous connecter et de confirmer votre présence, d'être ponctuel(le)
            et pleinement disponible tout au long de la formation.</p>
        <p>Nous vous souhaitons une excellente formation et une expérience d'apprentissage enrichissante ! 🚀</p>
        <p>💡 <em>Pour toute question ou information complémentaire, n'hésitez pas à nous contacter.</em></p>
        {{-- <li><strong>Ingénieur en charge :</strong> {{ $formation?->ingenieur?->name ?? 'Non disponible' }}</li>
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
        </li> --}}
    </ul>

    <a href="{{ route('nouvellesformations') }}" target="_blank" title="Voir la fiche formation"
        style="display: inline-block; padding: 10px 20px; background-color: #004080; color: white; text-decoration: none; border-radius: 4px;">
        Voir la fiche formation
    </a>

    <p>Merci de prendre les dispositions nécessaires.</p>

    <p><strong>L'équipe de l'ONFP</strong></p>

    @include('emails.footer_mail')
</body>

</html>
