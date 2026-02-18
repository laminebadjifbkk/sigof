@extends('layout.user-layout')
@section('title', 'ONFP - Enregistrement nouveau courrier arrivé')

@section('space-work')
    <section class="section py-3">
        <div class="container">

            @if ($message = Session::get('status'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>{{ $message }}</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card shadow-sm">
                <div class="card-body">

                    {{-- <div class="d-flex align-items-center mb-3">
                        <a href="{{ route('arrives.index') }}" class="btn btn-success btn-sm me-2">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                        <span>Liste des courriers arrivés</span>
                    </div> --}}

                    <div
                        class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 p-3 bg-light rounded shadow-sm">
                        <h4 class="mb-2 mb-md-0 text-primary fw-bold">
                            <i class="bi bi-journal-plus me-2"></i> Enregistrement Courrier Arrivé
                        </h4>
                        <form action="{{ route('couponArrive') }}" method="post" target="_blank">
                            @csrf
                            <input type="hidden" name="id" value="{{ $arrive?->id }}">
                            <button class="btn btn-outline-success btn-sm"><i class="fa fa-print"
                                    aria-hidden="true"></i>Télécharger
                                coupon dernier courrier</button>
                        </form>
                        <a href="{{ route('arrives.index') }}"
                            class="btn btn-outline-primary btn-sm d-flex align-items-center">
                            <i class="bi bi-arrow-left-circle me-1"></i> Retour à la liste
                        </a>
                    </div>

                    {{--  <div class="text-center mb-4">
                        <h5 class="fw-bold">Ajouter un nouveau courrier arrivé</h5>
                    </div> --}}

                    <form method="POST" action="{{ route('arrives.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="row">

                            <div class="col-lg-6 border-end">

                                <div class="mb-3">
                                    <label class="form-label fw-bold">
                                        Scan du courrier
                                    </label>

                                    <input type="file" name="scan" id="scanInput" accept=".pdf,.jpg,.jpeg,.png"
                                        class="form-control form-control-sm @error('scan') is-invalid @enderror">

                                    @error('scan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="border rounded bg-light p-2" style="height:650px; overflow:auto;">

                                    <embed id="pdfPreview" type="application/pdf" width="100%" height="100%"
                                        style="display:none;" />

                                    <img id="imagePreview" style="max-width:100%; display:none;" />

                                    <div id="noPreview" class="text-center text-muted mt-5">
                                        Aucun scan chargé
                                    </div>

                                </div>
                            </div>

                            {{-- ===================== RIGHT : FORM ===================== --}}
                            <div class="col-lg-6">

                                <div class="row g-3">

                                    <div class="col-12">
                                        <label class="form-label">Date arrivée <span class="text-danger">*</span></label>
                                        <input type="date" name="date_arrivee" value="{{ old('date_arrivee') }}"
                                            class="form-control form-control-sm @error('date_arrivee') is-invalid @enderror">
                                        @error('date_arrivee')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">Numéro <span class="text-danger">*</span></label>
                                        <input type="number" name="numero_arrive"
                                            value="{{ $numCourrier ?? old('numero_arrive') }}"
                                            class="form-control form-control-sm @error('numero_arrive') is-invalid @enderror">
                                        @error('numero_arrive')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">Date correspondance <span
                                                class="text-danger">*</span></label>
                                        <input type="date" name="date_correspondance"
                                            value="{{ old('date_correspondance') }}"
                                            class="form-control form-control-sm @error('date_correspondance') is-invalid @enderror">
                                        @error('date_correspondance')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">Année <span class="text-danger">*</span></label>
                                        <input type="number" name="annee" value="{{ $anneeEnCours ?? old('annee') }}"
                                            class="form-control form-control-sm @error('annee') is-invalid @enderror"
                                            placeholder="Année" min="2025" step="1">
                                        @error('annee')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">Numéro de correspondance</label>
                                        <textarea name="numero_courrier" rows="1" placeholder="Numéro sur le courrier receptionné"
                                            class="form-control form-control-sm @error('numero_courrier') is-invalid @enderror">{{ old('numero_courrier') }}</textarea>
                                        @error('numero_courrier')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">Expéditeur <span class="text-danger">*</span></label>
                                        <textarea name="expediteur" rows="2" placeholder="Propritaire du courrier"
                                            class="form-control form-control-sm @error('expediteur') is-invalid @enderror">{{ old('expediteur') }}</textarea>
                                        @error('expediteur')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">Objet <span class="text-danger">*</span></label>
                                        <textarea name="objet" rows="2" placeholder="Objet sur le courrier"
                                            class="form-control form-control-sm @error('objet') is-invalid @enderror">{{ old('objet') }}</textarea>
                                        @error('objet')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">Référence</label>
                                        <input type="text" name="reference" value="{{ old('reference') }}"
                                            class="form-control form-control-sm @error('reference') is-invalid @enderror">
                                        @error('reference')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Numéro réponse</label>
                                        <input type="number" name="numero_reponse" value="{{ old('numero_reponse') }}"
                                            class="form-control form-control-sm @error('numero_reponse') is-invalid @enderror">
                                        @error('numero_reponse')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Date réponse</label>
                                        <input type="date" name="date_reponse" value="{{ old('date_reponse') }}"
                                            class="form-control form-control-sm @error('date_reponse') is-invalid @enderror">
                                        @error('date_reponse')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">Observations</label>
                                        <textarea name="observation" rows="2"
                                            class="form-control form-control-sm @error('observation') is-invalid @enderror">{{ old('observation') }}</textarea>
                                        @error('observation')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12 text-end mt-3">
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            Enregistrer
                                        </button>
                                    </div>

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

            if (!input) {
                console.log("scanInput introuvable");
                return;
            }

            input.addEventListener("change", function(event) {

                const file = event.target.files[0];

                const pdfPreview = document.getElementById("pdfPreview");
                const imagePreview = document.getElementById("imagePreview");
                const noPreview = document.getElementById("noPreview");

                pdfPreview.style.display = "none";
                imagePreview.style.display = "none";
                noPreview.style.display = "none";

                if (!file) {
                    noPreview.style.display = "block";
                    return;
                }

                const reader = new FileReader();

                reader.onload = function(e) {

                    if (file.type === "application/pdf") {
                        pdfPreview.src = e.target.result;
                        pdfPreview.style.display = "block";
                    } else if (file.type.startsWith("image/")) {
                        imagePreview.src = e.target.result;
                        imagePreview.style.display = "block";
                    } else {
                        noPreview.style.display = "block";
                    }
                };

                reader.readAsDataURL(file);
            });

        });
    </script>
@endpush
