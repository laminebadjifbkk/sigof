<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Merci pour votre inscription | 40 ans de l'ONFP</title>
    <link rel="icon" href="{{ asset('assets/img/ONFP_photo-profil_1080x1080.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --orange: #F28500;
            --green: #1b8a3a;
            --gold: #f5b800;
            --red: #d62828;
            --ink: #1f2933;
            --muted: #6b7280;
            --line: #e8e2da;
        }

        body {
            font-family: 'Poppins', sans-serif;
            color: var(--ink);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px 16px;
            overflow-x: hidden;
            background:
                radial-gradient(900px 500px at 8% -5%, rgba(242, 133, 0, .18), transparent 60%),
                radial-gradient(800px 500px at 100% 0%, rgba(27, 138, 58, .14), transparent 60%),
                radial-gradient(700px 500px at 50% 110%, rgba(245, 184, 0, .18), transparent 60%),
                #fbf6ef;
            background-attachment: fixed;
        }

        /* ---------- Carte ---------- */
        .thank-card {
            position: relative;
            width: 100%;
            max-width: 560px;
            background: #fff;
            border-radius: 24px;
            text-align: center;
            box-shadow: 0 25px 60px -15px rgba(31, 41, 51, .25), 0 4px 14px rgba(31, 41, 51, .06);
            animation: rise .6s ease both;
            z-index: 2;
        }

        .thank-card::before {
            content: "";
            display: block;
            height: 8px;
            border-radius: 24px 24px 0 0;
            background: linear-gradient(90deg, var(--green) 0 33%, var(--gold) 33% 66%, var(--red) 66% 100%);
        }

        @keyframes rise {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: none;
            }
        }

        .card-top {
            padding: 30px 40px 6px;
            background: linear-gradient(180deg, #fffaf3 0%, #fff 100%);
            border-radius: 0;
        }

        .logos-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .logo-onfp {
            height: 90px;
            width: auto;
            max-width: 42%;
            object-fit: contain;
        }

        .logo-40 {
            height: 110px;
            width: auto;
            max-width: 55%;
            object-fit: contain;
            filter: drop-shadow(0 8px 14px rgba(0, 0, 0, .12));
        }

        .card-content {
            padding: 6px 40px 38px;
        }

        /* ---------- Coche animée ---------- */
        .check-wrap {
            width: 84px;
            height: 84px;
            margin: 10px auto 18px;
            border-radius: 50%;
            background: linear-gradient(135deg, #2fa653, var(--green));
            box-shadow: 0 12px 26px -8px rgba(27, 138, 58, .7), 0 0 0 10px rgba(27, 138, 58, .1);
            display: flex;
            align-items: center;
            justify-content: center;
            animation: pop .6s cubic-bezier(.2, 1.4, .4, 1) .2s both;
        }

        .check-wrap svg {
            width: 44px;
            height: 44px;
        }

        .check-wrap path {
            fill: none;
            stroke: #fff;
            stroke-width: 5;
            stroke-linecap: round;
            stroke-linejoin: round;
            stroke-dasharray: 50;
            stroke-dashoffset: 50;
            animation: draw .5s ease .65s forwards;
        }

        @keyframes pop {
            from {
                transform: scale(0);
                opacity: 0;
            }

            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        @keyframes draw {
            to {
                stroke-dashoffset: 0;
            }
        }

        h1 {
            font-size: 2rem;
            font-weight: 700;
            margin: 0 0 10px;
            background: linear-gradient(90deg, var(--orange), var(--red));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .lead-text {
            font-size: 1rem;
            color: #4b5563;
            line-height: 1.65;
            margin-bottom: 18px;
        }

        .lead-text strong {
            color: var(--ink);
        }

        /* ---------- Pastilles ---------- */
        .event-chips {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
            margin-bottom: 22px;
        }

        .chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #fff4e5;
            color: #a8520f;
            border: 1px solid rgba(242, 133, 0, .3);
            padding: 7px 16px;
            border-radius: 999px;
            font-size: .85rem;
            font-weight: 500;
        }

        .chip i {
            color: var(--orange);
        }

        /* ---------- Info e-mail ---------- */
        .info-box {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            text-align: left;
            background: #f2faf4;
            border: 1px solid #cfe9d6;
            border-radius: 14px;
            padding: 14px 16px;
            font-size: .88rem;
            color: #2f5d3b;
            margin-bottom: 8px;
        }

        .info-box i {
            font-size: 1.25rem;
            color: var(--green);
            line-height: 1.2;
        }

        .info-box.warn {
            background: #fff8e6;
            border-color: #f6dd9b;
            color: #7a5a06;
        }

        .info-box.warn i {
            color: #d79a00;
        }

        /* ---------- Bouton ---------- */
        .btn-home {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 22px;
            padding: 13px 28px;
            border-radius: 14px;
            border: 0;
            font-weight: 600;
            color: #fff;
            background: linear-gradient(135deg, var(--orange), #e0561a);
            box-shadow: 0 10px 22px -8px rgba(242, 133, 0, .7);
            transition: transform .2s, box-shadow .2s, filter .2s;
        }

        .btn-home:hover {
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 14px 26px -8px rgba(242, 133, 0, .8);
            filter: brightness(1.04);
        }

        .footer-text {
            font-size: .78rem;
            color: var(--muted);
            margin: 24px 0 0;
        }

        /* ---------- Confettis ---------- */
        .confetti {
            position: fixed;
            top: -20px;
            width: 10px;
            height: 14px;
            opacity: .95;
            z-index: 1;
            pointer-events: none;
            animation: fall linear forwards;
        }

        @keyframes fall {
            to {
                transform: translateY(110vh) rotate(720deg);
                opacity: .9;
            }
        }

        @media (prefers-reduced-motion: reduce) {

            .thank-card,
            .check-wrap,
            .check-wrap path {
                animation: none;
            }

            .check-wrap path {
                stroke-dashoffset: 0;
            }
        }

        @media (max-width: 575px) {

            .card-top,
            .card-content {
                padding-left: 22px;
                padding-right: 22px;
            }

            h1 {
                font-size: 1.6rem;
            }

            .logo-onfp {
                height: 62px;
            }

            .logo-40 {
                height: 78px;
            }
        }
    </style>
</head>

<body>
    <div class="thank-card">
        <div class="card-top">
            <div class="logos-row">
                <img src="{{ asset('assets/img/ONFP_photo-profil_1080x1080.png') }}" alt="ONFP" class="logo-onfp"
                    onerror="this.style.display='none'">
                <img src="{{ asset('assets/img/logo-40-ans.png') }}" alt="40 ans de l'ONFP - 1986-2026" class="logo-40"
                    onerror="this.style.display='none'">
            </div>
        </div>

        <div class="card-content">
            <div class="check-wrap" aria-hidden="true">
                <svg viewBox="0 0 52 52">
                    <path d="M14 27 l8 8 l16 -18" />
                </svg>
            </div>

            <h1>Merci !</h1>
            <p class="lead-text">
                Votre participation à la <strong>célébration des 40 ans de l'ONFP</strong>
                a été confirmée avec succès. Nous avons hâte de vous accueillir.
            </p>

            <div class="event-chips">
                <span class="chip"><i class="bi bi-calendar-event"></i> Jeudi 19 novembre 2026</span>
                {{-- TODO : précisez l'heure et le lieu exacts --}}
                <span class="chip"><i class="bi bi-geo-alt-fill"></i> Dakar</span>
            </div>

            @if (session('warning'))
                <div class="info-box warn">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <div>{{ session('warning') }}</div>
                </div>
            @else
                <div class="info-box">
                    <i class="bi bi-envelope-check-fill"></i>
                    <div>Un email de confirmation contenant le TDR vous a été envoyé. Pensez à vérifier vos courriers indésirables.
                    </div>
                </div>
            @endif

            <a href="{{ route('celebration-40-ans-onfp') }}" class="btn-home">
                <i class="bi bi-arrow-left"></i> Retour au formulaire
            </a>

            <p class="footer-text">© {{ date('Y') }} ONFP · Office National de Formation Professionnelle</p>
        </div>
    </div>

    <script>
        // Petite pluie de confettis aux couleurs de l'ONFP (désactivée si l'utilisateur limite les animations)
        (function() {
            if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
            const colors = ['#1b8a3a', '#f5b800', '#d62828', '#F28500', '#1f2933'];
            for (let i = 0; i < 70; i++) {
                const c = document.createElement('span');
                c.className = 'confetti';
                c.style.left = Math.random() * 100 + 'vw';
                c.style.background = colors[Math.floor(Math.random() * colors.length)];
                c.style.animationDuration = (3 + Math.random() * 3) + 's';
                c.style.animationDelay = (Math.random() * 1.5) + 's';
                c.style.transform = 'rotate(' + Math.random() * 360 + 'deg)';
                c.style.borderRadius = Math.random() > .5 ? '50%' : '2px';
                document.body.appendChild(c);
                setTimeout(() => c.remove(), 8000);
            }
        })();
    </script>
</body>

</html>
