<?php

namespace App\Services\Support;

use App\Models\Support\SupportArticleStock;
use App\Models\Support\SupportDocumentStock;
use App\Models\Support\SupportLigneDocumentStock;
use App\Models\Support\SupportMouvementStock;
use App\Models\Support\SupportStock;
use App\Models\Support\SupportUniteStock;
use Brick\Math\BigDecimal;
use Brick\Math\RoundingMode;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Throwable;

class SupportStockMouvementService
{
    private const ECHELLE_QUANTITE = 3;
    private const ECHELLE_VALEUR = 4;

    public function __construct(
        private readonly SupportStockCalculService $calculService,
        private readonly SupportHistoriqueStockService $historiqueService
    ) {
    }

    /**
     * Applique les mouvements d'un document déjà validé.
     *
     * Le changement de statut du document reste de la responsabilité
     * du futur SupportDocumentStockService.
     */
    public function appliquerDocument(
        int|SupportDocumentStock $document,
        ?int $userId = null
    ): Collection {
        $documentId = $document instanceof SupportDocumentStock
            ? (int) $document->id
            : $document;

        return DB::transaction(function () use ($documentId, $userId) {
            $document = SupportDocumentStock::query()
                ->whereKey($documentId)
                ->lockForUpdate()
                ->firstOrFail();

            $this->verifierDocument($document, $userId);

            $validateurId = (int) $document->user_validation_id;

            $lignes = SupportLigneDocumentStock::query()
                ->where('support_document_stock_id', $document->id)
                ->orderBy('id')
                ->lockForUpdate()
                ->get();

            if ($lignes->isEmpty()) {
                $this->erreur(
                    'Un document de stock doit contenir au moins une ligne.'
                );
            }

            $articleIds = $lignes->pluck('support_article_stock_id')
                ->unique()
                ->sort()
                ->values();

            // Verrouillage des articles dans un ordre déterministe.
            $articles = SupportArticleStock::query()
                ->whereIn('id', $articleIds)
                ->orderBy('id')
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            if ($articles->count() !== $articleIds->count()) {
                $this->erreur(
                    'Un ou plusieurs articles sont introuvables ou supprimés.'
                );
            }

            foreach ($articles as $article) {
                if (!$article->actif) {
                    $this->erreur(
                        "L'article {$article->reference} est inactif."
                    );
                }
            }

            // Verrouillage des unités de mesure.
            $uniteIds = $articles->pluck('support_unite_stock_id')
                ->unique()
                ->sort()
                ->values();

            $unites = SupportUniteStock::query()
                ->whereIn('id', $uniteIds)
                ->orderBy('id')
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            if ($unites->count() !== $uniteIds->count()) {
                $this->erreur(
                    'Une ou plusieurs unités de mesure sont introuvables.'
                );
            }

            foreach ($unites as $unite) {
                if (!$unite->actif) {
                    $this->erreur(
                        "L'unité {$unite->libelle} est inactive."
                    );
                }
            }

            // Création des lignes de stock manquantes.
            // Les articles sont déjà verrouillés, ce qui protège
            // la création concurrente d'une ligne par article.
            foreach ($articleIds as $articleId) {
                SupportStock::query()->firstOrCreate(
                    ['support_article_stock_id' => $articleId],
                    [
                        'quantite' => '0.000',
                        'cout_moyen_pondere' => '0.0000',
                        'valeur_stock' => '0.0000',
                        'verrouille' => false,
                        'support_inventaire_id' => null,
                    ]
                );
            }

            // Verrouillage des stocks dans le même ordre.
            $stocks = SupportStock::query()
                ->whereIn('support_article_stock_id', $articleIds)
                ->orderBy('support_article_stock_id')
                ->lockForUpdate()
                ->get()
                ->keyBy('support_article_stock_id');

            if ($stocks->count() !== $articleIds->count()) {
                $this->erreur(
                    'Impossible de charger toutes les lignes de stock.'
                );
            }

            $mouvements = collect();

            foreach ($lignes as $ligne) {
                $article = $articles->get(
                    $ligne->support_article_stock_id
                );

                $unite = $unites->get(
                    $article->support_unite_stock_id
                );

                $stock = $stocks->get(
                    $ligne->support_article_stock_id
                );

                $mouvements->push(
                    $this->appliquerLigne(
                        $document,
                        $ligne,
                        $article,
                        $unite,
                        $stock,
                        $validateurId
                    )
                );
            }

            $this->historiqueService->enregistrer(
                'DOCUMENT_STOCK',
                (int) $document->id,
                'MOUVEMENTS_APPLIQUES',
                "Application de {$mouvements->count()} mouvement(s) du document {$document->reference}.",
                null,
                [
                    'reference' => $document->reference,
                    'type_document' => $document->type_document,
                    'nombre_mouvements' => $mouvements->count(),
                ],
                $validateurId
            );

            return $mouvements;
        }, 5);
    }

