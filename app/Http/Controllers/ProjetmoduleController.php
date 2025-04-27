<?php
namespace App\Http\Controllers;

use App\Models\Individuelle;
use App\Models\Projetlocalite;
use App\Models\Projetmodule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use RealRashid\SweetAlert\Facades\Alert;

class ProjetmoduleController extends Controller
{
    public function store(Request $request)
    {
        $id = $request->input('projet');

        $this->validate($request, [
            'module'      => [
                'required',
                'string',
                Rule::unique('projetmodules')->where(function ($query) use ($id) {
                    return $query->whereNull('deleted_at')
                        ->where('projets_id', $id);
                }),
            ],
            'domaine'     => 'required|string',
            'effectif'    => 'nullable|numeric',
            'description' => 'nullable|string',
        ]);

        /*  $this->validate($request, [
            'module' => ["required", "string", Rule::unique('projetmodules')->where(function ($query) {
                return $query->whereNull('deleted_at');
            })],
            'domaine' => 'required|string',
            'effectif' => 'required|string',
        ]); */

        $projetmodule = new Projetmodule([
            "module"      => $request->input("module"),
            "domaine"     => $request->input("domaine"),
            "effectif"    => $request->input("effectif"),
            "description" => $request->input("description"),
            'projets_id'  => $request->input('projet'),
        ]);

        $projetmodule->save();

        Alert::success('Succès ! ', 'Le module a été ajouté avec succès');

        return redirect()->back();
    }

    /* public function show($id)
    {
        $projetmodule  = Projetmodule::findOrFail($id);
        $projet        = $projetmodule?->projet;
        $type_localite = $projet->type_localite;

        $individuelles = Individuelle::join('modules', 'modules.id', 'individuelles.modules_id')
            ->select('individuelles.*')
            ->where('individuelles.projets_id', $projetmodule?->projet?->id)
            ->where('modules.name', $projetmodule?->module)
            ->get();

        if ($type_localite == 'Region') {
            $region         = 'Région';
            $departement    = null;
            $arrondissement = null;
            $commune        = null;
        } elseif ($type_localite == 'Departement') {
            $departement    = 'Département';
            $arrondissement = null;
            $commune        = null;
            $region         = null;
        } elseif ($type_localite == 'Arrondissement') {
            $arrondissement = 'Arrondissement';
            $commune        = null;
            $departement    = null;
            $region         = null;
        } elseif ($type_localite == 'Commune') {
            $commune        = 'Commune';
            $departement    = null;
            $region         = null;
            $arrondissement = null;
        } else {
            $commune        = null;
            $departement    = null;
            $region         = null;
            $arrondissement = null;
        }

        return view('projets.module-individuelle',
            compact('individuelles',
                'projet',
                'commune',
                'arrondissement',
                'departement',
                'projetmodule',
                'region'
            ));

    } */

    public function edit($id)
    {
        // Récupérer le projet module avec ses relations
        $projetmodule  = Projetmodule::with('projet', 'projetlocalites')->findOrFail($id);
        $projet        = $projetmodule->projet;
        $type_localite = $projet->type_localite;

// Récupérer les localités du projet, sans doublons sur 'localite'
        $projetlocalites = Projetlocalite::where('projets_id', $projet->id)
            ->select('id', 'localite')
            ->groupBy('localite', 'id') // groupBy sur 'localite' + 'id' car MySQL impose que tous les champs sélectionnés soient groupés
            ->get();

// Récupérer les localités déjà liées au module
        $moduleLocalites = $projetmodule->projetlocalites->pluck('localite')->toArray();

        /* dd($moduleLocalites); */

        // Simplifier l'affectation des variables de localisation en utilisant un tableau
        $localites = [
            'Region'         => ['region' => 'Région', 'departement' => null, 'arrondissement' => null, 'commune' => null],
            'Departement'    => ['departement' => 'Département', 'region' => null, 'arrondissement' => null, 'commune' => null],
            'Arrondissement' => ['arrondissement' => 'Arrondissement', 'region' => null, 'departement' => null, 'commune' => null],
            'Commune'        => ['commune' => 'Commune', 'region' => null, 'departement' => null, 'arrondissement' => null],
        ];

        // Affecter les variables de localisation en fonction du type_localite
        $localiteData = $localites[$type_localite] ?? ['commune' => null, 'departement' => null, 'region' => null, 'arrondissement' => null];

        // Récupérer les individuelles avec Eloquent et relations pour plus de clarté
        $individuelles = Individuelle::whereHas('module', function ($query) use ($projetmodule) {
            $query->where('name', $projetmodule->module);
        })
            ->where('projets_id', $projet->id)
            ->get();

        return view('projets.updatemodule', array_merge(
            $localiteData,
            compact('individuelles', 'projet', 'projetmodule', 'projetlocalites', 'moduleLocalites')
        ));
    }

