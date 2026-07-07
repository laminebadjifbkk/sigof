<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreParcChauffeurRequest;
use App\Http\Requests\UpdateParcChauffeurRequest;
use App\Models\Employee;
use App\Models\ParcChauffeur;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

use App\Exports\ChauffeurMissionsExport;
use Maatwebsite\Excel\Facades\Excel;

class ParcChauffeurController extends Controller
{
    /* public function index()
    {
        $chauffeurs = ParcChauffeur::latest()->get();
        return view('parc.chauffeurs.index', compact('chauffeurs'));
    } */

    /* public function index(Request $request)
    {
        $annee = now()->year;

        $chauffeurs = ParcChauffeur::with(['employee.parcmissions' => function ($query) {
            $query->where('date_depart', '>=', now()->startOfYear());
        }])->get();

        // Filtrer par statut si nécessaire
        if ($statut = $request->query('statut')) {
            $chauffeurs->where('statut', $statut);
        }

        // Groupement par statut pour les cards (non filtré)
        $groupes = ParcChauffeur::latest()->get()->groupBy(fn($v) => $v->statut ?? 'Inconnu');

        // Calcul des pourcentages par statut
        $total = ParcChauffeur::count();
        $statutPourcentages = [];
        foreach ($groupes as $statutKey => $items) {
            $statutPourcentages[$statutKey] = [
                'percent' => $total ? round($items->count() * 100 / $total, 1) : 0
            ];
        }

        $totalChauffeurs = $total;

        return view('parc.chauffeurs.index', compact(
            'chauffeurs',
            'groupes',
            'statutPourcentages',
            'totalChauffeurs',
            'statut'
        ));
    } */
    public function index(Request $request)
    {
        $annee = now()->year;

        // Base query
        $query = ParcChauffeur::with(['employee.parcmissions' => function ($q) use ($annee) {
            $q->whereYear('date_depart', '>=', $annee);
        }]);

        // Filtrer par statut si fourni
        if ($statut = $request->query('statut')) {
            $query->where('statut', $statut);
        }

        // Récupérer les chauffeurs filtrés
        $chauffeurs = $query->get();

        // Groupement par statut pour les cards (non filtré)
        $groupes = ParcChauffeur::latest()->get()->groupBy(fn($v) => $v->statut ?? 'Inconnu');

        // Calcul des pourcentages par statut
        $total = ParcChauffeur::count();
        $statutPourcentages = [];
        foreach ($groupes as $statutKey => $items) {
            $statutPourcentages[$statutKey] = [
                'percent' => $total ? round($items->count() * 100 / $total, 1) : 0
            ];
        }

        $totalChauffeurs = $total;

        return view('parc.chauffeurs.index', compact(
            'chauffeurs',
            'groupes',
            'statutPourcentages',
            'totalChauffeurs',
            'statut'
        ));
    }

    public function create()
    {
        /* $employes = Employee::whereDoesntHave('chauffeur')
            ->get(); */
        $employes = Employee::whereDoesntHave('chauffeur')
            ->with('user') // pour accéder aux champs du user
            ->get()
            ->sortBy(fn($e) => $e->user->name); // tri croissant par nom

        return view('parc.chauffeurs.create', compact('employes'));
    }

    public function store(StoreParcChauffeurRequest $request)
    {

        /* ParcChauffeur::create($request->validated()); */

        $employe = Employee::findOrFail($request->employe_id);

        ParcChauffeur::create([
            'employee_id' => $employe->id,
            'matricule' => $employe->matricule, // récupérer depuis employee
            'nom' => $employe->user->name,
            'prenom' => $employe->user->firstname,
            'telephone' => $employe->user->telephone,
            'statut' => $request->statut,
            'permis_numero' => $request->permis_numero,
            'permis_categories' => $request->permis_categories,
            'permis_expire_le' => $request->permis_expire_le,
        ]);

        return redirect()->back()->with('status', 'Chauffeur ajouté avec succès');
    }

    /*     public function show($id)
    {
        $chauffeur = ParcChauffeur::findOrFail($id);

        // Compter les missions du chauffeur dans l'année en cours
        $chauffeurMissionsCount = $chauffeur->employee->missions()
            ->whereYear('date_depart', now()->year)
            ->count();

        return view('parc.chauffeurs.show', compact('chauffeur', 'chauffeurMissionsCount'));
    } */

    public function show($id)
    {
        $chauffeur = ParcChauffeur::findOrFail($id);

        // Missions du chauffeur dans l'année en cours
        $chauffeurMissionsCount = $chauffeur->employee->missions()
            ->whereYear('date_depart', now()->year)
            ->count();

        // Missions totales du chauffeur
        $chauffeurMissionsTotal = $chauffeur->employee->missions()->count();

        return view(
            'parc.chauffeurs.show',
            compact('chauffeur', 'chauffeurMissionsCount', 'chauffeurMissionsTotal')
        );
    }

    public function edit($id)
    {
        $chauffeur = ParcChauffeur::findOrFail($id);
        return view('parc.chauffeurs.update', compact('chauffeur'));
    }

    public function update(UpdateParcChauffeurRequest $request, $id)
    {
        $chauffeur = ParcChauffeur::findOrFail($id);
        $chauffeur->update($request->validated());
        return redirect()->back()->with('status', 'Chauffeur mis à jour avec succès');
    }

    /*   public function destroy($id)
    {
        $chauffeur = ParcChauffeur::findOrFail($id);
        $chauffeur->delete();
        return redirect()->route('parc-chauffeurs.index')->with('status', 'Chauffeur supprimé avec succès');
    } */
    public function destroy(ParcChauffeur $chauffeur)
    {
        if (
            $chauffeur->employee->missions()
            ->exists()
        ) {
            return back()->with(
                'error',
                'Suppression impossible : chauffeur affecté à des missions.'
            );
        }

        $chauffeur->delete();
        return back()->with('success', 'Chauffeur supprimé.');
    }

    public function showMissions(ParcChauffeur $chauffeur)
    {
        // Missions triées par date de départ décroissante, 5 par page
        $missions = $chauffeur->employee->parcmissions()
            ->orderByDesc('date_depart')
            ->paginate(1); // Pagination Laravel

        return view('parc.chauffeurs.missions', compact('chauffeur', 'missions'));
    }

    public function missionsPdf(ParcChauffeur $chauffeur)
    {
        $missions = $chauffeur->employee
            ->parcmissions()
            ->orderByDesc('date_depart')
            ->get();

        $pdf = Pdf::loadView(
            'parc.chauffeurs.pdf.missions',
            compact('chauffeur', 'missions')
        )->setPaper('A4', 'landscape'); // Mode paysage

        return $pdf->download(
            'Recapitulatif_Missions_' .
                $chauffeur->employee->user->firstname . '_' .
                $chauffeur->employee->user->name . '.pdf'
        );
    }

    public function missionsExcel(ParcChauffeur $chauffeur)
    {
        $missions = $chauffeur->employee
            ->parcmissions()
            ->orderByDesc('date_depart')
            ->get();

        return Excel::download(
            new ChauffeurMissionsExport($missions),
            'Recapitulatif_Missions_' .
                $chauffeur->employee->user->firstname . '_' .
                $chauffeur->employee->user->name .
                '.xlsx'
        );
    }
}
