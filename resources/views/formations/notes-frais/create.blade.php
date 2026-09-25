@extends('layout.user-layout')

@section('title', 'Nouvelle note de frais')

@section('space-work')

    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1">Nouvelle note de frais</h1>
                <p class="text-muted mb-0">Créer une note d'acompte ou une note définitive pour un opérateur</p>
            </div>

            <a href="{{ route('formations.notes-frais.index') }}" class="btn btn-sm btn-outline-secondary">
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
                <h5 class="mb-0">Informations de la note de frais</h5>
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('formations.notes-frais.store') }}">
                    @include('formations.notes-frais._form')
                </form>
            </div>
        </div>

    </div>

@endsection
