<?php

namespace App\Services\Support;

use App\Models\Support\SupportArticleStock;
use App\Models\Support\SupportDocumentStock;
use App\Models\Support\SupportInventaireLigne;
use App\Models\Support\SupportInventaireStock;
use App\Models\Support\SupportMouvementStock;
use App\Models\Support\SupportStock;
use Brick\Math\BigDecimal;
use Brick\Math\RoundingMode;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use RuntimeException;
use Throwable;

class SupportInventaireStockService
{
    public function __construct(
        private SupportStockReferenceService $referenceService,
        private SupportHistoriqueStockService $historiqueService,
        private SupportDocumentStockService $documentService
    ) {
    }

    /**
     * Création d'un inventaire en brouillon.
     */
    public function creer(
        array $donnees,
        ?int $userId = null
    ): SupportInventaireStock {
        $userId = $this->utilisateur($userId);

        Validator::make($donnees, [
            'libelle' => ['required', 'string', 'max:200'],
            'description' => ['nullable', 'string'],
            'date_debut' => ['required', 'date'],
            'observations' => ['nullable', 'string'],
        ])->validate();

        return DB::transaction(function () use ($donnees, $userId) {
            $inventaire = SupportInventaireStock::query()->create([
                'reference' => $this->referenceService->generer('INVENTAIRE'),
                'libelle' => trim($donnees['libelle']),
                'description' => $donnees['description'] ?? null,
                'date_debut' => $donnees['date_debut'],
                'statut' => 'BROUILLON',
                'user_create_id' => $userId,
                'observations' => $donnees['observations'] ?? null,
            ]);

            $this->historiqueService->enregistrer(
                'INVENTAIRE_STOCK',
                (int) $inventaire->id,
                'CREATION',
                "Création de l'inventaire {$inventaire->reference}.",
                null,
                [
                    'reference' => $inventaire->reference,
                    'statut' => 'BROUILLON',
                    'libelle' => $inventaire->libelle,
                ],
                $userId
            );

            return $inventaire->fresh();
        }, 5);
    }

    /**
     * Démarrage : sélection explicite des articles,
     * photographie des quantités théoriques et verrouillage.
     */
    public function demarrer(
        int|SupportInventaireStock $inventaire,
        array $articleIds,
        ?int $userId = null
    ): SupportInventaireStock {
        $userId = $this->utilisateur($userId);
        $inventaireId = $this->identifier($inventaire);

        $articleIds = array_values(array_unique(array_map(
            'intval',
            $articleIds
        )));

        if (empty($articleIds) || in_array(0, $articleIds, true)) {
            $this->erreur(
                'Sélectionnez au moins un article pour démarrer l’inventaire.'
            );
        }

        return DB::transaction(function () use (
            $inventaireId,
            $articleIds,
            $userId
        ) {
            $inventaire = SupportInventaireStock::query()
                ->whereKey($inventaireId)
                ->lockForUpdate()
                ->firstOrFail();

            $this->verifierCreateur($inventaire, $userId);

            if ($inventaire->statut !== 'BROUILLON') {
                $this->erreur(
                    'Seul un inventaire en brouillon peut être démarré.'
                );
            }

            if ($inventaire->lignes()->exists()) {
                $this->erreur(
                    'Cet inventaire contient déjà des lignes.'
                );
            }

            $articles = SupportArticleStock::query()
                ->whereIn('id', $articleIds)
                ->where('actif', true)
                ->orderBy('id')
                ->get();

            if ($articles->count() !== count($articleIds)) {
                $this->erreur(
                    'Un ou plusieurs articles sont inexistants ou inactifs.'
                );
            }

            foreach ($articles as $article) {
                SupportStock::query()->firstOrCreate(
                    ['support_article_stock_id' => $article->id],
                    [
                        'quantite' => '0.000',
                        'cout_moyen_pondere' => '0.0000',
                        'valeur_stock' => '0.0000',
                        'verrouille' => false,
                        'support_inventaire_id' => null,
                    ]
                );
            }

            $stocks = SupportStock::query()
                ->whereIn('support_article_stock_id', $articleIds)
                ->orderBy('support_article_stock_id')
                ->lockForUpdate()
                ->get()
                ->keyBy('support_article_stock_id');

            foreach ($articles as $article) {
                $stock = $stocks->get($article->id);

                if (!$stock) {
                    $this->erreur(
                        "Le stock de l'article {$article->reference} est introuvable."
                    );
                }

                if (
                    $stock->verrouille
                    || $stock->support_inventaire_id !== null
                ) {
                    $this->erreur(
                        "L'article {$article->reference} est déjà verrouillé par un inventaire."
                    );
                }

                SupportInventaireLigne::query()->create([
                    'support_inventaire_id' => $inventaire->id,
                    'support_article_stock_id' => $article->id,
                    'quantite_theorique' => $stock->quantite,
                    'quantite_comptee' => null,
                    'ecart' => null,
                ]);

                $stock->verrouille = true;
                $stock->support_inventaire_id = $inventaire->id;
                $stock->save();
            }

            $inventaire->statut = 'EN_COURS';
            $inventaire->save();

            $this->historiqueService->enregistrer(
                'INVENTAIRE_STOCK',
                (int) $inventaire->id,
                'DEMARRAGE',
                "Démarrage de l'inventaire {$inventaire->reference} : {$articles->count()} article(s).",
                ['statut' => 'BROUILLON'],
                [
                    'statut' => 'EN_COURS',
                    'nombre_articles' => $articles->count(),
                ],
                $userId
            );

            return $inventaire->fresh(['lignes.article']);
        }, 5);
    }

