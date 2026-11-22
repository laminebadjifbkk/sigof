<?php

use App\Services\Support\SupportDocumentStockService;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

require __DIR__ . '/../../vendor/autoload.php';

$app = require __DIR__ . '/../../bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

function verifierScenario7(bool $condition, string $message): void
{
    if (!$condition) {
        throw new RuntimeException("ECHEC : {$message}");
    }
}

verifierScenario7(
    DB::connection()->getDatabaseName() === 'sigof_stock_concurrency_test',
    'Mauvaise base de données : arrêt du test.'
);

$createurId = 2954;
$validateurId = 2955;
$employeId = 11;

$articles = [
    'article1' => [
        'article_id' => 46,
        'stock_id' => 44,
        'reference' => 'TEST-CONC-001',
        'quantite_initiale' => 10.0,
        'quantite_sortie' => 5.0,
        'quantite_finale' => 5.0,
        'valeur_initiale' => 100000.0,
        'valeur_finale' => 50000.0,
    ],
    'article2' => [
        'article_id' => 47,
        'stock_id' => 45,
        'reference' => 'TEST-CONC-002',
        'quantite_initiale' => 30.0,
        'quantite_sortie' => 10.0,
        'quantite_finale' => 20.0,
        'valeur_initiale' => 300000.0,
        'valeur_finale' => 200000.0,
    ],
];

// Vérification des préconditions avant toute création.
foreach ($articles as $cle => $article) {
    $stock = DB::table('support_stocks')
        ->where('id', $article['stock_id'])
        ->where('support_article_stock_id', $article['article_id'])
        ->first();

    verifierScenario7(
        $stock !== null,
        "Stock introuvable pour {$article['reference']}."
    );

    verifierScenario7(
        (float) $stock->quantite === $article['quantite_initiale'],
        "Quantité initiale incorrecte pour {$article['reference']}."
    );

    verifierScenario7(
        (float) $stock->cout_moyen_pondere === 10000.0,
        "CMP initial incorrect pour {$article['reference']}."
    );

    verifierScenario7(
        (float) $stock->valeur_stock === $article['valeur_initiale'],
        "Valeur initiale incorrecte pour {$article['reference']}."
    );

    verifierScenario7(
        !(bool) $stock->verrouille,
        "Stock verrouillé pour {$article['reference']}."
    );

    $articleActif = DB::table('support_articles_stocks')
        ->where('id', $article['article_id'])
        ->where('actif', 1)
        ->exists();

    verifierScenario7(
        $articleActif,
        "Article inactif ou introuvable : {$article['reference']}."
    );

    $articles[$cle]['mouvements_initiaux'] = DB::table('support_mouvements_stocks')
        ->where('support_article_stock_id', $article['article_id'])
        ->count();
}

$service = app(SupportDocumentStockService::class);

echo "========== PREPARATION DES DEUX SORTIES ==========\n";

$documents = [];

foreach ($articles as $cle => $article) {
    $document = $service->creerDocument([
        'type_document' => 'SORTIE',
        'date_document' => now()->toDateString(),
        'employee_id' => $employeId,
        'motif' => 'SCENARIO 7 - SORTIE ARTICLE DISTINCT',
    ], $createurId);

    $service->ajouterLigne($document, [
        'support_article_stock_id' => $article['article_id'],
        'quantite' => number_format(
            $article['quantite_sortie'],
            3,
            '.',
            ''
        ),
        'cout_unitaire' => '0',
        'observations' => 'Sortie concurrente sur article distinct.',
    ], $createurId);

    $service->soumettre($document, $createurId);

    $documents[$cle] = (int) $document->id;

    echo "{$article['reference']} : document {$document->id}, ";
    echo "sortie de {$article['quantite_sortie']} unités\n";
}

$token = bin2hex(random_bytes(8));
$base = sys_get_temp_dir() . '/sigof_scenario7_' . $token;
$gate = $base . '.go';
$children = [];

echo "\n========== LANCEMENT DES PROCESSUS ==========\n";

DB::disconnect('mysql');

foreach (array_keys($articles) as $i => $type) {
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
                    'message' => get_class($e) . ' : ' . $e->getMessage(),
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

    verifierScenario7(
        file_exists($fichier),
        "Résultat absent pour le processus {$i}."
    );

    $resultat = json_decode(
        file_get_contents($fichier),
        true
    );

    verifierScenario7(
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

verifierScenario7(
    count($erreursProcessus) === 0,
    'Un processus enfant a terminé avec une erreur technique.'
);

foreach ($resultats as $resultat) {
    verifierScenario7(
        $resultat['resultat'] === 'VALIDEE',
        "La sortie {$resultat['document']} n'a pas été validée."
    );
}

echo "\n========== CONTROLES D'INTEGRITE ==========\n";

foreach ($articles as $cle => $article) {
    $stock = DB::table('support_stocks')
        ->where('id', $article['stock_id'])
        ->first();

    $document = DB::table('support_documents_stocks')
        ->where('id', $documents[$cle])
        ->first();

    $mouvements = DB::table('support_mouvements_stocks')
        ->where('support_article_stock_id', $article['article_id'])
        ->count();

    $mouvementsDocument = DB::table('support_mouvements_stocks as m')
        ->join(
            'support_lignes_documents_stocks as l',
            'l.id',
            '=',
            'm.support_ligne_document_stock_id'
        )
        ->where('l.support_document_stock_id', $documents[$cle])
        ->count();

    verifierScenario7(
        $document !== null && $document->statut === 'VALIDEE',
        "Document non validé pour {$article['reference']}."
    );

    verifierScenario7(
        (float) $stock->quantite === $article['quantite_finale'],
        "Quantité finale incorrecte pour {$article['reference']}."
    );

    verifierScenario7(
        (float) $stock->cout_moyen_pondere === 10000.0,
        "CMP final incorrect pour {$article['reference']}."
    );

    verifierScenario7(
        (float) $stock->valeur_stock === $article['valeur_finale'],
        "Valorisation finale incorrecte pour {$article['reference']}."
    );

    verifierScenario7(
        !(bool) $stock->verrouille,
        "Verrou résiduel pour {$article['reference']}."
    );

    verifierScenario7(
        $mouvements === $article['mouvements_initiaux'] + 1,
        "Nombre de mouvements incohérent pour {$article['reference']}."
    );

    verifierScenario7(
        $mouvementsDocument === 1,
        "Le document doit produire exactement un mouvement."
    );

    echo "{$article['reference']} : ";
    echo "stock {$stock->quantite}, ";
    echo "CMP {$stock->cout_moyen_pondere}, ";
    echo "valeur {$stock->valeur_stock} FCFA\n";
    echo "Mouvements du document : {$mouvementsDocument}\n";
    echo "Verrou : " . ((bool) $stock->verrouille ? 'OUI' : 'NON') . "\n";
}

echo "\n[OK] LES DEUX SORTIES ONT ETE VALIDEES.\n";
echo "[OK] LES DEUX ARTICLES ONT ETE TRAITES INDEPENDAMMENT.\n";
echo "[OK] LES QUANTITES FINALES SONT COHERENTES.\n";
echo "[OK] LES CMP ET VALORISATIONS SONT COHERENTS.\n";
echo "[OK] UN SEUL MOUVEMENT PAR DOCUMENT.\n";
echo "[OK] AUCUN VERROU RESIDUEL.\n";

foreach (glob($base . '.*') ?: [] as $fichier) {
    @unlink($fichier);
}

echo "\nSCENARIO 7 : SUCCES\n";
