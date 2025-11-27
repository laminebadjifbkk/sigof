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
            <th>Dernier diplôme obtenu</th>
            <th>Établissement</th>
            <th>Région</th>
            <th>Formation sollicitée</th>
            <th>Diplôme visé</th>
            <th>Montant inscription</th>
            <th>Montant mensualité</th>
            <th>Montant unique</th>
            <th>Durée (en années)</th>
            <th>Situation de handicap</th>
            <th>Type de handicap</th>
            <th>Orphelin</th>
            <th>Type d’orphelinat</th>
            <th>Statut</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($formulaires as $index => $user)
            <tr>
                <td>{{ $user?->id }}</td>
                <td>{{ $user?->cin }}</td>
                <td>{{ $user?->civilite }}</td>
                <td>{{ $user?->prenom }}</td>
                <td>{{ $user?->nom }}</td>
                <td>{{ $user?->date_naissance?->format('d/m/Y') }}</td>
                <td>{{ $user?->lieu_naissance }}</td>
                <td>{{ $user?->email }}</td>

                {{-- Téléphones : nettoyage des caractères --}}
                <td>{{ preg_replace('/\D+/', '', $user?->telephone ?? '') }}</td>
                <td>{{ preg_replace('/\D+/', '', $user?->telephone_secondaire ?? '') }}</td>

                <td>{{ $user?->adresse }}</td>
                <td>{{ $user?->dernier_diplome }}</td>
                <td>{{ $user?->nom_etablissement }}</td>
                <td>{{ $user?->region }}</td>
                <td>{{ $user?->formation }}</td>
                <td>{{ $user?->diplome_vise }}</td>

                <td>{{ $user?->montant_inscription }}</td>
                <td>{{ $user?->montant_mensualite }}</td>
                <td>{{ $user?->montant_unique }}</td>

                <td>{{ $user?->duree }}</td>

                <td>{{ $user?->handicape }}</td>
                <td>{{ $user?->type_handicap }}</td>

                <td>{{ $user?->orphelin }}</td>
                <td>{{ $user?->type_orphelin }}</td>
                <td>{{ $user?->statut }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
