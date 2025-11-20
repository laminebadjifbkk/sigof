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

    <span class="d-flex mt-2 align-items-baseline"><a href="{{ route('formulaires.show', $formulaire->id) }}"
            class="btn btn-success btn-sm" title="retour"><i class="bi bi-arrow-counterclockwise"></i></a>&nbsp;
        <p> | Retour</p>
    </span>
    @foreach ($formulaire->historiques as $count => $historique)
        {{-- <img src="{{ asset($validationindividuelle->user->getImage()) }}" alt="" class="rounded-circle w-20" width="40" height="auto"> --}}
        {{-- @if ($validationindividuelle->action == 'Rejetée') --}}
        <div class="d-flex align-items-center mt-3">
            @hasanyrole('super-admin|admin|DIOF|ADIOF|Ingenieur')
                <h4>
                    @if (
                        $historique->created_at >= \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', '2025-05-16 22:40:00') &&
                            $historique->created_at <= \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', '2025-05-16 22:50:00'))
                        Système
                    @else
                        {{ $historique->user->firstname . ' ' . $historique->user->name }}
                    @endif
                </h4>
            @endhasanyrole
            <p class="ms-auto mb-0">
                <span class="{{ $historique?->statut }}">{{ $historique?->statut }}</span>
            </p>
        </div>
        {{-- @endif --}}
        <div>
            <p>
            <p>{!! $historique?->motif !!}</p>
            </p>
            <p>{!! $historique->created_at->diffForHumans() . ',' !!}
                {{ 'le ' . $historique->created_at->format('d/m/Y, H:i:s') }}
            </p>
        </div>
        <hr class="dropdown-divider">
    @endforeach
@endsection
