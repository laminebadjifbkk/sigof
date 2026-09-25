@extends('layout.user-layout')

@section('title', 'Notes de frais')

@section('space-work')

    <div class="container-fluid">

        {{-- ============================================================
        HEADER
        ============================================================= --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
            <div>
                <h1 class="h3 mb-1">Notes de frais opérateurs</h1>
                <p class="text-muted mb-0">Acomptes et notes définitives des opérateurs de formation</p>
            </div>

            <a href="{{ route('formations.notes-frais.create') }}" class="btn btn-sm btn-primary">
                <i class="bi bi-plus-circle me-1"></i>
                Nouvelle note de frais
            </a>
        </div>

        {{-- ============================================================
        MESSAGE
        ============================================================= --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
            </div>
        @endif

        {{-- ============================================================
        STATISTIQUES
        ============================================================= --}}
        <div class="row row-cols-4 g-3 mb-4">
            <div class="col">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-muted small mb-1">Total notes</div>
                                <h3 class="mb-0">{{ $totalNotes }}</h3>
                            </div>
                            <div class="fs-2 text-primary"><i class="bi bi-receipt"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-muted small mb-1">En attente de validation</div>
                                <h3 class="mb-0">{{ $notesEnAttente }}</h3>
                            </div>
                            <div class="fs-2 text-warning"><i class="bi bi-hourglass"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-muted small mb-1">Payées</div>
                                <h3 class="mb-0">{{ $notesPayees }}</h3>
                            </div>
                            <div class="fs-2 text-success"><i class="bi bi-check-circle"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body">
                        <div class="text-muted small mb-1">Total reliquats en attente</div>
                        <h3 class="mb-0">{{ number_format($totalReliquats, 0, ',', ' ') }} FCFA</h3>
                    </div>
                </div>
            </div>
        </div>

        {{-- ============================================================
        FILTRES
        ============================================================= --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-funnel me-1"></i> Filtrer les notes de frais</h5>

                    @if ($hasActiveFilters)
                        <a href="{{ route('formations.notes-frais.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-x-circle me-1"></i> Réinitialiser
                        </a>
                    @endif
                </div>
            </div>

            <div class="card-body">
                <form method="GET" action="{{ route('formations.notes-frais.index') }}">
                    <div class="row g-3">

                        <div class="col-md-4">
                            <label class="form-label">Recherche</label>
                            <input type="text" name="search" value="{{ request('search') }}"
                                class="form-control form-control-sm" placeholder="Opérateur, module...">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Type</label>
                            <select name="type" class="form-select form-select-sm">
                                <option value="">Tous</option>
                                <option value="ACOMPTE" @selected(request('type') === 'ACOMPTE')>Acompte</option>
                                <option value="DEFINITIVE" @selected(request('type') === 'DEFINITIVE')>Définitive</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Statut</label>
                            <select name="statut" class="form-select form-select-sm">
                                <option value="">Tous</option>
                                @foreach ($statuts as $key => $meta)
                                    <option value="{{ $key }}" @selected(request('statut') === $key)>
                                        {{ $meta['label'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-sm btn-primary w-100" title="Rechercher">
                                <i class="bi bi-search me-1"></i> Rechercher
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </div>

        {{-- ============================================================
        TABLEAU
        ============================================================= --}}
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">Notes de frais</h5>
                        <small class="text-muted">Liste des notes correspondant aux critères</small>
                    </div>
                    <span class="badge bg-secondary">{{ $notesFrais->count() }}</span>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 datatables" id="table-notes-frais">
                    <thead class="table-light">
                        <tr>
                            <th>Opérateur</th>
                            <th>Module / Formation</th>
                            <th>Type</th>
                            <th>Total (A)</th>
                            <th>Acompte / Reliquat</th>
                            <th>Statut</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($notesFrais as $note)
                            @php
                                $statutMeta = $statuts[$note->statut] ?? ['label' => $note->statut, 'badge' => 'secondary'];
                            @endphp
                            <tr>
                                <td>
                                    {{ $note->operateur?->user?->display_operateur ?? '—' }}
                                </td>
                                <td>
                                    <a href="{{ route('formations.notes-frais.show', $note) }}" class="text-decoration-none fw-semibold">
                                        {{ $note->formation?->intitule ?? $note->formation?->name }}
                                    </a>
                                    @if ($note->session_label)
                                        <div><small class="text-muted">{{ $note->session_label }}</small></div>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $note->type === 'ACOMPTE' ? 'info' : 'primary' }}">
                                        {{ $note->type === 'ACOMPTE' ? 'Acompte' : 'Définitive' }}
                                    </span>
                                </td>
                                <td>{{ number_format($note->total_frais_operateur, 0, ',', ' ') }} FCFA</td>
                                <td>
                                    @if ($note->type === 'ACOMPTE')
                                        {{ number_format($note->montant_acompte_demande, 0, ',', ' ') }} FCFA
                                    @else
                                        {{ number_format($note->reliquat, 0, ',', ' ') }} FCFA
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $statutMeta['badge'] }} {{ $statutMeta['badge'] === 'warning' ? 'text-dark' : '' }}">
                                        {{ $statutMeta['label'] }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <div class="btn-group">
                                        <a href="{{ route('formations.notes-frais.show', $note) }}"
                                            class="btn btn-sm btn-outline-primary" title="Voir">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        @if ($note->statut === 'BROUILLON')
                                            <a href="{{ route('formations.notes-frais.edit', $note) }}"
                                                class="btn btn-sm btn-outline-secondary" title="Modifier">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        @endif

                                        <a href="{{ route('formations.notes-frais.pdf', $note) }}" target="_blank"
                                            class="btn btn-sm btn-outline-dark" title="PDF">
                                            <i class="bi bi-file-earmark-pdf"></i>
                                        </a>

                                        @if ($note->statut === 'BROUILLON')
                                            <form method="POST" action="{{ route('formations.notes-frais.destroy', $note) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger show_confirm"
                                                    title="Supprimer">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

@endsection

@push('scripts')
    <script>
        new DataTable('#table-notes-frais', {
            layout: {
                topStart: {
                    buttons: ['csv', 'excel', 'print'],
                }
            },
            "order": [
                [0, 'asc']
            ],
            pageLength: 10,
            language: {
                "sProcessing": "Traitement en cours...",
                "sSearch": "Rechercher&nbsp;:",
                "sLengthMenu": "Afficher _MENU_ &eacute;l&eacute;ments",
                "sInfo": "Affichage de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
                "sInfoEmpty": "Affichage de l'&eacute;l&eacute;ment 0 &agrave; 0 sur 0 &eacute;l&eacute;ment",
                "sInfoFiltered": "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
                "sZeroRecords": "Aucune note de frais &agrave; afficher",
                "sEmptyTable": "Aucune donn&eacute;e disponible dans le tableau",
                "oPaginate": {
                    "sFirst": "Premier",
                    "sPrevious": "Pr&eacute;c&eacute;dent",
                    "sNext": "Suivant",
                    "sLast": "Dernier"
                }
            }
        });
    </script>
@endpush
