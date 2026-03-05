<table>
    <thead>
        <tr>
            <th>N°</th>
            <th>Numéro CIN</th>
            <th>Civilité</th>
            <th>Prénom</th>
            <th>Nom</th>
            <th>Date naissance</th>
            <th>Lieu naissance</th>
            <th>Adresse e-mail</th>
            <th>Téléphone</th>
            <th>Téléphone secondaire</th>
            <th>Adresse</th>
            <th>Région</th>
            <th>Module</th>
            <th>Niveau d'étude</th>
            <th>Diplôme académique</th>
            <th>Diplôme professionnel</th>
            <th>Experience</th>
            <th>Projet professionnel</th>
            <th>Qualification</th>
            <th>Prérequis</th>
            <th>Informations</th>
            <th>Statut</th>
            <th>fichiers</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($individuelles as $index => $individuelle)
            <tr>
                <td>{{ $loop?->iteration }}</td>
                <td>{{ $individuelle?->user?->cin }}</td>
                <td>{{ $individuelle?->user?->civilite }}</td>
                <td>{{ $individuelle?->user?->firstname }}</td>
                <td>{{ $individuelle?->user?->name }}</td>
                <td>{{ $individuelle?->user?->date_naissance?->format('d/m/Y') }}</td>
                <td>{{ $individuelle?->user?->lieu_naissance }}</td>
                <td>{{ $individuelle?->user?->email }}</td>
                {{-- Téléphones : nettoyage des caractères --}}
                <td>{{ preg_replace('/\D+/', '', $individuelle?->user?->telephone ?? '') }}</td>
                <td>{{ preg_replace('/\D+/', '', $individuelle?->user?->telephone_secondaire ?? $individuelle?->user?->telephone_parent) }}
                </td>
                <td>{{ $individuelle?->user?->adresse }}</td>
                <td>{{ $individuelle?->region?->nom }}</td>
                <td>{{ $individuelle?->module?->name }}</td>
                <td>{{ $individuelle?->niveau_etude }}</td>
                <td>{{ $individuelle?->diplome_academique }}</td>
                <td>{{ $individuelle?->diplome_professionnel }}</td>
                <td>{{ $individuelle?->experience }}</td>
                <td>{{ $individuelle?->projetprofessionnel }}</td>
                <td>{{ $individuelle?->qualification }}</td>
                <td>{{ $individuelle?->prerequis }}</td>
                <td>{{ $individuelle?->information }}</td>
                <td>{{ $individuelle?->statut }}</td>
                <td>
                    @if ($individuelle?->user?->files->isNotEmpty())
                        @foreach ($individuelle->user->files as $file)
                            @if ($file->getFichier())
                                {{ $file->legende }}<br>
                            @endif
                        @endforeach
                    @else
                        Aucun fichier
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
