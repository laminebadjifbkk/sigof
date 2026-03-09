<?php

namespace App\Http\Controllers;

use App\Models\SuiviPostIndividuel;
use Illuminate\Http\Request;

class SuiviPostIndividuelController extends Controller
{
    public function storeIndividuel(Request $request)
    {
        SuiviPostIndividuel::create([
            'individuelle_id' => $request->individuelle_id,
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
}
