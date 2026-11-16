<?php

use Illuminate\Support\Facades\DB;
use App\Models\Support\SupportCategorieStock;
use App\Models\Support\SupportUniteStock;
use App\Models\Support\SupportFournisseurStock;
use App\Models\Support\SupportArticleStock;
use App\Models\Support\SupportStock;
use App\Services\Support\SupportDocumentStockService;
use App\Services\Support\SupportInventaireStockService;

$transactionOuverte = false;

try {
    DB::beginTransaction();
    $transactionOuverte = true;

    $createurId = 1;
    $validateurId = 2;

    $verifier = function (bool $condition, string $message): void {
        if (!$condition) {
            throw new RuntimeException($message);
        }
    };

    $verifier(
        DB::table('users')->where('id', $createurId)->exists(),
        'Utilisateur créateur introuvable.'
    );

    $verifier(
        DB::table('users')->where('id', $validateurId)->exists(),
        'Utilisateur validateur introuvable.'
    );

    $suffixe = strtoupper(substr(str_replace('.', '', uniqid('', true)), -10));

    // =====================================================
    // 1. REFERENTIELS TEMPORAIRES
    // =====================================================

    $categorie = SupportCategorieStock::create([
        'code' => 'TST' . $suffixe,
        'libelle' => 'Catégorie test inventaire',
        'description' => 'Donnée temporaire de test',
        'actif' => true,
        'ordre' => 999,
        'user_create_id' => $createurId,
    ]);

    $unite = SupportUniteStock::create([
        'code' => 'UT' . $suffixe,
        'libelle' => 'Unité test',
        'symbole' => 'UT',
        'autorise_decimales' => false,
        'actif' => true,
        'ordre' => 999,
        'user_create_id' => $createurId,
    ]);

    $fournisseur = SupportFournisseurStock::create([
        'code' => 'F' . $suffixe,
        'raison_sociale' => 'FOURNISSEUR TEST INVENTAIRE',
        'observations' => 'Donnée temporaire de test',
        'actif' => true,
        'user_create_id' => $createurId,
    ]);

    // =====================================================
    // 2. CREATION DES TROIS ARTICLES
    // =====================================================

    $articles = [];

    for ($i = 1; $i <= 3; $i++) {
        $articles[] = SupportArticleStock::create([
            'reference' => 'ART-INV-' . $suffixe . '-' . $i,
            'designation' => 'Article test inventaire ' . $i,
            'support_categorie_stock_id' => $categorie->id,
            'support_unite_stock_id' => $unite->id,
            'description' => 'Article temporaire de test',
            'stock_minimum' => 0,
            'stock_maximum' => 100,
            'actif' => true,
            'user_create_id' => $createurId,
        ]);
    }

    $articleIds = array_map(
        fn ($article) => (int) $article->id,
        $articles
    );

    // =====================================================
    // 3. RECEPTION INITIALE : 10 UNITES PAR ARTICLE
    // =====================================================

    $documentService = app(SupportDocumentStockService::class);

    $reception = $documentService->creerDocument([
        'type_document' => 'RECEPTION',
        'support_fournisseur_stock_id' => $fournisseur->id,
        'date_document' => now()->toDateString(),
        'motif' => 'Constitution du stock initial pour test inventaire',
        'observations' => 'Document temporaire de test',
    ], $createurId);

    foreach ($articles as $article) {
        $documentService->ajouterLigne($reception, [
            'support_article_stock_id' => $article->id,
            'quantite' => 10,
            'cout_unitaire' => 100,
            'observations' => 'Réception initiale de test',
        ], $createurId);
    }

    $documentService->soumettre($reception, $createurId);
    $documentService->valider($reception, $validateurId);

    // Vérification du stock initial.
    foreach ($articles as $article) {
        $stock = SupportStock::where(
            'support_article_stock_id',
            $article->id
        )->first();

        $verifier($stock !== null, 'Stock initial introuvable.');
        $verifier(
            (float) $stock->quantite === 10.0,
            "Stock initial incorrect pour {$article->reference}."
        );
        $verifier(
            (float) $stock->cout_moyen_pondere === 100.0,
            "CMP initial incorrect pour {$article->reference}."
        );
    }

    // =====================================================
    // 4. CREATION ET DEMARRAGE DE L'INVENTAIRE
    // =====================================================

    $inventaireService = app(SupportInventaireStockService::class);

    $inventaire = $inventaireService->creer([
        'libelle' => 'TEST COMPLET INVENTAIRE ' . $suffixe,
        'description' => 'Test transactionnel des écarts',
        'date_debut' => now()->toDateTimeString(),
    ], $createurId);

    $inventaireService->demarrer(
        $inventaire,
        $articleIds,
        $createurId
    );

    $inventaire->refresh();

    $verifier(
        $inventaire->statut === 'EN_COURS',
        'Le démarrage de l’inventaire a échoué.'
    );

    // Vérification des trois verrouillages.
    foreach ($articleIds as $articleId) {
        $stock = SupportStock::where(
            'support_article_stock_id',
            $articleId
        )->first();

        $verifier(
            $stock->verrouille === true,
            "L'article {$articleId} n'est pas verrouillé."
        );

        $verifier(
            (int) $stock->support_inventaire_id === (int) $inventaire->id,
            "L'inventaire propriétaire de l'article {$articleId} est incorrect."
        );
    }

    // =====================================================
    // 5. COMPTAGES PHYSIQUES
    // =====================================================

    // Article 1 : 10 -> 11 : écart positif de +1.
    $inventaireService->enregistrerComptage(
        $inventaire,
        $articleIds[0],
        11,
        'Comptage supérieur au stock théorique',
        $createurId
    );

    // Article 2 : 10 -> 9 : écart négatif de -1.
    $inventaireService->enregistrerComptage(
        $inventaire,
        $articleIds[1],
        9,
        'Comptage inférieur au stock théorique',
        $createurId
    );

    // Article 3 : 10 -> 10 : aucun écart.
    $inventaireService->enregistrerComptage(
        $inventaire,
        $articleIds[2],
        10,
        'Aucun écart constaté',
        $createurId
    );

    // Vérification des écarts calculés.
    $lignes = DB::table('support_inventaire_lignes')
        ->where('support_inventaire_id', $inventaire->id)
        ->orderBy('support_article_stock_id')
        ->get();

    $verifier($lignes->count() === 3, 'Les trois lignes d’inventaire sont absentes.');

    $ecarts = $lignes->pluck('ecart')
        ->map(fn ($value) => (float) $value)
        ->values()
        ->all();

    $verifier(
        $ecarts === [1.0, -1.0, 0.0],
        'Les écarts calculés ne correspondent pas aux comptages : '
        . json_encode($ecarts)
    );

    // =====================================================
    // 6. SOUMISSION ET VALIDATION
    // =====================================================

    $inventaireService->soumettre($inventaire, $createurId);

    $inventaire->refresh();

    $verifier(
        $inventaire->statut === 'EN_ATTENTE_VALIDATION',
        'La soumission de l’inventaire a échoué.'
    );

    $inventaireService->valider($inventaire, $validateurId);

    $inventaire->refresh();

    $verifier(
        $inventaire->statut === 'VALIDEE',
        'La validation finale de l’inventaire a échoué.'
    );

    $verifier(
        (int) $inventaire->user_validation_id === $validateurId,
        'Le validateur enregistré est incorrect.'
    );

    $verifier(
        $inventaire->date_fin !== null,
        'La date de fin de l’inventaire est absente.'
    );

    // =====================================================
    // 7. CONTROLE DES QUANTITES ET DEVERROUILLAGE
    // =====================================================

    $quantitesAttendues = [11.0, 9.0, 10.0];
    $resultats = [];

    foreach ($articles as $index => $article) {
        $stock = SupportStock::where(
            'support_article_stock_id',
            $article->id
        )->first();

        $quantiteFinale = (float) $stock->quantite;

        $verifier(
            abs($quantiteFinale - $quantitesAttendues[$index]) < 0.0001,
            "Quantité finale incorrecte pour {$article->reference}."
        );

        $verifier(
            !$stock->verrouille,
            "L'article {$article->reference} est encore verrouillé."
        );

        $verifier(
            $stock->support_inventaire_id === null,
            "L'article {$article->reference} conserve son inventaire propriétaire."
        );

        $resultats[] = [
            'reference' => $article->reference,
            'quantite_finale' => $quantiteFinale,
            'cmp' => (float) $stock->cout_moyen_pondere,
            'valeur_stock' => (float) $stock->valeur_stock,
            'verrouille' => (bool) $stock->verrouille,
        ];
    }

    // =====================================================
    // 8. CONTROLE DES MOUVEMENTS
    // =====================================================

    $mouvements = DB::table('support_mouvements_stocks')
        ->whereIn('support_article_stock_id', $articleIds)
        ->orderBy('id')
        ->get();

    // 3 mouvements de réception + 1 ajustement positif + 1 négatif.
    $verifier(
        $mouvements->count() === 5,
        'Nombre de mouvements incorrect : ' . $mouvements->count()
    );

    $ajustementsPositifs = DB::table('support_documents_stocks')
        ->where('type_document', 'AJUSTEMENT_POSITIF')
        ->where('motif', 'like', '%inventaire%')
        ->count();

    $ajustementsNegatifs = DB::table('support_documents_stocks')
        ->where('type_document', 'AJUSTEMENT_NEGATIF')
        ->where('motif', 'like', '%inventaire%')
        ->count();

    dump([
        'test' => 'SUCCES',
        'reference_inventaire' => $inventaire->reference,
        'statut' => $inventaire->statut,
        'createur_id' => $createurId,
        'validateur_id' => $validateurId,
        'ecarts' => $ecarts,
        'articles' => $resultats,
        'nombre_mouvements' => $mouvements->count(),
        'ajustements_positifs' => $ajustementsPositifs,
        'ajustements_negatifs' => $ajustementsNegatifs,
        'date_fin' => $inventaire->date_fin,
    ]);

} catch (Throwable $e) {
    dump([
        'test' => 'ECHEC',
        'erreur' => get_class($e),
        'message' => $e->getMessage(),
        'fichier' => $e->getFile(),
        'ligne' => $e->getLine(),
        'trace' => $e->getTraceAsString(),
    ]);
} finally {
    if ($transactionOuverte && DB::transactionLevel() > 0) {
        DB::rollBack();
        dump('ROLLBACK OK : aucune donnée de test conservée.');
    }
}