    /**
     * Enregistrement d'un comptage physique.
     * Autorisé pendant EN_COURS et après rejet, pour correction.
     */
    public function enregistrerComptage(
        int|SupportInventaireStock $inventaire,
        int $articleId,
        string|int|float $quantiteComptee,
        ?string $observations = null,
        ?int $userId = null
    ): SupportInventaireLigne {
        $userId = $this->utilisateur($userId);
        $inventaireId = $this->identifier($inventaire);

        $quantite = $this->normaliserQuantite(
            (string) $quantiteComptee
        );

        return DB::transaction(function () use (
            $inventaireId,
            $articleId,
            $quantite,
            $observations,
            $userId
        ) {
            $inventaire = SupportInventaireStock::query()
                ->whereKey($inventaireId)
                ->lockForUpdate()
                ->firstOrFail();

            if (!in_array(
                $inventaire->statut,
                ['EN_COURS', 'REJETEE'],
                true
            )) {
                $this->erreur(
                    'Le comptage est autorisé uniquement sur un inventaire en cours ou rejeté.'
                );
            }

            $ligne = SupportInventaireLigne::query()
                ->where('support_inventaire_id', $inventaire->id)
                ->where('support_article_stock_id', $articleId)
                ->lockForUpdate()
                ->first();

            if (!$ligne) {
                $this->erreur(
                    'Cet article ne fait pas partie de cet inventaire.'
                );
            }

            $ancienComptage = $ligne->quantite_comptee;
            $ancienEcart = $ligne->ecart;

            $ecart = BigDecimal::of($quantite)
                ->minus((string) $ligne->quantite_theorique)
                ->toScale(3, RoundingMode::HALF_UP);

            $ligne->quantite_comptee = $quantite;
            $ligne->ecart = (string) $ecart;
            $ligne->user_comptage_id = $userId;
            $ligne->date_comptage = now();
            $ligne->observations = $observations;
            $ligne->save();

            $this->historiqueService->enregistrer(
                'INVENTAIRE_LIGNE',
                (int) $ligne->id,
                'COMPTAGE',
                "Comptage de l'article {$ligne->article->reference}.",
                [
                    'quantite_comptee' => $ancienComptage,
                    'ecart' => $ancienEcart,
                ],
                [
                    'quantite_comptee' => $ligne->quantite_comptee,
                    'ecart' => $ligne->ecart,
                    'user_comptage_id' => $userId,
                ],
                $userId
            );

            return $ligne->fresh(['article', 'compteur']);
        }, 5);
    }

