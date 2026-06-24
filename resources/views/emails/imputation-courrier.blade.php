<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>ONFP - Imputation courrier</title>
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
                        <td style="background:#F28500;color:#ffffff;padding:12px;text-align:center;">
                            <strong>NOTIFICATION D’IMPUTATION DE COURRIER</strong>
                        </td>
                    </tr>

                    {{-- CONTENU --}}
                    <tr>
                        <td style="padding:30px;">

                            <p style="font-size:15px;">
                                Bonjour,</strong>,
                            </p>

                            <p style="font-size:15px;">
                                La Direction Générale de l’ONFP vous a imputé un nouveau courrier dans le système SIGOF.
                            </p>

                            <!-- TABLE DETAILS -->
                            <table width="100%" cellpadding="8" cellspacing="0"
                                style="border-collapse:collapse;font-size:14px;margin-top:15px;">

                                <tr style="background:#f1f3f5;">
                                    <td><strong>Expéditeur</strong></td>
                                    <td>{{ $arrive->courrier->expediteur ?? '-' }}</td>
                                </tr>

                                <tr>
                                    <td><strong>Objet</strong></td>
                                    <td>{{ $arrive->courrier->objet ?? '-' }}</td>
                                </tr>

                                <tr style="background:#f1f3f5;">
                                    <td><strong>Date arrivée</strong></td>
                                    <td>
                                        {{ $arrive->courrier->date_recep?->format('d/m/Y') ?? ($arrive->created_at?->format('d/m/Y') ?? '-') }}
                                    </td>
                                </tr>

                                <tr>
                                    <td><strong>Numéro</strong></td>
                                    <td>{{ $arrive->numero_arrive ?? '-' }}</td>
                                </tr>

                            </table>

                            <!-- DESTINATAIRES -->
                            <div style="margin-top:20px;font-size:14px;">
                                <strong>Agents concernés :</strong>

                                <ul style="margin-top:8px;">
                                    @foreach ($arrive->users ?? [] as $u)
                                        <li>{{ $u->firstname ?? '' }} {{ $u->name ?? '' }}</li>
                                    @endforeach
                                </ul>
                            </div>

                            <!-- BOUTON -->
                            <table width="100%" style="margin-top:25px;">
                                <tr>
                                    <td>

                                        <a href="{{ url('https://sigof.onfp.sn/arrives/' . $arrive->id) }}"
                                            style="background:#F28500;color:white;text-decoration:none;
                                  padding:12px 25px;border-radius:4px;font-size:14px;display:inline-block;">
                                            Voir le courrier
                                        </a>

                                    </td>
                                </tr>
                            </table>

                            <p style="margin-top:30px;font-size:14px;">
                                Merci de prendre les dispositions nécessaires.
                            </p>

                            <p style="font-size:14px;">
                                <strong>L’équipe SIGOF - ONFP</strong>
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
