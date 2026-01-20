<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 25px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }

        th {
            background: #f5f5f5;
        }
    </style>
</head>

<body>

    <h2>Modules ayant atteint {{ $seuil }} demandes</h2>
    <p>Statut : <strong>Nouvelle</strong></p>

    @foreach ($donnees as $region => $modules)
        <h3>Région : {{ $region }}</h3>
        <table>
            <thead>
                <tr>
                    <th>Module</th>
                    <th>Nombre de demandes</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($modules as $module)
                    <tr>
                        <td>{{ $module['module'] }}</td>
                        <td>{{ $module['total'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach

    <p><em>Ce message est généré automatiquement.</em></p>
</body>

</html>
