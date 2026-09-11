@extends('layout.user-layout')

@section('title', 'Nouveau type d’activité')

@section('space-work')

<div class="container-fluid py-4">

    <div class="d-flex align-items-center mb-4">

        <a href="{{ route('onfp.activite-types.index') }}"
           class="btn btn-outline-secondary me-3">

            <i class="bi bi-arrow-left"></i>

        </a>

        <div>
            <h3 class="mb-1">
                Nouveau type d’activité
            </h3>

            <p class="text-muted mb-0">
                Ajouter une nouvelle catégorie d’activité
            </p>
        </div>

    </div>


    <div class="card shadow-sm border-0">

        <div class="card-body">

            <form method="POST"
                  action="{{ route('onfp.activite-types.store') }}">

                @csrf

                @include('onfp.activite-types._form')

                <div class="d-flex justify-content-end gap-2 mt-4">

                    <a href="{{ route('onfp.activite-types.index') }}"
                       class="btn btn-outline-secondary">

                        Annuler

                    </a>

                    <button type="submit"
                            class="btn btn-primary">

                        <i class="bi bi-check-lg me-1"></i>

                        Enregistrer

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection
