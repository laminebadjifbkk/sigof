<?php
namespace App\Http\Controllers;

use App\Models\Feuillepresencecollective;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class FeuillepresencecollectiveController extends Controller
{
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'presence' => ['nullable', 'in:Oui,Non'],
        ], [
            'presence.in'       => 'La valeur de la présence doit être Oui ou Non.',
            'presence.required' => 'Le champ présence est obligatoire.',
        ]);

        $feuillepresence = Feuillepresencecollective::where('emargementcollectives_id', $request->idemargement)
            ->where('listecollectives_id', $id)
            ->first();

        /*  if (! empty($request->presence) && $request->presence == 'Présent' && $individuelle?->user?->civilite == 'Mme') {
        $presence = 'Présente';
    } elseif (! empty($request->presence) && $request->presence == 'Absent' && $individuelle?->user?->civilite == 'Mme') {
        $presence = 'Absente';
    } else {
        $presence = $request->presence;
    } */

        $feuillepresence->update([
            'presence' => $request->presence,

        ]);

        Alert::success("Succès !", "Le pointage a été effectué avec succès.");

        return redirect()->back();
    }
}
