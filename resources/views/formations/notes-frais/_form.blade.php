@csrf

@if (isset($noteFrais))
    @method('PUT')
@endif

<div class="row g-4">

    {{-- Formation (donc opérateur, via formations.operateurs_id) --}}
    <div class="col-md-6">
        <label for="formations_id" class="form-label">
            Formation <span class="text-danger">*</span>
        </label>

        <select name="formations_id" id="formations_id"
            class="form-select form-select-sm @error('formations_id') is-invalid @enderror" required
            {{ isset($noteFrais) ? 'disabled' : '' }}>
            <option value="">-- Sélectionner une formation --</option>
            @foreach ($formations as $formation)
                <option value="{{ $formation->id }}"
                    @selected(old('formations_id', $noteFrais->formations_id ?? '') == $formation->id)>
                    {{ $formation->operateur?->user?->display_operateur }}
                    - {{ $formation->intitule ?? $formation->name }}
                </option>
            @endforeach
        </select>

        {{-- Le select est désactivé en modification (formation figée) : on renvoie
             quand même sa valeur au submit --}}
        @if (isset($noteFrais))
            <input type="hidden" name="formations_id" value="{{ $noteFrais->formations_id }}">
        @endif

        @error('formations_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Type --}}
    <div class="col-md-3">
        <label for="type" class="form-label">
            Type <span class="text-danger">*</span>
        </label>

        <select name="type" id="type" class="form-select form-select-sm @error('type') is-invalid @enderror"
            required {{ isset($noteFrais) ? 'disabled' : '' }}>
            <option value="ACOMPTE" @selected(old('type', $noteFrais->type ?? '') === 'ACOMPTE')>Acompte</option>
            <option value="DEFINITIVE" @selected(old('type', $noteFrais->type ?? '') === 'DEFINITIVE')>Définitive</option>
        </select>

        @if (isset($noteFrais))
            <input type="hidden" name="type" value="{{ $noteFrais->type }}">
        @endif

        @error('type')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Taux d'acompte --}}
    <div class="col-md-3">
        <label for="taux_acompte" class="form-label">
            Taux d'acompte (%)
        </label>
        <input type="number" step="0.01" min="0" max="100" name="taux_acompte" id="taux_acompte"
            value="{{ old('taux_acompte', $noteFrais->taux_acompte ?? 30) }}"
            class="form-control form-control-sm @error('taux_acompte') is-invalid @enderror">
        @error('taux_acompte')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Note d'acompte liée (uniquement pour une note DEFINITIVE) --}}
    <div class="col-md-6" id="bloc-note-acompte"
        style="display: {{ old('type', $noteFrais->type ?? '') === 'DEFINITIVE' ? 'block' : 'none' }}">
        <label for="note_acompte_id" class="form-label">
            Note d'acompte liée
        </label>
        <select name="note_acompte_id" id="note_acompte_id" class="form-select form-select-sm">
            <option value="">-- Aucune (100% dès la définitive) --</option>
            @foreach ($notesAcompte as $acompte)
                <option value="{{ $acompte->id }}"
                    @selected(old('note_acompte_id', $noteFrais->note_acompte_id ?? '') == $acompte->id)
                    data-formation="{{ $acompte->formations_id }}">
                    {{ $acompte->formation?->intitule }} — {{ number_format($acompte->total_frais_operateur, 0, ',', ' ') }} FCFA
                </option>
            @endforeach
        </select>
        <div class="form-text">Le montant reçu (B) sera repris automatiquement de cette note.</div>
    </div>

    {{-- Session --}}
    <div class="col-md-6">
        <label for="session_label" class="form-label">Session</label>
        <input type="text" name="session_label" id="session_label"
            value="{{ old('session_label', $noteFrais->session_label ?? '') }}"
            class="form-control form-control-sm" placeholder="Ex. : Session 1">
    </div>

    {{-- Lieu --}}
    <div class="col-md-4">
        <label for="lieu" class="form-label">Lieu de la formation</label>
        <input type="text" name="lieu" id="lieu" value="{{ old('lieu', $noteFrais->lieu ?? '') }}"
            class="form-control form-control-sm">
    </div>

    {{-- Bénéficiaires --}}
    <div class="col-md-4">
        <label for="beneficiaires" class="form-label">Bénéficiaires</label>
        <input type="text" name="beneficiaires" id="beneficiaires"
            value="{{ old('beneficiaires', $noteFrais->beneficiaires ?? '') }}"
            class="form-control form-control-sm" placeholder="Ex. : 20 membres du GIE...">
    </div>

    {{-- RIB --}}
    <div class="col-md-4">
        <label for="banque_rib" class="form-label">Banque RIB</label>
        <input type="text" name="banque_rib" id="banque_rib"
            value="{{ old('banque_rib', $noteFrais->banque_rib ?? '') }}" class="form-control form-control-sm">
    </div>

    {{-- Période (uniquement note DEFINITIVE) --}}
    <div class="col-md-4 champ-periode" style="display: {{ old('type', $noteFrais->type ?? '') === 'DEFINITIVE' ? 'block' : 'none' }}">
        <label for="periode_debut" class="form-label">Début de la formation</label>
        <input type="date" name="periode_debut" id="periode_debut"
            value="{{ old('periode_debut', isset($noteFrais->periode_debut) ? $noteFrais->periode_debut->format('Y-m-d') : '') }}"
            class="form-control form-control-sm">
    </div>

    <div class="col-md-4 champ-periode" style="display: {{ old('type', $noteFrais->type ?? '') === 'DEFINITIVE' ? 'block' : 'none' }}">
        <label for="periode_fin" class="form-label">Fin de la formation</label>
        <input type="date" name="periode_fin" id="periode_fin"
            value="{{ old('periode_fin', isset($noteFrais->periode_fin) ? $noteFrais->periode_fin->format('Y-m-d') : '') }}"
            class="form-control form-control-sm">
    </div>

    {{-- ==============================================================
    LIGNES DE FRAIS
    =============================================================== --}}
    <div class="col-12">
        <hr class="my-2">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="mb-0">Rubriques de frais</h6>
            <button type="button" id="btn-add-ligne" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-plus-circle me-1"></i> Ajouter une ligne
            </button>
        </div>

        <div class="table-responsive">
            <table class="table table-sm table-bordered align-middle" id="table-lignes">
                <thead class="table-light">
                    <tr>
                        <th style="width: 30%">Rubrique</th>
                        <th style="width: 15%">Unité</th>
                        <th style="width: 12%">Qte</th>
                        <th style="width: 15%">PU (FCFA)</th>
                        <th style="width: 18%">Montant (FCFA)</th>
                        <th style="width: 5%"></th>
                    </tr>
                </thead>
                <tbody id="lignes-body">
                    @php $existingLignes = old('lignes', isset($noteFrais) ? $noteFrais->lignes->toArray() : []); @endphp

                    @forelse ($existingLignes as $i => $ligne)
                        @include('formations.notes-frais._ligne-row', ['index' => $i, 'ligne' => (object) $ligne, 'rubriques' => $rubriques])
                    @empty
                        {{-- Au moins une ligne vide au chargement --}}
                        @include('formations.notes-frais._ligne-row', ['index' => 0, 'ligne' => null, 'rubriques' => $rubriques])
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="table-light">
                        <td colspan="4" class="text-end fw-bold">Sous total pédagogique</td>
                        <td id="affichage-sous-total-pedagogique" class="fw-bold">
                            {{ number_format($noteFrais->sous_total_pedagogique ?? 0, 0, ',', ' ') }}
                        </td>
                        <td></td>
                    </tr>
                    <tr class="table-light">
                        <td colspan="4" class="text-end fw-bold">Sous total administratif</td>
                        <td id="affichage-sous-total-administratif" class="fw-bold">
                            {{ number_format($noteFrais->sous_total_administratif ?? 0, 0, ',', ' ') }}
                        </td>
                        <td></td>
                    </tr>
                    <tr class="table-secondary">
                        <td colspan="4" class="text-end fw-bold">TOTAL FRAIS OPERATEUR</td>
                        <td id="affichage-total" class="fw-bold">
                            {{ number_format($noteFrais->total_frais_operateur ?? 0, 0, ',', ' ') }}
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="form-text">
            Les sous-totaux, le total, l'acompte et le reliquat sont recalculés automatiquement à
            l'enregistrement — ils ne sont donnés ici qu'à titre indicatif.
        </div>

        {{-- Template caché cloné en JS pour ajouter une ligne (voir @push('scripts') plus bas).
             __INDEX__ est remplacé par le prochain index disponible avant insertion. --}}
        <template id="ligne-row-template">
            <td>
                <select name="lignes[__INDEX__][rubriques_id]" class="form-select form-select-sm select-rubrique" required>
                    <option value="">-- Choisir --</option>
                    @foreach ($rubriques->groupBy('groupe') as $groupe => $rubriquesGroupe)
                        <optgroup label="{{ $groupe === 'PEDAGOGIQUE' ? 'Frais pédagogiques' : 'Frais administratifs' }}">
                            @foreach ($rubriquesGroupe as $rubrique)
                                <option value="{{ $rubrique->id }}" data-groupe="{{ $rubrique->groupe }}"
                                    data-unite="{{ $rubrique->unite_defaut }}">
                                    {{ $rubrique->libelle }}
                                </option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
            </td>
            <td>
                <input type="text" name="lignes[__INDEX__][unite]" class="form-control form-control-sm">
            </td>
            <td>
                <input type="number" step="0.01" min="0" name="lignes[__INDEX__][qte]"
                    class="form-control form-control-sm input-qte">
            </td>
            <td>
                <input type="number" step="0.01" min="0" name="lignes[__INDEX__][pu]"
                    class="form-control form-control-sm input-pu">
            </td>
            <td class="affichage-montant-ligne text-end">0</td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-outline-danger btn-remove-ligne" title="Retirer">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        </template>
    </div>

</div>

<hr class="my-4">

<div class="d-flex justify-content-between">
    <a href="{{ route('formations.notes-frais.index') }}" class="btn btn-sm btn-outline-secondary">
        Annuler
    </a>

    <button type="submit" class="btn btn-sm btn-primary">
        @if (isset($noteFrais))
            Enregistrer les modifications
        @else
            Créer la note de frais
        @endif
    </button>
</div>

@push('scripts')
    <script>
        (function() {
            let ligneIndex = {{ count($existingLignes) > 0 ? count($existingLignes) : 1 }};

            const lignesBody = document.getElementById('lignes-body');
            const rowTemplate = document.getElementById('ligne-row-template');

            // Ajout d'une ligne
            document.getElementById('btn-add-ligne').addEventListener('click', function() {
                const html = rowTemplate.innerHTML.replaceAll('__INDEX__', ligneIndex);
                const tr = document.createElement('tr');
                tr.innerHTML = html;
                lignesBody.appendChild(tr);
                ligneIndex++;
                recalculerTotaux();
            });

            // Suppression d'une ligne (délégation, car les lignes sont ajoutées dynamiquement)
            lignesBody.addEventListener('click', function(e) {
                const btn = e.target.closest('.btn-remove-ligne');
                if (!btn) return;
                if (lignesBody.querySelectorAll('tr').length <= 1) return; // garder au moins 1 ligne
                btn.closest('tr').remove();
                recalculerTotaux();
            });

            // Recalcul en direct du montant de chaque ligne + des sous-totaux (indicatif uniquement,
            // le calcul qui fait foi reste celui du backend via NoteFrais::recalculerTotaux())
            lignesBody.addEventListener('input', function(e) {
                if (e.target.matches('.input-qte, .input-pu')) {
                    const row = e.target.closest('tr');
                    const qte = parseFloat(row.querySelector('.input-qte').value) || 0;
                    const pu = parseFloat(row.querySelector('.input-pu').value) || 0;
                    row.querySelector('.affichage-montant-ligne').textContent =
                        (qte * pu).toLocaleString('fr-FR');
                    recalculerTotaux();
                }
            });

            lignesBody.addEventListener('change', function(e) {
                if (e.target.matches('.select-rubrique')) {
                    recalculerTotaux();
                }
            });

            function recalculerTotaux() {
                let sousTotalPedagogique = 0;
                let sousTotalAdministratif = 0;

                lignesBody.querySelectorAll('tr').forEach(function(row) {
                    const select = row.querySelector('.select-rubrique');
                    if (!select || !select.value) return;

                    const groupe = select.selectedOptions[0]?.dataset.groupe;
                    const qte = parseFloat(row.querySelector('.input-qte').value) || 0;
                    const pu = parseFloat(row.querySelector('.input-pu').value) || 0;
                    const montant = qte * pu;

                    if (groupe === 'PEDAGOGIQUE') sousTotalPedagogique += montant;
                    if (groupe === 'ADMINISTRATIF') sousTotalAdministratif += montant;
                });

                document.getElementById('affichage-sous-total-pedagogique').textContent =
                    sousTotalPedagogique.toLocaleString('fr-FR');
                document.getElementById('affichage-sous-total-administratif').textContent =
                    sousTotalAdministratif.toLocaleString('fr-FR');
                document.getElementById('affichage-total').textContent =
                    (sousTotalPedagogique + sousTotalAdministratif).toLocaleString('fr-FR');
            }

            // Afficher/masquer les champs propres à la note DEFINITIVE
            const typeSelect = document.getElementById('type');
            if (typeSelect) {
                typeSelect.addEventListener('change', function() {
                    const estDefinitive = this.value === 'DEFINITIVE';
                    document.getElementById('bloc-note-acompte').style.display = estDefinitive ? 'block' : 'none';
                    document.querySelectorAll('.champ-periode').forEach(el => {
                        el.style.display = estDefinitive ? 'block' : 'none';
                    });
                });
            }
        })();
    </script>
@endpush
