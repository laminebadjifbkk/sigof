@extends('layout.user-layout')
@section('title', 'ONFP', ' | Liste des employés ayant la fonction ' . $fonction->name)
@section('space-work')
    <div class="pagetitle">
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/home') }}">Accueil</a></li>
                <li class="breadcrumb-item">Tables</li>
                <li class="breadcrumb-item active">Données</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
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
                        <div class="row">
                            <div class="col-sm-12 pt-0">
                                <span class="d-flex mt-2 align-items-baseline"><a href="{{ route('fonctions.index') }}"
                                        class="btn btn-success btn-sm" title="retour"><i
                                            class="bi bi-arrow-counterclockwise"></i></a>&nbsp;
                                    <p> | retour</p>
                                </span>
                            </div>
                        </div>
                        <h5 class="card-title">Liste des employés ayant la fonction de : {{ $fonction?->name }}</h5>
                        @if ($fonction->employees->isNotEmpty())
                            <table class="table datatables align-middle" id="table-employes">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Matricule</th>
                                        <th>Prénom & NOM</th>
                                        <th>E-mail</th>
                                        <th>Téléphone</th>
                                        <th>Direction</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; ?>
                                    @foreach ($fonction->employees as $employe)
                                        <tr>
                                            <th scope="row">
                                                <a href="#" data-bs-toggle="modal"
                                                    data-bs-target="#ShowIMG{{ $employe?->user?->id }}">
                                                    <img class="rounded-circle w-40" alt="Profil"
                                                        src="{{ asset($employe?->user?->getImage()) }}" width="40"
                                                        height="auto">
                                                </a>
                                            </th>
                                            {{-- <td>{{ $i++ }}</td> --}}
                                            <td>{{ $employe?->matricule }}</td>
                                            <td>{{ implode(' ', array_filter([$employe?->user?->civilite, $employe?->user?->firstname, $employe?->user?->name])) }}
                                            </td>
                                            <td><a
                                                    href="mailto:{{ $employe?->user?->email }}">{{ $employe?->user?->email }}</a>
                                            </td>
                                            <td><a
                                                    href="tel:+221{{ $employe?->user?->telephone }}">{{ $employe?->user?->telephone }}</a>
                                            </td>
                                            <td>{{ $employe?->direction?->name }}</td>
                                            <td>

                                                @can('employe-show')
                                                    <span class="d-flex mt-2 align-items-baseline"><a
                                                            href="{{ route('employes.show', $employe->id) }}"
                                                            class="btn btn-success btn-sm mx-1" title="voir détails"><i
                                                                class="bi bi-eye"></i></a>
                                                        <div class="filter">
                                                            <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                                                    class="bi bi-three-dots"></i></a>
                                                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">

                                                                @can('employe-update')
                                                                    <li><a class="dropdown-item btn btn-sm mx-1"
                                                                            href="{{ route('employes.edit', $employe?->id) }}"
                                                                            class="mx-1"><i class="bi bi-pencil"></i> Modifier</a>
                                                                    </li>
                                                                @endcan

                                                                @can('employe-delete')
                                                                    <li>
                                                                        <form
                                                                            action="{{ route('employes.destroy', $employe?->id) }}"
                                                                            method="post">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="submit"
                                                                                class="dropdown-item show_confirm"><i
                                                                                    class="bi bi-trash"></i>Supprimer</button>
                                                                        </form>
                                                                    </li>
                                                                @endcan
                                                            </ul>
                                                        </div>
                                                    </span>
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                            <!-- End Table with stripped rows -->
                        @else
                            <div class="alert alert-info">Aucun employé dans cette direction pour l'instant !</div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
        @foreach ($fonction->employees as $employe)
            <div class="modal fade" id="ShowIMG{{ $employe?->user?->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header flex-column align-items-center text-center border-bottom-0">
                            <h2 class="h5 fw-bold mb-1">
                                {{ $employe?->user?->firstname . ' ' . $employe?->user?->name }}
                            </h2>
                            <p class="text-muted small mb-0">
                                {{ $employe?->fonction?->name }}
                            </p>
                        </div>
                        <div class="modal-body">
                            <div class="col-12">
                                <img src="{{ asset($employe?->user?->getImage() ?? 'images/default.png') }}"
                                    class="d-block w-100 rounded-4"
                                    alt="{{ $employe?->user?->civilite }} {{ $employe?->user?->firstname }} {{ $employe?->user?->name ?? 'Photo de profil' }}">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Fermer</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </section>

@endsection
@push('scripts')
    <script>
        new DataTable('#table-employes', {
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
