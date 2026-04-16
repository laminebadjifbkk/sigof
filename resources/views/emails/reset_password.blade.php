<!-- resources/views/emails/reset_password.blade.php -->

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Notification de réinitialisation du mot de passe</title>
</head>

<body>
    <p>Salut {{ $name }},</p>

    <p>Vous recevez cet e-mail car nous avons reçu une demande de réinitialisation de mot de passe pour votre compte.
    </p>

    <p>
        <a href="{{ $resetUrl }}"
            style="display:inline-block;padding:10px 15px;background:#4CAF50;color:#fff;text-decoration:none;">
            Réinitialiser le mot de passe
        </a>
    </p>

    <p>
        Ce lien de réinitialisation de mot de passe expirera dans {{ $expire }} minutes.
    </p>

    <p>Si vous n'avez pas demandé de réinitialisation de mot de passe, aucune autre action n'est requise.</p>

    <p>Cordialement,<br>L’équipe ONFP</p>
</body>

</html>
