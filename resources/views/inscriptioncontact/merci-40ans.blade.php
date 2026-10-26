<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Merci pour votre inscription | 40 ans de l'ONFP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #fdf1e6, #f8f9fa);
            font-family: 'Poppins', sans-serif;
        }

        .thank-you-card {
            max-width: 500px;
            margin: 100px auto;
            background: #fff;
            padding: 40px;
            border-radius: 16px;
            text-align: center;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .thank-you-card:hover {
            transform: translateY(-5px);
        }

        .badge-40 {
            display: inline-block;
            background: #fdf1e6;
            color: #c05f1c;
            border: 1px solid rgba(224, 122, 47, .35);
            padding: 5px 16px;
            border-radius: 999px;
            font-size: .8rem;
            font-weight: 700;
            margin-bottom: 14px;
        }

        h1 {
            color: #F28500;
            margin-bottom: 20px;
        }

        p {
            font-size: 16px;
            color: #495057;
        }

        .btn-home {
            margin-top: 25px;
            padding: 12px 25px;
            border-radius: 8px;
            background: #F28500;
            color: #fff;
            font-weight: 500;
            border: none;
            transition: background 0.3s;
        }

        .btn-home:hover {
            background: #d97500;
            color: #fff;
        }
    </style>
</head>

<body>
    <div class="thank-you-card">
        <span class="badge-40">🎉 1986 – 2026</span>
        <h1>Merci !</h1>
        <p>Votre participation à la <strong>célébration des 40 ans de l'ONFP</strong>, le
            <strong>19 novembre 2026</strong>, a été confirmée avec succès.</p>

        @if (session('warning'))
            <div class="alert alert-warning small rounded-3">{{ session('warning') }}</div>
        @else
            <p>Un email de confirmation vous a été envoyé. Pensez à vérifier vos courriers indésirables.</p>
        @endif

        <a href="{{ route('celebration-40-ans-onfp') }}" class="btn btn-home btn-sm">Retour au formulaire</a>
    </div>
</body>

</html>
