<?php

use App\Services\Support\SupportInventaireStockService;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\DB;

require __DIR__ . '/../../vendor/autoload.php';

$app = require __DIR__ . '/../../bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

function verifierDeuxInventaires(bool $condition, string $message): void
{
    if (!$condition) {
        throw new RuntimeException($message);
    }
}

verifierDeuxInventaires(
    DB::connection()->getDatabaseName() === 'sigof_stock_concurrency_test',
    'ABANDON : base différente de sigof_stock_concurrency_test.'
);

$articleId = 46;
$stockId = 44;
$createurId = 2954;
$validateurId = 2955;

$stock = DB::table('support_stocks')->where('id', $stockId)->first();

verifierDeuxInventaires($stock !== null, 'Stock introuvable.');
verifierDeuxInventaires(
    (float) $stock->quantite === 80.0,
    'Quantité initiale inattendue.'
);
verifierDeuxInventaires(
    !(bool) $stock->verrouille
        && $stock->support_inventaire_id === null,
    'Le stock est déjà verrouillé.'
);

$nbMouvementsAvant = DB::table('support_mouvements_stocks')
    ->where('support_article_stock_id', $articleId)
    ->count();

$service = app(SupportInventaireStockService::class);

echo "========== CREATION DES DEUX INVENTAIRES ==========\n";

$inventaires = [];

for ($i = 1; $i <= 2; $i++) {
    $inventaire = $service->creer([
        'libelle' => "TEST CONCURRENCE INVENTAIRE {$i}",
        'description' => 'Test isolé de verrouillage concurrent.',
        'date_debut' => now()->toDateTimeString(),
    ], $createurId);

    $inventaires[] = (int) $inventaire->id;

    echo "Inventaire {$i} créé : ID {$inventaire->id}\n";
}

$token = bin2hex(random_bytes(8));
$base = sys_get_temp_dir() . '/sigof_inv_conc_' . $token;
$gate = $base . '.go';

$children = [];

