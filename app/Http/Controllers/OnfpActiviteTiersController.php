<?php

namespace App\Http\Controllers;

use App\Models\OnfpActivite;
use App\Models\OnfpActiviteTiers;
use App\Models\OnfpTiers;
use Illuminate\Http\Request;

class OnfpActiviteTiersController extends Controller
{
    /**
     * Liste des tiers déjà associés à l'activité.
     */
    public function index(OnfpActivite $activite)
    {
        $activiteTiers = OnfpActiviteTiers::where('activite_id', $activite->id)
            ->with('tiers')
            ->get();

        return view('onfp.tiers.index', compact('activite', 'activiteTiers'));
    }

    /**
     * Formulaire d'ajout : choisir un tiers existant, ou en créer un nouveau à la volée.
     */
    public function create(OnfpActivite $activite)
    {
        // On exclut les tiers déjà associés à cette activité
        $dejaAssocies = OnfpActiviteTiers::where('activite_id', $activite->id)
            ->pluck('tier_id');

        $tiersDisponibles = OnfpTiers::actifs()
            ->whereNotIn('id', $dejaAssocies)
            ->orderBy('nom')
            ->get();

        return view('onfp.activites.tiers.create', [
            'activite' => $activite,
            'tiersDisponibles' => $tiersDisponibles,
            'types' => OnfpTiers::TYPES,
        ]);
    }

    /**
     * Associe un tiers existant, OU en crée un nouveau puis l'associe,
     * selon ce que le formulaire a soumis.
     */
    public function store(Request $request, OnfpActivite $activite)
    {
        $validated = $request->validate([
            'mode' => [
                'required',
                'in:existant,nouveau',
            ],
            'tier_id' => [
                'required_if:mode,existant',
                'nullable',
                'exists:onfp_tiers,id',
            ],
            'role' => [
                'nullable',
                'string',
                'max:150',
            ],
            'observation' => [
                'nullable',
                'string',
            ],

            // Champs pour la création d'un nouveau tiers à la volée
            'nom' => [
                'required_if:mode,nouveau',
                'nullable',
                'string',
                'max:255',
            ],
            'type' => [
                'nullable',
                'string',
                'in:' . implode(',', array_keys(OnfpTiers::TYPES)),
            ],
            'organisation' => [
                'nullable',
                'string',
                'max:255',
            ],
            'fonction' => [
                'nullable',
                'string',
                'max:150',
            ],
            'telephone' => [
                'nullable',
                'string',
                'max:50',
            ],
            'email' => [
                'nullable',
                'email',
                'max:150',
            ],
        ]);

        if ($validated['mode'] === 'nouveau') {
            $tier = OnfpTiers::create([
                'nom' => $validated['nom'],
                'type' => $validated['type'] ?? null,
                'organisation' => $validated['organisation'] ?? null,
                'fonction' => $validated['fonction'] ?? null,
                'telephone' => $validated['telephone'] ?? null,
                'email' => $validated['email'] ?? null,
                'actif' => true,
            ]);

            $tierId = $tier->id;
        } else {
            $tierId = $validated['tier_id'];
        }

        // Évite les doublons (contrainte unique en base de toute façon,
        // mais on donne un message clair plutôt qu'une erreur SQL brute)
        $existeDeja = OnfpActiviteTiers::where('activite_id', $activite->id)
            ->where('tier_id', $tierId)
            ->exists();

        if ($existeDeja) {
            return back()
                ->withInput()
                ->with('error', 'Ce tiers est déjà associé à cette activité.');
        }

        OnfpActiviteTiers::create([
            'activite_id' => $activite->id,
            'tier_id' => $tierId,
            'role' => $validated['role'] ?? null,
            'observation' => $validated['observation'] ?? null,
        ]);

        return redirect()
            ->route('onfp.tiers.index', $activite)
            ->with('success', 'Le tiers a été associé à l\'activité avec succès.');
    }

    /**
     * Détache un tiers de l'activité (ne supprime pas le tiers lui-même).
     */
    public function destroy(OnfpActivite $activite, OnfpActiviteTiers $tier)
    {
        abort_unless(
            (int) $tier->activite_id === (int) $activite->id,
            404
        );

        $tier->delete();

        return redirect()
            ->route('onfp.tiers.index', $activite)
            ->with('success', 'Le tiers a été retiré de l\'activité.');
    }
}
