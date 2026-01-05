<?php

namespace App\Http\Controllers;

use App\Models\ParcTypeMission;
use App\Http\Requests\StoreParcTypeMissionRequest;
use App\Http\Requests\UpdateParcTypeMissionRequest;
use Illuminate\Http\Request;

class ParcTypeMissionController extends Controller
{
    public function index(Request $request)
    {
        $typesMissions = ParcTypeMission::withCount('missions')
            ->latest()
            ->get();

        return view('parc.type-missions.index', compact('typesMissions'));
    }

    public function create()
    {
        return view('parc.type-missions.create');
    }

    public function store(StoreParcTypeMissionRequest $request)
    {
        ParcTypeMission::create($request->validated());

        return redirect()
            ->route('parc-type-missions.index')
            ->with('status', 'Type de mission créé avec succès.');
    }

    public function edit(ParcTypeMission $parc_type_mission)
    {
        return view('parc.type-missions.edit', [
            'typeMission' => $parc_type_mission
        ]);
    }

    public function update(UpdateParcTypeMissionRequest $request, ParcTypeMission $parc_type_mission)
    {
        $parc_type_mission->update($request->validated());

        return redirect()
            ->route('parc-type-missions.index')
            ->with('status', 'Type de mission mis à jour.');
    }

    public function destroy(ParcTypeMission $parc_type_mission)
    {
        /*Restriction suppression */
        if ($parc_type_mission->missions()->exists()) {
            return back()->withErrors(
                'Impossible de supprimer : ce type est utilisé dans des missions.'
            );
        }

        $parc_type_mission->delete();

        return redirect()
            ->route('parc-type-missions.index')
            ->with('status', 'Type de mission supprimé.');
    }

    public function show(ParcTypeMission $parc_type_mission)
    {
        // Missions triées par date de départ décroissante, 1 par page
        $parc_type_missions = $parc_type_mission->missions()
            ->orderByDesc('date_depart')
            ->paginate(1);

        return view('parc.type-missions.show', compact('parc_type_missions', 'parc_type_mission'));
    }
}
