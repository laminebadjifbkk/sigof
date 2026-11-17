<?php

namespace App\Exports;

use App\Models\Formulaire;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExportPrisenchargeStatut implements FromView, ShouldAutoSize
{
    protected $statut;
    protected $annee_scolaire;

    public function __construct($statut, $annee_scolaire = null)
    {
        $this->statut         = $statut;
        $this->annee_scolaire = $annee_scolaire;
    }

    public function view(): View
    {
        $formulaires = Formulaire::query()
            ->when($this->statut !== 'all', fn ($q) => $q->where('statut', $this->statut))
            ->when($this->annee_scolaire, fn ($q) => $q->where('annee_scolaire', $this->annee_scolaire))
            ->get();

        return view('formulaire.excel', [
            'formulaires'    => $formulaires,
            'statut'         => $this->statut,
            'annee_scolaire' => $this->annee_scolaire,
        ]);
    }
}
