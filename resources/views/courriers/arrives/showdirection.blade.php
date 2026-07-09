@extends('layout.user-layout')
@section('title', 'COURRIER ARRIVE, ' . $arrive?->courrier?->objet)
@section('space-work')
    <section class="section profile">
        <div class="container-fluid">
            <div class="row">
                @if ($message = Session::get('status'))
                    <div class="alert alert-success bg-success text-light border-0 alert-dismissible fade show"
                        role="alert">
                        <strong>{{ $message }}</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <div class="alert alert-danger bg-danger text-light border-0 alert-dismissible fade show"
                            role="alert">
                            <strong>{{ $error }}</strong>
                        </div>
                    @endforeach
                @endif

                @can('imputer-courrier-employe')
                    <span class="d-flex mt-2 align-items-baseline">
                        <a href="{{ route('arrives.index') }}"
                            class="btn btn-outline-primary btn-sm d-flex align-items-center mb-4">
                            <i class="bi bi-arrow-left-circle me-1"></i> Retour à la liste
                        </a>
                    </span>
                @endcan
                @can('imputer-employe-courrier')
                    <span class="d-flex mt-2 align-items-baseline">
                        <a href="{{ route('mescourriers') }}" class="btn btn-info btn-sm" title="Retour">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>&nbsp;
                        <p> | Liste des courriers arrivés</p>
                    </span>
                @endcan

                <div class="col-12">
                    <div class="card border-info mb-3">
                        <div class="card-body pt-3">
                            <ul class="nav nav-tabs nav-tabs-bordered">
                                @can('imputer-courrier-employe')
                                    {{-- @can('imputer', $arrive) --}}
                                    <li class="nav-item">
                                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#imputer_courrier"
                                            type="button">
                                            Imputations
                                        </button>
                                    </li>
                                    {{-- @endcan --}}
                                @endcan

                                <li class="nav-item">
                                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-overview">Détails
                                        courrier</button>
                                </li>

                                {{-- @can('update', $arrive)
                                    <li class="nav-item">
                                        <button class="nav-link" data-bs-toggle="tab"
                                            data-bs-target="#modifier_courrier">Modifier</button>
                                    </li>
                                @endcan --}}
                                <li class="nav-item">
                                    <button class="nav-link" data-bs-toggle="tab"
                                        data-bs-target="#profile-settings">Commentaires</button>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link" data-bs-toggle="tab"
                                        data-bs-target="#audit">Historiques</button>
                                </li>
                            </ul>
                            <div class="tab-content pt-0">
                                @can('imputer-courrier-employe')
                                    <div class="tab-pane fade show active pt-4" id="imputer_courrier">
                                        <div class="container-fluid">

                                            <div class="card shadow-lg border-0">

                                                <!-- ===== HEADER ===== -->
                                                <div
                                                    class="card-header bg-white d-flex justify-content-between align-items-center border-bottom">
                                                    <div>
                                                        <h4 class="fw-bold text-primary mb-0">
                                                            <i class="bi bi-folder-check me-2"></i>Imputation du courrier
                                                        </h4>
                                                        <small class="text-muted">Affectation aux employés</small>
                                                    </div>
                                                </div>

                                                <div class="card-body">

                                                    <!-- ===== INFOS COURRIER ===== -->
                                                    <div class="row mb-4">
                                                        <div class="col-md-6">
                                                            <label class="text-muted small">Expéditeur</label>
                                                            <div class="fw-semibold fs-6">
                                                                {{ $arrive?->courrier?->expediteur }}</div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="text-muted small">Objet</label>
                                                            <div class="fw-semibold fs-6">{{ $arrive?->courrier?->objet }}
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <hr>

                                                    <div class="mb-3">
                                                        <h5 class="fw-bold text-primary">
                                                            <i class="bi bi-list-check me-1"></i>Imputations
                                                        </h5>
                                                        {{-- <form action="{{ route('courriers.notifySend', $arrive->id) }}"
                                                            method="POST">
                                                            @csrf

                                                            <button type="submit" class="btn btn-primary btn-sm">
                                                                <i class="bi bi-envelope-paper me-1"></i>
                                                                Informer par email
                                                            </button>
                                                        </form> --}}
                                                    </div>

                                                    <div class="table-responsive mb-4">
                                                        <table class="table table-hover align-middle table-bordered">
                                                            <thead style="background-color: #cfe2ff; color: #0d6efd;">
                                                                <!-- bleu clair -->
                                                                <tr>
                                                                    <th>Employé</th>
                                                                    <th>Direction</th>
                                                                    {{-- <th width="5%">Action</th> --}}
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @forelse ($arrive->employees as $employe)
                                                                    <tr class="align-middle">
                                                                        <td>
                                                                            <input type="text"
                                                                                value="{{ $employe->user->firstname }} {{ $employe->user->name }}"
                                                                                class="form-control form-control-sm border-0 bg-light"
                                                                                readonly>
                                                                        </td>
                                                                        <td>
                                                                            <input type="text"
                                                                                value="{{ $employe->direction->name ?? '' }}"
                                                                                class="form-control form-control-sm border-0 bg-light"
                                                                                readonly>
                                                                        </td>
                                                                        {{-- <td class="text-center">
                                                                            @can('imputer-courrier')
                                                                                <form method="POST"
                                                                                    action="{{ route('arrives.detachEmployee', [$arrive->id, $employe->id]) }}">
                                                                                    @csrf
                                                                                    @method('DELETE')
                                                                                    <button type="submit"
                                                                                        class="btn btn-outline-danger btn-sm show_confirm_detach">
                                                                                        <i class="bi bi-x-circle"></i>
                                                                                    </button>
                                                                                </form>
                                                                            @endcan
                                                                        </td> --}}
                                                                    </tr>
                                                                @empty
                                                                    <tr>
                                                                        <td colspan="3" class="text-center text-muted">
                                                                            Aucune ancienne imputation</td>
                                                                    </tr>
                                                                @endforelse
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card shadow-lg border-0">
                                                <div class="card-body">

                                                    <div class="mb-3">
                                                        <h5 class="fw-bold text-success">
                                                            <i class="bi bi-diagram-3 me-1"></i>
                                                            Transférer - {{ $direction?->name }}
                                                        </h5>
                                                    </div>

                                                    <form method="POST"
                                                        action="{{ route('arrives.attachMultipleEmployees', $arrive->id) }}">
                                                        @csrf

                                                        <div class="table-responsive">
                                                            <table class="table table-hover table-bordered align-middle">

                                                                <thead class="table-success">
                                                                    <tr>
                                                                        <th width="5%">
                                                                            <input type="checkbox" id="checkAll">
                                                                        </th>
                                                                        <th>Employé</th>
                                                                        <th>Fonction</th>
                                                                        {{-- <th>Email</th> --}}
                                                                        <th>Téléphone</th>
                                                                        <th width="5%">Action</th>
                                                                    </tr>
                                                                </thead>

                                                                <tbody>
                                                                    @forelse($employeesDirections as $employee)
                                                                        <tr>
                                                                            <td>
                                                                                <input type="checkbox" name="employees[]"
                                                                                    value="{{ $employee->id }}"
                                                                                    class="emp-checkbox">
                                                                            </td>

                                                                            <td>
                                                                                {{ $employee->user?->firstname }}
                                                                                {{ $employee->user?->name }}
                                                                            </td>

                                                                            <td>
                                                                                {{ $employee->fonction?->name }}
                                                                            </td>

                                                                            {{-- <td>
                                                                                <a href="mailto:{{ $employee->user?->email }}">
                                                                                    {{ $employee->user?->email }}
                                                                                </a>
                                                                            </td> --}}

                                                                            <td>
                                                                                <a
                                                                                    href="tel:+221{{ $employee->user?->telephone }}">
                                                                                    {{ $employee->user?->telephone }}
                                                                                </a>
                                                                            </td>

                                                                            <td>

                                                                                @can('employe-show')
                                                                                    <span
                                                                                        class="d-flex mt-2 align-items-baseline"><a
                                                                                            href="{{ route('employes.show', $employee) }}"
                                                                                            class="btn btn-success btn-sm mx-1"
                                                                                            title="voir détails"><i
                                                                                                class="bi bi-eye"></i></a>
                                                                                    </span>
                                                                                @endcan
                                                                            </td>
                                                                        </tr>
                                                                    @empty
                                                                        <tr>
                                                                            <td colspan="4" class="text-center text-muted">
                                                                                Aucun employé disponible pour un transfert du courrier.
                                                                            </td>
                                                                        </tr>
                                                                    @endforelse
                                                                </tbody>

                                                            </table>
                                                        </div>

                                                        <button type="submit" class="btn btn-outline-success btn-sm mt-2">
                                                            <i class="bi bi-check2-circle"></i>
                                                            Transférer le courrier à la sélection
                                                        </button>

                                                    </form>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                @endcan

                                <div class="tab-pane fade profile-overview" id="profile-overview">

                                    <h5 class="card-title">Détails</h5>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label ">Objet</div>
                                        <div class="col-lg-9 col-md-8">{{ $arrive?->courrier?->objet }}</div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label ">N° courrier arrivé</div>
                                        <div class="col-lg-3 col-md-4">{{ $arrive?->numero_arrive }}</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label ">Date arrivé</div>
                                        <div class="col-lg-3 col-md-4">
                                            {{ $arrive?->courrier?->date_recep?->translatedFormat('l jS F Y') }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Date correspondance</div>
                                        <div class="col-lg-3 col-md-4">
                                            {{ $arrive?->courrier?->date_cores?->translatedFormat('l jS F Y') }}
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label ">N° correspondance</div>
                                        <div class="col-lg-3 col-md-4">{{ $arrive?->courrier?->numero_courrier }}</div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Année</div>
                                        <div class="col-lg-3 col-md-4">{{ $arrive?->courrier?->annee }}</div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Expéditeur</div>
                                        <div class="col-lg-3 col-md-4">{{ $arrive?->courrier?->expediteur }}</div>
                                    </div>
                                    <div class="row">
                                        @if (!empty($arrive?->courrier?->reference))
                                            <div class="col-lg-3 col-md-4 label">Référence</div>
                                            <div class="col-lg-3 col-md-4">{{ $arrive?->courrier?->reference }}</div>
                                        @endif
                                    </div>

                                    @if (!empty($arrive?->courrier?->numero_reponse))
                                        <div class="row">
                                            <div class="col-lg-3 col-md-4 label ">N° réponse</div>
                                            <div class="col-lg-3 col-md-4">{{ $arrive?->courrier?->numero_reponse }}</div>
                                            <div class="col-lg-3 col-md-4 label">Date réponse</div>
                                            <div class="col-lg-3 col-md-4">
                                                {{ $arrive?->courrier?->date_reponse?->format('d/m/Y') }}
                                            </div>
                                        </div>
                                    @endif

                                    @if (!empty($arrive?->courrier?->observation))
                                        <h5 class="card-title">Observations</h5>
                                        <p class="small fst-italic">{{ $arrive?->courrier?->observation }}.</p>
                                    @endif

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Imputation</div>
                                        <div class="col-lg-9 col-md-8">
                                            @if ($arrive?->employees && $arrive->employees->isNotEmpty())
                                                <?php $i = 1; ?>
                                                @foreach ($arrive->employees as $employee)
                                                    {{ $i++ }}. {!! $employee->user->firstname . ' ' . $employee->user->name !!}
                                                    @if ($employee?->fonction?->sigle)
                                                        <b>[{!! $employee?->fonction?->sigle ?? '' !!}]</b>
                                                    @endif
                                                    <br>
                                                @endforeach
                                            @else
                                                <div class="alert alert-warning">Aucune imputation pour ce courrier</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Prévisualisation du scan</div>
                                        <div class="col-lg-9 col-md-8">
                                            @if (isset($arrive?->courrier?->file))
                                                <a href="{{ asset($arrive?->courrier?->getFile()) }}" target="_blank"
                                                    class="btn btn-primary btn-sm">
                                                    <i class="bi bi-download"></i> Télécharger le scan
                                                </a>
                                            @else
                                                <div class="alert alert-info mt-2">Aucun fichier disponible pour ce
                                                    courrier.</div>
                                            @endif
                                        </div>
                                    </div>

                                </div>

                                <div class="tab-pane fade pt-3" id="profile-settings">

                                    <div class="mb-4">
                                        <h5 class="fw-bold text-primary">
                                            <i class="bi bi-chat-left-text me-1"></i> Ajouter un commentaire
                                        </h5>
                                    </div>

                                    <form method="POST" action="{{ route('comments.store', $arrive?->courrier) }}"
                                        class="mb-4">
                                        @csrf
                                        <div class="form-floating mb-3">
                                            <textarea class="form-control @error('commentaire') is-invalid @enderror" placeholder="Écrire votre commentaire..."
                                                name="commentaire" id="commentaire" style="height: 100px;"></textarea>
                                            <label for="commentaire">Écrire votre commentaire...</label>
                                            @error('commentaire')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-sm px-4 shadow-sm rounded-3">
                                            <i class="bi bi-send me-1"></i> Poster
                                        </button>
                                    </form>

                                    <hr>

                                    <!-- ===== LISTE DES COMMENTAIRES ===== -->
                                    <h5 class="fw-bold text-secondary mb-3 text-center">
                                        <i class="bi bi-chat-text me-1"></i> Commentaires
                                    </h5>

                                    @forelse ($arrive?->courrier?->comments as $comment)
                                        <div class="card mb-3 shadow-sm">
                                            <div class="card-body">
                                                <p class="mb-2">{!! $comment?->content !!}</p>
                                                <div
                                                    class="d-flex justify-content-between align-items-center small text-muted">
                                                    <span>Posté {{ $comment?->created_at?->diffForHumans() }}</span>
                                                    <span class="badge bg-info">{{ $comment?->user?->firstname ?? '' }}
                                                        {{ $comment?->user?->name ?? '' }}</span>
                                                </div>

                                                {{-- Réponses --}}
                                                @foreach ($comment?->comments as $replayComment)
                                                    <div class="card mt-2 ms-4 shadow-sm">
                                                        <div class="card-body">
                                                            <p class="mb-2">{!! $replayComment?->content !!}</p>
                                                            <div
                                                                class="d-flex justify-content-between align-items-center small text-muted">
                                                                <span>Posté
                                                                    {{ $replayComment?->created_at?->diffForHumans() }}</span>
                                                                <span
                                                                    class="badge bg-primary">{{ $replayComment?->user?->firstname ?? '' }}
                                                                    {{ $replayComment?->user?->name ?? '' }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach

                                                @auth
                                                    <button class="btn btn-outline-info btn-sm mt-2"
                                                        onclick="toggleReplayComment({{ $comment?->id }})">
                                                        <i class="bi bi-reply me-1"></i> Répondre
                                                    </button>
                                                    <form method="POST"
                                                        action="{{ route('comments.storeReply', $comment) }}"
                                                        class="d-none mt-2" id="replayComment-{{ $comment?->id }}">
                                                        @csrf
                                                        <div class="form-floating mb-2">
                                                            <textarea class="form-control @error('replayComment') is-invalid @enderror" placeholder="Répondre à ce commentaire"
                                                                name="replayComment" style="height: 80px;"></textarea>
                                                            <label>Répondre à ce commentaire</label>
                                                            @error('replayComment')
                                                                <small class="text-danger">{{ $message }}</small>
                                                            @enderror
                                                        </div>
                                                        <button type="submit" class="btn btn-primary btn-sm">
                                                            <i class="bi bi-send me-1"></i> Répondre
                                                        </button>
                                                    </form>
                                                @endauth
                                            </div>
                                        </div>
                                    @empty
                                        <div class="alert alert-info">Aucun commentaire pour ce courrier.</div>
                                    @endforelse
                                </div>
                                <div class="tab-pane fade pt-3" id="audit">
                                    <div class="card border-info shadow-sm mb-3">
                                        <div class="card-header bg-info text-white text-center fw-bold">
                                            <i class="bi bi-clipboard-data me-1"></i> Historiques
                                        </div>
                                        <div class="card-body d-flex flex-column gap-2">
                                            <h5 class="card-title text-primary fw-semibold">Informations complémentaires
                                            </h5>

                                            <p class="mb-1">
                                                <span class="badge bg-success me-1">Créé par</span>
                                                <b>{{ $user_create_name }}</b> —
                                                <small
                                                    class="text-muted">{{ $arrive?->courrier?->created_at?->diffForHumans() }}</small>
                                            </p>

                                            @if ($arrive?->courrier?->created_at != $courrier?->updated_at)
                                                <p class="mb-0">
                                                    <span class="badge bg-warning text-dark me-1">Modifié par</span>
                                                    <b>{{ $user_update_name }}</b> —
                                                    <small
                                                        class="text-muted">{{ $courrier?->updated_at?->diffForHumans() }}</small>
                                                </p>
                                            @else
                                                <p class="mb-0">
                                                    <span class="badge bg-secondary">Jamais modifié</span>
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        function toggleReplayComment(id) {
            let element = document.getElementById('replayComment-' + id);
            element.classList.toggle('d-none');
        }
        $(document).ready(function() {

            // ================================
            // AJOUT DYNAMIQUE D'EMPLOYÉ
            // ================================
            $('#addMore').on('click', function(e) {
                e.preventDefault();

                let product = $("#product").val().trim();
                let id_emp = $("#id_emp").val();
                let direction = $("#direction").val().trim();
                let id_direction = $("#id_direction").val();

                if (product === '' || id_emp === '') {
                    alert("Veuillez sélectionner un employé valide.");
                    return;
                }

                let newRow = `
        <tr class="align-middle">
            <td>
                <input type="hidden" name="id_emp[]" value="${id_emp}">
                <input type="text" name="product[]" value="${product}" 
                       class="form-control form-control-sm border-0 bg-transparent" readonly>
            </td>
            <td>
                <input type="hidden" name="id_direction[]" value="${id_direction}">
                <input type="text" name="direction[]" value="${direction}" 
                       class="form-control form-control-sm border-0 bg-transparent" readonly>
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-outline-danger removeRow">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        </tr>
        `;

                $('#addRow').append(newRow);

                // Reset champs
                $("#product").val('');
                $("#id_emp").val('');
                $("#direction").val('');
                $("#id_direction").val('');
            });

            // ================================
            // SUPPRESSION LIGNE
            // ================================
            $(document).on('click', '.removeRow', function() {
                $(this).closest('tr').remove();
            });

            // ================================
            // RECHERCHE EMPLOYÉ (AJAX)
            // ================================
            $('#product').keyup(function() {
                let query = $(this).val();
                if (query.length < 2) {
                    $('#productList').fadeOut();
                    return;
                }

                $.ajax({
                    url: "{{ route('arrive.fetch') }}",
                    method: "POST",
                    data: {
                        query: query,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(data) {
                        $('#productList').fadeIn().html(data);
                    }
                });
            });

            // ================================
            // SÉLECTION EMPLOYÉ DANS LISTE
            // ================================
            $(document).on('click', '#productList li', function() {
                $('#product').val($(this).text());
                $('#id_emp').val($(this).data("id"));
                $('#direction').val($(this).data("direction"));
                $('#id_direction').val($(this).data("iddirection"));

                $('#productList').fadeOut();
            });

            // ================================
            // CACHER LISTE SI CLICK AILLEURS
            // ================================
            $(document).click(function(e) {
                if (!$(e.target).closest('#product').length) {
                    $('#productList').fadeOut();
                }
            });

        });

        document.getElementById('checkAll').addEventListener('click', function() {
            let checkboxes = document.querySelectorAll('.emp-checkbox');
            checkboxes.forEach(cb => cb.checked = this.checked);
        });
    </script>
@endpush
