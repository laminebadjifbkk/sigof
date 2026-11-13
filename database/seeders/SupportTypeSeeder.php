<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use App\Models\Support\SupportCategory;
use App\Models\Support\SupportType;

class SupportTypeSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'Matériel informatique' => [
                'Panne matérielle',
                'Demande de matériel',
                'Installation et configuration',
                'Remplacement d’équipement',
                'Problème de périphérique',
            ],

            'Logiciels et applications' => [
                'Installation de logiciel',
                'Mise à jour',
                'Activation de licence',
                'Dysfonctionnement logiciel',
            ],

            'Réseau et connexion Internet' => [
                'Absence de connexion',
                'Connexion lente',
                'Problème Wi-Fi',
                'Accès VPN',
                'Problème de câblage ou de prise réseau',
            ],

            'Téléphonie' => [
                'Panne téléphonique',
                'Demande de ligne ou de terminal',
                'Configuration téléphonique',
                'Problème de communication',
            ],

            'Messagerie et comptes utilisateurs' => [
                'Création ou modification de compte',
                'Mot de passe oublié',
                'Problème d’envoi ou de réception',
                'Quota de messagerie',
                'Signature électronique',
            ],

            'Accès et habilitations' => [
                'Demande d’accès',
                'Modification de droits',
                'Réinitialisation de compte',
                'Révocation d’accès',
            ],

            'Impression et numérisation' => [
                'Installation d’imprimante',
                'Panne d’impression',
                'Problème de numérisation',
                'Consommables',
            ],

            'Sécurité informatique' => [
                'Alerte antivirus',
                'Suspicion de phishing',
                'Suspicion de compromission',
                'Perte ou vol d’équipement',
                'Incident de sécurité',
            ],

            'Assistance et conseil informatique' => [
                'Assistance utilisateur',
                'Prise en main',
                'Formation',
                'Conseil et accompagnement',
            ],

            'Autres demandes' => [
                'Demande non répertoriée',
            ],
        ];

        DB::transaction(function () use ($data) {
            foreach ($data as $categoryLabel => $types) {
                $category = SupportCategory::query()
                    ->where('libelle', $categoryLabel)
                    ->first();

                if (!$category) {
                    throw new RuntimeException(
                        "Catégorie introuvable : {$categoryLabel}"
                    );
                }

                foreach ($types as $index => $label) {
                    SupportType::firstOrCreate(
                        [
                            'support_category_id' => $category->id,
                            'libelle' => $label,
                        ],
                        [
                            'description' => null,
                            'actif' => true,
                            'ordre' => $index + 1,
                        ]
                    );
                }
            }
        });

        $this->command->info(
            'Les types de demandes du module Support ont été initialisés.'
        );
    }
}
