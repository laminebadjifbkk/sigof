@extends('layout.user-layout')
@section('title', 'Base de données des demandeurs du ' . $projet->type_projet . ' ' . $projet?->sigle)
@section('space-work')
    <div class="pagetitle">
        {{-- <h1>Data Tables</h1> --}}
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Accueil</a></li>
                <li class="breadcrumb-item">Tables</li>
                <li class="breadcrumb-item active">{{ $projet->type_projet . ' ' . $projet?->sigle }}</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="row">
            <!-- Left side columns -->
            <div class="col-12">
                <div class="row">
                    <div class="col-12 col-md-4 col-lg-2 col-sm-12 col-xs-12 col-xxl-2">
                        <div class="card info-card revenue-card shadow-sm" style="max-width: 220px;">
                            <div class="card-body p-2">
                                <h5 class="card-title text-truncate mb-1" title="{{ $projetlocalite?->localite }}"
                                    style="font-size: 1rem;">
                                    {{ $projetlocalite->projet->type_localite . ' | ' . $projetlocalite?->localite }}
                                </h5>
                                <div class="d-flex align-items-center mb-2">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-primary text-white"
                                        style="width: 32px; height: 32px; font-size: 1.25rem;">
                                        <i class="bi bi-people"></i>
                                    </div>
                                    <div class="ps-2">
                                        <h6 class="mb-0" style="font-size: 0.9rem;">{{ count($individuelles) }}</h6>
                                        <span class="text-muted small">demandeurs</span>
                                    </div>
                                </div>

                                <a href="{{ route('projetlocalites.show', $projetlocalite) }}"
                                    class="btn btn-outline-primary btn-sm w-100 d-flex align-items-center justify-content-center py-1"
                                    style="font-size: 0.85rem; gap: 6px;">
                                    Voir plus <i class="bi bi-arrow-right-short"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Sales Card -->
                    @foreach ($groupes as $statut => $items)
                        <div class="col-12 col-md-4 col-lg-2 col-sm-12 col-xs-12 col-xxl-2">
                            <div class="card info-card sales-card shadow-sm" style="max-width: 220px;">
                                <div class="card-body p-2">
                                    <h5 class="card-title text-truncate mb-1" title="{{ $statut }}"
                                        style="font-size: 1rem;">
                                        {{ $statut }}
                                    </h5>
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-primary text-white"
                                            style="width: 32px; height: 32px; font-size: 1.25rem;">
                                            <i class="bi bi-people"></i>
                                        </div>
                                        <div class="ps-2">
                                            <h6 class="mb-0" style="font-size: 0.9rem;">{{ $items->count() }}</h6>
                                            <span class="text-muted small">demandeurs</span>
                                        </div>
                                    </div>

                                    <a href="{{ route('projetlocalites.parStatut', ['statut' => $statut, 'projetlocaliteid' => $projetlocalite->id, 'typelocalite' => $projetlocalite->projet->type_localite, 'localite' => $projetlocalite->localite]) }}"
                                        class="btn btn-outline-primary btn-sm w-100 d-flex align-items-center justify-content-center py-1"
                                        style="font-size: 0.85rem; gap: 6px;">
                                        Voir plus <i class="bi bi-arrow-right-short"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="row">
            <div class="col-12">
                @if ($message = Session::get('status'))
                    <div class="alert alert-success bg-success text-light border-0 alert-dismissible fade show"
                        role="alert">
                        <strong>{{ $message }}</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if ($message = Session::get('danger'))
                    <div class="alert alert-danger bg-danger text-light border-0 alert-dismissible fade show"
                        role="alert">
                        <strong>{{ $message }}</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <div class="alert alert-danger bg-danger text-light border-0 alert-dismissible fade show"
                            role="alert"><strong>{{ $error }}</strong></div>
                    @endforeach
                @endif

                <div class="card">
                    <div class="card-body">
                        <span class="d-flex mt-2 align-items-baseline"><a
                                href="{{ route('projets.show', $projetlocalite->projet) }}" class="btn btn-info btn-sm"
                                title="retour"><i class="bi bi-arrow-counterclockwise"></i></a>&nbsp;
                            <p> | Retour</p>
                        </span>
                        {{-- <h4 class="card-title">
                            Liste des demandeurs :
                            <strong>{{ $projetlocalite->projet->type_localite . ' de ' . $projetlocalite?->localite }}</strong>
                            <span class="badge bg-success text-white">{{ $individuelles->count() }}</span>
                        </h4> --}}
                        @if (!empty($individuelles) && $individuelles->isNotEmpty())
                            <table class="table datatables align-middle" id="table-individuelles">
                                <thead>
                                    <tr>
                                        <th class="text-center">N°</th>
                                        <th class="text-center">CIN</th>
                                        <th>Prénom & NOM</th>
                                        <th>Date naissance</th>
                                        <th>Lieu naissance</th>
                                        <th>Telephone</th>
                                        <th>Module</th>
                                        <th class="text-center">Statut</th>
                                        <th class="text-center">#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; ?>
                                    @foreach ($individuelles as $individuelle)
                                        <tr>
                                            <td style="text-align: center">{{ $individuelle?->numero }}</td>
                                            <td style="text-align: center">{{ $individuelle?->user?->cin }}</td>
                                            <td>{{ $individuelle?->user?->civilite . ' ' . $individuelle?->user?->firstname . ' ' . $individuelle?->user?->name }}
                                            </td>
                                            <td>{{ $individuelle?->user?->date_naissance?->format('d/m/Y') }}</td>
                                            <td>{{ $individuelle?->user?->lieu_naissance }}</td>
                                            <td><a
                                                    href="tel:+221{{ $individuelle?->user?->telephone }}">{{ $individuelle?->user?->telephone }}</a>
                                            </td>
                                            <td>{{ $individuelle?->module?->name }}</td>
                                            <td>
                                                <span class="{{ $individuelle?->statut }}">
                                                    {{ $individuelle?->statut }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="d-flex align-items-baseline"><a
                                                        href="{{ route('individuelles.show', $individuelle) }}"
                                                        target="_blank" class="btn btn-primary btn-sm"
                                                        title="voir détails"><i class="bi bi-eye"></i></a>
                                                    <div class="filter">
                                                        <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                                                class="bi bi-three-dots"></i></a>
                                                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                                            <li><a class="dropdown-item btn btn-sm"
                                                                    href="{{ route('individuelles.edit', $individuelle) }}"
                                                                    class="mx-1" title="Modifier"><i
                                                                        class="bi bi-pencil"></i>Modifier</a>
                                                            </li>
                                                            <li>
                                                                <form
                                                                    action="{{ route('individuelles.destroy', $individuelle) }}"
                                                                    method="post">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit"
                                                                        class="dropdown-item show_confirm"
                                                                        title="Supprimer"><i
                                                                            class="bi bi-trash"></i>Supprimer</button>
                                                                </form>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="alert alert-info text-center text-muted">
                                Aucune donnée disponible.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        new DataTable('#table-individuelles', {
            layout: {
                topStart: {
                    buttons: ['csv', 'excel'],
                }
            },
            "order": [
                [0, 'desc']
            ],
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
    </script>
@endpush
