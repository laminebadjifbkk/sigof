<?php

use App\Services\Support\SupportDocumentStockService;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

require __DIR__ . '/../../vendor/autoload.php';

$app = require __DIR__ . '/../../bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

function verifierScenario8(bool $condition, string $message): void
{
    if (!$condition) {
        throw new RuntimeException("ECHEC : {$message}");
    }
}

verifierScenario8(
    DB::connection()->getDatabaseName() === 'sigof_stock_concurrency_test',
    'Mauvaise base de données : arrêt du test.'
);

$articleId = 47;
$stockId = 45;
$createurId = 2954;
$validateurId = 2955;
$employeId = 11;

$stockInitial = DB::table('support_stocks')
    ->where('id', $stockId)
    ->where('support_article_stock_id', $articleId)
    ->first();

verifierScenario8($stockInitial !== null, 'Stock introuvable.');
verifierScenario8(
    (float) $stockInitial->quantite === 20.0,
    'Stock initial attendu : 20 unités.'
);
verifierScenario8(
    (float) $stockInitial->cout_moyen_pondere === 10000.0,
    'CMP initial attendu : 10 000.'
);
verifierScenario8(
    (float) $stockInitial->valeur_stock === 200000.0,
    'Valeur initiale attendue : 200 000.'
);
verifierScenario8(
    !(bool) $stockInitial->verrouille,
    'Le stock est déjà verrouillé.'
);

$mouvementsInitiaux = DB::table('support_mouvements_stocks')
    ->where('support_article_stock_id', $articleId)
    ->count();

$service = app(SupportDocumentStockService::class);

echo "========== PREPARATION DE LA SORTIE ==========\n";

$sortie = $service->creerDocument([
    'type_document' => 'SORTIE',
    'date_document' => now()->toDateString(),
    'employee_id' => $employeId,
    'motif' => 'SCENARIO 8 - SORTIE CONCURRENTE',
], $createurId);

$service->ajouterLigne($sortie, [
    'support_article_stock_id' => $articleId,
    'quantite' => '15.000',
    'cout_unitaire' => '0',
    'observations' => 'Sortie concurrente de 15 unités.',
], $createurId);

$service->soumettre($sortie, $createurId);

echo "Sortie soumise : document {$sortie->id}\n";

echo "\n========== PREPARATION DE L'AJUSTEMENT NEGATIF ==========\n";

$ajustement = $service->creerDocument([
    'type_document' => 'AJUSTEMENT_NEGATIF',
    'date_document' => now()->toDateString(),
    'motif' => 'SCENARIO 8 - AJUSTEMENT NEGATIF CONCURRENT',
], $createurId);

$service->ajouterLigne($ajustement, [
    'support_article_stock_id' => $articleId,
    'quantite' => '10.000',
    'cout_unitaire' => '10000',
    'observations' => 'Ajustement négatif concurrent de 10 unités.',
], $createurId);

$service->soumettre($ajustement, $createurId);

echo "Ajustement soumis : document {$ajustement->id}\n";

$documents = [
    'sortie' => (int) $sortie->id,
    'ajustement' => (int) $ajustement->id,
];

$token = bin2hex(random_bytes(8));
$base = sys_get_temp_dir() . '/sigof_scenario8_' . $token;
$gate = $base . '.go';
$children = [];

echo "\n========== LANCEMENT DES PROCESSUS ==========\n";

DB::disconnect('mysql');

