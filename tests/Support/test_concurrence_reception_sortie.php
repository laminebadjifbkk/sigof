<?php

use App\Services\Support\SupportDocumentStockService;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

require __DIR__ . '/../../vendor/autoload.php';

$app = require __DIR__ . '/../../bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

function verifierScenario10(bool $condition, string $message): void
{
    if (!$condition) {
        throw new RuntimeException("ECHEC : {$message}");
    }
}

verifierScenario10(
    DB::connection()->getDatabaseName() === 'sigof_stock_concurrency_test',
    'Mauvaise base de données : arrêt du test.'
);

verifierScenario10(
    function_exists('pcntl_fork'),
    'Extension PCNTL indisponible.'
);

$articleId = 47;
$stockId = 45;
$fournisseurId = 21;
$employeId = 11;
$createurId = 2954;
$validateurId = 2955;

$stockInitial = DB::table('support_stocks')
    ->where('id', $stockId)
    ->where('support_article_stock_id', $articleId)
    ->first();

verifierScenario10($stockInitial !== null, 'Stock introuvable.');

verifierScenario10(
    (float) $stockInitial->quantite === 3.0,
    'Stock initial attendu : 3 unités.'
);

verifierScenario10(
    (float) $stockInitial->cout_moyen_pondere === 10000.0,
    'CMP initial attendu : 10 000.'
);

verifierScenario10(
    (float) $stockInitial->valeur_stock === 30000.0,
    'Valeur initiale attendue : 30 000.'
);

verifierScenario10(
    !(bool) $stockInitial->verrouille,
    'Le stock est déjà verrouillé.'
);

$fournisseur = DB::table('support_fournisseurs_stocks')
    ->where('id', $fournisseurId)
    ->whereNull('deleted_at')
    ->where('actif', 1)
    ->first();

verifierScenario10(
    $fournisseur !== null,
    'Fournisseur de test actif introuvable.'
);

$employe = DB::table('employees')
    ->where('id', $employeId)
    ->where('users_id', $createurId)
    ->whereNull('deleted_at')
    ->first();

verifierScenario10(
    $employe !== null,
    'Employé destinataire introuvable ou incorrect.'
);

$mouvementsInitiaux = DB::table('support_mouvements_stocks')
    ->where('support_article_stock_id', $articleId)
    ->count();

$service = app(SupportDocumentStockService::class);

echo "========== PREPARATION DE LA RECEPTION ==========\n";

$reception = $service->creerDocument([
    'type_document' => 'RECEPTION',
    'date_document' => now()->toDateString(),
    'support_fournisseur_stock_id' => $fournisseurId,
    'motif' => 'SCENARIO 10 - RECEPTION CONCURRENTE',
], $createurId);

$service->ajouterLigne($reception, [
    'support_article_stock_id' => $articleId,
    'quantite' => '10.000',
    'cout_unitaire' => '10000',
    'observations' => 'Réception concurrente de 10 unités.',
], $createurId);

$service->soumettre($reception, $createurId);

echo "Réception soumise : document {$reception->id}\n";

echo "\n========== PREPARATION DE LA SORTIE ==========\n";

$sortie = $service->creerDocument([
    'type_document' => 'SORTIE',
    'date_document' => now()->toDateString(),
    'employee_id' => $employeId,
    'motif' => 'SCENARIO 10 - SORTIE CONCURRENTE',
], $createurId);

$service->ajouterLigne($sortie, [
    'support_article_stock_id' => $articleId,
    'quantite' => '12.000',
    'cout_unitaire' => '0',
    'observations' => 'Sortie concurrente de 12 unités.',
], $createurId);

$service->soumettre($sortie, $createurId);

echo "Sortie soumise : document {$sortie->id}\n";

$documents = [
    'reception' => (int) $reception->id,
    'sortie' => (int) $sortie->id,
];

$token = bin2hex(random_bytes(8));
$base = sys_get_temp_dir() . '/sigof_scenario10_' . $token;
$gate = $base . '.go';
$children = [];

echo "\n========== LANCEMENT DES PROCESSUS ==========\n";

DB::disconnect('mysql');

