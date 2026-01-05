@extends('layout.user-layout')
@section('title', 'Missions - ' . $parc_type_mission->libelle)

@section('space-work')
    <section class="section">
        <div class="container">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3>Missions du type : {{ $parc_type_mission->libelle }}</h3>
                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left-circle"></i> Retour
                </a>
            </div>

            @if ($parc_type_missions->isEmpty())
                <p class="text-muted">Aucune mission pour ce type.</p>
            @else
                <div id="missions-container">
                    @foreach ($parc_type_missions as $mission)
                        <div class="d-flex justify-content-between align-items-center mb-2 p-3 border rounded shadow-sm">
                            <div>
                                <a href="{{ route('parc-missions.show', $mission->id) }}" class="text-decoration-none">
                                    <strong>{{ $mission->reference }}</strong>
                                </a>
                                - {{ $mission->objet }}
                            </div>

                            <div class="text-end">
                                <span class="badge bg-secondary">
                                    {{ $mission->date_depart->format('d/m/Y') }}
                                    au
                                    {{ $mission->date_retour->format('d/m/Y') }}
                                </span>
                                <br>
                                <span class="etat-btn {{ $mission->statut }}">
                                    {{ ucfirst(str_replace('ee', 'ée', str_replace('_', ' ', $mission->statut))) }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-3 text-center">
                    @if ($parc_type_missions->hasMorePages())
                        <a href="{{ $parc_type_missions->nextPageUrl() }}" id="loadMoreBtn" class="btn btn-sm btn-info">
                            Voir plus
                        </a>
                    @endif
                </div>
            @endif

        </div>
    </section>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            console.log('LOAD MORE SCRIPT OK');

            const loadMoreBtn = document.getElementById('loadMoreBtn');
            const missionsContainer = document.getElementById('missions-container');

            if (!loadMoreBtn) return;

            loadMoreBtn.addEventListener('click', function(e) {
                e.preventDefault();

                fetch(this.href)
                    .then(res => res.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');

                        const newMissions = doc.querySelectorAll('#missions-container > div');
                        newMissions.forEach(m => missionsContainer.appendChild(m));

                        const newBtn = doc.getElementById('loadMoreBtn');
                        if (newBtn) {
                            this.href = newBtn.href;
                        } else {
                            this.remove();
                        }
                    })
                    .catch(err => console.error('Erreur chargement missions:', err));
            });
        });
    </script>
@endpush
