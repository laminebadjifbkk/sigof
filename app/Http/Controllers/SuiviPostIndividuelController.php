<?php

namespace App\Http\Controllers;

use App\Models\Individuelle;
use App\Models\SuiviPostIndividuel;
use Illuminate\Http\Request;

class SuiviPostIndividuelController extends Controller
{
    // Méthode index attendue par Laravel
    public function index()
    {
        $suivis = SuiviPostIndividuel::all();
        return view('suivi_post_individuels.index', compact('suivis'));
    }

    public function create()
    {
        $individuelles = Individuelle::all(); // récupère les individus pour le select
        return view('suivi_post_individuels.create', compact('individuelles'));
    }

    public function store(Request $request)
    {
        SuiviPostIndividuel::create([
            'individuelles_id' => $request->individuelle_id,
            'situation_actuelle' => $request->situation,
            'temps_emploi' => $request->temps_emploi,
            'entreprise' => $request->entreprise,
            'secteur' => $request->secteur,
            'lien_formation' => $request->lien,
            'revenu' => $request->revenu,
            'formation_marche' => $request->formation_marche,
            'competences_utilisees' => $request->competences,
            'recommande' => $request->recommande,
            'difficultes' => json_encode($request->difficultes),
            'besoins' => json_encode($request->besoins),
            'diplome_retire' => $request->diplome,
            'commentaires' => $request->commentaires,
        ]);

        return redirect()->back()->with('success', 'Suivi enregistré');
    }

    public function destroy($id)
    {
        $suivi = SuiviPostIndividuel::findOrFail($id);

        $suivi->delete();

        return redirect()
            ->route('individuels.index')
            ->with('success', 'Suivi supprimé avec succès.');
    }
}
