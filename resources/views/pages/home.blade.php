@extends('layouts.app')

@section('title', 'ONFP - Youth Linguists Programme (YLP)')

@section('content')

    <div class="hero">
        <svg class="hero-rings-bg" width="520" height="420" viewBox="0 0 520 420"
            style="position:absolute; right:-60px; top:-40px;">
            <circle cx="120" cy="90" r="70" fill="none" stroke="var(--gold)" stroke-width="14" />
            <circle cx="240" cy="90" r="70" fill="none" stroke="var(--green)" stroke-width="14" />
            <circle cx="360" cy="90" r="70" fill="none" stroke="var(--brick)" stroke-width="14" />
            <circle cx="180" cy="190" r="70" fill="none" stroke="var(--navy)" stroke-width="14" />
            <circle cx="300" cy="190" r="70" fill="none" stroke="var(--cream)" stroke-width="14" />
        </svg>
        <div class="container">
            <div>
                <p class="eyebrow">Youth Linguists Programme (YLP)</p>
                <h1>Devenez la voix<br>des <em>Jeux</em> de la jeunesse.</h1>
                <p class="eyebrow">Partenariat ONFP × COJOJ - Dakar 2026</p>
                <p class="lead">
                    Cette plateforme ouvre les inscriptions au programme de formation et de mobilisation des Junior
                    Linguists Operators (JLO) (interprètes de liaison) appelés à accompagner les délégations des Jeux
                    Olympiques de la Jeunesse Dakar 2026.</p>
                <div class="hero-ctas">
                    <a href="{{ route('inscription') }}" class="btn btn-primary">Je m'inscris comme JLO</a>
                    <a href="{{ route('connexion') }}" class="btn btn-ghost">Accéder à mon espace</a>
                </div>

                {{-- Compte à rebours vers l'ouverture des Jeux (31 octobre 2026, 00:00 UTC = heure de Dakar).
             $gamesOpeningDate est passé par PageController@home ; valeur de secours ci-dessous si absent. --}}
                <div class="countdown" id="heroCountdown"
                    data-target="{{ $gamesOpeningDate ?? '2026-10-01T00:00:00+00:00' }}" role="timer" aria-live="off"
                    aria-label="Compte à rebours avant le démarrage des formations">
                    <div class="cd-item"><span class="cd-num" data-cd="days">00</span><span class="cd-label">Jours</span>
                    </div>
                    <span class="cd-sep">:</span>
                    <div class="cd-item"><span class="cd-num" data-cd="hours">00</span><span class="cd-label">Heures</span>
                    </div>
                    <span class="cd-sep">:</span>
                    <div class="cd-item"><span class="cd-num" data-cd="minutes">00</span><span class="cd-label">Min</span>
                    </div>
                    <span class="cd-sep">:</span>
                    <div class="cd-item"><span class="cd-num" data-cd="seconds">00</span><span class="cd-label">Sec</span>
                    </div>
                </div>
                <p class="countdown-caption">avant le démarrage des formations, 01 octobre 2026</p>
            </div>
            <div class="hero-visual">
                <div class="hero-bubble-card">
                    <div class="lang-dots">
                        <span style="background:var(--gold)"></span>
                        <span style="background:var(--green)"></span>
                        <span style="background:var(--brick)"></span>
                        <span style="background:var(--navy)"></span>
                        <span style="background:var(--black)"></span>
                    </div>
                    <p class="quote">« Afrig Dalal, Ndakaaru Jëmël - l'Afrique accueille, Dakar célèbre. Chaque langue
                        parlée est une porte ouverte sur les Jeux. »</p>
                    <p class="who">Motto Dakar 2026</p>
                </div>
            </div>
        </div>
    </div>

    <div class="stat-strip">
        <div class="container">
            <div class="stat">
                <div class="num">40</div>
                <div class="label">postes de Junior Linguist Operators à pourvoir</div>
            </div>
            <div class="stat">
                <div class="num">10</div>
                <div class="label">Langues de spécialisation</div>
            </div>
            <div class="stat">
                <div class="num">21-35</div>
                <div class="label">Tranche d'âge éligible</div>
            </div>
            <div class="stat">
                <div class="num">31 oct.</div>
                <div class="label">Ouverture des Jeux - 2026</div>
            </div>
        </div>
    </div>

    <section class="block">
        <div class="container">
            <div class="block-head">
                <p class="eyebrow">Le programme</p>
                <h2>Un parcours en trois temps</h2>
                <p>
                    De l'inscription en ligne à la mobilisation au sein des délégations, le SIGOF centralise chaque étape du
                    parcours des Junior Linguist Operators formés par l'ONFP pour le compte du COJOJ.
                </p>
            </div>
            <div class="cards-3">
                <div class="prog-card">
                    <svg class="icon-bubble" viewBox="0 0 38 34">
                        <use href="#bulle-teranga" fill="var(--gold)" />
                    </svg>
                    <h3>Formation</h3>
                    <p>Modules linguistiques et culturels dispensés par l'ONFP, adaptés aux exigences protocolaires du
                        COJOJ.
                    </p>
                </div>
                <div class="prog-card">
                    <svg class="icon-bubble" viewBox="0 0 38 34">
                        <use href="#bulle-teranga" fill="var(--green)" />
                    </svg>
                    <h3>Mobilisation</h3>
                    <p>Affectation des Junior Linguist Operators formés au sein des délégations et des sites de compétition.
                    </p>
                </div>
                <div class="prog-card">
                    <svg class="icon-bubble" viewBox="0 0 38 34">
                        <use href="#bulle-teranga" fill="var(--brick)" />
                    </svg>
                    <h3>Accompagnement</h3>
                    <p>Suivi individualisé et traitement centralisé des données via le tableau de bord SIGOF.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="block" style="padding-top:0;">
        <div class="container">
            <div class="block-head">
                <p class="eyebrow">YLP - Critères de sélection par langue</p>
                <h2>10 langues de spécialisation, 40 postes ouverts</h2>
                <p>Chaque poste correspond à une langue de spécialisation (LV1). Le niveau requis est évalué à la fois dans
                    cette langue et en français, langue de travail du programme.</p>
                <p class="trilingue-note">
                    Les profils Arabe, Espagnol, Portugais, Chinois, Japonais, Coréen, Allemand, Russe et Italien sont
                    des profils <strong>trilingues</strong> : Français + Anglais, en plus de leur langue de spécialisation.
                </p>
            </div>

            <div class="lang-grid">
                <div class="lang-card">
                    <div class="lc-top">
                        <span class="lc-name">Anglais<br><small class="lc-profile">Profil bilingue</small></span>
                        <span class="lc-posts">3 postes</span>
                    </div>
                    <div class="lc-row"><span>Niveau langue</span><b>C1</b></div>
                    <div class="lc-row"><span>Niveau français</span><b>C1</b></div>
                    <div class="lc-cert">TOEIC · IELTS · TOEFL</div>
                </div>
                <div class="lang-card">
                    <div class="lc-top">
                        <span class="lc-name">Arabe<br><small class="lc-profile">Profil trilingue</small></span>
                        <span class="lc-posts">6 postes</span>
                    </div>
                    <div class="lc-row"><span>Niveau langue</span><b>C1</b></div>
                    <div class="lc-row"><span>Niveau français</span><b>C1/B2</b></div>
                    <div class="lc-cert">ALPT</div>
                </div>
                <div class="lang-card">
                    <div class="lc-top">
                        <span class="lc-name">Espagnol<br><small class="lc-profile">Profil trilingue</small></span>
                        <span class="lc-posts">7 postes</span>
                    </div>
                    <div class="lc-row"><span>Niveau langue</span><b>C1</b></div>
                    <div class="lc-row"><span>Niveau français</span><b>C1/B2</b></div>
                    <div class="lc-cert">DELE</div>
                </div>
                <div class="lang-card">
                    <div class="lc-top">
                        <span class="lc-name">Portugais<br><small class="lc-profile">Profil trilingue</small></span>
                        <span class="lc-posts">4 postes</span>
                    </div>
                    <div class="lc-row"><span>Niveau langue</span><b>C1</b></div>
                    <div class="lc-row"><span>Niveau français</span><b>C1/B2</b></div>
                    <div class="lc-cert">CAPLE</div>
                </div>
                <div class="lang-card">
                    <div class="lc-top">
                        <span class="lc-name">Chinois<br><small class="lc-profile">Mandarin · Profil
                                trilingue</small></span>
                        <span class="lc-posts">4 postes</span>
                    </div>
                    <div class="lc-row"><span>Niveau langue</span><b>C1</b></div>
                    <div class="lc-row"><span>Niveau français</span><b>C1/B2</b></div>
                    <div class="lc-cert">HSK 5+</div>
                </div>
                <div class="lang-card">
                    <div class="lc-top">
                        <span class="lc-name">Japonais<br><small class="lc-profile">Profil trilingue</small></span>
                        <span class="lc-posts">4 postes</span>
                    </div>
                    <div class="lc-row"><span>Niveau langue</span><b>C1</b></div>
                    <div class="lc-row"><span>Niveau français</span><b>C1/B2</b></div>
                    <div class="lc-cert">JLPT N2+</div>
                </div>
                <div class="lang-card">
                    <div class="lc-top">
                        <span class="lc-name">Coréen<br><small class="lc-profile">Profil trilingue</small></span>
                        <span class="lc-posts">2 postes</span>
                    </div>
                    <div class="lc-row"><span>Niveau langue</span><b>C1</b></div>
                    <div class="lc-row"><span>Niveau français</span><b>C1/B2</b></div>
                    <div class="lc-cert">TOPIK 4+</div>
                </div>
                <div class="lang-card">
                    <div class="lc-top">
                        <span class="lc-name">Allemand<br><small class="lc-profile">Profil trilingue</small></span>
                        <span class="lc-posts">4 postes</span>
                    </div>
                    <div class="lc-row"><span>Niveau langue</span><b>C1</b></div>
                    <div class="lc-row"><span>Niveau français</span><b>C1/B2</b></div>
                    <div class="lc-cert">Goethe C1</div>
                </div>
                <div class="lang-card">
                    <div class="lc-top">
                        <span class="lc-name">Russe<br><small class="lc-profile">Profil trilingue</small></span>
                        <span class="lc-posts">2 postes</span>
                    </div>
                    <div class="lc-row"><span>Niveau langue</span><b>C1</b></div>
                    <div class="lc-row"><span>Niveau français</span><b>C1/B2</b></div>
                    <div class="lc-cert">TORFL</div>
                </div>
                <div class="lang-card">
                    <div class="lc-top">
                        <span class="lc-name">Italien<br><small class="lc-profile">Profil trilingue</small></span>
                        <span class="lc-posts">4 postes</span>
                    </div>
                    <div class="lc-row"><span>Niveau langue</span><b>C1</b></div>
                    <div class="lc-row"><span>Niveau français</span><b>C1/B2</b></div>
                    <div class="lc-cert">CILS · CELI</div>
                </div>
            </div>

            <h4>
                <span aria-hidden="true" style="font-size:20px;"></span>
                Diplôme minimum requis pour toutes les langues : <strong>Licence</strong> ou niveau équivalent.
            </h4>
        </div>
    </section>

    <section class="block" style="padding-top:0;">
        <div class="container">
            <div class="block-head">
                <p class="eyebrow">Conditions d'éligibilité</p>
                <h2>Qui peut candidater ?</h2>
            </div>
            <div class="elig-grid">
                <ul class="elig-list">
                    <li class="elig-item"><span class="elig-check">✓</span>Être âgé de 21 à 35 ans</li>
                    <li class="elig-item"><span class="elig-check">✓</span>Être titulaire au minimum d'une Licence
                        (Bachelor) ou d'une certification linguistique reconnue attestant du niveau requis dans la langue de
                        spécialisation choisie</li>
                    <li class="elig-item"><span class="elig-check">✓</span>Démontrer une maîtrise de la langue de
                        spécialisation conforme aux niveaux définis par le programme</li>
                    <li class="elig-item"><span class="elig-check">✓</span>Maîtriser le français et l’anglais au niveau
                        requis</li>
                    <li class="elig-item"><span class="elig-check">✓</span>Posséder de solides compétences rédactionnelles
                        et orales</li>
                    <li class="elig-item"><span class="elig-check">✓</span>Être disponible pour l'ensemble de la formation
                        et pour la période des Jeux Olympiques de la Jeunesse Dakar 2026</li>
                    <li class="elig-item"><span class="elig-check">✓</span>Être en mesure de travailler dans un
                        environnement multiculturel et à forte pression opérationnelle</li>
                </ul>
                <div class="panel" style="border-radius:22px 22px 22px 6px;">
                    <h3 style="margin-bottom:4px;">Niveaux linguistiques minimums</h3>
                    <p class="panel-sub">Cadre européen commun de référence (CECR)</p>
                    <table class="levels-table">
                        <thead>
                            <tr>
                                <th>Langue</th>
                                <th>Niveau minimum</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>LV1 - langue de spécialisation</td>
                                <td><b>C1</b></td>
                            </tr>
                            <tr>
                                <td>Français</td>
                                <td><b>C1</b> minimum</td>
                            </tr>
                            <tr>
                                <td>Anglais</td>
                                <td><b>C1/B2</b> (C1 si profil bilingue)</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <section class="block" style="padding-top:0;">
        <div class="container">
            <div class="block-head">
                <p class="eyebrow">Comment ça marche</p>
                <h2>Le parcours candidat</h2>
            </div>
            <div class="timeline">
                <div class="tl-step">
                    <h4>Inscription en ligne</h4>
                    <p>Création du profil et dépôt des documents sur le SIGOF.</p>
                </div>
                <div class="tl-step">
                    <h4>Évaluation linguistique</h4>
                    <p>Test de niveau dans la ou les langues déclarées.</p>
                </div>
                <div class="tl-step">
                    <h4>Formation COJOJ</h4>
                    <p>Modules protocolaires, vocabulaire sportif et logistique des Jeux.</p>
                </div>
                <div class="tl-step">
                    <h4>Mobilisation</h4>
                    <p>Affectation officielle auprès d'une délégation ou d'un site.</p>
                </div>
            </div>
        </div>
    </section>

    <div class="partners-strip">
        <div class="container">
            <div class="partners-row">
                <a href="https://www.onfp.sn" target="_blank" rel="noopener" class="partner-item">
                    <img src="{{ asset('assets/img/logo.png') }}" alt="ONFP" class="partner-logo">
                </a>
                <a href="https://www.olympics.com/cio/dakar-2026" target="_blank" rel="noopener" class="partner-item">
                    <img src="{{ asset('images/logo-ylp.png') }}" alt="COJOJ Dakar 2026" class="partner-logo">
                </a>
                <a href="https://www.ucad.sn" target="_blank" rel="noopener" class="partner-item">
                    <img src="{{ asset('assets/img/logo_ucad.png') }}" alt="UCAD" class="partner-logo">
                </a>
                <a href="https://astra-sn.com/?utm_source=chatgpt.com" target="_blank" rel="noopener"
                    class="partner-item">
                    <img src="{{ asset('assets/img/logo_astra.png') }}" alt="ASTRA" class="partner-logo">
                </a>
            </div>
        </div>
    </div>
@endsection
