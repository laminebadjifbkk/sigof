<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreParcMissionRequest;
use App\Http\Requests\UpdateParcMissionRequest;
use App\Models\Employee;
use App\Models\ParcChauffeur;
use App\Models\ParcMission;
use App\Models\ParcTypeMission;
use App\Models\ParcVehicule;
use Dompdf\Dompdf;
use Dompdf\Options;
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
        $typesMissions = ParcTypeMission::all();
        // Génération automatique de la référence
        $year = now()->year;

        // On récupère la dernière mission de l'année courante
        $lastMission = ParcMission::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();

        if ($lastMission) {
            // Extraire le numéro après le tiret
            $lastNumber = (int) substr($lastMission->reference, 5);
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            // Première mission de l'année
            $newNumber = '001';
        }

        $reference = $year . '-' . $newNumber;

        return view('parc.missions.create', compact('vehicules', 'chauffeurs', 'reference', 'typesMissions'));
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
        // Récupérer la mission avec ses relations pour éviter les requêtes N+1
        $mission = ParcMission::with(['typeMission', 'employees.user', 'employees.direction', 'employees.fonction'])
            ->findOrFail($id);

        // Compter les missions de l'année en cours
        $missionsCount = ParcMission::whereYear('date_depart', now()->year)->count();

        // Nombre de jours (calcul automatique si non stocké en base)
        $mission->nombre_jours = $mission->date_depart && $mission->date_retour
            ? $mission->date_depart->diffInDays($mission->date_retour) + 1
            : 1;

        // Les employés affectés à la mission
        $employees = $mission->employees;

        return view('parc.missions.show', compact('mission', 'missionsCount', 'employees'));
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
        $typesMissions = ParcTypeMission::all();
        return view('parc.missions.update', compact('mission', 'vehicules', 'chauffeurs', 'employees', 'typesMissions'));
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
        $employees = Employee::get();
        return view('parc.missions.edit-employe', compact('mission', 'employees'));
    }

    public function updateEmployees(Request $request, ParcMission $mission)
    {
        $employeesData = [];

        if ($request->has('employees')) {
            foreach ($request->input('employees') as $employee) {
                if (isset($employee['id'])) {
                    $employeesData[$employee['id']] = [
                        'role' => $employee['role'] ?? 'participant',
                        'vehicule_id' => $employee['vehicule_id'] ?? null,
                    ];
                }
            }
        }

        // Synchroniser les employés avec rôle + véhicule
        $mission->employees()->sync($employeesData);

        return redirect()->back()->with('status', 'Employés de la mission mis à jour avec succès');
    }

    public function editVehicules(ParcMission $mission)
    {
        // Charger tous les véhicules disponibles avec leurs chauffeurs
        $vehicules = ParcVehicule::with('chauffeur')->get();

        // Charger les véhicules déjà affectés à cette mission
        $missionVehicules = $mission->vehicules()->withPivot('chauffeur_id')->get();

        return view('parc.missions.edit-vehicule', compact('mission', 'vehicules', 'missionVehicules'));
    }

    public function updateVehicules(Request $request, ParcMission $mission)
    {
        $data = [];

        if ($request->has('vehicules')) {
            foreach ($request->input('vehicules') as $vehiculeId => $vehiculeData) {
                $data[$vehiculeId] = [
                    'chauffeur_id' => $vehiculeData['chauffeur_id'] ?? null
                ];
            }
        }

        // Synchroniser les véhicules avec la mission
        $mission->vehicules()->sync($data);

        return redirect()->back()->with('status', 'Véhicules de la mission mis à jour avec succès');
    }

    public function ordreMission($id)
    {
        try {
            // Récupérer le mission par ID
            $mission = ParcMission::findOrFail($id);
            $employees = $mission->employees;
            $jours = $mission->date_retour
                ? $mission->date_depart->diffInDays($mission->date_retour) + 1
                : 1;

            // Préparer les données pour la vue PDF
            $options = new Options();
            $options->set('isPhpEnabled', true);              // ⭐ OBLIGATOIRE
            $options->set('isHtml5ParserEnabled', true);
            $dompdf = new Dompdf($options);

            $dompdf->loadHtml(view(
                'parc.missions.ordre-mission',
                compact('mission', 'employees', 'jours')
            ));

            // Format du PDF
            $dompdf->setPaper('Letter', 'portrait');
            $dompdf->render();

            // Nom du fichier
            $name = 'Ordre_mission_' . $mission->reference . '.pdf';
            $name = str_replace(
                [' ', 'é', 'è', 'ê', 'à', 'ç', ','],
                ['_', 'e', 'e', 'e', 'a', 'c', ''],
                $name
            );

            // Stream vers le navigateur
            return $dompdf->stream($name, ['Attachment' => false]);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('status', 'Une erreur est survenue lors de la génération du P');
        }
    }
}
