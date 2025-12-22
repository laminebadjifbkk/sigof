<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreParcMissionRequest;
use App\Http\Requests\UpdateParcMissionRequest;
use App\Models\ParcChauffeur;
use App\Models\ParcMission;
use App\Models\ParcVehicule;
use Illuminate\Http\Request;

class ParcMissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $missions = ParcMission::latest()->get();
        return view('parc.missions.index', compact('missions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $vehicules = ParcVehicule::latest()->get();
        $chauffeurs = ParcChauffeur::latest()->get();
        return view('parc.missions.create', compact('vehicules', 'chauffeurs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreParcMissionRequest $request)
    {
        ParcMission::create($request->validated());
        return redirect()->route('parc-missions.index')
            ->with('status', 'Mission créée avec succès');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $mission = ParcMission::findOrFail($id);
        return view('parc.missions.show', compact('mission'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $mission = ParcMission::findOrFail($id);
        $vehicules = ParcVehicule::latest()->get();
        $chauffeurs = ParcChauffeur::latest()->get();
        return view('parc.missions.update', compact('mission', 'vehicules', 'chauffeurs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateParcMissionRequest $request, $id)
    {
        $mission = ParcMission::findOrFail($id);
        $mission->update($request->validated());
        return redirect()->back()
            ->with('status', 'Mission mise à jour avec succès');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        ParcMission::destroy($id);
        return redirect()->route('parc-missions.index')->with('status', 'Mission supprimée avec succès');
    }
}
