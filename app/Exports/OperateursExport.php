<?php
namespace App\Exports;

use App\Models\Operateur;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OperateursExport implements FromCollection, WithHeadings
{
    protected $operateurs;

    public function __construct($operateurs)
    {
        $this->operateurs = $operateurs;
    }

    public function collection()
    {
        return $this->operateurs->map(function ($operateur) {
            return [
                'Operateur' => $operateur?->user?->display_operateur ?? '',
                /* 'Sigle'     => $operateur?->user?->display_operateur ?? '', */
                'Email'     => $operateur?->user?->email ?? '',
                'Téléphone' => $operateur?->user?->telephone ?? '',
            ];
        });
    }

    public function headings(): array
    {
        return ['Operateur',  'Email', 'Téléphone'];
    }
}
