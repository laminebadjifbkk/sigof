<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>{{ $titre }}</title>
    <style>
        @page {
            margin: 25px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #222;
        }

        h2 {
            text-align: center;
            color: #F28500;
            font-size: 17px;
            margin-bottom: 8px;
        }

        h4 {
            margin-top: 0;
            text-align: center;
            font-size: 12px;
            font-weight: normal;
        }

        .infos {
            margin: 18px 0;
            padding: 10px;
            border: 1px solid #ddd;
            line-height: 1.7;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }

        th {
            background: #F28500;
            color: white;
            font-weight: bold;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 6px;
            text-align: left;
        }

        tr:nth-child(even) {
            background: #f7f7f7;
        }

        .signature {
            margin-top: 35px;
            width: 100%;
        }

        .signature td {
            border: none;
            width: 50%;
            text-align: center;
            padding-top: 20px;
        }

        .footer {
            margin-top: 20px;
            text-align: right;
            font-size: 8px;
            color: #777;
        }
    </style>
</head>
<body>
    <h2>{{ $titre }}</h2>
    <h4>{{ $inventaire->reference }} — {{ $inventaire->libelle }}</h4>

    <div class="infos">
        <strong>Statut :</strong> {{ str_replace('_', ' ', $inventaire->statut) }}<br>
        <strong>Date de début :</strong> {{ $inventaire->date_debut?->format('d/m/Y') ?? '—' }}<br>
        <strong>Créateur :</strong>
        {{ $inventaire->createur?->firstname }} {{ $inventaire->createur?->name }}<br>
        <strong>Validateur :</strong>
        {{ $inventaire->validateur?->firstname }} {{ $inventaire->validateur?->name ?? '—' }}<br>
        <strong>Nombre d'articles :</strong> {{ $statistiques['nombreArticles'] }}<br>
        <strong>Articles comptés :</strong> {{ $statistiques['nombreComptes'] }}
    </div>

    <table>
        <thead>
            <tr>
                @foreach($colonnes as $colonne)
                    <th>{{ $colonne }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse($lignes as $ligne)
                <tr>
                    @foreach($ligne as $valeur)
                        <td>{{ $valeur }}</td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($colonnes) }}">Aucune donnée disponible.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if(str_contains($titre, 'COMPTAGE'))
        <table class="signature">
            <tr>
                <td>Nom et signature du compteur<br><br><br>________________________</td>
                <td>Nom et signature du responsable<br><br><br>________________________</td>
            </tr>
        </table>
    @endif

    <div class="footer">
        ONFP — SIGOF — Document généré le {{ now()->format('d/m/Y à H:i') }}
    </div>
</body>
</html>
