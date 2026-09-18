@extends('layout.user-layout')

@section('space-work')

    <div class="container-fluid">

        <div class="mb-4">

            <h4>
                <i class="bi bi-pencil-square me-2"></i>
                Modifier la sous-activité
            </h4>

            <div class="text-muted">
                Activité :
                {{ $activite->titre }}
            </div>

            <div class="text-muted">
                Sous-activité :
                {{ $sousActivite->titre }}
            </div>

        </div>

        <div class="card shadow-sm border-0">

            <div class="card-body">

                {{-- Affichage des erreurs de validation --}}
                @if ($errors->any())
                    <div class="alert alert-danger">

                        <ul class="mb-0">

                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach

                        </ul>

                    </div>
                @endif

                <form method="POST"
                    action="{{ route('onfp.activites.sous-activites.update', [
                        'activite' => $activite,
                        'sousActivite' => $sousActivite,
                    ]) }}">

                    @csrf
                    @method('PUT')

                    @include('onfp.activites.sous-activites._form')

                    <div class="mt-4 d-flex gap-2">

                        <a href="{{ route('onfp.activites.sous-activites.show', [
                            'activite' => $activite,
                            'sousActivite' => $sousActivite,
                        ]) }}"
                            class="btn btn-sm btn-outline-secondary">

                            <i class="bi bi-arrow-left me-1"></i>
                            Annuler

                        </a>

                        <button type="submit" class="btn btn-sm btn-primary">

                            <i class="bi bi-check-lg me-1"></i>
                            Enregistrer les modifications

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

@endsection
