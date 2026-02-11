@extends('layout.user-layout')
@section('title', 'Modification courrier arrivé')
@section('space-work')
    <section class="section py-3">
        <div class="container">

            @if ($message = Session::get('status'))
                <div class="alert alert-success bg-success text-light border-0 alert-dismissible fade show" role="alert">
                    <strong>{{ $message }}</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <div class="alert alert-danger bg-danger text-light border-0 alert-dismissible fade show" role="alert">
                        <strong>{{ $error }}</strong>
                    </div>
                @endforeach
            @endif

            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <a href="{{ route('arrives.index') }}" class="btn btn-success btn-sm me-2">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                        <span>Liste des courriers arrivés</span>
                    </div>

                    <div class="text-center mb-4">
                        <h5 class="fw-bold">Modification</h5>
                    </div>

                    <form method="post" action="{{ route('arrives.update', $arrive->id) }}" enctype="multipart/form-data"
                        class="row g-3">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            {{-- === PREVIEW SCAN === --}}
                            <div class="col-lg-6 border-end">
                                <label class="form-label">Prévisualisation du scan</label>
                                <div class="border rounded p-2 bg-light" style="height:400px; overflow:auto;">
                                    @if ($arrive->courrier->file)
                                        @if (Str::endsWith($arrive->courrier->file, ['.pdf']))
                                            <embed id="pdfPreview" src="{{ asset('storage/' . $arrive->courrier->file) }}"
                                                type="application/pdf" width="100%" height="100%">
                                        @else
                                            <img id="imagePreview" src="{{ asset('storage/' . $arrive->courrier->file) }}"
                                                style="max-width:100%; max-height:100%;" />
                                        @endif
                                    @else
                                        <div id="noPreview" class="text-center text-muted mt-5">Aucun scan disponible
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- === FORMULAIRE === --}}
                            <div class="col-12 col-lg-6">

                                {{-- Date arrivée --}}
                                <div class="mb-2">
                                    <label for="date_arrivee" class="form-label">Date arrivée<span
                                            class="text-danger">*</span></label>
                                    <input type="date" name="date_arrivee"
                                        value="{{ old('date_arrivee', $arrive->courrier->date_recep?->format('Y-m-d')) }}"
                                        class="form-control form-control-sm @error('date_arrivee') is-invalid @enderror">
                                    @error('date_arrivee')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Numéro arrivé --}}
                                <div class="mb-2">
                                    <label for="numero_arrive" class="form-label">Numéro arrivé<span
                                            class="text-danger">*</span></label>
                                    <input type="number" name="numero_arrive"
                                        value="{{ old('numero_arrive', $arrive->numero_arrive) }}"
                                        class="form-control form-control-sm @error('numero_arrive') is-invalid @enderror">
                                    @error('numero_arrive')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Expéditeur --}}
                                <div class="mb-2">
                                    <label for="expediteur" class="form-label">Expéditeur<span
                                            class="text-danger">*</span></label>
                                    <textarea name="expediteur" rows="2"
                                        class="form-control form-control-sm @error('expediteur') is-invalid @enderror">{{ old('expediteur', $arrive->courrier->expediteur) }}</textarea>
                                    @error('expediteur')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Objet --}}
                                <div class="mb-2">
                                    <label for="objet" class="form-label">Objet<span class="text-danger">*</span></label>
                                    <textarea name="objet" rows="2" class="form-control form-control-sm @error('objet') is-invalid @enderror">{{ old('objet', $arrive->courrier->objet) }}</textarea>
                                    @error('objet')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Date correspondance -->
                                <div class="mb-2">
                                    <label for="legende" class="form-label">Date correspondance<span
                                            class="text-danger">*</span></label>
                                    <input type="date" name="date_correspondance"
                                        value="{{ old('date_correspondance', $arrive?->courrier?->date_cores?->format('Y-m-d')) }}"
                                        class="form-control form-control-sm @error('date_correspondance') is-invalid @enderror"
                                        required>
                                </div>

                                <!-- Année -->
                                <div class="mb-2">
                                    <label class="form-label">Année <span class="text-danger">*</span></label>
                                    <input type="number" name="annee"
                                        value="{{ old('annee', $arrive?->courrier?->annee) }}"
                                        class="form-control form-control-sm @error('annee') is-invalid @enderror"
                                        min="2024" required>
                                </div>

                                {{-- Légende scan --}}
                                {{-- <div class="mb-2">
                                    <label for="legende" class="form-label">Légende<span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="legende"
                                        value="{{ old('legende', $arrive->courrier->legende) }}"
                                        class="form-control form-control-sm @error('legende') is-invalid @enderror">
                                    @error('legende')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div> --}}

                                {{-- Upload scan --}}
                                <div class="mb-2">
                                    <label for="scan" class="form-label">Remplacer le scan</label>
                                    <input type="file" name="scan" id="scanInput" accept=".pdf,.jpg,.jpeg,.png"
                                        class="form-control form-control-sm @error('scan') is-invalid @enderror">
                                    @error('scan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="text-end mt-3">
                                    <button type="submit" class="btn btn-success btn-sm">Enregistrer les
                                        modifications</button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const input = document.getElementById("scanInput");
            const pdfPreview = document.getElementById("pdfPreview");
            const imagePreview = document.getElementById("imagePreview");
            const noPreview = document.getElementById("noPreview");

            if (!input) return;

            input.addEventListener("change", function(e) {
                const file = e.target.files[0];

                // Masquer tous les previews
                if (pdfPreview) pdfPreview.style.display = "none";
                if (imagePreview) imagePreview.style.display = "none";
                if (noPreview) noPreview.style.display = "none";

                if (!file) {
                    if (noPreview) noPreview.style.display = "block";
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(ev) {
                    if (file.type === "application/pdf" && pdfPreview) {
                        pdfPreview.src = ev.target.result;
                        pdfPreview.style.display = "block";
                    } else if (file.type.startsWith("image/") && imagePreview) {
                        imagePreview.src = ev.target.result;
                        imagePreview.style.display = "block";
                    } else if (noPreview) {
                        noPreview.style.display = "block";
                    }
                };
                reader.readAsDataURL(file);
            });
        });
    </script>
@endpush