foreach (['reception', 'sortie'] as $i => $type) {
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

    verifierScenario10(
        file_exists($fichier),
        "Résultat absent pour le processus {$i}."
    );

    $resultat = json_decode(file_get_contents($fichier), true);

    verifierScenario10(
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

verifierScenario10(
    count($erreursProcessus) === 0,
    'Un processus enfant a terminé avec une erreur technique.'
);

verifierScenario10(
    $resultats['reception']['resultat'] === 'VALIDEE',
    'La réception doit obligatoirement être validée.'
);

verifierScenario10(
    in_array(
        $resultats['sortie']['resultat'],
        ['VALIDEE', 'REFUSEE'],
        true
    ),
    'Résultat inattendu pour la sortie.'
);

$stockFinal = DB::table('support_stocks')
    ->where('id', $stockId)
    ->where('support_article_stock_id', $articleId)
    ->first();

verifierScenario10($stockFinal !== null, 'Stock final introuvable.');

$sortieValidee = $resultats['sortie']['resultat'] === 'VALIDEE';

$quantiteAttendue = $sortieValidee ? 1.0 : 13.0;
$valeurAttendue = $quantiteAttendue * 10000;

verifierScenario10(
    (float) $stockFinal->quantite === $quantiteAttendue,
    "Quantité finale attendue : {$quantiteAttendue}."
);

verifierScenario10(
    (float) $stockFinal->cout_moyen_pondere === 10000.0,
    'CMP final incorrect.'
);

verifierScenario10(
    (float) $stockFinal->valeur_stock === $valeurAttendue,
    'Valorisation finale incorrecte.'
);

verifierScenario10(
    (float) $stockFinal->quantite >= 0,
    'Stock négatif détecté.'
);

verifierScenario10(
    !(bool) $stockFinal->verrouille,
    'Un verrou de stock est resté actif.'
);

$documentsFinaux = DB::table('support_documents_stocks')
    ->whereIn('id', array_values($documents))
    ->get(['id', 'type_document', 'statut', 'user_validation_id'])
    ->keyBy('id');

$docReception = $documentsFinaux->get($documents['reception']);
$docSortie = $documentsFinaux->get($documents['sortie']);

verifierScenario10(
    $docReception !== null
    && $docReception->statut === 'VALIDEE'
    && (int) $docReception->user_validation_id === $validateurId,
    'Statut ou validateur de la réception incorrect.'
);

verifierScenario10(
    $docSortie !== null
    && (
        ($sortieValidee
            && $docSortie->statut === 'VALIDEE'
            && (int) $docSortie->user_validation_id === $validateurId)
        ||
        (!$sortieValidee
            && $docSortie->statut === 'EN_ATTENTE_VALIDATION')
    ),
    'Statut final de la sortie incohérent.'
);

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
        'l.support_document_stock_id',
    ]);

$nombreMouvementsAttendu = $sortieValidee ? 2 : 1;

verifierScenario10(
    $mouvements->count() === $nombreMouvementsAttendu,
    "Nombre de mouvements attendu : {$nombreMouvementsAttendu}."
);

$mouvementReception = $mouvements->first(
    fn ($m) => (int) $m->support_document_stock_id
        === $documents['reception']
);

verifierScenario10(
    $mouvementReception !== null,
    'Mouvement de réception absent.'
);

verifierScenario10(
    $mouvementReception->type_mouvement === 'ENTREE'
    && (float) $mouvementReception->quantite === 10.0
    && (float) $mouvementReception->quantite_avant === 3.0
    && (float) $mouvementReception->quantite_apres === 13.0
    && (float) $mouvementReception->cout_unitaire === 10000.0,
    'Données du mouvement de réception incohérentes.'
);

if ($sortieValidee) {
    $mouvementSortie = $mouvements->first(
        fn ($m) => (int) $m->support_document_stock_id
            === $documents['sortie']
    );

    verifierScenario10(
        $mouvementSortie !== null
        && $mouvementSortie->type_mouvement === 'SORTIE'
        && (float) $mouvementSortie->quantite === 12.0
        && (float) $mouvementSortie->quantite_avant === 13.0
        && (float) $mouvementSortie->quantite_apres === 1.0,
        'Données du mouvement de sortie incohérentes.'
    );
}

$nombreMouvementsTotal = DB::table('support_mouvements_stocks')
    ->where('support_article_stock_id', $articleId)
    ->count();

verifierScenario10(
    $nombreMouvementsTotal === $mouvementsInitiaux + $nombreMouvementsAttendu,
    'Nombre global de mouvements inattendu.'
);

echo "\n========== CONTROLES D'INTEGRITE ==========\n";

echo "Stock initial : 3.000\n";
echo "Stock final : " . number_format((float) $stockFinal->quantite, 3) . "\n";
echo "CMP final : " . number_format((float) $stockFinal->cout_moyen_pondere, 4) . "\n";
echo "Valeur finale : " . number_format((float) $stockFinal->valeur_stock, 2) . " FCFA\n";
echo "Mouvements créés : {$nombreMouvementsAttendu}\n";
echo "Réception : VALIDEE\n";
echo "Sortie : " . $resultats['sortie']['resultat'] . "\n";

echo "\n[OK] RECEPTION VALIDEE.\n";
echo "[OK] RESULTAT DE LA SORTIE COHERENT AVEC L'ORDRE DES VERROUS.\n";
echo "[OK] AUCUN DEPASSEMENT DU STOCK.\n";
echo "[OK] CMP ET VALORISATION COHERENTS.\n";
echo "[OK] MOUVEMENTS ET QUANTITES AVANT/APRES COHERENTS.\n";
echo "[OK] STATUTS DOCUMENTAIRES COHERENTS.\n";
echo "[OK] AUCUN VERROU RESIDUEL.\n";

echo "\nSCENARIO 10 : SUCCES\n";

// Suppression des seuls fichiers temporaires du scénario.
foreach (glob($base . '.*') ?: [] as $fichier) {
    @unlink($fichier);
}
