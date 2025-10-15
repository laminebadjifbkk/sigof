<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CorrectiveFilesSeeder extends Seeder
{
    public function run(): void
    {
        $documents = [
            ['legende' => 'Titre de propriété (titre foncier)', 'sigle' => 'Titre'],
            ['legende' => 'Bail', 'sigle' => 'Bail'],
            ['legende' => 'Contrat de location à usage professionnel', 'sigle' => 'Contrat'],
            ['legende' => 'Convention de partenariat', 'sigle' => 'Convention'],
            ['legende' => 'Organigramme', 'sigle' => 'Organigramme'],
            ['legende' => 'Quitus fiscal', 'sigle' => 'Quitus'],
            ['legende' => 'Permis de conduire', 'sigle' => 'Permis'],
            ['legende' => 'Carte professionnelle', 'sigle' => 'Carte'],
            ['legende' => 'Extrait de casier judiciaire', 'sigle' => 'Casier'],
            ['legende' => 'Extrait de naissance', 'sigle' => 'Extrait'],
            ['legende' => 'Justificatif de domicile', 'sigle' => 'Justificatif'],
            ['legende' => 'Certification', 'sigle' => 'Certification'],
            ['legende' => 'Relevé d\'identité bancaire', 'sigle' => 'RIB'],
            ['legende' => 'Attestation d\'assurance', 'sigle' => 'Assurance'],
            ['legende' => 'Attestation de stage', 'sigle' => 'Stage'],
            ['legende' => 'Lettre de motivation', 'sigle' => 'Lettre'],
        ];

        foreach ($documents as $doc) {
            DB::table('files')->insert([
                'legende'    => $doc['legende'],
                'sigle'      => $doc['sigle'],
                'users_id'   => null,
                'created_at' => now(),
                'updated_at' => now(),
                'uuid'       => Str::uuid(),
            ]);
        }
    }
}
