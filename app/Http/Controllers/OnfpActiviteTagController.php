<?php

namespace App\Http\Controllers;

use App\Models\OnfpActiviteTag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class OnfpActiviteTagController extends Controller
{
    public function index(Request $request)
    {
        $query = OnfpActiviteTag::withCount('activites');

        if ($request->filled('search')) {
            $query->where('nom', 'like', '%' . $request->string('search') . '%');
        }

        $tags = $query->orderBy('nom')->paginate(20)->appends($request->query());

        return view('onfp.activite-tags.index', compact('tags'));
    }

    public function create()
    {
        return view('onfp.activite-tags.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateData($request);
        $validated['slug'] = $this->genererSlugUnique($validated['nom']);

        OnfpActiviteTag::create($validated);

        return redirect()
            ->route('onfp.activite-tags.index')
            ->with('success', 'Tag créé avec succès.');
    }

    public function edit(OnfpActiviteTag $activiteTag)
    {
        return view('onfp.activite-tags.edit', ['tag' => $activiteTag]);
    }

    public function update(Request $request, OnfpActiviteTag $activiteTag)
    {
        $validated = $this->validateData($request, $activiteTag->id);

        if ($validated['nom'] !== $activiteTag->nom) {
            $validated['slug'] = $this->genererSlugUnique($validated['nom'], $activiteTag->id);
        }

        $activiteTag->update($validated);

        return redirect()
            ->route('onfp.activite-tags.index')
            ->with('success', 'Tag mis à jour avec succès.');
    }

    public function destroy(OnfpActiviteTag $activiteTag)
    {
        if ($activiteTag->activites()->exists()) {
            return back()->with(
                'error',
                'Impossible de supprimer ce tag : il est encore utilisé par une ou plusieurs activités.'
            );
        }

        $activiteTag->delete();

        return redirect()
            ->route('onfp.activite-tags.index')
            ->with('success', 'Tag supprimé avec succès.');
    }

    private function validateData(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'nom' => [
                'required',
                'string',
                'max:100',
                Rule::unique('onfp_activite_tags', 'nom')->ignore($ignoreId),
            ],
            'couleur' => [
                'nullable',
                'string',
                'max:20',
            ],
            'description' => [
                'nullable',
                'string',
            ],
            'actif' => [
                'nullable',
                'boolean',
            ],
        ]);
    }

    private function genererSlugUnique(string $nom, ?int $ignoreId = null): string
    {
        $slugBase = Str::slug($nom);
        $slug = $slugBase;
        $compteur = 1;

        while (
            OnfpActiviteTag::where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $slugBase . '-' . $compteur++;
        }

        return $slug;
    }
}
