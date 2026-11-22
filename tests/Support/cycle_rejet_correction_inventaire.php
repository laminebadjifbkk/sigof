<?php

use Illuminate\Support\Facades\DB;
use App\Models\Support\SupportCategorieStock;
use App\Models\Support\SupportUniteStock;
use App\Models\Support\SupportFournisseurStock;
use App\Models\Support\SupportArticleStock;
use App\Models\Support\SupportStock;
use App\Services\Support\SupportDocumentStockService;
use App\Services\Support\SupportInventaireStockService;

require __DIR__ . '/../../vendor/autoload.php';

$app = require __DIR__ . '/../../bootstrap/app.php';

$kernel = $app->make(
    \Illuminate\Contracts\Console\Kernel::class
);

$kernel->bootstrap();
$transactionOuverte = false;

try {
    DB::beginTransaction();
    $transactionOuverte = true;

    $createurId = 1;
    $validateurId = 2;
    $motifRejet = 'Comptages à vérifier : écarts physiques non justifiés.';

    // Références initiales : le test ne doit pas dépendre des données existantes.
    $mouvementsAvantTest = DB::table('support_mouvements_stocks')->count();

    $documentsAjustementsAvantTest = DB::table('support_documents_stocks')
        ->whereIn('type_document', ['AJUSTEMENT_POSITIF', 'AJUSTEMENT_NEGATIF'])
        ->where('statut', 'VALIDEE')
        ->count();

    $verifier = function (bool $condition, string $message): void {
        if (!$condition) {
            throw new RuntimeException($message);
        }
    };

    foreach ([$createurId, $validateurId] as $userId) {
        $verifier(
            DB::table('users')->where('id', $userId)->exists(),
            "Utilisateur {$userId} introuvable."
        );
    }

    $suffixe = strtoupper(substr(
        str_replace('.', '', uniqid('', true)),
        -10
    ));

    // =====================================================
    // 1. REFERENTIELS ET ARTICLES TEMPORAIRES
    // =====================================================

    $categorie = SupportCategorieStock::create([
        'code' => 'TST' . $suffixe,
        'libelle' => 'Catégorie test rejet inventaire',
        'description' => 'Test transactionnel',
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
        'raison_sociale' => 'FOURNISSEUR TEST REJET INVENTAIRE',
        'observations' => 'Test transactionnel',
        'actif' => true,
        'user_create_id' => $createurId,
    ]);

    $articles = [];

    for ($i = 1; $i <= 2; $i++) {
        $articles[] = SupportArticleStock::create([
            'reference' => 'ART-REJ-' . $suffixe . '-' . $i,
            'designation' => 'Article test rejet ' . $i,
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
    // 2. RECEPTION INITIALE : 10 UNITES PAR ARTICLE
    // =====================================================

    $documentService = app(SupportDocumentStockService::class);

    $reception = $documentService->creerDocument([
        'type_document' => 'RECEPTION',
        'support_fournisseur_stock_id' => $fournisseur->id,
        'date_document' => now()->toDateString(),
        'motif' => 'Stock initial test rejet inventaire',
    ], $createurId);

    foreach ($articles as $article) {
        $documentService->ajouterLigne($reception, [
            'support_article_stock_id' => $article->id,
            'quantite' => 10,
            'cout_unitaire' => 100,
        ], $createurId);
    }

    $documentService->soumettre($reception, $createurId);
    $documentService->valider($reception, $validateurId);

    $mouvementsApresReception = DB::table('support_mouvements_stocks')->count();

    $verifier(
        $mouvementsApresReception === $mouvementsAvantTest + 2,
        'Les deux mouvements de réception initiale ne sont pas créés.'
    );

    // =====================================================
    // 3. CREATION ET DEMARRAGE INVENTAIRE
    // =====================================================

    $inventaireService = app(SupportInventaireStockService::class);

    $inventaire = $inventaireService->creer([
        'libelle' => 'TEST REJET ET CORRECTION ' . $suffixe,
        'description' => 'Test du cycle de rejet et reprise',
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

    foreach ($articleIds as $articleId) {
        $stock = SupportStock::where(
            'support_article_stock_id',
            $articleId
        )->first();

        $verifier(
            $stock && $stock->verrouille,
            "Stock {$articleId} non verrouillé."
        );

        $verifier(
            (int) $stock->support_inventaire_id === (int) $inventaire->id,
            "Mauvais propriétaire du verrouillage pour {$articleId}."
        );
    }

    // =====================================================
    // 4. PREMIER COMPTAGE VOLONTAIREMENT INCORRECT
    // =====================================================

    $inventaireService->enregistrerComptage(
        $inventaire,
        $articleIds[0],
        8,
        'Premier comptage à vérifier',
        $createurId
    );

    $inventaireService->enregistrerComptage(
        $inventaire,
        $articleIds[1],
        12,
        'Premier comptage à vérifier',
        $createurId
    );

    $inventaireService->soumettre($inventaire, $createurId);

    $inventaire->refresh();

    $verifier(
        $inventaire->statut === 'EN_ATTENTE_VALIDATION',
        'Première soumission incorrecte.'
    );

    // =====================================================
    // 5. REJET PAR LE VALIDATEUR
    // =====================================================

    $inventaireService->rejeter(
        $inventaire,
        $motifRejet,
        $validateurId
    );

    $inventaire->refresh();

    $verifier(
        $inventaire->statut === 'REJETEE',
        'Le rejet de l’inventaire a échoué.'
    );

    $verifier(
        $inventaire->motif_rejet === $motifRejet,
        'Le motif du rejet n’est pas enregistré.'
    );

    $verifier(
        $inventaire->user_validation_id === null,
        'Le validateur ne devrait pas être affecté après rejet.'
    );

    // Les stocks doivent rester verrouillés.
    foreach ($articleIds as $articleId) {
        $stock = SupportStock::where(
            'support_article_stock_id',
            $articleId
        )->first();

        $verifier(
            $stock && $stock->verrouille,
            "Le stock {$articleId} a été déverrouillé après rejet."
        );
    }

    // Aucun mouvement supplémentaire après rejet.
    $verifier(
        DB::table('support_mouvements_stocks')->count() === $mouvementsApresReception,
        'Un mouvement a été créé pendant le rejet.'
    );

    // =====================================================
    // 6. CONTROLE DE LA TRACE DU REJET
    // =====================================================

    $historiqueRejet = DB::table('support_historiques_stocks')
        ->where('entite', 'INVENTAIRE_STOCK')
        ->where('entite_id', $inventaire->id)
        ->where('action', 'REJET')
        ->latest('created_at')
        ->first();

    $verifier(
        $historiqueRejet !== null,
        'Historique du rejet introuvable.'
    );

    $valeursRejet = is_array($historiqueRejet->nouvelles_valeurs)
        ? $historiqueRejet->nouvelles_valeurs
        : json_decode($historiqueRejet->nouvelles_valeurs, true);

    $verifier(
        ($valeursRejet['motif_rejet'] ?? null) === $motifRejet,
        'Le motif du rejet est absent de l’historique.'
    );

    // =====================================================
    // 7. CORRECTION PAR LE CREATEUR
    // =====================================================

    // Article 1 : quantité physique corrigée à 11.
    $inventaireService->enregistrerComptage(
        $inventaire,
        $articleIds[0],
        11,
        'Comptage corrigé après rejet',
        $createurId
    );

    // Article 2 : quantité physique corrigée à 9.
    $inventaireService->enregistrerComptage(
        $inventaire,
        $articleIds[1],
        9,
        'Comptage corrigé après rejet',
        $createurId
    );

    $lignes = DB::table('support_inventaire_lignes')
        ->where('support_inventaire_id', $inventaire->id)
        ->orderBy('support_article_stock_id')
        ->get();

    $verifier($lignes->count() === 2, 'Nombre de lignes incorrect.');

    $quantitesComptees = $lignes->pluck('quantite_comptee')
        ->map(fn ($value) => (float) $value)
        ->values()
        ->all();

    $ecarts = $lignes->pluck('ecart')
        ->map(fn ($value) => (float) $value)
        ->values()
        ->all();

    $verifier(
        $quantitesComptees === [11.0, 9.0],
        'Les quantités corrigées sont incorrectes.'
    );

    $verifier(
        $ecarts === [1.0, -1.0],
        'Les écarts après correction sont incorrects.'
    );

    // =====================================================
    // 8. NOUVELLE SOUMISSION
    // =====================================================

    $inventaireService->soumettre($inventaire, $createurId);

    $inventaire->refresh();

    $verifier(
        $inventaire->statut === 'EN_ATTENTE_VALIDATION',
        'La nouvelle soumission a échoué.'
    );

    $verifier(
        $inventaire->motif_rejet === null,
        'Le motif courant du rejet aurait dû être effacé.'
    );

    $verifier(
        $inventaire->date_soumission !== null,
        'La nouvelle date de soumission est absente.'
    );

    // Vérifier à nouveau la conservation du motif dans l'historique.
    $historiqueRejetApresCorrection = DB::table('support_historiques_stocks')
        ->where('entite', 'INVENTAIRE_STOCK')
        ->where('entite_id', $inventaire->id)
        ->where('action', 'REJET')
        ->first();

    $verifier(
        $historiqueRejetApresCorrection !== null,
        'La trace historique du rejet a disparu.'
    );

    $valeursRejetApresCorrection = is_array(
        $historiqueRejetApresCorrection->nouvelles_valeurs
    )
        ? $historiqueRejetApresCorrection->nouvelles_valeurs
        : json_decode(
            $historiqueRejetApresCorrection->nouvelles_valeurs,
            true
        );

    $verifier(
        ($valeursRejetApresCorrection['motif_rejet'] ?? null) === $motifRejet,
        'Le motif du rejet n’est plus conservé après correction.'
    );

    // Aucun mouvement entre le rejet et la validation finale.
    $verifier(
        DB::table('support_mouvements_stocks')->count() === $mouvementsApresReception,
        'Un mouvement a été créé avant la validation définitive.'
    );

    // Les stocks restent verrouillés pendant la reprise.
    foreach ($articleIds as $articleId) {
        $stock = SupportStock::where(
            'support_article_stock_id',
            $articleId
        )->first();

        $verifier(
            $stock && $stock->verrouille,
            "Le stock {$articleId} n'est plus verrouillé après resoumission."
        );
    }

    // =====================================================
    // 9. VALIDATION DEFINITIVE
    // =====================================================

    $inventaireService->valider($inventaire, $validateurId);

    $inventaire->refresh();

    $verifier(
        $inventaire->statut === 'VALIDEE',
        'La validation définitive a échoué.'
    );

    $verifier(
        (int) $inventaire->user_validation_id === $validateurId,
        'Le validateur final est incorrect.'
    );

    $verifier(
        $inventaire->date_validation !== null,
        'Date de validation absente.'
    );

    $verifier(
        $inventaire->date_fin !== null,
        'Date de fin absente.'
    );

    // =====================================================
    // 10. CONTROLE FINAL DES STOCKS ET MOUVEMENTS
    // =====================================================

    $quantitesAttendues = [11.0, 9.0];

    foreach ($articles as $index => $article) {
        $stock = SupportStock::where(
            'support_article_stock_id',
            $article->id
        )->first();

        $verifier(
            $stock !== null,
            "Stock final absent pour {$article->reference}."
        );

        $verifier(
            abs((float) $stock->quantite - $quantitesAttendues[$index]) < 0.0001,
            "Quantité finale incorrecte pour {$article->reference}."
        );

        $verifier(
            !$stock->verrouille,
            "Le stock {$article->reference} est toujours verrouillé."
        );

        $verifier(
            $stock->support_inventaire_id === null,
            "Le stock {$article->reference} conserve un inventaire propriétaire."
        );

        $verifier(
            abs((float) $stock->cout_moyen_pondere - 100.0) < 0.0001,
            "CMP final incorrect pour {$article->reference}."
        );
    }

    $nombreMouvementsFinal = DB::table('support_mouvements_stocks')->count();

    $verifier(
        $nombreMouvementsFinal === $mouvementsAvantTest + 4,
        "Nombre de mouvements final incorrect : {$nombreMouvementsFinal}."
    );

    $nombreAjustements = DB::table('support_documents_stocks')
        ->whereIn('type_document', [
            'AJUSTEMENT_POSITIF',
            'AJUSTEMENT_NEGATIF',
        ])
        ->where('statut', 'VALIDEE')
        ->count();

    $verifier(
        $nombreAjustements === $documentsAjustementsAvantTest + 2,
        "Nombre de documents d'ajustement incorrect : {$nombreAjustements}."
    );

    echo PHP_EOL;
    echo "============================================" . PHP_EOL;
    echo "TEST REJET / CORRECTION INVENTAIRE : SUCCES" . PHP_EOL;
    echo "============================================" . PHP_EOL;
    echo "Référence : {$inventaire->reference}" . PHP_EOL;
    echo "Statut final : {$inventaire->statut}" . PHP_EOL;
    echo "Motif du rejet conservé dans l'historique : OUI" . PHP_EOL;
    echo "Nouvelle soumission : OK" . PHP_EOL;
    echo "Aucun mouvement avant validation finale : OK" . PHP_EOL;
    echo "Quantités finales : 11 et 9" . PHP_EOL;
    echo "Stocks déverrouillés : OUI" . PHP_EOL;
    echo "Mouvements finaux : {$nombreMouvementsFinal}" . PHP_EOL;
    echo "Documents d'ajustement : {$nombreAjustements}" . PHP_EOL;
    echo "============================================" . PHP_EOL;

} catch (\Throwable $e) {
    echo PHP_EOL;
    echo "TEST REJET / CORRECTION INVENTAIRE : ECHEC" . PHP_EOL;
    echo $e->getMessage() . PHP_EOL;
    echo $e->getFile() . ':' . $e->getLine() . PHP_EOL;

} finally {
    if ($transactionOuverte && DB::transactionLevel() > 0) {
        DB::rollBack();
        echo PHP_EOL . "ROLLBACK OK : aucune donnée de test conservée." . PHP_EOL;
    }
}
