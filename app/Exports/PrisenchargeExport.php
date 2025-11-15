<?php

namespace App\Exports;

use App\Models\Formulaire;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PrisenchargeExport implements FromView, ShouldAutoSize
{
    protected $statut;
    protected $region;

    public function __construct($statut, $region)
    {
        $this->statut = $statut;
        $this->region = $region;
    }

    public function view(): View
    {
        // Filtre selon les paramètres reçus
        $formulaires = Formulaire::query()
            ->when($this->statut !== 'all', function ($q) {
                $q->where('statut', $this->statut);
            })
            ->when($this->region !== 'all', function ($q) {
                $q->where('region', $this->region);
            })
            ->get();

        return view('formulaire.excel', [
            'formulaires' => $formulaires,
            'statut'      => $this->statut,
            'region'      => $this->region,
        ]);
    }
}
