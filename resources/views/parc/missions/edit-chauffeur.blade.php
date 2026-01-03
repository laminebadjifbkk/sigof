@extends('layout.user-layout')
@section('title', 'ONFP - Mise à jour des chauffeurs de la mission')

@section('space-work')
    <section class="section register">
        <div class="container">

            {{-- En-tête --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="mb-0">Chauffeurs de la mission : {{ $mission->reference }}</h3>
                <a href="{{ route('parc-missions.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left-circle"></i> Retour à la liste
                </a>
            </div>

            {{-- Messages de succès --}}
            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                </div>
            @endif

            {{-- Affichage des erreurs --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Card principale --}}
            <div class="card shadow-sm">
                <div class="card-body">

                    {{-- Boutons de sélection rapide --}}
                    <div class="mb-3">
                        <button type="button" id="select-all" class="btn btn-sm btn-info">Tout sélectionner</button>
                        <button type="button" id="deselect-all" class="btn btn-sm btn-outline-secondary">Tout
                            désélectionner</button>
                    </div>

                    {{-- Formulaire --}}
                    <form action="{{ route('parc-missions.chauffeurs.update', $mission) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Chauffeur</th>
                                    <th width="12%" class="text-center">Missions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($chauffeurs as $chauffeur)
                                    @php
                                        // Vérifie si ce chauffeur est déjà affecté à la mission
                                        $isChecked = $missionChauffeurs->pluck('id')->contains($chauffeur->employee_id);
                                    @endphp
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="chauffeurs[{{ $chauffeur->id }}][selected]"
                                                value="{{ $chauffeur->id }}" {{ $isChecked ? 'checked' : '' }}>
                                            {{ $chauffeur->employee->user->firstname }}
                                            {{ $chauffeur->employee->user->name }}
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-secondary">
                                                {{ $chauffeur->employee->parcmissions->count() }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <button type="submit" class="btn btn-success btn-sm mt-2">
                            <i class="bi bi-check-circle"></i> Enregistrer
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAllBtn = document.getElementById('select-all');
            const deselectAllBtn = document.getElementById('deselect-all');

            selectAllBtn.addEventListener('click', () => {
                document.querySelectorAll('.chauffeur-checkbox').forEach(cb => cb.checked = true);
            });

            deselectAllBtn.addEventListener('click', () => {
                document.querySelectorAll('.chauffeur-checkbox').forEach(cb => cb.checked = false);
            });
        });
    </script>
@endpush
