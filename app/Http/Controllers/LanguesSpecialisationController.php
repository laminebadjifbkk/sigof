<?php

namespace App\Http\Controllers;

use App\Models\LanguesSpecialisation;
use Illuminate\Http\Request;

class LanguesSpecialisationController extends Controller
{
    public function index()
    {
        $langues = LanguesSpecialisation::withCount('candidatures')
            ->orderBy('nom')
            ->get();

        return view('langues.index', compact('langues'));
    }

    public function create()
    {
        return view('langues.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        LanguesSpecialisation::create($data);

        return redirect()->route('langues.index')->with('success', 'Langue créée avec succès.');
    }

    public function show(LanguesSpecialisation $langue)
    {
        return view('langues.show', compact('langue'));
    }

    public function edit(LanguesSpecialisation $langue)
    {
        return view('langues.edit', compact('langue'));
    }

    public function update(Request $request, LanguesSpecialisation $langue)
    {
        $data = $this->validateData($request, $langue->id);

        $langue->update($data);

        return redirect()->route('langues.index')->with('success', 'Langue mise à jour avec succès.');
    }

    public function destroy(LanguesSpecialisation $langue)
    {
        $langue->delete();

        return redirect()->route('langues.index')->with('success', 'Langue supprimée avec succès.');
    }

    private function validateData(Request $request, $ignoreId = null): array
    {
        return $request->validate([
            'nom' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:langues_specialisations,code' . ($ignoreId ? ",$ignoreId" : ''),
            'postes_disponibles' => 'required|integer|min:0',
            'niveau_langue_requis' => 'required|string|max:255',
            'niveau_francais_requis' => 'required|string|max:255',
            'diplome_minimum' => 'required|string|max:255',
            'certification_recommandee' => 'nullable|string|max:255',
        ]);
    }
}
