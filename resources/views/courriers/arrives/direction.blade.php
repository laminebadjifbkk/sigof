@extends('layout.user-layout')
@section('title', 'ONFP | COURRIERS ARRIVES')
@section('space-work')

    <div class="pagetitle">
        {{-- <h1>Data Tables</h1> --}}
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Accueil</a></li>
                <li class="breadcrumb-item">Tables</li>
                <li class="breadcrumb-item active">Liste des courriers arrivés</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="table-responsive">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">

                                <div>
                                    <h4 class="mb-0 fw-bold text-primary">
                                        Courriers Arrivés
                                    </h4>
                                    <small class="text-muted">
                                        Gestion et suivi des courriers entrants
                                    </small>
                                </div>

                                <div class="d-flex align-items-center gap-2 text-info fw-semibold">
                                    <i class="bi bi-inboxes"></i>
                                    Affichage :
                                    <span class="text-dark">{{ $affichees }}</span>
                                    sur
                                    <span class="text-dark">{{ $total }}</span>
                                </div>

                                <div class="d-flex gap-2">
                                    <a href="{{ route('courriers.direction') }}"
                                        class="btn btn-outline-success btn-sm rounded-pill">
                                        <i class="bi bi-arrow-left"></i> Retour
                                    </a>

                                    @can('arrive-create')
                                        {{-- <a href="{{ route('arrives.create') }}" class="btn btn-primary btn-sm rounded-pill">
                                            <i class="bi bi-plus-circle"></i> Ajouter
                                        </a> --}}

                                        <button class="btn btn-outline-secondary btn-sm rounded-pill" data-bs-toggle="modal"
                                            data-bs-target="#generate_rapport">
                                            Rechercher
                                        </button>
                                    @endcan
                                </div>

                            </div>
                            <table class="table table-bordered table-striped align-middle">
                                <thead class="table-primary">
                                    <tr>
                                        <th scope="col" style="width: 50px;">N°</th>
                                        <th scope="col">Années</th>
                                        <th scope="col" class="text-center">Courriers reçus</th>
                                        <th scope="col" style="width: 120px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="missions-container">
                                    @foreach ($groupes as $index => $items)
                                        <tr>
                                            <td>
                                                {{ ($groupes->currentPage() - 1) * $groupes->perPage() + $loop->iteration }}
                                            </td>
                                            <td>{{ $items->annee }}</td>
                                            <td class="text-center">{{ number_format($items->total, 0, '', ' ') }}</td>
                                            <td></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Bouton Load More --}}
                        @if ($groupes->hasMorePages())
                            <div class="text-center mt-3">
                                <a href="{{ $groupes->nextPageUrl() }}" id="loadMoreBtn" class="btn btn-info btn-sm">
                                    Voir plus
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        @if ($arrives->isNotEmpty())
                            <div class="table-responsive">
                                <table class="table datatables align-middle" id="table-arrives">
                                    <thead>
                                        <tr>
                                            <th class="text-center" width='8%'>N°</th>
                                            <th>Expéditeur</th>
                                            <th>Objet</th>
                                            <th>Imputation</th>
                                            <th width='2%'>#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($arrives as $arrive)
                                            <tr>
                                                <td class="text-center">{{ $arrive?->numero_arrive }}</td>
                                                <td>{{ $arrive?->courrier?->expediteur }}</td>
                                                <td>{{ $arrive?->courrier?->objet }}</td>
                                                {{-- <td>
                                                    @if ($arrive?->employees && $arrive->employees->isNotEmpty())
                                                        <div class="small">
                                                            @foreach ($arrive->employees as $employee)
                                                                <span class="badge bg-light text-dark border me-1 mb-1">
                                                                    {{ $employee?->user?->firstname . ' ' . $employee?->user?->name }}
                                                                </span>
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        <span class="badge bg-info text-dark">Aucune</span>
                                                    @endif
                                                </td> --}}
                                                <td>
                                                    @if ($arrive?->employees && $arrive->employees->isNotEmpty())
                                                        <button class="btn btn-sm btn-outline-primary"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#employeesModal{{ $arrive->id }}">
                                                            Voir ({{ $arrive->employees->count() }})
                                                        </button>

                                                        {{-- MODAL --}}
                                                        <div class="modal fade" id="employeesModal{{ $arrive->id }}"
                                                            tabindex="-1" aria-hidden="true">
                                                            <div class="modal-dialog modal-md modal-dialog-centered">
                                                                <div class="modal-content">

                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title">
                                                                            Imputations
                                                                        </h5>
                                                                        <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal"></button>
                                                                    </div>

                                                                    <div class="modal-body">

                                                                        @foreach ($arrive->employees as $index => $employee)
                                                                            <div
                                                                                class="d-flex align-items-center justify-content-between py-2 border-bottom">

                                                                                <div
                                                                                    class="d-flex align-items-center gap-2">

                                                                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                                                                        style="width:32px; height:32px; font-size:12px;">
                                                                                        {{ strtoupper(substr($employee?->user?->firstname ?? 'U', 0, 1)) }}
                                                                                    </div>

                                                                                    <div>
                                                                                        <div class="fw-semibold">
                                                                                            {{ $employee?->user?->firstname . ' ' . $employee?->user?->name }}
                                                                                        </div>
                                                                                        <small class="text-muted">
                                                                                            Imputation #{{ $index + 1 }}
                                                                                        </small>
                                                                                    </div>

                                                                                </div>

                                                                                @if ($employee?->fonction?->sigle)
                                                                                    <span class="badge bg-secondary">
                                                                                        {{ $employee->fonction->sigle }}
                                                                                    </span>
                                                                                @endif

                                                                            </div>
                                                                        @endforeach

                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <span class="badge bg-info text-dark">Aucune</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-baseline">
                                                        <a href="{{ route('arrives.showdirection', [
                                                            'idcourrier' => $arrive->id,
                                                            'iddirection' => $direction->id,
                                                        ]) }}"
                                                            class="btn btn-success btn-sm" title="Voir détails">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                        {{-- <div class="filter">
                                                            <a class="icon" href="#" data-bs-toggle="dropdown">
                                                                <i class="bi bi-three-dots"></i>
                                                            </a>
                                                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                                                <li><a class="dropdown-item btn btn-sm"
                                                                        href="{{ route('arrives.edit', $arrive?->id) }}">
                                                                        <i class="bi bi-pencil"></i> Modifier</a></li>
                                                                @can('delete', $arrive)
                                                                    @can('arrive-delete')
                                                                        <li>
                                                                            <form
                                                                                action="{{ route('arrives.destroy', $arrive?->id) }}"
                                                                                method="post">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                                <button type="submit"
                                                                                    class="dropdown-item show_confirm">
                                                                                    <i class="bi bi-trash"></i> Supprimer
                                                                                </button>
                                                                            </form>
                                                                        </li>
                                                                    @endcan
                                                                @endcan
                                                            </ul>
                                                        </div> --}}
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="alert alert-info mt-3">Aucun courrier arrivé enregistré pour le moment !!!</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade" id="generate_rapport" tabindex="-1" role="dialog" aria-labelledby="generate_rapportLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Générer une recherche<span class="text-danger mx-1">*</span></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="post" action="{{ route('arrives.report') }}">
                        @csrf
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="numero" class="form-label">Numero</label>
                                                <input type="text" name="numero" value="{{ old('numero') }}"
                                                    class="form-control form-control-sm @error('numero') is-invalid @enderror"
                                                    id="numero" placeholder="Numero">
                                                @error('numero')
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="objet" class="form-label">Objet</label>
                                                <input type="text" name="objet" value="{{ old('objet') }}"
                                                    class="form-control form-control-sm @error('objet') is-invalid @enderror"
                                                    id="objet" placeholder="Objet">
                                                @error('objet')
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="expediteur" class="form-label">Expéditeur</label>
                                                <input type="text" name="expediteur" value="{{ old('expediteur') }}"
                                                    class="form-control form-control-sm @error('expediteur') is-invalid @enderror"
                                                    id="expediteur" placeholder="Expéditeur">
                                                @error('expediteur')
                                                    <span class="invalid-feedback" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary btn-sm"
                                        data-bs-dismiss="modal">Fermer</button>
                                    <div class="text-center">
                                        <button type="submit"
                                            class="btn btn-primary btn-block submit_rapport btn-sm">Rechercher</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

