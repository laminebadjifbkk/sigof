<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Demande de prise en charge envoyée – ONFP</title>

    <style>
        :root {
            --bg: #f3f5f2;
            --paper: #ffffff;
            --ink: #1c2421;
            --muted: #5d6a64;
            --line: #dde3df;
            --green: #1f6b3a;
            --green-soft: #e6f2ea;
            --red: #b3261e;
            --amber-soft: #fff3d6;
            --amber: #7a5200;
        }

        @media (prefers-color-scheme: dark) {
            :root:not([data-print]) {
                --bg: #121715;
                --paper: #1a211e;
                --ink: #e8eeea;
                --muted: #9aa8a0;
                --line: #2d3733;
                --green: #5fbf82;
                --green-soft: #1f3327;
                --red: #f2857d;
                --amber-soft: #3a2f12;
                --amber: #f0c66b;
            }
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        html {
            -webkit-text-size-adjust: 100%;
        }

        body {
            margin: 0;
            background: var(--bg);
            color: var(--ink);
            font: 16px/1.55 system-ui, -apple-system, "Segoe UI", Roboto, Arial, sans-serif;
        }

        a {
            color: inherit;
        }

        :focus-visible {
            outline: 3px solid var(--green);
            outline-offset: 2px;
        }

        .page {
            max-width: 760px;
            margin: 0 auto;
            padding: 32px 16px 56px;
        }

        .receipt {
            background: var(--paper);
            border: 1px solid var(--line);
            border-radius: 10px;
            padding: 32px clamp(18px, 5vw, 44px);
        }

        /* En-tête */
        .head {
            text-align: center;
            padding-bottom: 24px;
            border-bottom: 1px solid var(--line);
        }

        .head svg {
            display: block;
            margin: 0 auto 12px;
        }

        .head h1 {
            margin: 0;
            font-size: clamp(1.35rem, 3.5vw, 1.75rem);
            line-height: 1.25;
        }

        .head p {
            margin: 10px auto 0;
            max-width: 56ch;
            color: var(--muted);
        }

        .head p strong {
            color: var(--ink);
            overflow-wrap: anywhere;
        }

        /* Numéro de dossier */
        .ref {
            display: flex;
            align-items: baseline;
            justify-content: center;
            gap: 12px;
            flex-wrap: wrap;
            margin: 24px 0 8px;
            padding: 14px 18px;
            background: var(--green-soft);
            border-radius: 8px;
        }

        .ref-label {
            color: var(--muted);
            font-size: .95rem;
        }

        .ref-value {
            font-size: 1.6rem;
            font-weight: 700;
            letter-spacing: .04em;
            color: var(--green);
            font-variant-numeric: tabular-nums;
        }

        /* Blocs récapitulatifs */
        .block {
            padding-top: 24px;
        }

        .block h2 {
            margin: 0 0 10px;
            font-size: 1.05rem;
            color: var(--green);
        }

        dl.rows {
            margin: 0;
            border-top: 1px solid var(--line);
        }

        .row {
            display: grid;
            grid-template-columns: minmax(130px, 210px) 1fr;
            gap: 4px 16px;
            padding: 9px 0;
            border-bottom: 1px solid var(--line);
        }

        .row dt {
            color: var(--muted);
            font-size: .93rem;
        }

        .row dd {
            margin: 0;
            overflow-wrap: anywhere;
        }

        .files {
            list-style: none;
            margin: 0;
            padding: 0;
            border-top: 1px solid var(--line);
        }

        .files li {
            display: flex;
            gap: 10px;
            padding: 9px 0;
            border-bottom: 1px solid var(--line);
        }

        .ok,
        .ko {
            font-weight: 700;
            width: 1.2em;
            flex: none;
        }

        .ok {
            color: var(--green);
        }

        .ko {
            color: var(--red);
        }

        .badge {
            display: inline-block;
            padding: 2px 10px;
            border-radius: 999px;
            background: var(--amber-soft);
            color: var(--amber);
            font-size: .88rem;
            font-weight: 600;
        }

        .note {
            margin: 28px 0 0;
            color: var(--muted);
            font-size: .9rem;
        }

        /* Actions */
        .actions {
            display: flex;
            justify-content: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-top: 24px;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            border: 1px solid var(--green);
            border-radius: 6px;
            font: inherit;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            background: transparent;
            color: var(--green);
        }

        .btn-primary {
            background: var(--green);
            color: #fff;
        }

        .btn:hover {
            filter: brightness(.94);
        }

        @media (max-width: 520px) {
            .row {
                grid-template-columns: 1fr;
            }
        }

        /* Impression : seul le reçu est imprimé, en clair */
        @media print {
            :root {
                --bg: #fff;
                --paper: #fff;
                --ink: #000;
                --muted: #444;
                --line: #bbb;
                --green: #1f6b3a;
                --green-soft: #fff;
                --red: #b3261e;
                --amber-soft: #fff;
                --amber: #000;
            }

            body {
                font-size: 12pt;
            }

            .page {
                padding: 0;
                max-width: none;
            }

            .receipt {
                border: 0;
                padding: 0;
            }

            .actions {
                display: none;
            }

            .block {
                break-inside: avoid;
            }

            .ref {
                border: 1px solid #000;
            }

            .badge {
                border: 1px solid #000;
            }
        }
    </style>
</head>

<body>
    @php
        $fcfa = fn($v) => number_format((float) $v, 0, ',', ' ') . ' FCFA';
        $oui = fn($v) => strtolower(trim((string) $v)) === 'oui';
        // CIN partiellement masqué (affichage + impression)
        $cin = (string) $formulaire->cin;
        $cinMasque = str_repeat('•', max(strlen($cin) - 4, 0)) . substr($cin, -4);
        $telephones = collect([$formulaire->telephone, $formulaire->telephone_secondaire])
            ->filter()
            ->implode(' / ');
        $pieces = [
            'cin_file' => "Copie de la pièce d'identité",
            'cv' => 'Curriculum vitae',
            'diplome' => 'Diplômes / certificats',
            'facture_file' => 'Facture proforma',
        ];
    @endphp

    <main class="page">
        <article class="receipt" id="printable-receipt">

            <header class="head">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <circle cx="12" cy="12" r="11" stroke="#2e7d32" stroke-width="1.5" fill="#e8f5e9" />
                    <path d="M7 12.5l3 3 7-7" stroke="#2e7d32" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
                <h1>Demande de prise en charge envoyée avec succès</h1>
                <p>
                    Merci {{ $formulaire->civilite }} {{ $formulaire->prenom }} {{ $formulaire->nom }}, votre
                    dossier a bien été enregistré. L'ONFP va l'examiner et vous répondra à l'adresse
                    <strong>{{ $formulaire->email }}</strong>.
                </p>
            </header>

            <div class="ref">
                <span class="ref-label">Numéro de dossier</span>
                <span class="ref-value">#{{ str_pad($formulaire->id, 6, '0', STR_PAD_LEFT) }}</span>
            </div>

            {{-- Candidat --}}
            <section class="block">
                <h2>Informations du candidat</h2>
                <dl class="rows">
                    <div class="row">
                        <dt>Nom complet</dt>
                        <dd>{{ $formulaire->civilite }} {{ $formulaire->prenom }} {{ $formulaire->nom }}</dd>
                    </div>
                    <div class="row">
                        <dt>N° pièce d'identité</dt>
                        <dd>{{ $cinMasque }}</dd>
                    </div>
                    <div class="row">
                        <dt>Naissance</dt>
                        <dd>Le {{ \Carbon\Carbon::parse($formulaire->date_naissance)->format('d/m/Y') }} à
                            {{ $formulaire->lieu_naissance }}</dd>
                    </div>
                    <div class="row">
                        <dt>Téléphones</dt>
                        <dd>{{ $telephones }}</dd>
                    </div>
                    <div class="row">
                        <dt>Adresse</dt>
                        <dd>{{ $formulaire->adresse }}</dd>
                    </div>
                    <div class="row">
                        <dt>Dernier diplôme</dt>
                        <dd>{{ $formulaire->dernier_diplome }}</dd>
                    </div>
                </dl>
            </section>

            {{-- Formation --}}
            <section class="block">
                <h2>Formation demandée</h2>
                <dl class="rows">
                    <div class="row">
                        <dt>Formation</dt>
                        <dd>{{ $formulaire->formation }}</dd>
                    </div>
                    <div class="row">
                        <dt>Diplôme visé</dt>
                        <dd>{{ $formulaire->diplome_vise }}</dd>
                    </div>
                    <div class="row">
                        <dt>Durée</dt>
                        <dd>{{ $formulaire->duree }} {{ $formulaire->duree > 1 ? 'ans' : 'an' }}</dd>
                    </div>
                    <div class="row">
                        <dt>Région</dt>
                        <dd>{{ $formulaire->region }}</dd>
                    </div>
                </dl>
            </section>

            {{-- Établissement --}}
            <section class="block">
                <h2>Établissement de formation</h2>
                <dl class="rows">
                    <div class="row">
                        <dt>Nom</dt>
                        <dd>{{ $formulaire->nom_etablissement }}
                            @if ($formulaire->autre_2)
                                ({{ $formulaire->autre_2 }})
                            @endif
                        </dd>
                    </div>
                    <div class="row">
                        <dt>Adresse</dt>
                        <dd>{{ $formulaire->adresse_etablessement }}</dd>
                    </div>
                    <div class="row">
                        <dt>Téléphone</dt>
                        <dd>{{ $formulaire->telephone_etablissement }}</dd>
                    </div>
                </dl>
            </section>

            {{-- Frais --}}
            <section class="block">
                <h2>Frais de scolarité (facture proforma)</h2>
                <dl class="rows">
                    <div class="row">
                        <dt>Inscription</dt>
                        <dd>{{ $fcfa($formulaire->montant_inscription) }}</dd>
                    </div>
                    <div class="row">
                        <dt>Mensualité</dt>
                        <dd>{{ $fcfa($formulaire->montant_mensualite) }}</dd>
                    </div>
                    @if ($formulaire->montant_unique)
                        <div class="row">
                            <dt>Montant unique</dt>
                            <dd>{{ $fcfa($formulaire->montant_unique) }}</dd>
                        </div>
                    @endif
                </dl>
            </section>

            {{-- Situation particulière --}}
            <section class="block">
                <h2>Situation particulière</h2>
                <dl class="rows">
                    <div class="row">
                        <dt>Situation de handicap</dt>
                        <dd>{{ $formulaire->handicape }}
                            @if ($oui($formulaire->handicape) && $formulaire->type_handicap)
                                ({{ $formulaire->type_handicap }})
                            @endif
                        </dd>
                    </div>
                    <div class="row">
                        <dt>Orphelin(e)</dt>
                        <dd>{{ $formulaire->orphelin }}
                            @if ($oui($formulaire->orphelin) && $formulaire->type_orphelin)
                                ({{ $formulaire->type_orphelin }})
                            @endif
                        </dd>
                    </div>
                </dl>
            </section>

            {{-- Pièces jointes --}}
            <section class="block">
                <h2>Pièces déposées</h2>
                <ul class="files">
                    @foreach ($pieces as $champ => $libelle)
                        <li>
                            <span class="{{ $formulaire->$champ ? 'ok' : 'ko' }}"
                                aria-hidden="true">{{ $formulaire->$champ ? '✔' : '✘' }}</span>
                            <span>{{ $libelle }}
                                <span class="sr-only" style="position:absolute;left:-9999px">
                                    {{ $formulaire->$champ ? '(fourni)' : '(non fourni)' }}</span></span>
                        </li>
                    @endforeach
                </ul>
            </section>

            {{-- Dépôt et statut --}}
            <section class="block">
                <dl class="rows">
                    <div class="row">
                        <dt>Date de dépôt</dt>
                        <dd>{{ $formulaire->created_at->timezone('Africa/Dakar')->format('d/m/Y à H:i') }}</dd>
                    </div>
                    <div class="row">
                        <dt>Statut</dt>
                        <dd><span class="badge">En attente d'examen</span></dd>
                    </div>
                </dl>
            </section>

            <p class="note">
                Conservez ce numéro de dossier : il pourra vous être demandé pour toute question sur votre
                demande. Contact : <a href="mailto:onfp@onfp.sn">onfp@onfp.sn</a> · 33 827 92 51.
            </p>
        </article>

        <div class="actions">
            <a href="{{ url('/') }}" class="btn">Retour à l'accueil</a>
            <button type="button" class="btn btn-primary" onclick="window.print()">Imprimer le récapitulatif</button>
        </div>
    </main>
</body>

</html>
