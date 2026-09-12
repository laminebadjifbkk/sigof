@extends('layout.user-layout')

@section('space-work')

<div class="container-fluid">

    {{-- En-tête --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">

        <div>

            <h4 class="mb-1">
                <i class="bi bi-cloud-arrow-up me-2"></i>
                Ajouter un document
            </h4>

            <div class="text-muted">
                Activité :
                <strong>{{ $activite->titre }}</strong>
            </div>

        </div>

        <a href="{{ route('onfp.activites.documents.index', $activite) }}"
           class="btn btn-sm btn-outline-secondary">

            <i class="bi bi-arrow-left me-1"></i>
            Retour aux documents

        </a>

    </div>


    {{-- Erreurs --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <div class="fw-semibold mb-2">
                <i class="bi bi-exclamation-triangle me-2"></i>
                Veuillez corriger les erreurs suivantes :
            </div>

            <ul class="mb-0">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    <div class="row">

        <div class="col-lg-8">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white border-bottom">

                    <h5 class="mb-1">
                        Informations du document
                    </h5>

                    <small class="text-muted">
                        Sélectionnez le fichier et renseignez les informations associées.
                    </small>

                </div>

                <div class="card-body">

                    <form method="POST"
                          action="{{ route('onfp.activites.documents.store', $activite) }}"
                          enctype="multipart/form-data">

                        @csrf

                        @include('onfp.activites.documents._form')

                        <div class="d-flex justify-content-end gap-2 mt-4">

                            <a href="{{ route('onfp.activites.documents.index', $activite) }}"
                               class="btn btn-sm btn-light border">

                                Annuler

                            </a>

                            <button type="submit"
                                    class="btn btn-sm btn-primary">

                                <i class="bi bi-cloud-arrow-up me-1"></i>
                                Ajouter le document

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>


        {{-- Aide --}}
        <div class="col-lg-4 mt-4 mt-lg-0">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white">

                    <h6 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i>
                        Informations
                    </h6>

                </div>

                <div class="card-body">

                    <p class="text-muted small">
                        Vous pouvez joindre les pièces nécessaires au suivi de
                        l'activité : TDR, rapports, procès-verbaux, courriers,
                        photos, tableaux, présentations, etc.
                    </p>

                    <hr>

                    <div class="small">

                        <div class="d-flex gap-2 mb-3">

                            <i class="bi bi-file-earmark text-primary"></i>

                            <div>
                                <strong>Formats acceptés</strong><br>
                                PDF, Word, Excel, PowerPoint, images et ZIP.
                            </div>

                        </div>


                        <div class="d-flex gap-2 mb-3">

                            <i class="bi bi-hdd text-primary"></i>

                            <div>
                                <strong>Taille maximale</strong><br>
                                20 Mo par fichier.
                            </div>

                        </div>


                        <div class="d-flex gap-2">

                            <i class="bi bi-check-circle text-success"></i>

                            <div>
                                <strong>Document final</strong><br>
                                Cochez cette option lorsque le document constitue
                                la version définitive.
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection
