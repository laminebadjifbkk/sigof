@extends('layout.user-layout')
@section('title', 'ONFP | Formations ' . $onfpevaluateur?->name)
@section('space-work')

    <div class="pagetitle">
        {{-- <h1>Data Tables</h1> --}}
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
                            role="alert">{{ $error }}</div>
                    @endforeach
                @endif
                <div class="card">
                    <div class="card-body">
                        <span class="d-flex align-items-baseline"><a href="{{ route('onfpevaluateurs.index') }}"
                                class="btn btn-success btn-sm" title="retour"><i
                                    class="bi bi-arrow-counterclockwise"></i></a>&nbsp;
                            <p> | retour</p>
                        </span>
                        <h5 class="card-title">Liste des formations de {{ $onfpevaluateur->name }}</h5>
                        @if ($onfpevaluateur->formations->isNotEmpty())
                            <table class="table datatables" id="table-formations">
                                <thead>
                                    <tr>
                                        <th width="5%" class="text-center">Code</th>
                                        <th>Type</th>
                                        <th>Intitulé formation</th>
                                        <th>Localité</th>
                                        <th>Modules</th>
                                        <th class="text-center">Statut</th>
                                        <th width="2%">#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; ?>
                                    @foreach ($onfpevaluateur->formations as $formation)
                                        <tr>
                                            <td>{{ $formation?->code }}</td>
                                            <td><a href="#">{{ $formation->types_formation?->name }}</a></td>
                                            <td>{{ $formation?->name }}</td>
                                            <td>{{ $formation->departement?->region?->nom }}</td>
                                            <td>
                                                @isset($formation?->module?->name)
                                                    {{ $formation?->module?->name }}
                                                @endisset
                                                @isset($formation?->collectivemodule?->module)
                                                    {{ $formation?->collectivemodule?->module }}
                                                @endisset
                                            </td>
                                            <td class="text-center"><a href="#"><span
                                                        class="{{ $formation?->statut }}">{{ $formation?->statut }}</span></a>
                                            </td>
                                            <td>
                                                <span class="d-flex align-items-baseline"><a
                                                        href="{{ route('formations.show', $formation->id) }}"
                                                        class="btn btn-primary btn-sm" title="voir détails"><i
                                                            class="bi bi-eye"></i></a>
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="alert alert-info">Aucune information pour le momement !</div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </section>

@endsection
@push('scripts')
    <script>
        new DataTable('#table-formations', {
            layout: {
                topStart: {
                    buttons: ['csv', 'excel', 'print'],
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
