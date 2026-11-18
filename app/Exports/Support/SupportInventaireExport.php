<?php

namespace App\Exports\Support;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SupportInventaireExport implements FromArray, WithHeadings, ShouldAutoSize
{
    public function __construct(
        private array $colonnes,
        private array $lignes
    ) {
    }

    public function headings(): array
    {
        return $this->colonnes;
    }

    public function array(): array
    {
        return $this->lignes;
    }
}
