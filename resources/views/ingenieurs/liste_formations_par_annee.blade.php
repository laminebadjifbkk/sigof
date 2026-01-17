@extends('layout.user-layout')
@section('title', 'ONFP | DEMANDEURS')
@section('space-work')
    @can('individuelle-view')
        <section class="section">
            <div class="row mb-4">
                {{-- Cases interactives style "card" responsive --}}
                @if (($individuelles ?? collect())->isNotEmpty())
                    <div class="col-6 col-sm-4 col-md-4 col-lg-3">
                        <div class="card shadow-sm text-center p-2 hover-pointer" style="min-height:140px; border-radius:10px;"
                            onclick="toggleTable('table-individuelles')">
                            <h6 class="card-title mb-2 text-truncate" title="Formations Individuelles" style="font-size:0.85rem;">
                                Formations Individuelles
                            </h6>

                            <!-- Badge type -->
                            <span class="etat-btn bg-primary text-white px-2 py-1 rounded" style="font-size:0.75rem;">
                                Individuelle
                            </span>

                            <!-- Nombre de formés -->
                            <div class="d-flex flex-column align-items-center justify-content-center mb-2 mt-2">
                                <span class="h6 mb-0"
                                    style="font-size:1rem;">{{ ($individuelles ?? collect())->count() }}</span>
                            </div>

                            <!-- Barre de pourcentage (optionnelle) -->
                            <div class="mb-2">
                                <div class="progress" style="height:6px; border-radius:3px;">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: 100%;"></div>
                                </div>
                                <small class="text-muted">100%</small>
                            </div>

                            <span class="btn btn-outline-primary btn-sm w-100" style="font-size:0.75rem;">
                                Voir plus <i class="bi bi-arrow-right-short"></i>
                            </span>
                        </div>
                    </div>
                @endif

                @if (($collectives ?? collect())->isNotEmpty())
                    <div class="col-6 col-sm-4 col-md-4 col-lg-3">
                        <div class="card shadow-sm text-center p-2 hover-pointer" style="min-height:140px; border-radius:10px;"
                            onclick="toggleTable('table-collectives')">
                            <h6 class="card-title mb-2 text-truncate" title="Formations Collectives" style="font-size:0.85rem;">
                                Formations Collectives
                            </h6>

                            <!-- Badge type -->
                            <span class="etat-btn bg-success text-white px-2 py-1 rounded" style="font-size:0.75rem;">
                                Collective
                            </span>

                            <!-- Nombre de formés -->
                            <div class="d-flex flex-column align-items-center justify-content-center mb-2 mt-2">
                                <span class="h6 mb-0" style="font-size:1rem;">{{ ($collectives ?? collect())->count() }}</span>
                            </div>

                            <!-- Barre de pourcentage (optionnelle) -->
                            <div class="mb-2">
                                <div class="progress" style="height:6px; border-radius:3px;">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: 100%;"></div>
                                </div>
                                <small class="text-muted">100%</small>
                            </div>

                            <span class="btn btn-outline-success btn-sm w-100" style="font-size:0.75rem;">
                                Voir plus <i class="bi bi-arrow-right-short"></i>
                            </span>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Tableaux masqués par défaut --}}
            <div class="row">
                <div class="col-12">
                    <div id="table-individuelles-container" style="display:none;">
                        <h5>Formations individuelles - année {{ $annee }}</h5>
                        @if (($individuelles ?? collect())->isNotEmpty())
                            <table class="table table-striped" id="table-individuelles">
                                <thead>
                                    <tr>
                                        <th>N°</th>
                                        <th width='13%'>CIN</th>
                                        <th>Prénom</th>
                                        <th>Nom</th>
                                        <th>Date nais.</th>
                                        <th>Lieu nais.</th>
                                        <th>Module</th>
                                        {{-- <th>Dépôt</th> --}}
                                        <th>Statut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i = 1; @endphp
                                    @foreach ($individuelles as $ind)
                                        <tr>
                                            <td class="text-center">{{ $i++ }}</td>
                                            <td>{{ $ind->user->cin }}</td>
                                            <td>{{ $ind->user->firstname }}</td>
                                            <td>{{ $ind->user->name }}</td>
                                            <td>{{ $ind->user->date_naissance?->format('d/m/Y') }}</td>
                                            <td>{{ $ind->user->lieu_naissance }}</td>
                                            <td>{{ $ind->module->name ?? '-' }}</td>
                                            {{-- <td>{{ $ind->date_depot?->format('d/m/Y') ?? 'Aucun' }}</td> --}}
                                            <td>
                                                <span class="{{ $ind->statut }}">{{ $ind->statut }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="alert alert-info">Aucune demande individuelle pour l’année {{ $annee }}</div>
                        @endif
                    </div>

                    <div id="table-collectives-container" style="display:none;">
                        <h5>Formations collectives - année {{ $annee }}</h5>
                        @if (($collectives ?? collect())->isNotEmpty())
                            <table class="table table-striped" id="table-collectives">
                                <thead>
                                    <tr>
                                        <th>N°</th>
                                        <th>N° CIN (NIN)</th>
                                        <th>Prénom</th>
                                        <th>NOM</th>
                                        <th>Date nais.</th>
                                        <th>Lieu nais.</th>
                                        <th>Structure</th>
                                        <th>Dépôt</th>
                                        <th>Statut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i = 1; @endphp
                                    @foreach ($collectives as $col)
                                        <tr>
                                            <td class="text-center">{{ $i++ }}</td>
                                            <td class="text-center">{{ $col?->cin }}</td>
                                            <td>{{ $col?->prenom }}</td>
                                            <td>{{ $col?->nom }}</td>
                                            <td>{{ $col?->date_naissance?->format('d/m/Y') }}</td>
                                            <td>{{ $col?->lieu_naissance }}</td>
                                            <td>
                                                @if ($col->collective)
                                                    <a href="{{ route('collectives.show', $col->collective) }}"
                                                        target="_blank">
                                                        {{ $col->collective->sigle }}
                                                    </a>
                                                @else
                                                    <span>Aucun</span>
                                                @endif
                                            </td>
                                            <td>{{ $col?->created_at?->format('d/m/Y') ?? 'Aucun' }}</td>
                                            <td>
                                                <span class="{{ $col?->statut }}">{{ $col?->statut }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="alert alert-info">Aucune demande collective pour l’année {{ $annee }}</div>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    @endcan
@endsection

@push('scripts')
    <script>
        function toggleTable(tableId) {
            const tables = ['table-individuelles-container', 'table-collectives-container'];
            tables.forEach(id => {
                if (id !== tableId + '-container') {
                    document.getElementById(id).style.display = 'none';
                }
            });
            const el = document.getElementById(tableId + '-container');
            el.style.display = (el.style.display === 'none') ? 'block' : 'none';
        }
    </script>

    <script>
        $(document).ready(function() {
            $('#table-individuelles').DataTable({
                responsive: true,
                ordering: false,
                buttons: ['csv', 'excel', 'print']
            });
            $('#table-collectives').DataTable({
                responsive: true,
                ordering: false,
                buttons: ['csv', 'excel', 'print']
            });
        });
    </script>
@endpush
