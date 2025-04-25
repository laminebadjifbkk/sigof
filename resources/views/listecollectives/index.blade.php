@extends('layout.user-layout')
@section('title', 'ONFP | DEMANDEURS COLLECTIFS')
@section('space-work')
    @can('collective-view')
        <div class="pagetitle">
            {{-- <h1>Data Tables</h1> --}}
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/home') }}">Accueil</a></li>
                    <li class="breadcrumb-item">Tables</li>
                    <li class="breadcrumb-item active">Demandes collectives</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->
        <section class="section">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12 col-sm-12 col-xs-12 col-xxl-12">
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
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title">{{ $title }}</h5>
                                <span class="d-flex align-items-baseline">
                                    <a href="#" class="btn btn-primary btn-sm float-end" data-bs-toggle="modal"
                                        data-bs-target="#searchCollective" title="Rechercher">Rechercher plus</a>
                                    {{-- <div class="filter">
                                        <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                                class="bi bi-three-dots"></i></a>
                                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                            <li>
                                                <button type="button" class="dropdown-item btn btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#generate_rapport">Rechercher
                                                    plus</button>
                                            </li>
                                        </ul>
                                    </div> --}}
                                </span>
                            </div>
                            @if ($listecollectives?->isNotEmpty())
                                <table class="table datatables align-middle" id="table-listecollectives">
                                    <thead>
                                        <tr>
                                            <th class="text-center">N°</th>
                                            <th class="text-center">N° CIN (NIN)</th>
                                            <th>Prénom & NOM</th>
                                            <th>Date nais.</th>
                                            <th>Lieu nais.</th>
                                            {{-- <th>Module</th> --}}
                                            <th>Structure</th>
                                            <th class="text-center">Dépôt</th>
                                            <th class="text-center">Statut</th>
                                            <th width="5%" class="text-center">#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1; ?>
                                        @foreach ($listecollectives as $listecollective)
                                            <tr>
                                                <td style="text-align: center">{{ $i++ }}</td>
                                                <td style="text-align: center">{{ $listecollective?->cin }}</td>
                                                <td>{{ $listecollective?->prenom . ' ' . $listecollective?->nom }}
                                                </td>
                                                <td>{{ $listecollective?->date_naissance?->format('d/m/Y') }}</td>
                                                <td>{{ $listecollective?->lieu_naissance }}</td>
                                                {{-- <td>{{ $listecollective?->collectivemodule?->module }}</td> --}}
                                                <td>
                                                    @if ($listecollective->collective)
                                                        <a href="{{ route('collectives.show', $listecollective->collective) }}"
                                                            title="voir" target="_blank">
                                                            {{ $listecollective->collective->sigle }}
                                                        </a>
                                                    @else
                                                        <span>Aucun</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if ($listecollective?->created_at)
                                                        {{ $listecollective?->created_at ? \Carbon\Carbon::parse($listecollective?->created_at)->format('d/m/Y') : 'Aucun' }}
                                                    @else
                                                        Aucun
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="{{ $listecollective?->statut }}">
                                                        {{ $listecollective?->statut }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="d-flex align-items-baseline"><a
                                                            href="{{ route('listecollectives.show', $listecollective) }}"
                                                            class="btn btn-primary btn-sm" title="voir détails"><i
                                                                class="bi bi-eye"></i></a>
                                                        <div class="filter">
                                                            <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                                                    class="bi bi-three-dots"></i></a>
                                                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                                                {{-- @can('listecollective-update') --}}
                                                                <li><a class="dropdown-item btn btn-sm"
                                                                        href="{{ route('listecollectives.edit', $listecollective) }}"
                                                                        class="mx-1" title="Modifier"><i
                                                                            class="bi bi-pencil"></i>Modifier</a>
                                                                </li>
                                                                {{-- @endcan
                                                                @can('listecollective-delete') --}}
                                                                <li>
                                                                    <form
                                                                        action="{{ route('listecollectives.destroy', $listecollective) }}"
                                                                        method="post">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit"
                                                                            class="dropdown-item show_confirm"
                                                                            title="Supprimer"><i
                                                                                class="bi bi-trash"></i>Supprimer</button>
                                                                    </form>
                                                                </li>
                                                                {{-- @endcan --}}
                                                            </ul>
                                                        </div>
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="alert alert-info">Aucune demande collective reçue pour l'instant !</div>
                            @endif

                        </div>
                    </div>

                </div>
            </div>
            <div class="modal fade" id="searchCollective" tabindex="-1" role="dialog" aria-labelledby="searchCollectiveLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Générer une recherche<span class="text-danger mx-1">*</span></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form method="post" action="{{ route('listecollectives.report') }}">
                            @csrf
                            <div class="modal-body">
                                <div class="row g-3">
                                    <div class="col-12 col-md-12 col-lg-12 col-sm-12 col-xs-12 col-xxl-12">
                                        <div class="row">
                                            <div class="col-12 col-md-12 col-lg-12 col-sm-12 col-xs-12 col-xxl-12">
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
                                    <div class="col-12 col-md-12 col-lg-12 col-sm-12 col-xs-12 col-xxl-12">
                                        <div class="row">
                                            <div class="col-12 col-md-12 col-lg-12 col-sm-12 col-xs-12 col-xxl-12">
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
                                    <div class="col-12 col-md-12 col-lg-12 col-sm-12 col-xs-12 col-xxl-12">
                                        <div class="row">
                                            <div class="col-12 col-md-12 col-lg-12 col-sm-12 col-xs-12 col-xxl-12">
                                                <div class="form-group">
                                                    <label for="cin" class="form-label">N° CIN</label>
                                                    <input name="cin" type="text"
                                                        class="form-control form-control-sm @error('cin') is-invalid @enderror"
                                                        id="cin2" value="{{ old('cin') }}" autocomplete="off"
                                                        placeholder="Ex: 1 099 2005 00012" minlength="16" maxlength="17">
                                                    @error('cin')
                                                        <span class="invalid-feedback" role="alert">
                                                            <div>{{ $message }}</div>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-12 col-lg-12 col-sm-12 col-xs-12 col-xxl-12">
                                        <div class="row">
                                            <div class="col-12 col-md-12 col-lg-12 col-sm-12 col-xs-12 col-xxl-12">
                                                <div class="form-group">
                                                    <label for="telephone" class="form-label">Téléphone</label>
                                                    <input name="telephone" type="text" maxlength="12"
                                                        class="form-control form-control-sm @error('telephone') is-invalid @enderror"
                                                        id="telephone_responsable" value="{{ old('telephone') }}"
                                                        autocomplete="tel" placeholder="XX:XXX:XX:XX">
                                                    @error('telephone')
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
    @endcan
@endsection
@push('scripts')
    <script>
        new DataTable('#table-listecollectives', {
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
