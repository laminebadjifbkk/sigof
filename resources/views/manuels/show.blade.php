<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('assets/img/favicon-onfp.png') }}" rel="icon">
    <title> {{ 'ONFP | Manuel de ' . $manuel->title }}</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
            display: flex;
        }

        /* Style de la sidebar */
        .sidebar {
            width: 400px;
            background-color: #2c3e50;
            color: white;
            padding: 20px;
            height: 150vh;
            overflow-y: auto;
        }

        .sidebar h2 {
            text-align: center;
            color: #ecf0f1;
        }

        .sidebar ul {
            list-style-type: none;
            padding: 0;
        }

        .sidebar ul li {
            margin: 15px 0;
            font-size: 1.1em;
        }

        .sidebar ul li a {
            color: #ecf0f1;
            text-decoration: none;
            display: block;
            padding: 5px 15px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .sidebar ul li a:hover {
            background-color: #34495e;
        }

        .content {
            flex-grow: 1;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
            font-size: 2.5em;
            text-align: center;
            margin-bottom: 10px;
        }

        .book-details {
            text-align: center;
            font-size: 1.2em;
            color: #555;
            margin-bottom: 30px;
        }

        .reader-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 30px;
        }

        canvas {
            border: 1px solid #ddd;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            max-width: 100%;
            max-height: 100%;
        }

        #controls {
            text-align: center;
            margin-top: 20px;
        }

        #controls button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 5px 15px;
            font-size: 1em;
            cursor: pointer;
            margin: 5px;
            border-radius: 5px;
        }

        #controls button:hover {
            background-color: #0056b3;
        }

        #controls input[type="number"] {
            padding: 5px;
            font-size: 1em;
            border: 1px solid #ccc;
            border-radius: 5px;
            text-align: center;
        }

        #pageNum {
            margin: 0 10px;
            font-size: 1.2em;
        }
    </style>
</head>

<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Nos publications</h2>
        <ul class="book-list">
            @foreach ($manuels as $man)
                <li class="book-item">
                    <a href="{{ route('manuel.view', $man->filename) }}">
                        <i class="fas fa-book"></i> {{ $man->title }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>

    <!-- Content Area -->
    <div class="content">
        <div class="container">
            <h2>{{ $manuel->title }}</h2>
            <div class="book-details">
                <p><strong>Auteur : </strong> {{ $manuel->author }}</p>
                <p><strong>Description :</strong>

                    {!! '' . implode('', array_map(fn($line) => nl2br(e($line)), explode("\n", ucfirst($manuel->description)))) !!}

                </p>
            </div>

            <div class="reader-container">
                <canvas id="pdf-render"></canvas>
            </div>

            <div id="controls">
                <button id="prevPage">Précédente</button>
                <span id="pageNum">Page 1 sur 1</span>
                <button id="nextPage">Suivante</button>
                <br><br>
                <input type="number" id="pageInput" min="1" style="width: 60px;" />
                <button id="goToPage">Aller à la page</button>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>

    <script>
        const url = '{{ asset('storage/manuels/' . $filename) }}';
        const pdfjsLib = window['pdfjs-dist/build/pdf'];

        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.worker.min.js';

        let pdfDoc = null,
            pageNum = 1,
            pageCount = 0;

        function drawWatermark(ctx, canvas) {
            const watermarkText = "ONFP-SENEGAL";
            const fontSize = 50;

            ctx.font = `${fontSize}px Arial`;
            ctx.fillStyle = "rgba(150, 150, 150, 0.3)";
            ctx.textAlign = "center";
            ctx.textBaseline = "middle";

            const x = canvas.width / 2;
            const y = canvas.height / 2;

            ctx.save();
            ctx.translate(x, y);
            ctx.rotate(-Math.PI / 4);
            ctx.fillText(watermarkText, 0, 0);
            ctx.restore();
        }

        function renderPage(num) {
            pdfDoc.getPage(num).then(function(page) {
                const canvas = document.getElementById('pdf-render');
                const ctx = canvas.getContext('2d');
                const A4_WIDTH = 595.28; // A4 width in pixels at 72 DPI
                const A4_HEIGHT = 841.89; // A4 height in pixels at 72 DPI

                const viewport = page.getViewport({
                    scale: 1
                });
                const scale = Math.min(A4_WIDTH / viewport.width, A4_HEIGHT / viewport.height);

                canvas.height = A4_HEIGHT;
                canvas.width = A4_WIDTH;

                const renderContext = {
                    canvasContext: ctx,
                    viewport: page.getViewport({
                        scale: scale
                    })
                };

                page.render(renderContext).promise.then(function() {
                    drawWatermark(ctx, canvas);
                });
            });
        }

        pdfjsLib.getDocument(url).promise.then(function(pdf) {
            pdfDoc = pdf;
            pageCount = pdf.numPages;

            renderPage(pageNum);

            document.getElementById('pageNum').textContent = `Page ${pageNum} sur ${pageCount}`;
        });

        document.getElementById('prevPage').addEventListener('click', function() {
            if (pageNum > 1) {
                pageNum--;
                document.getElementById('pageNum').textContent = `Page ${pageNum} sur ${pageCount}`;
                renderPage(pageNum);
            }
        });

        document.getElementById('nextPage').addEventListener('click', function() {
            if (pageNum < pageCount) {
                pageNum++;
                document.getElementById('pageNum').textContent = `Page ${pageNum} sur ${pageCount}`;
                renderPage(pageNum);
            }
        });

        document.getElementById('goToPage').addEventListener('click', function() {
            const input = document.getElementById('pageInput');
            const targetPage = parseInt(input.value, 10);

            if (!isNaN(targetPage) && targetPage >= 1 && targetPage <= pageCount) {
                pageNum = targetPage;
                document.getElementById('pageNum').textContent = `Page ${pageNum} sur ${pageCount}`;
                renderPage(pageNum);
            } else {
                alert(`Veuillez entrer un numéro de page valide entre 1 et ${pageCount}`);
            }
        });
    </script>

</body>

</html>
