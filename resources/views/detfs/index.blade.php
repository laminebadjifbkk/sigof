@extends('layout.user-layout')
@section('title', 'ONFP - Liste des DETFS')

@section('space-work')
    <section class="section register">
        <div class="container">
            <div class="row mb-4">
                <!-- Total missions -->
                <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                    <div class="card shadow-sm text-center p-2" style="min-height: 140px; border-radius: 10px;">
                        <h6 class="card-title mb-2 text-truncate missions-title" title="Total missions">
                            DETFS
                        </h6>
                        <div class="d-flex flex-column align-items-center justify-content-center mb-2">
                            <div class="rounded-circle bg-primary text-white d-flex justify-content-center align-items-center mb-1"
                                style="width:36px; height:36px; font-size:1rem;">
                                <i class="bi bi-flag"></i>
                            </div>
                            <span class="h6 mb-0" style="font-size:1rem;">{{ $total }}</span>
                            {{-- <small class="text-muted" style="font-size:0.7rem;">mission(s)</small> --}}
                        </div>

                        <!-- Barre de pourcentage -->
                        <div class="mb-2">
                            <div class="progress" style="height:6px; border-radius:3px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: 100%;"></div>
                            </div>
                            <small class="text-muted">100%</small>
                        </div>

                        <!-- Bouton voir plus -->
                        <a href="{{ route('detfs.index') }}" class="btn btn-outline-primary btn-sm w-100"
                            style="font-size:0.75rem;">
                            Voir plus <i class="bi bi-arrow-right-short"></i>
                        </a>
                    </div>
                </div>

                @foreach ($groupes as $etat => $items)
                    <div class="col-6 col-sm-4 col-md-3 col-lg-2 mb-3">
                        <div class="card shadow-sm text-center p-2" style="min-height: 120px; border-radius: 10px;">

                            <!-- Statut -->
                            <h6 class="card-title mb-2 text-truncate" title="{{ $etat }}"
                                style="font-size: 0.85rem;">
                                {{ ucfirst(str_replace('ee', 'ée', str_replace('_', ' ', $etat))) }}
                            </h6>

                            <!-- Nombre et icône -->
                            <div class="d-flex flex-column align-items-center justify-content-center mb-2">
                                <div class="rounded-circle bg-primary text-white d-flex justify-content-center align-items-center mb-1"
                                    style="width: 36px; height: 36px; font-size: 1rem;">
                                    <i class="bi bi-flag"></i>
                                </div>
                                <span class="h6 mb-0" style="font-size: 1rem;">
                                    {{ $items->count() }}
                                </span>
                                {{-- <small class="text-muted" style="font-size: 0.7rem;">mission(s)</small> --}}
                            </div>

                            <!-- Pourcentage -->
                            <div class="mb-2">
                                <div class="progress" style="height:6px; border-radius:3px;">
                                    <div class="progress-bar bg-success" role="progressbar"
                                        style="width: {{ $statutPourcentages[$etat]['percent'] }}%;"></div>
                                </div>
                                <small class="text-muted">{{ $statutPourcentages[$etat]['percent'] }}%</small>
                            </div>

                            <!-- Bouton voir plus -->
                            <a href="{{ route('detfs.index', ['etat' => $etat]) }}"
                                class="btn btn-outline-primary btn-sm w-100" style="font-size: 0.75rem;">
                                Voir plus <i class="bi bi-arrow-right-short"></i>
                            </a>

                        </div>
                    </div>
                @endforeach
            </div>

            @can('parc-mission-create')
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="mb-0">
                        LISTE DES DETFS
                        <span class="etat-btn">

                            @if (!empty(request('statut')))
                                {{-- Si un statut est présent dans l'URL --}}
                                {{ $labels[request('statut')] ?? ucfirst(str_replace('_', ' ', request('statut'))) }}
                            @elseif(request('annee'))
                                {{-- Si une année est présente dans l'URL --}}
                                de l'année {{ request('annee') }}
                            @endif
                        </span>
                    </h3>
                    <a href="{{ route('detfs.create') }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-plus-circle"></i> Ajouter
                    </a>
                </div>
            @endcan

            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover table-striped shadow-sm" id="table-parc-mission">
                    <thead class="table-dark">
                        <tr>
                            <th>Numéro</th>
                            <th>Intitulé</th>
                            <th>Ingénieur</th>
                            <th>Opérateur</th>
                            <th class="text-center" width="12%">Statut</th>
                            <th class="text-center" width="12%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($detfs as $detf)
                            <tr>
                                <td>{{ $detf->numero }}</td>
                                <td>{{ $detf->titre1 }}</td>
                                <td>
                                    {{ $detf->ingenieur->user->firstname . ' ' . $detf->ingenieur->user->name }}
                                </td>
                                <td>
                                    {{ $detf->operateur->user->username ? $detf->operateur->user->username : $detf->operateur->user->operateur }}
                                </td>
                                <td class="text-center">
                                    <span class="etat-btn {{ $detf->etat }}">
                                        {{ $detf->etat }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="d-flex align-items-baseline justify-content-center gap-1">
                                        <a href="{{ route('detfs.show', $detf->id) }}" class="btn btn-sm btn-info">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('detfs.edit', $detf->id) }}" class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form action="{{ route('detfs.destroy', $detf->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-sm btn-danger show_confirm"
                                                title="Supprimer">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        new DataTable('#table-parc-mission', {
            ordering: false,
            layout: {
                topStart: {
                    buttons: ['csv', 'excel', 'print'],
                }
            },
            language: {
                "sProcessing": "Traitement en cours...",
                "sSearch": "Rechercher&nbsp;:",
                "sLengthMenu": "Afficher _MENU_ éléments",
                "sInfo": "Affichage de l'élément _START_ à _END_ sur _TOTAL_ éléments",
                "sInfoEmpty": "Affichage de l'élément 0 à 0 sur 0 élément",
                "sInfoFiltered": "(filtré de _MAX_ éléments au total)",
                "sLoadingRecords": "Chargement en cours...",
                "sZeroRecords": "Aucun élément à afficher",
                "sEmptyTable": "Aucune donnée disponible dans le tableau",
                "oPaginate": {
                    "sFirst": "Premier",
                    "sPrevious": "Précédent",
                    "sNext": "Suivant",
                    "sLast": "Dernier"
                },
                "oAria": {
                    "sSortAscending": ": activer pour trier la colonne par ordre croissant",
                    "sSortDescending": ": activer pour trier la colonne par ordre décroissant"
                },
                "select": {
                    "rows": {
                        _: "%d lignes sélectionnées",
                        0: "Aucune ligne sélectionnée",
                        1: "1 ligne sélectionnée"
                    }
                }
            }
        });
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.toggle-text').forEach(function(link) {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const td = this.closest('td');
                    const shortText = td.querySelector('.short-text');
                    const fullText = td.querySelector('.full-text');

                    if (fullText.classList.contains('d-none')) {
                        shortText.classList.add('d-none');
                        fullText.classList.remove('d-none');
                        this.textContent = 'voir moins';
                    } else {
                        fullText.classList.add('d-none');
                        shortText.classList.remove('d-none');
                        this.textContent = '...voir plus';
                    }
                });
            });
        });
    </script>
@endpush
