<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreParcChauffeurRequest;
use App\Http\Requests\UpdateParcChauffeurRequest;
use App\Models\Employee;
use App\Models\ParcChauffeur;
use Illuminate\Http\Request;

class ParcChauffeurController extends Controller
{
    /* public function index()
    {
        $chauffeurs = ParcChauffeur::latest()->get();
        return view('parc.chauffeurs.index', compact('chauffeurs'));
    } */

    public function index(Request $request)
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
    }

    public function create()
    {
        $employes = Employee::whereDoesntHave('chauffeur')
            ->get();

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

    public function show($id)
    {
        $chauffeur = ParcChauffeur::findOrFail($id);

        // Compter les missions du chauffeur dans l'année en cours
        $chauffeurMissionsCount = $chauffeur->missions()
            ->whereYear('date_depart', now()->year)
            ->count();

        return view('parc.chauffeurs.show', compact('chauffeur', 'chauffeurMissionsCount'));
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

    public function destroy($id)
    {
        $chauffeur = ParcChauffeur::findOrFail($id);
        $chauffeur->delete();
        return redirect()->route('parc-chauffeurs.index')->with('status', 'Chauffeur supprimé avec succès');
    }
}
