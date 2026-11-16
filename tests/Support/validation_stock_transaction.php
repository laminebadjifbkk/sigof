<?php

use Illuminate\Support\Facades\DB;
use App\Services\Support\SupportDocumentStockService;
use App\Models\Support\SupportCategorieStock;
use App\Models\Support\SupportUniteStock;
use App\Models\Support\SupportFournisseurStock;
use App\Models\Support\SupportArticleStock;

$transactionOuverte = false;

try {
    DB::beginTransaction();
    $transactionOuverte = true;

    $createur = 1;
    $validateur = 2;

    $categorie = SupportCategorieStock::create([
        'code' => 'TEST-CYCLE',
        'libelle' => 'Catégorie test',
        'actif' => true,
        'user_create_id' => $createur,
    ]);

    $unite = SupportUniteStock::create([
        'code' => 'TEST-U',
        'libelle' => 'Unité test',
        'symbole' => 'u',
        'autorise_decimales' => true,
        'actif' => true,
        'user_create_id' => $createur,
    ]);

    $fournisseur = SupportFournisseurStock::create([
        'raison_sociale' => 'FOURNISSEUR TEST CYCLE',
        'actif' => true,
        'user_create_id' => $createur,
    ]);

    $article = SupportArticleStock::create([
        'reference' => 'TEST-CYCLE-001',
        'designation' => 'Article fictif de test',
        'support_categorie_stock_id' => $categorie->id,
        'support_unite_stock_id' => $unite->id,
        'stock_minimum' => '0.000',
        'actif' => true,
        'user_create_id' => $createur,
    ]);

    $service = app(SupportDocumentStockService::class);

    $document = $service->creerDocument([
        'type_document' => 'RECEPTION',
        'support_fournisseur_stock_id' => $fournisseur->id,
        'date_document' => now()->toDateString(),
        'motif' => 'Test validation réception',
        'observations' => 'Test transactionnel sans conservation',
    ], $createur);

    $service->ajouterLigne($document, [
        'support_article_stock_id' => $article->id,
        'quantite' => '12.000',
        'cout_unitaire' => '1300.0000',
        'observations' => 'Réception test',
    ], $createur);

    $document = $service->soumettre($document, $createur);

    dump([
        'avant_validation' => $document->statut,
        'reference' => $document->reference,
    ]);

    // Validation par un utilisateur différent du créateur
    $document = $service->valider($document, $validateur);
    $document->refresh();

    $stock = DB::table('support_stocks')
        ->where('support_article_stock_id', $article->id)
        ->first();

    $mouvements = DB::table('support_mouvements_stocks')
        ->where('support_document_stock_id', $document->id)
        ->get();

    dump([
        'document_statut' => $document->statut,
        'createur_id' => $document->user_create_id,
        'validateur_id' => $document->user_validation_id,
        'stock_quantite' => $stock?->quantite,
        'stock_cmp' => $stock?->cout_moyen_pondere,
        'stock_valeur' => $stock?->valeur_stock,
        'nombre_mouvements' => $mouvements->count(),
        'mouvements' => $mouvements,
    ]);

    if ($document->statut !== 'VALIDEE') {
        throw new RuntimeException('Le document n’est pas VALIDEE.');
    }

    if (!$stock || (string) $stock->quantite !== '12.000') {
        throw new RuntimeException('Quantité de stock incorrecte.');
    }

    if ((string) $stock->cout_moyen_pondere !== '1300.0000') {
        throw new RuntimeException('CMP incorrect.');
    }

    if ((string) $stock->valeur_stock !== '15600.0000') {
        throw new RuntimeException('Valeur du stock incorrecte.');
    }

    if ($mouvements->count() !== 1) {
        throw new RuntimeException('Le nombre de mouvements est incorrect.');
    }

    dump('TEST VALIDATION ET MOUVEMENT : SUCCES');

} catch (Throwable $e) {
    dump([
        'test' => 'ECHEC',
        'erreur' => get_class($e),
        'message' => $e->getMessage(),
        'fichier' => $e->getFile(),
        'ligne' => $e->getLine(),
    ]);
} finally {
    if ($transactionOuverte && DB::transactionLevel() > 0) {
        DB::rollBack();
        dump('ROLLBACK OK : aucune donnée de test conservée.');
    }
}