    public function show($id)
    {
        // Récupérer le projet module avec ses relations
        $projetmodule  = Projetmodule::with('projet')->findOrFail($id);
        $projet        = $projetmodule->projet;
        $type_localite = $projet->type_localite;

        // Simplifier l'affectation des variables de localisation en utilisant un tableau
        $localites = [
            'Region'         => ['region' => 'Région', 'departement' => null, 'arrondissement' => null, 'commune' => null],
            'Departement'    => ['departement' => 'Département', 'region' => null, 'arrondissement' => null, 'commune' => null],
            'Arrondissement' => ['arrondissement' => 'Arrondissement', 'region' => null, 'departement' => null, 'commune' => null],
            'Commune'        => ['commune' => 'Commune', 'region' => null, 'departement' => null, 'arrondissement' => null],
        ];

        // Affecter les variables de localisation en fonction du type_localite
        $localiteData = $localites[$type_localite] ?? ['commune' => null, 'departement' => null, 'region' => null, 'arrondissement' => null];

        // Récupérer les individuelles avec Eloquent et relations pour plus de clarté
        $individuelles = Individuelle::whereHas('module', function ($query) use ($projetmodule) {
            $query->where('name', $projetmodule->module);
        })
            ->where('projets_id', $projet->id)
            ->get();

        return view('projets.module-individuelle', array_merge(
            $localiteData,
            compact('individuelles', 'projet', 'projetmodule')
        ));
    }

    public function update(Request $request, $id)
    {
        $projetmodule = Projetmodule::findOrFail($id);

        $this->validate($request, [
            'module'            => 'required|string',
            'domaine'           => 'required|string',
            'effectif'          => 'nullable|string',
            'description'       => 'nullable|string',
            'projetlocalites'   => 'required|array',                  // Vérifie que la valeur est un tableau
            'projetlocalites.*' => 'exists:projetlocalites,localite', // Chaque élément doit exister dans la colonne 'localite' de la table 'projetlocalites'

        ]);

        $projetmodule->update([
            "module"      => $request->input("module"),
            "domaine"     => $request->input("domaine"),
            "effectif"    => $request->input("effectif"),
            "description" => $request->input("description"),
        ]);

        $projetmodule->save();

        // Met à jour la relation many-to-many avec les localités sélectionnées
        /* $projetmodule->projetlocalites()->sync($request->input('projetlocalites')); */
        $projetlocalites = $request->input('projetlocalites'); // tableau des localités sélectionnées

        /* foreach ($projetlocalites as $localite) {
            $projetlocaliteId = Projetlocalite::where('localite', $localite)->first()->id;

            // Insertion dans la table projetmodulelocalites
            DB::table('projetmodulelocalites')->insert([
                'projetlocalites_id' => $projetlocaliteId,
                'projetmodules_id'   => $projetmodule->id,
            ]);
        } */

        // SUPPRIMER d'abord les anciennes localités liées
        DB::table('projetmodulelocalites')
            ->where('projetmodules_id', $projetmodule->id)
            ->delete();

// Ensuite, insérer les nouvelles associations proprement
        foreach ($request->projetlocalites as $localite) {
            $projetlocaliteId = Projetlocalite::where('localite', $localite)->first()->id;
            DB::table('projetmodulelocalites')->insert([
                'projetmodules_id'   => $projetmodule->id,
                'projetlocalites_id' => $projetlocaliteId,
            ]);
        }

        Alert::success('Succès ! ', 'Le module a été modifié avec succès');

        return redirect()->back();
    }

    public function destroy($id)
    {
        $projetmodule = Projetmodule::findOrFail($id);
        $projetmodule->delete();

        Alert::success('Succès !', 'Le module a été supprimé avec succès');
        return redirect()->back();
    }
}
