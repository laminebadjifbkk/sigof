{{-- <!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('ONFP | Notification') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            padding-bottom: 10px;
            border-bottom: 2px solid #007bff;
        }

        .header img {
            max-width: 120px;
        }

        .header h2 {
            color: #007bff;
            margin: 10px 0 0;
        }

        .content {
            padding: 20px 0;
            color: #333;
            line-height: 1.6;
        }

        .content h3 {
            color: #333;
        }

        .content p {
            font-size: 16px;
            margin: 10px 0;
        }

        .footer {
            text-align: center;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            font-size: 14px;
            color: #666;
        }

        .footer a {
            color: #007bff;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <!-- En-tête -->
        <div class="header">
            <img src="cid:logo.png" alt="ONFP Logo" style="max-width: 20%;" class="w-25">
            <!-- <h2>ONFP | Notification</h2> -->
        </div>

        <!-- Contenu -->
        <div class="content">
            <h3>{{ $toUserName }}</h3>
            <p>{!! $mailMessage !!}</p>
        </div>

        <!-- Pied de page -->
        <div class="footer">
            <p>&copy; ONFP {{ date('Y') }}. Tous droits réservés.</p>
            <p><a href="https://sigof.onfp.sn">Visitez notre site</a> | <a href="mailto:onfp@onfp.sn">Contactez-nous</a>
            </p>
        </div>
    </div>
</body>

</html>
 --}}




{{-- 
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>COURRIER | ONFP</title>
</head>

<body style="font-family: Arial, sans-serif; line-height: 1.6;">
    //<img src="cid:logo.png" alt="ONFP Logo" style="max-width: 150px; margin-bottom: 20px;">

    <p>Bonjour <strong>{{ $toUserName }}</strong>,</p>

    //<p>{{ $mailMessage }}</p>

    <p>Par la présente, nous vous informons que la Direction Générale a procédé à l'imputation d'un courrier à votre
        nom.</p>

    <h3>📌 Détails du courrier :</h3>
    <ul>
        <li><strong>Expéditeur :</strong>
            {{ $arrive->courrier->expediteur ?? 'Non disponible' }}</li>
        <li><strong>Objet :</strong> {{ $arrive->courrier->objet ?? 'Non disponible' }}</li>
    </ul>

    <p>
        <a href="{{ route('arrives.show', $arrive->id) }}"
            style="display: inline-block; padding: 10px 20px; background-color: #004080; color: white; text-decoration: none; border-radius: 4px;">
            🔎 Voir le courrier
        </a>
    </p>

    <p>Merci de prendre les dispositions nécessaires.</p>

    <p><strong>Le Bureau du Courrier</strong></p>

    @include('emails.footer_mail')

</body>

</html> --}}




<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>ONFP - Notification imputation courrier</title>
</head>

<body style="margin:0;padding:0;background-color:#f4f6f9;font-family:Arial,Helvetica,sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f6f9;padding:20px;">
        <tr>
            <td align="center">

                <table width="600" cellpadding="0" cellspacing="0"
                    style="background:white;border-radius:6px;overflow:hidden;">

                    {{-- 🔷 HEADER --}}
                    <tr>
                        <td align="center" style="padding-bottom:15px;">
                            {{-- Logo --}}
                            <img src="https://sigof.onfp.sn/images/LOGOONFPTEXTEGOOD1.jpg" alt="ONFP"
                                style="max-width:250px;display:block;">
                        </td>
                    </tr>

                    {{-- 🔷 TITRE --}}
                    <tr>
                        <td style="background:#F28500;color:#ffffff;padding:10px;text-align:center;border-radius:2px;">
                            <strong>Imputation {{ strtolower($label) }}</strong>
                        </td>
                    </tr>

                    <!-- CONTENU -->
                    <tr>
                        <td style="padding:30px;">

                            <p style="font-size:15px;">Bonjour chers collègues,</p>

                            <p style="font-size:15px;">
                                La DIOF vous informe du démarrage de la formation en
                                <strong>{{ $formation?->module?->name ?? ($formation?->collectivemodule?->module ?? '-') }}</strong>
                                <strong>{{ strtolower($label) }}</strong>
                            </p>

                            <!-- TABLE DETAILS -->
                            <table width="100%" cellpadding="8" cellspacing="0"
                                style="border-collapse:collapse;font-size:14px;margin-top:15px;">

                                <tr style="background:#f1f3f5;">
                                    <td><strong>Intitulé</strong></td>
                                    <td>{{ $formation?->intitule ?? '-' }}</td>
                                </tr>

                                <tr>
                                    <td><strong>Type demande</strong></td>
                                    <td>{{ $formation?->types_formation?->name ?? '-' }}</td>
                                </tr>

                                <tr style="background:#f1f3f5;">
                                    <td><strong>Bénéficiaires</strong></td>
                                    <td>{{ $formation?->name ?? '-' }}</td>
                                </tr>

                                <tr>
                                    <td><strong>Date début</strong></td>
                                    <td>{{ $formation->date_debut->format('d/m/Y') ?? '' }}</td>
                                </tr>

                                <tr style="background:#f1f3f5;">
                                    <td><strong>Lieu</strong></td>
                                    <td>{{ $formation?->lieu ?? '-' }}</td>
                                </tr>

                                <tr>
                                    <td><strong>Durée</strong></td>
                                    <td>
                                        @if ($formation?->duree_formation)
                                            {{ $formation->duree_formation }}
                                            {{ $formation->duree_formation == 1 ? 'jour' : 'jours' }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>

                                <tr style="background:#f1f3f5;">
                                    <td><strong>Ingénieur en charge</strong></td>
                                    <td>{{ $formation?->ingenieur?->name ?? '-' }}</td>
                                </tr>

                                <tr>
                                    <td><strong>Opérateur</strong></td>
                                    <td>
                                        {{ $formation?->operateur?->user?->display_operateur }}
                                    </td>
                                </tr>

                            </table>

                            <!-- BOUTON -->
                            <table width="100%" style="margin-top:25px;">
                                <tr>
                                    <td align="center">

                                        <a href="{{ route('formations.show', $formation, true) }}"
                                            style="background:#F28500;color:white;text-decoration:none;padding:12px 25px;border-radius:4px;font-size:14px;display:inline-block;">
                                            Voir la fiche formation
                                        </a>

                                    </td>
                                </tr>
                            </table>

                            <p style="margin-top:30px;font-size:14px;">
                                Merci de prendre les dispositions nécessaires.
                            </p>

                            <p style="font-size:14px;">
                                <strong>L'équipe de l'ONFP</strong>
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
