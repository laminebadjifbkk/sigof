<?php

namespace App\Exports\Support;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SupportStockRapportExport implements FromArray, WithHeadings, ShouldAutoSize
{
    public function __construct(
        private array $lignes,
        private array $entetes
    ) {}

    public function array(): array
    {
        return $this->lignes;
    }

    public function headings(): array
    {
        return $this->entetes;
    }
}
