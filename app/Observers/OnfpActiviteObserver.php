<?php

namespace App\Observers;

use App\Models\OnfpActivite;
use Carbon\Carbon;

class OnfpActiviteObserver
{
    /**
     * Champs surveillés : clé technique => libellé affiché dans l'historique.
     * Ajoutez ici tout nouveau champ dont vous voulez tracer les changements.
     */
    private const CHAMPS_SUIVIS = [
        'statut' => 'Statut',
        'priorite' => 'Priorité',
        'etat_sante' => 'État de santé',
        'progression' => 'Progression',
        'direction_id' => 'Direction',
        'type_id' => "Type d'activité",
        'date_enclenchement' => "Date d'enclenchement",
        'date_execution_prevue' => 'Exécution prévue',
        'date_fin_prevue' => 'Fin prévue',
        'date_execution_reelle' => 'Exécution réelle',
        'date_fin_reelle' => 'Fin réelle',
    ];

    private const CHAMPS_DATE = [
        'date_enclenchement',
        'date_execution_prevue',
        'date_fin_prevue',
        'date_execution_reelle',
        'date_fin_reelle',
    ];

    public function created(OnfpActivite $activite): void
    {
        $activite->historiques()->create([
            'employee_id' => $this->employeeId(),
            'action' => 'creation',
            'description' => 'Activité créée.',
        ]);
    }

    public function updated(OnfpActivite $activite): void
    {
        $changements = [];
        $donneesAvant = [];
        $donneesApres = [];
        $ancienStatut = null;
        $nouveauStatut = null;
        $ancienneProgression = null;
        $nouvelleProgression = null;

        foreach (self::CHAMPS_SUIVIS as $champ => $label) {
            if (!$activite->wasChanged($champ)) {
                continue;
            }

            $ancienneValeur = $activite->getOriginal($champ);
            $nouvelleValeur = $activite->getAttribute($champ);

            $changements[] = sprintf(
                '%s : %s → %s',
                $label,
                $this->formatValeur($champ, $ancienneValeur),
                $this->formatValeur($champ, $nouvelleValeur)
            );

            $donneesAvant[$champ] = $ancienneValeur;
            $donneesApres[$champ] = $nouvelleValeur;

            if ($champ === 'statut') {
                $ancienStatut = $ancienneValeur;
                $nouveauStatut = $nouvelleValeur;
            }

            if ($champ === 'progression') {
                $ancienneProgression = $ancienneValeur;
                $nouvelleProgression = $nouvelleValeur;
            }
        }

        if (empty($changements)) {
            return;
        }

        $activite->historiques()->create([
            'employee_id' => $this->employeeId(),
            'action' => 'modification',
            'ancien_statut' => $ancienStatut,
            'nouveau_statut' => $nouveauStatut,
            'ancienne_progression' => $ancienneProgression,
            'nouvelle_progression' => $nouvelleProgression,
            'description' => implode(' | ', $changements),
            'donnees_avant' => $donneesAvant,
            'donnees_apres' => $donneesApres,
        ]);
    }

    /**
     * Se déclenche aussi bien pour un soft delete (SoftDeletes) que pour
     * une suppression définitive, selon ce que le modèle utilise.
     */
    public function deleted(OnfpActivite $activite): void
    {
        $activite->historiques()->create([
            'employee_id' => $this->employeeId(),
            'action' => 'suppression',
            'description' => 'Activité déplacée dans la corbeille.',
        ]);
    }

    public function restored(OnfpActivite $activite): void
    {
        $activite->historiques()->create([
            'employee_id' => $this->employeeId(),
            'action' => 'restauration',
            'description' => 'Activité restaurée depuis la corbeille.',
        ]);
    }

    private function formatValeur(string $champ, $valeur): string
    {
        if ($valeur === null || $valeur === '') {
            return '—';
        }

        if (in_array($champ, self::CHAMPS_DATE, true)) {
            return Carbon::parse($valeur)->format('d/m/Y');
        }

        if ($champ === 'progression') {
            return $valeur . '%';
        }

        return (string) $valeur;
    }

    private function employeeId(): ?int
    {
        return optional(auth()->user()?->employee)->id;
    }
}
