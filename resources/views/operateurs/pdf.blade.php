<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Liste des opérateurs {{ $statut }} en {{ $commissionagrement?->date?->format('Y') }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        h2 {
            text-align: center;
        }
    </style>
</head>

<body>
    <h2>Liste des opérateurs {{ $statut }} en {{ $commissionagrement?->date?->format('Y') }}</h2>

    <table>
        <thead>
            <tr>
                <th>Opérateur</th>
                {{-- <th>Sigle</th> --}}
                <th>Adresse</th>
                <th>Domaine</th>
                <th>Module</th>
                <th>Catégorie</th>
                <th>N° agrément</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($operateurs as $op)
                @foreach ($op->operateurmodules as $module)
                    <tr>
                        <td>{{ $op?->user?->operateur }}</td>
                        {{-- <td>{{ $op?->user?->username }}</td> --}}
                        <td>{{ $op?->user?->adresse }}</td>
                        <td>{{ $module?->domaine }}</td>
                        <td>{{ $module?->module }}</td>
                        <td>{{ $module?->categorie }}</td>
                        <td>{{ $op->numero_agrement }}</td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
</body>

</html>
