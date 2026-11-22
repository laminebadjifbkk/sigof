<?php

use App\Services\Support\SupportDocumentStockService;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

require __DIR__ . '/../../vendor/autoload.php';

$app = require __DIR__ . '/../../bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

function verifierScenario11(bool $condition, string $message): void
{
    if (!$condition) {
        throw new RuntimeException("ECHEC : {$message}");
    }
}

verifierScenario11(
    DB::connection()->getDatabaseName() === 'sigof_stock_concurrency_test',
    'Mauvaise base de données.'
);

verifierScenario11(
    function_exists('pcntl_fork'),
    'Extension PCNTL indisponible.'
);

$articleId = 47;
$stockId = 45;
$fournisseurId = 21;
$createurId = 2954;
$validateurId = 2955;

$stockInitial = DB::table('support_stocks')
    ->where('id', $stockId)
    ->where('support_article_stock_id', $articleId)
    ->first();

verifierScenario11($stockInitial !== null, 'Stock introuvable.');

verifierScenario11(
    (float) $stockInitial->quantite === 13.0,
    'Stock initial attendu : 13 unités.'
);

verifierScenario11(
    (float) $stockInitial->cout_moyen_pondere === 10000.0,
    'CMP initial attendu : 10 000.'
);

verifierScenario11(
    (float) $stockInitial->valeur_stock === 130000.0,
    'Valeur initiale attendue : 130 000.'
);

verifierScenario11(
    !(bool) $stockInitial->verrouille,
    'Le stock est déjà verrouillé.'
);

$fournisseur = DB::table('support_fournisseurs_stocks')
    ->where('id', $fournisseurId)
    ->whereNull('deleted_at')
    ->where('actif', 1)
    ->first();

verifierScenario11(
    $fournisseur !== null,
    'Fournisseur actif introuvable.'
);

$utilisateurs = DB::table('users')
    ->whereIn('id', [$createurId, $validateurId])
    ->count();

verifierScenario11(
    $utilisateurs === 2,
    'Créateur ou validateur introuvable.'
);

$mouvementsInitiaux = DB::table('support_mouvements_stocks')
    ->where('support_article_stock_id', $articleId)
    ->count();

$service = app(SupportDocumentStockService::class);

$receptions = [
    'reception_a' => [
        'quantite' => '5.000',
        'cout' => '12000',
    ],
    'reception_b' => [
        'quantite' => '7.000',
        'cout' => '15000',
    ],
];

$documents = [];

echo "========== PREPARATION DES RECEPTIONS ==========\n";

foreach ($receptions as $type => $donnees) {
    $document = $service->creerDocument([
        'type_document' => 'RECEPTION',
        'date_document' => now()->toDateString(),
        'support_fournisseur_stock_id' => $fournisseurId,
        'motif' => 'SCENARIO 11 - DEUX RECEPTIONS CONCURRENTES',
    ], $createurId);

    $service->ajouterLigne($document, [
        'support_article_stock_id' => $articleId,
        'quantite' => $donnees['quantite'],
        'cout_unitaire' => $donnees['cout'],
        'observations' => "Scénario 11 : {$type}.",
    ], $createurId);

    $service->soumettre($document, $createurId);

    $documents[$type] = (int) $document->id;

    echo strtoupper($type)
        . " soumise : document {$document->id}\n";
}

$token = bin2hex(random_bytes(8));
$base = sys_get_temp_dir() . '/sigof_scenario11_' . $token;
$gate = $base . '.go';
$children = [];

DB::disconnect('mysql');

echo "\n========== LANCEMENT DES PROCESSUS ==========\n";

foreach (array_keys($receptions) as $i => $type) {
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

    verifierScenario11(
        file_exists($fichier),
        "Résultat absent pour le processus {$i}."
    );

    $resultat = json_decode(file_get_contents($fichier), true);

    verifierScenario11(
        is_array($resultat),
        "Résultat illisible pour le processus {$i}."
    );

    $resultats[$resultat['type']] = $resultat;

    echo strtoupper($resultat['type'])
        . " {$resultat['document']} : {$resultat['resultat']}\n";

    if ($resultat['message']) {
        echo "Motif : {$resultat['message']}\n";
    }
}

verifierScenario11(
    count($erreursProcessus) === 0,
    'Un processus enfant a terminé avec une erreur technique.'
);

foreach (array_keys($receptions) as $type) {
    verifierScenario11(
        ($resultats[$type]['resultat'] ?? null) === 'VALIDEE',
        "{$type} doit être validée."
    );
}

$stockFinal = DB::table('support_stocks')
    ->where('id', $stockId)
    ->where('support_article_stock_id', $articleId)
    ->first();

verifierScenario11($stockFinal !== null, 'Stock final introuvable.');

verifierScenario11(
    (float) $stockFinal->quantite === 25.0,
    'Quantité finale attendue : 25.'
);

