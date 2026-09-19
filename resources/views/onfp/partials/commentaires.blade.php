{{--
    Partial commentaires. À inclure ainsi :

    Sur une activité :
    @include('onfp.partials.commentaires', [
        'commentaires' => $activite->commentaires()->surActivite()->latest()->get(),
        'storeRoute' => route('onfp.activites.commentaires.store', $activite),
    ])

    Sur une tâche (directement rattachée à une activité) :
    @include('onfp.partials.commentaires', [
        'commentaires' => $tache->commentaires()->latest()->get(),
        'storeRoute' => route('onfp.activites.taches.commentaires.store', [$activite, $tache]),
    ])
--}}

<div class="card shadow-sm border-0 mb-4">

    <div class="card-header bg-white py-3">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="bi bi-chat-left-text me-2"></i>
                Commentaires
            </h5>
            <span class="badge bg-secondary">{{ $commentaires->count() }}</span>
        </div>
    </div>

    <div class="card-body">

        {{-- Formulaire d'ajout --}}
        <form method="POST" action="{{ $storeRoute }}" class="mb-4">
            @csrf

            <textarea name="commentaire" rows="3" class="form-control @error('commentaire') is-invalid @enderror"
                placeholder="Ajouter un commentaire...">{{ old('commentaire') }}</textarea>

            @error('commentaire')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror

            <div class="d-flex justify-content-between align-items-center mt-2">

                <div class="form-check">
                    <input type="hidden" name="interne" value="0">
                    <input type="checkbox" name="interne" value="1" id="interneCheck{{ $storeRoute }}"
                        class="form-check-input" checked>
                    <label class="form-check-label small text-muted" for="interneCheck{{ $storeRoute }}">
                        Commentaire interne
                    </label>
                </div>

                <button type="submit" class="btn btn-sm btn-primary">
                    <i class="bi bi-send me-1"></i>
                    Publier
                </button>

            </div>
        </form>

        <hr>

        {{-- Liste des commentaires --}}
        @forelse ($commentaires as $commentaire)
            @php
                $nomAuteur = trim(
                    ($commentaire->employee?->user?->firstname ?? '') .
                        ' ' .
                        ($commentaire->employee?->user?->name ?? ''),
                );
            @endphp

            <div class="d-flex gap-3 mb-3">

                <div class="rounded-circle bg-secondary text-white
                        d-flex align-items-center justify-content-center flex-shrink-0"
                    style="width:36px;height:36px;">
                    <i class="bi bi-person"></i>
                </div>

                <div class="flex-grow-1" style="min-width: 0;">

                    <div class="d-flex justify-content-between align-items-start gap-2">

                        <div>
                            <span class="fw-semibold">
                                {{ $nomAuteur !== '' ? $nomAuteur : $commentaire->employee?->matricule ?? 'Utilisateur inconnu' }}
                            </span>

                            @if (!$commentaire->interne)
                                <span class="badge bg-info-subtle text-info ms-1">Externe</span>
                            @endif

                            <div class="small text-muted">
                                {{ $commentaire->created_at?->diffForHumans() }}
                            </div>
                        </div>

                        @if ($commentaire->employee_id === optional(auth()->user()->employee)->id)
                            <form method="POST" action="{{ route('onfp.commentaires.destroy', $commentaire) }}"
                                onsubmit="return confirm('Supprimer ce commentaire ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-link text-danger p-0" title="Supprimer">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        @endif

                    </div>

                    <div class="mt-1 text-break">
                        {!! nl2br(e($commentaire->commentaire)) !!}
                    </div>

                </div>

            </div>
        @empty
            <div class="text-center text-muted py-3">
                Aucun commentaire pour le moment.
            </div>
        @endforelse

    </div>

</div>
