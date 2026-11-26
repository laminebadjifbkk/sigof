<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>@yield('titre', 'Document ONFP')</title>

    <style>
        @page {
            margin: 22px 25px 30px 25px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
            color: #222;
        }

        .pdf-entete {
            text-align: center;
            margin-bottom: 18px;
            padding-bottom: 9px;
            border-bottom: 2px solid #F28500;
        }

        .pdf-republique {
            font-size: 13px;
            font-weight: bold;
            letter-spacing: .5px;
        }

        .pdf-devise {
            font-size: 10px;
            font-style: italic;
            margin-top: 3px;
        }

        .pdf-separateur {
            width: 65px;
            margin: 6px auto;
            border-top: 1px solid #555;
        }

        .pdf-ministere {
            font-size: 9px;
            font-weight: bold;
            line-height: 1.4;
        }

        .pdf-logo {
            margin: 7px auto 3px;
        }

        .pdf-logo img {
            width: 65px;
            height: auto;
        }

        .pdf-organisme {
            font-size: 10px;
            font-weight: bold;
            letter-spacing: .3px;
        }

        .pdf-titre {
            text-align: center;
            font-size: 15px;
            font-weight: bold;
            color: #222;
            margin: 12px 0 6px;
            text-transform: uppercase;
        }

        .pdf-date {
            text-align: right;
            color: #666;
            font-size: 8px;
            margin-bottom: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th {
            background: #F28500;
            color: #fff;
            font-weight: bold;
        }

        th, td {
            border: 1px solid #bbb;
            padding: 5px;
            vertical-align: middle;
        }

        tr:nth-child(even) {
            background: #f5f5f5;
        }

        .pdf-infos {
            margin: 12px 0;
            padding: 8px;
            border: 1px solid #ddd;
            line-height: 1.7;
        }

        .pdf-signatures {
            margin-top: 30px;
            width: 100%;
        }

        .pdf-signatures td {
            width: 50%;
            text-align: center;
            border: none;
            padding-top: 15px;
        }

        .pdf-pied-page {
            margin-top: 20px;
            padding-top: 6px;
            border-top: 1px solid #ddd;
            text-align: right;
            font-size: 8px;
            color: #777;
        }

        .pdf-texte-centre {
            text-align: center;
        }

        .pdf-texte-droite {
            text-align: right;
        }

        .pdf-sans-coupure {
            page-break-inside: avoid;
        }
    </style>
</head>

<body>
    @include('support.stocks.components.pdf.entete')

    @yield('content')

    @include('support.stocks.components.pdf.pied-page')
</body>
</html>
