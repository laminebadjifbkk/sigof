<?php
namespace App\Http\Controllers;

use App\Models\Individuelle;
use App\Models\Projetmodule;
use Illuminate\Http\Request;
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
            'module'      => 'required|string',
            'domaine'     => 'required|string',
            'effectif'    => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $projetmodule->update([
            "module"      => $request->input("module"),
            "domaine"     => $request->input("domaine"),
            "effectif"    => $request->input("effectif"),
            "description" => $request->input("description"),
        ]);

        $projetmodule->save();

        Alert::success('Effectué ! ', 'module modifié avec succès');

        return redirect()->back();
    }

    public function destroy($id)
    {
        $projetmodule = Projetmodule::find($id);
        $projetmodule->delete();

        Alert::success('Effectué', 'supprimé avec succès');
        return redirect()->back();
    }
}