echo "\n========== LANCEMENT DES PROCESSUS ==========\n";

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
                    throw new RuntimeException('Délai de départ dépassé.');
                }

                usleep(10000);
            }

            $service = app(SupportInventaireStockService::class);

            try {
                $service->demarrer(
                    $inventaires[$i],
                    [$articleId],
                    $createurId
                );

                $resultat = [
                    'inventaire' => $inventaires[$i],
                    'resultat' => 'DEMARRÉ',
                    'message' => null,
                ];
            } catch (Throwable $e) {
                $resultat = [
                    'inventaire' => $inventaires[$i],
                    'resultat' => 'REFUSÉ',
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
                    'inventaire' => $inventaires[$i],
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

            throw new RuntimeException('Processus non prêts.');
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

    verifierDeuxInventaires(
        file_exists($fichier),
        "Résultat du processus {$i} absent."
    );

    $resultat = json_decode(file_get_contents($fichier), true);

    verifierDeuxInventaires(
        is_array($resultat),
        "Résultat du processus {$i} invalide."
    );

    $resultats[] = $resultat;
}

echo "\n========== RESULTATS ==========\n";

foreach ($resultats as $resultat) {
    echo "Inventaire {$resultat['inventaire']} : {$resultat['resultat']}\n";

    if ($resultat['message']) {
        echo "Motif : {$resultat['message']}\n";
    }
}

$demarres = collect($resultats)
    ->where('resultat', 'DEMARRÉ')
    ->values();

$refuses = collect($resultats)
    ->where('resultat', 'REFUSÉ')
    ->values();

$erreurs = collect($resultats)
    ->where('resultat', 'ERREUR_TECHNIQUE')
    ->count();

verifierDeuxInventaires($demarres->count() === 1, 'Un seul inventaire devait démarrer.');
verifierDeuxInventaires($refuses->count() === 1, 'Un seul inventaire devait être refusé.');
verifierDeuxInventaires($erreurs === 0, 'Erreur technique détectée.');

$inventaireGagnant = (int) $demarres->first()['inventaire'];
$inventairePerdant = (int) $refuses->first()['inventaire'];

$stockFinal = DB::table('support_stocks')->where('id', $stockId)->first();

$lignes = DB::table('support_inventaire_lignes')
    ->whereIn('support_inventaire_id', $inventaires)
    ->count();

$statutGagnant = DB::table('support_inventaires_stocks')
    ->where('id', $inventaireGagnant)
    ->value('statut');

$statutPerdant = DB::table('support_inventaires_stocks')
    ->where('id', $inventairePerdant)
    ->value('statut');

echo "\n========== CONTROLES D'INTEGRITE ==========\n";
echo "Inventaire propriétaire du verrou : {$stockFinal->support_inventaire_id}\n";
echo "Inventaire gagnant : {$inventaireGagnant}\n";
echo "Inventaire perdant : {$inventairePerdant}\n";
echo "Lignes d'inventaire créées : {$lignes}\n";
echo "Statut gagnant : {$statutGagnant}\n";
echo "Statut perdant : {$statutPerdant}\n";
echo "Stock verrouillé : " . ((bool) $stockFinal->verrouille ? 'OUI' : 'NON') . "\n";

verifierDeuxInventaires(
    (int) $stockFinal->support_inventaire_id === $inventaireGagnant,
    'Le verrou n’appartient pas à l’inventaire gagnant.'
);
verifierDeuxInventaires((bool) $stockFinal->verrouille, 'Le stock devrait être verrouillé.');
verifierDeuxInventaires($lignes === 1, 'Une seule ligne devait être créée.');
verifierDeuxInventaires($statutGagnant === 'EN_COURS', 'Statut gagnant incorrect.');
verifierDeuxInventaires($statutPerdant === 'BROUILLON', 'Le perdant doit rester en brouillon.');

$nbMouvementsApres = DB::table('support_mouvements_stocks')
    ->where('support_article_stock_id', $articleId)
    ->count();

verifierDeuxInventaires(
    $nbMouvementsApres === $nbMouvementsAvant,
    'Un mouvement inattendu a été créé.'
);

echo "\n[OK] UN SEUL INVENTAIRE A DEMARRE.\n";
echo "[OK] LE SECOND EST RESTE EN BROUILLON.\n";
echo "[OK] LE VERROU APPARTIENT AU GAGNANT.\n";
echo "[OK] UNE SEULE LIGNE D'INVENTAIRE EXISTE.\n";
echo "[OK] AUCUN MOUVEMENT SUPPLEMENTAIRE.\n";

echo "\n========== CLOTURE DU TEST ==========\n";

$service = app(SupportInventaireStockService::class);

$service->enregistrerComptage(
    $inventaireGagnant,
    $articleId,
    '80.000',
    'Comptage de clôture du test de concurrence.',
    $createurId
);

$service->soumettre($inventaireGagnant, $createurId);
$service->valider($inventaireGagnant, $validateurId);

$stockFinal = DB::table('support_stocks')->where('id', $stockId)->first();

verifierDeuxInventaires(
    !(bool) $stockFinal->verrouille
        && $stockFinal->support_inventaire_id === null,
    'Le stock aurait dû être déverrouillé après clôture.'
);

verifierDeuxInventaires(
    (float) $stockFinal->quantite === 80.0,
    'La quantité finale a changé de manière inattendue.'
);

echo "Inventaire gagnant clôturé : VALIDEE\n";
echo "Inventaire perdant conservé : BROUILLON\n";
echo "Stock final : {$stockFinal->quantite}\n";
echo "Stock déverrouillé : OUI\n";
echo "\nTEST DE CONCURRENCE DEUX INVENTAIRES : SUCCES\n";

foreach (glob($base . '.*') ?: [] as $fichier) {
    @unlink($fichier);
}
