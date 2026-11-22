<?php

use App\Services\Support\SupportDocumentStockService;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\DB;

require __DIR__ . '/../../vendor/autoload.php';

$app = require __DIR__ . '/../../bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

function verifierRetour(bool $condition, string $message): void
{
    if (!$condition) {
        throw new RuntimeException($message);
    }
}

verifierRetour(
    DB::connection()->getDatabaseName() === 'sigof_stock_concurrency_test',
    'ABANDON : mauvaise base de données.'
);

$articleId = 46;
$stockId = 44;
$sortieId = 89;
$ligneOrigineId = 100;
$createurId = 2954;
$validateurId = 2955;

$origine = DB::table('support_lignes_documents_stocks as l')
    ->join('support_documents_stocks as d', 'd.id', '=', 'l.support_document_stock_id')
    ->join('support_mouvements_stocks as m', 'm.support_ligne_document_stock_id', '=', 'l.id')
    ->where('d.id', $sortieId)
    ->where('l.id', $ligneOrigineId)
    ->where('l.support_article_stock_id', $articleId)
    ->where('d.type_document', 'SORTIE')
    ->where('d.statut', 'VALIDEE')
    ->select('l.quantite', 'm.quantite as quantite_mouvement')
    ->first();

verifierRetour($origine !== null, 'Sortie d’origine introuvable ou invalide.');
verifierRetour(
    (float) $origine->quantite === 60.0
    && (float) $origine->quantite_mouvement === 60.0,
    'La quantité de la sortie d’origine doit être de 60.'
);

$stock = DB::table('support_stocks')->where('id', $stockId)->first();

verifierRetour($stock !== null, 'Stock introuvable.');
verifierRetour(
    (float) $stock->quantite === 40.0,
    'Le stock initial doit être de 40 unités.'
);
verifierRetour(
    !(bool) $stock->verrouille,
    'Le stock est verrouillé.'
);

$nbRetours = DB::table('support_mouvements_stocks as m')
    ->join('support_lignes_documents_stocks as l', 'l.id', '=', 'm.support_ligne_document_stock_id')
    ->where('l.support_ligne_origine_id', $ligneOrigineId)
    ->count();

verifierRetour($nbRetours === 0, 'Un retour existe déjà pour cette sortie.');

$service = app(SupportDocumentStockService::class);
$documents = [];

echo "========== PREPARATION DES RETOURS ==========\n";

for ($i = 1; $i <= 2; $i++) {
    $document = $service->creerDocument([
        'type_document' => 'RETOUR',
        'date_document' => now()->toDateString(),
        'motif' => "TEST CONCURRENCE RETOUR {$i}",
    ], $createurId);

    $service->ajouterLigne($document, [
        'support_article_stock_id' => $articleId,
        'support_ligne_origine_id' => $ligneOrigineId,
        'quantite' => '40.000',
        'cout_unitaire' => '0',
        'observations' => 'Test concurrence retours cumulés',
    ], $createurId);

    $service->soumettre($document, $createurId);

    $documents[] = (int) $document->id;

    echo "Retour {$i} soumis : document {$document->id}\n";
}

$token = bin2hex(random_bytes(8));
$base = sys_get_temp_dir() . '/sigof_retour_conc_' . $token;
$gate = $base . '.go';
$children = [];

DB::disconnect('mysql');

echo "\n========== LANCEMENT DES DEUX PROCESSUS ==========\n";

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

$deadline = microtime(true) + 30;

for ($i = 0; $i < 2; $i++) {
    while (!file_exists($base . ".ready.{$i}")) {
        if (microtime(true) > $deadline) {
            touch($gate);

            foreach ($children as $child) {
                pcntl_waitpid($child, $status);
            }

            throw new RuntimeException('Les processus ne sont pas tous prêts.');
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

    verifierRetour(file_exists($fichier), "Résultat du processus {$i} absent.");

    $resultat = json_decode(file_get_contents($fichier), true);

    verifierRetour(is_array($resultat), "Résultat du processus {$i} invalide.");

    $resultats[] = $resultat;
}

echo "\n========== RESULTATS ==========\n";

foreach ($resultats as $resultat) {
    echo "Document {$resultat['document']} : {$resultat['resultat']}\n";

    if ($resultat['message']) {
        echo "Motif : {$resultat['message']}\n";
    }
}

$nbValidees = collect($resultats)->where('resultat', 'VALIDEE')->count();
$nbRefusees = collect($resultats)->where('resultat', 'REFUSEE')->count();
$nbErreurs = collect($resultats)->where('resultat', 'ERREUR_TECHNIQUE')->count();

$stockFinal = DB::table('support_stocks')->where('id', $stockId)->first();

$mouvementsRetours = DB::table('support_mouvements_stocks as m')
    ->join('support_lignes_documents_stocks as l', 'l.id', '=', 'm.support_ligne_document_stock_id')
    ->whereIn('m.support_document_stock_id', $documents)
    ->where('l.support_ligne_origine_id', $ligneOrigineId)
    ->count();

$quantiteRetournee = DB::table('support_mouvements_stocks as m')
    ->join('support_lignes_documents_stocks as l', 'l.id', '=', 'm.support_ligne_document_stock_id')
    ->where('l.support_ligne_origine_id', $ligneOrigineId)
    ->sum('m.quantite');

$statuts = DB::table('support_documents_stocks')
    ->whereIn('id', $documents)
    ->orderBy('id')
    ->pluck('statut')
    ->all();

echo "\n========== CONTROLES D'INTEGRITE ==========\n";
echo "Retours validés : {$nbValidees}\n";
echo "Retours refusés : {$nbRefusees}\n";
echo "Erreurs techniques : {$nbErreurs}\n";
echo "Quantité totale retournée : {$quantiteRetournee}\n";
echo "Mouvements de retour : {$mouvementsRetours}\n";
echo "Stock final : {$stockFinal->quantite}\n";
echo "Valeur finale : {$stockFinal->valeur_stock}\n";
echo "Statuts : " . implode(' / ', $statuts) . "\n";

$succes =
    $nbValidees === 1
    && $nbRefusees === 1
    && $nbErreurs === 0
    && (float) $quantiteRetournee === 40.0
    && $mouvementsRetours === 1
    && (float) $stockFinal->quantite === 80.0
    && (float) $stockFinal->valeur_stock === 800000.0
    && count(array_filter($statuts, fn ($s) => $s === 'VALIDEE')) === 1
    && count(array_filter($statuts, fn ($s) => $s === 'EN_ATTENTE_VALIDATION')) === 1;

foreach (glob($base . '.*') ?: [] as $fichier) {
    @unlink($fichier);
}

if (!$succes) {
    throw new RuntimeException('ECHEC : invariants de concurrence non respectés.');
}

echo "\n[OK] UN SEUL RETOUR VALIDE.\n";
echo "[OK] CUMUL DES RETOURS LIMITE A 60.\n";
echo "[OK] AUCUN DOUBLE MOUVEMENT.\n";
echo "[OK] STOCK FINAL COHERENT.\n";
echo "\nTEST DE CONCURRENCE DES RETOURS : SUCCES\n";
