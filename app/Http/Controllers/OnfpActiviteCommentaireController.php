<?php

namespace App\Http\Controllers;

use App\Models\OnfpActivite;
use App\Models\OnfpActiviteCommentaire;
use App\Models\OnfpTache;
use Illuminate\Http\Request;

class OnfpActiviteCommentaireController extends Controller
{
    /**
     * Ajoute un commentaire.
     *
     * Partagé entre deux routes :
     *   - activites/{activite}/commentaires           (commentaire sur l'activité)
     *   - activites/{activite}/taches/{tache}/commentaires (commentaire sur une tâche)
     *
     * IMPORTANT : l'ordre des paramètres (activite, tache) doit correspondre
     * à l'ordre des segments dans l'URL, comme pour OnfpTacheActiviteController.
     * Ne pas réordonner, ne pas retirer $tache de la signature.
     */
    public function store(
        Request $request,
        OnfpActivite $activite,
        ?OnfpTache $tache = null
    ) {
        if ($tache) {
            abort_unless(
                (int) $tache->activite_id === (int) $activite->id,
                404
            );
        }

        $validated = $request->validate([
            'commentaire' => [
                'required',
                'string',
                'max:5000',
            ],
            'interne' => [
                'nullable',
                'boolean',
            ],
        ]);

        OnfpActiviteCommentaire::create([
            'activite_id' => $activite->id,
            'tache_id' => $tache?->id,
            'employee_id' => optional(auth()->user()->employee)->id,
            'commentaire' => $validated['commentaire'],
            'interne' => $validated['interne'] ?? true,
        ]);

        return back()->with('success', 'Commentaire ajouté avec succès.');
    }

    /**
     * Supprime un commentaire (soft delete).
     * Seul l'auteur ou un administrateur peut supprimer.
     */
    public function destroy(OnfpActiviteCommentaire $commentaire)
    {
        $employeeId = optional(auth()->user()->employee)->id;

        abort_unless(
            $commentaire->employee_id === $employeeId || auth()->user()->hasRole('super-admin'),
            403,
            'Vous ne pouvez supprimer que vos propres commentaires.'
        );

        $commentaire->delete();

        return back()->with('success', 'Commentaire supprimé.');
    }
}
