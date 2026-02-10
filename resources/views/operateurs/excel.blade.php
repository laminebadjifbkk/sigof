<table>
    <thead>
        <tr>
            <th>Opérateur</th>
            <th>Sigle</th>
            <th>Adresse</th>
            <th>Email</th>
            <th>Téléphone</th>
            <th>Domaine</th>
            <th>Module</th>
            <th>Catégorie</th>
            <th>Statut</th>
            <th>N° agrément</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($operateurs as $op)
            @php
                $modules = $op->operateurmodules;
                $rowspan = $modules->count();
            @endphp

            @foreach ($modules as $index => $module)
                <tr>
                    @if ($index === 0)
                        <td rowspan="{{ $rowspan }}">{{ $op?->user?->operateur }}</td>
                        <td rowspan="{{ $rowspan }}">{{ $op?->user?->username }}</td>
                        <td rowspan="{{ $rowspan }}">{{ $op?->user?->adresse }}</td>
                        <td rowspan="{{ $rowspan }}">{{ $op?->user?->email }}</td>
                        <td rowspan="{{ $rowspan }}">{{ preg_replace('/\D+/', '', $op?->user?->telephone ?? '') }}</td>
                    @endif

                    <td>{{ $module?->domaine }}</td>
                    <td>{{ $module?->module }}</td>
                    <td>{{ $module?->categorie }}</td>
                    <td>{{ $module?->statut }}</td>

                    @if ($index === 0)
                        <td rowspan="{{ $rowspan }}">{{ $op->numero_agrement }}</td>
                    @endif
                </tr>
            @endforeach
        @endforeach
    </tbody>
</table>
