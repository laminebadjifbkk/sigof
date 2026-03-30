{{-- <!DOCTYPE html>
<html lang="fr">

@php
    use Carbon\Carbon;

    $dateDebut = Carbon::parse($formation?->date_debut);
    $dateFin = $formation?->date_fin ? Carbon::parse($formation->date_fin) : null;

    if ($dateDebut && $dateFin) {
        $periode = 'Du ' . $dateDebut->format('d/m/Y') . ' au ' . $dateFin->format('d/m/Y');
    } elseif ($dateDebut && !$dateFin) {
        $periode = 'À partir du ' . $dateDebut->format('d/m/Y');
    } else {
        $periode = '-';
    }
@endphp

<head>
    <meta charset="UTF-8">
    <title>Démarrage formation {{ $label }}</title>
</head>

<body style="font-family: Arial, sans-serif; line-height: 1.6;">

    <p>Bonjour chers collègues,</p>

    <p>
        La DIOF vous informe du démarrage de la formation en
        <strong>
            {{ $formation?->module?->name ?? ($formation?->collectivemodule?->module ?? '-') }}
        </strong>
        {{ strtolower($label) }}
        <strong>{{ $dateDebut->format('d/m/Y') }}</strong>.
    </p>

    <h3>Détails de la formation :</h3>

    <ul>

        <li><strong>Intitulé :</strong>
            {{ $formation?->intitule ?? '-' }}
        </li>

        <li><strong>Type formation :</strong>
            {{ $formation?->types_formation?->name ?? '-' }}
        </li>

        <li><strong>Bénéficiaires :</strong>
            {{ $formation?->name ?? '-' }}
        </li>

        <li><strong>Lieu :</strong>
            {{ $formation?->lieu ?? '-' }}
        </li>

        <li><strong>Période :</strong>
            {{ $periode }}
        </li>

        <li><strong>Durée :</strong>
            @if ($formation?->duree_formation)
                {{ $formation->duree_formation }}
                {{ $formation->duree_formation == 1 ? 'jour' : 'jours' }}
            @else
                -
            @endif
        </li>

        <li><strong>Opérateur :</strong>
            @if ($formation?->operateur?->user?->username)
                {{ $formation->operateur->user->operateur ?? '' }}
                ({{ $formation->operateur->user->username }})
            @else
                -
            @endif
        </li>

        <li><strong>Lieu formation :</strong>
            @if ($formation?->operateur?->user?->adresse)
                {{ $formation->lieu }} - {{ $formation->operateur->user->adresse }}
            @else
                -
            @endif
        </li>

        <li><strong>Ingénieur en charge :</strong>
            {{ $formation?->ingenieur?->name ?? '-' }}
        </li>

        <li><strong>Agent de suivi :</strong>
            {{ $formation?->suivi_dossier ?? '-' }}
        </li>

    </ul>

    <p>
        <a href="{{ url(route('formations.show', $formation)) }}"
            style="display:inline-block;padding:10px 20px;background-color:#004080;color:white;text-decoration:none;border-radius:4px;">
            Voir la fiche formation
        </a>
    </p>

    <p>Merci de prendre les dispositions nécessaires.</p>

    <p><strong>L'équipe de l'ONFP</strong></p>

    @include('emails.footer_mail')

</body>

</html>
 --}}

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Démarrage formation</title>
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
                            <strong>Rappel démarrage formation {{ strtolower($label) }}</strong>
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
                                    <td><strong>Type formation</strong></td>
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

                    <!-- FOOTER -->
                    {{-- <tr>
                        <td style="background:#f1f3f5;text-align:center;padding:15px;font-size:12px;color:#666;">

                            @include('emails.footer_mail')

                        </td>
                    </tr> --}}

                </table>

            </td>
        </tr>
    </table>

</body>

</html>
