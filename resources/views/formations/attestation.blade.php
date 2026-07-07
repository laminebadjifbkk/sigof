@extends('layout.user-layout')
@section('title', 'ONFP - ATTESTATIONS')
@section('space-work')

    <div class="pagetitle">
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/home') }}">Accueil</a></li>
                <li class="breadcrumb-item">Tables</li>
                <li class="breadcrumb-item active">Données</li>
            </ol>
        </nav>
    </div>


    <section class="section">
        <div class="container-fluid">
            <!-- End Title -->
            <div class="row justify-content-center">
                <div class="flex items-center gap-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="tab-pane fade show active profile-overview" id="modules-overview">
                                    <div class="col-12 pt-1">
                                        <div class="row">
                                            <!-- Sales Card -->
                                            @foreach ($groupes as $statut => $items)
                                                <div class="col-12 col-md-4 col-lg-2 col-sm-12 col-xs-12 col-xxl-2">
                                                    <div class="card info-card sales-card shadow-sm"
                                                        style="max-width: 220px;">
                                                        <div class="card-body p-2">
                                                            <h5 class="card-title text-truncate mb-1"
                                                                title="{{ $statut }}" style="font-size: 1rem;">
                                                                {{ $statut }}
                                                            </h5>
                                                            <div class="d-flex align-items-center mb-2">
                                                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-primary text-white"
                                                                    style="width: 32px; height: 32px; font-size: 1.25rem;">
                                                                    <i class="bi bi-people"></i>
                                                                </div>
                                                                <div class="ps-2">
                                                                    <h6 class="mb-0" style="font-size: 0.9rem;">
                                                                        {{ number_format($items->count(), 0, '', ' ') }}
                                                                    </h6>
                                                                    <span class="text-muted small">formation(s)</span>
                                                                </div>
                                                            </div>
                                                            <a href="#"
                                                                class="btn btn-outline-primary btn-sm w-100 d-flex align-items-center justify-content-center py-1"
                                                                style="font-size: 0.85rem; gap: 6px;">
                                                                Voir plus <i class="bi bi-arrow-right-short"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Bordered Tabs -->
                    </div>
                </div>
            </div>
        </div>
        <!-- End Modal -->
        </div>
        <!-- End Left side columns -->
        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            {{-- <h4 class="card-title">ATTESTATIONS</h4> --}}

                            <div class="pt-1">
                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">

                                    {{-- Titre à gauche --}}
                                    <div class="d-flex align-items-center gap-2">
                                        <h6 class="mb-0 text-muted fw-semibold text-uppercase">
                                            Liste des attestations
                                        </h6>
                                    </div>

                                    {{-- Total au centre --}}
                                    @php
                                        $affichees = $attestations?->count(); // à adapter si tu fais une pagination
                                        $total = $totalAttestations ?? $attestations?->count(); // en cas de pagination avec ->total()
                                    @endphp

                                    <div class="d-flex align-items-center gap-2 text-info fw-semibold">
                                        <i class="bi bi-list-ul me-1"></i>
                                        <span>
                                            Affichage :
                                            <span class="text-dark">{{ $affichees }}</span>
                                            sur
                                            <span class="text-dark">{{ $total }}</span> demandes
                                        </span>
                                    </div>

                                </div>
                            </div>

                            <div class="table-responsive">
                                <table
                                    class="table datatables table-bordered table-hover align-middle justify-content-center"
                                    id="table-formations">
                                    <thead>
                                        <tr>
                                            <th>Bénéficiaires</th>
                                            {{-- <th width='10%'>Région</th> --}}
                                            <th>Modules</th>
                                            <th>Opérateurs</th>
                                            <th width='5%' class="text-center">Attestations</th>
                                            <th width='3%'><i class="bi bi-gear"></i></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1; ?>
                                        @foreach ($attestations as $formation)
                                            <tr>
                                                <td>{{ $formation?->name }}</td>
                                                {{-- <td>{{ $formation->departement?->region?->nom }}</td> --}}
                                                <td>
                                                    {{ $formation?->module?->name ?? ($formation?->collectivemodule?->module ?? '') }}
                                                </td>
                                                <td>{{ $formation?->operateur?->user?->display_operateur ?? ' ' }}</td>
                                                <td class="text-center"><a><span
                                                            class="{{ $formation?->attestation }}">{{ $formation?->attestation }}</span></a>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <!-- Bouton Voir détails -->
                                                        <a href="{{ route('formations.show', $formation) }}"
                                                            class="btn btn-primary btn-sm" title="Voir les détails"
                                                            target="_blank">
                                                            <i class="bi bi-eye"></i>
                                                        </a>

                                                        <!-- Bouton Statuer l'attestation -->
                                                        <button class="btn btn-warning btn-sm mx-1" data-bs-toggle="modal"
                                                            data-bs-target="#statuerAttestationModal-{{ $formation->id }}"
                                                            title="Statuer l'attestation">
                                                            <i class="bi bi-arrow-left-right"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                            <div class="modal fade" id="statuerAttestationModal-{{ $formation->id }}"
                                                tabindex="-1" aria-labelledby="changerModuleLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                    <form action="{{ route('attestations.check', $formation->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="changerModuleLabel">
                                                                    {{ $formation?->name }}</h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Fermer"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label for="attestation"
                                                                        class="form-label">{{ $formation->module->name ?? ($formation->collectivemodule->module ?? 'Aucun') }}</label>
                                                                    @php
                                                                        $statuts = [
                                                                            'Nouveau' => 'Nouveau',
                                                                            'Attente' => 'Attente',
                                                                            'En cours' => 'En cours',
                                                                            'Signature' => 'Signature',
                                                                            'Disponibles' => 'Disponibles',
                                                                            'Délivrés' => 'Délivrés',
                                                                        ];
                                                                    @endphp

                                                                    <select name="attestation" id="attestation"
                                                                        class="form-select form-select-sm" required>
                                                                        <option value="" disabled
                                                                            {{ empty($formation?->attestation) ? 'selected' : '' }}>
                                                                            -- Choisir --
                                                                        </option>

                                                                        @foreach ($statuts as $value => $label)
                                                                            <option value="{{ $value }}"
                                                                                {{ $formation?->attestation === $value ? 'selected' : '' }}>
                                                                                {{ $label }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>

                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary btn-sm"
                                                                    data-bs-dismiss="modal">Fermer</button>
                                                                <button type="submit"
                                                                    class="btn btn-success btn-sm">Valider</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    @endsection
    @push('scripts')
        <script>
            new DataTable('#table-formations', {
                layout: {
                    topStart: {
                        buttons: ['csv', 'excel', 'print'],
                    }
                },
                "order": [
                    [0, 'desc']
                ],
                language: {
                    "sProcessing": "Traitement en cours...",
                    "sSearch": "Rechercher&nbsp;:",
                    "sLengthMenu": "Afficher _MENU_ &eacute;l&eacute;ments",
                    "sInfo": "Affichage de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
                    "sInfoEmpty": "Affichage de l'&eacute;l&eacute;ment 0 &agrave; 0 sur 0 &eacute;l&eacute;ment",
                    "sInfoFiltered": "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
                    "sInfoPostFix": "",
                    "sLoadingRecords": "Chargement en cours...",
                    "sZeroRecords": "Aucun &eacute;l&eacute;ment &agrave; afficher",
                    "sEmptyTable": "Aucune donn&eacute;e disponible dans le tableau",
                    "oPaginate": {
                        "sFirst": "Premier",
                        "sPrevious": "Pr&eacute;c&eacute;dent",
                        "sNext": "Suivant",
                        "sLast": "Dernier"
                    },
                    "oAria": {
                        "sSortAscending": ": activer pour trier la colonne par ordre croissant",
                        "sSortDescending": ": activer pour trier la colonne par ordre d&eacute;croissant"
                    },
                    "select": {
                        "rows": {
                            _: "%d lignes sÃ©lÃ©ctionnÃ©es",
                            0: "Aucune ligne sÃ©lÃ©ctionnÃ©e",
                            1: "1 ligne sÃ©lÃ©ctionnÃ©e"
                        }
                    }
                }
            });
        </script>
    @endpush
