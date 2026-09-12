@extends('layout.user-layout')

@section('space-work')

    <div class="container-fluid">

        {{-- En-tête --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">

            <div>
                <h4 class="mb-1">
                    <i class="bi bi-folder2-open me-2"></i>
                    Documents
                </h4>

                <div class="text-muted">
                    Activité :
                    <strong>{{ $activite->titre }}</strong>
                </div>
            </div>

            <div class="d-flex gap-2">

                <a href="{{ route('onfp.activites.show', $activite) }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>
                    Retour à l'activité
                </a>

                <a href="{{ route('onfp.activites.documents.create', $activite) }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-plus-lg me-1"></i>
                    Ajouter un document
                </a>

            </div>

        </div>


        {{-- Messages --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle me-2"></i>
                {{ session('success') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert">
                </button>
            </div>
        @endif


        {{-- Statistiques --}}
        <div class="row g-3 mb-4">

            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>
                                <div class="text-muted small">
                                    Documents
                                </div>

                                <div class="fs-3 fw-bold">
                                    {{ $documents->total() }}
                                </div>
                            </div>

                            <div class="fs-2 text-primary">
                                <i class="bi bi-files"></i>
                            </div>

                        </div>

                    </div>
                </div>
            </div>


            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>
                                <div class="text-muted small">
                                    Documents finaux
                                </div>

                                <div class="fs-3 fw-bold">
                                    {{ $activite->documents()->where('document_final', true)->count() }}
                                </div>
                            </div>

                            <div class="fs-2 text-success">
                                <i class="bi bi-check2-circle"></i>
                            </div>

                        </div>

                    </div>
                </div>
            </div>


            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>
                                <div class="text-muted small">
                                    Dernier document
                                </div>

                                <div class="fw-bold">
                                    @if ($documents->count())
                                        {{ $documents->first()->created_at?->format('d/m/Y') }}
                                    @else
                                        —
                                    @endif
                                </div>
                            </div>

                            <div class="fs-2 text-warning">
                                <i class="bi bi-clock-history"></i>
                            </div>

                        </div>

                    </div>
                </div>
            </div>

        </div>


        {{-- Liste --}}
        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white border-bottom">

                <div class="d-flex justify-content-between align-items-center">

                    <div>
                        <h5 class="mb-0">
                            <i class="bi bi-paperclip me-2"></i>
                            Documents associés
                        </h5>

                        <small class="text-muted">
                            Rapports, TDR, PV, photos et autres pièces liées à l'activité.
                        </small>
                    </div>

                </div>

            </div>


            <div class="card-body p-0">

                @if ($documents->count())
                    <div class="table-responsive">

                        <table class="table table-hover align-middle mb-0">

                            <thead class="table-light">

                                <tr>
                                    <th class="ps-4">Document</th>
                                    <th>Type</th>
                                    <th>Tâche</th>
                                    <th>Auteur</th>
                                    <th>Taille</th>
                                    <th>Date</th>
                                    <th>État</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>

                            </thead>

                            <tbody>

                                @foreach ($documents as $document)
                                    @php
                                        $extension = strtolower(pathinfo($document->nom_original, PATHINFO_EXTENSION));

                                        $icon = match ($extension) {
                                            'pdf' => 'bi-file-earmark-pdf text-danger',
                                            'doc', 'docx' => 'bi-file-earmark-word text-primary',
                                            'xls', 'xlsx' => 'bi-file-earmark-excel text-success',
                                            'ppt', 'pptx' => 'bi-file-earmark-ppt text-warning',
                                            'jpg', 'jpeg', 'png' => 'bi-file-earmark-image text-info',
                                            'zip' => 'bi-file-earmark-zip text-secondary',
                                            default => 'bi-file-earmark text-secondary',
                                        };

                                        $taille = $document->taille
                                            ? number_format($document->taille / 1024 / 1024, 2, ',', ' ') . ' Mo'
                                            : '—';

                                        $nomAuteur = trim(
                                            ($document->employee?->user?->firstname ?? '') .
                                                ' ' .
                                                ($document->employee?->user?->name ?? ''),
                                        );

                                        if (!$nomAuteur) {
                                            $nomAuteur = '—';
                                        }
                                    @endphp

                                    <tr>

                                        {{-- Document --}}
                                        <td class="ps-4">

                                            <div class="d-flex align-items-center gap-3">

                                                <div class="fs-3">
                                                    <i class="bi {{ $icon }}"></i>
                                                </div>

                                                <div>

                                                    <div class="fw-semibold">
                                                        {{ $document->nom_original }}
                                                    </div>

                                                    @if ($document->description)
                                                        <small class="text-muted">
                                                            {{ Str::limit($document->description, 80) }}
                                                        </small>
                                                    @endif

                                                </div>

                                            </div>

                                        </td>


                                        {{-- Type --}}
                                        <td>
                                            <span class="badge bg-secondary">
                                                {{ $document->type }}
                                            </span>
                                        </td>


                                        {{-- Tâche --}}
                                        <td>

                                            @if ($document->tache)
                                                <div class="small fw-semibold">
                                                    {{ $document->tache->titre }}
                                                </div>

                                                @if ($document->tache->reference)
                                                    <small class="text-muted">
                                                        {{ $document->tache->reference }}
                                                    </small>
                                                @endif
                                            @else
                                                <span class="text-muted">
                                                    Activité
                                                </span>
                                            @endif

                                        </td>


                                        {{-- Auteur --}}
                                        <td>
                                            <span class="small">
                                                {{ $nomAuteur }}
                                            </span>
                                        </td>


                                        {{-- Taille --}}
                                        <td>
                                            <span class="small">
                                                {{ $taille }}
                                            </span>
                                        </td>


                                        {{-- Date --}}
                                        <td>
                                            <span class="small">
                                                {{ $document->created_at?->format('d/m/Y H:i') }}
                                            </span>
                                        </td>


                                        {{-- État --}}
                                        <td>

                                            @if ($document->document_final)
                                                <span class="badge bg-success">
                                                    <i class="bi bi-check-circle me-1"></i>
                                                    Final
                                                </span>
                                            @else
                                                <span class="badge bg-light text-dark border">
                                                    Version de travail
                                                </span>
                                            @endif

                                        </td>


                                        {{-- Actions --}}
                                        <td class="text-end pe-4">

                                            <div class="btn-group">

                                                {{-- <a href="{{ route(
                                            'onfp.activites.documents.download',
                                            [$activite, $document]
                                        ) }}"
                                           class="btn btn-sm btn-sm btn-outline-primary"
                                           title="Télécharger">

                                            <i class="bi bi-download"></i>

                                        </a> --}}
                                                <a href="{{ route('onfp.activites.documents.view', [$activite, $document]) }}"
                                                    class="btn btn-sm btn-sm btn-outline-primary" target="_blank"
                                                    title="Visualiser">

                                                    <i class="bi bi-eye"></i>

                                                </a>


                                                <form method="POST"
                                                    action="{{ route('onfp.activites.documents.destroy', [$activite, $document]) }}"
                                                    onsubmit="return confirm('Voulez-vous vraiment supprimer ce document ?');">

                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit" class="btn btn-sm btn-sm btn-outline-danger"
                                                        title="Supprimer">

                                                        <i class="bi bi-trash"></i>

                                                    </button>

                                                </form>

                                            </div>

                                        </td>

                                    </tr>
                                @endforeach

                            </tbody>

                        </table>

                    </div>
                @else
                    {{-- Aucun document --}}
                    <div class="text-center py-5">

                        <div class="display-4 text-muted mb-3">
                            <i class="bi bi-folder2-open"></i>
                        </div>

                        <h5>
                            Aucun document
                        </h5>

                        <p class="text-muted mb-4">
                            Aucun document n'est encore associé à cette activité.
                        </p>

                        <a href="{{ route('onfp.activites.documents.create', $activite) }}" class="btn btn-sm btn-primary">

                            <i class="bi bi-plus-lg me-1"></i>
                            Ajouter le premier document

                        </a>

                    </div>
                @endif

            </div>


            @if ($documents->hasPages())
                <div class="card-footer bg-white">

                    {{ $documents->links() }}

                </div>
            @endif

        </div>

    </div>

@endsection
