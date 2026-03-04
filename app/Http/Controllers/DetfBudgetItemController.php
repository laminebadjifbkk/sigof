<?php

namespace App\Http\Controllers;

use App\Models\Detf;
use App\Models\BudgetLabel;
use App\Models\DetfsBudgetItem;
use Illuminate\Http\Request;

class DetfBudgetItemController extends Controller
{
    // Affiche le formulaire pour compléter le budget
    public function editBudget(Detf $detf)
    {
        $labels = BudgetLabel::orderBy('libelle')->get();
        $budgetItems = $detf->budgetItems()->with('label')->get();

        return view('detfs.budget', compact('detf', 'labels', 'budgetItems'));
    }

    // Stocke une ligne budgétaire
    public function store(Request $request, Detf $detf)
    {
        $request->validate([
            'label_id' => 'required|exists:budget_labels,id',
            'unite' => 'nullable|string|max:50',
            'quantite' => 'required|numeric|min:0',
            'prix_unitaire' => 'required|numeric|min:0',
        ]);

        $montant = $request->quantite * $request->prix_unitaire;

        $detf->budgetItems()->create([
            'budget_label_id' => $request->label_id,
            'unite' => $request->unite,
            'quantite' => $request->quantite,
            'prix_unitaire' => $request->prix_unitaire,
            'montant' => $montant,
        ]);

        return redirect()->route('detfs.budget.edit', $detf->id)
            ->with('success', 'Ligne budgétaire ajoutée avec succès.');
    }
}
