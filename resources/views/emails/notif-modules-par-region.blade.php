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
    <p>
        Bonjour chers collègues,<br>
        Merci de recevoir la liste des modules ayant atteint {{ $seuil }} demandes
        en attente de formation, réparties par région.
    </p>
    {{-- <h2>Modules ayant atteint {{ $seuil }} demandes</h2> --}}
    <h2>Statut : <strong>Nouvelle & Conforme</strong></h2>

    @foreach ($donnees as $region => $modules)
        <h3>Région : {{ $region }}</h3>
        <table>
            <thead>
                <tr>
                    <th>N°</th>
                    <th>Module</th>
                    <th>Nombre de demandes</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($modules as $index => $module)
                    <tr>
                        <td>{{ $index + 1 }}</td> {{-- Numérotation à partir de 1 --}}
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
