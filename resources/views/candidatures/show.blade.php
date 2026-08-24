@extends('layouts.dashboard')

@section('title', 'Détail candidature')

@section('content')
    <div class="dash-topbar">
        <div>
            <h2>{{ $candidature->user->firstname }} {{ $candidature->user->name }}</h2>
            <p class="muted-sub">Candidature #{{ str_pad($candidature->id, 6, '0', STR_PAD_LEFT) }} - déposée le
                {{ $candidature->created_at->format('d/m/Y à H:i') }}</p>
        </div>
        <div class="topbar-right">
            <span class="status-pill {{ $candidature->statut_classe }}">{{ $candidature->statut_label }}</span>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-list">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="panel">
        <h3>Informations personnelles</h3>
        <div class="detail-grid">
            <div class="detail-item">
                <span class="detail-label">Civilité</span>
                <span class="detail-value">{{ $candidature->user->civilite ?? '-' }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Nom complet</span>
                <span class="detail-value">{{ $candidature->user->firstname }} {{ $candidature->user->name }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">E-mail</span>
                <span class="detail-value">{{ $candidature->user->email }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Téléphone</span>
                <span class="detail-value">{{ $candidature->user->telephone }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Date de naissance</span>
                <span class="detail-value">{{ $candidature->user->date_naissance?->format('d/m/Y') }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Lieu de naissance</span>
                <span class="detail-value">{{ $candidature->user->lieu_naissance ?? '-' }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Adresse</span>
                <span class="detail-value">{{ $candidature->user->adresse ?? '-' }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Région</span>
                <span class="detail-value">{{ $candidature->region->nom ?? '-' }}</span>
            </div>
        </div>
    </div>

    <div class="panel">
        <h3>Langues</h3>
        <div class="detail-grid">
            <div class="detail-item">
                <span class="detail-label">Langue de spécialisation (LV1)</span>
                <span class="detail-value">{{ $candidature->langueSpecialisation->nom }} -
                    {{ $candidature->langueSpecialisation->niveau_langue_requis }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Certification obtenue</span>
                <span class="detail-value">{{ $candidature->certification_obtenue ?? 'Aucune' }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Diplôme</span>
                <span class="detail-value">{{ ucfirst($candidature->diplome) }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Langue maternelle</span>
                <span class="detail-value">{{ ucfirst($candidature->langue_maternelle) }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Niveau de français</span>
                <span class="detail-value">{{ Str::upper($candidature->niveau_francais) }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Langue vivante 2</span>
                <span
                    class="detail-value">{{ $candidature->langue_vivante_2 ? ucfirst($candidature->langue_vivante_2) : 'Aucune' }}</span>
            </div>
        </div>
    </div>

    <div class="panel">
        <h3>Disponibilité et affectation</h3>
        <div class="detail-grid">
            <div class="detail-item">
                <span class="detail-label">Disponible du</span>
                <span class="detail-value">{{ $candidature->disponible_debut->format('d/m/Y') }} au
                    {{ $candidature->disponible_fin->format('d/m/Y') }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Zone souhaitée</span>
                <span class="detail-value">{{ $candidature->zone_label }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Délégation souhaitée</span>
                <span class="detail-value">{{ $candidature->delegation_souhaitee ?? 'Non précisé' }}</span>
            </div>
        </div>
    </div>

    <div class="panel">
        <h3>Documents justificatifs</h3>
        <div class="documents-grid">
            @foreach ($documents as $label => $path)
                <div class="document-card">
                    <span class="document-label">{{ $label }}</span>
                    @if ($path)
                        @php $ext = pathinfo($path, PATHINFO_EXTENSION); @endphp
                        <div class="document-preview">
                            @if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png']))
                                <a href="{{ asset('storage/' . $path) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $path) }}" alt="{{ $label }}">
                                </a>
                            @else
                                <a href="{{ asset('storage/' . $path) }}" target="_blank" class="document-file-link">
                                    📄 Voir le PDF
                                </a>
                            @endif
                        </div>
                        <a href="{{ asset('storage/' . $path) }}" download class="btn btn-ghost btn-sm">Télécharger</a>
                    @else
                        <span class="document-missing">Aucun fichier fourni</span>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <div class="panel">
        <h3>Traitement de la candidature</h3>
        <form action="{{ route('candidatures.statut', $candidature) }}" method="POST">
            @csrf
            @method('PATCH')

            <div class="field">
                <label>Commentaire administratif (optionnel)</label>
                <textarea name="commentaire_admin" rows="3" placeholder="Motif de rejet, remarque interne…">{{ old('commentaire_admin', $candidature->commentaire_admin) }}</textarea>
            </div>

            @can('candidatures.validation')
                <div class="reg-actions" style="justify-content:flex-start; gap:12px; margin-top:16px;">
                    <button type="submit" name="statut" value="validee" class="btn btn-success btn-sm">
                        Valider la candidature
                    </button>
                    <button type="submit" name="statut" value="rejetee" class="btn btn-danger btn-sm">
                        Rejeter la candidature
                    </button>
                    <button type="submit" name="statut" value="en_attente" class="btn btn-ghost btn-sm">
                        Remettre en attente
                    </button>
                </div>
            @endcan
        </form>
    </div>

    <div class="panel">
        <div class="reg-actions" style="justify-content:space-between;">
            <a href="{{ route('candidatures.index') }}" class="btn btn-ghost btn-sm">Retour à la liste</a>

            <div style="display:flex; gap:10px;">
                <a href="{{ route('candidatures.edit', $candidature) }}" class="btn btn-outline btn-sm">
                    Modifier
                </a>

                @can('candidatures.delete')
                    <form action="{{ route('candidatures.destroy', $candidature) }}" method="POST"
                        onsubmit="return confirm('Confirmer la suppression définitive de cette candidature ? Cette action est irréversible.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                    </form>
                @endcan
            </div>
        </div>
    </div>
@endsection
