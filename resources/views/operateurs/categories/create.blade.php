@extends('layout.user-layout')
@section('title', 'Enregistrement categorie opérateurs')
@section('space-work')
    <section class="section">
        <div class="row justify-content-center">
            <div class="col-lg-8">
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
                            role="alert"><strong>{{ $error }}</strong></div>
                    @endforeach
                @endif
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12 pt-4">
                                <div class="d-flex align-items-center gap-2">
                                    <a href="{{ route('operateurcategories.index') }}"
                                        class="btn btn-outline-success btn-sm rounded-pill shadow-sm"
                                        title="Retour à la liste">
                                        <i class="bi bi-arrow-counterclockwise me-1"></i> Retour
                                    </a>
                                    <span class="text-muted small">| Liste des catégories</span>
                                </div>
                            </div>
                        </div>

                        <h5 class="card-title mt-4 fw-semibold text-primary">
                            <i class="bi bi-plus-circle me-1"></i> Création d'une catégorie
                        </h5>
                        <!-- categorie -->
                        <form method="post" action="{{ route('operateurcategories.store') }}" enctype="multipart/form-data"
                            class="row g-3">
                            @csrf
                            <table class="table table-bordered" id="dynamicAddRemove">
                                <tr>
                                    <th>categories<span class="text-danger mx-1">*</span></th>
                                    <th width="5%">Action</th>
                                </tr>
                                <tr>
                                    <td><input type="text" name="categories[0][name]" placeholder="Entrer une categorie"
                                            class="form-control form-control-sm" autofocus /></td>
                                    <td><button type="button" name="add" id="add-btn" class="btn btn-sm btn-success"
                                            title="Ajouter une ligne">Ajouter</button>
                                    </td>
                                </tr>
                            </table>
                            <div class="col-12 text-left mt-2">
                                <button type="submit" class="btn btn-sm btn-outline-success"><i
                                        class="far fa-save"></i>&nbsp;Sauvegarder</button>
                            </div>

                        </form><!-- End categorie -->
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script type="text/javascript">
        var i = 0;
        $("#add-btn").click(function() {
            ++i;
            $("#dynamicAddRemove").append('<tr><td><input type="text" name="categories[' + i +
                '][name]" placeholder="Entrer une autre catégorie" class="form-control form-control-sm" /></td><td><button type="button" class="btn btn-sm btn-danger remove-tr">Supprimer</button></td></tr>'
            );
        });
        $(document).on('click', '.remove-tr', function() {
            $(this).parents('tr').remove();
        });
    </script>
@endpush
