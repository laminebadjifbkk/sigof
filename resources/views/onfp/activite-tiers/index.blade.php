@extends('layout.user-layout')

@section('title', "Tiers de l'activité")

@section('space-work')
    <div class="container-fluid py-4">

        {{-- En-tête --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
            <div class="d-flex align-items-center" style="min-width: 0;">
                <a href="{{ route('onfp.activites.show', $activite) }}"
                    class="btn btn-sm btn-outline-secondary me-3 flex-shrink-0" title="Retour à l'activité">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div style="min-width: 0;">
                    <h4 class="mb-1">
                        <i class="bi bi-person-vcard me-2"></i>Tiers intervenants
                    </h4>
                    <div class="text-muted small text-break">
                        <span class="badge bg-light text-dark border">{{ $activite->reference }}</span>
                        {{ $activite->titre }}
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('onfp.activites.edit', $activite) }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-pencil me-1"></i>Modifier les tiers
                </a>
                <a href="{{ route('onfp.tiers.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-journal-bookmark me-1"></i>Annuaire
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Statistiques --}}
        <div class="row g-3 mb-4">

            @foreach ($cartes as $carte)
                <div class="col-6 col-md-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="rounded-circle bg-{{ $carte['color'] }} bg-opacity-10 text-{{ $carte['color'] }}
                                    d-flex align-items-center justify-content-center flex-shrink-0"
                                style="width:48px;height:48px;">
                                <i class="bi {{ $carte['icon'] }} fs-4"></i>
                            </div>
                            <div>
                                <div class="fs-4 fw-bold lh-1">{{ $carte['valeur'] }}</div>
                                <div class="small text-muted">{{ $carte['label'] }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Liste --}}
        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white py-3">
                <div class="row g-2 align-items-center">
                    <div class="col-md-6">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                            <input type="search" id="filtre-tiers" class="form-control"
                                placeholder="Rechercher un nom, une organisation, un rôle…">
                        </div>
                    </div>
                    <div class="col-md-6 text-md-end small text-muted">
                        <span id="compteur-tiers">{{ $activiteTiers->count() }}</span>
                        tiers affiché(s)
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="table-tiers">
                    <thead class="table-light">
                        <tr>
                            <th>Tiers</th>
                            <th>Organisation</th>
                            <th>Rôle dans l'activité</th>
                            <th>Contact</th>
                            <th class="text-center">Statut</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($activiteTiers as $lien)
                            @php
                                $tier = $lien->tiers;
                                $nom = $tier?->nom ?? 'Tiers supprimé';
                            @endphp

                            <tr>
                                {{-- Identité --}}
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-circle bg-info text-white fw-semibold
                                                d-flex align-items-center justify-content-center flex-shrink-0"
                                            style="width:40px;height:40px;">
                                            {{ mb_strtoupper(mb_substr($nom, 0, 1)) }}
                                        </div>
                                        <div style="min-width: 0;">
                                            <div class="fw-semibold text-break">{{ $nom }}</div>
                                            <div class="small text-muted">
                                                @if ($tier?->fonction)
                                                    {{ $tier->fonction }}
                                                @endif
                                                @if ($tier?->type)
                                                    <span class="badge bg-light text-dark border ms-1">
                                                        {{ $types[$tier->type] ?? $tier->type }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Organisation --}}
                                <td class="text-break">
                                    {{ $tier?->organisation ?: '-' }}
                                </td>

                                {{-- Rôle --}}
                                {{-- Rôle --}}
                                <td>
                                    @php
                                        $roleAPreciser =
                                            blank($lien->role) ||
                                            $lien->role === \App\Models\OnfpActiviteTiers::ROLE_PAR_DEFAUT;
                                    @endphp

                                    @if ($roleAPreciser)
                                        <span
                                            class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">
                                            <i class="bi bi-exclamation-circle me-1"></i>À préciser
                                        </span>
                                    @else
                                        <span
                                            class="badge bg-primary bg-opacity-10 text-primary border border-primary-subtle">
                                            {{ $lien->role }}
                                        </span>
                                    @endif
                                </td>

                                {{-- Contact --}}
                                <td class="small">
                                    @if ($tier?->email)
                                        <div class="text-break">
                                            <i class="bi bi-envelope text-muted me-1"></i>
                                            <a href="mailto:{{ $tier->email }}"
                                                class="text-decoration-none">{{ $tier->email }}</a>
                                        </div>
                                    @endif
                                    @if ($tier?->telephone)
                                        <div>
                                            <i class="bi bi-telephone text-muted me-1"></i>
                                            <a href="tel:{{ preg_replace('/\s+/', '', $tier->telephone) }}"
                                                class="text-decoration-none">{{ $tier->telephone }}</a>
                                        </div>
                                    @endif
                                    @if (!$tier?->email && !$tier?->telephone)
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>

                                {{-- Statut --}}
                                <td class="text-center">
                                    @if (!$tier)
                                        <span class="badge bg-secondary">Supprimé</span>
                                    @elseif ($tier->actif)
                                        <span class="badge bg-success">Actif</span>
                                    @else
                                        <span class="badge bg-secondary">Inactif</span>
                                    @endif
                                </td>

                                {{-- Actions --}}
                                <td class="text-end text-nowrap">
                                    <button type="button"
                                        class="btn btn-sm {{ $roleAPreciser ? 'btn-warning' : 'btn-outline-primary' }}"
                                        title="Modifier le rôle" data-bs-toggle="modal" data-bs-target="#modalRole"
                                        data-action="{{ route('onfp.activites.tiers.update', [$activite, $lien]) }}"
                                        data-nom="{{ $nom }}"
                                        data-role="{{ $roleAPreciser ? '' : $lien->role }}">
                                        <i class="bi bi-pencil"></i>
                                    </button>

                                    @if ($tier)
                                        <a href="{{ route('onfp.tiers.show', $tier) }}"
                                            class="btn btn-sm btn-outline-secondary" title="Voir la fiche">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    @endif

                                    <form method="POST"
                                        action="{{ route('onfp.activites.tiers.destroy', [$activite, $lien]) }}"
                                        class="d-inline" onsubmit="return confirm('Retirer ce tiers de l\'activité ?');">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" title="Retirer de l'activité">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <i class="bi bi-person-vcard fs-1 text-muted"></i>
                                    <p class="text-muted mt-2 mb-3">
                                        Aucun tiers n'est associé à cette activité.
                                    </p>
                                    <a href="{{ route('onfp.activites.edit', $activite) }}"
                                        class="btn btn-sm btn-primary">
                                        <i class="bi bi-plus-lg me-1"></i>Associer des tiers
                                    </a>
                                </td>
                            </tr>
                        @endforelse

                        {{-- Ligne affichée quand la recherche ne donne rien --}}
                        <tr id="aucun-resultat" class="d-none">
                            <td colspan="6" class="text-center text-muted py-4">
                                Aucun résultat pour cette recherche.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <datalist id="roles-suggeres">
        @foreach ($rolesSuggeres as $r)
            <option value="{{ $r }}">
        @endforeach
    </datalist>

    <div class="modal fade" id="modalRole" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" id="formRole" class="modal-content">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-person-gear me-2"></i>Rôle dans l'activité
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <div class="text-muted small">Tiers</div>
                        <div class="fw-semibold" id="modalRoleNom"></div>
                    </div>

                    <label for="modalRoleInput" class="form-label fw-semibold">
                        Rôle <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="role" id="modalRoleInput" list="roles-suggeres" maxlength="100"
                        class="form-control form-control-sm" required placeholder="Choisir dans la liste ou saisir un rôle">
                    <div class="form-text">
                        Exemples : Partenaire, Prestataire, Bailleur / Financeur, Formateur…
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-light border" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-sm btn-primary">
                        <i class="bi bi-save me-1"></i>Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('modalRole');

            modal.addEventListener('show.bs.modal', (event) => {
                const btn = event.relatedTarget;

                document.getElementById('formRole').action = btn.dataset.action;
                document.getElementById('modalRoleNom').textContent = btn.dataset.nom;
                document.getElementById('modalRoleInput').value = btn.dataset.role || '';
            });

            modal.addEventListener('shown.bs.modal', () => {
                document.getElementById('modalRoleInput').focus();
            });

            // Recherche instantanée
            const input = document.getElementById('filtre-tiers');
            const lignes = document.querySelectorAll('#table-tiers tbody tr:not(#aucun-resultat)');
            const compteur = document.getElementById('compteur-tiers');
            const vide = document.getElementById('aucun-resultat');

            input?.addEventListener('input', () => {
                const terme = input.value.trim().toLowerCase();
                let visibles = 0;

                lignes.forEach(ligne => {
                    const ok = ligne.textContent.toLowerCase().includes(terme);
                    ligne.classList.toggle('d-none', !ok);
                    if (ok) visibles++;
                });

                compteur.textContent = visibles;
                vide.classList.toggle('d-none', visibles > 0 || lignes.length === 0);
            });
        });
    </script>
@endsection
