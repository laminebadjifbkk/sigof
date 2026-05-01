@extends('layout.user-layout')

@section('title', 'ONFP - QUESTIONNAIRE DE SUIVI POST-FORMATION (INDIVIDUEL)')

@section('space-work')
    <section class="section">
        <div class="container">

            <div class="card shadow-sm">

                {{-- HEADER --}}
                <div
                    class="card-body bg-light border-bottom d-flex justify-content-between align-items-center flex-wrap gap-3">

                    <h5 class="mb-0">
                        Modules disponibles pour le questionnaire
                    </h5>

                    <a href="{{ url('/demandesIndividuelles') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left"></i> Retour
                    </a>

                </div>
                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table align-middle table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>N°</th>
                                    <th>Module</th>
                                    <th>Date dépôt</th>
                                    <th>Date formation</th>
                                    <th>Lieu formation</th>
                                    <th>Région</th>
                                    <th>Appréciation</th>
                                    <th></th>
                                </tr>
                            </thead>

                            <tbody>
                                @php $i = 1; @endphp
                                @forelse ($individuelles as $individuelle)
                                    <tr>
                                        <td class="text-center">{{ $i++ }}</td>
                                        <td class="fw-semibold text-primary">
                                            {{ $individuelle?->module?->name }}
                                        </td>

                                        <td>
                                            {{ optional($individuelle->date_depot)->format('d/m/Y') }}
                                        </td>
                                        <td>
                                            {{ optional($individuelle->formation)->date_debut?->format('d/m/Y') }}
                                        </td>

                                        <td>{{ $individuelle?->formation?->lieu }}</td>

                                        <td>{{ $individuelle?->departement?->region?->nom }}</td>

                                        <td>
                                            <span class="{{ Str::slug($individuelle?->appreciation) }}">
                                                {{ $individuelle?->appreciation }}
                                            </span>
                                        </td>

                                        <td class="text-end">
                                            <a href="{{ route('individuelles.suivi.formulaire', $individuelle->id) }}"
                                                class="btn btn-primary btn-sm">
                                                Ajouter / Modifier le questionnaire
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">
                                            Aucun module formé disponible
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>

                        </table>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection
