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

    // Référentiels fictifs
    $categorie = SupportCategorieStock::create([
        'code' => 'TEST-CYCLE',
        'libelle' => 'Catégorie test cycle',
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

    // Création du document
    $document = $service->creerDocument([
        'type_document' => 'RECEPTION',
        'support_fournisseur_stock_id' => $fournisseur->id,
        'date_document' => now()->toDateString(),
        'motif' => 'Test du cycle de rejet et correction',
        'observations' => 'Document fictif',
    ], $createur);

    // Ajout de la ligne
    $ligne = $service->ajouterLigne($document, [
        'support_article_stock_id' => $article->id,
        'quantite' => '10.000',
        'cout_unitaire' => '1250.0000',
        'observations' => 'Ligne initiale',
    ], $createur);

    // Première soumission
    $document = $service->soumettre($document, $createur);

    if ($document->statut !== 'EN_ATTENTE_VALIDATION') {
        throw new RuntimeException('Échec de la première soumission.');
    }

    dump([
        'etape' => 'PREMIERE SOUMISSION',
        'statut' => $document->statut,
        'reference' => $document->reference,
    ]);

    // Rejet par un autre utilisateur
    $document = $service->rejeter(
        $document,
        'Veuillez corriger le coût unitaire.',
        $validateur
    );

    if ($document->statut !== 'REJETEE') {
        throw new RuntimeException('Échec du rejet.');
    }

    dump([
        'etape' => 'REJET',
        'statut' => $document->statut,
        'motif' => $document->motif_rejet,
    ]);

    // Correction par le créateur
    $ligne = $service->modifierLigne($document, $ligne->id, [
        'support_article_stock_id' => $article->id,
        'quantite' => '12.000',
        'cout_unitaire' => '1300.0000',
        'observations' => 'Ligne corrigée après rejet',
    ], $createur);

    dump([
        'etape' => 'CORRECTION',
        'quantite' => $ligne->quantite,
        'cout_unitaire' => $ligne->cout_unitaire,
    ]);

    // Nouvelle soumission
    $document = $service->soumettre($document, $createur);
    $document->refresh();

    if ($document->statut !== 'EN_ATTENTE_VALIDATION') {
        throw new RuntimeException('Échec de la nouvelle soumission.');
    }

    if ($document->motif_rejet !== null) {
        throw new RuntimeException('Le motif de rejet courant n’a pas été effacé.');
    }

    $historique = DB::table('support_historiques_stocks')
        ->where('entite', 'DOCUMENT_STOCK')
        ->where('entite_id', $document->id)
        ->orderBy('id')
        ->get(['action', 'anciennes_valeurs', 'nouvelles_valeurs']);

    dump([
        'etape' => 'NOUVELLE SOUMISSION',
        'statut' => $document->statut,
        'motif_rejet_courant' => $document->motif_rejet,
        'historique' => $historique,
        'mouvements_crees' => DB::table('support_mouvements_stocks')
            ->where('support_document_stock_id', $document->id)
            ->count(),
    ]);

    dump('TEST FONCTIONNEL : SUCCES');

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
