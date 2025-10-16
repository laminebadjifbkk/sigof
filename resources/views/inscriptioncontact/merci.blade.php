<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Merci pour votre participation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #e9f4ff, #f8f9fa);
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
            background: #0b5ed7;
            color: #fff;
        }
    </style>
</head>

<body>
    <div class="thank-you-card">
        <h1>Merci !</h1>
        <p>Votre participation à <strong>ONFP - Partnership Engagement Day</strong> a été confirmée avec succès.</p>
        <p>Veuillez vérifier votre email pour télécharger les termes de référence (TDR).</p>
        <a href="{{ route('inscriptioncontact') }}" class="btn btn-home btn-sm">Retour au formulaire</a>
    </div>
</body>

</html>
