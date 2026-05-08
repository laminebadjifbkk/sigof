@extends('layout.user-layout')

@section('title', 'ONFP | Confirmation de votre profil')

@section('space-work')

    <div class="container py-5">

        <div class="text-center mb-5">
            <h3 class="fw-bold">Activez votre profil</h3>
            <p class="text-muted">
                Pour continuer, choisissez votre type de profil.
            </p>
        </div>

        <div class="row justify-content-center g-4">

            <!-- Carte Demandeur -->
            <div class="col-md-5">
                <div class="profile-card card shadow-sm h-100 border-0 text-center p-4" onclick="confirmProfil('demandeur')">

                    <div class="mb-3">
                        <i class="bi bi-mortarboard-fill text-primary" style="font-size:40px;"></i>
                    </div>

                    <h5 class="fw-bold">Demandeur de formation</h5>

                    <p class="text-muted small">
                        Rechercher des formations, déposer des demandes de prise en charge
                        et suivre vos dossiers.
                    </p>

                    <div class="mt-3">
                        <span class="badge bg-primary">Choisir ce profil</span>
                    </div>

                </div>
            </div>

            <!-- Carte Operateur -->
            {{-- disabled-card pour déctiver le bouton --}}
            <div class="col-md-5">
                <div class="profile-card card shadow-sm h-100 border-0 text-center p-4 disabled-card"
                    onclick="confirmProfil('operateur')">

                    <div class="mb-3">
                        <i class="bi bi-building text-success" style="font-size:40px;"></i>
                    </div>

                    <h5 class="fw-bold">Opérateur de formation</h5>

                    <p class="text-muted small">
                        Proposer des formations, gérer vos programmes
                        et collaborer avec l’ONFP.
                    </p>

                    <div class="mt-3">
                        <span class="badge bg-success">Choisir ce profil</span>
                    </div>

                </div>
            </div>

        </div>

    </div>


    <form id="profilForm" method="POST" action="{{ route('profil.store') }}">
        @csrf
        <input type="hidden" name="profil" id="profilInput">
    </form>


@endsection
