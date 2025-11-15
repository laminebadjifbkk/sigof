<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Démarrage de votre formation</title>
</head>

<body>
    <h3>Bonjour {{ $individuelle?->user?->firstname . ' ' . $individuelle?->user?->name }} !</h3>

    <p>Nous avons le plaisir de vous annoncer que vous avez été sélectionné pour participer à la formation
        <strong>{{ $individuelle?->module?->name }}</strong>.
    </p>
    <p>
        <strong>Lieu :</strong> {{ $individuelle?->departement?->nom . ', ' . $individuelle?->formation?->lieu }}<br>
        <strong>Dates :</strong> du {{ $individuelle?->formation?->date_debut?->format('d/m/Y') }} au
        {{ $individuelle?->formation?->date_fin?->format('d/m/Y') }}<br>
        <strong>Opérateur :</strong> {{ $individuelle?->formation?->operateur?->user?->operateur }}
        ({{ $individuelle?->formation?->operateur?->user?->username }}) <br>
        <strong>Adresse :</strong>
        @if ($individuelle?->formation?->operateur?->user?->adresse)
            {{ $individuelle?->formation?->lieu . ' - ' . $individuelle?->formation?->operateur?->user?->adresse }}
        @else
            Non disponible
        @endif
    </p>
    <p>Cordialement,</p>
    @include('emails.footer_mail')
</body>

</html>
