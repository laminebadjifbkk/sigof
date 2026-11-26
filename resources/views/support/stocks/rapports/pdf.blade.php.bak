<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>{{ $titre }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 9px; }
        h2 { text-align: center; margin-bottom: 5px; }
        .date { text-align: right; color: #666; margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #bbb; padding: 5px; }
        th { background: #f28500; color: #fff; }
        tr:nth-child(even) { background: #f5f5f5; }
    </style>
</head>
<body>
    <h2>{{ $titre }}</h2>
    <div class="date">Édité le {{ now()->format('d/m/Y à H:i') }}</div>
    <table>
        <thead><tr>@foreach($colonnes as $colonne)<th>{{ $colonne }}</th>@endforeach</tr></thead>
        <tbody>
            @forelse($lignes as $ligne)
                <tr>@foreach($ligne as $valeur)<td>{{ $valeur }}</td>@endforeach</tr>
            @empty
                <tr><td colspan="{{ count($colonnes) }}">Aucune donnée disponible</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
