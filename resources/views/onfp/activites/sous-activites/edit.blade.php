@extends('layout.user-layout')

@section('space-work')

    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h4 class="mb-1">
                    <i class="bi bi-pencil-square me-2"></i>
                    Modifier la sous-activité
                </h4>

                <div class="text-muted">
                    {{ $sousActivite->titre }}
                </div>

            </div>

            <a href="{{ route('onfp.activites.sous-activites.show', [$activite, $sousActivite]) }}"
                class="btn btn-sm btn-outline-secondary">

                <i class="bi bi-arrow-left me-1"></i>
                Retour

            </a>

        </div>


        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    Modifier les informations
                </h5>

            </div>

            <div class="card-body">

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
                    action="{{ route('onfp.activites.sous-activites.update', [$activite, $sousActivite]) }}">

                    @csrf
                    @method('PUT')

                    @include('onfp.activites.sous-activites._form')

                    <div class="d-flex justify-content-end gap-2 mt-4">

                        <a href="{{ route('onfp.activites.sous-activites.show', [$activite, $sousActivite]) }}"
                            class="btn btn-sm btn-light border">

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
