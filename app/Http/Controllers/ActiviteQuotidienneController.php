<?php

namespace App\Http\Controllers;

use App\Models\ActiviteQuotidienne;
use App\Models\User;
use Illuminate\Http\Request;

class ActiviteQuotidienneController extends Controller
{

    public function create()
    {
        $users = User::orderBy('name')->get();

        return view('activites.create', compact('users'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'titre' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
            'date_activite' => 'required|date'
        ]);

        ActiviteQuotidienne::create([
            'titre' => $request->titre,
            'description' => $request->description,
            'user_id' => $request->user_id,
            'date_activite' => $request->date_activite,
            'priorite' => $request->priorite,
            'heure_debut' => $request->heure_debut,
            'heure_fin' => $request->heure_fin,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Activité créée avec succès');
    }

    public function index(Request $request)
    {
        $query = ActiviteQuotidienne::query();

        $statut = $request->query('statut');
        $annee  = $request->query('annee');

        // Filtre statut
        if ($statut) {
            $query->where('statut', $statut);
        }

        // Filtre année
        if ($annee) {
            $query->whereYear('date_activite', $annee);
        }

        // Activités filtrées
        /* $activites = $query->with('user')->orderBy('date_activite', 'desc')->get(); */

        $activites = $query
            ->with('user')->orderBy('date_activite', 'desc')
            ->limit(100)
            ->get();


        // Pour les cards : grouper toutes les activités par statut
        $allActivites = ActiviteQuotidienne::with('user')->orderBy('date_activite', 'desc')->get();
        $groupes = $allActivites->groupBy(fn($item) => $item->statut ?? 'Aucun');

        // Calcul des pourcentages par statut
        $total = $allActivites->count();
        $statutPourcentages = $groupes->mapWithKeys(function ($items, $statutKey) use ($total) {
            return [$statutKey => ['percent' => $total ? round($items->count() * 100 / $total, 1) : 0]];
        });

        // Totaux
        $totalActivites = $total;
        $activitesAnnee = ActiviteQuotidienne::whereYear('date_activite', now()->year)->count();

        $affichees = $activites?->count();
        $total     = $totalIndividuelles ?? ($activites instanceof \Illuminate\Pagination\LengthAwarePaginator
            ? $activites->total()
            : $activites?->count());

        // Labels lisibles (optionnel)
        $labels = [
            'en_attente' => 'En attente',
            'en_cours'   => 'En cours',
            'terminee'   => 'Terminée',
            'validee'    => 'Validée',
            'rejete'     => 'Rejetée',
            'urgente'     => 'Urgente',
            'normale'     => 'Normale',
            'faible'     => 'Faible',
        ];

        return view('activites.index', compact(
            'activites',
            'groupes',
            'statutPourcentages',
            'totalActivites',
            'activitesAnnee',
            'labels',
            'statut',
            'affichees',
            'total'
        ));
    }

    // Afficher une activité
    public function show($id)
    {
        $activitequotidienne = ActiviteQuotidienne::findOrFail($id);
        $labels = [
            'en_attente' => 'En attente',
            'en_cours'   => 'En cours',
            'terminee'   => 'Terminée',
            'validee'    => 'Validée',
            'rejete'     => 'Rejetée',
            'urgente'     => 'Urgente',
            'normale'     => 'Normale',
            'faible'     => 'Faible',
        ];
        return view('activites.show', compact('activitequotidienne', 'labels'));
    }

    // Formulaire modification
    public function edit($id)
    {
        $activitequotidienne = ActiviteQuotidienne::findOrFail($id);
        // Labels lisibles (optionnel)
        $labels = [
            'en_attente' => 'En attente',
            'en_cours'   => 'En cours',
            'terminee'   => 'Terminée',
            'validee'    => 'Validée',
            'rejete'     => 'Rejetée',
            'urgente'     => 'Urgente',
            'normale'     => 'Normale',
            'faible'     => 'Faible',
        ];
        return view('activites.update', compact('activitequotidienne', 'labels'));
    }

    // Mettre à jour une activité
    public function update(Request $request, $id)
    {

        $activitequotidienne = ActiviteQuotidienne::findOrFail($id);

        $request->validate([
            'titre'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'date_activite' => 'required|date',
            'priorite'     => 'required|in:faible,normale,urgente',
            'statut'       => 'required|in:en_attente,en_cours,terminee,validee,rejete',
        ]);

        $activitequotidienne->update($request->only([
            'titre',
            'description',
            'date_activite',
            'priorite',
            'heure_debut',
            'heure_fin',
            'statut'
        ]));

        return redirect()->back()
            ->with('status', 'Activité modifiée avec succès !');
    }

    public function destroy($id)
    {
        $activitequotidienne = ActiviteQuotidienne::findOrFail($id);

        $activitequotidienne->delete();

        return redirect()->back()
            ->with('status', 'Activité supprimée avec succès !');
    }
}
