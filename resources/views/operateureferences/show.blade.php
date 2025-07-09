@extends('layout.user-layout')
@section('title',
    remove_accents_uppercase($operateur?->user?->username) .
    ' | ' .
    remove_accents_uppercase('références
    et expériences professionnelles'))
@section('space-work')

    <section class="section register">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="pagetitle">
                    {{-- <h1>Data Tables</h1> --}}
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Accueil</a></li>
                            <li class="breadcrumb-item">Tables</li>
                            <li class="breadcrumb-item active">Références</li>
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
                <div class="card">
                    <div class="card-body">
                        {{-- <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title">EXPERIENCES ET REFERENCES PROFESSIONNELLES</h5>
                            @can('devenir-operateur-agrement-ouvert')
                                @can('agrement-visible-par-op')
                                    <h5 class="card-title">
                                        <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#AddRefModal">Ajouter
                                        </button>
                                    </h5>
                                @endcan
                            @endcan
                        </div> --}}
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0 text-uppercase fw-bold text-primary">
                                <i class="bi bi-person-lines-fill me-2"></i> EXPERIENCES ET REFERENCES PROFESSIONNELLES
                            </h5>
                            @can('devenir-operateur-agrement-ouvert')
                                @can('agrement-visible-par-op')
                                    <button type="button" class="btn btn-primary btn-sm d-flex align-items-center shadow-sm"
                                        data-bs-toggle="modal" data-bs-target="#AddRefModal">
                                        <i class="bi bi-plus-circle me-2"></i> Ajouter
                                    </button>
                                @endcan
                            @endcan
                        </div>
                        <!-- Table with stripped rows -->
                        <table
                            class="table table-bordered table-hover datatables align-middle justify-content-center table-borderless">
                            <thead class="table-primary text-center">
                                <tr>
                                    <th>DENOMINATION L'ORGANISME</th>
                                    <th>PERIODES D'INTERVENTION</th>
                                    <th>DESCRIPTION DES INTERVENTIONS</th>
                                    <th class="text-center">CONTACTS</th>
                                    <th class="text-center" width="2%"><i class="bi bi-gear"></i></th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                <?php $i = 1; ?>
                                @foreach ($operateur->operateureferences as $operateureference)
                                    <tr>
                                        <td>{{ $operateureference?->organisme }}</td>
                                        <td>{{ $operateureference?->periode }}</td>
                                        <td>{{ $operateureference?->description }}</td>
                                        <td style="text-align: center;">{{ $operateureference?->contact }}</td>
                                        <td style="text-align: center;">
                                            <span class="d-flex align-items-baseline justify-content-center"><a
                                                    href="" class="btn btn-outline-info btn-sm mx-1"
                                                    title="Voir détails">
                                                    <i class="bi bi-eye"></i></a>
                                                @can('devenir-operateur-agrement-ouvert')
                                                    <div class="filter">
                                                        <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                                                class="bi bi-three-dots"></i></a>
                                                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                                            <li>
                                                                <button type="button" class="dropdown-item btn btn-sm mx-1"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#EditoperateureferenceModal{{ $operateureference->id }}">
                                                                    <i class="bi bi-pencil" title="Modifier"></i> Modifier
                                                                </button>
                                                            </li>
                                                            <li>
                                                                <form
                                                                    action="{{ route('operateureferences.destroy', $operateureference->id) }}"
                                                                    method="post">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="dropdown-item show_confirm"><i
                                                                            class="bi bi-trash"></i>Supprimer</button>
                                                                </form>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                @endcan
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <!-- End Table with stripped rows -->
                    </div>
                </div>

            </div>
        </div>

        <!-- Add References -->
        {{-- <div class="modal fade" id="AddRefModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form method="post" action="{{ route('operateureferences.store') }}" enctype="multipart/form-data"
                        class="row g-3">
                        @csrf
                        <div class="card-header text-center bg-gradient-default">
                            <h1 class="h4 text-black mb-0">EXPERIENCES ET REFERENCES PROFESSIONNELLES</h1>
                        </div>
                        <input type="hidden" name="operateur" value="{{ $operateur->id }}">
                        <div class="modal-body">

                            <div class="col-12 mb-2">
                                <label for="organisme" class="form-label">Dénomination de l'organisme<span
                                        class="text-danger mx-1">*</span></label>
                                <input type="text" name="organisme" value="{{ old('organisme') }}"
                                    class="form-control form-control-sm @error('organisme') is-invalid @enderror"
                                    placeholder="Dénomination de l'organisme">
                                @error('organisme')
                                    <span class="invalid-feedback" role="alert">
                                        <div>{{ $message }}</div>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-12 mb-2">
                                <label for="periode" class="form-label">Période<span
                                        class="text-danger mx-1">*</span></label>
                                <input type="text" name="periode" value="{{ old('periode') }}"
                                    class="form-control form-control-sm @error('periode') is-invalid @enderror"
                                    placeholder="Période">
                                @error('periode')
                                    <span class="invalid-feedback" role="alert">
                                        <div>{{ $message }}</div>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-12 mb-2">
                                <label for="contact" class="form-label">Contact</label>
                                <input type="number" min="0" name="contact" value="{{ old('contact') }}"
                                    class="form-control form-control-sm @error('contact') is-invalid @enderror"
                                    placeholder="Contact">
                                @error('contact')
                                    <span class="invalid-feedback" role="alert">
                                        <div>{{ $message }}</div>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-12 mb-2">
                                <label for="contact" class="form-label">Description</label>
                                <textarea name="description" id="description" cols="30" rows="5"
                                    class="form-control form-control-sm @error('description') is-invalid @enderror"
                                    placeholder="Description de l'activité">{{ old('description') }}</textarea>

                                @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <div>{{ $message }}</div>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-sm"
                                data-bs-dismiss="modal">Fermer</button>
                            <button type="submit" class="btn btn-primary btn-sm">Ajouter</button>
                        </div>
                    </form>
                </div>
            </div>
        </div> --}}
        <div class="modal fade" id="AddRefModal" tabindex="-1" aria-labelledby="AddRefModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content shadow-lg border-0 rounded-4 overflow-hidden">
                    <form method="POST" action="{{ route('operateureferences.store') }}" enctype="multipart/form-data"
                        class="p-3">
                        @csrf

                        <div class="bg-info text-white text-center py-3">
                            <h5 class="mb-0 text-uppercase fw-bold">
                                <i class="bi bi-briefcase-fill me-2"></i> Ajouter une expérience / référence
                            </h5>
                        </div>

                        <input type="hidden" name="operateur" value="{{ $operateur->id }}">

                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="organisme" class="form-label">Dénomination de l'organisme <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="organisme" value="{{ old('organisme') }}"
                                    class="form-control form-control-sm @error('organisme') is-invalid @enderror"
                                    placeholder="Nom de l'organisme">
                                @error('organisme')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="periode" class="form-label">Période <span class="text-danger">*</span></label>
                                <input type="text" name="periode" value="{{ old('periode') }}"
                                    class="form-control form-control-sm @error('periode') is-invalid @enderror"
                                    placeholder="Ex. : Janvier 2022 - Mars 2023">
                                @error('periode')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="contact" class="form-label">Contact</label>
                                {{-- <input type="number" name="contact" min="0" value="{{ old('contact') }}"
                                    class="form-control form-control-sm @error('contact') is-invalid @enderror"
                                    placeholder="Téléphone / contact"> --}}
                                <input name="contact" type="text" maxlength="12"
                                    class="form-control form-control-sm @error('contact') is-invalid @enderror"
                                    id="contact_secondaire"
                                    value="{{ old('contact') }}" autocomplete="tel"
                                    placeholder="XX:XXX:XX:XX">
                                @error('contact')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea name="description" id="description" rows="4"
                                    class="form-control form-control-sm @error('description') is-invalid @enderror"
                                    placeholder="Description de l'activité">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="modal-footer d-flex justify-content-between px-4">
                            <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill"
                                data-bs-dismiss="modal">
                                <i class="bi bi-x-circle me-1"></i> Fermer
                            </button>
                            <button type="submit" class="btn btn-info btn-sm rounded-pill text-white">
                                <i class="bi bi-save2 me-1"></i> Ajouter
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- End Add References-->
        <!-- Edit References -->
        {{-- @foreach ($operateureferences as $operateureference)
            <div class="modal fade" id="EditoperateureferenceModal{{ $operateureference->id }}" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form method="post" action="{{ route('operateureferences.update', $operateureference->id) }}"
                            enctype="multipart/form-data" class="row g-3">
                            @csrf
                            @method('patch')
                            <div class="card-header text-center bg-gradient-default">
                                <h1 class="h4 text-black mb-0">MODIFICATION</h1>
                            </div>
                            <input type="hidden" name="operateur" value="{{ $operateur->id }}">
                            <div class="modal-body">


                                <div class="col-12 mb-2">
                                    <label for="organisme" class="form-label">Dénomination de l'organisme<span
                                            class="text-danger mx-1">*</span></label>
                                    <input type="text" name="organisme"
                                        value="{{ $operateureference->organisme ?? old('organisme') }}"
                                        class="form-control form-control-sm @error('organisme') is-invalid @enderror"
                                        placeholder="Dénomination de l'organisme">
                                    @error('organisme')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-12 mb-2">
                                    <label for="periode" class="form-label">Période<span
                                            class="text-danger mx-1">*</span></label>
                                    <input type="text" name="periode"
                                        value="{{ $operateureference->periode ?? old('periode') }}"
                                        class="form-control form-control-sm @error('periode') is-invalid @enderror"
                                        placeholder="Période">
                                    @error('periode')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12 mb-2">
                                    <label for="contact" class="form-label">Contact</label>
                                    <input type="number" min="0" name="contact"
                                        value="{{ $operateureference->contact ?? old('contact') }}"
                                        class="form-control form-control-sm @error('contact') is-invalid @enderror"
                                        placeholder="Contact">
                                    @error('contact')
                                        <span class="invalid-feedback" role="alert">
                                            <div>{{ $message }}</div>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12 mb-2">
                                    <label for="contact" class="form-label">Description</label>
                                    <textarea name="description" id="description" cols="30" rows="5"
                                        class="form-control form-control-sm @error('description') is-invalid @enderror"
                                        placeholder="Description de l'activité">{{ $operateureference->description ?? old('description') }}</textarea>

                                    @error('description')
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
                        </form>
                    </div>
                </div>
            </div>
        @endforeach --}}
        <!-- End Edit References-->
        @foreach ($operateureferences as $operateureference)
            <div class="modal fade" id="EditoperateureferenceModal{{ $operateureference->id }}" tabindex="-1"
                aria-labelledby="EditoperateureferenceModalLabel{{ $operateureference->id }}" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content shadow-lg border-0 rounded-4 overflow-hidden">
                        <form method="POST" action="{{ route('operateureferences.update', $operateureference->id) }}"
                            enctype="multipart/form-data" class="p-3">
                            @csrf
                            @method('patch')

                            <div class="bg-warning text-dark text-center py-3">
                                <h5 class="mb-0 text-uppercase fw-bold">
                                    <i class="bi bi-pencil-square me-2"></i> Modifier une référence
                                </h5>
                            </div>

                            <input type="hidden" name="operateur" value="{{ $operateur->id }}">

                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="organisme" class="form-label">Dénomination de l'organisme <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="organisme"
                                        value="{{ old('organisme', $operateureference->organisme) }}"
                                        class="form-control form-control-sm @error('organisme') is-invalid @enderror"
                                        placeholder="Nom de l'organisme">
                                    @error('organisme')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="periode" class="form-label">Période <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="periode"
                                        value="{{ old('periode', $operateureference->periode) }}"
                                        class="form-control form-control-sm @error('periode') is-invalid @enderror"
                                        placeholder="Ex. : Janvier 2022 - Mars 2023">
                                    @error('periode')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="contact" class="form-label">Contact</label>
                                    {{-- <input type="number" name="contact"
                                        value="{{ old('contact', $operateureference->contact) }}"
                                        class="form-control form-control-sm @error('contact') is-invalid @enderror"
                                        placeholder="Téléphone / contact"> --}}
                                    <input name="contact" type="text" maxlength="12"
                                        class="form-control form-control-sm @error('contact') is-invalid @enderror"
                                        id="contact_secondaire"
                                        value="{{ old('contact', $operateureference->contact ?? '') }}"
                                        autocomplete="tel" placeholder="XX:XXX:XX:XX">
                                    @error('contact')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea name="description" id="description" rows="4"
                                        class="form-control form-control-sm @error('description') is-invalid @enderror"
                                        placeholder="Description de l'activité">{{ old('description', $operateureference->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="modal-footer d-flex justify-content-between px-4">
                                <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill"
                                    data-bs-dismiss="modal">
                                    <i class="bi bi-x-circle me-1"></i> Fermer
                                </button>
                                <button type="submit" class="btn btn-warning btn-sm rounded-pill text-dark">
                                    <i class="bi bi-check2-square me-1"></i> Modifier
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </section>

@endsection
@push('scripts')
    <script>
        new DataTable('#table-regions', {
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
