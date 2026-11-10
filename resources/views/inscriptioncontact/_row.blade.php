@php
    $date = $inscription->created_at ? \Carbon\Carbon::parse($inscription->created_at) : null;
@endphp
<tr>
    <td>{{ $inscription->structure }}</td>
    <td>{{ $inscription->nom }}</td>
    <td>{{ $inscription->fonction }}</td>
    <td>
        <a href="mailto:{{ $inscription->email }}" class="text-decoration-none">
            {{ $inscription->email }}
        </a>
    </td>
    <td class="text-center">
        <a href="tel:+221{{ $inscription->telephone }}" class="text-decoration-none">
            {{ $inscription->telephone }}
        </a>
    </td>
    <td>
        <span>
            @if ($date)
                @php
                    $startOfDay = $date->copy()->startOfDay();
                    $days = (int) abs($startOfDay->diffInDays(\Carbon\Carbon::today()));
                @endphp
                <i class="bi bi-calendar-event text-success me-1"></i>
                {{ $date->format('d/m/Y H:i:s') }}

                @if ($date->isToday())
                    <span class="badge bg-success">Aujourd'hui</span>
                @elseif ($date->isYesterday())
                    <span class="badge bg-warning">Hier</span>
                @elseif ($days < 7)
                    <span class="badge bg-primary">Il y a {{ $days }} jours</span>
                @else
                    @php
                        $diff = $startOfDay->diff(\Carbon\Carbon::today());
                        $parts = [];
                        if ($diff->y > 0) {
                            $parts[] = $diff->y . ' ' . ($diff->y > 1 ? 'ans' : 'an');
                        }
                        if ($diff->m > 0) {
                            $parts[] = $diff->m . ' mois'; // "mois" invariable
                        }
                        if ($diff->d > 0) {
                            $parts[] = $diff->d . ' ' . ($diff->d > 1 ? 'jours' : 'jour');
                        }
                    @endphp
                    <span class="badge bg-secondary">Il y a {{ implode(' ', $parts) }}</span>
                @endif
            @else
                <span class="badge bg-danger">Date non disponible</span>
            @endif
        </span>
    </td>
    <td class="text-center">
        <div class="btn-group">
            {{-- Bouton Voir --}}
            <button class="btn btn-warning btn-sm viewInscriptionBtn" data-id="{{ $inscription->id }}"
                title="Voir détails">
                <i class="bi bi-eye"></i>
            </button>&nbsp;

            {{-- Bouton Modifier --}}
            <button class="btn btn-info btn-sm editInscriptionBtn" data-id="{{ $inscription->id }}" title="Modifier">
                <i class="bi bi-pencil-square"></i>
            </button>

            {{-- Menu Supprimer --}}
            <div class="filter">
                <a class="icon" href="#" data-bs-toggle="dropdown">
                    <i class="bi bi-three-dots"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <form action="{{ route('inscriptioncontacts.destroy', $inscription->id) }}" method="post">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="dropdown-item show_confirm">Supprimer</button>
                    </form>
                </ul>
            </div>
        </div>
    </td>
</tr>