    /**
     * Soumission de l'inventaire pour validation.
     */
    public function soumettre(
        int|SupportInventaireStock $inventaire,
        ?int $userId = null
    ): SupportInventaireStock {
        $userId = $this->utilisateur($userId);
        $inventaireId = $this->identifier($inventaire);

        return DB::transaction(function () use (
            $inventaireId,
            $userId
        ) {
            $inventaire = SupportInventaireStock::query()
                ->whereKey($inventaireId)
                ->lockForUpdate()
                ->firstOrFail();

            $this->verifierCreateur($inventaire, $userId);

            if (!in_array(
                $inventaire->statut,
                ['EN_COURS', 'REJETEE'],
                true
            )) {
                $this->erreur(
                    'Seuls les inventaires en cours ou rejetés peuvent être soumis.'
                );
            }

            $lignes = $inventaire->lignes()
                ->lockForUpdate()
                ->get();

            if ($lignes->isEmpty()) {
                $this->erreur(
                    'Un inventaire sans article ne peut pas être soumis.'
                );
            }

            $nonComptes = $lignes->filter(
                fn ($ligne) => $ligne->quantite_comptee === null
            );

            if ($nonComptes->isNotEmpty()) {
                $references = $nonComptes
                    ->load('article')
                    ->pluck('article.reference')
                    ->filter()
                    ->implode(', ');

                $this->erreur(
                    "Comptage manquant pour : {$references}."
                );
            }

            $ancienStatut = $inventaire->statut;
            $ancienMotif = $inventaire->motif_rejet;

            $inventaire->statut = 'EN_ATTENTE_VALIDATION';
            $inventaire->date_soumission = now();
            $inventaire->user_validation_id = null;
            $inventaire->date_validation = null;
            $inventaire->motif_rejet = null;
            $inventaire->save();

            $this->historiqueService->enregistrer(
                'INVENTAIRE_STOCK',
                (int) $inventaire->id,
                'SOUMISSION',
                "Soumission de l'inventaire {$inventaire->reference}.",
                [
                    'statut' => $ancienStatut,
                    'motif_rejet' => $ancienMotif,
                ],
                [
                    'statut' => $inventaire->statut,
                    'date_soumission' => $inventaire->date_soumission,
                ],
                $userId
            );

            return $inventaire->fresh(['lignes.article']);
        }, 5);
    }

    /**
     * Rejet motivé. Les stocks restent verrouillés
     * pour permettre la correction et la resoumission.
     */
    public function rejeter(
        int|SupportInventaireStock $inventaire,
        string $motif,
        ?int $userId = null
    ): SupportInventaireStock {
        $userId = $this->utilisateur($userId);
        $inventaireId = $this->identifier($inventaire);
        $motif = trim($motif);

        if ($motif === '' || mb_strlen($motif) > 5000) {
            $this->erreur(
                'Le motif du rejet est obligatoire et limité à 5 000 caractères.'
            );
        }

        return DB::transaction(function () use (
            $inventaireId,
            $motif,
            $userId
        ) {
            $inventaire = SupportInventaireStock::query()
                ->whereKey($inventaireId)
                ->lockForUpdate()
                ->firstOrFail();

            if ($inventaire->statut !== 'EN_ATTENTE_VALIDATION') {
                $this->erreur(
                    'Seul un inventaire en attente peut être rejeté.'
                );
            }

            $this->verifierValidateur($inventaire, $userId);

            $inventaire->statut = 'REJETEE';
            $inventaire->motif_rejet = $motif;
            $inventaire->user_validation_id = null;
            $inventaire->date_validation = null;
            $inventaire->save();

            $this->historiqueService->enregistrer(
                'INVENTAIRE_STOCK',
                (int) $inventaire->id,
                'REJET',
                "Rejet de l'inventaire {$inventaire->reference}.",
                ['statut' => 'EN_ATTENTE_VALIDATION'],
                [
                    'statut' => 'REJETEE',
                    'motif_rejet' => $motif,
                ],
                $userId
            );

            return $inventaire->fresh();
        }, 5);
    }

