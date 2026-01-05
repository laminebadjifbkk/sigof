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
use App\Http\Requests\UpdateParcMissionPersonnelRequest;

class ParcMissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /* public function index(Request $request)
    {
        $missions = ParcMission::query();

        $statut = $request->query('statut');
        $annee  = $request->query('annee');

        // Appliquer le filtre statut si présent
        if ($statut) {
            $missions->where('statut', $statut);
        }

        // Appliquer le filtre année si présent
        if ($annee) {
            $missions->whereYear('date_depart', $annee);
        }

        // Récupérer les missions filtrées
        $missions = $missions->latest()->get();

        // Regrouper toutes les missions pour les cards (non filtré)
        $groupes = ParcMission::latest()->get()->groupBy(fn($item) => $item->statut ?? 'Aucun');

        // Calculer les pourcentages
        $total = ParcMission::count();
        $statutPourcentages = [];
        foreach ($groupes as $statutKey => $items) {
            $statutPourcentages[$statutKey] = [
                'percent' => $total ? round($items->count() * 100 / $total, 1) : 0
            ];
        }

        $totalMissions = $total;
        $missionsAnnee = ParcMission::whereYear('date_depart', now()->year)->count();

        return view('parc.missions.index', compact(
            'missions',
            'groupes',
            'statutPourcentages',
            'totalMissions',
            'missionsAnnee'
        ));
    } */
    public function index(Request $request)
    {
        $query = ParcMission::query();

        $statut = $request->query('statut');
        $annee  = $request->query('annee');

        // Filtre statut
        if ($statut) {
            $query->where('statut', $statut);
        }

        // Filtre année
        if ($annee) {
            $query->whereYear('date_depart', $annee);
        }

        // Charger missions filtrées, avec count employés (pour bouton delete)
        $missions = $query->withCount('employees')->latest()->get();

        // Pour les cards : grouper toutes les missions par statut
        $allMissions = ParcMission::latest()->get();
        $groupes = $allMissions->groupBy(fn($item) => $item->statut ?? 'Aucun');

        // Calcul des pourcentages par statut
        $total = $allMissions->count();
        $statutPourcentages = $groupes->mapWithKeys(function ($items, $statutKey) use ($total) {
            return [$statutKey => ['percent' => $total ? round($items->count() * 100 / $total, 1) : 0]];
        });

        // Totaux
        $totalMissions = $total;
        $missionsAnnee = ParcMission::whereYear('date_depart', now()->year)->count();

        return view('parc.missions.index', compact(
            'missions',
            'groupes',
            'statutPourcentages',
            'totalMissions',
            'missionsAnnee',
            'statut'
        ));
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
        $lastMission = ParcMission::whereYear('date_depart', $year)
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
            ? $mission->date_depart->diffInDays($mission->date_retour)
            : 1;

        // Les employés affectés à la mission
        $employees = $mission->employees;

        $employeesCount = $mission->employees()->count();

        return view('parc.missions.show', compact('mission', 'missionsCount', 'employees', 'employeesCount'));
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
    /*    public function destroy(string $id)
    {
        ParcMission::destroy($id);
        return redirect()->route('parc-missions.index')->with('status', 'Mission supprimée avec succès');
    } */
    public function destroy(ParcMission $mission)
    {
        if ($mission->employees()->exists()) {
            return back()->with('error', 'Suppression impossible : cette mission est déjà assignée à des employés.');
        }

        $mission->delete();

        return back()->with('success', 'Mission supprimée avec succès.');
    }

    /*  public function editEmployees(ParcMission $mission)
    {
        // Récupérer les IDs des employés qui sont des chauffeurs
        $chauffeurIds = ParcChauffeur::pluck('employee_id')->toArray();

        // Récupérer tous les employés sauf ceux qui sont des chauffeurs
        $employees = Employee::whereNotIn('id', $chauffeurIds)->get();

        return view('parc.missions.edit-employe', compact('mission', 'employees'));
    }

    public function updateEmployees(Request $request, ParcMission $mission)
    {
        $employeesInput = $request->input('employees', []);

        // Préparer les données à synchroniser
        $syncData = [];
        foreach ($employeesInput as $employeeId => $data) {
            if (!empty($data['selected'])) {
                $syncData[$employeeId] = [
                    'role' => $data['role'] ?? 'participant',
                    'vehicule_id' => $data['vehicule_id'] ?? null,
                ];
            }
        }

        // Synchroniser les employés sur la mission
        $mission->employees()->sync($syncData);

        return redirect()->back()->with('status', 'Employés de la mission mis à jour avec succès.');
    } */

    public function editVehicules(ParcMission $mission)
    {
        $annee = now()->year;

        $vehicules = ParcVehicule::with('chauffeur')
            ->withCount([
                // Nombre total de missions du véhicule
                'missions as missions_total',

                // Nombre de missions de l'année en cours
                'missions as missions_annee' => function ($query) use ($annee) {
                    $query->whereYear('date_depart', $annee);
                }
            ])
            ->whereIn('etat', ['operationnel', 'disponible'])
            ->whereDoesntHave('missions', function ($query) {
                $query->where('statut', 'en_cours');
            })
            ->get();

        // Véhicules déjà affectés à la mission (pivot chauffeur)
        $missionVehicules = $mission->vehicules()->withPivot('chauffeur_id')->get();

        return view(
            'parc.missions.edit-vehicule',
            compact('mission', 'vehicules', 'missionVehicules')
        );
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

    /*  public function editChauffeurs(ParcMission $mission)
    {
        // Tous les chauffeurs avec leurs employés liés
        $chauffeurs = ParcChauffeur::with('employee.user')->get();

        // Récupérer les IDs des employés qui sont des chauffeurs
        $chauffeurIds = $chauffeurs->pluck('employee_id')->toArray();

        $missionChauffeurs = $mission->employees()
            ->whereIn('employees.id', $chauffeurIds) // <--- ici on précise la table
            ->get();

        return view('parc.missions.edit-chauffeur', compact('mission', 'chauffeurs', 'missionChauffeurs'));
    }

    public function updateChauffeurs(Request $request, ParcMission $mission)
    {
        $input = $request->input('chauffeurs', []);

        $syncData = [];

        foreach ($input as $chauffeurId => $data) {
            if (!empty($data['selected'])) {
                $employeeId = ParcChauffeur::find($chauffeurId)?->employee_id;
                if (!$employeeId) continue;

                // On fixe le rôle à 'chauffeur' car pas de sélection dans le formulaire
                $syncData[$employeeId] = ['role' => 'chauffeur'];
            }
        }

        // Ajouter ou mettre à jour sans supprimer les autres
        foreach ($syncData as $employeeId => $pivotData) {
            if ($mission->employees()->where('employee_id', $employeeId)->exists()) {
                $mission->employees()->updateExistingPivot($employeeId, $pivotData);
            } else {
                $mission->employees()->attach($employeeId, $pivotData);
            }
        }

        return redirect()->back()->with('status', 'Chauffeurs de la mission mis à jour avec succès.');
    }
 */

    /*     public function editPersonnel(ParcMission $mission)
    {
        $chauffeurs = ParcChauffeur::with('employee.user')->get();
        $chauffeurIds = $chauffeurs->pluck('employee_id')->toArray();

        $missionChauffeurs = $mission->employees()
            ->whereIn('employees.id', $chauffeurIds)
            ->get();

        $employees = Employee::whereNotIn('id', $chauffeurIds)
            ->with('user')
            ->get()
            ->sortBy(fn($e) => $e->user->name);

        return view('parc.missions.edit-personnel', compact(
            'mission',
            'chauffeurs',
            'missionChauffeurs',
            'employees'
        ));
    } */

    public function editPersonnel(ParcMission $mission)
    {
        $annee = now()->year;

        // Chauffeurs avec user + missions de l'année en cours
        /* $chauffeurs = ParcChauffeur::with([
            'employee.user',
            'employee.parcmissions' => function ($query) use ($annee) {
                $query->whereYear('date_depart', $annee)
                    ->orderByDesc('date_retour');
            }
        ])->get();

        dd($chauffeurs); */

        $annee = now()->year;

        $chauffeurs = ParcChauffeur::with([
            'employee.user', // pour afficher le nom
            'missions' => function ($query) use ($annee) {
                $query->whereYear('date_depart', $annee);
            }
        ])
            ->withMax([
                'missions as last_retour' => function ($query) use ($annee) {
                    $query->whereYear('date_depart', $annee);
                }
            ], 'date_retour')
            ->orderByRaw('last_retour IS NULL') // ceux sans mission en haut
            ->orderBy('last_retour', 'asc')     // plus ancienne date de retour → haut
            ->get();

        // IDs des employés chauffeurs
        $chauffeurIds = $chauffeurs->pluck('employee_id')->toArray();

        // Chauffeurs déjà liés à la mission
        $missionChauffeurs = $mission->employees()
            ->whereIn('employees.id', $chauffeurIds)
            ->get();

        // Employés non chauffeurs
        $employees = Employee::whereNotIn('id', $chauffeurIds)
            ->with('user')
            ->get()
            ->sortBy(fn($e) => $e->user->name);

        return view('parc.missions.edit-personnel', compact(
            'mission',
            'chauffeurs',
            'missionChauffeurs',
            'employees',
            'annee'
        ));
    }

    public function updatePersonnel(
        UpdateParcMissionPersonnelRequest $request,
        ParcMission $mission
    ) {
        $syncData = [];

        /* ========= Chauffeurs ========= */
        foreach ($request->validated()['chauffeurs'] ?? [] as $chauffeurId => $data) {
            if ($data['selected']) {
                $employeeId = ParcChauffeur::find($chauffeurId)?->employee_id;
                if ($employeeId) {
                    $syncData[$employeeId] = [
                        'role' => 'chauffeur',            // rôle fixe
                        'vehicule_id' => $data['vehicule_id'] ?? null, // véhicule choisi
                    ];
                }
            }
        }

        /* ========= Employés ========= */
        foreach ($request->validated()['employees'] ?? [] as $employeeId => $data) {
            if ($data['selected']) {
                $syncData[$employeeId] = [
                    'role' => $data['role'] ?? 'participant',
                    'vehicule_id' => $data['vehicule_id'] ?? null,
                ];
            }
        }

        // Synchroniser tous les employés et chauffeurs
        $mission->employees()->sync($syncData);

        return back()->with('status', 'Personnel de la mission mis à jour avec succès.');
    }

    public function ordreMission($id)
    {
        try {
            // Récupérer le mission par ID
            $mission = ParcMission::findOrFail($id);
            $employees = $mission->employees;
            $jours = $mission->date_retour
                ? $mission->date_depart->diffInDays($mission->date_retour)
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
