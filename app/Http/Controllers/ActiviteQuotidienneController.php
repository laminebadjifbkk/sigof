<?php

namespace App\Http\Controllers;

use App\Models\ActiviteQuotidienne;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;

class ActiviteQuotidienneController extends Controller
{

    public function create()
    {
        $employes = Employee::get();

        return view('activites.create', compact('employes'));
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

        // Récupération des paramètres de filtre
        $statut = $request->query('statut'); // peut être null
        $annee  = $request->query('annee');  // peut être null
        $filter = $request->query('filter'); // peut être null

        // Filtre statut
        if ($statut) {
            $query->where('statut', $statut);
        }

        // Filtre année
        if ($annee) {
            $query->whereYear('date_activite', $annee);
        }

        if ($filter) {
            if ($filter === 'today') {
                $query->whereDate('date_activite', now());
            } elseif ($filter === 'week') {
                $query->whereBetween('date_activite', [
                    now()->startOfWeek(),
                    now()->endOfWeek()
                ]);
            }
        }
        // Activités filtrées
        $activites = $query
            ->with('user')
            ->orderBy('date_activite', 'desc')
            ->limit(100)
            ->get();

        /*
    |--------------------------------------------------------------------------
    | Dashboard statistiques
    |--------------------------------------------------------------------------
    */

        $allActivites = ActiviteQuotidienne::with('user')->get();

        $groupes = $allActivites->groupBy(fn($item) => $item->statut ?? 'Aucun');

        $total = $allActivites->count();

        $statutPourcentages = $groupes->mapWithKeys(function ($items, $statutKey) use ($total) {
            return [
                $statutKey => [
                    'percent' => $total ? round($items->count() * 100 / $total, 1) : 0
                ]
            ];
        });

        /*
    |--------------------------------------------------------------------------
    | Totaux
    |--------------------------------------------------------------------------
    */

        $totalActivites = $total;

        $activitesAnnee = ActiviteQuotidienne::whereYear(
            'date_activite',
            now()->year
        )->count();

        $activitesJour = ActiviteQuotidienne::whereDate(
            'date_activite',
            now()
        )->count();

        $activitesRetard = ActiviteQuotidienne::where('statut', 'retard')->count();

        $activitesTerminees = ActiviteQuotidienne::where('statut', 'terminee')
            ->whereDate('updated_at', now())
            ->count();

        /*
    |--------------------------------------------------------------------------
    | Compteur affichage
    |--------------------------------------------------------------------------
    */

        $affichees = $activites->count();
        $total = $allActivites->count();

        /*
    |--------------------------------------------------------------------------
    | Labels
    |--------------------------------------------------------------------------
    */

        $labels = [
            'en_attente' => 'En attente',
            'en_cours'   => 'En cours',
            'terminee'   => 'Terminée',
            'validee'    => 'Validée',
            'rejete'     => 'Rejetée',
            'retard'     => 'En retard',

            // Priorité
            'urgente' => 'Urgente',
            'normale' => 'Normale',
            'faible'  => 'Faible',
        ];

        return view('activites.index', compact(
            'activites',
            'groupes',
            'statutPourcentages',
            'totalActivites',
            'activitesAnnee',
            'activitesJour',
            'activitesRetard',
            'activitesTerminees',
            'labels',
            'statut',
            'filter',
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
            'retard'     => 'Retard',
        ];
        return view('activites.show', compact('activitequotidienne', 'labels'));
    }

    // Formulaire modification
    public function edit($id)
    {
        $activitequotidienne = ActiviteQuotidienne::with('user')->find($id);
        $employes = Employee::get();
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
            'retard'     => 'Retard',
        ];
        return view('activites.update', compact('activitequotidienne', 'labels', 'employes'));
    }

    // Mettre à jour une activité
    public function update(Request $request, $id)
    {

        $activitequotidienne = ActiviteQuotidienne::findOrFail($id);

        $request->validate([
            'titre'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'date_activite' => 'required|date',
            'heure_debut' => 'required',
            'heure_fin' => 'required',
            'priorite'     => 'required|in:faible,normale,urgente',
            'statut'       => 'required|in:en_attente,en_cours,terminee,validee,rejete,retard',
            'user_id' => 'required|exists:users,id',
        ]);

        $activitequotidienne->update($request->only([
            'titre',
            'description',
            'date_activite',
            'priorite',
            'heure_debut',
            'heure_fin',
            'user_id',
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
