<?php

use App\Services\Support\SupportInventaireStockService;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\DB;

require __DIR__ . '/../../vendor/autoload.php';

$app = require __DIR__ . '/../../bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

function verifierScenario5(bool $ok, string $message): void
{
    if (!$ok) {
        throw new RuntimeException($message);
    }
}

function codeErreurScenario5(Throwable $e): ?string
{
    $courant = $e;

    while ($courant) {
        if ($courant instanceof PDOException) {
            return (string) $courant->getCode();
        }

        $courant = $courant->getPrevious();
    }

    return null;
}

verifierScenario5(
    DB::connection()->getDatabaseName() === 'sigof_stock_concurrency_test',
    'BASE INCORRECTE : abandon.'
);

$createurId = 2954;
$validateurId = 2955;
$articleIds = [46, 47];

$stocksInitiaux = DB::table('support_stocks')
    ->whereIn('support_article_stock_id', $articleIds)
    ->orderBy('support_article_stock_id')
    ->get()
    ->keyBy('support_article_stock_id');

verifierScenario5($stocksInitiaux->count() === 2, 'Les deux stocks sont requis.');

verifierScenario5(
    !(bool) $stocksInitiaux[46]->verrouille
        && $stocksInitiaux[46]->support_inventaire_id === null
        && !(bool) $stocksInitiaux[47]->verrouille
        && $stocksInitiaux[47]->support_inventaire_id === null,
    'Un des stocks est déjà verrouillé.'
);

$quantitesInitiales = [
    46 => $stocksInitiaux[46]->quantite,
    47 => $stocksInitiaux[47]->quantite,
];

$service = app(SupportInventaireStockService::class);

echo "========== CREATION DES INVENTAIRES ==========\n";

$inventaires = [];

for ($i = 1; $i <= 2; $i++) {
    $inventaire = $service->creer([
        'libelle' => "SCENARIO 5 - INVENTAIRE {$i}",
        'description' => 'Concurrence avec ordre inverse des articles.',
        'date_debut' => now()->toDateTimeString(),
    ], $createurId);

    $inventaires[$i - 1] = (int) $inventaire->id;

    echo "Inventaire {$i} : {$inventaire->id}\n";
}

$ordres = [
    [46, 47],
    [47, 46],
];

$token = bin2hex(random_bytes(8));
$base = sys_get_temp_dir() . '/sigof_s5_' . $token;
$gate = $base . '.go';

$children = [];

echo "\n========== DEMARRAGE DES PROCESSUS ==========\n";

DB::disconnect('mysql');

