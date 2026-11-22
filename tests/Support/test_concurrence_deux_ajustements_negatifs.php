<?php

use App\Services\Support\SupportDocumentStockService;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

require __DIR__ . '/../../vendor/autoload.php';

$app = require __DIR__ . '/../../bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

function verifierScenario9(bool $condition, string $message): void
{
    if (!$condition) {
        throw new RuntimeException("ECHEC : {$message}");
    }
}

verifierScenario9(
    DB::connection()->getDatabaseName() === 'sigof_stock_concurrency_test',
    'Mauvaise base de données : arrêt du test.'
);

verifierScenario9(
    function_exists('pcntl_fork'),
    'Extension PCNTL indisponible.'
);

$articleId = 47;
$stockId = 45;
$createurId = 2954;
$validateurId = 2955;

$stockInitial = DB::table('support_stocks')
    ->where('id', $stockId)
    ->where('support_article_stock_id', $articleId)
    ->first();

verifierScenario9($stockInitial !== null, 'Stock introuvable.');

verifierScenario9(
    (float) $stockInitial->quantite === 10.0,
    'Stock initial attendu : 10 unités.'
);

verifierScenario9(
    (float) $stockInitial->cout_moyen_pondere === 10000.0,
    'CMP initial attendu : 10 000.'
);

verifierScenario9(
    (float) $stockInitial->valeur_stock === 100000.0,
    'Valeur initiale attendue : 100 000.'
);

verifierScenario9(
    !(bool) $stockInitial->verrouille,
    'Le stock est déjà verrouillé.'
);

$mouvementsInitiaux = DB::table('support_mouvements_stocks')
    ->where('support_article_stock_id', $articleId)
    ->count();

$service = app(SupportDocumentStockService::class);

echo "========== PREPARATION DES AJUSTEMENTS ==========\n";

$documents = [];

foreach (['A', 'B'] as $suffixe) {
    $document = $service->creerDocument([
        'type_document' => 'AJUSTEMENT_NEGATIF',
        'date_document' => now()->toDateString(),
        'motif' => "SCENARIO 9 - AJUSTEMENT NEGATIF {$suffixe}",
    ], $createurId);

    $service->ajouterLigne($document, [
        'support_article_stock_id' => $articleId,
        'quantite' => '7.000',
        'cout_unitaire' => '10000',
        'observations' => "Ajustement négatif concurrent {$suffixe}.",
    ], $createurId);

    $service->soumettre($document, $createurId);

    $documents[$suffixe] = (int) $document->id;

    echo "Ajustement {$suffixe} soumis : document {$document->id}\n";
}

$token = bin2hex(random_bytes(8));
$base = sys_get_temp_dir() . '/sigof_scenario9_' . $token;
$gate = $base . '.go';
$children = [];

echo "\n========== LANCEMENT DES PROCESSUS ==========\n";

DB::disconnect('mysql');

