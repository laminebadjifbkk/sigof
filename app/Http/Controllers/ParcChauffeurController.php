<?php

namespace App\Http\Controllers;

use App\Models\ParcChauffeur;
use App\Http\Requests\StoreParcChauffeurRequest;
use App\Http\Requests\UpdateParcChauffeurRequest;
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
        /* $chauffeurs = ParcChauffeur::query(); */
        $annee = now()->year;

        $chauffeurs = ParcChauffeur::query()
            ->withCount([
                'missions as missions_annee_count' => function ($query) use ($annee) {
                    $query->whereYear('date_depart', $annee);
                }
            ]);
        // Récupérer l'état depuis la query string si présent
        $statutChauffeur = $request->query('statut');

        if ($statutChauffeur) {
            $chauffeurs->where('statut', $statutChauffeur);
        }

        $chauffeurs = $chauffeurs->latest()->get();

        // Regrouper par état pour les cards (non filtré)
        $groupes = ParcChauffeur::latest()->get()->groupBy(fn($v) => $v->statut ?? 'Inconnu');

        // Calculer les pourcentages
        $total = ParcChauffeur::count();
        $statutPourcentages = [];
        foreach ($groupes as $statutKey => $items) {
            $statutPourcentages[$statutKey] = [
                'percent' => $total ? round($items->count() * 100 / $total, 1) : 0
            ];
        }

        $totalChauffeurs = $total;

        return view('parc.chauffeurs.index', compact('chauffeurs', 'groupes', 'statutPourcentages', 'totalChauffeurs', 'statutChauffeur'));
    }

    public function create()
    {
        return view('parc.chauffeurs.create');
    }

    public function store(StoreParcChauffeurRequest $request)
    {
        ParcChauffeur::create($request->validated());
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
