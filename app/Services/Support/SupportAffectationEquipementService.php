<?php

namespace App\Services\Support;

use App\Models\User;
use App\Models\Support\SupportEquipement;
use App\Models\Support\SupportAffectationEquipement;
use App\Models\Support\SupportMouvementEquipement;
use App\Models\Support\SupportEquipementHistorique;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;

class SupportAffectationEquipementService
{
    private const STATUTS_AFFECTABLES = ['EN_STOCK'];

    private const ETATS_PHYSIQUES = [
        'BON',
        'MOYEN',
        'MAUVAIS',
        'HORS_SERVICE',
    ];

    /**
     * Affecter un équipement à un employé, une direction ou une antenne.
     */
    public function affecter(
        SupportEquipement $equipement,
        array $destinataire,
        User $acteur,
        array $options = []
    ): SupportAffectationEquipement {
        $this->autoriser($acteur, 'support.equipements.assign');

        $cible = $this->validerDestinataire($destinataire);
        $options = $this->validerOptions($options, false);

        return DB::transaction(function () use (
            $equipement,
            $cible,
            $acteur,
            $options
        ) {
            $equipement = SupportEquipement::query()
                ->whereKey($equipement->id)
                ->lockForUpdate()
                ->firstOrFail();

            if (!in_array($equipement->statut, self::STATUTS_AFFECTABLES, true)) {
                throw ValidationException::withMessages([
                    'equipement' =>
                        'Cet équipement n’est pas disponible pour une affectation. '
                        . 'Statut actuel : ' . $equipement->statut . '.',
                ]);
            }

            if ($equipement->etat_physique === 'HORS_SERVICE') {
                throw ValidationException::withMessages([
                    'equipement' =>
                        'Un équipement hors service ne peut pas être affecté.',
                ]);
            }

            if ($equipement->affectationActive()->exists()) {
                throw ValidationException::withMessages([
                    'equipement' =>
                        'Cet équipement possède déjà une affectation active.',
                ]);
            }

            if (($options['etat_remise'] ?? $equipement->etat_physique) === 'HORS_SERVICE') {
                throw ValidationException::withMessages([
                    'etat_remise' =>
                        'Un équipement hors service ne peut pas être remis à un détenteur.',
                ]);
            }

            $affectation = SupportAffectationEquipement::create([
                'support_equipement_id' => $equipement->id,
                'employee_id' => $cible['employee_id'],
                'direction_id' => $cible['direction_id'],
                'antenne_id' => $cible['antenne_id'],
                'date_debut' => now(),
                'date_fin' => null,
                'etat_remise' => $options['etat_remise'] ?? $equipement->etat_physique,
                'etat_retour' => null,
                'observations' => $options['observations'] ?? null,
                'motif_retour' => null,
                'user_create_id' => $acteur->id,
                'user_update_id' => null,
            ]);

            $ancienStatut = $equipement->statut;

            $equipement->statut = 'AFFECTE';
            $equipement->etat_physique = $affectation->etat_remise;
            $equipement->user_update_id = $acteur->id;
            $equipement->save();

            SupportMouvementEquipement::create([
                'support_equipement_id' => $equipement->id,
                'affectation_origine_id' => null,
                'affectation_destination_id' => $affectation->id,
                'type_mouvement' => 'AFFECTATION',
                'date_mouvement' => now(),
                'motif' => $options['motif'] ?? 'Affectation initiale',
                'observations' => $options['observations'] ?? null,
                'user_create_id' => $acteur->id,
            ]);

            $this->historiser(
                $equipement,
                $acteur,
                'AFFECTATION',
                'statut',
                $ancienStatut,
                'AFFECTE',
                'Affectation de l’équipement '
                    . $equipement->reference
                    . ' à ' . $this->descriptionCible($cible)
                    . '.'
            );

            return $affectation->fresh([
                'equipement',
                'employee',
                'direction',
                'antenne',
                'createur',
            ]);
        });
    }

    /**
     * Enregistrer le retour d'un équipement au parc.
     */
    public function retourner(
        SupportEquipement $equipement,
        array $donnees,
        User $acteur
    ): SupportAffectationEquipement {
        $this->autoriser($acteur, 'support.equipements.assign');

        $donnees = $this->validerRetour($donnees);

        return DB::transaction(function () use (
            $equipement,
            $donnees,
            $acteur
        ) {
            $equipement = SupportEquipement::query()
                ->whereKey($equipement->id)
                ->lockForUpdate()
                ->firstOrFail();

            $affectation = $equipement->affectationActive()
                ->lockForUpdate()
                ->first();

            if (!$affectation) {
                throw ValidationException::withMessages([
                    'equipement' =>
                        'Aucune affectation active n’a été trouvée pour cet équipement.',
                ]);
            }

            $dateRetour = now();

            $affectation->date_fin = $dateRetour;
            $affectation->etat_retour = $donnees['etat_retour']
                ?? $equipement->etat_physique;
            $affectation->motif_retour = $donnees['motif_retour'] ?? null;
            $affectation->user_update_id = $acteur->id;

            if (isset($donnees['observations'])) {
                $affectation->observations = $donnees['observations'];
            }

            $affectation->save();

            $ancienStatut = $equipement->statut;

            $equipement->statut =
                $affectation->etat_retour === 'HORS_SERVICE'
                    ? 'EN_MAINTENANCE'
                    : 'EN_STOCK';

            if (isset($donnees['etat_retour'])) {
                $equipement->etat_physique = $donnees['etat_retour'];
            }

            $equipement->user_update_id = $acteur->id;
            $equipement->save();

            SupportMouvementEquipement::create([
                'support_equipement_id' => $equipement->id,
                'affectation_origine_id' => $affectation->id,
                'affectation_destination_id' => null,
                'type_mouvement' => 'RETOUR',
                'date_mouvement' => $dateRetour,
                'motif' => $donnees['motif_retour'] ?? 'Retour au parc informatique',
                'observations' => $donnees['observations'] ?? null,
                'user_create_id' => $acteur->id,
            ]);

            $this->historiser(
                $equipement,
                $acteur,
                'RETOUR',
                'statut',
                $ancienStatut,
                $equipement->statut,
                'Retour de l’équipement '
                    . $equipement->reference
                    . ' au parc informatique.'
            );

            return $affectation->fresh([
                'equipement',
                'employee',
                'direction',
                'antenne',
                'modificateur',
            ]);
        });
    }