    private function verifierDocument(
        SupportDocumentStock $document,
        ?int $userId
    ): void {
        if ($document->statut !== 'VALIDEE') {
            $this->erreur(
                'Seuls les documents validés peuvent générer des mouvements.'
            );
        }

        if (!$document->user_validation_id) {
            $this->erreur(
                'Le document ne possède aucun validateur.'
            );
        }

        if ((int) $document->user_create_id ===
            (int) $document->user_validation_id) {
            $this->erreur(
                'Le créateur ne peut pas valider son propre document.'
            );
        }

        if (
            $userId !== null
            && (int) $userId !== (int) $document->user_validation_id
        ) {
            $this->erreur(
                'L’utilisateur exécutant le traitement doit être le validateur du document.'
            );
        }

        $typesAutorises = [
            'RECEPTION',
            'SORTIE',
            'RETOUR',
            'AJUSTEMENT_POSITIF',
            'AJUSTEMENT_NEGATIF',
        ];

        if (!in_array($document->type_document, $typesAutorises, true)) {
            $this->erreur('Type de document de stock non reconnu.');
        }
    }

    private function appliquerLigne(
        SupportDocumentStock $document,
        SupportLigneDocumentStock $ligne,
        SupportArticleStock $article,
        SupportUniteStock $unite,
        SupportStock $stock,
        int $validateurId
    ): SupportMouvementStock {
        if (SupportMouvementStock::query()
            ->where('support_ligne_document_stock_id', $ligne->id)
            ->exists()) {
            $this->erreur(
                "La ligne {$ligne->id} possède déjà un mouvement de stock."
            );
        }

        if ($stock->verrouille) {
            $this->erreur(
                "L'article {$article->reference} est verrouillé par un inventaire."
            );
        }

        $quantite = $this->normaliserQuantite(
            (string) $ligne->quantite
        );

        if (!$unite->autorise_decimales) {
            $quantiteEntiere = BigDecimal::of($quantite)
                ->toScale(0, RoundingMode::DOWN);

            if (!BigDecimal::of($quantite)->isEqualTo($quantiteEntiere)) {
                $this->erreur(
                    "L'unité {$unite->libelle} n'autorise pas les quantités décimales."
                );
            }
        }

        $quantiteAvant = (string) $stock->quantite;
        $cmpAvant = (string) $stock->cout_moyen_pondere;
        $valeurAvant = (string) $stock->valeur_stock;

        $typeDocument = $document->type_document;

        $typeMouvement = null;
        $coutUnitaire = '0.0000';
        $valeurMouvement = '0.0000';
        $quantiteApres = $quantiteAvant;
        $cmpApres = $cmpAvant;
        $valeurApres = $valeurAvant;

        switch ($typeDocument) {
            case 'RECEPTION':
                $typeMouvement = 'ENTREE';

                $coutUnitaire = $this->normaliserCout(
                    (string) $ligne->cout_unitaire
                );

                $valeurMouvement = $this->calculService
                    ->calculerValeur($quantite, $coutUnitaire);

                [$quantiteApres, $cmpApres, $valeurApres] =
                    $this->calculerEntree(
                        $quantiteAvant,
                        $valeurAvant,
                        $quantite,
                        $coutUnitaire
                    );

                break;

            case 'SORTIE':
                $typeMouvement = 'SORTIE';

                [$coutUnitaire, $valeurMouvement,
                    $quantiteApres, $cmpApres, $valeurApres] =
                    $this->calculerSortie(
                        $quantite,
                        $quantiteAvant,
                        $cmpAvant,
                        $valeurAvant
                    );

                break;

            case 'RETOUR':
                $typeMouvement = 'RETOUR';

                $coutUnitaire = $this->verifierEtObtenirCoutRetour(
                    $ligne,
                    $quantite
                );

                $valeurMouvement = $this->calculService
                    ->calculerValeur($quantite, $coutUnitaire);

                [$quantiteApres, $cmpApres, $valeurApres] =
                    $this->calculerEntree(
                        $quantiteAvant,
                        $valeurAvant,
                        $quantite,
                        $coutUnitaire
                    );

                break;

            case 'AJUSTEMENT_POSITIF':
                $typeMouvement = 'AJUSTEMENT_POSITIF';

                // Si le stock possède déjà une valeur, on reprend
                // son CMP. Sinon, on utilise le coût saisi.
                $stockDejaValorise =
                    BigDecimal::of($quantiteAvant)->isGreaterThan('0')
                    && BigDecimal::of($valeurAvant)->isGreaterThan('0');

                $coutUnitaire = $stockDejaValorise
                    ? $cmpAvant
                    : $this->normaliserCout(
                        (string) $ligne->cout_unitaire
                    );

                $valeurMouvement = $this->calculService
                    ->calculerValeur($quantite, $coutUnitaire);

                [$quantiteApres, $cmpApres, $valeurApres] =
                    $this->calculerEntree(
                        $quantiteAvant,
                        $valeurAvant,
                        $quantite,
                        $coutUnitaire
                    );

                break;

            case 'AJUSTEMENT_NEGATIF':
                $typeMouvement = 'AJUSTEMENT_NEGATIF';

                [$coutUnitaire, $valeurMouvement,
                    $quantiteApres, $cmpApres, $valeurApres] =
                    $this->calculerSortie(
                        $quantite,
                        $quantiteAvant,
                        $cmpAvant,
                        $valeurAvant
                    );

                break;
        }

        // La ligne documentaire conserve la valorisation réellement
        // appliquée au stock.
        $ligne->forceFill([
            'cout_unitaire' => $coutUnitaire,
            'valeur_ligne' => $valeurMouvement,
        ])->save();

        $stock->forceFill([
            'quantite' => $quantiteApres,
            'cout_moyen_pondere' => $cmpApres,
            'valeur_stock' => $valeurApres,
        ])->save();

        $mouvement = SupportMouvementStock::query()->create([
            'support_document_stock_id' => $document->id,
            'support_ligne_document_stock_id' => $ligne->id,
            'support_article_stock_id' => $article->id,
            'type_mouvement' => $typeMouvement,
            'quantite' => $quantite,
            'cout_unitaire' => $coutUnitaire,
            'valeur_mouvement' => $valeurMouvement,
            'quantite_avant' => $quantiteAvant,
            'quantite_apres' => $quantiteApres,
            'user_create_id' => $validateurId,
            'date_mouvement' => now(),
        ]);

        $this->historiqueService->enregistrer(
            'MOUVEMENT_STOCK',
            (int) $mouvement->id,
            'CREATION',
            "Mouvement {$typeMouvement} de {$quantite} pour l'article {$article->reference}.",
            [
                'quantite' => $quantiteAvant,
                'cmp' => $cmpAvant,
                'valeur_stock' => $valeurAvant,
            ],
            [
                'quantite' => $quantiteApres,
                'cmp' => $cmpApres,
                'valeur_stock' => $valeurApres,
                'valeur_mouvement' => $valeurMouvement,
            ],
            $validateurId
        );

        return $mouvement;
    }

