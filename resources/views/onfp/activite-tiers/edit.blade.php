@extends('layout.user-layout')

@section('title', 'Modifier le tiers')

@section('space-work')

    <div class="container-fluid py-4">

        <div class="d-flex align-items-center gap-3 mb-4">

            <a href="{{ route('onfp.tiers.index') }}"
                class="btn btn-sm btn-outline-secondary flex-shrink-0" title="Retour">
                <i class="bi bi-arrow-left"></i>
            </a>

            <div>
                <h4 class="fw-bold mb-1">
                    Modifier le tiers
                </h4>
                <div class="text-muted small">
                    {{ $tiers->nom }}
                </div>
            </div>

        </div>


        @if ($errors->any())
            <div class="alert alert-danger">
                <div class="fw-bold mb-2">
                    <i class="bi bi-exclamation-triangle me-1"></i>
                    Veuillez corriger les erreurs suivantes :
                </div>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        <form method="POST" action="{{ route('onfp.tiers.update', $tiers) }}">

            @csrf
            @method('PUT')

            @include('onfp.tiers._form', ['tiers' => $tiers, 'types' => $types])

            <div class="d-flex flex-wrap justify-content-end gap-2 mt-4">

                <a href="{{ route('onfp.tiers.index') }}" class="btn btn-sm btn-light border px-4">
                    <i class="bi bi-x-lg me-1"></i>
                    Annuler
                </a>

                <button type="submit" class="btn btn-sm btn-warning px-4">
                    <i class="bi bi-save me-1"></i>
                    Enregistrer les modifications
                </button>

            </div>

        </form>

    </div>

@endsection
