<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ChauffeurMissionsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $missions;

    public function __construct($missions)
    {
        $this->missions = $missions;
    }

    public function collection()
    {
        return $this->missions;
    }

    public function headings(): array
    {
        return [
            'N°',
            'Référence',
            'Objet',
            'Date départ',
            'Date retour',
            'Nuitées',
            'Taux journalier',
            'Montant total',
            'Statut',
        ];
    }

    public function map($mission): array
    {
        static $i = 0;

        return [
            ++$i,
            $mission->reference,
            $mission->objet,
            $mission->date_depart->format('d/m/Y'),
            $mission->date_retour->format('d/m/Y'),
            $mission->nuitees,
            $mission->taux_journalier,
            $mission->indemnites_total,
            ucfirst(str_replace('_', ' ', $mission->statut)),
        ];
    }
}