@endsection
@push('scripts')
    <script>
        new DataTable('#table-arrives', {
            ordering: false,
            layout: {
                topStart: {
                    buttons: ['csv', 'excel', 'print'],
                }
            },
            language: {
                "sProcessing": "Traitement en cours...",
                "sSearch": "Rechercher&nbsp;:",
                "sLengthMenu": "Afficher _MENU_ &eacute;l&eacute;ments",
                "sInfo": "Affichage de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
                "sInfoEmpty": "Affichage de l'&eacute;l&eacute;ment 0 &agrave; 0 sur 0 &eacute;l&eacute;ment",
                "sInfoFiltered": "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
                "sInfoPostFix": "",
                "sLoadingRecords": "Chargement en cours...",
                "sZeroRecords": "Aucun &eacute;l&eacute;ment &agrave; afficher",
                "sEmptyTable": "Aucune donn&eacute;e disponible dans le tableau",
                "oPaginate": {
                    "sFirst": "Premier",
                    "sPrevious": "Pr&eacute;c&eacute;dent",
                    "sNext": "Suivant",
                    "sLast": "Dernier"
                },
                "oAria": {
                    "sSortAscending": ": activer pour trier la colonne par ordre croissant",
                    "sSortDescending": ": activer pour trier la colonne par ordre d&eacute;croissant"
                },
                "select": {
                    "rows": {
                        _: "%d lignes sÃ©lÃ©ctionnÃ©es",
                        0: "Aucune ligne sÃ©lÃ©ctionnÃ©e",
                        1: "1 ligne sÃ©lÃ©ctionnÃ©e"
                    }
                }
            }
        });

        document.addEventListener('DOMContentLoaded', function() {

            const loadMoreBtn = document.getElementById('loadMoreBtn');
            const missionsContainer = document.getElementById('missions-container');

            if (!loadMoreBtn) return;

            loadMoreBtn.addEventListener('click', function(e) {
                e.preventDefault();

                fetch(this.href)
                    .then(res => res.text())
                    .then(html => {

                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');

                        const newRows = doc.querySelectorAll('#missions-container tr');

                        newRows.forEach(row => {
                            missionsContainer.appendChild(row);
                        });

                        const newBtn = doc.getElementById('loadMoreBtn');

                        if (newBtn) {
                            this.href = newBtn.href;
                        } else {
                            this.remove();
                        }
                    })
                    .catch(err => console.error('Erreur chargement :', err));
            });
        });
    </script>
@endpush
