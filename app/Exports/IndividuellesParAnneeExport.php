<?php

namespace App\Exports;

use App\Models\Individuelle;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class IndividuellesParAnneeExport implements
    FromQuery,
    WithHeadings,
    WithMapping,
    WithStyles,
    WithColumnWidths,
    WithChunkReading
{
    protected int $annee;
    private int $compteur = 0;

    public function __construct(int $annee)
    {
        $this->annee = $annee;
    }

    public function query()
    {
        $debut = Carbon::create($this->annee, 1, 1)->startOfDay();
        $fin   = Carbon::create($this->annee, 12, 31)->endOfDay();

        return Individuelle::query()
            ->with(['user', 'module', 'region', 'departement'])
            ->whereBetween('date_depot', [$debut, $fin])
            ->orderByDesc('date_depot')
            ->orderByDesc('id'); // tri stable pour le chunking
    }

    public function chunkSize(): int
    {
        return 500;
    }

    public function columnWidths(): array
    {
        $widths = [];

        foreach (range('A', 'Z') as $col) {
            $widths[$col] = 20;
        }

        $widths['A']  = 6;
        $widths['AA'] = 20;
        $widths['AB'] = 25;
        $widths['AC'] = 25;
        $widths['AD'] = 40;

        return $widths;
    }

    public function headings(): array
    {
        return [
            'N°',
            'N° CIN',
            'Civilite',
            'Prenom',
            'Nom',
            'Date de naissance',
            'Lieu de naissance',
            'Adresse',
            'Telephone',
            'Email',
            'Region',
            'Departement',
            'Module sollicite',
            'Date de depot',
            'Statut',
            'Situation familiale',
            'Situation professionnelle',
            'Niveau d’etude',
            'Diplome academique',
            'Autre diplome academique',
            'Option diplome academique',
            'Etablissement academique',
            'Diplome professionnel',
            'Autre diplome professionnel',
            'Specialite professionnelle',
            'Établissement professionnel',
            'Projet apres formation',
            'Qualification',
            'Experience',
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
                    'bold'  => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType'   => 'solid',
                    'startColor' => ['rgb' => 'F28500'],
                ],
            ],
        ];
    }
}