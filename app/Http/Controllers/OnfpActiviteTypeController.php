<?php

namespace App\Http\Controllers;

use App\Models\OnfpActiviteType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OnfpActiviteTypeController extends Controller
{
    /**
     * Liste des types d'activités.
     */
    public function index(Request $request)
    {
        $query = OnfpActiviteType::query();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('libelle', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('actif')) {
            $query->where('actif', $request->actif);
        }

        $types = $query
            ->orderBy('ordre')
            ->orderBy('libelle')
            ->paginate(15)
            ->withQueryString();

        return view('onfp.activite-types.index', compact('types'));
    }

    /**
     * Formulaire de création.
     */
    public function create()
    {
        return view('onfp.activite-types.create');
    }

    /**
     * Enregistrement.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => [
                'required',
                'string',
                'max:50',
                'unique:onfp_activite_types,code',
            ],
            'libelle' => [
                'required',
                'string',
                'max:150',
            ],
            'description' => [
                'nullable',
                'string',
            ],
            'ordre' => [
                'nullable',
                'integer',
                'min:0',
            ],
            'actif' => [
                'nullable',
                'boolean',
            ],
        ]);

        $validated['code'] = Str::upper(trim($validated['code']));
        $validated['actif'] = $request->boolean('actif');
        $validated['ordre'] = $validated['ordre'] ?? 0;

        OnfpActiviteType::create($validated);

        return redirect()
            ->route('onfp.activite-types.index')
            ->with('success', 'Le type d’activité a été créé avec succès.');
    }

    /**
     * Formulaire de modification.
     */
    public function edit(OnfpActiviteType $activiteType)
    {
        return view('onfp.activite-types.edit', compact('activiteType'));
    }

    /**
     * Mise à jour.
     */
    public function update(Request $request, OnfpActiviteType $activiteType)
    {
        $validated = $request->validate([
            'code' => [
                'required',
                'string',
                'max:50',
                'unique:onfp_activite_types,code,' . $activiteType->id,
            ],
            'libelle' => [
                'required',
                'string',
                'max:150',
            ],
            'description' => [
                'nullable',
                'string',
            ],
            'ordre' => [
                'nullable',
                'integer',
                'min:0',
            ],
            'actif' => [
                'nullable',
                'boolean',
            ],
        ]);

        $validated['code'] = Str::upper(trim($validated['code']));
        $validated['actif'] = $request->boolean('actif');
        $validated['ordre'] = $validated['ordre'] ?? 0;

        $activiteType->update($validated);

        return redirect()
            ->route('onfp.activite-types.index')
            ->with('success', 'Le type d’activité a été modifié avec succès.');
    }

    /**
     * Suppression.
     */
    public function destroy(OnfpActiviteType $activiteType)
    {
        // Protection : empêcher la suppression d'un type déjà utilisé.
        if ($activiteType->activites()->exists()) {
            return redirect()
                ->route('onfp.activite-types.index')
                ->with('error', 'Impossible de supprimer ce type : il est déjà utilisé par une activité.');
        }

        $activiteType->delete();

        return redirect()
            ->route('onfp.activite-types.index')
            ->with('success', 'Le type d’activité a été supprimé avec succès.');
    }
}
