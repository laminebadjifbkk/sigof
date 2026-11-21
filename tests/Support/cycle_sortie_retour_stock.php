<?php

use App\Models\Support\SupportDocumentStock;
use App\Services\Support\SupportDocumentStockService;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

$createur = 1;
$validateur = 2922;
$articleId = 32; // Disque SSD
$fournisseurId = 16; // TEWA
$employeId = 1;
$coutUnitaire = '10000.0000';

$service = app(SupportDocumentStockService::class);

$assert = function (bool $condition, string $message): void {
    if (!$condition) {
        throw new RuntimeException("ECHEC : {$message}");
    }

    echo "[OK] {$message}\n";
};

$creerDocument = function (
    string $type,
    array $ligne,
    array $entete = []
) use ($service, $createur, $validateur): SupportDocumentStock {
    $document = $service->creerDocument(array_merge([
        'date_document' => now()->toDateString(),
        'type_document' => $type,
        'motif' => 'TEST AUTOMATIQUE SORTIE ET RETOUR',
        'observations' => 'Document temporaire annulé après test.',
    ], $entete), $createur);

    $service->ajouterLigne($document, $ligne, $createur);
    $service->soumettre($document, $createur);

    return $service->valider($document, $validateur);
};

$attendreRefus = function (callable $operation, string $message): void {
    try {
        $operation();
    } catch (ValidationException | InvalidArgumentException $exception) {
        echo "[OK] {$message}\n";
        echo "     Motif : " . (
            $exception instanceof ValidationException
                ? implode(' | ', collect($exception->errors())->flatten()->all())
                : $exception->getMessage()
        ) . "\n";
        return;
    }

    throw new RuntimeException(
        "ECHEC : l'opération aurait dû être refusée : {$message}"
    );
};

DB::beginTransaction();

