<?php

use App\Models\Support\SupportArticleStock;
use App\Models\Support\SupportStock;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\DB;

require __DIR__ . '/../../vendor/autoload.php';

$app = require __DIR__ . '/../../bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

if (DB::connection()->getDatabaseName() !== 'sigof_stock_concurrency_test') {
    throw new RuntimeException('BASE DE TEST INCORRECTE : abandon.');
}

DB::transaction(function () {
    $article1 = SupportArticleStock::query()->findOrFail(46);

    if (!$article1->actif || $article1->deleted_at !== null) {
        throw new RuntimeException('Article 46 inactif ou supprimé.');
    }

    $existant = SupportArticleStock::query()
        ->where('reference', 'TEST-CONC-002')
        ->first();

    if ($existant) {
        throw new RuntimeException(
            'L’article TEST-CONC-002 existe déjà : aucune création.'
        );
    }

    $article2 = SupportArticleStock::query()->create([
        'reference' => 'TEST-CONC-002',
        'designation' => 'Deuxième article test concurrence',
        'support_categorie_stock_id' => $article1->support_categorie_stock_id,
        'support_unite_stock_id' => $article1->support_unite_stock_id,
        'description' => 'Article dédié au scénario 5 de concurrence.',
        'stock_minimum' => 0,
        'actif' => true,
        'user_create_id' => 2954,
    ]);

    SupportStock::query()->create([
        'support_article_stock_id' => $article2->id,
        'quantite' => '30.000',
        'cout_moyen_pondere' => '10000.0000',
        'valeur_stock' => '300000.0000',
        'verrouille' => false,
        'support_inventaire_id' => null,
    ]);

    echo "Article créé : {$article2->id}\n";
    echo "Référence : {$article2->reference}\n";
    echo "Stock initial : 30 unités\n";
});

echo "\nPREPARATION SCENARIO 5 : SUCCES\n";
