<?php
namespace App\Http\Controllers;

use App\Models\Arrondissement;
use App\Models\Commune;
use App\Models\Departement;
use App\Models\Individuelle;
use App\Models\Projet;
use App\Models\Projetlocalite;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use RealRashid\SweetAlert\Facades\Alert;

class ProjetlocaliteController extends Controller
{
    public function showLocalites($id)
    {
        $projetlocalites = Projetlocalite::where('projets_id', $id)->get();
        $projet          = Projet::findOrFail($id);

        return view('projetlocalites.index', compact('projetlocalites', 'projet'));
    }

    public function store(Request $request)
    {
        /* $id = $request->input('projet');
        $this->validate($request, [
            'localite' => ["required", "string", Rule::unique('projetlocalites')->where(function ($query) {
                return $query->whereNull('deleted_at');
            })],
            'effectif' => 'required|string',
        ]); */

        $id = $request->input('projet');

        $this->validate($request, [
            'localite' => [
                'required',
                'string',
                Rule::unique('projetlocalites')->where(function ($query) use ($id) {
                    return $query->whereNull('deleted_at')
                        ->where('projets_id', $id);
                }),
            ],
            'effectif' => 'nullable|numeric',
        ]);

        $localite = $request?->type_localite;

        if (! empty($localite) && $localite == 'Commune') {
            $lieu = Commune::where('nom', $request->input("localite"))->first();
            if (! empty($lieu)) {
                $projetlocalite = new Projetlocalite([
                    "localite"   => $request->input("localite"),
                    "effectif"   => $request->input("effectif"),
                    'projets_id' => $request->input('projet'),
                ]);
                $projetlocalite->save();
                Alert::success('Succès ! ', 'localité ajoutée avec succès');
            } else {
                Alert::warning('Désolé ! ', $request->input("localite") . " n'est pas reconnu(e) comme " . $request?->type_localite);
            }
            return redirect()->back();
        }
        if (! empty($localite) && $localite == 'Arrondissement') {
            $lieu = Arrondissement::where('nom', $request->input("localite"))->first();
            if (! empty($lieu)) {
                $projetlocalite = new Projetlocalite([
                    "localite"   => $request->input("localite"),
                    "effectif"   => $request->input("effectif"),
                    'projets_id' => $request->input('projet'),
                ]);
                $projetlocalite->save();
                Alert::success('Succès ! ', 'localité ajoutée avec succès');
            } else {
                Alert::warning('Désolé ! ', $request->input("localite") . " n'est pas reconnu(e) comme " . $request?->type_localite);
            }
            return redirect()->back();
        }
        if (! empty($localite) && $localite == 'Departement') {
            $lieu = Departement::where('nom', $request->input("localite"))->first();
            if (! empty($lieu)) {
                $projetlocalite = new Projetlocalite([
                    "localite"   => $request->input("localite"),
                    "effectif"   => $request->input("effectif"),
                    'projets_id' => $request->input('projet'),
                ]);
                $projetlocalite->save();
                Alert::success('Succès ! ', 'localité ajoutée avec succès');
            } else {
                Alert::warning('Désolé ! ', $request->input("localite") . " n'est pas reconnu(e) comme " . $request?->type_localite);
            }
            return redirect()->back();
        }
        if (! empty($localite) && $localite == 'Region') {
            $lieu = Region::where('nom', $request->input("localite"))->first();
            if (! empty($lieu)) {
                $projetlocalite = new Projetlocalite([
                    "localite"   => $request->input("localite"),
                    "effectif"   => $request->input("effectif"),
                    'projets_id' => $request->input('projet'),
                ]);
                $projetlocalite->save();
                Alert::success('Succès ! ', 'localité ajoutée avec succès');
            } else {
                Alert::warning('Désolé ! ', $request->input("localite") . " n'est pas reconnu(e) comme " . $request?->type_localite);
            }
            return redirect()->back();
        }
    }

/*     public function update(Request $request, Projetlocalite $projetlocalite)
    {
        $this->validate($request, [
            'localite' => ["required", "string", Rule::unique('projetlocalites')->where(function ($query) {
                return $query->whereNull('deleted_at');
            })->ignore($projetlocalite->id)],
            'effectif' => 'nullable|numeric',
        ]);

        $projetlocalite->update([
            "localite"   => $request->input("localite"),
            "effectif"   => $request->input("effectif"),
            'projets_id' => $request->input('id'),
        ]);

        $projetlocalite->save();

        Alert::success('Succès ! ', 'La localité a été modifiée avec succès');

        return redirect()->back();
    } */

