{{-- <!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Confirmation ONFP</title>
</head>

<body>
    <h2>Bonjour {{ $inscription->nom }},</h2>
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
 --}}

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Confirmation – 40 ans de l'ONFP</title>
</head>

<body style="margin:0;padding:0;background:#f4f3f1;font-family:Arial,Helvetica,sans-serif;color:#24262b;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f4f3f1;padding:24px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0"
                    style="max-width:600px;width:100%;background:#ffffff;border-radius:12px;overflow:hidden;">
                    <tr>
                        <td style="background:#e07a2f;padding:22px 28px;color:#ffffff;">
                            <div style="font-size:13px;letter-spacing:1px;font-weight:bold;">1986 – 2026</div>
                            <div style="font-size:22px;font-weight:bold;margin-top:4px;">Célébration des 40 ans de
                                l'ONFP</div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:28px;font-size:15px;line-height:1.6;">
                            <h2 style="margin:0 0 16px;font-size:18px;">Bonjour {{ $inscription->nom }},</h2>

                            <p style="margin:0 0 16px;">
                                Nous vous remercions d'avoir confirmé votre participation à la
                                <b>célébration des 40 ans de l'Office national de formation professionnelle (ONFP)</b>.
                            </p>

                            <p style="margin:0 0 16px;">
                                Nous serons ravis de vous accueillir le <b>jeudi 19 novembre 2026</b>
                                {{-- TODO : précisez l'heure et le lieu exacts --}}
                                à <b>10h</b>, à <b>Azalaï Hôtel, Dakar</b>.
                            </p>

                            <p style="margin:0 0 16px;">
                                Vous trouverez les termes de référence (TDR) de la célébration en cliquant sur le bouton
                                ci-dessous :
                            </p>

                            <table role="presentation" cellpadding="0" cellspacing="0" style="margin:0 0 24px;">
                                <tr>
                                    <td style="background:#e07a2f;border-radius:8px;">
                                        <a href="{{ $tdrUrl }}" target="_blank"
                                            style="display:inline-block;padding:12px 24px;font-size:15px;font-weight:bold;color:#ffffff;text-decoration:none;">
                                            Télécharger les termes de référence
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin:0 0 16px;font-size:13px;color:#5b5e66;">
                                Si le bouton ne fonctionne pas, copiez ce lien dans votre navigateur :<br>
                                <a href="{{ $tdrUrl }}"
                                    style="color:#c05f1c;word-break:break-all;">{{ $tdrUrl }}</a>
                            </p>

                            <p style="margin:0 0 8px;">Voici les informations que vous nous avez communiquées :</p>
                            <ul style="margin:0 0 20px;padding-left:20px;">
                                <li><b>Structure</b> : {{ $inscription->structure }}</li>
                                <li><b>Nom</b> : {{ $inscription->nom }}</li>
                                <li><b>Fonction</b> : {{ $inscription->fonction ?? 'N/A' }}</li>
                                <li><b>Téléphone</b> : {{ $inscription->telephone ?? 'N/A' }}</li>
                                <li><b>Email</b> : {{ $inscription->email }}</li>
                                @if (!empty($inscription->commentaire))
                                    <li><b>Commentaire</b> :<br>{!! nl2br(e(ucfirst($inscription->commentaire))) !!}</li>
                                @endif
                            </ul>

                            <p style="margin:0 0 16px;">À très bientôt !</p>

                            <p style="margin:0;">
                                Cordialement,<br>
                                <b>L'équipe de l'ONFP</b>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="background:#f4f3f1;padding:16px 28px;font-size:13px;color:#5b5e66;">
                            Une question ? <a href="tel:+221338279251" style="color:#c05f1c;">+221 33 827 92 51</a>
                            · <a href="mailto:onfp@onfp.sn" style="color:#c05f1c;">onfp@onfp.sn</a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
