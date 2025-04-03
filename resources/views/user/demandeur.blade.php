@extends('layout.user-layout')
@section('title', 'ONFP | DEMANDEURS')
@section('space-work')
    <div class="pagetitle">
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/home') }}">Accueil</a></li>
                <li class="breadcrumb-item">Tables</li>
                <li class="breadcrumb-item active">Données</li>
            </ol>
        </nav>
    </div>
    <section class="section dashboard">
        <div class="row">
            <!-- Left side columns -->
            <div class="col-lg-12">
                <div class="row">
                    @php
                        $totalCollectif = 0;
                        foreach ($user_liste as $user) {
                            if (!empty($user->collectives) && $user->collectives->isNotEmpty()) {
                                $totalCollectif += $user->collectives->count();
                            }
                        }
                    @endphp
                    <!-- Sales Card -->
                    <div class="col-12 col-md-6 col-lg-3 col-sm-12 col-xs-12 col-xxl-3">
                        <div class="card info-card sales-card">
                            <div class="filter">
                                <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                        class="bi bi-three-dots"></i></a>
                            </div>
                            <a href="#">
                                <div class="card-body">
                                    <h5 class="card-title">Demandeurs <span>| collectifs</span></h5>
                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-file-earmark-text"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6>
                                                <span class="text-primary">{{ $totalCollectif ?? '0' }}</span>
                                            </h6>
                                            {{-- <span class="text-success small pt-1 fw-bold">Toutes</span> --}}
                                            {{-- <span class="text-muted small pt-2 ps-1">increase</span> --}}
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

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
                        <h5 class="card-title">Demandeurs individuels</h5>
                        @if ($user_liste->isNotEmpty())
                            <table class="table datatables align-middle" id="table-users">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Prenom et NOM</th>
                                        <th>E-mail</th>
                                        <th>Téléphone</th>
                                        <th class="text-center">Demandes</th>
                                        <th width="5%">#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; ?>
                                    @foreach ($user_liste as $user)
                                        @if ($user->individuelles->isNotEmpty())
                                            <tr>
                                                <th scope="row">
                                                    <a href="{{ route('users.show', $user->id) }}">
                                                        <img class="rounded-circle w-20" alt="Profil"
                                                            src="{{ asset($user->getImage()) }}" width="40"
                                                            height="auto">
                                                    </a>
                                                </th>
                                                <td>{{ $user?->firstname . ' ' . $user?->name }}</td>
                                                <td><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></td>
                                                <td><a href="tel:+221{{ $user->telephone }}">{{ $user->telephone }}</a>
                                                </td>
                                                <td style="text-align: center;">
                                                    {{ $user?->individuelles?->count() ?? 0 }}
                                                </td>
                                                <td>
                                                    <span class="d-flex mt-2 align-items-baseline">
                                                        <a href="{{ route('demandeurs.show', $user->id) }}"
                                                            class="btn btn-info btn-sm mx-1 text-white"
                                                            title="Voir détails">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                    </span>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="alert alert-info mt-3">Aucun demandeur pour le moment !!!</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script>
        new DataTable('#table-users', {
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
    </script>
@endpush
