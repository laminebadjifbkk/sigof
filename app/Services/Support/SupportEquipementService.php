<?php

namespace App\Services\Support;

use App\Models\User;
use App\Models\Support\SupportCategorieEquipement;
use App\Models\Support\SupportEquipement;
use App\Models\Support\SupportEquipementHistorique;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class SupportEquipementService
{
    private const ETATS = ['BON', 'MOYEN', 'MAUVAIS', 'HORS_SERVICE'];

    private const STATUTS = [
        'EN_STOCK',
        'AFFECTE',
        'EN_MAINTENANCE',
        'REFORME',
        'PERDU',
    ];

    public function __construct(
        private SupportEquipementReferenceService $referenceService
    ) {
    }

    /**
     * Créer un équipement.
     */
    public function creer(array $donnees, User $acteur): SupportEquipement
    {
        $this->autoriser($acteur, 'support.equipements.create');

        $donnees = $this->valider($donnees, true);

        return DB::transaction(function () use ($donnees, $acteur) {

            $categorie = SupportCategorieEquipement::query()
                ->whereKey($donnees['support_categorie_equipement_id'])
                ->where('actif', true)
                ->first();

            if (!$categorie) {
                throw ValidationException::withMessages([
                    'support_categorie_equipement_id' =>
                        'La catégorie sélectionnée est indisponible.',
                ]);
            }

            $equipement = SupportEquipement::create([
                'reference' => $this->referenceService->generer(),
                'designation' => $donnees['designation'],
                'support_categorie_equipement_id' => $categorie->id,
                'marque' => $donnees['marque'] ?? null,
                'modele' => $donnees['modele'] ?? null,
                'numero_serie' => $donnees['numero_serie'] ?? null,
                'date_acquisition' => $donnees['date_acquisition'] ?? null,
                'fournisseur' => $donnees['fournisseur'] ?? null,
                'prix_acquisition' => $donnees['prix_acquisition'] ?? null,
                'date_fin_garantie' => $donnees['date_fin_garantie'] ?? null,
                'etat_physique' => $donnees['etat_physique'] ?? 'BON',
                'statut' => 'EN_STOCK',
                'emplacement' => $donnees['emplacement'] ?? null,
                'description' => $donnees['description'] ?? null,
                'observations' => $donnees['observations'] ?? null,
                'user_create_id' => $acteur->id,
                'user_update_id' => null,
            ]);

            $this->historiser(
                $equipement,
                $acteur,
                'CREATION',
                null,
                null,
                $equipement->reference,
                'Création de l’équipement ' . $equipement->reference . '.'
            );

            return $equipement->fresh([
                'categorie',
                'createur',
                'historiques',
            ]);
        });
    }

    /**
     * Modifier un équipement.
     */
    public function modifier(
        SupportEquipement $equipement,
        array $donnees,
        User $acteur
    ): SupportEquipement {
        $this->autoriser($acteur, 'support.equipements.update');

        $donnees = $this->valider($donnees, false, $equipement->id);

        return DB::transaction(function () use (
            $equipement,
            $donnees,
            $acteur
        ) {
            $equipement = SupportEquipement::query()
                ->whereKey($equipement->id)
                ->lockForUpdate()
                ->firstOrFail();

            if (isset($donnees['support_categorie_equipement_id'])) {
                $categorieActive = SupportCategorieEquipement::query()
                    ->whereKey($donnees['support_categorie_equipement_id'])
                    ->where('actif', true)
                    ->exists();

                if (!$categorieActive) {
                    throw ValidationException::withMessages([
                        'support_categorie_equipement_id' =>
                            'La catégorie sélectionnée est indisponible.',
                    ]);
                }
            }

            if (
                isset($donnees['statut'])
                && $equipement->affectationActive()->exists()
                && in_array($donnees['statut'], [
                    'EN_STOCK',
                    'REFORME',
                    'PERDU',
                ], true)
            ) {
                throw ValidationException::withMessages([
                    'statut' =>
                        'Cet équipement est affecté. Enregistrez son retour avant de modifier son statut.',
                ]);
            }

            $champs = [
                'designation',
                'support_categorie_equipement_id',
                'marque',
                'modele',
                'numero_serie',
                'date_acquisition',
                'fournisseur',
                'prix_acquisition',
                'date_fin_garantie',
                'etat_physique',
                'statut',
                'emplacement',
                'description',
                'observations',
            ];

            $modifications = [];

            foreach ($champs as $champ) {
                if (!array_key_exists($champ, $donnees)) {
                    continue;
                }

                $ancienne = $this->normaliser($equipement->getAttribute($champ));
                $nouvelle = $this->normaliser($donnees[$champ]);

                if ($ancienne === $nouvelle) {
                    continue;
                }

                $modifications[$champ] = [
                    'ancienne' => $ancienne,
                    'nouvelle' => $nouvelle,
                ];

                $equipement->{$champ} = $donnees[$champ];
            }

            if (empty($modifications)) {
                return $equipement->fresh([
                    'categorie',
                    'affectationActive',
                ]);
            }

            $equipement->user_update_id = $acteur->id;
            $equipement->save();

            foreach ($modifications as $champ => $valeurs) {
                $this->historiser(
                    $equipement,
                    $acteur,
                    'MODIFICATION',
                    $champ,
                    $valeurs['ancienne'],
                    $valeurs['nouvelle'],
                    'Modification du champ ' . $champ . '.'
                );
            }

            return $equipement->fresh([
                'categorie',
                'affectationActive',
                'modificateur',
                'historiques',
            ]);
        });
    }

    /**
     * Réformer un équipement sans le supprimer physiquement.
     */
    public function retirer(
        SupportEquipement $equipement,
        User $acteur,
        ?string $motif = null
    ): SupportEquipement {
        $this->autoriser($acteur, 'support.equipements.delete');

        return DB::transaction(function () use (
            $equipement,
            $acteur,
            $motif
        ) {
            $equipement = SupportEquipement::query()
                ->whereKey($equipement->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($equipement->affectationActive()->exists()) {
                throw ValidationException::withMessages([
                    'equipement' =>
                        'Impossible de réformer un équipement encore affecté.',
                ]);
            }

            if ($equipement->statut === 'REFORME') {
                throw ValidationException::withMessages([
                    'equipement' => 'Cet équipement est déjà réformé.',
                ]);
            }

            $ancienStatut = $equipement->statut;

            $equipement->statut = 'REFORME';
            $equipement->user_update_id = $acteur->id;

            if ($motif !== null && trim($motif) !== '') {
                $equipement->observations = trim($motif);
            }

            $equipement->save();

            $this->historiser(
                $equipement,
                $acteur,
                'REFORME',
                'statut',
                $ancienStatut,
                'REFORME',
                'Réforme de ' . $equipement->reference
                    . ($motif ? '. Motif : ' . $motif : '.')
            );

            return $equipement->fresh([
                'categorie',
                'historiques',
            ]);
        });
    }

    /**
     * Validation des données.
     */
    private function valider(
        array $donnees,
        bool $creation,
        ?int $equipementId = null
    ): array {
        $reglesNumeroSerie = $equipementId
            ? Rule::unique('support_equipements', 'numero_serie')
                ->ignore($equipementId)
            : 'unique:support_equipements,numero_serie';

        $regles = [
            'designation' => [
                $creation ? 'required' : 'sometimes',
                'string',
                'max:200',
            ],
            'support_categorie_equipement_id' => [
                $creation ? 'required' : 'sometimes',
                'integer',
                'exists:support_categories_equipements,id',
            ],
            'marque' => ['sometimes', 'nullable', 'string', 'max:100'],
            'modele' => ['sometimes', 'nullable', 'string', 'max:150'],
            'numero_serie' => [
                'sometimes',
                'nullable',
                'string',
                'max:150',
                $reglesNumeroSerie,
            ],
            'date_acquisition' => ['sometimes', 'nullable', 'date'],
            'fournisseur' => ['sometimes', 'nullable', 'string', 'max:200'],
            'prix_acquisition' => [
                'sometimes',
                'nullable',
                'numeric',
                'min:0',
            ],
            'date_fin_garantie' => ['sometimes', 'nullable', 'date'],
            'etat_physique' => [
                'sometimes',
                'required',
                'in:' . implode(',', self::ETATS),
            ],
            'statut' => [
                'sometimes',
                'required',
                'in:' . implode(',', self::STATUTS),
            ],
            'emplacement' => ['sometimes', 'nullable', 'string', 'max:200'],
            'description' => ['sometimes', 'nullable', 'string'],
            'observations' => ['sometimes', 'nullable', 'string'],
        ];

        if ($creation) {
            unset($regles['statut']);
        }

        $validateur = Validator::make($donnees, $regles);

        if ($validateur->fails()) {
            throw new ValidationException($validateur);
        }

        return $validateur->validated();
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

    private function normaliser(mixed $valeur): ?string
    {
        if ($valeur === null) {
            return null;
        }

        if ($valeur instanceof \DateTimeInterface) {
            return $valeur->format('Y-m-d');
        }

        return trim((string) $valeur);
    }
}
