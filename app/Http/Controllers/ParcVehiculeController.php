<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreParcVehiculeRequest;
use App\Http\Requests\UpdateParcVehiculeRequest;
use App\Models\ParcChauffeur;
use App\Models\ParcVehicule;
use Illuminate\Http\Request;

class ParcVehiculeController extends Controller
{
    public function index()
    {
        $vehicules = ParcVehicule::latest()->get();
        return view('parc.vehicules.index', compact('vehicules'));
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
        return view('parc.vehicules.show', compact('vehicule'));
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
}
