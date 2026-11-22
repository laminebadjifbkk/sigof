<?php

use App\Models\Support\SupportDocumentStock;
use App\Services\Support\SupportDocumentStockService;
use Illuminate\Support\Facades\DB;

require __DIR__ . '/../../vendor/autoload.php';

$app = require __DIR__ . '/../../bootstrap/app.php';

$kernel = $app->make(
    \Illuminate\Contracts\Console\Kernel::class
);

$kernel->bootstrap();
$createur = 1;
$validateur = 2922;
$articleId = 32;
$fournisseurId = 16;
$employeId = 2;
$cout = '10000.0000';

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
        'motif' => 'TEST TRANSVERSAL COMPLET DU STOCK',
        'observations' => 'Test temporaire annulé après vérification.',
    ], $entete), $createur);

    $service->ajouterLigne($document, $ligne, $createur);
    $service->soumettre($document, $createur);

    return $service->valider($document, $validateur);
};

$verifierStock = function (
    float $quantite,
    float $valeur,
    string $etape
) use ($assert, $articleId): void {
    $stock = DB::table('support_stocks')
        ->where('support_article_stock_id', $articleId)
        ->first();

    $assert(
        abs((float) $stock->quantite - $quantite) < 0.0001,
        "{$etape} : quantité = {$quantite}"
    );

    $assert(
        abs((float) $stock->valeur_stock - $valeur) < 0.0001,
        "{$etape} : valeur = " . number_format($valeur, 0, ',', ' ') . ' FCFA'
    );

    $assert(
        abs((float) $stock->cout_moyen_pondere - 10000.0) < 0.0001,
        "{$etape} : CMP = 10 000 FCFA"
    );

    $assert(!(bool) $stock->verrouille, "{$etape} : stock non verrouillé");
};

DB::beginTransaction();