    /**
     * Validation définitive et régularisation des stocks.
     *
     * Tous les traitements sont dans une transaction globale.
     * Les verrous SQL restent détenus jusqu'au commit.
     */
    public function valider(
        int|SupportInventaireStock $inventaire,
        ?int $userId = null
    ): SupportInventaireStock {
        $userId = $this->utilisateur($userId);
        $inventaireId = $this->identifier($inventaire);

        return DB::transaction(function () use (
            $inventaireId,
            $userId
        ) {
            $inventaire = SupportInventaireStock::query()
                ->whereKey($inventaireId)
                ->lockForUpdate()
                ->firstOrFail();

            if ($inventaire->statut !== 'EN_ATTENTE_VALIDATION') {
                $this->erreur(
                    'Seul un inventaire en attente peut être validé.'
                );
            }

            $this->verifierValidateur($inventaire, $userId);

            $lignes = $inventaire->lignes()
                ->with('article')
                ->orderBy('support_article_stock_id')
                ->lockForUpdate()
                ->get();

            if ($lignes->isEmpty()) {
                $this->erreur(
                    'Impossible de valider un inventaire sans ligne.'
                );
            }

            foreach ($lignes as $ligne) {
                if ($ligne->quantite_comptee === null) {
                    $this->erreur(
                        "Le comptage de l'article {$ligne->article->reference} est manquant."
                    );
                }
            }

            $articleIds = $lignes
                ->pluck('support_article_stock_id')
                ->map(fn ($id) => (int) $id)
                ->all();

            $stocks = SupportStock::query()
                ->whereIn('support_article_stock_id', $articleIds)
                ->orderBy('support_article_stock_id')
                ->lockForUpdate()
                ->get()
                ->keyBy('support_article_stock_id');

            // Vérification stricte de la propriété des verrous.
            foreach ($lignes as $ligne) {
                $article = $ligne->article;
                $stock = $stocks->get($article->id);

                if (
                    !$stock
                    || !$stock->verrouille
                    || (int) $stock->support_inventaire_id !==
                        (int) $inventaire->id
                ) {
                    $this->erreur(
                        "Le verrouillage du stock de {$article->reference} n'appartient pas à cet inventaire."
                    );
                }

                if (
                    BigDecimal::of((string) $stock->quantite)
                        ->compareTo((string) $ligne->quantite_theorique) !== 0
                ) {
                    $this->erreur(
                        "Le stock théorique de {$article->reference} a changé pendant l'inventaire."
                    );
                }
            }

            $ajustementsPositifs = [];
            $ajustementsNegatifs = [];

            foreach ($lignes as $ligne) {
                $ecart = BigDecimal::of((string) $ligne->quantite_comptee)
                    ->minus((string) $ligne->quantite_theorique)
                    ->toScale(3, RoundingMode::HALF_UP);

                if ($ecart->isGreaterThan('0')) {
                    $stock = $stocks->get(
                        $ligne->support_article_stock_id
                    );

                    $cout = $this->determinerCoutAjustementPositif(
                        $ligne,
                        $stock
                    );

                    $ajustementsPositifs[] = [
                        'article_id' => (int) $ligne->support_article_stock_id,
                        'quantite' => (string) $ecart,
                        'cout_unitaire' => $cout,
                    ];
                } elseif ($ecart->isLessThan('0')) {
                    $ajustementsNegatifs[] = [
                        'article_id' => (int) $ligne->support_article_stock_id,
                        'quantite' => (string) $ecart->abs(),
                    ];
                }
            }

            /*
             * Déverrouillage temporaire des seuls stocks de cet inventaire.
             * Les lignes restent verrouillées en base jusqu'au commit.
             */
            foreach ($stocks as $stock) {
                if (
                    !$stock->verrouille
                    || (int) $stock->support_inventaire_id !==
                        (int) $inventaire->id
                ) {
                    $this->erreur(
                        'Un stock ne peut pas être déverrouillé par cet inventaire.'
                    );
                }

                $stock->verrouille = false;
                $stock->support_inventaire_id = null;
                $stock->save();
            }

            // Régularisation positive par le circuit documentaire normal.
            if (!empty($ajustementsPositifs)) {
                $document = $this->creerDocumentAjustement(
                    $inventaire,
                    'AJUSTEMENT_POSITIF',
                    $ajustementsPositifs,
                    $userId
                );

                $this->documentService->soumettre(
                    $document,
                    (int) $inventaire->user_create_id
                );

                $this->documentService->valider(
                    $document,
                    $userId
                );
            }

            // Régularisation négative par le circuit documentaire normal.
            if (!empty($ajustementsNegatifs)) {
                $document = $this->creerDocumentAjustement(
                    $inventaire,
                    'AJUSTEMENT_NEGATIF',
                    $ajustementsNegatifs,
                    $userId
                );

                $this->documentService->soumettre(
                    $document,
                    (int) $inventaire->user_create_id
                );

                $this->documentService->valider(
                    $document,
                    $userId
                );
            }

            // Contrôle final avant clôture.
            foreach ($lignes as $ligne) {
                $stock = SupportStock::query()
                    ->where('support_article_stock_id', $ligne->support_article_stock_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if (
                    BigDecimal::of((string) $stock->quantite)
                        ->compareTo((string) $ligne->quantite_comptee) !== 0
                ) {
                    $this->erreur(
                        "La quantité finale de {$ligne->article->reference} ne correspond pas au comptage."
                    );
                }

                if (
                    $stock->verrouille
                    || $stock->support_inventaire_id !== null
                ) {
                    $this->erreur(
                        "Le stock de {$ligne->article->reference} est encore verrouillé."
                    );
                }
            }

            $inventaire->statut = 'VALIDEE';
            $inventaire->user_validation_id = $userId;
            $inventaire->date_validation = now();
            $inventaire->date_fin = now();
            $inventaire->motif_rejet = null;
            $inventaire->save();

            $this->historiqueService->enregistrer(
                'INVENTAIRE_STOCK',
                (int) $inventaire->id,
                'VALIDATION',
                "Validation de l'inventaire {$inventaire->reference}.",
                ['statut' => 'EN_ATTENTE_VALIDATION'],
                [
                    'statut' => 'VALIDEE',
                    'user_validation_id' => $userId,
                    'date_validation' => $inventaire->date_validation,
                    'ajustements_positifs' => count($ajustementsPositifs),
                    'ajustements_negatifs' => count($ajustementsNegatifs),
                ],
                $userId
            );

            return $inventaire->fresh([
                'lignes.article',
                'createur',
                'validateur',
            ]);
        }, 5);
    }

    /**
     * Création d'un document d'ajustement avec ses lignes.
     */
    private function creerDocumentAjustement(
        SupportInventaireStock $inventaire,
        string $type,
        array $lignes,
        int $userId
    ): SupportDocumentStock {
        $document = $this->documentService->creerDocument([
            'type_document' => $type,
            'date_document' => now()->toDateString(),
            'motif' => "Régularisation de l'inventaire {$inventaire->reference}",
            'observations' => "Ajustement automatique issu de l'inventaire {$inventaire->reference}.",
        ], (int) $inventaire->user_create_id);

        foreach ($lignes as $ligne) {
            $donnees = [
                'support_article_stock_id' => $ligne['article_id'],
                'quantite' => $ligne['quantite'],
                'observations' => "Écart constaté lors de l'inventaire {$inventaire->reference}.",
            ];

            if ($type === 'AJUSTEMENT_POSITIF') {
                $donnees['cout_unitaire'] = $ligne['cout_unitaire'];
            }

            $this->documentService->ajouterLigne(
                $document,
                $donnees,
                (int) $inventaire->user_create_id
            );
        }

        return $document->fresh(['lignes']);
    }

    /**
     * CMP courant si positif, sinon dernier coût d'entrée positif.
     */
    private function determinerCoutAjustementPositif(
        SupportInventaireLigne $ligne,
        SupportStock $stock
    ): string {
        $cmp = BigDecimal::of((string) $stock->cout_moyen_pondere);

        if ($cmp->isGreaterThan('0')) {
            return (string) $cmp->toScale(4, RoundingMode::HALF_UP);
        }

        $dernierCout = SupportMouvementStock::query()
            ->where('support_article_stock_id', $ligne->support_article_stock_id)
            ->where('type_mouvement', 'ENTREE')
            ->where('cout_unitaire', '>', 0)
            ->orderByDesc('date_mouvement')
            ->orderByDesc('id')
            ->value('cout_unitaire');

        if ($dernierCout === null) {
            $reference = $ligne->article->reference;

            $this->erreur(
                "Impossible de valoriser l'écart positif de {$reference} : aucun CMP ni coût d'entrée positif dans l'historique."
            );
        }

        return (string) BigDecimal::of((string) $dernierCout)
            ->toScale(4, RoundingMode::HALF_UP);
    }

    private function normaliserQuantite(string $valeur): string
    {
        try {
            $quantite = BigDecimal::of($valeur)
                ->toScale(3, RoundingMode::UNNECESSARY);

            if ($quantite->isLessThan('0')) {
                $this->erreur(
                    'La quantité comptée ne peut pas être négative.'
                );
            }

            return (string) $quantite;
        } catch (Throwable $exception) {
            if ($exception instanceof ValidationException) {
                throw $exception;
            }

            $this->erreur(
                'La quantité doit être positive ou nulle et comporter au maximum trois décimales.'
            );
        }
    }

    private function verifierCreateur(
        SupportInventaireStock $inventaire,
        int $userId
    ): void {
        if ((int) $inventaire->user_create_id !== $userId) {
            $this->erreur(
                "Seul le créateur de l'inventaire peut effectuer cette opération."
            );
        }
    }

    private function verifierValidateur(
        SupportInventaireStock $inventaire,
        int $userId
    ): void {
        if ((int) $inventaire->user_create_id === $userId) {
            $this->erreur(
                "Le créateur ne peut pas valider ou rejeter son propre inventaire."
            );
        }
    }

    private function utilisateur(?int $userId): int
    {
        $userId ??= Auth::id();

        if (!$userId || $userId < 1) {
            $this->erreur(
                'Un utilisateur authentifié est obligatoire.'
            );
        }

        return (int) $userId;
    }

    private function identifier(
        int|SupportInventaireStock $inventaire
    ): int {
        return $inventaire instanceof SupportInventaireStock
            ? (int) $inventaire->id
            : $inventaire;
    }

    private function erreur(string $message): never
    {
        throw ValidationException::withMessages([
            'inventaire' => $message,
        ]);
    }
}
