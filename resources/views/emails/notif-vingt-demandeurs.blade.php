<!DOCTYPE html>
<html>

<head>
    {{-- <img src="cid:logo.png" alt="ONFP Logo" style="max-width: 20%;" class="w-25"> --}}
    <title>Un groupe de 20 demandeurs trouvé pour la région de {{ $region }}</title>
</head>

<body>
    <p>Bonjour,</p>

    <p>Nous vous informons que la région de <strong>{{ $region }}</strong> a reçu
        <strong>{{ $total }}</strong> nouvelles demandes individuelles en attente de traitement.
    </p>

    <p>Il serait peut-être opportun de procéder à leur validation et d’envisager l’organisation d’une session de
        formation pour ce groupe.</p>

    <p><strong>L'équipe de l'ONFP</strong></p>

    @include('emails.footer_mail')
</body>

</html>
