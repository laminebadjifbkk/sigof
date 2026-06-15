<?php

namespace App\Http\Controllers;

use App\Models\Individuelle;
use App\Models\Listecollective;
use App\Services\NumeroAttestationService;
use Illuminate\Http\Request;

class VerificationAttestationController extends Controller
{
    // Affiche le formulaire
    public function index()
    {
        return view('attestations.verifier');
    }

    // Traite la recherche par numéro
    public function recherche(Request $request)
    {
        $numero = strtoupper(trim($request->query('numero')));

        if (!NumeroAttestationService::verifier($numero)) {
            return back()->with('error', 'Numéro invalide. Vérifiez la saisie et réessayez.');
        }

        $item = Listecollective::where('numero_attestation', $numero)->first()
             ?? Individuelle::where('numero_attestation', $numero)->first();

        if (!$item) {
            return back()->with('error', 'Aucune attestation trouvée avec ce numéro.');
        }

        return redirect()->route('attestation.verifier.numero', $numero);
    }

    // Affiche le résultat
    public function afficher(string $numero)
    {
        if (!NumeroAttestationService::verifier($numero)) {
            return view('attestations.invalide');
        }

        $item = Listecollective::with('formation')
                    ->where('numero_attestation', $numero)->first();
        $type = 'collective';

        if (!$item) {
            $item = Individuelle::with(['user', 'formation'])
                        ->where('numero_attestation', $numero)->first();
            $type = 'individuelle';
        }

        if (!$item) {
            return view('attestations.invalide');
        }

        $formation = $type === 'collective' ? $item->formations : $item->formation;

        return view('attestations.verification', compact('item', 'type', 'formation'));
    }
}