<?php

namespace App\Services\Support;

use App\Models\User;
use App\Models\Employee;
use App\Models\Direction;
use App\Models\Antenne;
use App\Models\Support\SupportEquipement;
use App\Models\Support\SupportAffectationEquipement;
use App\Models\Support\SupportMouvementEquipement;
use App\Models\Support\SupportEquipementHistorique;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class SupportAffectationEquipementService
{
    private const ETATS = [
        'BON',
        'MOYEN',
        'MAUVAIS',
        'HORS_SERVICE',
    ];

    /**
     * Affectation initiale à un employé, une direction ou une antenne.
     */
    public function affecter(
        SupportEquipement $equipement,
        array $cible,
        User $acteur,
        ?string $etatRemise = null,
        ?string $observations = null
    ): SupportAffectationEquipement {
        $this->autoriser($acteur, 'support.equipements.assign');

        $cible = $this->validerCible($cible);

        if ($etatRemise !== null && !in_array($etatRemise, self::ETATS, true)) {
            throw ValidationException::withMessages([
                'etat_remise' => 'L’état physique indiqué est invalide.',
            ]);
        }

        return DB::transaction(function () use (
            $equipement,
            $cible,
            $acteur,
            $etatRemise,
            $observations
        ) {
            $equipementVerrouille = SupportEquipement::query()
                ->whereKey($equipement->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($equipementVerrouille->statut !== 'EN_STOCK') {
                throw ValidationException::withMessages([
                    'equipement' =>
                        'Seul un équipement disponible en stock peut être affecté.',
                ]);
            }

            if ($equipementVerrouille->affectationActive()->exists()) {
                throw ValidationException::withMessages([
                    'equipement' =>
                        'Cet équipement possède déjà une affectation active.',
                ]);
            }

            $this->verifierCible($cible);

            $date = now();

            $affectation = SupportAffectationEquipement::create([
                'support_equipement_id' => $equipementVerrouille->id,
                'employee_id' => $cible['employee_id'],
                'direction_id' => $cible['direction_id'],
                'antenne_id' => $cible['antenne_id'],
                'date_debut' => $date,
                'date_fin' => null,
                'etat_remise' => $etatRemise
                    ?? $equipementVerrouille->etat_physique,
                'observations' => $observations,
                'user_create_id' => $acteur->id,
            ]);

            $equipementVerrouille->statut = 'AFFECTE';
            $equipementVerrouille->user_update_id = $acteur->id;
            $equipementVerrouille->save();

            SupportMouvementEquipement::create([
                'support_equipement_id' => $equipementVerrouille->id,
                'affectation_origine_id' => null,
                'affectation_destination_id' => $affectation->id,
                'type_mouvement' => 'AFFECTATION',
                'date_mouvement' => $date,
                'motif' => 'Affectation initiale',
                'observations' => $observations,
                'user_create_id' => $acteur->id,
            ]);

            $this->historiser(
                $equipementVerrouille,
                $acteur,
                'AFFECTATION',
                'statut',
                'EN_STOCK',
                'AFFECTE',
                'Affectation de ' . $equipementVerrouille->reference
                    . ' à ' . $this->libelleCible($cible) . '.'
            );

            return $affectation->fresh([
                'equipement',
                'employee',
                'direction',
                'antenne',
            ]);
        });
    }

    /**
     * Enregistrer le retour d'un équipement.
     */
    public function retourner(
        SupportEquipement $equipement,
        User $acteur,
        string $etatRetour,
        ?string $motif = null,
        ?string $observations = null
    ): SupportAffectationEquipement {
        $this->autoriser($acteur, 'support.equipements.assign');

        if (!in_array($etatRetour, self::ETATS, true)) {
            throw ValidationException::withMessages([
                'etat_retour' => 'L’état physique au retour est invalide.',
            ]);
        }

        return DB::transaction(function () use (
            $equipement,
            $acteur,
            $etatRetour,
            $motif,
            $observations
        ) {
            $equipementVerrouille = SupportEquipement::query()
                ->whereKey($equipement->id)
                ->lockForUpdate()
                ->firstOrFail();

            $affectation = SupportAffectationEquipement::query()
                ->where('support_equipement_id', $equipementVerrouille->id)
                ->whereNull('date_fin')
                ->lockForUpdate()
                ->first();

            if (!$affectation) {
                throw ValidationException::withMessages([
                    'equipement' =>
                        'Aucune affectation active n’a été trouvée pour cet équipement.',
                ]);
            }

            $date = now();

            $affectation->date_fin = $date;
            $affectation->etat_retour = $etatRetour;
            $affectation->motif_retour = $motif;
            $affectation->user_update_id = $acteur->id;

            if ($observations !== null) {
                $affectation->observations = $observations;
            }

            $affectation->save();

            $ancienStatut = $equipementVerrouille->statut;

            $equipementVerrouille->statut = 'EN_STOCK';
            $equipementVerrouille->etat_physique = $etatRetour;
            $equipementVerrouille->user_update_id = $acteur->id;
            $equipementVerrouille->save();

            SupportMouvementEquipement::create([
                'support_equipement_id' => $equipementVerrouille->id,
                'affectation_origine_id' => $affectation->id,
                'affectation_destination_id' => null,
                'type_mouvement' => 'RETOUR',
                'date_mouvement' => $date,
                'motif' => $motif ?? 'Retour de l’équipement',
                'observations' => $observations,
                'user_create_id' => $acteur->id,
            ]);

            $this->historiser(
                $equipementVerrouille,
                $acteur,
                'RETOUR',
                'statut',
                $ancienStatut,
                'EN_STOCK',
                'Retour de ' . $equipementVerrouille->reference
                    . '. État constaté : ' . $etatRetour
                    . ($motif ? '. Motif : ' . $motif : '.')
            );

            return $affectation->fresh([
                'equipement',
                'employee',
                'direction',
                'antenne',
            ]);
        });
    }

    /**
     * Transférer directement un équipement vers une autre cible.
     */
    public function transferer(
        SupportEquipement $equipement,
        array $nouvelleCible,
        User $acteur,
        ?string $motif = null,
        ?string $observations = null,
        ?string $etatRemise = null
    ): SupportAffectationEquipement {
        $this->autoriser($acteur, 'support.equipements.move');

        $nouvelleCible = $this->validerCible($nouvelleCible);

        if ($etatRemise !== null && !in_array($etatRemise, self::ETATS, true)) {
            throw ValidationException::withMessages([
                'etat_remise' => 'L’état physique indiqué est invalide.',
            ]);
        }

        return DB::transaction(function () use (
            $equipement,
            $nouvelleCible,
            $acteur,
            $motif,
            $observations,
            $etatRemise
        ) {
            $equipementVerrouille = SupportEquipement::query()
                ->whereKey($equipement->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($equipementVerrouille->statut !== 'AFFECTE') {
                throw ValidationException::withMessages([
                    'equipement' =>
                        'Seul un équipement actuellement affecté peut être transféré.',
                ]);
            }

            $ancienneAffectation = SupportAffectationEquipement::query()
                ->where('support_equipement_id', $equipementVerrouille->id)
                ->whereNull('date_fin')
                ->lockForUpdate()
                ->first();

            if (!$ancienneAffectation) {
                throw ValidationException::withMessages([
                    'equipement' =>
                        'Aucune affectation active n’a été trouvée.',
                ]);
            }

            $this->verifierCible($nouvelleCible);

            if (
                (int) ($ancienneAffectation->employee_id ?? 0)
                    === (int) ($nouvelleCible['employee_id'] ?? 0)
                && (int) ($ancienneAffectation->direction_id ?? 0)
                    === (int) ($nouvelleCible['direction_id'] ?? 0)
                && (int) ($ancienneAffectation->antenne_id ?? 0)
                    === (int) ($nouvelleCible['antenne_id'] ?? 0)
            ) {
                throw ValidationException::withMessages([
                    'cible' => 'La nouvelle destination est identique à la destination actuelle.',
                ]);
            }

            $date = now();

            // Clôturer l'ancienne affectation.
            $ancienneAffectation->date_fin = $date;
            $ancienneAffectation->motif_retour = $motif
                ?? 'Transfert vers une nouvelle destination';
            $ancienneAffectation->user_update_id = $acteur->id;
            $ancienneAffectation->save();

            // Créer la nouvelle affectation.
            $nouvelleAffectation = SupportAffectationEquipement::create([
                'support_equipement_id' => $equipementVerrouille->id,
                'employee_id' => $nouvelleCible['employee_id'],
                'direction_id' => $nouvelleCible['direction_id'],
                'antenne_id' => $nouvelleCible['antenne_id'],
                'date_debut' => $date,
                'date_fin' => null,
                'etat_remise' => $etatRemise
                    ?? $equipementVerrouille->etat_physique,
                'observations' => $observations,
                'user_create_id' => $acteur->id,
            ]);

            // Enregistrer le mouvement entre les deux affectations.
            SupportMouvementEquipement::create([
                'support_equipement_id' => $equipementVerrouille->id,
                'affectation_origine_id' => $ancienneAffectation->id,
                'affectation_destination_id' => $nouvelleAffectation->id,
                'type_mouvement' => 'TRANSFERT',
                'date_mouvement' => $date,
                'motif' => $motif ?? 'Transfert d’équipement',
                'observations' => $observations,
                'user_create_id' => $acteur->id,
            ]);

            $this->historiser(
                $equipementVerrouille,
                $acteur,
                'TRANSFERT',
                'affectation',
                $this->libelleAffectation($ancienneAffectation),
                $this->libelleCible($nouvelleCible),
                'Transfert de ' . $equipementVerrouille->reference
                    . ' vers ' . $this->libelleCible($nouvelleCible)
                    . ($motif ? '. Motif : ' . $motif : '.')
            );

            return $nouvelleAffectation->fresh([
                'equipement',
                'employee',
                'direction',
                'antenne',
            ]);
        });
    }

    /**
     * Valider qu'une seule cible est renseignée.
     */
    private function validerCible(array $cible): array
    {
        $regles = [
            'employee_id' => ['nullable', 'integer'],
            'direction_id' => ['nullable', 'integer'],
            'antenne_id' => ['nullable', 'integer'],
        ];

        $validateur = Validator::make($cible, $regles);

        if ($validateur->fails()) {
            throw new ValidationException($validateur);
        }

        $cible = $validateur->validated();

        $cible = [
            'employee_id' => $cible['employee_id'] ?? null,
            'direction_id' => $cible['direction_id'] ?? null,
            'antenne_id' => $cible['antenne_id'] ?? null,
        ];

        $nombreCibles = count(array_filter(
            $cible,
            fn ($valeur) => $valeur !== null
        ));

        if ($nombreCibles !== 1) {
            throw ValidationException::withMessages([
                'cible' =>
                    'Une seule destination doit être sélectionnée : employé, direction ou antenne.',
            ]);
        }

        return $cible;
    }

    /**
     * Vérifier l'existence de la cible.
     */
    private function verifierCible(array $cible): void
    {
        if ($cible['employee_id'] !== null) {
            $existe = Employee::query()
                ->whereKey($cible['employee_id'])
                ->exists();

            if (!$existe) {
                throw ValidationException::withMessages([
                    'employee_id' => 'L’employé sélectionné est introuvable.',
                ]);
            }
        }

        if ($cible['direction_id'] !== null) {
            $existe = Direction::query()
                ->whereKey($cible['direction_id'])
                ->exists();

            if (!$existe) {
                throw ValidationException::withMessages([
                    'direction_id' => 'La direction sélectionnée est introuvable.',
                ]);
            }
        }

        if ($cible['antenne_id'] !== null) {
            $existe = Antenne::query()
                ->whereKey($cible['antenne_id'])
                ->exists();

            if (!$existe) {
                throw ValidationException::withMessages([
                    'antenne_id' => 'L’antenne sélectionnée est introuvable.',
                ]);
            }
        }
    }

    private function autoriser(User $acteur, string $permission): void
    {
        if (!$acteur->can($permission)) {
            throw ValidationException::withMessages([
                'autorisation' =>
                    'Vous n’êtes pas autorisé à effectuer cette opération.',
            ]);
        }
    }

    private function historiser(
        SupportEquipement $equipement,
        User $acteur,
        string $action,
        ?string $champ,
        mixed $ancienneValeur,
        mixed $nouvelleValeur,
        ?string $description
    ): void {
        SupportEquipementHistorique::create([
            'support_equipement_id' => $equipement->id,
            'user_id' => $acteur->id,
            'action' => $action,
            'champ' => $champ,
            'ancienne_valeur' => $ancienneValeur,
            'nouvelle_valeur' => $nouvelleValeur,
            'description' => $description,
        ]);
    }

    private function libelleCible(array $cible): string
    {
        if ($cible['employee_id'] !== null) {
            $employee = Employee::find($cible['employee_id']);

            return 'l’employé '
                . trim(($employee->matricule ?? '') . ' '
                    . ($employee->firstname ?? '') . ' '
                    . ($employee->name ?? ''));
        }

        if ($cible['direction_id'] !== null) {
            $direction = Direction::find($cible['direction_id']);

            return 'la direction ' . ($direction->libelle ?? $direction->name ?? $direction->id);
        }

        $antenne = Antenne::find($cible['antenne_id']);

        return 'l’antenne ' . ($antenne->libelle ?? $antenne->name ?? $antenne->id);
    }

    private function libelleAffectation(
        SupportAffectationEquipement $affectation
    ): string {
        return $this->libelleCible([
            'employee_id' => $affectation->employee_id,
            'direction_id' => $affectation->direction_id,
            'antenne_id' => $affectation->antenne_id,
        ]);
    }
}
