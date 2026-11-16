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

    // Fonction utilitaire : créer, soumettre et valider un document.
    $executerDocument = function (
        string $type,
        array $entete,
        array $donneesLigne
    ) use ($service, $createur, $validateur) {
        $document = $service->creerDocument(array_merge([
            'type_document' => $type,
            'date_document' => now()->toDateString(),
            'motif' => 'Test transactionnel stock',
            'observations' => 'Données fictives',
        ], $entete), $createur);

        $ligne = $service->ajouterLigne(
            $document,
            $donneesLigne,
            $createur
        );

        $service->soumettre($document, $createur);
        $document = $service->valider($document, $validateur);

        return [$document, $ligne];
    };

    $lireStock = function () use ($article) {
        return DB::table('support_stocks')
            ->where('support_article_stock_id', $article->id)
            ->first();
    };

    // 1. Réception : 12 unités à 1 300 FCFA.
    [$reception] = $executerDocument(
        'RECEPTION',
        ['support_fournisseur_stock_id' => $fournisseur->id],
        [
            'support_article_stock_id' => $article->id,
            'quantite' => '12.000',
            'cout_unitaire' => '1300.0000',
        ]
    );

    $stock = $lireStock();

    dump([
        'etape' => 'RECEPTION',
        'statut' => $reception->statut,
        'quantite' => $stock->quantite,
        'cmp' => $stock->cout_moyen_pondere,
        'valeur' => $stock->valeur_stock,
    ]);

    // 2. Sortie partielle : 5 unités vers l'employé 2.
    [$sortie, $ligneSortie] = $executerDocument(
        'SORTIE',
        ['employee_id' => 2],
        [
            'support_article_stock_id' => $article->id,
            'quantite' => '5.000',
        ]
    );

    $stock = $lireStock();

    dump([
        'etape' => 'SORTIE PARTIELLE',
        'statut' => $sortie->statut,
        'quantite' => $stock->quantite,
        'cmp' => $stock->cout_moyen_pondere,
        'valeur' => $stock->valeur_stock,
    ]);

    // 3. Retour de 2 unités sur la ligne de sortie initiale.
    [$retour] = $executerDocument(
        'RETOUR',
        [],
        [
            'support_article_stock_id' => $article->id,
            'support_ligne_origine_id' => $ligneSortie->id,
            'quantite' => '2.000',
        ]
    );

    $stock = $lireStock();

    dump([
        'etape' => 'RETOUR PARTIEL',
        'statut' => $retour->statut,
        'quantite' => $stock->quantite,
        'cmp' => $stock->cout_moyen_pondere,
        'valeur' => $stock->valeur_stock,
    ]);

    // 4. Tentative de retour excessif : 4 unités.
    // Seulement 3 unités restent retournables sur la sortie de 5.
    $documentExcessif = $service->creerDocument([
        'type_document' => 'RETOUR',
        'date_document' => now()->toDateString(),
        'motif' => 'Test de dépassement du retour',
    ], $createur);

    $service->ajouterLigne($documentExcessif, [
        'support_article_stock_id' => $article->id,
        'support_ligne_origine_id' => $ligneSortie->id,
        'quantite' => '4.000',
    ], $createur);

    $service->soumettre($documentExcessif, $createur);

    $retourExcessifRefuse = false;

    try {
        $service->valider($documentExcessif, $validateur);
    } catch (Throwable $e) {
        $retourExcessifRefuse = true;

        dump([
            'etape' => 'RETOUR EXCESSIF',
            'refuse' => true,
            'motif' => $e->getMessage(),
        ]);
    }

    if (!$retourExcessifRefuse) {
        throw new RuntimeException(
            'Un retour supérieur au solde autorisé a été accepté.'
        );
    }

    // 5. Sortie totale du stock restant : 9 unités.
    [$sortieTotale] = $executerDocument(
        'SORTIE',
        ['employee_id' => 2],
        [
            'support_article_stock_id' => $article->id,
            'quantite' => '9.000',
        ]
    );

    $stock = $lireStock();

    $mouvements = DB::table('support_mouvements_stocks')
        ->where('support_article_stock_id', $article->id)
        ->orderBy('id')
        ->get([
            'type_mouvement',
            'quantite',
            'cout_unitaire',
            'valeur_mouvement',
            'quantite_avant',
            'quantite_apres',
        ]);

    dump([
        'etape' => 'SORTIE TOTALE',
        'statut' => $sortieTotale->statut,
        'quantite_finale' => $stock->quantite,
        'cmp_final' => $stock->cout_moyen_pondere,
        'valeur_finale' => $stock->valeur_stock,
        'mouvements' => $mouvements,
    ]);

    if ((string) $stock->quantite !== '0.000') {
        throw new RuntimeException('Le stock final devrait être nul.');
    }

    if ((string) $stock->valeur_stock !== '0.0000') {
        throw new RuntimeException('La valeur finale devrait être nulle.');
    }

    if ($mouvements->count() !== 4) {
        throw new RuntimeException('Le nombre de mouvements devrait être 4.');
    }

    dump('TEST SORTIES / RETOURS : SUCCES');

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
