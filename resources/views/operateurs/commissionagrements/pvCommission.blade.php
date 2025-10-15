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
            Les {{ $jours }} {{ $moisAnnee }}, s’est tenue au
            {{ $commissionagrement?->lieu ?? 'Centre de Formation Professionnelle Sénégal-Japon de Dakar' }},
            la session {{ $commissionagrement?->date?->format('Y') ?? now()->format('Y') }} de la Commission d’Agrément
            et de Labélisation
            (CAL) des opérateurs de formation de l’Office National de Formation Professionnelle (ONFP).
        </p>

        <p>
            Sont présents et ont émargé à la feuille de présence :
        </p>

        <ul>
            {{-- <li>{{ $commissionagrement->chef->civilite . ' ' . $commissionagrement->chef->prenom . ' ' . $commissionagrement->chef->nom . ',' . $commissionagrement->chef->fonction }}
            </li>
            <li>{{ $commissionagrement->secretaire->civilite . ' ' . $commissionagrement->secretaire->prenom . ' ' . $commissionagrement->secretaire->nom . ',' . $commissionagrement->secretaire->fonction }}
            </li> --}}
            @foreach ($commissionagrement->commissionmembres as $membre)
                <li>{{ $membre->civilite . ' ' . $membre->prenom . ' ' . $membre->nom . ', ' . $membre->fonction }}</li>
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
            Un total de <strong>{{ $countOperateurs ?? '(...)' }}</strong> demandes ont été soumises à
            l’examen
            des membres de la commission, dont
            <strong>{{ $countRenouvellements ?? '(...)' }}</strong> pour des renouvellements.
        </p>

        <p>
            À l’issue des travaux, il a été retenu :
        </p>

        @if (!empty($countRenouvellements))
            <p><strong>Pour les demandes de renouvellement :</strong></p>
            <ul>
                <li>{{ $countRenouvellements_agreer ?? '(...)' }} structures proposées à l’agrément
                    définitif ;</li>
                <li>{{ $countRenouvellements_sr ?? '(...)' }} proposées à l’agrément sous réserve
                    de
                    complément ou de mise à jour de pièces du dossier ;</li>
                <li>{{ $countRenouvellements_rejet ?? '(...)' }} demandes proposées au rejet.</li>
            </ul>
        @endif

        <p><strong>Pour les nouvelles demandes :</strong></p>
        <ul>
            <li>{{ $countNouvelles_agreer ?? '(...)' }} structures proposées à l’agrément
                définitif ;</li>
            <li>{{ $countNouvelles_sr ?? '(...)' }} proposées à l’agrément sous réserve de
                complément ou de mise à jour de pièces du dossier ;</li>
            <li>{{ $countNouvelles_rejet ?? '(...)' }} demandes proposées au rejet.</li>
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

        <h4 class="h4 mt-3">Les autres membres de la commission</h4>
        <table class="signature">
            @foreach ($commissionagrement->commissionmembres as $membre)
                @if ($membre->id !== $commissionagrement->chef_id && $membre->id !== $commissionagrement->secretaire_id)
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
