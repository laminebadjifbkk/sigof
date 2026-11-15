<?php

namespace Database\Seeders;

use App\Models\Support\SupportIncidentType;
use Illuminate\Database\Seeder;

class SupportIncidentTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['code' => 'RESEAU', 'libelle' => 'Réseau', 'description' => 'Incidents liés au réseau local, au Wi-Fi, à Internet, au VPN et aux équipements réseau.'],
            ['code' => 'SERVEUR', 'libelle' => 'Serveur', 'description' => 'Incidents affectant les serveurs, les systèmes et les services d’infrastructure.'],
            ['code' => 'APPLICATION', 'libelle' => 'Application', 'description' => 'Incidents affectant les applications métiers, les plateformes et les logiciels.'],
            ['code' => 'SECURITE', 'libelle' => 'Sécurité informatique', 'description' => 'Incidents de cybersécurité, compromission, intrusion, malware, phishing ou fuite de données.'],
            ['code' => 'MATERIEL', 'libelle' => 'Matériel informatique', 'description' => 'Incidents affectant les ordinateurs, périphériques et équipements informatiques.'],
            ['code' => 'TELEPHONIE', 'libelle' => 'Téléphonie', 'description' => 'Incidents affectant la téléphonie fixe, mobile, les lignes et les équipements associés.'],
            ['code' => 'AUTRE', 'libelle' => 'Autre', 'description' => 'Tout incident technique ne correspondant pas aux types précédents.'],
        ];

        foreach ($types as $index => $type) {
            SupportIncidentType::firstOrCreate(
                ['code' => $type['code']],
                array_merge($type, [
                    'actif' => true,
                    'ordre' => $index + 1,
                ])
            );
        }

        $this->command->info('Les types d’incidents ont été initialisés.');
    }
}
