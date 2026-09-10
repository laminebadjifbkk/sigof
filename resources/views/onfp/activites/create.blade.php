@extends('layouts.app')

@section('title', 'Nouvelle activité')

@section('content')

    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>
                <h1 class="h3 mb-1">
                    Nouvelle activité
                </h1>

                <p class="text-muted mb-0">
                    Créer une nouvelle activité de pilotage
                </p>
            </div>

            <a href="{{ route('onfp.activites.index') }}" class="btn btn-outline-secondary">
                Retour
            </a>

        </div>


        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Veuillez corriger les erreurs suivantes :</strong>

                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        <div class="card shadow-sm">

            <div class="card-header">
                <h5 class="mb-0">
                    Informations de l'activité
                </h5>
            </div>

            <div class="card-body">

                <form method="POST" action="{{ route('onfp.activites.store') }}">

                    @include('onfp.activites._form')

                </form>

            </div>

        </div>

    </div>

@endsection
