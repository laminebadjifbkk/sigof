<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreParcVehiculeRequest;
use App\Http\Requests\UpdateParcVehiculeRequest;
use App\Models\ParcChauffeur;
use App\Models\ParcVehicule;
use Illuminate\Http\Request;

class ParcVehiculeController extends Controller
{
    public function index(Request $request)
    {
        $vehicules = ParcVehicule::query();

        // Récupérer l'état depuis la query string si présent
        $etatVehicule = $request->query('etat');

        if ($etatVehicule) {
            $vehicules->where('etat', $etatVehicule);
        }

        $vehicules = $vehicules->latest()->get();

        // Regrouper par état pour les cards (non filtré)
        $groupes = ParcVehicule::latest()->get()->groupBy(fn($v) => $v->etat ?? 'Inconnu');

        // Calculer les pourcentages
        $total = ParcVehicule::count();
        $etatPourcentages = [];
        foreach ($groupes as $etatKey => $items) {
            $etatPourcentages[$etatKey] = [
                'percent' => $total ? round($items->count() * 100 / $total, 1) : 0
            ];
        }

        $totalVehicules = $total;

        return view('parc.vehicules.index', compact('vehicules', 'groupes', 'etatPourcentages', 'totalVehicules', 'etatVehicule'));
    }

    public function create()
    {
        $chauffeurs = ParcChauffeur::all();
        return view('parc.vehicules.create', compact('chauffeurs'));
    }

    public function store(StoreParcVehiculeRequest $request)
    {
        ParcVehicule::create($request->validated());
        return redirect()->back()->with('status', 'Véhicule ajouté avec succès');
    }

    public function show($id)
    {
        $vehicule = ParcVehicule::findOrFail($id);

        // Compter les missions du véhicule dans l'année en cours
        $vehiculeMissionsCount = $vehicule->missions()
            ->whereYear('date_depart', now()->year)
            ->count();

        return view('parc.vehicules.show', compact('vehicule', 'vehiculeMissionsCount'));
    }

    public function edit($id)
    {
        $vehicule = ParcVehicule::findOrFail($id);
        $chauffeurs = ParcChauffeur::all();
        return view('parc.vehicules.update', compact('vehicule', 'chauffeurs'));
    }

    public function update(UpdateParcVehiculeRequest $request, $id)
    {
        $vehicule = ParcVehicule::findOrFail($id);

        // Récupérer uniquement les données validées
        $data = $request->validated();

        // Si chauffeur_id est vide, on le met à null
        if (empty($data['chauffeur_id'])) {
            $data['chauffeur_id'] = null;
        }

        $vehicule->update($data);

        return redirect()
            ->back()
            ->with('status', 'Véhicule modifié avec succès');
    }

    public function destroy($id)
    {
        ParcVehicule::destroy($id);
        return redirect()->route('parc-vehicules.index')->with('status', 'Véhicule supprimé avec succès');
    }

    public function showMissions(ParcVehicule $vehicule)
    {
        // Missions triées par date de départ décroissante, 1 par page
        $missions = $vehicule->missions()
            ->orderByDesc('date_depart')
            ->paginate(1);

        return view('parc.vehicules.missions', compact('vehicule', 'missions'));
    }
}