for ($i = 0; $i < 2; $i++) {
    $pid = pcntl_fork();

    if ($pid === -1) {
        throw new RuntimeException('Échec de création du processus.');
    }

    if ($pid === 0) {
        try {
            DB::purge('mysql');
            DB::reconnect('mysql');

            file_put_contents($base . ".ready.{$i}", 'READY');

            $deadline = microtime(true) + 30;

            while (!file_exists($gate)) {
                if (microtime(true) > $deadline) {
                    throw new RuntimeException('Délai de synchronisation dépassé.');
                }

                usleep(10000);
            }

            $service = app(SupportInventaireStockService::class);

            try {
                $service->demarrer(
                    $inventaires[$i],
                    $ordres[$i],
                    $createurId
                );

                $resultat = [
                    'inventaire' => $inventaires[$i],
                    'ordre' => $ordres[$i],
                    'resultat' => 'DEMARRÉ',
                    'message' => null,
                    'code' => null,
                ];
            } catch (Throwable $e) {
                $code = codeErreurScenario5($e);

                $resultat = [
                    'inventaire' => $inventaires[$i],
                    'ordre' => $ordres[$i],
                    'resultat' => 'REFUSÉ',
                    'message' => $e->getMessage(),
                    'code' => $code,
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
                    'inventaire' => $inventaires[$i],
                    'ordre' => $ordres[$i],
                    'resultat' => 'ERREUR_TECHNIQUE',
                    'message' => $e->getMessage(),
                    'code' => codeErreurScenario5($e),
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

            throw new RuntimeException('Processus non prêts.');
        }

        usleep(10000);
    }
}

echo "Processus prêts : départ simultané.\n";
echo "Inventaire A : [46,47]\n";
echo "Inventaire B : [47,46]\n";

touch($gate);

foreach ($children as $child) {
    pcntl_waitpid($child, $status);
}

DB::reconnect('mysql');

$resultats = [];

for ($i = 0; $i < 2; $i++) {
    $fichier = $base . ".result.{$i}";

    verifierScenario5(file_exists($fichier), "Résultat {$i} absent.");

    $resultats[] = json_decode(file_get_contents($fichier), true);
}

echo "\n========== RESULTATS DES PROCESSUS ==========\n";

foreach ($resultats as $r) {
    echo "Inventaire {$r['inventaire']} - ordre ["
        . implode(',', $r['ordre']) . "] : {$r['resultat']}\n";

    if ($r['message']) {
        echo "Message : {$r['message']}\n";
    }

    if ($r['code']) {
        echo "Code SQL/PDO : {$r['code']}\n";
    }
}

$demarres = collect($resultats)->where('resultat', 'DEMARRÉ');
$refuses = collect($resultats)->where('resultat', 'REFUSÉ');
$erreurs = collect($resultats)->where('resultat', 'ERREUR_TECHNIQUE');

verifierScenario5($demarres->count() === 1, 'Un seul démarrage devait réussir.');
verifierScenario5($refuses->count() === 1, 'Un seul démarrage devait être refusé.');
verifierScenario5($erreurs->isEmpty(), 'Erreur technique détectée.');

foreach ($resultats as $r) {
    verifierScenario5(
        $r['code'] !== '1213',
        'DEADLOCK MYSQL DETECTE.'
    );
}

$gagnant = (int) $demarres->first()['inventaire'];
$perdant = (int) $refuses->first()['inventaire'];

$statuts = DB::table('support_inventaires_stocks')
    ->whereIn('id', $inventaires)
    ->pluck('statut', 'id');

$lignes = DB::table('support_inventaire_lignes')
    ->whereIn('support_inventaire_id', $inventaires)
    ->orderBy('support_article_stock_id')
    ->get();

echo "\n========== CONTROLE DES VERROUS ==========\n";

$stocksFinaux = DB::table('support_stocks')
    ->whereIn('support_article_stock_id', $articleIds)
    ->orderBy('support_article_stock_id')
    ->get();

foreach ($stocksFinaux as $stock) {
    echo "Article {$stock->support_article_stock_id} : "
        . "verrou={$stock->verrouille}, "
        . "inventaire={$stock->support_inventaire_id}, "
        . "quantité={$stock->quantite}\n";

    verifierScenario5(
        (bool) $stock->verrouille,
        "Article {$stock->support_article_stock_id} non verrouillé."
    );

    verifierScenario5(
        (int) $stock->support_inventaire_id === $gagnant,
        "Article {$stock->support_article_stock_id} verrouillé par le mauvais inventaire."
    );
}

verifierScenario5($lignes->count() === 2, 'Deux lignes étaient attendues.');
verifierScenario5(
    $lignes->pluck('support_article_stock_id')->map(fn ($id) => (int) $id)->all()
        === [46, 47],
    'Les lignes ne correspondent pas aux deux articles.'
);
verifierScenario5($statuts[$gagnant] === 'EN_COURS', 'Statut du gagnant incorrect.');
verifierScenario5($statuts[$perdant] === 'BROUILLON', 'Le perdant doit rester en brouillon.');

echo "\n[OK] UN SEUL INVENTAIRE A DEMARRE.\n";
echo "[OK] LES DEUX ARTICLES SONT VERROUILLES PAR LE MEME INVENTAIRE.\n";
echo "[OK] L'INVENTAIRE PERDANT N'A PAS DE LIGNE.\n";
echo "[OK] AUCUN DEADLOCK NI ERREUR TECHNIQUE.\n";

echo "\n========== CLOTURE DU TEST ==========\n";

$service = app(SupportInventaireStockService::class);

foreach ($articleIds as $articleId) {
    $service->enregistrerComptage(
        $gagnant,
        $articleId,
        (string) $quantitesInitiales[$articleId],
        'Comptage de clôture du scénario 5.',
        $createurId
    );
}

$service->soumettre($gagnant, $createurId);
$service->valider($gagnant, $validateurId);

$stocksCloture = DB::table('support_stocks')
    ->whereIn('support_article_stock_id', $articleIds)
    ->orderBy('support_article_stock_id')
    ->get();

foreach ($stocksCloture as $stock) {
    verifierScenario5(
        !(bool) $stock->verrouille
            && $stock->support_inventaire_id === null,
        "Article {$stock->support_article_stock_id} non déverrouillé."
    );

    verifierScenario5(
        (string) $stock->quantite === (string) $quantitesInitiales[$stock->support_article_stock_id],
        "Quantité modifiée pour l'article {$stock->support_article_stock_id}."
    );
}

echo "Inventaire gagnant : VALIDEE\n";
echo "Inventaire perdant : BROUILLON\n";
echo "Les deux stocks sont déverrouillés.\n";
echo "\nSCENARIO 5 : SUCCES\n";

foreach (glob($base . '.*') ?: [] as $fichier) {
    @unlink($fichier);
}
