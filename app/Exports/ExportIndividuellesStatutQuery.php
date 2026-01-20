<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExportIndividuellesStatutQuery implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $query;

    public function __construct($query)
    {
        $this->query = $query; // on reçoit déjà le QueryBuilder filtré
    }

    public function query()
    {
        return $this->query;
    }

    public function headings(): array
    {
        return [
            'CIN',
            'Civilité',
            'Prénom',
            'Nom',
            'Date naissance',
            'Lieu naissance',
            'Téléphone 1',
            'Téléphone 2',
            'Email',
            'Département',
            'Région',
            'Adresse',
            'Module',
            'Statut',
            'Date dépôt',
        ];
    }

    public function map($individuelle): array
    {
        return [
            $individuelle?->user?->cin,
            $individuelle?->user?->civilite,
            $individuelle?->user?->firstname,
            $individuelle?->user?->name,
            $individuelle?->user?->date_naissance?->format('d/m/Y'),
            $individuelle?->user?->lieu_naissance,
            $individuelle?->user?->telephone,
            $individuelle?->user?->telephone_secondaire,
            $individuelle?->user?->email,
            optional($individuelle?->departement)?->nom,
            optional($individuelle?->departement?->region)?->nom,
            $individuelle?->user?->adresse,
            optional($individuelle?->module)?->name,
            ucfirst(str_replace('_', ' ', $individuelle?->statut)),
            optional($individuelle?->date_depot)?->format('d/m/Y'),
        ];
    }
}