try {
    echo "\n========== TEST SORTIE / RETOUR STOCK ==========\n";

    // Vérification des données de départ.
    $article = DB::table('support_articles_stocks')
        ->where('id', $articleId)
        ->whereNull('deleted_at')
        ->first();

    $fournisseur = DB::table('support_fournisseurs_stocks')
        ->where('id', $fournisseurId)
        ->whereNull('deleted_at')
        ->first();

    $stock = DB::table('support_stocks')
        ->where('support_article_stock_id', $articleId)
        ->first();

    $assert($article !== null && (bool) $article->actif, 'Article SSD actif');
    $assert($fournisseur !== null, 'Fournisseur TEWA existant');
    $assert($stock !== null, 'Ligne de stock existante');

    $assert(
        (float) $stock->quantite === 0.0
        && !(bool) $stock->verrouille,
        'Stock initial nul et non verrouillé'
    );

    $assert(
        !DB::table('support_inventaire_lignes')
            ->join(
                'support_inventaires_stocks',
                'support_inventaires_stocks.id',
                '=',
                'support_inventaire_lignes.support_inventaire_id'
            )
            ->where(
                'support_inventaire_lignes.support_article_stock_id',
                $articleId
            )
            ->whereIn('support_inventaires_stocks.statut', [
                'BROUILLON',
                'EN_COURS',
                'EN_ATTENTE_VALIDATION',
                'REJETEE',
            ])
            ->exists(),
        'Aucun inventaire actif sur le SSD'
    );

    // Activation temporaire du fournisseur dans la transaction.
    DB::table('support_fournisseurs_stocks')
        ->where('id', $fournisseurId)
        ->update(['actif' => 1]);

    // 1. Réception de 10 SSD à 10 000 FCFA.
    $reception = $creerDocument(
        'RECEPTION',
        [
            'support_article_stock_id' => $articleId,
            'quantite' => '10',
            'cout_unitaire' => $coutUnitaire,
        ],
        ['support_fournisseur_stock_id' => $fournisseurId]
    );

    $stock = DB::table('support_stocks')
        ->where('support_article_stock_id', $articleId)
        ->first();

    $assert((float) $stock->quantite === 10.0, 'Réception : stock = 10');
    $assert((float) $stock->valeur_stock === 100000.0, 'Réception : valeur = 100 000 FCFA');

    // 2. Sortie de 3 SSD.
    $sortie = $creerDocument(
        'SORTIE',
        [
            'support_article_stock_id' => $articleId,
            'quantite' => '3',
        ],
        ['employee_id' => $employeId]
    );

    $ligneSortie = $sortie->lignes()->firstOrFail();

    $stock = DB::table('support_stocks')
        ->where('support_article_stock_id', $articleId)
        ->first();

    $assert((float) $stock->quantite === 7.0, 'Sortie partielle : stock = 7');
    $assert((float) $stock->valeur_stock === 70000.0, 'Sortie : valeur = 70 000 FCFA');

    // 3. Tentative de sortie excessive de 8 alors que 7 restent.
    $sortieExcessive = $service->creerDocument([
        'type_document' => 'SORTIE',
        'date_document' => now()->toDateString(),
        'motif' => 'TEST SORTIE EXCESSIVE',
        'employee_id' => $employeId,
    ], $createur);

    $service->ajouterLigne($sortieExcessive, [
        'support_article_stock_id' => $articleId,
        'quantite' => '8',
    ], $createur);

    $service->soumettre($sortieExcessive, $createur);

    $attendreRefus(
        fn () => $service->valider($sortieExcessive, $validateur),
        'Sortie excessive de 8 unités refusée'
    );

    $stock = DB::table('support_stocks')
        ->where('support_article_stock_id', $articleId)
        ->first();

    $assert((float) $stock->quantite === 7.0, 'Stock inchangé après sortie refusée');

    // 4. Retour de 2 unités sur la ligne de sortie initiale.
    $retour = $creerDocument(
        'RETOUR',
        [
            'support_article_stock_id' => $articleId,
            'quantite' => '2',
            'support_ligne_origine_id' => $ligneSortie->id,
        ]
    );

    $stock = DB::table('support_stocks')
        ->where('support_article_stock_id', $articleId)
        ->first();

    $assert((float) $stock->quantite === 9.0, 'Retour partiel : stock = 9');
    $assert((float) $stock->valeur_stock === 90000.0, 'Retour : valeur = 90 000 FCFA');
    $assert(
        (float) $stock->cout_moyen_pondere === 10000.0,
        'CMP après retour = 10 000 FCFA'
    );

    // 5. Tentative de retour supplémentaire de 2.
    // Seulement 1 unité reste retournable sur la sortie initiale.
    $retourExcessif = $service->creerDocument([
        'type_document' => 'RETOUR',
        'date_document' => now()->toDateString(),
        'motif' => 'TEST RETOUR EXCESSIF',
    ], $createur);

    $service->ajouterLigne($retourExcessif, [
        'support_article_stock_id' => $articleId,
        'quantite' => '2',
        'support_ligne_origine_id' => $ligneSortie->id,
    ], $createur);

    $service->soumettre($retourExcessif, $createur);

    $attendreRefus(
        fn () => $service->valider($retourExcessif, $validateur),
        'Retour excessif de 2 unités refusé'
    );

    $stock = DB::table('support_stocks')
        ->where('support_article_stock_id', $articleId)
        ->first();

    $assert((float) $stock->quantite === 9.0, 'Stock inchangé après retour refusé');

    // 6. Contrôle des mouvements réellement comptabilisés.
    $mouvements = DB::table('support_mouvements_stocks')
        ->whereIn('support_document_stock_id', [
            $reception->id,
            $sortie->id,
            $retour->id,
        ])
        ->orderBy('id')
        ->get();

    $assert($mouvements->count() === 3, 'Trois mouvements comptabilisés');

    $assert(
        $mouvements->pluck('type_mouvement')->all()
            === ['ENTREE', 'SORTIE', 'RETOUR'],
        'Types de mouvements conformes'
    );

    $assert(
        $mouvements->every(
            fn ($m) => (int) $m->user_create_id === $validateur
        ),
        'Mouvements attribués au validateur'
    );

    // 7. Contrôle des historiques.
    $historiques = DB::table('support_historiques_stocks')
        ->where('entite', 'DOCUMENT_STOCK')
        ->whereIn('entite_id', [
            $reception->id,
            $sortie->id,
            $retour->id,
        ])
        ->count();

    $assert($historiques >= 12, 'Historiques des trois documents présents');

    echo "\n========== BILAN DU TEST ==========\n";
    echo "Réception : {$reception->reference}\n";
    echo "Sortie : {$sortie->reference}\n";
    echo "Retour : {$retour->reference}\n";
    echo "Stock final temporaire : 9 SSD\n";
    echo "Valeur finale temporaire : 90 000 FCFA\n";
    echo "CMP final temporaire : 10 000 FCFA\n";
    echo "Sortie excessive : refusée\n";
    echo "Retour excessif : refusé\n";
    echo "Séparation créateur / validateur : respectée\n";

} catch (Throwable $exception) {
    echo "\n[ERREUR] " . $exception->getMessage() . "\n";
    throw $exception;
} finally {
    if (DB::transactionLevel() > 0) {
        DB::rollBack();
        echo "\n[OK] ROLLBACK : aucune donnée de test conservée.\n";
    }
}
