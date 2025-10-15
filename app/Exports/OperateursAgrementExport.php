<?php

namespace App\Exports;

use App\Models\Operateur;
use App\Models\Commissionagrement;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class OperateursAgrementExport implements FromView, ShouldAutoSize
{
    protected $statut;
    protected $commissionagrement;

    public function __construct($statut, $commissionagrement)
    {
        $this->statut = $statut;
        $this->commissionagrement = $commissionagrement;
    }

    public function view(): View
    {
        $operateurs = Operateur::when($this->statut !== 'Aucun', function ($query) {
                $query->where('statut_agrement', $this->statut);
            }, function ($query) {
                $query->whereNull('statut_agrement');
            })
            ->when(! empty($this->commissionagrement), function ($query) {
                $query->whereHas('commissionagrements', function ($q) {
                    $q->where('commissionagrements.id', $this->commissionagrement->id);
                });
            })
            ->get();

        return view('operateurs.excel', [
            'operateurs' => $operateurs,
            'statut' => $this->statut,
            'commissionagrement' => $this->commissionagrement,
        ]);
    }
}
