<?php

namespace App\Http\Controllers;

use App\Models\OnfpActivite;
use App\Models\OnfpSousActivite;
use App\Models\OnfpTache;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\User;

class OnfpTacheActiviteController extends Controller
{
    /**
     * Liste des tâches d'une sous-activité.
     */
    public function index(
        OnfpActivite $activite,
        OnfpSousActivite $sousActivite
    ) {
        // Vérifier que la sous-activité appartient bien à l'activité
        abort_unless(
            $sousActivite->activite_id == $activite->id,
            404
        );

        $taches = OnfpTache::where('sous_activite_id', $sousActivite->id)
            ->orderBy('ordre')
            ->orderBy('created_at')
            ->get();

        return view('onfp.activites.taches.index', compact(
            'activite',
            'sousActivite',
            'taches'
        ));
    }

    /**
     * Formulaire de création d'une tâche.
     */
public function create(
    OnfpActivite $activite,
    OnfpSousActivite $sousActivite
) {
    abort_unless(
        $sousActivite->activite_id == $activite->id,
        404
    );

    $employees = Employee::with('user')
        ->whereHas('user')
        ->get()
        ->sortBy(function ($employee) {
            return strtolower(
                ($employee->user->name ?? '') . ' ' .
                ($employee->user->firstname ?? '')
            );
        });

    return view('onfp.activites.taches.create', compact(
        'activite',
        'sousActivite',
        'employees'
    ));
}
    /**
     * Enregistrer une nouvelle tâche.
     */
    public function store(
        Request $request,
        OnfpActivite $activite,
        OnfpSousActivite $sousActivite
    ) {
        // Vérifier que la sous-activité appartient bien à l'activité
        abort_unless(
            $sousActivite->activite_id == $activite->id,
            404
        );

        $validated = $request->validate([
            'libelle' => [
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
                'string',
                'in:a_faire,en_cours,suspendue,terminee,annulee',
            ],

            'priorite' => [
                'nullable',
                'string',
                'in:basse,normale,haute,urgente',
            ],

            'date_enclenchement' => [
                'nullable',
                'date',
            ],

            'date_execution' => [
                'nullable',
                'date',
            ],

            'date_fin' => [
                'nullable',
                'date',
                'after_or_equal:date_execution',
            ],

            'progression' => [
                'nullable',
                'integer',
                'min:0',
                'max:100',
            ],

            'ordre' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'observations' => [
                'nullable',
                'string',
            ],
        ]);

        $validated['activite_id'] = $activite->id;
        $validated['sous_activite_id'] = $sousActivite->id;

        // Valeurs par défaut
        $validated['statut'] = $validated['statut'] ?? 'a_faire';
        $validated['priorite'] = $validated['priorite'] ?? 'normale';
        $validated['progression'] = $validated['progression'] ?? 0;

        if (!isset($validated['ordre'])) {
            $validated['ordre'] = (
                OnfpTache::where(
                    'sous_activite_id',
                    $sousActivite->id
                )->max('ordre') ?? 0
            ) + 1;
        }

        $tache = OnfpTache::create($validated);

        return redirect()
            ->route(
                'onfp.activites.sous-activites.taches.index',
                [
                    'activite' => $activite,
                    'sousActivite' => $sousActivite,
                ]
            )
            ->with(
                'success',
                'La tâche a été créée avec succès.'
            );
    }

    /**
     * Afficher une tâche.
     */
    public function show(
        OnfpActivite $activite,
        OnfpSousActivite $sousActivite,
        OnfpTache $tache
    ) {
        // Vérifications de cohérence
        abort_unless(
            $sousActivite->activite_id == $activite->id,
            404
        );

        abort_unless(
            $tache->sous_activite_id == $sousActivite->id,
            404
        );

        return view('onfp.activites.taches.show', compact(
            'activite',
            'sousActivite',
            'tache'
        ));
    }

    /**
     * Formulaire de modification d'une tâche.
     */
    public function edit(
        OnfpActivite $activite,
        OnfpSousActivite $sousActivite,
        OnfpTache $tache
    ) {
        // Vérifications de cohérence
        abort_unless(
            $sousActivite->activite_id == $activite->id,
            404
        );

        abort_unless(
            $tache->sous_activite_id == $sousActivite->id,
            404
        );

        return view('onfp.activites.taches.edit', compact(
            'activite',
            'sousActivite',
            'tache'
        ));
    }

    /**
     * Mettre à jour une tâche.
     */
    public function update(
        Request $request,
        OnfpActivite $activite,
        OnfpSousActivite $sousActivite,
        OnfpTache $tache
    ) {
        // Vérifications de cohérence
        abort_unless(
            $sousActivite->activite_id == $activite->id,
            404
        );

        abort_unless(
            $tache->sous_activite_id == $sousActivite->id,
            404
        );

        $validated = $request->validate([
            'libelle' => [
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
                'string',
                'in:a_faire,en_cours,suspendue,terminee,annulee',
            ],

            'priorite' => [
                'nullable',
                'string',
                'in:basse,normale,haute,urgente',
            ],

            'date_enclenchement' => [
                'nullable',
                'date',
            ],

            'date_execution' => [
                'nullable',
                'date',
            ],

            'date_fin' => [
                'nullable',
                'date',
                'after_or_equal:date_execution',
            ],

            'progression' => [
                'nullable',
                'integer',
                'min:0',
                'max:100',
            ],

            'ordre' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'observations' => [
                'nullable',
                'string',
            ],
        ]);

        $validated['activite_id'] = $activite->id;
        $validated['sous_activite_id'] = $sousActivite->id;

        // Si la tâche est terminée, la progression passe automatiquement à 100 %
        if (
            isset($validated['statut']) &&
            $validated['statut'] === 'terminee'
        ) {
            $validated['progression'] = 100;
        }

        $tache->update($validated);

        return redirect()
            ->route(
                'onfp.activites.sous-activites.taches.index',
                [
                    'activite' => $activite,
                    'sousActivite' => $sousActivite,
                ]
            )
            ->with(
                'success',
                'La tâche a été mise à jour avec succès.'
            );
    }

    /**
     * Supprimer une tâche.
     */
    public function destroy(
        OnfpActivite $activite,
        OnfpSousActivite $sousActivite,
        OnfpTache $tache
    ) {
        // Vérifications de cohérence
        abort_unless(
            $sousActivite->activite_id == $activite->id,
            404
        );

        abort_unless(
            $tache->sous_activite_id == $sousActivite->id,
            404
        );

        $tache->delete();

        return redirect()
            ->route(
                'onfp.activites.sous-activites.taches.index',
                [
                    'activite' => $activite,
                    'sousActivite' => $sousActivite,
                ]
            )
            ->with(
                'success',
                'La tâche a été supprimée avec succès.'
            );
    }
}
