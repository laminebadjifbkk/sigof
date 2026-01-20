<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ModulesParRegionExport implements FromCollection, WithHeadings
{
    protected $donnees;

    /**
     * @param array $donnees Tableau de la forme :
     * [
     *   'Région 1' => [
     *       ['module' => 'Module A', 'total' => 55],
     *       ['module' => 'Module B', 'total' => 60],
     *   ],
     *   'Région 2' => [...]
     * ]
     */
    public function __construct(array $donnees)
    {
        $this->donnees = $donnees;
    }

    /**
     * Retourne la collection de lignes pour Excel
     */
    public function collection()
    {
        $rows = collect();

        foreach ($this->donnees as $region => $modules) {
            foreach ($modules as $index => $module) {
                $rows->push([
                    'N°'     => $index + 1,           // numéro de ligne
                    'Région' => $region,
                    'Module' => $module['module'],
                    'Nombre' => $module['total'],
                ]);
            }
        }

        return $rows;
    }

    /**
     * Définir les en-têtes des colonnes
     */
    public function headings(): array
    {
        return ['N°', 'Région', 'Module', 'Nombre de demandes'];
    }
}
