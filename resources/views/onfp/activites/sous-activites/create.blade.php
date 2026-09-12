@extends('layout.user-layout')

@section('space-work')

    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h4 class="mb-1">
                    <i class="bi bi-plus-circle me-2"></i>
                    Nouvelle sous-activité
                </h4>

                <div class="text-muted">
                    Activité :
                    <strong>{{ $activite->titre }}</strong>
                </div>

            </div>

            <a href="{{ route('onfp.activites.sous-activites.index', $activite) }}"
                class="btn btn-sm btn-outline-secondary">

                <i class="bi bi-arrow-left me-1"></i>
                Retour

            </a>

        </div>


        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white">

                <h5 class="mb-1">
                    Informations de la sous-activité
                </h5>

                <small class="text-muted">
                    Définissez les informations opérationnelles de cette sous-activité.
                </small>

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
                    action="{{ route('onfp.activites.sous-activites.store', $activite) }}">

                    @csrf

                    @include('onfp.activites.sous-activites._form')

                    <div class="d-flex justify-content-end gap-2 mt-4">

                        <a href="{{ route('onfp.activites.sous-activites.index', $activite) }}"
                            class="btn btn-sm btn-light border">

                            Annuler

                        </a>

                        <button type="submit" class="btn btn-sm btn-primary">

                            <i class="bi bi-check-lg me-1"></i>
                            Enregistrer

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

@endsection
