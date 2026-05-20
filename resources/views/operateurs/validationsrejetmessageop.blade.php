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
                    <a href="{{ route('validation-operateur.edit', $validationoperateur->id) }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-pencil-square"></i>
                    </a>

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
        </div>
        <hr class="dropdown-divider">
    @endforeach
@endsection
