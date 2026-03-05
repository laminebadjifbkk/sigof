<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use App\Models\Individuelle;
use App\Models\Projetmodule;
use Illuminate\Contracts\View\View;
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
        // Récupère le module et le projet lié
        $projetmodule = Projetmodule::findOrFail($this->module);
        $projet = $projetmodule->projet;

        // Récupère les Individuelles liées au projet
        $individuelles = Individuelle::where('projets_id', $projet->id)
            ->when($this->statut !== 'Aucun statut', function ($query) {
                $query->where('statut', $this->statut);
            }, function ($query) {
                $query->whereNull('statut');
            })
            ->get();

        return view('projets.excel', [
            'individuelles' => $individuelles,
            'projet'        => $projet,
            'module'        => $projetmodule,
            'statut'        => $this->statut,
        ]);
    }
}
