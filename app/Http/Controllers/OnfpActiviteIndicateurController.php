<?php

namespace App\Http\Controllers;

use App\Models\OnfpActivite;
use App\Models\OnfpActiviteIndicateur;
use Illuminate\Http\Request;

class OnfpActiviteIndicateurController extends Controller
{
    /**
     * Afficher les indicateurs d'une activité.
     */
    public function index(OnfpActivite $activite)
    {
        $indicateurs = $activite->indicateurs()
            ->orderBy('libelle')
            ->get();

        return view('onfp.activites.indicateurs.index', compact(
            'activite',
            'indicateurs'
        ));
    }

    /**
     * Formulaire d'ajout.
     */
    public function create(OnfpActivite $activite)
    {
        return view('onfp.activites.indicateurs.create', compact('activite'));
    }

    /**
     * Enregistrer un indicateur.
     */
    public function store(Request $request, OnfpActivite $activite)
    {
        $validated = $request->validate([
            'code' => [
                'nullable',
                'string',
                'max:50',
            ],

            'libelle' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'unite' => [
                'nullable',
                'string',
                'max:50',
            ],

            'valeur_cible' => [
                'required',
                'numeric',
                'min:0',
            ],

            'valeur_realisee' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'date_reference' => [
                'nullable',
                'date',
            ],

            'sens' => [
                'required',
                'in:croissant,decroissant',
            ],

            'observation' => [
                'nullable',
                'string',
            ],
        ]);

        $cible = (float) $validated['valeur_cible'];
        $realisee = (float) ($validated['valeur_realisee'] ?? 0);

        $pourcentage = $this->calculerPourcentage(
            $cible,
            $realisee,
            $validated['sens']
        );

        $validated['valeur_realisee'] = $realisee;
        $validated['pourcentage'] = $pourcentage;

        $activite->indicateurs()->create($validated);

        return redirect()
            ->route('onfp.activites.indicateurs.index', $activite)
            ->with('success', 'L’indicateur a été ajouté avec succès.');
    }

    /**
     * Formulaire de modification.
     */
    public function edit(
        OnfpActivite $activite,
        OnfpActiviteIndicateur $indicateur
    ) {
        $this->verifierAppartenance($activite, $indicateur);

        return view('onfp.activites.indicateurs.edit', compact(
            'activite',
            'indicateur'
        ));
    }

    /**
     * Mise à jour.
     */
    public function update(
        Request $request,
        OnfpActivite $activite,
        OnfpActiviteIndicateur $indicateur
    ) {
        $this->verifierAppartenance($activite, $indicateur);

        $validated = $request->validate([
            'code' => [
                'nullable',
                'string',
                'max:50',
            ],

            'libelle' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'unite' => [
                'nullable',
                'string',
                'max:50',
            ],

            'valeur_cible' => [
                'required',
                'numeric',
                'min:0',
            ],

            'valeur_realisee' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'date_reference' => [
                'nullable',
                'date',
            ],

            'sens' => [
                'required',
                'in:croissant,decroissant',
            ],

            'observation' => [
                'nullable',
                'string',
            ],
        ]);

        $cible = (float) $validated['valeur_cible'];
        $realisee = (float) ($validated['valeur_realisee'] ?? 0);

        $pourcentage = $this->calculerPourcentage(
            $cible,
            $realisee,
            $validated['sens']
        );

        $validated['valeur_realisee'] = $realisee;
        $validated['pourcentage'] = $pourcentage;

        $indicateur->update($validated);

        return redirect()
            ->route('onfp.activites.indicateurs.index', $activite)
            ->with('success', 'L’indicateur a été modifié avec succès.');
    }

    /**
     * Suppression.
     */
    public function destroy(
        OnfpActivite $activite,
        OnfpActiviteIndicateur $indicateur
    ) {
        $this->verifierAppartenance($activite, $indicateur);

        $indicateur->delete();

        return redirect()
            ->route('onfp.activites.indicateurs.index', $activite)
            ->with('success', 'L’indicateur a été supprimé avec succès.');
    }

    /**
     * Calcul du taux de réalisation.
     */
    private function calculerPourcentage(
        float $cible,
        float $realisee,
        string $sens
    ): float {
        if ($cible <= 0) {
            return 0;
        }

        if ($sens === 'decroissant') {
            /*
             * Pour un indicateur où moins est mieux.
             *
             * Exemple :
             * Cible = 10 dossiers en retard
             * Réalisé = 5
             *
             * Le résultat est considéré comme 100 % atteint.
             */
            if ($realisee <= $cible) {
                return 100;
            }

            return round(($cible / $realisee) * 100, 2);
        }

        // Indicateur croissant : plus est mieux.
        return round(min(($realisee / $cible) * 100, 100), 2);
    }

    /**
     * Vérifie que l'indicateur appartient bien à l'activité.
     */
    private function verifierAppartenance(
        OnfpActivite $activite,
        OnfpActiviteIndicateur $indicateur
    ): void {
        abort_unless(
            $indicateur->activite_id === $activite->id,
            404
        );
    }
}
