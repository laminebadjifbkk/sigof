<?php

namespace App\Services\Support;

use App\Models\Support\SupportArticleStock;
use App\Models\Support\SupportDocumentStock;
use App\Models\Support\SupportLigneDocumentStock;
use Brick\Math\BigDecimal;
use Brick\Math\RoundingMode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class SupportDocumentStockService
{
    private const TYPES = [
        'RECEPTION',
        'SORTIE',
        'RETOUR',
        'AJUSTEMENT_POSITIF',
        'AJUSTEMENT_NEGATIF',
    ];

    public function __construct(
        private readonly SupportStockReferenceService $referenceService,
        private readonly SupportHistoriqueStockService $historiqueService,
        private readonly SupportStockMouvementService $mouvementService
    ) {
    }

    /**
     * Création d'un document de stock.
     */
    public function creerDocument(
        array $donnees,
        ?int $userId = null
    ): SupportDocumentStock {
        $userId ??= (int) Auth::id();

        return DB::transaction(function () use ($donnees, $userId) {
            $type = strtoupper(trim($donnees['type_document'] ?? ''));

            if (!in_array($type, self::TYPES, true)) {
                $this->erreur('Type de document de stock invalide.');
            }

            $this->validerEntete($donnees, $type);

            $document = SupportDocumentStock::create([
                'reference' => $this->referenceService->generer($type),
                'type_document' => $type,
                'statut' => 'BROUILLON',
                'support_fournisseur_stock_id' =>
                    $type === 'RECEPTION'
                        ? ($donnees['support_fournisseur_stock_id'] ?? null)
                        : null,
                'employee_id' => $type === 'SORTIE'
                    ? ($donnees['employee_id'] ?? null)
                    : null,
                'direction_id' => $type === 'SORTIE'
                    ? ($donnees['direction_id'] ?? null)
                    : null,
                'antenne_id' => $type === 'SORTIE'
                    ? ($donnees['antenne_id'] ?? null)
                    : null,
                'support_ticket_id' => $type === 'SORTIE'
                    ? ($donnees['support_ticket_id'] ?? null)
                    : null,
                'date_document' => $donnees['date_document'],
                'user_create_id' => $userId,
                'motif' => $donnees['motif'] ?? null,
                'observations' => $donnees['observations'] ?? null,
            ]);

            $this->historiqueService->enregistrer(
                'DOCUMENT_STOCK',
                (int) $document->id,
                'CREATION',
                "Création du document {$document->reference}.",
                null,
                $document->only([
                    'reference',
                    'type_document',
                    'statut',
                    'date_document',
                ]),
                $userId
            );

            return $document->fresh();
        }, 5);
    }

    /**
     * Modification de l'entête d'un document.
     * Autorisée au créateur sur BROUILLON ou REJETEE.
     */
    public function modifierDocument(
        int|SupportDocumentStock $document,
        array $donnees,
        ?int $userId = null
    ): SupportDocumentStock {
        $userId ??= (int) Auth::id();
        $documentId = $this->identifier($document);

        return DB::transaction(function () use (
            $documentId,
            $donnees,
            $userId
        ) {
            $document = $this->verrouillerDocument($documentId);

            $this->verifierModification($document, $userId);

            $this->validerEntete($donnees, $document->type_document);

            $anciennesValeurs = $document->only([
                'support_fournisseur_stock_id',
                'employee_id',
                'direction_id',
                'antenne_id',
                'support_ticket_id',
                'date_document',
                'motif',
                'observations',
            ]);

            $document->fill([
                'support_fournisseur_stock_id' =>
                    $document->type_document === 'RECEPTION'
                        ? ($donnees['support_fournisseur_stock_id'] ?? null)
                        : null,
                'employee_id' => $document->type_document === 'SORTIE'
                    ? ($donnees['employee_id'] ?? null)
                    : null,
                'direction_id' => $document->type_document === 'SORTIE'
                    ? ($donnees['direction_id'] ?? null)
                    : null,
                'antenne_id' => $document->type_document === 'SORTIE'
                    ? ($donnees['antenne_id'] ?? null)
                    : null,
                'support_ticket_id' => $document->type_document === 'SORTIE'
                    ? ($donnees['support_ticket_id'] ?? null)
                    : null,
                'date_document' => $donnees['date_document'],
                'motif' => $donnees['motif'] ?? null,
                'observations' => $donnees['observations'] ?? null,
            ]);

            $document->save();

            $this->historiqueService->enregistrer(
                'DOCUMENT_STOCK',
                (int) $document->id,
                'MODIFICATION',
                "Modification du document {$document->reference}.",
                $anciennesValeurs,
                $document->only(array_keys($anciennesValeurs)),
                $userId
            );

            return $document->fresh();
        }, 5);
    }

    /**
     * Ajout d'une ligne à un document modifiable.
     */
    public function ajouterLigne(
        int|SupportDocumentStock $document,
        array $donnees,
        ?int $userId = null
    ): SupportLigneDocumentStock {
        $userId ??= (int) Auth::id();
        $documentId = $this->identifier($document);

        return DB::transaction(function () use (
            $documentId,
            $donnees,
            $userId
        ) {
            $document = $this->verrouillerDocument($documentId);

            $this->verifierModification($document, $userId);

            $ligne = $this->creerOuValiderLigne(
                $document,
                $donnees
            );

            $this->historiqueService->enregistrer(
                'DOCUMENT_STOCK',
                (int) $document->id,
                'AJOUT_LIGNE',
                "Ajout d'une ligne au document {$document->reference}.",
                null,
                [
                    'ligne_id' => $ligne->id,
                    'article_id' => $ligne->support_article_stock_id,
                    'quantite' => $ligne->quantite,
                ],
                $userId
            );

            return $ligne;
        }, 5);
    }

    /**
     * Modification d'une ligne.
     */
    public function modifierLigne(
        int|SupportDocumentStock $document,
        int $ligneId,
        array $donnees,
        ?int $userId = null
    ): SupportLigneDocumentStock {
        $userId ??= (int) Auth::id();
        $documentId = $this->identifier($document);

        return DB::transaction(function () use (
            $documentId,
            $ligneId,
            $donnees,
            $userId
        ) {
            $document = $this->verrouillerDocument($documentId);

            $this->verifierModification($document, $userId);

            $ligne = SupportLigneDocumentStock::query()
                ->whereKey($ligneId)
                ->where('support_document_stock_id', $document->id)
                ->lockForUpdate()
                ->firstOrFail();

            $anciennesValeurs = $ligne->only([
                'support_article_stock_id',
                'support_ligne_origine_id',
                'quantite',
                'cout_unitaire',
                'observations',
            ]);

            $this->remplirLigne($ligne, $document, $donnees);
            $ligne->save();

            $this->historiqueService->enregistrer(
                'DOCUMENT_STOCK',
                (int) $document->id,
                'MODIFICATION_LIGNE',
                "Modification de la ligne {$ligne->id}.",
                $anciennesValeurs,
                $ligne->only(array_keys($anciennesValeurs)),
                $userId
            );

            return $ligne->fresh();
        }, 5);
    }

    /**
     * Suppression d'une ligne non encore mouvementée.
     */
    public function supprimerLigne(
        int|SupportDocumentStock $document,
        int $ligneId,
        ?int $userId = null
    ): bool {
        $userId ??= (int) Auth::id();
        $documentId = $this->identifier($document);

        return DB::transaction(function () use (
            $documentId,
            $ligneId,
            $userId
        ) {
            $document = $this->verrouillerDocument($documentId);

            $this->verifierModification($document, $userId);

            $ligne = SupportLigneDocumentStock::query()
                ->whereKey($ligneId)
                ->where('support_document_stock_id', $document->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($ligne->mouvements()->exists()) {
                $this->erreur(
                    'Une ligne ayant déjà généré un mouvement ne peut pas être supprimée.'
                );
            }

            $anciennesValeurs = $ligne->only([
                'support_article_stock_id',
                'support_ligne_origine_id',
                'quantite',
                'cout_unitaire',
                'observations',
            ]);

            $ligne->delete();

            $this->historiqueService->enregistrer(
                'DOCUMENT_STOCK',
                (int) $document->id,
                'SUPPRESSION_LIGNE',
                "Suppression de la ligne {$ligneId}.",
                $anciennesValeurs,
                null,
                $userId
            );

            return true;
        }, 5);
    }

    /**
     * Première soumission ou nouvelle soumission après rejet.
     */
    public function soumettre(
        int|SupportDocumentStock $document,
        ?int $userId = null
    ): SupportDocumentStock {
        $userId ??= (int) Auth::id();
        $documentId = $this->identifier($document);

        return DB::transaction(function () use (
            $documentId,
            $userId
        ) {
            $document = $this->verrouillerDocument($documentId);

            $this->verifierCreateur($document, $userId);

            if (!in_array(
                $document->statut,
                ['BROUILLON', 'REJETEE'],
                true
            )) {
                $this->erreur(
                    'Seuls les brouillons et documents rejetés peuvent être soumis.'
                );
            }

            if (!$document->lignes()->exists()) {
                $this->erreur(
                    'Ajoutez au moins une ligne avant de soumettre le document.'
                );
            }

            $ancienStatut = $document->statut;
            $ancienMotifRejet = $document->motif_rejet;

            $document->statut = 'EN_ATTENTE_VALIDATION';
            $document->date_soumission = now();
            $document->date_validation = null;
            $document->user_validation_id = null;
            $document->motif_rejet = null;
            $document->save();

            $this->historiqueService->enregistrer(
                'DOCUMENT_STOCK',
                (int) $document->id,
                'SOUMISSION',
                "Soumission du document {$document->reference}.",
                [
                    'statut' => $ancienStatut,
                    'motif_rejet' => $ancienMotifRejet,
                ],
                [
                    'statut' => $document->statut,
                    'date_soumission' => $document->date_soumission,
                ],
                $userId
            );

            return $document->fresh();
        }, 5);
    }

    /**
     * Rejet motivé par un utilisateur différent du créateur.
     */
    public function rejeter(
        int|SupportDocumentStock $document,
        string $motif,
        ?int $userId = null
    ): SupportDocumentStock {
        $userId ??= (int) Auth::id();
        $documentId = $this->identifier($document);

        $motif = trim($motif);

        if ($motif === '' || mb_strlen($motif) > 5000) {
            $this->erreur(
                'Le motif du rejet est obligatoire et limité à 5 000 caractères.'
            );
        }

        return DB::transaction(function () use (
            $documentId,
            $motif,
            $userId
        ) {
            $document = $this->verrouillerDocument($documentId);

            if ($document->statut !== 'EN_ATTENTE_VALIDATION') {
                $this->erreur(
                    'Seuls les documents en attente peuvent être rejetés.'
                );
            }

            $this->verifierValidateur($document, $userId);

            $document->statut = 'REJETEE';
            $document->motif_rejet = $motif;
            $document->user_validation_id = null;
            $document->date_validation = null;
            $document->save();

            $this->historiqueService->enregistrer(
                'DOCUMENT_STOCK',
                (int) $document->id,
                'REJET',
                "Rejet du document {$document->reference}.",
                ['statut' => 'EN_ATTENTE_VALIDATION'],
                [
                    'statut' => 'REJETEE',
                    'motif_rejet' => $motif,
                ],
                $userId
            );

            return $document->fresh();
        }, 5);
    }

    /**
     * Validation et application atomique des mouvements.
     */
    public function valider(
        int|SupportDocumentStock $document,
        ?int $userId = null
    ): SupportDocumentStock {
        $userId ??= (int) Auth::id();
        $documentId = $this->identifier($document);

        return DB::transaction(function () use (
            $documentId,
            $userId
        ) {
            $document = $this->verrouillerDocument($documentId);

            if ($document->statut !== 'EN_ATTENTE_VALIDATION') {
                $this->erreur(
                    'Seuls les documents en attente peuvent être validés.'
                );
            }

            $this->verifierValidateur($document, $userId);

            if (!$document->lignes()->exists()) {
                $this->erreur(
                    'Impossible de valider un document sans ligne.'
                );
            }

            $document->statut = 'VALIDEE';
            $document->user_validation_id = $userId;
            $document->date_validation = now();
            $document->motif_rejet = null;
            $document->save();

            // Les mouvements sont appliqués dans la même transaction.
            // Toute erreur annule aussi le changement de statut.
            $this->mouvementService->appliquerDocument(
                $document,
                $userId
            );

            $this->historiqueService->enregistrer(
                'DOCUMENT_STOCK',
                (int) $document->id,
                'VALIDATION',
                "Validation du document {$document->reference}.",
                ['statut' => 'EN_ATTENTE_VALIDATION'],
                [
                    'statut' => 'VALIDEE',
                    'user_validation_id' => $userId,
                    'date_validation' => $document->date_validation,
                ],
                $userId
            );

            return $document->fresh([
                'lignes',
                'mouvements',
                'createur',
                'validateur',
            ]);
        }, 5);
    }

    /**
     * Validation des données de l'entête.
     */
    private function validerEntete(array $donnees, string $type): void
    {
        $regles = [
            'date_document' => ['required', 'date'],
            'motif' => ['nullable', 'string', 'max:255'],
            'observations' => ['nullable', 'string'],
            'support_fournisseur_stock_id' => [
                $type === 'RECEPTION' ? 'required' : 'nullable',
                'integer',
                'exists:support_fournisseurs_stocks,id',
            ],
            'employee_id' => ['nullable', 'integer', 'exists:employees,id'],
            'direction_id' => ['nullable', 'integer', 'exists:directions,id'],
            'antenne_id' => ['nullable', 'integer', 'exists:antennes,id'],
            'support_ticket_id' => [
                'nullable',
                'integer',
                'exists:support_tickets,id',
            ],
        ];

        Validator::make($donnees, $regles)->validate();

        $destinataires = array_filter([
            $donnees['employee_id'] ?? null,
            $donnees['direction_id'] ?? null,
            $donnees['antenne_id'] ?? null,
            $donnees['support_ticket_id'] ?? null,
        ], fn ($valeur) => $valeur !== null && $valeur !== '');

        if ($type === 'SORTIE' && count($destinataires) !== 1) {
            $this->erreur(
                'Une sortie doit avoir exactement un destinataire.'
            );
        }

        if ($type !== 'SORTIE' && count($destinataires) > 0) {
            $this->erreur(
                'Seul un document de sortie peut avoir un destinataire.'
            );
        }
    }

    /**
     * Création et contrôle d'une ligne.
     */
    private function creerOuValiderLigne(
        SupportDocumentStock $document,
        array $donnees
    ): SupportLigneDocumentStock {
        $ligne = new SupportLigneDocumentStock();

        $this->remplirLigne($ligne, $document, $donnees);

        $ligne->support_document_stock_id = $document->id;
        $ligne->valeur_ligne = '0.0000';
        $ligne->save();

        return $ligne;
    }

    private function remplirLigne(
        SupportLigneDocumentStock $ligne,
        SupportDocumentStock $document,
        array $donnees
    ): void {
        $validation = Validator::make($donnees, [
            'support_article_stock_id' => [
                'required',
                'integer',
                'exists:support_articles_stocks,id,deleted_at,NULL',
            ],
            'quantite' => ['required', 'numeric', 'gt:0'],
            'cout_unitaire' => ['nullable', 'numeric', 'min:0'],
            'support_ligne_origine_id' => [
                $document->type_document === 'RETOUR'
                    ? 'required'
                    : 'nullable',
                'integer',
                'exists:support_lignes_documents_stocks,id',
            ],
            'observations' => ['nullable', 'string'],
        ]);

        $validation->validate();

        $article = SupportArticleStock::query()
            ->whereKey($donnees['support_article_stock_id'])
            ->firstOrFail();

        if (!$article->actif) {
            $this->erreur(
                "L'article {$article->reference} est inactif."
            );
        }

        $quantite = $this->normaliserDecimal(
            (string) $donnees['quantite'],
            3,
            true,
            'La quantité doit être positive et comporter au maximum trois décimales.'
        );

        $cout = $this->normaliserDecimal(
            (string) ($donnees['cout_unitaire'] ?? '0'),
            4,
            false,
            'Le coût unitaire doit être positif ou nul et comporter au maximum quatre décimales.'
        );

        if (
            in_array(
                $document->type_document,
                ['RECEPTION', 'AJUSTEMENT_POSITIF'],
                true
            )
            && !array_key_exists('cout_unitaire', $donnees)
        ) {
            $this->erreur(
                'Le coût unitaire est obligatoire pour ce type de document.'
            );
        }

        if ($document->type_document === 'RETOUR') {
            $origine = SupportLigneDocumentStock::query()
                ->with('document')
                ->findOrFail($donnees['support_ligne_origine_id']);

            if (
                !$origine->document
                || $origine->document->type_document !== 'SORTIE'
                || (int) $origine->support_article_stock_id !==
                    (int) $article->id
            ) {
                $this->erreur(
                    'La ligne d’origine doit appartenir à une sortie du même article.'
                );
            }
        } elseif (!empty($donnees['support_ligne_origine_id'])) {
            $this->erreur(
                'Une ligne d’origine est uniquement autorisée pour un retour.'
            );
        }

        $ligne->support_article_stock_id = $article->id;
        $ligne->support_ligne_origine_id =
            $document->type_document === 'RETOUR'
                ? $donnees['support_ligne_origine_id']
                : null;
        $ligne->quantite = $quantite;
        $ligne->cout_unitaire = $cout;
        $ligne->observations = $donnees['observations'] ?? null;
    }

    private function normaliserDecimal(
        string $valeur,
        int $echelle,
        bool $strictementPositif,
        string $message
    ): string {
        try {
            $decimal = BigDecimal::of($valeur);

            if (
                $strictementPositif
                ? !$decimal->isGreaterThan('0')
                : $decimal->isLessThan('0')
            ) {
                $this->erreur($message);
            }

            return (string) $decimal->toScale(
                $echelle,
                RoundingMode::UNNECESSARY
            );
        } catch (\Throwable $exception) {
            if ($exception instanceof ValidationException) {
                throw $exception;
            }

            $this->erreur($message);
        }
    }

    private function verifierModification(
        SupportDocumentStock $document,
        int $userId
    ): void {
        if (!in_array($document->statut, ['BROUILLON', 'REJETEE'], true)) {
            $this->erreur(
                'Ce document ne peut plus être modifié.'
            );
        }

        $this->verifierCreateur($document, $userId);
    }

    private function verifierCreateur(
        SupportDocumentStock $document,
        int $userId
    ): void {
        if ((int) $document->user_create_id !== $userId) {
            $this->erreur(
                'Seul le créateur du document peut effectuer cette opération.'
            );
        }
    }

    private function verifierValidateur(
        SupportDocumentStock $document,
        int $userId
    ): void {
        if ((int) $document->user_create_id === $userId) {
            $this->erreur(
                'Le créateur ne peut pas valider ou rejeter son propre document.'
            );
        }
    }

    private function verrouillerDocument(int $id): SupportDocumentStock
    {
        return SupportDocumentStock::query()
            ->whereKey($id)
            ->lockForUpdate()
            ->firstOrFail();
    }

    private function identifier(
        int|SupportDocumentStock $document
    ): int {
        return $document instanceof SupportDocumentStock
            ? (int) $document->id
            : $document;
    }

    private function erreur(string $message): never
    {
        throw ValidationException::withMessages([
            'document_stock' => $message,
        ]);
    }
}
