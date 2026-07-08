<table>
    <thead>
        <tr>
            <th>N°</th>
            <th>Opérateur</th>
            <th>Adresse</th>
            <th>Email</th>
            <th>Téléphone</th>
            <th>Civilite responsable</th>
            <th>Nom responsable</th>
            <th>Fonction responsble</th>
            <th>Domaine</th>
            <th>Module</th>
            <th>Catégorie</th>
            <th>Motif sous réserve</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($operateurs as $op)
            @php
                $modules = $op->operateurmodules;
                $rowspan = $modules->count();

                $lastValidation = collect($op->validationoperateurs)->sortByDesc('created_at')->first();
                $i = 1;
            @endphp

            @foreach ($modules as $index => $module)
                <tr>
                    @if ($index === 0)
                        <td rowspan="{{ $rowspan }}">{{ $i++ }}</td>
                        <td rowspan="{{ $rowspan }}">{{ $op?->user?->display_operateur }}</td>
                        <td rowspan="{{ $rowspan }}">{{ $op?->user?->adresse }}</td>
                        <td rowspan="{{ $rowspan }}">{{ $op?->user?->email }}</td>
                        <td rowspan="{{ $rowspan }}">{{ preg_replace('/\D+/', '', $op?->user?->telephone ?? '') }}
                        </td>
                        <td rowspan="{{ $rowspan }}">{{ $op?->user?->civilite }}</td>
                        <td rowspan="{{ $rowspan }}">{{ $op?->user?->firstname .' '.$op?->user?->name }}</td>
                        <td rowspan="{{ $rowspan }}">{{ $op?->user?->fonction_responsable }}</td>
                    @endif

                    <td>{{ $module?->domaine }}</td>
                    <td>{{ $module?->module }}</td>
                    <td>{{ $module?->categorie }}</td>

                    @if ($index === 0)
                        <td rowspan="{{ $rowspan }}">{{ old('motif', $lastValidation?->motif) }}</td>
                    @endif
                </tr>
            @endforeach
        @endforeach
    </tbody>
</table>
