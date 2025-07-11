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
                        @can('individuelle-create')
                            {{-- <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="card-title mb-0">{{ $title }}</h5>
                                <a href="#" class="btn btn-sm btn-primary d-flex align-items-center gap-1"
                                    data-bs-toggle="modal" data-bs-target="#generate_rapport" title="Ajouter">
                                    <i class="bi bi-search"></i> Rechercher plus
                                </a>
                            </div> --}}
                            <div class="pt-1">
                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">

                                    {{-- Titre à gauche --}}
                                    <div class="d-flex align-items-center gap-2">
                                        <h6 class="mb-0 text-muted fw-semibold text-uppercase">
                                            Liste des demandeurs individuelles
                                        </h6>
                                    </div>

                                    {{-- Total au centre --}}
                                    @php
                                        $affichees = $demandeurs->count(); // à adapter si tu fais une pagination
                                        $total = $totalIndividuelles ?? ($demandeurs->total() ?? $demandeurs->count()); // en cas de pagination avec ->total()
                                    @endphp

                                    <div class="d-flex align-items-center gap-2 text-info fw-semibold">
                                        <i class="bi bi-list-ul me-1"></i>
                                        <span>
                                            Affichage :
                                            <span class="text-dark">{{ $affichees }}</span>
                                            sur
                                            <span class="text-dark">{{ $total }}</span> demandeurs
                                        </span>
                                    </div>

                                    {{-- Boutons à droite --}}
                                    @can('individuelle-create')
                                        <div class="d-flex align-items-center gap-2">
                                            {{-- <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#AddIndividuelModal">
                                                Ajouter
                                            </a> --}}
                                            <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="modal"
                                                data-bs-target="#generate_rapport">
                                                Rechercher plus
                                            </button>
                                        </div>
                                    @endcan

                                </div>
                            </div>
                        @endcan
                        @if ($demandeurs->isNotEmpty())
                            <table class="table datatables align-middle" id="table-users">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Prenom</th>
                                        <th>NOM</th>
                                        <th>E-mail</th>
                                        <th>Téléphone</th>
                                        <th class="text-center">Demandes</th>
                                        <th width="5%">#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; ?>
                                    @foreach ($demandeurs as $user)
                                        @if ($user->individuelles->isNotEmpty())
                                            <tr>
                                                <th scope="row">
                                                    <a href="{{ route('users.show', $user) }}">
                                                        <img class="rounded-circle w-20" alt="Profil"
                                                            src="{{ asset($user->getImage()) }}" width="40"
                                                            height="auto">
                                                    </a>
                                                </th>
                                                <td>{{ $user?->firstname }}</td>
                                                <td>{{ $user?->name }}</td>
                                                <td><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></td>
                                                <td><a href="tel:+221{{ $user->telephone }}">{{ $user->telephone }}</a>
                                                </td>
                                                <td style="text-align: center;">
                                                    {{ $user?->individuelles?->count() ?? 0 }}
                                                </td>
                                                <td>
                                                    {{-- <span class="d-flex mt-2 align-items-baseline">
                                                        <a href="{{ route('demandeurs.show', $user) }}"
                                                            class="btn btn-info btn-sm mx-1 text-white"
                                                            title="Voir détails">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                    </span> --}}
                                                    <form action="{{ route('demandeurs.show', $user->uuid) }}"
                                                        method="GET">
                                                        @csrf
                                                        <input type="hidden" name="idUser" value="{{ $user->id }}">
                                                        <button type="submit" class="btn btn-info btn-sm mx-1 text-white"
                                                            title="Voir détails">
                                                            <i class="bi bi-eye"></i>
                                                        </button>
                                                    </form>
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
        <div class="modal fade" id="generate_rapport" tabindex="-1" role="dialog" aria-labelledby="generate_rapportLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Générer une recherche<span class="text-danger mx-1">*</span></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                    <form method="post" action="{{ route('demandeurs.report') }}">
                        @csrf
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="firstname" class="form-label">Prénom</label>
                                                <input type="text" name="firstname" value="{{ old('firstname') }}"
                                                    class="form-control form-control-sm @error('firstname') is-invalid @enderror"
                                                    id="firstname" placeholder="Prénom">
                                                @error('firstname')
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
                                                <label for="name" class="form-label">Nom</label>
                                                <input type="text" name="name" value="{{ old('name') }}"
                                                    class="form-control form-control-sm @error('name') is-invalid @enderror"
                                                    id="name" placeholder="Nom">
                                                @error('name')
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
                                                <label for="cin" class="form-label">N° CIN</label>
                                                <input minlength="5" maxlength="15" type="text" name="cin"
                                                    value="{{ old('cin') }}"
                                                    class="form-control form-control-sm @error('cin') is-invalid @enderror"
                                                    id="cin" placeholder="Numéro demande">
                                                @error('cin')
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
                                                <label for="telephone_responsable" class="form-label">Téléphone</label>
                                                <input name="telephone_responsable" type="text" maxlength="12"
                                                    class="form-control form-control-sm @error('telephone_responsable') is-invalid @enderror"
                                                    id="telephone_responsable" value="{{ old('telephone_responsable') }}"
                                                    autocomplete="tel" placeholder="XX:XXX:XX:XX">
                                                @error('telephone_responsable')
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
                                                <label for="email" class="form-label">Email</label>
                                                <input type="email" name="email" value="{{ old('email') }}"
                                                    class="form-control form-control-sm @error('email') is-invalid @enderror"
                                                    id="email" placeholder="email@email.com">
                                                @error('email')
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
