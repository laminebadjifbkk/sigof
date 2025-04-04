<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erreur @yield('code') - @yield('title')</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            text-align: center;
            padding: 50px;
        }

        .container {
            max-width: 600px;
            margin: auto;
            padding: 20px;
            background: white;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h1 {
            color: #dc3545;
            font-size: 48px;
        }

        h2 {
            color: #343a40;
        }

        p {
            color: #6c757d;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        a:hover {
            background: #0056b3;
        }
    </style>
</head>

<body>

    <div class="container">
        <h1>@yield('code')</h1>
        <h2>@yield('title')</h2>
        <p>@yield('message')</p>
        <p>Besoin d’aide ? Appelez-nous au <em><strong> <a href="tel:++221772913397">+221 77 291 33
                        97</a></strong></em></p>
        <a href="{{ url('/') }}">Retour à l'accueil</a>
    </div>

</body>

</html>
