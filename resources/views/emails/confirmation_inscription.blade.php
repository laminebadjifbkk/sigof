<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Confirmation ONFP</title>
</head>

<body>
    <h2>Bonjour {{ $inscription->nom }},</h2>
    <p>Merci d’avoir confirmé votre participation à <strong>ONFP - Partnership Engagement Day</strong>.</p>
    <p>Voici vos informations :</p>
    <ul>
        <li><b>Structure</b> : {{ $inscription->structure }}</li>
        <li><b>Nom</b> : {{ $inscription->nom }}</li>
        <li><b>Fonction</b> : {{ $inscription->fonction ?? 'N/A' }}</li>
        <li><b>Téléphone</b> : {{ $inscription->telephone ?? 'N/A' }}</li>
        <li><b>Email</b> : {{ $inscription->email }}</li>
        @if (!empty($inscription->commentaire))
            <li><b>Commentaire</b> :<br>
                {!! ' ' .
                    implode(' ', array_map(fn($line) => nl2br(e($line)), explode("\n", ucfirst($inscription->commentaire)))) !!}
            </li>
        @endif
    </ul>
    <p>Votre mot de passe TDR : <strong>PARTNERSHIP@ENGAGEMENT1DAy</strong></p>
    <p>À bientôt !</p>
</body>

</html>
