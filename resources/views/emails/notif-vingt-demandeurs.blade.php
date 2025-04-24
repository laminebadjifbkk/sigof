<!DOCTYPE html>
<html>

<head>
    {{-- <img src="cid:logo.png" alt="ONFP Logo" style="max-width: 20%;" class="w-25"> --}}
    <title>Un groupe de 20 demandeurs trouvé pour la région de {{ $region }}</title>
</head>

<body>
    <p>Bonjour,</p>

    <p>Nous vous informons que la région <strong>{{ $region }}</strong> a atteint
        <strong>{{ $total }}</strong> demandes individuelles au statut <strong>Nouvelle</strong>.</p>

    <p>Il est peut-être temps d’organiser une session de formation pour ce groupe.</p>

    <p><strong>L'équipe de l'ONFP</strong></p>

    @include('emails.footer_mail')
</body>

</html>
