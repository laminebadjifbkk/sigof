<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="color-scheme" content="light">
    <meta name="format-detection" content="telephone=no,address=no,email=no,date=no">
    <title>SIGOF | Système Intégré de Gestion des Opérations de Formation</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        /* ========== BASE ========== */
        :root {
            color-scheme: light;
            --ink: #24262b;
            --ink-soft: #5b5e66;
            --gray: #7c7f85;
            --gray-line: #e4e3e0;
            --gray-bg: #f4f3f1;
            --orange: #e07a2f;
            --orange-dark: #c05f1c;
            --orange-tint: #fdf1e6;
            --green: #3fa350;
            --green-tint: #eaf6ec;
            --red: #c00000;
            --cream: #fbfaf8;
            --white: #ffffff;
            --radius: 14px;
            --shadow: 0 10px 30px -12px rgba(36, 38, 43, .12);
            --max: 1300px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            scroll-behavior: smooth;
        }

        @media (prefers-reduced-motion: reduce) {
            html {
                scroll-behavior: auto;
            }

            * {
                transition: none !important;
            }
        }

        body {
            font-family: 'Manrope', sans-serif;
            color: var(--ink);
            background: var(--white);
            line-height: 1.55;
            -webkit-font-smoothing: antialiased;
        }

        h1,
        h2,
        h3,
        h4 {
            font-family: 'Space Grotesk', sans-serif;
            letter-spacing: -.01em;
            color: var(--ink);
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        img {
            max-width: 100%;
            display: block;
        }

        ul {
            list-style: none;
        }

        :focus-visible {
            outline: 2px solid var(--orange);
            outline-offset: 2px;
        }

        .wrap {
            max-width: var(--max);
            margin: 0 auto;
            padding: 0 28px;
        }

        section {
            padding: 88px 0;
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: .75rem;
            font-weight: 700;
            letter-spacing: .14em;
            text-transform: uppercase;
            color: var(--orange-dark);
            margin-bottom: 14px;
        }

        .eyebrow::before {
            content: "";
            width: 18px;
            height: 2px;
            background: var(--orange);
            border-radius: 2px;
        }

        .section-head {
            max-width: 640px;
            margin-bottom: 48px;
        }

        .section-head h2 {
            font-size: clamp(1.6rem, 3vw, 2.3rem);
        }

        .section-head p {
            color: var(--gray);
            margin-top: 12px;
            font-size: 1.02rem;
        }

        /* ========== BOUTONS ========== */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 13px 26px;
            border-radius: 999px;
            font-weight: 700;
            font-size: .92rem;
            border: 1.5px solid transparent;
            cursor: pointer;
            transition: all .18s ease;
            font-family: 'Manrope', sans-serif;
        }

        .btn-primary {
            background: var(--green);
            color: #fff;
        }

        .btn-primary:hover {
            background: var(--orange-dark);
            transform: translateY(-1px);
        }

        .btn-ghost {
            border-color: var(--gray-line);
            color: var(--ink);
            background: #fff;
        }

        .btn-ghost:hover {
            border-color: var(--orange);
            color: var(--orange-dark);
        }

        .btn-danger {
            background: hsla(0, 100%, 42%, .756);
            border-color: var(--gray-line);
            color: #fff;
        }

        .btn-danger:hover {
            background: var(--red);
            transform: translateY(-1px);
        }

        /* ========== NAVBAR ========== */
        header {
            position: sticky;
            top: 0;
            z-index: 50;
            background: rgba(251, 250, 248, .9);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--gray-line);
        }

        nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            padding: 12px 28px;
            max-width: var(--max);
            margin: 0 auto;
        }

        .brand-logo {
            height: 60px;
            width: auto;
            object-fit: contain;
        }

        .nav-links {
            display: flex;
            gap: 30px;
            align-items: center;
        }

        .nav-links a {
            font-size: .92rem;
            font-weight: 600;
            color: var(--ink-soft);
            transition: color .15s;
        }

        .nav-links a:hover {
            color: var(--orange-dark);
        }

        .nav-actions {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .menu-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--ink);
        }

        /* ========== HERO ========== */
        .hero {
            padding: 28px 0 64px;
        }

        .hero-grid {
            display: grid;
            grid-template-columns: 1.1fr .9fr;
            gap: 56px;
            align-items: center;
        }

        .hero h1 {
            font-size: clamp(1.7rem, 3.2vw, 2.6rem);
            line-height: 1.12;
            margin-bottom: 20px;
        }

        .hero h1 .accent {
            color: var(--orange);
        }

        .hero .lead {
            color: var(--ink-soft);
            font-size: 1.08rem;
            max-width: 480px;
            margin-bottom: 30px;
        }

        .hero-cta {
            display: flex;
            gap: 14px;
            flex-wrap: wrap;
            margin-bottom: 34px;
        }

        .hero-stat {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .hero-stat .num {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 2.1rem;
            font-weight: 700;
            color: var(--green);
            line-height: 1;
        }

        .hero-stat .lead {
            margin: 0;
            font-size: .95rem;
            max-width: 260px;
        }

        /* ========== CARROUSEL ========== */
        .hero-carousel {
            position: relative;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: var(--shadow);
            background: var(--white);
        }

        .hc-slide {
            display: none;
            flex-direction: column;
        }

        .hc-slide.active {
            display: flex;
        }

        .hc-slide img {
            width: 100%;
            height: auto;
        }

        .hc-caption {
            background: #fff;
            padding: 16px 26px 18px;
            border-top: 3px solid var(--orange);
        }

        .hc-caption .tag {
            font-size: .72rem;
            font-weight: 800;
            letter-spacing: .1em;
            text-transform: uppercase;
            color: var(--orange-dark);
            margin-bottom: 4px;
        }

        .hc-caption .title {
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
            font-size: 1.05rem;
            line-height: 1.3;
            color: var(--ink);
        }

        .hc-nav {
            position: absolute;
            top: 45%;
            transform: translateY(-50%);
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .85);
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            color: var(--ink);
            cursor: pointer;
            z-index: 3;
            transition: background .15s;
        }

        .hc-nav:hover {
            background: #fff;
        }

        .hc-prev {
            left: 14px;
        }

        .hc-next {
            right: 14px;
        }

        .hc-dots {
            position: absolute;
            top: 16px;
            right: 16px;
            display: flex;
            gap: 6px;
            z-index: 3;
        }

        .hc-dots button {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            border: none;
            background: rgba(255, 255, 255, .6);
            box-shadow: 0 0 0 1px rgba(0, 0, 0, .15);
            cursor: pointer;
            padding: 0;
            transition: all .2s;
        }

        .hc-dots button.active {
            background: #fff;
            width: 22px;
            border-radius: 5px;
        }

        /* ========== VERIFICATION ========== */
        .verify-card {
            background: var(--white);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 34px;
            margin-top: 44px;
            border: 1px solid var(--gray-line);
        }

        .verify-card h3 {
            font-size: 1.15rem;
            margin-bottom: 6px;
        }

        .verify-card>p {
            color: var(--gray);
            font-size: .9rem;
            margin-bottom: 22px;
        }

        .verify-form {
            display: grid;
            grid-template-columns: repeat(4, 1fr) auto;
            gap: 14px;
            align-items: end;
        }

        .field label {
            display: block;
            font-size: .78rem;
            font-weight: 700;
            color: var(--ink-soft);
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        .field input,
        .field textarea {
            width: 100%;
            padding: 11px 14px;
            border: 1.5px solid var(--gray-line);
            border-radius: 9px;
            font-family: inherit;
            font-size: .92rem;
            background: var(--gray-bg);
        }

        .field textarea {
            resize: vertical;
            min-height: 110px;
        }

        .field input:focus,
        .field textarea:focus {
            outline: none;
            border-color: var(--orange);
            background: #fff;
        }

        /* ========== À PROPOS ========== */
        .about {
            background: var(--white);
            border-top: 1px solid var(--gray-line);
            border-bottom: 1px solid var(--gray-line);
        }

        .about-grid {
            display: grid;
            grid-template-columns: .9fr 1.1fr;
            gap: 60px;
            align-items: start;
        }

        .about h2 {
            font-size: clamp(1.4rem, 2.4vw, 1.9rem);
            line-height: 1.2;
        }

        .about-list {
            margin-top: 20px;
            display: grid;
            gap: 14px;
        }

        .about-list li {
            display: flex;
            gap: 12px;
            align-items: flex-start;
            font-size: .95rem;
            color: var(--ink-soft);
        }

        .dot {
            flex: none;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--orange);
            margin-top: 8px;
        }

        .about-intro {
            color: var(--gray);
            margin-top: 14px;
        }

        .dg-card {
            background: var(--gray-bg);
            border-radius: var(--radius);
            padding: 26px;
            display: flex;
            gap: 18px;
            align-items: center;
            margin-top: 28px;
        }

        .dg-card img {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            object-fit: cover;
        }

        .dg-card .name {
            font-weight: 700;
            font-family: 'Space Grotesk', sans-serif;
        }

        .dg-card .role {
            color: var(--gray);
            font-size: .85rem;
        }

        .objectifs {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-top: 40px;
        }

        .obj-card {
            background: var(--white);
            border: 1px solid var(--gray-line);
            border-radius: var(--radius);
            padding: 26px;
            position: relative;
            overflow: hidden;
        }

        .obj-card::before {
            content: "";
            position: absolute;
            inset: 0 0 auto 0;
            height: 4px;
            background: linear-gradient(90deg, var(--orange), var(--green));
        }

        .obj-card h4 {
            font-size: 1rem;
            margin-bottom: 8px;
        }

        .obj-card p {
            font-size: .88rem;
            color: var(--gray);
        }

        /* ========== SERVICES ========== */
        .services-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
        }

        .service-card {
            background: var(--white);
            border-radius: var(--radius);
            padding: 30px 26px;
            border: 1px solid var(--gray-line);
            transition: transform .18s, box-shadow .18s;
        }

        .service-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow);
        }

        .service-icon {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            background: var(--orange-tint);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 18px;
            font-size: 1.3rem;
        }

        .service-card h4 {
            font-size: 1.05rem;
            margin-bottom: 10px;
        }

        .service-card p {
            font-size: .9rem;
            color: var(--gray);
            margin-bottom: 16px;
        }

        .service-card .link {
            font-size: .85rem;
            font-weight: 700;
            color: var(--green);
        }

        /* ========== PÔLES ========== */
        .poles {
            background: var(--gray-bg);
            border-top: 1px solid var(--gray-line);
            border-bottom: 1px solid var(--gray-line);
        }

        .poles-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
        }

        .pole-card {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            border: 1px solid var(--gray-line);
            box-shadow: 0 4px 14px -8px rgba(36, 38, 43, .1);
        }

        .pole-card .since {
            font-size: .72rem;
            color: var(--orange-dark);
            font-weight: 800;
            letter-spacing: .06em;
            background: var(--orange-tint);
            display: inline-block;
            padding: 3px 9px;
            border-radius: 999px;
        }

        .pole-card h4 {
            font-size: 1rem;
            margin: 10px 0;
        }

        .pole-card .zone {
            font-size: .85rem;
            color: var(--ink-soft);
            margin-bottom: 14px;
        }

        .pole-card .zone i {
            color: var(--green);
        }

        .pole-card .pole-contact {
            font-size: .82rem;
            color: var(--ink-soft);
            border-top: 1px solid var(--gray-line);
            padding-top: 12px;
            line-height: 1.6;
        }

        /* ========== FAQ ========== */
        .faq-item {
            border-bottom: 1px solid var(--gray-line);
            padding: 22px 0;
            cursor: pointer;
        }

        .faq-q {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 700;
            font-family: 'Space Grotesk', sans-serif;
            gap: 20px;
        }

        .faq-q .badge {
            font-size: .72rem;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 999px;
            background: var(--green-tint);
            color: var(--green);
            white-space: nowrap;
        }

        .faq-a {
            max-height: 0;
            overflow: hidden;
            transition: max-height .25s ease;
            font-size: .92rem;
            color: var(--gray);
        }

        .faq-item.open .faq-a {
            max-height: 400px;
            padding-top: 12px;
        }

        .faq-item .chev {
            transition: transform .2s;
            font-size: 1.2rem;
            color: var(--orange);
        }

        .faq-item.open .chev {
            transform: rotate(45deg);
        }

        /* ========== CONTACT ========== */
        .contact-section {
            background: var(--white);
            border-top: 1px solid var(--gray-line);
        }

        .contact-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
        }

        .contact-lead {
            color: var(--gray);
            margin: 14px 0 26px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .form-grid .full {
            grid-column: 1 / -1;
        }

        .contact-info {
            background: var(--gray-bg);
            border-radius: var(--radius);
            padding: 32px;
            align-self: start;
        }

        .contact-info h3 {
            margin-bottom: 20px;
        }

        .info-row {
            display: flex;
            gap: 14px;
            margin-bottom: 18px;
            font-size: .92rem;
        }

        .info-row:last-child {
            margin-bottom: 0;
        }

        .info-row .ic {
            flex: none;
        }

        /* ========== FOOTER ========== */
        footer {
            background: #1c1d21;
            color: #c9cace;
            padding: 64px 0 28px;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 1.4fr 1fr 1fr 1fr;
            gap: 40px;
            margin-bottom: 50px;
        }

        .footer-grid h5 {
            color: #fff;
            font-family: 'Space Grotesk', sans-serif;
            font-size: .92rem;
            margin-bottom: 16px;
        }

        .footer-grid li {
            margin-bottom: 10px;
            font-size: .88rem;
        }

        .footer-grid a:hover {
            color: var(--orange);
        }

        .footer-logo {
            height: 110px;
            width: auto;
            background: #fff;
            padding: 8px 12px;
            border-radius: 10px;
            object-fit: contain;
        }

        .social-row {
            display: flex;
            gap: 10px;
            margin-top: 22px;
        }

        .social-btn {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            border: 1.5px solid #45464c;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #c9cace;
            transition: all .18s ease;
            flex: none;
        }

        .social-btn svg {
            width: 16px;
            height: 16px;
        }

        .social-btn:hover {
            border-color: var(--orange);
            color: var(--orange);
            background: rgba(224, 122, 47, .1);
            transform: translateY(-2px);
        }

        .footer-bottom {
            border-top: 1px solid #33343a;
            padding-top: 24px;
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            font-size: .82rem;
            color: #8b8d94;
        }

        /* ========== RESPONSIVE ========== */
        @media (max-width: 920px) {
            .nav-links {
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: #fff;
                flex-direction: column;
                align-items: flex-start;
                padding: 20px 28px;
                border-bottom: 1px solid var(--gray-line);
                gap: 16px;
                display: none;
            }

            .nav-links.open {
                display: flex;
            }

            .menu-toggle {
                display: block;
            }

            .nav-actions .btn-ghost {
                display: none;
            }

            .hero-grid,
            .about-grid,
            .contact-grid {
                grid-template-columns: 1fr;
            }

            .hero-grid>div:first-child {
                text-align: center;
            }

            .hero-grid .lead {
                margin-left: auto;
                margin-right: auto;
            }

            .hero .eyebrow,
            .hero-cta,
            .hero-stat {
                justify-content: center;
            }

            .hero-stat .lead {
                text-align: left;
            }

            .verify-form {
                grid-template-columns: 1fr 1fr;
            }

            .objectifs,
            .services-grid,
            .poles-grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 800px) {
            .footer-grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 768px) {
            .brand-logo {
                height: 44px;
            }

            .footer-logo {
                height: 64px;
            }
        }

        @media (max-width: 600px) {

            .objectifs,
            .services-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 560px) {

            .verify-form,
            .poles-grid,
            .form-grid {
                grid-template-columns: 1fr;
            }
        }

        .hero-grid>* {
            min-width: 0;
            /* évite qu'une image large déforme la grille */
        }

        /* Cadre FIXE : ses dimensions ne dépendent plus de l'image affichée */
        .hero-carousel {
            position: relative;
            width: 100%;
            max-width: 540px;
            margin-inline: auto;
            aspect-ratio: 4 / 5;
            /* ratio fixe du bloc */
            border-radius: 10px;
            overflow: hidden;
            box-shadow: var(--shadow);
            background: var(--white-bg);
        }

        .hc-slide {
            display: none;
            flex-direction: column;
            background: var(--white);
            height: 100%;
        }

        .hc-slide.active {
            display: flex;
        }

        /* Zone image : prend tout l'espace restant au-dessus de la légende */
        .hc-media {
            flex: 1;
            min-height: 0;
            /* indispensable pour que flex puisse réduire */
            background: var(--white);
        }

        /* L'image s'adapte au cadre sans jamais le modifier */
        .hc-slide img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            /* affiche l'affiche en entier, sans rognage */
        }

        /* Légende à hauteur fixe pour que la zone image ne bouge pas non plus */
        .hc-caption {
            flex: none;
            height: 84px;
            background: #fff;
            padding: 14px 26px;
            border-top: 3px solid var(--orange);
            overflow: hidden;
        }

        .hc-caption .title {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</head>

<body>
    {{-- ========== NAVBAR ========== --}}
    <header>
        <nav>
            <a href="{{ url('/') }}" class="brand">
                <img src="{{ asset('assets/img/ONFP_logo_header_600px@2x.png') }}" alt="Logo ONFP" class="brand-logo">
            </a>

            <ul class="nav-links" id="navLinks">
                <li><a href="#accueil">Accueil</a></li>
                <li><a href="#apropos">À propos</a></li>
                <li><a href="#services">Services</a></li>
                <li><a href="#poles">Nos pôles</a></li>
                <li><a href="{{ route('manuels.showDefault') }}" target="_blank">Nos manuels</a></li>
                <li><a href="#faq">FAQ</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>

            <div class="nav-actions">
                <a href="{{ url('login') }}" class="btn btn-ghost">Se connecter</a>
                <a href="{{ route('register-page') }}" class="btn btn-primary">S'inscrire</a>
                <button type="button" class="menu-toggle" aria-label="Ouvrir le menu"
                    onclick="document.getElementById('navLinks').classList.toggle('open')">☰</button>
            </div>
        </nav>
    </header>

    {{-- ========== HERO ========== --}}
    @php $slides = ($posts ?? collect())->filter(fn($p) => !empty($p->image)); @endphp

    <section class="hero" id="accueil">
        <div class="wrap hero-grid">
            <div>
                <div class="eyebrow">Office national de formation professionnelle</div>
                <h1>La référence de la <span class="accente">formation professionnelle</span> au Sénégal</h1>
                <p class="lead">SIGOF centralise et digitalise vos démarches : demandes de formation, agréments
                    d'opérateurs, suivi des partenariats, en un seul portail, partout dans le pays.</p>
                <div class="hero-cta">
                    <a href="{{ route('register-page') }}" class="btn btn-primary">Créer mon compte</a>
                    <a href="{{ url('pcharge') }}" class="btn btn-danger">Déposer une prise en charge</a>
                </div>
                <div class="hero-stat">
                    <span class="num">{{ $anciennete }}+</span>
                    <p class="lead">ans d'expérience dans la formation professionnelle au Sénégal</p>
                </div>
            </div>

            {{-- @if ($slides->isNotEmpty())
                <div class="hero-carousel" id="heroCarousel">
                    @foreach ($slides as $post)
                        <div class="hc-slide {{ $loop->first ? 'active' : '' }}">
                            <img src="{{ asset($post->getPoste()) }}" alt="{{ $post->legende ?? $post->name }}">
                            <div class="hc-caption">
                                <div class="tag">{{ $post->titre }}</div>
                                <div class="title">{{ str($post->name)->limit(50) }}</div>
                            </div>
                        </div>
                    @endforeach

                    @if ($slides->count() > 1)
                        <button type="button" class="hc-nav hc-prev" onclick="hcMove(-1)"
                            aria-label="Image précédente">‹</button>
                        <button type="button" class="hc-nav hc-next" onclick="hcMove(1)"
                            aria-label="Image suivante">›</button>
                        <div class="hc-dots" id="hcDots"></div>
                    @endif
                </div>
            @endif --}}
            @if ($slides->isNotEmpty())
                <div class="hero-carousel" id="heroCarousel">
                    @foreach ($slides as $post)
                        <div class="hc-slide {{ $loop->first ? 'active' : '' }}">
                            <div class="hc-media">
                                <img src="{{ asset($post->getPoste()) }}" alt="{{ $post->legende ?? $post->name }}">
                            </div>
                            <div class="hc-caption">
                                <div class="tag">{{ $post->titre }}</div>
                                <div class="title">{{ str($post->name)->limit(50) }}</div>
                            </div>
                        </div>
                    @endforeach

                    @if ($slides->count() > 1)
                        <button type="button" class="hc-nav hc-prev" onclick="hcMove(-1)"
                            aria-label="Image précédente">‹</button>
                        <button type="button" class="hc-nav hc-next" onclick="hcMove(1)"
                            aria-label="Image suivante">›</button>
                        <div class="hc-dots" id="hcDots"></div>
                    @endif
                </div>
            @endif
        </div>

        {{-- <div class="wrap">
            <div class="verify-card">
                <h3>Vérifiez votre sélection</h3>
                <p>Consultez rapidement le statut de votre dossier à l'aide de vos informations personnelles.</p>
                <form class="verify-form" method="POST" action="#">
                    @csrf
                    <div class="field"><label for="v-prenom">Prénom</label>
                        <input id="v-prenom" name="prenom" type="text" placeholder="Aïssatou" required>
                    </div>
                    <div class="field"><label for="v-nom">Nom</label>
                        <input id="v-nom" name="nom" type="text" placeholder="Diop" required>
                    </div>
                    <div class="field"><label for="v-naissance">Date de naissance</label>
                        <input id="v-naissance" name="date_naissance" type="date" required>
                    </div>
                    <div class="field"><label for="v-email">Email</label>
                        <input id="v-email" name="email" type="email" placeholder="vous@exemple.sn" required>
                    </div>
                    <button type="submit" class="btn btn-primary" style="height:44px;">Vérifier</button>
                </form>
            </div>
        </div> --}}
    </section>

    {{-- ========== À PROPOS ========== --}}
    <section class="about" id="apropos">
        <div class="wrap about-grid">
            <div>
                <div class="eyebrow">À propos de l'ONFP</div>
                <h2>Un établissement public au service de la formation, depuis 1986</h2>
                <ul class="about-list">
                    <li><span class="dot"></span>Aider à mettre en œuvre les objectifs sectoriels du gouvernement et
                        assister les organismes publics et privés.</li>
                    <li><span class="dot"></span>Réaliser des études sur l'emploi, la qualification professionnelle
                        et
                        les moyens de la formation initiale et continue.</li>
                    <li><span class="dot"></span>Coordonner les interventions par branche professionnelle et par
                        action prioritaire.</li>
                    <li><span class="dot"></span>Coordonner l'action de formation professionnelle des organismes
                        d'aide bilatérale ou multilatérale.</li>
                </ul>
                <div class="dg-card">
                    <img src="https://sigof.onfp.sn/assets/img/dgawandoye.jpg" alt="Directrice générale">
                    <div>
                        <div class="name">Dr. Mame Awa NDOYE</div>
                        <div class="role">Directrice Générale · +221 33 827 92 51</div>
                    </div>
                </div>
            </div>

            <div>
                <div class="eyebrow">La plateforme SIGOF</div>
                <h2>Toute la gestion des opérations de formation, sur un seul portail</h2>
                <p class="about-intro">Le Système Intégré de Gestion des Opérations de Formation centralise et
                    automatise la gestion des demandeurs, des opérateurs et des partenaires de l'ONFP.</p>
                <div class="objectifs">
                    <div class="obj-card">
                        <h4>Optimisation des processus</h4>
                        <p>Simplifier la gestion des inscriptions et la coordination des parties prenantes.</p>
                    </div>
                    <div class="obj-card">
                        <h4>Gestion des demandeurs</h4>
                        <p>Enregistrement et traitement des demandes individuelles ou collectives.</p>
                    </div>
                    <div class="obj-card">
                        <h4>Gestion des opérateurs</h4>
                        <p>Enregistrement et traitement des prestataires de formation.</p>
                    </div>
                    <div class="obj-card">
                        <h4>Gestion des partenaires</h4>
                        <p>Coordination et suivi des collaborations institutionnelles.</p>
                    </div>
                    <div class="obj-card">
                        <h4>Portail interactif</h4>
                        <p>Interface accessible en ligne pour demandeurs et opérateurs.</p>
                    </div>
                    <div class="obj-card">
                        <h4>Statistiques à jour</h4>
                        <p>Suivi des actions de formation menées sur l'ensemble du territoire.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ========== SERVICES ========== --}}
    <section id="services">
        <div class="wrap">
            <div class="section-head">
                <div class="eyebrow">Nos missions</div>
                <h2>Six domaines d'intervention, une seule mission</h2>
                <p>De la formation à l'insertion professionnelle, l'ONFP accompagne les branches professionnelles, les
                    entreprises et les demandeurs d'emploi à chaque étape.</p>
            </div>
            <div class="services-grid">
                <div class="service-card">
                    <div class="service-icon">🎓</div>
                    <h4>Formation | Qualification</h4>
                    <p>Organisation d'actions de formation au bénéfice des branches professionnelles, demandeurs
                        d'emploi, travailleurs et entreprises.</p>
                    <a class="link" href="https://www.onfp.sn/formations">En savoir plus →</a>
                </div>
                <div class="service-card">
                    <div class="service-icon">✅</div>
                    <h4>Évaluation | Certification</h4>
                    <p>Contrôle de l'exécution des conventions signées avec les opérateurs et évaluation des actions
                        de formation menées.</p>
                    <a class="link" href="https://www.onfp.sn/evaluations">En savoir plus →</a>
                </div>
                <div class="service-card">
                    <div class="service-icon">🏗️</div>
                    <h4>Construction | Équipement</h4>
                    <p>Maîtrise d'ouvrage de construction et d'équipement de centres de formation professionnelle.</p>
                    <a class="link" href="https://www.onfp.sn/constructions">En savoir plus →</a>
                </div>
                <div class="service-card">
                    <div class="service-icon">🧭</div>
                    <h4>Suivi-Insertion</h4>
                    <p>Analyse des besoins, co-élaboration du projet professionnel et conseil sur les opportunités du
                        marché de l'emploi.</p>
                    <a class="link" href="https://www.onfp.sn/suivi-insertions">En savoir plus →</a>
                </div>
                <div class="service-card">
                    <div class="service-icon">📚</div>
                    <h4>Documentation | Édition</h4>
                    <p>Production et diffusion de documentation et de supports techniques et pédagogiques.</p>
                    <a class="link" href="https://www.onfp.sn/documentations">En savoir plus →</a>
                </div>
                <div class="service-card">
                    <div class="service-icon">🔎</div>
                    <h4>Étude | Recherche</h4>
                    <p>Production et diffusion de connaissances et de savoirs sur la formation professionnelle.</p>
                    <a class="link" href="https://www.onfp.sn/etudes">En savoir plus →</a>
                </div>
            </div>
        </div>
    </section>

    {{-- ========== PÔLES ========== --}}
    <section class="poles" id="poles">
        <div class="wrap">
            <div class="section-head">
                <div class="eyebrow">Couverture nationale</div>
                <h2>{{ $antennes->count() }} pôles, présents sur tout le territoire</h2>
                <p>De Dakar à Ziguinchor, un réseau de pôles régionaux au plus proche des demandeurs et des
                    opérateurs.</p>
            </div>
            <div class="poles-grid">
                @foreach ($antennes as $antenne)
                    @php
                        $chef = $antenne->chef?->user;
                        $tel = $chef?->telephone !== '78 291 33 33' ? $chef?->telephone : null;
                        $phones = collect([$antenne->contact, $tel])
                            ->filter()
                            ->implode(' / ');
                        $regions = $antenne->regions?->pluck('nom')->filter()->implode(' - ');
                    @endphp
                    <div class="pole-card">
                        <div class="since">
                            {{ $antenne->date_ouverture ? 'DEPUIS ' . $antenne->date_ouverture->format('Y') : $antenne->code }}
                        </div>
                        <h4>{{ $antenne->name }}</h4>
                        @if ($regions)
                            <div class="zone">
                                <i class="bi bi-check-circle-fill"></i> {{ $regions }}
                            </div>
                        @endif
                        <div class="pole-contact">
                            @if ($chef?->name)
                                <p><i class="bi bi-person"></i> {{ trim($chef->civilite . ' ' . $chef->name) }}</p>
                            @endif
                            @if ($phones)
                                {{ $phones }}<br>
                            @endif
                            {{ $antenne->adresse }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ========== FAQ ========== --}}
    <section id="faq">
        <div class="wrap" style="max-width:820px;">
            <div class="section-head">
                <div class="eyebrow">Besoin d'aide</div>
                <h2>Réponses aux questions fréquentes</h2>
            </div>
            <div class="faq-list">
                <div class="faq-item" onclick="this.classList.toggle('open')">
                    <div class="faq-q">Bonjour, je n'arrive pas à m'inscrire sur vos sites <span
                            class="chev">+</span></div>
                    <div class="faq-a">Pour l'inscription, suivez le guide ou la vidéo disponible sur la plateforme.
                        Si le problème persiste, appelez directement le 77 291 33 97.</div>
                </div>
                <div class="faq-item" onclick="this.classList.toggle('open')">
                    <div class="faq-q">Nous nous sommes trompés d'option : « apprenant » au lieu d'« opérateur »
                        <span class="badge">Résolu</span>
                    </div>
                    <div class="faq-a">Contactez le support via le formulaire de contact pour faire corriger votre
                        profil vers « Opérateur ».</div>
                </div>
                <div class="faq-item" onclick="this.classList.toggle('open')">
                    <div class="faq-q">Je voudrais déposer une demande d'agrément de formation <span
                            class="chev">+</span></div>
                    <div class="faq-a">Créez votre compte opérateur puis déposez votre dossier d'agrément directement
                        depuis votre espace personnel.</div>
                </div>
                <div class="faq-item" onclick="this.classList.toggle('open')">
                    <div class="faq-q">J'aimerais supprimer mon compte et mes informations <span
                            class="chev">+</span></div>
                    <div class="faq-a">Contactez notre équipe via le formulaire ci-dessous ; votre demande sera
                        traitée conformément à notre politique de données.</div>
                </div>
            </div>
        </div>
    </section>

    {{-- ========== CONTACT ========== --}}
    <section class="contact-section" id="contact">
        <div class="wrap contact-grid">
            <div>
                <div class="eyebrow">Nous contacter</div>
                <h2>Une question ? Écrivez-nous</h2>
                <p class="contact-lead">Ce formulaire est réservé aux questions et demandes d'information. Il ne
                    remplace pas un dépôt de candidature.</p>
                {{-- TODO : remplacer action="#" par votre route de contact --}}
                <form class="form-grid" method="POST" action="#">
                    @csrf
                    <div class="field"><label for="c-email">Email</label>
                        <input id="c-email" name="email" type="email" placeholder="vous@exemple.sn" required>
                    </div>
                    <div class="field"><label for="c-tel">Téléphone</label>
                        <input id="c-tel" name="telephone" type="tel" placeholder="+221 77 000 00 00">
                    </div>
                    <div class="field full"><label for="c-objet">Objet</label>
                        <input id="c-objet" name="objet" type="text" placeholder="Objet de votre message"
                            required>
                    </div>
                    <div class="field full"><label for="c-message">Message</label>
                        <textarea id="c-message" name="message" placeholder="Écrivez votre message ici…" required></textarea>
                    </div>
                    <div class="full"><button type="submit" class="btn btn-primary">Envoyer le message</button>
                    </div>
                </form>
            </div>

            <div class="contact-info">
                <h3>Direction générale</h3>
                <div class="info-row"><span class="ic"></span><span>Sipres 1, lot 2 - 2 voies Liberté 6,
                        extension
                        VDN, Dakar</span></div>
                <div class="info-row"><span class="ic"></span><span>+221 33 827 92 51</span></div>
                <div class="info-row"><span class="ic"></span><span>onfp@onfp.sn</span></div>
                <div class="info-row"><span class="ic"></span><span>WhatsApp : +221 77 291 18 38</span></div>
            </div>
        </div>
    </section>

    {{-- ========== FOOTER ========== --}}
    <footer>
        <div class="wrap">
            <div class="footer-grid">
                <div>
                    <a href="{{ url('/') }}">
                        <img src="{{ asset('assets/img/ONFP_logo_fond-blanc_1500px.jpg') }}" alt="Logo ONFP"
                            class="footer-logo">
                    </a>
                    <div class="social-row">
                        <a class="social-btn" href="https://x.com/ONFP_Officiel/" target="_blank" rel="noopener"
                            aria-label="X (Twitter)">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M18.3 2H21l-6.6 7.5L22.2 22H16l-5-6.6L5.2 22H2.5l7.1-8.1L1.8 2h6.4l4.5 6.1L18.3 2Zm-1.2 18h1.5L7 4h-1.6l11.7 16Z" />
                            </svg>
                        </a>
                        <a class="social-btn" href="https://www.facebook.com/profile.php?id=61566912421177" target="_blank" rel="noopener"
                            aria-label="Facebook">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M13.5 21v-8h2.7l.4-3.1h-3.1V7.9c0-.9.3-1.5 1.6-1.5h1.7V3.6C16.5 3.6 15.6 3.5 14.6 3.5c-2.5 0-4.2 1.5-4.2 4.3v2.1H7.7v3.1h2.7v8h3.1Z" />
                            </svg>
                        </a>
                        <a class="social-btn" href="https://www.instagram.com/onfp.sn/" target="_blank" rel="noopener"
                            aria-label="Instagram">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <rect x="3" y="3" width="18" height="18" rx="5" />
                                <circle cx="12" cy="12" r="4" />
                                <circle cx="17.2" cy="6.8" r="1" />
                            </svg>
                        </a>
                        <a class="social-btn" href="https://www.linkedin.com/company/104719756/admin/page-posts/published/" target="_blank"
                            rel="noopener" aria-label="LinkedIn">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M6.9 8.6H3.6V20h3.3V8.6ZM5.3 3.5a1.9 1.9 0 1 0 0 3.9 1.9 1.9 0 0 0 0-3.9ZM20.4 20h-3.3v-5.9c0-1.4 0-3.2-2-3.2s-2.3 1.6-2.3 3.1V20H9.5V8.6h3.1v1.5h.1c.4-.8 1.5-1.7 3.2-1.7 3.4 0 4.5 2.2 4.5 5.2V20Z" />
                            </svg>
                        </a>
                        <a class="social-btn" href="https://www.youtube.com/@CelluleCommunicationONFP/shorts" target="_blank" rel="noopener"
                            aria-label="YouTube">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M21.6 7.2s-.2-1.5-.8-2.1c-.8-.8-1.7-.8-2.1-.9C15.8 4 12 4 12 4h0s-3.8 0-6.7.2c-.4 0-1.3.1-2.1.9-.6.6-.8 2.1-.8 2.1S2.2 9 2.2 10.7v1.6C2.2 14 2.4 15.8 2.4 15.8s.2 1.5.8 2.1c.8.8 1.9.8 2.3.9 1.7.2 7 .2 7 .2s3.8 0 6.7-.2c.4 0 1.3-.1 2.1-.9.6-.6.8-2.1.8-2.1s.2-1.8.2-3.5v-1.6c0-1.7-.2-3.5-.2-3.5ZM10 14.4V8.9l5.3 2.8-5.3 2.7Z" />
                            </svg>
                        </a>
                        <a class="social-btn" href="https://wa.me/221787804411" target="_blank" rel="noopener"
                            aria-label="WhatsApp">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M12 2a10 10 0 0 0-8.6 15.1L2 22l5-1.3A10 10 0 1 0 12 2Zm5.8 14.2c-.3.7-1.4 1.3-2 1.4-.5.1-1.1.1-1.8-.1-.4-.1-1-.3-1.7-.6-3-1.3-4.9-4.3-5.1-4.5-.1-.2-1.2-1.6-1.2-3.1 0-1.5.8-2.2 1-2.5.3-.3.6-.4.8-.4h.6c.2 0 .4 0 .6.5.3.6.9 2.1 1 2.2.1.2.1.3 0 .5-.1.2-.2.3-.3.5-.2.2-.3.4-.5.5-.2.2-.3.4-.1.7.2.3.8 1.3 1.7 2.1 1.2 1 2.1 1.4 2.5 1.5.2.1.4.1.5-.1.2-.2.7-.8.9-1.1.2-.2.4-.2.6-.1.2.1 1.5.7 1.8.8.3.1.4.2.5.3.1.2.1.8-.2 1.5Z" />
                            </svg>
                        </a>
                    </div>
                </div>

                <div>
                    <h5>Navigation</h5>
                    <ul>
                        <li><a href="#accueil">Accueil</a></li>
                        <li><a href="#apropos">À propos</a></li>
                        <li><a href="#services">Services</a></li>
                        <li><a href="#contact">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h5>Compte</h5>
                    <ul>
                        <li><a href="{{ url('login') }}">Se connecter</a></li>
                        <li><a href="{{ route('register-page') }}">S'inscrire</a></li>
                        {{-- Adaptez si votre route porte un autre nom --}}
                        <li><a href="{{ route('password.request') }}">Mot de passe oublié</a></li>
                    </ul>
                </div>
                <div>
                    <h5>Contact</h5>
                    <ul>
                        <li>+221 33 827 92 51</li>
                        <li>onfp@onfp.sn</li>
                        <li>Sipres 1, Dakar</li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <span>© ONFP · Tous droits réservés</span>
                <span>Conçu par l'équipe digitale ONFP</span>
            </div>
        </div>
    </footer>

    <script>
        (function() {
            const slides = document.querySelectorAll('#heroCarousel .hc-slide');
            const dotsWrap = document.getElementById('hcDots');
            if (slides.length < 2 || !dotsWrap) return;

            let current = 0,
                timer;

            slides.forEach((_, i) => {
                const dot = document.createElement('button');
                dot.type = 'button';
                if (i === 0) dot.classList.add('active');
                dot.setAttribute('aria-label', "Aller à l'image " + (i + 1));
                dot.onclick = () => goTo(i);
                dotsWrap.appendChild(dot);
            });

            function goTo(i) {
                slides[current].classList.remove('active');
                dotsWrap.children[current].classList.remove('active');
                current = (i + slides.length) % slides.length;
                slides[current].classList.add('active');
                dotsWrap.children[current].classList.add('active');
                resetTimer();
            }

            function resetTimer() {
                clearInterval(timer);
                timer = setInterval(() => goTo(current + 1), 5000);
            }

            window.hcMove = (dir) => goTo(current + dir);

            const carousel = document.getElementById('heroCarousel');
            carousel.addEventListener('mouseenter', () => clearInterval(timer));
            carousel.addEventListener('mouseleave', resetTimer);
            resetTimer();
        })();
    </script>
</body>

</html>
