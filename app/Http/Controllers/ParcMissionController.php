<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreParcMissionRequest;
use App\Http\Requests\UpdateParcMissionRequest;
use App\Models\Employee;
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
        // Compter les missions de l'année en cours
        $missionsCount = ParcMission::whereYear('date_depart', now()->year)->count();
        return view('parc.missions.show', compact('mission', 'missionsCount'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $mission = ParcMission::findOrFail($id);
        $vehicules = ParcVehicule::latest()->get();
        $chauffeurs = ParcChauffeur::latest()->get();
        $employees = Employee::latest()->get();
        return view('parc.missions.update', compact('mission', 'vehicules', 'chauffeurs', 'employees'));
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

    public function editEmployees(ParcMission $mission)
    {
        $employees = Employee::with('user')->get();
        return view('parc.missions.edit-employe', compact('mission', 'employees'));
    }

    public function updateEmployees(Request $request, ParcMission $mission)
    {
        $employeesData = [];
        if ($request->has('employees')) {
            foreach ($request->input('employees') as $employee) {
                if (isset($employee['id'])) {
                    $employeesData[$employee['id']] = ['role' => $employee['role'] ?? 'participant'];
                }
            }
        }

        $mission->employees()->sync($employeesData);

        return redirect()->back()->with('status', 'Employés de la mission mis à jour avec succès');
    }
}
