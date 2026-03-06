<?php

namespace App\Http\Controllers;

use App\Models\Detf;
use App\Models\BudgetLabel;
use App\Models\DetfsBudgetItem;
use Illuminate\Http\Request;

class DetfBudgetItemController extends Controller
{

    public function editBudget(Detf $detf)
    {
        $labels = BudgetLabel::orderBy('libelle')->get();

        $budgetItems = $detf->budgetItems()
            ->with('label')
            ->get();

        $grouped = $budgetItems->groupBy(fn($item) => $item->label->type ?? 'Autres');
        $totalGeneral = 0;

        return view('detfs.budget', compact(
            'detf',
            'labels',
            'grouped',
            'totalGeneral',
            'budgetItems',
        ));
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

    // Affiche le formulaire pour modifier une ligne budgétaire
    public function edit(Detf $detf, DetfsBudgetItem $budget_item)
    {
        $labels = BudgetLabel::orderBy('libelle')->get();

        return view('detfs.budget-edit-item', [
            'detf' => $detf,
            'budgetItem' => $budget_item,
            'labels' => $labels,
        ]);
    }

    // Met à jour la ligne budgétaire
    public function update(Request $request, Detf $detf, DetfsBudgetItem $budget_item)
    {
        $request->validate([
            'label_id' => 'required|exists:budget_labels,id',
            'unite' => 'nullable|string|max:50',
            'quantite' => 'required|numeric|min:0',
            'prix_unitaire' => 'required|numeric|min:0',
        ]);

        $budget_item->update([
            'budget_label_id' => $request->label_id,
            'unite' => $request->unite,
            'quantite' => $request->quantite,
            'prix_unitaire' => $request->prix_unitaire,
            'montant' => $request->quantite * $request->prix_unitaire,
        ]);

        return redirect()->route('detfs.budget.edit', $detf->id)
            ->with('success', 'Ligne budgétaire mise à jour avec succès.');
    }

    // Supprime la ligne budgétaire
    public function destroy(Detf $detf, DetfsBudgetItem $budget_item)
    {
        $budget_item->delete();

        return redirect()->route('detfs.budget.edit', $detf->id)
            ->with('success', 'Ligne budgétaire supprimée.');
    }
}
