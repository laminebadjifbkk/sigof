<?php

namespace App\Http\Controllers;

use App\Models\OnfpActivite;
use App\Models\OnfpActiviteTiers;
use App\Models\OnfpTiers;
use Illuminate\Http\Request;

class OnfpActiviteTiersController extends Controller
{
    public const ROLES_SUGGERES = [
        'Partenaire',
        'Prestataire',
        'Bailleur / Financeur',
        'Formateur',
        'Consultant',
        'Facilitateur',
        'Observateur',
        'Bénéficiaire',
    ];
    /**
     * Liste des tiers déjà associés à l'activité.
     */

    public function index(OnfpActivite $activite)
    {
        $activiteTiers = OnfpActiviteTiers::where('activite_id', $activite->id)
            ->with('tiers')
            ->get()
            ->sortBy(fn($lien) => mb_strtolower($lien->tiers?->nom ?? ''))
            ->values();

        $stats = [
            'total'         => $activiteTiers->count(),
            'organisations' => $activiteTiers->pluck('tiers.organisation')->filter()->unique()->count(),
            'sans_role'     => $activiteTiers->filter(fn($l) => blank($l->role))->count(),
            'inactifs'      => $activiteTiers->filter(fn($l) => $l->tiers && ! $l->tiers->actif)->count(),
        ];
        $cartes = [
            [
                'icon' => 'bi-people',
                'color' => 'primary',
                'valeur' => $stats['total'],
                'label' => 'Tiers associés',
            ],
            [
                'icon' => 'bi-building',
                'color' => 'info',
                'valeur' => $stats['organisations'],
                'label' => 'Organisations',
            ],
            [
                'icon' => 'bi-question-circle',
                'color' => 'warning',
                'valeur' => $stats['sans_role'],
                'label' => 'Rôle non précisé',
            ],
            [
                'icon' => 'bi-person-slash',
                'color' => 'danger',
                'valeur' => $stats['inactifs'],
                'label' => 'Tiers inactifs',
            ],
        ];

        return view('onfp.activite-tiers.index', [
            'activite'      => $activite,
            'activiteTiers' => $activiteTiers,
            'stats'         => $stats,
            'cartes'         => $cartes,
            'types'         => OnfpTiers::TYPES,
            'rolesSuggeres' => self::ROLES_SUGGERES,   // <- à ajouter
        ]);
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

    public function update(Request $request, OnfpActivite $activite, OnfpActiviteTiers $lien)
    {
        abort_unless((string) $lien->activite_id === (string) $activite->id, 404);

        $data = $request->validate([
            'role' => ['required', 'string', 'max:100', 'not_in:' . OnfpActiviteTiers::ROLE_PAR_DEFAUT],
        ]);

        $lien->update($data);

        return back()->with('success', 'Rôle mis à jour.');
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