foreach (array_keys($documents) as $i => $suffixe) {
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
                    $documents[$suffixe],
                    $validateurId
                );

                $resultat = 'VALIDEE';
                $message = null;

            } catch (ValidationException | \InvalidArgumentException $e) {
                $resultat = 'REFUSEE';

                $message = $e instanceof ValidationException
                    ? implode(
                        ' | ',
                        collect($e->errors())->flatten()->all()
                    )
                    : $e->getMessage();

            } catch (Throwable $e) {
                $resultat = 'ERREUR_TECHNIQUE';
                $message = get_class($e) . ' : ' . $e->getMessage();
            }

            file_put_contents(
                $base . ".result.{$i}",
                json_encode([
                    'suffixe' => $suffixe,
                    'document' => $documents[$suffixe],
                    'resultat' => $resultat,
                    'message' => $message,
                ], JSON_UNESCAPED_UNICODE)
            );

            exit($resultat === 'ERREUR_TECHNIQUE' ? 1 : 0);

        } catch (Throwable $e) {
            file_put_contents(
                $base . ".result.{$i}",
                json_encode([
                    'suffixe' => $suffixe,
                    'document' => $documents[$suffixe],
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

    if (
        !pcntl_wifexited($status)
        || pcntl_wexitstatus($status) !== 0
    ) {
        $erreursProcessus[] = $child;
    }
}

DB::purge('mysql');
DB::reconnect('mysql');

echo "\n========== RESULTATS DES VALIDATIONS ==========\n";

$resultats = [];

for ($i = 0; $i < 2; $i++) {
    $fichier = $base . ".result.{$i}";

    verifierScenario9(
        file_exists($fichier),
        "Résultat absent pour le processus {$i}."
    );

    $resultat = json_decode(file_get_contents($fichier), true);

    verifierScenario9(
        is_array($resultat),
        "Résultat illisible pour le processus {$i}."
    );

    $resultats[$resultat['suffixe']] = $resultat;

    echo "AJUSTEMENT {$resultat['suffixe']}";
    echo " {$resultat['document']} : {$resultat['resultat']}\n";

    if ($resultat['message']) {
        echo "Motif : {$resultat['message']}\n";
    }
}

verifierScenario9(
    count($erreursProcessus) === 0,
    'Un processus enfant a terminé avec une erreur technique.'
);

verifierScenario9(
    count(array_filter(
        $resultats,
        fn ($r) => $r['resultat'] === 'VALIDEE'
    )) === 1,
    'Un seul ajustement doit être validé.'
);

verifierScenario9(
    count(array_filter(
        $resultats,
        fn ($r) => $r['resultat'] === 'REFUSEE'
    )) === 1,
    'Le second ajustement doit être refusé.'
);

verifierScenario9(
    !in_array(
        'ERREUR_TECHNIQUE',
        array_column($resultats, 'resultat'),
        true
    ),
    'Une erreur technique est survenue.'
);

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
    ->get([
        'm.id',
        'm.type_mouvement',
        'm.quantite',
        'm.quantite_avant',
        'm.quantite_apres',
        'l.support_document_stock_id',
    ]);

$mouvementsTotaux = DB::table('support_mouvements_stocks')
    ->where('support_article_stock_id', $articleId)
    ->count();

$documentValide = collect($resultats)
    ->firstWhere('resultat', 'VALIDEE');

$documentRefuse = collect($resultats)
    ->firstWhere('resultat', 'REFUSEE');

verifierScenario9(
    (float) $stockFinal->quantite === 3.0,
    'Quantité finale attendue : 3 unités.'
);

verifierScenario9(
    (float) $stockFinal->cout_moyen_pondere === 10000.0,
    'CMP final incorrect.'
);

verifierScenario9(
    (float) $stockFinal->valeur_stock === 30000.0,
    'Valeur finale attendue : 30 000.'
);

verifierScenario9(
    !(bool) $stockFinal->verrouille,
    'Un verrou de stock subsiste.'
);

verifierScenario9(
    $mouvementsScenario->count() === 1,
    'Un seul mouvement doit être créé.'
);

$mouvement = $mouvementsScenario->first();

verifierScenario9(
    $mouvement->type_mouvement === 'AJUSTEMENT_NEGATIF'
    && (float) $mouvement->quantite === 7.0
    && (float) $mouvement->quantite_avant === 10.0
    && (float) $mouvement->quantite_apres === 3.0,
    'Détails du mouvement incohérents.'
);

verifierScenario9(
    (int) $mouvement->support_document_stock_id
        === (int) $documentValide['document'],
    'Le mouvement ne correspond pas au document validé.'
);

verifierScenario9(
    $mouvementsTotaux === $mouvementsInitiaux + 1,
    'Nombre total de mouvements incohérent.'
);

foreach ($documents as $suffixe => $documentId) {
    $statutAttendu = $suffixe === $documentValide['suffixe']
        ? 'VALIDEE'
        : 'EN_ATTENTE_VALIDATION';

    verifierScenario9(
        $statuts->get($documentId) === $statutAttendu,
        "Statut inattendu pour le document {$documentId}."
    );
}

$historiqueValidation = DB::table('support_historiques_stocks')
    ->where('entite', 'DOCUMENT_STOCK')
    ->where('entite_id', $documentValide['document'])
    ->where('action', 'VALIDATION')
    ->where('user_id', $validateurId)
    ->exists();

verifierScenario9(
    $historiqueValidation,
    'Historique de validation du document gagnant absent.'
);

echo "Stock final : {$stockFinal->quantite}\n";
echo "CMP final : {$stockFinal->cout_moyen_pondere}\n";
echo "Valeur finale : {$stockFinal->valeur_stock} FCFA\n";
echo "Document validé : {$documentValide['document']}\n";
echo "Document refusé : {$documentRefuse['document']}\n";
echo "Mouvement créé : {$mouvement->id}\n";
echo "Verrou actif : " . ((bool) $stockFinal->verrouille ? 'OUI' : 'NON') . "\n";

echo "\n[OK] UN SEUL AJUSTEMENT A ETE VALIDE.\n";
echo "[OK] LE SECOND AJUSTEMENT A ETE REFUSE.\n";
echo "[OK] AUCUN DEPASSEMENT DU STOCK.\n";
echo "[OK] CMP ET VALORISATION COHERENTS.\n";
echo "[OK] UN SEUL MOUVEMENT CREE.\n";
echo "[OK] STATUTS DOCUMENTAIRES COHERENTS.\n";
echo "[OK] HISTORIQUE DE VALIDATION PRESENT.\n";
echo "[OK] AUCUN VERROU RESIDUEL.\n";

foreach (glob($base . '.*') ?: [] as $fichier) {
    @unlink($fichier);
}

echo "\nSCENARIO 9 : SUCCES\n";
