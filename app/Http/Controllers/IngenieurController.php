<?php

namespace App\Http\Controllers;

use App\Models\Departement;
use App\Models\Formation;
use App\Models\Individuelle;
use App\Models\Ingenieur;
use App\Models\Listecollective;
use App\Models\Module;
use App\Models\Operateur;
use App\Models\Region;
use App\Models\TypesFormation;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\DB;

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

    public function index(Request $request)
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

    public function update(Request $request, int $id)
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
                'size:9',
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

    public function show(Request $request, int $id)
    {
        $ingenieur        = Ingenieur::findOrFail($id);
        $modules          = Module::orderBy("created_at", "desc")->get();
        $departements     = Departement::orderBy("created_at", "desc")->get();
        $regions          = Region::orderBy("created_at", "desc")->get();
        $operateurs       = Operateur::orderBy("created_at", "desc")->get();
        $types_formations = TypesFormation::orderBy("created_at", "desc")->get();
        $ingenieurs       = Ingenieur::orderBy("created_at", "desc")->get();


        $query = Formation::query();

        if ($statut = $request->query('statut')) {
            $query->where('statut', $statut);
        }

        $formations = $query
            ->latest()
            ->limit(200)
            ->get();

        $affichees = $formations?->count();
        $total     = $totalIndividuelles ?? ($formations instanceof \Illuminate\Pagination\LengthAwarePaginator
            ? $formations->total()
            : $formations?->count());

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
                'affichees',
                'total',
                'formations',
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

    /* public function formationsParAnnee(Ingenieur $ingenieur, $annee)
    {
        $formations = $ingenieur->formations()
            ->where('annee', $annee)
            ->with([
                'regions',
                'individuelles',
                'collective.listecollectives'
            ])
            ->get();

        $groupes = $formations
            ->flatMap(function ($formation) {
                $totalFormes = $formation->individuelles->count()
                    + optional(optional($formation->collective)->listecollectives)->count();

                if ($formation->regions->isEmpty()) {
                    return collect([[
                        'region' => 'Aucune région',
                        'formation' => $formation,
                        'total' => $totalFormes
                    ]]);
                }

                return $formation->regions->map(function ($region) use ($formation, $totalFormes) {
                    return [
                        'region' => $region->nom,
                        'formation' => $formation,
                        'total' => $totalFormes
                    ];
                });
            })
            ->groupBy('region');

        return view('ingenieurs.formations_par_annee', compact(
            'ingenieur',
            'annee',
            'groupes',
            'formations'
        ));
    } */

    public function formationsParAnnee(Ingenieur $ingenieur, $annee)
    {
        // 1️⃣ Charger les formations avec les bénéficiaires et leur région
        $formations = Formation::query()
            ->where('ingenieurs_id', $ingenieur->id)
            ->where('annee', $annee)
            ->with([
                'individuelles.region',          // région de chaque bénéficiaire individuel
                'collective.region' // région de chaque bénéficiaire collectif
            ])
            ->get();

        // 2️⃣ Grouper les formations par région à partir des bénéficiaires
        $groupes = $formations->flatMap(function ($formation) {

            $resultats = collect();

            // Individuelles
            foreach ($formation->individuelles as $individuelle) {
                $regionNom = $individuelle->region->nom ?? 'Aucune région';
                $totalIndividuelle = count($formation->individuelles);
                $resultats->push([
                    'region' => $regionNom,
                    'formation' => $formation,
                    'totalIndividuelle' => $totalIndividuelle,
                    'totalCollective' => 0, // par défaut
                    'total' => 1,
                ]);
            }

            // Collectives
            if ($formation->collective) {
                foreach ($formation->collective->listecollectives as $collective) {
                    $regionNom = $formation->collective->region->nom ?? 'Aucune région';
                    $totalCollective = count($formation->collective->listecollectives);
                    $resultats->push([
                        'region' => $regionNom,
                        'formation' => $formation,
                        'totalIndividuelle' => 0, // par défaut
                        'totalCollective' => $totalCollective,
                        'total' => 1,
                    ]);
                }
            }

            return $resultats;
        })->groupBy('region');

        $totalIndividuelle = $formations->sum(fn($formation) => $formation->individuelles->count());
        $totalCollective  = $formations->sum(fn($formation) => $formation->collective ? $formation->collective->listecollectives->count() : 0);
        $totalGeneral     = $totalIndividuelle + $totalCollective;

        // 🔹 Transformer chaque groupe en collection pour pouvoir utiliser sum()
        $groupes = collect($groupes)->map(fn($items) => collect($items));

        // 3️⃣ Retourner la vue
        return view('ingenieurs.formations_par_annee', compact(
            'ingenieur',
            'annee',
            'groupes',
            'totalGeneral',
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
        // 1️⃣ Charger les formations avec les relations nécessaires
        $formations = Formation::query()
            ->where('ingenieurs_id', $ingenieur->id)
            ->where('annee', $annee)
            ->with([
                'individuelles.region',          // région des bénéficiaires individuels
                'collective.region',             // région de la collective
                'collective.listecollectives',   // listes collectives
            ])
            ->get();

        // 2️⃣ Récupérer les formations individuelles et filtrer par région si nécessaire
        $individuelles = $formations
            ->flatMap(fn($f) => $f->individuelles ?? collect())
            ->filter(function ($individuelle) use ($region) {
                if (!$region) return true;
                if ($region === 'Aucune région') return is_null($individuelle->region);
                return optional($individuelle->region)->nom === $region;
            })
            ->values();

        // 3️⃣ Récupérer les listes collectives et filtrer par région
        $collectives = $formations
            ->flatMap(function ($f) {
                return $f->collective ? $f->collective->listecollectives ?? collect() : collect();
            })
            ->filter(function ($collective) use ($region) {
                // la région est attachée à la collective parente
                $regionNom = $collective->collective->region->nom ?? null;

                if (!$region) return true;
                if ($region === 'Aucune région') return is_null($regionNom);
                return $regionNom === $region;
            })
            ->values();

        // 4️⃣ Calcul des totaux
        $nbIndividuelles = $individuelles->count();
        $nbCollectives   = $collectives->count();
        $totalFormes     = $nbIndividuelles + $nbCollectives;

        $pourcentageIndividuelles = $totalFormes
            ? round(($nbIndividuelles / $totalFormes) * 100)
            : 0;

        $pourcentageCollectives = $totalFormes
            ? round(($nbCollectives / $totalFormes) * 100)
            : 0;

        // 5️⃣ Retourner la vue
        return view('ingenieurs.liste_formations_par_annee', compact(
            'ingenieur',
            'annee',
            'region',
            'individuelles',
            'collectives',
            'pourcentageIndividuelles',
            'pourcentageCollectives'
        ));
    }
}
