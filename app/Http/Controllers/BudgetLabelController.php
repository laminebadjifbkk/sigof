<?php

namespace App\Http\Controllers;

use App\Models\BudgetLabel;
use Illuminate\Http\Request;

class BudgetLabelController extends Controller
{
    public function index()
    {
        $labels = BudgetLabel::orderBy('libelle')->paginate(10);
        return view('budget_labels.index', compact('labels'));
    }

    public function create()
    {
        return view('budget_labels.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'libelle' => 'required|string|max:255|unique:budget_labels,libelle',
            'description' => 'nullable|string|max:500',
        ]);

        BudgetLabel::create($request->only('libelle', 'description'));

        return redirect()->back()->with('success', 'Libellé créé avec succès.');
    }

    public function edit(BudgetLabel $budgetLabel)
    {
        return view('budget_labels.update', compact('budgetLabel'));
    }

    public function update(Request $request, BudgetLabel $budgetLabel)
    {
        $request->validate([
            'libelle' => 'required|string|max:255|unique:budget_labels,libelle,' . $budgetLabel->id,
            'description' => 'nullable|string|max:500',
        ]);

        $budgetLabel->update($request->only('libelle', 'description'));

        return redirect()->back()->with('success', 'Libellé mis à jour.');
    }

    public function destroy(BudgetLabel $budgetLabel)
    {
        $budgetLabel->delete();
        return redirect()->back()->with('success', 'Libellé supprimé.');
    }
}
