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

    // 1. Référentiels et article de test.

    $categorie = SupportCategorieStock::create([
        'code' => 'TST' . $suffixe,
        'libelle' => 'Catégorie test verrouillage',
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
        'raison_sociale' => 'FOURNISSEUR TEST VERROUILLAGE',
        'actif' => true,
        'user_create_id' => $createurId,
    ]);

    $article = SupportArticleStock::create([
        'reference' => 'ART-LOCK-' . $suffixe,
        'designation' => 'Article test verrouillage',
        'support_categorie_stock_id' => $categorie->id,
        'support_unite_stock_id' => $unite->id,
        'stock_minimum' => 0,
        'stock_maximum' => 100,
        'actif' => true,
        'user_create_id' => $createurId,
    ]);

    $documentService = app(SupportDocumentStockService::class);

    // 2. Réception initiale de 10 unités.

    $receptionInitiale = $documentService->creerDocument([
        'type_document' => 'RECEPTION',
        'support_fournisseur_stock_id' => $fournisseur->id,
        'date_document' => now()->toDateString(),
        'motif' => 'Réception initiale test verrouillage',
    ], $createurId);

    $documentService->ajouterLigne($receptionInitiale, [
        'support_article_stock_id' => $article->id,
        'quantite' => 10,
        'cout_unitaire' => 100,
    ], $createurId);

    $documentService->soumettre($receptionInitiale, $createurId);
    $documentService->valider($receptionInitiale, $validateurId);

    $mouvementsInitiaux = DB::table('support_mouvements_stocks')->count();

    $stock = SupportStock::where(
        'support_article_stock_id',
        $article->id
    )->firstOrFail();

    $verifier(
        (float) $stock->quantite === 10.0,
        'Le stock initial devrait être de 10 unités.'
    );

    // 3. Démarrage de l'inventaire et verrouillage.

    $inventaireService = app(SupportInventaireStockService::class);

    $inventaire = $inventaireService->creer([
        'libelle' => 'TEST BLOCAGE MOUVEMENT ' . $suffixe,
        'description' => 'Vérification du verrouillage',
        'date_debut' => now()->toDateTimeString(),
    ], $createurId);

    $inventaireService->demarrer(
        $inventaire,
        [(int) $article->id],
        $createurId
    );

    $stock->refresh();

    $verifier(
        $stock->verrouille === true,
        'Le stock aurait dû être verrouillé.'
    );

    // 4. Création d'une seconde réception de 5 unités.

    $secondeReception = $documentService->creerDocument([
        'type_document' => 'RECEPTION',
        'support_fournisseur_stock_id' => $fournisseur->id,
        'date_document' => now()->toDateString(),
        'motif' => 'Tentative de mouvement pendant inventaire',
    ], $createurId);

    $documentService->ajouterLigne($secondeReception, [
        'support_article_stock_id' => $article->id,
        'quantite' => 5,
        'cout_unitaire' => 100,
    ], $createurId);

    $documentService->soumettre($secondeReception, $createurId);

    // 5. Tentative de validation : elle doit échouer.

    $mouvementRefuse = false;
    $messageErreur = '';

    try {
        $documentService->valider(
            $secondeReception,
            $validateurId
        );
    } catch (\Throwable $e) {
        $mouvementRefuse = true;
        $messageErreur = $e->getMessage();

        if (method_exists($e, 'errors')) {
            $messageErreur .= ' ' . json_encode($e->errors());
        }
    }

    $verifier(
        $mouvementRefuse,
        'ERREUR : la réception a été validée malgré le verrouillage.'
    );

    $verifier(
        str_contains(
            mb_strtolower($messageErreur),
            mb_strtolower('verrouillé par un inventaire')
        ),
        'Le mouvement a échoué pour une autre raison : ' . $messageErreur
    );

    // 6. Vérification de l'absence de modification.

    $stock->refresh();
    $secondeReception->refresh();

    $verifier(
        (float) $stock->quantite === 10.0,
        'La quantité du stock a été modifiée malgré le verrouillage.'
    );

    $verifier(
        $stock->verrouille === true,
        'Le verrouillage du stock a été perdu.'
    );

    $verifier(
        (int) $stock->support_inventaire_id === (int) $inventaire->id,
        'Le propriétaire du verrouillage a été modifié.'
    );

    $verifier(
        $secondeReception->statut === 'EN_ATTENTE_VALIDATION',
        'Le document aurait dû rester en attente de validation.'
    );

    $verifier(
        DB::table('support_mouvements_stocks')->count() === $mouvementsInitiaux,
        'Un mouvement a été enregistré malgré le refus.'
    );

    $verifier(
        !DB::table('support_mouvements_stocks')
            ->where('support_article_stock_id', $article->id)
            ->where('type_mouvement', 'ENTREE')
            ->where('quantite', 5)
            ->exists(),
        'Le mouvement de 5 unités a été enregistré.'
    );

    echo PHP_EOL;
    echo "============================================" . PHP_EOL;
    echo "TEST BLOCAGE MOUVEMENT VERROUILLE : SUCCES" . PHP_EOL;
    echo "============================================" . PHP_EOL;
    echo "Stock initial : 10" . PHP_EOL;
    echo "Tentative de réception : 5" . PHP_EOL;
    echo "Mouvement refusé : OUI" . PHP_EOL;
    echo "Motif : article verrouillé par un inventaire" . PHP_EOL;
    echo "Stock final : " . $stock->quantite . PHP_EOL;
    echo "Stock toujours verrouillé : OUI" . PHP_EOL;
    echo "Document toujours en attente : OUI" . PHP_EOL;
    echo "Aucun mouvement supplémentaire : OUI" . PHP_EOL;
    echo "============================================" . PHP_EOL;

} catch (\Throwable $e) {
    echo PHP_EOL;
    echo "TEST BLOCAGE MOUVEMENT : ECHEC" . PHP_EOL;
    echo $e->getMessage() . PHP_EOL;
    echo $e->getFile() . ':' . $e->getLine() . PHP_EOL;

} finally {
    if ($transactionOuverte && DB::transactionLevel() > 0) {
        DB::rollBack();
        echo PHP_EOL . "ROLLBACK OK : aucune donnée de test conservée." . PHP_EOL;
    }
}
