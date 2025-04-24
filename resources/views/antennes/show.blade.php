@extends('layout.user-layout')
@section('title', $antenne?->name)
@section('space-work')
    {{-- @hasrole([$antenne->code, 'CAR', 'super-admin']) --}}
    @hasrole("{$antenne->code}|CAR|super-admin")
        <section class="section">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    @if ($message = Session::get('status'))
                        <div class="alert alert-success bg-success text-light border-0 alert-dismissible fade show"
                            region="alert">
                            <strong>{{ $message }}</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <div class="card">
                        <div class="card-body">

                            <div class="row">
                                <div class="col-sm-12 pt-0">
                                    <span class="d-flex mt-2 align-items-baseline"><a href="{{ url('/profil') }}"
                                            class="btn btn-success btn-sm" title="retour"><i
                                                class="bi bi-arrow-counterclockwise"></i></a>&nbsp;
                                        <p> | retour</p>
                                    </span>
                                </div>
                            </div>
                            <h5 class="card-title">{{ $antenne?->name }}</h5>
                            <table class="table datatables align-middle justify-content-center" id="table-departements">
                                <thead>
                                    <tr>
                                        <th width="5%" class="text-center">N°</th>
                                        <th>Région</th>
                                        <th width="10%" class="text-center">Demandes</th>
                                        <th class="text-center" width="5%">#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; ?>
                                    @foreach ($antenne->regions as $region)
                                        <tr>
                                            <td style="text-align: center;">{{ $i++ }}</td>
                                            <td>{{ $region->nom }}</td>
                                            <td style="text-align: center;">
                                                <span>
                                                    {{ count($region->individuelles) }}
                                                </span>
                                            </td>
                                            <td style="text-align: center;"><span class="d-flex mt-2 align-items-baseline"><a
                                                        href="{{ route('regiondemandeurs.show', $region->id) }}"
                                                        class="btn btn-warning btn-sm mx-1" title="Voir détails">
                                                        <i class="bi bi-eye"></i></a>
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endhasrole
@endsection
@push('scripts')
    <script>
        new DataTable('#table-departements', {
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
