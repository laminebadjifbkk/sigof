@extends('layout.user-layout')

@section('title', 'Modifier l’activité')

@section('space-work')

    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>
                <h1 class="h3 mb-1">
                    Modifier l'activité
                </h1>

                <p class="text-muted mb-0">
                    {{ $activite->reference }} -
                    {{ $activite->titre }}
                </p>
            </div>

            <a href="{{ route('onfp.activites.show', $activite) }}" class="btn btn-sm btn-outline-secondary">
                Retour
            </a>

        </div>


        @if ($errors->any())
            <div class="alert alert-danger">

                <strong>
                    Veuillez corriger les erreurs suivantes :
                </strong>

                <ul class="mb-0 mt-2">

                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach

                </ul>

            </div>
        @endif


        <div class="card shadow-sm">

            <div class="card-header">

                <div class="d-flex justify-content-between">

                    <h5 class="mb-0">
                        Informations de l'activité
                    </h5>

                    <span class="text-muted">
                        {{ $activite->reference }}
                    </span>

                </div>

            </div>

            <div class="card-body">

                <form method="POST" action="{{ route('onfp.activites.update', $activite) }}">

                    @include('onfp.activites._form')

                </form>

            </div>

        </div>

    </div>

@endsection
