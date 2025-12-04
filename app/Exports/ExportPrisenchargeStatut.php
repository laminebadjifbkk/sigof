<?php

namespace App\Exports;

use App\Models\Formulaire;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExportPrisenchargeStatut implements FromView, ShouldAutoSize
{
    protected $statut;

    public function __construct($statut)
    {
        $this->statut = $statut;
    }

    public function view(): View
    {
        // Filtre uniquement par statut
        $formulaires = Formulaire::query()
            ->when($this->statut !== 'all', function ($q) {
                $q->where('statut', $this->statut);
            })
            ->get();

        return view('formulaire.excel', [
            'formulaires' => $formulaires,
            'statut'      => $this->statut,
        ]);
    }
}
