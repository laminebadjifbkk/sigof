<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('assets/img/favicon.png') }}" rel="icon">
    <title>{{ 'ONFP | Manuel de ' . $manuel->title }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,600;12..96,800&family=Source+Sans+3:wght@400;600&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        :root {
            --forest: #0f3d2e;
            --forest-2: #17503d;
            --leaf: #1f7a57;
            --sun: #f0b323;
            --paper: #eef2ee;
            --card: #ffffff;
            --ink: #16241e;
            --muted: #5d6f66;
            --line: #d9e1db;
            --radius: 14px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Source Sans 3', system-ui, sans-serif;
            color: var(--ink);
            background: var(--paper);
            display: flex;
            min-height: 100vh;
        }

        h1,
        h2 {
            font-family: 'Bricolage Grotesque', 'Source Sans 3', sans-serif;
            margin: 0;
        }

        :focus-visible {
            outline: 3px solid var(--sun);
            outline-offset: 2px;
        }

        /* ---------- Bibliothèque (sidebar) ---------- */
        .sidebar {
            width: 340px;
            flex-shrink: 0;
            background: var(--forest);
            color: #e6f0ea;
            padding: 28px 20px;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-mark {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: var(--sun);
            color: var(--forest);
            display: grid;
            place-items: center;
            font-size: 1.2rem;
        }

        .brand h2 {
            font-size: 1.35rem;
            color: #fff;
            line-height: 1.1;
        }

        .brand small {
            color: #a9c4b8;
            font-size: .85rem;
        }

        .search {
            position: relative;
        }

        .search i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #8fb1a2;
        }

        .search input {
            width: 100%;
            padding: 12px 14px 12px 40px;
            border: 1px solid rgba(255, 255, 255, .15);
            background: rgba(255, 255, 255, .08);
            color: #fff;
            border-radius: 10px;
            font: inherit;
        }

        .search input::placeholder {
            color: #93b3a5;
        }

        .book-list {
            list-style: none;
            margin: 0;
            padding: 0;
            display: grid;
            gap: 4px;
        }

        .book-item a {
            display: flex;
            gap: 12px;
            align-items: flex-start;
            padding: 10px 12px;
            color: #dcebe3;
            text-decoration: none;
            border-radius: 10px;
            border-left: 4px solid transparent;
            line-height: 1.3;
            transition: background .2s;
        }

        .book-item a i {
            margin-top: 3px;
            color: #8fb1a2;
        }

        .book-item a:hover {
            background: rgba(255, 255, 255, .08);
        }

        .book-item.active a {
            background: rgba(255, 255, 255, .12);
            border-left-color: var(--sun);
            color: #fff;
            font-weight: 600;
        }

        .book-item.active a i {
            color: var(--sun);
        }

        .no-result {
            display: none;
            color: #a9c4b8;
            padding: 8px 12px;
        }

        /* ---------- Contenu ---------- */
        .content {
            flex: 1;
            min-width: 0;
            padding: 32px clamp(16px, 4vw, 56px) 56px;
        }

        .topbar {
            display: none;
        }

        .head {
            max-width: 980px;
            margin: 0 auto 24px;
        }

        .head h1 {
            font-size: clamp(1.8rem, 3.6vw, 2.9rem);
            font-weight: 800;
            line-height: 1.05;
            color: var(--forest);
            letter-spacing: -.02em;
        }

        .author {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 14px;
            color: var(--muted);
            font-weight: 600;
        }

        .author i {
            color: var(--leaf);
        }

        .desc {
            margin-top: 14px;
            max-width: 68ch;
            color: #33453d;
            line-height: 1.6;
            font-size: 1.05rem;
        }

        .reader {
            max-width: 980px;
            margin: 0 auto;
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: 0 12px 30px -18px rgba(15, 61, 46, .35);
        }

        .progress {
            height: 4px;
            background: var(--line);
        }

        .progress span {
            display: block;
            height: 100%;
            width: 0;
            background: var(--sun);
            transition: width .25s ease;
        }

        .stage {
            position: relative;
            padding: 28px 16px;
            background: #dfe6e1;
            display: flex;
            justify-content: center;
            min-height: 320px;
        }

        #pdf-render {
            display: block;
            max-width: 100%;
            background: #fff;
            box-shadow: 0 6px 22px rgba(0, 0, 0, .18);
            user-select: none;
            -webkit-user-select: none;
        }

        .status {
            position: absolute;
            inset: 0;
            display: grid;
            place-items: center;
            text-align: center;
            color: var(--forest);
            font-weight: 600;
            padding: 20px;
            background: #dfe6e1;
        }

        .status[hidden] {
            display: none;
        }

        .spinner {
            width: 34px;
            height: 34px;
            margin: 0 auto 12px;
            border: 4px solid rgba(15, 61, 46, .15);
            border-top-color: var(--forest);
            border-radius: 50%;
            animation: spin .8s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .spinner {
                animation-duration: 3s;
            }
        }

        #controls {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: center;
            gap: 10px 18px;
            padding: 16px;
            border-top: 1px solid var(--line);
        }

        .pager {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        #pageNum {
            min-width: 130px;
            text-align: center;
            font-weight: 600;
            color: var(--forest);
        }

        .btn {
            border: 0;
            cursor: pointer;
            font: inherit;
            font-weight: 600;
            padding: 10px 16px;
            border-radius: 10px;
            background: var(--forest);
            color: #fff;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: background .2s;
        }

        .btn:hover:not(:disabled) {
            background: var(--leaf);
        }

        .btn:disabled {
            opacity: .4;
            cursor: not-allowed;
        }

        .btn.ghost {
            background: transparent;
            color: var(--forest);
            border: 1px solid var(--line);
        }

        .btn.ghost:hover:not(:disabled) {
            background: var(--paper);
        }

        .goto {
            display: flex;
            gap: 8px;
        }

        .goto input {
            width: 76px;
            padding: 10px;
            text-align: center;
            border: 1px solid var(--line);
            border-radius: 10px;
            font: inherit;
        }

        /* ---------- Mobile ---------- */
        @media (max-width: 900px) {
            body {
                display: block;
            }

            .topbar {
                display: flex;
                align-items: center;
                justify-content: space-between;
                background: var(--forest);
                color: #fff;
                padding: 12px 16px;
                position: sticky;
                top: 0;
                z-index: 20;
            }

            .topbar strong {
                font-family: 'Bricolage Grotesque', sans-serif;
            }

            .topbar button {
                background: rgba(255, 255, 255, .12);
                color: #fff;
                border: 0;
                padding: 8px 12px;
                border-radius: 8px;
                font: inherit;
                cursor: pointer;
            }

            .sidebar {
                position: fixed;
                inset: 0 auto 0 0;
                z-index: 30;
                width: min(340px, 88vw);
                height: 100%;
                transform: translateX(-100%);
                transition: transform .25s ease;
            }

            .sidebar.open {
                transform: none;
            }

            .overlay {
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, .45);
                z-index: 25;
                display: none;
            }

            .overlay.show {
                display: block;
            }

            .content {
                padding-top: 24px;
            }
        }
    </style>
