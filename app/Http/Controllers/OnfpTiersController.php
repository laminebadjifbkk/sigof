<?php

namespace App\Http\Controllers;

use App\Models\OnfpTiers;
use Illuminate\Http\Request;

class OnfpTiersController extends Controller
{
    /**
     * Liste de l'annuaire des tiers.
     */
    public function index(Request $request)
    {
        $query = OnfpTiers::query();

        if ($request->filled('search')) {
            $search = $request->string('search');

            $query->where(function ($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                    ->orWhere('organisation', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->string('type'));
        }

        if ($request->filled('actif')) {
            $query->where('actif', $request->boolean('actif'));
        }

        $tiers = $query
            ->orderBy('nom')
            ->paginate(15)
            ->appends($request->query());

        return view('onfp.tiers.index', [
            'tiers' => $tiers,
            'types' => OnfpTiers::TYPES,
        ]);
    }

    public function create()
    {
        return view('onfp.tiers.create', [
            'types' => OnfpTiers::TYPES,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateData($request);

        OnfpTiers::create($validated);

        return redirect()
            ->route('onfp.tiers.index')
            ->with('success', 'Le tiers a été créé avec succès.');
    }

    public function show(OnfpTiers $tiers)
    {
        $tiers->load(['activiteTiers.activite']);

        return view('onfp.tiers.show', compact('tiers'));
    }

    public function edit(OnfpTiers $tiers)
    {
        return view('onfp.tiers.edit', [
            'tiers' => $tiers,
            'types' => OnfpTiers::TYPES,
        ]);
    }

    public function update(Request $request, OnfpTiers $tiers)
    {
        $validated = $this->validateData($request, $tiers->id);

        $tiers->update($validated);

        return redirect()
            ->route('onfp.tiers.index')
            ->with('success', 'Le tiers a été mis à jour avec succès.');
    }

    public function destroy(OnfpTiers $tiers)
    {
        // Empêcher la suppression si le tiers est encore associé à des activités
        if ($tiers->activiteTiers()->exists()) {
            return back()->with(
                'error',
                'Impossible de supprimer ce tiers : il est encore associé à une ou plusieurs activités.'
            );
        }

        $tiers->delete();

        return redirect()
            ->route('onfp.tiers.index')
            ->with('success', 'Le tiers a été supprimé avec succès.');
    }

    private function validateData(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'type' => [
                'nullable',
                'string',
                'in:' . implode(',', array_keys(OnfpTiers::TYPES)),
            ],
            'nom' => [
                'required',
                'string',
                'max:255',
            ],
            'organisation' => [
                'nullable',
                'string',
                'max:255',
            ],
            'fonction' => [
                'nullable',
                'string',
                'max:150',
            ],
            'telephone' => [
                'nullable',
                'string',
                'max:50',
            ],
            'email' => [
                'nullable',
                'email',
                'max:150',
            ],
            'adresse' => [
                'nullable',
                'string',
            ],
            'observation' => [
                'nullable',
                'string',
            ],
            'actif' => [
                'nullable',
                'boolean',
            ],
        ]);
    }
}
