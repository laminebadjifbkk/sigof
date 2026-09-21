<?php

namespace App\Http\Controllers;

use App\Models\OnfpActivite;
use App\Models\OnfpSousActivite;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OnfpSousActiviteController extends Controller
{
    /**
     * Liste des sous-activités.
     */
    public function index(OnfpActivite $activite)
    {
        $sousActivites = $activite->sousActivites()
            ->withCount('taches')
            ->with('taches')
            ->orderBy('created_at')
            ->paginate(15);

        return view(
            'onfp.activites.sous-activites.index',
            compact('activite', 'sousActivites')
        );
    }

    /**
     * Formulaire de création.
     */
    /*  public function create(OnfpActivite $activite)
    {
        $priorites = OnfpSousActivite::PRIORITES;

        return view(
            'onfp.activites.sous-activites.create',
            compact('activite', 'priorites')
        );
    } */
    public function create(OnfpActivite $activite)
    {
        $statuts   = OnfpSousActivite::STATUTS;
        $priorites = OnfpSousActivite::PRIORITES;

        return view(
            'onfp.activites.sous-activites.create',
            compact('activite', 'statuts', 'priorites')
        );
    }

    /**
     * Enregistrement.
     */
    public function store(
        Request $request,
        OnfpActivite $activite
    ) {
        $validated = $request->validate([
            'reference' => [
                'nullable',
                'string',
                'max:50',
            ],

            'titre' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'statut' => [
                'required',
                'in:a_faire,en_cours,suspendue,terminee,annulee',
            ],

            'priorite' => [
                'required',
                'in:basse,normale,haute,urgente',
            ],

            'progression' => [
                'required',
                'integer',
                'min:0',
                'max:100',
            ],

            'date_debut' => [
                'nullable',
                'date',
            ],

            'date_fin_prevue' => [
                'nullable',
                'date',
                'after_or_equal:date_debut',
            ],

            'date_fin_reelle' => [
                'nullable',
                'date',
                'after_or_equal:date_debut',
            ],

            'observation' => [
                'nullable',
                'string',
            ],
        ]);

        $validated['reference'] = $validated['reference']
            ?? 'SA-' . strtoupper(Str::random(8));

        $sousActivite = $activite->sousActivites()->create(
            $validated
        );

        return redirect()
            ->route(
                'onfp.activites.sous-activites.show',
                [$activite, $sousActivite]
            )
            ->with(
                'success',
                'Sous-activité créée avec succès.'
            );
    }

    /**
     * Affichage d'une sous-activité.
     */
    public function show(
        OnfpActivite $activite,
        OnfpSousActivite $sousActivite
    ) {
        $this->verifierAppartenance(
            $activite,
            $sousActivite
        );

        $sousActivite->load([
            'activite',
            'taches.responsables.employee.user',
            'taches.suiveurs.employee.user',
            'createdBy.user',
            'updatedBy.user',
        ]);


        $statuts   = OnfpSousActivite::STATUTS;
        $priorites = OnfpSousActivite::PRIORITES;

        return view(
            'onfp.activites.sous-activites.show',
            compact(
                'activite',
                'statuts',
                'priorites',
                'sousActivite'
            )
        );
    }

    public function edit(
        OnfpActivite $activite,
        OnfpSousActivite $sousActivite
    ) {
        $this->verifierAppartenance(
            $activite,
            $sousActivite
        );

        $statuts   = OnfpSousActivite::STATUTS;
        $priorites = OnfpSousActivite::PRIORITES;

        return view(
            'onfp.activites.sous-activites.edit',
            compact(
                'activite',
                'sousActivite',
                'statuts',
                'priorites'
            )
        );
    }

    /**
     * Mise à jour.
     */
    public function update(
        Request $request,
        OnfpActivite $activite,
        OnfpSousActivite $sousActivite
    ) {
        $this->verifierAppartenance(
            $activite,
            $sousActivite
        );

        $validated = $request->validate([
            'reference' => [
                'nullable',
                'string',
                'max:50',
            ],

            'titre' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'statut' => [
                'required',
                'in:a_faire,en_cours,suspendue,terminee,annulee',
            ],

            'priorite' => [
                'required',
                'in:basse,normale,haute,urgente',
            ],

            'progression' => [
                'required',
                'integer',
                'min:0',
                'max:100',
            ],

            'date_debut' => [
                'nullable',
                'date',
            ],

            'date_fin_prevue' => [
                'nullable',
                'date',
                'after_or_equal:date_debut',
            ],

            'date_fin_reelle' => [
                'nullable',
                'date',
                'after_or_equal:date_debut',
            ],

            'observation' => [
                'nullable',
                'string',
            ],
        ]);

        $sousActivite->update($validated);

        return redirect()
            ->route(
                'onfp.activites.sous-activites.show',
                [$activite, $sousActivite]
            )
            ->with(
                'success',
                'Sous-activité mise à jour avec succès.'
            );
    }

    /**
     * Suppression.
     */
    public function destroy(
        OnfpActivite $activite,
        OnfpSousActivite $sousActivite
    ) {
        $this->verifierAppartenance(
            $activite,
            $sousActivite
        );

        /*
         * Par sécurité, empêcher la suppression
         * d'une sous-activité contenant encore des tâches.
         */
        if ($sousActivite->taches()->exists()) {
            return back()->with(
                'error',
                'Cette sous-activité contient des tâches. '
                    . 'Veuillez les supprimer ou les réaffecter avant de continuer.'
            );
        }

        $sousActivite->delete();

        return redirect()
            ->route(
                'onfp.activites.sous-activites.index',
                $activite
            )
            ->with(
                'success',
                'Sous-activité supprimée avec succès.'
            );
    }

    /**
     * Vérification de l'appartenance.
     */
    private function verifierAppartenance(
        OnfpActivite $activite,
        OnfpSousActivite $sousActivite
    ): void {
        if ($sousActivite->activite_id !== $activite->id) {
            abort(404);
        }
    }
}
