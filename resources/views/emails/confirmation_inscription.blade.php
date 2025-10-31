<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Confirmation ONFP</title>
</head>

<body>
    <h2>Bonjour {{ $inscription->nom }},</h2>
    {{-- <p>Merci d’avoir confirmé votre participation à <strong>ONFP - Partnership Engagement Day</strong>.</p> --}}
    <p>
        Nous vous remercions d'avoir confirmé votre participation au <b>PARTNERSHIP ENGAGEMENT DAY</b>, vous trouverez
        ci-joints les termes de références.<br>
        <br>
        Afin d'identifier mutuellement des projets et programmes pertinents et de définir provisoirement notre cadre de
        collaboration, nous vous invitons à bien vouloir renseigner les deux formulaires ci-dessous et consulter la
        fiche synoptique de quelques projets de l'ONFP :<br><br>

        <a href="https://bit.ly/47k1zBX">FICHE D’IDENTIFICATION DES PROJETS ET/OU PROGRAMMES DES PARTENAIRES</a>
        <br><br>
        <a href="https://bit.ly/4qAd3Zt">MANIFESTATION D'INTÉRÊT DE PARTENARIAT</a>
        <br><br>
        <a
            href="https://docs.google.com/spreadsheets/d/1Nkn6l4yerZvs719u-Hf2-0UHDyEz4a3-/edit?usp=sharing&ouid=110350299308327959736&rtpof=true&sd=true">FICHE
            SYNOPTIQUE DES PROJETS</a>
        <br><br>
        Nous serons ravis de vous rencontrer le <b>06 Novembre 2025 à 08h00, à l’Hôtel AZALAI.</b><br><br>
        Cordialement, <br><br>

        <b>CELLULE COOPERATION ET PARTENARIAT-ONFP</b> <br><br>

        <b>Contact </b> : <a href="tel:+221772913349">772913349</a> / <a href="tel:+221783753781">783753781</a> <br>
    </p>
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
    <p>À bientôt !</p>
</body>

</html>
