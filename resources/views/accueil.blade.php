<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="color-scheme" content="light">
    <meta name="format-detection" content="telephone=no,address=no,email=no,date=no">
    <meta name="description"
        content="SIGOF, le portail de l'ONFP : demandes de formation, agréments d'opérateurs et suivi des partenariats au Sénégal.">
    <title>SIGOF | Système Intégré de Gestion des Opérations de Formation</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="{{ asset('assets/img/favicon-32x32.png') }}" rel="icon">
    <link href="{{ asset('assets/img/favicon-32x32.png') }}" rel="apple-touch-icon">
    <link
        href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <script>
        document.documentElement.classList.add('js');
    </script>

    <style>
        /* ========== BASE ========== */
        :root {
            color-scheme: light;

            /* Couleurs du logo ONFP */
            --green: #3fa350;
            --green-dark: #2c7d3a;
            --green-tint: #eaf6ec;
            --yellow: #ffc914;
            --yellow-tint: #fff7d6;
            --orange: #ff7a00;
            --orange-dark: #c85f00;
            --orange-tint: #fff0e0;
            --red: #e3262e;
            --red-tint: #fdeaea;
            --slate: #2b3742;
            --slate-deep: #19222a;

            --ink: #1f2a33;
            --ink-soft: #4d5a66;
            --gray: #6f7b87;
            --gray-line: #e6e9ee;
            --gray-bg: #f5f7f9;
            --cream: #fbfaf8;
            --white: #ffffff;

            --radius: 18px;
            --shadow: 0 18px 40px -18px rgba(25, 34, 42, .22);
            --shadow-sm: 0 8px 22px -12px rgba(25, 34, 42, .18);
            --max: 1260px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            scroll-behavior: smooth;
        }

        section[id] {
            scroll-margin-top: 84px;
        }

        @media (prefers-reduced-motion: reduce) {
            html {
                scroll-behavior: auto;
            }

            * {
                transition: none !important;
                animation: none !important;
            }
        }

        body {
            font-family: 'Manrope', sans-serif;
            color: var(--ink);
            background: var(--white);
            line-height: 1.6;
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
            outline-offset: 3px;
        }

        .wrap {
            max-width: var(--max);
            margin: 0 auto;
            padding: 0 28px;
        }

        section {
            padding: 96px 0;
        }

        .tricolor {
            display: block;
            height: 4px;
            background: linear-gradient(90deg, var(--green) 0 34%, var(--yellow) 34% 67%, var(--red) 67% 100%);
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-size: .75rem;
            font-weight: 800;
            letter-spacing: .14em;
            text-transform: uppercase;
            color: var(--orange-dark);
            margin-bottom: 14px;
        }

        .eyebrow::before {
            content: "";
            width: 28px;
            height: 4px;
            border-radius: 4px;
            background: linear-gradient(90deg, var(--green) 0 34%, var(--yellow) 34% 67%, var(--red) 67% 100%);
        }

        .section-head {
            max-width: 680px;
            margin-bottom: 52px;
        }

        .section-head h2 {
            font-size: clamp(1.7rem, 3.2vw, 2.4rem);
            line-height: 1.18;
        }

        .section-head p {
            color: var(--gray);
            margin-top: 14px;
            font-size: 1.04rem;
        }

        /* Apparition au scroll */
        .js .reveal {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity .6s ease, transform .6s ease;
        }

        .js .reveal.in {
            opacity: 1;
            transform: none;
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
            transition: all .2s ease;
            font-family: 'Manrope', sans-serif;
        }

        .btn-primary {
            background: var(--green);
            color: #fff;
            box-shadow: 0 10px 22px -10px rgba(63, 163, 80, .8);
        }

        .btn-primary:hover {
            background: var(--green-dark);
            transform: translateY(-2px);
            box-shadow: 0 14px 26px -10px rgba(63, 163, 80, .9);
        }

        .btn-ghost {
            border-color: var(--gray-line);
            color: var(--ink);
            background: #fff;
        }

        .btn-ghost:hover {
            border-color: var(--orange);
            color: var(--orange-dark);
            transform: translateY(-2px);
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

        /* ========== BARRE ANNIVERSAIRE ========== */
        .anniv-bar {
            background: var(--slate-deep);
            color: #fff;
            text-align: center;
            font-size: .85rem;
            font-weight: 700;
            padding: 9px 16px;
        }

        .anniv-bar a {
            color: var(--yellow);
            text-decoration: underline;
            text-underline-offset: 3px;
        }

        /* ========== NAVBAR ========== */
        header {
            position: sticky;
            top: 0;
            z-index: 50;
            background: rgba(255, 255, 255, .88);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--gray-line);
        }

        nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            padding: 10px 28px;
            max-width: var(--max);
            margin: 0 auto;
        }

        .brand-logo {
            height: 58px;
            width: auto;
            object-fit: contain;
        }

        .nav-links {
            display: flex;
            gap: 6px;
            align-items: center;
        }

        .nav-links a {
            position: relative;
            display: block;
            padding: 8px 12px;
            font-size: .9rem;
            font-weight: 600;
            color: var(--ink-soft);
            border-radius: 999px;
            transition: color .15s, background .15s;
        }

        .nav-links a:hover {
            color: var(--ink);
            background: var(--gray-bg);
        }

        .nav-links a.active {
            color: var(--green-dark);
            background: var(--green-tint);
        }

        .nav-actions {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .menu-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.8rem;
            line-height: 1;
            cursor: pointer;
            color: var(--ink);
        }

        /* ========== HERO ========== */
        .hero {
            position: relative;
            padding: 64px 0 88px;
            overflow: hidden;
            background:
                radial-gradient(700px 400px at 95% 0%, rgba(255, 201, 20, .20), transparent 60%),
                radial-gradient(640px 420px at 0% 25%, rgba(63, 163, 80, .13), transparent 60%),
                radial-gradient(520px 380px at 60% 100%, rgba(255, 122, 0, .10), transparent 60%),
                #fff;
        }

        .hero-grid {
            display: grid;
            grid-template-columns: 1.1fr .9fr;
            gap: 56px;
            align-items: center;
        }

        .hero-grid>* {
            min-width: 0;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--yellow-tint);
            color: #7a5a00;
            border: 1px solid rgba(255, 201, 20, .6);
            padding: 7px 16px;
            border-radius: 999px;
            font-size: .8rem;
            font-weight: 800;
            margin-bottom: 20px;
            transition: transform .2s;
        }

        .hero-badge:hover {
            transform: translateY(-2px);
        }

        .hero h1 {
            font-size: clamp(2rem, 4.2vw, 3.2rem);
            line-height: 1.08;
            margin-bottom: 22px;
        }

        .hero h1 .accent {
            color: var(--green);
            background: linear-gradient(90deg, var(--green), var(--orange));
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero .lead {
            color: var(--ink-soft);
            font-size: 1.1rem;
            max-width: 520px;
            margin-bottom: 32px;
        }

        .hero-cta {
            display: flex;
            gap: 14px;
            flex-wrap: wrap;
            margin-bottom: 40px;
        }

        .hero-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 14px;
            max-width: 520px;
        }

        .stat-card {
            background: #fff;
            border: 1px solid var(--gray-line);
            border-top: 4px solid var(--c, var(--green));
            border-radius: 14px;
            padding: 14px 16px;
            box-shadow: var(--shadow-sm);
        }

        .stat-card:nth-child(2) {
            --c: var(--yellow);
        }

        .stat-card:nth-child(3) {
            --c: var(--red);
        }

        .stat-card .num {
            display: block;
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1.9rem;
            font-weight: 700;
            line-height: 1.1;
            color: var(--ink);
        }

        .stat-card .lbl {
            display: block;
            font-size: .78rem;
            color: var(--gray);
            line-height: 1.35;
            margin-top: 4px;
        }

        /* Visuel de repli si aucune affiche */
        .hero-visual {
            display: grid;
            place-items: center;
            padding: 40px;
            background: #fff;
            border-radius: 28px;
            box-shadow: var(--shadow);
            border: 1px solid var(--gray-line);
            max-width: 460px;
            margin-inline: auto;
        }

        .hero-visual img {
            width: 100%;
            max-width: 320px;
        }

        /* ========== CARROUSEL (cadre fixe) ========== */
        .hero-carousel {
            position: relative;
            width: 100%;
            max-width: 520px;
            margin-inline: auto;
            aspect-ratio: 4 / 5;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: var(--shadow);
            background: #fff;
            border: 1px solid var(--gray-line);
        }

        .hero-carousel::after {
            content: "";
            position: absolute;
            inset: 0 0 auto 0;
            height: 5px;
            z-index: 4;
            background: linear-gradient(90deg, var(--green) 0 34%, var(--yellow) 34% 67%, var(--red) 67% 100%);
        }

        .hc-slide {
            display: none;
            flex-direction: column;
            background: #fff;
            height: 100%;
        }

        .hc-slide.active {
            display: flex;
            animation: hcFade .5s ease both;
        }

        @keyframes hcFade {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .hc-media {
            flex: 1;
            min-height: 0;
            background: #fff;
        }

        .hc-slide img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .hc-caption {
            flex: none;
            height: 88px;
            background: #fff;
            padding: 14px 26px;
            border-top: 1px solid var(--gray-line);
            overflow: hidden;
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
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .hc-nav {
            position: absolute;
            top: 45%;
            transform: translateY(-50%);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .92);
            border: none;
            box-shadow: 0 4px 14px rgba(0, 0, 0, .15);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: var(--ink);
            cursor: pointer;
            z-index: 3;
            transition: all .15s;
        }

        .hc-nav:hover {
            background: var(--green);
            color: #fff;
        }

        .hc-prev {
            left: 14px;
        }

        .hc-next {
            right: 14px;
        }

        .hc-dots {
            position: absolute;
            top: 18px;
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
            background: rgba(255, 255, 255, .7);
            box-shadow: 0 0 0 1px rgba(0, 0, 0, .18);
            cursor: pointer;
            padding: 0;
            transition: all .2s;
        }

        .hc-dots button.active {
            background: var(--yellow);
            width: 22px;
            border-radius: 5px;
        }

        /* ========== 40 ANS ========== */
        .anniv {
            position: relative;
            overflow: hidden;
            padding: 84px 0;
            color: #fff;
            background:
                radial-gradient(600px 360px at 8% 0%, rgba(63, 163, 80, .35), transparent 60%),
                radial-gradient(560px 380px at 100% 100%, rgba(255, 122, 0, .30), transparent 60%),
                radial-gradient(420px 300px at 70% 0%, rgba(255, 201, 20, .18), transparent 60%),
                linear-gradient(135deg, var(--slate-deep), var(--slate));
        }

        .anniv::before {
            content: "40";
            position: absolute;
            right: -10px;
            top: 50%;
            transform: translateY(-50%);
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
            font-size: clamp(12rem, 30vw, 26rem);
            line-height: 1;
            color: transparent;
            -webkit-text-stroke: 2px rgba(255, 201, 20, .16);
            pointer-events: none;
        }

        .anniv-grid {
            position: relative;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 56px;
            align-items: center;
        }

        .anniv .eyebrow {
            color: var(--yellow);
        }

        .anniv h2 {
            color: #fff;
            font-size: clamp(1.9rem, 3.8vw, 2.9rem);
            line-height: 1.12;
        }

        .anniv h2 .accent {
            color: var(--yellow);
        }

        .anniv p {
            color: #d3d8dd;
            margin: 16px 0 26px;
            max-width: 480px;
        }

        .anniv-meta {
            display: flex;
            gap: 14px;
            flex-wrap: wrap;
            font-size: .9rem;
            color: #fff;
            font-weight: 600;
        }

        .anniv-meta span {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, .08);
            border: 1px solid rgba(255, 255, 255, .14);
            padding: 8px 14px;
            border-radius: 999px;
        }

        .anniv-meta i {
            color: var(--yellow);
        }

        .countdown {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px;
        }

        .cd-box {
            background: rgba(255, 255, 255, .07);
            border: 1px solid rgba(255, 255, 255, .14);
            border-top: 4px solid var(--c, var(--green));
            border-radius: var(--radius);
            padding: 22px 8px;
            text-align: center;
            backdrop-filter: blur(6px);
        }

        .cd-box:nth-child(1) {
            --c: var(--green);
        }

        .cd-box:nth-child(2) {
            --c: var(--yellow);
        }

        .cd-box:nth-child(3) {
            --c: var(--orange);
        }

        .cd-box:nth-child(4) {
            --c: var(--red);
        }

        .cd-box .n {
            display: block;
            font-family: 'Space Grotesk', sans-serif;
            font-size: clamp(2rem, 4.5vw, 3.2rem);
            font-weight: 700;
            line-height: 1;
            color: var(--c);
            font-variant-numeric: tabular-nums;
        }

        .cd-box .l {
            display: block;
            margin-top: 8px;
            font-size: .7rem;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: #c9cace;
        }

        .cd-done {
            grid-column: 1 / -1;
            text-align: center;
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--yellow);
        }

        /* ========== À PROPOS ========== */
        .about {
            background: var(--white);
        }

        .about-grid {
            display: grid;
            grid-template-columns: .9fr 1.1fr;
            gap: 64px;
            align-items: start;
        }

        .about h2 {
            font-size: clamp(1.5rem, 2.6vw, 2rem);
            line-height: 1.2;
        }

        .about-list {
            margin-top: 22px;
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
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-top: 8px;
            background: var(--c, var(--green));
        }

        .about-list li:nth-child(2) .dot {
            --c: var(--yellow);
        }

        .about-list li:nth-child(3) .dot {
            --c: var(--orange);
        }

        .about-list li:nth-child(4) .dot {
            --c: var(--red);
        }

        .about-intro {
            color: var(--gray);
            margin-top: 14px;
        }

        .dg-card {
            background: linear-gradient(135deg, var(--green-tint), var(--yellow-tint));
            border-radius: var(--radius);
            padding: 24px;
            display: flex;
            gap: 18px;
            align-items: center;
            margin-top: 30px;
            border: 1px solid rgba(63, 163, 80, .18);
        }

        .dg-card img {
            width: 68px;
            height: 68px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #fff;
            box-shadow: var(--shadow-sm);
        }

        .dg-card .name {
            font-weight: 700;
            font-family: 'Space Grotesk', sans-serif;
        }

        .dg-card .role {
            color: var(--ink-soft);
            font-size: .85rem;
        }

        .objectifs {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
            margin-top: 38px;
        }

        .obj-card {
            --c: var(--green);
            --ct: var(--green-tint);
            background: var(--white);
            border: 1px solid var(--gray-line);
            border-radius: var(--radius);
            padding: 24px;
            position: relative;
            overflow: hidden;
            transition: transform .2s, box-shadow .2s;
        }

        .obj-card:nth-child(6n+2) {
            --c: var(--yellow);
            --ct: var(--yellow-tint);
        }

        .obj-card:nth-child(6n+3) {
            --c: var(--orange);
            --ct: var(--orange-tint);
        }

        .obj-card:nth-child(6n+4) {
            --c: var(--red);
            --ct: var(--red-tint);
        }

        .obj-card:nth-child(6n+5) {
            --c: var(--slate);
            --ct: #e9edf1;
        }

        .obj-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow);
        }

        .obj-card::before {
            content: "";
            position: absolute;
            inset: 0 0 auto 0;
            height: 4px;
            background: var(--c);
        }

        .obj-card .ico {
            width: 40px;
            height: 40px;
            border-radius: 11px;
            display: grid;
            place-items: center;
            font-size: 1.15rem;
            background: var(--ct);
            color: var(--c);
            margin-bottom: 14px;
        }

        .obj-card:nth-child(6n+2) .ico {
            color: #a07800;
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
        .services {
            background: var(--gray-bg);
        }

        .services-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
        }

        .service-card {
            --c: var(--green);
            --ct: var(--green-tint);
            position: relative;
            background: var(--white);
            border-radius: var(--radius);
            padding: 32px 28px;
            border: 1px solid var(--gray-line);
            transition: transform .2s, box-shadow .2s, border-color .2s;
            overflow: hidden;
        }

        .service-card:nth-child(6n+2) {
            --c: #d9a700;
            --ct: var(--yellow-tint);
        }

        .service-card:nth-child(6n+3) {
            --c: var(--orange);
            --ct: var(--orange-tint);
        }

        .service-card:nth-child(6n+4) {
            --c: var(--red);
            --ct: var(--red-tint);
        }

        .service-card:nth-child(6n+5) {
            --c: var(--slate);
            --ct: #e9edf1;
        }

        .service-card::after {
            content: "";
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            height: 4px;
            background: var(--c);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform .3s ease;
        }

        .service-card:hover {
            transform: translateY(-6px);
            box-shadow: var(--shadow);
        }

        .service-card:hover::after {
            transform: scaleX(1);
        }

        .service-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            background: var(--ct);
            color: var(--c);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            font-size: 1.5rem;
        }

        .service-card h4 {
            font-size: 1.08rem;
            margin-bottom: 10px;
        }

        .service-card p {
            font-size: .9rem;
            color: var(--gray);
            margin-bottom: 18px;
        }

        .service-card .link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: .85rem;
            font-weight: 800;
            color: var(--green-dark);
        }

        .service-card .link i {
            transition: transform .2s;
        }

        .service-card .link:hover i {
            transform: translateX(4px);
        }

        /* ========== PÔLES ========== */
        .poles {
            background: var(--white);
        }

        .poles-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
        }

        .pole-card {
            --c: var(--green);
            background: #fff;
            border-radius: 16px;
            padding: 22px;
            border: 1px solid var(--gray-line);
            border-top: 4px solid var(--c);
            box-shadow: var(--shadow-sm);
            transition: transform .2s, box-shadow .2s;
        }

        .pole-card:nth-child(4n+2) {
            --c: var(--yellow);
        }

        .pole-card:nth-child(4n+3) {
            --c: var(--orange);
        }

        .pole-card:nth-child(4n+4) {
            --c: var(--red);
        }

        .pole-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow);
        }

        .pole-card .since {
            font-size: .7rem;
            color: var(--ink-soft);
            font-weight: 800;
            letter-spacing: .06em;
            background: var(--gray-bg);
            display: inline-block;
            padding: 4px 10px;
            border-radius: 999px;
        }

        .pole-card h4 {
            font-size: 1.02rem;
            margin: 12px 0 10px;
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
            line-height: 1.65;
        }

        /* ========== FAQ ========== */
        .faq {
            background: var(--gray-bg);
        }

        .faq-item {
            background: #fff;
            border: 1px solid var(--gray-line);
            border-radius: 14px;
            margin-bottom: 12px;
            transition: box-shadow .2s, border-color .2s;
        }

        .faq-item.open {
            border-color: rgba(63, 163, 80, .45);
            box-shadow: var(--shadow-sm);
        }

        .faq-q {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            padding: 20px 22px;
            background: none;
            border: 0;
            text-align: left;
            font: inherit;
            font-weight: 700;
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1rem;
            color: var(--ink);
            cursor: pointer;
        }

        .faq-q .right {
            display: flex;
            align-items: center;
            gap: 12px;
            flex: none;
        }

        .faq-q .badge {
            font-family: 'Manrope', sans-serif;
            font-size: .72rem;
            font-weight: 800;
            padding: 4px 10px;
            border-radius: 999px;
            background: var(--green-tint);
            color: var(--green-dark);
            white-space: nowrap;
        }

        .faq-item .chev {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: grid;
            place-items: center;
            background: var(--orange-tint);
            color: var(--orange-dark);
            font-size: 1.2rem;
            line-height: 1;
            transition: transform .2s, background .2s, color .2s;
        }

        .faq-item.open .chev {
            transform: rotate(45deg);
            background: var(--green);
            color: #fff;
        }

        .faq-a {
            max-height: 0;
            overflow: hidden;
            transition: max-height .3s ease, padding .3s ease;
            padding: 0 22px;
            font-size: .93rem;
            color: var(--ink-soft);
        }

        .faq-item.open .faq-a {
            max-height: 400px;
            padding: 0 22px 22px;
        }

        /* ========== CONTACT ========== */
        .contact-section {
            background: var(--white);
        }

        .contact-grid {
            display: grid;
            grid-template-columns: 1.1fr .9fr;
            gap: 56px;
        }

        .contact-grid h2 {
            font-size: clamp(1.6rem, 3vw, 2.2rem);
        }

        .contact-lead {
            color: var(--gray);
            margin: 14px 0 26px;
        }

        .flash {
            display: flex;
            gap: 10px;
            align-items: flex-start;
            padding: 12px 16px;
            border-radius: 12px;
            font-size: .9rem;
            font-weight: 600;
            margin-bottom: 16px;
        }

        .flash-ok {
            background: var(--green-tint);
            color: var(--green-dark);
            border-left: 4px solid var(--green);
        }

        .flash-err {
            background: var(--red-tint);
            color: #a8141b;
            border-left: 4px solid var(--red);
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .form-grid .full {
            grid-column: 1 / -1;
        }

        .field label {
            display: block;
            font-size: .76rem;
            font-weight: 800;
            color: var(--ink-soft);
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: .05em;
        }

        .field input,
        .field textarea {
            width: 100%;
            padding: 13px 15px;
            border: 1.5px solid var(--gray-line);
            border-radius: 12px;
            font-family: inherit;
            font-size: .93rem;
            background: var(--gray-bg);
            transition: all .2s;
        }

        .field textarea {
            resize: vertical;
            min-height: 130px;
        }

        .field input:focus,
        .field textarea:focus {
            outline: none;
            border-color: var(--green);
            background: #fff;
            box-shadow: 0 0 0 4px rgba(63, 163, 80, .15);
        }

        .contact-info {
            position: relative;
            color: #fff;
            border-radius: 24px;
            padding: 36px;
            align-self: start;
            overflow: hidden;
            background:
                radial-gradient(420px 280px at 100% 0%, rgba(255, 122, 0, .35), transparent 60%),
                radial-gradient(380px 260px at 0% 100%, rgba(255, 201, 20, .22), transparent 60%),
                linear-gradient(145deg, var(--green), var(--green-dark));
            box-shadow: var(--shadow);
        }

        .contact-info h3 {
            color: #fff;
            margin-bottom: 24px;
            font-size: 1.3rem;
        }

        .info-row {
            display: flex;
            gap: 14px;
            align-items: center;
            margin-bottom: 18px;
            font-size: .93rem;
        }

        .info-row:last-child {
            margin-bottom: 0;
        }

        .info-row .ic {
            flex: none;
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: grid;
            place-items: center;
            background: rgba(255, 255, 255, .18);
            font-size: 1.1rem;
        }

        /* ========== FOOTER ========== */
        footer {
            position: relative;
            background: var(--slate-deep);
            color: #c9cace;
            padding: 68px 0 28px;
        }

        footer::before {
            content: "";
            position: absolute;
            inset: 0 0 auto 0;
            height: 5px;
            background: linear-gradient(90deg, var(--green) 0 34%, var(--yellow) 34% 67%, var(--red) 67% 100%);
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
            font-size: .95rem;
            margin-bottom: 16px;
        }

        .footer-grid li {
            margin-bottom: 10px;
            font-size: .88rem;
        }

        .footer-grid a {
            transition: color .15s;
        }

        .footer-grid a:hover {
            color: var(--yellow);
        }

        .footer-logo {
            height: 100px;
            width: auto;
            background: #fff;
            padding: 8px 14px;
            border-radius: 14px;
            object-fit: contain;
        }

        .social-row {
            display: flex;
            gap: 10px;
            margin-top: 22px;
            flex-wrap: wrap;
        }

        .social-btn {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            border: 1.5px solid #45505a;
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
            border-color: var(--yellow);
            color: var(--yellow);
            background: rgba(255, 201, 20, .1);
            transform: translateY(-2px);
        }

        .footer-bottom {
            border-top: 1px solid #333f49;
            padding-top: 24px;
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            font-size: .82rem;
            color: #8b95a0;
        }

        /* Bouton retour en haut */
        .to-top {
            position: fixed;
            right: 18px;
            bottom: 18px;
            z-index: 60;
            width: 44px;
            height: 44px;
            border: 0;
            border-radius: 50%;
            background: var(--green);
            color: #fff;
            font-size: 1.2rem;
            display: grid;
            place-items: center;
            cursor: pointer;
            box-shadow: 0 10px 22px -8px rgba(63, 163, 80, .9);
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px);
            transition: all .25s;
        }

        .to-top.show {
            opacity: 1;
            visibility: visible;
            transform: none;
        }

        .to-top:hover {
            background: var(--green-dark);
        }

        /* ========== RESPONSIVE ========== */
        @media (max-width: 1020px) {
            .nav-links a {
                padding: 8px 9px;
                font-size: .85rem;
            }
        }

        @media (max-width: 920px) {
            section {
                padding: 72px 0;
            }

            .nav-links {
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: #fff;
                flex-direction: column;
                align-items: stretch;
                padding: 14px 20px 20px;
                border-bottom: 1px solid var(--gray-line);
                box-shadow: 0 20px 30px -20px rgba(25, 34, 42, .3);
                gap: 4px;
                display: none;
            }

            .nav-links.open {
                display: flex;
            }

            .nav-links a {
                padding: 12px 14px;
                font-size: .95rem;
            }

            .menu-toggle {
                display: block;
            }

            .nav-actions .btn-ghost {
                display: none;
            }

            .hero-grid,
            .about-grid,
            .contact-grid,
            .anniv-grid {
                grid-template-columns: 1fr;
            }

            .hero-grid>div:first-child {
                text-align: center;
            }

            .hero .lead {
                margin-left: auto;
                margin-right: auto;
            }

            .hero-cta {
                justify-content: center;
            }

            .hero-stats {
                margin-inline: auto;
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

            .countdown {
                gap: 8px;
            }

            .nav-actions .btn {
                padding: 10px 16px;
                font-size: .85rem;
            }
        }

        @media (max-width: 560px) {

            .poles-grid,
            .form-grid {
                grid-template-columns: 1fr;
            }

            .hero-stats {
                grid-template-columns: 1fr;
            }

            .footer-grid {
                grid-template-columns: 1fr;
            }

            .wrap {
                padding: 0 20px;
            }
        }
    </style>
</head>

<body>
    {{-- ========== BARRE 40 ANS ========== --}}
    <div class="anniv-bar">
        🎉 L'ONFP fête ses 40 ans le 19 novembre 2026 · <a href="#anniversaire">Découvrir</a>
    </div>

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
                <li><a href="{{ route('manuels.showDefault') }}" target="_blank" rel="noopener">Nos manuels</a></li>
                <li><a href="#faq">FAQ</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>

            <div class="nav-actions">
                <a href="{{ route('register-page') }}" class="btn btn-ghost">S'inscrire</a>
                <a href="{{ url('login') }}" class="btn btn-primary">Se connecter</a>
                <button type="button" class="menu-toggle" id="menuToggle" aria-label="Ouvrir le menu"
                    aria-expanded="false" aria-controls="navLinks">
                    <i class="bi bi-list"></i>
                </button>
            </div>
        </nav>
    </header>

    {{-- ========== HERO ========== --}}
    @php $slides = ($posts ?? collect())->filter(fn($p) => !empty($p->image)); @endphp

    <section class="hero" id="accueil">
        <div class="wrap hero-grid">
            <div>
                <a href="#anniversaire" class="hero-badge">
                    1986 – 2026 · 40 ans au service de la formation professionnelle
                </a>
                <div class="eyebrow">Office national de formation professionnelle</div>
                <h1>La référence de la <span class="accent">formation professionnelle</span> au Sénégal</h1>
                <p class="lead">SIGOF centralise et digitalise vos démarches : demandes de formation, agréments
                    d'opérateurs, suivi des partenariats, en un seul portail, partout dans le pays.</p>
                <div class="hero-cta">
                    <a href="{{ route('register-page') }}" class="btn btn-primary">
                        Créer mon compte <i class="bi bi-arrow-right"></i>
                    </a>
                    <a href="#apropos" class="btn btn-ghost">Découvrir la plateforme</a>
                    {{-- <a href="{{ url('pcharge') }}" class="btn btn-danger">Déposer une prise en charge</a> --}}
                </div>

                <div class="hero-stats">
                    <div class="stat-card">
                        <span class="num">{{ $anciennete }}+</span>
                        <span class="lbl">ans d'expérience dans la formation professionnelle</span>
                    </div>
                    <div class="stat-card">
                        <span class="num">{{ $antennes->count() - 1 }}</span>
                        <span class="lbl">pôles sur tout le territoire</span>
                    </div>
                    <div class="stat-card">
                        <span class="num">6</span>
                        <span class="lbl">domaines d'intervention</span>
                    </div>
                </div>
            </div>

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
            @else
                <div class="hero-visual">
                    <img src="{{ asset('assets/img/logo-onfp.png') }}" alt="Logo ONFP">
                </div>
            @endif
        </div>

        {{-- Bloc « Vérifiez votre sélection » (désactivé) --}}
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

    {{-- ========== 40 ANS ========== --}}
    <section class="anniv" id="anniversaire">
        <div class="wrap anniv-grid">
            <div class="reveal">
                <div class="eyebrow">Événement</div>
                <h2>L'ONFP célèbre ses <span class="accent">40 ans</span></h2>
                <p>Quatre décennies d'engagement pour la formation professionnelle au Sénégal.
                    Rejoignez-nous pour célébrer cette étape historique.</p>
                <div class="anniv-meta">
                    <span><i class="bi bi-calendar-event"></i>Jeudi 19 novembre 2026</span>
                    <span><i class="bi bi-geo-alt"></i>Azalaï Hôtel, Dakar</span>
                </div>
            </div>

            <div class="countdown reveal" id="countdown" aria-live="off">
                <div class="cd-box"><span class="n" id="cd-d">--</span><span class="l">Jours</span></div>
                <div class="cd-box"><span class="n" id="cd-h">--</span><span class="l">Heures</span></div>
                <div class="cd-box"><span class="n" id="cd-m">--</span><span class="l">Minutes</span></div>
                <div class="cd-box"><span class="n" id="cd-s">--</span><span class="l">Secondes</span></div>
            </div>
        </div>
    </section>

    {{-- ========== À PROPOS ========== --}}
    <section class="about" id="apropos">
        <div class="wrap about-grid">
            <div class="reveal">
                <div class="eyebrow">À propos de l'ONFP</div>
                <h2>Un établissement public au service de la formation, depuis 1986</h2>
                <ul class="about-list">
                    <li><span class="dot"></span>Aider à mettre en œuvre les objectifs sectoriels du gouvernement et
                        assister les organismes publics et privés.</li>
                    <li><span class="dot"></span>Réaliser des études sur l'emploi, la qualification professionnelle
                        et les moyens de la formation initiale et continue.</li>
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

            <div class="reveal">
                <div class="eyebrow">La plateforme SIGOF</div>
                <h2>Toute la gestion des opérations de formation, sur un seul portail</h2>
                <p class="about-intro">Le Système Intégré de Gestion des Opérations de Formation centralise et
                    automatise la gestion des demandeurs, des opérateurs et des partenaires de l'ONFP.</p>
                <div class="objectifs">
                    <div class="obj-card">
                        <div class="ico"><i class="bi bi-sliders"></i></div>
                        <h4>Optimisation des processus</h4>
                        <p>Simplifier la gestion des inscriptions et la coordination des parties prenantes.</p>
                    </div>
                    <div class="obj-card">
                        <div class="ico"><i class="bi bi-person-lines-fill"></i></div>
                        <h4>Gestion des demandeurs</h4>
                        <p>Enregistrement et traitement des demandes individuelles ou collectives.</p>
                    </div>
                    <div class="obj-card">
                        <div class="ico"><i class="bi bi-building"></i></div>
                        <h4>Gestion des opérateurs</h4>
                        <p>Enregistrement et traitement des prestataires de formation.</p>
                    </div>
                    <div class="obj-card">
                        <div class="ico"><i class="bi bi-link-45deg"></i></div>
                        <h4>Gestion des partenaires</h4>
                        <p>Coordination et suivi des collaborations institutionnelles.</p>
                    </div>
                    <div class="obj-card">
                        <div class="ico"><i class="bi bi-window-desktop"></i></div>
                        <h4>Portail interactif</h4>
                        <p>Interface accessible en ligne pour demandeurs et opérateurs.</p>
                    </div>
                    <div class="obj-card">
                        <div class="ico"><i class="bi bi-bar-chart-line"></i></div>
                        <h4>Statistiques à jour</h4>
                        <p>Suivi des actions de formation menées sur l'ensemble du territoire.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ========== SERVICES ========== --}}
    <section class="services" id="services">
        <div class="wrap">
            <div class="section-head reveal">
                <div class="eyebrow">Nos missions</div>
                <h2>Six domaines d'intervention, une seule mission</h2>
                <p>De la formation à l'insertion professionnelle, l'ONFP accompagne les branches professionnelles, les
                    entreprises et les demandeurs d'emploi à chaque étape.</p>
            </div>
            <div class="services-grid">
                <div class="service-card reveal">
                    <div class="service-icon"><i class="bi bi-mortarboard"></i></div>
                    <h4>Formation | Qualification</h4>
                    <p>Organisation d'actions de formation au bénéfice des branches professionnelles, demandeurs
                        d'emploi, travailleurs et entreprises.</p>
                    <a class="link" href="https://www.onfp.sn/formations">En savoir plus <i
                            class="bi bi-arrow-right"></i></a>
                </div>
                <div class="service-card reveal">
                    <div class="service-icon"><i class="bi bi-patch-check"></i></div>
                    <h4>Évaluation | Certification</h4>
                    <p>Contrôle de l'exécution des conventions signées avec les opérateurs et évaluation des actions
                        de formation menées.</p>
                    <a class="link" href="https://www.onfp.sn/evaluations">En savoir plus <i
                            class="bi bi-arrow-right"></i></a>
                </div>
                <div class="service-card reveal">
                    <div class="service-icon"><i class="bi bi-bricks"></i></div>
                    <h4>Construction | Équipement</h4>
                    <p>Maîtrise d'ouvrage de construction et d'équipement de centres de formation professionnelle.</p>
                    <a class="link" href="https://www.onfp.sn/constructions">En savoir plus <i
                            class="bi bi-arrow-right"></i></a>
                </div>
                <div class="service-card reveal">
                    <div class="service-icon"><i class="bi bi-compass"></i></div>
                    <h4>Suivi-Insertion</h4>
                    <p>Analyse des besoins, co-élaboration du projet professionnel et conseil sur les opportunités du
                        marché de l'emploi.</p>
                    <a class="link" href="https://www.onfp.sn/suivi-insertions">En savoir plus <i
                            class="bi bi-arrow-right"></i></a>
                </div>
                <div class="service-card reveal">
                    <div class="service-icon"><i class="bi bi-journal-bookmark"></i></div>
                    <h4>Documentation | Édition</h4>
                    <p>Production et diffusion de documentation et de supports techniques et pédagogiques.</p>
                    <a class="link" href="https://www.onfp.sn/documentations">En savoir plus <i
                            class="bi bi-arrow-right"></i></a>
                </div>
                <div class="service-card reveal">
                    <div class="service-icon"><i class="bi bi-search"></i></div>
                    <h4>Étude | Recherche</h4>
                    <p>Production et diffusion de connaissances et de savoirs sur la formation professionnelle.</p>
                    <a class="link" href="https://www.onfp.sn/etudes">En savoir plus <i
                            class="bi bi-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </section>

    {{-- ========== PÔLES ========== --}}
    <section class="poles" id="poles">
        <div class="wrap">
            <div class="section-head reveal">
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
                    <div class="pole-card reveal">
                        <div class="since">
                            {{ $antenne->date_ouverture ? 'DEPUIS ' . $antenne->date_ouverture->format('Y') : $antenne->code }}
                        </div>
                        <h4>{{ $antenne->name }}</h4>
                        @if ($regions)
                            <div class="zone">
                                <i class="bi bi-check-circle-fill"></i>
                                {{ $regions }}
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
    <section class="faq" id="faq">
        <div class="wrap" style="max-width:820px;">
            <div class="section-head reveal">
                <div class="eyebrow">Besoin d'aide</div>
                <h2>Réponses aux questions fréquentes</h2>
            </div>
            <div class="faq-list reveal">
                <div class="faq-item">
                    <button type="button" class="faq-q" aria-expanded="false">
                        <span>Bonjour, je n'arrive pas à m'inscrire sur vos sites</span>
                        <span class="right"><span class="chev">+</span></span>
                    </button>
                    <div class="faq-a">Pour l'inscription, suivez le guide ou la vidéo disponible sur la plateforme.
                        Si le problème persiste, appelez directement le 33 827 92 51.</div>
                </div>
                <div class="faq-item">
                    <button type="button" class="faq-q" aria-expanded="false">
                        <span>Nous nous sommes trompés d'option : « apprenant » au lieu d'« opérateur »</span>
                        <span class="right"><span class="badge">Résolu</span><span class="chev">+</span></span>
                    </button>
                    <div class="faq-a">Contactez le support via le formulaire de contact pour faire corriger votre
                        profil vers « Opérateur ».</div>
                </div>
                <div class="faq-item">
                    <button type="button" class="faq-q" aria-expanded="false">
                        <span>Je voudrais déposer une demande d'agrément de formation</span>
                        <span class="right"><span class="chev">+</span></span>
                    </button>
                    <div class="faq-a">Créez votre compte opérateur puis déposez votre dossier d'agrément directement
                        depuis votre espace personnel.</div>
                </div>
                <div class="faq-item">
                    <button type="button" class="faq-q" aria-expanded="false">
                        <span>J'aimerais supprimer mon compte et mes informations</span>
                        <span class="right"><span class="chev">+</span></span>
                    </button>
                    <div class="faq-a">Contactez notre équipe via le formulaire ci-dessous ; votre demande sera
                        traitée conformément à notre politique de données.</div>
                </div>
            </div>
        </div>
    </section>

    {{-- ========== CONTACT ========== --}}
    <section class="contact-section" id="contact">
        <div class="wrap contact-grid">
            <div class="reveal">
                <div class="eyebrow">Nous contacter</div>
                <h2>Une question ? Écrivez-nous</h2>
                <p class="contact-lead">Ce formulaire est réservé aux questions et demandes d'information. Il ne
                    remplace pas un dépôt de candidature.</p>

                @if ($msg = session('success') ?? session('status'))
                    <div class="flash flash-ok" role="status"><i class="bi bi-check-circle-fill"></i>
                        <span>{{ $msg }}</span>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="flash flash-err" role="alert"><i class="bi bi-exclamation-circle-fill"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <form class="form-grid" method="POST" action="{{ route('contacts.store') }}">
                    @csrf
                    <div class="field"><label for="c-email">Email</label>
                        <input id="c-email" name="emailadresse" type="email" placeholder="vous@exemple.sn"
                            value="{{ old('emailadresse') }}" required>
                    </div>
                    <div class="field"><label for="c-tel">Téléphone</label>
                        <input id="c-tel" name="telephone" type="tel" placeholder="770000000"
                            value="{{ old('telephone') }}">
                    </div>
                    <div class="field full"><label for="c-objet">Objet</label>
                        <input id="c-objet" name="objet" type="text" placeholder="Objet de votre message"
                            value="{{ old('objet') }}" required>
                    </div>
                    <div class="field full"><label for="c-message">Message</label>
                        <textarea id="c-message" name="message" placeholder="Écrivez votre message ici…" required>{{ old('message') }}</textarea>
                    </div>
                    <div class="full"><button type="submit" class="btn btn-primary">Envoyer le message <i
                                class="bi bi-send"></i></button>
                    </div>
                </form>
            </div>

            <div class="contact-info reveal">
                <h3>Direction générale</h3>
                <div class="info-row"><span class="ic"><i class="bi bi-geo-alt"></i></span><span>Sipres 1, lot 2 - 2
                        voies Liberté 6, extension VDN, Dakar</span></div>
                <div class="info-row"><span class="ic"><i class="bi bi-telephone"></i></span><span>+221 33 827 92
                        51</span></div>
                <div class="info-row"><span class="ic"><i class="bi bi-envelope"></i></span><span>onfp@onfp.sn</span>
                </div>
                <div class="info-row"><span class="ic"><i class="bi bi-whatsapp"></i></span><span>WhatsApp : +221 77
                        291 18 38</span></div>
            </div>
        </div>
    </section>

    {{-- ========== FOOTER ========== --}}
    <footer>
        <div class="wrap">
            <div class="footer-grid">
                <div>
                    <a href="{{ url('/') }}">
                        <img src="{{ asset('assets/img/ONFP_logo_header_600px@2x.png') }}" alt="Logo ONFP"
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
                        <a class="social-btn" href="https://www.facebook.com/profile.php?id=61566912421177"
                            target="_blank" rel="noopener" aria-label="Facebook">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M13.5 21v-8h2.7l.4-3.1h-3.1V7.9c0-.9.3-1.5 1.6-1.5h1.7V3.6C16.5 3.6 15.6 3.5 14.6 3.5c-2.5 0-4.2 1.5-4.2 4.3v2.1H7.7v3.1h2.7v8h3.1Z" />
                            </svg>
                        </a>
                        <a class="social-btn" href="https://www.instagram.com/onfp.sn/" target="_blank"
                            rel="noopener" aria-label="Instagram">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <rect x="3" y="3" width="18" height="18" rx="5" />
                                <circle cx="12" cy="12" r="4" />
                                <circle cx="17.2" cy="6.8" r="1" />
                            </svg>
                        </a>
                        <a class="social-btn"
                            href="https://www.linkedin.com/company/104719756/admin/page-posts/published/"
                            target="_blank" rel="noopener" aria-label="LinkedIn">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M6.9 8.6H3.6V20h3.3V8.6ZM5.3 3.5a1.9 1.9 0 1 0 0 3.9 1.9 1.9 0 0 0 0-3.9ZM20.4 20h-3.3v-5.9c0-1.4 0-3.2-2-3.2s-2.3 1.6-2.3 3.1V20H9.5V8.6h3.1v1.5h.1c.4-.8 1.5-1.7 3.2-1.7 3.4 0 4.5 2.2 4.5 5.2V20Z" />
                            </svg>
                        </a>
                        <a class="social-btn" href="https://www.youtube.com/@CelluleCommunicationONFP/shorts"
                            target="_blank" rel="noopener" aria-label="YouTube">
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
                        <li><a href="#poles">Nos pôles</a></li>
                        <li><a href="#faq">FAQ</a></li>
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
                <span>© {{ date('Y') }} ONFP · Tous droits réservés</span>
                <span>Conçu par l'équipe digitale ONFP</span>
            </div>
        </div>
    </footer>

    <button type="button" class="to-top" id="toTop" aria-label="Retour en haut"><i
            class="bi bi-arrow-up"></i></button>

    {{-- ========== SCRIPTS ========== --}}
    <script>
        // Menu mobile
        (function() {
            const toggle = document.getElementById('menuToggle');
            const links = document.getElementById('navLinks');
            if (!toggle || !links) return;
            const icon = toggle.querySelector('i');

            function setOpen(open) {
                links.classList.toggle('open', open);
                toggle.setAttribute('aria-expanded', open);
                icon.className = open ? 'bi bi-x-lg' : 'bi bi-list';
            }
            toggle.addEventListener('click', () => setOpen(!links.classList.contains('open')));
            links.addEventListener('click', e => {
                if (e.target.closest('a')) setOpen(false);
            });
        })();

        // Lien actif selon la section visible + bouton retour en haut
        (function() {
            const links = [...document.querySelectorAll('.nav-links a[href^="#"]')];
            const map = new Map(links.map(a => [a.getAttribute('href').slice(1), a]));
            const sections = [...map.keys()].map(id => document.getElementById(id)).filter(Boolean);
            const toTop = document.getElementById('toTop');

            function onScroll() {
                const y = window.scrollY + 140;
                let current = null;
                sections.forEach(s => {
                    if (s.offsetTop <= y) current = s.id;
                });
                links.forEach(a => a.classList.remove('active'));
                if (current && map.get(current)) map.get(current).classList.add('active');
                toTop.classList.toggle('show', window.scrollY > 600);
            }
            window.addEventListener('scroll', onScroll, {
                passive: true
            });
            onScroll();
            toTop.addEventListener('click', () => window.scrollTo({
                top: 0,
                behavior: 'smooth'
            }));
        })();

        // Apparition au scroll
        (function() {
            const items = document.querySelectorAll('.reveal');
            if (!('IntersectionObserver' in window)) {
                items.forEach(el => el.classList.add('in'));
                return;
            }
            const io = new IntersectionObserver((entries) => {
                entries.forEach(en => {
                    if (en.isIntersecting) {
                        en.target.classList.add('in');
                        io.unobserve(en.target);
                    }
                });
            }, {
                threshold: .12
            });
            items.forEach((el, i) => {
                el.style.transitionDelay = (i % 3) * 80 + 'ms';
                io.observe(el);
            });
        })();

        // FAQ (accordéon accessible)
        document.querySelectorAll('.faq-item').forEach(item => {
            const btn = item.querySelector('.faq-q');
            btn.addEventListener('click', () => {
                const open = item.classList.toggle('open');
                btn.setAttribute('aria-expanded', open);
            });
        });
    </script>

    <script>
        // Carrousel du hero
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

    <script>
        // Compte à rebours des 40 ans
        (function() {
            // Dakar = UTC+0 toute l'année. Ajustez l'heure de début de la cérémonie.
            const target = new Date('2026-11-19T09:00:00Z').getTime();
            const box = document.getElementById('countdown');
            if (!box) return;
            const pad = n => String(n).padStart(2, '0');

            function tick() {
                const diff = target - Date.now();
                if (diff <= 0) {
                    const sameDay = Date.now() - target < 24 * 3600 * 1000;
                    box.innerHTML = '<div class="cd-done">' +
                        (sameDay ? "C'est aujourd'hui !" : "Merci d'avoir célébré les 40 ans de l'ONFP") + '</div>';
                    return clearInterval(t);
                }
                document.getElementById('cd-d').textContent = pad(Math.floor(diff / 864e5));
                document.getElementById('cd-h').textContent = pad(Math.floor(diff % 864e5 / 36e5));
                document.getElementById('cd-m').textContent = pad(Math.floor(diff % 36e5 / 6e4));
                document.getElementById('cd-s').textContent = pad(Math.floor(diff % 6e4 / 1e3));
            }
            const t = setInterval(tick, 1000);
            tick();
        })();
    </script>
</body>

</html>
