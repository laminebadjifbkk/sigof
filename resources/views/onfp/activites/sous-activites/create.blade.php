@extends('layout.user-layout')

@section('space-work')

<div class="container-fluid">

    <div class="mb-4">

        <h4>
            <i class="bi bi-plus-circle me-2"></i>
            Nouvelle sous-activité
        </h4>

        <div class="text-muted">
            Activité :
            {{ $activite->titre }}
        </div>

    </div>

    <div class="card shadow-sm border-0">

        <div class="card-body">

            <form method="POST"
                  action="{{ route(
                      'onfp.activites.sous-activites.store',
                      ['activite' => $activite]
                  ) }}">

                @csrf

                @include(
                    'onfp.activites.sous-activites._form'
                )

                <div class="mt-4 d-flex gap-2">

                    <a href="{{ route(
                        'onfp.activites.sous-activites.index',
                        ['activite' => $activite]
                    ) }}"
                       class="btn btn-sm btn-outline-secondary">

                        Annuler

                    </a>

                    <button type="submit"
                            class="btn btn-sm btn-primary">

                        <i class="bi bi-check-lg me-1"></i>
                        Enregistrer

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection
