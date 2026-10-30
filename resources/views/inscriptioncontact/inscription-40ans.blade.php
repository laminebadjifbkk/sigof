<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ONFP - Célébration des 40 ans</title>
    <link rel="icon" href="{{ asset('images/onfp-logo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --orange: #F28500;
            --orange-dark: #d97500;
            --green: #1b8a3a;
            --gold: #f5b800;
            --red: #d62828;
            --ink: #1f2933;
            --muted: #6b7280;
            --line: #e8e2da;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            color: var(--ink);
            min-height: 100vh;
            background:
                radial-gradient(900px 500px at 8% -5%, rgba(242, 133, 0, .18), transparent 60%),
                radial-gradient(800px 500px at 100% 0%, rgba(27, 138, 58, .14), transparent 60%),
                radial-gradient(700px 500px at 50% 110%, rgba(245, 184, 0, .18), transparent 60%),
                #fbf6ef;
            background-attachment: fixed;
        }

        /* ---------- Carte ---------- */
        .page {
            padding: 40px 16px 30px;
        }

        .form-card {
            max-width: 720px;
            margin: 0 auto;
            background: #fff;
            border-radius: 24px;
            box-shadow: 0 25px 60px -15px rgba(31, 41, 51, .25), 0 4px 14px rgba(31, 41, 51, .06);
            overflow: visible;
            position: relative;
            animation: rise .6s ease both;
        }

        .form-card::before {
            content: "";
            display: block;
            height: 8px;
            border-radius: 24px 24px 0 0;
            background: linear-gradient(90deg, var(--green) 0 33%, var(--gold) 33% 66%, var(--red) 66% 100%);
        }

        @keyframes rise {
            from {
                opacity: 0;
                transform: translateY(18px);
            }

            to {
                opacity: 1;
                transform: none;
            }
        }

        /* ---------- En-tête ---------- */
        .card-head {
            text-align: center;
            padding: 28px 40px 8px;
            background: linear-gradient(180deg, #fffaf3 0%, #fff 100%);
        }

        .logos-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 10px;
        }

        .logo-onfp {
            height: 96px;
            width: auto;
            max-width: 42%;
            object-fit: contain;
        }

        .logo-40 {
            height: 120px;
            width: auto;
            max-width: 55%;
            object-fit: contain;
            filter: drop-shadow(0 8px 14px rgba(0, 0, 0, .12));
        }

        .card-head h1 {
            font-size: 1.05rem;
            font-weight: 600;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: var(--ink);
            margin: 4px 0 2px;
        }

        .card-head h2 {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0 0 14px;
            background: linear-gradient(90deg, var(--orange), var(--red));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .event-chips {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
            margin-bottom: 8px;
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

        /* ---------- Corps ---------- */
        .card-body-x {
            padding: 20px 40px 34px;
        }

        .section-title {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: .78rem;
            font-weight: 600;
            letter-spacing: .1em;
            text-transform: uppercase;
            color: var(--muted);
            margin: 6px 0 18px;
        }

        .section-title::before,
        .section-title::after {
            content: "";
            flex: 1;
            height: 1px;
            background: var(--line);
        }

        .field {
            margin-bottom: 18px;
            position: relative;
        }

        .field label {
            font-size: .82rem;
            font-weight: 500;
            color: #4b5563;
            margin-bottom: 6px;
            display: block;
        }

        .field label .opt {
            color: #9ca3af;
            font-weight: 400;
        }

        .input-wrap {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            pointer-events: none;
            transition: color .2s;
        }

        .input-wrap:focus-within .input-icon {
            color: var(--orange);
        }

        .form-control {
            height: 50px;
            padding-left: 46px;
            border-radius: 14px;
            border: 1.5px solid var(--line);
            background: #fffdfa;
            font-size: .93rem;
            transition: border-color .2s, box-shadow .2s, background .2s;
        }

        textarea.form-control {
            height: auto;
            padding: 14px 16px;
            resize: vertical;
        }

        .form-control::placeholder {
            color: #b4b9c0;
        }

        .form-control:focus {
            background: #fff;
            border-color: var(--orange);
            box-shadow: 0 0 0 4px rgba(242, 133, 0, .15);
        }

        /* ---------- Autocomplétion structure ---------- */
        .field-structure {
            z-index: 30;
        }

        .field-structure .form-control {
            padding-right: 44px;
        }

        .ac-clear {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            border: 0;
            background: #f3efe9;
            color: #6b7280;
            width: 26px;
            height: 26px;
            border-radius: 50%;
            display: none;
            align-items: center;
            justify-content: center;
            font-size: .8rem;
            cursor: pointer;
        }

        .ac-clear.show {
            display: flex;
        }

        .ac-clear:hover {
            background: #ffe3b8;
            color: #a8520f;
        }

        .ac-list {
            position: absolute;
            top: calc(100% + 6px);
            left: 0;
            right: 0;
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 16px;
            box-shadow: 0 18px 40px -10px rgba(31, 41, 51, .28);
            max-height: 320px;
            overflow-y: auto;
            display: none;
            z-index: 50;
        }

        .ac-list.open {
            display: block;
            animation: pop .15s ease both;
        }

        @keyframes pop {
            from {
                opacity: 0;
                transform: translateY(-4px);
            }

            to {
                opacity: 1;
                transform: none;
            }
        }

        .ac-group {
            position: sticky;
            top: 0;
            padding: 8px 16px 6px;
            font-size: .66rem;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: #b45f10;
            background: #fff7ec;
            border-bottom: 1px solid #fde8c8;
        }

        .ac-item {
            padding: 10px 16px;
            font-size: .87rem;
            line-height: 1.35;
            cursor: pointer;
            border-left: 3px solid transparent;
        }

        .ac-item.active,
        .ac-item:hover {
            background: #fff3e3;
            border-left-color: var(--orange);
        }

        .ac-item mark {
            background: #ffdc9e;
            color: inherit;
            padding: 0 1px;
            border-radius: 3px;
        }

        .ac-empty {
            padding: 14px 16px;
            font-size: .85rem;
            color: var(--muted);
        }

        .ac-empty i {
            color: var(--orange);
        }

        .hint {
            font-size: .75rem;
            color: #9ca3af;
            margin-top: 6px;
        }

        /* ---------- Bouton ---------- */
        .btn-submit {
            width: 100%;
            border: 0;
            padding: 15px;
            border-radius: 14px;
            font-weight: 600;
            font-size: 1rem;
            color: #fff;
            background: linear-gradient(135deg, var(--orange), #e0561a);
            box-shadow: 0 10px 22px -8px rgba(242, 133, 0, .7);
            transition: transform .2s, box-shadow .2s, filter .2s;
            margin-top: 8px;
        }

        .btn-submit:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 14px 26px -8px rgba(242, 133, 0, .8);
            filter: brightness(1.04);
            color: #fff;
        }

        .btn-submit:disabled {
            opacity: .75;
            cursor: wait;
        }

        .btn-check-reg {
            border: 1.5px solid var(--line);
            background: #fff;
            color: #4b5563;
            border-radius: 999px;
            padding: 8px 20px;
            font-size: .85rem;
            font-weight: 500;
            transition: .2s;
        }

        .btn-check-reg:hover {
            border-color: var(--green);
            color: var(--green);
            background: #f2faf4;
        }

        .footer-text {
            text-align: center;
            font-size: .8rem;
            color: var(--muted);
            margin: 26px 0 0;
        }

        .alert {
            border-radius: 14px;
            font-size: .9rem;
        }

        /* ---------- Modal ---------- */
        .modal-content {
            border: 0;
            border-radius: 22px;
            overflow: hidden;
        }

        .modal-header {
            background: linear-gradient(135deg, #fff4e5, #fff);
            border-bottom: 1px solid var(--line);
        }

        .modal .form-control {
            padding-left: 46px;
        }

        @media (max-width: 575px) {

            .card-head,
            .card-body-x {
                padding-left: 22px;
                padding-right: 22px;
            }

            .card-head h2 {
                font-size: 1.25rem;
            }

            .logo-onfp {
                height: 64px;
            }

            .logo-40 {
                height: 84px;
            }
        }
    </style>
</head>

<body>
    <div class="page">
        <div class="form-card">

            {{-- ============ En-tête avec logos ============ --}}
            <div class="card-head">
                <div class="logos-row">
                    <img src="{{ asset('assets/img/ONFP_photo-profil_1080x1080.png') }}" alt="ONFP"
                        class="logo-onfp" onerror="this.style.display='none'">
                    <img src="{{ asset('assets/img/logo-40-ans.png') }}" alt="40 ans de l'ONFP - 1986-2026"
                        class="logo-40" onerror="this.style.display='none'">
                </div>

                <h1>Célébration des 40 ans</h1>
                <h2>Confirmez votre participation</h2>

                <div class="event-chips">
                    <span class="chip"><i class="bi bi-calendar-event"></i> Jeudi 19 novembre 2026</span>
                    {{-- TODO : précisez l'heure et le lieu exacts --}}
                    <span class="chip"><i class="bi bi-geo-alt-fill"></i> Azalaï Hôtel, Dakar</span>
                </div>
            </div>

            <div class="card-body-x">

                @if (session('success'))
                    <div class="alert alert-success text-center">
                        <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $message)
                                <li>{{ $message }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('inscriptioncontact.store') }}" method="POST" id="registerForm">
                    @csrf

                    <div class="section-title">Votre structure</div>

                    {{-- ============ Structure : liste déroulante avec recherche ============ --}}
                    <div class="field field-structure">
                        <label for="structure">Structure que vous représentez</label>
                        <div class="input-wrap">
                            <i class="bi bi-building input-icon"></i>
                            <input type="text" id="structure" name="structure" class="form-control"
                                placeholder="Commencez à taper pour rechercher…" value="{{ old('structure') }}"
                                autocomplete="off" role="combobox" aria-autocomplete="list" aria-expanded="false"
                                aria-controls="structureList" required>
                            <button type="button" class="ac-clear" id="structureClear" aria-label="Effacer">
                                <i class="bi bi-x-lg"></i>
                            </button>
                            <div class="ac-list" id="structureList" role="listbox"></div>
                        </div>
                        <div class="hint">Choisissez dans la liste, ou saisissez le nom de votre structure si elle n'y
                            figure pas.</div>
                    </div>

                    <div class="section-title">Vos coordonnées</div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="field">
                                <label for="nom">Prénom et Nom</label>
                                <div class="input-wrap">
                                    <i class="bi bi-person input-icon"></i>
                                    <input type="text" id="nom" name="nom" class="form-control"
                                        placeholder="Prénom et Nom du représentant" value="{{ old('nom') }}"
                                        required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="field">
                                <label for="fonction">Fonction</label>
                                <div class="input-wrap">
                                    <i class="bi bi-briefcase input-icon"></i>
                                    <input type="text" id="fonction" name="fonction" class="form-control"
                                        placeholder="Votre fonction" value="{{ old('fonction') }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="field">
                                <label for="telephone">Téléphone</label>
                                <div class="input-wrap">
                                    <i class="bi bi-telephone input-icon"></i>
                                    <input type="tel" id="telephone" name="telephone" class="form-control"
                                        placeholder="Ex : 77 000 00 00" value="{{ old('telephone') }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="field">
                                <label for="email">Adresse mail</label>
                                <div class="input-wrap">
                                    <i class="bi bi-envelope input-icon"></i>
                                    <input type="email" id="email" name="email" class="form-control"
                                        placeholder="nom@exemple.com" value="{{ old('email') }}" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="field">
                        <label for="commentaire">Commentaire <span class="opt">(facultatif)</span></label>
                        <textarea id="commentaire" name="commentaire" class="form-control" rows="3"
                            placeholder="Un message pour l'équipe d'organisation…">{{ old('commentaire') }}</textarea>
                    </div>

                    <input type="hidden" name="recaptcha_token" id="recaptcha_token">
                    <input type="hidden" name="autre" value="40 ANS ONFP">

                    <button type="submit" class="btn-submit" id="submitBtn">
                        <i class="bi bi-send-check me-2"></i> Envoyer ma confirmation
                    </button>
                </form>

                <div class="text-center mt-4">
                    <button type="button" class="btn-check-reg" data-bs-toggle="modal"
                        data-bs-target="#checkModal">
                        <i class="bi bi-search me-1"></i> Déjà inscrit ?
                    </button>
                </div>

                <p class="footer-text">© {{ date('Y') }} ONFP · Office National de Formation Professionnelle</p>
            </div>
        </div>
    </div>

    <!-- Modal Vérification -->
    <div class="modal fade" id="checkModal" tabindex="-1" aria-labelledby="checkModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow">
                <div class="modal-header">
                    <h5 class="modal-title mb-0 fw-semibold" id="checkModalLabel">Vérifier votre inscription</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="{{ route('inscriptioncontact.check') }}" method="POST">
                        @csrf

                        <div class="alert alert-info small mb-3">
                            <i class="bi bi-info-circle"></i>
                            Entrez l'adresse mail et le téléphone utilisés lors de votre inscription.
                        </div>

                        <div class="field">
                            <div class="input-wrap">
                                <i class="bi bi-envelope input-icon"></i>
                                <input type="email" name="email" class="form-control" placeholder="Adresse mail"
                                    required>
                            </div>
                        </div>

                        <div class="field">
                            <div class="input-wrap">
                                <i class="bi bi-telephone input-icon"></i>
                                <input type="tel" name="telephone" class="form-control" placeholder="Téléphone"
                                    required>
                            </div>
                        </div>

                        <button type="submit" class="btn-submit mt-0">
                            <i class="bi bi-search me-2"></i> Vérifier mon inscription
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Liste des structures (issue de la fonction create() du contrôleur)
        const STRUCTURES = @json($structures);

        (function() {
            const input = document.getElementById('structure');
            const list = document.getElementById('structureList');
            const clearBtn = document.getElementById('structureClear');
            const MAX_RESULTS = 50;

            // Insensible à la casse et aux accents
            const norm = s => s.normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLowerCase();

            const items = [];
            Object.entries(STRUCTURES).forEach(([group, names]) => {
                names.forEach(name => items.push({
                    group,
                    name,
                    key: norm(name)
                }));
            });

            let visible = [];
            let active = -1;

            function highlightInto(el, text, terms) {
                const k = norm(text);
                if (k.length !== text.length) {
                    el.textContent = text;
                    return;
                }
                const mask = new Array(text.length).fill(false);
                terms.forEach(t => {
                    let p = k.indexOf(t);
                    while (p !== -1) {
                        for (let j = p; j < p + t.length; j++) mask[j] = true;
                        p = k.indexOf(t, p + t.length);
                    }
                });
                let i = 0;
                while (i < text.length) {
                    let j = i;
                    while (j < text.length && mask[j] === mask[i]) j++;
                    const seg = text.slice(i, j);
                    if (mask[i]) {
                        const m = document.createElement('mark');
                        m.textContent = seg;
                        el.appendChild(m);
                    } else {
                        el.appendChild(document.createTextNode(seg));
                    }
                    i = j;
                }
            }

            function open() {
                list.classList.add('open');
                input.setAttribute('aria-expanded', 'true');
            }

            function close() {
                list.classList.remove('open');
                input.setAttribute('aria-expanded', 'false');
                active = -1;
            }

            function setActive(idx) {
                const els = list.querySelectorAll('.ac-item');
                els.forEach(e => e.classList.remove('active'));
                active = idx;
                if (idx >= 0 && els[idx]) {
                    els[idx].classList.add('active');
                    els[idx].scrollIntoView({
                        block: 'nearest'
                    });
                }
            }

            function select(idx) {
                if (!visible[idx]) return;
                input.value = visible[idx].name;
                clearBtn.classList.add('show');
                close();
            }

            function render() {
                const raw = input.value.trim();
                clearBtn.classList.toggle('show', input.value.length > 0);
                if (!raw) {
                    close();
                    return;
                }

                const terms = norm(raw).split(/\s+/).filter(Boolean);
                visible = items.filter(i => terms.every(t => i.key.includes(t))).slice(0, MAX_RESULTS);
                list.innerHTML = '';
                active = -1;

                if (!visible.length) {
                    const empty = document.createElement('div');
                    empty.className = 'ac-empty';
                    empty.innerHTML =
                        '<i class="bi bi-info-circle me-1"></i> Aucune correspondance. Vous pouvez saisir le nom de votre structure librement.';
                    list.appendChild(empty);
                    open();
                    return;
                }

                let lastGroup = null;
                visible.forEach((it, idx) => {
                    if (it.group !== lastGroup) {
                        const g = document.createElement('div');
                        g.className = 'ac-group';
                        g.textContent = it.group;
                        list.appendChild(g);
                        lastGroup = it.group;
                    }
                    const row = document.createElement('div');
                    row.className = 'ac-item';
                    row.setAttribute('role', 'option');
                    row.dataset.index = idx;
                    highlightInto(row, it.name, terms);
                    list.appendChild(row);
                });
                open();
            }

            input.addEventListener('input', render);
            input.addEventListener('focus', () => {
                if (input.value.trim()) render();
            });

            input.addEventListener('keydown', e => {
                const isOpen = list.classList.contains('open');
                if (e.key === 'ArrowDown' && visible.length) {
                    e.preventDefault();
                    if (!isOpen) render();
                    setActive((active + 1) % visible.length);
                } else if (e.key === 'ArrowUp' && visible.length) {
                    e.preventDefault();
                    setActive(active <= 0 ? visible.length - 1 : active - 1);
                } else if (e.key === 'Enter' && isOpen && active >= 0) {
                    e.preventDefault();
                    select(active);
                } else if (e.key === 'Escape') {
                    close();
                }
            });

            // mousedown (et non click) pour éviter la perte de focus avant la sélection
            list.addEventListener('mousedown', e => {
                e.preventDefault();
                const row = e.target.closest('.ac-item');
                if (row) select(parseInt(row.dataset.index, 10));
            });

            clearBtn.addEventListener('click', () => {
                input.value = '';
                clearBtn.classList.remove('show');
                close();
                input.focus();
            });

            document.addEventListener('click', e => {
                if (!e.target.closest('.field-structure')) close();
            });

            clearBtn.classList.toggle('show', input.value.length > 0);
        })();

        // reCAPTCHA : jeton généré au moment de l'envoi (il expire au bout de 2 minutes)
        (function() {
            const form = document.getElementById('registerForm');
            const btn = document.getElementById('submitBtn');
            const tokenInput = document.getElementById('recaptcha_token');
            const siteKey = '{{ config('services.recaptcha.site_key') }}';

            form.addEventListener('submit', function(e) {
                if (typeof grecaptcha === 'undefined') return;
                e.preventDefault();
                btn.disabled = true;
                btn.innerHTML =
                    '<span class="spinner-border spinner-border-sm me-2"></span> Envoi en cours…';

                grecaptcha.ready(function() {
                    grecaptcha.execute(siteKey, {
                            action: 'submit'
                        })
                        .then(function(token) {
                            tokenInput.value = token;
                            form.submit();
                        })
                        .catch(function() {
                            form.submit();
                        });
                });
            });
        })();
    </script>

    @include('sweetalert::alert')
</body>

</html>
