@extends('layout.user-layout')

@section('title', 'Courriers de la Direction ' . $direction?->name)

@section('space-work')
    <div class="pagetitle mb-3">
        <nav>
            <ol class="breadcrumb bg-white px-3 py-2 shadow-sm rounded">
                <li class="breadcrumb-item"><a href="{{ url('/home') }}">Accueil</a></li>
                <li class="breadcrumb-item">Courriers</li>
                <li class="breadcrumb-item active">{{ $direction->sigle }}</li>
            </ol>
        </nav>
    </div>

    <section class="section dashboard">
        <div class="container-fluid">
            <div class="mb-4">
                <div class="card border-0 shadow-sm text-white direction-header">
                    <div class="card-body py-3 d-flex align-items-center justify-content-between">

                        <div>
                            <h5 class="mb-0 fw-bold">
                                Tableau de bord des courriers
                            </h5>
                            <small>
                                Direction : {{ $direction->name ?? 'Non définie' }}
                            </small>
                            <br>
                            <small>
                                Responsable :
                                {{ $direction?->chef?->user?->firstname . ' ' . $direction?->chef?->user?->name ?? 'Non définie' }}
                            </small>
                        </div>

                        <div class="icon">
                            <i class="bi bi-building fs-3"></i>
                        </div>

                    </div>
                </div>
            </div>
            <div class="row g-4">

                <!-- TOTAL -->
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                    <a href="{{ route('courriers.direction') }}" class="text-decoration-none">
                        <div class="card info-card border-0 shadow-sm hover-card">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between">

                                    <div>
                                        <h6 class="text-muted mb-1">Courriers</h6>
                                        <h4 class="fw-bold mb-0">{{ $total_courrier }}</h4>
                                        <small class="text-secondary">Total général</small>
                                    </div>

                                    {{-- <div class="icon-box bg-primary text-white rounded-circle">
                                        <i class="bi bi-inboxes fs-4"></i>
                                    </div> --}}

                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- ARRIVÉS -->
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                    <a href="{{ route('arrives.direction') }}" class="text-decoration-none">
                        <div class="card info-card border-0 shadow-sm hover-card">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between">

                                    <div>
                                        <h6 class="text-muted mb-1">Arrivés</h6>
                                        <h4 class="fw-bold mb-0">{{ $total_arrive }}</h4>
                                        <small class="text-success">
                                            {{ number_format($pourcentage_arrive, 1, ',', ' ') }}%
                                        </small>
                                    </div>

                                    {{-- <div class="icon-box bg-success text-white rounded-circle">
                                        <i class="bi bi-envelope-open fs-4"></i>
                                    </div> --}}

                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- DÉPARTS -->
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                    <a href="{{ route('departs.direction') }}" class="text-decoration-none">
                        <div class="card info-card border-0 shadow-sm hover-card">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between">

                                    <div>
                                        <h6 class="text-muted mb-1">Départs</h6>
                                        <h4 class="fw-bold mb-0">{{ $total_depart }}</h4>
                                        <small class="text-warning">
                                            {{ number_format($pourcentage_depart, 1, ',', ' ') }}%
                                        </small>
                                    </div>

                                    {{--  <div class="icon-box bg-warning text-white rounded-circle">
                                        <i class="bi bi-envelope fs-4"></i>
                                    </div> --}}

                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- INTERNES -->
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                    <a href="{{ route('internes.direction') }}" class="text-decoration-none">
                        <div class="card info-card border-0 shadow-sm hover-card">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between">

                                    <div>
                                        <h6 class="text-muted mb-1">Internes</h6>
                                        <h4 class="fw-bold mb-0">{{ $total_interne }}</h4>
                                        <small class="text-info">
                                            {{ number_format($pourcentage_interne, 1, ',', ' ') }}%
                                        </small>
                                    </div>

                                    {{-- <div class="icon-box bg-info text-white rounded-circle">
                                        <i class="bi bi-envelope-paper fs-4"></i>
                                    </div> --}}

                                </div>
                            </div>
                        </div>
                    </a>
                </div>

            </div>
        </div>
    </section>
@endsection
