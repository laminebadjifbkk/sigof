<?php
namespace App\Http\Controllers;

use App\Models\Emargementcollective;
use App\Models\Feuillepresencecollective;
use App\Models\Formation;
use App\Models\Listecollective;
use App\Models\Validationindividuelle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class EmargementcollectiveController extends Controller
{

    public function formationemargementcollective(Request $request)
    {
        dd('ok');
        $formation            = Formation::findOrFail($request->input('idformation'));
        $emargementcollective = Emargementcollective::findOrFail($request->input('idemargement'));

        $feuillepresencecollectives = Feuillepresencecollective::where('emargementcollectives_id', $request->input('idemargement'))->get();

        $collectiveFormation = DB::table('listecollectives')
            ->where('formations_id', $formation?->id)
            ->pluck('formations_id', 'formations_id')
            ->all();

        $collectiveFormationCheck = DB::table('listecollectives')
            ->where('formations_id', '!=', null)
            ->where('formations_id', '!=', $formation?->id)
            ->pluck('formations_id', 'formations_id')
            ->all();

        $feuillepresenceListecollective = DB::table('feuillepresencecollectives')
            ->where('emargementcollectives_id', $emargementcollective?->id)
            ->pluck('emargementcollectives_id', 'emargementcollectives_id')
            ->all();

        return view(
            "emargementcollectives.show",
            compact(
                'emargementcollective',
                'formation',
                'feuillepresenceListecollective',
                'feuillepresencecollectives',
            )
        );
    }

    public function update(Request $request, $id)
    {
        $emargementcollective = Emargementcollective::findOrFail($id);

        $this->validate($request, [
            'jour'    => "required|string",
            'feuille' => "nullable|file|mimes:pdf,jpg,jpeg,png|max:1024",
            'date'    => 'nullable|date|size:10|date_format:Y-m-d',
        ]);

        if (! empty($request->input('date'))) {
            $date = $request->input('date');
        } else {
            $date = null;
        }

        /* if (request('feuille') && ! empty($emargementcollective->file)) {
            Storage::disk('public')->delete($emargementcollective->file);
            $filePath = request('feuille')->store('feuilles', 'public');
            $file     = $request->file('feuille');
            $emargementcollective->update([
                'file' => $filePath,
            ]);

            $emargementcollective->save();

        } elseif (request('feuille') && empty($emargementcollective->file)) {
            $filePath = request('feuille')->store('feuilles', 'public');
            $file     = $request->file('feuille');
            $emargementcollective->update([
                'file' => $filePath,
            ]);

            $emargementcollective->save();
        } */

        if (request()->hasFile('feuille')) {
            // Si un fichier est envoyé, on supprime l'ancien fichier si nécessaire
            if (! empty($emargementcollective->file)) {
                Storage::disk('public')->delete($emargementcollective->file);
            }

            // Générer un nom unique pour le fichier en ajoutant l'horodatage
            $file     = request()->file('feuille');
            $fileName = time() . '_' . $file->getClientOriginalName();

            // Stocker le fichier avec le nouveau nom
            $filePath = $file->storeAs('feuilles', $fileName, 'public');

            // Mise à jour de l'emargement avec le nouveau chemin du fichier
            $emargementcollective->update([
                'file' => $filePath,
            ]);
        }

        $emargementcollective->update([
            'jour'         => $request->jour,
            'date'         => $date,
            'observations' => $request->observations,

        ]);

        $emargementcollective->save();

        Alert::success("Succès !", "La modification a été effectuée avec succès.");

        return redirect()->back();
    }

    public function giveformationemargementcollective(Request $request, $idformation)
    {
        $request->validate([
            'collectives' => ['required'],
        ]);

        $formation = Formation::findOrFail($idformation);

        if ($formation->statut == "Terminée") {
            Alert::warning('Désolé !', 'Cette formation a déjà été exécutée.');
        } elseif ($formation->statut == 'Annulée') {
            Alert::warning('Désolé !', 'La formation a été annulée.');
        } else {
            foreach ($request->collectives as $collective_id) {
                $collective = Listecollective::findOrFail($collective_id);
                $collective->update([
                    "formations_id" => $idformation,
                    "statut"        => 'Sélectionné',
                ]);

                // Enregistrement de la validation
                Validationindividuelle::create([
                    'validated_id'     => Auth::id(),
                    'action'           => 'Sélectionné',
                    'collectives_id' => $collective->id,
                ]);
            }

            Alert::success('Opération réussie !', 'Le(s) candidat(s) a/ont été ajouté(s) avec succès.');
        }

        return redirect()->back();
    }

    public function destroy($id)
    {
        $emargementcollective = Emargementcollective::findOrFail($id);

        $feuillesPresenceCollectives = $emargementcollective->feuillesPresenceCollectives;

        foreach ($feuillesPresenceCollectives as $key => $feuillesPresenceCollective) {
            $feuillesPresenceCollective->delete();
        }

        /* $feuillepresenceListecollective = $emargementcollective->feui */

        $emargementcollective->delete();

        Alert::success('Succès !', 'La suppression a été effectuée avec succès');

        return redirect()->back();
    }
}
