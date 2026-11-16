<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Support\SupportCategorieStock;
use App\Models\Support\SupportUniteStock;
use App\Models\Support\SupportArticleStock;
use App\Models\Support\SupportFournisseurStock;
use App\Services\Support\SupportStockReferenceService;

class SupportStockCatalogueSeeder extends Seeder
{
    public function run(): void
    {
        $userId = 1;

        DB::transaction(function () use ($userId) {

            /*
            |--------------------------------------------------------------------------
            | 1. CATEGORIES
            |--------------------------------------------------------------------------
            */

            $categories = [
                'CAT-INFO' => [
                    'libelle' => 'Ordinateurs et serveurs',
                    'description' => 'Ordinateurs, serveurs et équipements informatiques.',
                ],
                'CAT-PERI' => [
                    'libelle' => 'Périphériques informatiques',
                    'description' => 'Équipements périphériques et accessoires.',
                ],
                'CAT-RESE' => [
                    'libelle' => 'Équipements réseau',
                    'description' => 'Équipements actifs et infrastructures réseau.',
                ],
                'CAT-CABL' => [
                    'libelle' => 'Câblage et connectique',
                    'description' => 'Câbles, connecteurs et accessoires de câblage.',
                ],
                'CAT-IMPR' => [
                    'libelle' => 'Impression et consommables',
                    'description' => 'Imprimantes et consommables associés.',
                ],
                'CAT-STOC' => [
                    'libelle' => 'Stockage informatique',
                    'description' => 'Supports de stockage et sauvegarde.',
                ],
                'CAT-ENER' => [
                    'libelle' => 'Énergie et alimentation',
                    'description' => 'Onduleurs et équipements d’alimentation.',
                ],
                'CAT-LOGI' => [
                    'libelle' => 'Logiciels et licences',
                    'description' => 'Logiciels, licences et abonnements informatiques.',
                ],
            ];

            $categoryIds = [];

            foreach ($categories as $code => $data) {
                $categorie = SupportCategorieStock::withTrashed()
                    ->firstOrNew(['code' => $code]);

                $categorie->fill([
                    'libelle' => $data['libelle'],
                    'description' => $data['description'],
                    'actif' => true,
                    'ordre' => count($categoryIds) + 1,
                    'user_create_id' => $categorie->exists
                        ? $categorie->user_create_id
                        : $userId,
                    'user_update_id' => $userId,
                ]);

                $categorie->deleted_at = null;
                $categorie->save();

                $categoryIds[$code] = $categorie->id;
            }

            /*
            |--------------------------------------------------------------------------
            | 2. UNITES DE MESURE
            |--------------------------------------------------------------------------
            */

            $unites = [
                'U-UNITE' => ['Unité', 'U', false],
                'U-PIECE' => ['Pièce', 'Pce', false],
                'U-BOITE' => ['Boîte', 'Bte', false],
                'U-PAQUET' => ['Paquet', 'Pqt', false],
                'U-RAMETTE' => ['Ramette', 'Rmt', false],
                'U-ROULEAU' => ['Rouleau', 'Rlx', false],
                'U-METRE' => ['Mètre', 'm', true],
                'U-LITRE' => ['Litre', 'L', true],
            ];

            $unitIds = [];

            foreach ($unites as $code => $data) {
                $unite = SupportUniteStock::withTrashed()
                    ->firstOrNew(['code' => $code]);

                $unite->fill([
                    'libelle' => $data[0],
                    'symbole' => $data[1],
                    'autorise_decimales' => $data[2],
                    'actif' => true,
                    'ordre' => count($unitIds) + 1,
                    'user_create_id' => $unite->exists
                        ? $unite->user_create_id
                        : $userId,
                    'user_update_id' => $userId,
                ]);

                $unite->deleted_at = null;
                $unite->save();

                $unitIds[$code] = $unite->id;
            }

            /*
            |--------------------------------------------------------------------------
            | 3. ARTICLES
            |--------------------------------------------------------------------------
            */

            $articles = [
                [
                    'designation' => 'Ordinateur portable',
                    'categorie' => 'CAT-INFO',
                    'unite' => 'U-UNITE',
                    'description' => 'Ordinateur portable destiné aux utilisateurs de l’ONFP.',
                ],
                [
                    'designation' => 'Ordinateur de bureau',
                    'categorie' => 'CAT-INFO',
                    'unite' => 'U-UNITE',
                    'description' => 'Unité centrale ou poste informatique fixe.',
                ],
                [
                    'designation' => 'Écran informatique',
                    'categorie' => 'CAT-PERI',
                    'unite' => 'U-UNITE',
                    'description' => 'Moniteur informatique.',
                ],
                [
                    'designation' => 'Clavier USB',
                    'categorie' => 'CAT-PERI',
                    'unite' => 'U-PIECE',
                    'description' => 'Clavier filaire USB.',
                ],
                [
                    'designation' => 'Souris USB',
                    'categorie' => 'CAT-PERI',
                    'unite' => 'U-PIECE',
                    'description' => 'Souris informatique filaire USB.',
                ],
                [
                    'designation' => 'Onduleur',
                    'categorie' => 'CAT-ENER',
                    'unite' => 'U-UNITE',
                    'description' => 'Onduleur de protection électrique.',
                ],
                [
                    'designation' => 'Switch réseau',
                    'categorie' => 'CAT-RESE',
                    'unite' => 'U-UNITE',
                    'description' => 'Commutateur réseau Ethernet.',
                ],
                [
                    'designation' => 'Point d’accès Wi-Fi',
                    'categorie' => 'CAT-RESE',
                    'unite' => 'U-UNITE',
                    'description' => 'Borne d’accès réseau sans fil.',
                ],
                [
                    'designation' => 'Routeur',
                    'categorie' => 'CAT-RESE',
                    'unite' => 'U-UNITE',
                    'description' => 'Équipement de routage réseau.',
                ],
                [
                    'designation' => 'Câble réseau RJ45',
                    'categorie' => 'CAT-CABL',
                    'unite' => 'U-METRE',
                    'description' => 'Câble réseau Ethernet cuivre.',
                ],
                [
                    'designation' => 'Connecteur RJ45',
                    'categorie' => 'CAT-CABL',
                    'unite' => 'U-PIECE',
                    'description' => 'Connecteur réseau RJ45.',
                ],
                [
                    'designation' => 'Disque SSD',
                    'categorie' => 'CAT-STOC',
                    'unite' => 'U-PIECE',
                    'description' => 'Support de stockage SSD.',
                ],
                [
                    'designation' => 'Disque dur externe',
                    'categorie' => 'CAT-STOC',
                    'unite' => 'U-PIECE',
                    'description' => 'Disque dur externe de sauvegarde.',
                ],
                [
                    'designation' => 'Clé USB',
                    'categorie' => 'CAT-STOC',
                    'unite' => 'U-PIECE',
                    'description' => 'Support de stockage USB amovible.',
                ],
                [
                    'designation' => 'Toner d’imprimante',
                    'categorie' => 'CAT-IMPR',
                    'unite' => 'U-PIECE',
                    'description' => 'Cartouche de toner pour imprimante laser.',
                ],
                [
                    'designation' => 'Cartouche d’encre',
                    'categorie' => 'CAT-IMPR',
                    'unite' => 'U-PIECE',
                    'description' => 'Cartouche d’encre pour imprimante.',
                ],
                [
                    'designation' => 'Papier A4',
                    'categorie' => 'CAT-IMPR',
                    'unite' => 'U-RAMETTE',
                    'description' => 'Papier blanc format A4.',
                ],
            ];

            $references = app(SupportStockReferenceService::class);

            foreach ($articles as $data) {

                $article = SupportArticleStock::query()
                    ->where('designation', $data['designation'])
                    ->first();

                /*
                 * Réutilisation de l'article fictif identifié
                 * lors de notre audit initial.
                 */
                if (
                    !$article &&
                    $data['designation'] === 'Ordinateur portable'
                ) {
                    $article = SupportArticleStock::query()
                        ->where('designation', 'Non placeat esse si')
                        ->first();
                }

                $isNew = !$article;

                if (!$article) {
                    $article = new SupportArticleStock();
                    $article->reference = $references->generer('ARTICLE');
                }

                $article->fill([
                    'designation' => $data['designation'],
                    'support_categorie_stock_id' =>
                        $categoryIds[$data['categorie']],
                    'support_unite_stock_id' =>
                        $unitIds[$data['unite']],
                    'description' => $data['description'],
                    'stock_minimum' => 0,
                    'stock_maximum' => null,
                    'actif' => true,
                    'user_create_id' => $isNew
                        ? $userId
                        : $article->user_create_id,
                    'user_update_id' => $isNew
                        ? null
                        : $userId,
                ]);

                $article->save();
            }

            /*
            |--------------------------------------------------------------------------
            | 4. NETTOYAGE LOGIQUE DES DONNEES DE TEST
            |--------------------------------------------------------------------------
            |
            | Les anciennes catégories et unités non utilisées par le
            | catalogue officiel sont désactivées et archivées.
            |
            | Les fournisseurs fictifs sont archivés. Aucun fournisseur
            | réel n'est créé sans information vérifiée.
            */

            SupportCategorieStock::query()
                ->whereNotIn('code', array_keys($categories))
                ->whereDoesntHave('articles')
                ->get()
                ->each(function ($categorie) use ($userId) {
                    $categorie->update([
                        'actif' => false,
                        'user_update_id' => $userId,
                    ]);

                    $categorie->delete();
                });

            SupportUniteStock::query()
                ->whereNotIn('code', array_keys($unites))
                ->whereDoesntHave('articles')
                ->get()
                ->each(function ($unite) use ($userId) {
                    $unite->update([
                        'actif' => false,
                        'user_update_id' => $userId,
                    ]);

                    $unite->delete();
                });

            SupportFournisseurStock::query()
                ->get()
                ->each(function ($fournisseur) use ($userId) {
                    if ($fournisseur->documents()->exists()) {
                        return;
                    }

                    $fournisseur->update([
                        'actif' => false,
                        'user_update_id' => $userId,
                    ]);

                    $fournisseur->delete();
                });
        });

        $this->command->info(
            'Catalogue informatique ONFP initialisé avec succès.'
        );
    }
}
