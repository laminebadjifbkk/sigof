@extends('layout.user-layout')
@section('title', 'ONFP | Offres spéciales')
@section('space-work')
    <section class="section">
        <div class="row justify-content-center">
            <div class="col-12">
                @if ($message = Session::get('status'))
                    <div class="alert alert-danger bg-danger text-light border-0 alert-dismissible fade show" region="alert">
                        <strong>{{ $message }}</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if ($message = Session::get('success'))
                    <div class="alert alert-success bg-success text-light border-0 alert-dismissible fade show"
                        region="alert">
                        <strong>{{ $message }}</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <div class="alert alert-danger bg-danger text-light border-0 alert-dismissible fade show"
                            role="alert">
                            <strong>{{ $error }}</strong>
                        </div>
                    @endforeach
                @endif
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Offres spéciales</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mt-0">

                            <a href="{{ url('/profil') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-arrow-left"></i> Retour
                            </a>
                            <button type="button" class="btn btn-info btn-sm">
                                <span class="badge bg-white text-info">{{ $count }}</span>
                            </button>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-0">
                            <h5 class="card-title">
                                Bonjour
                                {{ Auth::user()->civilite . ' ' . Auth::user()->firstname . ' ' . Auth::user()->name }}
                            </h5>
                        </div>
                        <div class="table-responsive">
                            <table
                                class="table datatables table-striped table-bordered table-hover align-middle justify-content-center">
                                <thead>
                                    <tr class="text-center">
                                        <th width="2%">N°</th>
                                        {{-- <th width="8%">Numéro</th> --}}
                                        <th>Module</th>
                                        <th>Niveau étude</th>
                                        <th>Diplome aca.</th>
                                        <th>Diplome pro.</th>
                                        <th>Partenaires</th>
                                        <th width="5%">Statut</th>
                                        <th style="width:3%;"><i class="bi bi-gear"></i></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; ?>
                                    @foreach ($userIndividuellesAvecProjet as $individuelle)
                                        <tr class="text-center">
                                            <td>{{ $i++ }}</td>
                                            {{-- <td>{{ $individuelle?->numero }}</td> --}}
                                            <td>{{ $individuelle?->module?->name }}</td>
                                            <td>{{ $individuelle?->niveau_etude }}</td>
                                            <td>{{ $individuelle?->diplome_academique }}</td>
                                            <td>{{ $individuelle?->diplome_professionnel }}</td>
                                            <td>{{ $individuelle?->projet?->sigle }}
                                            </td>
                                            <td>
                                                @hasanyrole('super-admin|admin|DIOF|ADIOF|Ingenieur')
                                                    <span
                                                        class="{{ $individuelle?->statut }}">{{ $individuelle?->statut }}</span>
                                                @endhasanyrole
                                                @hasrole('Demandeur')
                                                    @if (!empty($individuelle->projets_id))
                                                        @if ($individuelle->projet?->statut === 'ouvert')
                                                            <span
                                                                class="btn btn-info btn-sm text-white d-inline-flex align-items-center">
                                                                <i class="bi bi-check-circle me-1"></i> Enregistrée
                                                            </span>
                                                        @else
                                                            <span
                                                                class="{{ $individuelle?->statut }}">{{ $individuelle?->statut }}</span>
                                                        @endif
                                                    @else
                                                        <span
                                                            class="{{ $individuelle?->statut }}">{{ $individuelle?->statut }}</span>
                                                    @endif
                                                @endhasrole
                                            </td>
                                            <td>
                                                <span class="d-flex align-items-baseline">
                                                    <a href="{{ route('individuelles.show', $individuelle) }}"
                                                        class="btn btn-success btn-sm" title="voir détails"><i
                                                            class="bi bi-eye"></i></a>
                                                    <div class="filter">
                                                        <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                                                class="bi bi-three-dots"></i></a>
                                                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
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
                        </div>
                    </div>
                </div>
                @can('upload-file-si-fermeture-anticiper')

                    <div class="container-fluid pt-4">
                        <div class="row g-4">

                            <div class="col-12 col-lg-5">

                                <div class="card shadow-sm h-100">
                                    <div class="card-body">

                                        <h5 class="card-title mb-3">
                                            <i class="bi bi-upload me-1"></i>
                                            Joindre un document
                                        </h5>


                                        <form method="post" action="{{ route('files.update', $user?->uuid) }}"
                                            enctype="multipart/form-data">

                                            @csrf
                                            @method('patch')

                                            <input type="hidden" name="idUser" value="{{ $user?->id }}">

                                            <div class="alert border-0 shadow-sm rounded-4 p-4 mb-4 bg-warning bg-opacity-10">

                                                <div class="d-flex align-items-center mb-3">
                                                    <div class="me-3">
                                                        <i class="bi bi-exclamation-triangle-fill text-warning fs-4"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 fw-bold text-warning">
                                                            NB : Documents requis
                                                        </h6>
                                                    </div>
                                                </div>

                                                <ul class="mb-0 ps-4 small text-dark">
                                                    <li class="mb-2">
                                                        <i class="bi bi-card-text text-secondary me-2"></i>
                                                        La carte nationale d'identité (recto/verso)
                                                    </li>
                                                    <li class="mb-2">
                                                        <i class="bi bi-geo-alt text-secondary me-2"></i>
                                                        Un certificat de résidence
                                                    </li>
                                                    <li class="mb-2">
                                                        <i class="bi bi-file-earmark-person text-secondary me-2"></i>
                                                        Un curriculum vitae (CV)
                                                    </li>
                                                    <li>
                                                        <i class="bi bi-award text-secondary me-2"></i>
                                                        Diplômes ou attestations (si disponibles)
                                                    </li>
                                                </ul>

                                            </div>

                                            {{-- Légende --}}
                                            <div class="mb-3">
                                                <label class="form-label">
                                                    Légende <span class="text-danger">*</span>
                                                </label>

                                                <select name="legende"
                                                    class="form-select form-select-sm @error('legende') is-invalid @enderror">

                                                    <option value="">Choisir...</option>

                                                    @foreach ($user_files as $file)
                                                        <option value="{{ $file?->id }}">
                                                            {{ $file?->legende }}
                                                        </option>
                                                    @endforeach

                                                </select>

                                                @error('legende')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>

                                            {{-- Fichier --}}
                                            <div class="mb-3">
                                                <label class="form-label">
                                                    Fichier <span class="text-danger">*</span>
                                                </label>

                                                <input type="file" name="file"
                                                    class="form-control form-control-sm @error('file') is-invalid @enderror">

                                                @error('file')
                                                    <div class="text-danger small">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>

                                            {{-- Bouton --}}
                                            <div class="text-end">
                                                <button type="submit" class="btn btn-primary btn-sm">
                                                    <i class="bi bi-upload me-1"></i>
                                                    Téléverser
                                                </button>
                                            </div>

                                        </form>

                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-lg-7">

                                <div class="card shadow-sm h-100">

                                    <div class="card-body">

                                        <h5 class="card-title mb-3">
                                            <i class="bi bi-folder2-open me-1"></i>
                                            Fichiers joints
                                        </h5>

                                        <div class="table-responsive">
                                            <table class="table table-bordered table-sm table-hover align-middle">

                                                <thead class="table-light">
                                                    <tr>
                                                        <th style="width:5%">N°</th>
                                                        <th>Légende</th>
                                                        <th style="width:10%">Fichier</th>
                                                        <th style="width:10%" class="text-center">Statut</th>
                                                        <th style="width:10%" class="text-center">Actions</th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    @php $i = 1; @endphp

                                                    @foreach ($files as $file)
                                                        <tr>
                                                            <td>{{ $i++ }}</td>
                                                            <td class="text-start">{{ $file->legende }}</td>

                                                            <td>
                                                                <a class="btn btn-outline-secondary btn-sm" target="_blank"
                                                                    href="{{ asset($file->getFichier()) }}">
                                                                    <i class="bi bi-download"></i>
                                                                </a>
                                                            </td>

                                                            <td class="text-center">

                                                                <span class="{{ $file?->statut }}">
                                                                    {{ $file?->statut }}
                                                                </span>
                                                            </td>

                                                            {{-- <td>
                                                                        <button class="btn btn-outline-danger btn-sm">
                                                                            <i class="bi bi-trash"></i>
                                                                        </button>
                                                                    </td> --}}
                                                            <td class="text-center">
                                                                @if ($file->statut !== 'Validé')
                                                                    <form action="{{ route('fileDestroy') }}" method="post"
                                                                        class="d-inline">
                                                                        @csrf
                                                                        @method('put')
                                                                        <input type="hidden" name="idFile"
                                                                            value="{{ $file->id }}">
                                                                        <button type="submit"
                                                                            class="btn btn-outline-danger btn-sm show_confirm"
                                                                            title="Supprimer">
                                                                            <i class="bi bi-trash"></i>
                                                                        </button>
                                                                    </form>
                                                                @endif
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
                    </div>
                @endcan
            </div>
        </div>
    </section>
@endsection
