<?php

use App\Services\Support\SupportDocumentStockService;
use App\Services\Support\SupportInventaireStockService;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\DB;

require __DIR__ . '/../../vendor/autoload.php';

$app = require __DIR__ . '/../../bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

function verifierScenario3(bool $condition, string $message): void
{
    if (!$condition) {
        throw new RuntimeException($message);
    }
}

verifierScenario3(
    DB::connection()->getDatabaseName() === 'sigof_stock_concurrency_test',
    'ABANDON : base active différente de sigof_stock_concurrency_test.'
);

if (!function_exists('pcntl_fork')) {
    throw new RuntimeException('L’extension PHP pcntl est indispensable.');
}

$articleId = 46;
$stockId = 44;
$createurId = 2954;
$validateurId = 2955;
$employeId = 11;

$stock = DB::table('support_stocks')
    ->where('id', $stockId)
    ->where('support_article_stock_id', $articleId)
    ->first();

verifierScenario3($stock !== null, 'Stock de test introuvable.');
verifierScenario3(
    (float) $stock->quantite === 80.0,
    'Le stock initial attendu est de 80 unités.'
);
verifierScenario3(
    (float) $stock->valeur_stock === 800000.0,
    'La valeur initiale attendue est de 800 000 FCFA.'
);
verifierScenario3(
    !(bool) $stock->verrouille
        && $stock->support_inventaire_id === null,
    'Le stock est déjà verrouillé.'
);

$article = DB::table('support_articles_stocks')
    ->where('id', $articleId)
    ->where('actif', 1)
    ->first();

verifierScenario3($article !== null, 'Article de test absent ou inactif.');

echo "========== PREPARATION DE L'INVENTAIRE ==========\n";

$inventaireService = app(SupportInventaireStockService::class);

$inventaire = $inventaireService->creer([
    'libelle' => 'TEST CONCURRENCE INVENTAIRE / SORTIE',
    'description' => 'Test concurrent isolé sur article 46.',
    'date_debut' => now()->toDateTimeString(),
    'observations' => 'Test technique sur base isolée.',
], $createurId);

$inventaireId = (int) $inventaire->id;

$inventaireService->demarrer(
    $inventaireId,
    [$articleId],
    $createurId
);

$inventaireService->enregistrerComptage(
    $inventaireId,
    $articleId,
    '80.000',
    'Comptage physique du test concurrent.',
    $createurId
);

$inventaireService->soumettre($inventaireId, $createurId);

$verrou = DB::table('support_stocks')
    ->where('id', $stockId)
    ->first();

verifierScenario3(
    (bool) $verrou->verrouille
        && (int) $verrou->support_inventaire_id === $inventaireId,
    'Le stock n’est pas verrouillé par le nouvel inventaire.'
);

$ligneInventaire = DB::table('support_inventaire_lignes')
    ->where('support_inventaire_id', $inventaireId)
    ->where('support_article_stock_id', $articleId)
    ->first();

verifierScenario3(
    $ligneInventaire !== null
        && (float) $ligneInventaire->quantite_theorique === 80.0
        && (float) $ligneInventaire->quantite_comptee === 80.0,
    'Les quantités théorique et comptée doivent être égales à 80.'
);

echo "Inventaire créé : {$inventaireId}\n";
echo "Statut : EN_ATTENTE_VALIDATION\n";
echo "Stock verrouillé : OUI\n";
echo "Quantité théorique / comptée : 80 / 80\n";

echo "\n========== PREPARATION DE LA SORTIE ==========\n";

$documentService = app(SupportDocumentStockService::class);

$document = $documentService->creerDocument([
    'type_document' => 'SORTIE',
    'date_document' => now()->toDateString(),
    'employee_id' => $employeId,
    'motif' => 'TEST CONCURRENCE INVENTAIRE / SORTIE',
    'observations' => 'Sortie concurrente de 10 unités.',
], $createurId);

$documentService->ajouterLigne($document, [
    'support_article_stock_id' => $articleId,
    'quantite' => '10.000',
    'cout_unitaire' => '0',
    'observations' => 'Test de concurrence isolé.',
], $createurId);

$documentService->soumettre($document, $createurId);

$documentId = (int) $document->id;

echo "Document de sortie créé : {$documentId}\n";
echo "Quantité demandée : 10\n";

$token = bin2hex(random_bytes(8));
$base = sys_get_temp_dir() . '/sigof_stock_inv_sortie_' . $token;
$gate = $base . '.go';

$actions = [
    [
        'type' => 'INVENTAIRE',
        'id' => $inventaireId,
    ],
    [
        'type' => 'SORTIE',
        'id' => $documentId,
    ],
];

$children = [];

echo "\n========== LANCEMENT DES DEUX PROCESSUS ==========\n";

// Chaque processus doit disposer de sa propre connexion MySQL.
DB::disconnect('mysql');

for ($i = 0; $i < 2; $i++) {
    $pid = pcntl_fork();

    if ($pid === -1) {
        throw new RuntimeException('Impossible de créer un processus enfant.');
    }

    if ($pid === 0) {
        $action = $actions[$i];

        try {
            DB::purge('mysql');
            DB::reconnect('mysql');

            file_put_contents($base . ".ready.{$i}", 'READY');

            $deadline = microtime(true) + 30;

            while (!file_exists($gate)) {
                if (microtime(true) > $deadline) {
                    throw new RuntimeException(
                        'Délai dépassé avant le départ simultané.'
                    );
                }

                usleep(10000);
            }

            try {
                if ($action['type'] === 'INVENTAIRE') {
                    app(SupportInventaireStockService::class)->valider(
                        $action['id'],
                        $validateurId
                    );
                } else {
                    app(SupportDocumentStockService::class)->valider(
                        $action['id'],
                        $validateurId
                    );
                }

                $resultat = [
                    'type' => $action['type'],
                    'id' => $action['id'],
                    'resultat' => 'VALIDEE',
                    'message' => null,
                ];
            } catch (Throwable $e) {
                $resultat = [
                    'type' => $action['type'],
                    'id' => $action['id'],
                    'resultat' => 'REFUSEE',
                    'message' => $e->getMessage(),
                ];
            }

            file_put_contents(
                $base . ".result.{$i}",
                json_encode($resultat, JSON_UNESCAPED_UNICODE)
            );

            exit(0);
        } catch (Throwable $e) {
            file_put_contents(
                $base . ".result.{$i}",
                json_encode([
                    'type' => $action['type'],
                    'id' => $action['id'],
                    'resultat' => 'ERREUR_TECHNIQUE',
                    'message' => $e->getMessage(),
                ], JSON_UNESCAPED_UNICODE)
            );

            exit(1);
        }
    }

    $children[] = $pid;
}

// Attendre que les deux connexions soient prêtes.
$deadline = microtime(true) + 30;

for ($i = 0; $i < 2; $i++) {
    while (!file_exists($base . ".ready.{$i}")) {
        if (microtime(true) > $deadline) {
            touch($gate);

            foreach ($children as $child) {
                pcntl_waitpid($child, $status);
            }

            throw new RuntimeException(
                "Le processus {$i} n'est pas prêt."
            );
        }

        usleep(10000);
    }
}

echo "Deux processus prêts. Départ simultané.\n";
touch($gate);

foreach ($children as $child) {
    pcntl_waitpid($child, $status);
}

DB::reconnect('mysql');

$resultats = [];

for ($i = 0; $i < 2; $i++) {
    $fichier = $base . ".result.{$i}";

    verifierScenario3(
        file_exists($fichier),
        "Résultat du processus {$i} absent."
    );

    $resultat = json_decode(file_get_contents($fichier), true);

    verifierScenario3(
        is_array($resultat),
        "Résultat du processus {$i} invalide."
    );

    $resultats[] = $resultat;

    @unlink($fichier);
    @unlink($base . ".ready.{$i}");
}

@unlink($gate);

echo "\n========== RESULTATS DES VALIDATIONS ==========\n";

foreach ($resultats as $resultat) {
    echo "{$resultat['type']} {$resultat['id']} : "
        . "{$resultat['resultat']}\n";

    if ($resultat['message']) {
        echo "Motif : {$resultat['message']}\n";
    }
}

$erreurs = collect($resultats)
    ->where('resultat', 'ERREUR_TECHNIQUE')
    ->count();

$inventaireResultat = collect($resultats)
    ->firstWhere('type', 'INVENTAIRE');

$sortieResultat = collect($resultats)
    ->firstWhere('type', 'SORTIE');

verifierScenario3($erreurs === 0, 'Une erreur technique est survenue.');

verifierScenario3(
    $inventaireResultat['resultat'] === 'VALIDEE',
    'L’inventaire aurait dû être validé.'
);

$inventaireFinal = DB::table('support_inventaires_stocks')
    ->where('id', $inventaireId)
    ->first();

$documentFinal = DB::table('support_documents_stocks')
    ->where('id', $documentId)
    ->first();

$stockFinal = DB::table('support_stocks')
    ->where('id', $stockId)
    ->first();

$mouvementsSortie = DB::table('support_mouvements_stocks')
    ->where('support_document_stock_id', $documentId)
    ->count();

echo "\n========== CONTROLES D'INTEGRITE ==========\n";
echo "Statut inventaire : {$inventaireFinal->statut}\n";
echo "Statut sortie : {$documentFinal->statut}\n";
echo "Résultat sortie : {$sortieResultat['resultat']}\n";
echo "Stock final : {$stockFinal->quantite}\n";
echo "Valeur finale : {$stockFinal->valeur_stock} FCFA\n";
echo "Verrou actif : " . ($stockFinal->verrouille ? 'OUI' : 'NON') . "\n";
echo "Mouvements de la sortie : {$mouvementsSortie}\n";

verifierScenario3(
    $inventaireFinal->statut === 'VALIDEE',
    'Le statut final de l’inventaire est incorrect.'
);

verifierScenario3(
    !(bool) $stockFinal->verrouille
        && $stockFinal->support_inventaire_id === null,
    'Le stock est resté verrouillé après validation.'
);

if ($sortieResultat['resultat'] === 'VALIDEE') {
    verifierScenario3(
        $documentFinal->statut === 'VALIDEE',
        'La sortie devrait être validée.'
    );

    verifierScenario3(
        $mouvementsSortie === 1,
        'La sortie validée doit avoir exactement un mouvement.'
    );

    verifierScenario3(
        (float) $stockFinal->quantite === 70.0,
        'Après inventaire puis sortie, le stock attendu est de 70.'
    );

    verifierScenario3(
        (float) $stockFinal->valeur_stock === 700000.0,
        'La valeur attendue après sortie est de 700 000 FCFA.'
    );

    echo "[OK] INVENTAIRE VALIDE AVANT OU AVEC SORTIE DIFFEREE.\n";
    echo "[OK] SORTIE EXECUTEE APRES DEVERROUILLAGE.\n";
} else {
    verifierScenario3(
        $documentFinal->statut === 'EN_ATTENTE_VALIDATION',
        'Une sortie refusée doit rester en attente de validation.'
    );

    verifierScenario3(
        $mouvementsSortie === 0,
        'Une sortie refusée ne doit créer aucun mouvement.'
    );

    verifierScenario3(
        (float) $stockFinal->quantite === 80.0,
        'Le stock doit rester à 80 si la sortie est refusée.'
    );

    verifierScenario3(
        (float) $stockFinal->valeur_stock === 800000.0,
        'La valeur doit rester à 800 000 FCFA si la sortie est refusée.'
    );

    verifierScenario3(
        str_contains(
            mb_strtolower($sortieResultat['message'] ?? ''),
            'verrouill'
        ),
        'Le refus de la sortie ne semble pas provenir du verrou d’inventaire.'
    );

    echo "[OK] SORTIE BLOQUEE PAR LE VERROU D'INVENTAIRE.\n";
    echo "[OK] AUCUN MOUVEMENT DE SORTIE CREE.\n";
}

echo "[OK] INVENTAIRE VALIDE.\n";
echo "[OK] STOCK DEVERROUILLE.\n";
echo "[OK] QUANTITE ET VALORISATION COHERENTES.\n";
echo "\nTEST DE CONCURRENCE INVENTAIRE / SORTIE : SUCCES\n";
