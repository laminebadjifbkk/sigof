@extends('layouts.dashboard')

@section('title', 'Candidatures')

@section('content')
    <div class="dash-topbar">
        <div>
            <h2>Candidatures reçues</h2>
            <p class="muted-sub">Suivi des candidatures - programme traducteurs Dakar 2026</p>
        </div>
        <!-- <div class="topbar-right">
            <div class="search-box">
                <span>🔍</span>
                <input type="text" id="tableSearch" placeholder="Rechercher un candidat…">
            </div>
            <div class="avatar-bubble">{{ Auth::check() ? Str::upper(Str::substr(Auth::user()->name, 0, 2)) : 'FN' }}</div>
        </div>
        -->
    </div>

    <div class="panel">
        <div class="table-responsive">
            <h3>Liste des candidatures</h3>
            <table class="data-table table datatables align-middle" id="dataTableCandidature">
                <thead>
                    <tr>
                        <th>Candidat</th>
                        <th>Langue (LV1)</th>
                        <th>Niveau</th>
                        <th>Zone</th>
                        <th>Statut</th>
                        <!-- <th>Date</th> -->
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($candidatures ?? [] as $c)
                        <tr>
                            <td>
                                <div class="row-name">
                                    <span
                                        class="mini-avatar">{{ Str::upper(Str::substr($c?->user?->firstname, 0, 1) . Str::substr($c?->user?->name, 0, 1)) }}</span>
                                    {{ $c?->user?->civilite }} {{ $c?->user?->firstname }} {{ $c?->user?->name }}
                                </div>
                            </td>
                            <td>
                                <div class="lang-tags">
                                    <span class="lang-tag">{{ $c->langueSpecialisation->nom }}</span>
                                    <span class="lang-tag">Français</span>
                                </div>
                            </td>
                            <td>{{ $c->niveau_francais }}</td>
                            <td>{{ $c->zone_label }}</td>
                            <td>
                                <!-- <span class="status-pill {{ $c->statut }}">{{ ucfirst($c->statut) }}</span> -->
                                <span class="status-pill {{ $c->statut_classe }}">{{ $c->statut_label }}</span>
                            </td>
                            <!-- <td>{{ $c?->user?->date_naissance?->format('d/m/Y') }}</td> -->
                            <td>

                                @can('candidatures.voir')
                                    <a href="{{ route('candidatures.show', $c) }}" class="btn btn-sm btn-outline">
                                        Voir
                                    </a>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <!-- <tr>
                        <td colspan="6" class="empty-row">Aucune candidature pour le moment.</td>
                    </tr> -->
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        new DataTable('#dataTableCandidature', {
            ordering: false,
            layout: {
                topStart: {
                    buttons: ['csv', 'excel', 'print'],
                }
            },
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
                "sEmptyTable": "Aucune candidature disponible dans ce tableau pour le moment.",
                "oPaginate": {
                    "sFirst": "Premier",
                    "sPrevious": "Pr&eacute;c&eacute;dent",
                    "sNext": "Suivant",
                    "sLast": "Dernier"
                },
                "oAria": {
                    "sSortAscending": ": activer pour trier la colonne par ordre croissant",
                    "sSortDescending": ": activer pour trier la colonne par ordre d&eacute;croissant"
                }
            }
        });
    </script>
@endpush
