<?php

namespace Database\Seeders;

use App\Models\LanguesSpecialisation;
use Illuminate\Database\Seeder;

class LanguesSpecialisationSeeder extends Seeder
{
    public function run(): void
    {
        $langues = [
            [
                'nom' => 'Anglais (profil bilingue)',
                'code' => 'anglais_bilingue',
                'postes_disponibles' => 3,
                'niveau_langue_requis' => 'C1',
                'niveau_francais_requis' => 'C1',
                'diplome_minimum' => 'Licence / Master ou niveau équivalent',
                'certification_recommandee' => 'TOEIC',
            ],
            [
                'nom' => 'Arabe',
                'code' => 'arabe',
                'postes_disponibles' => 6,
                'niveau_langue_requis' => 'C1',
                'niveau_francais_requis' => 'C1',
                'diplome_minimum' => 'Licence / Master ou niveau équivalent',
                'certification_recommandee' => null,
            ],
            [
                'nom' => 'Espagnol',
                'code' => 'espagnol',
                'postes_disponibles' => 7,
                'niveau_langue_requis' => 'C1',
                'niveau_francais_requis' => 'C1',
                'diplome_minimum' => 'Licence / Master ou niveau équivalent',
                'certification_recommandee' => 'DELE',
            ],
            [
                'nom' => 'Portugais',
                'code' => 'portugais',
                'postes_disponibles' => 4,
                'niveau_langue_requis' => 'C1',
                'niveau_francais_requis' => 'C1',
                'diplome_minimum' => 'Licence / Master ou niveau équivalent',
                'certification_recommandee' => null,
            ],
            [
                'nom' => 'Chinois (Mandarin)',
                'code' => 'chinois',
                'postes_disponibles' => 4,
                'niveau_langue_requis' => 'C1',
                'niveau_francais_requis' => 'C1',
                'diplome_minimum' => 'Licence / Master ou niveau équivalent',
                'certification_recommandee' => 'HSK 5+',
            ],
            [
                'nom' => 'Japonais',
                'code' => 'japonais',
                'postes_disponibles' => 4,
                'niveau_langue_requis' => 'C1',
                'niveau_francais_requis' => 'C1',
                'diplome_minimum' => 'Licence / Master ou niveau équivalent',
                'certification_recommandee' => 'JLPT N2+',
            ],
            [
                'nom' => 'Coréen',
                'code' => 'coreen',
                'postes_disponibles' => 2,
                'niveau_langue_requis' => 'C1',
                'niveau_francais_requis' => 'C1',
                'diplome_minimum' => 'Licence / Master ou niveau équivalent',
                'certification_recommandee' => 'TOPIK',
            ],
            [
                'nom' => 'Allemand',
                'code' => 'allemand',
                'postes_disponibles' => 4,
                'niveau_langue_requis' => 'C1',
                'niveau_francais_requis' => 'C1',
                'diplome_minimum' => 'Licence / Master ou niveau équivalent',
                'certification_recommandee' => null,
            ],
            [
                'nom' => 'Russe',
                'code' => 'russe',
                'postes_disponibles' => 2,
                'niveau_langue_requis' => 'C1',
                'niveau_francais_requis' => 'C1',
                'diplome_minimum' => 'Licence / Master ou niveau équivalent',
                'certification_recommandee' => null,
            ],
            [
                'nom' => 'Italien',
                'code' => 'italien',
                'postes_disponibles' => 4,
                'niveau_langue_requis' => 'C1',
                'niveau_francais_requis' => 'C1',
                'diplome_minimum' => 'Licence / Master ou niveau équivalent',
                'certification_recommandee' => null,
            ],
        ];

        foreach ($langues as $langue) {
            LanguesSpecialisation::updateOrCreate(['code' => $langue['code']], $langue);
        }
    }
}
