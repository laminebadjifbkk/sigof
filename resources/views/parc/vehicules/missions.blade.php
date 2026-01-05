@extends('layout.user-layout')
@section('title', 'Missions de ' . $vehicule?->immatriculation)

@section('space-work')
    <section class="section">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3>Véhicule {{ $vehicule?->immatriculation }} - {{ $vehicule?->marque }}
                    {{ $vehicule?->modele }}</h3>
                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left-circle"></i> Retour
                </a>
            </div>

            @if ($missions->isEmpty())
                <p class="text-muted">Aucune mission assignée à ce véhicule.</p>
            @else
                <div id="missions-container">
                    @foreach ($missions as $cm)
                        <div class="d-flex justify-content-between align-items-center mb-2 p-3 border rounded shadow-sm">
                            <div>
                                <a href="{{ route('parc-missions.show', $cm->id) }}" class="text-decoration-none">
                                    <strong>{{ $cm->reference }}</strong>
                                </a>
                                - {{ $cm->objet }}
                            </div>
                            <div class="text-end">
                                <span class="badge bg-secondary">
                                    {{ $cm->date_depart->format('d/m/Y') }} au {{ $cm->date_retour->format('d/m/Y') }}
                                </span>
                                <br>
                                <span class="etat-btn {{ $cm->statut }}">
                                    {{ ucfirst(str_replace('ee', 'ée', str_replace('_', ' ', $cm->statut))) }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-3 text-center">
                    @if ($missions->hasMorePages())
                        <a href="{{ $missions->nextPageUrl() }}" id="loadMoreBtn" class="btn btn-sm btn-info">
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
            const loadMoreBtn = document.getElementById('loadMoreBtn');
            const missionsContainer = document.getElementById('missions-container');

            if (loadMoreBtn) {
                loadMoreBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    fetch(this.href)
                        .then(res => res.text())
                        .then(html => {
                            // extraire le contenu des nouvelles missions seulement
                            const parser = new DOMParser();
                            const doc = parser.parseFromString(html, 'text/html');
                            const newMissions = doc.querySelectorAll('#missions-container > div');
                            newMissions.forEach(m => missionsContainer.appendChild(m));

                            // mettre à jour le bouton pour la prochaine page
                            const newBtn = doc.getElementById('loadMoreBtn');
                            if (newBtn) {
                                this.href = newBtn.href;
                            } else {
                                this.remove(); // plus de pages, on supprime le bouton
                            }
                        });
                });
            }
        });
    </script>
@endpush