    private function calculerEntree(
        string $quantiteAvant,
        string $valeurAvant,
        string $quantiteEntree,
        string $coutUnitaire
    ): array {
        $valeurEntree = BigDecimal::of(
            $this->calculService->calculerValeur(
                $quantiteEntree,
                $coutUnitaire
            )
        );

        $quantiteApres = BigDecimal::of($quantiteAvant)
            ->plus($quantiteEntree)
            ->toScale(self::ECHELLE_QUANTITE, RoundingMode::HALF_UP);

        $valeurApres = BigDecimal::of($valeurAvant)
            ->plus($valeurEntree)
            ->toScale(self::ECHELLE_VALEUR, RoundingMode::HALF_UP);

        $cmpApres = $this->calculService->calculerCmpApresEntree(
            $quantiteAvant,
            $valeurAvant,
            $quantiteEntree,
            $coutUnitaire
        );

        return [
            (string) $quantiteApres,
            $cmpApres,
            (string) $valeurApres,
        ];
    }

    private function calculerSortie(
        string $quantiteSortie,
        string $quantiteAvant,
        string $cmpAvant,
        string $valeurAvant
    ): array {
        $valeurMouvement = $this->calculService
            ->calculerValeurSortie(
                $quantiteSortie,
                $quantiteAvant,
                $valeurAvant,
                $cmpAvant
            );

        $quantiteApres = $this->calculService
            ->calculerQuantiteApresSortie(
                $quantiteAvant,
                $quantiteSortie
            );

        $valeurApres = BigDecimal::of($valeurAvant)
            ->minus($valeurMouvement);

        if ($valeurApres->isLessThan('0')) {
            $this->erreur(
                'La valorisation de la sortie rendrait la valeur du stock négative.'
            );
        }

        $stockVide = BigDecimal::of($quantiteApres)->isZero();

        if ($stockVide) {
            $cmpApres = '0.0000';
            $valeurApres = BigDecimal::zero();
        } else {
            $cmpApres = $cmpAvant;
        }

        return [
            $cmpAvant,
            $valeurMouvement,
            $quantiteApres,
            $cmpApres,
            (string) $valeurApres->toScale(
                self::ECHELLE_VALEUR,
                RoundingMode::HALF_UP
            ),
        ];
    }

