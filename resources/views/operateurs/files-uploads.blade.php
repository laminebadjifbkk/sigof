<div class="row">
    {{-- Dossier complet/incomplet --}}
    <div class="col-12 col-lg-6 mb-4">
        <div class="card-body px-4 border rounded shadow-sm">
            <div class="my-2 p-3 text-center">
                @if (
                    ($operateur->user->categorie === 'Public' && $hasNinea && $hasQuitus) ||
                    ($operateur->user->categorie !== 'Public' && $hasNinea && $hasQuitus && $hasAC && $hasContrat && $hasNF)
                )
                    <span class="text-success fw-bold fs-5">Dossier complet</span>
                @else
                    <span class="text-danger fw-bold fs-5 d-block">Dossier incomplet !</span>
                    <div class="text-danger fs-6 mt-2">
                        @if (!$hasNinea)
                            Veuillez téléverser le NINEA.<br>
                        @endif
                        @if (!$hasQuitus)
                            Veuillez téléverser le quitus fiscal.<br>
                        @endif
                        @if ($operateur->user->categorie !== 'Public')
                            @if (!$hasAC)
                                Acte de création est requis.<br>
                            @endif
                            @if (!$hasContrat)
                                Contrat de location requis.<br>
                            @endif
                            @if (!$hasNF)
                                Attestation de non fonctionnaire requise.<br>
                            @endif
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Formulaire upload --}}
    <div class="col-12 col-lg-6 mb-4">
        <div class="card border-primary shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0 text-primary">
                    <i class="bi bi-upload me-2"></i>
                    Joindre un document
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('files.update', $operateur?->user) }}"
                      enctype="multipart/form-data" class="row g-3">
                    @csrf
                    @method('patch')

                    <input type="hidden" name="idUser" value="{{ $operateur?->user->id }}">

                    {{-- Légende --}}
                    <div class="col-12">
                        <label class="form-label fw-semibold">
                            Légende <span class="text-danger">*</span>
                        </label>
                        <select name="legende" class="form-select @error('legende') is-invalid @enderror"
                                id="select-field-file">
                            <option value="">Choisir un document</option>
                            @foreach ($user_files as $file)
                                <option value="{{ $file->id }}">
                                    {{ $labels[$file->legende] ?? $file->legende }} ({{ $file->sigle }})
                                </option>
                            @endforeach
                        </select>
                        @error('legende')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Fichier --}}
                    <div class="col-12">
                        <label class="form-label fw-semibold">
                            Choisir un fichier <span class="text-danger">*</span>
                        </label>
                        <input type="file" name="file" class="form-control @error('file') is-invalid @enderror">
                        @error('file')
                        <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Bouton --}}
                    <div class="col-6">
                        <button type="submit" class="btn btn-success w-100">
                            <i class="bi bi-upload me-1"></i>
                            Téléverser le fichier
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>