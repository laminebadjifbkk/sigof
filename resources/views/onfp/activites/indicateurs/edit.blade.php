@extends('layout.user-layout')

@section('title', 'Modifier un indicateur')

@section('space-work')

<div class="container-fluid py-4">

    <div class="d-flex align-items-center mb-4">

        <a href="{{ route('onfp.activites.indicateurs.index', $activite) }}"
           class="btn btn-sm btn-outline-secondary me-3">

            <i class="bi bi-arrow-left"></i>

        </a>

        <div>

            <h3 class="mb-1">
                Modifier l'indicateur
            </h3>

            <p class="text-muted mb-0">
                {{ $activite->reference }} - {{ $activite->titre }}
            </p>

        </div>

    </div>


    <div class="card shadow-sm border-0">

        <div class="card-header bg-white py-3">

            <h5 class="mb-0">
                <i class="bi bi-pencil me-2"></i>
                {{ $indicateur->libelle }}
            </h5>

        </div>


        <div class="card-body">

            <form method="POST"
                  action="{{ route('onfp.activites.indicateurs.update', [$activite, $indicateur]) }}">

                @csrf
                @method('PUT')

                @include('onfp.activites.indicateurs._form')

                <div class="d-flex justify-content-end gap-2 mt-4">

                    <a href="{{ route('onfp.activites.indicateurs.index', $activite) }}"
                       class="btn btn-sm btn-outline-secondary">

                        Annuler

                    </a>

                    <button type="submit"
                            class="btn btn-sm btn-primary">

                        <i class="bi bi-check-lg me-1"></i>

                        Enregistrer les modifications

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection
