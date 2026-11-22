<?php

use App\Services\Support\SupportDocumentStockService;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\DB;

require __DIR__ . '/../../vendor/autoload.php';

$app = require __DIR__ . '/../../bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

function verifier(bool $condition, string $message): void
{
    if (!$condition) {
        throw new RuntimeException($message);
    }
}

verifier(
    DB::connection()->getDatabaseName() === 'sigof_stock_concurrency_test',
    'ABANDON : base active différente de sigof_stock_concurrency_test.'
);

$articleId = 46;
$stockId = 44;
$createurId = 2954;
$validateurId = 2955;
$employeId = 11;

$stock = DB::table('support_stocks')
    ->where('id', $stockId)
    ->where('support_article_stock_id', $articleId)
    ->first();

verifier($stock !== null, 'Stock de test introuvable.');
verifier(
    (float) $stock->quantite === 100.0,
    'Le stock initial doit être exactement de 100 unités.'
);
verifier(
    (float) $stock->cout_moyen_pondere === 10000.0,
    'CMP initial inattendu.'
);
verifier(
    (float) $stock->valeur_stock === 1000000.0,
    'Valeur initiale inattendue.'
);
verifier(
    !(bool) $stock->verrouille,
    'Le stock est verrouillé.'
);

$nbMouvements = DB::table('support_mouvements_stocks')
    ->where('support_article_stock_id', $articleId)
    ->count();

verifier(
    $nbMouvements === 0,
    'Des mouvements existent déjà pour cet article : test refusé.'
);

$nbDocuments = DB::table('support_documents_stocks')
    ->where('type_document', 'SORTIE')
    ->where('user_create_id', $createurId)
    ->count();

verifier(
    $nbDocuments === 0,
    'Des documents de sortie de test existent déjà : test refusé.'
);

$service = app(SupportDocumentStockService::class);
$documents = [];

echo "========== PREPARATION DES DOCUMENTS ==========\n";

for ($i = 1; $i <= 2; $i++) {
    $document = $service->creerDocument([
        'type_document' => 'SORTIE',
        'date_document' => now()->toDateString(),
        'employee_id' => $employeId,
        'motif' => "TEST CONCURRENCE SORTIE {$i}",
    ], $createurId);

    $service->ajouterLigne($document, [
        'support_article_stock_id' => $articleId,
        'quantite' => '60.000',
        'cout_unitaire' => '0',
        'observations' => 'Test de concurrence isolé',
    ], $createurId);

    $service->soumettre($document, $createurId);

    $documents[] = (int) $document->id;

    echo "Document {$i} soumis : ID {$document->id}\n";
}

$token = bin2hex(random_bytes(8));
$base = sys_get_temp_dir() . '/sigof_stock_conc_' . $token;
$gate = $base . '.go';

$children = [];

echo "\n========== LANCEMENT DES PROCESSUS ==========\n";

// Fermer la connexion héritée avant le fork.
DB::disconnect('mysql');

for ($i = 0; $i < 2; $i++) {
    $pid = pcntl_fork();

    if ($pid === -1) {
        throw new RuntimeException('Impossible de créer un processus enfant.');
    }

    if ($pid === 0) {
        try {
            DB::purge('mysql');
            DB::reconnect('mysql');

            file_put_contents($base . ".ready.{$i}", 'READY');

            $deadline = microtime(true) + 30;

            while (!file_exists($gate)) {
                if (microtime(true) > $deadline) {
                    throw new RuntimeException('Délai dépassé avant le départ.');
                }

                usleep(10000);
            }

            $service = app(SupportDocumentStockService::class);

            try {
                $service->valider($documents[$i], $validateurId);

                $resultat = [
                    'document' => $documents[$i],
                    'resultat' => 'VALIDEE',
                    'message' => null,
                ];
            } catch (Throwable $e) {
                $resultat = [
                    'document' => $documents[$i],
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
                    'document' => $documents[$i],
                    'resultat' => 'ERREUR_TECHNIQUE',
                    'message' => $e->getMessage(),
                ], JSON_UNESCAPED_UNICODE)
            );

            exit(1);
        }
    }

    $children[] = $pid;
}

// Attendre que les deux processus soient prêts.
$deadline = microtime(true) + 30;

for ($i = 0; $i < 2; $i++) {
    while (!file_exists($base . ".ready.{$i}")) {
        if (microtime(true) > $deadline) {
            touch($gate);

            foreach ($children as $child) {
                pcntl_waitpid($child, $status);
            }

            throw new RuntimeException(
                'Les deux processus ne sont pas prêts.'
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

    verifier(file_exists($fichier), "Résultat du processus {$i} absent.");

    $resultat = json_decode(file_get_contents($fichier), true);

    verifier(is_array($resultat), "Résultat du processus {$i} invalide.");

    $resultats[] = $resultat;
}

echo "\n========== RESULTATS DES VALIDATIONS ==========\n";

foreach ($resultats as $resultat) {
    echo "Document {$resultat['document']} : {$resultat['resultat']}\n";

    if ($resultat['message']) {
        echo "Motif : {$resultat['message']}\n";
    }
}

$nbValidees = collect($resultats)
    ->where('resultat', 'VALIDEE')
    ->count();

$nbRefusees = collect($resultats)
    ->where('resultat', 'REFUSEE')
    ->count();

$nbErreurs = collect($resultats)
    ->where('resultat', 'ERREUR_TECHNIQUE')
    ->count();

$stockFinal = DB::table('support_stocks')
    ->where('id', $stockId)
    ->first();

$mouvements = DB::table('support_mouvements_stocks')
    ->whereIn('support_document_stock_id', $documents)
    ->count();

$statuts = DB::table('support_documents_stocks')
    ->whereIn('id', $documents)
    ->orderBy('id')
    ->pluck('statut')
    ->all();

echo "\n========== CONTROLES D'INTEGRITE ==========\n";
echo "Validations acceptées : {$nbValidees}\n";
echo "Validations refusées : {$nbRefusees}\n";
echo "Erreurs techniques : {$nbErreurs}\n";
echo "Stock final : {$stockFinal->quantite}\n";
echo "Mouvements créés : {$mouvements}\n";
echo "Statuts : " . implode(' / ', $statuts) . "\n";

$succes =
    $nbValidees === 1
    && $nbRefusees === 1
    && $nbErreurs === 0
    && (float) $stockFinal->quantite === 40.0
    && (float) $stockFinal->valeur_stock === 400000.0
    && $mouvements === 1
    && count(array_filter($statuts, fn ($s) => $s === 'VALIDEE')) === 1
    && count(array_filter($statuts, fn ($s) => $s === 'EN_ATTENTE_VALIDATION')) === 1;

foreach (glob($base . '.*') ?: [] as $fichier) {
    @unlink($fichier);
}

if (!$succes) {
    throw new RuntimeException(
        'ECHEC : les invariants de concurrence ne sont pas respectés.'
    );
}

echo "\n[OK] UNE SEULE SORTIE VALIDEE.\n";
echo "[OK] AUCUN STOCK NEGATIF.\n";
echo "[OK] AUCUN MOUVEMENT EN DOUBLE.\n";
echo "[OK] TRANSACTION REFUSEE RESTEE EN ATTENTE.\n";
echo "\nTEST DE CONCURRENCE : SUCCES\n";
