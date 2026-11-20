{{-- resources/views/verification/resultat.blade.php --}}
@php
    $themes = [
        'admis' => [
            'color' => '#00853f',
            'soft' => '#e8f6ee',
            'icon' => 'bi-check-lg',
            'badge' => 'Admis(e)',
            'title' => 'Félicitations, ' . $formulaire->prenom . ' !',
            'text' => 'Votre dossier a été retenu pour une prise en charge par l\'ONFP.',
        ],
        'rejete' => [
            'color' => '#e11d2e',
            'soft' => '#fdecee',
            'icon' => 'bi-x-lg',
            'badge' => 'Non retenu(e)',
            'title' => 'Bonjour ' . $formulaire->prenom,
            'text' => 'Nous sommes au regret de vous informer que votre dossier n\'a pas été retenu cette fois-ci.',
        ],
        'Non conforme' => [
            'color' => '#e11d2e',
            'soft' => '#fdecee',
            'icon' => 'bi-x-lg',
            'badge' => 'Non retenu(e)',
            'title' => 'Bonjour ' . $formulaire->prenom,
            'text' => 'Nous sommes au regret de vous informer que votre dossier n\'a pas été retenu cette fois-ci.',
        ],
        'attente' => [
            'color' => '#d97706',
            'soft' => '#fef3e2',
            'icon' => 'bi-hourglass-split',
            'badge' => 'En cours de traitement',
            'title' => 'Bonjour ' . $formulaire->prenom,
            'text' => 'Votre dossier est en cours de traitement. Les résultats ne sont pas encore disponibles.',
        ],
    ];
    $t = $themes[$etat];

    $details = array_filter([
        'Formation' => $formulaire->formation,
        'Diplôme visé' => $formulaire->diplome_vise,
        'Région' => $formulaire->region,
        'Année scolaire' => $formulaire->annee_scolaire,
        'Durée' => $formulaire->duree . ' an(s)',
    ], fn($v) => filled($v));

    // Email partiellement masqué : a•••@gmail.com
    $emailMasque = null;
    if (filled($formulaire->email) && str_contains($formulaire->email, '@')) {
        [$local, $domaine] = explode('@', $formulaire->email, 2);
        $emailMasque = mb_substr($local, 0, 2) . str_repeat('•', max(mb_strlen($local) - 2, 3)) . '@' . $domaine;
    }
