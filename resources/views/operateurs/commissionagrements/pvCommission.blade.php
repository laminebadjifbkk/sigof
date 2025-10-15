<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Procès-verbal {{ $commissionagrement?->commission }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 10px 40px 40px 40px;
            /* marge-top minimale */
            line-height: 1.5;
        }

        h2,
        h3,
        h4,
        h5 {
            text-align: center;
            margin: 0;
            padding: 0;
        }

        .header {
            text-align: center;
            margin-bottom: 5px;
            /* réduit presque tout l'espace sous l'en-tête */
            padding: 0;
        }

        .header em {
            font-size: 12px;
        }

        hr {
            border: none;
            border-top: 1px solid #000;
            margin: 2px 0;
            /* quasi aucun espace autour */
        }

        .content {
            text-align: justify;
            margin-top: 5px;
            /* réduit l’espace après l’en-tête */
        }

        .signature {
            margin-top: 60px;
            /* réduit l’espace avant les signatures */
            width: 100%;
        }

        .signature td {
            border: none;
            vertical-align: top;
            padding-top: 15px;
            /* réduit l’espace dans les td */
        }

        .signature .right {
            text-align: right;
        }

        .footer {
            margin-top: 20px;
            /* réduit l'espace au-dessus de la date */
            text-align: right;
            font-style: italic;
        }
    </style>


</head>

