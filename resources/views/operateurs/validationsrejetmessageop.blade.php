@extends('layout.user-layout')
@section('title', 'ONFP | Notifications')
@section('space-work')

    <div class="pagetitle">
        {{-- <h1>Data Tables</h1> --}}
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/home') }}">Accueil</a></li>
                <li class="breadcrumb-item">Tables</li>
                <li class="breadcrumb-item active">Validations</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <span class="d-flex mt-2 align-items-baseline"><a href="{{ route('operateurs.show', $operateur) }}"
            class="btn btn-success btn-sm" title="retour"><i class="bi bi-arrow-counterclockwise"></i></a>&nbsp;
        <p> | Retour</p>
    </span>
    @foreach ($operateur->validationoperateurs as $count => $validationoperateur)
        {{-- <i class="bi bi-exclamation-circle text-warning"></i> --}}
        {{-- <img src="{{ asset($validationoperateur->user->getImage()) }}" alt="" class="rounded-circle w-20" width="40" height="auto"> --}}
        {{-- @if ($validationoperateur->action == 'Rejetée') --}}
        {{-- <div class="d-flex align-items-center mt-3">
            @hasanyrole('super-admin|admin|DIOF|ADIOF|Ingenieur')
                <h4>
                    @if ($validationoperateur->created_at >= \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', '2025-05-16 22:40:00') && $validationoperateur->created_at <= \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', '2025-05-16 22:50:00'))
                        Système
                    @else
                        {{ $validationoperateur->user->firstname . ' ' . $validationoperateur->user->name }}
                    @endif
                </h4>
            @endhasanyrole
            <p class="ms-auto mb-0">
                <span class="{{ $validationoperateur->action }}">{{ $validationoperateur->action }}</span>
            </p>
        </div> --}}
        <div class="d-flex align-items-center mt-3">
            @hasanyrole('super-admin|admin|DIOF|ADIOF|Ingenieur')
                <h4>
                    @if (
                        $validationoperateur->created_at >= \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', '2025-05-16 22:40:00') &&
                            $validationoperateur->created_at <= \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', '2025-05-16 22:50:00'))
                        Système
                    @else
                        {{ $validationoperateur->user->firstname . ' ' . $validationoperateur->user->name }}
                    @endif
                </h4>
            @endhasanyrole

            <p class="ms-auto mb-0">
                <span class="{{ $validationoperateur->action }}">{{ $validationoperateur->action }}</span>
            </p>

            {{-- 🔥 Boutons admin uniquement --}}
            @hasrole('super-admin')
                <div class="ms-2 d-flex gap-2">

                    {{-- Modifier (si tu as déjà une route edit) --}}
                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                        data-bs-target="#editValidationModal{{ $validationoperateur->id }}">
                        <i class="bi bi-pencil-square"></i>
                    </button>

                    {{-- Supprimer validation (NOUVELLE ROUTE) --}}
                    <form action="{{ route('validation-operateur.deleteValidation', $validationoperateur->id) }}" method="POST"
                        onsubmit="return confirm('Confirmer la suppression ?')">

                        @csrf
                        @method('DELETE')

                        <button type="submit" class="btn btn-sm btn-danger">
                            <i class="bi bi-trash"></i>
                        </button>

                    </form>

                </div>
            @endhasrole
        </div>
        {{-- @endif --}}
        <div>
            <p>
            <p>{!! $validationoperateur?->motif !!}</p>
            </p>
            <p>{!! $validationoperateur->created_at->diffForHumans() . ',' !!}
                {{ 'le ' . $validationoperateur->created_at->format('d/m/Y, H:i:s') }}
            </p>
            @hasanyrole('super-admin')
                <p>{!! $validationoperateur->updated_at->diffForHumans() . ',' !!}
                    {{ 'le ' . $validationoperateur->updated_at->format('d/m/Y, H:i:s') }}
                </p>
            @endhasanyrole
        </div>
        <hr class="dropdown-divider">

        <div class="modal fade" id="editValidationModal{{ $validationoperateur->id }}" tabindex="-1" aria-hidden="true">

            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('validation-operateur.updateValidation', $validationoperateur->id) }}"
                        method="POST">

                        @csrf

                        <div class="modal-header">
                            <h5 class="modal-title">Modifier la validation</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">

                            {{-- <div class="mb-3">
                                <label>Motif</label>
                                <textarea name="motif" class="form-control" required>{{ $validationoperateur->motif }}</textarea>
                            </div> --}}

                            <div class="mb-3">
                                <label for="motif-{{ $operateur->id }}" class="form-label">
                                    Commentaires ou remarques
                                </label>
                                @php
                                    $lastValidation = collect($operateur->validationoperateurs)
                                        ->sortByDesc('created_at')
                                        ->first();
                                @endphp
                                <textarea name="motif" id="motif-{{ $operateur->id }}" rows="5"
                                    class="form-control form-control-sm @error('motif') is-invalid @enderror"
                                    placeholder="Indiquez les raisons ou recommandations">{{ old('motif', $lastValidation?->motif) }}</textarea>
                                @error('motif')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <div class="mb-3">
                                    <label for="statut-{{ $operateur->id }}" class="form-label">
                                        Statut de la demande
                                    </label>
                                    @php
                                        $selectedStatut = old('statut', $operateur->statut_agrement);
                                    @endphp
                                    <select name="action" id="statut-{{ $operateur->id }}"
                                        class="form-select form-select-sm @error('statut') is-invalid @enderror" autofocus>
                                        <option value="" disabled {{ !$selectedStatut ? 'selected' : '' }}>
                                            Sélectionner
                                        </option>
                                        <option value="À corriger"
                                            {{ $selectedStatut === 'À corriger' ? 'selected' : '' }}>
                                            À corriger
                                        </option>
                                        <option value="Conforme" {{ $selectedStatut === 'Conforme' ? 'selected' : '' }}>
                                            Conforme
                                        </option>
                                        <option value="Non conforme"
                                            {{ $selectedStatut === 'Non conforme' ? 'selected' : '' }}>
                                            Non conforme
                                        </option>
                                        <option value="liste attente"
                                            {{ $selectedStatut === 'liste attente' ? 'selected' : '' }}>En
                                            liste attente</option>
                                        <option value="Indisponible"
                                            {{ $selectedStatut === 'Indisponible' ? 'selected' : '' }}>
                                            Indisponible</option>
                                        <option value="Disponible"
                                            {{ $selectedStatut === 'Disponible' ? 'selected' : '' }}>
                                            Disponible</option>
                                        <option value="Abandon" {{ $selectedStatut === 'Abandon' ? 'selected' : '' }}>
                                            Abandon</option>
                                        <option value="Injoignable"
                                            {{ $selectedStatut === 'Injoignable' ? 'selected' : '' }}>
                                            Injoignable</option>
                                        <option value="rejeté" {{ $selectedStatut === 'rejeté' ? 'selected' : '' }}>
                                            rejeté</option>

                                    </select>
                                    @error('statut')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                Annuler
                            </button>

                            <button type="submit" class="btn btn-primary">
                                Enregistrer
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    @endforeach
@endsection