    public function update(Request $request, Projetlocalite $projetlocalite)
    {
        $request->validate([
            'localite' => [
                'required',
                'string',
                Rule::unique('projetlocalites', 'localite')
                    ->ignore($projetlocalite->id) // Ignore la localité actuelle
                    ->where(function ($query) use ($projetlocalite) {
                        return $query->whereNull('deleted_at')
                            ->where('projets_id', $projetlocalite->projets_id); // S'assurer que c'est dans le même projet
                    }),
            ],
            'effectif' => 'nullable|numeric|min:0',
        ]);

        $projetlocalite->update([
            'localite'   => $request->input('localite'),
            'effectif'   => $request->input('effectif'),
            'projets_id' => $request->input('id'), // À garder seulement si l'ID du projet peut changer ici
        ]);

        Alert::success('Succès !', 'La localité a été modifiée avec succès.');

        return redirect()->back();
    }

    /*  public function show(Projetlocalite $projetlocalite)
    {
        $projet         = $projetlocalite?->projet;
        $type_localite  = $projet->type_localite;

        $individuelles = Individuelle::join('modules', 'modules.id', 'individuelles.modules_id')
            ->select('individuelles.*')
            ->where('individuelles.projets_id', $projet?->id)
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

        return view('projetlocalites.individuelle',
            compact('individuelles',
                'projet',
                'commune',
                'arrondissement',
                'departement',
                'projetlocalite',
                'region'
            ));

    } */

    public function show(Projetlocalite $projetlocalite)
    {
        $projet        = $projetlocalite->projet;
        $type_localite = $projet?->type_localite;

        /* $individuelles = Individuelle::where('projets_id', $projet?->id)
            ->whereHas('module') // Vérifie que le module existe
            ->get(); */

        if ($type_localite === 'Departement') {
            $departement   = Departement::where('nom', $projetlocalite->localite)->first();
            $individuelles = Individuelle::where('projets_id', $projet?->id)
                ->where('departements_id', $departement?->id)
                ->get();

        } elseif ($type_localite === 'Region') {
            $region        = Region::where('nom', $projetlocalite->localite)->first();
            $individuelles = Individuelle::where('projets_id', $projet?->id)
                ->where('regions_id', $region?->id)
                ->get();

        } else {
            // Fallback vers département par défaut
            $departement   = Departement::where('nom', $projetlocalite->localite)->first();
            $individuelles = Individuelle::where('projets_id', $projet?->id)
                ->where('departements_id', $departement?->id)
                ->get();
        }

        // Initialiser toutes les variables à null
        $region = $departement = $arrondissement = $commune = null;

        // Remplir uniquement celle correspondant au type_localite
        switch ($type_localite) {
            case 'Region':
                $region = 'Région';
                break;
            case 'Departement':
                $departement = 'Département';
                break;
            case 'Arrondissement':
                $arrondissement = 'Arrondissement';
                break;
            case 'Commune':
                $commune = 'Commune';
                break;
        }

        return view('projetlocalites.individuelle', compact(
            'individuelles',
            'projet',
            'commune',
            'arrondissement',
            'departement',
            'projetlocalite',
            'region'
        ));
    }

    public function destroy(Projetlocalite $projetlocalite)
    {
        $projetlocalite->delete();

        Alert::success('Succès ! ', 'La localité a été supprimée avec succès');

        return redirect()->back();
    }

}
