<?php

namespace App\Http\Controllers;

use App\Models\Detf;
use App\Models\Operateur;
use Illuminate\Http\Request;

class DetfController extends Controller
{
    public function create()
    {
        $operateurs = Operateur::where('statut_agrement', 'agréé')->get();
        return view('detfs.create', compact('operateurs'));
    }


    public function index(Request $request)
    {
        $query = Detf::query();

        $statut = $request->query('statut');

        // Filtre statut
        if ($statut) {
            $query->where('statut', $statut);
        }

        // Charger Detf filtrées, avec count employés (pour bouton delete)
        $detfs = $query->latest()->get();

        // Pour les cards : grouper toutes les Detf par statut
        $allDetf = Detf::latest()->get();
        $groupes = $allDetf->groupBy(fn($item) => $item->statut ?? 'Aucun');

        // Calcul des pourcentages par statut
        $total = $allDetf->count();
        $statutPourcentages = $groupes->mapWithKeys(function ($items, $statutKey) use ($total) {
            return [$statutKey => ['percent' => $total ? round($items->count() * 100 / $total, 1) : 0]];
        });

        // Vérifier si un statut est passé
        $labels = [
            'planifiee' => 'Planifiées',
            'en_cours' => 'En cours',
            'terminee' => 'Terminées',
            'annulee' => 'Annulées',
        ];

        return view('detfs.index', compact(
            'detfs',
            'groupes',
            'statutPourcentages',
            'total',
            'labels',
            'statut'
        ));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'titre1' => 'nullable|string',
            'titre2' => 'nullable|string',
            'date1' => 'nullable|date',
            'operateurs_id' => 'nullable|integer',
        ]);

        Detf::create($data);

        return redirect()->route('detfs.create')->with('success', 'Formation créée avec succès !');
    }
}
