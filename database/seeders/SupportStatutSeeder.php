<?php

namespace Database\Seeders;

use App\Models\Support\SupportStatut;
use Illuminate\Database\Seeder;

class SupportStatutSeeder extends Seeder
{
    public function run(): void
    {
        $statuts = [
            ['code' => 'NOUVEAU', 'libelle' => 'Nouveau', 'description' => 'Ticket soumis et en attente de qualification.', 'est_terminal' => false],
            ['code' => 'OUVERT', 'libelle' => 'Ouvert', 'description' => 'Ticket enregistré et qualifié.', 'est_terminal' => false],
            ['code' => 'EN_COURS', 'libelle' => 'En cours', 'description' => 'Intervention en cours de traitement.', 'est_terminal' => false],
            ['code' => 'EN_ATTENTE', 'libelle' => 'En attente', 'description' => 'Traitement suspendu dans l’attente d’un élément externe.', 'est_terminal' => false],
            ['code' => 'RESOLU', 'libelle' => 'Résolu', 'description' => 'Solution apportée, en attente de confirmation ou clôture.', 'est_terminal' => false],
            ['code' => 'CLOTURE', 'libelle' => 'Clôturé', 'description' => 'Traitement définitivement terminé.', 'est_terminal' => true],
            ['code' => 'REJETE', 'libelle' => 'Rejeté', 'description' => 'Demande non recevable ou hors périmètre.', 'est_terminal' => true],
        ];

        foreach ($statuts as $index => $statut) {
            SupportStatut::firstOrCreate(
                ['code' => $statut['code']],
                array_merge($statut, [
                    'actif' => true,
                    'ordre' => $index + 1,
                ])
            );
        }

        $this->command->info('Les statuts du support ont été initialisés.');
    }
}
