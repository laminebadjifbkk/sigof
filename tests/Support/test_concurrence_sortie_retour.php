<?php

use App\Services\Support\SupportDocumentStockService;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

require __DIR__ . '/../../vendor/autoload.php';

$app = require __DIR__ . '/../../bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

function verifierScenario6(bool $condition, string $message): void
{
    if (!$condition) {
        throw new RuntimeException("ECHEC : {$message}");
    }
}

verifierScenario6(
    DB::connection()->getDatabaseName() === 'sigof_stock_concurrency_test',
    'Mauvaise base de données : arrêt du test.'
);

$articleId = 46;
$stockId = 44;
$sortieOrigineId = 89;
$ligneOrigineId = 100;
$createurId = 2954;
$validateurId = 2955;
$employeId = 11;

$stock = DB::table('support_stocks')->where('id', $stockId)->first();

verifierScenario6($stock !== null, 'Stock introuvable.');
verifierScenario6(
    (float) $stock->quantite === 80.0,
    'Stock initial attendu : 80 unités.'
);
verifierScenario6(
    (float) $stock->cout_moyen_pondere === 10000.0,
    'CMP initial attendu : 10 000.'
);
verifierScenario6(
    (float) $stock->valeur_stock === 800000.0,
    'Valeur initiale attendue : 800 000.'
);
verifierScenario6(
    !(bool) $stock->verrouille,
    'Le stock est verrouillé.'
);

// Vérifier la sortie d'origine et son mouvement.
$origine = DB::table('support_lignes_documents_stocks as l')
    ->join(
        'support_documents_stocks as d',
        'd.id',
        '=',
        'l.support_document_stock_id'
    )
    ->join(
        'support_mouvements_stocks as m',
        'm.support_ligne_document_stock_id',
        '=',
        'l.id'
    )
    ->where('d.id', $sortieOrigineId)
    ->where('l.id', $ligneOrigineId)
    ->where('l.support_article_stock_id', $articleId)
    ->where('d.type_document', 'SORTIE')
    ->where('d.statut', 'VALIDEE')
    ->select('l.quantite', 'm.quantite as quantite_mouvement')
    ->first();

verifierScenario6($origine !== null, 'Sortie historique introuvable.');
verifierScenario6(
    (float) $origine->quantite === 60.0
    && (float) $origine->quantite_mouvement === 60.0,
    'La sortie historique doit être de 60 unités.'
);

// Vérifier que 40 unités ont déjà été retournées.
$quantiteDejaRetournee = DB::table('support_mouvements_stocks as m')
    ->join(
        'support_lignes_documents_stocks as l',
        'l.id',
        '=',
        'm.support_ligne_document_stock_id'
    )
    ->where('l.support_ligne_origine_id', $ligneOrigineId)
    ->where('m.type_mouvement', 'RETOUR')
    ->sum('m.quantite');

verifierScenario6(
    (float) $quantiteDejaRetournee === 40.0,
    'Les retours historiques doivent totaliser 40 unités.'
);

$service = app(SupportDocumentStockService::class);

echo "========== PREPARATION DU RETOUR ==========\n";

$retour = $service->creerDocument([
    'type_document' => 'RETOUR',
    'date_document' => now()->toDateString(),
    'motif' => 'SCENARIO 6 - RETOUR CONCURRENT',
], $createurId);

$service->ajouterLigne($retour, [
    'support_article_stock_id' => $articleId,
    'support_ligne_origine_id' => $ligneOrigineId,
    'quantite' => '20.000',
    'cout_unitaire' => '0',
    'observations' => 'Retour concurrent de 20 unités.',
], $createurId);

$service->soumettre($retour, $createurId);

echo "Retour soumis : document {$retour->id}\n";

echo "\n========== PREPARATION DE LA SORTIE ==========\n";

$sortie = $service->creerDocument([
    'type_document' => 'SORTIE',
    'date_document' => now()->toDateString(),
    'employee_id' => $employeId,
    'motif' => 'SCENARIO 6 - SORTIE CONCURRENTE',
], $createurId);

$service->ajouterLigne($sortie, [
    'support_article_stock_id' => $articleId,
    'quantite' => '90.000',
    'cout_unitaire' => '0',
    'observations' => 'Sortie concurrente de 90 unités.',
], $createurId);

$service->soumettre($sortie, $createurId);

echo "Sortie soumise : document {$sortie->id}\n";

$documents = [
    'sortie' => (int) $sortie->id,
    'retour' => (int) $retour->id,
];

$token = bin2hex(random_bytes(8));
$base = sys_get_temp_dir() . '/sigof_scenario6_' . $token;
$gate = $base . '.go';
$children = [];

echo "\n========== LANCEMENT DES PROCESSUS ==========\n";

DB::disconnect('mysql');

