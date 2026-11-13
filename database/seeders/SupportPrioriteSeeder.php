<?php

namespace Database\Seeders;

use App\Models\Support\SupportPriorite;
use Illuminate\Database\Seeder;

class SupportPrioriteSeeder extends Seeder
{
    public function run(): void
    {
        $priorites = [
            [
                'code' => 'P1',
                'libelle' => 'Critique',
                'description' => 'Interruption majeure d’un service informatique ou incident de sécurité grave nécessitant une intervention immédiate.',
                'delai_prise_en_charge_minutes' => 0,
                'ordre' => 1,
            ],
            [
                'code' => 'P2',
                'libelle' => 'Haute',
                'description' => 'Service fortement perturbé ou plusieurs utilisateurs affectés.',
                'delai_prise_en_charge_minutes' => 60,
                'ordre' => 2,
            ],
            [
                'code' => 'P3',
                'libelle' => 'Normale',
                'description' => 'Dysfonctionnement limité ou demande courante.',
                'delai_prise_en_charge_minutes' => 240,
                'ordre' => 3,
            ],
            [
                'code' => 'P4',
                'libelle' => 'Basse',
                'description' => 'Demande non urgente, amélioration ou assistance pouvant être planifiée.',
                'delai_prise_en_charge_minutes' => 480,
                'ordre' => 4,
            ],
        ];

        foreach ($priorites as $priorite) {
            SupportPriorite::firstOrCreate(
                ['code' => $priorite['code']],
                array_merge($priorite, [
                    'actif' => true,
                ])
            );
        }

        $this->command->info('Les priorités du support ont été initialisées.');
    }
}
