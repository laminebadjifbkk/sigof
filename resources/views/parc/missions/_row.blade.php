{{-- Ligne d'une mission. Variables : $mission, $labels --}}
@php
    $objetLong = mb_strlen($mission->objet) > 25;
    $statutKey = $mission->statut ?? 'Aucun';
    $statutLabel = $labels[$statutKey] ?? ucfirst(str_replace('_', ' ', $statutKey));
@endphp
<tr>
    <td>
        <span class="short-text">{{ Str::limit($mission->objet, 25) }}</span>
        <span class="full-text d-none">{{ $mission->objet }}</span>
        @if ($objetLong)
            <a href="#" class="toggle-text">...voir plus</a>
        @endif
    </td>
    <td>
        {{ $mission->date_depart?->format('d/m/Y') }}
        @if ($mission->date_retour)
            → {{ $mission->date_retour->format('d/m/Y') }}
        @endif
    </td>
    <td class="text-center">{{ $mission->vehicules_count }}</td>
    <td class="text-center">{{ $mission->employees_count }}</td>
    <td class="text-center">
        <span class="etat-btn {{ $mission->statut }}">{{ $statutLabel }}</span>
    </td>
    <td class="text-center">
        <span class="d-flex align-items-baseline justify-content-center gap-1">
            <a href="{{ route('parc-missions.show', $mission->id) }}" class="btn btn-sm btn-info" title="Voir">
                <i class="bi bi-eye"></i>
            </a>
            <a href="{{ route('parc-missions.edit', $mission->id) }}" class="btn btn-sm btn-warning" title="Modifier">
                <i class="bi bi-pencil-square"></i>
            </a>
            <form action="{{ route('parc-missions.destroy', $mission->id) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <input type="hidden" name="id" value="{{ $mission->id }}">
                <button type="submit" class="btn btn-sm btn-danger show_confirm"
                    {{ $mission->employees_count > 0 ? 'disabled' : '' }}
                    title="{{ $mission->employees_count > 0 ? 'Mission déjà assignée à des employés' : 'Supprimer la mission' }}"
                    data-id="{{ $mission->id }}">
                    <i class="bi bi-trash"></i>
                </button>
            </form>
        </span>
    </td>
</tr>
