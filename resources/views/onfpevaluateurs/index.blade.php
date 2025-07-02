@extends('layout.user-layout')
@section('title', 'ONFP | EVALUATEURS ONFP')
@section('space-work')
    @can('onfpevaluateur-view')
        <section class="section register">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="pagetitle">
                        {{-- <h1>Data Tables</h1> --}}
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#">Accueil</a></li>
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
                    <div class="card">
                        <div class="card-body">
                            {{-- @can('role-create') --}}
                            <div class="pt-1">
                                @can('onfpevaluateur-create')
                                    <button type="button" class="btn btn-sm btn-primary float-end btn-rounded"
                                        data-bs-toggle="modal" data-bs-target="#AddonfpevaluateurModal">Ajouter
                                    </button>
                                @endcan
                            </div>
                            {{-- @endcan --}}
                            <h5 class="card-title">Evaluateurs ONFP</h5>
                            <!-- Table with stripped rows -->
                            @if ($onfpevaluateurs->isNotEmpty())
                                <table class="table datatables align-middle justify-content-center" id="table-onfpevaluateurs">
                                    <thead>
                                        <tr>
                                            <th>Prénom</th>
                                            <th>Nom</th>
                                            <th>Initiale</th>
                                            <th>Fonction</th>
                                            <th>Email</th>
                                            <th>Téléphone</th>
                                            <th>Formations</th>
                                            <th class="text-center" width="2%">#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1; ?>
                                        @foreach ($onfpevaluateurs as $onfpevaluateur)
                                            <tr>
                                                <td>{{ $onfpevaluateur?->name }}</td>
                                                <td>{{ $onfpevaluateur?->lastname }}</td>
                                                <td>{{ $onfpevaluateur?->initiale }}</td>
                                                <td>{{ $onfpevaluateur?->fonction }}</td>
                                                <td><a
                                                        href="mailto:{{ $onfpevaluateur?->email }}">{{ $onfpevaluateur?->email }}</a>
                                                </td>
                                                <td><a
                                                        href="tel:+221{{ $onfpevaluateur?->telephone }}">{{ $onfpevaluateur?->telephone }}</a>
                                                </td>
                                                <td style="text-align: center;">
                                                    @foreach ($onfpevaluateur?->formations as $formation)
                                                        @if ($loop->last)
                                                            <a class="text-primary fw-bold"
                                                                href="#">{!! $loop->count ?? '0' !!}</a>
                                                        @endif
                                                    @endforeach
                                                </td>

                                                <td style="text-align: center;">
                                                    @can('onfpevaluateur-show')
                                                        <span class="d-flex mt-2 align-items-baseline"><a
                                                                href="{{ route('onfpevaluateurs.show', $onfpevaluateur?->id) }}"
                                                                class="btn btn-warning btn-sm mx-1" title="Voir détails">
                                                                <i class="bi bi-eye"></i></a>
                                                            <div class="filter">
                                                                <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                                                        class="bi bi-three-dots"></i></a>
                                                                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                                                    @can('onfpevaluateur-update')
                                                                        <li>
                                                                            <button type="button" class="dropdown-item btn btn-sm mx-1"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#EditonfpevaluateurModal{{ $onfpevaluateur?->id }}">
                                                                                <i class="bi bi-pencil" title="Modifier"></i> Modifier
                                                                            </button>
                                                                        </li>
                                                                    @endcan
                                                                    @can('onfpevaluateur-delete')
                                                                        <li>
                                                                            <form
                                                                                action="{{ url('onfpevaluateurs', $onfpevaluateur?->id) }}"
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
                            @else
                                <div class="alert alert-info">Aucun évaluateur enregistré pour le momement !</div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
            <!-- Add onfpevaluateur -->
            <div class="modal fade" id="AddonfpevaluateurModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form method="POST" action="{{ url('onfpevaluateurs') }}" enctype="multipart/form-data"
                            class="needs-validation" novalidate>
                            @csrf

                            <div class="card shadow-lg border-0">
                                <div class="card-header bg-default text-center py-2 rounded-top">
                                    <h4 class="mb-0">➕ Ajouter un évaluateur ONFP</h4>
                                </div>

                                <div class="card-body row g-4 px-4">
                                    @php
                                        $fields = [
                                            'name' => [
                                                'label' => 'Prénom',
                                                'type' => 'text',
                                                'placeholder' => 'Prénom',
                                            ],
                                            'lastname' => [
                                                'label' => 'Nom',
                                                'type' => 'text',
                                                'placeholder' => 'Nom',
                                            ],
                                            'initiale' => [
                                                'label' => 'Initiale',
                                                'type' => 'text',
                                                'placeholder' => 'Initiale',
                                            ],
                                            'fonction' => [
                                                'label' => 'Fonction',
                                                'type' => 'text',
                                                'placeholder' => 'Fonction',
                                            ],
                                            'email' => [
                                                'label' => 'Adresse email',
                                                'type' => 'email',
                                                'placeholder' => 'exemple@email.com',
                                            ],
                                            'telephone' => [
                                                'label' => 'Téléphone',
                                                'type' => 'text',
                                                'id' => 'telephone',
                                                'placeholder' => 'XX:XXX:XX:XX',
                                            ],
                                        ];
                                    @endphp

                                    @foreach ($fields as $name => $data)
                                        <div class="col-md-12">
                                            <label for="{{ $name }}" class="form-label">{{ $data['label'] }} <span
                                                    class="text-danger">*</span></label>
                                            <input type="{{ $data['type'] }}" name="{{ $name }}"
                                                id="{{ $name }}" value="{{ old($name) }}"
                                                class="form-control form-control-sm @error($name) is-invalid @enderror"
                                                placeholder="{{ $data['placeholder'] }}"
                                                {{ $data['type'] !== 'number' ? 'required' : 'min=0 required' }}>
                                            @error($name)
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    @endforeach
                                </div>

                                <div class="card-footer d-flex justify-content-end gap-2 p-3 bg-light border-top">
                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                                        <i class="bi bi-x-circle"></i> Fermer
                                    </button>
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="bi bi-save"></i> Enregistrer
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- End Add onfpevaluateur-->

            <!-- Edit onfpevaluateur -->
            @foreach ($onfpevaluateurs as $onfpevaluateur)
                <div class="modal fade" id="EditonfpevaluateurModal{{ $onfpevaluateur->id }}" tabindex="-1" role="dialog"
                    aria-labelledby="EditonfpevaluateurModalLabel{{ $onfpevaluateur->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">

                            <form method="POST" action="{{ route('onfpevaluateurs.update', $onfpevaluateur->id) }}"
                                enctype="multipart/form-data" class="needs-validation" novalidate>
                                @csrf
                                @method('PATCH')

                                <div class="card shadow-lg border-0">
                                    <div class="card-header bg-default text-center py-2 rounded-top">
                                        <h4 class="mb-0">✏️ Modifier un évaluateur ONFP</h4>
                                    </div>

                                    <div class="card-body row g-4 px-4">
                                        @php
                                            $fields = [
                                                'name' => [
                                                    'label' => 'Prénom',
                                                    'type' => 'text',
                                                    'placeholder' => 'Prénom',
                                                ],
                                                'lastname' => [
                                                    'label' => 'Nom',
                                                    'type' => 'text',
                                                    'placeholder' => 'Nom',
                                                ],
                                                'initiale' => [
                                                    'label' => 'Initiale',
                                                    'type' => 'text',
                                                    'placeholder' => 'Initiale',
                                                ],
                                                'fonction' => [
                                                    'label' => 'Fonction',
                                                    'type' => 'text',
                                                    'placeholder' => 'Fonction',
                                                ],
                                                'email' => [
                                                    'label' => 'Adresse email',
                                                    'type' => 'email',
                                                    'placeholder' => 'exemple@email.com',
                                                ],
                                                'telephone' => [
                                                    'label' => 'Téléphone',
                                                    'type' => 'text',
                                                    'id' => 'telephone',
                                                    'placeholder' => 'XX:XXX:XX:XX',
                                                ],
                                            ];
                                        @endphp

                                        @foreach ($fields as $name => $data)
                                            <div class="col-md-12">
                                                <label for="{{ $name }}" class="form-label">{{ $data['label'] }}
                                                    <span class="text-danger">*</span></label>
                                                <input type="{{ $data['type'] }}" name="{{ $name }}"
                                                    id="{{ $name }}"
                                                    value="{{ old($name, $onfpevaluateur->$name) }}"
                                                    class="form-control form-control-sm @error($name) is-invalid @enderror"
                                                    placeholder="{{ $data['placeholder'] }}"
                                                    {{ $data['type'] !== 'number' ? 'required' : 'min=0 required' }}>
                                                @error($name)
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="card-footer d-flex justify-content-end gap-2 p-3 bg-light border-top">
                                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                                            <i class="bi bi-x-circle"></i> Fermer
                                        </button>
                                        <button type="submit" class="btn btn-success btn-sm">
                                            <i class="bi bi-check-circle"></i> Enregistrer les modifications
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
            <!-- End Edit onfpevaluateur-->
        </section>
    @endcan

@endsection
@push('scripts')
    <script>
        new DataTable('#table-onfpevaluateurs', {
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
