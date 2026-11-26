@extends('support.stocks.layouts.pdf')

@section('titre', $titre)

@section('content')
    <div class="pdf-titre">{{ $titre }}</div>

    <div style="text-align: center; font-size: 11px; margin-bottom: 12px;">
        {{ $inventaire->reference }} — {{ $inventaire->libelle }}
    </div>

    <div class="pdf-infos">
        <strong>Statut :</strong>
        {{ str_replace('_', ' ', $inventaire->statut) }}<br>

        <strong>Date de début :</strong>
        {{ $inventaire->date_debut?->format('d/m/Y') ?? '—' }}<br>

        <strong>Créateur :</strong>
        {{ $inventaire->createur?->firstname }}
        {{ $inventaire->createur?->name }}<br>

        <strong>Validateur :</strong>
        {{ $inventaire->validateur?->firstname }}
        {{ $inventaire->validateur?->name ?? '—' }}<br>

        <strong>Nombre d'articles :</strong>
        {{ $statistiques['nombreArticles'] }}<br>

        <strong>Articles comptés :</strong>
        {{ $statistiques['nombreComptes'] }}
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
                    <td colspan="{{ count($colonnes) }}">
                        Aucune donnée disponible.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if(str_contains($titre, 'COMPTAGE'))
        <table class="pdf-signatures">
            <tr>
                <td>
                    Nom et signature du compteur
                    <br><br><br>
                    ________________________
                </td>
                <td>
                    Nom et signature du responsable
                    <br><br><br>
                    ________________________
                </td>
            </tr>
        </table>
    @endif
@endsection