    /**
     * Transférer un équipement vers un autre destinataire.
     *
     * L'ancienne affectation est clôturée et la nouvelle est créée
     * dans une seule transaction.
     */
    public function transferer(
        SupportEquipement $equipement,
        array $nouveauDestinataire,
        User $acteur,
        array $options = []
    ): SupportAffectationEquipement {
        $this->autoriser($acteur, 'support.equipements.move');

        $cible = $this->validerDestinataire($nouveauDestinataire);
        $options = $this->validerOptions($options, true);

        return DB::transaction(function () use (
            $equipement,
            $cible,
            $acteur,
            $options
        ) {
            $equipement = SupportEquipement::query()
                ->whereKey($equipement->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($equipement->statut !== 'AFFECTE') {
                throw ValidationException::withMessages([
                    'equipement' =>
                        'Seul un équipement actuellement affecté peut être transféré.',
                ]);
            }

            $ancienneAffectation = $equipement->affectationActive()
                ->lockForUpdate()
                ->first();

            if (!$ancienneAffectation) {
                throw ValidationException::withMessages([
                    'equipement' =>
                        'Aucune affectation active ne permet ce transfert.',
                ]);
            }

            if (
                (int) ($ancienneAffectation->employee_id ?? 0)
                    === (int) ($cible['employee_id'] ?? 0)
                && (int) ($ancienneAffectation->direction_id ?? 0)
                    === (int) ($cible['direction_id'] ?? 0)
                && (int) ($ancienneAffectation->antenne_id ?? 0)
                    === (int) ($cible['antenne_id'] ?? 0)
            ) {
                throw ValidationException::withMessages([
                    'destinataire' =>
                        'Le nouvel emplacement correspond déjà au destinataire actuel.',
                ]);
            }

            // Un équipement hors service ne peut pas être transféré.
            if (($options['etat_retour'] ?? $equipement->etat_physique) === 'HORS_SERVICE') {
                throw ValidationException::withMessages([
                    'etat_retour' =>
                        'Cet équipement est hors service. Effectuez son retour et son envoi en maintenance avant toute nouvelle affectation.',
                ]);
            }

            if (($options['etat_remise'] ?? $equipement->etat_physique) === 'HORS_SERVICE') {
                throw ValidationException::withMessages([
                    'etat_remise' =>
                        'Un équipement hors service ne peut pas être remis à un nouveau détenteur.',
                ]);
            }

            $dateMouvement = now();

            // Clôturer l'ancienne affectation.
            $ancienneAffectation->date_fin = $dateMouvement;
            $ancienneAffectation->etat_retour =
                $options['etat_retour'] ?? $equipement->etat_physique;
            $ancienneAffectation->motif_retour =
                $options['motif'] ?? 'Transfert vers un nouveau destinataire';
            $ancienneAffectation->user_update_id = $acteur->id;
            $ancienneAffectation->save();

            // Créer la nouvelle affectation.
            $nouvelleAffectation = SupportAffectationEquipement::create([
                'support_equipement_id' => $equipement->id,
                'employee_id' => $cible['employee_id'],
                'direction_id' => $cible['direction_id'],
                'antenne_id' => $cible['antenne_id'],
                'date_debut' => $dateMouvement,
                'date_fin' => null,
                'etat_remise' => $options['etat_remise']
                    ?? $equipement->etat_physique,
                'etat_retour' => null,
                'observations' => $options['observations'] ?? null,
                'motif_retour' => null,
                'user_create_id' => $acteur->id,
                'user_update_id' => null,
            ]);

            // Enregistrer le mouvement entre les deux affectations.
            SupportMouvementEquipement::create([
                'support_equipement_id' => $equipement->id,
                'affectation_origine_id' => $ancienneAffectation->id,
                'affectation_destination_id' => $nouvelleAffectation->id,
                'type_mouvement' => 'TRANSFERT',
                'date_mouvement' => $dateMouvement,
                'motif' => $options['motif'] ?? 'Transfert d’équipement',
                'observations' => $options['observations'] ?? null,
                'user_create_id' => $acteur->id,
            ]);

            $this->historiser(
                $equipement,
                $acteur,
                'TRANSFERT',
                'affectation',
                (string) $ancienneAffectation->id,
                (string) $nouvelleAffectation->id,
                'Transfert de l’équipement '
                    . $equipement->reference
                    . ' de ' . $this->descriptionCible([
                        'employee_id' => $ancienneAffectation->employee_id,
                        'direction_id' => $ancienneAffectation->direction_id,
                        'antenne_id' => $ancienneAffectation->antenne_id,
                    ])
                    . ' vers ' . $this->descriptionCible($cible)
                    . '.'
            );

            $equipement->user_update_id = $acteur->id;
            $equipement->etat_physique =
                $nouvelleAffectation->etat_remise;
            $equipement->statut = 'AFFECTE';
            $equipement->save();

            return $nouvelleAffectation->fresh([
                'equipement',
                'employee',
                'direction',
                'antenne',
                'createur',
            ]);
        });
    }


/**
 * Vérifier qu'un seul destinataire est renseigné et qu'il existe.
 * Les enregistrements supprimés logiquement sont exclus.
 */
private function validerDestinataire(array $donnees): array
{
    $champs = ['employee_id', 'direction_id', 'antenne_id'];

    $nombre = 0;

    foreach ($champs as $champ) {
        if (
            isset($donnees[$champ])
            && (int) $donnees[$champ] > 0
        ) {
            $nombre++;
        }
    }

    if ($nombre !== 1) {
        throw ValidationException::withMessages([
            'destinataire' =>
                'Vous devez sélectionner exactement un employé, une direction ou une antenne.',
        ]);
    }

    $regles = [
        'employee_id' => [
            'nullable',
            'integer',
            Rule::exists('employees', 'id')->whereNull('deleted_at'),
        ],
        'direction_id' => [
            'nullable',
            'integer',
            Rule::exists('directions', 'id')->whereNull('deleted_at'),
        ],
        'antenne_id' => [
            'nullable',
            'integer',
            Rule::exists('antennes', 'id')->whereNull('deleted_at'),
        ],
    ];

    $validateur = Validator::make($donnees, $regles);

    if ($validateur->fails()) {
        throw new ValidationException($validateur);
    }

    $valide = $validateur->validated();

    return [
        'employee_id' => $valide['employee_id'] ?? null,
        'direction_id' => $valide['direction_id'] ?? null,
        'antenne_id' => $valide['antenne_id'] ?? null,
    ];
}


    /**
     * Valider les options d'affectation ou de transfert.
     */
    private function validerOptions(
        array $donnees,
        bool $transfert
    ): array {
        $regles = [
            'etat_remise' => [
                'sometimes',
                'nullable',
                'in:' . implode(',', self::ETATS_PHYSIQUES),
            ],
            'motif' => ['sometimes', 'nullable', 'string', 'max:255'],
            'observations' => ['sometimes', 'nullable', 'string'],
        ];

        if ($transfert) {
            $regles['etat_retour'] = [
                'sometimes',
                'nullable',
                'in:' . implode(',', self::ETATS_PHYSIQUES),
            ];
        }

        $validateur = Validator::make($donnees, $regles);

        if ($validateur->fails()) {
            throw new ValidationException($validateur);
        }

        return $validateur->validated();
    }

    /**
     * Valider les données de retour.
     */
    private function validerRetour(array $donnees): array
    {
        $validateur = Validator::make($donnees, [
            'etat_retour' => [
                'sometimes',
                'nullable',
                'in:' . implode(',', self::ETATS_PHYSIQUES),
            ],
            'motif_retour' => [
                'sometimes',
                'nullable',
                'string',
                'max:255',
            ],
            'observations' => [
                'sometimes',
                'nullable',
                'string',
            ],
        ]);

        if ($validateur->fails()) {
            throw new ValidationException($validateur);
        }

        return $validateur->validated();
    }

    /**
     * Vérifier les permissions.
     */
    private function autoriser(User $acteur, string $permission): void
    {
        if (!$acteur->can($permission)) {
            throw ValidationException::withMessages([
                'autorisation' =>
                    'Vous n’êtes pas autorisé à effectuer cette opération.',
            ]);
        }
    }

    /**
     * Enregistrer l'historique de l'équipement.
     */
    private function historiser(
        SupportEquipement $equipement,
        User $acteur,
        string $action,
        ?string $champ,
        mixed $ancienneValeur,
        mixed $nouvelleValeur,
        ?string $description = null
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

    /**
     * Construire une description du destinataire sans supposer
     * les colonnes de nom des référentiels RH.
     */
    private function descriptionCible(array $cible): string
    {
        if (!empty($cible['employee_id'])) {
            return 'l’employé #' . $cible['employee_id'];
        }

        if (!empty($cible['direction_id'])) {
            return 'la direction #' . $cible['direction_id'];
        }

        if (!empty($cible['antenne_id'])) {
            return 'l’antenne #' . $cible['antenne_id'];
        }

        return 'un destinataire non précisé';
    }
}
