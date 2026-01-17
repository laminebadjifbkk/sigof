<?php

namespace App\Http\Controllers;

use App\Models\Departement;
use App\Models\Ingenieur;
use App\Models\Module;
use App\Models\Operateur;
use App\Models\Region;
use App\Models\TypesFormation;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use RealRashid\SweetAlert\Facades\Alert;

class IngenieurController extends Controller
{
    public function __construct()
    {
        // examples:
        $this->middleware('auth');
        $this->middleware(['role:super-admin|admin|Ingenieur|DIOF|ADIOF|Ingenieur|']);
        $this->middleware("permission:ingenieur-view", ["only" => ["index"]]);
        /* $this->middleware(['permission:arrive-show']); */
        // or with specific guard
        /* $this->middleware(['role_or_permission:super-admin']); */
    }
    public function index()
    {
        $ingenieurs = Ingenieur::orderBy("created_at", "desc")->get();

        return view("ingenieurs.index", compact("ingenieurs"));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            "matricule" => ["nullable", "string", Rule::unique('ingenieurs')->where(function ($query) {
                return $query->whereNull('deleted_at');
            })],
            "name"      => ["required", "string", Rule::unique('ingenieurs')->where(function ($query) {
                return $query->whereNull('deleted_at');
            })],
            "initiale"  => ["required", "string", Rule::unique('ingenieurs')->where(function ($query) {
                return $query->whereNull('deleted_at');
            })],
            "email"     => ["required", "string", Rule::unique('ingenieurs')->where(function ($query) {
                return $query->whereNull('deleted_at');
            })],
            "telephone" => ["required", "string", Rule::unique('ingenieurs')->where(function ($query) {
                return $query->whereNull('deleted_at');
            })],
        ]);

        $ingenieur = Ingenieur::create([
            "matricule"  => $request->input("matricule"),
            "name"       => $request->input("name"),
            "initiale"   => $request->input("initiale"),
            "specialite" => $request->input("specialite"),
            "email"      => $request->input("email"),
            "telephone"  => $request->input("telephone"),
        ]);

        $ingenieur->save();

        Alert::success('Succès !', 'Enregistrement effectué');

        return redirect()->back();
    }

    public function update(Request $request, $id)
    {
        $ingenieur = Ingenieur::findOrFail($id);

        $this->validate($request, [
            'matricule' => [
                'nullable',
                'string',
                'max:25',
                Rule::unique('ingenieurs')->ignore($id)->where(fn($query) => $query->whereNull('deleted_at')),
            ],
            'name'      => [
                'required',
                'string',
                'max:50',
                Rule::unique('ingenieurs')->ignore($id)->where(fn($query) => $query->whereNull('deleted_at')),
            ],
            'initiale'  => [
                'required',
                'string',
                'max:25',
                Rule::unique('ingenieurs')->ignore($id)->where(fn($query) => $query->whereNull('deleted_at')),
            ],
            'email'     => [
                'required',
                'string',
                'max:50',
                Rule::unique('ingenieurs')->ignore($id)->where(fn($query) => $query->whereNull('deleted_at')),
            ],
            'telephone' => [
                'required',
                'string',
                'size:12',
                Rule::unique('ingenieurs')->ignore($id)->where(fn($query) => $query->whereNull('deleted_at')),
            ],
        ]);

        $ingenieur->update([
            "matricule"  => $request->input("matricule"),
            "name"       => $request->input("name"),
            "initiale"   => $request->input("initiale"),
            "fonction"   => $request->input("fonction"),
            "specialite" => $request->input("specialite"),
            "email"      => $request->input("email"),
            "telephone"  => $request->input("telephone"),
        ]);

        $ingenieur->save();

        Alert::success('Succès ! ', 'Modification effectuée');

        return redirect()->back();
    }

    public function show($id)
    {
        $ingenieur        = Ingenieur::findOrFail($id);
        $modules          = Module::orderBy("created_at", "desc")->get();
        $departements     = Departement::orderBy("created_at", "desc")->get();
        $regions          = Region::orderBy("created_at", "desc")->get();
        $operateurs       = Operateur::orderBy("created_at", "desc")->get();
        $types_formations = TypesFormation::orderBy("created_at", "desc")->get();
        $ingenieurs       = Ingenieur::orderBy("created_at", "desc")->get();

        /* $groupes = $ingenieur->formations
            ->flatMap(function ($formation) {
                // Si aucune région
                if ($formation->regions->isEmpty()) {
                    return collect([
                        'Aucune région' => collect([$formation])
                    ]);
                }

                // Sinon, une entrée par région
                return $formation->regions->mapWithKeys(function ($region) use ($formation) {
                    return [$region->nom => $formation];
                });
            })
            ->groupBy(fn($formation, $region) => $region); */

        $groupes = $ingenieur->formations
            ->groupBy(fn($item) => $item->annee ?? 'Aucune');

        return view(
            'ingenieurs.show',
            compact(
                'ingenieur',
                'departements',
                'modules',
                'regions',
                'operateurs',
                'types_formations',
                'ingenieurs',
                'groupes'
            )
        );
    }

    public function destroy($id)
    {
        $ingenieur = Ingenieur::find($id);
        $ingenieur->delete();

        Alert::success('Succès !', 'Suppression effectuée');

        return redirect()->back();
    }


    public function corbeille()
    {
        $total_count = Ingenieur::onlyTrashed()->count();
        $total_count = number_format($total_count, 0, ',', ' ');


        $ingenieurs = Ingenieur::onlyTrashed()
            ->latest()
            ->take(100)
            ->get();

        $count_ingenieur = number_format($ingenieurs->count(), 0, ',', ' ');

        if ($count_ingenieur < 1) {
            $title = 'Aucun ingénieur supprimé';
        } elseif ($count_ingenieur == 1) {
            $title = "$count_ingenieur ingénieur supprimé sur un total de $total_count";
        } else {
            $title = "Liste des $count_ingenieur derniers ingénieurs supprimés sur un total de $total_count";
        }

        return view("ingenieurs.corbeille", compact("ingenieurs", "title"));
    }


    public function restored($id)
    {
        // Récupérer l’ingénieur supprimé
        $ingenieur = Ingenieur::onlyTrashed()->findOrFail($id);

        // Restaurer l’ingénieur
        $ingenieur->restore();

        // Restaurer automatiquement son user s'il est soft delete
        if ($ingenieur->user()->withTrashed()->exists()) {
            $ingenieur->user()->withTrashed()->restore();
        }

        // Message de succès
        return redirect()->route('ingenieurs.corbeille')
            ->with('status', 'Ingénieur restauré avec succès !');
    }
    public function forceDelete($id)
    {
        $ingenieur = Ingenieur::onlyTrashed()->findOrFail($id);

        // Supprimer définitivement l’ingénieur
        /* $ingenieur->forceDelete(); */

        // Message de succès
        return redirect()->route('ingenieurs.corbeille')
            ->with('status', 'Ingénieur supprimé définitivement avec succès !');
    }

    public function formationsParAnnee(Ingenieur $ingenieur, $annee)
    {
        $formations = $ingenieur->formations()
            ->where('annee', $annee) // ou date_fin selon ton modèle
            ->with(['regions'])
            ->get();

        $groupes = $formations
            ->flatMap(function ($formation) {
                // Si aucune région
                if ($formation->regions->isEmpty()) {
                    return collect([
                        ['region' => 'Aucune région', 'formation' => $formation]
                    ]);
                }

                // Sinon, une entrée par région
                return $formation->regions->map(function ($region) use ($formation) {
                    return ['region' => $region->nom, 'formation' => $formation];
                });
            })
            ->groupBy('region'); // maintenant les clés = noms de régions

        return view('ingenieurs.formations_par_annee', compact(
            'ingenieur',
            'annee',
            'groupes',
            'formations'
        ));
    }

    /* public function listeFormationsParAnnee(Ingenieur $ingenieur, $annee, $region = null)
    {
        // Charger toutes les formations de l'ingénieur pour l'année
        $formations = $ingenieur->formations()
            ->where('annee', $annee) // ou whereYear('date_debut', $annee) selon ta colonne
            ->with([
                'types_formation',       // pour vérifier si individuelle ou collective
                'collectivemodule',
                'listecollectives',
                'regions'
            ])
            ->get();

        // Filtrer par région si demandé
        if ($region) {
            $formations = $formations->filter(function ($formation) use ($region) {
                if ($region === 'Aucune région') {
                    return $formation->regions->isEmpty();
                }
                return $formation->regions->pluck('nom')->contains($region);
            });
        }

        // Formations individuelles
        $individuelles = $formations
            ->filter(fn($f) => $f->types_formation?->name === 'individuelle')
            ->flatMap(fn($f) => $f->individuelles);

        // Formations collectives : récupérer les bénéficiaires via listecollectives
        $collectives = $formations
            ->filter(fn($f) => $f->types_formation?->name === 'collective')
            ->flatMap(fn($f) => optional($f->collectives)->flatMap(fn($c) => $c->listecollectives ?? collect()));

        return view('ingenieurs.liste_formations_par_annee', compact(
            'ingenieur',
            'annee',
            'region',
            'individuelles',
            'collectives'
        ));
    } */

    public function listeFormationsParAnnee(Ingenieur $ingenieur, $annee, $region = null)
    {
        // 1. Charger les formations de l'ingénieur pour l'année
        $formations = $ingenieur->formations()
            ->where('annee', $annee)
            ->with([
                'types_formation',
                'individuelles',
                'listecollectives.collective', // on n'a plus besoin de la région ici
                'regions'
            ])
            ->get();

        // 2. Filtrer par région de la formation
        if ($region) {
            $formations = $formations->filter(function ($formation) use ($region) {

                if ($region === 'Aucune région') {
                    return $formation->regions->isEmpty();
                }

                return $formation->regions->pluck('nom')->contains($region);
            });
        }

        // 3. Grouper par type de formation
        $formationsParType = $formations->groupBy(fn($f) => $f->types_formation?->name);

        // 4. Formations individuelles
        $individuelles = $formationsParType
            ->get('individuelle', collect())
            ->flatMap(fn($f) => $f->individuelles ?? collect());

        // 5. Formations collectives : toutes les listecollectives associées
        $collectives = $formationsParType
            ->get('collective', collect())
            ->flatMap(fn($f) => $f->listecollectives);

        return view('ingenieurs.liste_formations_par_annee', compact(
            'ingenieur',
            'annee',
            'region',
            'individuelles',
            'collectives'
        ));
    }
}
