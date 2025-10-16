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
        <li>Structure : {{ $inscription->structure }}</li>
        <li>Nom : {{ $inscription->nom }}</li>
        <li>Fonction : {{ $inscription->fonction ?? 'N/A' }}</li>
        <li>Téléphone : {{ $inscription->telephone ?? 'N/A' }}</li>
        <li>Email : {{ $inscription->email }}</li>
        @if (!empty($inscription->commentaire))
            <li>Commentaire : {{ $inscription->commentaire }}</li>
        @endif
    </ul>
    <p>À bientôt !</p>
</body>

</html>