</head>

<body>

    <!-- Barre mobile -->
    <div class="topbar">
        <strong>ONFP Sénégal</strong>
        <button id="openMenu" aria-label="Ouvrir la liste des publications"><i class="fas fa-bars"></i>
            Publications</button>
    </div>
    <div class="overlay" id="overlay"></div>

    <!-- Bibliothèque -->
    <aside class="sidebar" id="sidebar">
        <div class="brand">
            <div class="brand-mark"><i class="fas fa-book-open"></i></div>
            <div>
                <h2>Nos publications</h2>
                <small>Bibliothèque ONFP</small>
            </div>
        </div>

        <label class="search">
            <i class="fas fa-search"></i>
            <input type="text" id="searchInput" placeholder="Rechercher un manuel" aria-label="Rechercher un manuel">
        </label>

        <ul class="book-list">
            @foreach ($manuels as $man)
                <li class="book-item {{ $man->filename === $manuel->filename ? 'active' : '' }}">
                    <a href="{{ route('manuel.view', $man->filename) }}">
                        <i class="fas fa-book"></i> <span>{{ $man->title }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
        <p class="no-result" id="noResult">Aucun manuel ne correspond à votre recherche.</p>
    </aside>

    <!-- Lecteur -->
    <main class="content">
        <header class="head">
            <h1>{{ $manuel->title }}</h1>
            <div class="author"><i class="fas fa-user-edit"></i> {{ $manuel->author }}</div>
            <p class="desc">
                {!! implode('', array_map(fn($line) => nl2br(e($line)), explode("\n", ucfirst($manuel->description)))) !!}
            </p>
        </header>

        <section class="reader" aria-label="Lecteur du manuel">
            <div class="progress"><span id="progressBar"></span></div>

            <div class="stage" id="stage">
                <canvas id="pdf-render"></canvas>
                <div class="status" id="status">
                    <div>
                        <div class="spinner"></div>Chargement du manuel…
                    </div>
                </div>
            </div>

            <div id="controls">
                <div class="pager">
                    <button class="btn" id="prevPage"><i class="fas fa-chevron-left"></i> Précédente</button>
                    <span id="pageNum">Page 1 sur 1</span>
                    <button class="btn" id="nextPage">Suivante <i class="fas fa-chevron-right"></i></button>
                </div>
                <div class="goto">
                    <input type="number" id="pageInput" min="1" placeholder="N°" aria-label="Numéro de page">
                    <button class="btn ghost" id="goToPage">Aller à la page</button>
                </div>
            </div>
        </section>
    </main>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>

    <script>
        const url = '{{ asset('storage/manuels/' . $filename) }}';
        const pdfjsLib = window['pdfjs-dist/build/pdf'];
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.worker.min.js';

        const canvas = document.getElementById('pdf-render');
        const stage = document.getElementById('stage');
        const statusBox = document.getElementById('status');
        const pageLabel = document.getElementById('pageNum');
        const prevBtn = document.getElementById('prevPage');
        const nextBtn = document.getElementById('nextPage');
        const progressBar = document.getElementById('progressBar');

        let pdfDoc = null,
            pageNum = 1,
            pageCount = 0;
        let rendering = false,
            pending = null;

        function drawWatermark(ctx, w, h) {
            ctx.save();
            ctx.font = `${Math.round(w / 11)}px Arial`;
            ctx.fillStyle = 'rgba(150, 150, 150, 0.3)';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            ctx.translate(w / 2, h / 2);
            ctx.rotate(-Math.PI / 4);
            ctx.fillText('ONFP-SENEGAL', 0, 0);
            ctx.restore();
        }

        function updateUI() {
            pageLabel.textContent = `Page ${pageNum} sur ${pageCount}`;
            prevBtn.disabled = pageNum <= 1;
            nextBtn.disabled = pageNum >= pageCount;
            progressBar.style.width = pageCount ? (pageNum / pageCount * 100) + '%' : '0';
        }

        function renderPage(num) {
            if (rendering) {
                pending = num;
                return;
            }
            rendering = true;

            pdfDoc.getPage(num).then(page => {
                const base = page.getViewport({
                    scale: 1
                });
                const available = Math.min(stage.clientWidth - 32, 820);
                const cssScale = available / base.width;
                const dpr = window.devicePixelRatio || 1;
                const viewport = page.getViewport({
                    scale: cssScale * dpr
                });

                canvas.width = viewport.width;
                canvas.height = viewport.height;
                canvas.style.width = (viewport.width / dpr) + 'px';
                canvas.style.height = (viewport.height / dpr) + 'px';

                const ctx = canvas.getContext('2d');
                return page.render({
                    canvasContext: ctx,
                    viewport
                }).promise.then(() => {
                    drawWatermark(ctx, canvas.width, canvas.height);
                    statusBox.hidden = true;
                });
            }).catch(() => {
                statusBox.hidden = false;
                statusBox.innerHTML =
                    '<div><i class="fas fa-exclamation-triangle" style="font-size:1.6rem"></i><p>Impossible d\'afficher cette page. Rechargez la page ou réessayez plus tard.</p></div>';
            }).finally(() => {
                rendering = false;
                if (pending !== null) {
                    const n = pending;
                    pending = null;
                    renderPage(n);
                }
            });
        }

        function goTo(n) {
            if (n < 1 || n > pageCount || n === pageNum) return;
            pageNum = n;
            updateUI();
            renderPage(pageNum);
            stage.scrollIntoView({
                behavior: 'smooth',
                block: 'nearest'
            });
        }

        pdfjsLib.getDocument(url).promise.then(pdf => {
            pdfDoc = pdf;
            pageCount = pdf.numPages;
            document.getElementById('pageInput').max = pageCount;
            updateUI();
            renderPage(pageNum);
        }).catch(() => {
            statusBox.innerHTML =
                '<div><i class="fas fa-exclamation-triangle" style="font-size:1.6rem"></i><p>Le manuel n\'a pas pu être chargé.</p></div>';
        });

        prevBtn.addEventListener('click', () => goTo(pageNum - 1));
        nextBtn.addEventListener('click', () => goTo(pageNum + 1));

        document.getElementById('goToPage').addEventListener('click', () => {
            const target = parseInt(document.getElementById('pageInput').value, 10);
            if (!isNaN(target) && target >= 1 && target <= pageCount) {
                goTo(target);
            } else {
                alert(`Veuillez entrer un numéro de page valide entre 1 et ${pageCount}`);
            }
        });

        document.getElementById('pageInput').addEventListener('keydown', e => {
            if (e.key === 'Enter') document.getElementById('goToPage').click();
        });

        document.addEventListener('keydown', e => {
            if (e.target.tagName === 'INPUT') return;
            if (e.key === 'ArrowLeft') goTo(pageNum - 1);
            if (e.key === 'ArrowRight') goTo(pageNum + 1);
        });

        let resizeTimer;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(() => pdfDoc && renderPage(pageNum), 200);
        });

        // Recherche dans la bibliothèque
        document.getElementById('searchInput').addEventListener('input', function() {
            const filter = this.value.toLowerCase();
            let visible = 0;
            document.querySelectorAll('.book-list .book-item').forEach(item => {
                const match = item.textContent.toLowerCase().includes(filter);
                item.style.display = match ? '' : 'none';
                if (match) visible++;
            });
            document.getElementById('noResult').style.display = visible ? 'none' : 'block';
        });

        // Menu mobile
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        const toggleMenu = open => {
            sidebar.classList.toggle('open', open);
            overlay.classList.toggle('show', open);
        };
        document.getElementById('openMenu').addEventListener('click', () => toggleMenu(true));
        overlay.addEventListener('click', () => toggleMenu(false));
    </script>

</body>

</html>
