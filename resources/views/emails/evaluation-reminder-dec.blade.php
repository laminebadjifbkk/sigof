<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Rappel d'évaluation {{ $label }}</title>
</head>

<body style="margin:0;padding:0;background:#f4f4f4;font-family:Arial, sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f4f4;padding:20px 0;">
        <tr>
            <td align="center">

                <table width="600" cellpadding="0" cellspacing="0"
                    style="background:#ffffff;border-radius:6px;padding:20px;">

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
                            <strong>Rappel d’évaluation {{ $label }}</strong>
                        </td>
                    </tr>

                    {{-- 🔷 CONTENU --}}
                    <tr>
                        <td style="padding:20px;font-size:14px;color:#333;">

                            <p>Bonjour chers collègues,</p>

                            <p>
                                L'évaluation de la formation en
                                <strong>
                                    {{ $formation->module->name ?? ($formation?->collectivemodule?->module ?? 'Non disponible') }}
                                </strong>
                                est prévue <strong>{{ $label }}</strong>, soit le
                                <strong>{{ \Carbon\Carbon::parse($formation?->date_pv)->format('d/m/Y') }}</strong>.
                            </p>

                            <h3 style="color:#004080;">Détails de la formation :</h3>

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
                                    <td><strong>Lieu</strong></td>
                                    <td>{{ $formation?->lieu ?? '-' }}</td>
                                </tr>

                                <tr style="background:#f1f3f5;">
                                    <td><strong>Période</strong></td>
                                    <td>
                                        @if ($formation?->date_debut)
                                            du {{ \Carbon\Carbon::parse($formation->date_debut)->format('d/m/Y') }}
                                        @else
                                            -
                                        @endif

                                        @if ($formation?->date_fin)
                                            au {{ \Carbon\Carbon::parse($formation->date_fin)->format('d/m/Y') }}
                                        @endif
                                    </td>
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
                                    <td><strong>Opérateur</strong></td>
                                    <td>
                                        {{ $formation?->operateur?->user?->display_operateur ?? '-' }}
                                    </td>
                                </tr>

                                <tr>
                                    <td><strong>Ingénieur en charge</strong></td>
                                    <td>{{ $formation?->ingenieur?->name ?? '-' }}</td>
                                </tr>

                                <tr style="background:#f1f3f5;">
                                    <td><strong>Évaluateur(s)</strong></td>
                                    <td>
                                        @if ($formation?->evaluateurs && $formation->evaluateurs->count())
                                            {{ $formation->evaluateurs->map(fn($e) => $e->name . ' ' . $e->lastname)->implode(', ') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>

                                <tr>
                                    <td><strong>Évaluateur(s) ONFP</strong></td>
                                    <td>
                                        @if ($formation?->onfpevaluateurs && $formation->onfpevaluateurs->count())
                                            {{ $formation->onfpevaluateurs->map(fn($e) => $e->name . ' ' . $e->lastname)->implode(', ') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>

                                <tr style="background:#f1f3f5;">
                                    <td><strong>Agent de suivi</strong></td>
                                    <td>{{ $formation?->suivi_dossier ?? '-' }}</td>
                                </tr>

                            </table>

                            {{-- 🔘 BOUTON --}}
                            <p style="text-align:center;margin-top:25px;">
                                <a href="{{ route('formations.show', $formation) }}"
                                    style="background:#F28500;color:#ffffff;padding:12px 20px;text-decoration:none;border-radius:5px;display:inline-block;">
                                    Voir la fiche formation
                                </a>
                            </p>

                            <p>Merci de prendre les dispositions nécessaires.</p>

                            <p><strong>L'équipe de l'ONFP</strong></p>

                            @include('emails.footer_mail')
                        </td>
                    </tr>

                    {{-- 🔷 FOOTER --}}
                    {{-- <tr>
                        <td style="font-size:12px;color:#888;text-align:center;padding-top:15px;">
                            @include('emails.footer_mail')
                        </td>
                    </tr> --}}

                </table>

            </td>
        </tr>
    </table>

</body>

</html>
