<?php

namespace App\Http\Controllers;

use App\Models\Detf;
use App\Models\Ingenieur;
use App\Models\Operateur;
use Illuminate\Http\Request;

class DetfController extends Controller
{
    public function create()
    {
        $operateurs = Operateur::where('statut_agrement', 'agréé')->get();
        $ingenieurs = Ingenieur::get();
        return view('detfs.create', compact('operateurs', 'ingenieurs'));
    }


    public function index(Request $request)
    {
        $query = Detf::query();

        $etat = $request->query('etat');

        // Filtre etat
        if ($etat) {
            $query->where('etat', $etat);
        }

        // Charger Detf filtrées, avec count employés (pour bouton delete)
        $detfs = $query->latest()->get();

        // Pour les cards : grouper toutes les Detf par statut
        $allDetf = Detf::latest()->get();
        $groupes = $allDetf->groupBy(fn($item) => $item->etat ?? 'Aucun');

        // Calcul des pourcentages par statut
        $total = $allDetf->count();
        $statutPourcentages = $groupes->mapWithKeys(function ($items, $statutKey) use ($total) {
            return [$statutKey => ['percent' => $total ? round($items->count() * 100 / $total, 1) : 0]];
        });

        // Vérifier si un statut est passé
        $labels = [
            'planifiee' => 'Planifiées',
            'en_cours' => 'En cours',
            'terminee' => 'Terminées',
            'annulee' => 'Annulées',
        ];

        return view('detfs.index', compact(
            'detfs',
            'groupes',
            'statutPourcentages',
            'total',
            'labels',
            'etat'
        ));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'titre1' => 'nullable|string',
            'titre2' => 'nullable|string',
            'date1' => 'nullable|date',
            'operateurs_id' => 'nullable|integer',
        ]);

        $numero = 'DETF-' . date('Y') . '-' . str_pad(Detf::count() + 1, 3, '0', STR_PAD_LEFT);

        /* Detf::create($data); */

        Detf::create([
            'numero' => $numero,
            'titre1' => $request->titre1,
            'titre2' => $request->titre2,
            'date1'  => $request->date1,
            'etat'  => 'Nouveau',
            'operateurs_id'  => $request->operateurs_id,
            'ingenieurs_id'  => $request->ingenieurs_id,
        ]);

        return redirect()->route('detfs.create')->with('success', 'DETF créée avec succès !');
    }

    public function edit(Detf $detf)
    {
        $operateurs = Operateur::all();
        $ingenieurs = Ingenieur::all();

        return view('detfs.update', compact('detf', 'operateurs', 'ingenieurs'));
    }

    public function update(Request $request, Detf $detf)
    {
        $detf->update($request->all());

        return redirect()->back()
            ->with('success', 'DETF modifié avec succès.');
    }

    public function destroy(Detf $detf)
    {
        $detf->delete();

        return back()->with('success', 'DETF supprimé avec succès.');
    }
}
