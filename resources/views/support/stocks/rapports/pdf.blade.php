@extends('support.stocks.layouts.pdf')

@section('titre', $titre)

@section('content')
    <div class="pdf-titre">{{ $titre }}</div>

    <div class="pdf-date">
        Édité le {{ now()->format('d/m/Y à H:i') }}
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
                        Aucune donnée disponible
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
