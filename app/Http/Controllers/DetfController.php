<?php

namespace App\Http\Controllers;

use App\Models\Detf;
use App\Models\Operateur;
use Illuminate\Http\Request;

class DetfController extends Controller
{
    public function create()
    {
        $operateurs = Operateur::where('statut_agrement', 'agréé')->get();
        return view('detfs.create', compact('operateurs'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'titre1' => 'nullable|string',
            'titre2' => 'nullable|string',
            'date1' => 'nullable|date',
            'operateurs_id' => 'nullable|integer',
        ]);

        Detf::create($data);

        return redirect()->route('detfs.create')->with('success', 'Formation créée avec succès !');
    }
}
