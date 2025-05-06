@extends('layout.user-layout')
@section('title', 'ONFP | Offres spéciales')
@section('space-work')
    <section class="section">
        <div class="row justify-content-center">
            <div class="col-12 col-md-12 col-lg-12 col-sm-12 col-xs-12 col-xxl-12">
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
                            <span class="d-flex align-items-baseline"><a href="{{ url('/profil') }}"
                                    class="btn btn-success btn-sm" title="retour"><i
                                        class="bi bi-arrow-counterclockwise"></i></a>&nbsp;
                                <p> | Profil</p>
                            </span>
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
                        <table class="table table-bordered table-hover table-borderless">
                            <thead>
                                <tr class="text-center">
                                    <th width="2%">N°</th>
                                    <th width="8%">Numéro</th>
                                    <th>Module</th>
                                    <th>Niveau étude</th>
                                    <th>Diplome académique</th>
                                    <th>Diplome professionnel</th>
                                    <th>Projets / Programmes</th>
                                    <th width="5%">Statut</th>
                                    <th style="width:3%;"><i class="bi bi-gear"></i></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; ?>
                                @foreach ($userIndividuellesAvecProjet as $individuelle)
                                    <tr class="text-center">
                                        <td>{{ $i++ }}</td>
                                        <td>{{ $individuelle?->numero }}</td>
                                        <td>{{ $individuelle?->module?->name }}</td>
                                        <td>{{ $individuelle?->niveau_etude }}</td>
                                        <td>{{ $individuelle?->diplome_academique }}</td>
                                        <td>{{ $individuelle?->diplome_professionnel }}</td>
                                        <td>{{ $individuelle?->projet?->name . ' (' . $individuelle?->projet?->name . ')' }}
                                        </td>
                                        <td>
                                            @hasanyrole('super-admin|admin|DIOF|ADIOF|Ingenieur')
                                                <span class="{{ $individuelle?->statut }}">{{ $individuelle?->statut }}</span>
                                            @endhasanyrole
                                            @hasrole('Demandeur')
                                                @if (!empty($individuelle->projets_id))
                                                    @if ($individuelle->projet?->statut === 'ouvert')
                                                        <span class="badge bg-info">Enregistrée avec succès</span>
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
                                                                <button type="submit" class="dropdown-item show_confirm"
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
        </div>
    </section>
@endsection
