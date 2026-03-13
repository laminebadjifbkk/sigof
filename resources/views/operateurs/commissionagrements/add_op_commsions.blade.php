@extends('layout.user-layout')
@section('title', 'Choix des opérateurs dans la commission')
@section('space-work')
    <section class="section">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                @if ($message = Session::get('status'))
                    <div class="alert alert-success bg-success text-light border-0 alert-dismissible fade show"
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
                                <span class="d-flex mt-0 align-items-baseline"><a
                                        href="{{ route('commissionagrements.show', $commissionagrement->id) }}"
                                        class="btn btn-success btn-sm" title="retour"><i
                                            class="bi bi-arrow-counterclockwise"></i></a>&nbsp;
                                    <p> | {{ $commissionagrement?->commission }}</p>
                                </span>
                            </div>
                        </div>
                        @if ($commissionagrement->operateurs)
                            {{-- <h5 class="pt-2"><u><b>Opérateurs</b> :</u>
                                <span class="badge bg-secondary"> {{ count($commissionagrement?->operateurs) }}
                                </span>
                            </h5> --}}
                            <div class="p-1 mb-4 border rounded bg-light shadow-sm">
                                <div class="row text-center fw-semibold">
                                    <div class="col-md-12 mb-2">
                                        <span class="text-secondary">Opérateurs choisis</span><br>
                                        <span
                                            class="fs-5 text-dark">{{ count($commissionagrement?->operateurs) ?? 'Aucun' }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if ($operateurs->isEmpty())
                            <div class="alert alert-info bg-info text-light border-0 alert-dismissible fade show"
                                role="alert">
                                <strong>Aucun opérateur n'est encore imputé à cet agrément.</strong>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @else
                            <form method="post"
                                action="{{ url('commisionagrement', ['$idcommissionagrement' => $commissionagrement->id]) }}"
                                enctype="multipart/form-data" class="row g-3">
                                @csrf
                                @method('PUT')
                                <div class="row mb-0">
                                    <div class="col-md-12 pt-3">
                                        <div class="table-responsive">
                                            <table class="table datatables align-middle" id="table-operateurs">
                                                <thead>
                                                    <tr>
                                                        <th width="3%">
                                                            <input type="checkbox" class="form-check-input" id="checkAll">
                                                        </th>
                                                        <th>N°</th>
                                                        <th>N° agrément</th>
                                                        <th>Opérateurs</th>
                                                        <th>Sigle</th>
                                                        <th class="text-center">Modules</th>
                                                        {{-- <th class="text-center">Statut</th> --}}
                                                        <th class="text-center">Type</th>
                                                        <th width="2%"><i class="bi bi-gear"></i></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $i = 1; ?>
                                                    @foreach ($operateurs as $operateur)
                                                        {{-- @isset($operateur?->numero_agrement) --}}
                                                        <tr>
                                                            <td>
                                                                <input type="checkbox" name="operateurs[]"
                                                                    value="{{ $operateur->id }}"
                                                                    class="form-check-input operateur-checkbox"
                                                                    {{ in_array($operateur->id, $operateursSelectionnes ?? []) ? 'checked' : '' }}>
                                                            </td>

                                                            <td>{{ $i++ }}</td>
                                                            <td>
                                                                {{ $operateur->numero_agrement }}
                                                            </td>

                                                            <td>{{ $operateur?->user?->operateur }}</td>
                                                            <td>{{ $operateur?->user?->username }}</td>
                                                            <td style="text-align: center;">
                                                                @foreach ($operateur?->operateurmodules as $operateurmodule)
                                                                    @if ($loop->last)
                                                                        <a href="#"><span
                                                                                class="badge bg-info">{{ $loop->count }}</span></a>
                                                                    @endif
                                                                @endforeach
                                                            </td>
                                                            {{-- <td class="text-center">
                                                        <span
                                                            class="{{ $operateur->statut_agrement }}">{{ $operateur->statut_agrement }}</span>
                                                    </td> --}}
                                                            <td class="text-center">
                                                                <span
                                                                    class="{{ $operateur->type_demande }}">{{ $operateur->type_demande }}</span>
                                                            </td>
                                                            <td>
                                                                <span class="d-flex align-items-baseline"><a
                                                                        href="{{ route('operateurs.show', $operateur) }}"
                                                                        class="btn btn-primary btn-sm"
                                                                        title="voir détails"><i class="bi bi-eye"></i></a>
                                                                    <div class="filter">
                                                                        <a class="icon" href="#"
                                                                            data-bs-toggle="dropdown"><i
                                                                                class="bi bi-three-dots"></i></a>
                                                                        <ul
                                                                            class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                                                            <li>
                                                                                <button type="button"
                                                                                    class="dropdown-item btn btn-sm mx-1"
                                                                                    data-bs-toggle="modal"
                                                                                    data-bs-target="#EditOperateurModal{{ $operateur?->id }}">
                                                                                    <i class="bi bi-pencil"
                                                                                        title="Modifier"></i>
                                                                                    Modifier
                                                                                </button>
                                                                            </li>
                                                                            {{-- Détacher --}}
                                                                            <li>
                                                                                <form
                                                                                    action="{{ route('operateurs.detachCommission', ['operateur' => $operateur->id, 'commission' => $commissionagrement->id]) }}"
                                                                                    method="POST"
                                                                                    onsubmit="return confirm('Voulez-vous vraiment détacher cet opérateur de la commission ?');">
                                                                                    @csrf
                                                                                    @method('DELETE')
                                                                                    <button type="submit"
                                                                                        class="dropdown-item btn btn-sm mx-1">
                                                                                        <i class="bi bi-x-circle"
                                                                                            title="Détacher"></i> Détacher
                                                                                    </button>
                                                                                </form>
                                                                            </li>
                                                                            {{-- <li>
                                                                                <form
                                                                                    action="{{ route('operateurs.destroy', $operateur->id) }}"
                                                                                    method="post">
                                                                                    @csrf
                                                                                    @method('DELETE')
                                                                                    <button type="submit"
                                                                                        class="dropdown-item show_confirm"
                                                                                        title="Supprimer"><i
                                                                                            class="bi bi-trash"></i>Supprimer</button>
                                                                                </form>
                                                                            </li> --}}
                                                                        </ul>
                                                                    </div>
                                                                </span>
                                                            </td>
                                                        </tr>
                                                        {{--  @endisset --}}
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-outline-primary btn-sm"><i
                                                class="bi bi-check2-circle"></i>&nbsp;Mettre à jour la commission</button>
                                    </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.getElementById('checkAll').addEventListener('click', function(e) {

            let checkboxes = document.querySelectorAll('.operateur-checkbox');

            checkboxes.forEach(function(checkbox) {
                checkbox.checked = e.target.checked;
            });

        });
    </script>
@endpush