<body>

    {{-- === EN-TÊTE OFFICIELLE === --}}
    <div class="header">
        <b>REPUBLIQUE DU SENEGAL</b><br>
        <em>Un Peuple - Un But - Une Foi</em><br>
        <b>********</b><br>
        <b>MINISTERE DE L'EMPLOI ET DE LA FORMATION PROFESSIONNELLE ET TECHNIQUE</b><br><br>
        @if ($logoBase64)
            <img src="data:image/png;base64,{{ $logoBase64 }}" alt="Logo ONFP" style="width:100%; max-width:280px;">
        @endif
        <br>
        <p><b>Direction des Evaluations et Certifications</b></p>
        <hr>
    </div>

    {{-- === TITRE DU DOCUMENT === --}}
    <h3><strong>PROCÈS-VERBAL</strong></h3>
    <br>

    {{-- === CONTENU PRINCIPAL === --}}
    <div class="content">
        <p>
            L’an deux mille {{ $commissionagrement?->date?->format('Y') ?? now()->format('Y') }},<br>
            Les <b><i>{{ $jours }} {{ $moisAnnee }}</i></b>, s’est tenue au
            {{ $commissionagrement?->lieu ?? 'Centre de Formation Professionnelle Sénégal-Japon de Dakar' }},
            la session {{ $commissionagrement?->date?->format('Y') ?? now()->format('Y') }} de la Commission d’Agrément
            et de Labélisation
            (CAL) des opérateurs de formation de l’Office National de Formation Professionnelle (ONFP).
        </p>

        <p>
            Sont présents et ont émargé à la feuille de présence :
        </p>

        <ul>
            {{-- Membres avec statut non vide --}}
            @foreach ($commissionagrement->commissionmembres as $membre)
                @if (!empty($membre->statut))
                    <li><b>{{ $membre->civilite . ' ' . $membre->prenom . ' ' . $membre->nom }}</b>,
                        {{ $membre->fonction . ' - ' }}
                        <i>{{ $membre->structure }}</i>
                    </li>
                @endif
            @endforeach

            {{-- Membres avec statut vide --}}
            @foreach ($commissionagrement->commissionmembres as $membre)
                @if (empty($membre->statut))
                    <li><b>{{ $membre->civilite . ' ' . $membre->prenom . ' ' . $membre->nom }}</b>,
                        {{ $membre->fonction . ' - ' }}
                        <i>{{ $membre->structure }}</i>
                    </li>
                @endif
            @endforeach
        </ul>

        <p>
            {{ $commissionagrement?->chef?->civilite }}
            <strong>{{ $commissionagrement->chef->prenom . ' ' . $commissionagrement->chef->nom }}</strong>,
            {{ strtolower($commissionagrement->chef->statut) }} de séance, après avoir constaté la présence de tous les
            membres, a procédé à l’ouverture des travaux.
        </p>

        <p>
            Donnant la parole à la Directrice de la <strong>Direction des Évaluations et Certifications (DEC)</strong>
            de l’ONFP, cette dernière a rappelé le contexte de la tenue de cette commission, ses modalités, ses
            objectifs et les résultats attendus.
        </p>

        <h4>Examen des demandes</h4>

        <p>
            @if (!empty($countRenouvellements) && $countRenouvellements > 0)
                Au total <strong>{{ $countOperateurs ?? '(...)' }}</strong> demandes ont été soumises à
                l’examen des membres de la commission, dont <strong>{{ $countRenouvellements ?? '(...)' }}</strong>
                pour des renouvellements.
            @else
                Au total <strong>{{ $countOperateurs ?? '(...)' }}</strong> demandes ont été soumises à
                l’examen des membres de la commission.
            @endif
        </p>

        <p>
            À l’issue des travaux, il a été retenu :
        </p>

        @if (!empty($countRenouvellements) && $countRenouvellements > 0)
            <p><strong>Pour les demandes de renouvellement :</strong></p>
            <ul>
                @if (!empty($countRenouvellements_agreer) && $countRenouvellements_agreer > 0)
                    <li>{{ $countRenouvellements_agreer }} structures proposées à l’agrément définitif;</li>
                @endif

                @if (!empty($countRenouvellements_sr) && $countRenouvellements_sr > 0)
                    <li>{{ $countRenouvellements_sr }} proposées à l’agrément sous réserve de complément ou de mise à
                        jour de pièces du dossier;</li>
                @endif

                @if (!empty($countRenouvellements_rejet) && $countRenouvellements_rejet > 0)
                    <li>{{ $countRenouvellements_rejet }} demandes proposées au rejet.</li>
                @endif
            </ul>

        @endif

        <p><strong>Pour les nouvelles demandes :</strong></p>
        <ul>
            @if (!empty($countNouvelles_agreer) && $countNouvelles_agreer > 0)
                <li>{{ $countNouvelles_agreer }} structures proposées à l’agrément définitif;</li>
            @endif

            @if (!empty($countNouvelles_sr) && $countNouvelles_sr > 0)
                <li>{{ $countNouvelles_sr }} proposées à l’agrément sous réserve de complément ou de mise à jour de
                    pièces du dossier;</li>
            @endif

            @if (!empty($countNouvelles_rejet) && $countNouvelles_rejet > 0)
                <li>{{ $countNouvelles_rejet }} demandes proposées au rejet.</li>
            @endif
        </ul>

        <h4>Recommandations</h4>
        <p>
            {!! nl2br(e($commissionagrement?->recommandations ?? 'Aucune recommandation particulière n’a été formulée.')) !!}
        </p>

        <div class="footer">
            <p>Fait à Dakar, le {{ $commissionagrement?->fin_commission?->format('d/m/Y') ?? '6 décembre 2024' }}</p>
        </div>

        <table class="signature">
            <tr>
                <td><strong>Le Président de séance
                        :</strong><br><br><br><br>{{ $commissionagrement->chef->civilite . ' ' . $commissionagrement->chef->prenom . ' ' . $commissionagrement->chef->nom }}
                </td>
                <td class="right"><strong>Le Secrétaire de séance :</strong><br><br><br><br>
                    {{ $commissionagrement->secretaire->civilite . ' ' . $commissionagrement->secretaire->prenom . ' ' . $commissionagrement->secretaire->nom }}
                </td>
            </tr>
        </table>

        <h4 class="h4 mt-3"><br>Les autres membres de la commission</h4>
        <table class="signature">
            @foreach ($commissionagrement->commissionmembres as $membre)
                @if (
                    $membre->id !== $commissionagrement->chef_id &&
                        $membre->id !== $commissionagrement->secretaire_id &&
                        !empty($membre->statut))
                    <tr>
                        <td><strong>{{ $membre->civilite . ' ' . $membre->prenom . ' ' . $membre->nom }}
                            </strong><br><br><br></td>
                    </tr>
                @endif
            @endforeach
        </table>

    </div>

</body>

</html>
