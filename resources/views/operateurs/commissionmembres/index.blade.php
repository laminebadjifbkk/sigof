@extends('layout.user-layout')
@section('title', 'ONFP | MEMBRES DU JURY')
@section('space-work')
    @can('ingenieur-view')
        <section class="section register">
            <div class="row justify-content-center">
                <div class="col-12">
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
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="card-title mb-0">Membres du jury</h5>
                                <button type="button" class="btn btn-primary btn-sm btn-rounded" data-bs-toggle="modal"
                                    data-bs-target="#AddmembresjuryModal">
                                    <i class="bi bi-person-plus"></i> Ajouter
                                </button>
                            </div>
                            <div class="table-responsive">
                                <table class="table datatables table-bordered table-hover align-middle justify-content-center" id="table-jury">
                                    <thead class="table-primary text-center">
                                        <tr>
                                            <th width="8%">Civilité</th>
                                            <th>Prénom</th>
                                            <th>Nom</th>
                                            <th>Fonction</th>
                                            <th>Structure</th>
                                            <th>Email</th>
                                            <th>Téléphone</th>
                                            <th>Jury</th>
                                            <th width="8%">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($membres as $membre)
                                            <tr>
                                                <td class="text-center">{{ $membre->civilite }}</td>
                                                <td>{{ $membre->prenom }}</td>
                                                <td>{{ $membre->nom }}</td>
                                                <td>{{ $membre->fonction }}</td>
                                                <td>{{ $membre->structure }}</td>
                                                <td>
                                                    <a href="mailto:{{ $membre->email }}" class="text-decoration-none">
                                                        <i class="bi bi-envelope-fill text-primary"></i> {{ $membre->email }}
                                                    </a>
                                                </td>
                                                <td class="text-center">
                                                    <a href="tel:+221{{ $membre->telephone }}" class="text-decoration-none">
                                                        <i class="bi bi-telephone-fill text-success"></i>
                                                        {{ $membre->telephone }}
                                                    </a>
                                                </td>
                                                <td class="text-center">
                                                    <span
                                                        class="badge bg-primary">{{ count($membre->commissionagrements) }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group">
                                                        <a href="{{ route('commissionmembres.show', $membre->id) }}"
                                                            class="btn btn-warning btn-sm" title="Voir détails">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                        <button type="button" class="btn btn-secondary btn-sm"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#EditmembreModal{{ $membre->id }}"
                                                            title="Modifier">
                                                            <i class="bi bi-pencil"></i>
                                                        </button>
                                                        <form action="{{ route('commissionmembres.destroy', $membre->id) }}"
                                                            method="post" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm show_confirm"
                                                                title="Supprimer">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add membre -->
            <div class="modal fade" id="AddmembresjuryModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form method="post" action="{{ route('commissionmembres.store') }}" enctype="multipart/form-data"
                            class="row g-3">
                            @csrf

                            <div class="card-header text-center bg-gradient-default">
                                <h1 class="h4 text-black mb-0">AJOUTER MEMBRE</h1>
                            </div>
                            <div class="modal-body">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label for="civilite" class="form-label">Civilité<span
                                                class="text-danger mx-1">*</span></label>
                                        <select name="civilite"
                                            class="form-select form-select-sm @error('civilite') is-invalid @enderror"
                                            aria-label="Select" id="civilite" data-placeholder="Choisir civilité">
                                            <option value="{{ old('civilite') }}">
                                                {{ old('civilite') }}
                                            </option>
                                            <option value="M.">
                                                M.
                                            </option>
                                            <option value="Mme">
                                                Mme
                                            </option>
                                        </select>
                                        @error('civilite')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <label for="prenom" class="form-label">Prénom<span
                                                class="text-danger mx-1">*</span></label>
                                        <input type="text" name="prenom" value="{{ old('prenom') }}"
                                            class="form-control form-control-sm @error('prenom') is-invalid @enderror"
                                            id="prenom" placeholder="prenom">
                                        @error('prenom')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <label for="nom" class="form-label">Nom<span
                                                class="text-danger mx-1">*</span></label>
                                        <input type="text" name="nom" value="{{ old('nom') }}"
                                            class="form-control form-control-sm @error('nom') is-invalid @enderror"
                                            id="nom" placeholder="nom">
                                        @error('nom')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <label for="fonction" class="form-label">Fonction<span
                                                class="text-danger mx-1">*</span></label>
                                        <input type="text" name="fonction" value="{{ old('fonction') }}"
                                            class="form-control form-control-sm @error('fonction') is-invalid @enderror"
                                            id="fonction" placeholder="fonction">
                                        @error('fonction')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <label for="structure" class="form-label">Structure<span
                                                class="text-danger mx-1">*</span></label>
                                        <input type="text" name="structure" value="{{ old('structure') }}"
                                            class="form-control form-control-sm @error('structure') is-invalid @enderror"
                                            id="structure" placeholder="structure">
                                        @error('structure')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <label for="Email" class="form-label">email<span
                                                class="text-danger mx-1">*</span></label>
                                        <input type="email" name="email" value="{{ old('email') }}"
                                            class="form-control form-control-sm @error('email') is-invalid @enderror"
                                            id="email" placeholder="email">
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <label for="telephone" class="form-label">Téléphone<span
                                                class="text-danger mx-1">*</span></label>
                                        <input name="telephone" type="text" maxlength="12"
                                            class="form-control form-control-sm @error('telephone') is-invalid @enderror"
                                            id="telephone" value="{{ old('telephone') }}" autocomplete="tel"
                                            placeholder="XX:XXX:XX:XX">
                                        @error('telephone')
                                            <span class="invalid-feedback" role="alert">
                                                <div>{{ $message }}</div>
                                            </span>
                                        @enderror
                                    </div>

                                </div>
                                <div class="modal-footer mt-5">
                                    <button type="button" class="btn btn-secondary btn-sm"
                                        data-bs-dismiss="modal">Fermer</button>
                                    <button type="submit" class="btn btn-primary btn-sm">Ajouter</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- End Add membre-->

            <!-- Edit membre -->
            @foreach ($membres as $membre)
                <div class="modal fade" id="EditmembreModal{{ $membre->id }}" tabindex="-1" role="dialog"
                    aria-labelledby="EditmembreModalLabel{{ $membre->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="post" action="{{ route('commissionmembres.update', $membre->id) }}"
                                enctype="multipart/form-data" class="row g-3">
                                @csrf
                                @method('patch')
                                <div class="card-header text-center bg-gradient-default">
                                    <h1 class="h4 text-black mb-0">Modifier membre</h1>
                                </div>
                                <input type="hidden" name="id" value="{{ $membre->id }}">
                                <div class="modal-body">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label for="civilite" class="form-label">Civilité<span
                                                    class="text-danger mx-1">*</span></label>
                                            <select name="civilite"
                                                class="form-select form-select-sm @error('civilite') is-invalid @enderror"
                                                aria-label="Select" id="civiliteMembre" data-placeholder="Choisir civilité">
                                                <option value="{{ old('civilite', $membre->civilite ?? '') }}">
                                                    {{ old('civilite', $membre->civilite ?? '') }}</option>
                                                <option value="M.">
                                                    M.
                                                </option>
                                                <option value="Mme">
                                                    Mme
                                                </option>
                                            </select>
                                            @error('civilite')
                                                <span class="invalid-feedback" role="alert">
                                                    <div>{{ $message }}</div>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="col-12">
                                            <label for="prenom" class="form-label">Prénom<span
                                                    class="text-danger mx-1">*</span></label>
                                            <input type="text" name="prenom"
                                                value="{{ old('prenom', $membre->prenom ?? '') }}"
                                                class="form-control form-control-sm @error('prenom') is-invalid @enderror"
                                                id="prenom" placeholder="prenom">
                                            @error('prenom')
                                                <span class="invalid-feedback" role="alert">
                                                    <div>{{ $message }}</div>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="col-12">
                                            <label for="nom" class="form-label">Nom<span
                                                    class="text-danger mx-1">*</span></label>
                                            <input type="text" name="nom"
                                                value="{{ old('nom', $membre->nom ?? '') }}"
                                                class="form-control form-control-sm @error('nom') is-invalid @enderror"
                                                id="nom" placeholder="nom">
                                            @error('nom')
                                                <span class="invalid-feedback" role="alert">
                                                    <div>{{ $message }}</div>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="col-12">
                                            <label for="fonction" class="form-label">Fonction<span
                                                    class="text-danger mx-1">*</span></label>
                                            <input type="text" name="fonction"
                                                value="{{ old('fonction', $membre->fonction ?? '') }}"
                                                class="form-control form-control-sm @error('fonction') is-invalid @enderror"
                                                id="fonction" placeholder="fonction">
                                            @error('fonction')
                                                <span class="invalid-feedback" role="alert">
                                                    <div>{{ $message }}</div>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="col-12">
                                            <label for="structure" class="form-label">Structure<span
                                                    class="text-danger mx-1">*</span></label>
                                            <input type="text" name="structure"
                                                value="{{ old('structure', $membre->structure ?? '') }}"
                                                class="form-control form-control-sm @error('structure') is-invalid @enderror"
                                                id="structure" placeholder="structure">
                                            @error('structure')
                                                <span class="invalid-feedback" role="alert">
                                                    <div>{{ $message }}</div>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="col-12">
                                            <label for="Email" class="form-label">email<span
                                                    class="text-danger mx-1">*</span></label>
                                            <input type="email" name="email"
                                                value="{{ old('email', $membre->email ?? '') }}"
                                                class="form-control form-control-sm @error('email') is-invalid @enderror"
                                                id="email" placeholder="email">
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <div>{{ $message }}</div>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="col-12">
                                            <label for="telephone" class="form-label">Téléphone<span
                                                    class="text-danger mx-1">*</span></label>
                                            <input name="telephone" type="text" maxlength="12"
                                                class="form-control form-control-sm @error('telephone') is-invalid @enderror"
                                                id="telephone_secondaire" value="{{ old('telephone', $membre->telephone ?? '') }}"
                                                autocomplete="tel" placeholder="XX:XXX:XX:XX">
                                            @error('telephone')
                                                <span class="invalid-feedback" role="alert">
                                                    <div>{{ $message }}</div>
                                                </span>
                                            @enderror
                                        </div>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary btn-sm"
                                            data-bs-dismiss="modal">Fermer</button>
                                        <button type="submit" class="btn btn-primary btn-sm">Modifier</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
            <!-- End Edit membre-->
        </section>
    @endcan
@endsection

@push('scripts')
    <script>
        new DataTable('#table-jury', {
            layout: {
                topStart: {
                    buttons: ['csv', 'excel', 'print'],
                }
            },
            "order": [
                [0, 'asc']
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