foreach (['sortie', 'retour'] as $i => $type) {
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
                    throw new RuntimeException('Délai de départ dépassé.');
                }

                usleep(10000);
            }

            $serviceEnfant = app(SupportDocumentStockService::class);

            try {
                $serviceEnfant->valider(
                    $documents[$type],
                    $validateurId
                );

                $resultat = 'VALIDEE';
                $message = null;
            } catch (ValidationException $e) {
                $resultat = 'REFUSEE';
                $message = implode(
                    ' | ',
                    collect($e->errors())->flatten()->all()
                );
            } catch (Throwable $e) {
                $resultat = 'ERREUR_TECHNIQUE';
                $message = get_class($e) . ' : ' . $e->getMessage();
            }

            file_put_contents(
                $base . ".result.{$i}",
                json_encode([
                    'type' => $type,
                    'document' => $documents[$type],
                    'resultat' => $resultat,
                    'message' => $message,
                ], JSON_UNESCAPED_UNICODE)
            );

            exit($resultat === 'ERREUR_TECHNIQUE' ? 1 : 0);
        } catch (Throwable $e) {
            file_put_contents(
                $base . ".result.{$i}",
                json_encode([
                    'type' => $type,
                    'document' => $documents[$type],
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

    verifierScenario6(
        file_exists($fichier),
        "Résultat du processus {$i} absent."
    );

    $resultat = json_decode(file_get_contents($fichier), true);

    verifierScenario6(
        is_array($resultat),
        "Résultat du processus {$i} invalide."
    );

    $resultats[$resultat['type']] = $resultat;
}

echo "\n========== RESULTATS DES VALIDATIONS ==========\n";

foreach (['sortie', 'retour'] as $type) {
    $r = $resultats[$type];

    echo strtoupper($type) . " {$r['document']} : {$r['resultat']}\n";

    if ($r['message']) {
        echo "Motif : {$r['message']}\n";
    }
}

$stockFinal = DB::table('support_stocks')
    ->where('id', $stockId)
    ->first();

$documentsIds = array_values($documents);

$mouvements = DB::table('support_mouvements_stocks')
    ->whereIn('support_document_stock_id', $documentsIds)
    ->get();

$statuts = DB::table('support_documents_stocks')
    ->whereIn('id', $documentsIds)
    ->pluck('statut', 'type_document');

$totalRetourne = DB::table('support_mouvements_stocks as m')
    ->join(
        'support_lignes_documents_stocks as l',
        'l.id',
        '=',
        'm.support_ligne_document_stock_id'
    )
    ->where('l.support_ligne_origine_id', $ligneOrigineId)
    ->where('m.type_mouvement', 'RETOUR')
    ->sum('m.quantite');

echo "\n========== CONTROLES D'INTEGRITE ==========\n";
echo "Sortie : {$resultats['sortie']['resultat']}\n";
echo "Retour : {$resultats['retour']['resultat']}\n";
echo "Stock final : {$stockFinal->quantite}\n";
echo "CMP final : {$stockFinal->cout_moyen_pondere}\n";
echo "Valeur finale : {$stockFinal->valeur_stock} FCFA\n";
echo "Retours cumulés : {$totalRetourne}\n";
echo "Mouvements du scénario : {$mouvements->count()}\n";
echo "Verrou actif : " . ((bool) $stockFinal->verrouille ? 'OUI' : 'NON') . "\n";
echo "Statuts : " . json_encode($statuts->all()) . "\n";

$sortieValidee = $resultats['sortie']['resultat'] === 'VALIDEE';
$sortieRefusee = $resultats['sortie']['resultat'] === 'REFUSEE';
$retourValide = $resultats['retour']['resultat'] === 'VALIDEE';

$quantiteAttendue = $sortieValidee ? 10.0 : 100.0;
$valeurAttendue = $sortieValidee ? 100000.0 : 1000000.0;
$mouvementsAttendus = $sortieValidee ? 2 : 1;

$succes =
    $retourValide
    && ($sortieValidee || $sortieRefusee)
    && $resultats['sortie']['resultat'] !== 'ERREUR_TECHNIQUE'
    && $resultats['retour']['resultat'] !== 'ERREUR_TECHNIQUE'
    && (float) $stockFinal->quantite === $quantiteAttendue
    && (float) $stockFinal->cout_moyen_pondere === 10000.0
    && (float) $stockFinal->valeur_stock === $valeurAttendue
    && (float) $totalRetourne === 60.0
    && $mouvements->count() === $mouvementsAttendus
    && !(bool) $stockFinal->verrouille
    && $statuts->get('RETOUR') === 'VALIDEE'
    && $statuts->get('SORTIE') === (
        $sortieValidee ? 'VALIDEE' : 'EN_ATTENTE_VALIDATION'
    );

foreach (glob($base . '.*') ?: [] as $fichier) {
    @unlink($fichier);
}

if (!$succes) {
    throw new RuntimeException(
        'ECHEC : les invariants de concurrence ne sont pas respectés.'
    );
}

echo "\n[OK] RETOUR DE 20 UNITES VALIDE.\n";
echo "[OK] SORTIE VALIDEE UNIQUEMENT SI LE STOCK LE PERMET.\n";
echo "[OK] CUMUL DES RETOURS LIMITE A 60.\n";
echo "[OK] CMP ET VALORISATION COHERENTS.\n";
echo "[OK] AUCUN MOUVEMENT EN DOUBLE.\n";
echo "[OK] AUCUN VERROU RESIDUEL.\n";
echo "\nSCENARIO 6 : SUCCES\n";
