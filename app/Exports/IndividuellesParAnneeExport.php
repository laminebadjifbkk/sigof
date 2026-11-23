<?php

namespace App\Exports;

use App\Models\Individuelle;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class IndividuellesParAnneeExport implements
    FromQuery,
    WithHeadings,
    WithMapping,
    ShouldAutoSize,
    WithStyles
{
    protected int $annee;
    private int $compteur = 0;

    public function __construct(int $annee)
    {
        $this->annee = $annee;
    }

    public function query()
    {
        return Individuelle::query()
            ->with([
                'user',
                'module',
                'region',
                'departement',
                'commune',
                'arrondissement',
                'etude',
                'diplome',
                'diplomespro',
            ])
            ->whereYear('date_depot', $this->annee)
            ->orderByDesc('date_depot');
    }

    public function headings(): array
    {
        return [
            'N°',
            'N° CIN',
            'Civilité',
            'Prénom',
            'Nom',
            'Date de naissance',
            'Lieu de naissance',
            'Adresse',
            'Téléphone',
            'Email',
            'Région',
            'Département',
            /* 'Commune',
            'Arrondissement', */
            'Module sollicité',
            'Date de dépôt',
            'Statut',
            'Situation familiale',
            'Situation professionnelle',
            'Niveau d’étude',
            'Diplôme académique',
            'Autre diplôme académique',
            'Option diplôme académique',
            'Établissement académique',
            'Diplôme professionnel',
            'Autre diplôme professionnel',
            'Spécialité professionnelle',
            'Établissement professionnel',
            'Projet après formation',
            'Qualification',
            'Expérience',
            'Projet professionnel',
        ];
    }

    public function map($individuelle): array
    {
        $user = $individuelle->user;

        return [
            ++$this->compteur,
            $user?->cin,
            $user?->civilite,
            $user?->firstname,
            $user?->name,
            $user?->date_naissance?->format('d/m/Y'),
            $user?->lieu_naissance,
            $individuelle->adresse ?? $user?->adresse,
            $individuelle->telephone ?? $user?->telephone,
            $user?->email,
            $individuelle->region?->nom,
            $individuelle->departement?->nom,
            /* $individuelle->commune?->nom,
            $individuelle->arrondissement?->nom, */
            $individuelle->module?->name,
            $individuelle->date_depot?->format('d/m/Y H:i'),
            $individuelle->statut,
            $user?->situation_familiale,
            $user?->situation_professionnelle,
            $individuelle->niveau_etude,
            $individuelle->diplome_academique,
            $individuelle->autre_diplome_academique,
            $individuelle->option_diplome_academique,
            $individuelle->etablissement_academique,
            $individuelle->diplome_professionnel,
            $individuelle->autre_diplome_professionnel,
            $individuelle->specialite_diplome_professionnel,
            $individuelle->etablissement_professionnel,
            $individuelle->projet_poste_formation,
            $individuelle->qualification,
            $individuelle->experience,
            $individuelle->projetprofessionnel,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->freezePane('A2');

        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => 'solid',
                    'startColor' => ['rgb' => 'F28500'],
                ],
            ],
        ];
    }
}
