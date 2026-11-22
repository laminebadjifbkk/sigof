<?php

use App\Models\Support\SupportDocumentStock;
use App\Services\Support\SupportDocumentStockService;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

$createur = 1;
$validateur = 2922;
$articleId = 32;

$service = app(SupportDocumentStockService::class);

$assert = function (bool $condition, string $message): void {
    if (!$condition) {
        throw new RuntimeException("ECHEC : {$message}");
    }
    echo "[OK] {$message}\n";
};

$creerEtValider = function (
    string $type,
    string $quantite,
    string $cout
) use ($service, $createur, $validateur, $articleId): SupportDocumentStock {
    $document = $service->creerDocument([
        'type_document' => $type,
        'date_document' => now()->toDateString(),
        'motif' => 'TEST AUTOMATIQUE AJUSTEMENTS STOCK',
        'observations' => 'Test temporaire avec rollback.',
    ], $createur);

    $service->ajouterLigne($document, [
        'support_article_stock_id' => $articleId,
        'quantite' => $quantite,
        'cout_unitaire' => $cout,
    ], $createur);

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
        "ECHEC : opération qui aurait dû être refusée : {$message}"
    );
};

DB::beginTransaction();

try {
    echo "\n========== TEST AJUSTEMENTS POSITIFS / NEGATIFS ==========\n";

    $article = DB::table('support_articles_stocks')
        ->where('id', $articleId)
        ->whereNull('deleted_at')
        ->first();

    $stock = DB::table('support_stocks')
        ->where('support_article_stock_id', $articleId)
        ->first();

    $assert($article !== null && (bool) $article->actif, 'Article SSD actif');
    $assert($stock !== null, 'Ligne de stock existante');

    $assert(
        (float) $stock->quantite === 0.0
        && (float) $stock->valeur_stock === 0.0
        && !(bool) $stock->verrouille,
        'Stock initial nul, sans valeur et non verrouillé'
    );

    // 1. Ajustement positif initial : 10 unités à 10 000 FCFA.
    $ajustementPositif1 = $creerEtValider(
        'AJUSTEMENT_POSITIF',
        '10',
        '10000'
    );

    $stock = DB::table('support_stocks')
        ->where('support_article_stock_id', $articleId)
        ->first();

    $assert((float) $stock->quantite === 10.0, 'Premier ajustement positif : quantité = 10');
    $assert((float) $stock->valeur_stock === 100000.0, 'Premier ajustement positif : valeur = 100 000 FCFA');
    $assert((float) $stock->cout_moyen_pondere === 10000.0, 'Premier ajustement positif : CMP = 10 000 FCFA');

    // 2. Deuxième ajustement : le CMP existant doit être conservé.
    $ajustementPositif2 = $creerEtValider(
        'AJUSTEMENT_POSITIF',
        '5',
        '20000'
    );

    $stock = DB::table('support_stocks')
        ->where('support_article_stock_id', $articleId)
        ->first();

    $assert((float) $stock->quantite === 15.0, 'Deuxième ajustement positif : quantité = 15');
    $assert((float) $stock->cout_moyen_pondere === 10000.0, 'CMP conservé malgré le coût indiqué de 20 000 FCFA');
    $assert((float) $stock->valeur_stock === 150000.0, 'Valeur après deuxième ajustement = 150 000 FCFA');

    // 3. Ajustement négatif partiel de 4 unités.
    $ajustementNegatif = $creerEtValider(
        'AJUSTEMENT_NEGATIF',
        '4',
        '10000'
    );

    $stock = DB::table('support_stocks')
        ->where('support_article_stock_id', $articleId)
        ->first();

    $assert((float) $stock->quantite === 11.0, 'Ajustement négatif : quantité restante = 11');
    $assert((float) $stock->valeur_stock === 110000.0, 'Ajustement négatif : valeur restante = 110 000 FCFA');
    $assert((float) $stock->cout_moyen_pondere === 10000.0, 'CMP conservé après ajustement négatif');

    // 4. Ajustement négatif excessif : 12 alors que 11 restent.
    $excessif = $service->creerDocument([
        'type_document' => 'AJUSTEMENT_NEGATIF',
        'date_document' => now()->toDateString(),
        'motif' => 'TEST AJUSTEMENT NEGATIF EXCESSIF',
    ], $createur);

    $service->ajouterLigne($excessif, [
        'support_article_stock_id' => $articleId,
        'quantite' => '12',
        'cout_unitaire' => '10000',
    ], $createur);

    $service->soumettre($excessif, $createur);

    $attendreRefus(
        fn () => $service->valider($excessif, $validateur),
        'Ajustement négatif excessif de 12 unités refusé'
    );

    $stock = DB::table('support_stocks')
        ->where('support_article_stock_id', $articleId)
        ->first();

    $assert((float) $stock->quantite === 11.0, 'Stock inchangé après ajustement excessif refusé');
    $assert((float) $stock->valeur_stock === 110000.0, 'Valeur inchangée après refus');

    // 5. Vérification des mouvements réellement comptabilisés.
    $documents = [
        $ajustementPositif1->id,
        $ajustementPositif2->id,
        $ajustementNegatif->id,
    ];

    $mouvements = DB::table('support_mouvements_stocks')
        ->whereIn('support_document_stock_id', $documents)
        ->orderBy('id')
        ->get();

    $assert($mouvements->count() === 3, 'Trois mouvements comptabilisés');

    $assert(
        $mouvements->pluck('type_mouvement')->all() === [
            'AJUSTEMENT_POSITIF',
            'AJUSTEMENT_POSITIF',
            'AJUSTEMENT_NEGATIF',
        ],
        'Types de mouvements conformes'
    );

    $assert(
        $mouvements->every(
            fn ($m) => (int) $m->user_create_id === $validateur
        ),
        'Mouvements attribués au validateur'
    );

    // 6. Contrôle des historiques.
    $historiques = DB::table('support_historiques_stocks')
        ->where('entite', 'DOCUMENT_STOCK')
        ->whereIn('entite_id', $documents)
        ->count();

    $assert($historiques >= 12, 'Historiques des trois documents présents');

    echo "\n========== BILAN DU TEST ==========\n";
    echo "Ajustement positif 1 : {$ajustementPositif1->reference}\n";
    echo "Ajustement positif 2 : {$ajustementPositif2->reference}\n";
    echo "Ajustement négatif : {$ajustementNegatif->reference}\n";
    echo "Stock final temporaire : 11 SSD\n";
    echo "Valeur finale temporaire : 110 000 FCFA\n";
    echo "CMP final temporaire : 10 000 FCFA\n";
    echo "Ajustement négatif excessif : refusé\n";

} catch (Throwable $exception) {
    echo "\n[ERREUR] " . $exception->getMessage() . "\n";
    throw $exception;
} finally {
    if (DB::transactionLevel() > 0) {
        DB::rollBack();
        echo "\n[OK] ROLLBACK : aucune donnée de test conservée.\n";
    }
}
