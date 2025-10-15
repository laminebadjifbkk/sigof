<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('assets/img/favicon-onfp.png') }}" rel="icon">
    <title>Erreur @yield('code') - @yield('title')</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            text-align: center;
            padding: 40px 20px;
            margin: 0;
        }

        .container {
            max-width: 600px;
            margin: auto;
            padding: 30px;
            background-color: #ffffff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            border-radius: 10px;
        }

        h1 {
            color: #dc3545;
            font-size: 56px;
            margin-bottom: 10px;
        }

        h2 {
            color: #343a40;
            font-size: 24px;
            margin-bottom: 20px;
        }

        p {
            color: #6c757d;
            font-size: 16px;
            margin: 10px 0;
        }

        a.btn-home {
            display: inline-block;
            margin-top: 25px;
            padding: 12px 24px;
            background-color: #007bff;
            color: #ffffff;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        a.btn-home:hover {
            background-color: #0056b3;
        }

        .phone a {
            color: #007bff;
            text-decoration: none;
        }

        .phone a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <div class="container">
        <h1>@yield('code')</h1>
        <h2>@yield('title')</h2>
        <p>@yield('message')</p>
        <p class="phone">
            Besoin d’aide ? Appelez-nous au
            <strong><a href="tel:+221772913397">+221 77 291 33 97</a></strong>
        </p>
        <a href="{{ url('/') }}" class="btn-home">Retour à l'accueil</a>
    </div>

</body>

</html>
