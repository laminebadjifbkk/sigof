<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Support\SupportCategory;

class SupportCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'libelle' => 'Matériel informatique',
                'description' => 'Ordinateurs, écrans, claviers, souris et autres équipements informatiques.',
                'ordre' => 1,
            ],
            [
                'libelle' => 'Logiciels et applications',
                'description' => 'Installation, configuration, mise à jour et dysfonctionnement des logiciels et applications.',
                'ordre' => 2,
            ],
            [
                'libelle' => 'Réseau et connexion Internet',
                'description' => 'Problèmes de réseau local, Wi-Fi, connexion Internet et accès réseau.',
                'ordre' => 3,
            ],
            [
                'libelle' => 'Téléphonie',
                'description' => 'Téléphones fixes, mobiles, lignes téléphoniques et équipements associés.',
                'ordre' => 4,
            ],
            [
                'libelle' => 'Messagerie et comptes utilisateurs',
                'description' => 'Messagerie professionnelle, création de comptes et problèmes de connexion.',
                'ordre' => 5,
            ],
            [
                'libelle' => 'Accès et habilitations',
                'description' => 'Gestion des accès aux applications, plateformes, dossiers et ressources informatiques.',
                'ordre' => 6,
            ],
            [
                'libelle' => 'Impression et numérisation',
                'description' => 'Imprimantes, photocopieurs, scanners et problèmes d’impression ou de numérisation.',
                'ordre' => 7,
            ],
            [
                'libelle' => 'Sécurité informatique',
                'description' => 'Antivirus, logiciels malveillants, incidents de sécurité et protection des données.',
                'ordre' => 8,
            ],
            [
                'libelle' => 'Assistance et conseil informatique',
                'description' => 'Accompagnement, assistance aux utilisateurs et conseils informatiques.',
                'ordre' => 9,
            ],
            [
                'libelle' => 'Autres demandes',
                'description' => 'Toute demande informatique ne correspondant pas aux catégories précédentes.',
                'ordre' => 10,
            ],
        ];

        foreach ($categories as $category) {
            SupportCategory::firstOrCreate(
                ['libelle' => $category['libelle']],
                [
                    'description' => $category['description'],
                    'ordre' => $category['ordre'],
                    'actif' => true,
                ]
            );
        }

        $this->command->info('Les catégories du module Support ont été initialisées.');
    }
}
