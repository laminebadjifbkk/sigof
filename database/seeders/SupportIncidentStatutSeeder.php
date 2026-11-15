<?php

namespace Database\Seeders;

use App\Models\Support\SupportIncidentStatut;
use Illuminate\Database\Seeder;

class SupportIncidentStatutSeeder extends Seeder
{
    public function run(): void
    {
        $statuts = [
            ['code' => 'DECLARE', 'libelle' => 'Déclaré', 'description' => 'Incident enregistré et en attente de qualification.', 'est_terminal' => false],
            ['code' => 'QUALIFIE', 'libelle' => 'Qualifié', 'description' => 'Incident analysé et prêt à être pris en charge.', 'est_terminal' => false],
            ['code' => 'EN_COURS', 'libelle' => 'En cours', 'description' => 'Incident pris en charge et faisant l’objet d’une intervention.', 'est_terminal' => false],
            ['code' => 'EN_ATTENTE', 'libelle' => 'En attente', 'description' => 'Traitement suspendu dans l’attente d’un élément externe.', 'est_terminal' => false],
            ['code' => 'RESOLU', 'libelle' => 'Résolu', 'description' => 'Une solution a été apportée, en attente de vérification ou de clôture.', 'est_terminal' => false],
            ['code' => 'CLOTURE', 'libelle' => 'Clôturé', 'description' => 'Incident vérifié et définitivement clôturé.', 'est_terminal' => true],
        ];

        foreach ($statuts as $index => $statut) {
            SupportIncidentStatut::firstOrCreate(
                ['code' => $statut['code']],
                array_merge($statut, [
                    'actif' => true,
                    'ordre' => $index + 1,
                ])
            );
        }

        $this->command->info('Les statuts des incidents ont été initialisés.');
    }
}