    private function verifierEtObtenirCoutRetour(
        SupportLigneDocumentStock $ligne,
        string $quantiteRetour
    ): string {
        if (!$ligne->support_ligne_origine_id) {
            $this->erreur(
                'Un retour doit obligatoirement être rattaché à une ligne de sortie.'
            );
        }

        $origine = SupportLigneDocumentStock::query()
            ->whereKey($ligne->support_ligne_origine_id)
            ->lockForUpdate()
            ->first();

        if (!$origine) {
            $this->erreur('La ligne de sortie d’origine est introuvable.');
        }

        if ((int) $origine->support_article_stock_id !==
            (int) $ligne->support_article_stock_id) {
            $this->erreur(
                'Le retour doit concerner le même article que la sortie initiale.'
            );
        }

        $documentOrigine = SupportDocumentStock::query()
            ->whereKey($origine->support_document_stock_id)
            ->first();

        if (
            !$documentOrigine
            || $documentOrigine->type_document !== 'SORTIE'
            || $documentOrigine->statut !== 'VALIDEE'
        ) {
            $this->erreur(
                'La ligne d’origine doit appartenir à une sortie validée.'
            );
        }

        $mouvementOrigine = SupportMouvementStock::query()
            ->where('support_ligne_document_stock_id', $origine->id)
            ->where('type_mouvement', 'SORTIE')
            ->lockForUpdate()
            ->first();

        if (!$mouvementOrigine) {
            $this->erreur(
                'Le mouvement de sortie initial est introuvable.'
            );
        }

        // Les retours déjà comptabilisés sont identifiés par leurs
        // mouvements, et non par le simple statut de leurs documents.
        $quantiteDejaRetournee = SupportMouvementStock::query()
            ->join(
                'support_lignes_documents_stocks as lignes',
                'lignes.id',
                '=',
                'support_mouvements_stocks.support_ligne_document_stock_id'
            )
            ->where(
                'lignes.support_ligne_origine_id',
                $origine->id
            )
            ->where(
                'support_mouvements_stocks.type_mouvement',
                'RETOUR'
            )
            ->sum('support_mouvements_stocks.quantite');

        $totalRetour = BigDecimal::of(
            (string) $quantiteDejaRetournee
        )->plus($quantiteRetour);

        if ($totalRetour->isGreaterThan((string) $mouvementOrigine->quantite)) {
            $this->erreur(
                'Le cumul des retours dépasse la quantité initialement sortie.'
            );
        }

        return $this->normaliserCout(
            (string) $mouvementOrigine->cout_unitaire
        );
    }

    private function normaliserQuantite(string $valeur): string
    {
        try {
            $quantite = BigDecimal::of($valeur)
                ->toScale(
                    self::ECHELLE_QUANTITE,
                    RoundingMode::UNNECESSARY
                );

            if (!$quantite->isGreaterThan('0')) {
                $this->erreur(
                    'La quantité doit être strictement positive.'
                );
            }

            return (string) $quantite;
        } catch (Throwable $exception) {
            if ($exception instanceof ValidationException) {
                throw $exception;
            }

            $this->erreur(
                'La quantité doit être positive et comporter au maximum trois décimales.'
            );
        }
    }

    private function normaliserCout(string $valeur): string
    {
        try {
            $cout = BigDecimal::of($valeur)
                ->toScale(
                    self::ECHELLE_VALEUR,
                    RoundingMode::UNNECESSARY
                );

            if ($cout->isLessThan('0')) {
                $this->erreur(
                    'Le coût unitaire ne peut pas être négatif.'
                );
            }

            return (string) $cout;
        } catch (Throwable $exception) {
            if ($exception instanceof ValidationException) {
                throw $exception;
            }

            $this->erreur(
                'Le coût unitaire doit être positif ou nul et comporter au maximum quatre décimales.'
            );
        }
    }

    private function erreur(string $message): never
    {
        throw ValidationException::withMessages([
            'stock' => $message,
        ]);
    }
}
