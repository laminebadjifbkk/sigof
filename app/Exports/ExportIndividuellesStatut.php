<?php

namespace App\Exports;

use App\Models\Individuelle;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExportIndividuellesStatut implements
    FromQuery,
    WithHeadings,
    WithMapping,
    ShouldAutoSize
{
    protected string $statut;

    public function __construct(string $statut)
    {
        $this->statut = $statut;
    }

    /**
     * Requête SQL (streaming, pas de get())
     */
    public function query()
    {
        return Individuelle::query()
            ->when($this->statut !== 'all', function ($q) {
                $q->where('statut', $this->statut);
            })
            ->orderBy('created_at', 'DESC');
    }

    /**
     * En-têtes Excel
     */
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

    /**
     * Mapping ligne par ligne
     */
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
            optional($individuelle?->date_depot)->format('d/m/Y'),
        ];
    }
}
