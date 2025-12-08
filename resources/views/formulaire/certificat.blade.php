@extends('layout.user-layout')

@section('title', 'Téléverser certificat d’inscription')

@section('space-work')
    <section class="section">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                <div class="card shadow-sm">
                    <div class="card-header bg-warning text-white">
                        <h5 class="mb-0">Téléverser votre certificat d’inscription</h5>
                    </div>

                    <div class="card-body">
                        {{-- Message de succès --}}
                        @if (session('status'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('status') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        {{-- Message d’erreur --}}
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('formulaires.certificat.update', $formulaire->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Certificat d’inscription</label>
                                <input type="file" name="certificat_file" class="form-control" required>
                            </div>

                            {{-- Fichier existant --}}
                            @if ($formulaire->certificat_file)
                                <div class="mt-2">
                                    <a href="{{ asset('storage/' . $formulaire->certificat_file) }}" target="_blank"
                                        class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-file-earmark-arrow-down"></i> Voir certificat existant
                                    </a>
                                </div>
                            @endif
                            {{-- Statut actuel du certificat --}}
                            <div class="mt-2">
                                <span
                                    class="badge 
                                    @if ($formulaire->statut_certificat === 'Validé') bg-success 
                                    @elseif($formulaire->statut_certificat === 'Rejeté') bg-danger 
                                    @else bg-warning text-dark @endif">
                                    Statut : {{ $formulaire->statut_certificat ?? 'Non défini' }}
                                </span>
                            </div>
                            <div class="mt-4 d-flex justify-content-between">
                                <a href="{{ route('profil') }}" class="btn btn-secondary btn-sm">
                                    Retour
                                </a>
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="bi bi-upload"></i> Enregistrer
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection
