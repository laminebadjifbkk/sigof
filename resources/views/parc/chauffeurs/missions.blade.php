@extends('layout.user-layout')
@section('title', 'Missions de ' . $chauffeur->employee->user->firstname . ' ' . $chauffeur->employee->user->name)

@section('space-work')
    <section class="section">
        <div class="container">
            {{-- <div class="d-flex justify-content-between align-items-center mb-3">
                <h3>Missions de {{ $chauffeur->employee->user->firstname . ' ' . $chauffeur->employee->user->name }}</h3>
                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left-circle"></i> Retour
                </a>
            </div> --}}

            <div class="d-flex justify-content-between align-items-center mb-3">

                <h3>
                    Missions de {{ $chauffeur->employee->user->firstname }}
                    {{ $chauffeur->employee->user->name }}
                </h3>

                <div>

                    <a href="{{ route('parc-chauffeurs.missions.pdf', $chauffeur->id) }}" class="btn btn-danger btn-sm">
                        <i class="bi bi-file-earmark-pdf"></i>
                        Télécharger le récapitulatif PDF
                    </a>

                    <a href="{{ route('parc-chauffeurs.missions.excel', $chauffeur->id) }}" class="btn btn-success btn-sm">
                        <i class="bi bi-file-earmark-excel"></i>
                        Télécharger le récapitulatif Excel
                    </a>

                    <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left-circle"></i>
                        Retour
                    </a>

                </div>

            </div>

            @if ($missions->isEmpty())
                <p class="text-muted">Aucune mission assignée à ce chauffeur.</p>
            @else
                <div id="missions-container">
                    @foreach ($missions as $cm)
                        <div class="card shadow-sm border-0 mb-3">
                            <div class="card-body">

                                <div class="row align-items-center">

                                    {{-- Informations principales --}}
                                    <div class="col-lg-7">

                                        <h5 class="mb-1">
                                            <a href="{{ route('parc-missions.show', $cm->id) }}"
                                                class="text-decoration-none fw-bold">
                                                {{ $cm->reference }}
                                            </a>
                                        </h5>

                                        <p class="text-muted mb-2">
                                            {{ $cm->objet }}
                                        </p>

                                        {{-- <div class="small text-secondary">
                                            <i class="bi bi-calendar-event"></i>
                                            Du
                                            <strong>{{ $cm->date_depart->format('d/m/Y') }}</strong>
                                            au
                                            <strong>{{ $cm->date_retour->format('d/m/Y') }}</strong>
                                        </div> --}}
                                        <div class="small text-secondary">
                                            <i class="bi bi-calendar-event"></i>
                                            <strong>{{ $cm->periode_mission }}</strong>
                                        </div>

                                    </div>

                                    {{-- Statut --}}
                                    <div class="col-lg-2 text-center mt-3 mt-lg-0">

                                        @php
                                            $badge = match ($cm->statut) {
                                                'en_attente' => 'warning',
                                                'en_cours' => 'primary',
                                                'terminee' => 'success',
                                                'annulee' => 'danger',
                                                default => 'secondary',
                                            };
                                        @endphp

                                        <span class="badge bg-{{ $badge }} px-3 py-2">
                                            {{ ucfirst(str_replace('_', ' ', $cm->statut)) }}
                                        </span>

                                    </div>

                                    {{-- Nuitées --}}
                                    {{-- <div class="col-lg-3 text-lg-end mt-3 mt-lg-0">

                                        <h5 class="mb-2 text-primary">
                                            {{ $cm->nuitees }}
                                            <small class="text-muted">
                                                nuitée(s)
                                            </small>
                                        </h5>

                                        @if (!empty($cm->nuitees_par_mois))
                                            <div class="border rounded p-2 bg-light">
                                                <small class="fw-bold text-secondary">
                                                    Répartition
                                                </small>

                                                <ul class="list-unstyled mb-0 mt-2">
                                                    @foreach ($cm->nuitees_par_mois as $mois => $nb)
                                                        <li class="d-flex justify-content-between">
                                                            <span>
                                                                {{ \Carbon\Carbon::createFromFormat('Y-m', $mois)->translatedFormat('F Y') }}
                                                            </span>

                                                            <span class="fw-bold">
                                                                {{ $nb }}
                                                            </span>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif

                                    </div> --}}

                                    {{-- Nuitées et indemnités --}}
                                    <div class="col-lg-3 text-lg-end mt-3 mt-lg-0">

                                        <div class="border rounded p-3 bg-light">

                                            {{-- <h5 class="text-primary mb-3">
                                                <i class="bi bi-moon-stars"></i>
                                                {{ $cm->nuitees }} nuitée(s)
                                            </h5> --}}

                                            <div class="small mb-2">
                                                <strong>Taux / nuitée</strong><br>
                                                {{ number_format($cm->taux_journalier, 0, ',', ' ') }} F CFA
                                            </div>

                                            <div class="small mb-3">
                                                <strong>Montant total</strong><br>
                                                <span class="fw-bold text-success">
                                                    {{ number_format($cm->indemnites_total, 0, ',', ' ') }} F CFA
                                                </span>
                                            </div>

                                            @if (!empty($cm->nuitees_par_mois))
                                                <hr class="my-2">

                                                <small class="fw-bold text-secondary">
                                                    Répartition des nuitées - {{ $cm->nuitees }}
                                                </small>

                                                <ul class="list-unstyled mt-2 mb-0">
                                                    @foreach ($cm->nuitees_par_mois as $mois => $nb)
                                                        <li class="d-flex justify-content-between">
                                                            <span>
                                                                {{ \Carbon\Carbon::createFromFormat('Y-m', $mois)->translatedFormat('F Y') }}
                                                            </span>

                                                            <span>
                                                                {{ $nb }}
                                                            </span>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif

                                        </div>

                                    </div>

                                </div>

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