try {
    echo "\n========== TEST TRANSVERSAL COMPLET DU STOCK ==========\n";

    // 0. Contrôle des données initiales.
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
        abs((float) $stock->quantite) < 0.0001
        && abs((float) $stock->valeur_stock) < 0.0001
        && abs((float) $stock->cout_moyen_pondere) < 0.0001
        && !(bool) $stock->verrouille,
        'Stock initial nul, sans valeur et non verrouillé'
    );

    $inventaireActif = DB::table('support_inventaire_lignes')
        ->join(
            'support_inventaires_stocks',
            'support_inventaires_stocks.id',
            '=',
            'support_inventaire_lignes.support_inventaire_id'
        )
        ->where('support_inventaire_lignes.support_article_stock_id', $articleId)
        ->whereIn('support_inventaires_stocks.statut', [
            'BROUILLON',
            'EN_COURS',
            'EN_ATTENTE_VALIDATION',
            'REJETEE',
        ])
        ->exists();

    $assert(!$inventaireActif, 'Aucun inventaire actif sur le SSD');

    // Activation temporaire de TEWA dans la transaction.
    DB::table('support_fournisseurs_stocks')
        ->where('id', $fournisseurId)
        ->update(['actif' => 1]);

    // 1. RECEPTION : +20 SSD à 10 000 FCFA.
    $reception = $creerDocument(
        'RECEPTION',
        [
            'support_article_stock_id' => $articleId,
            'quantite' => '20',
            'cout_unitaire' => $cout,
        ],
        ['support_fournisseur_stock_id' => $fournisseurId]
    );

    $verifierStock(20, 200000, 'Après réception');

    // 2. SORTIE : -5 SSD.
    $sortie = $creerDocument(
        'SORTIE',
        [
            'support_article_stock_id' => $articleId,
            'quantite' => '5',
        ],
        ['employee_id' => $employeId]
    );

    $ligneSortie = $sortie->lignes()->firstOrFail();

    $verifierStock(15, 150000, 'Après sortie');

    // 3. RETOUR : +2 SSD sur la sortie précédente.
    $retour = $creerDocument(
        'RETOUR',
        [
            'support_article_stock_id' => $articleId,
            'quantite' => '2',
            'support_ligne_origine_id' => $ligneSortie->id,
        ]
    );

    $verifierStock(17, 170000, 'Après retour');

    // 4. AJUSTEMENT POSITIF : +3 SSD.
    $ajustementPositif = $creerDocument(
        'AJUSTEMENT_POSITIF',
        [
            'support_article_stock_id' => $articleId,
            'quantite' => '3',
            'cout_unitaire' => '20000',
        ]
    );

    $verifierStock(20, 200000, 'Après ajustement positif');

    // 5. AJUSTEMENT NEGATIF : -4 SSD.
    $ajustementNegatif = $creerDocument(
        'AJUSTEMENT_NEGATIF',
        [
            'support_article_stock_id' => $articleId,
            'quantite' => '4',
        ]
    );

    $verifierStock(16, 160000, 'Après ajustement négatif');

    $documents = [
        $reception,
        $sortie,
        $retour,
        $ajustementPositif,
        $ajustementNegatif,
    ];

    $ids = array_map(fn ($document) => $document->id, $documents);

    // 6. Vérification des statuts et de la séparation des rôles.
    $assert(
        collect($documents)->every(
            fn ($document) => $document->statut === 'VALIDEE'
        ),
        'Les cinq documents sont validés'
    );

    $assert(
        collect($documents)->every(
            fn ($document) =>
                (int) $document->user_create_id === $createur
                && (int) $document->user_validation_id === $validateur
        ),
        'Créateur et validateur distincts sur les cinq documents'
    );

    // 7. Vérification des mouvements et de leur chronologie.
    $mouvements = DB::table('support_mouvements_stocks')
        ->whereIn('support_document_stock_id', $ids)
        ->orderBy('id')
        ->get()
        ->values();

    $assert($mouvements->count() === 5, 'Exactement cinq mouvements générés');

    $attendus = [
        [
            'type' => 'ENTREE',
            'quantite' => 20,
            'avant' => 0,
            'apres' => 20,
            'cout' => 10000,
            'valeur' => 200000,
        ],
        [
            'type' => 'SORTIE',
            'quantite' => 5,
            'avant' => 20,
            'apres' => 15,
            'cout' => 10000,
            'valeur' => 50000,
        ],
        [
            'type' => 'RETOUR',
            'quantite' => 2,
            'avant' => 15,
            'apres' => 17,
            'cout' => 10000,
            'valeur' => 20000,
        ],
        [
            'type' => 'AJUSTEMENT_POSITIF',
            'quantite' => 3,
            'avant' => 17,
            'apres' => 20,
            'cout' => 10000,
            'valeur' => 30000,
        ],
        [
            'type' => 'AJUSTEMENT_NEGATIF',
            'quantite' => 4,
            'avant' => 20,
            'apres' => 16,
            'cout' => 10000,
            'valeur' => 40000,
        ],
    ];

    foreach ($attendus as $index => $attendu) {
        $mouvement = $mouvements[$index];

        $assert(
            $mouvement->type_mouvement === $attendu['type'],
            'Mouvement ' . ($index + 1) . ' : type conforme'
        );

        foreach ([
            'quantite' => 'quantite',
            'quantite_avant' => 'avant',
            'quantite_apres' => 'apres',
            'cout_unitaire' => 'cout',
            'valeur_mouvement' => 'valeur',
        ] as $champ => $cle) {
            $assert(
                abs((float) $mouvement->{$champ} - $attendu[$cle]) < 0.0001,
                'Mouvement ' . ($index + 1) . " : {$champ} conforme"
            );
        }

        $assert(
            (int) $mouvement->user_create_id === $validateur,
            'Mouvement ' . ($index + 1) . ' attribué au validateur'
        );
    }

    // 8. Vérification des rattachements documents / mouvements.
    foreach ($documents as $index => $document) {
        $mouvement = $mouvements[$index];

        $assert(
            (int) $mouvement->support_document_stock_id === (int) $document->id,
            'Document ' . ($index + 1) . ' correctement rattaché à son mouvement'
        );

        $assert(
            (int) $mouvement->support_article_stock_id === $articleId,
            'Mouvement ' . ($index + 1) . ' rattaché au SSD'
        );
    }

    $assert(
        (int) $mouvements[2]->support_ligne_document_stock_id > 0,
        'Le retour possède une ligne de mouvement'
    );

    // 9. Rapprochement indépendant du solde à partir des mouvements.
    $quantiteRecalculee = 0.0;
    $valeurRecalculee = 0.0;

    foreach ($mouvements as $mouvement) {
        $quantiteRecalculee = (float) $mouvement->quantite_apres;
        $valeurRecalculee = match ($mouvement->type_mouvement) {
            'ENTREE', 'RETOUR', 'AJUSTEMENT_POSITIF' =>
                $valeurRecalculee + (float) $mouvement->valeur_mouvement,
            'SORTIE', 'AJUSTEMENT_NEGATIF' =>
                $valeurRecalculee - (float) $mouvement->valeur_mouvement,
            default => throw new RuntimeException('Type de mouvement inattendu.'),
        };
    }

    $assert(abs($quantiteRecalculee - 16) < 0.0001, 'Quantité recalculée depuis les mouvements = 16');
    $assert(abs($valeurRecalculee - 160000) < 0.0001, 'Valeur recalculée depuis les mouvements = 160 000 FCFA');

    // 10. Contrôle des historiques.
    $historiques = DB::table('support_historiques_stocks')
        ->where('entite', 'DOCUMENT_STOCK')
        ->whereIn('entite_id', $ids)
        ->count();

    $assert($historiques >= 20, 'Historiques présents pour les cinq documents');

    foreach ($ids as $id) {
        $assert(
            DB::table('support_historiques_stocks')
                ->where('entite', 'DOCUMENT_STOCK')
                ->where('entite_id', $id)
                ->exists(),
            "Historique présent pour le document ID {$id}"
        );
    }

    echo "\n========== BILAN DU TEST TRANSVERSAL ==========\n";

    foreach ($documents as $document) {
        echo "{$document->type_document} : {$document->reference}\n";
    }

    echo "Mouvements vérifiés : 5\n";
    echo "Stock final temporaire : 16 SSD\n";
    echo "Valeur finale temporaire : 160 000 FCFA\n";
    echo "CMP final temporaire : 10 000 FCFA\n";
    echo "Créateur : {$createur} / Validateur : {$validateur}\n";
    echo "Rapprochement des mouvements : conforme\n";

} catch (Throwable $exception) {
    echo "\n[ERREUR] " . $exception->getMessage() . "\n";
    throw $exception;
} finally {
    if (DB::transactionLevel() > 0) {
        DB::rollBack();
        echo "\n[OK] ROLLBACK : aucune donnée de test conservée.\n";
    }
}
