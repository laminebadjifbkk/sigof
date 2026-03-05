<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\Individuelle;
use App\Models\Projetmodule;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExportProjetStatut implements FromView, ShouldAutoSize
{
    protected $statut, $module;

    public function __construct($module, $statut)
    {
        $this->module = $module;
        $this->statut = $statut;
    }

    public function view(): View
    {
        $projetmodule = Projetmodule::findorFail($this->module);
        $projet = $projetmodule->projet;

        // Supposons que $module est défini et contient l'id du module courant
        $individuelles = Individuelle::where('projets_id', $projet->id)
            ->when($this->statut !== 'all', function ($query) {
                $query->where('statut', $this->statut);
            })
            ->get();

            dd($individuelles);

        return view('projets.excel', [
            'individuelles' => $individuelles,
            'projet'        => $projet,
            'module'        => $this->module,
            'statut'        => $this->statut,
        ]);
    }
}