foreach (array_keys($documents) as $i => $type) {
    $pid = pcntl_fork();

    if ($pid === -1) {
        throw new RuntimeException(
            'Impossible de créer un processus enfant.'
        );
    }

    if ($pid === 0) {
        try {
            DB::purge('mysql');
            DB::reconnect('mysql');

            file_put_contents($base . ".ready.{$i}", 'READY');

            $deadline = microtime(true) + 30;

            while (!file_exists($gate)) {
                if (microtime(true) > $deadline) {
                    throw new RuntimeException(
                        'Délai de départ simultané dépassé.'
                    );
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
            } catch (ValidationException | InvalidArgumentException $e) {
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
                    'message' => get_class($e) . ' : ' . $e->getMessage(),
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
                "Le processus {$i} n'est pas prêt."
            );
        }

        usleep(10000);
    }
}

echo "Deux processus prêts. Départ simultané.\n";

touch($gate);

$erreursProcessus = [];

foreach ($children as $child) {
    pcntl_waitpid($child, $status);

    if (!pcntl_wifexited($status) || pcntl_wexitstatus($status) !== 0) {
        $erreursProcessus[] = $child;
    }
}

DB::purge('mysql');
DB::reconnect('mysql');

echo "\n========== RESULTATS DES VALIDATIONS ==========\n";

$resultats = [];

for ($i = 0; $i < 2; $i++) {
    $fichier = $base . ".result.{$i}";

    verifierScenario8(
        file_exists($fichier),
        "Résultat absent pour le processus {$i}."
    );

    $resultat = json_decode(file_get_contents($fichier), true);

    verifierScenario8(
        is_array($resultat),
        "Résultat illisible pour le processus {$i}."
    );

    $resultats[$resultat['type']] = $resultat;

    echo strtoupper($resultat['type']);
    echo " {$resultat['document']} : {$resultat['resultat']}\n";

    if ($resultat['message']) {
        echo "Motif : {$resultat['message']}\n";
    }
}

verifierScenario8(
    count($erreursProcessus) === 0,
    'Un processus enfant a terminé avec une erreur technique.'
);

$sortieValidee = $resultats['sortie']['resultat'] === 'VALIDEE';
$ajustementValide = $resultats['ajustement']['resultat'] === 'VALIDEE';

verifierScenario8(
    $sortieValidee xor $ajustementValide,
    'Une seule des deux opérations doit être validée.'
);

verifierScenario8(
    !in_array('ERREUR_TECHNIQUE', array_column($resultats, 'resultat'), true),
    'Une erreur technique est survenue.'
);

$quantiteAttendue = $sortieValidee ? 5.0 : 10.0;
$valeurAttendue = $sortieValidee ? 50000.0 : 100000.0;

echo "\n========== CONTROLES D'INTEGRITE ==========\n";

$stockFinal = DB::table('support_stocks')
    ->where('id', $stockId)
    ->first();

$statuts = DB::table('support_documents_stocks')
    ->whereIn('id', array_values($documents))
    ->pluck('statut', 'id');

$mouvementsScenario = DB::table('support_mouvements_stocks as m')
    ->join(
        'support_lignes_documents_stocks as l',
        'l.id',
        '=',
        'm.support_ligne_document_stock_id'
    )
    ->whereIn('l.support_document_stock_id', array_values($documents))
    ->count();

$mouvementsTotaux = DB::table('support_mouvements_stocks')
    ->where('support_article_stock_id', $articleId)
    ->count();

verifierScenario8(
    (float) $stockFinal->quantite === $quantiteAttendue,
    'Quantité finale incorrecte.'
);

verifierScenario8(
    (float) $stockFinal->cout_moyen_pondere === 10000.0,
    'CMP final incorrect.'
);

verifierScenario8(
    (float) $stockFinal->valeur_stock === $valeurAttendue,
    'Valeur finale incorrecte.'
);

verifierScenario8(
    !(bool) $stockFinal->verrouille,
    'Un verrou de stock subsiste.'
);

verifierScenario8(
    $mouvementsScenario === 1,
    'Un seul mouvement doit être créé.'
);

verifierScenario8(
    $mouvementsTotaux === $mouvementsInitiaux + 1,
    'Nombre total de mouvements incohérent.'
);

foreach ($documents as $type => $documentId) {
    $statutAttendu = $resultats[$type]['resultat'] === 'VALIDEE'
        ? 'VALIDEE'
        : 'EN_ATTENTE_VALIDATION';

    verifierScenario8(
        $statuts->get($documentId) === $statutAttendu,
        "Statut inattendu pour le document {$documentId}."
    );
}

echo "Stock final : {$stockFinal->quantite}\n";
echo "CMP final : {$stockFinal->cout_moyen_pondere}\n";
echo "Valeur finale : {$stockFinal->valeur_stock} FCFA\n";
echo "Mouvements créés : {$mouvementsScenario}\n";
echo "Verrou actif : " . ((bool) $stockFinal->verrouille ? 'OUI' : 'NON') . "\n";

echo "\n[OK] UNE SEULE OPERATION A ETE VALIDEE.\n";
echo "[OK] AUCUN DEPASSEMENT DU STOCK.\n";
echo "[OK] CMP ET VALORISATION COHERENTS.\n";
echo "[OK] AUCUN MOUVEMENT EN DOUBLE.\n";
echo "[OK] STATUTS DOCUMENTAIRES COHERENTS.\n";
echo "[OK] AUCUN VERROU RESIDUEL.\n";

foreach (glob($base . '.*') ?: [] as $fichier) {
    @unlink($fichier);
}

echo "\nSCENARIO 8 : SUCCES\n";
