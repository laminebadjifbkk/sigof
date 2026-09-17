@extends('layout.user-layout')

@section('title', 'Modifier la tâche')

@section('space-work')

@php
    $sousActivite = $sousActivite ?? ($tache->sousActivite ?? null);

    $isNested = $sousActivite !== null;

    $routePrefix = $isNested
        ? 'onfp.activites.sous-activites.taches'
        : 'onfp.activites.taches';

    $routeParams = $isNested
        ? [
            'activite' => $activite,
            'sousActivite' => $sousActivite,
        ]
        : [
            'activite' => $activite,
        ];

    $routeParamsWithTask = array_merge(
        $routeParams,
        ['tache' => $tache]
    );
@endphp


<div class="container-fluid py-4">

    {{-- ==========================================================
        EN-TÊTE
    =========================================================== --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

        <div>

            <div class="mb-2">

                <a
                    href="{{ route($routePrefix . '.show', $routeParamsWithTask) }}"
                    class="text-decoration-none text-muted"
                >
                    <i class="fas fa-arrow-left me-1"></i>
                    Retour à la tâche
                </a>

            </div>

            <h2 class="fw-bold mb-1">
                <i class="fas fa-edit text-warning me-2"></i>
                Modifier la tâche
            </h2>

            <div class="text-muted">

                <strong>
                    {{ $tache->reference ?? 'Tâche' }}
                </strong>

                <span class="mx-2">·</span>

                {{ $tache->titre }}

            </div>

        </div>


        <a
            href="{{ route($routePrefix . '.index', $routeParams) }}"
            class="btn btn-sm btn-light border"
        >
            <i class="fas fa-list me-1"></i>
            Liste des tâches
        </a>

    </div>


    {{-- ==========================================================
        ERREURS
    =========================================================== --}}
    @if($errors->any())

        <div class="alert alert-danger border-0 shadow-sm">

            <div class="fw-bold mb-2">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Veuillez corriger les erreurs suivantes :
            </div>

            <ul class="mb-0">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    {{-- ==========================================================
        FORMULAIRE
    =========================================================== --}}
    <form
        action="{{ route($routePrefix . '.update', $routeParamsWithTask) }}"
        method="POST"
    >

        @csrf
        @method('PUT')

        @include('onfp.activites.taches._form', [
            'tache' => $tache,
            'activite' => $activite,
            'sousActivite' => $sousActivite,
            'employees' => $employees ?? collect(),
        ])


        {{-- ======================================================
            ACTIONS
        ======================================================= --}}
        <div class="d-flex flex-wrap justify-content-end gap-2 mt-4">

            <a
                href="{{ route($routePrefix . '.show', $routeParamsWithTask) }}"
                class="btn btn-sm btn-light border px-4"
            >
                <i class="fas fa-times me-1"></i>
                Annuler
            </a>

            <button
                type="submit"
                class="btn btn-sm btn-warning px-4"
            >
                <i class="fas fa-save me-1"></i>
                Enregistrer les modifications
            </button>

        </div>

    </form>

</div>

@endsection
