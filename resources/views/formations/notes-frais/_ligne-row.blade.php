<tr>
    @if ($ligne && !empty($ligne->id))
        <input type="hidden" name="lignes[{{ $index }}][id]" value="{{ $ligne->id }}">
    @endif

    <td>
        <select name="lignes[{{ $index }}][rubriques_id]" class="form-select form-select-sm select-rubrique" required>
            <option value="">-- Choisir --</option>
            @foreach ($rubriques->groupBy('groupe') as $groupe => $rubriquesGroupe)
                <optgroup label="{{ $groupe === 'PEDAGOGIQUE' ? 'Frais pédagogiques' : 'Frais administratifs' }}">
                    @foreach ($rubriquesGroupe as $rubrique)
                        <option value="{{ $rubrique->id }}" data-groupe="{{ $rubrique->groupe }}"
                            data-unite="{{ $rubrique->unite_defaut }}"
                            @selected($ligne && ($ligne->rubriques_id ?? null) == $rubrique->id)>
                            {{ $rubrique->libelle }}
                        </option>
                    @endforeach
                </optgroup>
            @endforeach
        </select>
    </td>

    <td>
        <input type="text" name="lignes[{{ $index }}][unite]"
            value="{{ $ligne->unite ?? '' }}" class="form-control form-control-sm">
    </td>

    <td>
        <input type="number" step="0.01" min="0" name="lignes[{{ $index }}][qte]"
            value="{{ $ligne->qte ?? '' }}" class="form-control form-control-sm input-qte">
    </td>

    <td>
        <input type="number" step="0.01" min="0" name="lignes[{{ $index }}][pu]"
            value="{{ $ligne->pu ?? '' }}" class="form-control form-control-sm input-pu">
    </td>

    <td class="affichage-montant-ligne text-end">
        {{ number_format($ligne->montant ?? 0, 0, ',', ' ') }}
    </td>

    <td class="text-center">
        <button type="button" class="btn btn-sm btn-outline-danger btn-remove-ligne" title="Retirer">
            <i class="bi bi-trash"></i>
        </button>
    </td>
</tr>
