<?php

namespace App\Http\Controllers;

use App\Models\Direction;
use App\Models\OnfpActivite;
use Illuminate\Http\Request;

class OnfpDashboardController extends Controller
{
    /**
     * Rôles ayant une vision sur TOUTES les Directions.
     * Les autres utilisateurs ne voient que leur propre Direction
     * (celle de leur fiche employé).
     */
    private const ROLES_VISION_GLOBALE = ['SG', 'DG', 'super-admin'];

    public function global(Request $request)
    {
        $user = auth()->user();

        $visionGlobale = $user->hasAnyRole(self::ROLES_VISION_GLOBALE);

        // Si l'utilisateur n'a pas de vision globale, on le restreint à sa
        // propre Direction. S'il n'a lui-même aucune Direction rattachée,
        // il ne verra aucune donnée (plutôt que de fuiter les autres).
        $directionIdRestreinte = $visionGlobale
            ? null
            : optional($user->employee)->direction_id;

        abort_if(
            !$visionGlobale && !$directionIdRestreinte,
            403,
            "Vous n'êtes rattaché à aucune Direction, impossible d'afficher ce tableau de bord."
        );

        /*
        |----------------------------------------------------------------
        | Directions à afficher (toutes, ou uniquement la sienne)
        |----------------------------------------------------------------
        */

        $directions = Direction::query()
            ->when($directionIdRestreinte, fn($q) => $q->where('id', $directionIdRestreinte))
            ->orderBy('name')
            ->get();

        /*
        |----------------------------------------------------------------
        | Statistiques par Direction (une seule requête groupée)
        |----------------------------------------------------------------
        */

        $statsParDirection = OnfpActivite::query()
            ->when($directionIdRestreinte, fn($q) => $q->where('direction_id', $directionIdRestreinte))
            ->selectRaw("
                direction_id,
                COUNT(*) as total,
                SUM(CASE WHEN statut = 'a_faire'   THEN 1 ELSE 0 END) as a_faire,
                SUM(CASE WHEN statut = 'en_cours'  THEN 1 ELSE 0 END) as en_cours,
                SUM(CASE WHEN statut = 'terminee'  THEN 1 ELSE 0 END) as terminee,
                SUM(CASE WHEN statut = 'suspendue' THEN 1 ELSE 0 END) as suspendue,
                SUM(CASE WHEN statut = 'annulee'   THEN 1 ELSE 0 END) as annulee,
                SUM(CASE WHEN etat_sante IN ('risque','critique') THEN 1 ELSE 0 END) as a_risque,
                SUM(CASE
                    WHEN date_fin_prevue IS NOT NULL
                     AND date_fin_prevue < CURDATE()
                     AND statut NOT IN ('terminee','annulee')
                    THEN 1 ELSE 0 END
                ) as en_retard,
                COALESCE(AVG(progression), 0) as progression_moyenne
            ")
            ->groupBy('direction_id')
            ->get()
            ->keyBy('direction_id');

        /*
        |----------------------------------------------------------------
        | Nombre de responsables distincts actifs par Direction
        |----------------------------------------------------------------
        */

        $responsablesParDirection = OnfpActivite::query()
            ->when($directionIdRestreinte, fn($q) => $q->where('direction_id', $directionIdRestreinte))
            ->join('onfp_activite_responsables', 'onfp_activite_responsables.activite_id', '=', 'onfp_activites.id')
            ->selectRaw('onfp_activites.direction_id, COUNT(DISTINCT onfp_activite_responsables.employee_id) as nb_responsables')
            ->groupBy('onfp_activites.direction_id')
            ->pluck('nb_responsables', 'direction_id');

        /*
        |----------------------------------------------------------------
        | Assemblage final : une ligne par Direction, avec des valeurs
        | par défaut à 0 pour celles qui n'ont encore aucune activité.
        |----------------------------------------------------------------
        */

        $lignesDirections = $directions->map(function ($direction) use ($statsParDirection, $responsablesParDirection) {
            $stats = $statsParDirection->get($direction->id);

            return (object) [
                'direction' => $direction,
                'total' => (int) ($stats->total ?? 0),
                'a_faire' => (int) ($stats->a_faire ?? 0),
                'en_cours' => (int) ($stats->en_cours ?? 0),
                'terminee' => (int) ($stats->terminee ?? 0),
                'suspendue' => (int) ($stats->suspendue ?? 0),
                'annulee' => (int) ($stats->annulee ?? 0),
                'a_risque' => (int) ($stats->a_risque ?? 0),
                'en_retard' => (int) ($stats->en_retard ?? 0),
                'progression_moyenne' => (int) round($stats->progression_moyenne ?? 0),
                'nb_responsables' => (int) ($responsablesParDirection[$direction->id] ?? 0),
            ];
        })->sortByDesc('total')->values();

        /*
        |----------------------------------------------------------------
        | KPI globaux (agrégés sur le périmètre visible)
        |----------------------------------------------------------------
        */

        $kpiGlobaux = [
            'total' => $lignesDirections->sum('total'),
            'en_cours' => $lignesDirections->sum('en_cours'),
            'terminee' => $lignesDirections->sum('terminee'),
            'en_retard' => $lignesDirections->sum('en_retard'),
            'a_risque' => $lignesDirections->sum('a_risque'),
            'progression_moyenne' => $lignesDirections->count()
                ? (int) round($lignesDirections->avg('progression_moyenne'))
                : 0,
        ];

        /*
        |----------------------------------------------------------------
        | Activités les plus urgentes (à risque ou en retard),
        | tous responsables confondus, pour attirer l'œil en premier.
        |----------------------------------------------------------------
        */

        $activitesUrgentes = OnfpActivite::query()
            ->when($directionIdRestreinte, fn($q) => $q->where('direction_id', $directionIdRestreinte))
            ->whereNotIn('statut', ['terminee', 'annulee'])
            ->where(function ($q) {
                $q->whereIn('etat_sante', ['risque', 'critique'])
                    ->orWhere(function ($sub) {
                        $sub->whereNotNull('date_fin_prevue')
                            ->where('date_fin_prevue', '<', now());
                    });
            })
            ->with(['direction:id,name,sigle', 'responsables.employee.user'])
            ->orderBy('date_fin_prevue')
            ->limit(10)
            ->get();

        return view('onfp.dashboard.global', [
            'visionGlobale' => $visionGlobale,
            'lignesDirections' => $lignesDirections,
            'kpiGlobaux' => $kpiGlobaux,
            'activitesUrgentes' => $activitesUrgentes,
            'statuts' => [
                'a_faire' => 'À faire',
                'en_cours' => 'En cours',
                'suspendue' => 'Suspendue',
                'terminee' => 'Terminée',
                'annulee' => 'Annulée',
            ],
        ]);
    }
}
