<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>ONFP - Imputation mail</title>
</head>

<body style="margin:0;padding:0;background-color:#f4f6f9;font-family:Arial,Helvetica,sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f6f9;padding:20px;">
        <tr>
            <td align="center">

                <table width="600" cellpadding="0" cellspacing="0"
                    style="background:white;border-radius:6px;overflow:hidden;">

                    {{-- HEADER --}}
                    <tr>
                        <td align="center" style="padding:20px;">
                            <img src="https://sigof.onfp.sn/images/LOGOONFPTEXTEGOOD1.jpg" alt="ONFP"
                                style="max-width:250px;">
                        </td>
                    </tr>

                    {{-- TITRE --}}
                    <tr>
                        <td style="background:#d9534f;color:#fff;padding:12px;text-align:center;">
                            <strong>Notifications de courrier</strong>
                        </td>
                    </tr>

                    {{-- CONTENU --}}
                    <tr>
                        <td style="padding:30px;">

                            <p>Bonjour,</p>

                            <p>
                                Le courrier a été traité et les notifications ont été envoyées.
                            </p>

                            <p><strong>Liste des destinataires :</strong></p>

                           {{--  <ul>
                                @foreach ($emails as $email)
                                    <li>{{ $email }}</li>
                                @endforeach
                            </ul> --}}

                            <p>
                                Ce message est un rapport automatique du système SIGOF.
                            </p>

                            @include('emails.footer_mail')

                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>

</html>