@endphp
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Résultat de votre dossier · SIGOF</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --c:
                {{ $t['color'] }}
            ;
            --soft:
                {{ $t['soft'] }}
            ;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 1.5rem;
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
            color: #0f172a;
            background:
                radial-gradient(900px 400px at 85% -10%, var(--soft), transparent 70%),
                #f8fafc;
        }

        .result {
            width: 100%;
            max-width: 560px;
            overflow: hidden;
            background: #fff;
            border-radius: 22px;
            box-shadow: 0 24px 60px rgba(15, 23, 42, .14);
            border: 1px solid rgba(15, 23, 42, .06);
        }

        .result::before {
            content: "";
            display: block;
            height: 5px;
            background: linear-gradient(90deg, #00853f 0 33%, #fdef42 33% 66%, #e31b23 66% 100%);
        }

        .result__head {
            padding: 2rem 1.8rem 1.4rem;
            text-align: center;
            background: linear-gradient(180deg, var(--soft), #fff);
        }

        .result__icon {
            display: grid;
            place-items: center;
            width: 84px;
            height: 84px;
            margin: 0 auto 1rem;
            font-size: 2.6rem;
            color: #fff;
            background: var(--c);
            border-radius: 50%;
            box-shadow: 0 0 0 10px var(--soft), 0 12px 28px color-mix(in srgb, var(--c) 40%, transparent);
            animation: pop .55s cubic-bezier(.2, 1.4, .4, 1) both;
        }

        @keyframes pop {
            from {
                transform: scale(.3);
                opacity: 0;
            }

            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        .badge {
            display: inline-block;
            padding: .3rem .85rem;
            font-size: .78rem;
            font-weight: 800;
            letter-spacing: .05em;
            text-transform: uppercase;
            color: var(--c);
            background: var(--soft);
            border: 1.5px solid var(--c);
            border-radius: 999px;
        }

        h1 {
            margin: .8rem 0 .4rem;
            font-size: 1.6rem;
            font-weight: 800;
        }

        .result__head p {
            margin: 0 auto;
            max-width: 420px;
            color: #475569;
            line-height: 1.5;
        }

        .result__body {
            padding: 1.2rem 1.8rem 1.8rem;
        }

        .identity {
            display: flex;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
            padding: .9rem 1rem;
            margin-bottom: 1rem;
            font-size: .85rem;
            color: #475569;
            background: #f8fafc;
            border-radius: 12px;
        }

        .identity strong {
            display: block;
            color: #0f172a;
            font-size: .95rem;
        }

        .school {
            display: flex;
            align-items: center;
            gap: .9rem;
            padding: .9rem 1rem;
            margin-bottom: .6rem;
            background: var(--soft);
            border: 1.5px solid var(--c);
            border-radius: 12px;
        }

        .school__icon {
            flex: none;
            display: grid;
            place-items: center;
            width: 42px;
            height: 42px;
            font-size: 1.2rem;
            color: #fff;
            background: var(--c);
            border-radius: 10px;
        }

        .school small {
            display: block;
            font-size: .72rem;
            font-weight: 700;
            letter-spacing: .04em;
            text-transform: uppercase;
            color: #64748b;
        }

        .school strong {
            font-size: 1.02rem;
        }

        .steps {
            margin: .6rem 0 0;
            padding-left: 1.2rem;
        }

        .steps li {
            margin-bottom: .5rem;
        }

        .steps li:last-child {
            margin-bottom: 0;
        }

        dl {
            margin: 0;
        }

        .row {
            display: flex;
            justify-content: space-between;
            gap: 1rem;
            padding: .7rem 0;
            border-bottom: 1px solid #eef2f6;
        }

        .row:last-child {
            border-bottom: 0;
        }

        dt {
            color: #64748b;
            font-size: .85rem;
        }

        dd {
            margin: 0;
            font-weight: 700;
            text-align: right;
            font-size: .92rem;
        }

        .next {
            margin-top: 1.2rem;
            padding: 1rem 1.1rem;
            font-size: .88rem;
            line-height: 1.55;
            color: #334155;
            background: var(--soft);
            border-left: 4px solid var(--c);
            border-radius: 10px;
        }

        .next strong {
            color: var(--c);
        }

        .actions {
            display: flex;
            gap: .7rem;
            flex-wrap: wrap;
            margin-top: 1.4rem;
        }

        .btn {
            flex: 1;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            gap: .5rem;
            min-width: 160px;
            height: 46px;
            padding: 0 1rem;
            font: inherit;
            font-weight: 700;
            font-size: .92rem;
            text-decoration: none;
            cursor: pointer;
            border-radius: 12px;
            transition: transform .2s;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .btn-main {
            color: #fff;
            background: var(--c);
            border: 0;
        }

        .btn-ghost {
            color: #334155;
            background: #fff;
            border: 1.5px solid #e2e8f0;
        }

        @media print {
            body {
                background: #fff;
                padding: 0;
            }

            .result {
                box-shadow: none;
                border: 0;
            }

            .actions {
                display: none;
            }
        }
    </style>
</head>

<body>
    <main class="result">
        <header class="result__head">
            <div class="result__icon"><i class="bi {{ $t['icon'] }}"></i></div>
            <span class="badge">{{ $t['badge'] }}</span>
            <h1>{{ $t['title'] }}</h1>
            <p>{{ $t['text'] }}</p>
        </header>

        <section class="result__body">
            <div class="identity">
                <div>Candidat(e)<strong>{{ $formulaire->civilite }} {{ $formulaire->prenom }}
                        {{ strtoupper($formulaire->nom) }}</strong></div>
                <div>N° d'identité<strong>{{ $cinMasque }}</strong></div>
            </div>

            @if ($etat === 'admis' && filled($formulaire->nom_etablissement))
                <div class="school">
                    <span class="school__icon"><i class="bi bi-building"></i></span>
                    <div>
                        <small>Établissement d'accueil</small>
                        <strong>{{ $formulaire->nom_etablissement }}</strong>
                    </div>
                </div>
            @endif

            @if ($etat === 'admis' && count($details))
                <dl>
                    @foreach ($details as $label => $value)
                        <div class="row">
                            <dt>{{ $label }}</dt>
                            <dd>{{ $value }}</dd>
                        </div>
                    @endforeach
                </dl>
            @endif

            <div class="next">
                @if ($etat === 'admis')
                    <strong>Prochaines étapes :</strong>
                    <ol class="steps">
                        <li>
                            Rapprochez-vous de votre <strong>établissement d'accueil</strong>
                            @if (filled($formulaire->nom_etablissement))
                                (<em>{{ $formulaire->nom_etablissement }}</em>)
                            @endif
                            avec votre pièce d'identité pour finaliser votre inscription.
                        </li>
                        <li>
                            Revenez ensuite sur la plateforme <strong>SIGOF</strong> et <strong>ouvrez un compte</strong>
                            avec l'adresse email de votre dossier
                            @if ($emailMasque)
                                (<strong>{{ $emailMasque }}</strong>)
                            @endif
                            .
                        </li>
                        <li>
                            <strong>Connectez-vous</strong> puis <strong>téléversez votre certificat d'inscription
                                ou votre reçu</strong> pour valider définitivement votre prise en charge.
                        </li>
                    </ol>
                @elseif ($etat === 'rejete')
                    <strong>Et maintenant ?</strong> Vous pouvez soumettre un nouveau dossier lors des prochaines
                    sessions ou contacter l'ONFP pour plus d'informations.
                @else
                    <strong>À savoir :</strong> revenez consulter cette page plus tard. Vous serez informé(e) dès que
                    votre dossier aura été traité.
                @endif
            </div>

            <div class="actions">
                @if ($etat === 'admis')
                    <a href="{{ route('register-page') }}" class="btn btn-main">
                        <i class="bi bi-person-plus"></i> Créer mon compte
                    </a>
                    <button type="button" class="btn btn-ghost" onclick="window.print()">
                        <i class="bi bi-printer"></i> Imprimer
                    </button>
                @endif
                <a href="{{ url('/') }}#verifierprisencharge" class="btn btn-ghost">
                    <i class="bi bi-arrow-left"></i> Retour
                </a>
            </div>
        </section>
    </main>
</body>

</html>