verifierScenario11(
    (float) $stockFinal->valeur_stock === 295000.0,
    'Valeur finale attendue : 295 000.'
);

verifierScenario11(
    (float) $stockFinal->cout_moyen_pondere === 11800.0,
    'CMP final attendu : 11 800.'
);

verifierScenario11(
    !(bool) $stockFinal->verrouille,
    'Un verrou de stock est resté actif.'
);

$documentsFinaux = DB::table('support_documents_stocks')
    ->whereIn('id', array_values($documents))
    ->get(['id', 'type_document', 'statut', 'user_validation_id'])
    ->keyBy('id');

foreach ($documents as $type => $documentId) {
    $documentFinal = $documentsFinaux->get($documentId);

    verifierScenario11(
        $documentFinal !== null
        && $documentFinal->type_document === 'RECEPTION'
        && $documentFinal->statut === 'VALIDEE'
        && (int) $documentFinal->user_validation_id === $validateurId,
        "Statut ou validateur incorrect pour {$type}."
    );
}

$mouvements = DB::table('support_mouvements_stocks as m')
    ->join(
        'support_lignes_documents_stocks as l',
        'l.id',
        '=',
        'm.support_ligne_document_stock_id'
    )
    ->whereIn('l.support_document_stock_id', array_values($documents))
    ->orderBy('m.id')
    ->get([
        'm.id',
        'm.type_mouvement',
        'm.quantite',
        'm.quantite_avant',
        'm.quantite_apres',
        'm.cout_unitaire',
        'm.valeur_mouvement',
        'l.support_document_stock_id',
    ]);

verifierScenario11(
    $mouvements->count() === 2,
    'Deux mouvements étaient attendus.'
);

$attendus = [
    $documents['reception_a'] => [
        'quantite' => 5.0,
        'cout' => 12000.0,
        'valeur' => 60000.0,
    ],
    $documents['reception_b'] => [
        'quantite' => 7.0,
        'cout' => 15000.0,
        'valeur' => 105000.0,
    ],
];

foreach ($attendus as $documentId => $attendu) {
    $mouvement = $mouvements->first(
        fn ($m) => (int) $m->support_document_stock_id === $documentId
    );

    verifierScenario11(
        $mouvement !== null
        && $mouvement->type_mouvement === 'ENTREE'
        && (float) $mouvement->quantite === $attendu['quantite']
        && (float) $mouvement->cout_unitaire === $attendu['cout']
        && (float) $mouvement->valeur_mouvement === $attendu['valeur']
        && (
            (float) $mouvement->quantite_apres
            - (float) $mouvement->quantite_avant
        ) === $attendu['quantite'],
        "Mouvement incohérent pour le document {$documentId}."
    );
}

$transitions = $mouvements
    ->sortBy('quantite_avant')
    ->values();

verifierScenario11(
    (float) $transitions[0]->quantite_avant === 13.0
    && (float) $transitions[0]->quantite_apres
        === (float) $transitions[1]->quantite_avant
    && (float) $transitions[1]->quantite_apres === 25.0,
    'Enchaînement des quantités avant/après incohérent.'
);

$nombreMouvementsTotal = DB::table('support_mouvements_stocks')
    ->where('support_article_stock_id', $articleId)
    ->count();

verifierScenario11(
    $nombreMouvementsTotal === $mouvementsInitiaux + 2,
    'Nombre global de mouvements inattendu.'
);

echo "\n========== CONTROLES D'INTEGRITE ==========\n";

echo "Stock initial : 13.000\n";
echo "Stock final : "
    . number_format((float) $stockFinal->quantite, 3) . "\n";

echo "CMP final : "
    . number_format((float) $stockFinal->cout_moyen_pondere, 4) . "\n";

echo "Valeur finale : "
    . number_format((float) $stockFinal->valeur_stock, 2)
    . " FCFA\n";

echo "Mouvements créés : {$mouvements->count()}\n";

echo "\n[OK] LES DEUX RECEPTIONS SONT VALIDEES.\n";
echo "[OK] LES DEUX MOUVEMENTS ENTREE SONT PRESENTS.\n";
echo "[OK] QUANTITES ET COUTS UNITAIRES COHERENTS.\n";
echo "[OK] AUCUNE PERTE DE MISE A JOUR.\n";
echo "[OK] CMP ET VALORISATION COHERENTS.\n";
echo "[OK] ENCHAINEMENT DES QUANTITES COHERENT.\n";
echo "[OK] STATUTS DOCUMENTAIRES COHERENTS.\n";
echo "[OK] AUCUN VERROU RESIDUEL.\n";

echo "\nSCENARIO 11 : SUCCES\n";

foreach (glob($base . '.*') ?: [] as $fichier) {
    @unlink($fichier);
}
