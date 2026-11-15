<?php

namespace Database\Seeders;

use App\Models\Support\SupportCategorieEquipement;
use Illuminate\Database\Seeder;

class SupportCategorieEquipementSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'code' => 'ORDINATEURS',
                'libelle' => 'Ordinateurs',
                'description' => 'Ordinateurs fixes, portables et stations de travail.',
                'ordre' => 1,
            ],
            [
                'code' => 'ECRANS',
                'libelle' => 'Écrans',
                'description' => 'Moniteurs et écrans informatiques.',
                'ordre' => 2,
            ],
            [
                'code' => 'IMPRIMANTES_SCANNERS',
                'libelle' => 'Imprimantes et scanners',
                'description' => 'Imprimantes, photocopieurs et scanners.',
                'ordre' => 3,
            ],
            [
                'code' => 'SERVEURS',
                'libelle' => 'Serveurs',
                'description' => 'Serveurs physiques et équipements associés.',
                'ordre' => 4,
            ],
            [
                'code' => 'RESEAU',
                'libelle' => 'Équipements réseau',
                'description' => 'Switchs, routeurs, points d’accès et pare-feu.',
                'ordre' => 5,
            ],
            [
                'code' => 'TELEPHONES',
                'libelle' => 'Téléphones',
                'description' => 'Téléphones fixes et smartphones professionnels.',
                'ordre' => 6,
            ],
            [
                'code' => 'TABLETTES',
                'libelle' => 'Tablettes',
                'description' => 'Tablettes numériques professionnelles.',
                'ordre' => 7,
            ],
            [
                'code' => 'STOCKAGE',
                'libelle' => 'Équipements de stockage',
                'description' => 'NAS, baies de stockage et unités externes durables.',
                'ordre' => 8,
            ],
            [
                'code' => 'ONDULEURS',
                'libelle' => 'Onduleurs',
                'description' => 'Onduleurs et équipements de protection électrique.',
                'ordre' => 9,
            ],
            [
                'code' => 'VIDEOPROJECTEURS',
                'libelle' => 'Vidéoprojecteurs',
                'description' => 'Vidéoprojecteurs et équipements de projection.',
                'ordre' => 10,
            ],
            [
                'code' => 'AUTRES_EQUIPEMENTS',
                'libelle' => 'Autres équipements',
                'description' => 'Autres équipements informatiques durables.',
                'ordre' => 11,
            ],
        ];

        foreach ($categories as $donnees) {
            $categorie = SupportCategorieEquipement::withTrashed()
                ->firstOrNew(['code' => $donnees['code']]);

            $categorie->fill([
                'libelle' => $donnees['libelle'],
                'description' => $donnees['description'],
                'actif' => true,
                'ordre' => $donnees['ordre'],
            ]);

            if ($categorie->exists && $categorie->trashed()) {
                $categorie->restore();
            } else {
                $categorie->save();
            }
        }
    }
}
